<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Promo_supplier extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library(array('session','encryption'));
		$this->load->model(array("model1","modelPromoSupplier"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],8,54);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Promo Supplier";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data['store'] = $this->db->get("ap_store");
		$data['brand'] = $this->db->get("brand");
		$data['kategori'] = $this->db->get("ap_kategori")->result();
		$this->loadViews("promo_supplier/body",$this->global,$data,"promo_supplier/footer");
	}

	function importPromo(){
		$this->global['pageTitle'] = 'Solusi POS - Import Promo Supplier';
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("promo_supplier/import_produk", $this->global, array(), "promo_supplier/footer_import");
	}

	function templateProduk(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU');

		$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusi POS | IT Solutions")
								->setSubject("Solusi POS | IT Solutions")
								->setDescription("Export Data")
								->setKeywords("office 2007 openxml php")
								->setCategory("Data Purchase Item");

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=Promo Supplier Item Template.xlsx');
		$objWriter->save("php://output");
	}

	function importSQL(){
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

			$i = 1;
			$idUser = $this->global['idUser'];
			foreach($sheets as $row){
				if($i > 1){
					$code = isset($row['B']) ? $row['B'] : '';
					$id_produk = $code;
					if ($id_produk !== '' && $this->modelPromoSupplier->cekCartPO($id_produk, $idUser) < 1) {
						$dataCart = array(
							"idProduk"		=> $id_produk,
							"qty"			=> 1,
							"idUser" 		=> $idUser,
							"harga"			=> 0
						);
						$this->modelPromoSupplier->insertCartPO($dataCart);
					}
				}
				$i++;
			}

			unlink($file);
		}
	}

	function get_subkategori(){
		$id_kategori = $_POST['id_kategori'];

		$data['show_sub'] = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori));

		$count = 0;

		foreach($data['show_sub']->result() as $row){
			$count = $count+$row->id;
		}

		if(!empty($_POST['id_kategori']) && $count > 0){
			$this->load->view("promo_supplier/show_sub",$data);
		} else {
			echo "<input type='hidden' id='subkategori2' value=''/>";
		}
	}

	function get_subkategori_2(){
		$id_kategori_2 = $_POST['id'];

		$data['show_sub'] = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $id_kategori_2));
		$count = 0;

		foreach($data['show_sub']->result() as $row){
			$count = $count+$row->id;
		}

		if(!empty($_POST['id']) && $count > 0){
			$this->load->view("promo_supplier/show_sub2",$data);
		} else {
			echo "<input type='hidden' id='subkategori_3' value=''/>";
		}
	}

	function ajax_produk(){
		$q 			= $_GET['term'];

		$get_bahan_baku_select2 = $this->modelPromoSupplier->produkAjax($q);

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
		$kategori 			= $_POST['kategori'];
		$subkategori2 		= $_POST['subkategori'];
		$subkategori_3 		= $_POST['subkategori2'];
		$idToko = $this->model1->getIdStore($this->global['idUser']);
		$idUser = $this->global['idUser'];
		$del = $this->modelPromoSupplier->deleteCartPO($idUser);
		$_SESSION['id_brand']=$q;

		$getStok = $this->modelPromoSupplier->produkAjaxSupplier($q,$idToko,$kategori,$subkategori2,$subkategori_3);

		$data_array = array();$n=0;

		foreach($getStok->result() as $row){
			$dataCart = array(
								"idProduk"		=> $row->id_produk,
								"qty"			=> $row->pesan,
								"idUser" 		=> $idUser,
								"harga"			=> 0
						     );

			$this->modelPromoSupplier->insertCartPO($dataCart);
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
		$brand  		= $_POST['brand'];
		$toko_raw		= isset($_POST['toko']) ? $_POST['toko'] : array();
		if (!is_array($toko_raw)) {
			$toko_raw = ($toko_raw !== '' && $toko_raw !== null) ? array($toko_raw) : array();
		}
		$toko_arr = array_values(array_unique(array_filter(array_map('intval', $toko_raw))));
		if (empty($toko_arr)) {
			echo '';
			return;
		}
		$id_toko_header = $toko_arr[0];
		$jamMulai  		= $_POST['jamMulai'];
		$jamSelesai  	= $_POST['jamSelesai'];
		$setJam			= $_POST['setJam'];
		$setHari		= $_POST['setHari'];
		$tipe			= $_POST['tipe'];

		$arrHari		= isset($_POST['HariID']) ? $_POST['HariID'] : array();
		$HariID			= ".";

		if (!empty($arrHari) && is_array($arrHari)){
			foreach ($arrHari as $H) {
				$HariID.= $H.'.';
			}
		}
		

		$cek_tanggal 	= $this->model1->cek_tanggal_terima_promo($tanggal_po);

		$create_date 	= date_create($tanggal_po);
		$convert_date   = date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

		$no_inv = 'PS'.$convert_date.$id_user.date('hs').sprintf("%04d",$cek_tanggal+1);

		$data_masuk = array(
								"no_promo" 			=> $no_inv,
								"tanggal_buat" 		=> $tanggal_po,
								"tanggalMulai"		=> $tanggalMulai,
								"tanggalSelesai"	=> $tanggalSelesai,
								"id_brand"			=> $brand,
								"keterangan"		=> $keterangan,
								"JamMulai"			=> $jamMulai,
								"JamSelesai"		=> $jamSelesai,
								"setJam"			=> $setJam,
								"setHari"			=> $setHari,
								"HariID"			=> $HariID,
                                "id_pic"			=> $this->global['idUser'],
                                "id_toko"			=> $id_toko_header,
								"tipe"				=> $tipe,
								"status"			=> 0
							);
		
		$this->modelPromoSupplier->insertPONumber($data_masuk);

		$viewDataPO = $this->modelPromoSupplier->viewCartPO($this->global['idUser'],$this->global['idStore']);

		$data_bahan = array();
		foreach($viewDataPO->result() as $row){
			$sku 			= $row->id_produk;
			$jumlah_beli	= $row->qty;
			$harga 			= $row->harga;
			$disc_supplier	= $row->disc_supplier;

			if ($jumlah_beli>0){
				foreach ($toko_arr as $id_toko) {
					$data_bahan[]     = array(
											"no_promo"		=> $no_inv,
											"id_produk"		=> $sku,
											"qty"			=> $jumlah_beli,
											"quota"			=> $row->quota,
											"quotarp"		=> $row->quotarp,
											"discount"		=> $harga,
											"disc_supplier"	=> $disc_supplier,
											"date_start"	=> $tanggalMulai,
											"date_end"		=> $tanggalSelesai,
											"setJam"		=> $setJam,
											"JamMulai"		=> $jamMulai,
											"JamSelesai"	=> $jamSelesai,
											"setHari"		=> $setHari,
											"HariID"		=> $HariID,
											"tipe"			=> $tipe,
											"id_toko"		=> $id_toko
									   );
				}

				$data_update = array(
										"diskon" => 1
									);
				$this->modelPromoSupplier->setToDiskon($sku,$data_update);
			}
			
		}

		if (!empty($data_bahan)) {
			$this->modelPromoSupplier->insertPOItem($data_bahan);
		}
		$this->modelPromoSupplier->deleteCartPO($this->global['idUser']);
		echo $no_inv;

		//redirect("purchase_order/form_po?no_po=".$no_inv);
	}

	function daftar_po(){
		$this->global['pageTitle'] = "SOLUSI POS - Daftar Produk Promo Supplier";
		$this->global['navigation'] = $this->model1->callNavigation();

		$data['date_start'] = isset($_GET['dateStart'])?$_GET['dateStart']:'';
		$data['date_end'] = isset($_GET['dateEnd'])?$_GET['dateEnd']:'';
		$data['id_toko'] = isset($_GET['id_toko'])?$_GET['id_toko']:'';

		if($this->global['isSuperadmin']==1){
			$data['store'] = $this->db->get("ap_store")->result();
		}else $data['store'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
		$this->loadViews("promo_supplier/body_daftar",$this->global,$data,"promo_supplier/footerDaftarPO");
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function form_promo(){
		$no_po = $_GET['no_promo'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelPromoSupplier->purchase_item($no_po);
		$info_po = $this->modelPromoSupplier->infoPurchase($no_po);

		$data['tanggal_buat'] 		= $info_po->tanggal_buat;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['tipe'] 				= $info_po->tipe;
		$data['supplier'] 			= $info_po->supplier;
		$data['alamat_sp'] 			= $info_po->alamat;
		$data['kontak_sp'] 			= $info_po->kontak;
		$data['tanggalMulai']		= $info_po->tanggalMulai;
		$data['tanggalSelesai']		= $info_po->tanggalSelesai;
		$data['idSupplier'] 		= $info_po->id_supplier;
		$data['store'] 				= $info_po->store;
		$data['setJam'] 			= $info_po->setJam==1? $info_po->JamMulai.' s/d '.$info_po->JamSelesai:'tidak diatur';

		$this->global['pageTitle'] = "SOLUSI POS - Form Promo Supplier";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("promo_supplier/body_form_po",$this->global,$data,"promo_supplier/footer_barang_masuk");
	}

	function datatablesPO(){
        $idUser     = $this->global['idUser'];
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelPromoSupplier->totalPromoSupplier();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		$date_start = isset($_REQUEST['dateStart'])?$_REQUEST['dateStart']:'';
		$date_end = isset($_REQUEST['dateEnd'])?$_REQUEST['dateEnd']:'';
		$idToko = isset($_REQUEST['id_toko'])?$_REQUEST['id_toko']:'';

		if($search!=""){
			$query = $this->modelPromoSupplier->viewPOProduk($length,$start,$search,$idUser,$idToko,$date_start,$date_end);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelPromoSupplier->viewPOProduk($length,$start,$search,$idUser,$idToko,$date_start,$date_end);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			

			$output['data'][]=array($nomor_urut,"<a href='".base_url('promo_supplier/form_promo?no_promo='.$dt['no_promo'])."'>".$dt['no_promo']."</a>",$dt['tanggal_buat'],$dt['tanggalMulai'],$dt['tanggalSelesai'],$dt['brand'],$dt['first_name'],"<a onclick=\"if (confirm('Yakin hapus semua data yang berhubungan dengan nomor promo ini?')){hapusPromo('".$dt['no_promo']."');}\" ><i class=\"fa fa-trash\"></i></a>");
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function hapusAllPromo(){
		$id = $_POST['id'];
		$this->db->where("no_promo", $id);
        $this->db->delete("ap_produk_discount_rules");
		
		$this->modelPromoSupplier->delPromoSupplier($id);
	}

	function insertCartPO(){
		$idProduk 		= $_POST['idProduk'];
		$idUser = $this->global['idUser'];
		$idStore=empty($this->global['idStore'])?7:$this->global['idStore'];
		$hargaProduk = $this->modelPromoSupplier->hargaBeliProduk($idProduk,$idStore);

		//cek on cart if exist
		$cekCart = $this->modelPromoSupplier->cekCartPO($idProduk,$idUser);

		if($cekCart < 1){
			$dataCart = array(
								"idProduk"		=> $idProduk,
								"qty"			=> 1,
								"idUser" 		=> $idUser,
								"harga"			=> 0
						     );

			$this->modelPromoSupplier->insertCartPO($dataCart);
			echo 0;
		} else {			
			$id = $this->modelPromoSupplier->getIdCart($idProduk,$idUser);
			echo $id;
		}
	}

	function cartPO(){
		$idUser = $this->global['idUser'];
		// DISINI TAMBAH SCRIPT
		// Ambil data iduser dan supplier
		$data['viewCartPO'] = $this->modelPromoSupplier->viewCartPO($idUser,$this->global['idStore']);
		$this->load->view("promo_supplier/cartPO",$data);
	}

	function updateQtyCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$qty = $_POST['qty'];
		$harga_jual = $_POST['harga_jual'];
		$totHarga = $qty*$harga_jual;

		$dataUpdate = array(
								"qty"		=> $qty
					       );
		
		$this->modelPromoSupplier->updateQtyCart($idProduk,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelPromoSupplier->totalPeritem($idUser,$idProduk);

		echo number_format($totHarga-$totalPeritem,'0',',','.');
	}
	function updateQuotaCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$quota = $_POST['quota'];

		$dataUpdate = array(
								"quota"		=> $quota
					       );
		
		$this->modelPromoSupplier->updateQtyCart($idProduk,$idUser,$dataUpdate);
	}
	function updateQuotaRpCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$quota = $_POST['quota'];

		$dataUpdate = array(
								"quotarp"		=> $quota
					       );
		
		$this->modelPromoSupplier->updateQtyCart($idProduk,$idUser,$dataUpdate);
	}

	function totalCart(){
		$idUser   = $this->global['idUser'];
		$totalCart = $this->modelPromoSupplier->totalCartPeruser($idUser);

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

		$this->modelPromoSupplier->updateHargaCart($idProduk,$idUser,$dataUpdate);
	
		//get total peritem
		$totalPeritem = $this->modelPromoSupplier->totalPeritem($idUser,$idProduk);

		echo number_format($totHarga-$totalPeritem,'0',',','.');
	}
	function updateDiscSupplierCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$qty = $_POST['qty'];
		$disc_supplier = $_POST['disc_supplier']<100? ($_POST['disc_supplier']/100)*$_POST['harga_jual']:$_POST['disc_supplier'];
		$harga_jual = $_POST['harga_jual'];
		$totHarga = $qty*$harga_jual;

		$dataUpdate = array(
								"disc_supplier"		=> $disc_supplier
					       );

		$this->modelPromoSupplier->updateHargaCart($idProduk,$idUser,$dataUpdate);
	
		//get total peritem
		$totalPeritem = $this->modelPromoSupplier->totalPeritem($idUser,$idProduk);

		echo number_format($totHarga-$totalPeritem,'0',',','.');
	}

	function hapusCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$this->modelPromoSupplier->hapusCart($idProduk,$idUser);
	}

}