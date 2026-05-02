<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelBahanKeluar extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function getIdCart($idProduk,$idUser){
		$this->db->select("id");
		$this->db->from("cc_cartmutasi");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		$query = $this->db->get()->row();
		return $query->id;
	}

	function total_pengeluaran(){
		$this->db->from("sp_no_bahan_keluar");
		return $this->db->count_all_results();
	}

	function daftarPengeluaranBarang($limit,$start,$search){
		$this->db->select(array("ap_store.store","sp_no_bahan_keluar.no_bahan_keluar","users.first_name","sp_no_bahan_keluar.tanggal_keluar","sp_no_bahan_keluar.nama_penerima","sp_no_bahan_keluar.keterangan"));
		$this->db->from("sp_no_bahan_keluar");
		$this->db->join("users","sp_no_bahan_keluar.id_user = users.id","left");
		$this->db->join("ap_store","ap_store.id_store = sp_no_bahan_keluar.store_tujuan","left");
		$this->db->limit($limit,$start);

		if(!empty($search)){
			$this->db->where("sp_no_bahan_keluar.no_bahan_keluar",$search);
		}

		$this->db->group_by("sp_no_bahan_keluar.no_bahan_keluar");
		$this->db->order_by("sp_no_bahan_keluar.tanggal_keluar","DESC");
		return $this->db->get();
	}

	function insertNoBahanKeluar($data_pengeluaran){
		$this->db->insert("sp_no_bahan_keluar",$data_pengeluaran);
	}

	function insertItemKeluar($data_item){
		$this->db->insert("sp_bahan_keluar",$data_item);	
	}

	function stokKeluarGudang($sku,$store_tujuan,$dataStok){
		$this->db->where("id_produk",$sku);
		$this->db->where("id_store",$store_tujuan);
		$this->db->update("stok_store",$dataStok);
	}

	function insertStokBaru($dataStok){
		$this->db->insert("stok_store",$dataStok);
	}

	function updateBatchStokGudang($new_stok){
		$this->db->update_batch("ap_produk",$new_stok,"id_produk");
	}

	function hapusCartMutasi($idUser){
		$this->db->delete("cc_cartmutasi",array("idUser" => $idUser));
	}

	function insertCartMutasi($dataArray){
		$this->db->insert("cc_cartmutasi",$dataArray);
	}

	function deleteCart($rules){
		$this->db->delete("cc_cartmutasi",$rules);
	}

	function updateCart($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$this->db->update("cc_cartmutasi",$dataUpdate);
	}
}