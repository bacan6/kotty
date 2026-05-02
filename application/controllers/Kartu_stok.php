<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Kartu_stok extends CI_Controller{
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
			$access = 13;
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

	function index(){
		$this->load->view("navigation");
		$data['bahan_baku'] = $this->model1->get_bahan_baku_select2();

		if(!empty($_GET['date_start'])){
			$date_start = $_GET['date_start'];
			$date_end   = $_GET['date_end'];
			$sku 		= $_GET['sku'];
		} else {
			$date_start = NULL;
			$date_end   = NULL;
			$sku 		= NULL;
		}

		$data['kartu_stok'] = $this->model1->view_kartu_stok_harian($date_start,$date_end,$sku);
		$data['stok_awal']  = $this->model1->stok_awal($date_start,$date_end,$sku);
		$this->load->view("body_kartu_stok",$data); 
		$this->load->view("footer");
	}

	function finish_goods(){
		$this->load->view("navigation");
		$this->load->view("body_kartu_stok_finish_goods");
		$this->load->view("footer");
	}
}