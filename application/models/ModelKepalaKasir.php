<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelKepalaKasir extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function getUserFinger($user_id){
		$this->db->select(array("user_approver.finger_data"));
		$this->db->from("user_approver");
		$this->db->like("user_approver.username",$user_id);
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

	function totalUserAkif(){
		$this->db->from("user_approver");
		$this->db->where("NA",'N');
		return $this->db->count_all_results();
	}

	function viewUser($limit,$start,$search=''){
		$this->db->select(array("user_approver.finger_data","user_approver.username","user_approver.Nama","user_approver.pass","user_approver.NA"));
		$this->db->from("user_approver");

		if(!empty($search)){
			$this->db->like("user_approver.Nama",$search);
		}

		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function cekUserExist($UserID){
		$this->db->from("user_approver");
		$this->db->where("username",$UserID);
		return $this->db->count_all_results();
	}

	function insertUser($dataArray){
		$this->db->insert("user_approver",$dataArray);	
	}

	function editUser($UserID,$dataArray){
		$this->db->where("username",$UserID);
		$this->db->update("user_approver",$dataArray);
	}
	function approve($idUser,$ip,$dataArray){
		$this->db->where("idUser",$idUser);
		$this->db->where("ip",$ip);
		$this->db->update("ap_cart_approved",$dataArray);
	}
	function insertLogFinger($dataArray){
		$this->db->insert("log_finger",$dataArray);	
	}

}