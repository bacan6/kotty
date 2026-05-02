<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Customer_group extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelCustomer"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,41);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Group Customer";
		$this->loadViews("customer/body_customer_group",$this->global,NULL,"customer/footerCustomerGroup");
	}

	function customer_group_data(){
		$data['group_customer'] = $this->db->get("ap_customer_group");
		$this->load->view("customer/customer_group_data",$data);
	}

	function hapusGroup(){
		$id = $_POST['id'];

		$this->modelCustomer->hapusGroup($id);
	}

	function add_customer_group(){
		$nama_group  = $_POST['nama_grup'];

		$data_grup = array(
								"group_customer" 	=> $nama_group	
						  );

		$this->modelCustomer->addCustomerGroup($data_grup);
	}

	function formEditCustomerGroup(){
		$getData = $this->db->get_where("ap_customer_group",array("id_group" => $_POST['id']))->row();

		echo "<input type='text' class='form-control' id='groupValue' value='".$getData->group_customer."'/>";
		echo "<input type='hidden' id='idGroup' value='".$_POST['id']."'/>";
	}

	function ubahGroupSQL(){
		$groupValue = $_POST['groupValue'];
		$id = $_POST['id'];

		$dataUpdate = array(
								"group_customer" => $groupValue
						   );

		$this->modelCustomer->updateGroup($id,$dataUpdate);
	}

}