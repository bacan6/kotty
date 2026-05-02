<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelDashboard extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function totalSales($date){
		$this->db->select("SUM(COALESCE(total,0)-(diskon_free+poin_value+diskon_otomatis)) as total");
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(ap_invoice_number.tanggal)",$date);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->total;
		}
	}

	function totalSalesPermonth($date){
		$month = substr($date, 0,2);
    	$year  = substr($date, 3,4);


		$this->db->select("SUM(COALESCE(total,0)-(diskon_free+poin_value+diskon_otomatis)) as total");
		$this->db->from("ap_invoice_number");
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$month);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$year);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->total;
		}
	}

	function totalSalesPeryear($date){
		$this->db->select("SUM(COALESCE(total,0)-(diskon_free+poin_value+diskon_otomatis)) as total");
		$this->db->from("ap_invoice_number");
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$date);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->total;
		}
	}

	function totalTransaksi($date){
		$this->db->select("COUNT(ap_invoice_number.no_invoice) as totalTransaksi");
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(ap_invoice_number.tanggal)",$date);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalTransaksi;
		}
	}

	function totalTransaksiPermonth($date){
		$month = substr($date, 0,2);
    	$year  = substr($date, 3,4);

		$this->db->select("COUNT(ap_invoice_number.no_invoice) as totalTransaksi");
		$this->db->from("ap_invoice_number");
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$month);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$year);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalTransaksi;
		}
	}

	function totalTransaksiPeryear($date){
		$this->db->select("COUNT(ap_invoice_number.no_invoice) as totalTransaksi");
		$this->db->from("ap_invoice_number");
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$date);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalTransaksi;
		}
	}

	function produkTerjual($date){
		$this->db->select("SUM(ap_invoice_item.qty) as qty");
		$this->db->from("ap_invoice_item");
		$this->db->where("tanggal",$date);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->qty;
		}
	}

	function produkTerjualPermonth($date){
		$month = substr($date, 0,2);
    	$year  = substr($date, 3,4);

		$this->db->select("SUM(ap_invoice_item.qty) as qty");
		$this->db->from("ap_invoice_item");
		$this->db->where("MONTH(tanggal)",$month);
		$this->db->where("YEAR(tanggal)",$year);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->qty;
		}
	}

	function produkTerjualPeryear($date){
		$this->db->select("SUM(ap_invoice_item.qty) as qty");
		$this->db->from("ap_invoice_item");
		$this->db->where("YEAR(tanggal)",$date);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->qty;
		}
	}

	function salesPerkategori($date){
		$this->db->select(array("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as totalPenjualan","ap_kategori.kategori"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->where("ap_invoice_item.tanggal",$date);
		$this->db->group_by("ap_kategori.id_kategori");
		$this->db->order_by("totalPenjualan","DESC");
		return $this->db->get()->result();
	}

	function salesPerkategoriPermonth($date){
		$month = substr($date, 0,2);
    	$year  = substr($date, 3,4);

		$this->db->select(array("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as totalPenjualan","ap_kategori.kategori"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->where("MONTH(ap_invoice_item.tanggal)",$month);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$year);
		$this->db->group_by("ap_kategori.id_kategori");
		$this->db->order_by("totalPenjualan","DESC");
		return $this->db->get()->result();
	}

	function salesPerkategoriRange($start,$end,$toko){
		$this->db->select(array("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as totalPenjualan","ap_kategori.kategori"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice");

		if(!empty($toko)){
			$this->db->where("ap_invoice_number.id_toko",$toko);
		}

		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->group_by("ap_kategori.id_kategori");
		$this->db->order_by("totalPenjualan","DESC");
		return $this->db->get()->result();
	}

	function salesPerkategoriPeryear($date){
		$this->db->select(array("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as totalPenjualan","ap_kategori.kategori"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$date);
		$this->db->group_by("ap_kategori.id_kategori");
		$this->db->order_by("totalPenjualan","DESC");
		return $this->db->get()->result();
	}

	function bebanPokokPenjualan($date){
		$month = substr($date, 0,2);
    	$year  = substr($date, 3,4);

		$this->db->select(array("SUM(bahan_baku.harga*ap_invoice_item.qty) as totalPenjualan","ap_kategori.kategori"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->join("bahan_baku","bahan_baku.sku = ap_invoice_item.id_produk","left");
		$this->db->where("MONTH(ap_invoice_item.tanggal)",$month);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$year);
		$this->db->group_by("ap_kategori.id_kategori");
		$this->db->order_by("totalPenjualan","DESC");
		return $this->db->get()->result();
	}

	function bebanPokokPenjualanRange($start,$end,$toko){
		$this->db->select(array("SUM(ap_produk.hpp*ap_invoice_item.qty) as totalPenjualan","ap_kategori.kategori"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice");

		if(!empty($toko)){
			$this->db->where("ap_invoice_number.id_toko",$toko);
		}

		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->group_by("ap_kategori.id_kategori");
		$this->db->order_by("totalPenjualan","DESC");
		return $this->db->get()->result();
	}

	function dataReturRange($start,$end,$toko){
		$this->db->select(array("SUM(ap_retur_item.harga*ap_retur_item.qty) as totalRetur"));
		$this->db->from("ap_retur_item");
		$this->db->join("ap_retur","ap_retur.no_retur = ap_retur_item.no_retur");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_retur.no_invoice");
		$this->db->where("ap_retur_item.tanggal BETWEEN '$start' AND '$end'");

		if(!empty($toko)){
			$this->db->where("ap_invoice_number.id_toko",$toko);
		}	

		$query = $this->db->get()->row();
		return $query->totalRetur;
	}

	function salesPerstore($date){
		$this->db->select(array("ap_store.store","SUM(COALESCE(total,0)-(diskon_free+poin_value+diskon_otomatis)) as total","ap_store.id_store"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_store","ap_store.id_store = ap_invoice_number.id_toko","left");
		$this->db->where("DATE(ap_invoice_number.tanggal)",$date);
		$this->db->group_by("ap_store.id_store");
		$this->db->order_by("total","DESC");
		return $this->db->get()->result();
	}

	function salesPerstorePermonth($date){
		$month = substr($date, 0,2);
    	$year  = substr($date, 3,4);

		$this->db->select(array("ap_store.store","SUM(COALESCE(total,0)-(diskon_free+poin_value+diskon_otomatis)) as total","ap_store.id_store"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_store","ap_store.id_store = ap_invoice_number.id_toko","left");
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$month);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$year);
		$this->db->group_by("ap_store.id_store");
		$this->db->order_by("total","DESC");
		return $this->db->get()->result();
	}

	function salesPerstorePeryear($date){
		$this->db->select(array("ap_store.store","SUM(COALESCE(total,0)-(diskon_free+poin_value+diskon_otomatis)) as total","ap_store.id_store"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_store","ap_store.id_store = ap_invoice_number.id_toko","left");
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$date);
		$this->db->group_by("ap_store.id_store");
		$this->db->order_by("total","DESC");
		return $this->db->get()->result();
	}

	function produkTerlaris($date){
		$this->db->select(array("ap_produk.nama_produk","ap_produk.id_produk","SUM(ap_invoice_item.qty) as qty"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->where("ap_invoice_item.tanggal",$date);
		$this->db->group_by("ap_invoice_item.id_produk");
		$this->db->limit(10,0);
		$this->db->order_by("qty","DESC");
		return $this->db->get()->result();
	}

	function produkTerlarisPermonth($date){
		$month = substr($date, 0,2);
    	$year  = substr($date, 3,4);

		$this->db->select(array("ap_produk.nama_produk","ap_produk.id_produk","SUM(ap_invoice_item.qty) as qty"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->where("MONTH(ap_invoice_item.tanggal)",$month);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$year);
		$this->db->group_by("ap_invoice_item.id_produk");
		$this->db->limit(10,0);
		$this->db->order_by("qty","DESC");
		return $this->db->get()->result();
	}

	function produkTerlarisPeryear($date){
		$this->db->select(array("ap_produk.nama_produk","ap_produk.id_produk","SUM(ap_invoice_item.qty) as qty"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$date);
		$this->db->group_by("ap_invoice_item.id_produk");
		$this->db->limit(10,0);
		$this->db->order_by("qty","DESC");
		return $this->db->get()->result();
	}

	function salesPerkasir($date){
		$this->db->select(array("user.nama_user","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("user","user.id_user = ap_invoice_number.id_pic","left");
		$this->db->where("DATE(ap_invoice_number.tanggal)",$date);
		$this->db->group_by("ap_invoice_number.id_pic");
		$this->db->order_by("total","DESC");
		return $this->db->get()->result();
	}

	function salesPerkasirPermonth($date){
		$month = substr($date, 0,2);
    	$year  = substr($date, 3,4);

		$this->db->select(array("user.nama_user","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("user","user.id_user = ap_invoice_number.id_pic","left");
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$month);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$year);
		$this->db->group_by("ap_invoice_number.id_pic");
		$this->db->order_by("total","DESC");
		return $this->db->get()->result();
	}

	function salesPerkasirPeryear($date){
		$this->db->select(array("user.nama_user","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("user","user.id_user = ap_invoice_number.id_pic","left");
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$date);
		$this->db->group_by("ap_invoice_number.id_pic");
		$this->db->order_by("total","DESC");
		return $this->db->get()->result();
	}

	function salesByHour($date){
		$this->db->select(array("CONCAT(HOUR(ap_invoice_number.tanggal),':00-',HOUR(ap_invoice_number.tanggal)+1,':00') as tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(ap_invoice_number.tanggal)",$date);
		$this->db->group_by("HOUR(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function salesByHourPermonth($date){
		$month = substr($date, 0,2);
    	$year  = substr($date, 3,4);

		$this->db->select(array("CONCAT(HOUR(ap_invoice_number.tanggal),':00-',HOUR(ap_invoice_number.tanggal)+1,':00') as tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$month);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$year);
		$this->db->group_by("HOUR(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function salesByHourPeryear($date){
		$this->db->select(array("CONCAT(HOUR(ap_invoice_number.tanggal),':00-',HOUR(ap_invoice_number.tanggal)+1,':00') as tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$date);
		$this->db->group_by("HOUR(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function salesByPlace($date){
		$this->db->select(array("ap_stand.stand","SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as total"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_stand","ap_stand.id_stand = ap_produk.tempat","left");
		$this->db->where("ap_invoice_item.tanggal",$date);
		$this->db->group_by("ap_produk.tempat");
		$this->db->order_by("total","DESC");
		return $this->db->get()->result();
	}

	function salesByPlacePermonth($date){
		$month = substr($date, 0,2);
    	$year  = substr($date, 3,4);

		$this->db->select(array("ap_stand.stand","SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as total"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_stand","ap_stand.id_stand = ap_produk.tempat","left");
		$this->db->where("MONTH(ap_invoice_item.tanggal)",$month);
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$year);
		$this->db->group_by("ap_produk.tempat");
		$this->db->order_by("total","DESC");
		return $this->db->get()->result();
	}

	function salesByPlacePeryear($date){
		$this->db->select(array("ap_stand.stand","SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as total"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_stand","ap_stand.id_stand = ap_produk.tempat","left");
		$this->db->where("YEAR(ap_invoice_item.tanggal)",$date);
		$this->db->group_by("ap_produk.tempat");
		$this->db->order_by("total","DESC");
		return $this->db->get()->result();
	}


	function totalSalesPermonthOnly($date){
		$month = substr($date, 0,2);
    	$year  = substr($date, 3,4);


		$this->db->select_sum("total");
		$this->db->from("ap_invoice_number");
		$this->db->where("MONTH(ap_invoice_number.tanggal)",$month);
		$this->db->where("YEAR(ap_invoice_number.tanggal)",$year);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->total;
		}
	}

	function totalSalesRange($start,$end,$toko){
		$this->db->select_sum("total");
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");

		if(!empty($toko)){
			$this->db->where("ap_invoice_number.id_toko",$toko);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->total;
		}
	}

	function dataSales($start,$end){
		$this->db->select(array("ap_produk.nama_produk","ap_invoice_item.diskon","ap_invoice_item.harga_jual","SUM(qty) as qty","SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual) as total"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->order_by("qty","DESC");
		$this->db->group_by(array("ap_invoice_item.id_produk","ap_invoice_item.harga_jual"));
		return $this->db->get()->result();
	}

	function dataSalesStore($start,$end,$id_store){
		$this->db->select(array("ap_produk.nama_produk","ap_invoice_item.diskon","ap_invoice_item.harga_jual","SUM(qty) as qty","SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual) as total"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
		$this->db->where("ap_invoice_number.id_toko",$id_store);
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->order_by("qty","DESC");
		$this->db->group_by(array("ap_invoice_item.id_produk","ap_invoice_item.harga_jual"));
		return $this->db->get()->result();
	}

	function diskonMember($start,$end){
		$this->db->select_sum("diskon");
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(tanggal) BETWEEN '$start' AND '$end'");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function diskonGlobal($start,$end){
		$this->db->select_sum("diskon_free");
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(tanggal) BETWEEN '$start' AND '$end'");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon_free;
		}
	}

	function poinReimbursment($start,$end){
		$this->db->select_sum("poin_value");
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(tanggal) BETWEEN '$start' AND '$end'");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->poin_value;
		}
	}

	function diskonMemberStore($start,$end,$idStore){
		$this->db->select_sum("diskon");
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("id_toko",$idStore);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function diskonGlobalStore($start,$end,$idStore){
		$this->db->select_sum("diskon_free");
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("id_toko",$idStore);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon_free;
		}
	}

	function poinReimbursmentStore($start,$end,$idStore){
		$this->db->select_sum("poin_value");
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("id_toko",$idStore);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->poin_value;
		}
	}

}