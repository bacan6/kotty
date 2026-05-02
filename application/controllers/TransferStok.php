<?php 
defined('BASEPATH') OR exit('No direct script access allowed');	

require APPPATH . '/libraries/BaseController.php';
//error_reporting(E_ALL);ini_set('display_errors', 1);
class TransferStok extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelTransferStok","model_penjualan"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],2,14);
	}

	function index(){
		$data['store'] = $this->db->get("ap_store")->result();
		$data['last_transfer'] = $this->modelTransferStok->viewTransferStok($this->global['idStore']);
		$this->global['pageTitle'] = "SOLUSI POS - Transfer Stok Barang";
		$this->loadViews("transfer_stok/bodyTransferStok",$this->global,$data,"transfer_stok/footerTransferStok");
	}

	function importTransferItem(){
		$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
		$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
		$data['navigation'] = $this->model1->callNavigation();
		$data['pageTitle'] = "SOLUSI POS - Import Transfer Item";
		$this->load->view("navigation",$data);
		$this->load->view("transfer_stok/import_transfer_item");
		$this->load->view("transfer_stok/footer_import");
	}

	function templateTransferItem(){
		 // ini_set('display_errors', 1);
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('D1','Qty');

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("SOLUSI POS | IT Solutions")
								->setSubject("SOLUSI POS | IT Solutions")
								->setDescription("Export Data")
								->setKeywords("office 2007 openxml php")
								->setCategory("Data Purchase Item");
	 
	     //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	  ob_end_clean();
	   	//sesuaikan headernya 
	   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.ms-excel');
	    //ubah nama file saat diunduh
	    header('Content-Disposition: attachment;filename=Transfer Item Template.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function importTransferItemSQL(){
		$config['upload_path']          = './assets/temp/';
		$config['allowed_types']        = 'xls|xlsx';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file')){
			$error = array('error' => $this->upload->display_errors());
			echo $error['error'];
		} else {
			$upload_data = $this->upload->data();
			$this->load->library('excel/PHPExcel');

			$file =  $upload_data['full_path'];
			$objPHPExcel = PHPExcel_IOFactory::load($file);

			$sheets = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

			//do update
			$i = 1;
			$dataInsert = array();
			foreach($sheets as $row){
				if($i > 1){
					$id_produk= $row['B'];
					$qty = $row['D'];

					$dataInsert[] = array(
											"idProduk" => $id_produk,
											"qty" => $qty,
											"idUser" => $this->global['idUser']
									     );
				}
			$i++; }

			$this->modelTransferStok->hapusCartTransfer($this->global['idUser']);
			$this->db->insert_batch("cc_cartransfer", $dataInsert);
			unlink($file);
		}
		
	}

	function formTransfer(){
		$idStore = $this->input->get("idStore");
		$data['namaStore'] = $this->model1->namaStore($idStore);
		$data['store'] = $this->db->get("ap_store")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Form Transfer Barang";
		$this->loadViews("transfer_stok/formTransfer",$this->global,$data,"transfer_stok/footerTransferStok");
	}

	function ajax_produk(){
		$q 	= $_GET['term'];
		$id_store = $_GET['idStore'];
		
		
		$customer = $this->modelTransferStok->produk_search($q,$id_store);

		$data_array = array();

		foreach($customer->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk,
								 );
		}

		echo json_encode($data_array);
	}

	function insertCart(){
		$idProduk 	= $_POST['idProduk'];
		$idStore 	= $_POST['idStore'];
		$idUser 	= $this->global['idUser'];

		//cek stok toko
		$stokToko = $this->modelTransferStok->stokToko($idProduk,$idStore);

		if($stokToko > 0){
			$cekCart = $this->modelTransferStok->cekCartExist($idProduk,$idUser,$idStore);
			if($cekCart < 1){
				$dataInsert = array(
										"idProduk"		=> $idProduk,
										"qty"			=> 1,
										"idUser" 		=> $idUser,
										"idStore" 		=> $idStore
								   );

				$this->modelTransferStok->insertCartTransfer($dataInsert);
			} else {
				$id = $this->modelTransferStok->getIdCart($idProduk,$idUser);
				echo $id;
			}
		} else {
			echo "NotEnoughStock";
		}
	}

	function viewCart(){
		$idUser = $this->global['idUser'];
		$idStore = $_POST['idStore'];
		$data['viewCart'] = $this->modelTransferStok->viewCart($idUser,$idStore);
		$this->load->view("transfer_stok/viewCart",$data);
	}

	function updateCart(){
		$idProduk 	= $_POST['idProduk'];
		$qty 		= $_POST['qty'];
		$idUser 	= $this->global['idUser'];
		$idStore 	= $_POST['idStore'];

		$stokToko = $this->modelTransferStok->stokToko($idProduk,$idStore);

		if($qty > $stokToko){
			echo 0;
		} else {
			$dataUpdate = array(
									"qty"	=> $qty
							   );

			$this->modelTransferStok->updateCart($idProduk,$idUser,$idStore,$dataUpdate);
			echo 1;
		}
	}

	function hapusCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser 	= $this->global['idUser'];
		$idStore 	= $_POST['idStore'];

		$this->modelTransferStok->hapusCart($idProduk,$idUser,$idStore);
	}

	function doTransfer(){
		$tokoTujuan 	= $_POST['tokoTujuan'];
		$keterangan 	= $_POST['keterangan'];
		$transferFrom 	= $_POST['transferFrom'];
		$idUser 		= $this->global['idUser'];
		$today 			= date('Y-m-d');

		//cek no urut transfer
		$noUrutTransfer = $this->modelTransferStok->noUrutTransfer($idUser,$today);
		$noTransfer = "TS-".date('y').date('m').date('d').sprintf('%03d',$idUser).sprintf('%04d',$noUrutTransfer+1);

		$dataTransfer = array(
								"noTransfer"		=> $noTransfer,
								"tanggal"			=> date('Y-m-d H:i:s'),
								"idUser"			=> $idUser,
								"transferFrom"		=> $transferFrom,
								"transferTo"		=> $tokoTujuan,
								"keterangan"		=> $keterangan
							 );

		$this->modelTransferStok->insertTransferStokNumber($dataTransfer);

		//ambil item transfer
		$itemTransfer = $this->modelTransferStok->itemTransfer($idUser,$transferFrom);

		foreach($itemTransfer as $row){
			$idProduk = $row->idProduk;
			$qty 	  = $row->qty;
            $hpp	  = $row->hpp;
			$harga	  = $row->harga;

			$data_kartu[]     = array(
									"id_store"		=> $transferFrom,
									"id_produk"		=> $idProduk,
									"qty"			=> (-1)*$qty,
									"hpp"			=> $hpp,
									"harga"			=> $harga,
									"tanggal"		=> date('Y-m-d H:i:s'),
									"tipe"			=> 'Kirim Transfer Stok',
									"no_transaksi"	=> $noTransfer,
									"id_pic"		=> $this->global['idUser']
								);

			//input item transfer 
			$this->inputItemTransfer($idProduk,$qty,$noTransfer,$hpp,$harga);

			//kurangi stok lama toko asal transfer
			$stokLamaAsalToko = $this->modelTransferStok->stokTokoAsal($idProduk,$transferFrom);
			$this->updateStokAsalToko($stokLamaAsalToko,$qty,$transferFrom,$idProduk);

			//tambah stok pada tujuan toko baru
			//$this->updateStokTokoTujuan($qty,$tokoTujuan,$idProduk);
			
		}
		if($data_kartu){
			$this->model1->insertKartuStok($data_kartu);
		}
		$this->modelTransferStok->hapusCartTransfer($idUser);
		echo $noTransfer;
		
	}

	function inputItemTransfer($idProduk,$qty,$noTransfer,$hpp,$harga){
		$dataInsert = array(
								"noTransfer"  => $noTransfer,
								"idProduk"	  => $idProduk,
								"qty"		  => $qty,
								"qty_rec"	  => '-1',
                                "hpp"		  => $hpp,
								"harga"			=> $harga
						   );

		$this->modelTransferStok->inputItemTransfer($dataInsert);
	}

	function updateStokAsalToko($stokLama,$qty,$tokoAsal,$idProduk){
		$updateStokAsal = array(
									"stok" 		=> $stokLama-$qty
							    );

		$this->modelTransferStok->updateStokAsalToko($idProduk,$tokoAsal,$updateStokAsal);
	}

	function updateStokTokoTujuan($qty,$idToko,$idProduk){
		//cek if id barang exist on store
		$cekProdukToko = $this->modelTransferStok->cekProdukToko($idProduk,$idToko);

		if($cekProdukToko > 0){
			$stokLama = $this->modelTransferStok->stokTokoTujuan($idProduk,$idToko);

			$dataUpdate = array(
									"stok" 	=> $stokLama+$qty
							   );

			$this->modelTransferStok->updateStokTokoTujuan($idProduk,$idToko,$dataUpdate);
		} else {
			$dataInsert = array(
									"id_produk" => $idProduk,
									"stok" => $qty,
									"id_store" => $idToko
							    );

			$this->modelTransferStok->insertStokTokoTujuan($dataInsert);
		}
	}

	function invoiceTransfer(){
		$noTransfer = $this->input->get("noTransfer");

		$data['header'] = $this->db->get("ap_receipt")->row();
		$data['infoTransfer'] = $this->modelTransferStok->infoTransfer($noTransfer);
		$data['itemTransfer'] = $this->modelTransferStok->itemTransferView($noTransfer);

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Transfer Barang";
		$this->loadViews("transfer_stok/bodyInvoiceTransfer",$this->global,$data,"footer_empty");
	}

	function formReceive(){
		$noTransfer = $this->input->get("noTransfer");

		$data['header'] = $this->db->get("ap_receipt")->row();
		$data['infoTransfer'] = $this->modelTransferStok->infoTransfer($noTransfer);
		$data['itemTransfer'] = $this->modelTransferStok->itemTransferView($noTransfer);

		$this->global['pageTitle'] = "SOLUSI POS - Terima Transfer Barang";
		$this->loadViews("transfer_stok/bodyReceiveTransfer",$this->global,$data,"footer_empty");
	}

	function updateQty(){
		$noTransfer = $_POST['noTransfer'];
		$qty = $_POST['qty'];
		$idProduk = $_POST['idProduk'];

		$data_update = array(
								"qty_rec"	=> $qty
							);

		$data['riwayatPenerimaan'] = $this->modelTransferStok->updateQty($noTransfer,$data_update,$idProduk);
		echo "Disimpan!";
	}

	function change_transfer_status(){
		$noTransfer 	 		= $_GET['noTransfer'];

		
		$this->db->query("UPDATE transferstokitem set qty_rec=qty where noTransfer='$noTransfer' and qty_rec='-1'");
			
		$infoTransfer = $this->modelTransferStok->infoTransfer($noTransfer);
		$data_update = array(
								"Accepted"	=> 1,
								"tanggal_terima" => date("Y-m-d H:i:s"),
								"id_penerima" => $this->global['idUser']
							);
		
		
		$itemTransfer = $this->modelTransferStok->itemTransferView($noTransfer);
		if ($infoTransfer->Accepted == 1) {
			// tidak usah diapa2in
		}else{
			foreach($itemTransfer as $row){
				$idProduk = $row->id_produk;
				$qty 	  = $row->qty_rec+0;
				$hpp	  = $row->hpp+0;
				$harga	  = $row->harga+0;

				$data_kartu[]     = array(
										"id_store"		=> $this->global['idStore'],
										"id_produk"		=> $idProduk,
										"qty"			=> $qty,
										"hpp"			=> $hpp,
										"harga"			=> $harga,
										"tanggal"		=> date('Y-m-d H:i:s'),
										"tipe"			=> 'Terima Transfer Stok',
										"no_transaksi"	=> $noTransfer,
										"id_pic"		=> $this->global['idUser']
									);

				//tambah stok pada tujuan toko baru
				$this->updateStokTokoTujuan($qty,$infoTransfer->transferTo,$idProduk);
				
			}

			if($data_kartu){
				$this->model1->insertKartuStok($data_kartu);
			}

			$this->modelTransferStok->changeTransferStatus($noTransfer,$data_update);
		}
		
		redirect("transferStok/formReceive?noTransfer=".$noTransfer);
	}

}