<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class AkumulasiStok extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,17);
	}

	function index(){
		$this->load->model("modelAset");
		
		$total_rows = $this->modelAset->totalProduk();
		
		$this->load->library('pagination');
		$config['base_url'] 			= base_url('akumulasiStok/index');
		$config['total_rows']			= $total_rows;
		$config["per_page"]				= $per_page = 30;
		$config["uri_segment"]			= 3;
		$config["full_tag_open"] 		= '<ul class="pagination">';
		$config["full_tag_close"] 		= '</ul>';
		$config["first_link"] 			= "&laquo;";
		$config["first_tag_open"] 		= "<li>";
		$config["first_tag_close"] 		= "</li>";
		$config["last_link"] 			= "&raquo;";
		$config["last_tag_open"] 		= "<li>";
		$config["last_tag_close"] 		= "</li>";
		$config['next_link'] 			= '&gt;';
		$config['next_tag_open'] 		= '<li>';
		$config['next_tag_close'] 		= '<li>';
		$config['prev_link'] 			= '&lt;';
		$config['prev_tag_open'] 		= '<li>';
		$config['prev_tag_close'] 		= '<li>';
		$config['cur_tag_open'] 		= '<li class="active"><a href="#">';
		$config['cur_tag_close'] 		= '</a></li>';
		$config['num_tag_open'] 		= '<li>';
		$config['num_tag_close'] 		= '</li>';

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		if(empty($_GET['q'])){
			$data['dataStok'] = $this->modelAset->dataStokAkumulasi($per_page,$page);
		} else {
			$data['dataStok'] = $this->modelAset->dataStokAkumulasiCari($_GET['q']);
		}

		$this->global['pageTitle'] = "SOLUSI POS - Akumulasi Stok";
		$this->loadViews("aset/bodyAset",$this->global,$data,"aset/footer");
	}

	function viewAllStok(){
		$id 			= $_POST['idProduk'];
		$idProduk 		= str_replace("-"," ",$id);

		$this->load->model("modelAset");

		$data['id']			= $id;
		$data['stokGudang'] = $this->modelAset->viewStokGudang($idProduk);
		$data['stokStore'] 	= $this->modelAset->viewStokStore($idProduk);
		$this->load->view("aset/stokStore",$data);
	}

}