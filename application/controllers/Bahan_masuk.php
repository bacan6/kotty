<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Bahan_masuk extends BaseController{
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
		$data['store'] = $this->db->get("ap_store")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Barang Masuk";
		$this->loadViews("bahan_masuk/body_bahan_masuk",$this->global,$data,"bahan_masuk/footerBahanMasuk");
	}

	function ajax_produk(){
		$q 			= $_GET['term'];
		//$id_brand 	= $_SESSION['id_supplier'];

		$get_bahan_baku_select2 = $this->modelPurchaseOrder->produkAjax($q);

		$data_array = array();

		foreach($get_bahan_baku_select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}
	function insertReceiveItem(){
		// error_reporting(E_ALL);
		// ini_set('display_errors',1);
		$idProduk 	= $_POST['idProduk'];
		$nopo 		= $_POST['nopo'];
		// /$idUser 	= $this->global['idUser'];
		
		$info		= $this->modelPurchaseOrder->infoPurchase($nopo);

		$idStore = $info->id_toko;
		$tanggal = $info->tanggal_po;
		
		$hargaProduk = $this->modelPurchaseOrder->hargaBeliProduk($idProduk,$idStore);
		//cek on cart if exist
		$cekCart = $this->modelBahanMasukMaterial->cekPO($nopo, $idProduk);

		if($cekCart==0){
			$dataCart = array(
								"sku"			=> $idProduk,
								"qty"			=> 1,
								"no_po" 		=> $nopo,
								"tanggal" 		=> $tanggal,
								"harga"			=> $hargaProduk
						     );

			$this->db->insert("purchase_item",$dataCart);
			//echo 0;
			$data['idProduk'] = $idProduk;
			$data['nama_produk'] = $this->model1->nama_produk($idProduk);
			$data['harga'] = $hargaProduk;
			$data['hargaJual'] = $this->modelPurchaseOrder->hargaJualProduk($idProduk,$idStore);
			$this->load->view("bahan_masuk/addOnReceive",$data);
		} else {			
			echo 0;
		}
	}

	function POFilter(){
		$data['tanggalPO'] = $_POST['tanggalPO'];
		$data['tanggalKirim'] = $_POST['tanggalKirim'];
		$data['supplier'] = $_POST['supplier'];
		$data['store'] = $_POST['store'];
		$data['status'] = $_POST['status'];
		$data['status_receive'] = $_POST['status_receive'];
		$data['jenis'] = $_POST['jenis'];

		$this->load->view("bahan_masuk/POFilter",$data);
	}

	function datatablesPO(){
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
			$query = $this->modelBahanMasukMaterial->viewPOProdukReceive($length,$start,$search,$idUser,$this->global['idStore']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewPOProdukReceive($length,$start,$search,$idUser,$this->global['idStore']);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-danger">Waiting for MD</span></a>';
			} elseif($status==1){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-success">Approved</span></a>';
			} elseif($status==2){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-danger">Declined</span></a>';
			} elseif($status==3){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-info">Order Received</span></a>';
			}elseif($status==4){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-warning">Waiting for Supplier</span></a>';
			}elseif($status==9){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-danger">Expired</span></a>';
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
		$store = $_POST['store'];
		$status = $_POST['status'];
		$status_receive = $_POST['status_receive'];
		$jenis = $_POST['jenis'];

		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelBahanMasukMaterial->totalPOProdukFilter($status_receive,$tanggalPO,$tanggalKirim,$supplier,$store,$status,$jenis);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewPOProdukFilter($length,$start,$search,$status_receive,$tanggalPO,$tanggalKirim,$supplier,$store,$status,$jenis);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewPOProdukFilter($length,$start,$search,$status_receive,$tanggalPO,$tanggalKirim,$supplier,$store,$status,$jenis);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-danger">Waiting for MD</span></a>';
			} elseif($status==1){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-success">Approved</span></a>';
			} elseif($status==2){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-danger">Declined</span></a>';
			} elseif($status==3){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-info">Order Received</span></a>';
			} elseif($status==9){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-danger">Expired</span></a>';
			}

			$output['data'][]=array($nomor_urut,"<a href='#'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['tanggal_kirim'],$dt['supplier'],$dt['first_name'],$button);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function importReceiveItem(){
		$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
		$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
		$data['navigation'] = $this->model1->callNavigation();
		$data['pageTitle'] = "SOLUSI POS - Import Received Item";

		$data['po'] = $this->modelBahanMasukMaterial->viewPOImportPDT($this->global['idUser'],$this->global['idStore']);
		$this->load->view("navigation",$data);
		$this->load->view("bahan_masuk/import_receive_item");
		$this->load->view("bahan_masuk/footer_import");
	}

	function templateReceiveItem(){
		 // ini_set('display_errors', 1);
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();
		

		$objPHPExcel->getActiveSheet()->setCellValueExplicit('A1','SKU',PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('B1','Jumlah');

		

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("SOLUSI POS | IT Solutions")
								->setSubject("SOLUSI POS | IT Solutions")
								->setDescription("Export Data")
								->setKeywords("office 2007 openxml php")
								->setCategory("Data SO Item");
	 
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
	    header('Content-Disposition: attachment;filename=Receiving_PDT.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function importReceiveitemSQL(){
		$config['upload_path']          = './assets/temp/';
		$config['allowed_types']        = 'xls|xlsx';

		$no_po = $_POST['no_po'];

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
					$id_produk= $row['A'];
					$qty = $row['B'];


					$dataInsert[] = array(
											"sku" => $id_produk,
											"qty" => $qty,
											"no_po" => $no_po
									     );
				}
			$i++; }

			$this->modelBahanMasukMaterial->hapusCartImportPDT($no_po);
			$this->db->insert_batch("cc_cart_receive_item", $dataInsert);
			unlink($file);
		}
		
	}

	function change_po_status(){
		$status 		= $_GET['status'];
		$no_po 	 		= $_GET['no_po'];

		$data_update = array(
								"status"	=> $status
							);

		$this->modelBahanMasukMaterial->changePOStatus($no_po,$data_update);
		redirect("bahan_masuk/good_receipt?no_po=".$no_po);
	}

	function updateQtyCart(){
		$sku = $_POST['sku'];
		$qty = $_POST['qty'];
		$hpp = $_POST['hpp'];
		$no_po = $_POST['no_po'];

		$cek = $this->modelBahanMasukMaterial->cekCart($sku,$no_po);
		if($cek){
			$dataUpdate = array(
								"qty"		=> $qty,
								"hpp"		=> $hpp
					       );
		
			$this->db->where("sku",$sku);
			$this->db->where('no_po',$no_po);
			$this->db->update("cc_cart_receive_item",$dataUpdate);
		}else{
			$dataInsert = array(
								"qty"		=> $qty,
								"hpp"		=> $hpp,
								"no_po"		=> $no_po,
								"sku"		=> $sku
						       );
			$this->db->insert("cc_cart_receive_item",$dataInsert);
		}
		
	}

	function proses_receive_item(){
		error_reporting(0);ini_set("display_errors",0);
		$id_user  		= sprintf("%03d",$this->global['idUser']);
		$received_by 	= $_POST['diterimaOleh'];
		$checked_by 	= $_POST['diperiksaOleh'];
		$tanggal_terima = $_POST['tanggalTerima'];
		$no_po 			= $_POST['noPo'];
		$PPN 			= $_POST['PPN'];
		$id_supplier	= $_POST['idSupplier'];
		$diterimaDi 	= $_POST['diterimaDi'];
		$status_receive = isset($_POST['status_receive'])?$_POST['status_receive']:'Done';
		$diskon 		= $_POST['diskon'];
		$keterangan_receive 		= isset($_POST['keterangan_receive'])?$_POST['keterangan_receive']:'';

		$cek_terima 	= $this->model1->cek_tanggal_receive($tanggal_terima);

		$create_date  	= date_create($tanggal_terima);
		$convert_date 	= date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

		$no_inv = 'RCV'.$convert_date.$id_user.sprintf("%03d",$cek_terima+1);


		$data_receive = array(
								"no_receive"		=> $no_inv,
								"no_po"				=> $no_po,
								"received_by"		=> $received_by,
								"checked_by"		=> $checked_by,
								"tanggal_terima"	=> $tanggal_terima,
								"id_pic"			=> $this->global['idUser'],
								"id_supplier"		=> $id_supplier,
								"diterimaDi" 		=> $diterimaDi,
								"status_receive" 	=> $status_receive,
								"keterangan_receive" => $keterangan_receive,
								"diskon" 			=> $diskon
							);

		if($this->modelBahanMasukMaterial->insertReceiveOrder($data_receive)){
			$itemProduk = $_POST['produkItem'];
		}else{
			$itemProduk = '';
		}
		$decodeJSON = json_decode(stripcslashes($itemProduk));

		foreach($decodeJSON as $row){
			$sku 	= $row->sku;
			$qty 	= $row->qty;
			$price 	= $row->harga;
			$hargajual 	= $row->hargajual;
			$bonus 	= $row->bonus;
			$diskon1 	= $row->diskon1;
			$diskon2 	= $row->diskon2;
			$diskon3 	= $row->diskon3;

			$modal = $price-(($diskon1/100)*$price)-(($diskon2/100)*($price-(($diskon1/100)*$price)))-$diskon3;
			if ($PPN=='1') $modal = $modal+(0.11*$modal);

			$data_insert[] = array(
									"no_receive" 	=> $no_inv,
									"sku"			=> $sku,
									"qty"			=> $qty,
									"price"			=> $price,
									"bonus"			=> $bonus,
									"diskon1"		=> $diskon1,
									"diskon2"		=> $diskon2,
									"diskon3"		=> $diskon3,
									"ppn"			=> $PPN,
									"tanggal"		=> $tanggal_terima
								);
			$data_kartu[] = array(
									"id_store"		=> $diterimaDi,
									"id_produk"		=> $sku,
									"qty"			=> $qty+$bonus+0,
									"hpp"			=> 0,
									"tanggal"		=> date('Y-m-d H:i:s'),
									"tipe"			=> 'Receive Item',
									"no_transaksi"	=> $no_inv,
									"id_pic"		=> $this->global['idUser']
								);
			if ($PPN=='1') $price = $price+(0.11*$price);

			$dataHarga[] = array(
										"hpp" 	=> $modal,
										"harga" => $hargajual,
										"id_produk" => $sku
									);
			if($diterimaDi < 1){
				//stok lama produk
				$stok_lama_produk = $this->model1->get_stok_lama_produk($sku);
				$data_stok = array(
									"stok"	=> $stok_lama_produk+$qty+$bonus
								  );

				$this->modelBahanMasukMaterial->penerimaanGudang($sku,$data_stok);
			} else {
				$stok_barang = $this->model1->cek_stok_new($sku,$diterimaDi);
				$ada_barang = $this->model1->cek_stok_toko($sku,$diterimaDi);
				

				if($ada_barang > 0){
					//dapatkan stok lama barang di virtual warehouse
					$newStokToko = $stok_barang['stok'] + $qty + $bonus;
					$updateDataStok[] = array(
										"stok" 	=> $newStokToko,
										"id_produk" => $sku,
										"last_received" => $tanggal_terima.' | '.$no_inv . ' | qty:' .$qty. ' | aft:' .$newStokToko . ' | bef:' .$stok_barang['stok']
									);
					$dataUpdate = array(
										"stok" 	=> $newStokToko,
										"last_received" => $tanggal_terima.' | '.$no_inv . ' | qty:' .$qty. ' | aft:' .$newStokToko . ' | bef:' .$stok_barang['stok']
									);
					$this->modelBahanMasukMaterial->updateStok($diterimaDi,$dataUpdate,$sku);
				} else {
					//sisipkan barang baru
					$insertDataStok[] = array(
										"id_produk" 	=> $sku,
										"id_store"		=> $diterimaDi,
										"stok"			=> $qty + $bonus,
										"last_received" => $tanggal_terima.' | '.$no_inv . ' | ' .$qty
									);
				}
			}
		}

		$this->modelBahanMasukMaterial->insertBatchReceiveItem($data_insert);

		if($data_kartu){
			$this->model1->insertKartuStok($data_kartu);
		}
		
		if ($insertDataStok){
			$this->modelBahanMasukMaterial->insertBatchDataStok($insertDataStok);
		}
		if ($updateDataStok){
			//$this->modelBahanMasukMaterial->updateBatchDataStok($updateDataStok,$diterimaDi);
		}
		if ($dataHarga){
			$this->modelBahanMasukMaterial->updateBatchHarga($dataHarga,$diterimaDi);
			$this->modelBahanMasukMaterial->updateBatchHargaStok($dataHarga,$diterimaDi);
		}
		//PROSES PENERBITAN HUTANG
		//SET INSERT HUTANG 

		//cek if exist

		$cek_penerbitan_hutang = $this->model1->cek_penerbitan_hutang($no_po);

		if($cek_penerbitan_hutang < 1){
			$data_tagihan = array(
									"no_tagihan"		=> $no_po,
									"status_hutang" 	=> 0
								 );

			$this->modelBahanMasukMaterial->terbitkanStatusHutang($data_tagihan);
		}
		if($status_receive!='Done'){
			$data_update = array(
				"status_receive" 	=> $status_receive
			 );
	
			$this->modelBahanMasukMaterial->changePOStatus($no_po,$data_update);
		}else{
			$data_update = array(
				"status" 	=> 3
				);
	
			$this->modelBahanMasukMaterial->changePOStatus($no_po,$data_update);
		}
		

		echo $no_inv;
		//redirect("bahan_masuk/invoice_receive?no_receive=".$no_inv."&id_supplier=".$id_supplier."&no_po=".$no_po."&arrival_date=".$tanggal_terima."&received_by=".$received_by."&checked_by=".$checked_by);
	}

	function good_receipt(){
		$no_po 	= $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelBahanMasukMaterial->purchase_item($no_po);
		$data['received_invoice'] = $this->model1->received_invoice($no_po);
		//$this->db->where("id_store",$this->global['idStore']);
		$data['store'] = $this->db->get("ap_store")->result();
		$data['noteInfo'] = $this->modelBahanMasukMaterial->noteInfoPO($no_po);
		$this->global['pageTitle'] = "SOLUSI POS - Goods Receipt";
		$this->loadViews("bahan_masuk/body_good_receipt",$this->global,$data,"bahan_masuk/footerBarangMasuk");
	}

	function invoice_receive(){
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);
		$data['receive_item'] = $this->modelBahanMasukMaterial->received_item($no_receive);

		if ($data['receive_item']->num_rows() > 0) {
			// tidak usah diapakan
		} else {
			$this->db->query("INSERT INTO `receive_item` (`no_receive`, `sku`, `qty`,  `tanggal`) 
							select no_transaksi,id_produk,qty,tanggal 
							from stok_store_kartu 
							where no_transaksi='$no_receive'");
			$this->db->query("UPDATE receive_item,stok_store 
					set receive_item.price=stok_store.hpp 
					where stok_store.last_received like '%$no_receive%' 
						and receive_item.no_receive='$no_receive' and 
							stok_store.id_produk=receive_item.sku");
			$data['receive_item'] = $this->modelBahanMasukMaterial->received_item($no_receive);
		}


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
		$this->load->view("bahan_masuk/detailOrder",$data);
	}

	function invoiceReceive(){
		$no_po 	= $_POST['noPo'];
		$data['received_invoice'] = $this->model1->received_invoice($no_po);
		$this->load->view("bahan_masuk/invoiceReceive",$data);
	}

	function riwayatPenerimaan(){
		$noPo = $_POST['noPo'];
		$data['riwayatPenerimaan'] = $this->model1->riwayatPenerimaan($noPo);
		$this->load->view("bahan_masuk/riwayatPenerimaan",$data);
	}

	function qtyReceived(){
		$idProduk 	= $_POST['idProduk'];
		$noPo 		= $_POST['noPo'];

		$qtyReceived = $this->model1->qtyDiterima($idProduk,$noPo);
		echo $qtyReceived;
	}
}