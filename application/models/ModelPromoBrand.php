<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelPromoBrand extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function showHari($id){
		if($id=='0') echo "Minggu ";
		else if($id==1) echo "Senin ";
		else if($id==2) echo "Selasa ";
		else if($id==3) echo "Rabu ";
		else if($id==4) echo "Kamis ";
		else if($id==5) echo "Jumat ";
		else if($id==6) echo "Sabtu ";
	}

	function cek_tanggal_promo($tanggal){
		$this->db->from("ap_promo_brand_rules");
		$this->db->where("tanggal_buat",$tanggal);
		return $this->db->count_all_results();
	}

	function totalPromoSupplier(){
		$this->db->from("ap_promo_brand_rules");
		return $this->db->count_all_results();
	}

	function viewPOProduk($limit,$start,$search='',$idUser='',$idStore=''){
		$dataSelect = array(
								"ap_promo_brand_rules.no_promo","ap_promo_brand_rules.tanggal_buat","ap_promo_brand_rules.rules_type","ap_promo_brand_rules.date_start","ap_promo_brand_rules.date_end","ap_promo_brand_rules.minBelanja","ap_promo_brand_rules.discount","brand.brand","users.first_name"
						   );

		$this->db->select($dataSelect);
		$this->db->from("ap_promo_brand_rules");
		$this->db->join("brand","brand.id_brand = ap_promo_brand_rules.id_brand","left");
		$this->db->join("users","users.id = ap_promo_brand_rules.id_pic");

		if(!empty($search)){
			$this->db->like("ap_promo_brand_rules.no_promo",$search);
			$this->db->or_like("brand.brand",$search);
		}
        

		$this->db->limit($limit,$start);
		$this->db->order_by("ap_promo_brand_rules.tanggal_buat","DESC");
		$this->db->order_by("ap_promo_brand_rules.no_promo","DESC");
        
        
        if ($idUser!=1 && $idUser!=101 && $idUser!=150){
            $this->db->where("ap_promo_brand_rules.id_toko",$idStore);
        }
        
		return $this->db->get();
	}

	function infoPurchase($no_po){
		$this->db->select(array("ap_promo_brand_rules.tanggal_buat","ap_promo_brand_rules.keterangan","brand.brand","ap_promo_brand_rules.rules_type","ap_promo_brand_rules.date_start","ap_promo_brand_rules.date_end","ap_promo_brand_rules.id_brand","ap_promo_brand_rules.minBelanja","ap_promo_brand_rules.discount","ap_promo_brand_rules.setJam","ap_promo_brand_rules.JamMulai","ap_promo_brand_rules.JamSelesai","ap_store.store"));
		$this->db->from("ap_promo_brand_rules");
		$this->db->join("brand","brand.id_brand = ap_promo_brand_rules.id_brand","left");
		$this->db->join("ap_store","ap_store.id_store=ap_promo_brand_rules.id_toko");
		$this->db->where("ap_promo_brand_rules.no_promo",$no_po);
		$this->db->group_by("ap_promo_brand_rules.no_promo");
		$query = $this->db->get()->row();
		return $query;
	}


	function insertPONumber($data_masuk){
		$this->db->insert("ap_promo_brand_rules",$data_masuk);
	}
}