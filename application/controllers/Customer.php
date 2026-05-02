<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Customer extends BaseController
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1", "modelCustomer", "model_penjualan"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'], 2, 40);
	}

	function cekMember()
	{
		$kontak = $_POST['kontak'];

		$kabupaten = $this->db->get_where("ap_customer", array("kontak" => $kontak, "activated" => 1));

		foreach ($kabupaten->result() as $dt) {
			echo "Data Customer sudah ada, klik <a href='" . base_url('customer/edit_customer?id=' . $dt->id_customer) . "'>" . $dt->nama . "</a>";
		}
	}

	function index_old()
	{
		$total_rows = $this->model1->total_customer();
		$this->load->library('pagination');
		$config['base_url'] = base_url('customer');
		$config['total_rows'] = $total_rows;
		$config["per_page"] = $per_page = 50;
		$config["uri_segment"] = 3;
		$config["full_tag_open"] = '<ul class="pagination">';
		$config["full_tag_close"] = '</ul>';
		$config["first_link"] = "&laquo;";
		$config["first_tag_open"] = "<li>";
		$config["first_tag_close"] = "</li>";
		$config["last_link"] = "&raquo;";
		$config["last_tag_open"] = "<li>";
		$config["last_tag_close"] = "</li>";
		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '<li>';
		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '<li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

		if (empty($_GET['query'])) {
			$data['customer'] = $this->model1->get_customer($per_page, $page);
		} else {
			$query = $_GET['query'];
			$data['customer'] = $this->model1->get_customer_sort($query);
		}

		$this->global['pageTitle'] = "SOLUSI POS - Data Customer";
		$this->loadViews("customer/body_customer", $this->global, $data, "footer_empty");
	}

	function index()
	{
		$this->global['pageTitle'] = "SOLUSI POS - Customer";
		$data['group'] = $this->db->get("ap_customer_group")->result();
		$data['kategori'] = isset($_REQUEST['kategori']) ? $_REQUEST['kategori'] : '';
		$data['approver'] = $this->db->get("user_approver");
		$ip = $_SERVER['REMOTE_ADDR'];
		$this->model_penjualan->deleteApprover($ip);
		$this->loadViews("customer/body_new", $this->global, $data, "customer/footerNew");
	}
	function datatables()
	{
		$draw = $_REQUEST['draw'];
		$length = $_REQUEST['length'];
		$start = $_REQUEST['start'];
		$search = $_REQUEST['search']["value"];

		$this->db->select("*");
		$this->db->from("ap_customer");
		$this->db->join("ap_customer_group", "ap_customer_group.id_group = ap_customer.kategori", "left");
		$this->db->join("ae_provinsi", "ae_provinsi.id_provinsi = ap_customer.id_provinsi", "left");
		$this->db->join("ae_kabupaten", "ae_kabupaten.kabupaten_id = ap_customer.id_kabupaten", "left");
		$this->db->join("ae_kecamatan", "ae_kecamatan.id_kecamatan = ap_customer.id_kecamatan", "left");
		if (!empty($search)) {
			$this->db->where("(ap_customer.nama like '%$search%' or ap_customer.id_customer like '%$search%' or ap_customer.kontak like '%$search%' or ap_customer.tanggal_lahir like '%$search%') ");
		}
		if (!empty($_REQUEST['kategori']) && isset($_REQUEST['kategori'])) {
			$this->db->where("ap_customer.kategori", $_REQUEST['kategori']);
		}
		$this->db->order_by("ap_customer.tanggal_gabung", "DESC");
		$this->db->limit($length, $start);
		$query = $this->db->get();

		$kategori = isset($_REQUEST['kategori']) ? $_REQUEST['kategori'] : '';


		$total = $this->model1->get_customer_sort($search, $kategori);
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();

		$nomor_urut = $start + 1;
		foreach ($query->result_array() as $dt) {
			if ($dt['activated'] == 1) {
				$status = "<label class='label label-success'>Aktif</label>";
			} else {
				$status = "<label class='label label-warning'>Non Aktif</label>";
			}

			$btn = '<div class="izin" style="display:none;"><a href="' . base_url('customer/hapus_customer?id=' . $dt['id_customer']) . '" onclick="return confirm(\'Apakah anda yakin menghapus data ini ?\')" class="btn btn-icon btn-danger m-b-5"><i class="fa fa-trash"></i></a> <a href="' . base_url('customer/edit_customer?id=' . $dt['id_customer']) . '" class="btn btn-icon btn-info m-b-5"><i class="fa fa-pencil"></i></a></div>';

			$output['data'][] = array($nomor_urut, $_GET['kategori'] . $dt['nama'], '<b>' . $dt['kontak'] . '</b>', date_format(date_create($dt['tanggal_lahir']), 'd M Y'), date_format(date_create($dt['tanggal_gabung']), 'd M Y'), $dt['alamat'] . " - " . $dt['nama_provinsi'] . " - " . $dt['nama_kabupaten'] . " - " . $dt['kecamatan'], $dt['group_customer'], $dt['diskon'], $dt['point'], $status, $btn);


			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function export_csv()
	{
		$delimiter = $this->input->get('delimiter');
		if ($delimiter === 'semicolon') {
			$sep = ';';
		} else {
			$sep = ',';
		}
		$search = $this->input->get('search');
		$kategori = $this->input->get('kategori');

		$this->db->select("ap_customer.nama, ap_customer.kontak, ap_customer.tanggal_lahir, ap_customer.tanggal_gabung, ap_customer.alamat, ae_provinsi.nama_provinsi, ae_kabupaten.nama_kabupaten, ae_kecamatan.kecamatan, ap_customer.diskon, ap_customer.point, ap_customer.activated, ap_customer_group.group_customer");
		$this->db->from("ap_customer");
		$this->db->join("ap_customer_group", "ap_customer_group.id_group = ap_customer.kategori", "left");
		$this->db->join("ae_provinsi", "ae_provinsi.id_provinsi = ap_customer.id_provinsi", "left");
		$this->db->join("ae_kabupaten", "ae_kabupaten.kabupaten_id = ap_customer.id_kabupaten", "left");
		$this->db->join("ae_kecamatan", "ae_kecamatan.id_kecamatan = ap_customer.id_kecamatan", "left");
		if (!empty($search)) {
			$this->db->where("(ap_customer.nama like '%" . $this->db->escape_like_str($search) . "%' or ap_customer.id_customer like '%" . $this->db->escape_like_str($search) . "%' or ap_customer.kontak like '%" . $this->db->escape_like_str($search) . "%' or ap_customer.tanggal_lahir like '%" . $this->db->escape_like_str($search) . "%')");
		}
		if (!empty($kategori)) {
			$this->db->where("ap_customer.kategori", (int) $kategori);
		}
		$this->db->order_by("ap_customer.tanggal_gabung", "DESC");
		$query = $this->db->get();

		$headers = array('No', 'Nama', 'Kontak', 'Tgl Lahir', 'Tanggal Input', 'Alamat', 'Kategori', 'Diskon (%)', 'Point', 'Aktif?');
		$filename = 'customer_' . date('Y-m-d_His') . '.csv';

		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');

		$out = fopen('php://output', 'w');
		if ($sep === ';') {
			fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
		}
		fputcsv($out, $headers, $sep);

		$no = 1;
		foreach ($query->result_array() as $dt) {
			$alamat = trim($dt['alamat'] . ' - ' . (isset($dt['nama_provinsi']) ? $dt['nama_provinsi'] : '') . ' - ' . (isset($dt['nama_kabupaten']) ? $dt['nama_kabupaten'] : '') . ' - ' . (isset($dt['kecamatan']) ? $dt['kecamatan'] : ''), ' - ');
			$tgl_lahir = !empty($dt['tanggal_lahir']) ? date('d M Y', strtotime($dt['tanggal_lahir'])) : '';
			$tgl_gabung = !empty($dt['tanggal_gabung']) ? date('d M Y', strtotime($dt['tanggal_gabung'])) : '';
			$aktif = !empty($dt['activated']) ? 'Aktif' : 'Non Aktif';
			$row = array($no, $dt['nama'], $dt['kontak'], $tgl_lahir, $tgl_gabung, $alamat, isset($dt['group_customer']) ? $dt['group_customer'] : '', $dt['diskon'], $dt['point'], $aktif);
			fputcsv($out, $row, $sep);
			$no++;
		}
		fclose($out);
		exit;
	}

	function data_customer()
	{
		$tanggal_lahir = isset($_GET['birthday']) ? $_GET['birthday'] : '';

		$this->db->select("*");
		$this->db->from("ap_customer");
		$this->db->join("ap_customer_group", "ap_customer_group.id_group = ap_customer.kategori", "left");
		if (!empty($tanggal_lahir))
			$this->db->where('ap_customer.tanggal_lahir', $tanggal_lahir);
		$this->db->order_by("ap_customer.id_customer", "DESC");
		$data['customer'] = $this->db->get();
		$this->load->view("customer/data_customer", $data);
	}

	function add_customer()
	{
		$data['group_customer'] = $this->db->get("ap_customer_group");
		$data['provinsi'] = $this->db->get("ae_provinsi");
		$this->global['pageTitle'] = "SOLUSI POS - Tambah Customer";
		$this->loadViews("customer/body_add_customer", $this->global, $data, "footer");
	}

	function hapus_customer()
	{
		$id = $_GET['id'];

		$this->modelCustomer->hapus_customer($id);

		$affect = $this->db->affected_rows();

		if ($affect > 0) {
			$this->session->set_flashdata('message', 'Data Berhasil Dihapus !');
		} else {
			$this->session->set_flashdata('message', 'Data Gagal Dihapus !');
		}

		redirect("customer");
	}

	function edit_customer()
	{
		$data['group_customer'] = $this->db->get("ap_customer_group");
		$data['provinsi'] = $this->db->get("ae_provinsi");

		$id_customer = $this->input->get('id');
		$data['customer'] = $this->db->get_where("ap_customer", array("id_customer" => $id_customer));
		$this->global['pageTitle'] = "SOLUSI POS - Edit Customer";
		$this->loadViews("customer/body_edit_customer", $this->global, $data, "footer");
	}

	function edit_customer_sql()
	{
		$nama = $_POST['nama'];
		$kontak = $_POST['kontak'];
		$alamat = $_POST['alamat'];
		$provinsi = $_POST['provinsi'];
		$kabupaten = $_POST['kabupaten'];
		$kecamatan = $_POST['kecamatan'];
		$group = $_POST['group'];
		$diskon = $_POST['diskon'];
		$idCustomer = $_POST['id_customer'];
		$no_id = $_POST['no_id'];
		$no_kartu = $_POST['no_kartu'];
		$password = $_POST['Password'];
		$tanggal_lahir = $_POST['tanggal_lahir'];
		$activated = $_POST['activated'];

		$statusCustomer = $this->modelCustomer->cekStatusAktivasi($idCustomer);
		$cekGroup = $this->modelCustomer->cekGroup($idCustomer);
		if ($statusCustomer == '0' && $activated == '1') {
			$sku = 'REGCUSTOMER';

			if ($this->global['idStore'] == '9' && $group == '4') {
				$biaya = 0;
			} else {
				$biaya = $this->modelCustomer->biaya_aktivasi($group);
			}


			$tanggal = date('Y-m-d');
			$id_user = $this->global['idUser'];
			$count_invoice = $this->model1->count_invoice($tanggal) + 1;

			$no_inv = "INV" . date('y') . date('m') . date('d') . $id_user . sprintf('%04d', $count_invoice);

			$data_penjualan = array(
				"no_invoice" => $no_inv,
				"tipe_bayar" => '1',
				"sub_account" => '0',
				"jatuh_tempo" => '',
				"total" => $biaya,
				"surcharge" => '0',
				"ongkir" => '0',
				"diskon" => 0,
				"diskon_free" => 0,
				"hpp" => $biaya,
				"poin_value" => 0,
				"poin" => 0,
				"poin_reimburs" => 0,
				"diskon_otomatis" => 0,
				"jumlah_bayar" => $biaya,
				"id_pic" => $id_user,
				"id_customer" => $idCustomer,
				"keterangan" => 'Aktivasi Member',
				"tanggal" => date('Y-m-d H:i:s'),
				"alamat" => '',
				"id_provinsi" => '',
				"id_kabupaten" => '',
				"id_kecamatan" => '',
				"kontak_pengiriman" => '',
				"nama_penerima" => '',
				"id_ekspedisi" => '',
				"id_toko" => $this->global['idStore']
			);
			$this->model_penjualan->insertApInvoiceNumber($data_penjualan);
			$data_item = array(
				"no_invoice" => $no_inv,
				"id_produk" => $sku,
				"hpp" => $biaya,
				"harga_jual" => $biaya,
				"diskon" => 0,
				"disc_supplier" => 0,
				"tebusmurah" => 0,
				"qty" => 1,
				"tanggal" => date('Y-m-d')
			);

			$this->db->insert("ap_invoice_item", $data_item);
		} else {
			$group = ($cekGroup > 0 && $cekGroup != $group) ? $cekGroup : $group;
			// pastikan kategori tdk diubah kalau edit
		}

		if (!empty($password)) {
			$data_customer = array(
				"nama" => $nama,
				"kontak" => $kontak,
				"tanggal_gabung" => date('Y-m-d'),
				"alamat" => $alamat,
				"id_provinsi" => $provinsi,
				"id_kabupaten" => $kabupaten,
				"id_kecamatan" => $kecamatan,
				"kategori" => $group,
				"diskon" => $diskon,
				"no_id" => $no_id,
				"no_kartu" => $no_kartu,
				"tanggal_lahir" => $tanggal_lahir,
				"activated" => $activated,
				"password" => md5($password)
			);
		} else {
			$data_customer = array(
				"nama" => $nama,
				"kontak" => $kontak,
				"tanggal_gabung" => date('Y-m-d'),
				"alamat" => $alamat,
				"id_provinsi" => $provinsi,
				"id_kabupaten" => $kabupaten,
				"id_kecamatan" => $kecamatan,
				"kategori" => $group,
				"diskon" => $diskon,
				"no_id" => $no_id,
				"tanggal_lahir" => $tanggal_lahir,
				"activated" => $activated,
				"no_kartu" => $no_kartu

			);
		}





		$affect = $this->modelCustomer->editCustomer($idCustomer, $data_customer);

		if ($affect > 0) {
			$this->session->set_flashdata('message', 'Data Berhasil Diubah !');
		} else {
			$this->session->set_flashdata('message2', 'Data Gagal Diubah !');

		}

		redirect("customer");
	}

	function add_customer_sql()
	{
		//ini_set('display_errors', 1);error_reporting(E_ALL);
		$nama = $_POST['nama'];
		$kontak = $_POST['kontak'];
		$alamat = $_POST['alamat'];
		$provinsi = $_POST['provinsi'];
		$kabupaten = $_POST['kabupaten'];
		$kecamatan = $_POST['kecamatan'];
		$group = $_POST['group'];
		$diskon = $_POST['diskon'];
		$tanggal_lahir = $_POST['tanggal_lahir'];
		$no_id = $_POST['no_id'];
		$no_kartu = $_POST['no_kartu'];
		$id_customer = $_POST['id_customer'];
		$activated = $_POST['activated'];
		$password = !empty($_POST['password']) ? md5($_POST['password']) : '';

		if ($activated == '1') {
			$sku = 'REGCUSTOMER';

			if ($this->global['idStore'] == 9 && $group == '4') {
				$biaya = 0;
			} else {
				$biaya = $this->modelCustomer->biaya_aktivasi($group);
			}

			$tanggal = date('Y-m-d');
			$id_user = $this->global['idUser'];
			$count_invoice = $this->model1->count_invoice($tanggal) + 1;

			$no_inv = "INV" . date('y') . date('m') . date('d') . $id_user . sprintf('%04d', $count_invoice);

			$data_penjualan = array(
				"no_invoice" => $no_inv,
				"tipe_bayar" => '1',
				"sub_account" => '0',
				"jatuh_tempo" => '',
				"total" => $biaya,
				"surcharge" => '0',
				"ongkir" => '0',
				"diskon" => 0,
				"diskon_free" => 0,
				"hpp" => $biaya,
				"poin_value" => 0,
				"poin" => 0,
				"poin_reimburs" => 0,
				"diskon_otomatis" => 0,
				"jumlah_bayar" => $biaya,
				"id_pic" => $id_user,
				"id_customer" => $id_customer,
				"keterangan" => 'Aktivasi Member',
				"tanggal" => date('Y-m-d H:i:s'),
				"alamat" => '',
				"id_provinsi" => '',
				"id_kabupaten" => '',
				"id_kecamatan" => '',
				"kontak_pengiriman" => '',
				"nama_penerima" => '',
				"id_ekspedisi" => '',
				"id_toko" => $this->global['idStore']
			);
			$this->model_penjualan->insertApInvoiceNumber($data_penjualan);
			$data_item = array(
				"no_invoice" => $no_inv,
				"id_produk" => $sku,
				"hpp" => $biaya,
				"harga_jual" => $biaya,
				"diskon" => 0,
				"disc_supplier" => 0,
				"tebusmurah" => 0,
				"qty" => 1,
				"tanggal" => date('Y-m-d')
			);
			$this->db->insert("ap_invoice_item", $data_item);
		}

		$data_customer = array(
			"nama" => $nama,
			"kontak" => $kontak,
			"tanggal_lahir" => $tanggal_lahir,
			"tanggal_gabung" => date('Y-m-d'),
			"alamat" => $alamat,
			"id_provinsi" => $provinsi,
			"id_kabupaten" => $kabupaten,
			"id_kecamatan" => $kecamatan,
			"kategori" => $group,
			"no_id" => $no_id,
			"no_kartu" => $no_kartu,
			"id_customer" => $id_customer,
			"activated" => $activated,
			"diskon" => $diskon,
			"password" => $password
		);


		$affect = $this->modelCustomer->addCustomer($data_customer);

		if ($affect > 0) {
			$this->session->set_flashdata('message', 'Data Berhasil Ditambahkan !');
		} else {
			$this->session->set_flashdata('message', 'Data Gagal Ditambahkan !');
		}

		redirect("customer");
	}

	function list_kabupaten()
	{
		$id = $_POST['id'];

		$kabupaten = $this->db->get_where("ae_kabupaten", array("id_provinsi" => $id));

		foreach ($kabupaten->result() as $dt) {
			echo "<option value='" . $dt->kabupaten_id . "'>" . $dt->nama_kabupaten . "</option>";
		}
	}

	function list_kecamatan()
	{
		$id = $_POST['id'];

		$kecamatan = $this->db->get_where("ae_kecamatan", array("kabupaten_id" => $id));

		foreach ($kecamatan->result() as $dt) {
			echo "<option value='" . $dt->id_kecamatan . "'>" . $dt->kecamatan . "</option>";
		}
	}
}