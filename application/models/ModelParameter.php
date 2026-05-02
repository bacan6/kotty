<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelParameter extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function insertSatuan($data_insert){
		$this->db->insert("satuan",$data_insert);
	}

	function hapusSatuan($id){
		$this->db->delete("satuan",array("id_satuan" => $id));
	}

	function kategoriLevel2EditSQL($id,$dataUpdate){
		$this->db->where("id",$id);
		$this->db->update("ap_kategori_1",$dataUpdate);
		return $this->db->affected_rows();
	}
	function kategoriLevel2EditSQL_updateLevel3($id,$dataUpdate){
		$this->db->where("id_kategori_1",$id);
		$this->db->update("ap_kategori_2",$dataUpdate);
		return $this->db->affected_rows();
	}

	function hapusKategoriLevel2($id){
		$this->db->delete("ap_kategori_1",array("id" => $id));
		return $this->db->affected_rows();
	}

	function kategoriLevel3EditSQL($idKategori,$dataUpdate){
		$this->db->where("id",$idKategori);
		$this->db->update("ap_kategori_2",$dataUpdate);
		return $this->db->affected_rows();
	}

	function hapusKategori3($id){
		$this->db->delete("ap_kategori_2",array("id" => $id));
		return $this->db->affected_rows();
	}

	function kategoriLevel1Submit($data_insert){
		$this->db->insert("ap_kategori",$data_insert);
		return $this->db->affected_rows();	
	}

	function kategoriLevel2Submit($data_insert){
		$this->db->insert("ap_kategori_1",$data_insert);
		return $this->db->affected_rows();
	}

	function kategoriLevel3Submit($data_insert){
		$this->db->insert("ap_kategori_2",$data_insert);
		return $this->db->affected_rows();
	}

	function kategoriLevel1Edit($id,$data_update){
		$this->db->where("id_kategori",$id);
		$this->db->update("ap_kategori",$data_update);

		$affect = $this->db->affected_rows();
		return $affect;
	}

	function kategoriLevel1Hapus($id){
		$this->db->delete("ap_kategori",array("id_kategori" => $id));
		$affect = $this->db->affected_rows();
		return $affect;
	}

	function addKategoriSQL($data_insert){
		$this->db->insert("kategori",$data_insert);
	}

	function hapusKategori($id){
		$this->db->delete("kategori",array("id_kategori" => $id));
	}

	function editKategoriSQL($id,$data_update){
		$this->db->where("id_kategori",$id);
		$this->db->update("kategori",$data_update);
	}

	function addKategoriWaste($data_insert){
		$this->db->insert("keterangan_waste",$data_insert);
	}

	function hapusWaste($id){
		$this->db->delete("keterangan_waste",array("id_keterangan" => $id));
	}

	function updateWasteSQL($id,$data_update){
		$this->db->where("id_keterangan",$id);
		$this->db->update("keterangan_waste",$data_update);
	}
}
