<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelPurchaseApproval extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}


	function cek_no_request(){

		$day 	= date('d');
		$month  = date('m');
		$year 	= date('Y');

		$this->db->from("rq_purchase_no");
		$this->db->where("MONTH(tanggal_request)",$month);
		$this->db->where("YEAR(tanggal_request)",$year);
		return $this->db->count_all_results();
	}

	function wait_approve_list(){
		$this->db->select("*");
		$this->db->from("rq_purchase_no");
		$this->db->join("user","user.id_user = rq_purchase_no.id_pic","left");
		$this->db->join("bahan_baku","bahan_baku.sku = rq_purchase_no.sku","left");
		$this->db->where("rq_purchase_no.status",0);
		$this->db->order_by("tanggal_request","DESC");
		return $this->db->get();
	}

	function data_request($no_request){
		$this->db->select("*");
		$this->db->from("rq_purchase_no");
		$this->db->join("user","user.id_user = rq_purchase_no.id_pic","left");
		$this->db->join("bahan_baku","bahan_baku.sku = rq_purchase_no.sku","left");
		$this->db->order_by("tanggal_request","DESC");
		$this->db->where("rq_purchase_no.purchase_no",$no_request);
		return $this->db->get();
	}

	function item_request($no_request){
		$this->db->select(array("rq_purchase_item.harga","supplier.supplier","rq_purchase_item.remark","rq_purchase_item.id","rq_purchase_item.sku","rq_purchase_item.isChoose"));
		$this->db->from("rq_purchase_item");
		$this->db->join("supplier","supplier.id_supplier = rq_purchase_item.id_supplier","left");
		$this->db->where("rq_purchase_item.purchase_no",$no_request);
		return $this->db->get();
	}

	function daftar_request_approved(){
		$this->db->select("*");
		$this->db->from("rq_purchase_no");
		$this->db->join("user","user.id_user = rq_purchase_no.id_pic","left");
		$this->db->join("bahan_baku","bahan_baku.sku = rq_purchase_no.sku","left");
		$this->db->join("rq_purchase_approved","rq_purchase_approved.purchase_no = rq_purchase_no.purchase_no","left");
		$this->db->where("rq_purchase_no.status",1);
		$this->db->order_by("tanggal_request","DESC");
		return $this->db->get();
	}

	function daftar_request_ditolak(){
		$this->db->select("*");
		$this->db->from("rq_purchase_no");
		$this->db->join("user","user.id_user = rq_purchase_no.id_pic","left");
		$this->db->join("bahan_baku","bahan_baku.sku = rq_purchase_no.sku","left");
		//$this->db->join("rq_purchase_approved","rq_purchase_approved.purchase_no = rq_purchase_no.purchase_no","left");
		$this->db->where("rq_purchase_no.status",2);
		$this->db->order_by("tanggal_request","DESC");
		return $this->db->get();
	}



		


}