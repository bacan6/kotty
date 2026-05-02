<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Ekspedisi extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],2,4);
	}

	function index(){
		$data['ekspedisi'] = $this->db->get("ap_ekspedisi");
		$this->global['pageTitle'] = "SOLUSI POS - Ekspedisi";
		//$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("ekspedisi/body_ekspedisi",$this->global,$data,"footer_empty");
	}

	function ekspedisi_sql(){
		$ekspedisi = $_POST['ekspedisi'];

		$data_insert = array(
								"ekspedisi"	=> $ekspedisi
							);

		$this->db->insert("ap_ekspedisi",$data_insert);
		$affect = $this->db->affected_rows();

		if($affect > 0){
			$this->session->set_flashdata("message","Data Berhasil Ditambahkan");
		}

		redirect("ekspedisi/");
	}

	function hapus_ekspedisi(){
		$id = $_GET['id'];

		$this->db->delete("ap_ekspedisi",array("id_ekspedisi" => $id));
		$affect = $this->db->affected_rows();

		if($affect > 0){
			$this->session->set_flashdata("message","Data Berhasil Dihapus !");		
		}

		redirect("ekspedisi/");
	}

}