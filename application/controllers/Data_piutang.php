<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Data_piutang extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->model(array('model1','modelPiutang'));
		$this->load->library(array("session","encryption"));

		$this->isLoggedIn($this->global['idUser'],2,8);	
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Data Piutang";
		$statementBelumJatuhTempo = "ap_piutang.jatuh_tempo >= '".date('Y-m-d')."'";
		$data['belumJatuhTempo'] = $this->modelPiutang->dataPiutangNumRows($statementBelumJatuhTempo);
		$statementMelebihiTempo = "ap_piutang.jatuh_tempo < '".date('Y-m-d')."'";
		$data['melebihiTempo'] = $this->modelPiutang->dataPiutangNumRows($statementMelebihiTempo);
		$data['piutangLunas'] = $this->modelPiutang->dataPiutangLunasNumRows();
		$this->loadViews("data_piutang/body_data_piutang",$this->global,$data,"data_piutang/footerDataPiutang");
	}

	function spinner(){
		echo '<center><img src="'.base_url('assets/loading.gif').'"/></center>';
	}

	function lunasiPiutang(){
		$noInvoice = $_POST['noInvoice'];

		$dataUpdate = array(
								"status" => 1
						   );

		$this->modelPiutang->updateStatusPiutangKeLunas($noInvoice,$dataUpdate);
	}

	function dataBelumJatuhTempo(){
		$where = "ap_piutang.jatuh_tempo > '".date('Y-m-d')."'";
		$data['dataPiutang'] = $this->modelPiutang->dataPiutang($where);
		$this->load->view("data_piutang/piutangBelumJatuhTempo",$data);
	}

	function dataMelebihiTempo(){
		$where = "ap_piutang.jatuh_tempo < '".date('Y-m-d')."'";
		$data['dataPiutang'] = $this->modelPiutang->dataPiutang($where);
		$this->load->view("data_piutang/piutangBelumJatuhTempo",$data);
	}

	function dataPiutangLunas(){
		$data['dataPiutang'] = $this->modelPiutang->dataPiutangLunas();
		$this->load->view("data_piutang/piutangBelumJatuhTempo",$data);
	}

	function bayar_piutang(){
		$no_invoice = $this->input->get("no_invoice");
		$data['paymentType'] = $this->db->get("ap_payment_type")->result();
		$data['statusPiutang'] = $this->modelPiutang->statusPiutang($no_invoice);


		$this->global['pageTitle'] = "SOLUSI POS - Pembayaran Piutang";
		$this->loadViews("body_bayar_piutang",$this->global,$data,"footer_piutang_payment");
	}

	function riwayatPembayaran(){
		$noInvoice = $this->input->post("noInvoice");
		$data['riwayatPembayaran'] = $this->modelPiutang->riwayatPembayaran($noInvoice);
		$this->load->view("data_piutang/riwayatPembayaran",$data);
	}

	function dataPembayaran(){
		$this->load->model("model_penjualan");
		$noInvoice = $this->input->post("noInvoice");
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['totalTerbayar'] = $this->modelPiutang->totalTerbayar($noInvoice);
		$this->load->view("data_piutang/dataPembayaran",$data);
	}

	function printPembayaranPiutang(){
		$this->load->model("model_penjualan");
		$noInvoice = $this->input->get("noInvoice");
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['totalTerbayar'] = $this->modelPiutang->totalTerbayar($noInvoice);
		$data['header'] = $this->db->get_where("ap_store",array("id_store" => $idStore))->row();
		$data['riwayatPembayaran'] = $this->modelPiutang->riwayatPembayaran($noInvoice);

		$this->global['pageTitle'] = "SOLUSI POS - Print InvoicePembayaran Piutang";
		$this->loadViews("data_piutang/notaPembayaranPiutang",$this->global,$data,"footer_empty");
	}

	function sub_account(){
		$id 	= $_POST['id'];

		$query = $this->db->get_where("ap_payment_account",array("id_payment_type" => $id));

		$data['sub_account'] = $query;

		$rows = $query->num_rows();

		if($rows > 0 ){
			$this->load->view("penjualan/sub_account",$data);
		} 
	}

	function list_account(){
		$this->load->view("list_account_piutang");
	}

	function bayar_piutang_sql(){
		$nominal 		= $this->input->post("nominal");
		$payment_type 	= $this->input->post("typeBayar");
		$account 		= $this->input->post("subAccount");
		$keterangan 	= $this->input->post("keterangan");
		$no_invoice 	= $this->input->post("noInvoice");

		$cek_piutang_payment = $this->model1->cek_piutang_payment()+1;

		$no_seri = "TRX-".date('y').date('m').sprintf('%04d',$cek_piutang_payment);

		$data_piutang = array(
								"no_seri"			=> $no_seri,
								"no_invoice" 		=> $no_invoice,
								"id_pic"			=> $this->global['idUser'],
								"id_payment"		=> $payment_type,
								"account"			=> $account,
								"tanggal"			=> date('Y-m-d'),
								"nominal"			=> $nominal,
								"keterangan"		=> $keterangan
							 );

		$this->modelPiutang->insertPembayaranPiutang($data_piutang);
	}

	function close_trx(){
		$data_trx = array(
							"status"	=> 1,
						 );

		$this->modelPiutang->closeTrx($_GET['no_invoice'],$data_trx);
		redirect("data_piutang/bayar_piutang?no_invoice=".$_GET['no_invoice']);
	}

}