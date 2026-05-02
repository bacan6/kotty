<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Dashboard_brand extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		
		$this->load->model(array("model1","model_dashboard_brand","model_dashboard"));
		
		$this->load->library("session");
	
		$this->isLoggedIn($this->global['idUser'],1,13);
	}

	function index(){
		$this->load->model("model_dashboard_brand");
		$this->global['pageTitle'] = "SOLUSI POS - Dashboard";
		$data['toko'] = $this->db->get("ap_store")->result();

		$_SESSION['id_toko']=empty($_GET['id_toko'])? '':$_GET['id_toko'];
		$this->loadViews("staff/body_brand",$this->global,$data,"staff/footer_dashboard_brand");
	}

	function dayFilter(){
		$this->load->view("staff/dayFilterBrand");
	}

	function bulanFilter(){
		$this->load->view("staff/bulanFilterBrand");
	}

	function tahunFilter(){
		$this->load->view("staff/tahunFilterBrand");
	}

	function dataPenjualan(){
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
		} else {
			$tanggal = date('Y-m-d');
		}

		$id_toko = $_SESSION['id_toko'];

		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);

		$totalSales = $this->model_dashboard_brand->total_sales($brand,$this->global['idStore'],$this->global['idUser'],$tanggal,$id_toko);
		$transaction = $this->model_dashboard_brand->transaction($brand,$this->global['idStore'],$this->global['idUser'],$tanggal,$id_toko);
		$totalItemTerjual = $this->model_dashboard_brand->totalItemTerjual($brand,$this->global['idStore'],$this->global['idUser'],$tanggal,$id_toko);
        
        $totalInv = $this->model_dashboard_brand->totalInv($brand);
		// $totalInv01 = $this->model_dashboard_brand->totalInvStore('7');
		// $totalInv02 = $this->model_dashboard_brand->totalInvStore('8');
		//$totalInvJMB = $this->model_dashboard_brand->totalInvJMB();

		// $warningStokPDG = $this->model_dashboard_brand->warningStokPDG();
		// $warningStokPKU = $this->model_dashboard_brand->warningStokPKU();
		// $warningStokJMB = $this->model_dashboard_brand->warningStokJMB();
        
        $totalMargin = $this->model_dashboard_brand->totalMargin($brand,$this->global['idStore'],$this->global['idUser'],$tanggal,$id_toko);
        
        $totalMargin = number_format($totalMargin*100,2);

		if($transaction > 0){
			$basketSize = number_format($totalSales/$transaction,'0','','');
		} else {
			$basketSize = 0;	
		}

		$dataArray[] = array(
								"totalSales" => $totalSales, 
								"transaction" => $transaction,
								"basketSize" => $basketSize,
								"totalItemTerjual" => $totalItemTerjual,
                                "totalMargin" => $totalMargin,
                                "totalInv" => $totalInv[0]->nilai
							);

		echo json_encode($dataArray);
	}

	function dataPenjualanPerbulan(){
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
			$bulan = substr($tanggal, 5,2);
        	$tahun = substr($tanggal, 0,4);
		} else {
			$bulan = date('m');
			$tahun = date('Y');
		}

		$id_toko = $_SESSION['id_toko'];

		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$totalSales = $this->model_dashboard_brand->total_sales_perbulan($brand,$this->global['idStore'],$this->global['idUser'],$bulan,$tahun,$id_toko);
		$transaction = $this->model_dashboard_brand->transactionPerbulan($brand,$this->global['idStore'],$this->global['idUser'],$bulan,$tahun,$id_toko);
		$totalItemTerjual = $this->model_dashboard_brand->totalItemTerjualPerbulan($brand,$this->global['idStore'],$this->global['idUser'],$bulan,$tahun,$id_toko);
        
        $totalMargin = $this->model_dashboard_brand->totalMarginPerbulan($brand,$this->global['idStore'],$this->global['idUser'],$bulan,$tahun,$id_toko);
        
        $totalInv = $this->model_dashboard_brand->totalInv($brand);
		// $totalInv01 = $this->model_dashboard_brand->totalInvStore('7');
		// $totalInv02 = $this->model_dashboard_brand->totalInvStore('8');
		//$totalInvJMB = $this->model_dashboard_brand->totalInvJMB();

		// $warningStokPDG = $this->model_dashboard_brand->warningStokPDG();
		// $warningStokPKU = $this->model_dashboard_brand->warningStokPKU();
		// $warningStokJMB = $this->model_dashboard_brand->warningStokJMB();
        
        $totalMargin = number_format($totalMargin*100,2);

		if($transaction > 0){
			$basketSize = number_format($totalSales/$transaction,'0','','');
		} else {
			$basketSize = 0;	
		}

		$dataArray[] = array(
								"totalSales" => $totalSales, 
								"transaction" => $transaction,
								"basketSize" => $basketSize,
								"totalItemTerjual" => $totalItemTerjual,
                                "totalMargin" => $totalMargin,
                                "totalInv" => $totalInv[0]->nilai
								// "totalInvJMB" => $totalInvJMB[0]->nilai,
								// "warningStokPDG" => $warningStokPDG[0]->nilai,
								// "warningStokPKU" => $warningStokPKU[0]->nilai,
								// "warningStokJMB" => $warningStokJMB[0]->nilai
							);

		echo json_encode($dataArray);
	}

	function dataPenjualanPertahun(){
		if(!empty($_POST['tanggal'])){
			$tahun = $_POST['tanggal'];
		} else {
			$tahun = date('Y');
		}
		$id_toko = $_SESSION['id_toko'];

		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$totalSales = $this->model_dashboard_brand->total_sales_pertahun($brand,$this->global['idStore'],$this->global['idUser'],$tahun,$id_toko);
		$transaction = $this->model_dashboard_brand->transactionPertahun($brand,$this->global['idStore'],$this->global['idUser'],$tahun,$id_toko);
		$totalItemTerjual = $this->model_dashboard_brand->totalItemTerjualPertahun($brand,$this->global['idStore'],$this->global['idUser'],$tahun,$id_toko);
        
        $totalMargin = $this->model_dashboard_brand->totalMarginPertahun($brand,$this->global['idStore'],$this->global['idUser'],$tahun,$id_toko);
        
        $totalMargin = number_format($totalMargin*100,2);
        
        $totalInv = $this->model_dashboard_brand->totalInv($brand);
		//$totalInvPDG = $this->model_dashboard_brand->totalInvPDG();

		// $warningStokPDG = $this->model_dashboard_brand->warningStokPDG();
		// $warningStokPKU = $this->model_dashboard_brand->warningStokPKU();
		// $warningStokJMB = $this->model_dashboard_brand->warningStokJMB();

		if($transaction > 0){
			$basketSize = number_format($totalSales/$transaction,'0','','');
		} else {
			$basketSize = 0;	
		}

		$dataArray[] = array(
								"totalSales" => $totalSales, 
								"transaction" => $transaction,
								"basketSize" => $basketSize,
								"totalItemTerjual" => $totalItemTerjual,
                                "totalMargin" => $totalMargin,
                                "totalInv" => $totalInv[0]->nilai
								// "totalInvJMB" => $totalInvJMB[0]->nilai
								// "warningStokPDG" => $warningStokPDG[0]->nilai,
								// "warningStokPKU" => $warningStokPKU[0]->nilai,
								// "warningStokJMB" => $warningStokJMB[0]->nilai
							);

		echo json_encode($dataArray);
	}

	function salesPerHour(){
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
		} else{
			$tanggal = date('Y-m-d');
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesByHour = $this->model_dashboard_brand->salesByHour($brand,$this->global['idStore'],$this->global['idUser'],$tanggal,$id_toko);

		$numRows = $salesByHour->num_rows();

		if($numRows > 0){
			foreach($salesByHour->result() as $dt){
				$date[] = $dt->tanggal;
				$sales[] = $dt->total;
			}

			$data['tanggal'] = json_encode($date);
			$data['sales'] = json_encode($sales);
			$this->load->view('staff/salesByHour',$data);
		} else {
			$this->load->view("staff/noData");
		}
	}

	function salesPerHourMonth(){
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
			$bulan = substr($tanggal, 5,2);
        	$tahun = substr($tanggal, 0,4);
		} else{
			$bulan = date('m');
			$tahun = date('Y');
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesByHour = $this->model_dashboard_brand->salesByHourMonth($brand,$this->global['idStore'],$this->global['idUser'],$bulan,$tahun,$id_toko);

		$numRows = $salesByHour->num_rows();

		if($numRows > 0){
			foreach($salesByHour->result() as $dt){
				$date[] = $dt->tanggal;
				$sales[] = $dt->total;
			}

			$data['tanggal'] = json_encode($date);
			$data['sales'] = json_encode($sales);
			$this->load->view('staff/salesByHour',$data);
		} else {
			$this->load->view("staff/noData");
		}
	}

	function salesPerHourYear(){
		if(!empty($_POST['tanggal'])){
			$tahun = $_POST['tanggal'];
		} else{
			$tahun = date('Y');
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesByHour = $this->model_dashboard_brand->salesByHourYear($brand,$this->global['idStore'],$this->global['idUser'],$tahun,$id_toko);

		$numRows = $salesByHour->num_rows();

		if($numRows > 0){
			foreach($salesByHour->result() as $dt){
				$date[] = $dt->tanggal;
				$sales[] = $dt->total;
			}

			$data['tanggal'] = json_encode($date);
			$data['sales'] = json_encode($sales);
			$this->load->view('staff/salesByHour',$data);
		} else {
			$this->load->view("staff/noData");
		}
	}

	function perkategoriSales(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];
		//$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesPerkategori = $this->model_dashboard->salesPerkategori($this->global['idStore'],$this->global['idUser'],$date,$id_toko);

		$numRows = $salesPerkategori->num_rows();
		$total = 0;
		if($numRows > 0){
			foreach($salesPerkategori->result() as $row){
				$total += $row->totalPenjualan;
			}

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = ($row->totalPenjualan/$total)*100;
			}

			$data['kategori'] = json_encode($kategori);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/perkategoriSales",$data);

		} else {
			$this->load->view("staff/noData");
		}
	}

	function perkategoriSalesMonth(){
		if(empty($_POST['tanggal'])){
			$bulan = date('m');
			$tahun = date('Y');
		} else {
			$date = $_POST['tanggal'];
			$bulan = substr($date, 5,2);
        	$tahun = substr($date, 0,4);
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesPerkategori = $this->model_dashboard->salesPerkategoriMonth($this->global['idStore'],$this->global['idUser'],$bulan,$tahun,$id_toko);

		$numRows = $salesPerkategori->num_rows();

		if($numRows > 0){

			foreach($salesPerkategori->result() as $row){
				$total += $row->totalPenjualan;
			}

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = ($row->totalPenjualan/$total)*100;
			}

			$data['kategori'] = json_encode($kategori);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/perkategoriSales",$data);

		} else {
			$this->load->view("staff/noData");
		}
	}

	function perkategoriSalesYear(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesPerkategori = $this->model_dashboard->salesPerkategoriYear($this->global['idStore'],$this->global['idUser'],$tahun,$id_toko);

		$numRows = $salesPerkategori->num_rows();

		if($numRows > 0){

			foreach($salesPerkategori->result() as $row){
				$total += $row->totalPenjualan;
			}

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = ($row->totalPenjualan/$total)*100;
			}

			$data['kategori'] = json_encode($kategori);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/perkategoriSales",$data);

		} else {
			$this->load->view("staff/noData");
		}
	}
	function fastMoving(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];
		//$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$fastMoving = $this->model_dashboard->fastMoving($this->global['idStore'],$this->global['idUser'],$date,$id_toko);

		$numRows = $fastMoving->num_rows();

		if($numRows > 0){
			
			foreach($fastMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/fastMoving",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function fastMovingMonth(){
		if(empty($_POST['tanggal'])){
			$bulan = date('m');
			$tahun = date('Y');
		} else {
			$date = $_POST['tanggal'];
			$bulan = substr($date, 5,2);
        	$tahun = substr($date, 0,4);
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$fastMoving = $this->model_dashboard->fastMovingMonth($this->global['idStore'],$this->global['idUser'],$bulan,$tahun,$id_toko);

		$numRows = $fastMoving->num_rows();
		$total = 0;

		if($numRows > 0){
			foreach($fastMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/fastMoving",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function fastMovingYear(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$fastMoving = $this->model_dashboard->fastMovingYear($this->global['idStore'],$this->global['idUser'],$tahun,$id_toko);

		$numRows = $fastMoving->num_rows();

		if($numRows > 0){
			foreach($fastMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/fastMoving",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function tebusMurah(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);

		$tebusMurah = $this->model_dashboard_brand->tebusMurah($brand,$this->global['idStore'],$this->global['idUser'],$date);

		$numRows = $tebusMurah->num_rows();

		if($numRows > 0){
			foreach($tebusMurah->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/tebusMurah",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function slowMoving(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$slowMoving = $this->model_dashboard_brand->slowMoving($this->global['idStore'],$this->global['idUser'],$date,$id_toko);

		$numRows = $slowMoving->num_rows();

		if($numRows > 0){
			foreach($slowMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/slowMoving",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function salesPerBrand1(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];
		//$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesPerBrand1 = $this->model_dashboard->salesPerBrand1($this->global['idStore'],$this->global['idUser'],$date,$id_toko);

		$numRows = $salesPerBrand1->num_rows();

		$total = 0;

		if($numRows > 0){

			foreach($salesPerBrand1->result() as $row){
				$total += $row->qty;
			}

			foreach($salesPerBrand1->result() as $row){
				$produk[] = $row->produk;
				$qty[] = ($row->qty/$total)*100;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/perBrand1",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function salesPerBrand2(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesPerBrand2 = $this->model_dashboard->salesPerBrand2($this->global['idStore'],$this->global['idUser'],$date,$id_toko);

		$numRows = $salesPerBrand2->num_rows();
		$total = 0;

		if($numRows > 0){
			foreach($salesPerBrand2->result() as $row){
				$total += $row->qty;
			}

			foreach($salesPerBrand2->result() as $row){
				$produk[] = $row->produk;
				$qty[] = ($row->qty/$total)*100;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/perBrand2",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function salesPerBrand1Month(){
		if(empty($_POST['tanggal'])){
			$bulan = date('m');
			$tahun = date('Y');
		} else {
			$date = $_POST['tanggal'];
			$bulan = substr($date, 5,2);
        	$tahun = substr($date, 0,4);
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesPerBrand1 = $this->model_dashboard->salesPerBrand1Month($this->global['idStore'],$this->global['idUser'],$bulan,$tahun,$id_toko);

		$numRows = $salesPerBrand1->num_rows();

		if($numRows > 0){
			foreach($salesPerBrand1->result() as $row){
				$total += $row->qty;
			}

			foreach($salesPerBrand1->result() as $row){
				$produk[] = $row->produk;
				$qty[] = ($row->qty/$total)*100;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/perBrand1",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function salesPerBrand2Month(){
		if(empty($_POST['tanggal'])){
			$bulan = date('m');
			$tahun = date('Y');
		} else {
			$date = $_POST['tanggal'];
			$bulan = substr($date, 5,2);
        	$tahun = substr($date, 0,4);
		}
		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesPerBrand2 = $this->model_dashboard->salesPerBrand2Month($this->global['idStore'],$this->global['idUser'],$bulan,$tahun,$id_toko);

		$numRows = $salesPerBrand2->num_rows();

		if($numRows > 0){
			foreach($salesPerBrand2->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/perBrand2",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function salesPerBrand1Year(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesPerBrand1 = $this->model_dashboard->salesPerBrand1Year($this->global['idStore'],$this->global['idUser'],$tahun,$id_toko);

		$numRows = $salesPerBrand1->num_rows();

		if($numRows > 0){
			foreach($salesPerBrand1->result() as $row){
				$total += $row->qty;
			}

			foreach($salesPerBrand1->result() as $row){
				$produk[] = $row->produk;
				$qty[] = ($row->qty/$total)*100;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/perBrand1",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function salesPerBrand2Year(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);
		$salesPerBrand2 = $this->model_dashboard->salesPerBrand2Year($this->global['idStore'],$this->global['idUser'],$tahun,$id_toko);

		$numRows = $salesPerBrand2->num_rows();

		if($numRows > 0){
			foreach($salesPerBrand2->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/perBrand2",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function slowMovingMonth(){
		if(empty($_POST['tanggal'])){
			$bulan = date('m');
			$tahun = date('Y');
		} else {
			$date = $_POST['tanggal'];
			$bulan = substr($date, 5,2);
        	$tahun = substr($date, 0,4);
		}

		$id_toko = $_SESSION['id_toko'];

		$slowMoving = $this->model_dashboard_brand->slowMovingMonth($this->global['idStore'],$this->global['idUser'],$bulan,$tahun,$id_toko);

		$numRows = $slowMoving->num_rows();

		if($numRows > 0){
			foreach($slowMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/slowMoving",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function slowMovingYear(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];
		$slowMoving = $this->model_dashboard_brand->slowMovingYear($this->global['idStore'],$this->global['idUser'],$tahun,$id_toko);

		$numRows = $slowMoving->num_rows();

		if($numRows > 0){
			foreach($slowMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("staff/slowMoving",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	function salesPerKasir(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];
		$salesPerkasir = $this->model_dashboard_brand->salesPerkasir($this->global['idStore'],$this->global['idUser'],$date,$id_toko);

		$numRows = $salesPerkasir->num_rows();

		if($numRows > 0){
			foreach($salesPerkasir->result() as $row){
				$kasir[] = $row->first_name;
				$sales[] = $row->total;
			}

			$data['kasir'] = json_encode($kasir);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/salesByKasir",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}
	

	function salesPerKasirMonth(){
		if(empty($_POST['tanggal'])){
			$bulan = date('m');
			$tahun = date('Y');
		} else {
			$date = $_POST['tanggal'];
			$bulan = substr($date, 5,2);
        	$tahun = substr($date, 0,4);
		}

		$id_toko = $_SESSION['id_toko'];
		$salesPerkasir = $this->model_dashboard_brand->salesPerkasirMonth($this->global['idStore'],$this->global['idUser'],$bulan,$tahun,$id_toko);

		$numRows = $salesPerkasir->num_rows();

		if($numRows > 0){
			foreach($salesPerkasir->result() as $row){
				$kasir[] = $row->first_name;
				$sales[] = $row->total;
			}

			$data['kasir'] = json_encode($kasir);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/salesByKasir",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}

	function salesPerKasirYear(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = $_SESSION['id_toko'];

		$salesPerkasir = $this->model_dashboard_brand->salesPerkasirYear($this->global['idStore'],$this->global['idUser'],$tahun,$id_toko);

		$numRows = $salesPerkasir->num_rows();

		if($numRows > 0){
			foreach($salesPerkasir->result() as $row){
				$kasir[] = $row->first_name;
				$sales[] = $row->total;
			}

			$data['kasir'] = json_encode($kasir);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/salesByKasir",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}

	function hariLiniMasa(){
		$this->load->view("staff/hariLiniMasa");
	}

	function bulanLiniMasa(){
		$this->load->view("staff/bulanLiniMasa");
	}

	function tahunLiniMasa(){
		$this->load->view("staff/tahunLiniMasa");
	}

	function loading(){
		echo "<center><img src='".base_url('assets/loading.gif')."'/></center>";
	}

	function convertDateMonth($tanggal){
		$month = substr($tanggal, 0,2);
    	$year  = substr($tanggal, 3,4);
    		
    		if($month=='01'){
    			$var =  "Januari"." ".$year;
    		} elseif($month=='02'){
    			$var =   "Februari"." ".$year;
    		} elseif($month=='03'){
    			$var =   "Maret"." ".$year;
    		} elseif($month=='04'){
    			$var =   "April"." ".$year;
    		} elseif($month=='05'){
    			$var =   "Mei"." ".$year;
    		} elseif($month=='06'){
    			$var =   "Juni"." ".$year;
    		} elseif($month=='07'){
    			$var =   "Juli"." ".$year;
    		} elseif($month=='08'){
    			$var =   "Agustus"." ".$year;
    		} elseif($month=='09'){
    			$var =   "September"." ".$year;
    		} elseif($month=='10'){
    			$var =   "Oktober"." ".$year;
    		} elseif($month=='11'){
    			$var =   "Nopember"." ".$year;
    		} elseif($month=='12'){
    			$var =   "Desember"." ".$year;
    		}

    		return $var;
	}

	function liniMasa(){
		$this->load->model("modelLiniMasa_brand");

		$start 		 = $_POST['dateStart'];
		$end 		 = $_POST['dateEnd'];
		$type 		 = $_POST['type'];

		$id_toko = $_SESSION['id_toko'];
		$brand = $this->model_dashboard_brand->list_brand($this->global['idUser']);

		if($type=='day'){
			$data['periode'] 	= date_format(date_create($start),"d M Y")." - ".date_format(date_create($end),"d M Y");
			$akumulasiPendapatan = $this->modelLiniMasa_brand->akumulasiPendapatan($brand,$this->global['idStore'],$this->global['idUser'],$start,$end,$id_toko);
			$potongan 			 = $this->modelLiniMasa_brand->potongan($brand,$this->global['idStore'],$this->global['idUser'],$start,$end,$id_toko);
		} elseif($type=='month'){
			$data['periode'] 	= $this->convertDateMonth($start)." - ".$this->convertDateMonth($end);
			$akumulasiPendapatan = $this->modelLiniMasa_brand->akumulasiPendapatanPermonth($brand,$this->global['idStore'],$this->global['idUser'],$start,$end,$id_toko);
			$potongan 			 = $this->modelLiniMasa_brand->potonganPermonth($brand,$this->global['idStore'],$this->global['idUser'],$start,$end,$id_toko);
		} elseif($type=='year'){
			$data['periode'] 	= $start." - ".$end;
			$akumulasiPendapatan = $this->modelLiniMasa_brand->akumulasiPendapatanPeryear($brand,$this->global['idStore'],$this->global['idUser'],$start,$end,$id_toko);
			$potongan 			 = $this->modelLiniMasa_brand->potonganPeryear($brand,$this->global['idStore'],$this->global['idUser'],$start,$end,$id_toko);
		}

		$numRows = $akumulasiPendapatan->num_rows();

		foreach($akumulasiPendapatan->result() as $row){
			if($type=='day'){
				$dateFormat = date_format(date_create($row->tanggal),"d M Y");
			} elseif($type=='month'){
				$dateFormat = date_format(date_create($row->tanggal),"M Y");
			} elseif($type=='year'){
				$dateFormat = date_format(date_create($row->tanggal),"Y");
			}

			$dataTitle[] = $dateFormat;
			$dataVal[] 	 = $row->total; 
		}

		foreach($potongan as $dt){
			$dataPotongan[] = $dt->total;
		}

		if($numRows > 0){
			$data['title'] 				= json_encode($dataTitle);
			$data['value']  			= json_encode($dataVal);
			$data['potongan'] 			= json_encode($dataPotongan);
			$data['akumulasiIncome'] 	= $akumulasiPendapatan;
			$data['type'] 				= $type;
			$data['start']  			= $start;
			$data['end'] 				= $end;
			$this->load->view("staff/akumulasiPendapatan",$data);
		} else {
			$this->load->view("staff/test");
		}
	}
}