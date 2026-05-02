<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Promo extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->model("modelPromo");
		$this->load->database();
		$this->load->library("session");

		$this->isLoggedIn($this->global['idUser'],13,64);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Promo";
		$this->loadViews("promo/body_promo",$this->global,NULL,"promo/footerPromo");
	}

	function data_promo(){
		$this->db->select('*');
		$this->db->from('k_promo');
		$this->db->join('brand', 'brand.id_brand = k_promo.id_brand', 'left');
		$query = $this->db->get();

		$data['promo'] = $query;
		$this->load->view("promo/data_promo",$data);
	}

	function select_brand(){
		$data['brand']=$this->db->get("brand")->result();
		$this->load->view("kupon/select_brand",$data);
	}

	function ajax_brand(){
		$q 	= $_GET['term'];
		$select2 = $this->modelPromo->brandAjax($q);

		$data_array = array();

		foreach($select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_brand,
									"text"	=> $row->id_brand." / ".$row->brand
								 );
		}

		echo json_encode($data_array);
	}


	function add_promo_sql(){

		$nama_promo 		= $_POST['nama_promo'];
		$brand 		= $_POST['brand'];
		$tgl 				=date('Y-m-d H:i:s');

        $uploadPath = 'uploads/files/promo/'; 
        $config['upload_path'] = $uploadPath; 
        $config['allowed_types'] = 'jpg|jpeg|png|gif'; 
        $config['overwrite']            = true;
        $config['remove_spaces']            = true;
        $config['encrypt_name']            = true;
        $config['max_size']             = 3000;

        $this->load->library('upload', $config);
        $this->upload->initialize($config); 

      	if (!$this->upload->do_upload('gambar')){

              $data['error'] = $this->upload->display_errors();

        }else{

        	$uploaded_data = $this->upload->data();

			$data_insert = array(
				 	'nm_promo' => $nama_promo,
				 	'id_brand' => $brand,
				    'gambar'=> $uploaded_data['file_name'],
				    'dibuat'=> $tgl);

			$this->db->insert("k_promo",$data_insert);
			$affect = $this->db->affected_rows();
			echo $affect;
		}
	}

	function form_edit_promo(){
		$id = $_POST['id'];
		$data['promo'] = $this->db->get_where("k_promo",array("id_promo" => $id));
		$this->load->view("promo/form_edit_promo",$data);
	}

	function edit_promo_sql(){

		$id 				= $_POST['id_promo'];
		$nama_promo 		= $_POST['nama_promo'];
		$brand 		= $_POST['brand'];
		$status 		= $_POST['status'];


		if(!empty($_FILES['gambar']['name'])){

			$uploadPath = 'uploads/files/promo/'; 
	        $config['upload_path'] = $uploadPath; 
	        $config['allowed_types'] = 'jpg|jpeg|png|gif'; 
	        $config['overwrite']            = true;
	        $config['remove_spaces']            = true;
	        $config['encrypt_name']            = true;
	        $config['max_size']             = 2000;

	        $this->load->library('upload', $config);
	        $this->upload->initialize($config); 

	      	if (!$this->upload->do_upload('gambar')){

	              $data['error'] = $this->upload->display_errors();

	        }else{
	        	$uploaded_data = $this->upload->data();

	        	@unlink($uploadPath.$_POST['file_lama']);

	        	$nm_file = $uploaded_data['file_name'];

	        }
		}else{
			$nm_file 			= $_POST['file_lama'];
		}
				$data_update = array(
				 	'nm_promo' => $nama_promo,
				 	'brand' => $brand,
				    'gambar'=> $nm_file,
				    'status'=> $status
				);

			$this->db->where("id_promo",$id);
			$this->db->update("k_promo",$data_update);
			$affect = $this->db->affected_rows();
			echo $affect;
		
	}

	function hapus_promo(){
		$id = $_POST['id'];
		$promo = $this->db->get_where("k_promo",array("id_promo" => $id))->row();
		
		$this->db->delete("k_promo",array("id_promo" => $id));
		@unlink('uploads/files/promo/'.$promo->gambar);
	}
}