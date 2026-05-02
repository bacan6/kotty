<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class PurchaseOrderMaterial extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model(array('model1','modelPurchaseOrderMaterial'));
		$this->load->library("session");

		$this->isLoggedIn($this->global['idUser'],2,35);
	}

	function index(){
		$data['supplier'] = $this->db->get("supplier");
		$this->global['pageTitle'] = "SOLUSI POS - Purchase Order Material";
		$this->loadViews("purchase_order_material/bodyPOMaterial",$this->global,$data,"purchase_order_material/footer");
	}

	function ajaxProduk(){
		$q 			= $_GET['term'];

		$get_bahan_baku_select2 = $this->modelPurchaseOrderMaterial->produkAjax($q);

		$data_array = array();

		foreach($get_bahan_baku_select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->sku,
									"text"	=> $row->nama_bahan
								 );
		}

		echo json_encode($data_array);
	}

	function insertCartPO(){
		$idProduk 		= $_POST['idProduk'];
		$idUser = $this->global['idUser'];
		$hargaProduk = $this->modelPurchaseOrderMaterial->hargaBeliProduk($idProduk);

		//cek on cart if exist
		$cekCart = $this->modelPurchaseOrderMaterial->cekCartPO($idProduk,$idUser);

		if($cekCart < 1){
			$dataCart = array(
								"idProduk"		=> $idProduk,
								"qty"			=> 1,
								"idUser" 		=> $idUser,
								"harga"			=> $hargaProduk
						     );

			$this->modelPurchaseOrderMaterial->insertCartPO($dataCart);
			echo 0;
		} else {
			$currentQtyCart = $this->modelPurchaseOrderMaterial->currentQtyCart($idProduk,$idUser);
			$dataUpdate = array(
									"qty" 		=> $currentQtyCart+1
							   );

			$this->modelPurchaseOrderMaterial->updateCartPO($idProduk,$idUser,$dataUpdate);
			echo 1;
		}
	}

	function cartPO(){
		$idUser = $this->global['idUser'];
		$data['viewCartPO'] = $this->modelPurchaseOrderMaterial->viewCartPO($idUser);
		$this->load->view("purchase_order_material/cartPO",$data);
	}

	function updateQtyCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$qty = $_POST['qty'];

		$dataUpdate = array(
								"qty"		=> $qty
					       );
		
		$this->modelPurchaseOrderMaterial->updateQtyCart($idProduk,$idUser,$dataUpdate);
	}

	function updateHargaCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$harga = $_POST['harga'];

		$dataUpdate = array(
								"harga"		=> $harga
					       );
			
		$this->modelPurchaseOrderMaterial->updateHargaCart($idProduk,$idUser,$dataUpdate);
	}

	function hapusCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$this->modelPurchaseOrderMaterial->hapusCart($idProduk,$idUser);
	}

	function insertPO(){
		$id_user  		= sprintf("%03d",$this->global['idUser']);
		$tanggal_po 	= date('Y-m-d');
		$tanggal_kirim 	= $_POST['tanggalKirim'];
		$jatuh_tempo 	= $_POST['jatuhTempo'];
		$keterangan 	= $_POST['keterangan'];
		$supplier  		= $_POST['supplier'];
		$alamat 		= $_POST['alamatPengiriman'];

		$cek_tanggal 	= $this->model1->cek_tanggal_terima($tanggal_po);

		$create_date 	= date_create($tanggal_po);
		$convert_date   = date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

		$no_inv = 'PO'.$convert_date.$id_user.sprintf("%04d",$cek_tanggal+1);

		$data_masuk = array(
								"no_po" 			=> $no_inv,
								"tanggal_po" 		=> $tanggal_po,
								"tanggal_kirim"		=> $tanggal_kirim,
								"jatuh_tempo"		=> $jatuh_tempo,
								"alamat_pengiriman"	=> $alamat,
								"id_supplier"		=> $supplier,
								"keterangan"		=> $keterangan,
								"id_pic"			=> $this->global['idUser'],
								"status"			=> 0,
								"type"				=> 1
							);
		
		$this->modelPurchaseOrderMaterial->insertPO($data_masuk);

		$viewDataPO = $this->modelPurchaseOrderMaterial->viewCartPO($this->global['idUser']);

		foreach($viewDataPO->result() as $row){
			$sku 			= $row->sku;
			$jumlah_beli	= $row->qty;
			$harga 			= $row->harga;

			$data_bahan[]     = array(
										"no_po"			=> $no_inv,
										"sku"			=> $sku,
										"qty"			=> $jumlah_beli,
										"harga"			=> $harga,
										"tanggal"		=> $tanggal_po
								   );
		}

		$this->modelPurchaseOrderMaterial->insertBatchPurchaseItem($data_bahan);
		$this->modelPurchaseOrderMaterial->hapusCCPOMaterial($this->global['idUser']);
		echo $no_inv;
	}

	function formPOMaterial(){
		$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelPurchaseOrderMaterial->purchase_item($no_po);
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
			$data['idSupplier'] 		= $row->id_supplier;
		}

		$this->global['pageTitle'] = "SOLUSI POS - Form PO Material";
		$this->loadViews("purchase_order_material/bodyFormPO",$this->global,$data,"purchase_order_material/footer");
	}
}