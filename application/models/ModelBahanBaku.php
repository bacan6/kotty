<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelBahanBaku extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function totalBahanBakuAkif(){
		$this->db->from("bahan_baku");
		$this->db->where("del",1);
		return $this->db->count_all_results();
	}

	function viewBahanBaku($limit,$start,$search=''){
		$this->db->select(array("bahan_baku.sku","bahan_baku.nama_bahan","bahan_baku.satuan","kategori.kategori","bahan_baku.harga","bahan_baku.status"));
		$this->db->from("bahan_baku");
		$this->db->join("kategori","kategori.id_kategori = bahan_baku.id_kategori");

		if(!empty($search)){
			$this->db->like("bahan_baku.nama_bahan",$search);
		}

		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function cekSKUExist($sku){
		$this->db->from("bahan_baku");
		$this->db->where("sku",$sku);
		return $this->db->count_all_results();
	}

	function insertBahanBaku($dataArray){
		$this->db->insert("bahan_baku",$dataArray);	
	}

	function editBahanBaku($sku,$dataArray){
		$this->db->where("sku",$sku);
		$this->db->update("bahan_baku",$dataArray);
	}

}