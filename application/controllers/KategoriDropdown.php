<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class KategoriDropdown extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model('model1');
		$this->load->library("session");
	}

	function get_subkategori(){
		$id_kategori = $_POST['id_kategori'];

		$data['show_sub'] = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori));

		$count = 0;

		foreach($data['show_sub']->result() as $row){
			$count = $count+$row->id;
		}

		if(!empty($_POST['id_kategori']) && $count > 0){
			$this->load->view("bahan_baku/show_sub",$data);
		}
	}

	function get_subkategori_2(){
		$id_kategori_2 = $_POST['id'];

		$data['show_sub'] = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $id_kategori_2));
		$count = 0;

		foreach($data['show_sub']->result() as $row){
			$count = $count+$row->id;
		}

		if(!empty($_POST['id']) && $count > 0){
			$this->load->view("bahan_baku/show_sub2",$data);
		}
	}
}