<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Banner extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->model("modelBanner");
		$this->load->database();
		$this->load->library("session");

		$this->isLoggedIn($this->global['idUser'],13,64);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Banner";
		$this->loadViews("banner/body_banner",$this->global,NULL,"banner/footerBanner");
	}

	function data_banner(){
		$this->db->select('*');
		$this->db->from('k_banner');
		$query = $this->db->get();

		$data['banner'] = $query;
		$this->load->view("banner/data_banner",$data);
	}

	function add_banner_sql(){
		// error_reporting(E_ALL);ini_set('display_errors',1);
		$nama_banner 		= $_POST['nama_banner'];
		$posisi 		= $_POST['posisi'];
		$tgl 				=date('Y-m-d H:i:s');

        $uploadPath = 'uploads/files/banner/'; 
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
			  var_dump($data);

        }else{

        	$uploaded_data = $this->upload->data();

			$data_insert = array(
				 	'nm_banner' => $nama_banner,
				 	'posisi' => $posisi,
				    'gambar'=> $uploaded_data['file_name'],
				    'status'=>'A',
				    'dibuat'=> $tgl);

			$this->db->insert("k_banner",$data_insert);
			$affect = $this->db->affected_rows();
			echo $affect;
		}
	}

	function form_edit_Banner(){
		$id = $_POST['id'];
		$data['banner'] = $this->db->get_where("k_banner",array("id_banner" => $id));
		$this->load->view("banner/form_edit_banner",$data);
	}

	function edit_Banner_sql(){

		$id 				= $_POST['id_banner'];
		$nama_banner 		= $_POST['nama_banner'];
		$posisi 		= $_POST['posisi'];
		$status 		= $_POST['status'];


		if(!empty($_FILES['gambar']['name'])){

			$uploadPath = 'uploads/files/banner/'; 
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
				 	'nm_banner' => $nama_banner,
				 	'posisi' => $posisi,
				 	'status' => $status,
				    'gambar'=> $nm_file,
				    'status'=> $status
				);

			$this->db->where("id_banner",$id);
			$this->db->update("k_banner",$data_update);
			$affect = $this->db->affected_rows();
			echo $affect;
		
	}

	function hapus_Banner(){
		$id = $_POST['id'];
		$Banner = $this->db->get_where("k_banner",array("id_banner" => $id))->row();
		
		$this->db->delete("k_banner",array("id_banner" => $id));
		@unlink('uploads/files/banner/'.$Banner->gambar);
	}
}