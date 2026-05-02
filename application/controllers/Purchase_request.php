<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Purchase_request extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelBahanMasukMaterial","modelPurchaseOrder"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,10);
	}

	function index(){
		$data['supplier'] = $this->db->get("supplier")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Purchase Request";
		$this->loadViews("bahan_masuk/body_bahan_masuk_req",$this->global,$data,"bahan_masuk/footerBahanMasukReq");
	}

	function ajax_produk(){
		$q 			= $_GET['term'];
		$id_brand	= (isset($_SESSION['id_brand']))?$_SESSION['id_brand']:'';
		$get_bahan_baku_select2 = $this->modelBahanMasukMaterial->produkAjax($q,$id_brand);

		$data_array = array();

		foreach($get_bahan_baku_select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}

	function insertNewReq(){
		$idProduk 		= $_POST['idProduk'];
		$no_po 			= $_POST['no_po'];
		$tanggal 		= $_POST['tanggal'];
		$idUser 		= $this->global['idUser'];
		$idStore		= empty($_POST['id_toko'])?7:$_POST['id_toko'];
		$hargaProduk 	= $this->modelBahanMasukMaterial->hargaBeliProduk($idProduk,$idStore);

		//cek on cart if exist
		$cekCart = $this->modelBahanMasukMaterial->cekPO($no_po,$idProduk,$idUser);

		if($cekCart < 1){
			$dataCart = array(
								"sku"		=> $idProduk,
								"qty"		=> 1,
								"qty_approved" => 1,
								"qty_req"	=> 0,
								"qty_confirmed" => '-1',
								"no_po" 	=> $no_po,
								"harga"		=> $hargaProduk,
								"tanggal"	=> $tanggal
						     );

			$this->modelBahanMasukMaterial->insertPOItem($dataCart);
			echo 0;
		} else {
			echo $idProduk;
		}
	}

	function POFilter(){
		$data['tanggalPO'] = $_POST['tanggalPO'];
		$data['tanggalKirim'] = $_POST['tanggalKirim'];
		$data['supplier'] = $_POST['supplier'];
		$data['status'] = $_POST['status'];

		$this->load->view("bahan_masuk/POFilterReq",$data);
	}

	function datatablesPO(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];
        $idUser     = $this->global['idUser'];

		$total 			 			= $this->modelBahanMasukMaterial->totalPOProdukReq();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewPOProdukReq($length,$start,$search,$idUser,$this->global['idStore']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewPOProdukReq($length,$start,$search,$idUser,$this->global['idStore']);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<a href="'.base_url('purchase_request/good_request?no_po='.$dt['no_po']).'"><span class="label label-danger">Waiting for MD</span></a>';
			} elseif($status==1){
				$button = '<a href="'.base_url('purchase_request/good_request?no_po='.$dt['no_po']).'"><span class="label label-success">Approved</span></a>';
			} elseif($status==2){
				$button = '<a href="'.base_url('purchase_request/good_request?no_po='.$dt['no_po']).'"><span class="label label-danger">Declined</span></a>';
			} elseif($status==3){
				$button = '<a href="'.base_url('purchase_request/good_request?no_po='.$dt['no_po']).'"><span class="label label-info">Order Received</span></a>';
			}

			$output['data'][]=array($nomor_urut,"<a href='#'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['tanggal_kirim'],$dt['brand'],$dt['supplier'],$dt['store'],$dt['first_name'],$button);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function datatablesPOFilter(){
		$tanggalPO = $_POST['tanggalPO'];
		$tanggalKirim = $_POST['tanggalKirim'];
		$supplier = $_POST['supplier'];
		$status = $_POST['status'];

		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelBahanMasukMaterial->totalPOProdukFilterReq($tanggalPO,$tanggalKirim,$supplier,$status);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewPOProdukFilterReq($length,$start,$search,$tanggalPO,$tanggalKirim,$supplier,$status);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewPOProdukFilterReq($length,$start,$search,$tanggalPO,$tanggalKirim,$supplier,$status);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<a href="'.base_url('purchase_request/good_request?no_po='.$dt['no_po']).'"><span class="label label-danger">Waiting for MD</span></a>';
			} elseif($status==1){
				$button = '<a href="'.base_url('purchase_request/good_request?no_po='.$dt['no_po']).'"><span class="label label-success">Approved</span></a>';
			} elseif($status==2){
				$button = '<a href="'.base_url('purchase_request/good_request?no_po='.$dt['no_po']).'"><span class="label label-danger">Declined</span></a>';
			} elseif($status==3){
				$button = '<a href="'.base_url('purchase_request/good_request?no_po='.$dt['no_po']).'"><span class="label label-info">Order Received</span></a>';
			}

			$output['data'][]=array($nomor_urut,"<a href='#'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['tanggal_kirim'],$dt['supplier'],$dt['first_name'],$button);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function change_po_status(){
		$status 		= $_GET['status'];
		$no_po 	 		= $_GET['no_po'];

		$data_update = array(
								"status"	=> $status,
								"tanggal_MD" => date('Y-m-d H:i:s')
							);
		if ($status=4 || $status==1){
			$this->db->query("UPDATE purchase_item set qty=qty_req,qty_approved=qty_req,qty_confirmed='-1' where no_po='$no_po' and qty='-1'");
		}					

		$this->modelBahanMasukMaterial->changePOStatus($no_po,$data_update);
		echo "<script>window.open('".base_url('purchase_order/form_po?no_po=').$no_po."','_blank');
		window.location='".base_url('purchase_request')."';</script>";
		//redirect("purchase_request");
	}


	function good_request(){
		$no_po 	= $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelBahanMasukMaterial->purchase_item($no_po,$this->global['idStore']);
		$data['received_invoice'] = $this->model1->received_invoice($no_po);
		$data['store'] = $this->db->get("ap_store")->result();
		$data['noteInfo'] = $this->modelBahanMasukMaterial->noteInfoPO($no_po);
		error_reporting(0);ini_set('display_errors',0);
		$this->global['pageTitle'] = "SOLUSI POS - Goods Requests";
		$this->loadViews("bahan_masuk/body_good_request",$this->global,$data,"bahan_masuk/footerBarangMasukReq");
	}

	function invoice_receive(){
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);
		$data['receive_item'] = $this->modelBahanMasukMaterial->received_item($no_receive);

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Penerimaan";
		$this->loadViews("bahan_masuk/body_invoice_receive",$this->global,$data,"footer_empty");
	}

	function form_po(){
		$this->load->view("navigation");
				$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelBahanMasukMaterial->purchase_item($no_po);
		$info_po = $this->model1->info_purchase($no_po);

		foreach($info_po as $row){
			$data['tanggal_po'] 		= $row->tanggal_po;
			$data['keterangan'] 		= $row->keterangan;
			$data['supplier'] 			= $row->supplier;
			$data['alamat_sp'] 			= $row->alamat;
			$data['kontak_sp'] 			= $row->kontak;
			$data['ppn']				= $row->ppn;
			$data['nilai_ppn']			= $row->nilai_ppn;
			$data['alamat_pengiriman'] 	= $row->alamat_pengiriman;
			$data['tanggal_kirim']		= $row->tanggal_kirim;
		}
		$this->load->view("bahan_masuk/body_form_po",$data);
		$this->load->view("bahan_masuk/footer_barang_masuk");
	}

	function detailOrder(){
		$no_po = $_POST['noPo'];
		$data['no_po'] = $no_po;
		$data['purchase_item'] = $this->modelBahanMasukMaterial->purchase_item($no_po);
		$data['noteInfo'] = $this->modelBahanMasukMaterial->noteInfoPO($no_po);
		$this->load->view("bahan_masuk/detailOrderReq",$data);
	}

	function invoiceReceive(){
		$no_po 	= $_POST['noPo'];
		$data['received_invoice'] = $this->model1->received_invoice($no_po);
		$this->load->view("bahan_masuk/invoiceReceive",$data);
	}

	function updateQty(){
		$noPo = $_POST['noPo'];
		$qty = $_POST['qty'];
		$idProduk = $_POST['idProduk'];

		$data_update = array(
								"qty"	=> $qty,
								"qty_approved" => $qty
							);

		$data['riwayatPenerimaan'] = $this->modelBahanMasukMaterial->updateQty($noPo,$data_update,$idProduk);
		echo "Disimpan!";
	}

	function qtyReceived(){
		$idProduk 	= $_POST['idProduk'];
		$noPo 		= $_POST['noPo'];

		$qtyReceived = $this->model1->qtyDiterima($idProduk,$noPo);
		echo $qtyReceived;
	}
}