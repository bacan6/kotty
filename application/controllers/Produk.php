<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . '/third_party/fpdf/fpdf.php';
require APPPATH . '/libraries/BaseController.php';

class Produk extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model(array('model1','modelProduk'));
		$this->load->library("session");

		$this->isLoggedIn($this->global['idUser'],2,1);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Produk";
		$data['ap_kategori'] = $this->db->get("ap_kategori");
		$data['brand'] = $this->db->get("brand");
		$data['store'] = $this->db->get("ap_store");
		$data['id_toko'] = $this->global['idStore'];
		if (isset($_GET['id_kategori'])){
			$id_kategori = $_GET['id_kategori'];
			$_SESSION['id_kategori']=$id_kategori;
		$data['ap_kategori_1'] = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori));	
		}
		if (isset($_GET['sub_kategori'])){
			$_SESSION['sub_kategori']=$_GET['sub_kategori'];
		}
		if (isset($_GET['toko'])){
			$_SESSION['toko']=$_GET['toko'];
		}
		if (isset($_GET['id_brand'])){
			$_SESSION['id_brand_produk']=$_GET['id_brand'];
		}
		
		$this->loadViews("produk/body_produk",$this->global,$data,"produk/footerProduk");
	}

	function datatablesProduk(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		if(isset($_SESSION['toko'])){
			$idToko = $_SESSION['toko'];
		}else $idToko = '';
		
		$id_brand = isset($_SESSION['id_brand_produk'])? $_SESSION['id_brand_produk']:
						'';
		// $id_brand = isset($_POST['id_brand'])? $_POST['id_brand']:
		// 				$id_brand;
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
			$query = $this->modelProduk->daftarProdukAll($length,$start,$search,$id_kategori,$sub_kategori,$idToko,$id_brand);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelProduk->daftarProdukAll($length,$start,$search,$id_kategori,$sub_kategori,$idToko,$id_brand);
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
            
			if ($this->global['isAdmin']==1){
                $output['data'][]=array($nomor_urut,$dt['id_produk'],'<b>'.$dt['nama_produk'].'</b><br><small>'.$dt['brand'].' | '.$dt['supplier'].'</small>',$dt['satuan'],number_format($dt['hpp']),'Rp'.number_format($dt['harga'],0,',','.'),number_format($dt['margin'],2),$dt['kategori']." / ".$dt['kategori_level_1']." / ".$dt['kategori_3'],$status,"<a href='".base_url('produk/upload_foto?sku='.$dt['id_produk'])."'><i class='fa fa-picture-o'></i></a> <br><a href='".base_url('produk/edit_produk?sku='.$dt['id_produk'])."'><i class='fa fa-pencil'></i></a> <br><a onclick=\"return confirm('Yakin Hapus Data?')\" href='".base_url('produk/hapus_produk?sku='.$dt['id_produk'])."'><i class='fa fa-trash'></i></a>");
            }else{
                $output['data'][]=array($nomor_urut,$dt['id_produk'],'<b>'.$dt['nama_produk'].'</b><br><small>'.$dt['brand'].' | '.$dt['supplier'].'</small>',$dt['satuan'],number_format($dt['hpp']),'Rp'.number_format($dt['harga'],0,',','.'),number_format($dt['margin'],2),$dt['kategori']." / ".$dt['kategori_level_1']." / ".$dt['kategori_3'],$status,"");
            }
			
		$nomor_urut++;
		}

		echo json_encode($output);
	}

	function hapus_foto(){
		$this->load->model("model_file");
		$id = $_POST['idfile'];
		$file = $this->model_file->getRow($id);
		var_dump($file.$id);
		unlink('uploads/files/'.$file['file_name']) or die('error'.'uploads/files/'.$file[0]['file_name']);
		$this->model_file->hapus_file($id);
	}
	function upload_foto(){
		$this->load->model("model_file");
		
		$data = array(); 
        $errorUploadType = $statusMsg = ''; 
		$sku 		= $this->input->get("sku");
         
        // If file upload form submitted 
        if($this->input->post('fileSubmit')){ 
             
            // If files are selected to upload 
            if(!empty($_FILES['files']['name']) && count(array_filter($_FILES['files']['name'])) > 0){ 
                $filesCount = count($_FILES['files']['name']); 
                for($i = 0; $i < $filesCount; $i++){ 
                    $_FILES['file']['name']     = $_FILES['files']['name'][$i]; 
                    $_FILES['file']['type']     = $_FILES['files']['type'][$i]; 
                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i]; 
                    $_FILES['file']['error']     = $_FILES['files']['error'][$i]; 
                    $_FILES['file']['size']     = $_FILES['files']['size'][$i]; 
                     
                    // File upload configuration 
                    $uploadPath = 'uploads/files/'; 
                    $config['upload_path'] = $uploadPath; 
                    $config['allowed_types'] = 'jpg|jpeg|png|gif'; 
                    //$config['max_size']    = '100'; 
                    //$config['max_width'] = '1024'; 
                    //$config['max_height'] = '768'; 
                     
                    // Load and initialize upload library 
                    $this->load->library('upload', $config); 
                    $this->upload->initialize($config); 
                     
                    // Upload file to server 
                    if($this->upload->do_upload('file')){ 
                        // Uploaded file data 
                        $fileData = $this->upload->data(); 
						$config['image_library'] = 'gd2';
						$config['source_image'] = $fileData['full_path']; //get original image
						$config['maintain_ratio'] = TRUE;
						$config['width'] = 600;
						$config['height'] = 480;

						// watermark
						// $config['wm_type'] = 'overlay';
						// $config['wm_overlay_path'] = APPPATH.'/third_party/logo_small.png';
						// $config['wm_opacity'] = '10';
						// $config['wm_vrt_alignment'] = 'bottom';
						// $config['wm_hor_alignment'] = 'right';
						// $config['wm_padding'] = '-10';
						// $config['wm_x_transp'] = '4';
						// $config['wm_y_transp'] = '4';
						
						$this->load->library('image_lib', $config);
						if (!$this->image_lib->resize()) {
							$this->handle_error($this->image_lib->display_errors());
						}

						$this->image_lib->initialize($config);
						//$this->image_lib->watermark();
						
                        $uploadData[$i]['file_name'] = $fileData['file_name'];
						$uploadData[$i]['id_produk'] = $sku;
                        $uploadData[$i]['uploaded_on'] = date("Y-m-d H:i:s"); 
                    }else{  
                        $errorUploadType .= $_FILES['file']['name'].' | ';  
                    } 
                } 
                 
                $errorUploadType = !empty($errorUploadType)?'<br/>File Type Error: '.trim($errorUploadType, ' | '):''; 
                if(!empty($uploadData)){ 
                    // Insert files data into the database 
                    $insert = $this->model_file->insert($uploadData);
					$uris = current_url().'?sku='.$sku; 
                    $reload = "<script>window.location='$uris';</script>";
                    // Upload status message 
                    $statusMsg = $insert?'Files uploaded successfully!'.$reload.$errorUploadType:'Some problem occurred, please try again.'; 
                }else{ 
                    $statusMsg = "Sorry, there was an error uploading your file.".$errorUploadType; 
                } 
            }else{ 
                $statusMsg = 'Please select image files to upload.'; 
            } 
        } 
         
        // Get files data from the database 
        $data['files'] = $this->model_file->getRows($sku); 
         
        // Pass the files data to view 
        $data['statusMsg'] = $statusMsg; 
		$this->global['pageTitle'] = "SOLUSI POS - Foto Produk";
		//$this->load->view('upload_files/index',$data);
		$this->loadViews('upload_files/index',$this->global,$data,"upload_files/footer");
		//$this->load->view('upload_files/index',$data);
	}

	function add_produk(){
		$this->global['pageTitle'] = "SOLUSI POS - Tambah Produk";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data['store'] = $this->db->get("ap_store")->result();
		$data['satuan'] = $this->db->get("satuan")->result();
		$data['stand'] = $this->db->get("ap_stand")->result();
		$data['show_kategori'] = $this->db->get("ap_kategori");
		$data['show_supplier'] = $this->db->order_by("supplier","ASC")->get("supplier");
		$data['show_brand'] = $this->db->order_by("brand","ASC")->get("brand");
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
	function getNewSKU(){
		$id_subkategori_3 	= $_POST['id_subkategori_3'];

		$this->load->model("modelProduk");
		$count = $this->modelProduk->getNewSKU($id_subkategori_3);

		echo str_pad($count+1, 5, "0", STR_PAD_LEFT);
	}

	function tambahProdukNonProduksiSQL(){
		$id_produk	 	= $_POST['sku'];
		$qr_code	 	= $_POST['qr_code'];
		$nama_produk 	= $_POST['namaProduk'];
		$isi 			= $_POST['isi'];
		$kategori 		= $_POST['kategori'];
		//$harga_beli 	= $_POST['hargaBeli'];
		$satuan 		= $_POST['satuan'];
		$tempat 		= $_POST['tempat'];
		$brand 			= $_POST['brand'];
        
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
									"id_produk"			=> trim(preg_replace('/^[\p{Z}\s]+|[\p{Z}\s]+$/u', '', $id_produk)),
									"nama_produk"		=> $nama_produk,
									"isi"				=> $isi,
									"diskon"			=> 0,
									"id_kategori"		=> $kategori,
									"id_subkategori"	=> $subkategori,
									"id_subkategori_2"	=> $subkategori3,
									"status"			=> 1,
									"satuan"			=> $satuan,
									"stok"				=> 0,
									"type"				=> 1,
									"tempat"			=> $tempat,
									"id_brand"			=> $brand,
									"qr_code"			=> $qr_code
								);

		$this->modelProduk->insertProduk($data_upload);
		
		$hargaJualJSON 	= $_POST['hargaJual'];

		$decodeJSON = json_decode(stripcslashes($hargaJualJSON));

		foreach($decodeJSON as $dt){
			$idStore 	= $dt->idStore;
			$hargaJual 	= $dt->hargaJual;
            $hargaBeli 	= $dt->hargaBeli;
			$supplier 	= $dt->supplier;

			$dataHarga[] = array(
									"id_toko"		=> $idStore,
									"id_produk"		=> $id_produk,
									"harga"			=> $hargaJual,
                                    "hpp"			=> $hargaBeli
							    );
            $dataStock[] = array(
                            "id_produk"		=> $id_produk,
				            "id_store"		=> $idStore,
							"harga"			=> $hargaJual,
                            "hpp"			=> $hargaBeli,
                            "stok"  => 0,
                            "min" => 0,
                            "max" => 0
                        );
			$countIfSupplierExist = $this->modelProduk->countIfSupplierExist($idStore,$id_produk);
			if ($countIfSupplierExist>0){
				// update
				$dataSupplier = array(
										"id_produk" 	=> $id_produk,
										"id_supplier" 	=> $supplier
				);
				$this->modelProduk->updateSupplier($id_produk,$idStore,$dataSupplier);
			}else{
				// insert
				$dataSupplier = array(
										"id_produk" 	=> $id_produk,
										"id_supplier" 	=> $supplier,
										"id_toko" 		=> $idStore
				);
				$this->modelProduk->insertSupplier($dataSupplier);
			}
		}
		$countIfStoreExist = $this->modelProduk->countIfStoreExist($idStore,$id_produk);
		if($countIfStoreExist==0){
			$this->modelProduk->insertHargaJual($dataHarga);
		}
		
		$countIfStoreExist = $this->modelProduk->countIfStockExist($idStore,$id_produk);
		if($countIfStoreExist==0){
			$this->modelProduk->insertStock($dataStock);
		}
	}

	function form_produk_non_produksi(){
		$data['store'] = $this->db->get("ap_store")->result();
		$data['satuan'] = $this->db->get("satuan")->result();
		$data['stand'] = $this->db->get("ap_stand")->result();
		$data['show_kategori'] = $this->db->get("ap_kategori");
		$data['show_supplier'] = $this->db->order_by("supplier","ASC")->get("supplier");
		$data['show_brand'] = $this->db->order_by("brand","ASC")->get("brand");
		$this->load->view("produk/form_produk_non_produksi",$data);
	}

	function edit_produk(){
		$data['show_kategori'] = $this->db->get("ap_kategori");

		$data['show_brand'] = $this->db->order_by("brand","ASC")->get("brand");
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

	function editProdukNonProduksiSQL(){
		$id_produk	 	= $_POST['sku'];
		$qr_code	 	= $_POST['qr_code'];
		$nama_produk 	= $_POST['namaProduk'];
		$isi 			= $_POST['isi'];
		$kategori 		= $_POST['kategori'];
		$subkategori    = $_POST['kategori2'];
		$subkategori3  	= $_POST['kategori3'];
		$harga_beli 	= $_POST['hargaBeli'];
		$satuan 		= $_POST['satuan'];
		$tempat 		= $_POST['tempat'];
		$status 		= $_POST['status'];
		$brand 			= $_POST['brand'];
		$sku_awal		= $_POST['sku_awal'];


		$data_upload = array(
									"nama_produk"		=> $nama_produk,
									"isi"				=> $isi,
									"diskon"			=> 1,
									"id_kategori"		=> $kategori,
									"id_subkategori"	=> $subkategori,
									"id_subkategori_2"	=> $subkategori3,
									"status"			=> $status,
									"satuan"			=> $satuan, 
									"type"				=> 1,
									"tempat"			=> $tempat,
									"id_brand"			=> $brand,
									"id_produk"			=> $id_produk,
									"qr_code"			=> $qr_code
								);

		$this->modelProduk->updateProduk($sku_awal,$data_upload);
		
		$hargaJualJSON 	= $_POST['hargaJual'];

		$decodeJSON = json_decode(stripcslashes($hargaJualJSON));

		$this->load->model("modelProduk");

		foreach($decodeJSON as $dt){
			$idStore 	= $dt->idStore;
			$hargaJual 	= $dt->hargaJual;
            $hargaBeli 	= $dt->hargaBeli;
			$supplier 	= $dt->supplier;


			$countIfStoreExist = $this->modelProduk->countIfStoreExist($idStore,$sku_awal);

			if ($sku_awal!=$id_produk){
				$dataItem = array(
										"sku" => $id_produk
				);
				$this->modelProduk->updateSkuPO($sku_awal,$dataItem);
				$this->modelProduk->updateSkuReceived($sku_awal,$dataItem);
				$this->modelProduk->updateSkuRetur($sku_awal,$dataItem);
				$dataItem2 = array(
										"id_produk" => $id_produk
				);
				$this->modelProduk->updateSkuInvoice($sku_awal,$dataItem2);
			}

			$countIfSupplierExist = $this->modelProduk->countIfSupplierExist($idStore,$sku_awal);
			if ($countIfSupplierExist>0){
				// update
				$dataSupplier = array(
										"id_produk" 	=> $id_produk,
										"id_supplier" 	=> $supplier
				);
				$this->modelProduk->updateSupplier($sku_awal,$idStore,$dataSupplier);
			}else{
				// insert
				$dataSupplier = array(
										"id_produk" 	=> $id_produk,
										"id_supplier" 	=> $supplier,
										"id_toko" 		=> $idStore
				);
				$this->modelProduk->insertSupplier($dataSupplier);
			}

			if($countIfStoreExist > 0){
				$dataHarga = array(
										"harga"			=> $hargaJual,
                                        "hpp"			=> $hargaBeli,
										"id_produk"		=> $id_produk
								    );

				$this->modelProduk->updateHargaPertoko($idStore,$sku_awal,$dataHarga);

				$dataStock = array(
                            "id_produk"		=> $id_produk,
				            "id_store"		=> $idStore,
                            "stok"  => 0,
                            "min" => 0,
                            "max" => 0
                        );
				
				if ($sku_awal==$id_produk){
					$countIfStoreExist2 = $this->modelProduk->countIfStockExist($idStore,$id_produk);
					if($countIfStoreExist2==0){
						$this->modelProduk->insertStock($dataStock);
					}
				}else{
					$dataStok = array(
										"harga"			=> $hargaJual,
                                        "hpp"			=> $hargaBeli,
										"id_produk"		=> $id_produk
								    );
					$countIfStoreExist2 = $this->modelProduk->countIfStockExist($idStore,$sku_awal);
					if($countIfStoreExist2==0){
						$this->modelProduk->insertStock($dataStock);
					}else{
						$this->modelProduk->updateStokPertoko($idStore,$sku_awal,$dataStok);
					}
				}
				
			} else {
				$dataHarga = array(
										"id_toko"		=> $idStore,
										"id_produk"		=> $id_produk,
										"harga"			=> $hargaJual,
                                        "hpp"			=> $hargaBeli
								   );

				$this->modelProduk->insertNewHargaPertoko($dataHarga);


				$dataStock = array(
                            "id_produk"		=> $id_produk,
				            "id_store"		=> $idStore,
                            "stok"  => 0,
                            "min" => 0,
                            "max" => 0
                        );
		
				if ($sku_awal==$id_produk){
					$countIfStoreExist2 = $this->modelProduk->countIfStockExist($idStore,$id_produk);
					if($countIfStoreExist2==0){
						$this->modelProduk->insertStock($dataStock);
					}
				}else{
					$dataStok = array(
										"harga"			=> $hargaJual,
                                        "hpp"			=> $hargaBeli,
										"id_produk"		=> $id_produk
								    );
					$countIfStoreExist2 = $this->modelProduk->countIfStockExist($idStore,$sku_awal);
					if($countIfStoreExist2==0){
						$this->modelProduk->insertStock($dataStock);
					}else{
						$this->modelProduk->updateStokPertoko($idStore,$sku_awal,$dataStok);
					}
				}
			}	
		}
		redirect("produk");
	}

	function hapus_produk(){
		$sku 		= $this->input->get("sku");

		$updateDataProduk = array(
									"status" => 2
						   );

		$this->modelProduk->hapusProduk($sku,$updateDataProduk);
		redirect("produk");
	}

	function exportExcelProduk(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Kategori')
									  ->setCellValue('E1','Brand')
									  ->setCellValue('F1','Harga Beli')
									  ->setCellValue('G1','Harga Jual')	
									  ->setCellValue('H1','Status');

		$data_stok = $this->model1->daftarProdukAll($this->global['idStore']);

		$i=2;
		foreach($data_stok as $row){

			$status = $row->status === '1' ? "Aktif" : "Non Aktif";


			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->kategori." / ".$row->kategori_level_1." / ".$row->kategori_3)
									 ->setCellValue('E'.$i,$row->brand) 
									  ->setCellValue('F'.$i,$row->hpp)
									  ->setCellValue('G'.$i,$row->harga)
									  ->setCellValue('H'.$i,$status);
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
	    header('Content-Disposition: attachment;filename=ExportDataProduk.xlsx');
	    //unduh file

	    $objWriter->save("php://output");	
	}

	function formEditNonProduksi(){
		$data['show_kategori'] = $this->db->get("ap_kategori");
		$data['show_supplier'] = $this->db->order_by("supplier","ASC")->get("supplier");
		$data['show_brand'] = $this->db->order_by("brand","ASC")->get("brand");
		$data['satuan'] = $this->db->get("satuan");
		$data['stand'] = $this->db->get("ap_stand")->result();
        
        
		/*if ($this->global['idUser']!=1 && $this->global['idUser']!=22 
			&& $this->global['idUser']!=51
			&& $this->global['idUser']!=45
			&& $this->global['idUser']!=59){*/
            //$data['store'] = $this->db->get_where("ap_store",array("id_store" => $this->global['idStore']))->result();
        //}else{
            $data['store'] = $this->db->get("ap_store")->result();    
        //}
		

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
		}else if($uri=='supplier'){
			$data['pageTitle'] = "SOLUSI POS - Mass Update Supplier";
			$this->load->view("navigation",$data);
			$this->massUpdateSupplier();
		}else if($uri=='nama_produk'){
			$data['pageTitle'] = "SOLUSI POS - Mass Update Nama Produk";
			$this->load->view("navigation",$data);
			$this->massUpdateNama();
		}
	}	

	function massUpdateNama(){
		$this->load->view("produk/massupdate/bodyMassNama");
		$this->load->view("produk/massupdate/footerMassNama");
	}
	function massUpdateKategori(){
		$this->load->view("produk/massupdate/bodyMassKategori");
		$this->load->view("produk/massupdate/footerMassKategori");
	}

	function massUpdateSupplier(){
		$this->load->view("produk/massupdate/bodyMassSupplier");
		$this->load->view("produk/massupdate/footerMassSupplier");
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
		$data['brand'] = $this->db->get("brand")->result();
		$this->load->view("produk/massupdate/bodyMassHargaJual",$data);
		$this->load->view("produk/massupdate/footerMassHargaJual");
	}

	function templateUpdateKategori(){
		 // ini_set('display_errors', 1);
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Departemen')
									  ->setCellValue('E1','ID Departemen')	
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
	  ob_end_clean();
	   	//sesuaikan headernya 
	   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.ms-excel');
	    //ubah nama file saat diunduh
	    header('Content-Disposition: attachment;filename=Mass Update Kategori Template.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}
	function templateUpdateNama(){
		// ini_set('display_errors', 1);
	   $this->load->library("excel/PHPExcel");

	   $objPHPExcel = new PHPExcel();

	   $objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									 ->setCellValue('B1','SKU')
									 ->setCellValue('C1','Nama Produk');
  
	   //set title pada sheet (me rename nama sheet)
		 $objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	   // Set document properties
	   $objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
							   ->setLastModifiedBy("Arisal Yanuarafi")
							   ->setTitle("SOLUSI POS | IT Solutions")
							   ->setSubject("SOLUSI POS | IT Solutions")
							   ->setDescription("Export Data")
							   ->setKeywords("office 2007 openxml php")
							   ->setCategory("Data Nama Produk");
	
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
	   header('Content-Disposition: attachment;filename=Mass Update Nama Produk Template.xlsx');
	   //unduh file
	   $objWriter->save("php://output");
   }

	function templateUpdateSupplier(){
		 // ini_set('display_errors', 1);
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Brand')
									  ->setCellValue('E1','Supplier')	
									  ->setCellValue('F1','ID Supplier');

		$data_stok = $this->modelProduk->exportTemplateSupplier($this->global['idStore']);

		$i=2;
		foreach($data_stok as $row){

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->brand)
									  ->setCellValue('E'.$i,$row->supplier)
									  ->setCellValue('F'.$i,$row->id_supplier);
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
								->setCategory("Data SUPPLIER");
	 
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
	    header('Content-Disposition: attachment;filename=Mass Update Supplier Template.xlsx');
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
        	$pdf->Cell(20,6,"(".$row->id_kategori.")",0,0);
	        $pdf->Cell(100,6,$row->kategori,0,1);

	        $subkategori = $this->db->get_where("ap_kategori_1",array("id_kategori" => $row->id_kategori))->result();
        
	        foreach($subkategori as $dt){
	        	$pdf->Cell(20,6,"            (".$dt->id.")",0,0);
	        	$pdf->Cell(100,6,"             ".$dt->kategori_level_1,0,1);

	        	$subsubkategori = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $dt->id))->result();

	        	foreach($subsubkategori as $bk){
	        		$pdf->Cell(20,6,"                          (".$bk->id.")",0,0);
	        		$pdf->Cell(100,6,"                        ".$bk->kategori_3,0,1);
	        	}
	        }
        }

        $pdf->Output();
	}

	function supplierProduk(){
		$pdf = new FPDF('P','mm','A4');

		$pdf->AddPage();
		// setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        // mencetak string 
        $pdf->Cell(190,7,'SUPPLIER LIST',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,date('d F Y'),0,1,'C');
        


        $kategori = $this->db->get("supplier")->result();

        $pdf->SetFont('Arial','',10);
        foreach($kategori as $row){
        	$pdf->Cell(20,6,"(".$row->id_supplier.")",0,0);
	        $pdf->Cell(100,6,$row->supplier,0,1);
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

	function massUpdateSupplierSQL(){
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
					$idSupplier = $row['F'];

					$dataUpdate = array(
											"id_supplier" => $idSupplier
									     );
					$this->modelProduk->updateSupplier($id_produk,$this->global['idStore'],$dataUpdate);
				}
			$i++; }
			
			unlink($file);
		}
		
	}

	function massUpdateNamaSQL(){
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
					$nama_produk = $row['C'];

					$dataUpdate = array(
											"nama_produk" => $nama_produk
									     );
					$this->db->where("id_produk",$id_produk);	
					$this->db->update("ap_produk",$dataUpdate);
				}
			$i++; }
			
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
									  ->setCellValue('H1','Harga Jual')
									  ->setCellValue('I1','Harga Member');

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
		$idBrand 			= $_POST['brand'];

		$data_stok = $this->modelProduk->exportTemplateHargaJual($idToko,$idKategori,$subkategori,$subSubKategori,$idStand,$idBrand);

		$i=2;
		foreach($data_stok as $row){

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->kategori." / ".$row->kategori_level_1." / ".$row->kategori_3)
									  ->setCellValue('E'.$i,$row->stand)
									  ->setCellValue('F'.$i,$row->id_toko)
									  ->setCellValue('G'.$i,$row->store)
									  ->setCellValue('H'.$i,$row->harga)
									  ->setCellValue('I'.$i,$row->harga_member);
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
					$hargaMember = $row['I'];
					$idToko = $row['F'];

					$dataUpdate = array(
											"harga"		=> $hargaJual,
											"harga_member" => $hargaMember
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