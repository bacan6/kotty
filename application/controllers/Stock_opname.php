<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Stock_opname extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelStockOpname"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],2,15);
	}

	function index(){
		$data['show_kategori'] = $this->db->get("ap_kategori")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Stock Opname";
		$this->loadViews("stock_opname/body_stock_opname",$this->global,$data,"stock_opname/footer_so");
	}
	
	function stock_opname_report(){
		$data['header'] = $this->db->get("ap_receipt");
		$no_so = $_GET['no_so'];
		$data['header_so'] = $this->db->get_where("stock_opname_info",array("no_so" => $no_so))->row();
		$type = $this->model1->header_type($no_so);
		$data['type'] = $type;
		$data['item_so'] = $this->model1->item_so($no_so,$type);
		$this->global['pageTitle'] = "SOLUSI POS - Laporan Stock Opname Gudang";
		$this->loadViews("stock_opname/body_stock_opname_report",$this->global,$data,"footer_empty");
	}

	function export_excel(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Bahan')
									  ->setCellValue('D1','Kategori')
									  ->setCellValue('E1','Last Stok')	
									  ->setCellValue('F1','Satuan')	
									  ->setCellValue('G1','Harga')	
									  ->setCellValue('H1','Stock Opname');	
		$data_stok = $this->model1->data_stok_all();

		$i=2;
		foreach($data_stok->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->sku, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_bahan)
									  ->setCellValue('D'.$i,$row->kategori)
									  ->setCellValue('E'.$i,$row->stok)
									  ->setCellValue('F'.$i,$row->satuan)
									  ->setCellValue('G'.$i,$row->harga)
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
	 
	   	//sesuaikan headernya 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    //ubah nama file saat diunduh
	    header('Content-Disposition: attachment;filename=ExportDataMaterial.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
								
	}

	function exportExcelFG(){
		$this->load->library("excel/PHPExcel");

		$kategori = $_POST['kategori'];

		if(!empty($_POST['subkategori'])){
			$subKategori = $_POST['subkategori'];
		} else {
			$subKategori = '';
		}

		if(!empty($_POST['subkategori2'])){
			$subKategori2 = $_POST['subkategori2'];
		} else {
			$subKategori2 = '';
		}

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Kategori')
									  ->setCellValue('E1','Last Stok')	
									  ->setCellValue('F1','Harga')	
									  ->setCellValue('G1','Stock Opname');	

		$data_stok = $this->modelStockOpname->dataStokFG($kategori,$subKategori,$subKategori2);

		$i=2;
		foreach($data_stok->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->kategori)
									  ->setCellValue('E'.$i,$row->stok)
									  ->setCellValue('F'.$i,$row->harga)
									  ->setCellValue('G'.$i,$row->stok);
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
	    header('Content-Disposition: attachment;filename=StockOpnameGudangTemplate.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}


	//UPLOAD DATA SO FINISH GOODS KE EXCEL
	function uploadFGSQL(){
		$config['upload_path'] = './excel/';
		$config['allowed_types'] = 'xls|xlsx';

		$this->load->library("upload",$config);

		if(! $this->upload->do_upload('file')){
			$error = array('error' => $this->upload->display_errors());

			echo $error['error'];
		} else {
			$bulan = date('m');
			$tahun = date('y');
			$year = date('Y');
			$hari = date('d');

			$id_user = $this->global['idUser'];

			$today = date('Y-m-d');

			$cek_no_so = $this->model1->cek_no_so($today,$id_user);

			$no_so = "SOWH-".$tahun.$bulan.$hari.$id_user.sprintf("%03d",$cek_no_so+1);


			//SISIPKAN INFORMASI SO
			$data_so = array(
							"no_so" 		=> $no_so,
							"tanggal"		=> date('Y-m-d'),
							"id_pic"		=> $id_user,
							"keterangan"	=> "SO By Excel",
							"type"			=> 1
						);

			$this->modelStockOpname->insertStockOpnameInfo($data_so);
			
			$upload_data = $this->upload->data();
			$this->load->library('excel/PHPExcel');

			$file =  $upload_data['full_path'];
			$objPHPExcel = PHPExcel_IOFactory::load($file);

			$sheets = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

			$i=1;
			foreach($sheets as $row){
				if($i>1){
					$sku  		 = $row['B'];
					$last_stok 	 = $row['E'];
					$new_stok 	 = $row['G'];	

					//INPUT DATA ITEM DAN SELISIH
					$data_item[] = array(
										"no_so"		=> $no_so,
										"sku" 		=> $sku,
										"last_stok" => $last_stok,
										"new_stok" 	=> $new_stok
							  	   );

					$data_stok[] = array(
									"id_produk" => $sku,
									"stok" 		=> $new_stok
							  );
				}
			$i++;}

			//INSERT BATCH ITEM DATA 
			$this->modelStockOpname->insertBatchSO($data_item);
			$this->modelStockOpname->updateBatchStok($data_stok);
			unlink($file);
		}
		
		echo $no_so;
    }
}