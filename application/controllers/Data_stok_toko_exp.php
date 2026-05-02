<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . '/third_party/fpdf/fpdf.php';
require APPPATH . '/libraries/BaseController.php';

class Data_stok_toko_exp extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelProduk","modelDataStok"));
		$this->load->database();
		$this->isLoggedIn($this->global['idUser'],2,17);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Data Stok Toko Kadaluarsa";
		$data['show_kategori'] = $this->db->get("ap_kategori")->result();
        
        $data['idUser'] = $this->global['idUser'];

		$data['stand'] = $this->db->get("ap_stand")->result();
		$data['show_supplier'] = $this->db->order_by("supplier","ASC")->get("supplier")->result();

		if($this->global['isAdmin']==0){
			$this->db->where("id_store",$this->global['idStore']);
		}
		
		$data['distributor'] = $this->db->get("ap_store");

		if(empty($_GET['idToko'])){
			$this->loadViews("data_stok/body_data_stok_exp",$this->global,$data,"data_stok/footerDataStokTokoExp");
		} else {
			$id_distributor = $_GET['idToko'];
			$data['data_stok_distributor'] 	= $this->model1->data_stok_distributor($id_distributor);
			$data['nama_distributor'] 		= $this->model1->nama_toko($id_distributor);

			$query = $this->modelProduk->dataStokTokoFullInventori($id_distributor);
			$data['nilai_toko'] 		= $query->result_array();
			$this->loadViews("data_stok/body_data_stok_exp_fill",$this->global,$data,"data_stok/footerDataStokTokoExp");
		}
	}

	function datatablesStok(){
		$this->load->model("modelProduk");

		$idToko 			= $this->global['isAdmin']==1? $_POST['idToko']:$this->global['idStore'];

		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelProduk->totalProdukPromotionExp($idToko);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelProduk->dataStokTokoExp($length,$start,$search,$idToko);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelProduk->dataStokTokoExpFull($length,$start,$idToko);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {

			$output['data'][]=array($nomor_urut,$dt['tanggal_po'],$dt['id_produk'],$dt['nama_produk'],$dt['kategori']."|".$dt['kategori_level_1']."-".$dt['kategori_3'],number_format($dt['hpp'],'0',',','.'),number_format($dt['harga'],'0',',','.'),number_format($dt['stok'],'0',',','.'),number_format($dt['stok']*$dt['hpp'],'0',',','.'),number_format($dt['stok']*$dt['harga'],'0',',','.'));
		$nomor_urut++;
		}

		echo json_encode($output);
	}

	function datatablesStokTokoFilter(){
		$idKategori 		= $_POST['idKategori'];
		$subkategori 		= $_POST['subkategori'];
		$subSubKategori 	= $_POST['subSubKategori'];
		$stokSign 			= $_POST['stokSign'];
		$stokValue 			= $_POST['stokValue'];
		$priceSign 			= $_POST['priceSign'];
		$priceSignValue 	= $_POST['priceSignValue'];
		$idToko 			= $this->global['idUser']>1 && $this->global['idUser']!=22? $this->global['idStore']:$_POST['idToko'];
		$idStand 			= $_POST['idStand'];
		$salePriceSign 		= $_POST['salePriceSign'];
		$salePriceValue 	= $_POST['salePriceValue'];
		$idSupplier 		= $_POST['idSupplier'];

		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelDataStok->totalProdukPromotion($idToko,$idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue,$idStand,$salePriceSign,$salePriceValue,$idSupplier);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelDataStok->dataStokToko($length,$start,$search,$idToko,$idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue,$idStand,$salePriceSign,$salePriceValue,$idSupplier);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelDataStok->dataStokToko($length,$start,$search,$idToko,$idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue,$idStand,$salePriceSign,$salePriceValue,$idSupplier);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {

			$harga_jual = $this->modelProduk->hargaJual($dt['id_produk'],$idToko);

			$output['data'][]=array($nomor_urut,$dt['id_produk'],$dt['nama_produk'],$dt['kategori']."-".$dt['kategori_level_1']."-".$dt['kategori_3']."-".$dt['kategori_level_1']."-".$dt['kategori_3'],number_format($dt['harga_beli'],'0',',','.'),number_format($harga_jual,'0',',','.'),number_format($dt['stok'],'0',',',''),number_format($dt['stok']*$dt['harga_beli'],'0',',','.'));
		$nomor_urut++;
		}

		echo json_encode($output);
	}

	function exportExcel(){
		$idToko  = $_GET['idToko'];

		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Kategori')
									  ->setCellValue('E1','Tempat')
									  ->setCellValue('F1','Harga Beli')	
									  ->setCellValue('G1','Stok Akhir')
									  ->setCellValue('H1','HB x S');

		$data_stok = $this->modelProduk->dataStokTokoFullExport($idToko);

		$i=2;$tstok=0;$tjml=0;
		foreach($data_stok->result() as $row){
			$harga_jual = $this->modelProduk->hargaJual($row->id_produk,$idToko);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->kategori."-".$row->kategori_level_1."-".$row->kategori_3)
									  ->setCellValue('E'.$i,$row->stand)
									  ->setCellValue('F'.$i,$row->harga_beli)
									  ->setCellValue('G'.$i,$row->stok)
									  ->setCellValue('H'.$i,($row->stok*$row->harga_beli));
			$tstok += $row->stok+0;
			$tjml += $row->stok*$row->harga_beli;

		$i++; }
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValue('B'.$i,'')
									  ->setCellValue('C'.$i,'')
									  ->setCellValue('D'.$i,'')
									  ->setCellValue('E'.$i,'')
									  ->setCellValue('F'.$i,'TOTAL')
									  ->setCellValue('G'.$i,$tstok)
									  ->setCellValue('H'.$i,$tjml);
		

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("SOLUSI POS | IT Solutions")
								->setSubject("SOLUSI POS | IT Solutions")
								->setDescription("Export Data")
								->setKeywords("office 2007 openxml php")
								->setCategory("Data SO");
	 
	     //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	 		
	 	$namaToko = $this->model1->nama_toko($idToko);

	   	//sesuaikan headernya 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    //ubah nama file saat diunduh
	    header("Content-Disposition: attachment;filename=Export Data Stok Produk (".$namaToko.").xlsx");
	    //unduh file
	    $objWriter->save("php://output");	
	}

	function exportExcelTokoFilter(){
		$idToko  		= $_GET['idToko'];
		$idSupplier  	= $_GET['idSupplier'];
		$idKategori 	= $_GET['idKategori'];
		$subkategori 	= $_GET['subkategori'];
		$subSubKategori	= $_GET['subSubKategori'];
		$stokSign 		= $_GET['stokSign'];
		$stokValue 		= $_GET['stokValue'];
		$priceSign 		= $_GET['priceSign'];
		$priceSignValue = $_GET['priceSignValue'];
		$idStand 		= $_GET['idStand'];
		$salePriceSign 	= $_GET['salePriceSign'];
		$salePriceValue = $_GET['salePriceValue'];

		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Kategori')
									  ->setCellValue('E1','Tempat')
									  ->setCellValue('F1','Harga Beli')	
									  ->setCellValue('G1','Harga Jual')
									  ->setCellValue('H1','Stok Akhir');

		$data_stok = $this->modelDataStok->dataStokTokoFilterExport($idToko,$idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue,$idStand,$salePriceSign,$salePriceValue,$idSupplier);

		$i=2;
		foreach($data_stok->result() as $row){
			$harga_jual = $this->modelProduk->hargaJual($row->id_produk,$idToko);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->kategori."-".$row->kategori_level_1."-".$row->kategori_3)
									  ->setCellValue('E'.$i,$row->stand)
									  ->setCellValue('F'.$i,$row->harga_beli)
									  ->setCellValue('G'.$i,$harga_jual)
									  ->setCellValue('H'.$i,$row->stok);
		$i++; }

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("SOLUSI POS | IT Solutions")
								->setSubject("SOLUSI POS | IT Solutions")
								->setDescription("Export Data")
								->setKeywords("office 2007 openxml php")
								->setCategory("Data SO");
	 
	     //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	 		
	 	$namaToko = $this->model1->nama_toko($idToko);

	   	//sesuaikan headernya 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    //ubah nama file saat diunduh
	    header("Content-Disposition: attachment;filename=Export Data Stok Produk (".$namaToko.").xlsx");
	    //unduh file
	    $objWriter->save("php://output");	
	}

	function exportPdf(){
		$idToko = $_GET['idToko'];
		$namaToko = $this->model1->nama_toko($idToko);

		$pdf = new FPDF('P','mm','A4');

		$pdf->AddPage();
		// setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        // mencetak string 
        $pdf->Cell(190,7,'LAPORAN DATA STOK PERTOKO',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,$namaToko,0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,'Periode '.date('d M Y'),0,1,'C');

        //add space
        $pdf->Cell(10,7,'',0,1);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(10,6,'No',1,0);
        $pdf->Cell(30,6,'SKU',1,0);
        $pdf->Cell(40,6,'Nama Produk',1,0);
        $pdf->Cell(30,6,'Kategori',1,0);
        $pdf->Cell(20,6,'Tempat',1,0);
        $pdf->Cell(20,6,'Harga Beli',1,0);
        $pdf->Cell(20,6,'Harga Jual',1,0);
        $pdf->Cell(20,6,'Stok Akhir',1,1);
        $pdf->SetFont('Arial','',8);

        $dataStok = $this->modelProduk->dataStokTokoFullExport($idToko)->result();

        $i=1;
        foreach($dataStok as $row){
        	$harga_jual = $this->modelProduk->hargaJual($row->id_produk,$idToko);

        	$pdf->Cell(10,6,$i,1,0);
	        $pdf->Cell(30,6,$row->id_produk,1,0);
	        $pdf->Cell(40,6,$row->nama_produk,1,0);
	        $pdf->Cell(30,6,$row->kategori.'-'.$row->kategori_level_1.'-'.$row->kategori_3,1,0);
	        $pdf->Cell(20,6,$row->stand,1,0);
	        $pdf->Cell(20,6,number_format($row->harga_beli,'0',',','.'),1,0);
	        $pdf->Cell(20,6,number_format($harga_jual,'0',',','.'),1,0);
	        $pdf->Cell(20,6,$row->stok,1,1);
        $i++; }

        $pdf->Output();
	}

	function exportPdfTokoFilter(){
		$idToko  		= $_GET['idToko'];
		$idKategori 	= $_GET['idKategori'];
		$subkategori 	= $_GET['subkategori'];
		$subSubKategori	= $_GET['subSubKategori'];
		$stokSign 		= $_GET['stokSign'];
		$stokValue 		= $_GET['stokValue'];
		$priceSign 		= $_GET['priceSign'];
		$priceSignValue = $_GET['priceSignValue'];
		$idStand 		= $_GET['idStand'];
		$salePriceSign 	= $_GET['salePriceSign'];
		$salePriceValue = $_GET['salePriceValue'];
		$idSupplier		= $_GET['idSupplier'];

		$namaToko = $this->model1->nama_toko($idToko);

		$pdf = new FPDF('P','mm','A4');

		$pdf->AddPage();
		// setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        // mencetak string 
        $pdf->Cell(190,7,'LAPORAN DATA STOK PERTOKO',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,$namaToko,0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,'Periode '.date('d M Y'),0,1,'C');

        //add space
        $pdf->Cell(10,7,'',0,1);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(10,6,'No',1,0);
        $pdf->Cell(30,6,'SKU',1,0);
        $pdf->Cell(40,6,'Nama Produk',1,0);
        $pdf->Cell(30,6,'Kategori',1,0);
        $pdf->Cell(20,6,'Tempat',1,0);
        $pdf->Cell(20,6,'Harga Beli',1,0);
        $pdf->Cell(20,6,'Harga Jual',1,0);
        $pdf->Cell(20,6,'Stok Akhir',1,1);
        $pdf->SetFont('Arial','',8);

        $dataStok = $this->modelDataStok->dataStokTokoFilterExport($idToko,$idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue,$idStand,$salePriceSign,$salePriceValue,$idSupplier)->result();

        $i=1;
        foreach($dataStok as $row){
        	$harga_jual = $this->modelProduk->hargaJual($row->id_produk,$idToko);

        	$pdf->Cell(10,6,$i,1,0);
	        $pdf->Cell(30,6,$row->id_produk,1,0);
	        $pdf->Cell(40,6,$row->nama_produk,1,0);
	        $pdf->Cell(30,6,$row->kategori.'-'.$row->kategori_level_1.'-'.$row->kategori_3,1,0);
	        $pdf->Cell(20,6,$row->stand,1,0);
	        $pdf->Cell(20,6,number_format($row->harga_beli,'0',',','.'),1,0);
	        $pdf->Cell(20,6,number_format($harga_jual,'0',',','.'),1,0);
	        $pdf->Cell(20,6,$row->stok,1,1);
        $i++; }

        $pdf->Output();
	}

	function dataStokTokoFilter(){
		if(empty($_POST['subkategori'])){
			$subkategori = '';
		} else {
			$subkategori = $_POST['subkategori'];
		}

		if(empty($_POST['subSubKategori'])){
			$subSubKategori = '';
		} else {
			$subSubKategori = $_POST['subSubKategori'];
		}

		$data['idSupplier'] 	= $_POST['idSupplier'];
		$data['idKategori'] 	= $_POST['idKategori'];
		$data['subkategori'] 	= $subkategori;
		$data['subSubKategori']	= $subSubKategori;
		$data['stokSign'] 		= $_POST['stokSign'];
		$data['stokValue'] 		= $_POST['stokValue'];
		$data['priceSign'] 		= $_POST['priceSign'];
		$data['priceSignValue'] = $_POST['priceSignValue'];
		$data['idToko'] 		= $_POST['idToko'];
		$data['idStand'] 		= $_POST['idStand'];
		$data['salePriceSign'] 	= $_POST['salePriceSign'];
		$data['salePriceValue'] = $_POST['salePriceValue'];

		$query = $this->modelDataStok->totalProdukInventori($_POST['idToko'],$_POST['idKategori'],$subkategori,$subSubKategori,$_POST['stokSign'],$_POST['stokValue'],$_POST['priceSign'],$_POST['priceSignValue'],$_POST['idStand'],$_POST['salePriceSign'],$_POST['salePriceValue'],$_POST['idSupplier']);

		$data['nama_distributor'] 		= $this->model1->nama_toko($_POST['idToko']);


		$data['nilai_toko'] 		= $query->result_array();
		$this->load->view("data_stok/dataStokTokoFilter",$data);
	}

	function buttonExportToko(){
		if(empty($_POST['subkategori'])){
			$subkategori = '';
		} else {
			$subkategori = $_POST['subkategori'];
		}

		if(empty($_POST['subSubKategori'])){
			$subSubKategori = '';
		} else {
			$subSubKategori = $_POST['subSubKategori'];
		}

		$data['idKategori'] 	= $_POST['idKategori'];
		$data['subkategori'] 	= $subkategori;
		$data['subSubKategori']	= $subSubKategori;
		$data['stokSign'] 		= $_POST['stokSign'];
		$data['stokValue'] 		= $_POST['stokValue'];
		$data['priceSign'] 		= $_POST['priceSign'];
		$data['priceSignValue'] = $_POST['priceSignValue'];
		$data['idToko'] 		= $_POST['idToko'];
		$data['salePriceSign']	= $_POST['salePriceSign'];
		$data['salePriceValue'] = $_POST['salePriceValue']; 
		$data['idStand'] 		= $_POST['idStand'];
		$data['idSupplier'] 	= $_POST['idSupplier'];

		$this->load->view("data_stok/buttonExportToko",$data);
	}

}