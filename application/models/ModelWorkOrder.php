<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelWorkOrder extends CI_Model{
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

	function hargaBahanBaku($sku){
		$this->db->select("harga");
		$this->db->from("bahan_baku");
		$this->db->where("sku",$sku);
		$query = $this->db->get()->row();

		return $query->harga;
	}

	function viewCart($idUser){
		$this->db->select(array("bahan_baku.satuan","bahan_baku.sku","bahan_baku.nama_bahan","cc_workorderitem.harga","cc_workorderitem.qty","(cc_workorderitem.harga*cc_workorderitem.qty) as totalHarga"));
		$this->db->from("cc_workorderitem");
		$this->db->join("bahan_baku","bahan_baku.sku = cc_workorderitem.sku");
		$this->db->where("cc_workorderitem.idUser",$idUser);
		return $this->db->get();
	}

	function cekSKUIfExist($sku,$idUser){
		$this->db->from("cc_workorderitem");
		$this->db->where("sku",$sku);
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function currentStokBahanBaku($sku){
		$this->db->select("stok");
		$this->db->from("bahan_baku");
		$this->db->where("sku",$sku);
		$query = $this->db->get()->row();
		return $query->stok;
	}

	function currentStokCart($sku,$idUser){
		$this->db->select("qty");
		$this->db->from("cc_workorderitem");
		$this->db->where("idUser",$idUser);
		$this->db->where("sku",$sku);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function produkAjaxFG($q){
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

	function cekCartConvert($idProduk,$idUser){
		$this->db->from("cc_workorderconvert");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function viewCartConvert($idUser){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","cc_workorderconvert.qty"));
		$this->db->from("cc_workorderconvert");
		$this->db->join("ap_produk","ap_produk.id_produk = cc_workorderconvert.idProduk");
		$this->db->where("cc_workorderconvert.idUser",$idUser);
		return $this->db->get();
	}

	function urutanNoWO($today){
		$this->db->from("work_order");
		$this->db->where("tanggalWO",$today);
		return $this->db->count_all_results();
	}

	function infoWO($noWO){
		$this->db->select(array("work_order.no_order","work_order.tanggalWO","work_order.tanggalPenyelesaian","supplier.supplier","work_order.pemohon","work_order.keterangan","work_order.id_supplier","work_order.status"));
		$this->db->from("work_order");
		$this->db->join("supplier","supplier.id_supplier = work_order.id_supplier");
		$this->db->where("work_order.no_order",$noWO);
		return $this->db->get()->row();
	}

	function daftarBahanBakuOrder($noWO){
		$this->db->select(array("bahan_baku.sku","bahan_baku.nama_bahan","work_order_item.qty","bahan_baku.satuan"));
		$this->db->from("work_order_item");
		$this->db->join("bahan_baku","bahan_baku.sku = work_order_item.sku");
		$this->db->where("work_order_item.no_order",$noWO);
		$this->db->group_by("work_order_item.sku");
		return $this->db->get()->result(); 
	}

	function daftarPesananOrder($noWO){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","work_order_convert.qty"));
		$this->db->from("work_order_convert");
		$this->db->join("ap_produk","ap_produk.id_produk = work_order_convert.idProduk");
		$this->db->where("work_order_convert.no_order",$noWO);
		$this->db->group_by("work_order_convert.idProduk",$noWO);
		return $this->db->get()->result();
	}

	function daftarBiaya($noWO){
		$this->db->select("*");
		$this->db->from("work_order_biaya");
		$this->db->where("no_order",$noWO);
		return $this->db->get()->row();
	}

	function totalWO(){
		$this->db->from("work_order");
		return $this->db->count_all_results();
	}

	function viewWorkOrder($limit,$start,$search=''){
		$this->db->select(array("work_order.status","work_order.no_order","work_order.tanggalWO","work_order.tanggalPenyelesaian","supplier.supplier","work_order.pemohon"));
		$this->db->from("work_order");
		$this->db->join("supplier","supplier.id_supplier = work_order.id_supplier");
		if(!empty($search)){
			$this->db->like("work_order.no_order",$search);
		}
		$this->db->order_by("tanggalWO","DESC");
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function viewWorkOrderFilter($limit,$start,$search='',$tanggalWO='',$tanggalPenyelesaian='',$vendor='',$status=''){
		$this->db->select(array("work_order.status","work_order.no_order","work_order.tanggalWO","work_order.tanggalPenyelesaian","supplier.supplier","work_order.pemohon"));
		$this->db->from("work_order");
		$this->db->join("supplier","supplier.id_supplier = work_order.id_supplier");

		if(!empty($tanggalWO)){
			$this->db->where("work_order.tanggalWO",$tanggalWO);
		}

		if(!empty($tanggalPenyelesaian)){
			$this->db->where("work_order.tanggalPenyelesaian",$tanggalPenyelesaian);
		}

		if(!empty($vendor)){
			$this->db->where("work_order.id_supplier",$vendor);
		}

		if(!empty($status)){
			$this->db->where("work_order.status",$status);
		}

		if(!empty($search)){
			$this->db->like("work_order.no_order",$search);
		}
		$this->db->order_by("tanggalWO","DESC");
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function totalWOFilter($tanggalWO='',$tanggalPenyelesaian='',$vendor='',$status=''){
		$this->db->from("work_order");
		if(!empty($tanggalWO)){
			$this->db->where("work_order.tanggalWO",$tanggalWO);
		}

		if(!empty($tanggalPenyelesaian)){
			$this->db->where("work_order.tanggalPenyelesaian",$tanggalPenyelesaian);
		}

		if(!empty($vendor)){
			$this->db->where("work_order.id_supplier",$vendor);
		}

		if(!empty($status)){
			$this->db->where("work_order.status",$status);
		}	
		return $this->db->count_all_results();
	}

	function cekTanggalReceiveWO($tanggal){
		$this->db->from("receive_order");
		$this->db->where("tanggal_terima",$tanggal);
		$this->db->where("type",2);
		return $this->db->count_all_results();
	}

	function hargaBeliProduk($idProduk){
		$this->db->select("hpp");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$idProduk);
		$query = $this->db->get()->row();

		return $query->hpp;
	}

	function dataReceive($noReceive){
		$this->db->select(array("receive_order.no_receive","receive_order.no_po","receive_order.tanggal_terima","receive_order.received_by","receive_order.checked_by","supplier.supplier","receive_order.diterimaDi"));
		$this->db->from("receive_order");
		$this->db->join("work_order","work_order.no_order = receive_order.no_po");
		$this->db->join("supplier","supplier.id_supplier = work_order.id_supplier","right");
		$this->db->where("receive_order.no_receive",$noReceive);
		return $this->db->get()->result();
	}

	function received_item($no_receive){
		$this->db->select("*");
		$this->db->from("receive_item");
		$this->db->join("ap_produk","ap_produk.id_produk = receive_item.sku","left");
		$this->db->where("no_receive",$no_receive);
		return $this->db->get();
	}

	function cekAdjustDate($tanggal){
		$this->db->from("work_order_adjustment");
		$this->db->where("tanggal",$tanggal);
		return $this->db->count_all_results();
	}

	function riwayatPenerimaan($noWO){
		$this->db->select("*");
		$this->db->from("receive_order");
		$this->db->where("no_po",$noWO);
		return $this->db->get()->result();
	}

	function riwayatAdjusment($noWO){
		$this->db->select("*");
		$this->db->from("work_order_adjustment");
		$this->db->where("noWO",$noWO);
		return $this->db->get()->result();
	}

	function adjusmentItem($noAdjusment){
		$this->db->select(array("bahan_baku.nama_bahan","work_order_adjustment_item.qty","bahan_baku.satuan"));
		$this->db->from("work_order_adjustment_item");
		$this->db->join("bahan_baku","bahan_baku.sku = work_order_adjustment_item.sku");
		$this->db->where("work_order_adjustment_item.no_adjusment",$noAdjusment);
		$this->db->group_by("bahan_baku.sku");
		return $this->db->get()->result();
	}

	function infoAdj($noAdj){
		$this->db->select(array("work_order_adjustment.no_adjusment","work_order_adjustment.noWO","work_order_adjustment.tanggal","work_order_adjustment.keterangan"));
		$this->db->from("work_order_adjustment");
		$this->db->where("work_order_adjustment.no_adjusment",$noAdj);
		return $this->db->get()->row();
	}

	function itemAdj($noAdj){
		$this->db->select(array("bahan_baku.nama_bahan","work_order_adjustment_item.qty","bahan_baku.satuan"));
		$this->db->from("work_order_adjustment_item");
		$this->db->join("bahan_baku","bahan_baku.sku = work_order_adjustment_item.sku");
		$this->db->where("work_order_adjustment_item.no_adjusment",$noAdj);
		$this->db->group_by("bahan_baku.sku");
		return $this->db->get()->result();
	}

	function orderDiterimaPeritem($idProduk,$noWO){
		$this->db->select('SUM(qty) as qty');
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive");
		$this->db->where("receive_order.no_po",$noWO);
		$this->db->where("receive_item.sku",$idProduk);
		$this->db->group_by("receive_item.sku");
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function adjPermaterial($sku,$noWO){
		$this->db->select("qty");
		$this->db->from("work_order_adjustment_item");
		$this->db->join("work_order_adjustment","work_order_adjustment.no_adjusment = work_order_adjustment_item.no_adjusment");
		$this->db->where("work_order_adjustment.noWO",$noWO);
		$this->db->where("sku",$sku);
		return $this->db->get()->result();
	}

	function insertCart($dataInsert){
		$this->db->insert("cc_workorderitem",$dataInsert);
	}

	function hapusCart($param){
		$this->db->delete("cc_workorderitem",$param);
	}

	function updateCart($dataUpdate){
		$this->db->where("sku",$sku);
		$this->db->where("idUser",$idUser);
		$this->db->update("cc_workorderitem",$dataUpdate);
	}

	function insertCartFG($dataCart){
		$this->db->insert("cc_workorderconvert",$dataCart);
	}

	function hapusCartConvert($idProduk,$idUser){
		$this->db->delete("cc_workorderconvert",array('idProduk' => $idProduk, 'idUser' => $idUser));
	}

	function updateCartConvert($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$this->db->update("cc_workorderconvert",$dataUpdate);
	}

	function prosesWO($dataWO){
		$this->db->insert("work_order",$dataWO);
	}

	function prosesBatchWO($dataWOItem,$dataStokUpdate,$idUser){
		$this->db->insert_batch("work_order_item",$dataWOItem);
		$this->db->update_batch("bahan_baku",$dataStokUpdate,"sku");
		$this->db->delete("cc_workorderitem",array("idUser" => $idUser));
	}

	function insertBatchWorkOrderConvert($dataConvert,$idUser){
		$this->db->insert_batch("work_order_convert",$dataConvert);
		$this->db->delete("cc_workorderconvert",array("idUser" => $idUser));
	}

	function insertWorkOrderBiaya($dataBiaya){
		$this->db->insert("work_order_biaya",$dataBiaya);
	}

	function submitAdjusmentWO($dataAdjust){
		$this->db->insert("work_order_adjustment",$dataAdjust);
	}

	function insertBatchWOAdjustment($dataUpdate,$currentStok){
		$this->db->insert_batch("work_order_adjustment_item",$dataUpdate);
		$this->db->update_batch("bahan_baku",$currentStok,"sku");
	}

	function receiveWOResultSQL($data_receive){
		$this->db->insert("receive_order",$data_receive);
	}

	function receiveWorkOrder($sku,$diterimaDi,$data_vr){
		$this->db->where("id_produk",$sku);
		$this->db->where("id_store",$diterimaDi);
		$this->db->update("stok_store",$data_vr);
	}

	function insertStokStore($data_vr){
		$this->db->insert("stok_store",$data_vr);
	}
}

