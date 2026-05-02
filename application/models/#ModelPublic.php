<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelPublic extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function checkEmailIfExist($email){
		$this->db->from("users");
		$this->db->where("email",$email);
		return $this->db->count_all_results();
	}

	function checkUsername($username){
		$this->db->from("users");
		$this->db->where("username",$username);
		return $this->db->count_all_results();
	}

	function idUser($email){
		$this->db->select("id");
		$this->db->from("users");
		$this->db->where("email",$email);
		$query = $this->db->get()->row();

		return $query->id;
	}

	function updateInfoPerusahaanSQL($dataUpdate){
		$this->db->where("id",1);
		$this->db->update("ap_receipt",$dataUpdate);
		$affect = $this->db->affected_rows();
		return $affect;
	}

	function updateEmailSetting($dataUpdate){
		$this->db->where("id",1);
		$this->db->update("settingemail",$dataUpdate);

		$affect = $this->db->affected_rows();
		return $affect;
	}
}