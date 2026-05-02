<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->model('model1');
		$this->load->library("ion_auth");

		if($this->ion_auth->logged_in()){
	      redirect('welcome_page');
	    }
	}

	function index(){
		$data['store'] = $this->db->get("ap_store");
		$this->load->view("login",$data);
	}

	function auth(){
		$identity = $this->input->post("username");
		$password = $this->input->post("password");
		$remember = NULL;

		$loginProcess = $this->ion_auth->login($identity,$password,$remember);

		if($loginProcess){
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("welcome_page","refresh");
		} else {
			$this->session->set_flashdata('message', "<div class='alert alert-danger' role='alert'><span class='alert_icon lnr lnr-cross'></span><strong>".$this->ion_auth->errors()."</div>");
			redirect("login");
		}
	}

	function logout(){
		$this->session->sess_destroy();
		redirect('login');
	}
}