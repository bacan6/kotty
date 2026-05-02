<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelWaste extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function totalWasteProduk(){
		$this->db->from("waste");
		return $this->db->count_all_results();
	}

	function viewWaste($limit,$start,$search='',$status,$id_brand,$id_supplier){
		$dataSelect = array(
								"supplier.supplier","brand.brand","waste.status as Lunas","waste.no_waste","waste.tanggal_waste","users.first_name","waste.keterangan","ap_store.store","keterangan_waste.keterangan as status"
						   );

		$this->db->select($dataSelect);
		$this->db->from("waste");
		$this->db->join("ap_store","ap_store.id_store = waste.id_toko");
		$this->db->join("users","users.id = waste.id_pic");
		$this->db->join("brand","brand.id_brand = waste.id_brand","left");
		$this->db->join("supplier","supplier.id_supplier = waste.id_supplier","left");
		$this->db->join("keterangan_waste","keterangan_waste.id_keterangan = waste.id_keterangan");

		if(!empty($search)){
			$this->db->like("waste.no_waste",$search);
		}
		if(!empty($status)){
			$this->db->where("waste.status",$status);
		}
		if(!empty($id_brand)){
			$this->db->where("waste.id_brand",$id_brand);
		}
		if(!empty($id_supplier)){
			$this->db->where("waste.id_supplier",$id_supplier);
		}

		$this->db->limit($limit,$start);
		$this->db->order_by("waste.tanggal_waste","DESC");
		$this->db->order_by("waste.no_waste","DESC");
		return $this->db->get();
	}

	function cekCartWaste($idProduk,$idUser){
		$this->db->from("cc_cartwaste");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function produk_search_all($q){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		$this->db->like("ap_produk.nama_produk",$q);
		$this->db->or_like("ap_produk.id_produk",$q);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function cek_stok_lama($sku,$idStore='7'){
		$this->db->select_sum("stok_store.stok");
		$this->db->from("stok_store");
		//$this->db->join("expired_product","expired_product.no_po=expired_product_item.no_po");
		$this->db->where("stok_store.id_produk",$sku);
		$this->db->where("stok_store.id_store",$idStore);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->stok;
		}
	}

	function getIdCart($idProduk,$idUser){
		$this->db->select("id");
		$this->db->from("cc_cartwaste");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		$query = $this->db->get()->row();
		return $query->id;
	}

	function viewCartWaste($idUser,$id_toko){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan","cc_cartwaste.qty","cc_cartwaste.id","ap_produk_price.hpp"));
		$this->db->from("cc_cartwaste");
		$this->db->join("ap_produk","ap_produk.id_produk = cc_cartwaste.idProduk");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = cc_cartwaste.idProduk","left");
		$this->db->where("cc_cartwaste.idUser",$idUser);
		$this->db->where("ap_produk_price.id_toko",$id_toko);
		$this->db->order_by("cc_cartwaste.id","DESC");
		return $this->db->get()->result();
	}

	function currentStokWarehouse($sku,$idStore){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$sku);
		$this->db->where("id_store",$idStore);
		$query = $this->db->get()->row();
		return $query->stok;
	}

	function cek_tanggal_waste($tanggal_waste){
		$this->db->from("waste");
		$this->db->where("tanggal_waste",$tanggal_waste);
		return $this->db->count_all_results();
	}

	function insertWaste($data_waste){
		$this->db->insert("waste",$data_waste);
	}

	function insertWastefromExp($data_waste){
		$this->db->insert("expired_product",$data_waste);
	}

	function insertWasteItemBatch($data_item){
		$this->db->insert_batch("waste_item",$data_item);
	}

	function insertExpiredItemBatch($data_item){
		$this->db->insert_batch("expired_product_item",$data_item);
	}

	function updateStokBatch($data_update,$idStore){
		$this->db->where("id_store",$idStore);
		$this->db->update_batch("stok_store",$data_update,"id_produk");
	}

	function hapusCartWaste($idUser){
		$this->db->delete("cc_cartwaste",array("idUser" => $idUser));
	}

	function insertCartWaste($dataArray){
		$this->db->insert("cc_cartwaste",$dataArray);
	}

	function updateQtyCartWaste($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$this->db->update("cc_cartwaste",$dataUpdate);
	}

	function hapusCartId($id){
		$this->db->delete("cc_cartwaste",array("id" => $id));
	}
}