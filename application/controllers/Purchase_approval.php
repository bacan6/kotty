<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_approval extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("modelPurchaseApproval","model1"));
		$this->load->database();

		//cek login
		$username = $this->session->userdata("username");
		$password = $this->session->userdata("password");
		$id_user  = $this->session->userdata("id_user");

		$cek_auth = $this->model1->cek_auth($username,$password);

		if($cek_auth > 0){
			//cek hak navigasi
			$access = 23;
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
		$this->load->view("purchaseApproval/body_daftar_request");
		$this->load->view("purchaseApproval/footer");
	}

	function wait_approve_list(){
		$data['wait_approve'] = $this->modelPurchaseApproval->wait_approve_list();
		$this->load->view("purchaseApproval/body_wait_approve_list",$data);
	}

		function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function item_list_request(){
		$no_request 	= $_POST['id_request'];

		$data['no_request']   = $no_request;
		$data['data_request'] = $this->modelPurchaseApproval->data_request($no_request);
		$data['item_request'] = $this->modelPurchaseApproval->item_request($no_request); 
		$this->load->view("purchaseApproval/item_list_request",$data);
	}

	function choose_item(){
		$id 			= $_POST['id'];
		$no_request 	= $_POST['no_request'];
		$harga 			= $_POST['harga'];
		$sku 		 	= $_POST['sku'];

		$data_update = array(
								"isChoose"	=> 1
							);

		$this->db->where("id",$id);
		$this->db->update("rq_purchase_item",$data_update);
	
		$data_status 	= array(
									"status"	=> 1
							   );

		$this->db->where("purchase_no",$no_request);
		$this->db->update("rq_purchase_no",$data_status);

		$data_harga = array(
								"harga"		=> $harga
						   );

		$this->db->where("bahan_baku.sku",$sku);
		$this->db->update("bahan_baku",$data_harga);

		$approved_id = array(
								"purchase_no"		=> $no_request,
								"approved_date"		=> date('Y-m-d H:i:s'),
								"approved_by"		=> $this->session->userdata("id_user")
						   );

		$this->db->insert("rq_purchase_approved",$approved_id);

	}

	function daftar_request_approved(){
		$data['approved_request'] = $this->modelPurchaseApproval->daftar_request_approved();
		$this->load->view("purchaseApproval/daftar_request_approved",$data);
	}

	function approved_item(){
		$no_request 	= $_POST['id_request'];

		$data['no_request']   = $no_request;
		$data['data_request'] = $this->modelPurchaseApproval->data_request($no_request);
		$data['item_request'] = $this->modelPurchaseApproval->item_request($no_request); 
		$this->load->view("purchaseApproval/approved_item",$data);
	}

	function daftar_request_ditolak(){
		$data['approved_request'] = $this->modelPurchaseApproval->daftar_request_ditolak();
		$this->load->view("purchaseApproval/daftar_request_ditolak",$data);
	}

	function form_ignore(){
		$data['id']  = $_POST['id'];
		$this->load->view("purchaseApproval/form_ignore",$data);
	}

	function submit_ignore(){
		$reason 		= $_POST['reason'];
		$no_request 	= $_POST['no_request'];


		$data_update = array(
								"message"	=> $reason,
								"status"	=> 2
							);

		$this->db->where("purchase_no",$no_request);
		$this->db->update("rq_purchase_no",$data_update);

	}

}