<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelPurchaseOrderMaterial extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function produkAjax($q){
		$this->db->select(array("bahan_baku.sku","bahan_baku.nama_bahan"));
		$this->db->from("bahan_baku");
		$this->db->like("bahan_baku.nama_bahan",$q);
		$this->db->where("status",1);
		$this->db->where("del",1);
		return $this->db->get();
	}

	function hargaBeliProduk($idProduk){
		$this->db->select("harga");
		$this->db->from("bahan_baku");
		$this->db->where("sku",$idProduk);
		$query = $this->db->get()->row(); 
		return $query->harga;
	}

	function cekCartPO($idProduk,$idUser){
		$this->db->from("cc_pomaterial");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function currentQtyCart($idProduk,$idUser){
		$this->db->select("qty");
		$this->db->from("cc_pomaterial");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function viewCartPO($idUser){
		$this->db->select(array("bahan_baku.sku","bahan_baku.nama_bahan","bahan_baku.satuan","cc_pomaterial.harga","cc_pomaterial.qty"));
		$this->db->from("cc_pomaterial");
		$this->db->join("bahan_baku","bahan_baku.sku = cc_pomaterial.idProduk");
		$this->db->where("cc_pomaterial.idUser",$idUser);
		return $this->db->get();
	}

	function purchase_item($no_po){
		$this->db->select(array("bahan_baku.nama_bahan","purchase_item.qty","bahan_baku.satuan","purchase_item.harga","(purchase_item.harga*purchase_item.qty) as total","bahan_baku.sku"));
		$this->db->from("purchase_item");
		$this->db->join("bahan_baku","bahan_baku.sku = purchase_item.sku","left");
		$this->db->where("purchase_item.no_po",$no_po);
		return $this->db->get();
	}

	function insertCartPO($dataCart){
		$this->db->insert("cc_pomaterial",$dataCart);
	}

	function updateCartPO($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$this->db->update("cc_pomaterial",$dataUpdate);
	}

	function updateQtyCart($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where('idUser',$idUser);
		$this->db->update("cc_pomaterial",$dataUpdate);
	}

	function updateHargaCart($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where('idUser',$idUser);
		$this->db->update("cc_pomaterial",$dataUpdate);
	}

	function hapusCart($idProduk,$idUser){
		$this->db->delete("cc_pomaterial",array("idProduk" => $idProduk, "idUser" => $idUser));
	}

	function insertPO($data_masuk){
		$this->db->insert("purchase_order",$data_masuk);
	}

	function insertBatchPurchaseItem($data_bahan){
		$this->db->insert_batch("purchase_item",$data_bahan);
	}

	function hapusCCPOMaterial($idUser){
		$this->db->delete("cc_pomaterial",array("idUser" => $idUser));
	}
}