<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class BahanMasukMaterial extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelBahanMasukMaterial"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,36);
	}
	
	function index(){
		$data['supplier'] = $this->db->get("supplier")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Barang Masuk Material";
		$this->loadViews("bahan_masuk_material/bodyBahanMasukMaterial",$this->global,$data,"bahan_masuk_material/footerBahanMasukMaterial");
	}	

	function POFilter(){
		$data['tanggalPO'] = $_POST['tanggalPO'];
		$data['tanggalKirim'] = $_POST['tanggalKirim'];
		$data['supplier'] = $_POST['supplier'];
		$data['status'] = $_POST['status'];

		$this->load->view("bahan_masuk_material/POFilter",$data);
	}

	function datatablesPO(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelBahanMasukMaterial->totalPOMaterial();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewPOMaterial($length,$start,$search);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewPOMaterial($length,$start,$search);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<a href="'.base_url('bahanMasukMaterial/goodReceipt?noPO='.$dt['no_po']).'"><span class="label label-primary">Menunggu Approve</span></a>';
			} elseif($status==1){
				$button = '<a href="'.base_url('bahanMasukMaterial/goodReceipt?noPO='.$dt['no_po']).'"><span class="label label-success">Diterima</span></a>';
			} elseif($status==2){
				$button = '<a href="'.base_url('bahanMasukMaterial/goodReceipt?noPO='.$dt['no_po']).'"><span class="label label-danger">Ditolak</span></a>';
			} elseif($status==3){
				$button = '<a href="'.base_url('bahanMasukMaterial/goodReceipt?noPO='.$dt['no_po']).'"><span class="label label-info">Selesai</span></a>';
			}

			$output['data'][]=array($nomor_urut,"<a href='#'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['tanggal_kirim'],$dt['supplier'],$dt['first_name'],$button);
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

		$total 			 			= $this->modelBahanMasukMaterial->totalPOMaterialFilter($tanggalPO,$tanggalKirim,$supplier,$status);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewPOMaterialFilter($length,$start,$search,$tanggalPO,$tanggalKirim,$supplier,$status);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewPOMaterialFilter($length,$start,$search,$tanggalPO,$tanggalKirim,$supplier,$status);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<a href="'.base_url('bahanMasukMaterial/goodReceipt?noPO='.$dt['no_po']).'"><span class="label label-primary">Menunggu Approve</span></a>';
			} elseif($status==1){
				$button = '<a href="'.base_url('bahanMasukMaterial/goodReceipt?noPO='.$dt['no_po']).'"><span class="label label-success">Diterima</span></a>';
			} elseif($status==2){
				$button = '<a href="'.base_url('bahanMasukMaterial/goodReceipt?noPO='.$dt['no_po']).'"><span class="label label-danger">Ditolak</span></a>';
			} elseif($status==3){
				$button = '<a href="'.base_url('bahanMasukMaterial/goodReceipt?noPO='.$dt['no_po']).'"><span class="label label-info">Selesai</span></a>';
			}

			$output['data'][]=array($nomor_urut,"<a href='#'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['tanggal_kirim'],$dt['supplier'],$dt['first_name'],$button);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function goodReceipt(){
		$no_po 	= $_GET['noPO'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelBahanMasukMaterial->purchaseItemMaterial($no_po);
		$data['received_invoice'] = $this->model1->received_invoice($no_po);
		$data['store'] = $this->db->get("ap_store")->result();
		$data['noteInfo'] = $this->modelBahanMasukMaterial->noteInfoPO($no_po);

		$this->global['pageTitle'] = "SOLUSI POS - Form Penerimaan Bahan Baku";
		$this->loadViews("bahan_masuk_material/bodyGoodReceipt",$this->global,$data,"bahan_masuk_material/footerGR");
	}

	function detailOrder(){
		$no_po = $_POST['noPo'];
		$data['no_po'] = $no_po;
		$data['purchase_item'] = $this->modelBahanMasukMaterial->purchaseItemMaterial($no_po);
		$this->load->view("bahan_masuk_material/detailOrder",$data);
	}

	function changePOStatus(){
		$status 		= $_GET['status'];
		$no_po 	 		= $_GET['no_po'];

		$data_update = array(
								"status"	=> $status
							);

		$this->modelBahanMasukMaterial->changePOStatus($no_po,$data_update);
		redirect("bahanMasukMaterial/goodReceipt?noPO=".$no_po);
	}

	function prosesReceiveItem(){
		$id_user  		= sprintf("%03d",$this->global['idUser']);
		$received_by 	= $_POST['diterimaOleh'];
		$checked_by 	= $_POST['diperiksaOleh'];
		$tanggal_terima = $_POST['tanggalTerima'];
		$no_po 			= $_POST['noPo'];
		$id_supplier	= $_POST['idSupplier'];

		$cek_terima 	= $this->model1->cek_tanggal_receive($tanggal_terima);

		$create_date  	= date_create($tanggal_terima);
		$convert_date 	= date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

		$no_inv = 'RCVM'.$convert_date.$id_user.sprintf("%03d",$cek_terima+1);


		$data_receive = array(
								"no_receive"		=> $no_inv,
								"no_po"				=> $no_po,
								"received_by"		=> $received_by,
								"checked_by"		=> $checked_by,
								"tanggal_terima"	=> $tanggal_terima,
								"id_pic"			=> $this->global['idUser'],
								"id_supplier"		=> $id_supplier,
								"type"				=> 1
							);

		$this->modelBahanMasukMaterial->insertReceiveOrder($data_receive);

		$itemProduk = $_POST['produkItem'];
		$decodeJSON = json_decode(stripcslashes($itemProduk));

		foreach($decodeJSON as $row){
			$sku 	= $row->sku;
			$qty 	= $row->qty;
			$price 	= $row->harga;

			$data_insert[] = array(
									"no_receive" 	=> $no_inv,
									"sku"			=> $sku,
									"qty"			=> $qty,
									"price"			=> $price,
									"tanggal"		=> $tanggal_terima
								);

			$stok_lama = $this->modelBahanMasukMaterial->cekStokBahanBaku($sku);


			$data_update[] = array(
									"stok" 	=> $stok_lama+$qty,
									"sku"	=> $sku
								);

		}

		$this->modelBahanMasukMaterial->insertBatchReceiveItem($data_insert);
		$this->modelBahanMasukMaterial->updateBatchStokBahanBaku($data_update);
		
		//PROSES PENERBITAN HUTANG
		//SET INSERT HUTANG 

		//cek if exist

		$cek_penerbitan_hutang = $this->model1->cek_penerbitan_hutang($no_po);

		if($cek_penerbitan_hutang < 1){
			$data_tagihan = array(
									"no_tagihan"		=> $no_po,
									"status_hutang" 	=> 0
								 );

			$this->modelBahanMasukMaterial->terbitkanHutang($data_tagihan);
		}

		echo $no_inv;
	}

	function invoiceReceive(){
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['noReceive'];
		$data['dataReceive'] = $this->modelBahanMasukMaterial->dataReceive($no_receive);
		$data['receive_item'] = $this->modelBahanMasukMaterial->receivedItemMaterial($no_receive);

		$this->global['pageTitle'] = "SOLUSI POS - Good Receive Bahan Baku";
		$this->loadViews("bahan_masuk_material/bodyInvoiceReceive",$this->global,$data,"footer_empty");
	}

	function riwayatPenerimaan(){
		$noPo = $_POST['noPo'];
		$data['riwayatPenerimaan'] = $this->modelBahanMasukMaterial->riwayatPenerimaan($noPo);
		$this->load->view("bahan_masuk_material/riwayatPenerimaan",$data);
	}

	function invoiceReceiveHistory(){
		$no_po 	= $_POST['noPo'];
		$data['received_invoice'] = $this->modelBahanMasukMaterial->receivedInvoice($no_po);
		$this->load->view("bahan_masuk_material/invoiceReceive",$data);
	}

}