<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Expired_product extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library(array('session','encryption'));
		$this->load->model(array("model1","modelExpired","modelProduk"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],4,52);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Expired Product";
		$this->global['navigation'] = $this->model1->callNavigation();
        $data['supplier'] = $this->db->get("supplier");
		$this->loadViews("expired_product/bodyExpired",$this->global,$data,"expired_product/footer_expired_product");
	}

	function ajax_produk(){
		$q 			= $_GET['term'];

		$get_bahan_baku_select2 = $this->modelExpired->produkAjax($q);

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
		$del = $this->modelExpired->deleteCartPO($idUser);

		$getStok = $this->modelExpired->produkAjaxSupplier($q,$idToko);

		$data_array = array();$n=0;

		foreach($getStok->result() as $row){
			$dataCart = array(
								"idProduk"		=> $row->id_produk,
								"qty"			=> $row->pesan,
								"idUser" 		=> $idUser,
								"harga"			=> $row->hpp
						     );

			$this->modelExpired->insertCartPO($dataCart);
			$n++;
		}

		echo json_encode($n);
	}

	function insertPO(){
		$id_user  		= sprintf("%03d",$this->global['idUser']);
        

		$no_inv = 'EXP'.$id_user.date('YmdHis');

		$data_masuk = array(
								"no_po" 			=> $no_inv,
                                "tanggal_po"        => date('Y-m-d'),
								"id_pic"			=> $this->global['idUser'],
                                "id_toko"			=> $this->global['idStore'],
								"keterangan"		=> $_REQUEST['keterangan'],
								"status"			=> 0
							);
		
		$this->modelExpired->insertPONumber($data_masuk);

		$viewDataPO = $this->modelExpired->viewCartPO($this->global['idUser']);

		foreach($viewDataPO->result() as $row){
			$sku 			= $row->id_produk;
			$jumlah_beli	= $row->qty;
			$harga 			= $row->harga;

			$harga_jual = $this->modelProduk->hargaJual($sku,$this->global['idStore']);
			$harga_beli = $this->modelProduk->hargaBeli($sku,$this->global['idStore']);

			$data_bahan[]     = array(
										"no_po"			=> $no_inv,
										"sku"			=> $sku,
										"qty"			=> $jumlah_beli,
										"harga"			=> $harga,
										"tanggal"		=> date('Y-m-d')
								   );

			$data_kartu_stok[] = array(
									"id_store"		=> $this->global['idStore'],
									"id_produk"		=> $sku,
									"qty"			=> '-'.$jumlah_beli,
									"harga"			=> $harga_jual,
									"hpp"			=> $harga_beli,
									"tanggal"		=> date('Y-m-d H:i:s'),
									"tipe"			=> 'Mutasi ke Warehouse',
									"no_transaksi"	=> $no_inv,
									"id_pic"		=> $this->global['idUser'],
									"keterangan"	=> $_REQUEST['keterangan']
								);
		$qty = $this->modelExpired->stokItem($this->global['idStore'],$sku);
		$qty_after = $qty - $jumlah_beli;
			$dataUpdate = array(
							"stok"		=> $qty_after
							   );
			// AKTIFKAN NANTI				   
			$this->modelExpired->updateStokItem($sku,$dataUpdate,$this->global['idStore']);
		}

		$this->modelExpired->insertPOItem($data_bahan);
		$this->modelExpired->deleteCartPO($this->global['idUser']);
		$this->model1->insertKartuStok($data_kartu_stok);
		echo $no_inv;

		//redirect("purchase_order/form_po?no_po=".$no_inv);
	}

	function daftar_po(){
		$this->global['pageTitle'] = "SOLUSI POS - Daftar Produk Kadaluarsa";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("expired_product/body_daftar_ep",$this->global,NULL,"expired_product/footerDaftarEP");
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function form_po(){
		$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['expired_product_item'] = $this->modelExpired->expired_product_item($no_po);
		$info_po = $this->modelExpired->infoPurchase($no_po);


		$this->global['pageTitle'] = "SOLUSI POS - Form Produk Kadaluarsa";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("expired_product/body_form_ep",$this->global,$data,"bahan_masuk/footer_barang_masuk");
	}

	function datatablesPO(){
		$this->load->model("modelBahanMasukMaterial");
        $idUser     = $this->global['idUser'];
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelBahanMasukMaterial->totalExProduk();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewExProduk($length,$start,$search,$idUser,$this->global['idStore']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewExProduk($length,$start,$search,$idUser,$this->global['idStore']);
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

			$output['data'][]=array($nomor_urut,"<a href='".base_url('expired_product/form_po?no_po='.$dt['no_po'])."'>".$dt['no_po']."</a>",$dt['keterangan'],$dt['tanggal_po'],$dt['first_name'],$dt['store'],$button);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_receive(){
		$this->load->view("navigation");
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);
		$data['receive_item'] = $this->modelExpired->received_item($no_receive);
		$this->load->view("body_invoice_receive",$data);
		$this->load->view("footer");
	}	

	function sendEmailPOSupplier(){
		$no_po 	= $_POST['noPo'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['expired_product_item'] = $this->modelExpired->expired_product_item($no_po);
		$info_po = $this->modelExpired->infoPurchase($no_po);
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
		$email = $this->modelExpired->emailSupplier($_POST['idSupplier']);	

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
		$cekEmailIfExist = $this->modelExpired->cekEmailIfExist($idSupplier);

		if($cekEmailIfExist == 1){
			echo 1;
		} else {
			echo 0;
		}
	}

	function insertCartPO(){
		$idProduk 		= $_POST['idProduk'];
		$idUser = $this->global['idUser'];
        $idStore= $this->global['idStore'];
		$hargaProduk = $this->modelExpired->hargaBeliProduk($idProduk,$idStore);

		//cek on cart if exist
		$cekCart = $this->modelExpired->cekCartPO($idProduk,$idUser);

		if($cekCart < 1){
			$dataCart = array(
								"idProduk"		=> $idProduk,
								"qty"			=> 1,
								"idUser" 		=> $idUser,
								"harga"			=> $hargaProduk
						     );

			$this->modelExpired->insertCartPO($dataCart);
			echo 0;
		} else {			
			$id = $this->modelExpired->getIdCart($idProduk,$idUser);
			echo $id;
		}
	}

	function cartEP(){
		$idUser = $this->global['idUser'];
		// DISINI TAMBAH SCRIPT
		// Ambil data iduser dan supplier
		$data['viewCartPO'] = $this->modelExpired->viewCartPO($idUser);
		$this->load->view("expired_product/cartEP",$data);
	}

	function updateQtyCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$qty = $_POST['qty'];

		$dataUpdate = array(
								"qty"		=> $qty
					       );
		
		$this->modelExpired->updateQtyCart($idProduk,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelExpired->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}

	function totalCart(){
		$idUser   = $this->global['idUser'];
		$totalCart = $this->modelExpired->totalCartPeruser($idUser);

		if($totalCart){
			echo number_format($totalCart,'0',',','.');
		} else {
			echo 0;
		}
	}

	function updateHargaCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$harga = $_POST['harga'];

		$dataUpdate = array(
								"harga"		=> $harga
					       );

		$this->modelExpired->updateHargaCart($idProduk,$idUser,$dataUpdate);
	
		//get total peritem
		$totalPeritem = $this->modelExpired->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}

	function hapusCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$this->modelExpired->hapusCart($idProduk,$idUser);
	}

}