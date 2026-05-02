<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class WorkOrder extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelWorkOrder"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,37);
	}

	function index(){
		$data['supplier'] = $this->db->get("supplier")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Work Order";
		$this->loadViews("work_order/bodyWorkOrder",$this->global,$data,"work_order/footerWO");
	}

	function ajaxBahan(){
		$q 	= $_GET['term'];

		$get_bahan_baku_select2 = $this->modelWorkOrder->produkAjax($q);

		$data_array = array();

		foreach($get_bahan_baku_select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->sku,
									"text"	=> $row->nama_bahan
								 );
		}

		echo json_encode($data_array);
	}

	function viewCartBahanBaku(){
		$idUser = $this->global['idUser'];
		$data['viewCart'] = $this->modelWorkOrder->viewCart($idUser);
		$this->load->view("work_order/viewCart",$data);
	}

	function insertCart(){
		$sku 		= $_POST['sku'];	
		$idUser 	= $this->global['idUser'];
		$cekIfExist = $this->modelWorkOrder->cekSKUIfExist($sku,$idUser);

		$currentStokBahanBaku = $this->modelWorkOrder->currentStokBahanBaku($sku);

		if($cekIfExist < 1){

			if($currentStokBahanBaku < 1){
				// tidak ada stok
				echo 0;
			} else {
				$harga = $this->modelWorkOrder->hargaBahanBaku($sku);
				$dataInsert = array(
										"sku"		=> $sku,
										"harga"		=> $harga,
										"qty"		=> 1,
										"idUser" 	=> $idUser
								   );

				$this->modelWorkOrder->insertCart($dataInsert);
				//able to input
				echo 2;
			}

		} else {
			//barang sudah terinput
			echo 1;
		}
	}

	function hapusCart(){
		$sku 	= $_POST['sku'];
		$idUser = $this->global['idUser'];

		$param = array(
							"idUser" 	=> $idUser,
							"sku"		=> $sku
					  );

		$this->modelWorkOrder->hapusCart($param);
	}

	function updateCart(){
		$idUser = $this->global['idUser'];
		$sku 	= $_POST['sku'];
		$qty 	= $_POST['qty'];

		$currentStokBahanBaku = $this->modelWorkOrder->currentStokBahanBaku($sku);

		if($qty > $currentStokBahanBaku){
			//melebihi stok
			echo 0;
		} else {
			$dataUpdate = array(
						       		"qty"		=> $qty
						       );	

			$this->modelWorkOrder->updateCart($dataUpdate);
			//able to update
			echo 1;
		}
	}

	function ajaxProduk(){
		$q 			= $_GET['term'];

		$get_bahan_baku_select2 = $this->modelWorkOrder->produkAjaxFG($q);

		$data_array = array();

		foreach($get_bahan_baku_select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}

	function insertCartFG(){
		$idProduk 		= $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		//cek on cart if exist
		$cekCart = $this->modelWorkOrder->cekCartConvert($idProduk,$idUser);

		if($cekCart < 1){
			$dataCart = array(
								"idProduk"		=> $idProduk,
								"qty"			=> 1,
								"idUser" 		=> $idUser
						     );

			$this->modelWorkOrder->insertCartFG($dataCart);
			echo 0;
		} else {
			echo 1;
		}
	}

	function viewCartProdukConvert(){
		$idUser = $this->global['idUser'];
		$data['viewCart'] = $this->modelWorkOrder->viewCartConvert($idUser);
		$this->load->view("work_order/viewCartProdukConvert",$data);
	}

	function hapusCartConvert(){
		$idProduk 		= $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$this->modelWorkOrder->hapusCartConvert($idProduk,$idUser);
	}

	function updateCartConvert(){
		$idProduk 	= $_POST['idProduk'];
		$idUser 	= $this->global['idUser'];
		$qty 		= $_POST['qty'];

		$dataUpdate = array(
								"qty" 	=> $qty
						   );
		
		$this->modelWorkOrder->updateCartConvert($idProduk,$idUser,$dataUpdate);
	}

	function prosesWO(){
		$datePromise 		= $_POST['datePromise'];
		$vendor 			= $_POST['vendor'];
		$pemohon 			= $_POST['pemohon'];
		$keterangan 		= $_POST['keterangan'];
		$alamatPengiriman 	= $_POST['alamatPengiriman'];
		$jenisBiaya 		= $_POST['jenisBiaya'];
		$biaya 				= $_POST['biaya'];
		$idUser 			= $this->global['idUser'];
		$today 				= date('Y-m-d');
		$cekUrutan = $this->modelWorkOrder->urutanNoWO($today);	
		$noWO = "ORD-".date('y').date('m').date('d').sprintf('%03d',$cekUrutan+1);

		//insert work_order
		$dataWO = array(
							"no_order" 				=> $noWO,
							"tanggalWO"				=> $today,
							"tanggalPenyelesaian" 	=> $datePromise,
							"id_user"				=> $idUser,
							"id_supplier"			=> $vendor,
							"pemohon"				=> $pemohon,
							"alamatPengiriman"		=> $alamatPengiriman,
							"keterangan"			=> $keterangan,
							"status"				=> 0
					   );

		$this->modelWorkOrder->prosesWO($dataWO);
	
		//insert work_order_item
		$workItem = $this->modelWorkOrder->viewCart($idUser);

		foreach($workItem->result() as $row){
			$sku = $row->sku;
			$harga = $row->harga;
			$qty = $row->qty;

			$dataWOItem[] = array(
									"no_order" 		=> $noWO,
									"sku"			=> $sku,
									"qty"			=> $qty,
									"harga"			=> $harga
								 );

			//KURANGI STOK BAHAN BAKU
			$stokBahanBaku = $this->modelWorkOrder->currentStokBahanBaku($sku);

			$dataStokUpdate[] = array(
										"sku"		=> $sku,
										"stok"		=> $stokBahanBaku-$qty
								   );
		}

		$this->modelWorkOrder->prosesBatchWO($dataWOItem,$dataStokUpdate,$idUser);

		//insert convert item
		$convertItem = $this->modelWorkOrder->viewCartConvert($idUser);

		foreach($convertItem->result() as $dt){
			$idProduk = $dt->id_produk;
			$qty = $dt->qty;

			$dataConvert[] = array(
										"no_order"	=> $noWO,
										"idProduk" 	=> $idProduk,
										"qty"		=> $qty
								  );
		}

		$this->modelWorkOrder->insertBatchWorkOrderConvert($dataConvert,$idUser);

		//insert biaya
		$decodeJenisBiaya = json_decode(stripcslashes($jenisBiaya));
		$decodeBiaya = json_decode(stripcslashes($biaya));

		$jenisBiaya = array();
		foreach($decodeJenisBiaya as $dt){
			$jenisBiaya[] = $dt->jenisBiaya;
		}

		$biaya = array();
		foreach($decodeBiaya as $db){
			$biaya[] = $db->biaya;
		}

		$kindOfPayment = json_encode($jenisBiaya);
		$payment = json_encode($biaya);

		$dataBiaya = array(
								"no_order"		=>$noWO,
								"nama_biaya"	=> $kindOfPayment,
								"biaya"			=> $payment
						  );

		$this->modelWorkOrder->insertWorkOrderBiaya($dataBiaya);
		echo $noWO;
	}

	function formWO(){
		$noWO = $this->input->get("noWO");
		$data['infoStore'] = $this->db->get("ap_receipt")->row();
		$data['infoWO'] = $this->modelWorkOrder->infoWO($noWO);
		$data['bahanBakuOrder'] = $this->modelWorkOrder->daftarBahanBakuOrder($noWO);
		$data['daftarPesananOrder'] = $this->modelWorkOrder->daftarPesananOrder($noWO);
		$daftarBiaya = $this->modelWorkOrder->daftarBiaya($noWO);

		$jenisBiaya = $daftarBiaya->nama_biaya;
		$biaya = $daftarBiaya->biaya;
		$array1 = json_decode($jenisBiaya);
		$array2 = json_decode($biaya);

		$data['payment'] = array_combine($array1, $array2);
		$this->global['pageTitle'] = "SOLUSI POS - Form WO";
		$this->loadViews("work_order/formWO",$this->global,$data,"work_order/footerWO");
	}
}