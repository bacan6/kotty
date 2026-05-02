<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelGrowth extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function akumulasiPendapatan($departement,$idStore,$idUser,$start,$end){
		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_item.harga_jual) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
        if ($idUser>1 && $idUser!='42' && $idUser!='41' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
		}
		$this->db->where("ap_produk.id_kategori",$departement);
		$this->db->where("DATE(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->group_by("DATE(ap_invoice_number.tanggal)");
		return $this->db->get();
	}

	function akumulasiPendapatanPermonth($departement,$idStore,$idUser,$start,$end){
		$monthStart = substr($start, 0,2);
    	$yearStart  = substr($start, 3,4);

    	$monthEnd = substr($end, 0,2);
    	$yearEnd  = substr($end, 3,4);

		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_item.harga_jual) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
        if ($idUser>1 && $idUser!='42' && $idUser!='41' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
		}
		$this->db->where("ap_produk.id_kategori",$departement);
		$this->db->where("MONTH(ap_invoice_number.tanggal) BETWEEN '$monthStart' AND '$monthEnd'");
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$yearStart' AND '$yearEnd'");
		$this->db->group_by("MONTH(ap_invoice_number.tanggal)");
		return $this->db->get();
	}

	function akumulasiPendapatanPeryear($departement,$idStore,$idUser,$start,$end){
		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_item.harga_jual) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
        if ($idUser>1 && $idUser!='42' && $idUser!='41' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
		}
		$this->db->where("ap_produk.id_kategori",$departement);
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->group_by("YEAR(ap_invoice_number.tanggal)");
		return $this->db->get();
	}

	function akumulasiPendapatanPerstore($departement,$start,$end,$store){
		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_item.harga_jual) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->where("ap_produk.id_kategori",$departement);
		$this->db->where("DATE(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("DATE(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function akumulasiPendapatanPerstorePermonth($departement,$start,$end,$store){
		$monthStart = substr($start, 0,2);
    	$yearStart  = substr($start, 3,4);

    	$monthEnd = substr($end, 0,2);
    	$yearEnd  = substr($end, 3,4);

		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_item.harga_jual) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->where("ap_produk.id_kategori",$departement);
		$this->db->where("MONTH(ap_invoice_number.tanggal) BETWEEN '$monthStart' AND '$monthEnd'");
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$yearStart' AND '$yearEnd'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("MONTH(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function akumulasiPendapatanPerstorePeryear($departement,$start,$end,$store){
		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_item.harga_jual) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->where("ap_produk.id_kategori",$departement);
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("YEAR(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potongan($departement,$idStore,$idUser,$start,$end){
		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->where("ap_produk.id_kategori",$departement);
        if ($idUser>1 && $idUser!='42' && $idUser!='41' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		$this->db->where("DATE(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->group_by("DATE(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potonganPermonth($departement,$idStore,$idUser,$start,$end){
		$monthStart = substr($start, 0,2);
    	$yearStart  = substr($start, 3,4);

    	$monthEnd = substr($end, 0,2);
    	$yearEnd  = substr($end, 3,4);

		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->where("ap_produk.id_kategori",$departement);
        if ($idUser>1 && $idUser!='42' && $idUser!='41' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		$this->db->where("MONTH(ap_invoice_number.tanggal) BETWEEN '$monthStart' AND '$monthEnd'");
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$yearStart' AND '$yearEnd'");
		$this->db->group_by("MONTH(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potonganPeryear($departement,$idStore,$idUser,$start,$end){
		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->where("ap_produk.id_kategori",$departement);
        if ($idUser>1 && $idUser!='42' && $idUser!='41' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
        
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->group_by("YEAR(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potonganPerstore($departement,$start,$end,$store){
		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->where("ap_produk.id_kategori",$departement);
		$this->db->where("DATE(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("DATE(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potonganPerstorePermonth($departement,$start,$end,$store){
		$monthStart = substr($start, 0,2);
    	$yearStart  = substr($start, 3,4);

    	$monthEnd = substr($end, 0,2);
    	$yearEnd  = substr($end, 3,4);

		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->where("ap_produk.id_kategori",$departement);
		$this->db->where("MONTH(ap_invoice_number.tanggal) BETWEEN '$monthStart' AND '$monthEnd'");
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$yearStart' AND '$yearEnd'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("MONTH(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potonganPerstorePeryear($departement,$start,$end,$store){
		$this->db->select(array("SUM(COALESCE(ap_invoice_item.diskon,0)) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item","ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->where("ap_produk.id_kategori",$departement);
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("YEAR(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function listOfDepartment($department){
		$this->db->select(array("ap_kategori.id_kategori","ap_kategori.kategori"));
		$this->db->from("ap_kategori");
		$this->db->where("ap_kategori.id_kategori",$department);
		return $this->db->get();
	}

}
