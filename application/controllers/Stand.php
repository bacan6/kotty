<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Stand extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],2,6);
	}

	function index(){
		$data['stand'] = $this->db->get("ap_stand");
		$this->global['pageTitle'] = "SOLUSI POS - Stand";
		$this->loadViews("parameter/body_stand",$this->global,$data,"footer_empty");
	}

	function add_stand_sql(){
		$stand = $_POST['nama_stand'];

		$data_stand = array(
								"stand"		=> $stand
						   );

		$this->db->insert("ap_stand",$data_stand);

		$affect = $this->db->affected_rows();

		if($affect > 0){
			$this->session->set_flashdata("message","Data Berhasil Ditambah");
		}

		redirect("stand");
	}

	function hapus_stand(){
		$id_stand 	= $_GET['id'];

		$this->db->delete("ap_stand",array("id_stand" => $id_stand));

		$affect = $this->db->affected_rows();

		if($affect > 0){
			$this->session->set_flashdata("message","Data Berhasil Dihapus");
		}

		redirect("stand");
	}

}