<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Kupon extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("modelKupon");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],8,64);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Kupon";
		$data['ap_customer_group'] = $this->db->get("ap_customer_group");
		$this->loadViews("kupon/body_kupon",$this->global,$data,"kupon/footerKupon");
	}

	function data_kupon(){
		$this->db->select(array("k_kupon.gambar","k_kupon.id_kupon","k_kupon.nm_kupon","k_kupon.tgl_berlaku","k_kupon.tgl_expired","k_kupon.jml","k_kupon.sisa","k_kupon.status","ap_customer_group.group_customer","ap_produk.nama_produk","k_kupon.id_produk","ap_store.store"));
		$this->db->from("k_kupon");
		$this->db->join("ap_produk","ap_produk.id_produk=k_kupon.id_produk","left");
		$this->db->join("ap_customer_group","ap_customer_group.id_group=k_kupon.id_group","left");
		$this->db->join("ap_store","ap_store.id_store=k_kupon.id_toko");
		if($this->global['idUser']>2){
			$this->db->where("k_kupon.id_toko",$this->global['idStore']);
		}
		$data['kupon'] = $this->db->get();
		$this->load->view("kupon/data_kupon",$data);
	}

	function select_produk(){
		$data['produk']='';
		$this->load->view("kupon/select_produk",$data);
	}

	function ajax_produk(){
		$q 	= $_GET['term'];
		$select2 = $this->modelKupon->produkAjax($q);

		$data_array = array();

		foreach($select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}


	function add_kupon_sql(){

		$nama_kupon 		= $_POST['nama_kupon'];
		$tgl_berlaku 		= $_POST['tgl_berlaku'];
		$tgl_expired 		= $_POST['tgl_expired'];
		$jml 				= $_POST['jml'];
		$max_tukar 			= $_POST['max_tukar'];
		$jml_point 			= $_POST['point'];
		$potongan 			= $_POST['potongan'];
		$produk 			= $_POST['produk'];
		$id_group 			= $_POST['id_group'];
		$hpp				= $this->modelKupon->getHPP($produk,$this->global['idStore']);
		$syarat 			= $_POST['syarat'];
		$tgl 				= date('Y-m-d H:i:s');

        $uploadPath = 'uploads/files/kupon/'; 
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
	        $id_kupon=substr(str_shuffle($huruf), 0, 6).date('s');

			$data_insert = array(
					'id_kupon' => $id_kupon,
				 	'nm_kupon' => $nama_kupon,
				  	'tgl_berlaku'=> date("Y-m-d H:i:s",strtotime($tgl_berlaku)),
				   	'tgl_expired'=> date("Y-m-d H:i:s",strtotime($tgl_expired)),
				    'jml'=> $jml,
				    'sisa'=> $jml,
				    'max_tukar'=> $max_tukar,
				    'jml_point'=> $jml_point,
				    'id_produk'=> $produk,
					'hpp'=> $hpp,
				    'potongan'=> 0,
				    'status'=> "A",
				    'syarat'=> $syarat,
				    'gambar'=> $uploaded_data['file_name'],
				    'dibuat'=> $tgl,
					'id_pic' => $this->global['idUser'],
					'id_toko' => $this->global['idStore'],
					'id_group' => $id_group);

			$this->db->insert("k_kupon",$data_insert);
			$affect = $this->db->affected_rows();
			echo $affect;
		}
	}

	function form_edit_kupon(){
		$id = $_POST['id'];
		$data['kupon'] = $this->db->get_where("k_kupon",array("id_kupon" => $id));
		$data['ap_customer_group'] = $this->db->get("ap_customer_group");
		$this->load->view("kupon/form_edit_kupon",$data);
	}

	function edit_kupon_sql(){

		$id 				= $_POST['id_kupon'];
		$nama_kupon 		= $_POST['nama_kupon'];
		$tgl_berlaku 		= $_POST['tgl_berlaku'];
		$tgl_expired 		= $_POST['tgl_expired'];
		$jml 				= $_POST['jml'];
		$max_tukar 			= $_POST['max_tukar'];
		$jml_point 			= $_POST['point'];
		$potongan 			= $_POST['potongan'];
		$syarat 			= $_POST['syarat'];
		$status 			= $_POST['status'];
		$sisa				= $_POST['sisa'];
		$produk 			= $_POST['produk'];
		$id_group			= $_POST['id_group'];
		$hpp				= $this->modelKupon->getHPP($produk,$this->global['idStore']);


		if(!empty($_FILES['gambar']['name'])){

			$uploadPath = 'uploads/files/kupon/'; 
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
				 	'nm_kupon' => $nama_kupon,
				  	'tgl_berlaku'=> date("Y-m-d H:i:s",strtotime($tgl_berlaku)),
				   	'tgl_expired'=> date("Y-m-d H:i:s",strtotime($tgl_expired)),
				    'jml'=> $jml,
				    'sisa'=> $sisa,
				    'max_tukar'=> $max_tukar,
				    'jml_point'=> $jml_point,
				    'id_produk'=> $produk,
					'hpp'=> $hpp,
				    'potongan'=> 0,
				    'status'=> $status,
				    'syarat'=> $syarat,
				    'gambar'=> $nm_file,
					'id_pic' => $this->global['idUser'],
					'id_group' => $id_group
				);

			$this->db->where("id_kupon",$id);
			$this->db->update("k_kupon",$data_update);
			$affect = $this->db->affected_rows();
			echo $affect;
		
	}

	function hapus_kupon(){
		$id = $_POST['id'];
		$kupon = $this->db->get_where("k_kupon",array("id_kupon" => $id))->row();
		
		$this->db->delete("k_kupon",array("id_kupon" => $id));
		@unlink('uploads/files/kupon/'.$kupon->gambar);
	}
}