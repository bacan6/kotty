<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Model_promotion extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function totalProduk(){
		$this->db->from("ap_produk");
		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		return $this->db->count_all_results();
	}

	function daftar_produk_diskon($length,$start){
		$this->db->select(array("ap_produk.nama_produk","ap_produk.id_produk","ap_kategori.kategori","bahan_baku.harga","ap_produk.harga as harga_jual"));
		$this->db->from("ap_produk");
		$this->db->join("bahan_baku","bahan_baku.sku = ap_produk.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->where("ap_produk.diskon",0);
		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		$this->db->limit($length,$start);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function daftar_produk_diskon_search($length,$start,$search){
		$this->db->select(array("ap_produk.nama_produk","ap_produk.id_produk","ap_kategori.kategori","bahan_baku.harga","ap_produk.harga as harga_jual"));
		$this->db->from("ap_produk");
		$this->db->join("bahan_baku","bahan_baku.sku = ap_produk.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->where("diskon",0);
		$this->db->like("ap_produk.nama_produk",$search);
		$this->db->or_like("ap_produk.id_produk",$search);
		$this->db->limit($length,$start);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function searchingProduk($search){
		$this->db->from("ap_produk");
		$this->db->like("ap_produk.nama_produk",$search);
		$this->db->or_like("ap_produk.id_produk",$search);
		$this->db->where("diskon",0);
		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		return $this->db->count_all_results();
	}

	function jumlah_produk(){
		$this->db->from("ap_produk");
		$this->db->where("ap_produk.diskon",0);
		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		return $this->db->count_all_results();
	}

	function jumlah_produk_diskon(){
		$this->db->from("ap_produk");
		$this->db->where("ap_produk.diskon",1);
		return $this->db->count_all_results();
	}

	function daftar_produk_diskon_enable($length,$start,$idStore='7'){
		$this->db->select(array("ap_produk.nama_produk","ap_produk.id_produk","ap_kategori.kategori","ap_produk_price.hpp as harga","ap_produk_price.harga as harga_jual"));
		$this->db->from("ap_produk");
		$this->db->join("bahan_baku","bahan_baku.sku = ap_produk.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
        $this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk and ap_produk_price.id_toko=".$idStore,"left");
		$this->db->where("ap_produk.diskon",1);
		$this->db->limit($length,$start);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function daftar_produk_diskon_enableSearch($length,$start,$search,$idStore='7'){
		$query = " SELECT ap_produk.nama_produk, ap_produk.id_produk,ap_kategori.kategori,ap_produk_price.hpp as harga,ap_produk_price.harga as harga_jual
				   FROM ap_produk
				   LEFT JOIN bahan_baku ON bahan_baku.sku = ap_produk.id_produk
                   LEFT JOIN ap_produk_price ON ap_produk_price.id_produk = ap_produk.id_produk
                   and ap_produk_price.id_toko='$idStore'
				   LEFT JOIN ap_kategori ON ap_kategori.id_kategori = ap_produk.id_kategori
				   WHERE ap_produk.diskon=1 AND (ap_produk.nama_produk LIKE '%$search%' OR ap_produk.id_produk LIKE '%$search%')
				   GROUP BY ap_produk.id_produk
				   LIMIT $start,$length";

		return $this->db->query($query);
	}

	function rules_discount_produk($sku,$idStore){
		$this->db->select("*");
		$this->db->from("ap_produk_discount_rules");
		$this->db->where("id_produk",$sku);
        $this->db->where("id_toko",$idStore); 
		$this->db->order_by("ap_produk_discount_rules.qty","ASC");
		return $this->db->get()->result();
	}

	function cek_diskon($sku){
		$this->db->select("diskon");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$sku);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function totalProdukDiskon(){
		$this->db->from("ap_produk");
		$this->db->where("diskon",1);
		return $this->db->count_all_results();
	}

	function infoProduk($sku,$idStore){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk_price.hpp as harga_beli","ap_produk.harga as harga_jual"));
		$this->db->from("ap_produk");
		$this->db->join("bahan_baku","bahan_baku.sku = ap_produk.id_produk","left");
        $this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk and ap_produk_price.id_toko=".$idStore,"left");
		$this->db->where("ap_produk.id_produk",$sku);
		return $this->db->get()->result();
	}

	function setToDiskon($sku,$data_update){
		$this->db->where("id_produk",$sku);
		$this->db->update("ap_produk",$data_update);
		$affect = $this->db->affected_rows();
		return $affect;
	}

	function deleteRulesDiscount($sku,$idStore){
		$this->db->delete("ap_produk_discount_rules",array("id_produk" => $sku,"id_toko" => $idStore));
	}

	function insertBatchPromotionDiskon($data_produk){
		$this->db->insert_batch("ap_produk_discount_rules",$data_produk);
		$affect = $this->db->affected_rows();
		return $affect;
	}

	function hapusPromo($id){
		$this->db->delete("ap_produk_discount_rules",array("id" => $id));
		$affect = $this->db->affected_rows();
		return $affect;
	}

	function updateStatusDiskon($sku,$data_update){
		$this->db->where("id_produk",$sku);
		$this->db->update("ap_produk",$data_update);
		$this->db->delete("ap_produk_discount_rules",array("id_produk" => $sku));
	}

	function updatePointSetting($data_update){
		$this->db->where("id",1);
		$this->db->update("poin",$data_update);
		$affect = $this->db->affected_rows();
		return $affect;
	}

}
