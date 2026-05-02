<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Retur extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelRetur"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,13);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Retur Pembelian";
		$this->loadViews("retur/body_retur",$this->global,NULL,"footer_empty");		
	}

	function returPO(){
		$this->global['pageTitle'] = "SOLUSI POS - Retur Pembelian";
		$this->loadViews("retur/bodyReturPO",$this->global,NULL,"retur/footerReturPO");	
	}

	function datatablesPO(){
		$this->load->model("modelBahanMasukMaterial");
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];
        $idUser     = $this->global['idUser'];
        

		$total 			 			= $this->modelBahanMasukMaterial->totalPOProduk();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore']);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,"<a href='".base_url('retur/returPOForm?noPO='.$dt['no_po'])."'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['tanggal_kirim'],$dt['supplier'],$dt['first_name']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function returPOForm(){
		$noPO = $this->input->get("noPO");
		$data['infoPO'] = $this->modelRetur->infoPO($noPO);
		$data['purchase_item'] = $this->modelRetur->purchase_item($noPO);

		$this->global['pageTitle'] = "SOLUSI POS - Form Retur Pembelian";
		$this->loadViews("retur/bodyReturPOForm",$this->global,$data,"retur/footerReturPOForm");
	}

	function cekPurchasePeritem(){
		$idProduk = $_POST['idProduk'];
		$noPO = $_POST['noPO'];
		$qty = $_POST['qty'];

		$purchasePeritem = $this->modelRetur->barangDiterima($idProduk,$noPO);
		$returHistory = $this->modelRetur->returHistory($idProduk,$noPO);

		$max = $purchasePeritem-$returHistory;

		if($qty > $max){
			echo 0;
		} else {
			echo 1;
		}
	}

	function returSQL(){
		$tanggal_retur 		= date('Y-m-d H:i:s');
		$id_pic 			= $this->global['idUser'];
		$cek_no_retur 		= $this->modelRetur->cekNoRetur();
		$no_inv 			= "RT-".date('y').date('m').date('d').sprintf("%03d",$id_pic).sprintf("%03d",$cek_no_retur+1);
		$noPO 				= $_POST['noPO'];

		$data_retur = array(
								"no_retur"		=> $no_inv,
								"no_po" 		=> $noPO,
								"id_pic"		=> $id_pic,
								"tanggal_retur"	=> $tanggal_retur
						   );

		$this->modelRetur->insertNoRetur($data_retur);
		
		$item = json_decode(stripcslashes($_POST['item']));

		foreach($item as $dt){

			$sku 		= $dt->idProduk;
			$qty 		= $dt->qty;
			$harga 		= $dt->harga;

			if($qty > 0){

				$data_item[] = array(
									"no_retur"		=> $no_inv,
									"sku"			=> $sku,
									"qty"			=> $qty,
									"harga"			=> $harga,
									"keterangan"	=> "",
									"tanggal"		=> $tanggal_retur
								  );
				$data_kartu[] = array(
									"id_store"		=> $this->global['idStore'],
									"id_produk"		=> $sku,
									"qty"			=> (-1)*$qty,
									"hpp"			=> $harga,
									"tanggal"		=> date('Y-m-d H:i:s'),
									"tipe"			=> 'Retur PO',
									"no_transaksi"	=> $no_inv,
									"id_pic"		=> $this->global['idUser']
								);

				$stok_lama = $this->model1->cek_stok_lama($sku,$this->global['idStore']);

				$data_update[] = array(
										"id_produk" => $sku,
										"stok"	=> $stok_lama-$qty
									);
			}

		}
		if($data_kartu){
			$this->model1->insertKartuStok($data_kartu);
		}
		$this->modelRetur->insertReturItemBatch($data_item);
		$this->modelRetur->updateBatchStok($data_update,$this->global['idStore']);
		echo $no_inv;
		//redirect("retur/nota_retur?no_retur=".$no_inv);
	}


	function nota_retur(){
		$data['header'] = $this->db->get("ap_receipt");
		$no_retur = $_GET['no_retur'];
		$data['returInfo'] = $this->modelRetur->returInfo($no_retur);
		$data['returItem'] = $this->modelRetur->returItem($no_retur);

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Retur";
		$this->loadViews("retur/nota_retur",$this->global,$data,"footer_empty");
	}

	function returToWarehouse(){
		$data['getStore'] = $this->db->get("ap_store")->result();
		
		$this->global['pageTitle'] = "SOLUSI POS - Retur Toko ke Gudang";
		$this->loadViews("retur/bodyReturToWarehouse",$this->global,$data,"retur/footerReturWarehouse");
	}

	function returContent(){
		$data['idStore'] 	= $_POST['idStore'];
		$this->load->view("retur/returContent",$data);
	}

	function ajaxProdukStore(){
		$q 	= $_GET['term'];
		$id_store = $_GET['idStore'];
		$this->load->model("model_penjualan");
		$customer = $this->model_penjualan->produkSearchRetur($q,$id_store);
		$data_array = array();

		foreach($customer->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk,
								 );
		}

		echo json_encode($data_array);
	}

	function data_form(){
		$this->load->model("model_penjualan");
		$data['no'] 	= $_GET['no'];
		$sku = $_GET['sku'];
		$data['idStore'] = $_GET['idStore'];

		$data['bahan_baku'] = $this->db->get_where("ap_produk",array("id_produk"	=> $sku));
		$this->load->view("retur/expandDataRetur",$data);
	}

	function returPerStoreSQL(){
		$this->load->model("model_penjualan");
		$tanggalRetur 		= date('Y-m-d H:i:s');
		$id_pic 			= $this->global['idUser'];
		$idStore 			= $_POST['idStore'];
		$cek_no_retur 		= $this->model_penjualan->cekNoReturPerstore();
		$no_inv 			= "RTS-".date('y').date('m').date('d')."-".sprintf("%02d",$cek_no_retur+1);
		
		$dataRetur 			= array(
										"NoRetur"		=> $no_inv,
										"tanggal"		=> $tanggalRetur,
										"id_user"		=> $id_pic,
										"idStoreFrom"	=> $idStore
								   );

		$this->modelRetur->returPerstore($dataRetur);
		
		$count = count($_POST['sku']);

		for($i=0;$i<$count;$i++){
			$sku 	= $_POST['sku'][$i];
			$qty 	= $_POST['qty'][$i];

			//potong stok di store
			$stokStore = $this->model_penjualan->cekStokPerStore($sku,$idStore);

			$dataUpdate = array(
									"stok"	=> $stokStore-$qty
							   );

			$this->modelRetur->updateStokStore($idStore,$sku,$dataUpdate);

			//update data di gudang 
			//get old stock on warehouse
			$oldStokGudang = $this->model_penjualan->oldStokWarehouse($sku);

			$stokGudang[] = array(
								    "id_produk" => $sku,
									"stok" => $oldStokGudang+$qty
							   );
		
			$dataArray[] = array(
								"NoRetur"	=> $no_inv,
								"sku"		=> $sku,
								"qty"		=> $qty,
								"tanggal"	=> date('Y-m-d')
							  );
		}

		$this->modelRetur->tambahStokGudang($stokGudang);
		$this->modelRetur->insertBatchReturStoreItem($dataArray);
		redirect("retur/invReturPerStore?noRetur=".$no_inv);
	}

	function invReturPerStore(){
		$this->load->model("model_penjualan");
		$noRetur 	= $this->input->get("noRetur");
		$data['infoCompany'] = $this->db->get("ap_receipt")->result();
		$data['infoRetur'] 	 = $this->model_penjualan->infoReturPerstore($noRetur);
		$data['returItem'] 	 = $this->model_penjualan->returItem($noRetur);

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Retur Toko ke Gudang";
		$this->loadViews("retur/bodyInvRetuPerstore",$this->global,$data,"footer_empty");
	}

	function daftarReturPerstore(){
		$this->load->model("model_penjualan");
		$data['dataRetur'] = $this->model_penjualan->dataReturPerstore();
		$this->global['pageTitle'] = "SOLUSI POS - Data Retur Store ke Gudang";
		$this->loadViews("retur/bodyDaftarReturPerstore",$this->global,$data,"footer_empty");
	}

}