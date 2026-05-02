<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelSalesByKategori extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function salesPerkategori($start,$end,$id_kategori,$idStore){
		$this->db->select("(SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty)-SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.diskon,0))) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
		if ($idStore>0)
		$this->db->where("ap_invoice_number.id_toko = '$idStore'");
		
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->group_by("ap_kategori.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}
    
    function returPerkategori($start,$end,$id_kategori,$idStore){
		$this->db->select("SUM(ap_invoice_item.harga_jual*ap_retur_item.qty) as totalRetur");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left outer");
		if($idStore>0) $this->db->where("ap_invoice_number.id_toko = '$idStore'");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->group_by("ap_kategori.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}

	function salesPerkategoriHPP($start,$end,$id_kategori,$idStore){
		$this->db->select("SUM(ap_invoice_item.hpp*ap_invoice_item.qty) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left outer");
		if($idStore>0)
		$this->db->where("ap_invoice_number.id_toko = '$idStore'");
		
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->group_by("ap_kategori.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}
    
    

	function salesPerkategori2($start,$end,$id_kategori,$id,$idStore){
		$this->db->select("(SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty)-SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.diskon,0))) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left outer");
		if($idStore>0)
		$this->db->where("ap_invoice_number.id_toko = '$idStore'");
		
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->where("ap_produk.id_subkategori",$id);
		$this->db->group_by("ap_kategori.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}

	function salesPerkategori2HPP($start,$end,$id_kategori,$id,$idStore){
		$this->db->select("SUM(ap_invoice_item.hpp*ap_invoice_item.qty) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left outer");
		if($idStore>0)
		$this->db->where("ap_invoice_number.id_toko = '$idStore'");

		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->where("ap_produk.id_subkategori",$id);
		$this->db->group_by("ap_kategori.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}

	function salesPerkategori3($start,$end,$id_kategori,$id,$id2,$idStore){
		$this->db->select("(SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty)-SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.diskon,0))) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left outer");
		if($idStore>0)
		$this->db->where("ap_invoice_number.id_toko = '$idStore'");
		
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->where("ap_produk.id_subkategori",$id);
		$this->db->where("ap_produk.id_subkategori_2",$id2);
		$this->db->group_by("ap_kategori.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}

	function salesPerkategori3HPP($start,$end,$id_kategori,$id,$id2,$idStore){
		$this->db->select("SUM(ap_invoice_item.hpp*ap_invoice_item.qty) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left outer");
		if($idStore>0)
		$this->db->where("ap_invoice_number.id_toko = '$idStore'");

		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->where("ap_produk.id_subkategori",$id);
		$this->db->where("ap_produk.id_subkategori_2",$id2);
		$this->db->group_by("ap_kategori.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}

	function total_sales($idStore='',$start,$end){
		$this->db->select(array("SUM(ap_invoice_number.total) as total","SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.diskon,0)) as diskon","SUM(ap_invoice_number.diskon_free) as diskon_free","SUM(ap_invoice_number.poin_value) as poin_value","SUM(ap_invoice_number.diskon_otomatis) as diskon_otomatis"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		//$this->db->group_by("ap_invoice_number.no_invoice");

		$query = $this->db->get()->result();

		foreach($query as $row){
			$total 	= $row->total;
			$diskon = $row->diskon;
			$diskon_free =$row->diskon_free;
			$reimburs = $row->poin_value;
			$diskonOtomatis = $row->diskon_otomatis;

			return $total-($diskon+$diskon_free+$reimburs+$diskonOtomatis);
		}
	}

}