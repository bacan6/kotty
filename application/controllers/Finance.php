<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Finance extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelFinance"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,18);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Hutang Pembelian";
		$data['supplier'] = $this->db->get('supplier')->result();
		$data['store'] = $this->db->get('ap_store')->result();
		$this->loadViews("finance/body_finance",$this->global,$data,"finance/footerFinance");
	}

	function datatablesTagihan(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$supplier = $this->input->post('supplier');
		$terbayar   = $this->input->post('status');
		$store   = $this->input->post('store');

        $idUser     = $this->global['idUser'];

		$total 			 			= $this->modelFinance->total_tagihan($store,$supplier,$terbayar);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelFinance->dataTagihan($length,$start,$search,$idUser,$store,$supplier,$terbayar);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelFinance->dataTagihan($length,$start,$search,$idUser,$store,$supplier,$terbayar);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {

			if($dt['status_hutang']==0){
				$status = "Belum Terbayar";
			} elseif($dt['status_hutang']==1){
				$status = "Terbayar";
			} elseif($dt['status_hutang']==2){
				$status = "Selesai";
			}

			$output['data'][]=array($nomor_urut,"<a href='".base_url('finance/invoice_penagihan?no_tagihan='.$dt['no_tagihan'])."'>".$dt['no_tagihan']."</a>",$dt['supplier'],$dt['tanggal_po'],$dt['jatuh_tempo'],$dt['first_name'],$dt['keterangan'],'<b>'.$dt['status_receive'].'</b><br>'.$dt['keterangan_receive'],$status);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_penagihan(){
		$data['header'] = $this->db->get("ap_receipt");

		$no_tagihan 				= $_GET['no_tagihan'];

		if (isset($_POST['potongan_retur'])){
			$this->modelFinance->updateStatusHutang_potongretur($no_tagihan,$_POST['potongan_retur']);
		}
		$data['info_hutang'] 		= $this->modelFinance->infoHutang($no_tagihan);
		$data['paymentType'] = $this->db->get("payment_type_debt")->result();

		$idSupplier = $this->modelFinance->infoHutang($no_tagihan)->id_supplier;
		$data['supplier'] = $this->db->get_where("supplier",array("id_supplier" => $idSupplier))->row();
		$this->global['pageTitle'] = "SOLUSI POS - Invoice Penagihan";
		$this->loadViews("finance/body_invoice_penagihan",$this->global,$data,"finance/footerInvoicePenagihan");
	}

	function jatuhTempoForm(){
		$noPO = $_POST['noPO'];
		$data['currentDeadline'] = $this->db->get_where("purchase_order",array('no_po' => $noPO))->row()->jatuh_tempo;
		$data['noPO'] = $noPO;
		$this->load->view("finance/jatuhTempoForm",$data);
	}

	function updateTanggalJatuhTempo(){
		$noPO = $this->input->post("noPO");
		$tanggal = $this->input->post("tanggal");

		$dataUpdate = array(
								"jatuh_tempo" => $tanggal
						   );

		$this->modelFinance->updateTanggalJatuhTempo($noPO,$dataUpdate);
	}

	function dataTagihan(){
		$no_tagihan 				= $_POST['noPO'];

		$typePO = $this->db->get_where("purchase_order",array("no_po" => $no_tagihan))->row()->type;

		if($typePO==1){
			$data['purchaseItem'] = $this->modelFinance->purchaseItemMaterial($no_tagihan);
		} else {
			$data['purchaseItem'] = $this->modelFinance->purchaseItem($no_tagihan);
		}
		
		$data['noTagihan'] = $no_tagihan;
		$data['potongan_retur'] = $this->modelFinance->potongan_retur($no_tagihan);
		$data['hutangTerbayar'] = $this->modelFinance->hutangTerbayar($no_tagihan);
		$this->load->view("finance/dataTagihan",$data);
	}

	function invoiceReceive(){
		$no_po 	= $_POST['noPO'];
		$data['received_invoice'] = $this->model1->received_invoice($no_po);
		$this->load->view("finance/invoiceReceive",$data);
	}

	function riwayatPembayaran(){
		$noPO = $_POST['noPO'];
		$data['riwayatPembayaran'] = $this->modelFinance->riwayatPembayaran($noPO);
		$this->load->view("finance/riwayatPembayaran",$data);
	}

	function invoice_receive(){
		$this->load->model("modelBahanMasukMaterial");
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);

		$noPO = $this->db->get_where("receive_order",array("no_receive" => $no_receive))->row()->no_po; 
		$typePO = $this->db->get_where("purchase_order",array("no_po" => $noPO))->row()->type;

		if($typePO==1){
			$data['receive_item'] = $this->modelBahanMasukMaterial->received_item_material($no_receive);
		} else {
			$data['receive_item'] = $this->modelBahanMasukMaterial->received_item($no_receive);
		}

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Penerimaan";
		$this->loadViews("bahan_masuk/body_invoice_receive",$this->global,$data,"footer_empty");
	}

	function invoicePembayaran(){
		$noPayment = $this->input->get("no_payment");
		$data['header'] = $this->db->get("ap_receipt")->row();
		$data['infoPembayaran'] = $this->modelFinance->infoPembayaran($noPayment);

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Pembayaran";
		$this->loadViews("finance/bodyInvoicePembayaran",$this->global,$data,"footer_empty");
	}

	function submitPembayaran(){
		$jumlahPembayaran = $_POST['jumlahPembayaran'];
		$tipeBayar = $_POST['tipeBayar'];
		$keterangan = $_POST['keterangan'];
		$id_pic = $this->global['idUser'];
		$noPO = $_POST['noPO'];

		$noPayment 		= $this->modelFinance->noPayment();

		//inisialisasi ke bentuk no pembayaran
		$no_payment = "PY-".date('y').date('m').date('d').sprintf("%03d",$id_pic).sprintf("%04d",$noPayment+1);

		//insert data pembayaran hutang

		$dataPayment = array(
								"no_payment"	=> $no_payment,
								"no_po" => $noPO,
								"id_pic" => $id_pic,
								"id_payment" => $tipeBayar,
								"tanggal_pembayaran" => date('Y-m-d H:i:s'),
								"pembayaran" => $jumlahPembayaran,
								"keterangan" => $keterangan
						    );

		$this->modelFinance->insertDebtPayment($dataPayment);
		
		//ubah status hutang
		$this->modelFinance->updateStatusHutang($noPO);
		echo $no_payment;
		//redirect("finance/invoice_penagihan?no_tagihan=".$no_penagihan);
	}


	function tutup_transaksi(){
		$no_tagihan = $_GET['no_tagihan'];

		$update_hutang = array(
									"status_hutang" 	=> 2
							  );

		$this->modelFinance->tutup_transaksi($no_tagihan,$update_hutang);
		redirect("finance/invoice_penagihan?no_tagihan=".$no_tagihan);
	}
}