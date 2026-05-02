<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Welcome_page extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		
	}

	function index(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$this->global['pageTitle'] = "SOLUSI POS";
			$data['navigation'] = $this->model1->callNavigation();
			$this->loadViews("bodyWelcome",$this->global,NULL,"footer_empty");
		}
	}

}