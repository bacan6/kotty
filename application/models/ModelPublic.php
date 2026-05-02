<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelPublic extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function updateUser($user,$dataUpdate){
		$this->db->where("username",$user);
		$this->db->update("users",$dataUpdate);

		$affect = $this->db->affected_rows();
		return $affect;
	}

	function getUserFinger($user_id){
		$this->db->select(array("users.finger_data"));
		$this->db->from("users");
		$this->db->where("users.username",$user_id);
		$this->db->limit(1);
		return $this->db->get()->row();
	}

	function getUserFingerNew($user_id){
		$this->db->select(array("users.fingerprint1"));
		$this->db->from("users");
		$this->db->where("users.username",$user_id);
		$this->db->limit(1);
		return $this->db->get()->row();
	}

	function getDeviceBySn($sn){
		//`device_name`, `sn`, `vc`, `ac`, `vkey`
		$this->db->select(array("fingerprint_device.device_name","fingerprint_device.sn","fingerprint_device.vc","fingerprint_device.ac","fingerprint_device.vkey"));
		$this->db->from("fingerprint_device");
		$this->db->like("fingerprint_device.sn",$sn);
		$this->db->limit(1);
		return $this->db->get()->row();
	}

	function getDeviceAcSn($vc){
		$this->db->select(array("fingerprint_device.device_name","fingerprint_device.sn","fingerprint_device.vc","fingerprint_device.ac","fingerprint_device.vkey"));
		$this->db->from("fingerprint_device");
		$this->db->like("fingerprint_device.vc",$vc);
		$this->db->limit(1);
		return $this->db->get()->row();
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