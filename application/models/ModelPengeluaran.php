<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelPengeluaran extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function insertJurnal($data_insert){
		$this->db->insert("jurnal_umum",$data_insert);
	}

	function hapusJurnal($id){
		$this->db->delete("jurnal_umum",array("JurnalID" => $id));
	}

	function totalDebet($idStore){
		$this->db->select("sum(D) as Debet");
		$this->db->from("jurnal_umum");
		$this->db->where("id_toko",$idStore);
		return $this->db->get()->result();
	}
    function totalKredit($idStore){
		$this->db->select("sum(K) as Kredit");
		$this->db->from("jurnal_umum");
		$this->db->where("id_toko",$idStore);
		return $this->db->get()->result();
        
	}
}
