<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelRetur extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function infoPO($noPO){
		$this->db->select(array("purchase_order.no_po","purchase_order.tanggal_po","purchase_order.jatuh_tempo","purchase_order.tanggal_kirim","supplier.supplier","purchase_order.alamat_pengiriman","purchase_order.keterangan"));
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->where("purchase_order.no_po",$noPO);
		return $this->db->get()->row();
	}

	function purchase_item($no_po){
		$this->db->select(array("purchase_item.no_po","ap_produk.nama_produk","purchase_item.qty","ap_produk.satuan","purchase_item.harga","(purchase_item.harga*purchase_item.qty) as total","ap_produk.id_produk"));
		$this->db->from("purchase_item");
		$this->db->join("ap_produk","ap_produk.id_produk = purchase_item.sku","left");
		$this->db->where("purchase_item.no_po",$no_po);
		return $this->db->get()->result();
	}

	function purchasePeritem($idProduk,$noPO){
		$this->db->select("qty");
		$this->db->from("purchase_item");
		$this->db->where("sku",$idProduk);
		$this->db->where("no_po",$noPO);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function barangDiterima($idProduk,$noPO){
		$this->db->select_sum("qty");
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive");
		$this->db->where("receive_item.sku",$idProduk);
		$this->db->where("receive_order.no_po",$noPO);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function returHistory($idProduk,$noPO){
		$this->db->select_sum("qty");
		$this->db->from("retur_item");
		$this->db->join("retur","retur.no_retur = retur_item.no_retur");
		$this->db->where("retur_item.sku",$idProduk);
		$this->db->where("retur.no_po",$noPO);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function cekNoRetur(){
		$day = date('d');
		$month = date('m');
		$year = date('Y');

		$this->db->from("retur");
		$this->db->where("DAY(tanggal_retur)",$day);
		$this->db->where("MONTH(tanggal_retur)",$month);
		$this->db->where("YEAR(tanggal_retur)",$year);
		return $this->db->count_all_results();
	}

	function returInfo($noRetur){
		$this->db->select(array("retur.no_retur","retur.no_po","retur.tanggal_retur","supplier.supplier","supplier.alamat","supplier.kontak","users.first_name as nama_user"));
		$this->db->from("retur");
		$this->db->join("purchase_order","purchase_order.no_po = retur.no_po");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->join("users","users.id = retur.id_pic","left");
		$this->db->where("retur.no_retur",$noRetur);
		$this->db->group_by("retur.no_retur");
		$query = $this->db->get()->row();
		return $query;
	}

	function returItem($noRetur){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","retur_item.qty","ap_produk.satuan","retur_item.harga","(retur_item.harga*retur_item.qty) as subtotal"));
		$this->db->from("retur_item");
		$this->db->join("ap_produk","ap_produk.id_produk = retur_item.sku","left");
		$this->db->where("retur_item.no_retur",$noRetur);
		return $this->db->get()->result();
	}

	function insertNoRetur($data_retur){
		$this->db->insert("retur",$data_retur);
	}

	function insertReturItemBatch($data_item){
		$this->db->insert_batch("retur_item",$data_item);
	}

	function updateBatchStok($data_update,$kode_toko){
		$this->db->where("id_store",$kode_toko);
		$this->db->update_batch("stok_store",$data_update,"id_produk");
	}

	function returPerstore($dataRetur){
		$this->db->insert("returstore",$dataRetur);
	}

	function updateStokStore($idStore,$sku,$dataUpdate){
		$this->db->where("id_store",$idStore);
		$this->db->where("id_produk",$sku);
		$this->db->update("stok_store",$dataUpdate);
	}

	function tambahStokGudang($stokGudang){
		$this->db->update_batch("ap_produk",$stokGudang,"id_produk");
	}

	function insertBatchReturStoreItem($dataArray){
		$this->db->insert_batch("returstoreitem",$dataArray);
	}
}