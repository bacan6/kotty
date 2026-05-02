<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Saran extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();
		$this->load->library("session");

		$this->isLoggedIn($this->global['idUser'],13,64);
	}

	function index(){
		$this->global['pageTitle'] = "Solusinformatika.com - saran";
		$this->loadViews("saran/body_saran",$this->global,NULL,"saran/footerSaran");
	}

	function data_saran(){
		$data['saran'] = $this->db->get("k_saran");
		$this->load->view("saran/data_saran",$data);
	}


	function add_saran_sql(){

		
	}

	function form_edit_saran(){
		
	}

	function edit_saran_sql(){


	}

	function hapus_saran(){
		$id = $_POST['id'];
		$saran = $this->db->get_where("k_saran",array("id_saran" => $id))->row();
		$this->db->delete("k_saran",array("id_saran" => $id));
	
	}
}