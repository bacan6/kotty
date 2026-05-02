<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Growth extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model(array("model1","model_dashboard"));
		$this->load->library("session");
	
		$this->isLoggedIn($this->global['idUser'],1,12);
	}

	function index(){
		$this->load->model("model_dashboard");
		$this->global['pageTitle'] = "SOLUSI POS - Growth Sales";
		$this->loadViews("growth/body",$this->global,NULL,"growth/footer_dashboard");
	}

	function dayFilter(){
		$this->load->view("staff/dayFilter");
	}

	function bulanFilter(){
		$this->load->view("staff/bulanFilter");
	}

	function tahunFilter(){
		$this->load->view("staff/tahunFilter");
	}

	function dataPenjualan(){
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
		} else {
			$tanggal = date('Y-m-d');
		}

		$totalSales = $this->model_dashboard->total_sales($this->global['idStore'],$this->global['idUser'],$tanggal);
		$transaction = $this->model_dashboard->transaction($this->global['idStore'],$this->global['idUser'],$tanggal);
		$totalItemTerjual = $this->model_dashboard->totalItemTerjual($this->global['idStore'],$this->global['idUser'],$tanggal);
        
        $totalInv = $this->model_dashboard->totalInv();
        
        $totalMargin = $this->model_dashboard->totalMargin($this->global['idStore'],$this->global['idUser'],$tanggal);
        
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

		$totalSales = $this->model_dashboard->total_sales_perbulan($this->global['idStore'],$this->global['idUser'],$bulan,$tahun);
		$transaction = $this->model_dashboard->transactionPerbulan($this->global['idStore'],$this->global['idUser'],$bulan,$tahun);
		$totalItemTerjual = $this->model_dashboard->totalItemTerjualPerbulan($this->global['idStore'],$this->global['idUser'],$bulan,$tahun);
        
        $totalMargin = $this->model_dashboard->totalMarginPerbulan($this->global['idStore'],$this->global['idUser'],$bulan,$tahun);
        
        $totalInv = $this->model_dashboard->totalInv();
        
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
                                "totalInv" => $totalInv
							);

		echo json_encode($dataArray);
	}

	function dataPenjualanPertahun(){
		if(!empty($_POST['tanggal'])){
			$tahun = $_POST['tanggal'];
		} else {
			$tahun = date('Y');
		}

		$totalSales = $this->model_dashboard->total_sales_pertahun($this->global['idStore'],$this->global['idUser'],$tahun);
		$transaction = $this->model_dashboard->transactionPertahun($this->global['idStore'],$this->global['idUser'],$tahun);
		$totalItemTerjual = $this->model_dashboard->totalItemTerjualPertahun($this->global['idStore'],$this->global['idUser'],$tahun);
        
        $totalMargin = $this->model_dashboard->totalMarginPertahun($this->global['idStore'],$this->global['idUser'],$tahun);
        
        $totalMargin = number_format($totalMargin*100,2);
        
        $totalInv = $this->model_dashboard->totalInv();

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
                                "totalInv" => $totalInv
							);

		echo json_encode($dataArray);
	}

	function salesPerHour(){
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
		} else{
			$tanggal = date('Y-m-d');
		}

		$salesByHour = $this->model_dashboard->salesByHour($this->global['idStore'],$this->global['idUser'],$tanggal);

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

		$salesByHour = $this->model_dashboard->salesByHourMonth($this->global['idStore'],$this->global['idUser'],$bulan,$tahun);

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

		$salesByHour = $this->model_dashboard->salesByHourYear($this->global['idStore'],$this->global['idUser'],$tahun);

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

		$salesPerkategori = $this->model_dashboard->salesPerkategori($this->global['idStore'],$this->global['idUser'],$date);

		$numRows = $salesPerkategori->num_rows();

		if($numRows > 0){

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = $row->totalPenjualan;
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

		$salesPerkategori = $this->model_dashboard->salesPerkategoriMonth($this->global['idStore'],$this->global['idUser'],$bulan,$tahun);

		$numRows = $salesPerkategori->num_rows();

		if($numRows > 0){

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = $row->totalPenjualan;
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

		$salesPerkategori = $this->model_dashboard->salesPerkategoriYear($this->global['idStore'],$this->global['idUser'],$tahun);

		$numRows = $salesPerkategori->num_rows();

		if($numRows > 0){

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = $row->totalPenjualan;
			}

			$data['kategori'] = json_encode($kategori);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/perkategoriSales",$data);

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

		$salesPerkasir = $this->model_dashboard->salesPerkasir($this->global['idStore'],$this->global['idUser'],$date);

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

		$salesPerkasir = $this->model_dashboard->salesPerkasirMonth($this->global['idStore'],$this->global['idUser'],$bulan,$tahun);

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

		$salesPerkasir = $this->model_dashboard->salesPerkasirYear($this->global['idStore'],$this->global['idUser'],$tahun);

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

	function liniMasa($departement=1){
		$this->load->model("modelGrowth");
		$this->load->model("modelLiniMasa");

		$start 		 = $_POST['dateStart'];
		$end 		 = $_POST['dateEnd'];
		$type 		 = $_POST['type'];

		if($type=='day'){
			$data['periode'] 	= date_format(date_create($start),"d M Y")." - ".date_format(date_create($end),"d M Y");
			$akumulasiPendapatan = $this->modelLiniMasa->akumulasiPendapatan($this->global['idStore'],$this->global['idUser'],$start,$end);
			$potongan 			 = $this->modelLiniMasa->potongan($this->global['idStore'],$this->global['idUser'],$start,$end);
		} elseif($type=='month'){
			$data['periode'] 	= $this->convertDateMonth($start)." - ".$this->convertDateMonth($end);
			$akumulasiPendapatan = $this->modelLiniMasa->akumulasiPendapatanPermonth($this->global['idStore'],$this->global['idUser'],$start,$end);
			$potongan 			 = $this->modelLiniMasa->potonganPermonth($this->global['idStore'],$this->global['idUser'],$start,$end);
		} elseif($type=='year'){
			$data['periode'] 	= $start." - ".$end;
			$akumulasiPendapatan = $this->modelLiniMasa->akumulasiPendapatanPeryear($this->global['idStore'],$this->global['idUser'],$start,$end);
			$potongan 			 = $this->modelLiniMasa->potonganPeryear($this->global['idStore'],$this->global['idUser'],$start,$end);
		}

		$numRows = $akumulasiPendapatan->num_rows();
		$growth = '';$pendapatan=0;
		foreach($akumulasiPendapatan->result() as $row){
			if($type=='day'){
				$dateFormat = date_format(date_create($row->tanggal),"d M Y");
			} elseif($type=='month'){
				$dateFormat = date_format(date_create($row->tanggal),"M Y");
			} elseif($type=='year'){
				$dateFormat = date_format(date_create($row->tanggal),"Y");
			}

			$growth = ($pendapatan>0)? (($row->total - $pendapatan)/$pendapatan)*100:0;
			$pendapatan = $row->total; 
			$dataTitle[] = $dateFormat."(".number_format($growth,2)."%)";
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
			$this->load->view("growth/akumulasiPendapatan",$data);
		} else {
			$this->load->view("growth/test");
		}
	}

	function growthDepartment(){
		$this->load->model("modelGrowth");

		$start 		 = $_POST['dateStart'];
		$end 		 = $_POST['dateEnd'];
		$type 		 = $_POST['type'];
		$department  = $_POST['department'];
		
		$listDept = $this->modelGrowth->listOfDepartment($department);

	foreach($listDept->result() as $dept){	
		if($type=='day'){
			$data['periode'] 	= date_format(date_create($start),"d M Y")." - ".date_format(date_create($end),"d M Y");
			$akumulasiPendapatan = $this->modelGrowth->akumulasiPendapatan($dept->id_kategori,$this->global['idStore'],$this->global['idUser'],$start,$end);
			$potongan 			 = $this->modelGrowth->potongan($dept->id_kategori,$this->global['idStore'],$this->global['idUser'],$start,$end);
		} elseif($type=='month'){
			$data['periode'] 	= $this->convertDateMonth($start)." - ".$this->convertDateMonth($end);
			$akumulasiPendapatan = $this->modelGrowth->akumulasiPendapatanPermonth($dept->id_kategori,$this->global['idStore'],$this->global['idUser'],$start,$end);
			$potongan 			 = $this->modelGrowth->potonganPermonth($dept->id_kategori,$this->global['idStore'],$this->global['idUser'],$start,$end);
		} elseif($type=='year'){
			$data['periode'] 	= $start." - ".$end;
			$akumulasiPendapatan = $this->modelGrowth->akumulasiPendapatanPeryear($dept->id_kategori,$this->global['idStore'],$this->global['idUser'],$start,$end);
			$potongan 			 = $this->modelGrowth->potonganPeryear($dept->id_kategori,$this->global['idStore'],$this->global['idUser'],$start,$end);
		}

		$numRows = $akumulasiPendapatan->num_rows();
		$growth = '';$pendapatan=0;
		foreach($akumulasiPendapatan->result() as $row){
			if($type=='day'){
				$dateFormat = date_format(date_create($row->tanggal),"d M Y");
			} elseif($type=='month'){
				$dateFormat = date_format(date_create($row->tanggal),"M Y");
			} elseif($type=='year'){
				$dateFormat = date_format(date_create($row->tanggal),"Y");
			}

			$growth = ($pendapatan>0)? (($row->total - $pendapatan)/$pendapatan)*100:0;
			$pendapatan = $row->total; 
			if ($growth==0) $string = "";
			else if ($growth>0) $string = "<sup><font color=green>Naik</font></sup>";
			else if ($growth<0) $string = "<sup><font color=red>Turun</font></sup>";
			$dataTitle[] = $dateFormat."<br>(".number_format($growth,2)."%) $string";
			$dataVal[] 	 = $row->total; 
		}

		foreach($potongan as $dt){
			$dataPotongan[] = $dt->total;
		}

		if($numRows > 0){
			$data['title'] 				= $dataTitle;
			$data['value']  			= $dataVal;
			$data['potongan'] 			= $dataPotongan;
			$data['akumulasiIncome'] 	= $akumulasiPendapatan;
			$data['type'] 				= $type;
			$data['start']  			= $start;
			$data['end'] 				= $end;
			$data['department']			= $dept;
			$this->load->view("growth/tableOfGrowth",$data);
		} else {
			$data['department']			= $dept;
			$this->load->view("growth/test",$data);
		}
	}
	}
}