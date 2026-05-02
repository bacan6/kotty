<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Model1 extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function nama_produk($id_produk){
		$this->db->select("nama_produk");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$id_produk);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->nama_produk;
		}
	}

	function count_retur_dobel($invoice){
		$limamenit = date("Y-m-d H:i:s",strtotime("5 minutes ago"));
		$skrg = date("Y-m-d H:i:s");
		$this->db->select("tanggal");
		$this->db->from("ap_retur");
		$this->db->where("tanggal between '$limamenit' and '$skrg'");
		$this->db->where("no_invoice",$invoice);
		return $this->db->count_all_results();
	}

	function stokKartu($id_produk,$id_store){
		$this->db->select("SUM(qty) as stok");
		$this->db->from("stok_store_kartu");
		$this->db->where("id_store",$id_store);
		$this->db->where("id_produk",$id_produk);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->stok;
		}
	}

	function insertKartuStok($data_stok){
		$this->db->insert_batch("stok_store_kartu",$data_stok);
	}

	function setoran_item($no_setor){
		$this->db->select(array("no_setor", "tanggal", "id_user", "id_toko", "jam_setor", "penggantian","n100k", "n75k","n50k", "n20k", "n10k", "n5k", "n2k", "n1kp", "n1kc", "n500", "n200", "n100","voucher", "catatan"));
		$this->db->from("setoran_kasir");
		$this->db->where("no_setor",$no_setor);
		return $this->db->get();
	}

	function tanggal_setor($no_setor){
		$this->db->select("tanggal");
		$this->db->from("setoran_kasir");
		$this->db->where("no_setor",$no_setor);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->tanggal;
		}
	}

	function jam_setor($no_setor){
		$this->db->select("jam_setor");
		$this->db->from("setoran_kasir");
		$this->db->where("no_setor",$no_setor);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->jam_setor;
		}
	}

	function setorBefore($idKasir,$tanggal,$jam='',$no_setor=''){
		$this->db->select("(SUM(penggantian)+SUM(n100k*100000)+SUM(n75k*75000)+SUM(n50k*50000)+SUM(n20k*20000)+SUM(n10k*10000)+SUM(n5k*5000)+SUM(n2k*2000)+SUM(n1kp*1000)+SUM(n1kc*1000)+SUM(n500*500)+SUM(n200*200)+SUM(n100*100)) as setor");
		$this->db->from("setoran_kasir");
		if(!empty($no_setor)) {
			$this->db->where("no_setor!='".$no_setor."'");
		}
		if(!empty($jam)){
			$this->db->where("jam_setor < '".$jam."'");
		}
		
		$this->db->where("tanggal",$tanggal);
		$this->db->where("id_user",$idKasir);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->setor;
		}
	}

	function cek_stok_new($sku,$idStore='6'){
		$res = array();
		$this->db->select("MAX(stok) as stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk='$sku'");
		$this->db->where("id_store='$idStore'");
		$this->db->group_by("id_produk");
		$query = $this->db->get();
		foreach($query->result() as $row){
			$res['stok']  = $row->stok;
		}
		$res['count'] = $this->db->count_all_results();
		return $res;
	}
	function cek_sku_bahan(){
		$this->db->from("bahan_baku");
		return $this->db->count_all_results();
	}

	function footertext(){
		$this->db->select("footer");
		$this->db->from("footer_text");
		$this->db->where("id",1);
		$query = $this->db->get()->row();
		return $query->footer;
	}

	function call_sub($id_kategori){
        $this->db->select("*");
        $this->db->from("ap_sub_kategori");
        $this->db->where("parent_id",$id_kategori);
        return $this->db->get();
    }


    function count_produk(){
        $this->db->from("ap_produk");
        return $this->db->count_all_results();
    }

    function cekStoreExist($id_user,$idStore='7'){
    	$this->db->where("id_store",$idStore);
    	$this->db->where("id_user",$id_user);
    	$this->db->from("user_access_store");
    	return $this->db->count_all_results();
    }

    function cekStoreChecked($idUser,$idStore='7'){
    	$this->db->where("id_store",$idStore);
    	$this->db->where("id_user",$idUser);
    	$this->db->where("status",1);
    	$this->db->from("user_access_store");
    	return $this->db->count_all_results();
    }

	function get_bahan_baku(){
		$this->db->select("*");
		$this->db->from("bahan_baku");
		$this->db->join("kategori","kategori.id_kategori = bahan_baku.id_kategori","left");
		//$this->db->where("status",1); //CHOOOSE ACTIVE
		$this->db->where("del",1); //CHOOSE ACTIVE
		$this->db->where("type != 4");
		$this->db->where("isProdNo",NULL);
		$this->db->order_by("sku","DESC");
		return $this->db->get();
	}

	function get_bahan_baku_filter($sku_barang){
		$this->db->select("*");
		$this->db->from("bahan_baku");
		$this->db->where("status",1);
		$this->db->where("del",1);
		$this->db->like("bahan_baku.nama_bahan",$sku_barang);
		$this->db->join("kategori","kategori.id_kategori = bahan_baku.id_kategori","left");
		return $this->db->get();
	}

	function get_user(){
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where("id_user > 1");
		return $this->db->get();
	}

	function cek_user_access($id_user){
		$this->db->from("user_access");
		$this->db->where("id_user",$id_user);
		return $this->db->count_all_results();
	}

	function cek_auth($username,$password){
		$this->db->from("users");
		$this->db->where("username",$username);
		$this->db->where("password",$password);
		return $this->db->count_all_results();
	}

	function level_dashboard($id_user,$id){
		$this->db->select("*");
		$this->db->from("user_access");
		$this->db->where("access_level",$id);
		$this->db->where("id_user",$id_user);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->status;
		}
	}

	function get_bahan_baku_select2(){
		$this->db->select(array("sku","nama_bahan"));
		$this->db->from("bahan_baku");
		$this->db->where("status",1); //CHOOOSE ACTIVE
		$this->db->where("del",1); //CHOOSE ACTIVE
		$this->db->where("type",0);
		$this->db->or_where("type",1);
		$this->db->order_by("sku","ASC");
		return $this->db->get();
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

	function get_bahan_baku_select2_waste(){
		$this->db->select(array("sku","nama_bahan"));
		$this->db->from("bahan_baku");
		$this->db->where("status",1); //CHOOOSE ACTIVE
		$this->db->where("del",1); //CHOOSE ACTIVE
		$this->db->order_by("sku","ASC");
		return $this->db->get();
	}

	function get_bahan_baku_select2_keluar(){
		$this->db->select(array("sku","nama_bahan"));
		$this->db->from("bahan_baku");
		$this->db->where("status",1); //CHOOOSE ACTIVE
		$this->db->where("del",1); //CHOOSE ACTIVE
		$this->db->where("type",1);
		$this->db->order_by("sku","ASC");
		return $this->db->get();
	}

	function bahan_baku_komposisi(){
		$this->db->select(array("sku","nama_bahan"));
		$this->db->from("bahan_baku");
		$this->db->where("status",1); //CHOOOSE ACTIVE
		$this->db->where("del",1); //CHOOSE ACTIVE
		$this->db->where("type",0);
		$this->db->order_by("sku","ASC");
		return $this->db->get();
	}

	function cek_tanggal_terima($tanggal){
		$this->db->from("purchase_order");
		$this->db->where("tanggal_po",$tanggal);
		return $this->db->count_all_results();
	}

	function cek_tanggal_terima_promo($tanggal){
		$this->db->from("promo_supplier");
		$this->db->where("tanggal_buat",$tanggal);
		return $this->db->count_all_results();
	}

	function total_po(){
		$this->db->from("purchase_order");
		return $this->db->count_all_results();
	}

	function total_so(){
		$this->db->from("stock_opname_info");
		return $this->db->count_all_results();
	}

	function total_customer(){
		$this->db->from("ap_customer");
		return $this->db->count_all_results();
	}

	function daftar_po($limit,$start){
		$this->db->select(array("purchase_order.no_po","purchase_order.tanggal_po","purchase_order.tanggal_kirim","users.first_name","SUM(purchase_item.harga*purchase_item.qty) as value","purchase_order.keterangan","purchase_order.status","supplier.supplier","purchase_order.id_supplier","purchase_order.id_pic"));
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->join("users","users.id = purchase_order.id_pic","left");
		$this->db->join("purchase_item","purchase_item.no_po = purchase_order.no_po","left");
		$this->db->limit($limit,$start);
		$this->db->group_by("purchase_order.no_po");
		$this->db->order_by("tanggal_po","DESC");
		return $this->db->get();
	}

	function daftar_penjualan($limit,$start,$tab){
		$this->db->select(array("ap_invoice_number.poin_value","ap_invoice_number.alasan_cancel","ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.tanggal","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_customer.nama","ap_invoice_number.alamat","ae_provinsi.nama_provinsi","ae_kecamatan.kecamatan","ae_kabupaten.nama_kabupaten","ap_invoice_number.keterangan","ap_invoice_number.status","ap_invoice_number.tanggal_kirim","ap_ekspedisi.ekspedisi","ap_invoice_number.no_resi","ap_invoice_number.diskon_free","ap_invoice_number.diskon_otomatis"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ae_provinsi","ae_provinsi.id_provinsi = ap_invoice_number.id_provinsi","left");
		$this->db->join("ae_kabupaten","ae_kabupaten.kabupaten_id = ap_invoice_number.id_kabupaten","left");
		$this->db->join("ae_kecamatan","ae_kecamatan.id_kecamatan = ap_invoice_number.id_kecamatan","left");
		$this->db->join("ap_ekspedisi","ap_ekspedisi.id_ekspedisi = ap_invoice_number.id_ekspedisi","left");
		$this->db->limit($limit,$start);
		$this->db->group_by("ap_invoice_number.no_invoice");
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		//$this->db->where("ap_invoice_number.tipe_bayar",0);
		$this->db->where("ap_invoice_number.status",$tab);
		return $this->db->get();
	}

	function daftar_penjualan_sort($query){
		$this->db->select(array("ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.tanggal","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_customer.nama","ap_invoice_number.alamat","ae_provinsi.nama_provinsi","ae_kecamatan.kecamatan","ae_kabupaten.nama_kabupaten","ap_invoice_number.keterangan","ap_invoice_number.diskon_otomatis"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ae_provinsi","ae_provinsi.id_provinsi = ap_invoice_number.id_provinsi","left");
		$this->db->join("ae_kabupaten","ae_kabupaten.kabupaten_id = ap_invoice_number.id_kabupaten","left");
		$this->db->join("ae_kecamatan","ae_kecamatan.id_kecamatan = ap_invoice_number.id_kecamatan","left");
		$this->db->where("no_invoice",$query);
		$this->db->group_by("ap_invoice_number.no_invoice");
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		return $this->db->get();
	}

	function daftar_penjualan_all($limit,$start){
		$this->db->select(array("ap_invoice_number.poin_value","ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.tanggal","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_customer.nama","ap_invoice_number.alamat","ae_provinsi.nama_provinsi","ae_kecamatan.kecamatan","ae_kabupaten.nama_kabupaten","ap_invoice_number.keterangan","ap_invoice_number.status","ap_invoice_number.tanggal_kirim","ap_invoice_number.diskon_free","ap_invoice_number.diskon_otomatis","ap_payment_type.payment_type","ap_payment_account.account"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ae_provinsi","ae_provinsi.id_provinsi = ap_invoice_number.id_provinsi","left");
		$this->db->join("ae_kabupaten","ae_kabupaten.kabupaten_id = ap_invoice_number.id_kabupaten","left");
		$this->db->join("ae_kecamatan","ae_kecamatan.id_kecamatan = ap_invoice_number.id_kecamatan","left");
		$this->db->limit($limit,$start);
		$this->db->group_by("ap_invoice_number.no_invoice");
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		return $this->db->get();
	}

	function daftarPenjualan($limit,$start,$query='',$idStore='',$idUser=''){
		$this->db->select(array("ap_invoice_number.poin_value","ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.tanggal","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.keterangan","ap_invoice_number.status","ap_invoice_number.diskon_free","ap_invoice_number.diskon_otomatis","ap_payment_type.payment_type","ap_payment_account.account"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");

		if(!empty($query)){
			$this->db->like("ap_invoice_number.no_invoice",$query);
		}
        
        if ($idUser!=1 && $idUser!=22){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }

		$this->db->limit($limit,$start);
		$this->db->group_by("ap_invoice_number.no_invoice");
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		return $this->db->get();
	}

	function daftar_penjualan_all_sort($query){
		$this->db->select(array("ap_invoice_number.poin_value","ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.tanggal","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_customer.nama","ap_invoice_number.alamat","ae_provinsi.nama_provinsi","ae_kecamatan.kecamatan","ae_kabupaten.nama_kabupaten","ap_invoice_number.keterangan","ap_invoice_number.status","ap_invoice_number.tanggal_kirim","ap_invoice_number.diskon_free","ap_invoice_number.diskon_otomatis","ap_payment_type.payment_type","ap_payment_account.account"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ae_provinsi","ae_provinsi.id_provinsi = ap_invoice_number.id_provinsi","left");
		$this->db->join("ae_kabupaten","ae_kabupaten.kabupaten_id = ap_invoice_number.id_kabupaten","left");
		$this->db->join("ae_kecamatan","ae_kecamatan.id_kecamatan = ap_invoice_number.id_kecamatan","left");
		$this->db->like("ap_invoice_number.no_invoice",$query);
		$this->db->or_like("ap_customer.nama",$query);
		$this->db->group_by("ap_invoice_number.no_invoice");
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		return $this->db->get();
	}

	function invoice_ket($no_invoice){
		$this->db->select(array("ap_invoice_number.jumlah_bayar","ap_invoice_number.jatuh_tempo","ap_customer_group.group_customer","ap_invoice_number.poin_value","ap_invoice_number.poin","ap_invoice_number.poin_before","ap_invoice_number.status","ap_invoice_number.voucher","ap_invoice_number.surcharge","ap_invoice_number.kontak_pengiriman","ap_invoice_number.no_invoice","ap_invoice_number.diskon_free","ap_invoice_number.tipe_bayar","ap_invoice_number.tanggal","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_customer.nama","ap_customer.no_kartu","ap_customer.point","ap_invoice_number.alamat","ae_provinsi.nama_provinsi","ae_kecamatan.kecamatan","ae_kabupaten.nama_kabupaten","ap_invoice_number.keterangan","ap_payment_account.account"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ap_customer_group","ap_customer_group.id_group = ap_customer.kategori","left");
		$this->db->join("ae_provinsi","ae_provinsi.id_provinsi = ap_invoice_number.id_provinsi","left");
		$this->db->join("ae_kabupaten","ae_kabupaten.kabupaten_id = ap_invoice_number.id_kabupaten","left");
		$this->db->join("ae_kecamatan","ae_kecamatan.id_kecamatan = ap_invoice_number.id_kecamatan","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.no_invoice",$no_invoice);
		$this->db->group_by("ap_invoice_number.no_invoice");
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		return $this->db->get();
	}

	function invoice_item($no_invoice){
		$this->db->select(array("ap_produk.nama_produk","ap_invoice_item.qty","ap_invoice_item.harga_jual","ap_produk.id_produk","ap_produk.id_produk","ap_invoice_item.diskon","ap_invoice_item.hpp"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->where("ap_invoice_item.no_invoice",$no_invoice);
		$this->db->group_by("ap_invoice_item.id_produk");
		return $this->db->get();
	}

	function daftar_produk($limit,$start){
		$this->db->select("*");
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		$this->db->limit($limit,$start);
		$this->db->order_by("ap_produk.id_produk");
		return $this->db->get();	
	}

	function daftar_produk_sort($nama_produk){
		$this->db->select("*");
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		$this->db->where("ap_produk.status != 2");
		$this->db->like("ap_produk.nama_produk",$nama_produk);
		$this->db->or_like("ap_produk.id_produk",$nama_produk);
		return $this->db->get();
	}

	function daftarProdukAll($idStore){
		$this->db->select("*");
		$this->db->from("ap_produk");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");
		$this->db->where("ap_produk_price.id_toko",$idStore);
		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		$this->db->order_by("ap_produk.id_produk");
		return $this->db->get()->result();	
	}

	function daftar_Pengeluaran_barang($limit,$start){
		$this->db->select(array("ap_store.store","sp_no_bahan_keluar.no_bahan_keluar","users.first_name","sp_no_bahan_keluar.tanggal_keluar","sp_no_bahan_keluar.nama_penerima","sp_no_bahan_keluar.keterangan"));
		$this->db->from("sp_no_bahan_keluar");
		$this->db->join("users","sp_no_bahan_keluar.id_user = users.id","left");
		$this->db->join("ap_store","ap_store.id_store = sp_no_bahan_keluar.store_tujuan","left");
		$this->db->limit($limit,$start);
		$this->db->group_by("sp_no_bahan_keluar.no_bahan_keluar");
		$this->db->order_by("sp_no_bahan_keluar.tanggal_keluar","DESC");
		return $this->db->get();
	}

	function daftar_Pengeluaran_barang_sort($limit,$start,$no_pengeluaran){
		$this->db->select(array("ap_store.store","sp_no_bahan_keluar.no_bahan_keluar","users.first_name as nama_user","sp_no_bahan_keluar.tanggal_keluar","sp_no_bahan_keluar.nama_penerima","sp_no_bahan_keluar.keterangan"));
		$this->db->from("sp_no_bahan_keluar");
		$this->db->join("users","sp_no_bahan_keluar.id_user = users.id","left");
		$this->db->join("ap_store","ap_store.id_store = sp_no_bahan_keluar.store_tujuan","left");
		$this->db->limit($limit,$start);
		$this->db->where("");
		$this->db->group_by("sp_no_bahan_keluar.no_bahan_keluar");
		$this->db->order_by("sp_no_bahan_keluar.tanggal_keluar","DESC");
		return $this->db->get();
	}

	function data_stok($limit,$start){
		$this->db->select("*");
		$this->db->from("data_stok");
		$this->db->join("bahan_baku","bahan_baku.sku = data_stok.sku","left");
		$this->db->join("kategori","kategori.id_kategori = bahan_baku.id_kategori","left");
		$this->db->limit($limit,$start);
		$this->db->where("status",1); //CHOOOSE ACTIVE
		$this->db->where("del",1); //CHOOSE ACTIVE
		return $this->db->get();
	}

	function data_stok_all(){
		$this->db->select("*");
		$this->db->from("data_stok");
		$this->db->join("bahan_baku","bahan_baku.sku = data_stok.sku","left");
		$this->db->join("kategori","kategori.id_kategori = bahan_baku.id_kategori","left");
		$this->db->where("status",1); //CHOOOSE ACTIVE
		$this->db->where("del",1); //CHOOSE ACTIVE
		return $this->db->get();
	}

	function data_stok_sort($limit,$start,$query){
		$this->db->select("*");
		$this->db->from("data_stok");
		$this->db->join("bahan_baku","bahan_baku.sku = data_stok.sku","left");
		$this->db->join("kategori","kategori.id_kategori = bahan_baku.id_kategori","left");
		$this->db->limit($limit,$start);
		$this->db->where("status",1); //CHOOOSE ACTIVE
		$this->db->where("del",1); //CHOOSE ACTIVE
		$this->db->like("bahan_baku.nama_bahan",$query);
		$this->db->or_like("bahan_baku.sku",$query);
		return $this->db->get();
	}

	function daftar_po_sort($no_po,$tanggal_po,$tanggal_kirim,$supplier,$pic,$status){
		$this->db->select(array("purchase_order.no_po","purchase_order.tanggal_po","purchase_order.tanggal_kirim","users.first_name as nama_user","SUM(purchase_item.harga*purchase_item.qty) as value","purchase_order.keterangan","purchase_order.status","supplier.supplier","purchase_order.id_supplier","purchase_order.id_pic"));
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->join("users","users.id = purchase_order.id_pic","left");
		$this->db->join("purchase_item","purchase_item.no_po = purchase_order.no_po","left");
		
		if(!empty($no_po)){
			$this->db->where("purchase_order.no_po",$no_po);
		}

		if(!empty($tanggal_po)){
			$this->db->where("purchase_order.tanggal_po",$tanggal_po);
		}

		if(!empty($tanggal_kirim)){
			$this->db->where("purchase_order.tanggal_kirim",$tanggal_kirim);
		}

		if(!empty($supplier)){
			$this->db->where("purchase_order.id_supplier",$supplier);
		}

		if(!empty($pic)){
			$this->db->where("purchase_order.id_pic",$pic);
		}

		if(!empty($status)){
			$this->db->where("purchase_order.status",$status);
		}

		$this->db->group_by("purchase_order.no_po");
		$this->db->order_by("purchase_order.tanggal_po","DESC");
		return $this->db->get();
	}

	function purchase_item($no_po){
		$this->db->select(array("bahan_baku.nama_bahan","purchase_item.qty","bahan_baku.satuan","purchase_item.harga","(purchase_item.harga*purchase_item.qty) as total","bahan_baku.sku"));
		$this->db->from("purchase_item");
		$this->db->join("bahan_baku","bahan_baku.sku = purchase_item.sku","left");
		$this->db->where("purchase_item.no_po",$no_po);
		return $this->db->get();
	}

	function status_po($no_po){
		$this->db->select("status");
		$this->db->from("purchase_order");
		$this->db->where("no_po",$no_po);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->status;
		}
	}

	function cek_tanggal_receive($tanggal){
		$this->db->from("receive_order");
		$this->db->where("tanggal_terima",$tanggal);
		return $this->db->count_all_results();
	}

	function cek_tanggal_kedatangan($tanggal){
		$this->db->from("hutang");
		$this->db->where("tanggal_kedatangan",$tanggal);
		return $this->db->count_all_results();
	}

	function received_invoice($no_po){
		$this->db->select(array(
									"receive_order.no_receive",
									"receive_order.received_by",
									"receive_order.checked_by",
									"receive_order.tanggal_terima",
									"receive_order.diterimaDi",
									"receive_order.status_receive",
									"receive_order.keterangan_receive",
									"SUM(receive_item.qty*receive_item.price) as total"
							    ));
		$this->db->from("receive_order");
		$this->db->join("receive_item","receive_item.no_receive = receive_order.no_receive","left");
		$this->db->where("no_po",$no_po);
		$this->db->order_by("receive_order.no_receive","DESC");
		$this->db->group_by("receive_order.no_receive");
		return $this->db->get();
	}

	function received_invoice_all($limit,$start,$query=''){
		$this->db->select(array(
									"receive_order.no_receive",
									"receive_order.received_by",
									"receive_order.no_po",
									"receive_order.checked_by",
									"receive_order.tanggal_terima",
									"receive_order.diterimaDi",
									"SUM(receive_item.qty*receive_item.price) as total",
									"supplier.supplier",
									"receive_order.id_supplier",
									""
							    ));
		$this->db->from("receive_order");
		$this->db->join("receive_item","receive_item.no_receive = receive_order.no_receive","left");
		$this->db->join("supplier","supplier.id_supplier = receive_order.id_supplier","left");

		if(!empty($query)){
			$this->db->like("receive_order.no_receive",$query);
			$this->db->or_like("receive_order.no_po",$query);
			$this->db->or_like("bahan_baku.nama_bahan",$query);
		}

		$this->db->limit($limit,$start);
		$this->db->group_by("receive_order.no_receive");
		$this->db->order_by("tanggal_terima","DESC");
		return $this->db->get();
	}

	function data_so_all($limit,$start,$idStore='',$idUser=''){
		$this->db->select("*");
		$this->db->from("stock_opname_info");
		$this->db->join("users","users.id = stock_opname_info.id_pic","left");
        
        if ($idUser!=1 && $idUser!=22){
            $this->db->where("stock_opname_info.store",$idStore);
        }
		$this->db->limit($limit,$start);
		$this->db->group_by("stock_opname_info.no_so");
		$this->db->order_by("tanggal","DESC");
		return $this->db->get();
	}

	function data_so_sort($limit,$start,$query,$idStore='',$idUser=''){
		$this->db->select("*");
		$this->db->from("stock_opname_info");
		$this->db->join("users","users.id = stock_opname_info.id_pic","left");
        
        if ($idUser!=1 && $idUser!=22){
            $this->db->where("stock_opname_info.store",$idStore);
        }
		$this->db->limit($limit,$start);
		$this->db->where("stock_opname_info.no_so",$query);
		$this->db->group_by("stock_opname_info.no_so");
		$this->db->order_by("tanggal","DESC");
		return $this->db->get();
	}

	function received_invoice_all_sort($limit,$start,$query){
		$this->db->select(array(
									"receive_order.no_receive",
									"receive_order.received_by",
									"receive_order.no_po",
									"receive_order.checked_by",
									"receive_order.tanggal_terima",
									"SUM(receive_item.qty*receive_item.price) as total",
									"supplier.supplier",
									"receive_order.id_supplier"
							    ));
		$this->db->from("receive_order");
		$this->db->join("receive_item","receive_item.no_receive = receive_order.no_receive","left");
		$this->db->join("supplier","supplier.id_supplier = receive_order.id_supplier","left");
		$this->db->join("bahan_baku","receive_item.sku = bahan_baku.sku","left");
		$this->db->like("receive_order.no_receive",$query);
		$this->db->or_like("receive_order.no_po",$query);
		$this->db->or_like("bahan_baku.nama_bahan",$query);
		$this->db->limit($limit,$start);
		$this->db->group_by("receive_order.no_receive");
		$this->db->order_by("tanggal_terima","DESC");
		return $this->db->get();
	}

	function received_item($no_receive){
		$this->db->select("*");
		$this->db->from("receive_item");
		$this->db->join("bahan_baku","bahan_baku.sku = receive_item.sku","left");
		$this->db->where("no_receive",$no_receive);
		return $this->db->get();
	}

	function delivered_qty($no_po,$sku){
		$this->db->select("SUM(qty) as qty");
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive","left");
		$this->db->where("receive_order.no_po",$no_po);
		$this->db->where("receive_item.sku",$sku);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->qty;
		}
	}

	function cek_stok_lama($sku,$idStore='7'){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$sku);
		$this->db->where("id_store",$idStore);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->stok;
		}
	}

	function cek_stok_toko($id_produk,$id_store){
		$this->db->select("id_store");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$id_produk);
		$this->db->where("id_store",$id_store);
		return $this->db->count_all_results();
	}

	function total_material_active(){
		$this->db->from("bahan_baku");
		$this->db->where("status",1); //CHOOOSE ACTIVE
		$this->db->where("del",1); //CHOOSE ACTIVE
		$this->db->where("isProdNo",NULL);
		return $this->db->count_all_results();
	}

	function info_po($id){
		$this->db->select("*");
		$this->db->from("receive_order");
		$this->db->join("supplier","supplier.id_supplier = receive_order.id_supplier","left");
		$this->db->where("no_receive",$id);
		return $this->db->get();
	}

	function info_purchase($no_po){
		$this->db->select(array("purchase_order.tanggal_po","purchase_order.keterangan","supplier.supplier","supplier.alamat","supplier.kontak","purchase_order.ppn","purchase_order.nilai_ppn","purchase_order.alamat_pengiriman","purchase_order.tanggal_kirim","purchase_order.id_supplier"));
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->where("purchase_order.no_po",$no_po);
		$this->db->group_by("purchase_order.no_po");
		$query = $this->db->get();
		return $query->result();
	}

	function data_penerimaan($no_po){
		$this->db->select("*");
		$this->db->from("receive_order");
		$this->db->where("no_po",$no_po);
		return $this->db->get();
	}

	function item_receive($id){
		$this->db->select("*");
		$this->db->from("receive_item");
		$this->db->join("bahan_baku","bahan_baku.sku = receive_item.sku","left");
		$this->db->where("no_receive",$id);
		return $this->db->get();
	}

	function cek_no_receive($id){
		$this->db->from("receive_order");
		$this->db->where("no_receive",$id);
		return $this->db->count_all_results();
	}

	function cek_no_retur(){
		$day = date('d');
		$month = date('m');
		$year = date('Y');

		$this->db->from("retur");
		$this->db->where("DAY(tanggal_retur)",$day);
		$this->db->where("MONTH(tanggal_retur)",$month);
		$this->db->where("YEAR(tanggal_retur)",$year);
		return $this->db->count_all_results();
	}

	function cek_no_retur_penjualan(){
		$day 	= date('d');
		$month 	= date('m');
		$year 	= date('Y');

		$this->db->from("ap_retur");
		$this->db->where("DAY(tanggal)",$day);
		$this->db->where("MONTH(tanggal)",$month);
		$this->db->where("YEAR(tanggal)",$year);
		return $this->db->count_all_results();
	}

	function cek_no_so($today,$id_user){
		$this->db->from("stock_opname_info");
		$this->db->where("tanggal",$today);
		$this->db->where("id_pic",$id_user);
		return $this->db->count_all_results();
	}

	function total_retur(){
		$this->db->from("retur");
		return $this->db->count_all_results();
	}

	function daftar_retur($limit,$start){
		$this->db->select("*");
		$this->db->from("retur");
		$this->db->join("user","user.id_user = retur.id_pic","left");
		$this->db->limit($limit,$start);
		$this->db->order_by("tanggal_retur","DESC");
		return $this->db->get();
	}

	function daftar_retur_sort($limit,$start,$query){
		$this->db->select("*");
		$this->db->from("retur");
		$this->db->join("user","user.id_user = retur.id_pic","left");
		$this->db->limit($limit,$start);
		$this->db->like("no_retur",$query);
		$this->db->or_like("no_receive",$query);
		$this->db->order_by("tanggal_retur","DESC");
		return $this->db->get();
	}

	function info_retur($no_retur){
		$this->db->select(array("retur.no_retur","retur.no_receive","retur.tanggal_retur","users.first_name as nama_user","supplier.supplier","receive_order.no_po"));
		$this->db->from("retur");
		$this->db->join("receive_order","receive_order.no_receive = retur.no_receive","left");
		$this->db->join("supplier","receive_order.id_supplier = supplier.id_supplier","left");
		$this->db->join("users","retur.id_pic = users.id","left");
		$this->db->where("retur.no_retur",$no_retur);
		return $this->db->get();
	}

	function retur_item($no_retur){
		$this->db->select("*");
		$this->db->from("retur_item");
		$this->db->join("bahan_baku","bahan_baku.sku = retur_item.sku","left");
		$this->db->where("no_retur",$no_retur);
		return $this->db->get();
	}

	function retur_item_2($no_retur){
		$this->db->select(array("bahan_baku.nama_bahan","bahan_baku.satuan","retur_item.harga","retur_item.qty","retur_item.keterangan"));
		$this->db->from("retur_item");
		$this->db->join("bahan_baku","bahan_baku.sku = retur_item.sku","left");
		$this->db->where("no_retur",$no_retur);
		return $this->db->get();
	}

	function retur_perbarang($date_start,$date_end,$sku){
		$this->db->select(array("retur_item.qty","retur_item.keterangan","retur.no_retur","retur.tanggal_retur","retur_item.tanggal"));
		$this->db->from("retur_item");
		$this->db->join("bahan_baku","bahan_baku.sku = retur_item.sku","left");
		$this->db->join("retur","retur.no_retur = retur_item.no_retur","left");
		$this->db->where("retur_item.sku",$sku);
		$this->db->where("retur_item.tanggal BETWEEN '$date_start' AND '$date_end'");
		return $this->db->get();
	}

	function retur_item_invoice($no_invoice){
		$this->db->select(array("ap_retur_item.no_retur","ap_retur_item.tanggal","ap_produk.id_produk","ap_produk.nama_produk","ap_retur_item.qty","ap_retur_item.harga"));
		$this->db->from("ap_retur_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_retur_item.id_produk","left");
		$this->db->join("ap_retur","ap_retur.no_retur = ap_retur_item.no_retur","left");
		$this->db->where("ap_retur.no_invoice",$no_invoice);
		return $this->db->get()->result();
	}

	function max_stok($sku){
		$this->db->select("stok");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$sku);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->stok;
		}
	}

	function total_penerimaan(){
		$this->db->from("receive_order");
		return $this->db->count_all_results();
	}

	function cek_tanggal_waste($tanggal_waste){
		$this->db->from("waste");
		$this->db->where("tanggal_waste",$tanggal_waste);
		return $this->db->count_all_results();
	}

	function rupiah_awal($bulan,$tahun,$sku){
		if($bulan==01){
			$last_month =  12;
			$last_year 	= $tahun-1;
		} else {
			$last_month = $bulan-1;
			$last_year 	= $tahun;
		}

		$this->db->select("harga");
		$this->db->from("stock_awal");
		$this->db->where("bulan",$last_month);
		$this->db->where("tahun",$last_year);
		$this->db->where("sku",$sku);
		$query2 = $this->db->get();

		foreach($query2->result() as $dt){
			return $dt->harga;
		}
	}

	function qty_awal($bulan,$tahun,$sku){
		if($bulan==01){
			$last_month =  12;
			$last_year 	= $tahun-1;
		} else {
			$last_month = $bulan-1;
			$last_year 	= $tahun;
		}

		$this->db->select("qty");
		$this->db->from("stock_awal");
		$this->db->where("bulan",$last_month);
		$this->db->where("tahun",$last_year);
		$this->db->where("sku",$sku);
		$query2 = $this->db->get();

		foreach($query2->result() as $dt){
			return $dt->qty; 
		}
	}

	function harga_average_permonth($bulan,$tahun,$sku){
		
		$rp_awal 	= $this->rupiah_awal($bulan,$tahun,$sku);
		$qty_awal 	= $this->qty_awal($bulan,$tahun,$sku);
		
		$this->db->select(array("SUM(receive_item.price*receive_item.qty) as rp_pembelian","SUM(receive_item.qty) as qty_pembelian"));
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive","left");
		$this->db->where("MONTH(tanggal_terima)",$bulan);
		$this->db->where("YEAR(tanggal_terima)",$tahun);
		$this->db->where("sku",$sku);
		$query = $this->db->get();

		foreach($query->result() as $row){
			$rp_pembelian 	= $row->rp_pembelian;
			$qty_pembelian 	= $row->qty_pembelian;
		}

		$div1 = ($rp_pembelian+($rp_awal*$qty_awal));
		$div2 = ($qty_awal+$qty_pembelian);

		if($div2<1){
			return 0;
		} else {
			return $div1/$div2;
		}
		
	}

	function daftar_waste($limit,$start){
		$this->db->select(array("users.first_name as nama_user","waste.no_waste","waste.tanggal_waste","(keterangan_waste.keterangan) as tipe_waste","waste.keterangan","SUM(waste_item.harga*waste_item.qty) as value"));
		$this->db->from("waste");
		$this->db->join("keterangan_waste","keterangan_waste.id_keterangan = waste.id_keterangan","left");
		$this->db->join("waste_item","waste_item.no_waste = waste.no_waste","left");
		$this->db->join("users","users.id = waste.id_pic","left");
		$this->db->limit($limit,$start);
		$this->db->order_by("waste.tanggal_waste","DESC");
		$this->db->group_by("waste.no_waste");
		return $this->db->get();
	}

	function get_produk_page($limit,$start){
		$this->db->select("*");
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori");
		$this->db->where("ap_produk.type",0);
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function get_produk_page_sort($limit,$start,$query){
		$this->db->select("*");
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori");
		$this->db->where("ap_produk.type",0);
		$this->db->like("nama_produk",$query);
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function daftar_waste_sort($limit,$start,$query){
		$this->db->select(array("users.first_name as nama_user","waste.no_waste","waste.tanggal_waste","(keterangan_waste.keterangan) as tipe_waste","waste.keterangan","SUM(waste_item.harga*waste_item.qty) as value"));
		$this->db->from("waste");
		$this->db->join("keterangan_waste","keterangan_waste.id_keterangan = waste.id_keterangan","left");
		$this->db->join("waste_item","waste_item.no_waste = waste.no_waste","left");
		$this->db->join("users","users.id = waste.id_pic","left");
		$this->db->limit($limit,$start);
		$this->db->like("waste.no_waste",$query);
		$this->db->or_like("waste.keterangan",$query);
		$this->db->order_by("waste.tanggal_waste","DESC");
		$this->db->group_by("waste.no_waste");
		return $this->db->get();
	}

	function total_waste(){
		$this->db->from("waste");
		return $this->db->count_all_results();
	}

	function info_waste($no_waste){
		$this->db->select(array("brand.brand","supplier.supplier","waste.status as Lunas","users.first_name as nama_user","waste.no_waste","waste.tanggal_waste","(keterangan_waste.keterangan) as tipe_waste","waste.keterangan","SUM(waste_item.harga*waste_item.qty) as value","waste.image"));
		$this->db->from("waste");
		$this->db->join("keterangan_waste","keterangan_waste.id_keterangan = waste.id_keterangan","left");
		$this->db->join("waste_item","waste_item.no_waste = waste.no_waste","left");
		$this->db->join("users","users.id = waste.id_pic","left");
		$this->db->join("brand","brand.id_brand = waste.id_brand","left");
		$this->db->join("supplier","supplier.id_supplier = waste.id_supplier","left");
		$this->db->where("waste.no_waste",$no_waste);
		$this->db->order_by("waste.tanggal_waste","DESC");
		$this->db->group_by("waste.no_waste");
		return $this->db->get();
	}

	function item_waste($no_waste){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","waste_item.qty","ap_produk.satuan","waste_item.harga","ap_produk_price.harga as harga_jual"));
		$this->db->from("waste_item");
		$this->db->join("ap_produk","ap_produk.id_produk = waste_item.sku","left");
		$this->db->join("waste","waste.no_waste = waste_item.no_waste","left");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = waste_item.sku and ap_produk_price.id_toko=waste.id_toko","left");
		$this->db->where("waste_item.no_waste",$no_waste);
		return $this->db->get();
	}

	function get_produk(){
		$this->db->select("*");
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori");
		$this->db->join("ap_sub_kategori","ap_sub_kategori.id = ap_produk.id_subkategori","left");
		return $this->db->get();
	}

	function get_produk_select2(){
		$this->db->select(array("ap_produk.nama_produk","ap_produk.id_produk"));
		$this->db->from("ap_produk");
		$this->db->where("status",1);
		return $this->db->get();
	}

	function komposisi_bahan($id_produk){
		$this->db->select("*");
		$this->db->from("ap_produk_bahan_baku");
		$this->db->join("bahan_baku","bahan_baku.sku = ap_produk_bahan_baku.sku","left");
		$this->db->where("id_produk",$id_produk);
		return $this->db->get();
	}	

	function cek_pembelian($bulan,$tahun,$sku){
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive");
		$this->db->where("MONTH(tanggal_terima)",$bulan);
		$this->db->where("YEAR(tanggal_terima)",$tahun);
		$this->db->where("receive_item.sku",$sku);
		return $this->db->count_all_results();
	}

	function get_qty_ingredient($sku,$id_produk){
		$this->db->select("qty");
		$this->db->from("ap_produk_bahan_baku");
		$this->db->where("sku",$sku);
		$this->db->where("id_produk",$id_produk);
		$query = $this->db->get();
		
		foreach($query->result() as $row){
			return $row->qty;
		}	
	}

	function cek_bahan($sku,$id_produk){
		$this->db->from("ap_produk_bahan_baku");
		$this->db->where("sku",$sku);
		$this->db->where("id_produk",$id_produk);
		return $this->db->count_all_results();
	}

	function total_produk(){
		$this->db->from("ap_produk");
		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		return $this->db->count_all_results();
	}

	function bahan_baku_peritem($id_produk){
		$this->db->select("*");
		$this->db->from("ap_produk_bahan_baku");
		$this->db->where("id_produk",$id_produk);
		return $this->db->get();
	}

	function item_order_in_chart($id_user){
		$this->db->select("*");
		$this->db->from("work_order");
		$this->db->join("ap_produk","ap_produk.id_produk = work_order.id_produk","left");
		$this->db->where("work_order.id_user",$id_user);
		return $this->db->get();
	}

	function cek_work_order($id_produk,$id_user){
		$this->db->from("work_order");
		$this->db->where("id_produk",$id_produk);
		$this->db->where("id_user",$id_user);
		return $this->db->count_all_results();
	}

	function komposisi_in_work_item($id_produk,$id_user){
		$this->db->select("*");
		$this->db->from("work_item");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item.sku","left");
		$this->db->where("work_item.id_produk",$id_produk);
		$this->db->where("work_item.id_user",$id_user);
		return $this->db->get();
	}

	function rekap_order_item($id_user){
		$this->db->select(array("data_stok.stok","bahan_baku.satuan","work_item.sku","SUM(work_item.qty) as qty","bahan_baku.nama_bahan"));
		$this->db->from("work_item");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item.sku","left");
		$this->db->join("data_stok","data_stok.sku = work_item.sku","left");
		$this->db->where("work_item.id_user",$id_user);
		$this->db->group_by("work_item.sku");
		return $this->db->get();
	}

	//cekselisih
	function cek_selisih_order($id_user){
		$this->db->select(array("((SUM(work_item.qty))-data_stok.stok) as selisih"));
		$this->db->from("work_item");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item.sku","left");
		$this->db->join("data_stok","data_stok.sku = work_item.sku","left");
		$this->db->where("work_item.id_user",$id_user);
		$this->db->having("selisih > 0");
		$this->db->group_by("work_item.sku");
		return $this->db->count_all_results();
	}

	function cek_order_in_cart($id_user){
		$this->db->select(array("data_stok.stok","bahan_baku.satuan","work_item.sku","SUM(work_item.qty) as qty","bahan_baku.nama_bahan"));
		$this->db->from("work_item");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item.sku","left");
		$this->db->join("data_stok","data_stok.sku = work_item.sku","left");
		$this->db->where("work_item.id_user",$id_user);
		$this->db->group_by("work_item.sku");
		return $this->db->count_all_results();
	}

	function qty_item_in_cart($sku,$id_user,$id_produk){
		$this->db->select("qty");
		$this->db->from("work_item");
		$this->db->where("sku",$sku);
		$this->db->where("id_user",$id_user);
		$this->db->where("id_produk",$id_produk);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->qty;
		}
	}

	function cek_tanggal_wo($day,$month,$year){
		$this->db->from("work_order_ok");
		$this->db->where("DAY(tanggal_order)",$day);
		$this->db->where("MONTH(tanggal_order)",$month);
		$this->db->where("YEAR(tanggal_order)",$year);
		return $this->db->count_all_results();
	}

	function daftar_wo($limit,$start){
		$this->db->select(array("work_order_ok.no_order","work_order_ok.tanggal_order","users.first_name as nama_user","SUM(jumlah_produksi) as jumlah_produksi"));
		$this->db->from("work_order_ok");
		$this->db->join("users","users.id = work_order_ok.id_user","left");
		$this->db->join("work_order_produk","work_order_produk.no_order = work_order_ok.no_order","left");
		//$this->db->join("work_order_result","work_order_result.no_order = work_order_ok.no_order","left");
		$this->db->limit($limit,$start);
		$this->db->group_by("work_order_ok.no_order");
		$this->db->order_by("tanggal_order","DESC");
		return $this->db->get();
	}

	function daftar_wo_sort($limit,$start,$query){
		$this->db->select(array("work_order_ok.no_order","work_order_ok.tanggal_order","users.first_name nama_user","SUM(jumlah_produksi) as jumlah_produksi"));
		$this->db->from("work_order_ok");
		$this->db->join("users","users.id = work_order_ok.id_user","left");
		$this->db->join("work_order_produk","work_order_produk.no_order = work_order_ok.no_order","left");
		$this->db->like("work_order_ok.no_order",$query);
		$this->db->limit($limit,$start);
		$this->db->group_by("work_order_ok.no_order");
		$this->db->order_by("tanggal_order","DESC");
		return $this->db->get();
	}

	function info_wo($no_wo){
		$this->db->select("*");
		$this->db->from("work_order_ok");
		$this->db->join("user","user.id_user = work_order_ok.id_user","left");
		$this->db->where("work_order_ok.no_order",$no_wo);
		return $this->db->Get();
	}

	function order_item($no_wo){
		$this->db->select("*");
		$this->db->from("work_order_produk");
		$this->db->join("ap_produk","ap_produk.id_produk = work_order_produk.id_produk","left");
		$this->db->where("work_order_produk.no_order",$no_wo);
		return $this->db->get();
	}

	function material_row($no_wo){
		$this->db->select(array("bahan_baku.nama_bahan","SUM(work_item_ok.qty) as qty","bahan_baku.satuan"));
		$this->db->from("work_item_ok");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item_ok.sku","left");
		$this->db->where("work_item_ok.no_order",$no_wo);
		$this->db->group_by("work_item_ok.sku");
		return $this->db->get();			
	}

	function data_tagihan($limit,$start){
		$this->db->select(array("hutang.no_tagihan","purchase_order.tanggal_po","purchase_order.jatuh_tempo","users.first_name as nama_user","hutang.status_hutang","purchase_order.keterangan","SUM(purchase_item.harga*purchase_item.qty) as total"));
		$this->db->from("hutang");
		$this->db->join("purchase_order","purchase_order.no_po = hutang.no_tagihan","left");
		$this->db->join("users","users.id = purchase_order.id_pic","left");
		$this->db->join("purchase_item","purchase_item.no_po = hutang.no_tagihan","left");
		$this->db->limit($limit,$start);
		$this->db->order_by("hutang.no_tagihan","DESC");
		$this->db->group_by("hutang.no_tagihan");
		return $this->db->get();		
	}

	function data_tagihan_sort($limit,$start,$query){
		$this->db->select(array("hutang.no_tagihan","purchase_order.tanggal_po","purchase_order.jatuh_tempo","users.first_name as nama_user","hutang.status_hutang","purchase_order.keterangan","SUM(purchase_item.harga*purchase_item.qty) as total"));
		$this->db->from("hutang");
		$this->db->join("purchase_order","purchase_order.no_po = hutang.no_tagihan","left");
		$this->db->join("users","users.id = purchase_order.id_pic","left");
		$this->db->join("purchase_item","purchase_item.no_po = hutang.no_tagihan","left");
		$this->db->like("hutang.no_tagihan",$query);
		$this->db->limit($limit,$start);
		$this->db->order_by("hutang.no_tagihan","DESC");
		$this->db->group_by("hutang.no_tagihan");
		return $this->db->get();
	}

	function info_hutang($no_tagihan){
		$this->db->select(array("hutang.no_tagihan","hutang.status_hutang","users.first_name as nama_user","supplier.supplier","purchase_order.jatuh_tempo","purchase_order.keterangan","supplier.id_supplier"));
		$this->db->from("hutang");
		$this->db->join("purchase_order","purchase_order.no_po = hutang.no_tagihan","left");
		$this->db->join("users","users.id = purchase_order.id_pic","left");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->where("hutang.no_tagihan",$no_tagihan);
		return $this->db->get();
	}

	function cek_tanggal_pembayaran($day,$month,$year){
		$this->db->from("hutang_order");
		$this->db->where("DAY(tanggal_pembayaran)",$day);
		$this->db->where("MONTH(tanggal_pembayaran)",$month);
		$this->db->where("YEAR(tanggal_pembayaran)",$year);
		return $this->db->count_all_results();
	}

	function cek_tanggal_pengeluaran($day,$month,$year){

		$tanggal = $year.'-'.$month.'-'.$day;

		$this->db->from("sp_bahan_keluar");
		$this->db->like("tanggal_keluar",$tanggal);
		return $this->db->count_all_results();
	}

	function cek_payment_type($id_payment){
		$this->db->select("type");
		$this->db->from("hutang_payment");
		$this->db->where("id_payment",$id_payment);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->type;
		}
	}

	function invoice_pembayaran($no_tagihan){
		$this->db->select("*");
		$this->db->from("hutang_order");
		$this->db->join("user","user.id_user = hutang_order.id_pic","left");
		$this->db->join("hutang_payment","hutang_payment.id_payment = hutang_order.id_payment","left");
		$this->db->where("no_penagihan",$no_tagihan);
		return $this->db->get();
	}

	function invoice_pembayaran_2($no_receive){
		$this->db->select("*");
		$this->db->from("hutang_order");
		$this->db->join("user","user.id_user = hutang_order.id_pic","left");
		$this->db->join("hutang_payment","hutang_payment.id_payment = hutang_order.id_payment","left");
		$this->db->join("hutang","hutang.no_tagihan = hutang_order.no_penagihan","left");
		$this->db->where("hutang.no_receive",$no_receive);
		return $this->db->get();
	}

	function info_payment($no_payment){
		$this->db->select("*");
		$this->db->from("hutang_order");
		$this->db->join("hutang","hutang.no_tagihan = hutang_order.no_penagihan","left");
		$this->db->join("user","user.id_user = hutang_order.id_pic","left");
		$this->db->join("hutang_payment","hutang_payment.id_payment = hutang_order.id_payment");
		$this->db->where("hutang_order.no_payment",$no_payment);
		return $this->db->get();
	}

	function hutang_terbayar($no_tagihan){
		$this->db->select(array("SUM(debit) as debit","SUM(kredit) as kredit"));
		$this->db->from("hutang_order");
		$this->db->where("no_penagihan",$no_tagihan);
		$this->db->group_by("no_penagihan");
		return $this->db->get();
	}

	function status_transaksi($id){
		$this->db->select("status_hutang");
		$this->db->from("hutang");
		$this->db->where("no_tagihan",$id);
		$query = $this->db->get();	

		foreach($query->result() as $row){
			return $row->status_hutang;
		}
	}

	function total_tagihan(){
		$this->db->from("hutang");
		return $this->db->count_all_results();
	}

	function total_wo(){
		$this->db->from("work_order_ok");
		return $this->db->count_all_results();
	}

	function kartu_stok($bulan,$tahun){
		$this->db->select(array("bahan_baku.nama_bahan","bahan_baku.sku","bahan_baku.satuan","SUM(received_item.qty) as received_item","SUM(work_item_ok.qty) as barang_keluar","SUM(retur_item.qty) as retur","SUM(waste_item.waste) as waste"));
		$this->db->from("bahan_baku");
	}

	function bahan_baku_stok(){
		$this->db->select("*");
		$this->db->from("ap_produk");
		return $this->db->get();
	}

	function total_barang_masuk($date_start,$date_end,$sku){
		$this->db->select("SUM(receive_item.qty) as qty");
		$this->db->from("receive_item");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->where("receive_item.sku",$sku);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->qty;
		}
	}

	function total_barang_keluar($date_start,$date_end,$sku){
		$this->db->select_sum('work_item_ok.qty');
		$this->db->from("work_item_ok");
		$this->db->where("work_item_ok.tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->where("work_item_ok.sku",$sku);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->qty;
		}
	}

	function total_barang_retur($bulan,$tahun,$sku){
		$this->db->select_sum("retur_item.qty");
		$this->db->from("retur_item");
		$this->db->where("MONTH(retur_item.tanggal)",$bulan);
		$this->db->where("YEAR(retur_item.tanggal)",$tahun);
		$this->db->where("retur_item.sku",$sku);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->qty;
		}
	}

	function total_barang_waste($bulan,$tahun,$sku){
		$this->db->select_sum("waste_item.qty");
		$this->db->from("waste_item");
		$this->db->where("MONTH(waste_item.tanggal)",$bulan);
		$this->db->where("YEAR(waste_item.tanggal)",$tahun);
		$this->db->where("waste_item.sku",$sku);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->qty; 
		}
	}

	function total_stok_awal($bulan,$tahun,$sku){
		$this->db->select("qty");
		$this->db->from("stock_awal");
		$this->db->where("bulan",$bulan);
		$this->db->where("tahun",$tahun);
		$this->db->where("sku",$sku);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->qty;
		}
	}

	function cek_so_bulan_ini($bulan,$tahun){
		$this->db->from("stock_opname_info");
		$this->db->where("bulan",$bulan);
		$this->db->where("tahun",$tahun);
		return $this->db->count_all_results();
	}

	function hasil_so($bulan,$tahun){
		$this->db->select("*");
		$this->db->from("stock_opname");
		$this->db->join("stock_opname_info","stock_opname.no_so = stock_opname_info.no_so","left");
		$this->db->join("bahan_baku","bahan_baku.sku = stock_opname.sku","left");
		$this->db->where("stock_opname_info.bulan",$bulan);
		$this->db->where("stock_opname_info.tahun",$tahun);
		return $this->db->get();
	}

	function status_hutang($no_receive){
		$this->db->from("hutang");
		$this->db->where("no_receive",$no_receive);
		return $this->db->count_all_results();
	}

	function data_order($no_order){
		$this->db->select("*");
		$this->db->from("work_order_produk");
		$this->db->join("ap_produk","ap_produk.id_produk = work_order_produk.id_produk","left");
		$this->db->where("work_order_produk.no_order",$no_order);
		$this->db->group_by("work_order_produk.id_produk");
		return $this->db->get();
	}

	function stok_produk_lama($id_produk){
		$this->db->select("stok");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$id_produk);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->stok;
		}
	}

	function hasil_produksi($no_order){
		$this->db->select_sum("stok");
		$this->db->from("work_order_result");
		$this->db->where("no_order",$no_order);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->stok;
		}
	}

	function hasil_produksi_peritem($id_produk,$no_order){
		$this->db->select_sum("stok");
		$this->db->from("work_order_result");
		$this->db->where("no_order",$no_order);
		$this->db->where("id_produk",$id_produk);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->stok;
		}
	}

	function data_adjust_order($id_user,$id_produk){
		$this->db->select("*");
		$this->db->from("work_order_adjust");
		$this->db->join("bahan_baku","bahan_baku.sku = work_order_adjust.sku","left");
		$this->db->where("work_order_adjust.id_user",$id_user);
		$this->db->where("work_order_adjust.id_produk",$id_produk);
		return $this->db->get();
	}

	function cek_adjust($id_user,$sku,$id_produk){
		$this->db->from("work_order_adjust");
		$this->db->where("id_user",$id_user);
		$this->db->where("sku",$sku);
		$this->db->where("id_produk",$id_produk);
		return $this->db->count_all_results();
	}

	function cek_tanggal_adjust($day,$month,$year){
		$this->db->from("work_order_no_adjust");
		$this->db->where("DAY(tanggal_adjust)",$day);
		$this->db->where("MONTH(tanggal_adjust)",$month);
		$this->db->where("YEAR(tanggal_adjust)",$year);
		return $this->db->count_all_results();
	}

	function data_adjust($no_order){
		$this->db->select("*");
		$this->db->from("work_order_no_adjust");
		$this->db->join("user","user.id_user = work_order_no_adjust.id_user","left");
		$this->db->where("no_order",$no_order);
		return $this->db->get();
	}

	function get_no_adjust($no_wo){
		$this->db->select("no_adjust");
		$this->db->from("work_order_no_adjust");
		$this->db->where("no_order",$no_wo);
		return $this->db->get();
	}

	function data_item_tambahan($no_adjust){
		$this->db->select(array("bahan_baku.nama_bahan","SUM(work_item_ok.qty) as qty","bahan_baku.satuan"));
		$this->db->from("work_item_ok");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item_ok.sku","left");
		$this->db->where("work_item_ok.no_order",$no_adjust);
		$this->db->group_by("work_item_ok.sku");
		return $this->db->get();
	}

	function rekapan_pembelian($date_start,$date_end,$supplier,$status){	

		if(empty($supplier) && empty($status)){

			$query = "SELECT purchase_order.no_po, purchase_order.tanggal_po,supplier.supplier,total_po,purchase_order.keterangan, purchase_order.status
					  FROM purchase_order 
					  LEFT JOIN (SELECT SUM(purchase_item.harga*purchase_item.qty) as total_po, purchase_item.no_po
					  			 FROM purchase_item
					  			 GROUP BY purchase_item.no_po)
					  			 as purchase_item_join ON purchase_item_join.no_po = purchase_order.no_po				  			 
					  LEFT JOIN supplier ON purchase_order.id_supplier = supplier.id_supplier
					  WHERE tanggal_po BETWEEN '$date_start' AND '$date_end'
					  GROUP BY purchase_order.no_po";
		} elseif(!empty($supplier) && empty($status)){
			$query = "SELECT purchase_order.no_po, purchase_order.tanggal_po,supplier.supplier,total_po,purchase_order.keterangan, purchase_order.status
					  FROM purchase_order 
					  LEFT JOIN (SELECT SUM(purchase_item.harga*purchase_item.qty) as total_po, purchase_item.no_po
					  			 FROM purchase_item
					  			 GROUP BY purchase_item.no_po)
					  			 as purchase_item_join ON purchase_item_join.no_po = purchase_order.no_po				  			 
					  LEFT JOIN supplier ON purchase_order.id_supplier = supplier.id_supplier
					  WHERE tanggal_po BETWEEN '$date_start' AND '$date_end' AND purchase_order.id_supplier = '$supplier'
					  GROUP BY purchase_order.no_po";
		} elseif(empty($supplier) && !empty($status)){
			$query = "SELECT purchase_order.no_po, purchase_order.tanggal_po,supplier.supplier,total_po,purchase_order.keterangan, purchase_order.status
					  FROM purchase_order 
					  LEFT JOIN (SELECT SUM(purchase_item.harga*purchase_item.qty) as total_po, purchase_item.no_po
					  			 FROM purchase_item
					  			 GROUP BY purchase_item.no_po)
					  			 as purchase_item_join ON purchase_item_join.no_po = purchase_order.no_po				  			 
					  LEFT JOIN supplier ON purchase_order.id_supplier = supplier.id_supplier
					  WHERE tanggal_po BETWEEN '$date_start' AND '$date_end' AND status='$status'
					  GROUP BY purchase_order.no_po";
		} elseif(!empty($supplier) && !empty($status)){
			$query = "SELECT purchase_order.no_po, purchase_order.tanggal_po,supplier.supplier,total_po,purchase_order.keterangan, purchase_order.status
					  FROM purchase_order 
					  LEFT JOIN (SELECT SUM(purchase_item.harga*purchase_item.qty) as total_po, purchase_item.no_po
					  			 FROM purchase_item
					  			 GROUP BY purchase_item.no_po)
					  			 as purchase_item_join ON purchase_item_join.no_po = purchase_order.no_po				  			 
					  LEFT JOIN supplier ON purchase_order.id_supplier = supplier.id_supplier
					  WHERE tanggal_po BETWEEN '$date_start' AND '$date_end' AND status='$status' AND purchase_order.id_supplier='$supplier'
					  GROUP BY purchase_order.no_po";
		}

		return $this->db->query($query);
	}

	function material_po_receive($no_po){
		$this->db->select(array("bahan_baku.nama_bahan","SUM(receive_item.qty) as delivered_qty","receive_item.price","receive_item.sku"));
		$this->db->from("receive_order");
		$this->db->join("receive_item","receive_item.no_receive = receive_order.no_receive","left");
		$this->db->join("bahan_baku","bahan_baku.sku = receive_item.sku","left");
		$this->db->where("receive_order.no_po",$no_po);
		$this->db->group_by("receive_item.sku");
		return $this->db->get();
	}

	function total_pembayaran_po($no_po){
		$query = "SELECT SUM(hutang_order.kredit) as kredit
				  FROM hutang_order 
				  LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_penagihan
	
				  WHERE receive_order.no_po='$no_po'
				  GROUP BY receive_order.no_po";
		$loop = $this->db->query($query);
		
		foreach($loop->result() as $row){
			return $row->kredit;
		}
	}

	function total_pembayaran_diskon($no_po){
		$query = "SELECT SUM(hutang_order.debit) as debit
				  FROM hutang_order 
				  LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_penagihan
				  LEFT JOIN receive_order ON receive_order.no_receive = hutang.no_receive
				  WHERE receive_order.no_po='$no_po'
				  GROUP BY receive_order.no_po";
		$loop = $this->db->query($query);
		
		foreach($loop->result() as $row){
			return $row->debit;
		}
	}

	function order_qty($no_po,$sku){
		$this->db->select_sum("qty");
		$this->db->from("purchase_item");
		$this->db->where("no_po",$no_po);
		$this->db->where("sku",$sku);
		$this->db->group_by("no_po");
		$this->db->group_by("sku");
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->qty;
		}
	}

	function rekapan_pembelian_persupplier($date_start,$date_end,$id_supplier){
		$query = "SELECT purchase_order.no_po, purchase_order.tanggal_po,supplier.supplier,total_po, total_terbayar, purchase_order.keterangan, users.first_name as nama_user, purchase_order.status, purchase_order.nilai_ppn
				  FROM purchase_order 
				  LEFT JOIN (SELECT SUM(purchase_item.harga*purchase_item.qty) as total_po, purchase_item.no_po
				  			 FROM purchase_item
				  			 GROUP BY purchase_item.no_po)
				  			 as purchase_item_join ON purchase_item_join.no_po = purchase_order.no_po
				  LEFT JOIN (SELECT SUM(hutang_order.kredit) as total_terbayar, hutang_order.no_penagihan
				  			 FROM hutang_order
				  			 GROUP BY hutang_order.no_penagihan ) as terbayar_join ON terbayar_join.no_penagihan = purchase_order.no_po 

				  LEFT JOIN supplier ON purchase_order.id_supplier = supplier.id_supplier
				  LEFT JOIN users ON users.id = purchase_order.id_pic
				  WHERE tanggal_po BETWEEN '$date_start' AND '$date_end' AND purchase_order.id_supplier='$id_supplier' AND status != '0' AND status != '2'
				  GROUP BY purchase_order.no_po";
		return $this->db->query($query);
	}


	function rekapan_pembelian_purchasing($date_start,$date_end,$id,$status){
		$query = "SELECT purchase_order.no_po, purchase_order.tanggal_po,supplier.supplier,total_po, total_terbayar, purchase_order.keterangan, users.first_name as nama_user, purchase_order.status, purchase_order.nilai_ppn
				  FROM purchase_order 
				  LEFT JOIN (SELECT SUM(purchase_item.harga*purchase_item.qty) as total_po, purchase_item.no_po
				  			 FROM purchase_item
				  			 GROUP BY purchase_item.no_po)
				  			 as purchase_item_join ON purchase_item_join.no_po = purchase_order.no_po
				  LEFT JOIN (SELECT SUM(hutang_order.kredit) as total_terbayar, hutang_order.no_penagihan
				  			 FROM hutang_order
				  			 GROUP BY hutang_order.no_penagihan ) as terbayar_join ON terbayar_join.no_penagihan = purchase_order.no_po 

				  LEFT JOIN supplier ON purchase_order.id_supplier = supplier.id_supplier
				  LEFT JOIN users ON users.id = purchase_order.id_pic
				  WHERE tanggal_po BETWEEN '$date_start' AND '$date_end' AND purchase_order.id_pic='$id' AND purchase_order.status='$status'
				  GROUP BY purchase_order.no_po";
		return $this->db->query($query);
	}

	function rekapan_pembelian_persupplier_rangkum($date_start,$date_end){
		$query = "SELECT purchase_order.no_po, purchase_order.tanggal_po,supplier.supplier,total_po, total_terbayar, purchase_order.keterangan, users.first_name nama_user, purchase_order.status, purchase_order.nilai_ppn
				  FROM purchase_order 
				  LEFT JOIN (SELECT SUM(purchase_item.harga*purchase_item.qty) as total_po, purchase_item.no_po
				  			 FROM purchase_item
				  			 GROUP BY purchase_item.no_po)
				  			 as purchase_item_join ON purchase_item_join.no_po = purchase_order.no_po
				  LEFT JOIN (SELECT SUM(hutang_order.kredit) as total_terbayar, hutang_order.no_penagihan
				  			 FROM hutang_order
				  			 GROUP BY hutang_order.no_penagihan ) as terbayar_join ON terbayar_join.no_penagihan = purchase_order.no_po 

				  LEFT JOIN supplier ON purchase_order.id_supplier = supplier.id_supplier
				  LEFT JOIN users ON users.id = purchase_order.id_pic
				  WHERE tanggal_po BETWEEN '$date_start' AND '$date_end' AND status != '0' AND status != '2'
				  GROUP BY purchase_order.no_po";
		return $this->db->query($query);
	}

	function pembelian_perbarang($date_start,$date_end,$sku){
		$this->db->select(array("purchase_item.no_po","purchase_order.tanggal_po","supplier.supplier","purchase_item.qty","purchase_item.harga","purchase_item.sku"));
		$this->db->from("purchase_item");
		$this->db->join("purchase_order","purchase_order.no_po = purchase_item.no_po","left");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->where("purchase_order.tanggal_po BETWEEN '$date_start' AND '$date_end'");
		$this->db->where("purchase_item.sku",$sku);
		$this->db->group_by("purchase_item.no_po");
		return $this->db->get();
	}

	function laporan_cogs($bulan,$tahun){
		$this->db->select(array("SUM(work_item_ok.qty) as qty","work_item_ok.sku","bahan_baku.nama_bahan","bahan_baku.satuan","bahan_baku.harga"));
		$this->db->from("work_item_ok");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item_ok.sku","left");
		$this->db->where("MONTH(work_item_ok.tanggal)",$bulan);
		$this->db->where("YEAR(work_item_ok.tanggal)",$tahun);
		$this->db->group_by("work_item_ok.sku");
		return $this->db->get();
	}

	function laporan_cogs_pervarian($id_produk,$bulan,$tahun){
		$this->db->select(array("SUM(work_item_ok.qty) as qty","work_item_ok.sku","bahan_baku.nama_bahan","bahan_baku.satuan"));
		$this->db->from("work_item_ok");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item_ok.sku","left");
		$this->db->where("MONTH(work_item_ok.tanggal)",$bulan);
		$this->db->where("YEAR(work_item_ok.tanggal)",$tahun);
		$this->db->where("work_item_ok.id_produk",$id_produk);
		$this->db->group_by("work_item_ok.sku");
		return $this->db->get();
	}

	function target_produksi($id_produk,$bulan,$tahun){
		$this->db->select_sum("jumlah_produksi");
		$this->db->from("work_order_produk");
		$this->db->where("id_produk",$id_produk);
		$this->db->where("MONTH(tanggal)",$bulan);
		$this->db->where("YEAR(tanggal)",$tahun);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->jumlah_produksi;
		}
	}

	function hasil_produksi_total($id_produk,$bulan,$tahun){
		$this->db->select_sum("stok");
		$this->db->from("work_order_result");
		$this->db->where("id_produk",$id_produk);
		$this->db->where("MONTH(tanggal)",$bulan);
		$this->db->where("YEAR(tanggal)",$tahun);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->stok;
		}
	}

	function data_waste($bulan,$tahun){
		$this->db->select(array("bahan_baku.nama_bahan","bahan_baku.sku","SUM(qty) as qty","keterangan_waste.keterangan","bahan_baku.satuan"));
		$this->db->from("waste_item");
		$this->db->join("keterangan_waste","keterangan_waste.id_keterangan = waste_item.id_keterangan","left");
		$this->db->join("bahan_baku","bahan_baku.sku = waste_item.sku","left");
		$this->db->where("MONTH(tanggal)",$bulan);
		$this->db->where("YEAR(tanggal)",$tahun);
		$this->db->group_by("waste_item.sku");
		return $this->db->get();
	}

	function stok_minimum(){
		$this->db->select("*");
		$this->db->from("data_stok");
		$this->db->join("bahan_baku","bahan_baku.sku = data_stok.sku","left");
		$this->db->where("stok < 100");
		return $this->db->get();
	}

	function data_tagihan_dashboard(){
		$this->db->select(array("hutang.no_tagihan","hutang.deadline_pembayaran","hutang.no_receive","hutang.tanggal_kedatangan","hutang.tipe_pembayaran","hutang.status_hutang","SUM(receive_item.price*receive_item.qty) as value","users.first_name nama_user"));
		$this->db->from("hutang");
		$this->db->join("receive_item","receive_item.no_receive = hutang.no_receive","left");
		$this->db->join("receive_order","receive_order.no_receive = hutang.no_receive");
		$this->db->join("users","users.id = receive_order.id_pic","left");
		$this->db->where("hutang.status_hutang",0);
		$this->db->or_where("hutang.status_hutang",1);
		$this->db->group_by("receive_item.no_receive");
		$this->db->order_by("hutang.deadline_pembayaran","ASC");
		return $this->db->get();		
	}

	function cek_status_navigasi($id_user,$access){
		$this->db->select("status");
		$this->db->from("user_access");
		$this->db->where("id_user",$id_user);
		$this->db->where("access_level",$access);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->status;
		}
	}

	function get_data_coa(){
		$this->db->select("*");
		$this->db->from("ac_account");
		$this->db->join("ac_head_account","ac_head_account.id_head_account = ac_account.id_head_account","left");
		$this->db->join("ac_sub_account","ac_sub_account.id_sub_account = ac_account.id_sub_account","left");
		return $this->db->get();	
	}

	function expand_data_barang_spending($sku){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.harga","ap_produk.stok","bahan_baku.harga as hargaBeli"));
		$this->db->from("ap_produk");
		$this->db->join("bahan_baku","bahan_baku.sku = ap_produk.id_produk");
		$this->db->where("ap_produk.id_produk",$sku);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function total_pengeluaran(){
		$this->db->from("sp_no_bahan_keluar");
		return $this->db->count_all_results();
	}

	function info_pengeluaran($no_keluaran){
		$this->db->select("*");
		$this->db->from("sp_no_bahan_keluar");
		$this->db->join("users","users.id = sp_no_bahan_keluar.id_user","left");
		$this->db->join("ap_store","ap_store.id_store = sp_no_bahan_keluar.store_tujuan","left");
		$this->db->where("no_bahan_keluar",$no_keluaran);
		return $this->db->get();
	}

	function spending_item($no_keluaran){
		$this->db->select("*");
		$this->db->from("sp_bahan_keluar");	
		$this->db->join("ap_produk","ap_produk.id_produk = sp_bahan_keluar.sku","left");
        $this->db->join("sp_no_bahan_keluar","sp_no_bahan_keluar.no_bahan_keluar = sp_bahan_keluar.no_bahan_keluar","left");
        $this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk and ap_produk_price.id_toko=sp_no_bahan_keluar.store_tujuan","left");
        
		$this->db->where("sp_bahan_keluar.no_bahan_keluar",$no_keluaran);
		return $this->db->get();
	}

	function proyeksi_produk_join(){
		$this->db->select(array("ap_produk.nama_produk","proyeksi_bahan_baku.qty","proyeksi_bahan_baku.id_produk"));
		$this->db->from("proyeksi_bahan_baku");
		$this->db->join("ap_produk","ap_produk.id_produk = proyeksi_bahan_baku.id_produk","left");
		return $this->db->get();
	}

	function proyeksi_kebutuhan($id_produk){
		$this->db->select(array("(ap_produk_bahan_baku.qty) as qty","bahan_baku.nama_bahan","data_stok.stok","bahan_baku.satuan"));
		$this->db->from("ap_produk_bahan_baku");
		$this->db->join("bahan_baku","bahan_baku.sku = ap_produk_bahan_baku.sku","left");
		$this->db->join("data_stok","data_stok.sku = ap_produk_bahan_baku.sku","left");
		$this->db->where("ap_produk_bahan_baku.id_produk",$id_produk);
		$this->db->group_by("ap_produk_bahan_baku.sku");
		return $this->db->get();
	}

	function jumlah_proyeksi(){
		$this->db->select_sum("qty");
		$this->db->from("proyeksi_bahan_baku");
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->qty;
		}
	}

	function rekap_proyeksi(){
		$this->db->select(array("SUM(ap_produk_bahan_baku.qty) as total_qty","bahan_baku.nama_bahan","bahan_baku.satuan","data_stok.stok"));
		$this->db->from("ap_produk_bahan_baku");
		$this->db->join("bahan_baku","bahan_baku.sku = ap_produk_bahan_baku.sku","left");
		$this->db->join("data_stok","data_stok.sku = ap_produk_bahan_baku.sku");
		$this->db->group_by("ap_produk_bahan_baku.sku");
		return $this->db->get();
	}

	function data_varian(){
		$this->db->select("*");
		$this->db->from("proyeksi_bahan_baku");
		$this->db->join("ap_produk","ap_produk.id_produk = proyeksi_bahan_baku.id_produk","left");
		$this->db->group_by("proyeksi_bahan_baku.id_produk");
		return $this->db->get();
	}

	function quantity_varian($id_produk){
		$this->db->select("qty");
		$this->db->from("proyeksi_bahan_baku");
		$this->db->where("id_produk",$id_produk);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->qty;
		}
	}

	function rekapan_penerimaan($bulan,$tahun){
		$query 	= "SELECT bahan_baku.sku, bahan_baku.nama_bahan, penerimaan_barang
				   FROM bahan_baku
				   LEFT JOIN (SELECT SUM(receive_item.qty) as penerimaan_barang, receive_item.sku FROM receive_item WHERE MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun'

				   ) as receive_join ON receive_join.sku = bahan_baku.sku
				   WHERE (bahan_baku.status=1 AND bahan_baku.del=1)
				   GROUP BY bahan_baku.sku";
		return $this->db->query($query);
	}

	function data_stok_awal($bulan,$tahun,$sku){
		$this->db->select("qty");
		$this->db->from("stock_awal");
		$this->db->where("bulan",$bulan);
		$this->db->where("tahun",$tahun);	
		$this->db->where("sku",$sku);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->qty;
		}
	}

	function supplier_dropdown($supplier){
		$this->db->select("*");
		$this->db->from("supplier");
		$this->db->where("id_supplier",$supplier);
		return $this->db->get();
	}

	function get_id_produk($sku){
		$this->db->select("id_produk");
		$this->db->from("ap_produk_bahan_baku");
		$this->db->where("sku",$sku);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->id_produk;
		}
	}

	/** SKU/kode excel -> id_produk (ap_produk langsung, fallback ap_produk_bahan_baku) */
	function resolve_sku_to_id_produk($code){
		$code = trim((string) $code);
		if ($code === '') {
			return '';
		}
		$this->db->select('id_produk');
		$this->db->from('ap_produk');
		$this->db->where('id_produk', $code);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			return $q->row()->id_produk;
		}
		$fb = $this->get_id_produk($code);
		return $fb !== null && $fb !== '' ? $fb : '';
	}

	function get_tipe_produk($id_produk){
		$this->db->select("type");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$id_produk);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->type;
		}
	}

	function get_stok_lama_produk($id_produk){
		$this->db->select("stok");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$id_produk);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->stok;
		}
	}

	function get_stok_lama_produk_store($id_produk,$id_store){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$id_produk);
		$this->db->where("id_store",$id_store);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->stok;
		}
	}

	function item_so($no_so){
		$this->db->select("*");
		$this->db->from("stock_opname");
		$this->db->join("ap_produk","ap_produk.id_produk = stock_opname.sku","left");
		$this->db->where("no_so",$no_so);
		return $this->db->get();
	}

	function view_kartu_stok_harian($date_start,$date_end,$sku){
		$this->db->select("*");
		$this->db->from("kartu_stok");
		$this->db->join("bahan_baku","bahan_baku.sku = kartu_stok.id_produk","left");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->where("id_produk",$sku);
		return $this->db->get();
	}

	function stok_awal($date_start,$date_end,$sku){
		$this->db->select("*");
		$this->db->from("kartu_stok");
		$this->db->join("bahan_baku","bahan_baku.sku = kartu_stok.id_produk","left");
		$this->db->limit(1,0);
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->where("id_produk",$sku);
		$this->db->order_by("id","ASC");
		return $this->db->get();
	}

	function qty_received($id,$no_po){
		$this->db->select("qty");
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive","left");
		$this->db->where("receive_order.no_po",$no_po);
		$this->db->where("receive_item.sku",$id);
		$query = $this->db->get();

		foreach($query->result() as $dt){
			return $dt->qty;
		}
	}

	function qtyDiterima($id,$no_po){
		$this->db->select("SUM(receive_item.qty) as qty");
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive","left");
		$this->db->where("receive_order.no_po",$no_po);
		$this->db->where("receive_item.sku",$id);
		$query = $this->db->get();

		foreach($query->result() as $dt){
			return $dt->qty;
		}
	}

	function cek_penerbitan_hutang($no_po){
		$this->db->from("hutang");
		$this->db->where("no_tagihan",$no_po);
		return $this->db->count_all_results();
	}

	function pembelian_barang_terbanyak(){
		$this->db->select(array("SUM(receive_item.qty) as jumlah_beli","bahan_baku.nama_bahan","bahan_baku.sku"));
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive","left");
		$this->db->join("bahan_baku","bahan_baku.sku = receive_item.sku","left");
		$this->db->group_by("receive_item.sku");
		$this->db->order_by("jumlah_beli","DESC");
		return $this->db->get();
	}

	function pembelian_barang_terbanyak_filter($date_start,$date_end){
		$this->db->select(array("SUM(receive_item.qty) as jumlah_beli","bahan_baku.nama_bahan","bahan_baku.sku"));
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive","left");
		$this->db->join("bahan_baku","bahan_baku.sku = receive_item.sku","left");
		$this->db->where("receive_item.tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->group_by("receive_item.sku");
		$this->db->order_by("jumlah_beli","DESC");
		return $this->db->get();
	}

	function gr_persupplier($date_start,$date_end,$supplier){
		$this->db->select("*");
		$this->db->from("receive_order");
		$this->db->join("user","user.id_user = receive_order.id_pic","left");
		$this->db->join("supplier","supplier.id_supplier = receive_order.id_supplier","left");
		$this->db->where("receive_order.tanggal_terima BETWEEN '$date_start' AND '$date_end'");
		$this->db->where("receive_order.id_supplier",$supplier);
		$this->db->group_by("receive_order.no_receive");
		return $this->db->get();	
	}

	function gr_berdasarkan_penerima($date_start,$date_end,$penerima){
		$this->db->select("*");
		$this->db->from("receive_order");
		$this->db->join("user","user.id_user = receive_order.id_pic","left");
		$this->db->join("supplier","supplier.id_supplier = receive_order.id_supplier","left");
		$this->db->where("receive_order.tanggal_terima BETWEEN '$date_start' AND '$date_end'");
		$this->db->like("receive_order.received_by",$penerima);
		$this->db->group_by("receive_order.no_receive");
		return $this->db->get();
	}

	function gr_berdasarkan_po($no_po){
		$this->db->select("*");
		$this->db->from("receive_order");
		$this->db->join("user","user.id_user = receive_order.id_pic","left");
		$this->db->join("supplier","supplier.id_supplier = receive_order.id_supplier","left");
		$this->db->where("receive_order.no_po",$no_po);
		$this->db->group_by("receive_order.no_receive");
		return $this->db->get();
	}

	function rangkuman_penerimaan($date_start,$date_end){
		$this->db->select("*");
		$this->db->from("receive_order");
		$this->db->join("user","user.id_user = receive_order.id_pic","left");
		$this->db->join("supplier","supplier.id_supplier = receive_order.id_supplier","left");
		$this->db->where("receive_order.tanggal_terima BETWEEN '$date_start' AND '$date_end'");
		$this->db->group_by("receive_order.no_receive");
		return $this->db->get();	
	}

	function nama_supplier($id){
		$this->db->select("supplier");
		$this->db->from("supplier");
		$this->db->where("id_supplier",$id);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->supplier;
		}	
	}

	function gr_perbarang($date_start,$date_end,$sku){
		$this->db->select(array("receive_item.no_receive","receive_item.tanggal","receive_order.received_by","receive_order.checked_by","receive_item.qty"));
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive","left");
		$this->db->where("receive_item.tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->where("receive_item.sku",$sku);
		$this->db->order_by("receive_item.tanggal","DESC");
		return $this->db->get();
	}

	function retur_persupplier($date_start,$date_end,$supplier){
		$this->db->select(array("retur.no_retur","retur.no_receive","users.first_name as nama_user","retur.tanggal_retur"));
		$this->db->from("retur");
		$this->db->join("receive_order","receive_order.no_receive = retur.no_receive","left");
		$this->db->join("users","users.id = retur.id_pic");
		$this->db->where("receive_order.id_supplier",$supplier);
		$this->db->where("retur.tanggal_retur BETWEEN '$date_start 00:00:00' AND '$date_end 23:59:59'");
		$this->db->group_by("retur.no_retur");
		return $this->db->get();
	}

	function harga_beli($sku){
		$this->db->select("harga");
		$this->db->from("bahan_baku");
		$this->db->where("sku",$sku);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->harga;
		}
	}

	function item_transfer_temp($id_user){
		$this->db->select("*");
		$this->db->from("transfer_stok_temp");
		$this->db->join("ap_produk","ap_produk.id_produk = transfer_stok_temp.sku","left");
		$this->db->where("transfer_stok_temp.id_user",$id_user);
		return $this->db->get();
	}

	function satuan_barang($sku){
		$this->db->select("satuan");
		$this->db->from("bahan_baku");
		$this->db->where("sku",$sku);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->satuan;
		}
	}

	function harga_terakhir_beli($sku){
		$this->db->select("harga");
		$this->db->from("purchase_item");
		$this->db->where("sku",$sku);
		$this->db->order_by("tanggal","DESC");
		$this->db->limit(1,0);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->harga;
		}
	}

	function cek_no_request(){

		$day 	= date('d');
		$month  = date('m');
		$year 	= date('Y');

		$this->db->from("rq_purchase_no");
		$this->db->where("MONTH(tanggal_request)",$month);
		$this->db->where("YEAR(tanggal_request)",$year);
		return $this->db->count_all_results();
	}

	function wait_approve_list(){
		$this->db->select("*");
		$this->db->from("rq_purchase_no");
		$this->db->join("user","user.id_user = rq_purchase_no.id_pic","left");
		$this->db->join("bahan_baku","bahan_baku.sku = rq_purchase_no.sku","left");
		$this->db->where("rq_purchase_no.status",0);
		$this->db->order_by("tanggal_request","DESC");
		return $this->db->get();
	}


	function data_request($no_request){
		$this->db->select("*");
		$this->db->from("rq_purchase_no");
		$this->db->join("user","user.id_user = rq_purchase_no.id_pic","left");
		$this->db->join("bahan_baku","bahan_baku.sku = rq_purchase_no.sku","left");
		$this->db->order_by("tanggal_request","DESC");
		$this->db->where("rq_purchase_no.purchase_no",$no_request);
		return $this->db->get();
	}

	function item_request($no_request){
		$this->db->select(array("rq_purchase_item.harga","supplier.supplier","rq_purchase_item.remark","rq_purchase_item.id","rq_purchase_item.sku","rq_purchase_item.isChoose"));
		$this->db->from("rq_purchase_item");
		$this->db->join("supplier","supplier.id_supplier = rq_purchase_item.id_supplier","left");
		$this->db->where("rq_purchase_item.purchase_no",$no_request);
		return $this->db->get();
	}

	function daftar_request_approved(){
		$this->db->select("*");
		$this->db->from("rq_purchase_no");
		$this->db->join("user","user.id_user = rq_purchase_no.id_pic","left");
		$this->db->join("bahan_baku","bahan_baku.sku = rq_purchase_no.sku","left");
		$this->db->join("rq_purchase_approved","rq_purchase_approved.purchase_no = rq_purchase_no.purchase_no","left");
		$this->db->where("rq_purchase_no.status",1);
		$this->db->order_by("tanggal_request","DESC");
		return $this->db->get();
	}

	function cogs_perbahan_baku($date_start,$date_end,$sku){
		$this->db->select(array("work_item_ok.tanggal","work_item_ok.sku","bahan_baku.nama_bahan","bahan_baku.satuan","SUM(work_item_ok.qty) as qty"));
		$this->db->from("work_item_ok");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item_ok.sku","left");
		$this->db->where("work_item_ok.sku",$sku);
		$this->db->where("work_item_ok.tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->group_by("work_item_ok.tanggal");
		return $this->db->get();
	}

	function history_produksi($no_order){
		$this->db->select(array("ap_produk.nama_produk","work_order_result.tanggal","work_order_result.stok"));
		$this->db->from("work_order_result");
		$this->db->join("ap_produk","ap_produk.id_produk = work_order_result.id_produk","left");
		$this->db->where("no_order",$no_order);
		return $this->db->get();
	}


	function rekap_penerimaan($start,$end){
		$this->db->select(array("bahan_baku.nama_bahan","bahan_baku.satuan","SUM(receive_item.qty) as qty"));
		$this->db->from("receive_item");
		$this->db->join("bahan_baku","bahan_baku.sku = receive_item.sku","left");
		$this->db->where("receive_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->group_by("receive_item.sku");
		return $this->db->get();
	}

	function sisa_po($date_start,$date_end){
		$query = "SELECT bahan_baku.nama_bahan,bahan_baku.satuan,SUM(purchase_item.qty) as qty_order, SUM(receive_item.qty) as qty_delivered
				  FROM purchase_item
				  LEFT JOIN receive_order ON receive_order.no_po = purchase_item.no_po

				  LEFT JOIN (SELECT SUM(receive_item.qty) as qty_delivered
				  			 FROM receive_item) as receive_item_join ON receive_item_join.no_receive = receive_order.no_receive
				  LEFT JOIN bahan_baku ON bahan_baku.sku = purchase_item.sku
				  WHERE purchase_item.tanggal BETWEEN '$date_start' AND '$date_end'
				  GROUP BY purchase_item.sku";
		return $this->db->query($query);
	}

	function cogs_pevarian($date_start,$date_end,$id_produk){
		$this->db->select(array("bahan_baku.nama_bahan","bahan_baku.satuan","SUM(work_item_ok.qty) as qty","work_item_ok.sku"));
		$this->db->from("work_item_ok");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item_ok.sku","left");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->where("id_produk",$id_produk);
		$this->db->group_by("work_item_ok.sku");
		return $this->db->get();
	}
		
	function akumulasi_cogs($date_start,$date_end){
		$this->db->select(array("bahan_baku.nama_bahan","bahan_baku.satuan","SUM(work_item_ok.qty) as qty","work_item_ok.sku"));
		$this->db->from("work_item_ok");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item_ok.sku","left");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->group_by("work_item_ok.sku");
		return $this->db->get();
	}

	function penggunaan_berdasarkan_qty($date_start,$date_end){
		$this->db->select(array("bahan_baku.nama_bahan","bahan_baku.satuan","SUM(work_item_ok.qty) as qty","work_item_ok.sku"));
		$this->db->from("work_item_ok");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item_ok.sku","left");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->group_by("work_item_ok.sku");
		$this->db->order_by("qty","DESC");
		return $this->db->get();
	}

	function penggunaan_berdasarkan_nominal($date_start,$date_end){
		$this->db->select(array("bahan_baku.nama_bahan","bahan_baku.satuan","SUM(work_item_ok.qty) as qty","SUM(work_item_ok.qty*bahan_baku.harga) as harga","work_item_ok.sku"));
		$this->db->from("work_item_ok");
		$this->db->join("bahan_baku","bahan_baku.sku = work_item_ok.sku","left");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->group_by("work_item_ok.sku");
		$this->db->order_by("harga","DESC");
		return $this->db->get();
	}

	function produksi_pervarian($date_start,$date_end){
		$query = "SELECT ap_produk.nama_produk, SUM(work_order_produk.jumlah_produksi) as jumlah_produksi, finish_goods, produk_terjual
				  FROM work_order_produk 
				  LEFT JOIN ap_produk ON ap_produk.id_produk = work_order_produk.id_produk
				  LEFT JOIN (SELECT SUM(work_order_result.stok) as finish_goods, work_order_result.id_produk
				  			 FROM work_order_result 
				  			 WHERE work_order_result.tanggal BETWEEN '$date_start 00:00:00' AND '$date_end 23:59:59'
				  			 GROUP BY work_order_result.id_produk) as work_order_join ON work_order_join.id_produk = work_order_produk.id_produk
				  LEFT JOIN (SELECT SUM(ap_invoice_item.qty) as produk_terjual, ap_invoice_item.id_produk
				  			 FROM ap_invoice_item
				  			 WHERE ap_invoice_item.tanggal BETWEEN '$date_start' AND '$date_end'
				  			 GROUP BY ap_invoice_item.id_produk) as sales_join ON sales_join.id_produk = work_order_produk.id_produk
				  WHERE work_order_produk.tanggal BETWEEN '$date_start' AND '$date_end'
				  GROUP BY ap_produk.id_produk";

		return $this->db->query($query);
	}

	function kartu_stok_finish_goods($date_start,$date_end){
		$query 	= "SELECT ap_produk.nama_produk, barang_masuk, barang_keluar, ap_produk.id_produk
				   FROM ap_produk
				   LEFT JOIN (SELECT SUM(receive_item.qty) as barang_masuk, receive_item.sku
							  FROM receive_item 
							  WHERE receive_item.tanggal BETWEEN '$date_start' AND '$date_end'
							  GROUP BY receive_item.sku) as receive_join ON receive_join.sku = ap_produk.id_produk
				   LEFT JOIN (SELECT SUM(ap_invoice_item.qty) as barang_keluar, ap_invoice_item.id_produk
							  FROM ap_invoice_item 
							  WHERE ap_invoice_item.tanggal BETWEEN '$date_start' AND '$date_end'
							  GROUP BY ap_invoice_item.id_produk) as sales_join ON sales_join.id_produk = ap_produk.id_produk
				   WHERE ap_produk.type='1'
				   GROUP BY ap_produk.id_produk";
		return $this->db->query($query);
	}

	function get_customer($limit,$start){
		$this->db->select("*");
		$this->db->from("ap_customer");
		$this->db->join("ap_customer_group","ap_customer_group.id_group = ap_customer.kategori","left");
		$this->db->join("ae_provinsi","ae_provinsi.id_provinsi = ap_customer.id_provinsi","left");
		$this->db->join("ae_kabupaten","ae_kabupaten.kabupaten_id = ap_customer.id_kabupaten","left");
		$this->db->join("ae_kecamatan","ae_kecamatan.id_kecamatan = ap_customer.id_kecamatan","left");
		$this->db->order_by("ap_customer.id_customer","DESC");
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function get_customer_sort($query,$kategori){
		$this->db->select("*");
		$this->db->from("ap_customer");
		$this->db->join("ap_customer_group","ap_customer_group.id_group = ap_customer.kategori","left");
		$this->db->join("ae_provinsi","ae_provinsi.id_provinsi = ap_customer.id_provinsi","left");
		$this->db->join("ae_kabupaten","ae_kabupaten.kabupaten_id = ap_customer.id_kabupaten","left");
		$this->db->join("ae_kecamatan","ae_kecamatan.id_kecamatan = ap_customer.id_kecamatan","left");
		// $this->db->like("ap_customer.nama",$query);
		// $this->db->or_like("ap_customer.kontak",$query);
		if(!empty($query)){
			$this->db->where("(ap_customer.nama like '%$query%' or ap_customer.id_customer like '%$query%' or ap_customer.kontak like '%$query%' or ap_customer.tanggal_lahir like '%$query%') ");
		}
		if(!empty($kategori)){
			$this->db->where("ap_customer.kategori",$kategori);
		}
		//$this->db->order_by("ap_customer.id_customer","DESC");
		
		//$this->db->limit($limit,$start);
		return $this->db->count_all_results();
	}

	function get_customer_select2($query){
		$this->db->select(array("id_customer","nama","diskon"));
		$this->db->from("ap_customer");
		$this->db->like("nama",$query);
		$this->db->where("activated",1);
		return $this->db->get();
	}

	function get_diskon_customer($id){
		$this->db->select("diskon");
		$this->db->from("ap_customer");
		$this->db->where("id_customer",$id);
		$query = $this->db->get();

		foreach($query->result() as $dt){
			return $dt->diskon;
		}
	}

	function count_invoice($tanggal){
		$this->db->from("ap_invoice_number");
		$this->db->like("tanggal",$tanggal);
		return $this->db->count_all_results();
	}

	function total_penjualan($status){
		$this->db->from("ap_invoice_number");
		$this->db->where("status",$status);
		return $this->db->count_all_results();
	}

	function total_penjualan_all($idStore='',$idUser=''){
		$this->db->from("ap_invoice_number");
        if ($idUser!=1 && $idUser!=22){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		return $this->db->count_all_results();
	}

	function poin_pembelian(){
		$this->db->select("poin_pembelian");
		$this->db->from("poin");
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->poin_pembelian;
		}
	}

	function nilai_pembelian(){
		$this->db->select("nilai_pembelian");
		$this->db->from("poin");
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->nilai_pembelian;
		}
	}

	function tipe_customer($id_customer){
		$this->db->select("kategori");
		$this->db->from("ap_customer");
		$this->db->where("id_customer",$id_customer);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->kategori;
		}
	}

	function virtual_warehouse($sku,$id_customer){
		$this->db->from("virtual_warehouse");
		$this->db->where("id_produk",$sku);
		$this->db->where("id_customer",$id_customer);
		return $this->db->count_all_results();
	}

	function stok_lama_toko($id_produk,$id_store){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$id_produk);
		$this->db->where("id_store",$id_store);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->stok;
		}
	}

	function get_distributor(){
		$this->db->select(array("id_customer","nama"));
		$this->db->from("ap_customer");
		$this->db->where("kategori",4);
		$this->db->or_where("kategori",5);
		return $this->db->get();
	}

	function data_stok_distributor($id_distributor){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","stok_store.stok","ap_kategori.kategori","bahan_baku.harga"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk","left");
		$this->db->join("ap_kategori","ap_produk.id_kategori = ap_kategori.id_kategori","left");
		$this->db->join("bahan_baku","bahan_baku.sku = stok_store.id_produk","left");
		$this->db->where("id_store",$id_distributor);
		$this->db->group_by("stok_store.id_produk");
		return $this->db->get();
	}

	function count_tab($val){
		$this->db->from("ap_invoice_number");
		$this->db->where("ap_invoice_number.status",$val);
		return $this->db->count_all_results();
	}

	function nama_distributor($id_distributor){
		$this->db->select("nama");
		$this->db->from("ap_customer");
		$this->db->where("id_customer",$id_distributor);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->nama;
		}
	}

	function nama_toko($id){
		$this->db->select("store");
		$this->db->from("ap_store");
		$this->db->where("id_store",$id);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->store;
		}
	}

	function id_store_setoran($no_setor){
		$this->db->select("id_toko");
		$this->db->from("setoran_kasir");
		$this->db->where("no_setor",$no_setor);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->id_toko;
		}
	}
	function getIdKasirSetor($id){
		$this->db->select("id_user");
		$this->db->from("setoran_kasir");
		$this->db->where("no_setor",$id);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->id_user;
		}
	}

	function id_store_invoice($no_invoice){
		$this->db->select("id_toko");
		$this->db->from("ap_invoice_number");
		$this->db->where("no_invoice",$no_invoice);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->id_toko;
		}
	}

	function insert_toko($nama_toko,$alamat,$footer,$kontak){
		$data_toko = array(
								"store"		=> $nama_toko,
								"alamat"	=> $alamat,
								"footer"	=> $footer,
								"kontak"	=> $kontak
						  );

		$this->db->insert("ap_store",$data_toko);
		$affect = $this->db->affected_rows();

		if($affect > 0){
			return TRUE;
		}
	}

	function edit_toko($nama_toko,$alamat,$footer,$id,$kontak){
		$data_toko = array(
								"store"		=> $nama_toko,
								"alamat"	=> $alamat,
								"footer"	=> $footer,
								"kontak"	=> $kontak
						  );

		$this->db->where("id_store",$id);
		$this->db->update("ap_store",$data_toko);
		$affect = $this->db->affected_rows();

		if($affect > 0){
			return 1;
		}
	}

	function poin_lama($id_customer){
		$this->db->select("point");
		$this->db->from("ap_customer");
		$this->db->where("ap_customer.id_customer",$id_customer);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->point;
		}
	}
	function ulang_tahun($id_customer){
		$this->db->select("tanggal_lahir");
		$this->db->from("ap_customer");
		$this->db->where("id_customer",$id_customer);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return substr($row->tanggal_lahir,-5);
		}
	}

	function tipe_kustomer($no_invoice){
		$this->db->select("ap_customer.kategori");
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer=ap_invoice_number.id_customer");
		$this->db->where("ap_invoice_number.no_invoice",$no_invoice);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->kategori;
		}
	}

	function data_customer_poin($id){
		$this->db->select(array("ap_customer.nama","ap_customer_group.group_customer","ap_customer.point"));
		$this->db->from("ap_customer");
		$this->db->join("ap_customer_group","ap_customer_group.id_group = ap_customer.kategori","left");
		$this->db->where("ap_customer.id_customer",$id);
		return $this->db->get();
	}

	function nilai_reimburs(){
		$this->db->select(array("poin_pengeluaran","nilai_pengeluaran"));
		$this->db->from("poin");
		return $this->db->get();
	}

	function total_produk_active(){
		$this->db->from("ap_produk");
		return $this->db->count_all_results();	
	}

	function data_stok_fg($limit,$start){
		$this->db->select("*");
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function data_stok_sort_fg($limit,$start,$query){
		$this->db->select("*");
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->like("ap_produk.nama_produk",$query);
		$this->db->or_like("ap_produk.id_produk",$query);
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function data_stok_all_fg($kategori,$subKategori,$subKategori2){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_kategori.kategori","ap_produk.stok","bahan_baku.harga"));
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");

		if(!empty($kategori)){
			$this->db->where("ap_produk.id_kategori",$kategori);
		}

		if(!empty($subKategori)){
			$this->db->where("ap_produk.id_subkategori",$subKategori);
		}

		if(!empty($subKategori2)){
			$this->db->where("ap_produk.id_subkategori_2",$subKategori2);
		}

		return $this->db->get();
	}

	function get_finish_goods(){
		$this->db->select("*");
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->where("type",1);
		return $this->db->get();
	}

	function retur_item_sales($no_invoice){
		$this->db->select(array("ap_produk.nama_produk","ap_produk.id_produk","ap_retur_item.qty","ap_retur_item.harga"));
		$this->db->from("ap_retur_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_retur_item.id_produk","left");
		$this->db->join("ap_retur","ap_retur.no_retur = ap_retur_item.no_retur","left");
		$this->db->where("ap_retur.no_invoice",$no_invoice);
		$this->db->group_by("ap_retur_item.id_produk");
		return $this->db->get()->result();
	}

	function data_piutang(){
		$this->db->select(array("ap_piutang.no_invoice","ap_customer.nama","ap_piutang.jatuh_tempo","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.poin_value","ap_invoice_number.diskon_free","ap_piutang.status"));
		$this->db->from("ap_piutang");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_piutang.no_invoice","left");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->group_by("ap_piutang.no_invoice");
		$this->db->order_by("ap_piutang.jatuh_tempo","DESC");
		return $this->db->get()->result();
	}

	function piutang_terbayar($no_invoice){
		$this->db->select(array("ap_piutang_pay.no_seri","ap_piutang_pay.no_invoice","users.first_name as nama_user","ap_piutang_payment_type.payment_type","ap_piutang_payment_account.account","ap_piutang_pay.nominal","ap_piutang_pay.keterangan","ap_piutang_pay.tanggal"));
		$this->db->from("ap_piutang_pay");
		$this->db->join("users","users.id = ap_piutang_pay.id_pic","left");
		$this->db->join("ap_piutang_payment_type","ap_piutang_payment_type.id = ap_piutang_pay.id_payment","left");
		$this->db->join("ap_piutang_payment_account","ap_piutang_payment_account.id_account = ap_piutang_pay.account","left");
		$this->db->where("no_invoice",$no_invoice);
		$this->db->group_by("ap_piutang_pay.no_seri");
		return $this->db->get();
	}

	function cek_piutang_payment(){

		$month = date('m');
		$year  = date('Y');

		$date = $year."-".$month;

		$this->db->from("ap_piutang_pay");
		$this->db->like("tanggal",$date);
		return $this->db->count_all_results();
	}

	function get_status_trx($no_invoice){
		$this->db->select("status");
		$this->db->from("ap_piutang");
		$this->db->where("no_invoice",$no_invoice);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->status;
		}
	}

	function penjualan_perbarang($start,$end,$id_produk){
		$this->db->select(array("ap_invoice_item.no_invoice","ap_invoice_item.tanggal","ap_invoice_item.qty","ap_invoice_item.harga_jual","(ap_invoice_item.qty*ap_invoice_item.harga_jual) as total"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
		$this->db->where("id_produk",$id_produk);
		$this->db->where("ap_invoice_number.status=1 or ap_invoice_number.status=2");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		return $this->db->get();
	}

	function info_produk($id_produk){
		$this->db->select("*");
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->where("ap_produk.id_produk",$id_produk);
		return $this->db->get()->result();
	}

	function akumulasi_penjualan($start,$end){
		$this->db->select(array("ap_invoice_number.tanggal","ap_invoice_number.no_invoice","ap_customer.nama","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get();
	}

	function penjualan_percustomer($start,$end,$id_customer){
		$this->db->select(array("ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.tanggal","ap_invoice_number.no_invoice","ap_customer.nama","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value+ap_invoice_number.diskon_otomatis)) as grand_total","ap_invoice_number.diskon_otomatis"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.id_customer",$id_customer);
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
		
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get();
	}

	function sales_perkategori($id_kategori,$start,$end){
		$this->db->select(array("ap_produk.nama_produk","ap_invoice_item.harga_jual","ap_invoice_item.qty","(ap_invoice_item.harga_jual*ap_invoice_item.qty) as total","ap_invoice_item.tanggal"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->group_start();
		$this->db->where("ap_invoice_number.status",1);
		$this->db->or_group_start();
		$this->db->where("ap_invoice_number.status",2);
		$this->db->group_end();
		$this->db->group_end();
		return $this->db->get()->result();
	}

	function penjualan_perekspedisi($start,$end,$ekspedisi){
		$this->db->select(array("ap_ekspedisi.ekspedisi","ap_invoice_number.no_resi","ap_invoice_number.tanggal","ap_invoice_number.no_invoice","ap_customer.nama","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ap_ekspedisi","ap_ekspedisi.id_ekspedisi = ap_invoice_number.id_ekspedisi","left");
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
		$this->db->where("ap_invoice_number.id_ekspedisi",$ekspedisi);
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get()->result();
	}

	function nama_ekspedisi($id_ekspedisi){
		$this->db->select("ekspedisi");
		$this->db->from("ap_ekspedisi");
		$this->db->where("id_ekspedisi",$id_ekspedisi);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->ekspedisi;
		}
	}

	function penjualan_pernilai_belanja($start,$end,$first,$second){
		$this->db->select(array("ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.diskon_otomatis","ap_invoice_number.tanggal","ap_invoice_number.no_invoice","ap_customer.nama","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
		$this->db->where("(ap_invoice_number.total+ap_invoice_number.diskon)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value) BETWEEN '$first' AND '$second'");
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get();
	}

	private function _top_customer_query_base($datestart,$dateend,$idToko,$brandIds,$groupIds){
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ap_customer_group","ap_customer_group.id_group = ap_customer.kategori","left");
		$this->db->where("ap_customer.nama is not null");
		$ds = $this->db->escape_str($datestart);
		$de = $this->db->escape_str($dateend);
		$this->db->where("ap_invoice_number.tanggal between '$ds 00:00:00' and '$de 23:59:59'");
		if (!empty($idToko)){
			$this->db->where("ap_invoice_number.id_toko",$idToko);
		}
		if (!empty($groupIds)){
			$this->db->where_in("ap_customer.kategori",$groupIds);
		}
		if (!empty($brandIds)){
			$ids = implode(',', array_map('intval',$brandIds));
			$this->db->where("EXISTS (SELECT 1 FROM ap_invoice_item ii INNER JOIN ap_produk pr ON pr.id_produk = ii.id_produk AND pr.id_brand IN ($ids) WHERE ii.no_invoice = ap_invoice_number.no_invoice)",NULL,FALSE);
		}
	}

	/** Subquery: net line total per invoice (qty*harga - diskon - tebusmurah); optional brand limits summed lines */
	private function _top_customer_line_grand_subquery_sql($datestart,$dateend,$idToko,$brandIds){
		$ds = $this->db->escape_str($datestart);
		$de = $this->db->escape_str($dateend);
		$sql = "SELECT ii.no_invoice, SUM((ii.qty * ii.harga_jual) - IFNULL(ii.diskon,0) - IFNULL(ii.tebusmurah,0)) AS line_grand_sum
			FROM ap_invoice_item ii
			INNER JOIN ap_invoice_number inv ON inv.no_invoice = ii.no_invoice
			WHERE inv.tanggal BETWEEN '$ds 00:00:00' AND '$de 23:59:59'";
		if (!empty($idToko)){
			$sql .= " AND inv.id_toko = ".(int)$idToko;
		}
		if (!empty($brandIds)){
			$ids = implode(',', array_map('intval',$brandIds));
			$sql .= " AND EXISTS (SELECT 1 FROM ap_produk prb WHERE prb.id_produk = ii.id_produk AND prb.id_brand IN ($ids))";
		}
		$sql .= " GROUP BY ii.no_invoice";
		return $sql;
	}

	private function _top_customer_grand_sql($totalsMode){
		if ($totalsMode === 'peritem'){
			return "SUM(IFNULL(line_tot.line_grand_sum,0)) as grand_total";
		}
		return "SUM((ap_invoice_number.total)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total";
	}

	private function _top_customer_join_line_tot($totalsMode,$datestart,$dateend,$idToko,$brandIds){
		if ($totalsMode === 'peritem'){
			$sub = $this->_top_customer_line_grand_subquery_sql($datestart,$dateend,$idToko,$brandIds);
			$this->db->join("($sub) line_tot","line_tot.no_invoice = ap_invoice_number.no_invoice","left",FALSE);
		}
	}

	function top_customer_count($datestart,$dateend,$idToko='',$brandIds=array(),$groupIds=array(),$totalsMode='global'){
		$this->db->select("COUNT(DISTINCT ap_invoice_number.id_customer) AS num",FALSE);
		$this->_top_customer_query_base($datestart,$dateend,$idToko,$brandIds,$groupIds);
		$this->_top_customer_join_line_tot($totalsMode,$datestart,$dateend,$idToko,$brandIds);
		$row = $this->db->get()->row();
		return $row ? (int)$row->num : 0;
	}

	function top_customer($start,$end,$datestart='',$dateend='',$idToko='',$brandIds=array(),$groupIds=array(),$totalsMode='global'){
		$grand = $this->_top_customer_grand_sql($totalsMode);
		$this->db->select(array(
			"ap_customer.kontak","ap_customer.id_customer","ap_customer.nama","MAX(ap_customer_group.group_customer) as group_customer",
			"COUNT(ap_invoice_number.no_invoice) as presence","SUM(ap_invoice_number.diskon_otomatis) as diskon_otomatis","SUM(ap_invoice_number.total) as total",
			"SUM(ap_invoice_number.ongkir) as ongkir","SUM(ap_invoice_number.diskon) as diskon","SUM(ap_invoice_number.diskon_free) as diskon_free",
			"MAX(ap_invoice_number.tanggal) as last_transaction","SUM(ap_invoice_number.poin_value) as poin_value",
			$grand
		),FALSE);
		$this->_top_customer_query_base($datestart,$dateend,$idToko,$brandIds,$groupIds);
		$this->_top_customer_join_line_tot($totalsMode,$datestart,$dateend,$idToko,$brandIds);
		$this->db->limit($start,$end);
		$this->db->group_by("ap_invoice_number.id_customer");
		$this->db->order_by("grand_total","DESC");
		return $this->db->get()->result();
	}

	function top_customer_all($datestart,$dateend,$idToko='',$brandIds=array(),$groupIds=array(),$totalsMode='global'){
		$grand = $this->_top_customer_grand_sql($totalsMode);
		$this->db->select(array(
			"ap_customer.kontak","ap_customer.id_customer","ap_customer.nama","MAX(ap_customer_group.group_customer) as group_customer",
			"COUNT(ap_invoice_number.no_invoice) as presence","SUM(ap_invoice_number.diskon_otomatis) as diskon_otomatis","SUM(ap_invoice_number.total) as total",
			"SUM(ap_invoice_number.ongkir) as ongkir","SUM(ap_invoice_number.diskon) as diskon","SUM(ap_invoice_number.diskon_free) as diskon_free",
			"MAX(ap_invoice_number.tanggal) as last_transaction","SUM(ap_invoice_number.poin_value) as poin_value",
			$grand
		),FALSE);
		$this->_top_customer_query_base($datestart,$dateend,$idToko,$brandIds,$groupIds);
		$this->_top_customer_join_line_tot($totalsMode,$datestart,$dateend,$idToko,$brandIds);
		$this->db->group_by("ap_invoice_number.id_customer");
		$this->db->order_by("grand_total","DESC");
		return $this->db->get()->result();
	}

	function top_customer_presence($start,$end,$datestart='',$dateend='',$idToko='',$brandIds=array(),$groupIds=array(),$totalsMode='global'){
		$grand = $this->_top_customer_grand_sql($totalsMode);
		$this->db->select(array(
			"ap_customer.kontak","ap_customer.id_customer","ap_customer.nama","MAX(ap_customer_group.group_customer) as group_customer",
			"MAX(ap_invoice_number.tanggal) as last_transaction","SUM(ap_invoice_number.diskon_otomatis) as diskon_otomatis","SUM(ap_invoice_number.total) as total",
			"SUM(ap_invoice_number.ongkir) as ongkir","COUNT(ap_invoice_number.no_invoice) as presence","SUM(ap_invoice_number.diskon) as diskon",
			"SUM(ap_invoice_number.diskon_free) as diskon_free","SUM(ap_invoice_number.poin_value) as poin_value",
			$grand
		),FALSE);
		$this->_top_customer_query_base($datestart,$dateend,$idToko,$brandIds,$groupIds);
		$this->_top_customer_join_line_tot($totalsMode,$datestart,$dateend,$idToko,$brandIds);
		$this->db->limit($start,$end);
		$this->db->group_by("ap_invoice_number.id_customer");
		$this->db->order_by("presence","DESC");
		return $this->db->get()->result();
	}

	function top_customer_presence_all($datestart,$dateend,$idToko='',$brandIds=array(),$groupIds=array(),$totalsMode='global'){
		$grand = $this->_top_customer_grand_sql($totalsMode);
		$this->db->select(array(
			"ap_customer.kontak","ap_customer.id_customer","ap_customer.nama","MAX(ap_customer_group.group_customer) as group_customer",
			"MAX(ap_invoice_number.tanggal) as last_transaction","SUM(ap_invoice_number.diskon_otomatis) as diskon_otomatis","SUM(ap_invoice_number.total) as total",
			"SUM(ap_invoice_number.ongkir) as ongkir","COUNT(ap_invoice_number.no_invoice) as presence","SUM(ap_invoice_number.diskon) as diskon",
			"SUM(ap_invoice_number.diskon_free) as diskon_free","SUM(ap_invoice_number.poin_value) as poin_value",
			$grand
		),FALSE);
		$this->_top_customer_query_base($datestart,$dateend,$idToko,$brandIds,$groupIds);
		$this->_top_customer_join_line_tot($totalsMode,$datestart,$dateend,$idToko,$brandIds);
		$this->db->group_by("ap_invoice_number.id_customer");
		$this->db->order_by("presence","DESC");
		return $this->db->get()->result();
	}

	function sales_kriteria($start,$end,$customer_group,$id_customer,$id_ekspedisi,$tipe_bayar,$status){
		$this->db->select(array(
									"ap_invoice_number.no_invoice",
									"ap_invoice_number.tanggal",
									"ap_customer_group.group_customer",
									"ap_customer.nama",
									"ap_invoice_number.tipe_bayar",
									"ap_invoice_number.total",
									"ap_invoice_number.ongkir",
									"ap_invoice_number.diskon",
									"ap_invoice_number.diskon_free",
									"ap_invoice_number.poin_value",
									"ap_ekspedisi.ekspedisi",
									"ap_invoice_number.no_resi"
							   ));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ap_customer_group","ap_customer_group.id_group = ap_customer.kategori","left");
		$this->db->join("ap_ekspedisi","ap_ekspedisi.ekspedisi = ap_invoice_number.id_ekspedisi","left");

		if(!empty($start) or !empty($end)){
			$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
		}

		if(!empty($customer_group)){
			$this->db->where("ap_customer_group.id_group",$customer_group);
		}

		if(!empty($id_customer)){
			$this->db->where("ap_invoice_number.id_customer",$id_customer);
		}

		if(!empty($id_ekspedisi)){
			$this->db->where("ap_invoice_number.id_ekspedisi",$id_ekspedisi);
		}

		if(!empty($tipe_bayar)){
			$this->db->where("ap_invoice_number.tipe_bayar",$tipe_bayar);
		}

		if(!empty($status)){
			$this->db->where("ap_invoice_number.status",$status);
		}

		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get()->result();

	}

	function penjualan_perkategori_customer($start,$end,$kategori){
		$this->db->select(array("ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.diskon_otomatis","ap_invoice_number.tanggal","ap_invoice_number.no_invoice","ap_customer.nama","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ap_customer_group","ap_customer_group.id_group = ap_customer.kategori","left");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
		$this->db->where("ap_customer_group.id_group",$kategori);
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get();
	}

	function nama_kategori($kategori){
		$kt = $this->db->get_where("ap_customer_group",array("id_group" => $kategori))->result();

		foreach($kt as $row){
			return $row->group_customer;
		}
	}
		
	function customer_search($q){
		$this->db->select(array("no_kartu","id_customer","nama","point"));
		$this->db->from("ap_customer");
		$this->db->group_start();
		$this->db->like("id_customer",$q);
		$this->db->or_like("nama",$q);
		$this->db->or_like("no_kartu",$q);
		$this->db->or_like("no_id",$q);
		$this->db->group_end();
		$this->db->where("activated",1);
		return $this->db->get();
	}

	function supplierAjax($q){
		$this->db->select(array("id_supplier","supplier"));
		$this->db->from("supplier");
		$this->db->like("supplier",$q);
		return $this->db->get();
	}

	function produk_search($q,$id_store){
		$this->db->select(array("stok_store.id_produk","ap_produk.nama_produk","ap_produk_price.harga"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk","left");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk","left");
		$this->db->where("ap_produk_price.id_toko",$id_store);
		$this->db->where("stok_store.id_store",$id_store);
		$this->db->where("ap_produk.status",1);
		$this->db->like("ap_produk.nama_produk",$q);
		$this->db->or_like("stok_store.id_produk",$q);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function produk_search_all($q){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk"));
		$this->db->from("ap_produk");
		$this->db->like("ap_produk.nama_produk",$q);
		$this->db->or_like("ap_produk.id_produk",$q);
		return $this->db->get();
	}

	function data_kategori($id){
		$this->db->select(array("id_kategori","kategori"));
		$this->db->from("ap_kategori");
		$this->db->where("id_kategori",$id);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->kategori;
		}
	}

	function cek_diskon($sku){
		$this->db->select("diskon");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$sku);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function nama_kasir($id){
		$this->db->select("first_name");
		$this->db->from("users");
		$this->db->where("id",$id);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->first_name;
		}
	}

	function count_setor($tanggal){
		$this->db->from("setoran_kasir");
		$this->db->where("DATE(tanggal)",$tanggal);
		return $this->db->count_all_results();
	}

	function data_stok_toko($sku,$id_store){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$sku);
		$this->db->where("id_store",$id_store);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->stok;
		}
	}

	function count_invoice_reservasi($tanggal){
		$this->db->from("ap_reservasi");
		$this->db->where("tanggal_reservasi",$tanggal);
		return $this->db->count_all_results();
	}

	function total_reservasi_all(){
		$this->db->from("ap_reservasi");
		return $this->db->count_all_results();
	}

	function daftar_reservasi_all($limit,$start){
		$this->db->select("*");
		$this->db->from("ap_reservasi");
		$this->db->limit($limit,$start);
		return $this->db->get()->result();
	}

	function daftar_reservasi_all_sort($query){
		$this->db->select("*");
		$this->db->from("ap_reservasi");
		$this->db->like("no_reservasi",$query);
		$this->db->or_like("atas_nama",$query);
		return $this->db->get()->result();
	}

	function reservasi_item($no_reservasi){
		$this->db->select(array("ap_produk.nama_produk","ap_reservasi_item.diskon","ap_reservasi_item.harga","ap_reservasi_item.qty","ap_reservasi_item.id_produk"));
		$this->db->from("ap_reservasi_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_reservasi_item.id_produk","left");
		$this->db->where("ap_reservasi_item.no_reservasi",$no_reservasi);
		return $this->db->get()->result();
	}

	function riwayat_pembayaran($no_reservasi){
		$this->db->select("*");
		$this->db->from("ap_reservasi_payment");
		//$this->db->join("ap_piutang_payment_type","ap_piutang_payment_type.id = ap_reservasi_payment.id_payment_type","left");
		$this->db->join("user","user.id_user = ap_reservasi_payment.id_pic","left");
		$this->db->where("ap_reservasi_payment.no_reservasi",$no_reservasi);
		return $this->db->get()->result();
	}

	function riwayat_pengambilan($no_reservasi){
		$this->db->select("*");
		$this->db->from("ap_reservasi_item_ambil");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_reservasi_item_ambil.id_produk","left");
		$this->db->join("user","user.id_user = ap_reservasi_item_ambil.id_pic","left");
		$this->db->where("ap_reservasi_item_ambil.no_reservasi",$no_reservasi);
		return $this->db->get()->result();
	}

	function cek_no_pending(){
		$this->db->from("ap_order_temp_no");
		$this->db->where("tanggal",date('Y-m-d'));
		//$this->db->group_by("ap_order_temp.id_pending");
		return $this->db->count_all_results();
	}

	function order_temp($id_pending){
		$this->db->select("*");
		$this->db->from("ap_order_temp");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_order_temp.id_produk","left");
		$this->db->where("ap_order_temp.id_pending",$id_pending);
		return $this->db->get()->result();
	}

	function item_barang_struk($no_invoice){
		$this->db->select("COUNT(no_invoice) as qty");
		$this->db->from("ap_invoice_item");
		$this->db->where("no_invoice",$no_invoice);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->qty;
		}
	}

	function qty_barang_struk($no_invoice){
		$this->db->select("SUM(qty) as qty");
		$this->db->from("ap_invoice_item");
		$this->db->where("no_invoice",$no_invoice);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->qty;
		}
	}

	function tipe_bayar_struk($no_invoice){
		$this->db->select(array("ap_payment_type.payment_type","ap_payment_account.account"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.no_invoice",$no_invoice);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->payment_type." ".$row->account;
		}
	}

	

	function header_type($no_so){
		$this->db->select("type");
		$this->db->from("stock_opname_info");
		$this->db->where("no_so",$no_so);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->type;
		}
	}

	function count_retur($tanggal){
		$this->db->select("tanggal");
		$this->db->from("ap_retur");
		$this->db->where("DATE(tanggal)",$tanggal);
		return $this->db->count_all_results();
	}

	function cekClosing($id){
		$tanggal = date('Y-m-d');
		
		$this->db->from("closing_id");
		$this->db->where("tanggal",$tanggal);
		$this->db->where("id_kasir",$id);
		$this->db->where("jam >=","02:00:00");
		return $this->db->count_all_results();
	}
	function cekSetoran($id){
		$tanggal = date('Y-m-d');
		
		$this->db->from("setoran_kasir");
		$this->db->where("tanggal",$tanggal);
		$this->db->where("id_user",$id);
		$this->db->where("jam_setor >=","02:00:00");
		return $this->db->count_all_results();
	}

	function getIdKasir($id){
		$this->db->select("id_pic");
		$this->db->from("ap_invoice_number");
		$this->db->where("no_invoice",$id);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->id_pic;
		}
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

	function emailSupplier($idSupplier){
		$this->db->select("email");
		$this->db->from("supplier");
		$this->db->where("id_supplier",$idSupplier);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->email;
		}
	}

	function riwayatPenerimaan($noPo){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","receive_item.tanggal","receive_item.qty"));
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive");
		$this->db->join("ap_produk","ap_produk.id_produk=receive_item.sku");
		$this->db->where("receive_order.no_po",$noPo);
		$this->db->where("receive_item.qty > 0");
		$this->db->order_by("receive_item.tanggal","DESC");
		return $this->db->get()->result();
	}

	function dataReceive($noReceive){
		$this->db->select(array("receive_order.no_receive","receive_order.no_po","receive_order.tanggal_terima","receive_order.received_by","receive_order.checked_by","supplier.supplier","supplier.termOfPay","receive_order.diterimaDi","receive_order.diskon"));
		$this->db->from("receive_order");
		$this->db->join("purchase_order","purchase_order.no_po = receive_order.no_po");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","right");
		$this->db->where("receive_order.no_receive",$noReceive);
		return $this->db->get()->result();
	}

	function namaStore($idStore='7'){
		$this->db->select("store");
		$this->db->from("ap_store");
		$this->db->where("id_store",$idStore);
		$query = $this->db->get()->row();
		return $query->store;
	}

	function callNavigation(){
		$this->db->select(array("z_menu.id","z_menu.menu","z_menu.slug","z_menu.icon"));
		$this->db->from("z_menu");
		$this->db->group_by("z_menu.id");
		$query = $this->db->get()->result();
		return $query;
	}

	function submenu($id){
		$this->db->select("*");
		$this->db->from("z_submenu");
		$this->db->where("id",$id);
		return $this->db->get()->result();
	}

	function permitAccess($idUser){
		$this->db->select("menu");
		$this->db->from("users");
		$this->db->where("id",$idUser);
		$query = $this->db->get()->row();
		return $query->menu;
	}

	function permitAccessSub($idUser){
		$this->db->select("sub_menu");
		$this->db->from("users");
		$this->db->where("id",$idUser);
		$query = $this->db->get()->row();
		return $query->sub_menu;
	}

	function masterMenu($slug){
		$this->db->select("id");
		$this->db->from("z_menu");
		$this->db->where("slug",$slug);
		$query = $this->db->get()->row();
		return $query->id;
	}

	function subMenuId($slug){
		$this->db->select("idSub");
		$this->db->from("z_submenu");
		$this->db->where("z_submenu.slug",$slug);
		$query = $this->db->get()->row();
		return $query->idSub;
	}

	function cekMyAccess($idUser,$type,$code){
		if($type==1){
			$permit = json_decode($this->permitAccess($idUser));
			$accessMenu = in_array($code,$permit);
		} else {
			$permit = json_decode($this->permitAccessSub($idUser));
			$accessMenu = in_array($code,$permit);
		}

		return $accessMenu;
	}

	function getIdStore($idUser){
		$this->db->select("toko");
		$this->db->from("users");
		$this->db->where("id",$idUser);
		$query = $this->db->get()->row();
		return $query->toko;
	}
	function getIsAdmin($idUser){
		$this->db->select("is_admin");
		$this->db->from("users");
		$this->db->where("id",$idUser);
		$query = $this->db->get()->row();
		return $query->is_admin;
	}
	function getIsSuperadmin($idUser){
		$this->db->select("superadmin");
		$this->db->from("users");
		$this->db->where("id",$idUser);
		$query = $this->db->get()->row();
		return $query->superadmin;
	}
}