<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Poin extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		//cek login
		$username = $this->session->userdata("username");
		$password = $this->session->userdata("password");
		$id_user  = $this->session->userdata("id_user");

		$cek_auth = $this->model1->cek_auth($username,$password);

		if($cek_auth > 0){
			//cek hak navigasi
			$access = 11;
			$cek_status = $this->model1->cek_status_navigasi($id_user,$access);
			
			if($cek_status=='0'){
				redirect("access_denied");
			} else {
				//do nothing
			}

		} else {
			redirect("login");
		}
	}

}