<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Model_dashboard_brand extends CI_Model{
	function __construct(){
		parent::__construct();
        $this->load->library('session');
		$this->load->database();
	}
    
    function fastMoving($brand='',$idStore='',$idUser='',$date,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_item.qty) as qty","ap_produk.nama_produk as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("ap_invoice_item.tanggal",$date);
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("SUM(ap_invoice_item.qty)","DESC");
		 $this->db->limit(10, 0);
		return $this->db->get();
	}
	function tebusMurah($brand='',$idStore='',$idUser='',$date,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_item.harga_jual - ap_invoice_item.tebusmurah) as qty","ap_produk.nama_produk as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("ap_invoice_item.tanggal",$date);
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("ap_invoice_item.tebusmurah>0");
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("SUM(ap_invoice_item.harga_jual - ap_invoice_item.tebusmurah)","DESC");
		 $this->db->limit(10, 0);
		return $this->db->get();
	}
	function fastMovingMonth($brand='',$idStore='',$idUser='',$bulan,$tahun,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_item.qty) as qty","ap_produk.nama_produk as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("MONTH(ap_invoice_item.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("SUM(ap_invoice_item.qty)","DESC");
		 $this->db->limit(10, 0);
		return $this->db->get();
	}
	function fastMovingYear($brand='',$idStore='',$idUser='',$tahun,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_item.qty) as qty","ap_produk.nama_produk as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("SUM(ap_invoice_item.qty)","DESC");
		 $this->db->limit(10, 0);
		return $this->db->get();
	}
	function salesPerBrand1($brand='',$idStore='',$idUser='',$date,$id_toko=''){
		$this->db->select(array("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon) as qty","brand.brand as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("ap_invoice_item.tanggal",$date);
		$this->db->where("ap_invoice_item.qty>0");
		$this->db->group_by("ap_produk.id_brand");
		$this->db->order_by("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon)","DESC");
		$this->db->limit(10, 0);
		return $this->db->get();
	}
	function salesPerBrand2($brand='',$idStore='',$idUser='',$date,$id_toko=''){
		$this->db->select(array("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon) as qty","brand.brand as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("ap_invoice_item.tanggal",$date);
		$this->db->where("ap_invoice_item.qty>0");
		$this->db->group_by("ap_produk.id_brand");
		$this->db->order_by("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon)","DESC");
		$this->db->limit(10, 11);
		return $this->db->get();
	}
	function salesPerBrand1Month($brand='',$idStore='',$idUser='',$bulan,$tahun,$id_toko=''){
		$this->db->select(array("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon) as qty","brand.brand as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("MONTH(ap_invoice_item.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$this->db->where("ap_invoice_item.qty>0");
		$this->db->group_by("ap_produk.id_brand");
		$this->db->order_by("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon)","DESC");
		$this->db->limit(10, 0);
		return $this->db->get();
	}
	function salesPerBrand2Month($brand='',$idStore='',$idUser='',$bulan,$tahun,$id_toko=''){
		$this->db->select(array("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon) as qty","brand.brand as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("MONTH(ap_invoice_item.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$this->db->where("ap_invoice_item.qty>0");
		$this->db->group_by("ap_produk.id_brand");
		$this->db->order_by("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon)","DESC");
		$this->db->limit(10, 11);
		return $this->db->get();
	}
	function salesPerBrand1Year($brand='',$idStore='',$idUser='',$tahun,$id_toko=''){
		$this->db->select(array("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon) as qty","brand.brand as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$this->db->where("ap_invoice_item.qty>0");
		$this->db->group_by("ap_produk.id_brand");
		$this->db->order_by("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon)","DESC");
		$this->db->limit(10, 0);
		return $this->db->get();
	}
	function salesPerBrand2Year($brand='',$idStore='',$idUser='',$tahun,$id_toko=''){
		$this->db->select(array("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon) as qty","brand.brand as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$this->db->where("ap_invoice_item.qty>0");
		$this->db->group_by("ap_produk.id_brand");
		$this->db->order_by("SUM((ap_invoice_item.qty*ap_invoice_item.harga_jual)-ap_invoice_item.diskon)","DESC");
		$this->db->limit(10, 11);
		return $this->db->get();
	}
	function slowMoving($brand='',$idStore='',$idUser='',$date,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_item.qty) as qty","ap_produk.nama_produk as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("ap_invoice_item.tanggal",$date);
		$this->db->where("ap_invoice_item.qty>0");
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("SUM(ap_invoice_item.qty)","ASC");
		$this->db->limit(10, 0);
		return $this->db->get();
	}
	function slowMovingMonth($brand='',$idStore='',$idUser='',$bulan,$tahun,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_item.qty) as qty","ap_produk.nama_produk as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_item.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$this->db->where("ap_invoice_item.qty>0");
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("SUM(ap_invoice_item.qty)","ASC");
		$this->db->limit(10, 0);
		return $this->db->get();
	}
	function slowMovingYear($brand='',$idStore='',$idUser='',$tahun,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_item.qty) as qty","ap_produk.nama_produk as produk"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser!='2' && $idUser!='1' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$this->db->where("ap_invoice_item.qty>0");
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("SUM(ap_invoice_item.qty)","ASC");
		$this->db->limit(10, 0);
		return $this->db->get();
	}
    function totalInv($brand=''){

		$this->db->select(array("SUM(stok_store.stok*stok_store.harga) as nilai"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
        $this->db->where("ap_produk.status",1);
		
		
		return $this->db->get()->result();
	}
	function totalInvStore($store){

		$this->db->select(array("SUM(stok_store.stok*stok_store.harga) as nilai"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		 
        $this->db->where("ap_produk.status",1);
		$this->db->where("stok_store.id_store",$store);
		
		return $this->db->get()->result();
	}

	function warningStokPDG(){

		$this->db->select(array("COUNT(stok_store.id_produk) as nilai"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		 
        $this->db->where("ap_produk.status",1);
		$this->db->where("stok_store.stok < stok_store.min");
		$this->db->where("stok_store.id_store",7);
		
		return $this->db->get()->result();
	}

	function warningStokPKU(){

		$this->db->select(array("COUNT(stok_store.id_produk) as nilai"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		 
        $this->db->where("ap_produk.status",1);
		$this->db->where("stok_store.stok < stok_store.min");
		$this->db->where("stok_store.id_store",8);
		
		return $this->db->get()->result();
	}

	function warningStokJMB(){

		$this->db->select(array("COUNT(stok_store.id_produk) as nilai"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		 
        $this->db->where("ap_produk.status",1);
		$this->db->where("stok_store.stok < stok_store.min");
		$this->db->where("stok_store.id_store",9);
		
		return $this->db->get()->result();
	}
    
	function total_sales($brand='',$idStore='',$idUser='',$tanggal,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_number.total) as total","SUM(ap_invoice_number.diskon) as diskon","SUM(ap_invoice_number.diskon_free) as diskon_free","SUM(ap_invoice_number.poin_value) as poin_value","SUM(ap_invoice_number.diskon_otomatis) as diskon_otomatis"));
		$this->db->from("ap_invoice_number");

		if(!empty($tanggal)){
			$this->db->where("DATE(ap_invoice_number.tanggal)",$tanggal);
		}
        if ($idUser>1  && $idUser!='2' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
			$this->db->where("ap_invoice_number.total>",0);

		$query = $this->db->get()->result();

		foreach($query as $row){
			$total 	= $row->total;
			$diskon = $row->diskon;
			$diskon_free =$row->diskon_free;
			$reimburs = $row->poin_value;
			$diskonOtomatis = $row->diskon_otomatis;

			return $total-($diskon+$diskon_free+$reimburs+$diskonOtomatis);
		}
	}

	function total_sales_perbulan($brand='',$idStore='',$idUser='',$bulan,$tahun,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_number.total) as total","SUM(ap_invoice_number.diskon) as diskon","SUM(ap_invoice_number.diskon_free) as diskon_free","SUM(ap_invoice_number.poin_value) as poin_value","SUM(ap_invoice_number.diskon_otomatis) as diskon_otomatis"));
		$this->db->from("ap_invoice_number");

		if(!empty($bulan)){
			$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		}

		if(!empty($tahun)){
			$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		}
        if ($idUser>1 && $idUser!='150' && $idUser!='101' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			$total 	= $row->total;
			$diskon = $row->diskon;
			$diskon_free =$row->diskon_free;
			$reimburs = $row->poin_value;
			$diskonOtomatis = $row->diskon_otomatis;

			return $total-($diskon+$diskon_free+$reimburs+$diskonOtomatis);
		}
	}

	function total_sales_pertahun($brand='',$idStore='',$idUser='',$tahun,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_number.total) as total","SUM(ap_invoice_number.diskon) as diskon","SUM(ap_invoice_number.diskon_free) as diskon_free","SUM(ap_invoice_number.poin_value) as poin_value","SUM(ap_invoice_number.diskon_otomatis) as diskon_otomatis"));
		$this->db->from("ap_invoice_number");

		if(!empty($tahun)){
			$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		}
        if ($idUser>1 && $idUser!='150' && $idUser!='101' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			$total 	= $row->total;
			$diskon = $row->diskon;
			$diskon_free =$row->diskon_free;
			$reimburs = $row->poin_value;
			$diskonOtomatis = $row->diskon_otomatis;

			return $total-($diskon+$diskon_free+$reimburs+$diskonOtomatis);
		}
	}

	function transaction($brand='',$idStore='',$idUser='',$tanggal,$id_toko=''){
		$this->db->select("COUNT(ap_invoice_number.no_invoice) as trx");
		$this->db->from("ap_invoice_number");

		if(!empty($tanggal)){
			$this->db->where("DATE(ap_invoice_number.tanggal)",$tanggal);
		}
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}

	function list_brand($idUser=''){
		$this->db->select("brand");
		$this->db->from("users");

        $this->db->where("id",$idUser);
        
		
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->brand;
		}
	}

	function transactionPerbulan($brand='',$idStore='',$idUser='',$bulan,$tahun,$id_toko=''){
		$this->db->select("COUNT(ap_invoice_number.no_invoice) as trx");
		$this->db->from("ap_invoice_number");

		if(!empty($bulan)){
			$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		}

		if(!empty($tahun)){
			$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		}
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}

	function transactionPertahun($brand='',$idStore='',$idUser='',$tahun,$id_toko=''){
		$this->db->select("COUNT(ap_invoice_number.no_invoice) as trx");
		$this->db->from("ap_invoice_number");

		if(!empty($tahun)){
			$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		}
        if ($idUser>1   && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}

	function totalItemTerjual($brand='',$idStore='',$idUser='',$tanggal,$id_toko=''){
		$this->db->select("SUM(ap_invoice_item.qty) as qty");
		$this->db->from("ap_invoice_item");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
		$this->db->where("ap_invoice_item.tanggal",$tanggal);
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$query = $this->db->get()->row();
		return $query->qty;		
	}
    
    function totalMargin($brand='',$idStore='',$idUser='',$tanggal,$id_toko=''){
		$this->db->select("(SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual)-SUM(ap_invoice_item.diskon)-SUM(ap_invoice_item.qty*ap_invoice_item.hpp))/(SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual)-SUM(ap_invoice_item.diskon)) as qty");
		$this->db->from("ap_invoice_item");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
		$this->db->where("ap_invoice_item.tanggal",$tanggal);
        
        if ($idUser>1 && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$query = $this->db->get()->row();
		return $query->qty;		
	}
    
    function totalMarginPerbulan($brand='',$idStore='',$idUser='',$bulan,$tahun,$id_toko=''){
		$this->db->select("(SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual)-SUM(ap_invoice_item.diskon)-SUM(ap_invoice_number.diskon_free)-SUM(ap_invoice_item.qty*ap_invoice_item.hpp))/(SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual)-SUM(ap_invoice_item.diskon)-SUM(ap_invoice_number.diskon_free)) as qty");
		$this->db->from("ap_invoice_item");
        
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_item.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$query = $this->db->get()->row();
		return $query->qty;		
	}

	function totalItemTerjualPerbulan($brand='',$idStore='',$idUser='',$bulan,$tahun,$id_toko=''){
		$this->db->select("SUM(ap_invoice_item.qty) as qty");
		$this->db->from("ap_invoice_item");
        
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_item.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$query = $this->db->get()->row();
		return $query->qty;		
	}

	function totalItemTerjualPertahun($brand='',$idStore='',$idUser='',$tahun,$id_toko=''){
		$this->db->select("SUM(ap_invoice_item.qty) as qty");
		$this->db->from("ap_invoice_item");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$query = $this->db->get()->row();
		return $query->qty;		
	}
    
    function totalMarginPertahun($brand='',$idStore='',$idUser='',$tahun,$id_toko=''){
		$this->db->select("(SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual)-SUM(ap_invoice_item.diskon)-SUM(ap_invoice_number.diskon_free)-SUM(ap_invoice_item.qty*ap_invoice_item.hpp))/(SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual)-SUM(ap_invoice_number.diskon_free)-SUM(ap_invoice_item.diskon)) as qty");
		$this->db->from("ap_invoice_item");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$query = $this->db->get()->row();
		return $query->qty;		
	}

	function previous_transaction(){
		$bulan = date('m');
		$tahun  = date('Y');

		if($bulan==01){
			$last_month = 01;
			$last_year  = $tahun-1;
		} else {
			$last_month = sprintf("%02d",$bulan-1);
			$last_year  = $tahun;
		}

		$this->db->select("COUNT(ap_invoice_number.no_invoice) as trx");
		$this->db->from("ap_invoice_number");
        
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$last_month);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$last_year);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}

	function customer(){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select("COUNT(ap_customer.id_customer) as customer");
		$this->db->from("ap_customer");
		$this->db->where("MONTH(ap_customer.tanggal_gabung)",$bulan);
		$this->db->where("YEAR(ap_customer.tanggal_gabung)",$tahun);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->customer;
		}
	}

	function previous_customer(){
		$bulan = date('m');
		$tahun  = date('Y');

		if($bulan==01){
			$last_month = 01;
			$last_year  = $tahun-1;
		} else {
			$last_month = sprintf("%02d",$bulan-1);
			$last_year  = $tahun;
		}

		$this->db->select("COUNT(ap_customer.id_customer) as customer");
		$this->db->from("ap_customer");
		$this->db->where("MONTH(ap_customer.tanggal_gabung)",$last_month);
		$this->db->where("YEAR(ap_customer.tanggal_gabung)",$last_year);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->customer;
		}
	}


	function sales_perkategori($brand='',$idStore='',$idUser='',$id_toko=''){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select(array("ap_kategori.kategori","SUM((ap_invoice_item.harga_jual*ap_invoice_item.qty))  as total"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		//$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        } 
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("MONTH(ap_invoice_item.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$this->db->group_by("ap_kategori.id_kategori");
		return $this->db->get()->result();
	}

	function discount_channel($brand='',$idStore='',$idUser=''){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select_sum("diskon");
		$this->db->from("ap_invoice_number");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function discount($brand='',$idStore='',$idUser=''){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select_sum("diskon_free");
		$this->db->from("ap_invoice_number");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
        if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon_free;
		}
	}

	function poin(){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select_sum("poin_value");
		$this->db->from("ap_invoice_number");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->poin_value;
		}
	}

	function customer_group($brand='',$idStore='',$idUser=''){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select(array("ap_customer_group.group_customer","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ap_customer_group","ap_customer_group.id_group = ap_customer.kategori","left");
        if ($idUser>1 && $idUser!='150' && $idUser!='101' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->group_by("ap_customer_group.id_group");
		return $this->db->get()->result();
	}

	function pending_status($brand='',$idStore='',$idUser=''){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select("COUNT(ap_invoice_number.status) as status");
		$this->db->from("ap_invoice_number");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->where("status",0);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->status;
		}
	}

	function on_process($brand='',$idStore='',$idUser=''){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select("COUNT(ap_invoice_number.status) as status");
		$this->db->from("ap_invoice_number");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->where("status",1);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->status;
		}
	}

	function terkirim($brand='',$idStore='',$idUser=''){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select("COUNT(ap_invoice_number.status) as status");
		$this->db->from("ap_invoice_number");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->where("status",2);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->status;
		}
	}

	function dibatalkan($brand='',$idStore='',$idUser=''){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select("COUNT(ap_invoice_number.status) as status");
		$this->db->from("ap_invoice_number");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->where("status",3);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->status;
		}
	}

	function sales_by_province($brand='',$idStore='',$idUser=''){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select(array("SUM(ap_invoice_number.total) as total","ae_provinsi.nama_provinsi"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ae_provinsi","ae_provinsi.id_provinsi = ap_invoice_number.id_provinsi","left");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->group_by("ap_invoice_number.id_provinsi");
		$this->db->order_by("total","DESC");
		return $this->db->get()->result();
	}

	function year_to_month($brand='',$idStore='',$idUser=''){
		$tahun = date('Y');

		$this->db->select(array("SUM(ap_invoice_number.total) as total","ap_invoice_number.tanggal"));
		$this->db->from("ap_invoice_number");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->group_by("MONTH(ap_invoice_number.tanggal)");
		return $this->db->get();
	}

	function penjualan_perstore($id_store){
		$bulan = date('m');
		$tahun  = date('Y');

		$this->db->select(array("SUM(ap_invoice_number.total) as total","SUM(ap_invoice_number.diskon) as diskon","SUM(ap_invoice_number.diskon_free) as diskon_free","SUM(ap_invoice_number.poin_value) as poin_value"));
		$this->db->from("ap_invoice_number");
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->where("ap_invoice_number.id_toko",$id_store);
		$query = $this->db->get()->result();

		foreach($query as $row){
			$total 	= $row->total;

			return $total;
		}
	}

	function salesByHour($brand='',$idStore='',$idUser='',$date,$id_toko=''){
		$this->db->select(array("CONCAT(HOUR(ap_invoice_number.tanggal),':00-',HOUR(ap_invoice_number.tanggal)+1,':00') as tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("DATE(ap_invoice_number.tanggal)",$date);
		$this->db->group_by("HOUR(ap_invoice_number.tanggal)");
		return $this->db->get();
	}

	function salesByHourMonth($brand='',$idStore='',$idUser='',$bulan,$tahun,$id_toko=''){
		$this->db->select(array("CONCAT(HOUR(ap_invoice_number.tanggal),':00-',HOUR(ap_invoice_number.tanggal)+1,':00') as tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->group_by("HOUR(ap_invoice_number.tanggal)");
		return $this->db->get();
	}

	function salesByHourYear($brand='',$idStore='',$idUser='',$tahun,$id_toko=''){
		$this->db->select(array("CONCAT(HOUR(ap_invoice_number.tanggal),':00-',HOUR(ap_invoice_number.tanggal)+1,':00') as tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->group_by("HOUR(ap_invoice_number.tanggal)");
		return $this->db->get();
	}

	function salesPerkategori($brand='',$idStore='',$idUser='',$date,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as totalPenjualan","ap_kategori.kategori"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("ap_invoice_item.tanggal",$date);
		$this->db->group_by("ap_kategori.id_kategori");
		$this->db->order_by("totalPenjualan","DESC");
		return $this->db->get();
	}

	function salesPerkategoriMonth($brand='',$idStore='',$idUser='',$bulan,$tahun,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as totalPenjualan","ap_kategori.kategori"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");

		$this->db->where("MONTH(ap_invoice_item.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$this->db->group_by("ap_kategori.id_kategori");
		$this->db->order_by("totalPenjualan","DESC");
		return $this->db->get();
	}

	function salesPerkategoriYear($brand='',$idStore='',$idUser='',$tahun,$id_toko=''){
		$this->db->select(array("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as totalPenjualan","ap_kategori.kategori"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$brand = str_replace("[","",$brand);
		$brand = str_replace("]","",$brand);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$tahun);
		$this->db->group_by("ap_kategori.id_kategori");
		$this->db->order_by("totalPenjualan","DESC");
		return $this->db->get();
	}

	function salesPerkasir($brand='',$idStore='',$idUser='',$date,$id_toko=''){
		$this->db->select(array("users.first_name","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("users","users.id = ap_invoice_number.id_pic","left");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("DATE(ap_invoice_number.tanggal)",$date);
		$this->db->group_by("ap_invoice_number.id_pic");
		$this->db->order_by("total","DESC");
		return $this->db->get();
	}

	function salesPerkasirMonth($brand='',$idStore='',$idUser='',$bulan,$tahun,$id_toko=''){
		$this->db->select(array("users.first_name","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("users","users.id = ap_invoice_number.id_pic","left");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$bulan);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->group_by("ap_invoice_number.id_pic");
		$this->db->order_by("total","DESC");
		return $this->db->get();
	}

	function salesPerkasirYear($brand='',$idStore='',$idUser='',$tahun,$id_toko=''){
		$this->db->select(array("users.first_name","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("users","users.id = ap_invoice_number.id_pic","left");
        
        if ($idUser>1  && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$tahun);
		$this->db->group_by("ap_invoice_number.id_pic");
		$this->db->order_by("total","DESC");
		return $this->db->get();
	}
}