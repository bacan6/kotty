<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class DataStok extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,17);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Laporan Data Stok";
		$this->loadViews("data_stok/dataStokPilih",$this->global,NULL,"footer_empty");
	}
}