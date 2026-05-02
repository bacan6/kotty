<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelCustomer extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function cekGroup($idCustomer){
		$this->db->select("kategori");
		$this->db->from("ap_customer");
		$this->db->where("id_customer",$idCustomer);
		$query = $this->db->get()->row();

		return $query->kategori;
	}
	
	function biaya_aktivasi($idGrup){
		$this->db->select("biaya_aktivasi");
		$this->db->from("ap_customer_group");
		$this->db->where("id_group",$idGrup);
		$query = $this->db->get()->row();

		return $query->biaya_aktivasi;
	}

	function hapus_customer($id){
		$this->db->delete("ap_customer",array("id_customer" => $id));
	}

	function editCustomer($idCustomer,$data_customer){
		$this->db->where("id_customer",$idCustomer);
		$this->db->update("ap_customer",$data_customer);
		$affect = $this->db->affected_rows();
		return $affect;
	}

	function cekStatusAktivasi($idCustomer){
		$this->db->select("activated");
		$this->db->from("ap_customer");
		$this->db->where("id_customer",$idCustomer);
		$query = $this->db->get()->row();

		return $query->activated;
	}

	function addCustomer($data_customer){
		$this->db->insert("ap_customer",$data_customer);
		$affect = $this->db->affected_rows();
		return $affect;
	}

	function hapusGroup($id){
		$this->db->delete("ap_customer_group",array("id_group" => $id));
	}

	function addCustomerGroup($data_grup){
		$this->db->insert("ap_customer_group",$data_grup);
	}

	function updateGroup($id,$dataUpdate){
		$this->db->where("id_group",$id);
		$this->db->update("ap_customer_group",$dataUpdate);
	}
}