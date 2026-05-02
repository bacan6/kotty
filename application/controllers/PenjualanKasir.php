<?php
defined('BASEPATH') OR exit('No direct script access allowed');	

require APPPATH . '/libraries/BaseController.php';

class PenjualanKasir extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","model_penjualan"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,7);
	}

	function index(){
		$data['pageTitle'] = "SOLUSI POS - Penjualan";
		$data['permitAccess'] = $this->global['permitAccess'];
		$data['permitAccessSub'] = $this->global['permitAccessSub'];
		$data['navigation'] = $this->model1->callNavigation();
		$this->load->view("navigation",$data);
		$data['produk'] = $this->model1->get_produk_select2();
		//$data['provinsi'] = $this->db->get("ae_provinsi");
		$data['payment_type'] = $this->db->get("ap_payment_type");
		$idUser = $this->global['idUser'];

		if(empty($_GET['idPending'])){
			// cek closing belum

			$cekClosing = $this->model1->cekClosing($this->global['idUser']);

			if($cekClosing < 1){
				$data['diskonPromosi'] = $this->model_penjualan->viewDiskon($idUser);
				$data['group_customer'] = $this->db->get("ap_customer_group");
				$data['idStore'] = $this->global['idStore'];
				$this->load->view("penjualan_kasir/body_penjualan",$data);
			} else {
				$this->load->view("penjualan_kasir/accessClosed");
			}

			$this->load->view("penjualan_kasir/footer_penjualan");
		} else {
			$idPending = $this->input->get("idPending");
			$data['diskonPromosi'] = $this->model_penjualan->viewDiskonPending($idPending);
			$data['idPending'] = $_GET['idPending'];
			$this->load->view("penjualan_kasir/bodyPendingExec",$data);
			$this->load->view("penjualan_kasir/footerPendingExec",$data);
		}
	}


	function getProdukPrice(){
		$sku 	= $_POST['sku'];
		$idStore = $this->global['idStore'];

		$this->load->model('model_penjualan');

		$harga_produk = $this->model_penjualan->getProdukPrice($sku,$idStore);

		echo $harga_produk;
	}


	function get_diskon_customer(){
		$id 	= $_POST['id'];
		$diskon_customer  = $this->model1->get_diskon_customer($id);

		echo  $diskon_customer;
	}


	function returPenjualanSQL(){
		$no_invoice 	= $_POST['noInvoice'];
		$tanggal 		= date('Y-m-d');

		$count_retur 	= $this->model1->count_retur($tanggal)+1;

		$back_id 		= sprintf("%03d",$count_retur);

		$id_user 		= sprintf("%02d",$this->global['idUser']);
		$no_retur 		= "RN-".date('ymd').'-'.$id_user.'-'.$back_id;

		$data_retur 	= array(
									"no_retur"		=> $no_retur,
									"no_invoice" 	=> $no_invoice,
									"pic"			=> $this->global['idUser'],
									"tanggal"		=> date('Y-m-d H:i'),
									"keterangan"	=> ""
							   );

		$this->model_penjualan->insertReturPenjualanSQL($data_retur);
		
		$idStore = $this->global['idStore'];

		$dataProduk = $_POST['dataProduk'];

		$decodeJSON = json_decode(stripcslashes($dataProduk));

		foreach($decodeJSON as $dt){
			$sku 	= $dt->idProduk;
			$qty 	= $dt->qty;
			$harga 	= $dt->hargaJual;
			$diskon = $dt->diskon;

			if($qty > 0){

				$data_item[] = array(
										"no_retur" 		=> $no_retur,
										"id_produk"		=> $sku,
										"qty"			=> $qty,
										"harga"			=> $harga,
										"tanggal"		=> date('Y-m-d'),
										"diskon" 		=> $diskon
									);

			}

			//kembalikan stok
			$stokLamaToko = $this->model_penjualan->cekStokPerStore($sku,$idStore);

			$dataUpdate = array(
									"stok" 			=> $stokLamaToko+$qty
			 				   );

			$this->model_penjualan->updateStokPerstore($idStore,$sku,$dataUpdate);
		}

		$this->db->insert_batch("ap_retur_item",$data_item);

	}

	function invoiceRetur(){
		$noInvoice = $_POST['noInvoice'];
		$data['invoiceRetur'] = $this->model_penjualan->invoiceRetur($noInvoice);
		$this->load->view("penjualan_kasir/invoiceRetur",$data);
	}

	function printInvoiceRetur(){
		$noRetur = $this->input->get('noRetur');
		$no_invoice = $this->db->get_where("ap_retur",array('no_retur' => $noRetur))->row()->no_invoice;
		$id_store = $this->model1->id_store_invoice($no_invoice);
		$data['receipt'] = $this->db->get_where("ap_store",array("id_store" => $id_store));
		$data['invoiceItem'] = $this->model_penjualan->returItemSale($noRetur);
		$this->load->view("penjualan_kasir/invoiceReturCetak",$data);
	}	

	function data_penjualan(){
		$this->global['pageTitle'] = "SOLUSI POS - Data Penjualan";
		$this->loadViews("penjualan_kasir/body_data_penjualan",$this->global,NULL,"penjualan_kasir/footerDataPenjualan");
	}

	function suratJalan(){
		$noInvoice = $this->input->get('no_invoice');
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['header'] = $this->db->get_where("ap_store",array("id_store" => $idStore))->row();
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);
		$this->global['pageTitle'] = "SOLUSI POS - Surat Jalan";
		$this->loadViews("penjualan_kasir/suratJalan",$this->global,$data,"footer_empty");
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



		$this->global['pageTitle'] = "SOLUSI POS - Shipping Label";
		$this->loadViews("penjualan_kasir/shippingLabel",$this->global,$data,"footer_empty");
	}

	function invoiceA4(){
		$noInvoice = $this->input->get('no_invoice');
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['header'] = $this->db->get_where("ap_store",array("id_store" => $idStore))->row();
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);
		$data['qty_barang'] = $this->model1->qty_barang_struk($noInvoice);
		$data['item_barang'] = $this->model1->item_barang_struk($noInvoice);

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Penjualan";
		$this->loadViews("penjualan_kasir/invoiceA4",$this->global,$data,"footer_empty");
	}

	function datatableDaftarPenjualan(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->model1->total_penjualan_all();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->model1->daftarPenjualan($length,$start,$search);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->model1->daftarPenjualan($length,$start,$search);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,"<a href='".base_url('penjualan_kasir/invoice_penjualan?no_invoice='.$dt['no_invoice'])."'>".$dt['no_invoice']."</a>",$dt['payment_type']." ".$dt['account'],date_format(date_create($dt['tanggal']),'d/m/y H:i'),number_format($dt['total'],'0',',','.'),number_format($dt['ongkir'],'0',',','.'),number_format($dt['diskon'],'0',',','.'),number_format($dt['diskon_free'],'0',',','.'),number_format($dt['poin_value'],'0',',','.'),number_format($dt['diskon_otomatis'],'0',',','.'),number_format(($dt['total']+$dt['ongkir'])-($dt['diskon']+$dt['diskon_free']+$dt['poin_value']+$dt['diskon_otomatis']),'0',',','.'));
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_penjualan(){
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


		$this->global['pageTitle'] = "SOLUSI POS - Invoice Penjualan";
		$this->loadViews("penjualan_kasir/body_invoice_penjualan",$this->global,$data,"penjualan_kasir/footerInvoicePenjualan");
	}

	function tempo_form(){
		$this->load->view("penjualan_kasir/jatuh_tempo");
	}

	function use_profil_address(){
		$data['provinsi'] = $this->db->get("ae_provinsi");

		$id_customer 	= $_POST['id'];
		$data['customer'] = $this->db->get_where("ap_customer",array("id_customer" => $id_customer));

		$this->load->view("penjualan_kasir/body_use_profil_address",$data);
	}

	function data_customer_poin(){
		$idUser = $this->global['idUser'];
		$cekIfPoinExist = $this->model_penjualan->cekIfPoinExist($idUser);

		$data['idUser'] = $this->global['idUser'];

		if($cekIfPoinExist > 0){
			$idMember = $this->model_penjualan->getIdMemberDiskon($idUser);
			$id = $idMember;
			$data['customer_poin'] = $this->model1->data_customer_poin($id);
			$data['poinValue'] 	  = $this->model_penjualan->poinValue($idUser);
			$this->load->view("penjualan_kasir/reimbursment_point",$data);
		} else {
			if(!empty($_POST['id'])){
				$id = $_POST['id'];
				$data['customer_poin'] = $this->model1->data_customer_poin($id);
				$data['poinValue'] 	  = 0;
				$this->load->view("penjualan_kasir/reimbursment_point",$data);
			} 
		}
	}

	function data_customer_poinPending(){
		$noCart = $_POST['noCart'];
		$cekIfPoinExist = $this->model_penjualan->cekIfPoinExistPending($noCart);
		$data['idUser'] = $this->global['idUser'];

		if($cekIfPoinExist > 0){
			$idMember = $this->model_penjualan->getIdMemberDiskonPending($noCart);
			$id = $idMember;
			$data['customer_poin'] = $this->model1->data_customer_poin($id);
			$data['poinValue'] 	  = $this->model_penjualan->poinValuePending($noCart);
			$this->load->view("penjualan_kasir/reimbursment_pointPending",$data);
		} else {
			if(!empty($_POST['id'])){
				$id = $_POST['id'];
				$data['customer_poin'] = $this->model1->data_customer_poin($id);
				$data['poinValue'] 	  = 0;
				$this->load->view("penjualan_kasir/reimbursment_pointPending",$data);
			} 
		}
	}

	function get_max_poin(){
		$id_customer = $_POST['id'];

		$max_poin = $this->model1->poin_lama($id_customer);

		echo $max_poin;
	}

	function sub_account(){
		$id 	= $_POST['id'];

		$query = $this->db->get_where("ap_payment_account",array("id_payment_type" => $id));

		$data['sub_account'] = $query;

		$rows = $query->num_rows();

		if($rows > 0 ){
			$this->load->view("sub_account",$data);
		} 
	}

	function cekStokPerStore(){
		$sku 	= $_POST['sku'];
		$idStore = $this->global['idStore'];
		$qty = $_POST['qty'];
		$id = $_POST['id'];
		
		$cekStok = $this->model_penjualan->cekStokPerStore($sku,$idStore);

		if($qty <= $cekStok){
			echo "StokEnough";
		} else {
			$currentCart = $this->model_penjualan->currentQtyPeritem($id);
		 	echo $currentCart;
		}
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

	function ajax_produk(){
		$q 	= $_GET['term'];

		$id_store = $this->global['idStore'];
		
		
		$customer = $this->model_penjualan->produk_search($q,$id_store);

		$data_array = array();

		foreach($customer->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk,
								 );
		}

		echo json_encode($data_array);
	}

	function cek_diskon(){
		$sku 	= $_POST['sku'];
		$cek_diskon = $this->model1->cek_diskon($sku);

		echo $cek_diskon;
	}

	function ambil_nilai_diskon(){
		$sku 	= $_POST['sku'];
		$qty	= $_POST['qty'];

		$nilai_diskon = $this->db->get_where("ap_produk_discount_rules",array("id_produk"	=> $sku));

		$countRulesIfExist = $nilai_diskon->num_rows();

		if($countRulesIfExist > 0){

			foreach($nilai_diskon->result() as $row){
				if($qty >= $row->qty){
					$diskon = $row->discount;
				} 
			}

			echo $diskon; 

		} else {
			echo 0;
		}
	}

	function pendingTrx(){
		$idUser 	  = $this->global['idUser'];
		$cekNoPending = $this->model_penjualan->cekNoPending($idUser);

		//generate pending number
		$noPendingTrx = "TN/".date('ymd')."/".sprintf("%02d",$idUser)."/".sprintf("%02d",$cekNoPending+1);

		//insert into pending number table
		$dataPending = array(
								"cartNo"		=> $noPendingTrx,
								"idUser"		=> $idUser,
								"tanggal"		=> date('Y-m-d H:i:s')
							);

		$this->model_penjualan->insertCartTemp($dataPending);

		//insert data to ap_cart_temp
		$viewCart = $this->model_penjualan->dataCart($idUser);

		foreach($viewCart as $row){
			$sku 			= $row->id_produk;
			$harga 			= $row->harga;
			$hpp 			= 0;
			$qty 			= $row->qty;
			$diskon_item 	= $row->diskon;

			$data_item[] = array(
									"id_produk"	 	=> $sku,
									"quantity"		=> $qty,
									"noCart" 		=> $noPendingTrx,
									"harga"			=> $harga,
									"diskon"		=> $diskon_item

								  );
		}

		$this->model_penjualan->inserCartTempItem($data_item,$idUser);
	}

	function pendingList(){
		$idUser = $this->global['idUser'];
		$data['pendingList'] = $this->db->get_where("ap_cart_temp_no",array("idUser" => $idUser,"status" => 0))->result();
		$this->global['pageTitle'] = "SOLUSI POS - Daftar Penjualan Tunda";
		$this->loadViews("penjualan_kasir/daftar_tunggu",$this->global,$data,"footer_empty");
	}

	function hapus_pending(){
		$id_pending = $_GET['id_pending'];

		$this->db->delete("ap_order_temp_no",array("id_pending" => $id_pending));
		$this->db->delete("ap_order_temp",array("id_pending" => $id_pending));

		$affect = $this->db->affected_rows();
			
		if($affect > 0){
				$message = "<div class='alert alert-success alert-dismissable'>";
                $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
                $message .= "Data Berhasil Dihapus";
                $message .= "</div>";

				$this->session->set_flashdata("message",$message);
		} else {
				$message = "<div class='alert alert-danger alert-dismissable'>";
                $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
                $message .= "Data Gagal Dihapus";
                $message .= "</div>";

				$this->session->set_flashdata("message",$message_error);
		}

		redirect("penjualankasir/daftar_tunggu");
	}

	function retur(){
		$this->global['pageTitle'] = "SOLUSI POS - Retur Penjualan";
		$this->loadViews("penjualan_kasir/body_retur",$this->global,NULL,"footer_empty");
	}

	function retur_search(){

		$no_invoice = $_GET['no_invoice'];

		$data['data_invoice'] = $this->model1->invoice_item($no_invoice);


			$data['no_invoice'] = $no_invoice;
			$data['retur_item'] = $this->model1->retur_item_invoice($no_invoice);



		$data['supplier'] = $this->db->get("supplier")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Retur";
		$this->loadViews("penjualan_kasir/body_retur_search",$this->global,$data,"penjualan_kasir/footerRetur");
	}

	function getDataProduk(){
		

		$sku 		= $_POST['sku'];
		$idStore 	= $this->global['idStore'];
		$dataProduk = $this->model_penjualan->getProdukData($sku,$idStore);

		foreach($dataProduk as $row){
			$arrayData[] = array(
									"harga"			=> $row->harga,
									"stok"			=> $row->stok
								);
		}

		echo json_encode($arrayData);
	}

	function cekDiskon($sku){
		//0 tidak ada diskon
		//1 ada diskon 

		$cekDiskon = $this->model_penjualan->cekDiskon($sku);

		return $cekDiskon;
	}

	function ambilNilaiDiskon($sku,$qty){
		$nilaiDiskon = $this->db->get_where("ap_produk_discount_rules",array("id_produk"=> $sku));
		$countRulesIfExist = $nilaiDiskon->num_rows();

		if($countRulesIfExist > 0){	
			foreach($nilaiDiskon->result() as $row){
				if($qty >= $row->qty){
					if(strtotime(date('Y-m-d')) >= strtotime($row->date_start) && strtotime(date('Y-m-d')) <= strtotime($row->date_end)){
						$diskon = $row->discount;
					} else {
						$diskon = 0;
					}
				} 
			}
			return $diskon; 

		} else {
			return 0;
		}
	}

	function insertCart(){
		$sku 	 = $_POST['sku'];
		$harga 	 = $_POST['harga'];
		$qty 	 = $_POST['qty'];
		$idUser = $this->global['idUser'];
		$idStore = $this->global['idStore'];

		$cekDiskon = $this->cekDiskon($sku);

		if($cekDiskon == 1){
			$nilaiDiskon = $this->ambilNilaiDiskon($sku,$qty);
		} else {
			$nilaiDiskon = 0;
		}

		//cek if data exist on cart
		$cekDataCart = $this->model_penjualan->cekCartIfExist($sku,$idUser);
		//MAX STOK ON CART
		$maxQTY = $this->model_penjualan->cekStokPerStore($sku,$idStore);


		$qtyCart 		= $this->model_penjualan->cekQtyCart($sku,$idUser);
		$diskonBefore 	= $this->model_penjualan->cekDiskonBefore($sku,$idUser);

		if($cekDataCart > 0 ){

			$qtyAdd = $qtyCart+1; 

			if($qtyAdd > $maxQTY){
				echo 0;
			} else {
				//delete first
				$this->model_penjualan->hapusCart($sku,$idUser);
				
				$dataCart = array(
							"id_produk"	=> $sku,
							"quantity" 	=> $qtyCart + 1,
							"id_user"	=> $idUser,
							"harga"		=> $harga,
							"diskon"	=> $diskonBefore+$nilaiDiskon
						 );

				$this->model_penjualan->insertCart($dataCart);
				echo 1;
			}

		} else {
			$dataCart = array(
							"id_produk"	=> $sku,
							"quantity" 	=> $qty,
							"id_user"	=> $idUser,
							"harga"		=> $harga,
							"diskon"	=> $nilaiDiskon
						 );

			$this->model_penjualan->insertCart($dataCart);
			echo 2;
		}
	}

	function insertCartPending(){
		$sku 	 = $_POST['sku'];
		$harga 	 = $_POST['harga'];
		$qty 	 = $_POST['qty'];
		$noCart  = $_POST['noCart'];

		$cekDiskon = $this->cekDiskon($sku);

		if($cekDiskon == 1){
			$nilaiDiskon = $this->ambilNilaiDiskon($sku,$qty);
		} else {
			$nilaiDiskon = 0;
		}

		//cek if data exist on cart
		

		$cekDataCart = $this->model_penjualan->cekCartIfExistPending($sku,$noCart);

		if($cekDataCart > 0 ){
			$qtyCart 		= $this->model_penjualan->cekQtyCartPending($sku,$noCart);
			$diskonBefore 	= $this->model_penjualan->cekDiskonBeforePending($sku,$noCart);

			$dataCartUpdate = array(
										"quantity"	=> $qtyCart + 1,
										"diskon"	=> $diskonBefore+$nilaiDiskon
								   );

			$this->model_penjualan->updateCartPendingTemp($noCart,$sku,$dataCartUpdate);
		} else {
			$dataCart = array(
							"id_produk"	=> $sku,
							"quantity" 	=> $qty,
							"noCart"	=> $noCart,
							"harga"		=> $harga,
							"diskon"	=> $nilaiDiskon
						 );

			$this->model_penjualan->insertCartPendingTemp($dataCart);
		}
	}

	function viewCart(){
		$idUser = $this->global['idUser'];	
		$data['dataCart'] = $this->model_penjualan->dataCart($idUser);
		$this->load->view("penjualan_kasir/viewCart",$data);
	}

	function currentQTYPeritem(){
		$id = $_POST['id'];
		$qty = $this->model_penjualan->currentQtyPeritem($id);
		echo $qty;
	}

	function viewCartPending(){
		$idpending = $_POST['noCart'];	
		$data['dataCart'] = $this->model_penjualan->dataCartPending($idpending);
		$this->load->view("penjualan_kasir/viewCartPending",$data);
	}

	function updateDiskon(){
		$sku 	= $_POST['idProduk'];
		$diskon = $_POST['diskon'];
		$idUser = $this->global['idUser'];

		$ifPercent = strripos($diskon,"%");
		$totalPurchase = $this->model_penjualan->totalByRow($idUser,$sku);

		//jika terdapat tanda persen maka konversi ke nilai persen
		if($ifPercent > 0){
			$intDiskon = str_replace('%','',$diskon);
			$diskon    = ($intDiskon/100)*$totalPurchase;

			$dataUpdate = array(
									"diskon"	=> $diskon
							   );

			$this->model_penjualan->updateDiskon($sku,$idUser,$dataUpdate);
		} else {
			$dataUpdate = array(
									"diskon"	=> $diskon
							   );

			$this->model_penjualan->updateDiskon($sku,$idUser,$dataUpdate);
		}

		//harga on cart
		$id = $_POST['id'];
		$dataProduk = $this->model_penjualan->hargaOnCart($id);
		
		foreach($dataProduk as $row){
			$arrayData[] = array(
									"harga"		=> $row->harga,
									"diskon"	=> $row->diskon,
									"qty"		=> $row->qty
								);
		}

		echo json_encode($arrayData);
	}

	function updateQtyCart(){
		$sku 	  = $_POST['idProduk'];
		$qty 	  = $_POST['qty'];
		$idUser   = $this->global['idUser'];

		//cek diskon
		$cekDiskon = $this->cekDiskon($sku);

		if($cekDiskon == 1){
			$nilaiDiskon = $this->ambilNilaiDiskon($sku,$qty);
		} else {
			$nilaiDiskon = 0;
		}	

		$dataUpdate = array(
								"quantity"	=> $qty,
								"diskon"	=> $nilaiDiskon*$qty
						   );

		$this->model_penjualan->updateQtyCart($sku,$idUser,$dataUpdate);
		
		//harga on cart
		$id = $_POST['id'];
		$dataProduk = $this->model_penjualan->hargaOnCart($id);
		
		foreach($dataProduk as $row){
			$arrayData[] = array(
									"harga"		=> $row->harga,
									"diskon"	=> $row->diskon
								);
		}

		echo json_encode($arrayData);
	}

	function updateQtyCartPending(){
		$sku 	  = $_POST['idProduk'];
		$qty 	  = $_POST['qty'];
		$noCart   = $_POST['noCart'];

		//cek diskon
		$cekDiskon = $this->cekDiskon($sku);

		if($cekDiskon == 1){
			$nilaiDiskon = $this->ambilNilaiDiskon($sku,$qty);
		} else {
			$nilaiDiskon = 0;
		}	

		$dataUpdate = array(
								"quantity"	=> $qty,
								"diskon"	=> $nilaiDiskon*$qty
						   );

		$this->model_penjualan->updateQtyCartPending($sku,$noCart,$dataUpdate);

		//harga on cart
		$id = $_POST['id'];
		$dataProduk = $this->model_penjualan->hargaOnCartTemp($id);
		
		foreach($dataProduk as $row){
			$arrayData[] = array(
									"harga"		=> $row->harga,
									"diskon"	=> $row->diskon
								);
		}

		echo json_encode($arrayData);

	}

	function insertDiskonPending(){
		$diskon 	= $_POST['diskon'];
		$noCart 	= $_POST['noCart'];

		//cek diskon
		$cekIfDiskonExist = $this->model_penjualan->cekIfDiskonExistPending($noCart);

		if($cekIfDiskonExist > 0){
			$dataUpdate = array(
									"diskon"		=> $diskon
							   );

			$this->model_penjualan->updateDiskonPending($noCart,$dataUpdate);
		} else {
			$dataInsert = array(
									"noCart"		=> $noCart,
									"diskon"		=> $diskon
							   );

			$this->model_penjualan->insertDiskonPending($dataInsert);
		}
	}

	function insertDiskon(){
		$diskon 	= $_POST['diskon'];
		$idUser     = $this->global['idUser'];

		//cek diskon
		$cekIfDiskonExist = $this->model_penjualan->cekIfDiskonExist($idUser);
		$totalPurchase = $this->model_penjualan->totalPurchase($idUser);
		$diskonPeritem = $this->model_penjualan->diskonPeritemPanel($idUser);

		$ifPercent = strripos($diskon,"%");

		if($cekIfDiskonExist > 0){
			$dataUpdate = array(
									"diskon"		=> $diskon
							   );

			$this->model_penjualan->updateCartDiskon($idUser,$dataUpdate);
		} else {
			$dataInsert = array(
									"idUser"		=> $idUser,
									"diskon"		=> $diskon
								);

			$this->model_penjualan->insertCartDiskon($dataInsert);
		}
	}

	

	function updateDiskonPending(){
		$sku 	= $_POST['idProduk'];
		$diskon = $_POST['diskon'];
		$noCart = $_POST['noCart'];

		$ifPercent = strripos($diskon,"%");
		$totalPurchase = $this->model_penjualan->totalByRowTemp($noCart,$sku);

		if($ifPercent > 0){
			$intDiskon = str_replace('%','',$diskon);
			$diskon    = ($intDiskon/100)*$totalPurchase;

			$dataUpdate = array(
									"diskon"	=> $diskon
							   );

			$this->model_penjualan->updateCartDiskonPending($sku,$noCart,$dataUpdate);
		} else {
			$dataUpdate = array(
									"diskon"	=> $diskon
							   );

			$this->model_penjualan->updateCartDiskonPending($sku,$noCart,$dataUpdate);
		}

		//harga on cart
		$id = $_POST['id'];
		$dataProduk = $this->model_penjualan->hargaOnCartTemp($id);
		
		foreach($dataProduk as $row){
			$arrayData[] = array(
									"harga"		=> $row->harga,
									"diskon"	=> $row->diskon,
									"qty"		=> $row->qty
								);
		}

		echo json_encode($arrayData);
	}

	function totalPurchase(){
		$idUser = $this->global['idUser'];
		$totalPurchase = $this->model_penjualan->totalPurchase($idUser);

		echo number_format($totalPurchase,'0',',','.');
	}

	function totalPurchasePending(){
		$idPending = $_POST['noCart'];
		$totalPurchase = $this->model_penjualan->totalPurchasePending($idPending);

		echo number_format($totalPurchase,'0',',','.');
	}

	function diskonPeritemPanel(){
		$idUser = $this->global['idUser'];
		$diskonPeritemPanel = $this->model_penjualan->diskonPeritemPanel($idUser);

		if($diskonPeritemPanel > 0){
			$msg 	= "<td><i class='fa fa-bullhorn'></i> Diskon Peritem</td>";
			$msg   .= "<td align='right'>".number_format($diskonPeritemPanel,'0',',','.')."</td>";
			echo $msg;
		}
	}

	function diskonPeritemPanelPending(){
		$idPending = $_POST['noCart'];
		$diskonPeritemPanel = $this->model_penjualan->diskonPeritemPanelPending($idPending);

		if($diskonPeritemPanel > 0){
			$msg 	= "<td><i class='fa fa-bullhorn'></i> Diskon Peritem</td>";
			$msg   .= "<td align='right'>".number_format($diskonPeritemPanel,'0',',','.')."</td>";
			echo $msg;
		}
	}

	function hapusCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];

		$this->model_penjualan->hapusCart($idProduk,$idUser);
	}

	function hapusCartPending(){
		$idProduk = $_POST['idProduk'];
		$noCart   = $_POST['noCart'];

		$this->model_penjualan->hapusCartPending($idProduk,$noCart);
	}

	function saveDiskonMember(){
		$totalDiskon = $_POST['totalDiskon'];
		$idUser   = $this->global['idUser'];
		$idCustomer = $_POST['idCustomer'];

		$dataDiskon = array(
								"idUser" 	=> $idUser,
								"idMember"	=> $idCustomer,
								"diskon"	=> $totalDiskon
						   );

		$this->model_penjualan->saveDiskonMember($dataDiskon);
	}

	function saveDiskonMemberPending(){
		$totalDiskon = $_POST['totalDiskon'];
		$noCart   = $_POST['noCart'];
		$idCustomer = $_POST['idCustomer'];

		$dataDiskon = array(
								"noCart" 	=> $noCart,
								"idMember"	=> $idCustomer,
								"diskon"	=> $totalDiskon,
								"poinReimburs" => 0,
								"poinValue" => 0
						   );

		$this->model_penjualan->saveDiskonMemberPending($dataDiskon);
	}

	function diskonMemberDisplay(){
		$idUser   		 = $this->global['idUser'];
		$getDiskonMember = $this->model_penjualan->getDiskonMember($idUser);

		if($getDiskonMember > 0){
			$msg 	= "<td><i class='fa fa-money'></i> Diskon Member</td>";
			$msg   .= "<td align='right'>".number_format($getDiskonMember,'0',',','.')."</td>";
			
			echo $msg;
		}
	}

	function diskonMemberDisplayPending(){
		$noCart 		 = $_POST['noCart'];
		$getDiskonMember = $this->model_penjualan->getDiskonMemberPending($noCart);

		if($getDiskonMember > 0){
			$msg 	= "<td><i class='fa fa-money'></i> Diskon Member</td>";
			$msg   .= "<td align='right'>".number_format($getDiskonMember,'0',',','.')."</td>";
			
			echo $msg;
		}
	}

	function deleteDiscMember(){
		$idUser = $_POST['idUser'];

		$this->model_penjualan->deleteDiscMember($idUser);
	}

	function deleteDiscMemberPending(){
		$noCart = $_POST['noCart'];

		$this->model_penjualan->deleteDiscMemberPending($noCart);
	}

	function get_nilai_reimburs(){
		$poin = $_POST['poin'];

		$nilai_reimburs = $this->model1->nilai_reimburs();

		foreach($nilai_reimburs->result() as $row){
			$nilai_poin 			= $row->poin_pengeluaran;
			$nilai_pengeluaran		= $row->nilai_pengeluaran;
		}

		$nominal_poin = ($poin/$nilai_poin)*$nilai_pengeluaran;

		echo $nominal_poin;
	}

	function insertPoin(){
		$poinVal 		= $_POST['poinVal'];
		$nilaiReimburs 	= $_POST['nilaiReimburs'];
		$idUser 		= $this->global['idUser'];

		$dataUpdate 	= array(
									"poinReimburs" 		=> $nilaiReimburs,
									"poinValue"			=> $poinVal
							   );

		$this->model_penjualan->insertPoin($idUser,$dataUpdate);
	}

	function insertPoinPending(){
		$poinVal 		= $_POST['poinVal'];
		$nilaiReimburs 	= $_POST['nilaiReimburs'];
		$noCart 		= $_POST['noCart'];

		$dataUpdate 	= array(
									"poinReimburs" 		=> $nilaiReimburs,
									"poinValue"			=> $poinVal
							   );

		$this->model_penjualan->insertPoinPending($noCart,$dataUpdate);
	}

	function viewNilaiReimburs(){
		$idUser = $this->global['idUser'];
	
		$poinReimburs = $this->model_penjualan->poinReimburs($idUser);
		$poinValue 	  = $this->model_penjualan->poinValue($idUser);

		if($poinReimburs > 0){

			$msg 	= "<td><i class='fa fa-tree'></i> Poin Reimbursment</td>";
			$msg   .= "<td  align='right'>".number_format($poinReimburs,'0',',','.')."</td>";

			echo $msg;

		}
	}

	function viewNilaiReimbursPending(){
		$noCart  = $_POST['noCart'];
	
		$poinReimburs =  $this->model_penjualan->poinReimbursPending($noCart);
		$poinValue 	  = $this->model_penjualan->poinValuePending($noCart);

		if($poinReimburs > 0){

			$msg 	= "<td><i class='fa fa-tree'></i> Poin Reimbursment</td>";
			$msg   .= "<td  align='right'>".number_format($poinReimburs,'0',',','.')."</td>";

			echo $msg;

		}
	}

	function urlPoin(){
		$idUser = $this->global['idUser'];
	
		$poinReimburs = $this->model_penjualan->poinReimburs($idUser);
		$poinValue 	  = $this->model_penjualan->poinValue($idUser);

		echo $poinValue;	
	}

	function insertOngkir(){
		$ongkir 	= $_POST['ongkir'];
		$idUser 	= $this->global['idUser'];

		//cek ongkir exist
		$cekIfOngkirExist = $this->model_penjualan->cekIfOngkirExist($idUser);

		if($cekIfOngkirExist > 0){
			$dataUpdate = array(
									"ongkir"	=> $ongkir,
							   );

			$this->model_penjualan->updateOngkir($idUser,$dataUpdate);
		} else {
			$dataInsert = array(
									"idUser"	=> $idUser,
									"ongkir"	=> $ongkir
							   );

			$this->model_penjualan->insertOngkir($dataInsert);
		}
	}

	function insertOngkirPending(){
		$ongkir 	= $_POST['ongkir'];
		$noCart 	= $_POST['noCart'];

		//cek ongkir exist
		$cekIfOngkirExist = $this->model_penjualan->cekIfOngkirExistPending($noCart);

		if($cekIfOngkirExist > 0){
			$dataUpdate = array(
									"ongkir"	=> $ongkir,
							   );

			$this->model_penjualan->updateOngkirPending($noCart,$dataUpdate);
		} else {
			$dataInsert = array(
									"noCart"	=> $noCart,
									"ongkir"	=> $ongkir
							   );

			$this->model_penjualan->insertOngkirPending($dataInsert);
		}
	}

	function viewOngkir(){
		$idUser = $this->global['idUser'];

		$viewOngkir = $this->model_penjualan->viewOngkir($idUser);

		if($viewOngkir > 0){
			$msg = "<td><i class='fa fa-car'></i> Ongkir</td>";
			$msg.= "<td align='right'>".number_format($viewOngkir,'0',',','.')."</td>";

			echo $msg;
		}
	}

	function viewOngkirPending(){
		$noCart = $_POST['noCart'];

		$viewOngkir = $this->model_penjualan->viewOngkirPending($noCart);

		if($viewOngkir > 0){
			$msg = "<td><i class='fa fa-car'></i> Ongkir</td>";
			$msg.= "<td align='right'>".number_format($viewOngkir,'0',',','.')."</td>";

			echo $msg;
		}
	}

	

	function viewDiskon(){
		$idUser = $this->global['idUser'];

		$viewDiskon = $this->model_penjualan->viewDiskon($idUser);

		if($viewDiskon > 0){
			$msg = "<td><i class='fa fa-bullhorn'></i> Diskon Promosi</td>";
			$msg.= "<td align='right'>".number_format($viewDiskon,'0',',','.')."</td>";

			echo $msg;
		}
	}

	function viewDiskonPending(){
		$noCart = $_POST['noCart'];

		$viewDiskon = $this->model_penjualan->viewDiskonPending($noCart);

		if($viewDiskon > 0){
			$msg = "<td><i class='fa fa-bullhorn'></i> Diskon Promosi</td>";
			$msg.= "<td align='right'>".number_format($viewDiskon,'0',',','.')."</td>";

			echo $msg;
		}
	}

	function viewGrandTotal(){
		$idUser 		= $this->global['idUser'];

		$subtotal 		= $this->model_penjualan->totalPurchase($idUser);
		$diskonPeritem  = $this->model_penjualan->diskonPeritemPanel($idUser);
		$ongkir 		= $this->model_penjualan->viewOngkir($idUser);
		$diskonMember   = $this->model_penjualan->getDiskonMember($idUser);
		$diskonPromosi  = $this->model_penjualan->viewDiskon($idUser);
		$poinReimburs   = $this->model_penjualan->poinReimburs($idUser);

		$grandTotal     = ($subtotal+$ongkir)-($diskonPeritem+$diskonMember+$diskonPromosi+$poinReimburs);

		echo number_format($grandTotal,'0',',','.');
	}

	function totalKeseluruhan(){
		$idUser 		= $this->global['idUser'];

		$subtotal 		= $this->model_penjualan->totalPurchase($idUser);
		$diskonPeritem  = $this->model_penjualan->diskonPeritemPanel($idUser);
		$ongkir 		= $this->model_penjualan->viewOngkir($idUser);
		$diskonMember   = $this->model_penjualan->getDiskonMember($idUser);
		$diskonPromosi  = $this->model_penjualan->viewDiskon($idUser);
		$poinReimburs   = $this->model_penjualan->poinReimburs($idUser);

		$grandTotal     = ($subtotal+$ongkir)-($diskonPeritem+$diskonMember+$diskonPromosi+$poinReimburs);

		echo $grandTotal;
	}

	function viewGrandTotalPending(){
		$noCart  = $_POST['noCart'];

		$subtotal 		= $this->model_penjualan->totalPurchasePending($noCart);
		$diskonPeritem  = $this->model_penjualan->diskonPeritemPanelPending($noCart);
		$ongkir 		= $this->model_penjualan->viewOngkirPending($noCart);
		$diskonMember   = $this->model_penjualan->getDiskonMemberPending($noCart);
		$diskonPromosi  = $this->model_penjualan->viewDiskonPending($noCart);
		$poinReimburs   = $this->model_penjualan->poinReimbursPending($noCart);

		$grandTotal     = ($subtotal+$ongkir)-($diskonPeritem+$diskonMember+$diskonPromosi+$poinReimburs);

		echo number_format($grandTotal,'0',',','.');
	}

	function penjualan_sql(){
		$idUser = $this->global['idUser'];

		$poinReimburs = $this->model_penjualan->poinReimburs($idUser);
		$poinValue 	  = $this->model_penjualan->poinValue($idUser);

		$id_customer	 = $this->model_penjualan->getIdMemberDiskon($idUser);
		$ongkir 	 	 = $this->model_penjualan->viewOngkir($idUser) > 0 ? $this->model_penjualan->viewOngkir($idUser) : 0;
		$type_bayar 	 = $this->input->post("type_bayar");
		$keterangan 	 = $this->input->post("keterangan");

		$alamat 		 = $this->input->post("alamatPenerima");
		$provinsi 		 = $this->input->post("provinsi");
		$kabupaten 		 = $this->input->post("kabupaten");
		$kecamatan 		 = $this->input->post("kecamatan");
		$ekspedisi 		 = $this->input->post("ekspedisi");
		$namaPenerima 	 = $this->input->post("namaPenerima");
		$noHPPenerima 	 = $this->input->post("noHPPenerima");


		$total 			 = $this->model_penjualan->totalPurchase($idUser);
		$diskon 		 = $this->model_penjualan->getDiskonMember($idUser); //diskon member
		$diskon_promosi  = $this->model_penjualan->viewDiskon($idUser) > 0 ? $this->model_penjualan->viewDiskon($idUser) : 0;
		/**$no_hp 			 = $this->input->post("no_hp");**/
		$value_poin 	 = $this->model_penjualan->poinReimburs($idUser) > 0 ? $this->model_penjualan->poinReimburs($idUser) : 0;
		$point_reimburs  = $this->model_penjualan->poinValue($idUser) > 0 ? $this->model_penjualan->poinValue($idUser) : 0 ;
		$jumlah_bayar 	 = $this->input->post("jumlah_bayar");
		$diskon_otomatis = $this->model_penjualan->diskonPeritemPanel($idUser);

		$tanggal 		= date('Y-m-d');
		$id_user 		= $this->global['idUser'];

		$count_invoice  = $this->model1->count_invoice($tanggal)+1; 

		$no_inv 		= "INV".date('y').date('m').date('d').$id_user.sprintf('%04d',$count_invoice);

		//DAPATKAN NILAI POIN
		$poin_pembelian 	= $this->model1->poin_pembelian();
		$nilai_pembelian	= $this->model1->nilai_pembelian();
		$total_transaksi = $total-($diskon+$diskon_promosi+$value_poin);

		$poin = ($total_transaksi/$nilai_pembelian)*$poin_pembelian;
		
		//UPDATE POIN CUSTOMER ALGORITHM

		//dapatkan poin lama
		$poin_lama = $this->model1->poin_lama($id_customer);

		//update poin customer setelah transaksi 
		$data_poin = array(
								"point" => $poin_lama+$poin
						  ); 

		$this->model_penjualan->updatePoinReimburs($id_customer,$data_poin);

		if(empty($this->input->post("sub_account"))){
			$sub_account = "";
		} else {
			$sub_account = $this->input->post("sub_account");
		}

		//kurangi poin jika nilai reimbursment lebih dari 0
		if($point_reimburs > 0){
			$old_poin = $this->model1->poin_lama($id_customer);

			$kurang_poin = array(
									"point" => $old_poin-$point_reimburs
								);

			$this->model_penjualan->updatePoinReimburs($id_customer,$kurang_poin);
		}

		

		$data_penjualan = array(
									"no_invoice"		=> $no_inv,
									"tipe_bayar"		=> $type_bayar,
									"sub_account"		=> $sub_account,
									"jatuh_tempo" 		=> $this->input->post("jatuh_tempo"),
									"total"				=> $total,
									"ongkir"			=> $ongkir,
									"diskon"			=> $diskon,
									"diskon_free"		=> $diskon_promosi,
									"poin_value"		=> $value_poin,
									"poin"				=> $poin,
									"poin_reimburs" 	=> $point_reimburs,
									"diskon_otomatis"	=> $diskon_otomatis,
									"jumlah_bayar"		=> $jumlah_bayar,
									"id_pic"			=> $id_user,
									"id_customer"		=> $id_customer,
									"keterangan"		=> $keterangan, 
									"tanggal"			=> date('Y-m-d H:i:s'),
									"alamat"			=> $alamat,
									"id_provinsi"		=> $provinsi,
									"id_kabupaten"		=> $kabupaten,
									"id_kecamatan"		=> $kecamatan,
									"kontak_pengiriman" => $noHPPenerima,
									"nama_penerima"		=> $namaPenerima,
									"id_ekspedisi"		=> $ekspedisi,
									"id_toko"			=> $this->global['idStore']
							   );

		$this->model_penjualan->insertApInvoiceNumber($data_penjualan);

		$affect = $this->db->affected_rows();

		//cek tipe bayar, jika 1 = piutang dan sisipkan ke tabel piutag
		if($type_bayar == 5){
			$data_piutang = array(
										"no_invoice" 	=> $no_inv,
										"status" 		=> 0, //0 = TERBAYAR, 1 = LUNAS,
										"jatuh_tempo"	=> $this->input->post("jatuh_tempo")
									 );

			$this->db->insert("ap_piutang",$data_piutang);

			//masukan dp 
			$cek_piutang_payment = $this->model1->cek_piutang_payment()+1;

			$no_seri = "TRX-".date('y').date('m').sprintf('%04d',$cek_piutang_payment);

			$data_piutang = array(
									"no_seri"			=> $no_seri,
									"no_invoice" 		=> $no_inv,
									"id_pic"			=> $this->global['idUser'],
									"id_payment"		=> $type_bayar,
									"account"			=> $sub_account,
									"tanggal"			=> date('Y-m-d'),
									"nominal"			=> $jumlah_bayar,
									"keterangan"		=> $keterangan
								 );

			$this->model_penjualan->insertPiutangInvoice($data_piutang);
		}

		// view cart
		$viewCart = $this->model_penjualan->dataCart($idUser);

		foreach($viewCart as $row){
			$sku 			= $row->id_produk;
			$harga 			= $row->harga;
			$hpp 			= 0;
			$qty 			= $row->qty;
			$diskon_item 	= $row->diskon;

			$data_item[] = array(
									"no_invoice" 	=> $no_inv,
									"id_produk"	 	=> $sku,
									"hpp"		 	=> $hpp,
									"harga_jual" 	=> $harga,
									"diskon"		=> $diskon_item,
									"qty"			=> $qty,
									"tanggal"		=> date('Y-m-d')
								  );

			$id_store = $this->global['idStore'];
			//kurangi stok di gudang utama
			$stok_lama = $this->model1->get_stok_lama_produk_store($sku,$id_store);
			$new_stok = $stok_lama-$qty;

			//UPDATE STOK BARU
			$data_update = array(
									"stok" 			=> $new_stok
								);

			$this->model_penjualan->updateStokStore($sku,$id_store,$data_update);
		}

		$this->model_penjualan->insertBatch($data_item,$idUser);
		$this->model_penjualan->hapusTrx($idUser);
		echo $no_inv; 
	}

	function cancelTrx(){
		$idUser = $this->global['idUser'];

		$this->model_penjualan->hapusTrx($idUser);
		redirect("penjualankasir");
	}

	function penjualanSqlPending(){
		$noCart = $_POST['noCart'];

		$poinReimburs = $this->model_penjualan->poinReimbursPending($noCart);
		$poinValue 	  = $this->model_penjualan->poinValuePending($noCart);

		$id_customer	 = $this->model_penjualan->getIdMemberDiskonPending($noCart);
		$ongkir 	 	 = $this->model_penjualan->viewOngkirPending($noCart) > 0 ? $this->model_penjualan->viewOngkirPending($noCart) : 0;
		
		$type_bayar 	 = $this->input->post("type_bayar");
		$keterangan 	 = $this->input->post("keterangan");

		$alamat 		 = $this->input->post("alamatPenerima");
		$provinsi 		 = $this->input->post("provinsi");
		$kabupaten 		 = $this->input->post("kabupaten");
		$kecamatan 		 = $this->input->post("kecamatan");
		$ekspedisi 		 = $this->input->post("ekspedisi");
		$namaPenerima 	 = $this->input->post("namaPenerima");
		$noHPPenerima 	 = $this->input->post("noHPPenerima");

		$total 			 = $this->model_penjualan->totalPurchasePending($noCart);
		$diskon 		 = $this->model_penjualan->getDiskonMemberPending($noCart); //diskon member
		$diskon_promosi  = $this->model_penjualan->viewDiskonPending($noCart) > 0 ? $this->model_penjualan->viewDiskonPending($noCart) : 0;
		$value_poin 	 = $this->model_penjualan->poinReimbursPending($noCart) > 0 ? $this->model_penjualan->poinReimbursPending($noCart) : 0;
		$point_reimburs  = $this->model_penjualan->poinValuePending($noCart) > 0 ? $this->model_penjualan->poinValuePending($noCart) : 0 ;
		$jumlah_bayar 	 = $this->input->post("jumlah_bayar");
		$diskon_otomatis = $this->model_penjualan->diskonPeritemPanelPending($noCart);

		$tanggal 		= date('Y-m-d');
		$id_user 		= $this->global['idUser'];

		$count_invoice  = $this->model1->count_invoice($tanggal)+1; 

		$no_inv 		= "INV".date('y').date('m').date('d').$id_user.sprintf('%04d',$count_invoice);

		//DAPATKAN NILAI POIN
		$poin_pembelian 	= $this->model1->poin_pembelian();
		$nilai_pembelian	= $this->model1->nilai_pembelian();
		$total_transaksi = $total-($diskon+$diskon_promosi+$value_poin);

		$poin = ($total_transaksi/$nilai_pembelian)*$poin_pembelian;
		
		//UPDATE POIN CUSTOMER 

		//dapatkan poin lama
		$poin_lama = $this->model1->poin_lama($id_customer);

		//update poin customer setelah transaksi 
		$data_poin = array(
								"point" => $poin_lama+$poin
						  ); 

		$this->model_penjualan->updatePoinReimburs($id_customer,$data_poin);

		//kurangi poin jika nilai reimbursment lebih dari 0
		if($point_reimburs > 0){
			$old_poin = $this->model1->poin_lama($id_customer);

			$kurang_poin = array(
									"point" => $old_poin-$point_reimburs
								);

			$this->model_penjualan->updatePoinReimburs($id_customer,$kurang_poin);
		}

		if(empty($this->input->post("sub_account"))){
			$sub_account = "";
		} else {
			$sub_account = $this->input->post("sub_account");
		}

		$data_penjualan = array(
									"no_invoice"		=> $no_inv,
									"tipe_bayar"		=> $type_bayar,
									"sub_account"		=> $sub_account,
									"jatuh_tempo" 		=> $this->input->post("jatuh_tempo"),
									"total"				=> $total,
									"ongkir"			=> $ongkir,
									"diskon"			=> $diskon,
									"diskon_free"		=> $diskon_promosi,
									"poin_value"		=> $value_poin,
									"poin"				=> $poin,
									"poin_reimburs" 	=> $point_reimburs,
									"diskon_otomatis"	=> $diskon_otomatis,
									"jumlah_bayar"		=> $jumlah_bayar,
									"id_pic"			=> $id_user,
									"id_customer"		=> $id_customer,
									"keterangan"		=> $keterangan, 
									"tanggal"			=> date('Y-m-d H:i:s'),
									"alamat"			=> $alamat,
									"id_provinsi"		=> $provinsi,
									"id_kabupaten"		=> $kabupaten,
									"id_kecamatan"		=> $kecamatan,
									"kontak_pengiriman" => $noHPPenerima,
									"nama_penerima"		=> $namaPenerima,
									"id_ekspedisi"		=> $ekspedisi,
									"id_toko"			=> $this->global['idStore']
							   );

		$this->model_penjualan->insertApInvoiceNumber($data_penjualan);

		$affect = $this->db->affected_rows();

		//dapatkan tipe customer
		$tipe_customer = $this->model1->tipe_customer($id_customer);

	

			//cek tipe bayar, jika 1 = piutang dan sisipkan ke tabel piutag

			if($type_bayar == 5){
				$data_piutang = array(
										"no_invoice" 	=> $no_inv,
										"status" 		=> 0, //0 = TERBAYAR, 1 = LUNAS,
										"jatuh_tempo"	=> $this->input->post("jatuh_tempo")
									 );
				$this->model_penjualan->insertPiutangInvoice($data_piutang);
			}

			// view cart
			$viewCart = $this->model_penjualan->dataCartPending($noCart);

			foreach($viewCart as $row){
				$sku 			= $row->id_produk;
				$harga 			= $row->harga;
				$hpp 			= 0;
				$qty 			= $row->qty;
				$diskon_item 	= $row->diskon;

				$data_item[] = array(
									"no_invoice" 	=> $no_inv,
									"id_produk"	 	=> $sku,
									"hpp"		 	=> $hpp,
									"harga_jual" 	=> $harga,
									"diskon"		=> $diskon_item,
									"qty"			=> $qty,
									"tanggal"		=> date('Y-m-d')
								  );	

				$id_store = $this->global['idStore'];

				//kurangi stok di gudang utama
				$stok_lama = $this->model1->get_stok_lama_produk_store($sku,$id_store);
				$new_stok = $stok_lama-$qty;

				//UPDATE STOK BARU
				$data_update = array(
										"stok" 			=> $new_stok
									);

				$this->model_penjualan->updateStokStore($sku,$id_store,$data_update);
			}

		
		$this->model_penjualan->insertBatch($data_item);
		$this->model_penjualan->hapusTrxTemp($noCart);

		$dataCartTempNo = array(
									"status" => 1
							   );

		$this->model_penjualan->updateCartTempStatus($noCart,$dataCartTempNo);
 		echo $no_inv;
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function cekNoMemberIfDuplicate(){
		$noMember 		= $_POST['noMember'];
		$statement 		= $this->model_penjualan->cekNoMemberIfDuplicate($noMember);
		
		if($statement > 0){
			echo 1;
		} else {
			echo 0;
		}
	}

	function simpanMember(){
		$noMember 		 	= $_POST['noMember'];
		$namaCustomer 	 	= $_POST['namaCustomer'];
		$kontak 		 	= $_POST['kontak'];
		$email 			 	= $_POST['email'];
		$tanggalLahir 	 	= $_POST['tanggalLahir'];
		$kategoriCustomer 	= $_POST['kategoriCustomer'];
		$diskonMember 		= $_POST['diskonMember'];
		$alamat 			= $_POST['alamat'];
		$provinsi 			= $_POST['provinsi'];
		$kabupaten 			= $_POST['kabupaten'];
		$kecamatan 			= $_POST['kecamatan'];

		$dataCustomer = array(
								"id_customer"		=> $noMember,
								"nama"				=> $namaCustomer,
								"kontak"			=> $kontak,
								"tanggal_lahir"		=> $tanggalLahir,
								"diskon"			=> $diskonMember,
								"tanggal_gabung"	=> date('Y-m-d'),
								"point"				=> 0,
								"alamat"			=> $alamat,
								"id_provinsi"		=> $provinsi,
								"id_kabupaten"		=> $kabupaten,
								"id_kecamatan"		=> $kecamatan,
								"kategori"			=> $kategoriCustomer
							 );

		$simpan = $this->model_penjualan->simpanDataMember($dataCustomer);
		echo $simpan;
	}

	function viewAlamatCustomer(){
		$idCustomer = $_POST['idCustomer'];
		$data['provinsi'] = $this->db->get("ae_provinsi");
		$data['ekspedisi'] = $this->db->get("ap_ekspedisi")->result();
		$data['dataCustomer'] = $this->model_penjualan->customerRow($idCustomer);
		$idUser = $this->global['idUser'];
		$data['ongkir'] = $this->model_penjualan->viewOngkir($idUser);
		$this->load->view("penjualan_kasir/viewAlamatCustomer",$data);
	}
	
	function emptyAlamatCust(){
		$data['provinsi'] = $this->db->get("ae_provinsi");
		$data['ekspedisi'] = $this->db->get("ap_ekspedisi")->result();
		$idUser = $this->global['idUser'];
		$data['ongkir'] = $this->model_penjualan->viewOngkir($idUser);
		$this->load->view("penjualan_kasir/alamatEmpty",$data);
	}
}
