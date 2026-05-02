<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Stock_opname_toko extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelStockOpname"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,16);
	}

	function index(){
		$data['toko'] = $this->db->get("ap_store")->result();
		$data['show_kategori'] = $this->db->get("ap_kategori")->result();
		$data['stand'] = $this->db->get("ap_stand")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Stock Opname Toko";
		$this->loadViews("stock_opname/body_so_toko",$this->global,$data,"stock_opname/footerSOToko");
	}

	function download_format_so(){
		$this->load->library("excel/PHPExcel");
		$id_store = $_POST['id_store'];
		$stand = $_POST['tempat'];
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


		$nama_toko = $this->model1->nama_toko($id_store);

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','Kode Toko')
									  ->setCellValue('B1','Nama Toko')
									  ->setCellValue('C1','Tanggal');

		$objPHPExcel->getActiveSheet()->setCellValue('A2',$id_store)
									  ->setCellValue('B2',$nama_toko)
									  ->setCellValue('C2',date('d M Y H:i'));

		$objPHPExcel->getActiveSheet()->setCellValue('A3','No')
									  ->setCellValue('B3','SKU')
									  ->setCellValue('C3','Nama Produk')
									  ->setCellValue('D3','Kategori')
									  ->setCellValue('E3','Last Stok')	
									  ->setCellValue('F3','Harga Beli')	
									  ->setCellValue('G3','Stock Opname');	

		$this->load->model("modelProduk");
		

		$data_stok = $this->modelProduk->data_stok_distributor($id_store,$stand,$kategori,$subKategori,$subKategori2);

		$i=4;
		foreach($data_stok->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->kategori."/".$row->kategori_level_1)
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
	    header('Content-Disposition: attachment;filename=StockOpnameTokoTemplate.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function upload_so(){
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
			$no_so = "SOTK-".$tahun.$bulan.$hari.$id_user.sprintf("%03d",$cek_no_so+1);
			$upload_data = $this->upload->data();
			$this->load->library('excel/PHPExcel');
			$file =  $upload_data['full_path'];
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			$sheets = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

			$x = 1;
			foreach($sheets as $dt){
				if($x==2){
					$kode_toko = $dt['A'];
				}
			$x++; }

			//SISIPKAN INFORMASI SO
			$data_so = array(
							"no_so" 		=> $no_so,
							"tanggal"		=> date('Y-m-d'),
							"id_pic"		=> $id_user,
							"keterangan"	=> "SO By Excel",
							"type"			=> 2,
							"store"			=> $kode_toko
						);

			$this->modelStockOpname->insertStockOpnameInfoToko($data_so);

			$i=1;
			foreach($sheets as $row){
				if($i>3){
					$sku  		 = str_replace("'","",$row['B']);
					$last_stok 	 = $row['E'];
					$new_stok 	 = $row['G'];	
                    
                    $penjualan = $this->modelStockOpname->dataPenjualan($kode_toko,$sku);
                    $dijual=0;
                    foreach($penjualan->result() as $w){
                        $dijual+= $w->jumlah;
                    }
                    $new_stok = $new_stok - $dijual;
                    
					//INPUT DATA ITEM DAN SELISIH
					$data_item[] = array(
										"no_so"		=> $no_so,
										"sku" 		=> $sku,
										"last_stok" => $last_stok,
										"new_stok" 	=> $new_stok
							  	   );

					$data_stok = array(
									"stok" 		=> $new_stok
							  );

					$this->modelStockOpname->updateStokToko($kode_toko,$sku,$data_stok);
				}
			$i++;}

			//INSERT BATCH ITEM DATA 
			$this->modelStockOpname->insertBatchStokOpnameToko($data_item);
		}
		unlink($file);
		echo $no_so;
	}

	function stock_opname_report(){
		$data['header'] = $this->db->get("ap_receipt");

		$no_so = $_GET['no_so'];

		$data['header_so'] = $this->db->get_where("stock_opname_info",array("no_so" => $no_so))->row();

		$type = $this->model1->header_type($no_so);
		$data['type'] = $type;

		$data['item_so'] = $this->model1->item_so($no_so,$type);


		$this->global['pageTitle'] = "SOLUSI POS - Laporan Stock Opname Toko";
		$this->loadViews("stock_opname/body_stock_opname_report",$this->global,$data,"footer_empty");
	}

}