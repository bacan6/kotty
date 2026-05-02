<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Voucerbelanja extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],8,64);
	}

	function index(){
		$this->global['pageTitle'] = "Solusinformatika.com - Voucer Belanja";
		$this->loadViews("voucerbelanja/body_voucer",$this->global,NULL,"voucerbelanja/footerVoucer");
	}

	function data_voucer(){
		
		$data['voucer'] = $this->db->get("k_voucer_belanja");
		$this->load->view("voucerbelanja/data_voucer",$data);
	}


	function add_voucer_sql(){

		$nama_voucer 		= $_POST['nama_voucer'];
		$tgl_mulai 			= $_POST['tgl_mulai'];
		$tgl_selesai 		= $_POST['tgl_selesai'];
		$quota 				= $_POST['quota'];
		$nominal 			= $_POST['nominal'];
		$poin 				= $_POST['poin'];
		$syarat 			= $_POST['syarat'];
		$tgl 				= date('Y-m-d H:i:s');

        $uploadPath = 'uploads/files/voucer_belanja/'; 
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

			$huruf="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	        $kode=substr(str_shuffle($huruf), 0, 6).date('s');


			$data_insert = array(
					'kode' => $kode,
				 	'nama' => $nama_voucer,
				  	'tgl_mulai'=> date("Y-m-d H:i:s",strtotime($tgl_mulai)),
				   	'tgl_selesai'=> date("Y-m-d H:i:s",strtotime($tgl_selesai)),
				    'quota'=> $quota,
				    'sisa'=> $quota,
				    'nominal'=> $nominal,
				    'poin'=> $poin,
				    'status'=> "A",
				    'syarat'=> $syarat,
				    'gambar'=> $uploaded_data['file_name'],
				    'dibuat'=> $tgl,
					'id_pic' => $this->global['idUser'],
					'id_toko' => $this->global['idStore']);

			$this->db->insert("k_voucer_belanja",$data_insert);
			$affect = $this->db->affected_rows();
			echo $affect;
		}
	}

	function form_edit_voucer(){
		$id = $_POST['id'];
		$data['voucer'] = $this->db->get_where("k_voucer_belanja",array("kode" => $id));
		$this->load->view("voucerbelanja/form_edit_voucer",$data);
	}

	function edit_voucer_sql(){

		$id 				= $_POST['kode'];
		$nama_voucer 		= $_POST['nama_voucer'];
		$tgl_mulai 			= $_POST['tgl_mulai'];
		$tgl_selesai 		= $_POST['tgl_selesai'];
		$quota 				= $_POST['quota'];
		$nominal 			= $_POST['nominal'];
		$poin 				= $_POST['poin'];
		$syarat 			= $_POST['syarat'];
		$status 			= $_POST['status'];
		$sisa				= $_POST['quota']+$_POST['sisa'];


		if(!empty($_FILES['gambar']['name'])){

			$uploadPath = 'uploads/files/voucer_belanja/'; 
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
				 	'nama' => $nama_voucer,
				  	'tgl_mulai'=> date("Y-m-d H:i:s",strtotime($tgl_mulai)),
				   	'tgl_selesai'=> date("Y-m-d H:i:s",strtotime($tgl_selesai)),
				    'quota'=> $quota,
				    'sisa'=> $quota,
				    'nominal'=> $nominal,
				    'poin'=> $poin,
				    'status'=> $status,
				    'syarat'=> $syarat,
				    'gambar'=> $nm_file,
					'id_pic' => $this->global['idUser'],
					'id_toko' => $this->global['idStore']
				);

			$this->db->where("kode",$id);
			$this->db->update("k_voucer_belanja",$data_update);
			$affect = $this->db->affected_rows();
			echo $affect;
		
	}

	function hapus_voucer(){
		$id = $_POST['id'];
		$voucer = $this->db->get_where("k_voucer_belanja",array("kode" => $id))->row();
		
		$this->db->delete("k_voucer_belanja",array("kode" => $id));
		@unlink('uploads/files/voucer_belanja/'.$voucer->gambar);
	}
}