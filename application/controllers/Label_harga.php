<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Label_harga extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library(array('session','encryption'));
		$this->load->model(array("model1","modelLabelHarga","modelProduk"));
		$this->load->database();

		//error_reporting(E_ALL);ini_set('display_errors', true);
		
		$this->isLoggedIn($this->global['idUser'],2,50);
	}

	function index(){
		$this->global['pageTitle'] = "Solusi POS - Label Harga";
		$this->global['navigation'] = $this->model1->callNavigation();
        $data['supplier'] = $this->db->get("supplier");
		$this->loadViews("label_harga/bodyLabelHarga",$this->global,$data,"label_harga/footer_label_harga");
	}

	function importLabel(){
		$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
		$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
		$data['navigation'] = $this->model1->callNavigation();
		$data['pageTitle'] = "Solusi POS - Import Purchase Item";
		$this->load->view("navigation",$data);
		$this->load->view("label_harga/import_label");
		$this->load->view("label_harga/footer_import");
	}
	function templateLabel(){
		 // ini_set('display_errors', 1);
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU');

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusi POS | IT Solutions")
								->setSubject("Solusi POS | IT Solutions")
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

	function importLabelSQL(){
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
					$hargaProduk = $this->modelLabelHarga->hargaBeliProduk($id_produk,$this->global['idStore']);

					$dataCart = array(
								"idProduk"		=> $id_produk,
								"harga"			=> $hargaProduk,
								"idUser" 		=> $this->global['idUser']
						     );

					$this->modelLabelHarga->insertCartPO($dataCart);
				}
			$i++; }

			unlink($file);
		}
		
	}

	function ajax_produk(){
		$q 			= $_GET['term'];

		$get_bahan_baku_select2 = $this->modelLabelHarga->produkAjax($q);

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
		$q 			= $_POST['supplier'];
		$idToko = $this->model1->getIdStore($this->global['idUser']);
		$idUser = $this->global['idUser'];
		$del = $this->modelLabelHarga->deleteCartPO($idUser);

		$getStok = $this->modelLabelHarga->produkAjaxSupplier($q,$idToko);

		$data_array = array();$n=0;

		foreach($getStok->result() as $row){
			$dataCart = array(
								"idProduk"		=> $row->id_produk,
								"qty"			=> $row->pesan,
								"idUser" 		=> $idUser,
								"harga"			=> $row->hpp
						     );

			$this->modelLabelHarga->insertCartPO($dataCart);
			$n++;
		}

		echo json_encode($n);
	}

	function insertPO(){
		$id_user  		= sprintf("%03d",$this->global['idUser']);
        

		$no_inv = 'LH'.$id_user.date('YmdHis');

		$data_masuk = array(
								"no_po" 			=> $no_inv,
                                "tanggal_po"        => date('Y-m-d'),
								"id_pic"			=> $this->global['idUser'],
                                "id_toko"			=> $this->global['idStore'],
								"status"			=> 0
							);
		
		$this->modelLabelHarga->insertPONumber($data_masuk);

		$viewDataPO = $this->modelLabelHarga->viewCartPO($this->global['idUser']);

		foreach($viewDataPO->result() as $row){
			$sku 			= $row->id_produk;
			$jumlah_beli	= $row->qty;
			$harga 			= $row->harga;

			$data_bahan[]     = array(
										"no_po"			=> $no_inv,
										"sku"			=> $sku,
										"qty"			=> $jumlah_beli,
										"harga"			=> $harga,
										"tanggal"		=> date('Y-m-d')
								   );
		}

		$this->modelLabelHarga->insertPOItem($data_bahan);
		$this->modelLabelHarga->deleteCartPO($this->global['idUser']);
		echo $no_inv;

		//redirect("purchase_order/form_po?no_po=".$no_inv);
	}

	function daftar_po(){
		$this->global['pageTitle'] = "Solusi POS - Daftar Label";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("label_harga/body_daftar_lh",$this->global,NULL,"label_harga/footerDaftarLH");
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function form_po(){
		$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['label_item'] = $this->modelLabelHarga->label_item($no_po,$this->global['idStore']);
		$info_po = $this->modelLabelHarga->infoPurchase($no_po);


		$this->global['pageTitle'] = "Solusi POS - Form Label Harga";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("label_harga/body_form_lh_small",$this->global,$data,"bahan_masuk/footer_barang_masuk");
	}
	function label_kecil(){
		$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['label_item'] = $this->modelLabelHarga->label_item($no_po,$this->global['idStore']);
		$info_po = $this->modelLabelHarga->infoPurchase($no_po);


		$this->global['pageTitle'] = "Solusi POS - Label Harga";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("label_harga/body_form_lh_small",$this->global,$data,"bahan_masuk/footer_barang_masuk");
	}
	function label_standar(){
		$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['label_item'] = $this->modelLabelHarga->label_item($no_po,$this->global['idStore']);
		$info_po = $this->modelLabelHarga->infoPurchase($no_po);


		$this->global['pageTitle'] = "Solusi POS - Label Harga";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("label_harga/body_form_lh",$this->global,$data,"bahan_masuk/footer_barang_masuk");
	}

	function datatablesPO(){
		$this->load->model("modelBahanMasukMaterial");
        $idUser     = $this->global['idUser'];
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelBahanMasukMaterial->totalLHProduk();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewLHProduk($length,$start,$search,$idUser,$this->global['idStore']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewLHProduk($length,$start,$search,$idUser,$this->global['idStore']);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$button = "<a class='btn btn-info btn-xs' href='".base_url('label_harga/label_standar?no_po='.$dt['no_po'])."'>Label Biasa</a> <a class='btn btn-warning btn-xs' href='".base_url('label_harga/label_kecil?no_po='.$dt['no_po'])."'>Label Kecil</a>";

			$output['data'][]=array($nomor_urut,"<a href='".base_url('label_harga/label_standar?no_po='.$dt['no_po'])."'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['first_name'],$button);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_receive(){
		$this->load->view("navigation");
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);
		$data['receive_item'] = $this->modelLabelHarga->received_item($no_receive);
		$this->load->view("body_invoice_receive",$data);
		$this->load->view("footer");
	}	

	function sendEmailPOSupplier(){
		$no_po 	= $_POST['noPo'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['label_item'] = $this->modelLabelHarga->label_item($no_po);
		$info_po = $this->modelLabelHarga->infoPurchase($no_po);
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
		$config['crlf'] = "\r\n";
		
		//get email supplier
		$email = $this->modelLabelHarga->emailSupplier($_POST['idSupplier']);	

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
		$cekEmailIfExist = $this->modelLabelHarga->cekEmailIfExist($idSupplier);

		if($cekEmailIfExist == 1){
			echo 1;
		} else {
			echo 0;
		}
	}

	function insertCartPO(){
		$idProduk 		= $_POST['idProduk'];
		$idUser = $this->global['idUser'];
        $idStore= $this->global['idStore'];
		$hargaProduk = $this->modelLabelHarga->hargaBeliProduk($idProduk,$idStore);

		//cek on cart if exist
		$cekCart = $this->modelLabelHarga->cekCartPO($idProduk,$idUser);

		if($cekCart < 1){
			$dataCart = array(
								"idProduk"		=> $idProduk,
								"qty"			=> 1,
								"idUser" 		=> $idUser,
								"harga"			=> $hargaProduk
						     );

			$this->modelLabelHarga->insertCartPO($dataCart);
			echo 0;
		} else {			
			$id = $this->modelLabelHarga->getIdCart($idProduk,$idUser);
			echo $id;
		}
	}

	function cartLH(){
		$idUser = $this->global['idUser'];
		// DISINI TAMBAH SCRIPT
		// Ambil data iduser dan supplier
		$data['viewCartPO'] = $this->modelLabelHarga->viewCartPO($idUser);
		$this->load->view("label_harga/cartLH",$data);
	}

	function updateQtyCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$qty = $_POST['qty'];

		$dataUpdate = array(
								"qty"		=> $qty
					       );
		
		$this->modelLabelHarga->updateQtyCart($idProduk,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelLabelHarga->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}

	function totalCart(){
		$idUser   = $this->global['idUser'];
		$totalCart = $this->modelLabelHarga->totalCartPeruser($idUser);

		if($totalCart){
			echo number_format($totalCart,'0',',','.');
		} else {
			echo 0;
		}
	}

	function updateHargaCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$harga = $_POST['harga'];

		$dataUpdate = array(
								"harga"		=> $harga
					       );

		$this->modelLabelHarga->updateHargaCart($idProduk,$idUser,$dataUpdate);
	
		//get total peritem
		$totalPeritem = $this->modelLabelHarga->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}

	function hapusCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$this->modelLabelHarga->hapusCart($idProduk,$idUser);
	}

}