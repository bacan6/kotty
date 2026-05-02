<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_staff extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function cek_target($bulan,$tahun,$id_kategori){
		$this->db->from("target");
		$this->db->where("bulan",$bulan);
		$this->db->where("tahun",$tahun);
		$this->db->where("id_kategori",$id_kategori);
		return $this->db->count_all_results();
	}

	function get_value_target($bulan,$tahun,$id_kategori){
		$this->db->select(array("target","target_qty"));
		$this->db->from("target");
		$this->db->where("bulan",$bulan);
		$this->db->where("tahun",$tahun);
		$this->db->where("id_kategori",$id_kategori);
		return $this->db->get();
	}

	function total_target($bulan,$tahun){
		$this->db->select_sum("target");
		$this->db->from("target");
		$this->db->where("bulan",$bulan);
		$this->db->where("tahun",$tahun);
		return $this->db->get();
	}

	function total_pencapaian($bulan,$tahun){
		$this->db->select("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as nilai_penjualan");
		$this->db->from("ap_invoice_item");
		$this->db->where("MONTH(tanggal)",$bulan);
		$this->db->where("YEAR(tanggal)",$tahun);
		return $this->db->get();
	}

	function avg_perday($bulan,$tahun){
		$this->db->select("AVG(ap_invoice_item.harga_jual*ap_invoice_item.qty) as nilai_penjualan");
		$this->db->from("ap_invoice_item");
		$this->db->where("MONTH(tanggal)",$bulan);
		$this->db->where("YEAR(tanggal)",$tahun);
		return $this->db->get();
	}

	function target_permonth($tahun){
		$this->db->select(array("SUM(target.target) as target","target.bulan","target.tahun","ap_produk.nama_produk"));
		$this->db->from("target");
		$this->db->join("ap_produk","ap_produk.id_produk = target.id_kategori","left");
		$this->db->where("target.tahun",$tahun);
		$this->db->group_by("target.tahun");
		$this->db->group_by("target.bulan");
		$this->db->order_by("target.bulan","ASC");
		return $this->db->get();
	}

	function total_penjualan($period,$id_kategori){
		$this->db->select("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as nilai_penjualan");
		$this->db->from("ap_invoice_item");
		$this->db->where("id_produk",$id_kategori);
		$this->db->like("tanggal",$period);
		return $this->db->get();
	}

	function show_penjualan($id,$period){
		$this->db->select("*");
		$this->db->from("data_penjualan");
		$this->db->where("id_kategori",$id);
		$this->db->like("tanggal_input",$period,"AFTER");
		$this->db->order_by("tanggal_input","DESC");
		return $this->db->get();
	}

	function jumlah_trafik($period,$id_kategori){
		$this->db->select_sum("nilai_trafik");
		$this->db->from("data_trafik");
		$this->db->where("id_kategori_kend",$id_kategori);
		$this->db->like("tanggal_input",$period,"AFTER");
		return $this->db->get();
	}

	function show_trafik($id,$period){
		$this->db->select("*");
		$this->db->from("data_trafik");
		$this->db->where("id_kategori_kend",$id);
		$this->db->like("tanggal_input",$period,"AFTER");
		$this->db->order_by("tanggal_input","DESC");
		return $this->db->get();
	}

	function penjualan_perhari($period){
		$this->db->select(array("SUM(harga_jual*qty) as penjualan","tanggal"));
		$this->db->from("ap_invoice_item");
		$this->db->like("tanggal",$period,"after");
		$this->db->group_by("tanggal");
		return $this->db->get();
	}

	function penjualan_perhari_range($date_start,$date_end){
		$this->db->select(array("SUM(harga_jual*qty) as penjualan","tanggal"));
		$this->db->from("ap_invoice_item");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->group_by("tanggal");
		return $this->db->get();
	}

	function penjualan_perhari_range_qty($date_start,$date_end){
		$this->db->select(array("SUM(qty) as penjualan","tanggal"));
		$this->db->from("ap_invoice_item");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->group_by("tanggal");
		return $this->db->get();
	}

	function penjualan_permonth_range($date_start, $date_end){
		$this->db->select(array("SUM(harga_jual*qty) as penjualan","tanggal"));
		$this->db->from("ap_invoice_item");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->order_by("tanggal","ASC");
		$this->db->group_by("MONTH(tanggal)");
		$this->db->group_by("YEAR(tanggal)");
		return $this->db->get();
	}

	function penjualan_permonth_range_qty($date_start, $date_end){
		$this->db->select(array("SUM(qty) as penjualan","tanggal"));
		$this->db->from("ap_invoice_item");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->order_by("tanggal","ASC");
		$this->db->group_by("MONTH(tanggal)");
		$this->db->group_by("YEAR(tanggal)");
		return $this->db->get();
	}


	function target_permonth_range($month,$years){
		$this->db->select("SUM(target) as target");
		$this->db->from("target");
		$this->db->where("bulan",$month);
		$this->db->where("tahun",$years);
		$this->db->group_by(array("bulan","tahun"));
		return $this->db->get();
	}

	function target_permonth_range_qty($month,$years){
		$this->db->select("SUM(target_qty) as target");
		$this->db->from("target");
		$this->db->where("bulan",$month);
		$this->db->where("tahun",$years);
		$this->db->group_by(array("bulan","tahun"));
		return $this->db->get();
	}

	function penjualan_peryear_range($date_start,$date_end){
		$this->db->select(array("SUM(harga_jual*qty) as penjualan","tanggal"));
		$this->db->from("ap_invoice_item");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->group_by("YEAR(tanggal)");
		return $this->db->get();
	}

	function penjualan_peryear_range_qty($date_start,$date_end){
		$this->db->select(array("SUM(qty) as penjualan","tanggal"));
		$this->db->from("ap_invoice_item");
		$this->db->where("tanggal BETWEEN '$date_start' AND '$date_end'");
		$this->db->group_by("YEAR(tanggal)");
		return $this->db->get();
	}

	function target_peryear_range($years){
		$this->db->select("SUM(target) as target");
		$this->db->from("target");
		$this->db->where("tahun",$years);
		$this->db->group_by("tahun");
		return $this->db->get();
	}

	function target_peryear_range_qty($years){
		$this->db->select("SUM(target_qty) as target");
		$this->db->from("target");
		$this->db->where("tahun",$years);
		$this->db->group_by("tahun");
		return $this->db->get();
	}

	function sales_perstand($tanggal,$id_kategori){
		$this->db->select("SUM(harga_jual*qty) as nilai_penjualan");
		$this->db->from("ap_invoice_item");
		$this->db->where("id_produk",$id_kategori);
		$this->db->where("tanggal",$tanggal);
		$this->db->group_by(array("id_produk","tanggal"));
		return $this->db->get();
	}

	function sales_perstand_qty($tanggal,$id_kategori){
		$this->db->select("SUM(qty) as nilai_penjualan");
		$this->db->from("ap_invoice_item");
		$this->db->where("id_produk",$id_kategori);
		$this->db->where("tanggal",$tanggal);
		$this->db->group_by(array("id_produk","tanggal"));
		return $this->db->get();
	}

	function sales_perstand_permonth($bulan,$tahun,$id_kategori){

		$period = $tahun.'-'.$bulan;

		$this->db->select("SUM(harga_jual*qty) as nilai_penjualan");
		$this->db->from("ap_invoice_item");
		$this->db->where("id_produk",$id_kategori);
		$this->db->like("tanggal",$period,"after");
		$this->db->group_by("id_produk");
		$this->db->group_by("MONTH(tanggal)");
		return $this->db->get();
	}

	function sales_perstand_permonth_qty($bulan,$tahun,$id_kategori){

		$period = $tahun.'-'.$bulan;

		$this->db->select("SUM(qty) as nilai_penjualan");
		$this->db->from("ap_invoice_item");
		$this->db->where("id_produk",$id_kategori);
		$this->db->like("tanggal",$period,"after");
		$this->db->group_by("id_produk");
		$this->db->group_by("MONTH(tanggal)");
		return $this->db->get();
	}

	function sales_perstand_peryear($tahun,$id_kategori){
		$this->db->select("SUM(harga_jual*qty) as nilai_penjualan");
		$this->db->from("ap_invoice_item");
		$this->db->where("id_produk",$id_kategori);
		$this->db->where("YEAR(tanggal)='$tahun'");
		$this->db->group_by("YEAR(tanggal)");
		return $this->db->get();
	}

	function sales_perstand_peryear_qty($tahun,$id_kategori){
		$this->db->select("SUM(qty) as nilai_penjualan");
		$this->db->from("ap_invoice_item");
		$this->db->where("id_produk",$id_kategori);
		$this->db->where("YEAR(tanggal)='$tahun'");
		$this->db->group_by("YEAR(tanggal)");
		return $this->db->get();
	}

	function nilai_trafik($bulan,$tahun,$id_kategori){
		$this->db->select_sum("nilai_trafik");
		$this->db->from("data_trafik");
		$this->db->where("MONTH(tanggal_input)",$bulan);
		$this->db->where("YEAR(tanggal_input)",$tahun);
		$this->db->where("id_kategori_kend",$id_kategori);
		return $this->db->get();
	}

	function nilai_trafik_join($bulan,$tahun){
		$this->db->select(array("SUM(data_trafik.nilai_trafik) as nilai_trafik","kategori_kendaraan.jenis_kendaraan"));
		$this->db->from("data_trafik");
		$this->db->join("kategori_kendaraan","kategori_kendaraan.id_kategori_kend = data_trafik.id_kategori_kend","left");
		$this->db->where("MONTH(data_trafik.tanggal_input)",$bulan);
		$this->db->where("YEAR(data_trafik.tanggal_input)",$tahun);
		$this->db->group_by("data_trafik.id_kategori_kend");
		return $this->db->get();
	}

	function total_trafik($bulan,$tahun){
		$this->db->select_sum("nilai_trafik");
		$this->db->from("data_trafik");
		$this->db->where("MONTH(data_trafik.tanggal_input)",$bulan);
		$this->db->where("YEAR(data_trafik.tanggal_input)",$tahun);
		return $this->db->get();
	}

	function data_pengunjung($id_kategori,$bulan,$tahun){
		$this->db->select_sum("nilai");
		$this->db->from("data_pengunjung");
		$this->db->where("MONTH(tanggal_input)",$bulan);
		$this->db->where("YEAR(tanggal_input)",$tahun);
		$this->db->where("id_kategori_pengunjung",$id_kategori);
		return $this->db->get();
	}

	function data_pengunjung_all($bulan,$tahun,$id_kategori){
		$this->db->select("*");
		$this->db->from("data_pengunjung");
		$this->db->where("MONTH(tanggal_input)",$bulan);
		$this->db->where("YEAR(tanggal_input)",$tahun);
		$this->db->where("id_kategori_pengunjung",$id_kategori);
		$this->db->order_by("tanggal_input","DESC");
		return $this->db->get();
	}

	function cek_data($param){
		$this->db->from("data_penjualan");
		$this->db->where("tanggal_input",$param);
		return $this->db->count_all_results();
	}

	function chart_hitrate($bulan,$tahun){
		$this->db->select(array("SUM(data_pengunjung.nilai) as pengunjung","kategori_pengunjung.kategori_pengunjung"));
		$this->db->from("data_pengunjung");
		$this->db->join("kategori_pengunjung","kategori_pengunjung.id_kategori_pengunjung = data_pengunjung.id_kategori_pengunjung","left");
		$this->db->where("MONTH(data_pengunjung.tanggal_input)",$bulan);
		$this->db->where("YEAR(data_pengunjung.tanggal_input)",$tahun);
		$this->db->group_by("data_pengunjung.id_kategori_pengunjung");
		return $this->db->get();
	}

	function select_pengunjung($bulan,$tahun){
		$this->db->select(array("SUM(data_pengunjung.nilai) as pengunjung"));
		$this->db->from("data_pengunjung");
		$this->db->where("MONTH(data_pengunjung.tanggal_input)",$bulan);
		$this->db->where("YEAR(data_pengunjung.tanggal_input)",$tahun);
		$this->db->where("data_pengunjung.id_kategori_pengunjung",1);
		return $this->db->get();
	}

	function select_pembeli($bulan,$tahun){
		$this->db->select(array("SUM(data_pengunjung.nilai) as pengunjung"));
		$this->db->from("data_pengunjung");
		$this->db->where("MONTH(data_pengunjung.tanggal_input)",$bulan);
		$this->db->where("YEAR(data_pengunjung.tanggal_input)",$tahun);
		$this->db->where("data_pengunjung.id_kategori_pengunjung != 1");
		return $this->db->get();
	}

	function select_pembeli_member($bulan,$tahun){
		$this->db->select(array("SUM(data_pengunjung.nilai) as pengunjung"));
		$this->db->from("data_pengunjung");
		$this->db->where("MONTH(data_pengunjung.tanggal_input)",$bulan);
		$this->db->where("YEAR(data_pengunjung.tanggal_input)",$tahun);
		$this->db->where("data_pengunjung.id_kategori_pengunjung",2);
		return $this->db->get();
	}

	function select_pembeli_nonmember($bulan,$tahun){
		$this->db->select(array("SUM(data_pengunjung.nilai) as pengunjung"));
		$this->db->from("data_pengunjung");
		$this->db->where("MONTH(data_pengunjung.tanggal_input)",$bulan);
		$this->db->where("YEAR(data_pengunjung.tanggal_input)",$tahun);
		$this->db->where("data_pengunjung.id_kategori_pengunjung",3);
		return $this->db->get();
	}

	function sales_global($bulan,$tahun){
		$this->db->select_sum("data_pengunjung.transaksi");
		$this->db->from("data_pengunjung");
		$this->db->where("MONTH(tanggal_input)",$bulan);
		$this->db->where("YEAR(tanggal_input)",$tahun);
		$this->db->where("id_kategori_pengunjung",2);
		$this->db->or_where("id_kategori_pengunjung",3);
		return $this->db->get();
	}

	function sales_member($bulan,$tahun){
		$this->db->select_sum("data_pengunjung.transaksi");
		$this->db->from("data_pengunjung");
		$this->db->where("id_kategori_pengunjung",2);
		$this->db->where("MONTH(tanggal_input)",$bulan);
		$this->db->where("YEAR(tanggal_input)",$tahun);
		return $this->db->get();
	}

	function sales_nonmember($bulan,$tahun){
		$this->db->select_sum("data_pengunjung.transaksi");
		$this->db->from("data_pengunjung");
		$this->db->where("id_kategori_pengunjung",3);
		$this->db->where("MONTH(tanggal_input)",$bulan);
		$this->db->where("YEAR(tanggal_input)",$tahun);
		return $this->db->get();
	}

	function harga_produk($id_kategori){
		$this->db->select("harga");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$id_kategori);
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->harga;
		}
	}



	
}
?>