<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Pengumuman extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],1,14);
	}

	function index(){
		$this->global['pageTitle'] = "Solusinformatika.com - Pengumuman";
		$this->loadViews("pengumuman/body_pengumuman",$this->global,NULL,"pengumuman/footerPengumuman");
	}

	function data_pengumuman(){
		$data['pengumuman'] = $this->db->get("pengumuman");
		$this->load->view("pengumuman/data_pengumuman",$data);
	}

	function form_edit_pengumuman(){
		$id = $_POST['id'];
		$data['pengumuman'] = $this->db->get_where("pengumuman",array("ID" => $id));
		$this->load->view("pengumuman/form_edit_pengumuman",$data);
	}

	function edit_pengumuman_sql(){
		$konten 			= $_POST['konten'];

		$data_update = array(
								"Isi"				=> $konten,
								"TanggalEdit"		=> date("Y-m-d H:i:s"),
								"LoginEdit"			=> $this->global['idUser']
							);

		$this->db->where("ID",'5');
		$this->db->update("pengumuman",$data_update);
		$affect = $this->db->affected_rows();
		echo $affect;
	}

	
}