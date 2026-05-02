<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelStockOpname extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function dataStokFG($kategori,$subKategori,$subKategori2){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_kategori.kategori","ap_produk.stok","ap_produk.hpp as harga"));
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");

		if(!empty($kategori)){
			$this->db->where("ap_produk.id_kategori",$kategori);
		}

		if(!empty($subKategori)){
			$this->db->where("ap_produk.id_subkategori",$subKategori);
		}

		if(!empty($subKategori2)){
			$this->db->where("ap_produk.id_subkategori_2",$subKategori2);
		}

		return $this->db->get();
	}
    
    function dataPenjualan($toko,$id){
        date_default_timezone_set('Asia/Jakarta');
        
        //$kemarin = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 days'));    
        $kemarin = date('Y-m-d');    
        $tanggal = date('Y-m-d');
		$query = "SELECT sum(i.qty) as jumlah FROM ap_invoice_item i 
                    left outer join ap_invoice_number n on n.no_invoice=i.no_invoice WHERE i.id_produk='$id' and (i.tanggal BETWEEN '$kemarin 00:00:00' and '$tanggal 23:59:00') and n.id_toko='$toko' ";
		return $this->db->query($query);
	}

	function insertStockOpnameInfo($data_so){
		$this->db->insert("stock_opname_info",$data_so);
	}

	function insertBatchSO($data_item){
		$this->db->insert_batch("stock_opname",$data_item);
	}

	function updateBatchStok($data_stok){
		$this->db->update_batch("ap_produk",$data_stok,"id_produk");
	}

	function insertStockOpnameInfoToko($data_so){
		$this->db->insert("stock_opname_info",$data_so);
	}

	function updateStokToko($kode_toko,$sku,$data_stok){
		$this->db->where("id_store",$kode_toko);
		$this->db->where("id_produk in ('$sku','".'0'.$sku."')");
		$this->db->update("stok_store",$data_stok);
	}

	function insertBatchStokOpnameToko($data_item){
		$this->db->insert_batch("stock_opname",$data_item);
	}
}