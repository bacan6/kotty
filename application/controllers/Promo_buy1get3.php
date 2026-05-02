<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class promo_buy1get3 extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library(array('session','encryption'));
		$this->load->model(array("model1","modelPromoBuy1Get3"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],8,54);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Promo Buy 1 Get 3";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data = array();
		if($this->global['isSuperadmin']==1){
			$data['store'] = $this->db->get("ap_store")->result();
		}else $data['store'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
		//$data='';
		$this->loadViews("promo_buy1get3/body",$this->global,$data,"promo_buy1get3/footer");
	}
	function importPromo(){
		$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
		$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
		$data['navigation'] = $this->model1->callNavigation();
		$data['pageTitle'] = "Solusi POS - Import Promo Item";
		$this->load->view("navigation",$data);
		$this->load->view("promo_buy1get3/import_produk");
		$this->load->view("promo_buy1get3/footer_import");
	}
	function templateProduk(){
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
	    header('Content-Disposition: attachment;filename=Item Template.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function importSQL(){
		$config['upload_path']          = './assets/temp/';
		$config['allowed_types']        = 'xls|xlsx';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file')){
			$error = array('error' => $this->upload->display_errors());
			//var_dump($config);
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
					$code= $row['B'];

					$id_produk = $this->model1->id_produk($code);
					
					

					$dataCart = array(
								"idProduk"		=> $id_produk,
								"idUser" 		=> $this->global['idUser']
						     );

					$this->db->insert("cc_cartbuy1get3",$dataCart);
				}
			$i++; }

			unlink($file);
		}
		
	}

	function ajax_produk(){
		$q 			= $_GET['term'];

		$get_bahan_baku_select2 = $this->modelPromoBuy1Get3->produkAjax($q);

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
		$q 			= $_POST['brand'];
		$idToko = $this->model1->getIdStore($this->global['idUser']);
		$idUser = $this->global['idUser'];
		$del = $this->modelPromoBuy1Get3->deleteCartPO($idUser);
		$_SESSION['id_brand']=$q;

		$getStok = $this->modelPromoBuy1Get3->produkAjaxSupplier($q,$idToko);

		$data_array = array();$n=0;

		foreach($getStok->result() as $row){
			$dataCart = array(
								"idProduk"		=> $row->id_produk,
								"qty"			=> $row->pesan,
								"idUser" 		=> $idUser,
								"harga"			=> 0
						     );

			$this->modelPromoBuy1Get3->insertCartPO($dataCart);
			$n++;
		}

		echo json_encode($n);
	}

	function insertPO(){
		$id_user  		= sprintf("%03d",$this->global['idUser']);
		$tanggal_po 	= date('Y-m-d');
		$tanggalMulai 	= $_POST['tanggalMulai'];
		$tanggalSelesai = $_POST['tanggalSelesai'];
		$keterangan 	= $_POST['keterangan'];
		$jamMulai  		= $_POST['jamMulai'];
		$jamSelesai  	= $_POST['jamSelesai'];
		$setJam			= $_POST['setJam'];
		$setHari		= $_POST['setHari'];
		$store 			= $_POST['store'];
		$jumlah_bayar 	= $_POST['jumlah_bayar'];
		$jumlah_gratis 	= $_POST['jumlah_gratis'];
		$discount_percent = $_POST['discount_percent'];
		$discount_rp 	= $_POST['discount_rp'];


		$arrHari		= $_POST['HariID'];
		$HariID			= ".";

		if (!empty($arrHari)){
			foreach ($arrHari as $H) {
				$HariID.= $H.'.';
			}
		}

		$viewDataPO = $this->modelPromoBuy1Get3->viewCartPO($this->global['idUser'],$this->global['idStore']);
		
		$seq = 1;
		foreach ($store as $t) {
			$cek_tanggal 	= $this->model1->cek_tanggal_terima_promo($tanggal_po);

			$create_date 	= date_create($tanggal_po);
			$convert_date   = date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

			$no_inv = 'PBG'.date('hs').$convert_date.$id_user.sprintf("%04d",$cek_tanggal+$seq);
			$seq++;
			$data_bahan = array();

			foreach($viewDataPO->result() as $row){
				$sku 			= $row->id_produk;

				$data_bahan[]     = array(
										"no_promo"		=> $no_inv,
										"id_produk"		=> $sku,
										"group_series"	=> $row->group_series,
								);
			
			}

			$data_masuk = array(
									"no_promo" 			=> $no_inv,
									"tanggal_buat" 		=> date('Y-m-d H:i:s'),
									"tanggalMulai"		=> $tanggalMulai,
									"tanggalSelesai"	=> $tanggalSelesai,
									"keterangan"		=> $keterangan,
									"JamMulai"			=> $jamMulai,
									"JamSelesai"		=> $jamSelesai,
									"setJam"			=> $setJam,
									"setHari"			=> $setHari,
									"HariID"			=> $HariID,
									"id_pic"			=> $this->global['idUser'],
									"id_toko"			=> $t,
									"status"			=> 1,
									"jumlah_bayar"		=> $jumlah_bayar,
									"jumlah_gratis"		=> $jumlah_gratis,
									"discount_percent"	=> $discount_percent,
									"discount_rp"		=> $discount_rp
								);
			
			$this->modelPromoBuy1Get3->insertPONumber($data_masuk);
			$this->modelPromoBuy1Get3->insertPOItem($data_bahan);
		}
		

		$this->modelPromoBuy1Get3->deleteCartPO($this->global['idUser']);
		echo $no_inv;

		//redirect("purchase_order/form_po?no_po=".$no_inv);
	}

	function daftar_po(){
		$this->global['pageTitle'] = "SOLUSI POS - Daftar Promo Buy 1 Get 3";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("promo_buy1get3/body_daftar",$this->global,NULL,"promo_buy1get3/footerDaftarPO");
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function form_promo(){
		$no_po = $_GET['no_promo'];
		$data['header'] = $this->db->get("ap_receipt");
		$info_po = $this->modelPromoBuy1Get3->infoPurchase($no_po);
		$data['purchase_item'] = $this->modelPromoBuy1Get3->purchase_item($no_po);

		$data['tanggal_buat'] 		= $info_po->tanggal_buat;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['tanggalMulai']		= $info_po->tanggalMulai;
		$data['tanggalSelesai']		= $info_po->tanggalSelesai;
		$data['store']				= $info_po->store;
		$data['jumlah_bayar']		= $info_po->jumlah_bayar;
		$data['jumlah_gratis']		= $info_po->jumlah_gratis;
		$data['discount_percent']	= $info_po->discount_percent;
		$data['discount_rp']		= $info_po->discount_rp;
		$data['setJam'] 			= $info_po->setJam==1? $info_po->JamMulai.' s/d '.$info_po->JamSelesai:'tidak diatur';

		$this->global['pageTitle'] = "SOLUSI POS - Form Promo Buy 1 Get 3";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("promo_buy1get3/body_form_po",$this->global,$data,"promo_buy1get3/footer_barang_masuk");
	}

	function datatablesPO(){
        $idUser     = $this->global['idUser'];
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelPromoBuy1Get3->totalPromoSupplier();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelPromoBuy1Get3->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore'],$this->global['isSuperadmin']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelPromoBuy1Get3->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore'],$this->global['isSuperadmin']);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			

			$output['data'][]=array($nomor_urut,"<a href='".base_url('promo_buy1get3/form_promo?no_promo='.$dt['no_promo'])."'>".$dt['no_promo']."</a>",$dt['tanggal_buat'],$dt['tanggalMulai'],$dt['tanggalSelesai'],$dt['first_name'],"<a onclick=\"if (confirm('Yakin hapus semua data yang berhubungan dengan nomor promo ini?')){hapusPromo('".$dt['no_promo']."');}\" ><i class=\"fa fa-trash\"></i></a>");
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function hapusAllPromo(){
		$id = $_POST['id'];

		$dataUpdate = array(
								"status"	=> 0
					       );
		$this->db->where("no_promo",$id);
		$this->db->update("promo_buy1get3",$dataUpdate);
	}

	function insertCartPO(){
		$idProduk 		= $_POST['idProduk'];
		$idUser 		= $this->global['idUser'];
		$group_series	= isset($_POST['group_series']) ? $_POST['group_series'] : 'A';

		$cekCart = $this->modelPromoBuy1Get3->cekCartPO($idProduk,$idUser);

		if($cekCart < 1){
			$dataCart = array(
								"idProduk"		=> $idProduk,
								"group_series"	=> $group_series,
								"paid_item"		=> 1,
								"idUser" 		=> $idUser,
								"harga"			=> 0
						     );

			$this->modelPromoBuy1Get3->insertCartPO($dataCart);
			echo 0;
		} else {			
			$id = $this->modelPromoBuy1Get3->getIdCart($idProduk,$idUser);
			echo $id;
		}
	}

	function updateGroupCart(){
		$idProduk 		= $_POST['idProduk'];
		$idUser   		= $this->global['idUser'];
		$group_series 	= $_POST['group_series'];

		$dataUpdate = array(
								"group_series"	=> $group_series
					       );
		
		$this->modelPromoBuy1Get3->updateQtyCart($idProduk,$idUser,$dataUpdate);
	}

	function cartPO(){
		$idUser = $this->global['idUser'];
		// DISINI TAMBAH SCRIPT
		// Ambil data iduser dan supplier
		$data['viewCartPO'] = $this->modelPromoBuy1Get3->viewCartPO($idUser,$this->global['idStore']);
		$this->load->view("promo_buy1get3/cartPO",$data);
	}

	function updateQtyPaidCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser   	= $this->global['idUser'];
		$qty 		= $_POST['qty'];

		$dataUpdate = array(
								"paid_item"		=> $qty
					       );
		
		$this->modelPromoBuy1Get3->updateQtyCart($idProduk,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelPromoBuy1Get3->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}
	function updateQtyFreeCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$qty 		= $_POST['qty'];

		$dataUpdate = array(
								"free_item"		=> $qty
					       );
		
		$this->modelPromoBuy1Get3->updateQtyCart($idProduk,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelPromoBuy1Get3->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}
	function updateQuotaCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$quota = $_POST['quota'];

		$dataUpdate = array(
								"quota"		=> $quota
					       );
		
		$this->modelPromoBuy1Get3->updateQtyCart($idProduk,$idUser,$dataUpdate);
	}
	function updateNominalCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$nominal = $_POST['nominal'];

		$dataUpdate = array(
								"minimal_belanja"		=> $nominal
					       );
		
		$this->modelPromoBuy1Get3->updateQtyCart($idProduk,$idUser,$dataUpdate);
	}

	function totalCart(){
		$idUser   = $this->global['idUser'];
		$totalCart = $this->modelPromoBuy1Get3->totalCartPeruser($idUser);

		if($totalCart){
			echo number_format($totalCart,'0',',','.');
		} else {
			echo 0;
		}
	}

	function updateHargaCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$qty = $_POST['qty'];
		$harga = $_POST['harga']<100? ($_POST['harga']/100)*$_POST['harga_jual']:$_POST['harga'];
		$harga_jual = $_POST['harga_jual'];
		$totHarga = $qty*$harga_jual;

		$dataUpdate = array(
								"harga"		=> $harga
					       );

		$this->modelPromoBuy1Get3->updateHargaCart($idProduk,$idUser,$dataUpdate);
	
		//get total peritem
		$totalPeritem = $this->modelPromoBuy1Get3->totalPeritem($idUser,$idProduk);

		echo number_format($totHarga-$totalPeritem,'0',',','.');
	}
	

	function hapusCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$this->modelPromoBuy1Get3->hapusCart($idProduk,$idUser);
	}

}