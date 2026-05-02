<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelLiniMasa extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function akumulasiPendapatan($idStore,$idUser,$start,$end,$id_toko=''){
		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
        if ($idUser>1 && $idUser!='150' && $idUser!='101' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("DATE(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->group_by("DATE(ap_invoice_number.tanggal)");
		return $this->db->get();
	}

	function akumulasiPendapatanPermonth($idStore,$idUser,$start,$end,$id_toko=''){
		$monthStart = substr($start, 0,2);
    	$yearStart  = substr($start, 3,4);

    	$monthEnd = substr($end, 0,2);
    	$yearEnd  = substr($end, 3,4);

		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
        if ($idUser>1 && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal) BETWEEN '$monthStart' AND '$monthEnd'");
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$yearStart' AND '$yearEnd'");
		$this->db->group_by("MONTH(ap_invoice_number.tanggal)");
		return $this->db->get();
	}

	function akumulasiPendapatanPeryear($idStore,$idUser,$start,$end,$id_toko=''){
		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
        if ($idUser>1 && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->group_by("YEAR(ap_invoice_number.tanggal)");
		return $this->db->get();
	}

	function akumulasiPendapatanPerstore($start,$end,$store,$id_toko=''){
		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("DATE(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function akumulasiPendapatanPerstorePermonth($start,$end,$store,$id_toko=''){
		$monthStart = substr($start, 0,2);
    	$yearStart  = substr($start, 3,4);

    	$monthEnd = substr($end, 0,2);
    	$yearEnd  = substr($end, 3,4);

		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->where("MONTH(ap_invoice_number.tanggal) BETWEEN '$monthStart' AND '$monthEnd'");
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$yearStart' AND '$yearEnd'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("MONTH(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function akumulasiPendapatanPerstorePeryear($start,$end,$store,$id_toko=''){
		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as potongan","ap_invoice_number.tanggal","SUM(ap_invoice_number.total) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("YEAR(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potongan($idStore,$idUser,$start,$end,$id_toko=''){
		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as total"));
		$this->db->from("ap_invoice_number");
        if ($idUser>1 && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("DATE(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->group_by("DATE(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potonganPermonth($idStore,$idUser,$start,$end,$id_toko=''){
		$monthStart = substr($start, 0,2);
    	$yearStart  = substr($start, 3,4);

    	$monthEnd = substr($end, 0,2);
    	$yearEnd  = substr($end, 3,4);

		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as total"));
		$this->db->from("ap_invoice_number");
        if ($idUser>1 && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("MONTH(ap_invoice_number.tanggal) BETWEEN '$monthStart' AND '$monthEnd'");
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$yearStart' AND '$yearEnd'");
		$this->db->group_by("MONTH(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potonganPeryear($idStore,$idUser,$start,$end,$id_toko=''){
		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as total"));
		$this->db->from("ap_invoice_number");
        if ($idUser>1 && $idUser!='101' && $idUser!='150' ){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
        if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->group_by("YEAR(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potonganPerstore($start,$end,$store,$id_toko=''){
		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->where("DATE(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("DATE(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potonganPerstorePermonth($start,$end,$store,$id_toko=''){
		$monthStart = substr($start, 0,2);
    	$yearStart  = substr($start, 3,4);

    	$monthEnd = substr($end, 0,2);
    	$yearEnd  = substr($end, 3,4);

		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->where("MONTH(ap_invoice_number.tanggal) BETWEEN '$monthStart' AND '$monthEnd'");
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$yearStart' AND '$yearEnd'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("MONTH(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

	function potonganPerstorePeryear($start,$end,$store,$id_toko=''){
		$this->db->select(array("SUM(COALESCE(ap_invoice_number.diskon,0)+COALESCE(ap_invoice_number.diskon_free)+COALESCE(ap_invoice_number.poin_value,0)+COALESCE(ap_invoice_number.diskon_otomatis,0)) as total"));
		$this->db->from("ap_invoice_number");
		$this->db->where("YEAR(ap_invoice_number.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_number.id_toko",$store);
		$this->db->group_by("YEAR(ap_invoice_number.tanggal)");
		return $this->db->get()->result();
	}

}
