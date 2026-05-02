<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelBahanMasukMaterial extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function cekCart($sku,$no_po){
		$this->db->select("sku");
		$this->db->from("cc_cart_receive_item");
		$this->db->where("sku",$sku);
		$this->db->where("no_po",$no_po);
		$query = $this->db->get()->row();
		return $query->sku;
	}

	function hapusCartImportPDT($no_po){
		$this->db->where("no_po",$no_po);
        $this->db->delete("cc_cart_receive_item");
	}

	function updateStok($idStore,$data_update,$idProduk){
		$this->db->where("id_store",$idStore);
		$this->db->where("id_produk = '$idProduk'");
		$this->db->update("stok_store",$data_update);
	}

	function insertBatchDataStok($data_insert){
		$this->db->insert_batch("stok_store",$data_insert);
	}
	function updateBatchHarga($data_update,$store='6'){
		$this->db->where("id_toko",$store);
		$this->db->update_batch("ap_produk_price",$data_update,"id_produk");
	}
	function updateBatchHargaStok($data_update,$store='6'){
		$this->db->where("id_store",$store);
		$this->db->update_batch("stok_store",$data_update,"id_produk");
	}
	function updateBatchDataStok($data_update,$store='6'){
		$this->db->where("id_store",$store);
		$this->db->update_batch("stok_store",$data_update,"id_produk");
	}
	function totalPOMaterial(){
		$this->db->from("purchase_order");
		$this->db->where("type",1);
		return $this->db->count_all_results();
	}

	function hargaBeliProduk($idProduk,$idStore='7'){
		$this->db->select("hpp");
        $this->db->from("ap_produk_price");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_toko",$idStore);
		if ($query = $this->db->get()->row()) return $query->hpp;
		else return 0;
	}
	function produkAjax($q,$id_brand){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk"));
		$this->db->from("ap_produk");
		$this->db->group_start();
		$this->db->like("ap_produk.id_produk",$q);
		$this->db->or_like("ap_produk.nama_produk",$q);	
		$this->db->group_end();
		$this->db->group_start();
		$this->db->where("ap_produk.status",1);
		if (!empty($id_brand)){
			$brand = '';
			foreach($id_brand as $w){
				$brand .= $w.',';
			}
			$brand = substr($brand,0,-1);
			$this->db->where("ap_produk.id_brand in ($brand)");
		}
		//$this->db->or_where("ap_produk.status",0);
		$this->db->group_end();
		return $this->db->get();
	}

	function totalPOMaterialFilter($tanggalPO='',$tanggalKirim='',$supplier='',$status=''){
		$this->db->from("purchase_order");
		if(!empty($tanggalPO)){
			$this->db->where("purchase_order.tanggal_po",$tanggalPO);
		}

		if(!empty($tanggalKirim)){
			$this->db->where("purchase_order.tanggal_kirim",$tanggalKirim);
		}

		if(!empty($supplier)){
			$this->db->where("purchase_order.id_supplier",$supplier);
		}

		if(!empty($status)){
			$this->db->where("purchase_order.status",$status);
		}
		$this->db->where("type",1);
		return $this->db->count_all_results();
	}

	function totalPOProduk(){
		$this->db->from("purchase_order");
		$this->db->where("type",0);
		$this->db->where("status != 0");
		return $this->db->count_all_results();
	}

	function totalPOProdukReq(){
		$this->db->from("purchase_order");
		$this->db->where("type",0);
		$this->db->where("status",0);
		return $this->db->count_all_results();
	}

	function totalPOProdukFilter($status_receive,$tanggalPO='',$tanggalKirim='',$supplier='',$store='',$status=''){
		$this->db->from("purchase_order");

		if(!empty($tanggalPO)){
			$this->db->where("purchase_order.tanggal_po",$tanggalPO);
		}

		if(!empty($tanggalKirim)){
			$this->db->where("purchase_order.tanggal_kirim",$tanggalKirim);
		}

		if(!empty($supplier)){
			$this->db->where("purchase_order.id_supplier",$supplier);
		}

		if(!empty($store)){
			$this->db->where("purchase_order.id_toko",$store);
		}

		if(!empty($status)){
			$this->db->where("purchase_order.status",$status);
		}
		if(!empty($status_receive)){
			$this->db->where("purchase_order.status_receive",$status_receive);
		}
		$this->db->where("type",0);
		$this->db->where("status != 0");
		return $this->db->count_all_results();
	}

	function totalPOProdukFilterReq($tanggalPO='',$tanggalKirim='',$supplier='',$status=''){
		$this->db->from("purchase_order");

		if(!empty($tanggalPO)){
			$this->db->where("purchase_order.tanggal_po",$tanggalPO);
		}

		if(!empty($tanggalKirim)){
			$this->db->where("purchase_order.tanggal_kirim",$tanggalKirim);
		}

		if(!empty($supplier)){
			$this->db->where("purchase_order.id_supplier",$supplier);
		}

		if(!empty($status)){
			$this->db->where("purchase_order.status",$status);
		}
		$this->db->where("type",0);
		$this->db->where("status",0);
		return $this->db->count_all_results();
	}
    
    
    function totalLHProduk(){
		$this->db->from("label_harga");
		$this->db->where("type",0);
		return $this->db->count_all_results();
	}

	function totalLHProdukFilter($tanggalPO='',$tanggalKirim='',$supplier='',$status=''){
		$this->db->from("label_harga");

		if(!empty($tanggalPO)){
			$this->db->where("label_harga.tanggal_po",$tanggalPO);
		}

		if(!empty($tanggalKirim)){
			$this->db->where("label_harga.tanggal_kirim",$tanggalKirim);
		}

		if(!empty($supplier)){
			$this->db->where("label_harga.id_supplier",$supplier);
		}

		if(!empty($status)){
			$this->db->where("label_harga.status",$status);
		}
		$this->db->where("type",0);
		return $this->db->count_all_results();
	}

	function totalExProduk(){
		$this->db->from("expired_product");
		$this->db->where("type",0);
		return $this->db->count_all_results();
	}

	function totalSOPeritemProduk(){
		$this->db->from("so_peritem");
		$this->db->where("type",0);
		return $this->db->count_all_results();
	}

	

	function totalExProdukFilter($tanggalPO='',$tanggalKirim='',$supplier='',$status=''){
		$this->db->from("expired_product");

		if(!empty($tanggalPO)){
			$this->db->where("expired_product.tanggal_po",$tanggalPO);
		}

		if(!empty($tanggalKirim)){
			$this->db->where("expired_product.tanggal_kirim",$tanggalKirim);
		}

		if(!empty($supplier)){
			$this->db->where("expired_product.id_supplier",$supplier);
		}

		if(!empty($status)){
			$this->db->where("expired_product.status",$status);
		}
		$this->db->where("type",0);
		return $this->db->count_all_results();
	}

	function viewPOMaterial($limit,$start,$search=''){
		$dataSelect = array(
								"purchase_order.no_po","purchase_order.tanggal_po","purchase_order.tanggal_kirim","supplier.supplier","users.first_name","purchase_order.status"
						   );

		$this->db->select($dataSelect);
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier");
		$this->db->join("users","users.id = purchase_order.id_pic");

		if(!empty($search)){
			$this->db->like("purchase_order.no_po",$search);
		}

		$this->db->limit($limit,$start);
		$this->db->order_by("purchase_order.tanggal_po","DESC");
		$this->db->order_by("purchase_order.no_po","DESC");
		$this->db->where('type',1);
		return $this->db->get();
	}

	function viewPOMaterialFilter($limit,$start,$search='',$tanggalPO='',$tanggalKirim='',$supplier='',$status=''){
		$dataSelect = array(
								"purchase_order.no_po","purchase_order.tanggal_po","purchase_order.tanggal_kirim","supplier.supplier","users.first_name","purchase_order.status"
						   );

		$this->db->select($dataSelect);
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier");
		$this->db->join("users","users.id = purchase_order.id_pic");

		if(!empty($search)){
			$this->db->like("purchase_order.no_po",$search);
		}

		if(!empty($tanggalPO)){
			$this->db->where("purchase_order.tanggal_po",$tanggalPO);
		}

		if(!empty($tanggalKirim)){
			$this->db->where("purchase_order.tanggal_kirim",$tanggalKirim);
		}

		if(!empty($supplier)){
			$this->db->where("purchase_order.id_supplier",$supplier);
		}

		if(!empty($status)){
			$this->db->where("purchase_order.status",$status);
		}

		$this->db->where('type',1);

		$this->db->limit($limit,$start);
		$this->db->order_by("purchase_order.tanggal_po","DESC");
		$this->db->order_by("purchase_order.no_po","DESC");
		return $this->db->get();
	}

	function viewPOProduk($limit,$start,$search='',$idUser='',$idStore=''){
		$dataSelect = array(
								"purchase_order.no_po","purchase_order.tanggal_po","purchase_order.tanggal_kirim","supplier.supplier","users.first_name","purchase_order.status"
								,"brand.brand","ap_store.store"
						   );

		// $dataSelect = array(
		// 						"purchase_order.no_po","purchase_order.tanggal_po","purchase_order.tanggal_kirim","supplier.supplier","users.first_name","purchase_order.status"
		// 						,"ap_store.store"
		// 				   );

		$this->db->select($dataSelect);
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->join("ap_store","ap_store.id_store = purchase_order.id_toko");
		$this->db->join("purchase_item","purchase_item.no_po = purchase_order.no_po");
		$this->db->join("ap_produk","ap_produk.id_produk = purchase_item.sku");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");
		$this->db->join("users","users.id = purchase_order.id_pic");

		if(!empty($search)){
			$this->db->like("purchase_order.no_po",$search);
			$this->db->or_like("brand.brand",$search);
		}
        
		$this->db->group_by("purchase_order.no_po");
		$this->db->limit($limit,$start);
		$this->db->order_by("purchase_order.tanggal_po","DESC");
		$this->db->order_by("purchase_order.no_po","DESC");
        
        
        if ($idUser!=1 && $idUser!=101 && $idUser!=150 && $idUser!=162){
            $this->db->where("purchase_order.id_toko",$idStore);
        }
		$this->db->where("purchase_order.id_toko=users.toko");
        
        
		$this->db->where('purchase_order.type',0);
		//$this->db->where('purchase_order.status !=0');
		return $this->db->get();
	}
	function viewPOProdukReceive($limit,$start,$search='',$idUser='',$idStore=''){
		$dataSelect = array(
								"purchase_order.no_po","purchase_order.tanggal_po","purchase_order.tanggal_kirim","supplier.supplier","users.first_name","purchase_order.status"
								,"brand.brand","ap_store.store"
						   );

		// $dataSelect = array(
		// 						"purchase_order.no_po","purchase_order.tanggal_po","purchase_order.tanggal_kirim","supplier.supplier","users.first_name","purchase_order.status"
		// 						,"ap_store.store"
		// 				   );

		$this->db->select($dataSelect);
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->join("ap_store","ap_store.id_store = purchase_order.id_toko");
		$this->db->join("purchase_item","purchase_item.no_po = purchase_order.no_po");
		$this->db->join("receive_order","receive_order.no_po = purchase_order.no_po","left");
		$this->db->join("ap_produk","ap_produk.id_produk = purchase_item.sku");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");
		$this->db->join("users","users.id = purchase_order.id_pic");

		if(!empty($search)){
			$this->db->group_start();
			$this->db->like("purchase_order.no_po",$search);
			$this->db->or_like("receive_order.no_receive",$search);
			$this->db->or_like("brand.brand",$search);
			$this->db->group_end();
		}
        
		$this->db->group_by("purchase_order.no_po");
		$this->db->limit($limit,$start);
		//$this->db->order_by("purchase_order.tanggal_supplier","DESC");
		$this->db->order_by("purchase_order.tanggal_po","DESC");
		$this->db->order_by("purchase_order.no_po","DESC");
        
        
        if ($idUser!=1 && $idUser!=2){
            $this->db->where("purchase_order.id_toko",$idStore);
        }
		//$this->db->where("purchase_order.id_toko=users.toko");
        
        
		$this->db->where('purchase_order.type',0);
		//$this->db->where('purchase_order.status !=0');
		return $this->db->get();
	}
	function viewPOImportPDT($idUser='',$idStore=''){
		$dataSelect = array(
								"purchase_order.no_po","supplier.supplier","ap_store.store"
						   );

		$this->db->select($dataSelect);
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->join("ap_store","ap_store.id_store = purchase_order.id_toko");
		
        
		
        
        
        if ($idUser!=1 && $idUser!=101 && $idUser!=150 && $idUser!=162){
            $this->db->where("purchase_order.id_toko",$idStore);
        }
        
		$this->db->where('purchase_order.status',1);
        
		$this->db->where('purchase_order.type',0);
		//$this->db->where('purchase_order.status !=0');

		$this->db->group_by("purchase_order.no_po");
		$this->db->order_by("purchase_order.tanggal_po","DESC");
		$this->db->order_by("purchase_order.no_po","DESC");
		return $this->db->get();
	}
	function viewPOProdukReq($limit,$start,$search='',$idUser='',$idStore=''){
		$dataSelect = array(
								"purchase_order.no_po","purchase_order.tanggal_po","purchase_order.tanggal_kirim","supplier.supplier","users.first_name","purchase_order.status"
								,"brand.brand","ap_store.store"
						   );

		$this->db->select($dataSelect);
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->join("ap_store","ap_store.id_store = purchase_order.id_toko","left");
		$this->db->join("purchase_item","purchase_item.no_po = purchase_order.no_po","left");
		$this->db->join("ap_produk","ap_produk.id_produk = purchase_item.sku","left");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left outer");
		$this->db->join("users","users.id = purchase_order.id_pic","left");

		if(!empty($search)){
			$this->db->like("purchase_order.no_po",$search);
			$this->db->or_like("brand.brand",$search);
		}
        $this->db->where('purchase_order.type',0);
		$this->db->where('purchase_order.status',0);
		$this->db->group_by("purchase_order.no_po");
		$this->db->limit($limit,$start);
		$this->db->order_by("purchase_order.tanggal_po","DESC");
		$this->db->order_by("purchase_order.no_po","DESC");
		return $this->db->get();
	}
    
    function viewLHProduk($limit,$start,$search='',$idUser='',$idStore=''){
		$dataSelect = array(
								"label_harga.no_po","label_harga.tanggal_po","label_harga.tanggal_kirim","users.first_name","label_harga.status"
						   );

		$this->db->select($dataSelect);
		$this->db->from("label_harga");
		$this->db->join("users","users.id = label_harga.id_pic");

		if(!empty($search)){
			$this->db->like("label_harga.no_po",$search);
		}
        

		$this->db->limit($limit,$start);
		$this->db->order_by("label_harga.tanggal_po","DESC");
		$this->db->order_by("label_harga.no_po","DESC");
        
        
        if ($idUser!=1 && $idUser!=101 && $idUser!=150 && $idUser!=162){
            $this->db->where("label_harga.id_toko",$idStore);
        }
        
        
		$this->db->where('type',0);
		return $this->db->get();
	}

	function viewExProduk($limit,$start,$search='',$idUser='',$idStore=''){
		$dataSelect = array(
								"expired_product.no_po","expired_product.tanggal_po","expired_product.tanggal_kirim","users.first_name","ap_store.store","expired_product.status","expired_product.keterangan"
						   );

		$this->db->select($dataSelect);
		$this->db->from("expired_product");
		$this->db->join("users","users.id = expired_product.id_pic");
		$this->db->join("ap_store","ap_store.id_store = expired_product.id_toko");

		if(!empty($search)){
			$this->db->like("expired_product.no_po",$search);
		}
        

		$this->db->limit($limit,$start);
		$this->db->order_by("expired_product.tanggal_po","DESC");
		$this->db->order_by("expired_product.no_po","DESC");
        
        
        if ($idUser!=1 && $idUser!=22 && $idUser!=51 && $idUser!=41){
            $this->db->where("expired_product.id_toko",$idStore);
        }
        
        
		$this->db->where('type',0);
		return $this->db->get();
	}

	function viewSOItemProduk($limit,$start,$search='',$idUser='',$idStore=''){
		$dataSelect = array(
								"brand.brand","so_peritem.no_so","so_peritem.tanggal_so","so_peritem.tanggal_kirim","so_peritem.keterangan","users.first_name","ap_store.store","so_peritem.status","IFNULL(ap_kategori.kategori,'') as nama_kategori"
						   );

		$this->db->select($dataSelect);
		$this->db->from("so_peritem");
		$this->db->join("users","users.id = so_peritem.id_pic","left");
		$this->db->join("ap_store","ap_store.id_store = so_peritem.id_toko");
		$this->db->join("brand","brand.id_brand = so_peritem.id_brand","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = so_peritem.id_kategori","left");

		if(!empty($search)){
			
			$this->db->like("so_peritem.no_so",$search);
			$this->db->or_like("brand.brand",$search);
			$this->db->or_like("so_peritem.keterangan",$search);
			$this->db->or_like("ap_kategori.kategori",$search);
		}
        

		$this->db->limit($limit,$start);
		$this->db->order_by("so_peritem.tanggal_so","DESC");
		$this->db->order_by("so_peritem.no_so","DESC");
        
        
        if ($idUser!=1 && $idUser!=2 && $idUser!=4 && $idUser!=41){
            $this->db->where("so_peritem.id_toko",$idStore);
        }
        
        
		$this->db->where('type',0);
		return $this->db->get();
	}

	function viewPOProdukFilter($limit,$start,$search='',$status_receive='',$tanggalPO='',$tanggalKirim='',$supplier='',$store='',$status='',$jenis=''){
		$dataSelect = array(
								"purchase_order.no_po","purchase_order.tanggal_po","purchase_order.tanggal_kirim","supplier.supplier","users.first_name","purchase_order.status"
						   );

		$this->db->select($dataSelect);
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier");
		$this->db->join("users","users.id = purchase_order.id_pic");
		
		if(!empty($jenis)){
			$this->db->where("purchase_order.no_po like '$jenis%'");
		}

		if(!empty($search)){
			$this->db->like("purchase_order.no_po",$search);
		}

		if(!empty($tanggalPO)){
			$this->db->where("purchase_order.tanggal_po",$tanggalPO);
		}

		if(!empty($tanggalKirim)){
			$this->db->where("purchase_order.tanggal_kirim",$tanggalKirim);
		}

		if(!empty($supplier)){
			$this->db->where("purchase_order.id_supplier",$supplier);
		}

		if(!empty($store)){
			$this->db->where("purchase_order.id_toko",$store);
		}

		if(!empty($status)){
			$this->db->where("purchase_order.status",$status);
		}
		if(!empty($status_receive)){
			$this->db->where("purchase_order.status_receive",$status_receive);
		}

		$this->db->where("purchase_order.id_toko=users.toko");

		$this->db->limit($limit,$start);
		$this->db->order_by("purchase_order.tanggal_po","DESC");
		$this->db->order_by("purchase_order.no_po","DESC");
		$this->db->where('type',0);
		return $this->db->get();
	}

	function purchase_item($no_po){
		$this->db->select(array("ap_produk.nama_produk","cc_cart_receive_item.qty as qty_pdt","purchase_item.qty","purchase_item.bonus","purchase_item.qty_req","purchase_item.qty_approved","purchase_item.qty_confirmed","ap_produk.satuan","IF(cc_cart_receive_item.hpp>0,cc_cart_receive_item.hpp,ap_produk_price.hpp) as harga","ap_produk_price.harga as hargajual","(purchase_item.harga*purchase_item.qty_req) as total","ap_produk.id_produk","ap_produk.id_brand"));
		$this->db->from("purchase_item");
		$this->db->join("purchase_order","purchase_order.no_po = purchase_item.no_po");
		$this->db->join("ap_produk","ap_produk.id_produk = purchase_item.sku","left");
		$this->db->join("cc_cart_receive_item","cc_cart_receive_item.sku = purchase_item.sku and cc_cart_receive_item.no_po=purchase_item.no_po","left");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = purchase_item.sku and ap_produk_price.id_toko=purchase_order.id_toko");
		$this->db->where("purchase_item.no_po",$no_po);
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("ap_produk.nama_produk");

		return $this->db->get();
	}

	function purchaseItemMaterial($no_po){
		$this->db->select(array("bahan_baku.sku","bahan_baku.nama_bahan","purchase_item.qty","bahan_baku.satuan","purchase_item.harga","(purchase_item.harga*purchase_item.qty) as total"));
		$this->db->from("purchase_item");
		$this->db->join("bahan_baku","bahan_baku.sku = purchase_item.sku","left");
		$this->db->where("purchase_item.no_po",$no_po);
		return $this->db->get();
	}

	function received_item($no_receive){
		$this->db->select("*");
		$this->db->from("receive_item");
		$this->db->join("ap_produk","ap_produk.id_produk = receive_item.sku","left");
		$this->db->where("no_receive",$no_receive);
		$this->db->group_by("ap_produk.id_produk","ASC");
		$this->db->order_by("ap_produk.nama_produk","ASC");
		$this->db->order_by("receive_item.qty","DESC");
		return $this->db->get();
	}

	function received_item_material($no_receive){
		$this->db->select(array("bahan_baku.sku as id_produk","bahan_baku.nama_bahan as nama_produk","bahan_baku.satuan","receive_item.qty"));
		$this->db->from("receive_item");
		$this->db->join("bahan_baku","bahan_baku.sku = receive_item.sku","left");
		$this->db->where("no_receive",$no_receive);
		return $this->db->get();
	}

	function receivedItemMaterial($no_receive){
		$this->db->select(array('bahan_baku.sku','bahan_baku.nama_bahan','receive_item.qty','bahan_baku.satuan'));
		$this->db->from("receive_item");
		$this->db->join("bahan_baku","bahan_baku.sku = receive_item.sku","left");
		$this->db->where("no_receive",$no_receive);
		return $this->db->get();
	}

	function noteInfoPO($noPO){
		$colSelect = array(
							"purchase_order.tanggal_po",
							"purchase_order.no_po",
							"purchase_order.tanggal_kirim",
							"purchase_order.status",
							"purchase_order.keterangan",
							"supplier.supplier",
							"supplier.alamat",
							"supplier.kontak",
							"purchase_order.alamat_pengiriman",
							"purchase_order.id_supplier",
							"ap_store.store",
							"purchase_order.id_toko",
							"ap_store.alamat as alamatpenerima"
						);
		$this->db->select($colSelect);
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier");
		$this->db->join("ap_store","ap_store.id_store = purchase_order.id_toko");
		$this->db->where("purchase_order.no_po",$noPO);
		return $this->db->get()->row();
	}

	function cekStokBahanBaku($sku){
		$this->db->select("stok");
		$this->db->from("bahan_baku");
		$this->db->where("sku",$sku);
		$row = $this->db->get()->row();
		return $row->stok;
	}
	function cekStokProduk($sku,$idStore){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$sku);
		$this->db->where("id_store",$idStore);
		$row = $this->db->get()->row();
		return $row->stok;
	}

	function cekPO($no_po,$idProduk){
		$this->db->from("purchase_item");
		$this->db->where("sku",$idProduk);
		$this->db->where("no_po",$no_po);
		return $this->db->count_all_results();
	}

	function dataReceive($noReceive){
		$this->db->select(array("receive_order.no_receive","receive_order.no_po","receive_order.tanggal_terima","receive_order.received_by","receive_order.checked_by","supplier.supplier"));
		$this->db->from("receive_order");
		$this->db->join("purchase_order","purchase_order.no_po = receive_order.no_po");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","right");
		$this->db->where("receive_order.no_receive",$noReceive);
		return $this->db->get()->result();
	}

	function riwayatPenerimaan($noPo){
		$this->db->select(array("bahan_baku.sku","bahan_baku.nama_bahan","receive_item.tanggal","receive_item.qty"));
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive");
		$this->db->join("bahan_baku","bahan_baku.sku=receive_item.sku");
		$this->db->where("receive_order.no_po",$noPo);
		$this->db->where("receive_item.qty > 0");
		$this->db->order_by("receive_item.tanggal","DESC");
		return $this->db->get()->result();
	}

	function receivedInvoice($no_po){
		$this->db->select(array(
									"receive_order.no_receive",
									"receive_order.received_by",
									"receive_order.checked_by",
									"receive_order.tanggal_terima",
									"receive_order.diterimaDi",
									"SUM(receive_item.qty*receive_item.price) as total"
							    ));
		$this->db->from("receive_order");
		$this->db->join("receive_item","receive_item.no_receive = receive_order.no_receive","left");
		$this->db->where("no_po",$no_po);
		$this->db->order_by("receive_order.no_receive","DESC");
		$this->db->group_by("receive_order.no_receive");
		return $this->db->get();
	}

	function changePOStatus($no_po,$data_update){
		$this->db->where("no_po",$no_po);
		$this->db->update("purchase_order",$data_update);
	}

	function updateQty($noPo,$data_update,$idProduk){
		$this->db->where("no_po",$noPo);
		$this->db->where("sku",$idProduk);
		$this->db->update("purchase_item",$data_update);
	}


	function penerimaanGudang($sku,$data_stok){
		$this->db->where("id_produk",$sku);
		$this->db->update("ap_produk",$data_stok);
	}

	function penerimaanToko($sku,$diterimaDi,$dataStok){
		$this->db->where("id_produk",$sku);
		$this->db->where("id_store",$diterimaDi);
		$this->db->update("stok_store",$dataStok);
	}

	function penerimaanTokoHarga($sku,$diterimaDi,$dataHarga){
		$this->db->where("id_produk",$sku);
		$this->db->where("id_toko",$diterimaDi);
		$this->db->update("ap_produk_price",$dataHarga);
	}

	function insertNewStokStoreTransfer($dataStok){
		$this->db->insert("stok_store",$dataStok);
	}

	function terbitkanStatusHutang($data_tagihan){
		$this->db->insert("hutang",$data_tagihan);
	}

	function insertReceiveOrder($data_receive){
		$this->db->insert("receive_order",$data_receive);
		return ($this->db->affected_rows() != 1) ? false : true;
	}

	function insertBatchReceiveItem($data_insert){
		$this->db->insert_batch("receive_item",$data_insert);
	}

	function insertPOItem($data_bahan){
		$this->db->insert("purchase_item",$data_bahan);
	}

	function updateBatchStokBahanBaku($data_update){
		$this->db->update_batch("bahan_baku",$data_update,"sku");
	}

	function terbitkanHutang($data_tagihan){
		$this->db->insert("hutang",$data_tagihan);
	}
}