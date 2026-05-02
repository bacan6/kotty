<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Purchase_order_xrec extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library(array('session','encryption'));
		$this->load->model(array("model1","modelPurchaseOrder"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],4,63);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Exclusive PO";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data['supplier'] = $this->db->get("supplier");
		$data['brand'] = $this->db->get("brand");
		$data['store'] = $this->db->get("ap_store")->result();
		$this->loadViews("purchase_order/bodyPurchaseOrderXrec",$this->global,$data,"bahan_masuk/footer_barang_masuk_xrec");
	}

	function kosongkanCart(){
		$idUser = $this->global['idUser'];
		$this->modelPurchaseOrder->deleteCartPO($idUser);
	}

	function ajax_produk(){
		$q 			= $_GET['term'];
		//$id_brand 	= $_SESSION['id_supplier'];

		$get_bahan_baku_select2 = $this->modelPurchaseOrder->produkAjax($q);

		$data_array = array();

		foreach($get_bahan_baku_select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}

	function ajax_produk_supplier(){
		$q 			= $_POST['supplier'];
		$idToko = $this->model1->getIdStore($this->global['idUser']);
		$idUser = $this->global['idUser'];
		$del = $this->modelPurchaseOrder->deleteCartPO($idUser);
		$_SESSION['id_supplier']=$q;

		$getStok = $this->modelPurchaseOrder->produkAjaxSupplier2($q,$idToko);

		$data_array = array();$n=0;

		foreach($getStok->result() as $row){
			if($row->hpp>0){
				$dataCart = array(
								"idProduk"		=> $row->id_produk,
								"qty"			=> $row->pesan,
								"idUser" 		=> $idUser,
								"harga"			=> $row->hpp
						     );

			$this->modelPurchaseOrder->insertCartPO($dataCart);
			$n++;
			}
			
		}

		echo json_encode($n);
	}

	function insertPO(){
		$id_user  		= sprintf("%03d",$this->global['idUser']);
		$tanggal_po 	= date('Y-m-d');
		$tanggal_kirim 	= $_POST['tanggalKirim'];
		$jatuh_tempo 	= $_POST['jatuhTempo'];
		$keterangan 	= $_POST['keterangan'];
		$supplier  		= $_POST['supplier'];
		$alamat 		= $_POST['alamatPengiriman'];

		$cek_tanggal 	= $this->model1->cek_tanggal_terima($tanggal_po);

		$create_date 	= date_create($tanggal_po);
		$convert_date   = date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

		$no_inv = 'XPO'.$convert_date.$id_user.sprintf("%04d",$cek_tanggal+1);

		$data_masuk = array(
								"no_po" 			=> $no_inv,
								"tanggal_po" 		=> $tanggal_po,
								"tanggal_kirim"		=> $tanggal_kirim,
								"jatuh_tempo"		=> $jatuh_tempo,
								"alamat_pengiriman"	=> $alamat,
								"id_supplier"		=> $supplier,
								"keterangan"		=> $keterangan,
								"id_pic"			=> $this->global['idUser'],
                                "id_toko"			=> $this->global['idStore'],
								"status"			=> 1
							);
		
		$this->modelPurchaseOrder->insertPONumber($data_masuk);

		$viewDataPO = $this->modelPurchaseOrder->viewCartPO($this->global['idUser'],$this->global['idStore']);

		foreach($viewDataPO->result() as $row){
			$sku 			= $row->id_produk;
			$jumlah_beli	= $row->qty;
			$harga 			= $row->harga;

			if ($jumlah_beli>0){
				$data_bahan[]     = array(
										"no_po"			=> $no_inv,
										"sku"			=> $sku,
										"qty"			=> $jumlah_beli,
										"harga"			=> $harga,
										"tanggal"		=> $tanggal_po,
								   );
			}
			
		}

		$this->modelPurchaseOrder->insertPOItem($data_bahan);
		$this->modelPurchaseOrder->deleteCartPO($this->global['idUser']);
		echo $no_inv;

		//redirect("purchase_order/form_po?no_po=".$no_inv);
	}

	function daftar_po(){
		$this->global['pageTitle'] = "SOLUSI POS - Daftar PO";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("purchase_order/body_daftar_po",$this->global,NULL,"purchase_order/footerDaftarPO");
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function form_po(){
		$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelPurchaseOrder->purchase_item($no_po);
		$info_po = $this->modelPurchaseOrder->infoPurchase($no_po);

		$data['tanggal_po'] 		= $info_po->tanggal_po;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['supplier'] 			= $info_po->supplier;
		$data['alamat_sp'] 			= $info_po->alamat;
		$data['kontak_sp'] 			= $info_po->kontak;
		$data['ppn']				= $info_po->ppn;
		$data['nilai_ppn']			= $info_po->nilai_ppn;
		$data['alamat_pengiriman'] 	= $info_po->alamat_pengiriman;
		$data['tanggal_kirim']		= $info_po->tanggal_kirim;
		$data['idSupplier'] 		= $info_po->id_supplier;

		$this->global['pageTitle'] = "SOLUSI POS - Form Purchase Order";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("bahan_masuk/body_form_po",$this->global,$data,"bahan_masuk/footer_barang_masuk");
	}
	function form_po_checker(){
		$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelPurchaseOrder->purchase_item($no_po);
		$info_po = $this->modelPurchaseOrder->infoPurchase($no_po);

		$data['tanggal_po'] 		= $info_po->tanggal_po;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['supplier'] 			= $info_po->supplier;
		$data['alamat_sp'] 			= $info_po->alamat;
		$data['kontak_sp'] 			= $info_po->kontak;
		$data['ppn']				= $info_po->ppn;
		$data['nilai_ppn']			= $info_po->nilai_ppn;
		$data['alamat_pengiriman'] 	= $info_po->alamat_pengiriman;
		$data['tanggal_kirim']		= $info_po->tanggal_kirim;
		$data['idSupplier'] 		= $info_po->id_supplier;

		$this->global['pageTitle'] = "SOLUSI POS - Form Purchase Order";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("bahan_masuk/body_form_po_checker",$this->global,$data,"bahan_masuk/footer_barang_masuk");
	}

	function datatablesPO(){
		$this->load->model("modelBahanMasukMaterial");
        $idUser     = $this->global['idUser'];
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelBahanMasukMaterial->totalPOProduk();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore']);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<span class="label label-primary">Menunggu Approve</span>';
			} elseif($status==1){
				$button = '<span class="label label-success">Diterima</span>';
			} elseif($status==2){
				$button = '<span class="label label-danger">Ditolak</span>';
			} elseif($status==3){
				$button = '<span class="label label-info">Selesai</span>';
			}

			$output['data'][]=array($nomor_urut,"<a href='".base_url('purchase_order/form_po?no_po='.$dt['no_po'])."'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['tanggal_kirim'],$dt['supplier'],$dt['first_name'],$button);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_receive(){
		$this->load->view("navigation");
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);
		$data['receive_item'] = $this->modelPurchaseOrder->received_item($no_receive);
		$this->load->view("body_invoice_receive",$data);
		$this->load->view("footer");
	}	

	function sendEmailPOSupplier(){
		$no_po 	= $_POST['noPo'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelPurchaseOrder->purchase_item($no_po);
		$info_po = $this->modelPurchaseOrder->infoPurchase($no_po);
		$data['noPO'] = $no_po;

		$data['tanggal_po'] 		= $info_po->tanggal_po;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['supplier'] 			= $info_po->supplier;
		$data['alamat_sp'] 			= $info_po->alamat;
		$data['kontak_sp'] 			= $info_po->kontak;
		$data['ppn']				= $info_po->ppn;
		$data['nilai_ppn']			= $info_po->nilai_ppn;
		$data['alamat_pengiriman'] 	= $info_po->alamat_pengiriman;
		$data['tanggal_kirim']		= $info_po->tanggal_kirim;
		

		$mesg = $this->load->view("emailFormPO",$data,TRUE);

		$this->load->library("email");

		//get data email
		$dataEmail  = $this->db->get("settingemail")->row();

		$SMTPHost 	= $dataEmail->SMTPHost;
		$SMTPPort 	= $dataEmail->SMTPPort;
		$SMTPUser 	= $dataEmail->SMTPUser;
		$SMTPPass 	= $dataEmail->SMTPPas;
		$SenderName = $dataEmail->UserName;
		

		$config['protocol'] = 'smtp';
		$config['smtp_host'] = $SMTPHost;
		$config['smtp_port'] = $SMTPPort;
		$config['smtp_user'] = $SMTPUser;
		$config['smtp_pass'] = $SMTPPass;
		$config['mailtype'] = 'html';
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['newline'] = "\r\n";
		$config['crlf'] = "\r\n";
		
		//get email supplier
		$email = $this->modelPurchaseOrder->emailSupplier($_POST['idSupplier']);	

		$this->email->initialize($config);

		$this->email->from($SMTPUser, $SenderName);
		$this->email->to($email);

		$this->email->subject('Purchase Order | '.$no_po);
		$this->email->message($mesg);

		if($this->email->send()){
			echo "1";
		} else {
			echo "0";
			show_error($this->email->print_debugger());
		}
	}

	function cekEmailSupplier(){
		$idSupplier = $_POST['idSupplier'];

		//cek email
		$cekEmailIfExist = $this->modelPurchaseOrder->cekEmailIfExist($idSupplier);

		if($cekEmailIfExist == 1){
			echo 1;
		} else {
			echo 0;
		}
	}

	function insertCartPO(){
		$idProduk 		= $_POST['idProduk'];
		$idUser = $this->global['idUser'];
		$idStore=empty($this->global['idStore'])?12:$this->global['idStore'];
		$hargaProduk = $this->modelPurchaseOrder->hargaBeliProduk($idProduk,$idStore);

		//cek on cart if exist
		$cekCart = $this->modelPurchaseOrder->cekCartPO($idProduk,$idUser);

		if(empty($cekCart)){
			$dataCart = array(
								"idProduk"		=> $idProduk,
								"qty"			=> 1,
								"idUser" 		=> $idUser,
								"harga"			=> $hargaProduk
						     );

			$this->modelPurchaseOrder->insertCartPO($dataCart);
			echo 0;
		} else {			
			$id = $this->modelPurchaseOrder->getIdCart($idProduk,$idUser);
			echo $id;
		}
	}

	function cartPO(){
		$idUser = $this->global['idUser'];
		// DISINI TAMBAH SCRIPT
		// Ambil data iduser dan supplier
		$data['idStore'] = $this->global['idStore'];
		$data['viewCartPO'] = $this->modelPurchaseOrder->viewCartPO($idUser,$this->global['idStore']);
		$this->load->view("purchase_order/cartPOXrec",$data);
	}

	function updateQtyCart(){
		$id = $_POST['id'];
		$idUser   = $this->global['idUser'];
		$qty = $_POST['qty'];

		$dataUpdate = array(
								"qty"		=> $qty
					       );
		
		$this->modelPurchaseOrder->updateQtyCart($id,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelPurchaseOrder->totalPeritem($idUser,$id);

		echo number_format($totalPeritem,'0',',','.');
	}
	function updateDiskon1(){
		$id = $_POST['id'];
		$idUser   = $this->global['idUser'];
		$diskon = $_POST['diskon'];

		$dataUpdate = array(
								"diskon1"		=> $diskon
					       );
		
		$this->modelPurchaseOrder->updateQtyCart($id,$idUser,$dataUpdate);
		echo "1";
	}
	function updateDiskon2(){
		$id = $_POST['id'];
		$idUser   = $this->global['idUser'];
		$diskon = $_POST['diskon'];

		$dataUpdate = array(
								"diskon2"		=> $diskon
					       );
		
		$this->modelPurchaseOrder->updateQtyCart($id,$idUser,$dataUpdate);
		echo "1";
	}
	function updateBonusCart(){
		$id = $_POST['id'];
		$idUser   = $this->global['idUser'];
		$bonus = $_POST['bonus'];

		$dataUpdate = array(
								"bonus"		=> $bonus
					       );
		
		$this->modelPurchaseOrder->updateQtyCart($id,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelPurchaseOrder->totalPeritem($idUser,$id);

		echo number_format($totalPeritem,'0',',','.');
	}

	function totalCart(){
		$idUser   = $this->global['idUser'];
		$totalCart = $this->modelPurchaseOrder->totalCartPeruser($idUser);

		if($totalCart){
			echo $totalCart;
		} else {
			echo 0;
		}
	}


	function updateHargaCart(){
		$id = $_POST['id'];
		$idUser   = $this->global['idUser'];
		$harga = $_POST['harga'];

		$dataUpdate = array(
								"harga"		=> $harga
					       );

		$this->modelPurchaseOrder->updateHargaCart($id,$idUser,$dataUpdate);
	
		//get total peritem
		$totalPeritem = $this->modelPurchaseOrder->totalPeritem($idUser,$id);

		echo number_format($totalPeritem,'0',',','.');
	}
	function updateHargaJualCart(){
		$id = $_POST['id'];
		$idUser   = $this->global['idUser'];
		$harga = $_POST['harga'];

		$dataUpdate = array(
								"hargajual"		=> $harga
					       );

		$this->modelPurchaseOrder->updateHargaJualCart($id,$idUser,$dataUpdate);
	
		//get total peritem
		//$totalPeritem = $this->modelPurchaseOrder->totalPeritem($idUser,$idProduk);

		//echo number_format($totalPeritem,'0',',','.');
	}

	function hapusCart(){
		$id 	= $_POST['id'];
		$idUser = $this->global['idUser'];

		$this->modelPurchaseOrder->hapusCart($id,$idUser);
	}

}