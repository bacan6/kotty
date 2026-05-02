<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Kasir extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],2,19);
	}

	function index(){
		$this->global['pageTitle'] = "Solusi POS - Buka Tutup Kasir";
		if($this->global['isSuperadmin']==1){
			$data['store'] = $this->db->get("ap_store")->result();
		}else $data['store'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
		$this->loadViews("transaksi_kasir/body_kasir",$this->global,$data,"transaksi_kasir/footer");
	}

	function submit_closing_sql(){
		$idUser 	= $_POST['idUser'];
		$tanggal 	= $_POST['tanggal'];
		$param 		= $_POST['param']; // data json


		$dateFormat = date_format(date_create($tanggal),'dmy');

		//no closing
		$noClosing = 	"CLS-".$dateFormat."-".sprintf('%03d',$idUser);

		$closingId = array(
								"id_closing"	=> $noClosing,
								"id_kasir" 		=> $idUser,
								"tanggal"		=> $tanggal,
								"jam"			=> date('H:i:s')
						  );
		$this->db->insert("closing_id",$closingId);

		$convert = json_decode(stripslashes($param));

		foreach($convert as $dt){
			$value 			= $dt->value;
			$paymentType 	= $dt->paymentType; 	
			$accountType 	= $dt->accountType;

			$dataClosing[] = array(
										"id_closing"	=> $noClosing,
										"id_kasir"		=> $idUser,
										"payment_type"	=> $paymentType,
										"account"		=> $accountType,
										"tanggal"		=> $tanggal,
										"value"			=> $value
								  );
		}

		$this->db->insert_batch("closing_account",$dataClosing);
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function list_kasir_trx(){
		$this->load->model("model_penjualan");
		$tanggal 		    = $_POST['tanggal'];
		$id_toko 		    = $_POST['id_toko'];
		$data['tanggal'] 	= $tanggal;
		$data['store'] 	= $id_toko;
		$data['list_kasir'] = $this->model_penjualan->listKasir($id_toko);
		$this->load->view("transaksi_kasir/list_kasir_trx",$data);
	}

	function form_modal(){
		$id = $_POST['id'];
		$data['id'] = $id;
		$data['nama_kasir'] = $this->model1->nama_kasir($id);
		$data['tanggal'] 	= $_POST['tanggal'];
		$this->load->view("transaksi_kasir/form_modal",$data);
	}

	function input_modal_sql(){
		$id 				= $_POST['id_kasir'];
		$modal_kasir		= $_POST['modal_kasir'];
		$tanggal 			= $_POST['tanggal']." ".date('H:i:s');

		$data_modal = array(
								"id_user"		=> $id,
								"modal"			=> $modal_kasir,
								"tanggal"		=> $tanggal
						   );

		$this->db->insert("closing_modal",$data_modal);
	}

	function form_closing_kasir(){
		$this->load->model("model_penjualan");
		$id 	= $_POST['id'];

		$data['tanggal'] = $_POST['tanggal'];
		$data['id'] = $id;
		$data['nama_kasir'] = $this->model1->nama_kasir($id);
		
		$data['list_debit'] 	= $this->model_penjualan->list_debit();
		$data['list_kredit'] 	= $this->model_penjualan->list_kredit();

		$this->load->view("transaksi_kasir/form_closing_kasir",$data);
	}



	function closingInsertSuccess(){
		$this->load->model("model_penjualan");
		$id 	 	= $_POST['idUser'];
		$tanggal 	= $_POST['tanggal'];

		$data['tanggal'] = $tanggal;
		$data['id'] = $id;
		$data['nama_kasir'] = $this->model1->nama_kasir($id);
		
		$data['list_debit'] 	= $this->model_penjualan->list_debit();
		$data['list_kredit'] 	= $this->model_penjualan->list_kredit();

		$data['jamClosing'] 	= $this->model_penjualan->jamClosing($id,$tanggal);
		$data['noClosing']	 	= $this->model_penjualan->noClosing($id,$tanggal);

		$this->load->view("transaksi_kasir/dataClosingSuccess",$data);
	}

	function loader(){
		$this->load->view("public/loader");
	}

	function printButton(){
		$data['tanggal'] = $_POST['tanggal'];
		$this->load->view("transaksi_kasir/printButton",$data);
	}

	function adjusment(){


		$idUser 	= $this->input->get("id");
		$tanggal  	= $this->input->get("tanggal");

		$this->load->model("model_penjualan");
		$data['nama_kasir'] = $this->model1->nama_kasir($idUser);
		$data['tanggal'] = $tanggal;
		$data['idUser'] = $idUser;


		$this->global['pageTitle'] = "Solusinformatika.com - Adjusment";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data['supplier'] = $this->db->get("supplier");
		$this->loadViews("transaksi_kasir/adjusmentClosing",$this->global,$data,"transaksi_kasir/footerAdjusmentClosing");
	}

	function dataAdjusmentKasir(){
		$idUser 	= $_POST['idUser'];
		$tanggal  	= $_POST['tanggal'];

		$this->load->model("model_penjualan");
		$data['nama_kasir'] = $this->model1->nama_kasir($idUser);
		$data['tanggal'] = $tanggal;
		$data['idUser'] = $idUser;

		$data['setAdjusment'] = $this->model_penjualan->setAdjusment($idUser,$tanggal);
		$this->load->view("transaksi_kasir/dataAdjusment",$data);
	}

	function dataAdjusmentKasirFilter(){
		$idUser 	= $_POST['idUser'];
		$tanggal  	= $_POST['tanggal'];
		$search 	= $_POST['search'];

		$this->load->model("model_penjualan");
		$data['nama_kasir'] = $this->model1->nama_kasir($idUser);
		$data['tanggal'] = $tanggal;
		$data['idUser'] = $idUser;

		$data['setAdjusment'] = $this->model_penjualan->setAdjusmentFilter($idUser,$tanggal,$search);
		$this->load->view("transaksi_kasir/dataAdjusment",$data);
	}

	function modalAdjustment(){
		$noInvoice 		= $_POST['noInvoice'];

		$this->load->model("model_penjualan");
		$data['paymentType'] 	= $this->model_penjualan->readPaymentType($noInvoice);
		$data['getPaymentType'] = $this->model_penjualan->paymentTypeSelection();
		$data['noInvoice'] 		= $noInvoice;
		$data['tanggal'] 	 	= $_POST['tanggal'];
		$data['idUser']			= $_POST['idUser'];
  		$this->load->view("transaksi_kasir/bodyAdjustmentForm",$data);
	}

	function sub_account(){
		$id 	= $_POST['id'];

		$query = $this->db->get_where("ap_payment_account",array("id_payment_type" => $id));

		$data['sub_account'] = $query;

		$rows = $query->num_rows();

		if($rows > 0 ){
			$this->load->view("sub_account",$data);
		} 
	}

	function updatePaymentTypeSQL(){
		$noInvoice 		= $_POST['noInvoice'];
		$paymentType 	= $_POST['paymentType'];

		if(empty($_POST['account'])){
			$account = "";
		} else {
			$account = $_POST['account'];
		}

		$dataUpdate = array(
								"tipe_bayar"		=> $paymentType,
								"sub_account"		=> $account
						   );	

		$this->db->where("no_invoice",$noInvoice);
		$this->db->update("ap_invoice_number",$dataUpdate);
		$affect = $this->db->affected_rows();

		if($affect > 0){
			echo 1;
		} else {
			echo 0;
		}
	}

}