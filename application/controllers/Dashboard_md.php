<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Dashboard_md extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model(array("model1","model_dashboard","modelMD"));
		$this->load->library("session");
	
		$this->isLoggedIn($this->global['idUser'],15,78);
	}

	function exportTop1000(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$status = $_GET['status'];
		$id_toko = $_GET['id_toko'];

		if($status=='green'){
			$where = "aging>10";
		}else if($status=='red'){
			$where = 'aging <= 5 and aging>0';
		}elseif($status=='yellow'){
			$where = 'aging <= 10 and aging>5';
		}else if($status=='black'){
			$where = 'aging <= 0';
		}

		$dalamProsesPO = $this->modelMD->dalamPO($where,$id_toko);

		foreach ($dalamProsesPO->result() as $w) {
			$po[$w->id_produk]=$w->qty+0;
			$supplier[$w->id_produk]=$w->qty_supplier+0;
			$no_po[$w->id_produk]=$w->no_po;
		}

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Terjual')
									  ->setCellValue('E1','Stok')
										->setCellValue('F1','HPP')
									//   ->setCellValue('G1','Last Sales')	
									  ->setCellValue('G1','Aging')
									  ->setCellValue('H1','PO')
									  ->setCellValue('I1','Supplier')
									  ->setCellValue('J1','No.PO');

		$data = $this->modelMD->top1000_data($where,$id_toko);

		$i=2;
		foreach($data as $row){


			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_produk, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->qty_terjual)
									 ->setCellValue('E'.$i,$row->stok) 
									->setCellValue('F'.$i,$row->hpp)
									//   ->setCellValue('G'.$i,$row->last_sales)
									  ->setCellValue('G'.$i,$row->aging)
									  ->setCellValue('H'.$i,$po[$row->id_produk])
									  ->setCellValue('I'.$i,$supplier[$row->id_produk])
									  ->setCellValue('J'.$i,$no_po[$row->id_produk]);
									  
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
	    header('Content-Disposition: attachment;filename=ExportDataTop1000SKU.xlsx');
	    //unduh file

	    $objWriter->save("php://output");	
	}

	function top1000(){
		$status = $_GET['status'];
		$id_toko = $_GET['id_toko'];

		if($status=='green'){
			$where = "aging>10";
		}else if($status=='red'){
			$where = 'aging <= 5 and aging>0';
		}elseif($status=='yellow'){
			$where = 'aging <= 10 and aging>5';
		}else if($status=='black'){
			$where = 'aging <= 0';
		}

		$dalamProsesPO = $this->modelMD->dalamPO($where,$id_toko);

		foreach ($dalamProsesPO->result() as $w) {
			$po[$w->id_produk]=$w->qty+0;
			$supplier[$w->id_produk]=$w->qty_supplier+0;
			$no_po[$w->id_produk]=$w->no_po;
		}

		$data = $this->modelMD->top1000_data($where,$id_toko);
		?>
		<a href="<?php echo base_url('dashboard_md/exportTop1000?status='.$status.'&id_toko='.$id_toko)?>" class="btn btn-primary btn-rounded m-b-5"><i class="fa fa-download"></i> Download Excel</a>
		<table class="table table-striped" width="100%">
		<tr>
			<th>No.</th>
			<th>SKU</th>
			<th>Nama Produk</th>
			<th>Terjual</th>
			<th>Stok</th>
			<th>HPP</th>
			<th>Last Receive</th>
			<th>Last Sales</th>
			<th>Aging</th>
			<th>PO</th>
			<th>Suppl.</th>
		</tr>
		<?php 
		$no = 0;
		foreach ($data as $row){
			$no++;
			?>
		<tr>
			<td><?php echo $no?></td>
			<td><a href="#!"><?php echo $row->id_produk?></a></td>
			<td><?php echo $row->nama_produk?></td>
			<td><?php echo $row->qty_terjual?></td>
			<td><?php echo $row->stok?></td>
			<td><?php echo $row->hpp?></td>
			<td><?php echo $row->last_receive?></td>
			<td><?php echo $row->last_sales?></td>
			<td><?php echo $row->aging?></td>
			<td><a href="<?php echo base_url('purchase_order/form_po?no_po='.$no_po[$row->id_produk])?>" target="_blank"><?php echo $po[$row->id_produk]?></a></td>
			<td><?php echo $supplier[$row->id_produk]?></td>
		</tr>
		<?php }?>

		</table><?php
	}

	function recalculateTop1000(){
		$toko = $_GET['id_toko'];
		$this->db->where("id_toko",$toko);
		$this->db->delete("top1000");
		$store = $this->db->get("ap_store")->result();
		foreach($store as $t){
			$data = $this->modelMD->recalculateTop1000($t->id_store);
			foreach($data->result() as $row){
				$aging = number_format($row->stok/($row->qty_terjual/21),0);
				$data_item[] = array(
					"id_toko" 			=> $t->id_store,
					"id_produk"	 		=> $row->id_produk,
					"qty_terjual"		=> $row->qty_terjual,
					"stok" 				=> $row->stok,
					"last_receive"		=> $row->last_receives,
					"last_sales"		=> $row->last_sales,
					"expire_date"		=> $row->expire_date,
					"calculate_time"	=> date('Y-m-d H:i:s'),
					"aging"				=> $aging
				  );
			}
			
		}
		$this->modelMD->insertBatchTop1000($data_item);
		redirect("dashboard_md?id_toko=".$toko);
	}

	function leadMD(){
		//error_reporting(E_ALL);ini_set('display_errors',1);
		$s = explode('~',$_GET['status']);
		$status = $s[0];
		$day = $s[1];
		$data = $this->modelMD->leadMD_data($status,$day);
		?>
		<table class="table table-striped" width="100%">
		<tr>
			<th>No.</th>
			<th>No. PO</th>
			<th><?php echo $status?></th>
			<th>Supplier</th>
			<th>Lead Time (Day)</th>
		</tr>
		<?php 
		$no = 0;
		foreach ($data as $row){
			$no++;
			?>
		<tr>
			<td><?php echo $no?></td>
			<td><a href="<?php echo base_url('purchase_order/form_po?no_po='.$row->no_po);?>" target="_blank"><?php echo $row->no_po?></a></td>
			<td><?php echo $row->waktu?></td>
			<td><?php echo $row->supplier?></td>
			<td><?php echo $row->lead_day?></td>
		</tr>
		<?php }?>

		</table><?php
	}

	function prepo(){
		$data['data'] = $this->modelMD->prepo_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/prepo',$data);
	}
	function waitingmd(){
		$data['data'] = $this->modelMD->waitingmd_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/waitingmd',$data);
	}
	function waitingsupplier(){
		$data['data'] = $this->modelMD->waitingsupplier_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/waitingsupplier',$data);
	}
	function waitingdelivery(){
		$data['data'] = $this->modelMD->waitingdelivery_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/waitingdelivery',$data);
	}
	function waitingreceive(){
		$data['data'] = $this->modelMD->waitingreceive_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/waitingreceive',$data);
	}
	function lowperformance(){
		$data['data'] = $this->modelMD->lowperformance_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/lowperformance',$data);
	}
	function transferblm(){
		$data['data'] = $this->modelMD->transferblm_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/transferblm',$data);
	}
	function todaypo(){
		$data['data'] = $this->modelMD->todaypo_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/todaypo',$data);
	}
	function belanja(){
		$data['data'] = $this->modelMD->belanja_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/belanja',$data);
	}
	function todayreceive(){
		$data['data'] = $this->modelMD->todayreceive_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/todayreceive',$data);
	}
	function todaytransfer(){
		$data['data'] = $this->modelMD->todaytransfer_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/todaytransfer',$data);
	}
	function todaytransferrec(){
		$data['data'] = $this->modelMD->todaytransferrec_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/todaytransferrec',$data);
	}
	function todayretur(){
		$data['data'] = $this->modelMD->todayretur_data($this->global['idStore'],$this->global['isSuperadmin'],$_SESSION['id_toko']);
		$this->load->view('dashboard_md/todayretur',$data);
	}
	function ssr3(){
		$this->load->view('dashboard_md/ssrbesar');
	}
	function ssr1(){
		$this->load->view('dashboard_md/ssrkecil');
	}

	function calculatePerfomance(){
		$this->load->model(array("modelBahanMasukMaterial"));
		$po = $this->modelBahanMasukMaterial->approvedPO();
		$n = 0;
		foreach ($po->result_array() as $dt) {
			$order = $dt['qtyOrder']==0? $dt['qtyOrderXPO']:$dt['qtyOrder'];
			$received = $this->modelBahanMasukMaterial->qtyReceived($dt['no_po']);
			$perfomance = ($received / $order)*100;

			$n++;

			$q = "UPDATE purchase_order set perfomance='$perfomance',perfomance_calculated=1 
								where no_po='$dt[no_po]'";

			$this->db->query($q);
		}
		redirect("dashboard_md?id_toko=".$_SESSION['id_toko']);
	}
	function calculateSSR(){
		$this->modelMD->hapusStokSSR($_SESSION['id_toko']);
		$this->modelMD->recalculateSSR($_SESSION['id_toko']);
		redirect("dashboard_md?id_toko=".$_SESSION['id_toko']);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Dashboard";
		$data['toko'] = $this->db->get("ap_store")->result();

		$_SESSION['id_toko']=empty($_GET['id_toko'])? '':$_GET['id_toko'];
		$this->loadViews("dashboard_md/body",$this->global,$data,"dashboard_md/footer_dashboard");
	}

	function dayFilter(){
		$this->load->view("dashboard_md/dayFilter");
	}

	function bulanFilter(){
		$this->load->view("dashboard_md/bulanFilter");
	}

	function tahunFilter(){
		$this->load->view("dashboard_md/tahunFilter");
	}

	function dataInv(){
		$id_toko = $_POST['id_toko'];
		if (empty($id_toko)){
			$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
			$totalInv = ($this->global['isSuperadmin']==1)?$this->model_dashboard->totalInv():$this->model_dashboard->totalInvStore($id_toko);
		}else $totalInv = $this->model_dashboard->totalInvStore($id_toko);

		$dataArray[] = array(
                                "totalInv" => $totalInv[0]->nilai
							);

		echo json_encode($dataArray);
	}

	function dataMD(){
		$_SESSION['ssrkecil'] = array();
		$_SESSION['ssrbesar'] = array();
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
		} else {
			$tanggal = date('Y-m-d');
		}

		$id_toko = $_SESSION['id_toko'];

		$waitingmd = $this->modelMD->waitingmd($this->global['idStore'],$this->global['isSuperadmin'],$id_toko);
		$waitingsupplier = $this->modelMD->waitingsupplier($this->global['idStore'],$this->global['isSuperadmin'],$id_toko);
		$waitingdelivery = $this->modelMD->waitingdelivery($this->global['idStore'],$this->global['isSuperadmin'],$id_toko);
		$todaypo = $this->modelMD->todaypo($this->global['idStore'],$this->global['isSuperadmin'],$id_toko);
		$todayreceive = $this->modelMD->todayreceive($this->global['idStore'],$this->global['isSuperadmin'],$id_toko);
		$todaytransfer = $this->modelMD->todaytransfer($this->global['idStore'],$this->global['isSuperadmin'],$id_toko);
		$todaytransferrec = $this->modelMD->todaytransferrec($this->global['idStore'],$this->global['isSuperadmin'],$id_toko);
		$todayretur = $this->modelMD->todayretur($this->global['idStore'],$this->global['isSuperadmin'],$id_toko);
		$transferblm = $this->modelMD->transferblm($this->global['idStore'],$this->global['isSuperadmin'],$id_toko);
		// $omseth1 = $this->modelMD->total_sales($this->global['idStore'],$this->global['isSuperadmin'],$id_toko);
		$belanja = $this->modelMD->todaybelanja($this->global['idStore'],$this->global['isSuperadmin'],$id_toko);

	
		
		$dataArray[] = array(
								"waitingmd" => $waitingmd,
								"waitingsupplier" => $waitingsupplier,
								"waitingdelivery" => $waitingdelivery,
								"todaypo"	=> $todaypo,
								"todayreceive"	=> $todayreceive,
								"todaytransfer"	=> $todaytransfer,
								"todaytransferrec"	=> $todaytransferrec,
								"todayretur"	=> $todayretur,
								"transferblm"	=> $transferblm,
								"belanja"	=> $belanja
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

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
		$totalSales = $this->model_dashboard->total_sales_perbulan($this->global['idStore'],$this->global['isSuperadmin'],$bulan,$tahun,$id_toko);
		$transaction = $this->model_dashboard->transactionPerbulan($this->global['idStore'],$this->global['isSuperadmin'],$bulan,$tahun,$id_toko);
		$totalItemTerjual = $this->model_dashboard->totalItemTerjualPerbulan($this->global['idStore'],$this->global['isSuperadmin'],$bulan,$tahun,$id_toko);
        
        $totalMargin = $this->model_dashboard->totalMarginPerbulan($this->global['idStore'],$this->global['isSuperadmin'],$bulan,$tahun,$id_toko);
        
        $totalInv = ($this->global['isSuperadmin']==1)?$this->model_dashboard->totalInv():$this->model_dashboard->totalInvStore($id_toko);
		

		// $warningStokPDG = $this->model_dashboard->warningStokPDG();
		// $warningStokPKU = $this->model_dashboard->warningStokPKU();
		// $warningStokJMB = $this->model_dashboard->warningStokJMB();
        
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

	function dataPenjualanPertahun(){
		if(!empty($_POST['tanggal'])){
			$tahun = $_POST['tanggal'];
		} else {
			$tahun = date('Y');
		}
		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
		
		$totalSales = $this->model_dashboard->total_sales_pertahun($this->global['idStore'],$this->global['isSuperadmin'],$tahun,$id_toko);
		$transaction = $this->model_dashboard->transactionPertahun($this->global['idStore'],$this->global['isSuperadmin'],$tahun,$id_toko);
		$totalItemTerjual = $this->model_dashboard->totalItemTerjualPertahun($this->global['idStore'],$this->global['isSuperadmin'],$tahun,$id_toko);
        
        $totalMargin = $this->model_dashboard->totalMarginPertahun($this->global['idStore'],$this->global['isSuperadmin'],$tahun,$id_toko);
        
        $totalMargin = number_format($totalMargin*100,2);
        
        $totalInv = ($this->global['isSuperadmin']==1)?$this->model_dashboard->totalInv():$this->model_dashboard->totalInvStore($id_toko);
		

		// $warningStokPDG = $this->model_dashboard->warningStokPDG();
		// $warningStokPKU = $this->model_dashboard->warningStokPKU();
		// $warningStokJMB = $this->model_dashboard->warningStokJMB();

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

	function salesPerHour(){
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
		} else{
			$tanggal = date('Y-m-d');
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
		$salesByHour = $this->model_dashboard->salesByHour($this->global['idStore'],$this->global['isSuperadmin'],$tanggal,$id_toko);

		$numRows = $salesByHour->num_rows();

		if($numRows > 0){
			foreach($salesByHour->result() as $dt){
				$date[] = $dt->tanggal;
				$sales[] = $dt->total;
			}

			$data['tanggal'] = json_encode($date);
			$data['sales'] = json_encode($sales);
			$this->load->view('dashboard_md/salesByHour',$data);
		} else {
			$this->load->view("dashboard_md/noData");
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

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$salesByHour = $this->model_dashboard->salesByHourMonth($this->global['idStore'],$this->global['isSuperadmin'],$bulan,$tahun,$id_toko);

		$numRows = $salesByHour->num_rows();

		if($numRows > 0){
			foreach($salesByHour->result() as $dt){
				$date[] = $dt->tanggal;
				$sales[] = $dt->total;
			}

			$data['tanggal'] = json_encode($date);
			$data['sales'] = json_encode($sales);
			$this->load->view('dashboard_md/salesByHour',$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}

	function salesPerHourYear(){
		if(!empty($_POST['tanggal'])){
			$tahun = $_POST['tanggal'];
		} else{
			$tahun = date('Y');
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$salesByHour = $this->model_dashboard->salesByHourYear($this->global['idStore'],$this->global['isSuperadmin'],$tahun,$id_toko);

		$numRows = $salesByHour->num_rows();

		if($numRows > 0){
			foreach($salesByHour->result() as $dt){
				$date[] = $dt->tanggal;
				$sales[] = $dt->total;
			}

			$data['tanggal'] = json_encode($date);
			$data['sales'] = json_encode($sales);
			$this->load->view('dashboard_md/salesByHour',$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}

	function perkategoriSales(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$salesPerkategori = $this->model_dashboard->salesPerkategori($this->global['idStore'],$this->global['isSuperadmin'],$date,$id_toko);

		$numRows = $salesPerkategori->num_rows();

		if($numRows > 0){

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = $row->totalPenjualan;
			}

			$data['kategori'] = json_encode($kategori);
			$data['sales'] = json_encode($sales);

			$this->load->view("dashboard_md/perkategoriSales",$data);

		} else {
			$this->load->view("dashboard_md/noData");
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

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$salesPerkategori = $this->model_dashboard->salesPerkategoriMonth($this->global['idStore'],$this->global['isSuperadmin'],$bulan,$tahun,$id_toko);

		$numRows = $salesPerkategori->num_rows();

		if($numRows > 0){

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = $row->totalPenjualan;
			}

			$data['kategori'] = json_encode($kategori);
			$data['sales'] = json_encode($sales);

			$this->load->view("dashboard_md/perkategoriSales",$data);

		} else {
			$this->load->view("dashboard_md/noData");
		}
	}

	function perkategoriSalesYear(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
		$salesPerkategori = $this->model_dashboard->salesPerkategoriYear($this->global['idStore'],$this->global['isSuperadmin'],$tahun,$id_toko);

		$numRows = $salesPerkategori->num_rows();

		if($numRows > 0){

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = $row->totalPenjualan;
			}

			$data['kategori'] = json_encode($kategori);
			$data['sales'] = json_encode($sales);

			$this->load->view("dashboard_md/perkategoriSales",$data);

		} else {
			$this->load->view("dashboard_md/noData");
		}
	}
	function fastMoving(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$fastMoving = $this->model_dashboard->fastMoving($this->global['idStore'],$this->global['isSuperadmin'],$date,$id_toko);

		$numRows = $fastMoving->num_rows();

		if($numRows > 0){
			foreach($fastMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/fastMoving",$data);
		} else {
			$this->load->view("dashboard_md/noData");
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

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$fastMoving = $this->model_dashboard->fastMovingMonth($this->global['idStore'],$this->global['isSuperadmin'],$bulan,$tahun,$id_toko);

		$numRows = $fastMoving->num_rows();

		if($numRows > 0){
			foreach($fastMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/fastMoving",$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}
	function fastMovingYear(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$fastMoving = $this->model_dashboard->fastMovingYear($this->global['idStore'],$this->global['isSuperadmin'],$tahun,$id_toko);

		$numRows = $fastMoving->num_rows();

		if($numRows > 0){
			foreach($fastMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/fastMoving",$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}
	function tebusMurah(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$tebusMurah = $this->model_dashboard->tebusMurah($this->global['idStore'],$this->global['isSuperadmin'],$date);

		$numRows = $tebusMurah->num_rows();

		if($numRows > 0){
			foreach($tebusMurah->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/tebusMurah",$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}
	function slowMoving(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
		$slowMoving = $this->model_dashboard->slowMoving($this->global['idStore'],$this->global['isSuperadmin'],$date,$id_toko);

		$numRows = $slowMoving->num_rows();

		if($numRows > 0){
			foreach($slowMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/slowMoving",$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}
	function salesPerBrand1(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
		$salesPerBrand1 = $this->model_dashboard->salesPerBrand1($this->global['idStore'],$this->global['isSuperadmin'],$date,$id_toko);

		$numRows = $salesPerBrand1->num_rows();

		if($numRows > 0){
			foreach($salesPerBrand1->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/perBrand1",$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}
	function salesPerBrand2(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$salesPerBrand2 = $this->model_dashboard->salesPerBrand2($this->global['idStore'],$this->global['isSuperadmin'],$date,$id_toko);

		$numRows = $salesPerBrand2->num_rows();

		if($numRows > 0){
			foreach($salesPerBrand2->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/perBrand2",$data);
		} else {
			$this->load->view("dashboard_md/noData");
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

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$salesPerBrand1 = $this->model_dashboard->salesPerBrand1Month($this->global['idStore'],$this->global['isSuperadmin'],$bulan,$tahun,$id_toko);

		$numRows = $salesPerBrand1->num_rows();

		if($numRows > 0){
			foreach($salesPerBrand1->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/perBrand1",$data);
		} else {
			$this->load->view("dashboard_md/noData");
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
		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
		$salesPerBrand2 = $this->model_dashboard->salesPerBrand2Month($this->global['idStore'],$this->global['isSuperadmin'],$bulan,$tahun,$id_toko);

		$numRows = $salesPerBrand2->num_rows();

		if($numRows > 0){
			foreach($salesPerBrand2->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/perBrand2",$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}
	function salesPerBrand1Year(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$salesPerBrand1 = $this->model_dashboard->salesPerBrand1Year($this->global['idStore'],$this->global['isSuperadmin'],$tahun,$id_toko);

		$numRows = $salesPerBrand1->num_rows();

		if($numRows > 0){
			foreach($salesPerBrand1->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/perBrand1",$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}
	function salesPerBrand2Year(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
		$salesPerBrand2 = $this->model_dashboard->salesPerBrand2Year($this->global['idStore'],$this->global['isSuperadmin'],$tahun,$id_toko);

		$numRows = $salesPerBrand2->num_rows();

		if($numRows > 0){
			foreach($salesPerBrand2->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/perBrand2",$data);
		} else {
			$this->load->view("dashboard_md/noData");
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

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$slowMoving = $this->model_dashboard->slowMovingMonth($this->global['idStore'],$this->global['isSuperadmin'],$bulan,$tahun,$id_toko);

		$numRows = $slowMoving->num_rows();

		if($numRows > 0){
			foreach($slowMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/slowMoving",$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}
	function slowMovingYear(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
		$slowMoving = $this->model_dashboard->slowMovingYear($this->global['idStore'],$this->global['isSuperadmin'],$tahun,$id_toko);

		$numRows = $slowMoving->num_rows();

		if($numRows > 0){
			foreach($slowMoving->result() as $row){
				$produk[] = $row->produk;
				$qty[] = $row->qty;
			}

			$data['produk'] = json_encode($produk);
			$data['qty'] = json_encode($qty);

			$this->load->view("dashboard_md/slowMoving",$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}
	function salesPerKasir(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
		$salesPerkasir = $this->model_dashboard->salesPerkasir($this->global['idStore'],$this->global['isSuperadmin'],$date,$id_toko);

		$numRows = $salesPerkasir->num_rows();

		if($numRows > 0){
			foreach($salesPerkasir->result() as $row){
				$kasir[] = $row->first_name;
				$sales[] = $row->total;
			}

			$data['kasir'] = json_encode($kasir);
			$data['sales'] = json_encode($sales);

			$this->load->view("dashboard_md/salesByKasir",$data);
		} else {
			$this->load->view("dashboard_md/noData");
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

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];
		$salesPerkasir = $this->model_dashboard->salesPerkasirMonth($this->global['idStore'],$this->global['isSuperadmin'],$bulan,$tahun,$id_toko);

		$numRows = $salesPerkasir->num_rows();

		if($numRows > 0){
			foreach($salesPerkasir->result() as $row){
				$kasir[] = $row->first_name;
				$sales[] = $row->total;
			}

			$data['kasir'] = json_encode($kasir);
			$data['sales'] = json_encode($sales);

			$this->load->view("dashboard_md/salesByKasir",$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}

	function salesPerKasirYear(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$id_toko = empty($_SESSION['id_toko']) ? $this->global['idStore'] : $_SESSION['id_toko'];

		$salesPerkasir = $this->model_dashboard->salesPerkasirYear($this->global['idStore'],$this->global['isSuperadmin'],$tahun,$id_toko);

		$numRows = $salesPerkasir->num_rows();

		if($numRows > 0){
			foreach($salesPerkasir->result() as $row){
				$kasir[] = $row->first_name;
				$sales[] = $row->total;
			}

			$data['kasir'] = json_encode($kasir);
			$data['sales'] = json_encode($sales);

			$this->load->view("dashboard_md/salesByKasir",$data);
		} else {
			$this->load->view("dashboard_md/noData");
		}
	}

	function hariLiniMasa(){
		$this->load->view("dashboard_md/hariLiniMasa");
	}

	function bulanLiniMasa(){
		$this->load->view("dashboard_md/bulanLiniMasa");
	}

	function tahunLiniMasa(){
		$this->load->view("dashboard_md/tahunLiniMasa");
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
		$this->load->model("modelLiniMasaMD");

		$start 		 = $_POST['dateStart'];
		$end 		 = $_POST['dateEnd'];
		$type 		 = $_POST['type'];

		$id_toko = empty($_SESSION['id_toko']) ? '' : $_SESSION['id_toko'];

		if($type=='day'){
			$data['periode'] 	= date_format(date_create($start),"d M Y")." - ".date_format(date_create($end),"d M Y");
			$akumulasiPendapatan = $this->modelLiniMasaMD->akumulasiPendapatan($this->global['idStore'],$this->global['isSuperadmin'],$start,$end,$id_toko);
			$potongan 			 = $this->modelLiniMasaMD->potongan($this->global['idStore'],$this->global['isSuperadmin'],$start,$end,$id_toko);
		} elseif($type=='month'){
			$data['periode'] 	= $this->convertDateMonth($start)." - ".$this->convertDateMonth($end);
			$akumulasiPendapatan = $this->modelLiniMasaMD->akumulasiPendapatanPermonth($this->global['idStore'],$this->global['isSuperadmin'],$start,$end,$id_toko);
			$potongan 			 = $this->modelLiniMasaMD->potonganPermonth($this->global['idStore'],$this->global['isSuperadmin'],$start,$end,$id_toko);
		} elseif($type=='year'){
			$data['periode'] 	= $start." - ".$end;
			$akumulasiPendapatan = $this->modelLiniMasaMD->akumulasiPendapatanPeryear($this->global['idStore'],$this->global['isSuperadmin'],$start,$end,$id_toko);
			$potongan 			 = $this->modelLiniMasaMD->potonganPeryear($this->global['idStore'],$this->global['isSuperadmin'],$start,$end,$id_toko);
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
			$this->load->view("dashboard_md/akumulasiPendapatan",$data);
		} else {
			$this->load->view("dashboard_md/test");
		}
	}







	function exportExcel(){
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
										->setCellValue('S1','Est. 14')
										->setCellValue('T1','Est. 21')
										->setCellValue('U1','SSR 14')
										->setCellValue('V1','SSR 21')
										->setCellValue('W1','Stock Aging')
										->setCellValue('X1','Info Retur')
										->setCellValue('Y1','Exp. Date');
		
		$dateStart 		= date('Y-m-d',strtotime("-30 day"));
 		$dateEnd 		= date('Y-m-d');
 		$toko 			= "7";
		$filterby 		= '';

		$day 		= 30;

		$laporan = $this->modelMD->exportDataMD($filterby,$dateStart, $dateEnd,$toko);

		$i=2;
		foreach($laporan->result() as $row){
			$stok = $row->stok+0;
			//$est =number_format(($row->qty_terjual/$day)*$dayMonth,2);
			$aging = number_format($row->stok/($row->qty_terjual/$day),0);
			$est14 =number_format(($row->qty_terjual/$day)*14,2);
			$est21 =number_format(($row->qty_terjual/$day)*21,2);
			$ssr14 = number_format($row->stok/$est14,2);
			$ssr21 = number_format($row->stok/$est21,2);

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
										->setCellValue('S'.$i,$est14)
										->setCellValue('T'.$i,$est21)
										->setCellValue('U'.$i,$ssr14)
										->setCellValue('V'.$i,$ssr21)
										->setCellValue('W'.$i,$aging)
										->setCellValue('X'.$i,$row->info_retur)
										->setCellValue('Y'.$i,$row->expire_date);
										// ->setCellValue('W'.$i,$orders[$row->id_produk])
										// ->setCellValue('X'.$i,$receives[$row->id_produk])
										// ->setCellValue('Y'.$i,$row->expire_date);

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
	    header('Content-Disposition: attachment;filename=Laporan Penjualan.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}
}