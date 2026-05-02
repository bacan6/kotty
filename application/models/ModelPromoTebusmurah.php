<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class modelPromoTebusmurah extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function delPromoSupplier($id){
		$this->db->where("no_promo", $id);
        $this->db->delete("promo_tebusmurah");
	}

	function listProdukPromo($start,$end,$idStore,$tempat,$kategori,$subkategori,$subkategori2,$id_produk){
		$this->db->select(array("ap_produk_tebusmurah_rules.id","ap_produk_tebusmurah_rules.discount","ap_produk_tebusmurah_rules.max_qty","ap_produk_tebusmurah_rules.date_start","ap_produk_tebusmurah_rules.date_end","IF(ap_produk_tebusmurah_rules.setJam=1,concat(ap_produk_tebusmurah_rules.JamMulai,' s/d ',ap_produk_tebusmurah_rules.JamSelesai),'-') as Jam","IF(ap_produk_tebusmurah_rules.setHari=1,ap_produk_tebusmurah_rules.HariID,'9') as Hari","ap_produk_tebusmurah_rules.no_promo","ap_produk.nama_produk","ap_produk.id_produk"));
		$this->db->from("ap_produk_tebusmurah_rules");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_produk_tebusmurah_rules.id_produk");

		// $this->db->join("brand","brand.id_brand = ap_produk.id_brand");
		//$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk_tebusmurah_rules.id_produk and ap_produk_price.id_toko=ap_produk_tebusmurah_rules.id_toko");
		//$this->db->join("stok_store","stok_store.id_produk = ap_produk_tebusmurah_rules.id_produk and stok_store.id_store=ap_produk_tebusmurah_rules.id_toko");
		$this->db->where("(ap_produk_tebusmurah_rules.date_start BETWEEN  '$start' AND '$end') and (ap_produk_tebusmurah_rules.date_end BETWEEN '$start' AND '$end')");
		$this->db->where("ap_produk_tebusmurah_rules.id_toko",$idStore);
		

		if(!empty($tempat)){
			$this->db->where("ap_produk.tempat",$tempat);
		}

		if(!empty($kategori)){
			$this->db->where("ap_produk.id_kategori",$kategori);
		}

		if(!empty($subkategori)){
			$this->db->where("id_subkategori",$subkategori);
		}

		if(!empty($subkategori2)){
			$this->db->where("id_subkategori_2",$subkategori2);
		}
        
		if(!empty($id_produk)){
			$this->db->where("ap_produk_tebusmurah_rules.id_produk",$id_produk);
			$this->db->where("ap_produk.id_produk",$id_produk);
		}
		$this->db->group_by("ap_produk_tebusmurah_rules.id");
		$this->db->order_by("ap_produk.nama_produk");
		
		return $this->db->get()->result();
	}

	function showHari($id){
		if($id=='0') echo "Minggu ";
		else if($id==1) echo "Senin ";
		else if($id==2) echo "Selasa ";
		else if($id==3) echo "Rabu ";
		else if($id==4) echo "Kamis ";
		else if($id==5) echo "Jumat ";
		else if($id==6) echo "Sabtu ";
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

	function totalPromoSupplier(){
		$this->db->from("promo_tebusmurah");
		return $this->db->count_all_results();
	}

	function viewPOProduk($limit,$start,$search='',$idUser='',$idStore=''){
		$dataSelect = array(
								"promo_tebusmurah.no_promo","promo_tebusmurah.tanggal_buat","promo_tebusmurah.tanggalMulai","promo_tebusmurah.tanggalSelesai","users.first_name","promo_tebusmurah.status"
						   );

		$this->db->select($dataSelect);
		$this->db->from("promo_tebusmurah");
		$this->db->join("users","users.id = promo_tebusmurah.id_pic");

		if(!empty($search)){
			$this->db->like("promo_tebusmurah.no_promo",$search);
		}
        

		$this->db->limit($limit,$start);
		$this->db->order_by("promo_tebusmurah.tanggal_buat","DESC");
		$this->db->order_by("promo_tebusmurah.no_promo","DESC");
        
        
        if ($idUser!=1 && $idUser!=101 && $idUser!=150){
            $this->db->where("promo_tebusmurah.id_toko",$idStore);
        }
        
        
		$this->db->where('type',0);
		return $this->db->get();
	}

	function setToDiskon($sku,$data_update){
		$this->db->where("id_produk",$sku);
		$this->db->update("ap_produk",$data_update);
		//$affect = $this->db->affected_rows();
		//return $affect;
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
	function produkAjaxSupplier($q,$idToko=13){
		$this->db->select(array("ap_produk.id_produk","ap_produk_price.hpp","concat (0) as pesan","stok_store.stok"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk","left");
        $this->db->join("ap_produk_price","ap_produk_price.id_produk = stok_store.id_produk","left");
		$this->db->where("ap_produk.id_brand",$q);
		$this->db->where("stok_store.id_store",$idToko);
        $this->db->where("ap_produk_price.id_toko",$idToko);
		//$this->db->where("stok_store.stok < stok_store.min");
		$this->db->where("ap_produk.status",1);
		$this->db->order_by("ap_produk.nama_produk","DESC");
		return $this->db->get();
	}

	function getIdCart($idProduk,$idUser){
		$this->db->select("id");
		$this->db->from("cc_carttebusmurah");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		$query = $this->db->get()->row();
		return $query->id;
	}

	function totalPeritem($idUser,$idProduk){
		$this->db->select("(harga*qty) as total");
		$this->db->from("cc_carttebusmurah");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		$query = $this->db->get()->row();
		return $query->total; 
	}

	function totalCartPeruser($idUser){
		$this->db->select("SUM(harga*qty) as total");
		$this->db->from("cc_carttebusmurah");
		$this->db->where("idUser",$idUser);
		$this->db->group_by("cc_carttebusmurah.idUser");
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

	function viewCartPO($idUser,$idStore=7){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk", "ap_produk_price.hpp","ap_produk_price.harga as harga_jual", "cc_carttebusmurah.nominal_belanja","cc_carttebusmurah.qty","cc_carttebusmurah.harga","cc_carttebusmurah.id","cc_carttebusmurah.quota","stok_store.stok"));
		$this->db->from("cc_carttebusmurah");
		$this->db->join("ap_produk","ap_produk.id_produk = cc_carttebusmurah.idProduk");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = cc_carttebusmurah.idProduk and id_toko='".$idStore."'");
		$this->db->join("stok_store","stok_store.id_produk = cc_carttebusmurah.idProduk");
		$this->db->where("cc_carttebusmurah.idUser",$idUser);
		$this->db->where("stok_store.id_store",$idStore);
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("cc_carttebusmurah.id","DESC");
		return $this->db->get();
	}

	function cekCartPO($idProduk,$idUser){
		$this->db->from("cc_carttebusmurah");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function currentQtyCart($idProduk,$idUser){
		$this->db->select("qty");
		$this->db->from("cc_carttebusmurah");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function purchase_item($no_po){
		$this->db->select(array("ap_produk.nama_produk","ap_produk_tebusmurah_rules.nominal_belanja","ap_produk_tebusmurah_rules.max_qty","ap_produk_tebusmurah_rules.quota","ap_produk.satuan","ap_produk_tebusmurah_rules.discount","ap_produk_price.harga","(ap_produk_price.harga-ap_produk_tebusmurah_rules.discount) as harga_promo","ap_produk_tebusmurah_rules.discount as total","ap_produk.id_produk"));
		$this->db->from("ap_produk_tebusmurah_rules");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_produk_tebusmurah_rules.id_produk","left");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk_tebusmurah_rules.id_produk and ap_produk_price.id_toko=ap_produk_tebusmurah_rules.id_toko");
		$this->db->where("ap_produk_tebusmurah_rules.no_promo",$no_po);
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
		$this->db->select(array("promo_tebusmurah.tanggal_buat","promo_tebusmurah.keterangan","promo_tebusmurah.tanggalMulai","promo_tebusmurah.tanggalSelesai","promo_tebusmurah.setJam","promo_tebusmurah.JamMulai","promo_tebusmurah.JamSelesai"));
		$this->db->from("promo_tebusmurah");
		$this->db->where("promo_tebusmurah.no_promo",$no_po);
		$this->db->group_by("promo_tebusmurah.no_promo");
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
		$this->db->insert("promo_tebusmurah",$data_masuk);
	}

	function insertPOItem($data_bahan){
		$this->db->insert_batch("ap_produk_tebusmurah_rules",$data_bahan);
	}

	function deleteCartPO($idUser){
		$this->db->delete("cc_carttebusmurah",array("idUser" => $idUser));
	}

	function insertCartPO($dataCart){
		$this->db->insert("cc_carttebusmurah",$dataCart);
	}

	function updateQtyCart($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where('idUser',$idUser);
		$this->db->update("cc_carttebusmurah",$dataUpdate);
	}

	function updateHargaCart($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where('idUser',$idUser);
		$this->db->update("cc_carttebusmurah",$dataUpdate);
	}

	function hapusCart($idProduk,$idUser){
		$this->db->delete("cc_carttebusmurah",array("idProduk" => $idProduk, "idUser" => $idUser));
	}
}