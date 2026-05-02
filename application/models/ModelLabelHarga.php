<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelLabelHarga extends CI_Model{
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
	function produkAjaxSupplier($q,$idToko){
		$this->db->select(array("ap_produk.id_produk","ap_produk_price.hpp","concat (stok_store.max - stok_store.stok) as pesan"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk","left");
        $this->db->join("ap_produk_price","ap_produk_price.id_produk = stok_store.id_produk","left");
		$this->db->where("ap_produk.id_supplier",$q);
		$this->db->where("stok_store.id_store",$idToko);
        $this->db->where("ap_produk_price.id_toko",$idToko);
		$this->db->where("stok_store.stok < stok_store.min");
		$this->db->where("ap_produk.status",1);
		return $this->db->get();
	}

	function getIdCart($idProduk,$idUser){
		$this->db->select("id");
		$this->db->from("cc_cartlabelharga");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		$query = $this->db->get()->row();
		return $query->id;
	}

	function totalPeritem($idUser,$idProduk){
		$this->db->select("(harga*qty) as total");
		$this->db->from("cc_cartlabelharga");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		$query = $this->db->get()->row();
		return $query->total; 
	}

	function totalCartPeruser($idUser){
		$this->db->select("SUM(harga*qty) as total");
		$this->db->from("cc_cartlabelharga");
		$this->db->where("idUser",$idUser);
		$this->db->group_by("cc_cartlabelharga.idUser");
		$query = $this->db->get()->row();
		return $query->total;
	}

	function hargaBeliProduk($idProduk,$idStore='7'){
		$this->db->select("harga");
        $this->db->from("ap_produk_price");
		$this->db->where("id_produk",$idProduk);
        $this->db->where("id_toko",$idStore);
		$query = $this->db->get()->row(); 
		return $query->harga;
	}

	function viewCartPO($idUser){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan","cc_cartlabelharga.qty","cc_cartlabelharga.harga","cc_cartlabelharga.id"));
		$this->db->from("cc_cartlabelharga");
		$this->db->join("ap_produk","ap_produk.id_produk = cc_cartlabelharga.idProduk");
		$this->db->where("cc_cartlabelharga.idUser",$idUser);
		$this->db->order_by("cc_cartlabelharga.id","DESC");
		return $this->db->get();
	}

	function cekCartPO($idProduk,$idUser){
		$this->db->from("cc_cartlabelharga");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function currentQtyCart($idProduk,$idUser){
		$this->db->select("qty");
		$this->db->from("cc_cartlabelharga");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	// function label_item($no_po){
	// 	$this->db->select(array("ap_produk.nama_produk","max(receive_item.tanggal) as tgl","label_item.qty","ap_produk.satuan","label_item.harga","(label_item.harga*label_item.qty) as total","ap_produk.id_produk"));
	// 	$this->db->from("label_item");
	// 	$this->db->join("ap_produk","ap_produk.id_produk = label_item.sku","left");
	// 	$this->db->join("receive_item","receive_item.sku = label_item.sku","left");
	// 	$this->db->where("label_item.no_po",$no_po);
	// 	$this->db->group_by("label_item.sku");
	// 	return $this->db->get();
	// }
	function label_item($no_po,$toko){
		$this->db->select(array("ap_produk.nama_produk","max(receive_item.tanggal) as tgl","label_item.qty","ap_produk.satuan","label_item.harga","(label_item.harga*label_item.qty) as total","ap_produk.id_produk"));
		$this->db->from("label_item");
		$this->db->join("ap_produk","ap_produk.id_produk = label_item.sku","left");
		$this->db->join("receive_item","receive_item.sku = label_item.sku","left");
		$this->db->where("label_item.no_po",$no_po);
		$this->db->group_by("label_item.sku");
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
		$this->db->select(array("label_harga.tanggal_po","label_harga.keterangan","supplier.supplier","supplier.alamat","supplier.kontak","label_harga.ppn","label_harga.nilai_ppn","label_harga.alamat_pengiriman","label_harga.tanggal_kirim","label_harga.id_supplier"));
		$this->db->from("label_harga");
		$this->db->join("supplier","supplier.id_supplier = label_harga.id_supplier","left");
		$this->db->where("label_harga.no_po",$no_po);
		$this->db->group_by("label_harga.no_po");
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
		$this->db->insert("label_harga",$data_masuk);
	}

	function insertPOItem($data_bahan){
		$this->db->insert_batch("label_item",$data_bahan);
	}

	function deleteCartPO($idUser){
		$this->db->delete("cc_cartlabelharga",array("idUser" => $idUser));
	}

	function insertCartPO($dataCart){
		$this->db->insert("cc_cartlabelharga",$dataCart);
	}

	function updateQtyCart($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where('idUser',$idUser);
		$this->db->update("cc_cartlabelharga",$dataUpdate);
	}

	function updateHargaCart($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where('idUser',$idUser);
		$this->db->update("cc_cartlabelharga",$dataUpdate);
	}

	function hapusCart($idProduk,$idUser){
		$this->db->delete("cc_cartlabelharga",array("idProduk" => $idProduk, "idUser" => $idUser));
	}
}