<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . '/third_party/fpdf/fpdf.php';
require APPPATH . '/libraries/BaseController.php';

class DataStokBahanBaku extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelDataStok"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,17);
	}

	function index(){
		$data['show_kategori'] = $this->db->get("kategori")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Data Stok Bahan Baku";
		$this->loadViews("data_stok_bahan_baku/bodyDataStokMaterial",$this->global,$data,"data_stok_bahan_baku/footerDataStokMaterial");
	}

	function filterBahanBaku(){
		$data['idKategori'] = $_POST['idKategori'];
		$data['stokSign'] 	= $_POST['stokSign'];
		$data['stokValue']	= $_POST['stokValue'];
		$data['priceSign']	= $_POST['priceSign'];
		$data['priceSignValue'] = $_POST['priceSignValue'];

		$this->load->view("data_stok_bahan_baku/filterBahanBaku",$data);
	}

	function datatablesBahanBaku(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelDataStok->totalProdukActiveMaterial();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelDataStok->dataStokBahanBakuDatatables($length,$start,$search);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelDataStok->dataStokBahanBakuDatatables($length,$start,$search);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,$dt['sku'],$dt['nama_bahan'],$dt['kategori'],number_format($dt['stok'],'0',',',''),number_format($dt['harga'],'0',',','.'));
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function datatablesBahanBakuFilter(){
		$idKategori = $_POST['idKategori'];
		$stokSign = $_POST['stokSign'];
		$stokValue = $_POST['stokValue'];
		$priceSign = $_POST['priceSign'];
		$priceSignValue = $_POST['priceSignValue'];

		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelDataStok->totalProdukActiveMaterialFilter($idKategori,$stokSign,$stokValue,$priceSign,$priceSignValue);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelDataStok->dataStokBahanBakuDatatablesFilter($length,$start,$search,$idKategori,$stokSign,$stokValue,$priceSign,$priceSignValue);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelDataStok->dataStokBahanBakuDatatablesFilter($length,$start,$search,$idKategori,$stokSign,$stokValue,$priceSign,$priceSignValue);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,$dt['sku'],$dt['nama_bahan'],$dt['kategori'],number_format($dt['stok'],'0',',',''),number_format($dt['harga'],'0',',','.'));
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function exportExcelBahanBaku(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','Kode Bahan')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Kategori')
									  ->setCellValue('E1','Harga Beli')	
									  ->setCellValue('F1','Stok Akhir');	

		$data_stok = $this->modelDataStok->dataStokBahanBakuActive();

		$i=2;
		foreach($data_stok->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValue('B'.$i,$row->sku)
									  ->setCellValue('C'.$i,$row->nama_bahan)
									  ->setCellValue('D'.$i,$row->kategori)
									  ->setCellValue('E'.$i,$row->harga)
									  ->setCellValue('F'.$i,$row->stok);
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
	 
	   	//sesuaikan headernya 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    //ubah nama file saat diunduh
	    header('Content-Disposition: attachment;filename=Data Stok Bahan Baku'.date('d/m/y').'.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function exportExcelBahanBakuFilter(){
		$this->load->library("excel/PHPExcel");
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','Nama Produk')
									  ->setCellValue('C1','Kategori')
									  ->setCellValue('D1','Harga Beli')	
									  ->setCellValue('E1','Stok Akhir');	

		$idKategori = $this->input->get("idKategori");
		$stokSign = $this->input->get("stokSign");
		$stokValue = $this->input->get("stokValue");
		$priceSign = $this->input->get("priceSign");
		$priceSignValue = $this->input->get("priceSignValue");		

		$data_stok = $this->modelDataStok->dataStokBahanBakuActiveFilter($idKategori,$stokSign,$stokValue,$priceSign,$priceSignValue);

		$i=2;
		foreach($data_stok->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValue('B'.$i,$row->nama_bahan)
									  ->setCellValue('C'.$i,$row->kategori)
									  ->setCellValue('D'.$i,$row->harga)
									  ->setCellValue('E'.$i,$row->stok);
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
	 
	   	//sesuaikan headernya 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    //ubah nama file saat diunduh
	    header('Content-Disposition: attachment;filename=Data Stok Bahan Baku'.date('d/m/y').'.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function exportPdf(){
		$pdf = new FPDF('P','mm','A4');

		$pdf->AddPage();
		// setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        // mencetak string 
        $pdf->Cell(190,7,'LAPORAN DATA STOK',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,'BAHAN BAKU',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,'Periode '.date('d M Y'),0,1,'C');

        //add space
        $pdf->Cell(10,7,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'No',1,0);
        $pdf->Cell(30,6,'Kode Bahan',1,0);
        $pdf->Cell(65,6,'Nama Produk',1,0);
        $pdf->Cell(40,6,'Kategori',1,0);
        $pdf->Cell(20,6,'Harga Beli',1,0);
        $pdf->Cell(30,6,'Stok Akhir',1,1);
        $pdf->SetFont('Arial','',10);

        $dataStok = $this->modelDataStok->dataStokBahanBakuActive()->result();

        $i=1;
        foreach($dataStok as $row){
        	$pdf->Cell(10,6,$i,1,0);
	        $pdf->Cell(30,6,$row->sku,1,0);
	        $pdf->Cell(65,6,$row->nama_bahan,1,0);
	        $pdf->Cell(40,6,$row->kategori,1,0);
	        $pdf->Cell(20,6,number_format($row->harga,'0',',','.'),1,0);
	        $pdf->Cell(30,6,$row->stok,1,1);
        $i++; }

        $pdf->Output();
	}

function exportPdfFilter(){
		$pdf = new FPDF('P','mm','A4');

		$pdf->AddPage();
		// setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        // mencetak string 
        $pdf->Cell(190,7,'LAPORAN DATA STOK',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,'BAHAN BAKU',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,'Periode '.date('d M Y'),0,1,'C');

        //add space
        $pdf->Cell(10,7,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'No',1,0);
        $pdf->Cell(30,6,'Kode Bahan',1,0);
        $pdf->Cell(65,6,'Nama Produk',1,0);
        $pdf->Cell(40,6,'Kategori',1,0);
        $pdf->Cell(20,6,'Harga Beli',1,0);
        $pdf->Cell(30,6,'Stok Akhir',1,1);
        $pdf->SetFont('Arial','',10);

        $idKategori = $this->input->get("idKategori");
		$stokSign = $this->input->get("stokSign");
		$stokValue = $this->input->get("stokValue");
		$priceSign = $this->input->get("priceSign");
		$priceSignValue = $this->input->get("priceSignValue");		

        $dataStok = $this->modelDataStok->dataStokBahanBakuActiveFilter($idKategori,$stokSign,$stokValue,$priceSign,$priceSignValue)->result();

        $i=1;
        foreach($dataStok as $row){
        	$pdf->Cell(10,6,$i,1,0);
	        $pdf->Cell(30,6,$row->sku,1,0);
	        $pdf->Cell(65,6,$row->nama_bahan,1,0);
	        $pdf->Cell(40,6,$row->kategori,1,0);
	        $pdf->Cell(20,6,number_format($row->harga,'0',',','.'),1,0);
	        $pdf->Cell(30,6,$row->stok,1,1);
        $i++; }

        $pdf->Output();
	}

	function buttonExportBahanBaku(){
		$data['idKategori'] 	= $_POST['idKategori'];
		$data['stokSign'] 		= $_POST['stokSign'];
		$data['stokValue'] 		= $_POST['stokValue'];
		$data['priceSign'] 		= $_POST['priceSign'];
		$data['priceSignValue'] = $_POST['priceSignValue'];
		$this->load->view("data_stok_bahan_baku/buttonExportMaterial",$data);
	}
}