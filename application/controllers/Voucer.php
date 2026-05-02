<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Voucer extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],8,64);
	}

	function index(){
		$this->global['pageTitle'] = "Solusinformatika.com - Voucer";
		$this->loadViews("voucer/body_voucer",$this->global,NULL,"voucer/footerVoucer");
	}

	function data_voucer(){
		
		$data['voucer'] = $this->db->get("k_voucer");
		$this->load->view("voucer/data_voucer",$data);
	}

	function select_produk(){
		$data['produk']='';
		$this->load->view("voucer/select_produk",$data);
	}

	function ajax_produk(){
		$q 	= $_GET['term'];
		$select2 = $this->modelvoucer->produkAjax($q);

		$data_array = array();

		foreach($select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}


	function add_voucer_sql(){

		$nama_voucer 		= $_POST['nama_voucer'];
		$tgl_berlaku 		= $_POST['tgl_berlaku'];
		$tgl_expired 		= $_POST['tgl_expired'];
		$jml 				= $_POST['jml'];
		$max_tukar 			= $_POST['max_tukar'];
		$potongan 			= $_POST['potongan'];
		$min_trans 			= $_POST['min_transaksi'];
		$syarat 			= $_POST['syarat'];
		$tgl 				= date('Y-m-d H:i:s');

        $uploadPath = 'uploads/files/voucer/'; 
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
	        $id_voucer=substr(str_shuffle($huruf), 0, 6).date('s');


			$data_insert = array(
					'id_voucer' => $id_voucer,
				 	'nm_voucer' => $nama_voucer,
				  	'tgl_berlaku'=> date("Y-m-d H:i:s",strtotime($tgl_berlaku)),
				   	'tgl_expired'=> date("Y-m-d H:i:s",strtotime($tgl_expired)),
				    'jml'=> $jml,
				    'sisa'=> $jml,
				    'potongan'=> $potongan,
				    'min_transaksi'=> $min_trans,
				    'status'=> "A",
				    'syarat'=> $syarat,
				    'gambar'=> $uploaded_data['file_name'],
				    'dibuat'=> $tgl,
					'id_pic' => $this->global['idUser'],
					'id_toko' => $this->global['idStore']);

			$this->db->insert("k_voucer",$data_insert);
			$affect = $this->db->affected_rows();
			echo $affect;
		}
	}

	function form_edit_voucer(){
		$id = $_POST['id'];
		$data['voucer'] = $this->db->get_where("k_voucer",array("id_voucer" => $id));
		$this->load->view("voucer/form_edit_voucer",$data);
	}

	function edit_voucer_sql(){

		$id 				= $_POST['id_voucer'];
		$nama_voucer 		= $_POST['nama_voucer'];
		$tgl_berlaku 		= $_POST['tgl_berlaku'];
		$tgl_expired 		= $_POST['tgl_expired'];
		$jml 				= $_POST['jml'];
		$potongan 			= $_POST['potongan'];
		$min_trans 			= $_POST['min_transaksi'];
		$syarat 			= $_POST['syarat'];
		$status 			= $_POST['status'];
		$sisa				= $_POST['jml']+$_POST['sisa'];


		if(!empty($_FILES['gambar']['name'])){

			$uploadPath = 'uploads/files/voucer/'; 
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
				 	'nm_voucer' => $nama_voucer,
				  	'tgl_berlaku'=> date("Y-m-d H:i:s",strtotime($tgl_berlaku)),
				   	'tgl_expired'=> date("Y-m-d H:i:s",strtotime($tgl_expired)),
				    'jml'=> $jml,
				    'sisa'=> $sisa,
				    'potongan'=> $potongan,
				    'min_transaksi'=> $min_trans,
				    'status'=> $status,
				    'syarat'=> $syarat,
				    'gambar'=> $nm_file,
					'id_pic' => $this->global['idUser'],
					'id_toko' => $this->global['idStore']
				);

			$this->db->where("id_voucer",$id);
			$this->db->update("k_voucer",$data_update);
			$affect = $this->db->affected_rows();
			echo $affect;
		
	}

	function hapus_voucer(){
		$id = $_POST['id'];
		$voucer = $this->db->get_where("k_voucer",array("id_voucer" => $id))->row();
		
		$this->db->delete("k_voucer",array("id_voucer" => $id));
		@unlink('uploads/files/voucer/'.$voucer->gambar);
	}
}