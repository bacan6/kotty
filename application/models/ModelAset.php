<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelAset extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function dataStokAkumulasi($limit,$start){
		$query = "SELECT ap_produk.stok,ap_produk.hpp, ap_produk.id_produk, stokGudang, ap_produk.nama_produk, bahan_baku.harga
				  FROM ap_produk
				  LEFT JOIN bahan_baku ON bahan_baku.sku = ap_produk.id_produk
				  LEFT JOIN (SELECT SUM(stok_store.stok) as stokGudang, stok_store.id_produk FROM stok_store GROUP BY stok_store.id_produk) as stokStoreJoin ON stokStoreJoin.id_produk = ap_produk.id_produk
				  GROUP BY ap_produk.id_produk
				  ORDER BY ap_produk.nama_produk DESC
				  limit  $start,$limit";

		return $this->db->query($query);
	}

	function dataStokAkumulasiCari($q){
		$query = "SELECT ap_produk.stok,ap_produk.hpp, ap_produk.id_produk, stokGudang, ap_produk.nama_produk, bahan_baku.harga
				  FROM ap_produk
				  LEFT JOIN bahan_baku ON bahan_baku.sku = ap_produk.id_produk
				  LEFT JOIN (SELECT SUM(stok_store.stok) as stokGudang, stok_store.id_produk FROM stok_store GROUP BY stok_store.id_produk) as stokStoreJoin ON stokStoreJoin.id_produk = ap_produk.id_produk
				  WHERE ap_produk.nama_produk LIKE '%$q%' OR ap_produk.id_produk LIKE '%$q%'
				  GROUP BY ap_produk.id_produk
				  ORDER BY ap_produk.nama_produk DESC";

		return $this->db->query($query);
	}

	function totalProduk(){
		$this->db->from("ap_produk");
		$this->db->where("ap_produk.status",1);
		return $this->db->count_all_results();
	}

	function viewStokGudang($idProduk){
		$this->db->select("stok");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$idProduk);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->stok;
		}
	}

	function viewStokStore($idProduk){
		$this->db->select(array("ap_store.store","stok_store.stok"));
		$this->db->from("stok_store");
		$this->db->join("ap_store","ap_store.id_store = stok_store.id_store","left");
		$this->db->where("stok_store.id_produk",$idProduk);
		return $this->db->get()->result();
	}
}