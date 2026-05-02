<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_transaksi extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->model('model1');
		$this->load->library(array("session","encryption"));

		//cek login
		$username = $this->session->userdata("username");
		$password = $this->session->userdata("password");
		$id_user  = $this->session->userdata("id_user");

		$cek_auth = $this->model1->cek_auth($username,$password);

		if($cek_auth > 0){
			//cek hak navigasi
			$access = 14;
			$cek_status = $this->model1->cek_status_navigasi($id_user,$access);
			
			if($cek_status=='0'){
				redirect("access_denied");
			} else {
				//do nothing
			}

		} else {
			redirect("login");
		}
	}

	function index(){
		$this->load->view("navigation");

		//dapatkan status transakasi
		$status = $this->uri->segment(3);

		if(empty($status)){
			$status = 0;
		} else {
			$status = $this->uri->segment(3);
		}

		$total_rows = $this->model1->total_penjualan($status);

		$this->load->library('pagination');
		$config['base_url'] 			= base_url('data_transaksi/index/'.$status);
		$config['total_rows']			= $total_rows;
		$config["per_page"]				= $per_page = 20;
		$config["uri_segment"]			= 4;
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
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		//DAPATKAN STATUS TAB, 
		//0 = Pending
		//1 = On Process
		//2 = Terkirim
		//3 = Transaksi Batal

		$tab = $this->uri->segment(3);

		if(empty($tab)){
			$tab = 0;
		} else {
			$tab = $tab;
		}

		if(empty($_GET['query'])){
			$data['daftar_penjualan'] = $this->model1->daftar_penjualan($per_page,$page,$tab);
		} else {
			
		}

		$pending 	= 0;
		$on_process = 1;
		$terkirim 	= 2;
		$failed 	= 3; 
		$retur 		= 4;
		$data['count_pending'] 		= $this->model1->count_tab($pending);
		$data['count_on_process'] 	= $this->model1->count_tab($on_process);
		$data['terkirim']			= $this->model1->count_tab($terkirim);
		$data['transaksi_batal'] 	= $this->model1->count_tab($failed);
		$data['count_retur'] 		= $this->model1->count_tab ($retur);

		$this->load->view("body_daftar_transaksi",$data);
		$this->load->view("footer");
	}

	function set_on_process(){
		$no_invoice = $this->input->get("id");

		//UBAH STATUS MENJADI ON PROCESS
		$data_update = array(
								"status" 	=> 1
							);

		$this->db->where("no_invoice",$no_invoice);
		$this->db->update("ap_invoice_number",$data_update);

		$affect = $this->db->affected_rows();

		$success = "<div class='alert alert-success alert-dismissable'>";
        $success.= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
        $success.= "Status Transaksi Berhasil Diubah";
        $success.= "</div>";

        $gagal = "<div class='alert alert-danger alert-dismissable'>";
        $gagal.= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
        $gagal.= "Status Transaksi Gagal Diubah";
        $gagal.= "</div>";

		if($affect > 0){
			$this->session->set_flashdata("message",$success);
		} else {
			$this->session->set_flashdata("message",$gagal);
		}

		redirect('data_transaksi/index/0');

	}

	function form_pembatalan(){
		$data['id'] = $_POST['id'];

		$this->load->view("form_pembatalan",$data);
	}

	function exec_pembatalan(){
		$no_invoice = $_POST['no_invoice'];
		$alasan 	= $_POST['alasan'];

		//kembalikan stok ke semula
		$data_barang = $this->model1->invoice_item($no_invoice);

		foreach($data_barang->result() as $row){
			$id_produk 			= $row->id_produk;
			$qty 				= $row->qty;

			//ambil stok lama
			$stok_lama = $this->model1->stok_produk_lama($id_produk);

			$kembalikan_stok[] = array(
											"stok" 			=> $stok_lama + $qty,
											"id_produk" 	=> $id_produk
									   );

		}
		//update batch untuk pengembalian stok
		$this->db->update_batch("ap_produk",$kembalikan_stok,"id_produk");

		$data_update = array(
								"status"			=> 3,
								"alasan_cancel" 	=> $alasan,
								"cancel_by"			=> $this->session->userdata("id_user")
							);

		$this->db->where("no_invoice",$no_invoice);
		$this->db->update("ap_invoice_number",$data_update);

		$affect = $this->db->affected_rows();

		if($affect > 0){
			$success = "<div class='alert alert-success alert-dismissable'>";
        	$success.= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
        	$success.= "Status Transaksi Berhasil Diubah";
        	$success.= "</div>";

			$this->session->set_flashdata("message",$success);
		} else {
			$gagal = "<div class='alert alert-danger alert-dismissable'>";
        	$gagal.= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
        	$gagal.= "Status Transaksi Gagal Diubah";
        	$gagal.= "</div>";

			$this->session->set_flashdata("message",$gagal);
		}
	}

	function siap_dikirim(){
		$no_invoice = $_POST['no_invoice'];
		$no_resi 	= $_POST['no_resi'];
		$ekspedisi 	= $_POST['ekspedisi'];

		$data_update = array(
								"dikirim_oleh" 	=> $this->session->userdata("id_user"),
								"status"		=> 2,
								"no_resi"		=> $no_resi,
								"id_ekspedisi"	=> $ekspedisi
							);

		$this->db->where("no_invoice",$no_invoice);
		$this->db->update("ap_invoice_number",$data_update);
		$affect = $this->db->affected_rows();

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
			$message.= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
			$message.= "Status berhasil di ubah !";
			$message.= "</div>";

			$this->session->set_flashdata("message",$message);
		}
	}

	function invoice_penjualan(){
		$this->load->view("navigation");

		$data['receipt'] 		= $this->db->get("ap_receipt");
		$no_invoice 			= $_GET['no_invoice'];
		$data['no_invoice'] 	= $this->model1->invoice_ket($no_invoice);
		$data['invoice_item'] 	= $this->model1->invoice_item($no_invoice);

		/**$this->load->library('ciqrcode');

		$qr['data'] 			= $no_invoice;
		$qr['level']			= 'H';
		$qr['size']				= '10';
		$qr['savename']			= FCPATH."qr/".$no_invoice.".png";
		$this->ciqrcode->generate($qr);**/

		$data['retur_item'] = $this->model1->retur_item_sales($no_invoice);
		$this->load->view("body_invoice_penjualan",$data);
		$this->load->view("footer");
	}

	function form_resi(){
		$data['ekspedisi'] = $this->db->get("ap_ekspedisi");
		$data['no_invoice'] = $_POST['no_invoice'];
		$this->load->view("form_resi",$data);
	}

	function retur_penjualan(){
		$no_invoice = $_GET['no_invoice'];

		$this->load->view("navigation");
		$data['ket_invoice'] 	= $this->model1->invoice_ket($no_invoice);
		$data['invoice_item'] 	= $this->model1->invoice_item($no_invoice);
		$this->load->view("body_retur_penjualan",$data);
		$this->load->view("footer");
	}

	function retur_sql(){
		$count 			= count($_POST['id_produk']);
		$id_pic  		= $this->session->userdata("id_user");
		$cek_no_retur 	= $this->model1->cek_no_retur_penjualan();  
		$no_invoice 	= $_POST['no_invoice'];

		$no_retur 		= "RT".date('y').date('m').date('d').sprintf("%04d",$cek_no_retur+1);

		$data_invoice = array(
								"no_retur" 		=> $no_retur,
								"no_invoice"	=> $no_invoice,
								"pic"			=> $id_pic,
								"tanggal"		=> date('Y-m-d H:i:s'),
								"keterangan"	=> "" 
							 );

		$this->db->insert("ap_retur",$data_invoice);
		
		//ubah status pada invoice menjadi retur == 4

		$data_invoice = array(
								"status"	=> 4
						 	 );

		$this->db->where("no_invoice",$no_invoice);
		$this->db->update("ap_invoice_number",$data_invoice);


		for($i=0;$i<$count;$i++){
			$id_produk = $_POST['id_produk'][$i];
			$qty 	   = $_POST['qty'][$i];
			$remark    = $_POST['remark'][$i];
			$harga 	   = $_POST['harga'][$i];
		
			$data_insert[] = array(
									"no_retur"	=> $no_retur,
									"id_produk" => $id_produk,
									"qty"		=> $qty,
									"harga" 	=> $harga,
									"remark"	=> $remark
								);

			//update stok, kembalikan stok retur
			$stok_lama = $this->model1->stok_produk_lama($id_produk);

			$data_update[] = array(
								 	"stok" 			=> $stok_lama+$qty,
								 	"id_produk"	 	=> $id_produk
								  );
		}

		$this->db->insert_batch("ap_retur_item",$data_insert);
		$this->db->update_batch("ap_produk",$data_update,"id_produk");

		redirect("data_transaksi/invoice_penjualan?no_invoice=".$no_invoice."&tab=4");
	}

	function invoice_retur(){
		$this->load->view("navigation");
		$no_retur 	= $_GET['no_retur'];
		$data['receipt'] = $this->db->get("ap_receipt");
		$this->load->view("body_invoice_retur",$data);
		$this->load->view("footer");
	}

}