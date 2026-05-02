<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller{
	function index(){
		$this->load->library("ion_auth");
		$this->ion_auth->logout();
		redirect("login");
	}
}