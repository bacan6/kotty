<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Purchase_order extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library(array('session','encryption'));
		$this->load->model(array("model1","modelPurchaseOrder"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],2,9);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Purchase Order";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data['brand'] = $this->db->get("brand");
		
		$this->db->order_by("supplier.supplier");
		$data['supplier'] = $this->db->get("supplier");
		$data['store'] = $this->db->get("ap_store");
		// $this->db->select("supplier.*");
		// $this->db->from("ap_produk_supplier");
		// $this->db->join("supplier","supplier.id_supplier=ap_produk_supplier.id_supplier");
		// $this->db->where("ap_produk_supplier.id_toko",$this->global['idStore']);
		// $this->db->group_by("supplier.id_supplier");
		// $data['supplier'] = $this->db->get();
		//$data['supplier'] = $this->db->get_where("ap_produk_supplier left join supplier on ",array("ap_produk_supplier.id_toko" => ));
		$this->loadViews("purchase_order/bodyPurchaseOrder",$this->global,$data,"bahan_masuk/footer_barang_masuk");
	}

	function importPurchaseItem(){
		$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
		$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
		$data['navigation'] = $this->model1->callNavigation();
		$data['pageTitle'] = "Solusinformatika.com - Import Purchase Item";
		$this->load->view("navigation",$data);
		$this->load->view("purchase_order/import_purchase_item");
		$this->load->view("purchase_order/footer_import");
	}

	function templatePurchaseItem(){
		 // ini_set('display_errors', 1);
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','HPP')
									  ->setCellValue('D1','Qty');

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusinformatika.com | IT Solutions")
								->setSubject("Solusinformatika.com | IT Solutions")
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
	    header('Content-Disposition: attachment;filename=Purchase Item Template.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function importPOitemSQL(){
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
					$hpp = empty($row['C'])? $this->modelPurchaseOrder->hargaBeliProduk($id_produk,$this->global['idStore']):$row['C'];
					$qty = $row['D'];

					$dataInsert[] = array(
											"idProduk" => $id_produk,
											"harga" => $hpp,
											"qty" => $qty,
											"idUser" => $this->global['idUser']
									     );
				}
			$i++; }

			$this->modelPurchaseOrder->hapusCartPO($this->global['idUser']);
			$this->db->insert_batch("cc_cartpurchaseorder", $dataInsert);
			unlink($file);
		}
		
	}

	function export_excel_cartpo(){
		// ini_set('display_errors', 1);
	   $this->load->library("excel/PHPExcel");

	   $objPHPExcel = new PHPExcel();

	   $objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									 ->setCellValue('B1','SKU')
									 ->setCellValue('C1','HPP')
									 ->setCellValue('D1','Qty');

		$cart = $this->modelPurchaseOrder->viewCartPO($this->global['idUser'],$this->global['idStore']);

		$no = 2;
		foreach($cart->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no,$no-1)
									 ->setCellValueExplicit('B'.$no,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									 ->setCellValue('C'.$no,$row->harga)
									 ->setCellValue('D'.$no,$row->qty);
		
		$no++;
		}
	   
	   //set title pada sheet (me rename nama sheet)
		 $objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	   // Set document properties
	   $objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
							   ->setLastModifiedBy("Arisal Yanuarafi")
							   ->setTitle("Solusinformatika.com | IT Solutions")
							   ->setSubject("Solusinformatika.com | IT Solutions")
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
	   header('Content-Disposition: attachment;filename=Cart PO Export.xlsx');
	   //unduh file
	   $objWriter->save("php://output");
   }
   function export_excel_po(){
	   $no_po = $_GET['no_po'];
		// ini_set('display_errors', 1);
	   $this->load->library("excel/PHPExcel");

	   $objPHPExcel = new PHPExcel();

	   $objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									 ->setCellValue('B1','SKU')
									 ->setCellValue('C1','HPP')
									 ->setCellValue('D1','Qty');

		$cart = $this->modelPurchaseOrder->purchase_item($no_po);

		$no = 2;
		foreach($cart->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no,$no-1)
									 ->setCellValueExplicit('B'.$no,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									 ->setCellValue('C'.$no,$row->harga)
									 ->setCellValue('D'.$no,$row->qty);
		
		$no++;
		}
	   
	   //set title pada sheet (me rename nama sheet)
		 $objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	   // Set document properties
	   $objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
							   ->setLastModifiedBy("Arisal Yanuarafi")
							   ->setTitle("Solusinformatika.com | IT Solutions")
							   ->setSubject("Solusinformatika.com | IT Solutions")
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
	   header('Content-Disposition: attachment;filename=PO Export.xlsx');
	   //unduh file
	   $objWriter->save("php://output");
   }

	function ajax_produk(){
		$q 			= $_GET['term'];
		$id_brand	= (isset($_SESSION['id_brand']) && !empty($_SESSION['id_brand']))?$_SESSION['id_brand']:'';
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

	function ajax_produk_supplier(){
		$q 				= $_POST['brand'];
		$id_toko 		= $_POST['id_toko'];
		//$idToko = $this->global['idStore'];
		$idUser = $this->global['idUser'];
		$del = $this->modelPurchaseOrder->deleteSelectedCartPO($idUser);
		$_SESSION['id_brand']=$q;

		$getStok = $this->modelPurchaseOrder->produkAjaxSupplier2($q,$id_toko);

		$data_array = array();$n=0;

		foreach($getStok->result() as $row){
			$dataCart = array(
								"idProduk"		=> trim(preg_replace('/^[\p{Z}\s]+|[\p{Z}\s]+$/u', '', $row->id_produk)),
								"qty"			=> $row->pesan,
								"idUser" 		=> $idUser,
								"harga"			=> $row->hpp
						     );

			$this->modelPurchaseOrder->insertCartPO($dataCart);
			$n++;
		}

		echo json_encode($n);
	}

	function insertPO(){
		$id_user  		= sprintf("%03d",$this->global['idUser']);
		$tanggal_po 	= date('Y-m-d');
		$tanggal_kirim 	= $_POST['tanggalKirim'];
		$jatuh_tempo 	= $_POST['jatuhTempo'];
		$keterangan 	= $_POST['keterangan'];
		$supplier  		= $_POST['supplier'];
		$alamat 		= $_POST['alamatPengiriman'];
		$id_toko 		= $_POST['id_toko'];

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
                                "id_toko"			=> $id_toko,
								"status"			=> 0
							);
		
		$this->modelPurchaseOrder->insertPONumber($data_masuk);

		$viewDataPO = $this->modelPurchaseOrder->viewCartPO($this->global['idUser'],$id_toko);

		foreach($viewDataPO->result() as $row){
			$sku 			= $row->id_produk;
			$jumlah_beli	= $row->qty;
			$bonus			= $row->bonus;
			$harga 			= $row->harga;

			if ($jumlah_beli>0){
				$data_bahan[]     = array(
										"no_po"			=> $no_inv,
										"sku"			=> $sku,
										"qty_req"		=> $jumlah_beli,
										"qty"			=> '-1',
										"qty_confirmed" => '-1',
										"harga"			=> $harga,
										"bonus"			=> $bonus,
										"tanggal"		=> $tanggal_po
								   );
			}
			
		}

		$this->modelPurchaseOrder->insertPOItem($data_bahan);
		$this->modelPurchaseOrder->deleteCartPO($this->global['idUser']);
		echo $no_inv;

		//redirect("purchase_order/form_po?no_po=".$no_inv);
	}

	function daftar_po(){
		$this->global['pageTitle'] = "SOLUSI POS - Daftar PO";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("purchase_order/body_daftar_po",$this->global,NULL,"purchase_order/footerDaftarPO");
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}
	function form_po_checker(){
		$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelPurchaseOrder->purchase_item($no_po);
		$info_po = $this->modelPurchaseOrder->infoPurchase($no_po);

		$data['tanggal_po'] 		= $info_po->tanggal_po;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['supplier'] 			= $info_po->supplier;
		$data['alamat_sp'] 			= $info_po->alamat;
		$data['kontak_sp'] 			= $info_po->kontak;
		$data['ppn']				= $info_po->ppn;
		$data['nilai_ppn']			= $info_po->nilai_ppn;
		$data['alamat_pengiriman'] 	= $info_po->alamat_pengiriman;
		$data['tanggal_kirim']		= $info_po->tanggal_kirim;
		$data['idSupplier'] 		= $info_po->id_supplier;

		$this->global['pageTitle'] = "SOLUSI POS - Form Purchase Request";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("bahan_masuk/body_form_po_checker",$this->global,$data,"bahan_masuk/footer_barang_masuk");
	}


	function form_po(){
		$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelPurchaseOrder->purchase_item($no_po);
		$info_po = $this->modelPurchaseOrder->infoPurchase($no_po);

		$data['tanggal_po'] 		= $info_po->tanggal_po;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['supplier'] 			= $info_po->supplier;
		$data['alamat_sp'] 			= $info_po->alamat;
		$data['kontak_sp'] 			= $info_po->kontak;
		$data['ppn']				= $info_po->ppn;
		$data['nilai_ppn']			= $info_po->nilai_ppn;
		$data['alamat_pengiriman'] 	= $info_po->alamat_pengiriman;
		$data['tanggal_kirim']		= $info_po->tanggal_kirim;
		$data['idSupplier'] 		= $info_po->id_supplier;
		$data['status']				= $info_po->status;
		$data['pic']				= $info_po->first_name;
		$data['store']				= $info_po->store;

		$this->global['pageTitle'] = "SOLUSI POS - Form Purchase Order";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("bahan_masuk/body_form_po",$this->global,$data,"bahan_masuk/footer_barang_masuk");
	}

	function datatablesPO(){
		$this->load->model("modelBahanMasukMaterial");
        $idUser     = $this->global['idUser'];
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

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
			$status = $dt['status'];

			if($status==0){
				$button = '<span class="label label-danger">Waiting for MD</span>';
			} elseif($status==1){
				$button = '<span class="label label-info">Approved</span>';
			} elseif($status==2){
				$button = '<span class="label label-danger">Declined</span>';
			} elseif($status==3){
				$button = '<span class="label label-success">Order Received</span>';
			} elseif($status==4){
				$button = '<span class="label label-warning">Waiting for Supplier</span>';
			}elseif($status==9){
				$button = '<span class="label label-danger">Expired</span>';
			}

			$output['data'][]=array($nomor_urut,"<a href='".base_url('purchase_order/form_po?no_po='.$dt['no_po'])."'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['tanggal_kirim'],$dt['brand'],$dt['supplier'],$dt['store'],$dt['first_name'],$button);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_receive(){
		$this->load->view("navigation");
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);
		$data['receive_item'] = $this->modelPurchaseOrder->received_item($no_receive);
		$this->load->view("body_invoice_receive",$data);
		$this->load->view("footer");
	}	

	function sendEmailPOSupplier(){
		$no_po 	= $_POST['noPo'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelPurchaseOrder->purchase_item($no_po);
		$info_po = $this->modelPurchaseOrder->infoPurchase($no_po);
		$data['noPO'] = $no_po;

		$data['tanggal_po'] 		= $info_po->tanggal_po;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['supplier'] 			= $info_po->supplier;
		$data['alamat_sp'] 			= $info_po->alamat;
		$data['kontak_sp'] 			= $info_po->kontak;
		$data['ppn']				= $info_po->ppn;
		$data['nilai_ppn']			= $info_po->nilai_ppn;
		$data['alamat_pengiriman'] 	= $info_po->alamat_pengiriman;
		$data['tanggal_kirim']		= $info_po->tanggal_kirim;
		

		$mesg = $this->load->view("emailFormPO",$data,TRUE);

		$this->load->library("email");

		//get data email
		$dataEmail  = $this->db->get("settingemail")->row();

		$SMTPHost 	= $dataEmail->SMTPHost;
		$SMTPPort 	= $dataEmail->SMTPPort;
		$SMTPUser 	= $dataEmail->SMTPUser;
		$SMTPPass 	= $dataEmail->SMTPPas;
		$SenderName = $dataEmail->UserName;
		

		$config['protocol'] = 'smtp';
		$config['smtp_host'] = $SMTPHost;
		$config['smtp_port'] = $SMTPPort;
		$config['smtp_user'] = $SMTPUser;
		$config['smtp_pass'] = $SMTPPass;
		$config['mailtype'] = 'html';
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['newline'] = "\r\n";
		$config['smtp_crypto'] = 'tls';
		
		$config['crlf'] = "\r\n";
		
		//get email supplier
		$email = $this->modelPurchaseOrder->emailSupplier($_POST['idSupplier']);	

		$this->email->initialize($config);

		$this->email->from($SMTPUser, $SenderName);
		$this->email->to($email);

		$this->email->subject('Purchase Order | '.$no_po);
		$this->email->message($mesg);

		if($this->email->send()){
			echo "1";
		} else {
			echo "0";
			show_error($this->email->print_debugger());
		}
	}

	function cekEmailSupplier(){
		$idSupplier = $_POST['idSupplier'];

		//cek email
		$cekEmailIfExist = $this->modelPurchaseOrder->cekEmailIfExist($idSupplier);

		if($cekEmailIfExist == 1){
			echo 1;
		} else {
			echo 0;
		}
	}

	function insertCartPO(){
		$idProduk 		= $_POST['idProduk'];
		$id_toko 		= $_POST['id_toko'];
		$idUser = $this->global['idUser'];
		//$idStore=empty($this->global['idStore'])?12:$this->global['idStore'];
		$hargaProduk = $this->modelPurchaseOrder->hargaBeliProduk($idProduk,$id_toko);

		//cek on cart if exist
		$cekCart = $this->modelPurchaseOrder->cekCartPO($idProduk,$idUser);

		if($cekCart < 1){
			$dataCart = array(
								"idProduk"		=> trim(preg_replace('/^[\p{Z}\s]+|[\p{Z}\s]+$/u', '', $idProduk)),
								"qty"			=> 1,
								"idUser" 		=> $idUser,
								"harga"			=> $hargaProduk
						     );

			$this->modelPurchaseOrder->insertCartPO($dataCart);
			echo 0;
		} else {			
			$id = $this->modelPurchaseOrder->getIdCart($idProduk,$idUser);
			echo $id;
		}
	}

	function cartPO(){
		$idUser = $this->global['idUser'];
		// DISINI TAMBAH SCRIPT
		// Ambil data iduser dan supplier
		$data['idStore'] = isset($_POST['id_toko'])?$_POST['id_toko']:'';
		$data['viewCartPO'] = $this->modelPurchaseOrder->viewCartPO($idUser,$data['idStore']);
		$this->load->view("purchase_order/cartPO",$data);
	}

	function updateQtyCart(){
		$id = $_POST['id'];
		$idUser   = $this->global['idUser'];
		$qty = $_POST['qty'];

		$dataUpdate = array(
								"qty"		=> $qty
					       );
		
		$this->modelPurchaseOrder->updateQtyCart($id,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelPurchaseOrder->totalPeritem($idUser,$id);

		echo number_format($totalPeritem,'0',',','.');
	}
	function updateBonusCart(){
		$id = $_POST['id'];
		$idUser   = $this->global['idUser'];
		$bonus = $_POST['bonus'];

		$dataUpdate = array(
								"bonus"		=> $bonus
					       );
		
		$this->modelPurchaseOrder->updateQtyCart($id,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelPurchaseOrder->totalPeritem($idUser,$id);

		echo number_format($totalPeritem,'0',',','.');
	}

	function totalCart(){
		$idUser   = $this->global['idUser'];
		$totalCart = $this->modelPurchaseOrder->totalCartPeruser($idUser);

		if($totalCart){
			echo number_format($totalCart,'0',',','.');
		} else {
			echo 0;
		}
	}

	function updateHargaCart(){
		$id = $_POST['id'];
		$idUser   = $this->global['idUser'];
		$harga = $_POST['harga'];

		$dataUpdate = array(
								"harga"		=> $harga
					       );

		$this->modelPurchaseOrder->updateHargaCart($id,$idUser,$dataUpdate);
	
		//get total peritem
		$totalPeritem = $this->modelPurchaseOrder->totalPeritem($idUser,$id);

		echo number_format($totalPeritem,'0',',','.');
	}

	function hapusCart(){
		$id 	= $_POST['id'];
		$idUser = $this->global['idUser'];

		$this->modelPurchaseOrder->hapusCart($id,$idUser);
	}

}