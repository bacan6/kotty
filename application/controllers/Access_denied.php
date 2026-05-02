<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class access_denied extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model("model1");
		$this->load->library("session");
	}

	function index(){
		$this->load->view("navigation");
		$this->load->view("access_denied");
		$this->load->view("footer");
	}
}
	