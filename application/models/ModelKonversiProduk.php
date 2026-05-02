<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelKonversiProduk extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function produkAjax($q){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk"));
		$this->db->from("ap_produk");
		$this->db->group_start();
		$this->db->like("ap_produk.id_produk",$q);
		$this->db->or_like("ap_produk.nama_produk",$q);	
		$this->db->group_end();
		$this->db->group_start();
		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		$this->db->group_end();
		return $this->db->get();
	}

	function cekStokGudang($idProduk){
		$this->db->select(array("stok","hpp"));
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$idProduk);
		$query = $this->db->get()->row();
		return $query;
	}

	function cekCartExist($idProduk,$idUser){
		$this->db->from("cc_konversiproduk");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		return $this->db->count_all_results();
	}

	function viewCart($idUser){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","cc_konversiproduk.hargaBeli","cc_konversiproduk.qty","(cc_konversiproduk.hargaBeli*cc_konversiproduk.qty) as total","ap_produk.satuan"));
		$this->db->from("cc_konversiproduk");
		$this->db->join("ap_produk","ap_produk.id_produk = cc_konversiproduk.idProduk");
		$this->db->where("cc_konversiproduk.idUser",$idUser);
		return $this->db->get()->result();
	}

	function updateCart($dataUpdate,$idProduk,$qty){
		$this->db->where("idProduk",$idProduk);
		$this->db->update("cc_konversiproduk",$dataUpdate);
	}

	function cekConvertNumber($idUser,$tanggal){
		$this->db->from("konversi_number");
		$this->db->where("idUser",$idUser);
		$this->db->where("tanggal",$tanggal);
		return $this->db->count_all_results();
	}

	function cekBahanBakuSKU($idProduk){
		$this->db->from("bahan_baku");
		$this->db->where("sku",$idProduk);
		return $this->db->count_all_results();
	}

	function infoProduk($idProduk){
		$this->db->select("*");
		$this->db->from("ap_produk");
		$this->db->where("ap_produk.id_produk",$idProduk);
		return $this->db->get()->row();
	}

	function stokBahanBaku($idProduk){
		$this->db->select("stok");
		$this->db->from("bahan_baku");
		$this->db->where("sku",$idProduk);
		$query = $this->db->get()->row();
		return $query->stok;
	}

	function infoKonversi($noKonversi){
		$this->db->select(array("konversi_number.no_convert","konversi_number.tanggal","users.first_name as nama_user"));
		$this->db->from("konversi_number");
		$this->db->join("users","users.id = konversi_number.idUser");
		$this->db->where("konversi_number.no_convert",$noKonversi);
		return $this->db->get()->row();
	}

	function itemKonversi($noKonversi){
		$this->db->select(array("bahan_baku.sku","bahan_baku.nama_bahan","konversi_item.qty","bahan_baku.satuan"));
		$this->db->from("konversi_item");
		$this->db->join("bahan_baku","bahan_baku.sku = konversi_item.idProduk");
		$this->db->where("konversi_item.no_convert",$noKonversi);
		return $this->db->get()->result();
	}

	function insertCart($dataInsert){
		$this->db->insert("cc_konversiproduk",$dataInsert);
	}

	function hapuCart($idProduk,$idUser){
		$this->db->delete("cc_konversiproduk",array("idProduk" => $idProduk, "idUser" => $idUser));
	}

	function insertKonversiNumber($dataConvertNumber){
		$this->db->insert("konversi_number",$dataConvertNumber);
	}
}
