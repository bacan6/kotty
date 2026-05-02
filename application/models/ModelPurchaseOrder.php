<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelPurchaseOrder extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_bahan_baku_select2_ajax($term){
		$this->db->select(array("sku","nama_bahan"));
		$this->db->from("bahan_baku");
		$this->db->like("bahan_baku.sku",$term);
		$this->db->or_like("bahan_baku.nama_bahan",$term);
		$this->db->where("status",1); //CHOOOSE ACTIVE
		$this->db->where("del",1); //CHOOSE ACTIVE
		$this->db->where("type",0);
		$this->db->or_where("type",1);
		$this->db->order_by("sku","ASC");
		return $this->db->get();
	}

	function hapusCartPO($idUser){
		$this->db->where("idUser",$idUser);
        $this->db->delete("cc_cartpurchaseorder");
	}

	function produkAjax($q,$id_brand=''){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk"));
		$this->db->from("ap_produk");
		$this->db->group_start();
		$this->db->like("ap_produk.id_produk",$q);
		$this->db->or_like("ap_produk.nama_produk",$q);	
		$this->db->group_end();
		//$this->db->group_start();
		$this->db->where("ap_produk.status",1);
		if (!empty($id_brand)){
			//$this->db->where("ap_produk.id_brand",$id_brand);
		}
		//$this->db->or_where("ap_produk.status",0);
		//$this->db->group_end();
		return $this->db->get();
	}
	function produkAjaxSupplier($q,$idToko){
		$this->db->select(array("ap_produk.id_produk","ap_produk_price.hpp","concat (0) as pesan","stok_store.stok"));
		$this->db->from("ap_produk");
		$this->db->join("ap_produk_price","ap_produk.id_produk = ap_produk_price.id_produk","left");
        $this->db->join("stok_store","stok_store.id_produk = ap_produk.id_produk and stok_store.id_store='$idToko'","left");
		$this->db->where("ap_produk.id_brand",$q);
        $this->db->where("ap_produk_price.id_toko",$idToko);
		//$this->db->where("stok_store.stok < stok_store.min");
		$this->db->where("ap_produk.status",1);
		$this->db->order_by("ap_produk.nama_produk","DESC");
		return $this->db->get();
	}
	function produkAjaxSupplier2($q,$idToko){
		$supplier = '';
		foreach($q as $supp){
			$supplier.= "'$supp',";
		}
		$supplier = substr($supplier,0,-1);
		$this->db->select(array("ap_produk.id_produk","ap_produk_price.hpp","concat (0) as pesan"));
		$this->db->from("ap_produk_price");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_produk_price.id_produk");
		$this->db->where("ap_produk.id_brand in ($supplier)");
        $this->db->where("ap_produk_price.id_toko",$idToko);
		$this->db->where("ap_produk.status",1);
		$this->db->order_by("ap_produk.nama_produk","DESC");
		return $this->db->get();
	}

	function getIdCart($idProduk,$idUser){
		$this->db->select("id");
		$this->db->from("cc_cartpurchaseorder");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		$query = $this->db->get()->row();
		return $query->id;
	}

	function totalPeritem($idUser,$id){
		$this->db->select("(harga*qty) as total");
		$this->db->from("cc_cartpurchaseorder");
		$this->db->where("idUser",$idUser);
		$this->db->where("id",$id);
		$query = $this->db->get()->row();
		return $query->total; 
	}

	function totalCartPeruser($idUser){
		$this->db->select("SUM(harga*qty) as total");
		$this->db->from("cc_cartpurchaseorder");
		$this->db->where("idUser",$idUser);
		$this->db->group_by("cc_cartpurchaseorder.idUser");
		$query = $this->db->get()->row();
		return $query->total;
	}

	function hargaBeliProduk($idProduk,$idStore='7'){
		$this->db->select("hpp");
        $this->db->from("ap_produk_price");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_toko",$idStore);
		if ($query = $this->db->get()->row()) return $query->hpp;
		else return 0;
	}
	function hargaJualProduk($idProduk,$idStore='7'){
		$this->db->select("harga");
        $this->db->from("ap_produk_price");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_toko",$idStore);
		if ($query = $this->db->get()->row()) return $query->harga;
		else return 0;
	}

	function lastPurchased($idProduk,$idStore='7'){
		$this->db->select(array("receive_item.qty","MAX(receive_item.tanggal) as tanggal"));
        $this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive");
		$this->db->where("receive_item.sku",$idProduk);
		$this->db->where("receive_order.diterimaDi",$idStore);
		$query = $this->db->get()->row();
		return $query;
	}
	function lastSales($idProduk,$idStore='7',$tanggal){
		$tanggal = date('Y-m-d', strtotime("-31 days"));
		$this->db->select("SUM(ap_invoice_item.qty) as qty");
        $this->db->from("ap_invoice_item");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice");
		$this->db->where("ap_invoice_item.id_produk",$idProduk);
		$this->db->where("ap_invoice_number.id_toko",$idStore);
		$this->db->where("ap_invoice_item.tanggal >= '$tanggal'");
		
		$query = $this->db->get()->row();
		return $query->qty;
		
	}
	function updateHargaJualCart($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where('idUser',$idUser);
		$this->db->update("cc_cartpurchaseorder",$dataUpdate);
	}
	function viewCartPO($idUser,$idStore=7){
		$this->db->select(array("cc_cartpurchaseorder.diskon1","cc_cartpurchaseorder.diskon2","cc_cartpurchaseorder.idProduk as id_produk","ap_produk.nama_produk","ap_produk.isi","ap_produk.satuan","IF(cc_cartpurchaseorder.hargajual=0,ap_produk_price.harga,cc_cartpurchaseorder.hargajual) as hargajual","cc_cartpurchaseorder.qty","cc_cartpurchaseorder.bonus","cc_cartpurchaseorder.harga","cc_cartpurchaseorder.id","stok_store.stok","stok_store.min","stok_store.max","stok_store.last_sales","stok_store.last_receives"));
		$this->db->from("cc_cartpurchaseorder");
		$this->db->join("ap_produk","ap_produk.id_produk = cc_cartpurchaseorder.idProduk","left");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = cc_cartpurchaseorder.idProduk and ap_produk_price.id_toko='".$idStore."'","left");
		$this->db->join("stok_store","stok_store.id_produk = cc_cartpurchaseorder.idProduk and stok_store.id_store='$idStore'","LEFT");
		
		
		$this->db->where("cc_cartpurchaseorder.idUser",$idUser);
		$this->db->group_by("cc_cartpurchaseorder.idProduk");
		$this->db->order_by("cc_cartpurchaseorder.id","DESC");
		return $this->db->get();
	}

	function cekCartPO($idProduk,$idUser){
		$this->db->from("cc_cartpurchaseorder");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function currentQtyCart($idProduk,$idUser){
		$this->db->select("qty");
		$this->db->from("cc_cartpurchaseorder");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function purchase_item($no_po){
		$this->db->select(array("ap_produk.nama_produk","purchase_item.qty","purchase_item.bonus","purchase_item.qty_req","ap_produk.satuan","purchase_item.harga","(purchase_item.harga*purchase_item.qty) as total","ap_produk.id_produk"));
		$this->db->from("purchase_item");
		$this->db->join("ap_produk","ap_produk.id_produk = purchase_item.sku","left");
		$this->db->where("purchase_item.no_po",$no_po);
		$this->db->order_by("ap_produk.nama_produk");
		return $this->db->get();
	}

	function received_item($no_receive){
		$this->db->select("*");
		$this->db->from("receive_item");
		$this->db->join("ap_produk","ap_produk.id_produk = receive_item.sku","left");
		$this->db->where("no_receive",$no_receive);
		return $this->db->get();
	}

	function infoPurchase($no_po){
		$this->db->select(array("purchase_order.tanggal_po","ap_store.store","users.first_name","purchase_order.keterangan","supplier.supplier","supplier.alamat","supplier.kontak","purchase_order.ppn","purchase_order.nilai_ppn","purchase_order.alamat_pengiriman","purchase_order.tanggal_kirim","purchase_order.id_supplier","purchase_order.status","purchase_order.id_toko"));
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->join("ap_store","ap_store.id_store = purchase_order.id_toko","left");
		$this->db->join("users","users.id = purchase_order.id_pic","left");
		$this->db->where("purchase_order.no_po",$no_po);
		$this->db->group_by("purchase_order.no_po");
		$query = $this->db->get()->row();
		return $query;
	}

	function emailSupplier($idSupplier){
		$this->db->select("email");
		$this->db->from("supplier");
		$this->db->where("id_supplier",$idSupplier);
		$query = $this->db->get()->row();

		return $query->email;
	}

	function cekEmailIfExist($idSupplier){
		$this->db->select("email");
		$this->db->from("supplier");
		$this->db->where("id_supplier",$idSupplier);
		$query = $this->db->get();

		foreach($query->result() as $row){
			$email = $row->email;
		
			if($email==''){
				return 0;
			} else {
				return 1;
			}
		}
	}

	function insertPONumber($data_masuk){
		$this->db->insert("purchase_order",$data_masuk);
	}

	function insertPOItem($data_bahan){
		$this->db->insert_batch("purchase_item",$data_bahan);
	}

	function deleteCartPO($idUser){
		$this->db->delete("cc_cartpurchaseorder",array("idUser" => $idUser));
	}
	function deleteSelectedCartPO($idUser){
		$this->db->delete("cc_cartpurchaseorder",array("idUser" => $idUser,"qty" => 0));
	}

	function insertCartPO($dataCart){
		$this->db->insert("cc_cartpurchaseorder",$dataCart);
	}

	function updateQtyCart($id,$idUser,$dataUpdate){
		$this->db->where("id",$id);
		$this->db->where('idUser',$idUser);
		$this->db->update("cc_cartpurchaseorder",$dataUpdate);
	}

	function updateHargaCart($id,$idUser,$dataUpdate){
		$this->db->where("id",$id);
		$this->db->where('idUser',$idUser);
		$this->db->update("cc_cartpurchaseorder",$dataUpdate);
	}

	function hapusCart($id,$idUser){
		$this->db->delete("cc_cartpurchaseorder",array("id" => $id, "idUser" => $idUser));
	}
}