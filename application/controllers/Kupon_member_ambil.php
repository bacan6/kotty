<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Kupon_member_ambil extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("modelKupon");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],3,66);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Promo Kupon Produk";
		$id = $_GET['id_customer'];
		$data['id_customer'] = $id;
		$this->loadViews("kupon_member_ambil/body_kupon",$this->global,$data,"kupon_member_ambil/footerKupon");
	}
	function invoice(){
		$noInvoice = $_GET['no_kupon'];
		$kupon_member = $this->db->get_where("k_kupon_member",array("id_kupon_member" => $noInvoice))->row();

		$this->db->where('id_customer', $kupon_member->id_customer);
		$this->db->or_where('kontak', $kupon_member->id_customer);
		$data['member'] = $this->db->get('ap_customer')->row();
		// $data['member'] = $this->db->get_where("ap_customer",array("id_customer" => $kupon_member->id_customer))->row();
		$data['kupon'] = $this->db->get_where("k_kupon",array("id_kupon" => $kupon_member->id_kupon))->row();
		$data['kasir'] = $this->db->get_where("users",array("id" => $kupon_member->pic))->row();
		$data['store'] = $this->db->get_where("ap_store",array("id_store" => $kupon_member->toko))->row();
		$data['tanggal'] = $kupon_member->tgl_out;
		//$this->load->view("kupon_member_ambil/invoice",$data);

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Pengambilan Produk Penukaran Poin";
		
		$this->loadViews("kupon_member_ambil/invoice",$this->global,$data,"kupon_member_ambil/footerInvoice");
	}
	function data_kupon(){
		//error_reporting(E_ALL);ini_set("display_errors",1);
		$id = $_GET['id_customer'];
		$data['id_customer'] = $id;

		$data['poin'] = $this->modelKupon->poinMember($id);
		$data['nama'] = $this->modelKupon->namaMember($id);

		//$point = $data['poin']+0;
		
		

		$data['kupon'] = $this->modelKupon->dataKupon($id,$this->global['idStore']);
		$this->load->view("kupon_member_ambil/data_kupon",$data);
	}


	function ambil_produk(){
		$id = $_POST['id'];
		$id_customer = $_POST['id_customer'];
		//$id_kupon = $_POST['id_kupon'];
		//$kupon = $this->db->get_where("k_kupon",array("id_kupon" => $id_kupon))->row();
		//$member = $this->db->get_where("ap_customer",array("id_customer" => $id_customer))->row();

		$data_update = array( 
							 "status" => 'Out', 
							 "tgl_out" => date("Y-m-d H:i:s"),
							"pic" => $this->global['idUser'],
							"toko" =>$this->global['idStore']);
		$update = $this->modelKupon->update_kupon_member($data_update,$id,$id_customer);
		
		//@unlink('uploads/files/kupon_member_ambil/'.$kupon->gambar);
		echo $id;
	}
}