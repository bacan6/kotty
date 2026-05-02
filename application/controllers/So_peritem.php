<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class SO_Peritem extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library(array('session','encryption'));
		$this->load->model(array("model1","modelSOPeritem","model_penjualan"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],4,49);
	}
	function rekapBrand(){
		$this->global['pageTitle'] = "SOLUSI POS - Rekap SO Per Brand";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data['tahun'] 		= isset($_GET['tahun'])?$_GET['tahun']:date('Y');
		$data['id_toko'] 	= isset($_GET['toko'])?$_GET['toko']:'';
		$data['toko']		= $this->db->get("ap_store");
		$this->loadViews("stock_opname_peritem/body_rekap_so",$this->global,$data,"stock_opname_peritem/footerRekapSO");
	}
	function datatablesRekap(){
		$draw 		= isset($_REQUEST['draw']) ? (int) $_REQUEST['draw'] : 0;
		$length 	= isset($_REQUEST['length']) ? (int) $_REQUEST['length'] : 10;
		$start 		= isset($_REQUEST['start']) ? (int) $_REQUEST['start'] : 0;
		$search 	= isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';

		$toko = isset($_POST['toko']) ? $_POST['toko'] : '';
		$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');

		$recordsTotal = $this->modelSOPeritem->countRekapDistinctPairs($toko, $tahun, '');
		$recordsFiltered = $this->modelSOPeritem->countRekapDistinctPairs($toko, $tahun, $search);

		$query = $this->modelSOPeritem->rekapMatrixSoRows($toko, $tahun, $search);

		$rowMeta = array();
		$rekap = array();
		$orderKeys = array();

		foreach ($query->result_array() as $dt) {
			if (empty($dt['brand'])) {
				continue;
			}
			$kid = (isset($dt['id_kategori']) && $dt['id_kategori'] !== null && $dt['id_kategori'] !== '')
				? (string) (int) $dt['id_kategori']
				: 'n';
			$rowKey = (int) $dt['id_brand'].'|'.$kid;
			if (!isset($rowMeta[$rowKey])) {
				$kat = (isset($dt['nama_kategori']) && $dt['nama_kategori'] !== null && $dt['nama_kategori'] !== '') ? $dt['nama_kategori'] : '';
				$rowMeta[$rowKey] = array(
					'display' => $kat !== '' ? $dt['brand'].' — '.$kat : $dt['brand']
				);
				$orderKeys[] = $rowKey;
			}
			if (!empty($dt['bulan']) && !isset($rekap[$rowKey][$dt['bulan']])) {
				$rekap[$rowKey][$dt['bulan']] = "<a href='".base_url('so_peritem/form_so_selisih?no_so='.$dt['no_so'])."' target='_blank'>".$dt['tanggal_so']."</a>";
			}
		}

		usort($orderKeys, function ($a, $b) use ($rowMeta) {
			return strcmp($rowMeta[$a]['display'], $rowMeta[$b]['display']);
		});

		if ($length === -1) {
			$pageKeys = $orderKeys;
		} else {
			$pageKeys = array_slice($orderKeys, $start, $length);
		}

		$output = array(
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => array()
		);

		$nomor_urut = $start + 1;
		foreach ($pageKeys as $rowKey) {
			$disp = $rowMeta[$rowKey]['display'];
			$output['data'][] = array(
				$nomor_urut,
				$disp,
				isset($rekap[$rowKey][$tahun.'-01']) ? $rekap[$rowKey][$tahun.'-01'] : '',
				isset($rekap[$rowKey][$tahun.'-02']) ? $rekap[$rowKey][$tahun.'-02'] : '',
				isset($rekap[$rowKey][$tahun.'-03']) ? $rekap[$rowKey][$tahun.'-03'] : '',
				isset($rekap[$rowKey][$tahun.'-04']) ? $rekap[$rowKey][$tahun.'-04'] : '',
				isset($rekap[$rowKey][$tahun.'-05']) ? $rekap[$rowKey][$tahun.'-05'] : '',
				isset($rekap[$rowKey][$tahun.'-06']) ? $rekap[$rowKey][$tahun.'-06'] : '',
				isset($rekap[$rowKey][$tahun.'-07']) ? $rekap[$rowKey][$tahun.'-07'] : '',
				isset($rekap[$rowKey][$tahun.'-08']) ? $rekap[$rowKey][$tahun.'-08'] : '',
				isset($rekap[$rowKey][$tahun.'-09']) ? $rekap[$rowKey][$tahun.'-09'] : '',
				isset($rekap[$rowKey][$tahun.'-10']) ? $rekap[$rowKey][$tahun.'-10'] : '',
				isset($rekap[$rowKey][$tahun.'-11']) ? $rekap[$rowKey][$tahun.'-11'] : '',
				isset($rekap[$rowKey][$tahun.'-12']) ? $rekap[$rowKey][$tahun.'-12'] : ''
			);
			$nomor_urut++;
		}

		echo json_encode($output);
	}
	function simpanRevisi(){
		$itemProduk = $_POST['revisiItem'];
		$decodeJSON = json_decode(stripcslashes($itemProduk));
		$data_kartu = array();

		foreach($decodeJSON as $row){
			$id_produk 	= $row->id_produk;
			$no_so 		= $row->no_so;
			$revisi 	= $row->revisi+0;

			$dataRevisi = array(
				"revisi" 	=> $revisi
			);
			$this->modelSOPeritem->updateRevisi($id_produk,$no_so,$dataRevisi);

			$id_toko = $this->modelSOPeritem->getIdToko($no_so);

			$stok_before = $this->modelSOPeritem->stokItem($id_toko,$id_produk);
			$qty = $revisi - $stok_before;

			$data_kartu[]    = array(
				"id_store"		=> $id_toko,
				"id_produk"		=> $id_produk,
				"qty"			=> $qty,
				"harga"			=> 0,
				"hpp"			=> 0,
				"tanggal"		=> date('Y-m-d H:i:s'),
				"tipe"			=> 'REVISI - SO item',
				"no_transaksi"	=> $no_so,
				"id_pic"		=> $this->global['idUser']
			);

			$dataUpdate = array(
				"stok"		=> $revisi,
				"last_SO"		=> date('Y-m-d')
				);
				
			$this->modelSOPeritem->updateStokItem($id_produk,$dataUpdate,$id_toko);
		}
		echo "1";

		if($data_kartu){
			$this->model1->insertKartuStok($data_kartu);
		}
		
	}

	function kosongkanCart(){
		$this->modelSOPeritem->deleteCartPO($this->global['idUser']);
		redirect("so_peritem");
	}
	function exportExcelCartSo(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','NAMA PRODUK')
									  ->setCellValue('D1','HPP')
									  ->setCellValue('E1','Harga Jual')
									  ->setCellValue('F1','Stok Sistem')
									  ->setCellValue('G1','Stok Toko')
									  ->setCellValue('H1','Total Harga');

		$idUser = $this->global['idUser'];
		$laporan = $this->modelSOPeritem->viewCartPO($idUser,$this->global['idStore']);

		$i=2;
		foreach($laporan->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValue('B'.$i,$row->id_produk)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->hpp)
									  ->setCellValue('E'.$i,$row->harga)
									  ->setCellValue('F'.$i,$row->stok_before)
									  ->setCellValue('G'.$i,$row->stok_after)
									  ->setCellValue('H'.$i,$row->stok_after*$row->hpp);

		$i++; 
		}

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusi POS | IT Solutions")
								->setSubject("Solusi POS | IT Solutions")
								->setDescription("Export Data")
								->setKeywords("office 2007 openxml php")
								->setCategory("Data SO");
	 
	     //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	 
	   	//sesuaikan headernya 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    //ubah nama file saat diunduh
	    header('Content-Disposition: attachment;filename=Cart Stok Opname Per Item.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}
	function laporanSOnull(){
		$this->global['pageTitle'] = "SOLUSI POS- Belum Stock Opname";
		if($this->global['isSuperadmin']==1){
			$data['toko'] = $this->db->get("ap_store")->result();
		}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
		$data['brand'] = $this->db->get("brand")->result();
		$this->loadViews("stock_opname_peritem/bodyLaporanSOnull",$this->global,$data,"stock_opname_peritem/footerLaporanSOnull");	
	}
	function viewReportSOnull(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],4,49);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {

				$start 		= $_POST['dateStart'];
				$end 		= $_POST['dateEnd'];
				$toko 		= $_POST['toko'];
				$brand 		= $_POST['brand'];

				$data['start'] = $start;
				$data['end']   = $end;
				$data['toko']   = $toko;
				$data['brand']   = $brand;

				if(empty($toko)){
					$data['title'] = "Akumulasi";
				} else {
					$data['title'] = $this->model1->namaStore($toko);
				}
				//error_reporting(E_ALL);ini_set('display_errors',1);
				$data['SO_item'] = $this->modelSOPeritem->SO_item_hasil_null($start,$end,$toko,$brand);
				$data['idStore'] = $toko;

				$this->load->view("stock_opname_peritem/viewReportLaporanSOnull",$data);
			}
		}
	}
	function exportExcelHasilSOnull(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','Brand')
									  ->setCellValue('C1','Nama')
									  ->setCellValue('D1','SKU')
									  ->setCellValue('E1','Stok');
		
		$start 			= $_GET['start'];
		$end 			= $_GET['end'];
		$toko 			= $_GET['toko'];

		$laporan = $this->modelSOPeritem->SO_item_hasil_null($start,$end,$toko);

		$i=2;
		foreach($laporan->result() as $row){
			$stok = $this->modelSOPeritem->stokItem($toko,$row->id_produk);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValue('B'.$i,$row->brand)
									  ->setCellValue('C'.$i,strtoupper($row->nama_produk))
									  ->setCellValue('D'.$i,$row->id_produk)
									  ->setCellValue('E'.$i,$stok);

		$i++; }

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusi POS | IT Solutions")
								->setSubject("Solusi POS | IT Solutions")
								->setDescription("Export Data")
								->setKeywords("office 2007 openxml php")
								->setCategory("Data SO");
	 
	     //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	 
	   	//sesuaikan headernya 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    //ubah nama file saat diunduh
	    header('Content-Disposition: attachment;filename=Belum SO.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}
	function laporanSO(){
		$this->global['pageTitle'] = "SOLUSI POS- Laporan Hasil Stock Opname";
		if($this->global['isSuperadmin']==1){
			$data['toko'] = $this->db->get("ap_store")->result();
		}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
		$this->loadViews("stock_opname_peritem/bodyLaporanSO",$this->global,$data,"stock_opname_peritem/footerLaporanSO");	
	}
	function viewReportSO(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],4,49);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {

				$start 		= $_POST['dateStart'];
				$end 		= $_POST['dateEnd'];
				$toko 		= $_POST['toko'];

				$data['start'] = $start;
				$data['end']   = $end;
				$data['toko']   = $toko;

				if(empty($toko)){
					$data['title'] = "Akumulasi";
				} else {
					$data['title'] = $this->model1->namaStore($toko);
				}
				//error_reporting(E_ALL);ini_set('display_errors',1);
				$data['SO_item'] = $this->modelSOPeritem->SO_item_hasil($start,$end,$toko);

				$this->load->view("stock_opname_peritem/viewReportLaporanSO",$data);
			}
		}
	}
	function exportExcelHasilSO(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','Brand')
									  ->setCellValue('C1','Kategori (SO)')
									  ->setCellValue('D1','Nama')
									  ->setCellValue('E1','SKU')
									  ->setCellValue('F1','No. SO')
									  ->setCellValue('G1','Harga')
									  ->setCellValue('H1','Stok Sistem')
									  ->setCellValue('I1','Stok Fisik')
									  ->setCellValue('J1','Selisih')
									  ->setCellValue('K1','Harga Selisih')
									  ->setCellValue('L1','Nilai Akhir')
									  ->setCellValue('M1','Harga Jual');
		
		$start 			= $_GET['start'];
		$end 			= $_GET['end'];
		$toko 			= $_GET['toko'];

		$laporan = $this->modelSOPeritem->SO_item_hasil($start,$end,$toko);

		$i=2;
		foreach($laporan->result() as $row){
			$katSo = isset($row->kategori_so) ? $row->kategori_so : '';
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValue('B'.$i,$row->brand)
									  ->setCellValue('C'.$i,$katSo)
									  ->setCellValue('D'.$i,strtoupper($row->nama_produk))
									  ->setCellValue('E'.$i,$row->id_produk)
									  ->setCellValue('F'.$i,$row->no_so)
									  ->setCellValue('G'.$i,$row->harga)
									  ->setCellValue('H'.$i,$row->stok_before)
									  ->setCellValue('I'.$i,$row->stok_after)
									  ->setCellValue('J'.$i,$row->stok_after-$row->stok_before)
									  ->setCellValue('K'.$i,($row->stok_after-$row->stok_before)*$row->harga)
									  ->setCellValue('L'.$i,$row->stok_after*$row->harga)
									  ->setCellValue('M'.$i,$row->harga_jual);

		$i++; }

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusi POS | IT Solutions")
								->setSubject("Solusi POS | IT Solutions")
								->setDescription("Export Data")
								->setKeywords("office 2007 openxml php")
								->setCategory("Data SO");
	 
	     //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	 
	   	//sesuaikan headernya 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    //ubah nama file saat diunduh
	    header('Content-Disposition: attachment;filename=Laporan Hasil SO.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function index(){
		// error_reporting(E_ALL);ini_set('display_errors',1);
		$this->global['pageTitle'] = "SOLUSI POS - Stock Opname per Item";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data['brand'] = $this->modelSOPeritem->listBrand($this->global['idStore']);
		$data['store']=$this->modelSOPeritem->getStore($this->global['idStore']);
		$idBrandCart = $this->modelSOPeritem->brandFromCart($this->global['idUser'], $this->global['idStore']);
		if ($idBrandCart !== null && $idBrandCart !== '') {
			$data['id_brand'] = $idBrandCart;
			$this->session->set_userdata('so_brand', $idBrandCart);
		} else {
			$data['id_brand'] = $this->session->userdata('so_brand');
		}
		$data['id_kategori'] = $this->session->userdata('so_kategori');
		$this->loadViews("stock_opname_peritem/body",$this->global,$data,"stock_opname_peritem/footer");
	}

	function verifyApproval(){
		$user 	= $_POST['user'];
		$password = $_POST['pw'];
		$setuju = $this->model_penjualan->verifyApprovalPass($user,$password);
		echo json_encode($setuju);
	}

	function ajax_produk(){
		$q = isset($_GET['term']) ? $_GET['term'] : '';
		$id_brand = isset($_GET['brand']) ? $_GET['brand'] : '';

		$get_bahan_baku_select2 = $this->modelSOPeritem->produkAjax($q, $id_brand);

		$data_array = array();

		foreach($get_bahan_baku_select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}

	function ajax_kategori_so(){
		$idBrand = isset($_POST['id_brand']) ? (int) $_POST['id_brand'] : 0;
		$out = array();
		if ($idBrand < 1) {
			echo json_encode($out);
			return;
		}
		$q = $this->modelSOPeritem->listKategoriEligibleForBrand($this->global['idStore'], $idBrand);
		foreach ($q->result() as $row) {
			$out[] = array('id' => $row->id_kategori, 'text' => $row->kategori);
		}
		echo json_encode($out);
	}

	function ajax_produk_supplier(){
		$brand = isset($_POST['brand']) ? $_POST['brand'] : '';
		$idKategori = isset($_POST['id_kategori']) ? (int) $_POST['id_kategori'] : 0;
		$idToko = $this->global['idStore'];
		$idUser = $this->global['idUser'];
		$this->modelSOPeritem->deleteCartPO($idUser);

		if ($brand === '' || $brand === null) {
			$this->session->unset_userdata(array('so_brand', 'so_kategori'));
			echo json_encode(0);
			return;
		}

		$getStok = $this->modelSOPeritem->produkAjaxSupplier($brand, $idToko, $idKategori);
		$tgl = date('Y-m-d H:i:s');
		$dataBatch = array();

		foreach ($getStok->result() as $row) {
			$dataBatch[] = array(
				"idProduk"		=> $row->id_produk,
				"stok_before"	=> $row->pesan,
				"stok_after"	=> 0,
				"min"			=> $row->min,
				"max"			=> $row->max,
				"idUser" 		=> $idUser,
				"harga"			=> $row->hpp,
				"tanggal"		=> $tgl,
				"store"			=> $idToko
			);
		}

		if (!empty($dataBatch)) {
			$this->db->insert_batch("cc_cartSO_peritem", $dataBatch);
		}

		$n = count($dataBatch);

		$this->session->set_userdata('so_brand', $brand);
		if ($idKategori >= 1) {
			$this->session->set_userdata('so_kategori', $idKategori);
		} else {
			$this->session->unset_userdata('so_kategori');
		}

		echo json_encode($n);
	}

	function importSOItem(){
		$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
		$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
		$data['navigation'] = $this->model1->callNavigation();
		$data['pageTitle'] = "SOLUSI POS - Import Data Stock Opname";
		$this->load->view("navigation",$data);
		$this->load->view("stock_opname_peritem/import_so_item");
		$this->load->view("stock_opname_peritem/footer_import");
	}

	function templateSOItem(){
		 // ini_set('display_errors', 1);
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();
		

		$objPHPExcel->getActiveSheet()->setCellValueExplicit('A1','SKU',PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('B1','Stok');

		

		
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
	    header('Content-Disposition: attachment;filename=SOTemplate.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function importSOitemSQL(){
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
			//die(var_dump($sheets));
			foreach($sheets as $row){
				if($i > 1){
					$id_produk= $row['A'];
					$stok = $row['B'];

					$stok_before=$this->modelSOPeritem->stokProduk($id_produk,$this->global['idStore']);

					$dataInsert[] = array(
											"idProduk" => $id_produk,
											"stok_before" => $stok_before->stok,
											"stok_after" => $stok,
											"idUser" => $this->global['idUser'],
											"store" => $this->global['idStore']
									     );
				}
			$i++; }

			$this->modelSOPeritem->hapusCartSO($this->global['idUser']);
			$this->db->insert_batch("cc_cartSO_peritem", $dataInsert);
			unlink($file);
		}
		
	}

	function insertPO(){
		$id_user  		= $this->global['idUser'];
        

		$no_inv = 'SO'.$id_user.date('YmdHis');

		

		$viewDataPO = $this->modelSOPeritem->viewCartPO($this->global['idUser'],$this->global['idStore']);
		$brand = '';

		foreach($viewDataPO->result() as $row){
			$sku 			= $row->id_produk;
			$stok_after		= $row->stok_after;
			$harga 			= $row->harga;

			$brand = empty($brand)? $this->modelSOPeritem->getIdBrand($sku):$brand;

			// Ambil data terkini, siapa tau ada yg sudah terjual
			//$stok_kartu = $this->model1->stokKartu($sku,$this->global['idStore']);
			$ada = $this->model1->cek_stok_toko($sku,$this->global['idStore']);
			$stok_before = $this->modelSOPeritem->stokItem($this->global['idStore'],$sku);

			if ($ada==0){
				//$stok_before = 0;
				$harga_jual = $this->modelSOPeritem->hargaJualProduk($sku,$this->global['idStore']);
				//tambahkan barang baru
				$insertDataStok[] = array(
										"id_produk" 	=> $sku,
										"id_store"		=> $this->global['idStore'],
										"stok"			=> $stok_after,
										"hpp"			=> $harga,
										"harga"			=> $harga_jual,
										"last_SO"		=> date('Y-m-d')
									);
				$this->modelSOPeritem->insertBatchDataStok($insertDataStok);					
			}

			$data_bahan[]     = array(
										"no_so"			=> $no_inv,
										"sku"			=> $sku,
										"stok_after"	=> $stok_after,
										"stok_before"	=> $stok_before,
										"harga"			=> $harga,
										"harga_jual"	=> $harga_jual,
										"tanggal"		=> date('Y-m-d')
								   );
			$qty = $stok_after - $stok_before;

			$data_kartu[]    = array(
										"id_store"		=> $this->global['idStore'],
										"id_produk"		=> $sku,
										"qty"			=> $qty,
										"harga"			=> $harga_jual,
										"hpp"			=> $harga,
										"tanggal"		=> date('Y-m-d H:i:s'),
										"tipe"			=> 'Stock Opname item',
										"no_transaksi"	=> $no_inv,
										"id_pic"		=> $this->global['idUser']
									);
		
		$dataUpdate = array(
							"stok"		=> $stok_after,
							"last_SO"		=> date('Y-m-d')
							   );
							   
			$this->modelSOPeritem->updateStokItem($sku,$dataUpdate,$this->global['idStore']);
		}

		if($data_kartu){
			$this->model1->insertKartuStok($data_kartu);
		}

		$this->modelSOPeritem->insertPOItem($data_bahan);
		$this->modelSOPeritem->deleteCartPO($this->global['idUser']);

		$id_kategori_save = isset($_POST['kategori']) ? (int) $_POST['kategori'] : 0;

		$data_masuk = array(
			"no_so" 			=> $no_inv,
			"tanggal_so"        => date('Y-m-d H:i:s'),
			"id_pic"			=> $this->global['idUser'],
			"id_toko"			=> $this->global['idStore'],
			"keterangan"		=> $_REQUEST['keterangan'],
			"id_brand"			=> $brand,
			"id_kategori"		=> $id_kategori_save > 0 ? $id_kategori_save : null,
			"status"			=> 0
		);

		$this->modelSOPeritem->insertPONumber($data_masuk);
		echo $no_inv;

		//redirect("purchase_order/form_po?no_po=".$no_inv);
	}

	function daftar_po(){
		$this->global['pageTitle'] = "SOLUSI POS - Daftar Stock Opname Produk";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("stock_opname_peritem/body_daftar_ep",$this->global,NULL,"stock_opname_peritem/footerDaftarEP");
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	
	function form_so(){
		$no_so = $_GET['no_so'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['SO_item'] = $this->modelSOPeritem->SO_item($no_so);
		$data['tanggal_so'] = $this->modelSOPeritem->tanggal_so($no_so);
		$data['infoSO'] = $this->modelSOPeritem->infoPurchase($no_so);
		$data['store']=$this->modelSOPeritem->getStore($this->global['idStore']);
		$data['status_selisih'] = false;

		$this->global['pageTitle'] = "SOLUSI POS - Form SO Per Item";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("stock_opname_peritem/body_form_ep",$this->global,$data,"stock_opname_peritem/footer_form_ep");
	}
	function form_so_selisih(){
		$no_so = $_GET['no_so'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['SO_item'] = $this->modelSOPeritem->SO_item_selisih($no_so);
		$data['tanggal_so'] = $this->modelSOPeritem->tanggal_so($no_so);
		$data['infoSO'] = $this->modelSOPeritem->infoPurchase($no_so);
		$info_po = $this->modelSOPeritem->infoPurchase($no_so);
		$data['store']=$this->modelSOPeritem->getStore($this->global['idStore']);
		$data['status_selisih'] = true;

		$this->global['pageTitle'] = "SOLUSI POS - Form SO Per Item";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("stock_opname_peritem/body_form_ep",$this->global,$data,"stock_opname_peritem/footer_form_ep");
	}

	function datatablesPO(){
		$this->load->model("modelBahanMasukMaterial");
        $idUser     = $this->global['idUser'];
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelBahanMasukMaterial->totalSOPeritemProduk();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewSOItemProduk($length,$start,$search,$idUser,$this->global['idStore']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewSOItemProduk($length,$start,$search,$idUser,$this->global['idStore']);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			// $status = $dt['status'];

			// if($status==0){
			// 	$button = '<span class="label label-primary">Menunggu Approve</span>';
			// } elseif($status==1){
			// 	$button = '<span class="label label-success">Diterima</span>';
			// } elseif($status==2){
			// 	$button = '<span class="label label-danger">Ditolak</span>';
			// } elseif($status==3){
			// 	$button = '<span class="label label-info">Selesai</span>';
			// }

			$katLabel = isset($dt['nama_kategori']) && $dt['nama_kategori'] !== '' ? $dt['nama_kategori'] : '—';
			$output['data'][]=array($nomor_urut,"<a href='".base_url('so_peritem/form_so?no_so='.$dt['no_so'])."'>".$dt['no_so']."</a>",$dt['tanggal_so'],$dt['keterangan'],$dt['first_name'],$dt['store'],$dt['brand'],$katLabel,"<a href='".base_url('so_peritem/form_so?no_so='.$dt['no_so'])."' class='btn btn-info btn-xs'>Total</a><br><a href='".base_url('so_peritem/form_so_selisih?no_so='.$dt['no_so'])."' class='btn btn-warning btn-xs'>Selisih</a>");
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_receive(){
		$this->load->view("navigation");
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);
		$data['receive_item'] = $this->modelSOPeritem->received_item($no_receive);
		$this->load->view("body_invoice_receive",$data);
		$this->load->view("footer");
	}	

	function sendEmailPOSupplier(){
		$no_po 	= $_POST['noPo'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['expired_product_item'] = $this->modelSOPeritem->expired_product_item($no_po);
		$info_po = $this->modelSOPeritem->infoPurchase($no_po);
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
		$email = $this->modelSOPeritem->emailSupplier($_POST['idSupplier']);	

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
		$cekEmailIfExist = $this->modelSOPeritem->cekEmailIfExist($idSupplier);

		if($cekEmailIfExist == 1){
			echo 1;
		} else {
			echo 0;
		}
	}

	function insertCartPO(){
		$idProduk 		= $_POST['idProduk'];
		$idUser 		= $this->global['idUser'];
        $idStore		= $this->global['idStore'];
		$hargaProduk 	= $this->modelSOPeritem->hargaBeliProduk($idProduk,$idStore);
		$stok 			= $this->modelSOPeritem->stokProduk($idProduk,$idStore);

		//cek on cart if exist
		$cekCart = $this->modelSOPeritem->cekCartPO($idProduk,$idUser);

		$dt_stok = is_object($stok) ? $stok->stok:0;

		if($cekCart < 1){
			$dataCart = array(
								"idProduk"		=> $idProduk,
								"stok_before"	=> $dt_stok,
								"stok_after"	=> $dt_stok,
								"idUser" 		=> $idUser,
								"harga"			=> $hargaProduk,
								"store"			=> $idStore
						     );

			$this->modelSOPeritem->insertCartPO($dataCart);
			echo 0;
		} else {			
			$id = $this->modelSOPeritem->getIdCart($idProduk,$idUser);
			echo $id;
		}
	}

	function cartEP(){
		$idUser = $this->global['idUser'];
		// DISINI TAMBAH SCRIPT
		// Ambil data iduser dan supplier
		$data['viewCartPO'] = $this->modelSOPeritem->viewCartPO($idUser,$this->global['idStore']);
		$this->load->view("stock_opname_peritem/cartEP",$data);
	}

	function updateQtyCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$qty = $_POST['qty'];

		$dataUpdate = array(
								"stok_after"		=> $qty
					       );
		
		$this->modelSOPeritem->updateQtyCart($idProduk,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelSOPeritem->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}

	function updateMinCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$min = $_POST['min'];

		$dataUpdate = array(
								"min"		=> $min
					       );
		
		$this->modelSOPeritem->updateQtyCart($idProduk,$idUser,$dataUpdate);
		//get total peritem
		
	}

	function updateMaxCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$max = $_POST['max'];

		$dataUpdate = array(
								"max"		=> $max
					       );
		
		$this->modelSOPeritem->updateQtyCart($idProduk,$idUser,$dataUpdate);
		//get total peritem
		
	}

	function totalCart(){
		$idUser   = $this->global['idUser'];
		$totalCart = $this->modelSOPeritem->totalCartPeruser($idUser);

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

		$this->modelSOPeritem->updateHargaCart($idProduk,$idUser,$dataUpdate);
	
		//get total peritem
		$totalPeritem = $this->modelSOPeritem->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}

	function hapusCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$this->modelSOPeritem->hapusCart($idProduk,$idUser);
	}

}