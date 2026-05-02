<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelPromo extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	function brandAjax($q){
		$this->db->select(array("brand.id_brand","brand.brand"));
		$this->db->from("brand");
		$this->db->group_start();
		$this->db->like("brand.id_brand",$q);
		$this->db->or_like("brand.brand",$q);	
		$this->db->group_end();
		return $this->db->get();
	}

	
}
