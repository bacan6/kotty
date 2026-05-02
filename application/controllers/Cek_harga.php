<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . '/third_party/fpdf/fpdf.php';
require APPPATH . '/libraries/BaseController.php';

class Cek_harga extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model(array('model1','modelProduk'));
		$this->load->library("session");

		$this->isLoggedIn($this->global['idUser'],2,53);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Cek Harga";
		$data['ap_kategori'] = $this->db->get("ap_kategori");
		if (isset($_GET['id_kategori'])){
			$id_kategori = $_GET['id_kategori'];
			$_SESSION['id_kategori']=$id_kategori;
		$data['ap_kategori_1'] = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori));	
		}
		if (isset($_GET['sub_kategori'])){
			$_SESSION['sub_kategori']=$_GET['sub_kategori'];
		}
		if (isset($_POST['query'])){
			$_SESSION['query']=$_POST['query'];
		}
		
		$this->loadViews("produk/body_cek_produk",$this->global,$data,"produk/footerCekProduk");
	}

	function datatablesProduk(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= !empty($_SESSION['query'])? $_SESSION['query']:
						'jgkdoekekjgislfj';

		$idToko = $this->global['idStore'];

		$id_brand = isset($_SESSION['id_brand'])? $_SESSION['id_brand']:
						'';

		$id_kategori = isset($_SESSION['id_kategori'])? $_SESSION['id_kategori']:
						'';
		$sub_kategori = isset($_SESSION['sub_kategori'])? $_SESSION['sub_kategori']:
						'';

		$total 			 			= $this->modelProduk->totalProdukActive($id_kategori,$sub_kategori,$id_brand);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelProduk->daftarProdukAllCekHarga($length,$start,$search,$id_kategori,$sub_kategori,$idToko,$id_brand);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelProduk->daftarProdukAllCekHarga($length,$start,$search,$id_kategori,$sub_kategori,$idToko,$id_brand);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			if($dt['status']==1){
				$status = "<label class='label label-success'>Aktif</label>";
			} elseif($dt['status']==0) {
				$status = "<label class='label label-warning'>Non Aktif</label>";
			} else{
				$status = "";
			}

            $diskon_produk = $this->modelProduk->hargaPromo($dt['id_produk'],$idToko,$this->global['idUser'])+0;
			$strPersen = '';
			
			// custom 
			$data = $this->modelProduk->hargaPromoBrand($dt['id_produk'],$idToko);
			$discount = 0;$diskon_brand = 0;
			foreach($data as $dp){
				$rule = $dp->rules_type;
				$minBelanja = $dp->minBelanja;
				$discount = $dp->discount;
			}
			if ($discount>0){
				//'Count2Percent', 'Count2Price', 'Sum2Percent'
				if ($rule=='Count2Percent' || $rule=='Sum2Percent'){
					$diskon_brand = $dt['harga']*($discount/100);
					
				}else $diskon_brand = $discount;
			}
			$diskon = $diskon_produk>$diskon_brand? $diskon_produk: $diskon_brand;
			$hargaPromo = $dt['harga']-$diskon;
			$countPersen = ($diskon/$dt['harga'])*100;
			$strPersen = "(".number_format($countPersen,0)."%)";
			$str = $diskon>0? "<strike>Rp".number_format($dt['harga'],0)."</strike> $strPersen<br>":'';
            
                $output['data'][]=array('<p align=center>'.$dt['id_produk'].'<br><span style="font-size:30px;">'.$dt['nama_produk'].'</span><br><span style="font-size:60px;color:darkgrey">'.$str.'</span> <span style="font-size:130px;font-weight:bold">Rp '.number_format($hargaPromo,0,',','.').'</span><br><span style="font-size:23px;font-weight:bold">Stok tersedia: '.number_format($dt['stok'],0,',','.').' Pcs</span></p>');
            
			
		$nomor_urut++;
		}

		echo json_encode($output);
	}

	function add_produk(){
		$this->global['pageTitle'] = "SOLUSI POS - Tambah Produk";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data['store'] = $this->db->get("ap_store")->result();
		$data['satuan'] = $this->db->get("satuan")->result();
		$data['stand'] = $this->db->get("ap_stand")->result();
		$data['show_kategori'] = $this->db->get("ap_kategori");
		$data['show_supplier'] = $this->db->order_by("supplier","ASC")->get("supplier");
		$this->loadViews("produk/body_add_produk",$this->global,$data,"produk/footer_add_produk");
	}

	function get_subkategori(){
		$id_kategori = $_POST['id_kategori'];

		$data['show_sub'] = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori));

		$count = 0;

		foreach($data['show_sub']->result() as $row){
			$count = $count+$row->id;
		}

		if(!empty($_POST['id_kategori']) && $count > 0){
			$this->load->view("bahan_baku/show_sub",$data);
		}
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function cekSKUIfExist(){
		$sku 	= $_POST['sku'];

		$this->load->model("modelProduk");
		$count = $this->modelProduk->cekSKUIfExist($sku);

		echo $count;
	}

	function tambahProdukNonProduksiSQL(){
		$id_produk	 	= $_POST['sku'];
		$nama_produk 	= $_POST['namaProduk'];
		$kategori 		= $_POST['kategori'];
		//$harga_beli 	= $_POST['hargaBeli'];
		$satuan 		= $_POST['satuan'];
		$tempat 		= $_POST['tempat'];
        $supplier 		= $_POST['supplier'];
        
		if(empty($_POST['kategori2'])){
			$subkategori = '';
		} else {
			$subkategori = $_POST['kategori2'];
		}

		if(empty($_POST['kategori3'])){
			$subkategori3 = '';
		} else {
			$subkategori3 = $_POST['kategori3'];
		}


		$data_upload = array(
									"id_produk"			=> $id_produk,
									"nama_produk"		=> $nama_produk,
									"diskon"			=> 0,
									"id_kategori"		=> $kategori,
									"id_subkategori"	=> $subkategori,
									"id_subkategori_2"	=> $subkategori3,
									"status"			=> 1,
									"satuan"			=> $satuan,
									"stok"				=> 0,
									"type"				=> 1,
									"tempat"			=> $tempat,
                                    "id_supplier"		=> $supplier
								);

		$this->modelProduk->insertProduk($data_upload);
		
		$hargaJualJSON 	= $_POST['hargaJual'];

		$decodeJSON = json_decode(stripcslashes($hargaJualJSON));

		foreach($decodeJSON as $dt){
			$idStore 	= $dt->idStore;
			$hargaJual 	= $dt->hargaJual;
            $hargaBeli 	= $dt->hargaBeli;

			$dataHarga[] = array(
									"id_toko"		=> $idStore,
									"id_produk"		=> $id_produk,
									"harga"			=> $hargaJual,
                                    "hpp"			=> $hargaBeli
							    );
            $dataStock[] = array(
                            "id_produk"		=> $id_produk,
				            "id_store"		=> $idStore,
                            "stok"  => 0,
                            "min" => 0,
                            "max" => 0
                        );
		}

		$this->modelProduk->insertHargaJual($dataHarga);
        $this->modelProduk->insertStock($dataStock);
	}

	function form_produk_non_produksi(){
		$data['show_supplier'] = $this->db->from('supplier')->order_by('supplier', 'ASC')->get();
        $data['show_kategori'] = $this->db->get("ap_kategori");
		$data['sku'] = $this->model1->count_produk();
		$data['satuan'] = $this->db->get("satuan");
		$data['stand'] = $this->db->get("ap_stand")->result();
		$data['store'] = $this->db->get("ap_store")->result();
		$this->load->view("produk/form_produk_non_produksi",$data);
	}

	function edit_produk(){
		$data['show_kategori'] = $this->db->get("ap_kategori");

		$data['show_supplier'] = $this->db->order_by("supplier","ASC")->get("supplier");
		$data['satuan'] = $this->db->get("satuan");
		$data['stand'] = $this->db->get("ap_stand")->result();
		$data['store'] = $this->db->get("ap_store")->result();
		$sku = $_GET['sku'];
		$data['sku'] = $sku;
		$this->load->model("modelProduk");
		$data['produk'] = $this->modelProduk->produkJoin($sku);
		$this->global['pageTitle'] = "SOLUSI POS - Edit Produk";
		$this->loadViews("produk/body_edit_produk",$this->global,$data,"produk/footer_edit_produk");
	}

	

	function formEditNonProduksi(){
		$data['show_kategori'] = $this->db->get("ap_kategori");
		$data['show_supplier'] = $this->db->order_by("supplier","ASC")->get("supplier");
		$data['satuan'] = $this->db->get("satuan");
		$data['stand'] = $this->db->get("ap_stand")->result();
        
        
		if ($this->global['idUser']!=1 && $this->global['idUser']!=22 
			&& $this->global['idUser']!=51
			&& $this->global['idUser']!=45
			&& $this->global['idUser']!=59){
            $data['store'] = $this->db->get_where("ap_store",array("id_store" => $this->global['idStore']))->result();
        }else{
            $data['store'] = $this->db->get("ap_store")->result();    
        }
		

		$sku = $_POST['sku'];

		$data['sku'] = $sku;
        
        $data['idStore'] = $this->global['idStore'];
        $data['idUser'] = $this->global['idUser'];

		$this->load->model("modelProduk");
		$data['produk'] = $this->modelProduk->produkJoin($sku);

		$this->load->view("produk/editNonProduksi",$data);
	}

	function massUpdate(){
		$uri = $this->uri->segment(3);
		$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
		$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
		$data['navigation'] = $this->model1->callNavigation();

		if($uri=='kategori'){
			$data['pageTitle'] = "SOLUSI POS - Mass Update Department";
			$this->load->view("navigation",$data);
			$this->massUpdateKategori();
		} elseif($uri=='hargaBeli'){
			$data['pageTitle'] = "SOLUSI POS - Mass Update Harga Beli";
			$this->load->view("navigation",$data);
			$this->massUpdateHargaBeli();
		} elseif($uri=='hargaJual'){
			$data['pageTitle'] = "SOLUSI POS - Mass Update Harga Jual";
			$this->load->view("navigation",$data);
			$this->massUpdateHargaJual();
		} elseif($uri=='minMax'){
			$data['pageTitle'] = "SOLUSI POS - Mass Update Stok Min Max";
			$this->load->view("navigation",$data);
			$this->massUpdateMinMax();
		}
	}	

	function massUpdateKategori(){
		$this->load->view("produk/massupdate/bodyMassKategori");
		$this->load->view("produk/massupdate/footerMassKategori");
	}

	function massUpdateMinMax(){
		$this->load->view("produk/massupdate/bodyMassMinMax");
		$this->load->view("produk/massupdate/footerMassMinMax");
	}

	function massUpdateHargaBeli(){
		$this->load->view("produk/massupdate/bodyMassHargaBeli");
		$this->load->view("produk/massupdate/footerMassHargaBeli");
	}

	function massUpdateHargaJual(){
		$data['toko'] = $this->db->get("ap_store")->result();
		$data['show_kategori'] = $this->db->get("ap_kategori")->result();
		$data['stand'] = $this->db->get("ap_stand")->result();
		$this->load->view("produk/massupdate/bodyMassHargaJual",$data);
		$this->load->view("produk/massupdate/footerMassHargaJual");
	}

	function templateUpdateKategori(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Kategori')
									  ->setCellValue('E1','ID Kategori')	
									  ->setCellValue('F1','ID Subkategori')
									  ->setCellValue('G1','ID Subkategori');

		$data_stok = $this->modelProduk->exportTemplateKategori();

		$i=2;
		foreach($data_stok as $row){

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->kategori." / ".$row->kategori_level_1." / ".$row->kategori_3)
									  ->setCellValue('E'.$i,$row->id_kategori)
									  ->setCellValue('F'.$i,$row->id_subkategori)
									  ->setCellValue('G'.$i,$row->id_subkategori_2);
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
	    header('Content-Disposition: attachment;filename=Mass Update Kategori Template.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function templateUpdateMinMax(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Departemen')
									  ->setCellValue('E1','Stok Minimal')	
									  ->setCellValue('F1','Stok Maksimal');

		$idToko = $this->global['idStore'];
		
		$data_stok = $this->modelProduk->exportTemplateMinMax($idToko);

		$i=2;
		foreach($data_stok as $row){

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->kategori." / ".$row->kategori_level_1." / ".$row->kategori_3)
									  ->setCellValue('E'.$i,$row->min)
									  ->setCellValue('F'.$i,$row->max);
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
	    header('Content-Disposition: attachment;filename=Mass Update Kategori Template.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function templateUpdateHargaBeli(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Kategori')
									  ->setCellValue('E1','Harga Beli');

		$data_stok = $this->modelProduk->exportTemplateKategori();

		$i=2;
		foreach($data_stok as $row){

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->kategori." / ".$row->kategori_level_1." / ".$row->kategori_3)
									  ->setCellValue('E'.$i,$row->hpp);
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
	    header('Content-Disposition: attachment;filename=Mass Update Harga Beli Template.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function kategoriProdukJual(){
		$pdf = new FPDF('P','mm','A4');

		$pdf->AddPage();
		// setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        // mencetak string 
        $pdf->Cell(190,7,'DEPARTMENT TREE',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,date('d F Y'),0,1,'C');
        


        $kategori = $this->db->get("ap_kategori")->result();

        $pdf->SetFont('Arial','',10);
        foreach($kategori as $row){
        	$pdf->Cell(15,6,"(".$row->id_kategori.")",0,0);
	        $pdf->Cell(100,6,$row->kategori,0,1);

	        $subkategori = $this->db->get_where("ap_kategori_1",array("id_kategori" => $row->id_kategori))->result();
        
	        foreach($subkategori as $dt){
	        	$pdf->Cell(15,6,"            (".$dt->id.")",0,0);
	        	$pdf->Cell(100,6,"             ".$dt->kategori_level_1,0,1);

	        	$subsubkategori = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $dt->id))->result();

	        	foreach($subsubkategori as $bk){
	        		$pdf->Cell(15,6,"                          (".$bk->id.")",0,0);
	        		$pdf->Cell(100,6,"                        ".$bk->kategori_3,0,1);
	        	}
	        }
        }

        $pdf->Output();
	}

	function massUpdateKategoriSQL(){
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
			$dataUpdate = array();
			foreach($sheets as $row){
				if($i > 1){
					$id_produk= $row['B'];
					$idKategori = $row['E'];
					$subkategori = $row['F'];
					$subsubkategori = $row['G'];

					$dataUpdate[] = array(
											"id_produk" => $id_produk,
											"id_kategori" => $idKategori,
											"id_subkategori" => $subkategori,
											"id_subkategori_2" => $subsubkategori
									     );
				}
			$i++; }

			$this->db->update_batch('ap_produk',$dataUpdate,'id_produk');
			unlink($file);
		}
		
	}

	function massUpdateMinMaxSQL(){
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
			$dataUpdate = array();
			foreach($sheets as $row){
				if($i > 1){
					$id_produk= $row['B'];
					$min = $row['E'];
					$max = $row['F'];

					$dataUpdate[] = array(
											"id_produk" => $id_produk,
											"min" => $min,
											"max" => $max
									     );
				}
			$i++; }

			$idToko = $this->global['idStore'];

			$this->db->where("id_store",$idToko);
			$this->db->update_batch('stok_store',$dataUpdate,'id_produk');
			unlink($file);
		}
		
	}


	function massUpdateHargaBeliSQL(){
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
			$dataUpdate = array();
			foreach($sheets as $row){
				if($i > 1){
					$id_produk= $row['B'];
					$hargaBeli = $row['E'];

					$dataUpdate[] = array(
											"id_produk" => $id_produk,
											"hpp"		=> $hargaBeli
									     );
				}
			$i++; }

			$this->db->update_batch('ap_produk',$dataUpdate,'id_produk');
			unlink($file);
		}	
	}

	function templateUpdateHargaJual(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Kategori')
									  ->setCellValue('E1','Tempat')
									  ->setCellValue('F1','Kode Toko')
									  ->setCellValue('G1','Toko')	
									  ->setCellValue('H1','Harga Jual');

		$idKategori 		= $_POST['kategori'];

		if(!empty($_POST['subkategori_2'])){
			$subkategori = $_POST['subkategori_2'];
		} else {
			$subkategori = '';
		}

		if(!empty($_POST['subkategori_3'])){
			$subSubKategori 	= $_POST['subkategori_3'];
		} else {
			$subSubKategori = '';
		}

		$idToko 			= $_POST['toko'];
		$idStand 			= $_POST['stand'];

		$data_stok = $this->modelProduk->exportTemplateHargaJual($idToko,$idKategori,$subkategori,$subSubKategori,$idStand);

		$i=2;
		foreach($data_stok as $row){

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->kategori." / ".$row->kategori_level_1." / ".$row->kategori_3)
									  ->setCellValue('E'.$i,$row->stand)
									  ->setCellValue('F'.$i,$row->id_toko)
									  ->setCellValue('G'.$i,$row->store)
									  ->setCellValue('H'.$i,$row->harga);
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
	    header('Content-Disposition: attachment;filename=Mass Update Harga Jual Template.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function massUpdateHargaJualSQL(){
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
			$dataUpdate = array();
			foreach($sheets as $row){
				if($i > 1){
					$id_produk= $row['B'];
					$hargaJual = $row['H'];
					$idToko = $row['F'];

					$dataUpdate = array(
											"harga"		=> $hargaJual
									     );

					$this->db->where("id_toko",$idToko);
					$this->db->where("id_produk",$id_produk);
					$this->db->update("ap_produk_price",$dataUpdate);
				}
			$i++; }
			unlink($file);
		}
	}


}