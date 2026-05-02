<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelStokByKategori extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function salesPerkategori($start,$end,$id_kategori,$idStore,$idBrand=''){
		$this->db->select("(SUM(ap_invoice_item.qty)) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left outer");
		if ($idStore>0)
		$this->db->where("ap_invoice_number.id_toko = '$idStore'");
		
		if(!empty($idBrand)){
			$this->db->where("ap_produk.id_brand",$idBrand);
		}
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->group_by("ap_kategori.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}
    
    function returPerkategori($start,$end,$id_kategori,$idStore,$idBrand=''){
		$this->db->select("SUM(ap_invoice_item.harga_jual*ap_retur_item.qty) as totalRetur");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left outer");
		if($idStore>0) $this->db->where("ap_invoice_number.id_toko = '$idStore'");

		if(!empty($idBrand)){
			$this->db->where("ap_produk.id_brand",$idBrand);
		}
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->group_by("ap_produk.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}

	function stokPerkategori($start,$end,$id_kategori,$idStore,$idBrand=''){
		$this->db->select(array("SUM(stok_store.stok) as totalPenjualan","SUM(stok_store.stok*stok_store.hpp) as modal","SUM(stok_store.stok*stok_store.harga) as harga"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		if($idStore>0)
		$this->db->where("stok_store.id_store = '$idStore'");
		$this->db->where("ap_produk.status",1);

		if(!empty($idBrand)){
			$this->db->where("ap_produk.id_brand",$idBrand);
		}
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->group_by("ap_produk.id_kategori");
		$query = $this->db->get()->result();

		return $query;
	}
    
    

	function salesPerkategori2($start,$end,$id_kategori,$id,$idStore,$idBrand=''){
		$this->db->select("(SUM(ap_invoice_item.qty)) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left outer");
		if($idStore>0)
		$this->db->where("ap_invoice_number.id_toko = '$idStore'");
		
		if(!empty($idBrand)){
			$this->db->where("ap_produk.id_brand",$idBrand);
		}
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->where("ap_produk.id_subkategori",$id);
		$this->db->group_by("ap_produk.id_subkategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}

	function stokPerkategori2($start,$end,$id_kategori,$id,$idStore,$idBrand=''){
		$this->db->select(array("SUM(stok_store.stok) as totalPenjualan","SUM(stok_store.stok*stok_store.hpp) as modal","SUM(stok_store.stok*stok_store.harga) as harga"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		if($idStore>0)
		$this->db->where("stok_store.id_store = '$idStore'");

		if(!empty($idBrand)){
			$this->db->where("ap_produk.id_brand",$idBrand);
		}
		$this->db->where("ap_produk.status",1);
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->where("ap_produk.id_subkategori",$id);
		$this->db->group_by("ap_produk.id_subkategori");
		$query = $this->db->get()->result();

		return $query;
	}

	function salesPerkategori3($start,$end,$id_kategori,$id,$id2,$idStore,$idBrand=''){
		$this->db->select("(SUM(ap_invoice_item.qty)) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left outer");
		if($idStore>0)
		$this->db->where("ap_invoice_number.id_toko = '$idStore'");
		
		if(!empty($idBrand)){
			$this->db->where("ap_produk.id_brand",$idBrand);
		}
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->where("ap_produk.id_subkategori",$id);
		$this->db->where("ap_produk.id_subkategori_2",$id2);
		$this->db->group_by("ap_produk.id_subkategori_2");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}

	function stokPerkategori3($start,$end,$id_kategori,$id,$id2,$idStore,$idBrand=''){
		$this->db->select(array("SUM(stok_store.stok) as totalPenjualan","SUM(stok_store.stok*stok_store.hpp) as modal","SUM(stok_store.stok*stok_store.harga) as harga"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		if($idStore>0)
		$this->db->where("stok_store.id_store = '$idStore'");

		if(!empty($idBrand)){
			$this->db->where("ap_produk.id_brand",$idBrand);
		}
		$this->db->where("ap_produk.status",1);
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->where("ap_produk.id_subkategori",$id);
		$this->db->where("ap_produk.id_subkategori_2",$id2);
		$this->db->group_by("ap_produk.id_subkategori_2");
		$query = $this->db->get()->result();

		return $query;
	}

}