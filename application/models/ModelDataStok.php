<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelDataStok extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function dataStokBahanBakuDatatables($limit,$start,$query=''){
		$this->db->select(array("bahan_baku.sku","bahan_baku.nama_bahan","kategori.kategori","bahan_baku.stok","bahan_baku.harga"));
		$this->db->from("bahan_baku");
		$this->db->join("kategori","kategori.id_kategori = bahan_baku.id_kategori");
		if(!empty($query)){
			$this->db->like("bahan_baku.nama_bahan",$query);
			$this->db->or_like("bahan_baku.sku",$query);
		}
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function dataStokBahanBakuDatatablesFilter($limit,$start,$query='',$idKategori='',$stokSign='',$stokValue='',$priceSign='',$priceSignValue=''){
		$this->db->select(array("bahan_baku.sku","bahan_baku.nama_bahan","kategori.kategori","bahan_baku.stok","bahan_baku.harga"));
		$this->db->from("bahan_baku");
		$this->db->join("kategori","kategori.id_kategori = bahan_baku.id_kategori");
		
		if(!empty($idKategori)){
			$this->db->where("bahan_baku.id_kategori",$idKategori);
		}

		if(!empty($stokSign) && !empty($stokValue)){
			$this->db->where("bahan_baku.stok".$stokSign.$stokValue);
		}

		if(!empty($priceSign) && !empty($priceSignValue)){
			$this->db->where("bahan_baku.harga".$priceSign.$priceSignValue);
		}

		if(!empty($query)){
			$this->db->like("bahan_baku.nama_bahan",$query);
			$this->db->or_like("bahan_baku.sku",$query);
		}
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function dataStokFg($limit,$start,$query=''){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","ap_produk.stok","ap_produk.hpp as harga"));
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		//$this->db->where("ap_produk.status",1);
		//$this->db->or_where("ap_produk.status",0);

		if(!empty($query)){
			$this->db->like("ap_produk.nama_produk",$query);
			$this->db->or_like("ap_produk.id_produk",$query);
		}

		$this->db->limit($limit,$start);
		return $this->db->get();
	}	

	function dataStokFgFilter($limit,$start,$query='',$idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","ap_produk.stok","ap_produk.hpp as harga"));
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");

		if(!empty($query)){
			$this->db->like("ap_produk.nama_produk",$query);
			$this->db->or_like("ap_produk.id_produk",$query);
		}

		if(!empty($idKategori)){
			$this->db->where("ap_produk.id_kategori",$idKategori);
		}

		if(!empty($subkategori)){
			$this->db->where("ap_produk.id_subkategori",$subkategori);
		}

		if(!empty($subSubKategori)){
			$this->db->where("ap_produk.id_subkategori_2",$subSubKategori);
		}

		if(!empty($stokSign) && !empty($stokValue)){
			$this->db->where("ap_produk.stok".$stokSign.$stokValue);
		}

		if(!empty($priceSign) && !empty($priceSignValue)){
			$this->db->where("ap_produk.hpp".$priceSign.$priceSignValue);
		}

		//$this->db->where("ap_produk.status",1);
		//$this->db->or_where("ap_produk.status",0);
		$this->db->limit($limit,$start);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}	

	function totalProdukActive(){
		$this->db->from("ap_produk");
		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		return $this->db->count_all_results();
	}

	function totalProdukActiveMaterial(){
		$this->db->from("bahan_baku");
		$this->db->where("del",1);
		$this->db->where("status",1);
		return $this->db->count_all_results();
	}

	function totalProdukActiveMaterialFilter($idKategori='',$stokSign='',$stokValue='',$priceSign='',$priceSignValue=''){
		$this->db->from("bahan_baku");

		if(!empty($idKategori)){
			$this->db->where("bahan_baku.id_kategori",$idKategori);
		}

		if(!empty($stokSign) && !empty($stokValue)){
			$this->db->where("bahan_baku.stok".$stokSign.$stokValue);
		}

		if(!empty($priceSign) && !empty($priceSignValue)){
			$this->db->where("bahan_baku.harga".$priceSign.$priceSignValue);
		}

		$this->db->where("del",1);
		$this->db->where("status",1);
		return $this->db->count_all_results();
	}

	function totalProdukActiveFilter($idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue){
		$this->db->from("ap_produk");

		if(!empty($idKategori)){
			$this->db->where("ap_produk.id_kategori",$idKategori);
		}

		if(!empty($subkategori)){
			$this->db->where("ap_produk.id_subkategori",$subkategori);
		}

		if(!empty($subSubKategori)){
			$this->db->where("ap_produk.id_subkategori_2",$subSubKategori);
		}

		if(!empty($stokSign) && !empty($stokValue)){
			$this->db->where("ap_produk.stok".$stokSign.$stokValue);
		}

		if(!empty($priceSign) && !empty($priceSignValue)){
			$this->db->where("ap_produk.hpp".$priceSign.$priceSignValue);
		}

		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		return $this->db->count_all_results();
	}

	function dataStokFgAll(){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","ap_produk.stok","ap_produk.hpp as harga"));
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}	

	function dataStokFgFilterExport($idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","ap_produk.stok","ap_produk.hpp as harga"));
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");

		if(!empty($idKategori)){
			$this->db->where("ap_produk.id_kategori",$idKategori);
		}

		if(!empty($subkategori)){
			$this->db->where("ap_produk.id_subkategori",$subkategori);
		}

		if(!empty($subSubKategori)){
			$this->db->where("ap_produk.id_subkategori_2",$subSubKategori);
		}

		if(!empty($stokSign) && !empty($stokValue)){
			$this->db->where("ap_produk.stok".$stokSign.$stokValue);
		}

		if(!empty($priceSign) && !empty($priceSignValue)){
			$this->db->where("ap_produk.hpp".$priceSign.$priceSignValue);
		}

		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function dataStokToko($limit,$start,$search='',$idToko,$idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue,$idStand,$salePriceSign,$salePriceValue,$id_supplier,$id_brand,$stokMinim){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.id_brand","stok_store.stok","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","stok_store.hpp as harga_beli","ap_stand.stand"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		$this->db->join("ap_stand","ap_stand.id_stand = ap_produk.tempat","left");
		//$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk");

		if(!empty($search)){
			$this->db->where("concat_ws('',ap_produk.nama_produk,ap_produk.id_produk) like '%$search%'");
			//$this->db->or_like("ap_produk.id_produk",$search);
		}

		if(!empty($id_supplier)){
			$this->db->where("ap_produk.id_supplier",$id_supplier);
		}

		if(!empty($id_brand)){
			$this->db->where("ap_produk.id_brand",$id_brand);
		}

		if(!empty($idKategori)){
			$this->db->where("ap_produk.id_kategori",$idKategori);
		}

		if(!empty($subkategori)){
			$this->db->where("ap_produk.id_subkategori",$subkategori);
		}

		if(!empty($subSubKategori)){
			$this->db->where("ap_produk.id_subkategori_2",$subSubKategori);
		}

		if(!empty($idStand)){
			$this->db->where("ap_produk.tempat",$idStand);
		}

		if(!empty($stokSign) && !empty($stokValue)){
			$this->db->where("stok_store.stok".$stokSign.$stokValue);
		}

		if(!empty($stokMinim)){
			$this->db->where("stok_store.stok < stok_store.min");
			$this->db->where("stok_store.min>0");
		}

		if(!empty($priceSign) && !empty($priceSignValue)){
			$this->db->where("stok_store.hpp".$priceSign.$priceSignValue);
		}

		if(!empty($salePriceSign) && !empty($salePriceValue)){
			$this->db->where("stok_store.harga".$salePriceSign.$salePriceValue);
		}

		$this->db->where("stok_store.id_store",$idToko);
		//$this->db->where("ap_produk_price.id_toko",$idToko);
		$this->db->limit($limit,$start);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function dataStokTokoFilterExport($idToko,$idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue,$idStand,$salePriceSign,$salePriceValue,$id_supplier,$id_brand,$stokMinim){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","stok_store.stok","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","stok_store.hpp as harga_beli","ap_stand.stand"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		$this->db->join("ap_stand","ap_stand.id_stand = ap_produk.tempat","left");
		//$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk");

		if(!empty($id_brand)){
			$this->db->where("ap_produk.id_brand",$id_brand);
		}

		if(!empty($id_supplier)){
			$this->db->where("ap_produk.id_supplier",$id_supplier);
		}

		if(!empty($idKategori)){
			$this->db->where("ap_produk.id_kategori",$idKategori);
		}

		if(!empty($subkategori)){
			$this->db->where("ap_produk.id_subkategori",$subkategori);
		}

		if(!empty($subSubKategori)){
			$this->db->where("ap_produk.id_subkategori_2",$subSubKategori);
		}

		if(!empty($idStand)){
			$this->db->where("ap_produk.tempat",$idStand);
		}

		if(!empty($stokSign) && !empty($stokValue)){
			$this->db->where("stok_store.stok".$stokSign.$stokValue);
		}

		if(!empty($stokMinim)){
			$this->db->where("stok_store.stok < stok_store.min");
			$this->db->where("stok_store.min>0");
		}

		if(!empty($priceSign) && !empty($priceSignValue)){
			$this->db->where("ap_produk.hpp".$priceSign.$priceSignValue);
		}

		if(!empty($salePriceSign) && !empty($salePriceValue)){
			$this->db->where("stok_store.harga".$salePriceSign.$salePriceValue);
		}

		$this->db->where("stok_store.id_store",$idToko);
		//$this->db->where("ap_produk_price.id_toko",$idToko);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function totalProdukPromotion($idToko,$idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue,$idStand,$salePriceSign,$salePriceValue,$id_supplier,$id_brand,$stokMinim){
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		//$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk");	
		$this->db->where("stok_store.id_store",$idToko);

		if(!empty($id_supplier)){
			$this->db->where("ap_produk.id_supplier",$id_supplier);
		}

		if(!empty($id_brand)){
			$this->db->where("ap_produk.id_brand",$id_brand);
		}

		if(!empty($idKategori)){
			$this->db->where("ap_produk.id_kategori",$idKategori);
		}

		if(!empty($subkategori)){
			$this->db->where("ap_produk.id_subkategori",$subkategori);
		}

		if(!empty($subSubKategori)){
			$this->db->where("ap_produk.id_subkategori_2",$subSubKategori);
		}

		if(!empty($idStand)){
			$this->db->where("ap_produk.tempat",$idStand);
		}

		if(!empty($stokSign) && !empty($stokValue)){
			$this->db->where("stok_store.stok".$stokSign.$stokValue);
		}

		if(!empty($stokMinim)){
			$this->db->where("stok_store.stok < stok_store.min");
			$this->db->where("stok_store.min>0");
		}

		if(!empty($priceSign) && !empty($priceSignValue)){
			$this->db->where("ap_produk.hpp".$priceSign.$priceSignValue);
		}

		if(!empty($salePriceSign) && !empty($salePriceValue)){
			$this->db->where("stok_store.harga".$salePriceSign.$salePriceValue);
		}

		$this->db->where("stok_store.id_store",$idToko);
		//$this->db->where("ap_produk_price.id_toko",$idToko);
		//$this->db->group_by("ap_produk_price.id_produk");
		return $this->db->count_all_results();
	}

	function totalProdukInventori($idToko,$idKategori,$subkategori,$subSubKategori,$stokSign,$stokValue,$priceSign,$priceSignValue,$idStand,$salePriceSign,$salePriceValue,$id_supplier,$id_brand,$stokMinim){

		$this->db->select(array("SUM(stok_store.stok*stok_store.hpp) as nilai","SUM(stok_store.stok*stok_store.harga) as nilaiJual"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");	
		$this->db->where("stok_store.id_store",$idToko);

		if(!empty($id_supplier)){
			$this->db->where("ap_produk.id_supplier",$id_supplier);
		}
		if(!empty($id_brand)){
			$this->db->where("ap_produk.id_brand",$id_brand);
		}

		if(!empty($idKategori)){
			$this->db->where("ap_produk.id_kategori",$idKategori);
		}

		if(!empty($subkategori)){
			$this->db->where("ap_produk.id_subkategori",$subkategori);
		}

		if(!empty($subSubKategori)){
			$this->db->where("ap_produk.id_subkategori_2",$subSubKategori);
		}

		if(!empty($idStand)){
			$this->db->where("ap_produk.tempat",$idStand);
		}

		if(!empty($stokSign) && !empty($stokValue)){
			$this->db->where("stok_store.stok".$stokSign.$stokValue);
		}

		if(!empty($stokMinim)){
			$this->db->where("stok_store.stok < stok_store.min");
			$this->db->where("stok_store.min>0");
		}

		if(!empty($priceSign) && !empty($priceSignValue)){
			$this->db->where("ap_produk.hpp".$priceSign.$priceSignValue);
		}

		if(!empty($salePriceSign) && !empty($salePriceValue)){
			$this->db->where("stok_store.harga".$salePriceSign.$salePriceValue);
		}

		//$this->db->where("stok_store.id_store",$idToko);
		//$this->db->where("ap_produk_price.id_toko",$idToko);
		$this->db->where("ap_produk.status",1);
		//$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function dataStokBahanBakuActive(){
		$this->db->select(array("bahan_baku.sku","bahan_baku.nama_bahan","kategori.kategori","bahan_baku.stok","bahan_baku.harga"));
		$this->db->from("bahan_baku");
		$this->db->join("kategori","kategori.id_kategori = bahan_baku.id_kategori");
		$this->db->where("bahan_baku.status",1);
		$this->db->where("bahan_baku.del",1);
		return $this->db->get();	
	}

	function dataStokBahanBakuActiveFilter($idKategori,$stokSign,$stokValue,$priceSign,$priceSignValue){
		$this->db->select(array("bahan_baku.sku","bahan_baku.nama_bahan","kategori.kategori","bahan_baku.stok","bahan_baku.harga"));
		$this->db->from("bahan_baku");
		$this->db->join("kategori","kategori.id_kategori = bahan_baku.id_kategori");
		if(!empty($idKategori)){
			$this->db->where("bahan_baku.id_kategori",$idKategori);
		}

		if(!empty($stokSign) && !empty($stokValue)){
			$this->db->where("bahan_baku.stok".$stokSign.$stokValue);
		}

		if(!empty($priceSign) && !empty($priceSignValue)){
			$this->db->where("bahan_baku.harga".$priceSign.$priceSignValue);
		}
		$this->db->where("bahan_baku.status",1);
		$this->db->where("bahan_baku.del",1);
		return $this->db->get();
	}


}