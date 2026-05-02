<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Q_Po_receiving extends CI_Controller{
	function __construct(){
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelStockOpname"));
		$this->load->database();
	}

	function get_po(){
		$toko = $this->input->post('toko');
		$this->db->select('purchase_order.no_po,supplier.supplier');
		$this->db->from('purchase_order');
		$this->db->join('supplier','supplier.id_supplier=purchase_order.id_supplier');
		$this->db->where('purchase_order.status','1');
		$this->db->where('purchase_order.id_toko',$toko);
		$query = $this->db->get();
		$po  = $query->result();

		echo json_encode($po);
	}

	function cari_order(){
		$nopo = $this->input->post('nopo');
		$sku = $this->input->post('sku');
		$toko = $this->input->post('toko');

		$this->db->select('ap_produk.nama_produk,ap_produk.id_produk,purchase_item.qty');
		$this->db->from('ap_produk');
		$this->db->join('purchase_item','purchase_item.sku=ap_produk.id_produk');
		$this->db->join('purchase_order','purchase_order.no_po=purchase_item.no_po');
		$this->db->where('purchase_item.sku', $sku);
		$this->db->where('purchase_item.no_po', $nopo);
		$this->db->where('purchase_order.status','1');
		$this->db->where('purchase_order.id_toko',$toko);
		$this->db->order_by('purchase_order.tanggal_po');
		$query = $this->db->get();
		$produk  = $query->result();

		echo json_encode($produk);
	}

	function simpan_po(){
		//error_reporting(E_ALL);ini_set('display_errors',1);
		$po = $this->input->post("po");
		$sku = $this->input->post("sku");
		$qty = $this->input->post("stok");
		$iduser = $this->input->post("iduser");
		$token = $this->input->post("token");

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('token',$token);
		$query=$this->db->get();

		if(!empty($query->num_rows())){

		$where=array(
			'no_po' => $po,
		    'sku' => $sku,
		);

		$this->db->select('*');
		$this->db->from('cc_cart_receive_item');
		$this->db->where($where);
		$query = $this->db->get();
		$receive=$query->row();

		if(empty($query->num_rows())){

			$datai = array(
		        'no_po' => $po,
		        'sku' => $sku,
		        'qty' => $qty
			);

			$this->db->insert('cc_cart_receive_item', $datai);
			$save = $this->db->affected_rows();

		}else{
			$stok=$qty;
			$datae = array(
		        'qty' => $stok,
			);

			$this->db->set($datae);
			$this->db->where($where);
			$this->db->update('cc_cart_receive_item');
			$save = $this->db->affected_rows();
		}
		
		if($save){
			$data['pesan'] = "Menyimpan data berhasil.";
			$data['status'] = "sukses";
		}else{
			$data['pesan'] = "Gagal menyimpan data\nKemungkinan data sudah terinput sebelumnya.";
			$data['status'] = "error";
		}
	}else{

		$data['pesan'] = "Terjadi kesalahan, silahkan login kembali.";
		$data['status'] = "error";
	}
		echo json_encode($data);
	}
}