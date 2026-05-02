<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelKupon extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function kategoriMember($id_customer){
		$this->db->select("kategori");
		$this->db->from("ap_customer");
		$this->db->group_start();
		$this->db->where("id_customer",$id_customer);
		$this->db->or_where("kontak",$id_customer);
		$this->db->group_end();
		$this->db->where("id_customer not in ('082288441715','082385362727','081318727242')");
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->kategori;
		}
	}

	function daftarProdukAll($idToko=''){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan","((ap_produk_price.harga - ap_produk_price.hpp)/ap_produk_price.harga *100) as margin","ap_produk_price.harga","ap_produk_price.hpp","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","ap_produk.status","ap_produk.id_supplier"));
		$this->db->from("ap_produk");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk and ap_produk_price.id_toko='$idToko'","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		$this->db->where("ap_produk_price.id_toko",$idToko); 
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("ap_produk.nama_produk");
		return $this->db->get();
	}
	function produkAjax($q){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk"));
		$this->db->from("ap_produk");
		$this->db->group_start();
		$this->db->like("ap_produk.id_produk",$q);
		$this->db->or_like("ap_produk.nama_produk",$q);	
		$this->db->group_end();
		//$this->db->group_start();
		$this->db->where("ap_produk.status",1);
		//$this->db->or_where("ap_produk.status",0);
		//$this->db->group_end();
		return $this->db->get();
	}

	function getHPP($idProduk,$idStore){
		$this->db->select("hpp");
		$this->db->from("ap_produk_price");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_toko",$idStore);
		$query = $this->db->get()->row();
		return $query->hpp;
	}

	function poinMember($id_customer){
		$this->db->select("point");
		$this->db->from("ap_customer");
		$this->db->group_start();
		$this->db->where("id_customer",$id_customer);
		$this->db->or_where("kontak",$id_customer);
		$this->db->group_end();
		$this->db->where("id_customer not in ('082288441715','082385362727','081318727242')");
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->point;
		}
	}

	function namaMember($id_customer){
		$this->db->select("nama");
		$this->db->from("ap_customer");
		$this->db->group_start();
		$this->db->where("id_customer",$id_customer);
		$this->db->or_where("kontak",$id_customer);
		$this->db->group_end();
		$this->db->where("id_customer not in ('082288441715','082385362727','081318727242')");
		$query = $this->db->get();

		foreach($query->result() as $row){
			return $row->nama;
		}
	}


	function insert_kupon($data_insert){

		$this->db->insert("k_kupon_member",$data_insert);
		$affect = $this->db->affected_rows();

		$id = $this->db->insert_id();

		if($affect > 0){
			return $id;
		}
	}

	function update_member($id_customer,$data_update){

		$this->db->group_start();
		$this->db->where("id_customer",$id_customer);
		$this->db->or_where("kontak",$id_customer);
		$this->db->group_end();
		$this->db->update("ap_customer",$data_update);
		$affect = $this->db->affected_rows();

		if($affect > 0){
			return 1;
		}
	}

	function update_kupon($id,$data_update){

		$this->db->where("id_kupon",$id);
		$this->db->update("k_kupon",$data_update);
		$affect = $this->db->affected_rows();

		if($affect > 0){
			return 1;
		}
	}

	function update_kupon_member($data_update,$id_kupon_member,$id_customer){
		$this->db->where("id_kupon_member",$id_kupon_member);
		$this->db->update("k_kupon_member",$data_update);
		$affect = $this->db->affected_rows();

		if($affect > 0){
			return 1;
		}
	}

	function dataKupon($id,$idStore){
        $this->db->select("k_kupon.*,k_kupon_member.id_kupon_member");
		$this->db->from("k_kupon_member");
		$this->db->join("k_kupon","k_kupon.id_kupon=k_kupon_member.id_kupon","left");
		$this->db->where('k_kupon.id_toko',$idStore);
		//$this->db->group_start();
		$this->db->where('k_kupon_member.id_customer',$id);
		//$this->db->or_where('k_kupon_member.kontak',$id);
		//$this->db->group_end();
		$this->db->where('k_kupon_member.status','In');
		$this->db->where('k_kupon.status','A');
		// $this->db->where("k_kupon.tgl_expired > '".date("Y-m-d H:i:s")."'");
		// $this->db->where("k_kupon.tgl_berlaku < '".date("Y-m-d H:i:s")."'");
        return $this->db->get();
    }
	
}
