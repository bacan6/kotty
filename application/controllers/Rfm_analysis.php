<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Rfm_analysis extends BaseController {

    function __construct() {
        parent::__construct();
        $this->load->helper("url");
        $this->load->database();
        $this->load->model(array("model1", "RFM_model"));
        $this->load->library("session");
        $this->isLoggedIn($this->global['idUser'], 1, 1);
    }

    function index() {
        $this->global['pageTitle'] = 'Solusi POS - RFM Analysis';
        $data['toko'] = $this->RFM_model->get_stores();
        $data['group_customer'] = $this->RFM_model->get_customer_groups();
        $data['isAdmin'] = isset($this->global['isAdmin']) ? $this->global['isAdmin'] : 0;
        $data['id_toko'] = isset($_GET['id_toko']) ? $_GET['id_toko'] : '';
        $data['id_group'] = isset($_GET['id_group']) ? $_GET['id_group'] : '';
        $data['segment_name'] = isset($_GET['segment_name']) ? $_GET['segment_name'] : '';
        $data['tanggal_mulai'] = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : date('Y-01-01');
        $data['tanggal_selesai'] = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : date('Y-m-d');
        $data['recency_min'] = isset($_GET['recency_min']) ? $_GET['recency_min'] : '';
        $data['recency_max'] = isset($_GET['recency_max']) ? $_GET['recency_max'] : '';
        $data['frequency_min'] = isset($_GET['frequency_min']) ? $_GET['frequency_min'] : '';
        $data['frequency_max'] = isset($_GET['frequency_max']) ? $_GET['frequency_max'] : '';
        $data['monetary_min'] = isset($_GET['monetary_min']) ? $_GET['monetary_min'] : '';
        $data['monetary_max'] = isset($_GET['monetary_max']) ? $_GET['monetary_max'] : '';
        $this->loadViews("staff/rfm_analysis", $this->global, $data, "staff/footer_rfm_analysis");
    }

    function get_rfm_data() {
        $id_toko = $this->input->post('id_toko') ?: '';
        $tanggal_mulai = $this->input->post('tanggal_mulai') ?: date('Y-01-01');
        $tanggal_selesai = $this->input->post('tanggal_selesai') ?: date('Y-m-d');
        $id_group = $this->input->post('id_group');
        $date_range = array('start' => $tanggal_mulai, 'end' => $tanggal_selesai);
        $list = $this->RFM_model->get_rfm_data($id_toko, $date_range, $id_group);

        $segment_name = $this->input->post('segment_name');
        $recency_min = $this->input->post('recency_min');
        $recency_max = $this->input->post('recency_max');
        $frequency_min = $this->input->post('frequency_min');
        $frequency_max = $this->input->post('frequency_max');
        $monetary_min = $this->input->post('monetary_min');
        $monetary_max = $this->input->post('monetary_max');

        foreach (array('recency_min', 'recency_max', 'frequency_min', 'frequency_max') as $k) {
            if ($$k !== null && $$k !== '') $$k = (int) $$k;
        }
        foreach (array('monetary_min', 'monetary_max') as $k) {
            if ($$k !== null && $$k !== '') $$k = (float) str_replace(',', '.', $$k);
        }

        $filtered = array();
        foreach ($list as $row) {
            if ($segment_name !== null && $segment_name !== '' && $row->segment_name !== $segment_name) continue;
            if ($recency_min !== null && $recency_min !== '' && $row->recency_days < $recency_min) continue;
            if ($recency_max !== null && $recency_max !== '' && $row->recency_days > $recency_max) continue;
            if ($frequency_min !== null && $frequency_min !== '' && $row->frequency < $frequency_min) continue;
            if ($frequency_max !== null && $frequency_max !== '' && $row->frequency > $frequency_max) continue;
            if ($monetary_min !== null && $monetary_min !== '' && $row->monetary < $monetary_min) continue;
            if ($monetary_max !== null && $monetary_max !== '' && $row->monetary > $monetary_max) continue;
            $filtered[] = $row;
        }

        $segment_summary = array();
        foreach ($filtered as $r) {
            $s = $r->segment_name;
            if (!isset($segment_summary[$s])) $segment_summary[$s] = 0;
            $segment_summary[$s]++;
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'data' => $filtered,
            'segment_summary' => $segment_summary,
        ));
    }
}
