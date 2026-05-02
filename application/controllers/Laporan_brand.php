<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Laporan_brand extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');

		//error_reporting(E_ALL);ini_set('display_errors', 1);
		
		$this->load->model(array("model1","modelLaporan","model_penjualan","modelDashboard"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],1,13);
	}

	function pembelian(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],1,13);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Pembelian";
				$this->loadViews("laporan/bodyLaporanPembelian",$this->global,NULL,"footer_empty");
			}
		}
	}



	function waste(){
		$this->global['pageTitle'] = "Solusinformatika.com - Laporan Waste";
		$this->loadViews("laporan/body_laporan_waste",$this->global,NULL,"laporan/footerWaste");
	}

	function viewReportWaste(){
		$dateStart 	 	= $_POST['dateStart'];
		$dateEnd 		= $_POST['dateEnd'];
		$idProduk 		= $_POST['idProduk'];

		$data['viewReport'] = $this->modelLaporan->viewReportWaste($dateStart,$dateEnd,$idProduk);
		$this->load->view("laporan/viewReportWaste",$data);
	}

	function stock_opname(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,24);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$total_rows = $this->model1->total_so();
				$this->load->library("pagination");
				$config['base_url'] 			= base_url('laporan/stock_opname/');
				$config['total_rows']			= $total_rows;
				$config["per_page"]				= $per_page = 25;
				$config["uri_segment"]			= 3;
				$config["full_tag_open"] 		= '<ul class="pagination">';
				$config["full_tag_close"] 		= '</ul>';
				$config["first_link"] 			= "&laquo;";
				$config["first_tag_open"] 		= "<li>";
				$config["first_tag_close"] 		= "</li>";
				$config["last_link"] 			= "&raquo;";
				$config["last_tag_open"] 		= "<li>";
				$config["last_tag_close"] 		= "</li>";
				$config['next_link'] 			= '&gt;';
				$config['next_tag_open'] 		= '<li>';
				$config['next_tag_close'] 		= '<li>';
				$config['prev_link'] 			= '&lt;';
				$config['prev_tag_open'] 		= '<li>';
				$config['prev_tag_close'] 		= '<li>';
				$config['cur_tag_open'] 		= '<li class="active"><a href="#">';
				$config['cur_tag_close'] 		= '</a></li>';
				$config['num_tag_open'] 		= '<li>';
				$config['num_tag_close'] 		= '</li>';

				$this->pagination->initialize($config);

				$data['paging'] = $this->pagination->create_links();
				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

				if(empty($_GET['query'])){
					$data['data_so'] = $this->model1->data_so_all($per_page,$page,$this->global['idStore'],$this->global['idUser']);
				} else {
					$query = $_GET['query'];
					$data['data_so'] = $this->model1->data_so_sort($per_page,$page,$query,$this->global['idStore'],$this->global['idUser']);
				}

				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Stock Opname";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/body_laporan_stock_opname_detail",$this->global,$data,"footer_empty");
			}
		}
	}

	function stock_opname_report(){
		$data['header'] = $this->db->get("ap_receipt");

		$no_so = $_GET['no_so'];

		$data['header_so'] = $this->db->get_where("stock_opname_info",array("no_so" => $no_so))->row();

		$type = $this->model1->header_type($no_so);
		$data['type'] = $type;

		$data['item_so'] = $this->model1->item_so($no_so,$type);

		$this->global['pageTitle'] = "Solusinformatika.com - Laporan Stock Opname";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("stock_opname/body_stock_opname_report",$this->global,$data,"footer_empty");
	}

	function launch_detail_barang(){
		$no_po = $_POST['id'];

		$data['purchase_item'] = $this->model1->purchase_item($no_po);
		$data['no_po'] = $no_po;
		$this->load->view("laporan/detail_barang_modal",$data);
	}


	function hutang_po(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,20);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['header'] = $this->db->get("ap_receipt")->row();
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Hutang Pembelian";
				$this->loadViews("laporan/body_hutang_po",$this->global,$data,"laporan/footerHutangPO");
			}
		}
	}

	function dataHutangPO(){
		$data['tagihan_hutang'] = $this->modelLaporan->hutang_ditagih('',$this->global['idStore']);
		$this->load->view("laporan/dataHutangPO",$data);
	}

	function dataHutangPOFilter(){
		$supplier = $_POST['supplier'];
		$tanggalPO = $_POST['tanggalPO'];
		$jatuhTempo = $_POST['jatuhTempo'];

		$data['tagihan_hutang'] = $this->modelLaporan->hutang_ditagih_filter($supplier,$tanggalPO,$jatuhTempo,$this->global['idStore']);
		$this->load->view("laporan/dataHutangPO",$data);
	}

	function hutang_jatuh_tempo(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,20);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Hutang Jatuh Tempo";
				$data['header'] = $this->db->get("ap_receipt")->row();
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->loadViews("laporan/body_hutang_jatuh_tempo",$this->global,$data,"laporan/footerHutangJatuhTempo");
			}
		}
	}

	function dataHutangJatuhTempo(){
		$data['hutang_jatuh_tempo'] = $this->modelLaporan->hutangJatuhTempo($this->global['idStore']);
		$this->load->view("laporan/dataHutangJatuhTempo",$data);
	}

	function dataHutangJatuhTempoFilter(){
		$supplier = $_POST['supplier'];
		$data['hutang_jatuh_tempo'] = $this->modelLaporan->hutangJatuhTempoFilter($supplier,$this->global['idStore']);
		$this->load->view("laporan/dataHutangJatuhTempo",$data);
	}

	function hutang_terbayar(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,20);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Hutang Terbayar";
				$data['supplier'] = $this->db->get("supplier")->result();
				$data['tipeBayar'] = $this->db->get("payment_type_debt")->result();
				$this->loadViews("laporan/body_hutang_terbayar",$this->global,$data,"laporan/footerHutangTerbayar");
			}
		}
	}

	function dataHutangTerbayar(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$supplier = $_POST['supplier'];
		$tipeBayar = $_POST['tipeBayar'];
		$noPO = $_POST['noPO'];
		$noPayment = $_POST['noPayment'];

		$data['hutang_terbayar'] = $this->modelLaporan->laporanHutangTerbayar($dateStart,$dateEnd,$supplier,$tipeBayar,$noPO,$noPayment,$this->global['idUser']);
		$this->load->view("laporan/dataHutangTerbayar",$data);
	}


	function analisa_umur_hutang(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,20);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "Solusinformatika.com - Analisa Umur Hutang";
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->loadViews("laporan/body_analisa_umur_hutang",$this->global,$data,"laporan/footerAnalisaUmurHutang");
			}
		}
	}

	function dataAnalisaUmurHutang(){

		if(!empty($_POST['supplier'])){
			$supplier = $_POST['supplier'];
		} else {
			$supplier = '';
		}

		$data['hutang_ditagih'] = $this->modelLaporan->hutang_ditagih($supplier,$this->global['idUser']);
		$this->load->view("laporan/dataAnalisaUmurHutang",$data);
	}


	function purchaseOrder(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,20);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Pembelian";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyLaporanPurchaseOrder",$this->global,$data,"laporan/footerPurchaseOrder");
			}
		}
	}

	function viewReportPurchaseOrder(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$supplier = $_POST['supplier'];
		$status = $_POST['status'];

		$data['viewReport'] = $this->modelLaporan->viewReportPurchaseOrder($dateStart,$dateEnd,$supplier,$status,$this->global['idStore']);
		$data['ap_receipt'] = $this->db->get("ap_receipt")->row();
		$data['dateStart'] = date_format(date_create($dateStart),'d M Y');
		$data['dateEnd'] = date_format(date_create($dateEnd),'d M Y');
		$this->load->view("laporan/viewReportPurchaseOrder",$data);
	}

	function penjualan(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Penjualan";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/body_laporan_penjualan",$this->global,NULL,"footer_empty");
			}
		}
	}

	function penjualan_perbarang(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['pageTitle'] = "Solusinformatika.com - Penjualan Perbarang";
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$this->load->view("navigation",$data);
				if($this->global['isSuperadmin']==1){
					$data['store'] = $this->db->get("ap_store")->result();
				}else $data['store'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$data['idUser'] = $this->global['idUser'];
                $data['idStore'] = $this->global['idStore'];
				$data['isAdmin'] = $this->global['isAdmin'];
				$this->load->view("laporan_penjualan/body_penjualan_perbarang",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPenjualanPerbarang");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPerbarangReport(){
		$data['produk'] = $this->db->get("ap_produk")->result();

		$date_start  = $_POST['dateStart'];
		$date_end 	 = $_POST['dateEnd'];
		$id_produk   = $_POST['idProduk'];
		$idToko 	 = $_POST['idToko'];

		//check user ini punya akses tidak
		$data['penjualan_perbarang'] = $this->modelLaporan->penjualanPerbarang($date_start,$date_end,$id_produk,$idToko);
		$data['info_produk'] = $this->model1->info_produk($id_produk);
		$data['dateStart'] = $date_start;
		$data['dateEnd'] = $date_end;
		$this->load->view("laporan_penjualan/bodyPenjualanPerbarangReport",$data);
	}

	function cekUserAccess(){
		$idToko = $_POST['idToko'];
		$cekUserAccess = $this->modelLaporan->cekAksesPertoko($idToko,$this->global['idUser']);

		//echo $cekUserAccess;
		echo 1; // bypass dulu by Arisal, nanti kroscek lagi
	}

	function sub_account(){
		$id 	= $_POST['id'];

		$query = $this->db->get_where("ap_payment_account",array("id_payment_type" => $id));

		$data['sub_account'] = $query;

		$rows = $query->num_rows();

		if($rows > 0 ){
			$this->load->view("sub_account",$data);
		} else {
			echo "<input type='hidden' id='subAccount' value=''/>";
		}
	}

	function akumulasiPenjualanProdukPerkriteria(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],1,13);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "Solusinformatika.com - Akumulasi Penjualan Berdasarkan Produk";
				$this->load->view("navigation",$data);
				$data['listKasir'] 	= $this->modelLaporan->list_kasir();
				//if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				//}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				
				
				$data['payment_type'] = $this->db->get("ap_payment_type")->result();
				$data['customer'] = $this->db->get("ap_customer")->result();
				$data['tempat'] = $this->db->get("ap_stand")->result();
				$data['kategori'] = $this->db->get("ap_kategori")->result();
				$data['idUser'] = $this->global['idUser'];
                $data['idStore'] = $this->global['idStore'];
				$data['isAdmin'] = $this->global['isAdmin'];
                $data['show_supplier'] = $this->db->order_by("supplier","ASC")->get("supplier");
				$data['show_brand'] = $this->db->order_by("brand","ASC")->get("brand");
				$this->load->view("laporan_penjualan/bodyPenjualanPerkriteriaProduk",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan_brand/jsAkumulasiKriteriaProduk");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function akumulasiPenjualanPerkriteria(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "Solusinformatika.com - Akumulasi Penjualan Berdasarkan Invoice";
	
				$this->load->view("navigation",$data);
				$data['listKasir'] 	= $this->modelLaporan->list_kasir();
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$data['payment_type'] = $this->db->get("ap_payment_type")->result();
				$data['customer'] = $this->db->get("ap_customer")->result();
				$data['idUser'] = $this->global['idUser'];
                $data['idStore'] = $this->global['idStore'];
				$data['isAdmin'] = $this->global['isAdmin'];
				$this->load->view("laporan_penjualan/bodyPenjualanPerkriteria",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsAkumulasiKriteria");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function laporanPenjualanPerkriteria(){
		$dateStart 			= $_POST['dateStart'];
		$dateEnd 			= $_POST['dateEnd'];
		$idKasir 			= $_POST['idKasir'];
		$toko 				= $_POST['toko'];
		$idCustomer 		= $_POST['idCustomer'];
		$typeBayar 			= $_POST['typeBayar'];
		$subAccount 		= $_POST['subAccount'];

		$data['dateStart'] 	= $_POST['dateStart'];
		$data['dateEnd'] 	= $_POST['dateEnd'];
		$data['idKasir'] 	= $_POST['idKasir'];
		$data['toko'] 		= $_POST['toko'];
		$data['idCustomer'] = $_POST['idCustomer'];
		$data['typeBayar'] 	= $_POST['typeBayar'];
		$data['subAccount'] = $_POST['subAccount'];

		$data['laporan'] = $this->modelLaporan->laporanPenjualanPerkriteria($dateStart, $dateEnd,$idKasir,$toko,$idCustomer,$typeBayar,$subAccount,$this);
		$this->load->view("laporan_penjualan/laporanPenjualanPerkriteria",$data);
	}

	function laporanPenjualanPerkriteriaProduk(){
 		$dateStart 		= $_POST['dateStart'];
 		$dateEnd 		= $_POST['dateEnd'];
 		$toko 			= $_POST['toko'];
 		$tempat 		= $_POST['tempat'];
 		$customer 		= $_POST['customer'];
 		$kategori 		= $_POST['kategori'];
 		$subkategori 	= $_POST['subkategori'];
 		$subkategori2 	= $_POST['subkategori2'];
        $id_supplier 	= $_POST['id_supplier'];
		$id_brand 		= $_POST['id_brand'];

 		$data['dateStart'] 		= $_POST['dateStart'];
 		$data['dateEnd'] 		= $_POST['dateEnd'];
 		$data['toko'] 			= $_POST['toko'];
 		$data['tempat'] 		= $_POST['tempat'];
 		$data['customer'] 		= $_POST['customer'];
 		$data['kategori'] 		= $_POST['kategori'];
 		$data['subkategori'] 	= $_POST['subkategori'];
 		$data['subkategori2'] 	= $_POST['subkategori2'];
        $data['id_supplier'] 	= $_POST['id_supplier'];
		$data['id_brand'] 		= $_POST['id_brand'];

		$brand = $this->modelLaporan->list_brand($this->global['idUser']);

		$data['laporan'] = $this->modelLaporan->penjualanPerkriteriaProdukBrand($brand,$dateStart, $dateEnd,$toko,$tempat,$customer,$kategori,$subkategori,$subkategori2,$id_supplier,$id_brand);
 		//$data['laporan_tidak_terjual'] = $this->modelLaporan->penjualanPerkriteriaProdukTidakTerjual($dateStart, $dateEnd,$toko,$tempat,$customer,$kategori,$subkategori,$subkategori2,$id_supplier,$id_brand);
		$this->load->view("laporan_penjualan_brand/laporanPenjualanPerkriteriaProduk",$data);
	}

	function exportExcelLaporanPenjualanPerkriteriaProduk(){
		error_reporting(0); ini_set('display_errors',0);
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Satuan')
									  ->setCellValue('E1','Harga Beli')	
									  ->setCellValue('F1','Harga Jual')
									  ->setCellValue('G1','QTY Terjual')
									  ->setCellValue('H1','Total HPP')
									  ->setCellValue('I1','Total Harga')
									  ->setCellValue('J1','Total Diskon')
									  ->setCellValue('K1','Diskon Supplier')
									  ->setCellValue('L1','Grand Total')
									  ->setCellValue('M1','Profit')
									  ->setCellValue('N1','Brand')
                                        ->setCellValue('O1','Stok')
										->setCellValue('P1','Last Received Info')
										->setCellValue('Q1','Last Sales')
										->setCellValue('R1','Last Receives')
										->setCellValue('S1','Est. Sales(%)')
										->setCellValue('T1','SSR')
										->setCellValue('U1','Stock Aging');
		
		$dateStart 		= $_GET['dateStart'];
 		$dateEnd 		= $_GET['dateEnd'];
 		$toko 			= $_GET['toko'];
 		$tempat 		= $_GET['tempat'];
 		$customer 		= $_GET['customer'];
 		$kategori 		= $_GET['kategori'];
 		$subkategori 	= $_GET['subkategori'];
 		$subkategori2 	= $_GET['subkategori2'];
        $id_supplier 	= $_GET['id_supplier'];
		$id_brand 		= $_GET['id_brand'];

		$start_date = new DateTime($_GET['dateStart']);
		$end_date 	= new DateTime($_GET['dateEnd']);

		$brand = $this->modelLaporan->list_brand($this->global['idUser']);
		
		//difference between two dates
		$diff 		= $start_date->diff($end_date);
		
		//find the number of days between two dates
		$day 		= $diff->format("%a")+1;

		// find the number of days in month 
		$dayMonth 	= cal_days_in_month(CAL_GREGORIAN, substr($_GET['dateStart'],5,2), substr($_GET['dateStart'],0,4)); // 31

		$laporan = $this->modelLaporan->penjualanPerkriteriaProdukBrand($brand,$dateStart, $dateEnd,$toko,$tempat,$customer,$kategori,$subkategori,$subkategori2,$id_supplier,$id_brand);


				$qty_terjual = 0;
				$modal = 0;
				$penjualan = 0;
				$diskon = 0;
				$disc_supplier = 0;
				$grand_total = 0;
				$profit = 0;
				$stok = 0;
		$i=2;
		foreach($laporan->result() as $row){
			


			$est =number_format(($row->qty_terjual/$day)*$dayMonth,2);
			$aging = number_format($row->stok/($row->qty_terjual/$day),0);
			$ssr = number_format($row->stok/$est,2);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->satuan)
									  ->setCellValue('E'.$i,$row->harga_beli)
									  ->setCellValue('F'.$i,$row->harga_jual)
									  ->setCellValue('G'.$i,$row->qty_terjual)
									  ->setCellValue('H'.$i,$row->harga_beli*$row->qty_terjual)
									  ->setCellValue('I'.$i,$row->harga_jual*$row->qty_terjual)
									  ->setCellValue('J'.$i,$row->diskon)
									  ->setCellValue('K'.$i,$row->disc_supplier)
									  ->setCellValue('L'.$i,($row->harga_jual*$row->qty_terjual)-$row->diskon)
									  ->setCellValue('M'.$i,($row->harga_jual*$row->qty_terjual)-$row->diskon-($row->harga_beli*$row->qty_terjual))
                                     ->setCellValue('N'.$i,$row->brand)   
                                        ->setCellValue('O'.$i,$row->stok)
										->setCellValue('P'.$i,$row->tanggal_po)
										->setCellValue('Q'.$i,$row->last_sales)
										->setCellValue('R'.$i,$row->last_receives)
										->setCellValue('S'.$i,$est)
										->setCellValue('T'.$i,$ssr)
										->setCellValue('U'.$i,$aging);
				$qty_terjual +=$row->qty_terjual;
				$modal += $row->harga_beli*$row->qty_terjual;
				$penjualan += $row->harga_jual*$row->qty_terjual;
				$diskon += $row->diskon;
				$disc_supplier += $row->disc_supplier;
				$grand_total += ($row->harga_jual*$row->qty_terjual)-$row->diskon;
				$profit += ($row->harga_jual*$row->qty_terjual)-$row->diskon-($row->harga_beli*$row->qty_terjual);
				$stok += $row->stok;

		$i++; }

		
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$i,$qty_terjual)
									  ->setCellValue('H'.$i,$modal)
									  ->setCellValue('I'.$i,$penjualan)
									  ->setCellValue('J'.$i,$diskon)
									  ->setCellValue('K'.$i,$disc_supplier)
									  ->setCellValue('L'.$i,$grand_total)
									  ->setCellValue('M'.$i,$profit)
                                        ->setCellValue('O'.$i,$stok);
		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusinformatika.com | IT Solutions")
								->setSubject("Solusinformatika.com | IT Solutions")
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
	    header('Content-Disposition: attachment;filename=Laporan Penjualan.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	} 

	function exportExcelLaporanPenjualanPerkriteria(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','No Invoice')
									  ->setCellValue('C1','Toko')
									  ->setCellValue('D1','Tanggal')
									  ->setCellValue('E1','Tipe Bayar')	
									  ->setCellValue('F1','Subtotal')
									  ->setCellValue('G1','Ongkir')
									  ->setCellValue('H1','Diskon Member')
									  ->setCellValue('I1','Diskon')
									  ->setCellValue('J1','Pon Reimburs')
									  ->setCellValue('K1','Diskon Peritem')
									  ->setCellValue('L1','Total');
		
		$dateStart 			= $_GET['dateStart'];
		$dateEnd 			= $_GET['dateEnd'];
		$idKasir 			= $_GET['idKasir'];
		$toko 				= $_GET['toko'];
		$idCustomer 		= $_GET['idCustomer'];
		$typeBayar 			= $_GET['typeBayar'];
		$subAccount 		= $_GET['subAccount'];

		$laporan = $this->modelLaporan->laporanPenjualanPerkriteria($dateStart, $dateEnd,$idKasir,$toko,$idCustomer,$typeBayar,$subAccount);

		$i=2;
		foreach($laporan->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValue('B'.$i,$row->no_invoice)
									  ->setCellValue('C'.$i,$row->store)
									  ->setCellValue('D'.$i,$row->tanggal)
									  ->setCellValue('E'.$i,$row->payment_type." ".$row->account)
									  ->setCellValue('F'.$i,$row->total)
									  ->setCellValue('G'.$i,$row->ongkir)
									  ->setCellValue('H'.$i,$row->diskon)
									  ->setCellValue('I'.$i,$row->diskon_free)
									  ->setCellValue('J'.$i,$row->poin_value)
									  ->setCellValue('K'.$i,$row->diskon_otomatis)
									  ->setCellValue('L'.$i,($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis));
		$i++; }

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusinformatika.com | IT Solutions")
								->setSubject("Solusinformatika.com | IT Solutions")
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
	    header('Content-Disposition: attachment;filename=Laporan Penjualan Produk.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function akumulasiPenjualanReport(){
		$dateStart  = $_POST['dateStart'];
		$dateEnd 	= $_POST['dateEnd'];

		$data['akumulasi_penjualan'] = $this->modelLaporan->akumulasiPenjualan($dateStart,$dateEnd);
		$this->load->view("laporan_penjualan/bodyAkumulasiReport",$data);
	}

	function dateReport(){
		$dateStart 		= $_POST['dateStart'];
			$dateEnd 		= $_POST['dateEnd'];

		echo "<h2>Laporan Akumulasi Penjualan</h2>";
		echo "<h2>Periode</h2>";
		echo "<h4>".date_format(date_create($dateStart),"d M Y")."-".date_format(date_create($dateEnd),"d M Y")."</h4>";
	}

	function ajax_customer(){
		$q 	= $_GET['term'];

		$customer = $this->model1->customer_search($q);

		$data_array = array();

		foreach($customer->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_customer,
									"text"	=> $row->nama." / ".$row->id_customer
								 );
		}

		echo json_encode($data_array);
	}

	function penjualan_perkategori_customer(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "Solusinformatika.com - Penjualan Perkategori Customer";
		
				$this->load->view("navigation",$data);
				$data['kategori'] = $this->db->get("ap_customer_group")->result();
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$data['idUser'] = $this->global['idUser'];
                $data['idStore'] = $this->global['idStore'];
				$data['isAdmin'] = $this->global['isAdmin'];
		 		$this->load->view("laporan_penjualan/body_penjualan_perkategori_custumer",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPenjualanPerkategoriCustomer");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPerkategoriCustomerReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];
		$kategori 		= $_POST['kategoriCustomer'];
		$idToko 		= $_POST['idToko'];

		$data['nama_kategori'] = $this->model1->nama_kategori($kategori);
		$data['penjualan_perkategori_customer'] = $this->modelLaporan->penjualan_perkategori_customer($start,$end,$kategori,$idToko);
		$this->load->view("laporan_penjualan/bodyPenjualanPerkategoriCustomerReport",$data);
	}


	function penjualan_percustomer(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['pageTitle'] = "Solusinformatika.com - Penjualan Percustomer";
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$this->load->view("navigation",$data);
				$this->load->view("body_penjualan_percustomer");
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPenjualanPercustomer");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPercustomerReport(){
		$data['customer'] = $this->db->get("ap_customer")->result();

		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];
		$id_customer 	= $_POST['idCustomer'];
        
        $idStore    = $_POST['idToko'];
        $idUser     = $this->global['idUser'];
		$isAdmin	= $this->global['isAdmin'];

		$data['penjualan_percustomer'] = $this->modelLaporan->penjualan_percustomer($start,$end,$id_customer,$idStore,$idUser,$isAdmin);

		$nama_customer = $this->db->get_where("ap_customer",array("id_customer" => $id_customer))->result();

		foreach($nama_customer as $cs){
			$data['nama']	= $cs->nama;
		}	

		$data['start'] = $start;
		$data['end'] = $end;
		$this->load->view("laporan_penjualan/bodyPenjualanPercustomerReport",$data);
	}

	function penjualan_perkategori_produk(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "Solusinformatika.com - Penjualan Perkategori Produk";
				$data['navigation'] = $this->model1->callNavigation();
				$this->load->view("navigation",$data);
				$this->load->view("body_penjualan_perkategori_produk");
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPerkategoriProduk");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPerkategoriProdukReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];

		$idKategori = $_POST['idKategori'];
        
        $idUser = $this->global['idUser'];
        $idStore = $this->global['idStore'];
	
		$data['sales_perkategori'] = $this->modelLaporan->salesPerkategori($start,$end,$idKategori,$idStore,$idUser);		
		$data['start'] = $start;
		$data['end'] = $end;
		$this->load->view("laporan_penjualan/penjualanPerkategoriProdukReport",$data);
	}

	function penjualan_pertoko(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "Solusinformatika.com - Penjualan Pertoko";

				$this->load->view("navigation",$data);
				if($this->global['isSuperadmin']==1){
					$data['store'] = $this->db->get("ap_store")->result();
				}else $data['store'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$data['idUser'] = $this->global['idUser'];
                $data['idStore'] = $this->global['idStore'];
				$this->load->view("laporan_penjualan/penjualanPertoko",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPenjualanPertoko");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPertokoReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];
		$idToko 		= $_POST['idToko'];

		$data['start'] 	= $_POST['dateStart'];
		$data['end'] 	= $_POST['dateEnd'];
		$data['idToko'] = $_POST['idToko'];
		$data['laporanPertoko'] = $this->modelLaporan->penjualanPertoko($start,$end,$idToko);
		$data['storeName'] 	 	= $this->modelLaporan->storeName($idToko);
        
        $data['totalInv'] = $this->modelLaporan->totalInvToko($idToko);
		$this->load->view("laporan_penjualan/penjualanPertokoReport",$data);
	}

	function penjualan_perkasir(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "Solusinformatika.com - Penjualan Perkasir";
		
				$this->load->view("navigation",$data);
				$data['listKasir'] 	= $this->modelLaporan->list_kasir();
				$this->load->view("laporan_penjualan/penjualanPerkasir",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPenjualanPerkasir");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPerkasirReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];
		$idKasir 		= $_POST['idKasir'];

		$data['start'] 		= $_POST['dateStart'];
		$data['end'] 		= $_POST['dateEnd'];
		$data['idKasir'] 	= $_POST['idKasir'];
        
        $data['idUser']     = $this->global['idUser'];
        $data['idStore']    = $this->global['idStore'];
		$isAdmin			= $this->global['isAdmin'];

		$data['nama_kasir'] = $this->model1->nama_kasir($idKasir);
		$data['laporanPerkasir'] = $this->modelLaporan->penjualanPerkasir($start,$end,$idKasir,$this->global['idStore'],$this->global['idUser'],$isAdmin);
		$this->load->view("laporan_penjualan/penjualanPerkasirReport",$data);
	}

	function akumulasi_penjualan_produk(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],1,13);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "Solusinformatika.com - Akumulasi Penjualan Produk";

				//if($this->global['isSuperadmin']==1){
					$data['store'] = $this->db->get("ap_store")->result();
				//}else $data['store'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
			
				$this->load->view("navigation",$data);
				$this->load->view("laporan_penjualan_brand/akumulasiPenjualanProduk");
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan_brand/jsAkumulasiPenjualanProduk");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function akumulasiPenjualanProdukReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];
		$id_toko 		= $_POST['id_toko'];
		

		$data['start'] = $start;
		$data['end'] = $end;
		$data['id_toko'] = $id_toko;
		$brand = $this->modelLaporan->list_brand($this->global['idUser']);
		$data['laporanAkumulasiProduk'] = $this->modelLaporan->akumulasiPenjualanProdukBrand($brand,$start,$end,$id_toko,$this->global['idUser'],$this->global['isAdmin']);
		$this->load->view("laporan_penjualan/akumulasiPenjualanProdukReport",$data);
	}

	function exportExcelakumulasiPenjualanProduk(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Satuan')
									  ->setCellValue('E1','HPP')	
									  ->setCellValue('F1','Harga')
									  ->setCellValue('G1','Qty Terjual')
									  ->setCellValue('H1','Total HPP')
									  ->setCellValue('I1','Total Harga')
									  ->setCellValue('J1','Profit');
		
		$start 			= $_GET['dateStart'];
		$end 			= $_GET['dateEnd'];
		$id_toko 		= $_GET['id_toko'];
		$brand = $this->modelLaporan->list_brand($this->global['idUser']);
		$laporan = $this->modelLaporan->akumulasiPenjualanProduk($brand,$start,$end,$this->global['idStore'],$this->global['idUser'],$this->global['isAdmin']);

		$i=2;
		foreach($laporan->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->satuan)
									  ->setCellValue('E'.$i,$row->hpp)
									  ->setCellValue('F'.$i,$row->harga_jual)
									  ->setCellValue('G'.$i,$row->qty_terjual)
									  ->setCellValue('H'.$i,$row->hpp*$row->qty_terjual)
									  ->setCellValue('I'.$i,($row->harga_jual*$row->qty_terjual))
									  ->setCellValue('J'.$i,($row->harga_jual*$row->qty_terjual)-($row->hpp*$row->qty_terjual));
		$i++; }

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusinformatika.com | IT Solutions")
								->setSubject("Solusinformatika.com | IT Solutions")
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
	    header('Content-Disposition: attachment;filename=Laporan Akumulasi Produk.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	// PENJUALAN per supplier 

	function akumulasi_penjualan_supplier(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "Solusinformatika.com - Akumulasi Penjualan Brand";
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
			
				$this->load->view("navigation",$data);
				$this->load->view("laporan_penjualan/akumulasiPenjualanSupplier");
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsAkumulasiPenjualanSupplier");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function akumulasiPenjualanSupplierReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];
		$id_toko 		= $_POST['id_toko'];

		$data['start'] = $start;
		$data['end'] = $end;
		$data['id_toko'] = $id_toko;
		$data['laporanAkumulasiSupplier'] = $this->modelLaporan->akumulasiPenjualanSupplier($start,$end,$id_toko,$this->global['idUser'],$this->global['isAdmin']);
		$this->load->view("laporan_penjualan/akumulasiPenjualanSupplierReport",$data);
	}

	function exportExcelakumulasiPenjualanSupplier(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','Nama Brand')
									  ->setCellValue('C1','Qty Terjual')
									  ->setCellValue('D1','Total HPP')
									  ->setCellValue('E1','Total Terjual')	
									  ->setCellValue('F1','Disc. Supplier')
									  ->setCellValue('G1','Disc. Toko')
									  ->setCellValue('H1','Total Diskon')
									  ->setCellValue('I1','Profit')
									  ->setCellValue('J1','Grand Total')
									  ->setCellValue('K1','Stok')
									  ->setCellValue('L1','Stok x HPP')
									  ->setCellValue('M1','Stok x Harga');
		
		$start 			= $_GET['dateStart'];
		$end 			= $_GET['dateEnd'];
		$id_toko 		= $_GET['id_toko'];

		$laporan = $this->modelLaporan->akumulasiPenjualanSupplier($start,$end,$id_toko,$this->global['idUser'],$this->global['isAdmin']);

		$i=2;
		foreach($laporan->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValue('B'.$i,$row->brand)
									  ->setCellValue('C'.$i,$row->qty_terjual)
									  ->setCellValue('D'.$i,$row->hpp)
									  ->setCellValue('E'.$i,$row->harga_jual)
									  ->setCellValue('F'.$i,$row->disc_supplier)
									  ->setCellValue('G'.$i,$row->disc_toko)
									  ->setCellValue('H'.$i,$row->diskon)
									  ->setCellValue('I'.$i,($row->harga_jual-$row->diskon)-($row->hpp))
									  ->setCellValue('J'.$i,($row->harga_jual-$row->diskon))
									  ->setCellValue('K'.$i,$row->stok)
									  ->setCellValue('L'.$i,$row->stokhpp)
									  ->setCellValue('M'.$i,$row->stokharga);
		$i++; }

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusinformatika.com | IT Solutions")
								->setSubject("Solusinformatika.com | IT Solutions")
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
	    header('Content-Disposition: attachment;filename=Laporan Akumulasi Brand.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	// end per supplier

	// start lap per brand
	function akumulasi_pembelian_brand(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "Solusinformatika.com - Akumulasi Pembelian Brand";
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
			
				$this->load->view("navigation",$data);
				$this->load->view("laporan/akumulasiPembelianBrand");
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan/jsAkumulasiPembelianBrand");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function akumulasiPembelianBrandReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];
		$toko 			= $_POST['toko'];

		$data['start'] = $start;
		$data['end'] = $end;
		$data['toko'] = $toko;
		$data['laporanAkumulasiBrand'] = $this->modelLaporan->akumulasiPembelianBrand($start,$end,$toko,$this->global['idStore'],$this->global['idUser'],$this->global['isAdmin']);
		$this->load->view("laporan/akumulasiPembelianBrandReport",$data);
	}

	function exportExcelakumulasiPembelianBrand(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','Nama Brand')
									  ->setCellValue('C1','Qty')
									  ->setCellValue('D1','Harga Beli')
									  ->setCellValue('E1','Harga Jual');
		
		$start 			= $_GET['dateStart'];
		$end 			= $_GET['dateEnd'];
		$toko 			= $_GET['toko'];

		$laporan = $this->modelLaporan->akumulasiPembelianBrand($start,$end,$toko,$this->global['idStore'],$this->global['idUser'],$this->global['isAdmin']);

		$i=2;
		foreach($laporan->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValue('B'.$i,$row->brand)
									  ->setCellValue('C'.$i,$row->qty)
									  ->setCellValue('D'.$i,$row->harga)
									  ->setCellValue('E'.$i,$row->hargajual);
		$i++; }

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusinformatika.com | IT Solutions")
								->setSubject("Solusinformatika.com | IT Solutions")
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
	    header('Content-Disposition: attachment;filename=Laporan Akumulasi Pembelian per Brand.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}
	function get_subkategori(){
		$id_kategori = $_POST['id_kategori'];

		$data['show_sub'] = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori));

		$count = 0;

		foreach($data['show_sub']->result() as $row){
			$count = $count+$row->id;
		}

		if(!empty($_POST['id_kategori']) && $count > 0){
			$this->load->view("laporan_penjualan/show_sub",$data);
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
			$this->load->view("laporan_penjualan/show_sub2",$data);
		} else {
			echo "<input type='hidden' id='subkategori_3' value=''/>";
		}
	}

	function top_customer(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "Solusinformatika.com - Top Customer";
		
				$this->load->view("navigation",$data);

				$total_rows = $this->db->get("ap_customer")->num_rows();
				$this->load->library('pagination');
				$config['base_url'] 			= base_url('laporan/top_customer');
				$config['total_rows']			= $total_rows;
				$config["per_page"]				= $per_page = 50;
				$config["uri_segment"]			= 3;
				$config["full_tag_open"] 		= '<ul class="pagination">';
				$config["full_tag_close"] 		= '</ul>';
				$config["first_link"] 			= "&laquo;";
				$config["first_tag_open"] 		= "<li>";
				$config["first_tag_close"] 		= "</li>";
				$config["last_link"] 			= "&raquo;";
				$config["last_tag_open"] 		= "<li>";
				$config["last_tag_close"] 		= "</li>";
				$config['next_link'] 			= '&gt;';
				$config['next_tag_open'] 		= '<li>';
				$config['next_tag_close'] 		= '<li>';
				$config['prev_link'] 			= '&lt;';
				$config['prev_tag_open'] 		= '<li>';
				$config['prev_tag_close'] 		= '<li>';
				$config['cur_tag_open'] 		= '<li class="active"><a href="#">';
				$config['cur_tag_close'] 		= '</a></li>';
				$config['num_tag_open'] 		= '<li>';
				$config['num_tag_close'] 		= '</li>';

				$this->pagination->initialize($config);

				$data['paging'] = $this->pagination->create_links();
				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

				$data['top_customer'] = $this->model1->top_customer($per_page,$page);
				$this->load->view("body_top_customer",$data);
				$this->load->view("footer_empty");
			}
		}
	}

	function top_customer_presensce(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "Solusinformatika.com - Top Customer by Presence";
		
				$this->load->view("navigation",$data);

				$total_rows = $this->db->get("ap_customer")->num_rows();
				$this->load->library('pagination');
				$config['base_url'] 			= base_url('laporan/top_customer_presensce');
				$config['total_rows']			= $total_rows;
				$config["per_page"]				= $per_page = 50;
				$config["uri_segment"]			= 3;
				$config["full_tag_open"] 		= '<ul class="pagination">';
				$config["full_tag_close"] 		= '</ul>';
				$config["first_link"] 			= "&laquo;";
				$config["first_tag_open"] 		= "<li>";
				$config["first_tag_close"] 		= "</li>";
				$config["last_link"] 			= "&raquo;";
				$config["last_tag_open"] 		= "<li>";
				$config["last_tag_close"] 		= "</li>";
				$config['next_link'] 			= '&gt;';
				$config['next_tag_open'] 		= '<li>';
				$config['next_tag_close'] 		= '<li>';
				$config['prev_link'] 			= '&lt;';
				$config['prev_tag_open'] 		= '<li>';
				$config['prev_tag_close'] 		= '<li>';
				$config['cur_tag_open'] 		= '<li class="active"><a href="#">';
				$config['cur_tag_close'] 		= '</a></li>';
				$config['num_tag_open'] 		= '<li>';
				$config['num_tag_close'] 		= '</li>';

				$this->pagination->initialize($config);

				$data['paging'] = $this->pagination->create_links();
				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

				$data['top_customer'] = $this->model1->top_customer_presensce($per_page,$page);
				$this->load->view("body_top_customer_presensce",$data);
				$this->load->view("footer_empty");
			}
		}
	}
	
	function customer_by_group(){

		if($_POST['id']!=''){
			$customer = $this->db->get_where("ap_customer",array("kategori" => $_POST['id']))->result();
		} else {
			$customer = $this->db->get("ap_customer")->result();
		}

		foreach($customer as $cs){
			echo "<option value='".$cs->id_customer."'>".$cs->nama."</option>";
		}

	}

	function detailPenjualan(){
		$noInvoice 			= $_POST['noInvoice'];

		$data['invoiceDetail'] 	= $this->modelLaporan->invoiceDetail($noInvoice);
		$data['invoiceItem'] 	= $this->modelLaporan->invoiceItem($noInvoice);
		$this->load->view("laporan_penjualan/detailPenjualan",$data);
	}

	function ajax_produk(){
		$q 	= $_GET['term'];
				
		$customer = $this->modelLaporan->ajaxProduk($q);

		$data_array = array();

		foreach($customer->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk,
								 );
		}

		echo json_encode($data_array);
	}

	function mutasiBarang(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,22);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Barang Keluar / Mutasi";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyMutasiBarang",$this->global,$data,"laporan/footerMutasiBarang");
			}
		}
	}

	function viewReportMutasi(){
		$data['dateStart'] 	= $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['idStore'] = $_POST['idStore'];
		$this->load->view("laporan/viewReportMutasi",$data);
	}

	function datatableMutasi(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$idStore = $_POST['idStore'];

		$total 			 			= $this->modelLaporan->rowDataMutasi($dateStart,$dateEnd,$idStore);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewMutasi($length,$start,$search,$dateStart,$dateEnd,$idStore);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewMutasi($length,$start,$search,$dateStart,$dateEnd,$idStore);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoice_pengeluaran_barang?no_keluaran='.$dt['no_bahan_keluar'])."'>".$dt['no_bahan_keluar']."</a>",date_format(date_create($dt['tanggal_keluar']),'d M Y H:i'),$dt['store'],$dt['nama_penerima']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_pengeluaran_barang(){
		$data['header'] = $this->db->get("ap_receipt");
		$no_keluaran = $_GET['no_keluaran'];
		$data['info'] = $this->model1->info_pengeluaran($no_keluaran);
		$data['spending_item'] = $this->model1->spending_item($no_keluaran);
		$this->load->model("modelProduk");
		$this->global['pageTitle'] = "Solusinformatika.com - Invoice Pengeluaran Barang";
		//$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("barang_keluar/body_invoice_pengeluaran_barang",$this->global,$data,"footer_empty");
	}

	function mutasiPeritem(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,22);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$this->global['pageTitle'] = "Solusinformatika.com - Mutasi Peritem";
				$this->loadViews("laporan/bodyMutasiBarangPeritem",$this->global,$data,"laporan/footerMutasiBarangPeritem");
			}
		}
	}

	function viewReportMutasiPeritem(){
		$data['dateStart'] 	= $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['idStore'] = $_POST['idStore'];
		$data['idProduk'] = $_POST['idProduk'];
		$this->load->view("laporan/viewReportMutasiPeritem",$data);
	}

	function datatableMutasiPeritem(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$idStore = $_POST['idStore'];
		$idProduk = $_POST['idProduk'];

		$total 			 			= $this->modelLaporan->rowDataMutasiPeritem($dateStart,$dateEnd,$idStore,$idProduk);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewMutasiPeritem($length,$start,$search,$dateStart,$dateEnd,$idStore,$idProduk);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewMutasiPeritem($length,$start,$search,$dateStart,$dateEnd,$idStore,$idProduk);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoice_pengeluaran_barang?no_keluaran='.$dt['no_bahan_keluar'])."'>".$dt['no_bahan_keluar']."</a>",date_format(date_create($dt['tanggal_keluar']),'d M Y H:i'),$dt['id_produk'],$dt['nama_produk'],$dt['store'],$dt['qty']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function totalQTYMutasi(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$idStore = $_POST['idStore'];
		$idProduk = $_POST['idProduk'];

		$totalQTY = $this->modelLaporan->totalQTYMutasi($dateStart,$dateEnd,$idStore,$idProduk);
		echo $totalQTY;
	}

	function transferStok(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,23);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Transfer Stok";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyTransferStok",$this->global,$data,"laporan/footerTransferStok");
			}
		}
	}

	function viewReportTransfer(){
		$data['dateStart'] 	= $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['transferFrom'] = $_POST['transferFrom'];
		$data['transferTo'] = $_POST['transferTo'];
		$this->load->view("laporan/viewReportTransferStok",$data);
	}

	function viewReportTransferPeritem(){
		$data['dateStart'] 	= $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['transferFrom'] = $_POST['transferFrom'];
		$data['transferTo'] = $_POST['transferTo'];
		$data['idProduk'] = $_POST['idProduk'];
		$this->load->view("laporan/viewReportTransferStokPeritem",$data);
	}

	function datatableTransferStok(){
		error_reporting(0);
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$transferFrom = $_POST['transferFrom'];
		$transferTo = $_POST['transferTo'];

		$total 			 			= $this->modelLaporan->rowDataTransferStok($dateStart,$dateEnd,$transferFrom,$transferTo);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewTransferStok($length,$start,$search,$dateStart,$dateEnd,$transferFrom,$transferTo);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewTransferStok($length,$start,$search,$dateStart,$dateEnd,$transferFrom,$transferTo);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			if ($dt['Accepted']==0){
				$status = "Dikirim";
			}if ($dt['Accepted']==1){
				$status = "Diterima";
			}
			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoiceTransfer?noTransfer='.$dt['noTransfer'])."'>".$dt['noTransfer']."</a>",date_format(date_create($dt['tanggal']),'d M Y H:i'),$this->model1->namaStore($dt['transferFrom']),$this->model1->namaStore($dt['transferTo']),$status,$dt['tanggal_terima'],$dt['first_name']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function datatableTransferStokPeritem(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$transferFrom = $_POST['transferFrom'];
		$transferTo = $_POST['transferTo'];
		$idProduk = $_POST['idProduk'];

		$total 			 			= $this->modelLaporan->rowDataTransferStokPeritem($dateStart,$dateEnd,$transferFrom,$transferTo,$idProduk);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewTransferStokPeritem($length,$start,$search,$dateStart,$dateEnd,$transferFrom,$transferTo,$idProduk);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewTransferStokPeritem($length,$start,$search,$dateStart,$dateEnd,$transferFrom,$transferTo,$idProduk);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoiceTransfer?noTransfer='.$dt['noTransfer'])."'>".$dt['noTransfer']."</a>",date_format(date_create($dt['tanggal']),'d/m/y'),$dt['id_produk'],$dt['nama_produk'],$this->model1->namaStore($dt['transferFrom']),$this->model1->namaStore($dt['transferTo']),$dt['qty'],$dt['qty_rec']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function totalQtyTransferStok(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$transferFrom = $_POST['transferFrom'];
		$transferTo = $_POST['transferTo'];
		$idProduk = $_POST['idProduk'];
		$qty = $this->modelLaporan->totalQtyTransferStok($dateStart,$dateEnd,$transferFrom,$transferTo,$idProduk);
		echo $qty;
	}

	function invoiceTransfer(){
		$noTransfer = $this->input->get("noTransfer");

		$this->load->model("modelTransferStok");
		$data['header'] = $this->db->get("ap_receipt")->row();
		$data['infoTransfer'] = $this->modelTransferStok->infoTransfer($noTransfer);
		$data['itemTransfer'] = $this->modelTransferStok->itemTransferView($noTransfer);
		$this->global['pageTitle'] = "Solusinformatika.com - Data Customer";
		//$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("transfer_stok/bodyInvoiceTransfer",$this->global,$data,"footer_empty");
	}

	function transferStokPeritem(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,23);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$this->global['pageTitle'] = "Solusinformatika.com - Transfer Stok Peritem";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyTransferStokPeritem",$this->global,$data,"laporan/footerTransferStokPeritem");
			}
		}
	}


	function invoice_penjualan(){
		$data['pageTitle'] = "Solusinformatika.com - Invoice Penjualan";
		$no_invoice = $_GET['no_invoice'];
		$id_store = $this->model1->id_store_invoice($no_invoice);
		$data['receipt'] = $this->db->get_where("ap_store",array("id_store" => $id_store));
		$data['no_invoice'] = $this->model1->invoice_ket($no_invoice);
		$data['invoice_item'] = $this->model1->invoice_item($no_invoice);

		$idKasir = $this->model1->getIdKasir($no_invoice);

		$data['nama_kasir'] = $this->model1->nama_kasir($idKasir);
		$data['item_barang'] = $this->model1->item_barang_struk($no_invoice);
		$data['qty_barang'] = $this->model1->qty_barang_struk($no_invoice);
		$data['tipe_bayar'] = $this->model1->tipe_bayar_struk($no_invoice);

		$this->load->library('ciqrcode');

		/**$qr['data'] 	= $no_invoice;
		$qr['level']	= 'H';
		$qr['size']		= '10';
		$qr['savename']	= FCPATH."qr/".$no_invoice.".png";
		$this->ciqrcode->generate($qr);**/


		$this->global['pageTitle'] = "Solusinformatika.com - Invoice Penjualan";
		$this->loadViews("penjualan/body_invoice_penjualan",$this->global,$data,"penjualan/footerInvoicePenjualan");
	}

	function invoiceA4(){
		$noInvoice = $this->input->get('no_invoice');
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['header'] = $this->db->get_where("ap_store",array("id_store" => $idStore))->row();
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);
		$data['qty_barang'] = $this->model1->qty_barang_struk($noInvoice);
		$data['item_barang'] = $this->model1->item_barang_struk($noInvoice);

		$this->global['pageTitle'] = "Solusinformatika.com - Invoice Penjualan";
		$this->loadViews("penjualan/invoiceA4",$this->global,$data,"footer_empty");
	}

	function suratJalan(){
		$data['pageTitle'] = "Solusinformatika.com - Surat Jalan";
		$noInvoice = $this->input->get('no_invoice');
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['header'] = $this->db->get_where("ap_store",array("id_store" => $idStore))->row();
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);

		$this->global['pageTitle'] = "Solusinformatika.com - Surat Jalan";
		$this->loadViews("penjualan/suratJalan",$this->global,$data,"footer_empty");
	}

	function shippingLabel(){
		$noInvoice = $this->input->get('no_invoice');
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['header'] = $this->db->get_where("ap_store",array("id_store" => $idStore))->row();
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);

		$this->load->library('ciqrcode');

		$qr['data'] 	= $noInvoice;
		$qr['level']	= 'H';
		$qr['size']		= '4';
		$qr['savename']	= FCPATH."qr/".$noInvoice.".png";
		$this->ciqrcode->generate($qr);


		$this->global['pageTitle'] = "Solusinformatika.com - Shipping Label";
		$this->loadViews("penjualan/shippingLabel",$this->global,$data,"footer_empty");
	}

	function penerimaanBarang(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,24);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$data['supplier'] = $this->db->get("supplier")->result();
				$data['brand'] = $this->db->get("brand")->result();
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Penerimaan Barang";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyPenerimaanBarang",$this->global,$data,"laporan/footerPenerimaanBarang");
			}
		}
	}

	function penerimaanBarangPeritem(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,24);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->global['pageTitle'] = "Solusinformatika.com - Penerimaan Barang Peritem";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyPenerimaanBarangPeritem",$this->global,$data,"laporan/footerPenerimaanBarangPeritem");
			}
		}
	}

	function viewReportPenerimaanBarang(){
		$data['dateStart'] = $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['tempatPenerimaan'] = $_POST['tempatPenerimaan'];
		$data['supplier'] = $_POST['supplier'];
		$data['brand'] = $_POST['brand'];
		$this->load->view("laporan/viewReportPenerimaanBarang",$data);
	}

	function viewReportPenerimaanBarangPeritem(){
		$data['dateStart'] = $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['tempatPenerimaan'] = $_POST['tempatPenerimaan'];
		$data['supplier'] = $_POST['supplier'];
		$data['idProduk'] = $_POST['idProduk'];
		$this->load->view("laporan/viewReportPenerimaanBarangPeritem",$data);
	}

	function convertDate($date){
		return date_format(date_create($date),'d/m/Y');
	}

	function invoice_receive(){
		$this->load->model("modelBahanMasukMaterial");
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);
		$data['receive_item'] = $this->modelBahanMasukMaterial->received_item($no_receive);

		$this->global['pageTitle'] = "Solusinformatika.com - Data Customer";
		//$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("bahan_masuk/body_invoice_receive",$this->global,$data,"footer_empty");
	}

	function datatablePenerimaanBarang(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$tempatPenerimaan = $_POST['tempatPenerimaan'];
		$supplier = $_POST['supplier'];
		$brand = $_POST['brand'];

		$total 			 			= $this->modelLaporan->rowPenerimaanBarang($dateStart,$dateEnd,$tempatPenerimaan,$supplier,$brand);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewPenerimaanBarang($length,$start,$search,$dateStart,$dateEnd,$tempatPenerimaan,$supplier,$brand);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewPenerimaanBarang($length,$start,$search,$dateStart,$dateEnd,$tempatPenerimaan,$supplier,$brand);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {

			if(empty($dt['store'])){
				$place = "Gudang";
			} else {
				$place = $dt['store'];
			}

			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoice_receive?no_receive='.$dt['no_receive'])."'>".$dt['no_receive']."</a>",$dt['no_po'],$this->convertDate($dt['tanggal_terima']),$place,number_format($dt['harga'],0,',','.'),$dt['penerima'],$dt['pemeriksa'],$dt['supplier']);
			$nomor_urut++;
		}
		

		echo json_encode($output);
	}

	function qtyPeritemPenerimaan(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$tempatPenerimaan = $_POST['tempatPenerimaan'];
		$supplier = $_POST['supplier'];
		$idProduk = $_POST['idProduk'];

		$qty = $this->modelLaporan->qtyPeritemPenerimaan($dateStart,$dateEnd,$tempatPenerimaan,$supplier,$idProduk);
		echo $qty;
	}

	function datatablePenerimaanBarangPeritem(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$tempatPenerimaan = $_POST['tempatPenerimaan'];
		$supplier = $_POST['supplier'];
		$idProduk = $_POST['idProduk'];

		$total 			 			= $this->modelLaporan->rowPenerimaanBarangPeritem($dateStart,$dateEnd,$tempatPenerimaan,$supplier,$idProduk);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewPenerimaanBarangPeritem($length,$start,$search,$dateStart,$dateEnd,$tempatPenerimaan,$supplier,$idProduk);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewPenerimaanBarangPeritem($length,$start,$search,$dateStart,$dateEnd,$tempatPenerimaan,$supplier,$idProduk);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {

			if(empty($dt['store'])){
				$place = "Gudang";
			} else {
				$place = $dt['store'];
			}

			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoice_receive?no_receive='.$dt['no_receive'])."'>".$dt['no_receive']."</a>",$this->convertDate($dt['tanggal']),$place,$dt['supplier'],$dt['id_produk'],$dt['nama_produk'],$dt['qty']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function returPembelian(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,47);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Retur Pembelian";
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->loadViews("laporan/returPembelian",$this->global,$data,"laporan/footerReturPembelian");
			}
		}
	}

	function viewReportReturPembelian(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$supplier = $_POST['supplier'];

		$data['viewReport'] = $this->modelLaporan->viewReportReturPembelian($dateStart,$dateEnd,$supplier);
		$data['ap_receipt'] = $this->db->get("ap_receipt")->row();
		$data['dateStart'] = date_format(date_create($dateStart),'d M Y');
		$data['dateEnd'] = date_format(date_create($dateEnd),'d M Y');
		$this->load->view("laporan/viewReportReturPembelian",$data);
	}

	function returPenjualan(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Retur Penjualan";
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$this->loadViews("laporan/returPenjualan",$this->global,$data,"laporan/footerReturPenjualan");
			}
		}
	}

	function setoranKasir(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Setoran Kasir";
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$this->loadViews("laporan/setoranKasir",$this->global,$data,"laporan/footerSetoranKasir");
			}
		}
	}

	function labaRugi(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Laba Rugi";
				if($this->global['isSuperadmin']==1){
					$data['toko'] = $this->db->get("ap_store")->result();
				}else $data['toko'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$this->loadViews("laporan/bodyLabaRugi",$this->global,$data,"laporan/footerReturLabaRugi");
			}
		}
	}

	function viewReportLabaRugi(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->load->model("modelFinance");

				$start 		= $_POST['dateStart'];
				$end 		= $_POST['dateEnd'];
				$toko 		= $_POST['toko'];

				$data['start'] = $start;
				$data['end']   = $end;

				if(empty($toko)){
					$data['title'] = "Akumulasi";
				} else {
					$data['title'] = $this->model1->namaStore($toko);
				}

				$data['salesPerkategori'] = $this->modelDashboard->salesPerkategoriRange($start,$end,$toko);
				$data['cogs'] 			  = $this->modelDashboard->bebanPokokPenjualanRange($start,$end,$toko);
				$data['totalSales'] 	  = $this->modelDashboard->totalSalesRange($start,$end,$toko);
				$data['retur'] 			  = $this->modelDashboard->dataReturRange($start,$end,$toko);

				$data['diskonMember'] 	  = $this->modelFinance->diskonMemberRange($start,$end,$toko);
				$data['diskonGlobal'] 	  = $this->modelFinance->diskonGlobalRange($start,$end,$toko);
				$data['diskonPeritem'] 	  = $this->modelFinance->diskonPeritemRange($start,$end,$toko);
				$data['poinReimburs'] 	  = $this->modelFinance->poinReimbursRange($start,$end,$toko);

				$this->load->view("laporan_penjualan/laporanLabaRugi",$data);
			}
		}
	}

	function viewReportReturPenjualan(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$idProduk = $_POST['idProduk'];
		$noInvoice = $_POST['noInvoice'];
		$store = $_POST['store'];
		
		$data['viewReport'] = $this->modelLaporan->viewReportPenjualanPeritem($dateStart,$dateEnd,$idProduk,$noInvoice,$store);
		$this->load->view("laporan/viewReportPenjualanPeritem",$data);
	}

	function viewReportSetoranKasir(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$store = $_POST['store'];
		
		$data['viewReport'] = $this->modelLaporan->viewReportSetoranKasir($dateStart,$dateEnd,$store);
		$this->load->view("laporan/viewReportSetoranKasir",$data);
	}

	function produkPromo(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->load->model("modelPromoSupplier");
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Produk Promo";
				if($this->global['isSuperadmin']==1){
					$data['store'] = $this->db->get("ap_store")->result();
				}else $data['store'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$data['tempat'] = $this->db->get("ap_stand")->result();
				$data['kategori'] = $this->db->get("ap_kategori")->result();
                $data['show_supplier'] = $this->db->order_by("supplier","ASC")->get("supplier");
				$data['show_brand'] = $this->db->order_by("brand","ASC")->get("brand");
				$this->loadViews("laporan/produkPromo",$this->global,$data,"laporan/footerProdukPromo");
			}
		}
	}

	function viewReportProdukPromo(){
		$this->load->model("ModelPromoSupplier");
		$start 		= $_POST['dateStart'];
		$end 		= $_POST['dateEnd'];
		$idStore 	= $_POST['store'];

		$tempat 		= $_POST['tempat'];
 		$kategori 		= $_POST['kategori'];
 		$subkategori 	= $_POST['subkategori'];
 		$subkategori2 	= $_POST['subkategori2'];
        $id_supplier 	= $_POST['id_supplier'];
		$id_brand 		= $_POST['id_brand'];
		$id_produk 		= $_POST['id_produk'];

 		$data['tempat'] 		= $_POST['tempat'];
 		$data['kategori'] 		= $_POST['kategori'];
 		$data['subkategori'] 	= $_POST['subkategori'];
 		$data['subkategori2'] 	= $_POST['subkategori2'];
        $data['id_supplier'] 	= $_POST['id_supplier'];
		$data['id_brand'] 		= $_POST['id_brand'];
		$data['id_produk'] 		= $_POST['id_produk'];

		$data['periode'] = date_format(date_create($start),"d M Y")." s/d ".date_format(date_create($end),"d M Y");
		$data['start']   = $start;
		$data['end'] 	 = $end;
		$data['idStore'] = $idStore;
		
		$data['list'] 	= $this->ModelPromoSupplier->listProdukPromo($start,$end,$idStore,$tempat,$kategori,$subkategori,$subkategori2,$id_supplier,$id_brand,$id_produk);
		$this->load->view("laporan/viewProdukPromo",$data);
	}

	function hapusProdukPromo(){
		$id = $_POST['id'];
		$this->db->where("id", $id);
        $this->db->delete("ap_produk_discount_rules");
	}

	function penjualanPerkategori(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->load->model("ModelSalesByKategori");
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Penjualan Per Departemen";
				if($this->global['isSuperadmin']==1){
					$data['store'] = $this->db->get("ap_store")->result();
				}else $data['store'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$this->loadViews("laporan/penjualanPerkategori",$this->global,$data,"laporan/footerPenjualanPerkategori");
			}
		}
	}

	function viewReportSalesPerkategori(){
		$this->load->model("ModelSalesByKategori");
		$start 		= $_POST['dateStart'];
		$end 		= $_POST['dateEnd'];
		$idStore 	= $_POST['store'];

		$data['periode'] = date_format(date_create($start),"d M Y")." ".date_format(date_create($end),"d M Y");
		$data['start']   = $start;
		$data['end'] 	 = $end;
		$data['idStore'] = $idStore;
		$this->load->view("laporan/viewSalesByKategori",$data);
	}


	function penjualanDibatalkan(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->load->model("ModelSalesByKategori");
				$this->global['pageTitle'] = "Solusinformatika.com - Laporan Transaksi dibatalkan";
				if($this->global['isSuperadmin']==1){
					$data['store'] = $this->db->get("ap_store")->result();
				}else $data['store'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
				$this->loadViews("laporan/penjualanDibatalkan",$this->global,$data,"laporan/footerPenjualanDibatalkan");
			}
		}
	}

	function viewReportSalesDibatalkan(){
		$this->load->model("ModelSalesByKategori");
		$start 		= $_POST['dateStart'];
		$end 		= $_POST['dateEnd'];
		$idStore 	= $_POST['store'];

		$data['periode'] = date_format(date_create($start),"d M Y")." ".date_format(date_create($end),"d M Y");
		$data['start']   = $start;
		$data['end'] 	 = $end;
		$data['idStore'] = $idStore;
		$data['viewReport'] = $this->modelLaporan->viewReportSalesDibatalkan($start,$end,$idStore);
		$this->load->view("laporan/viewSalesDibatalkan",$data);
	}
}