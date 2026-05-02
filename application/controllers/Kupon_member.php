<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Kupon_member extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("modelKupon");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],3,66);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Promo Kupon Produk";
		$id = $_GET['id_customer'];
		$data['id_customer'] = $id;
		$this->loadViews("kupon_member/body_kupon",$this->global,$data,"kupon_member/footerKupon");
	}
	function invoice(){
		$noInvoice = $_GET['no_kupon'];
		$kupon_member = $this->db->get_where("k_kupon_member",array("id_kupon_member" => $noInvoice))->row();

		$this->db->where('id_customer', $kupon_member->id_customer);
		$this->db->or_where('kontak', $kupon_member->id_customer);
		$data['member'] = $this->db->get('ap_customer')->row();
		// $data['member'] = $this->db->get_where("ap_customer",array("id_customer" => $kupon_member->id_customer))->row();
		$data['kupon'] = $this->db->get_where("k_kupon",array("id_kupon" => $kupon_member->id_kupon))->row();
		$data['kasir'] = $this->db->get_where("users",array("id" => $kupon_member->pic))->row();
		$data['store'] = $this->db->get_where("ap_store",array("id_store" => $kupon_member->toko))->row();
		$data['tanggal'] = $kupon_member->tgl_out;
		//$this->load->view("kupon_member/invoice",$data);

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Penukaran Produk";
		
		$this->loadViews("kupon_member/invoice",$this->global,$data,"kupon_member/footerInvoice");
	}
	function data_kupon(){
		//error_reporting(0);ini_set("display_errors",0);
		$id = $_GET['id_customer'];
		$data['id_customer'] = $id;

		$data['poin'] = $this->modelKupon->poinMember($id);
		$data['nama'] = $this->modelKupon->namaMember($id);

		$data['kategoriMember'] = $this->modelKupon->kategoriMember($id);

		$point = $data['poin']+0;
		$this->db->where('sisa>0');
		$this->db->where('id_toko',$this->global['idStore']);
		$this->db->where("jml_point < '$point'");
		$this->db->where("tgl_expired > '".date("Y-m-d H:i:s")."'");
		$this->db->where("tgl_berlaku < '".date("Y-m-d H:i:s")."'");
		$this->db->where('id_group',$data['kategoriMember']);

		$data['kupon'] = $this->db->get("k_kupon");
		$this->load->view("kupon_member/data_kupon",$data);
	}

	function select_produk(){
		$data['produk']='';
		$this->load->view("kupon_member/select_produk",$data);
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
		$hpp				= $this->modelKupon->getHPP($produk,$this->global['idStore']);
		$syarat 			= $_POST['syarat'];
		$tgl 				= date('Y-m-d H:i:s');

        $uploadPath = 'uploads/files/kupon_member/'; 
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
				    'dibuat'=> $tgl);

			$this->db->insert("k_kupon",$data_insert);
			$affect = $this->db->affected_rows();
			echo $affect;
		}
	}

	function form_edit_kupon(){
		$id = $_POST['id'];
		$data['kupon'] = $this->db->get_where("k_kupon",array("id_kupon" => $id));
		$this->load->view("kupon_member/form_edit_kupon",$data);
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
		$sisa				= $_POST['jml']+$_POST['sisa'];
		$produk 			= $_POST['produk'];
		$hpp				= $this->modelKupon->getHPP($produk,$this->global['idStore']);


		if(!empty($_FILES['gambar']['name'])){

			$uploadPath = 'uploads/files/kupon_member/'; 
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
				    'gambar'=> $nm_file
				);

			$this->db->where("id_kupon",$id);
			$this->db->update("k_kupon",$data_update);
			$affect = $this->db->affected_rows();
			echo $affect;
		
	}


	function tukar_kupon(){
		$id = $_POST['id'];
		$id_customer = $_POST['id_customer'];
		$kupon = $this->db->get_where("k_kupon",array("id_kupon" => $id))->row();
		$member = $this->db->get_where("ap_customer",array("id_customer" => $id_customer))->row();

		$data_insert = array("id_kupon" => $id,
							 "id_customer" => $id_customer, 
							 "status" => 'Out', 
							 "tgl_in" => date("Y-m-d H:i:s"),
							 "tgl_out" => date("Y-m-d H:i:s"),
							"pic" => $this->global['idUser'],
							"toko" =>$this->global['idStore']);
		$insert = $this->modelKupon->insert_kupon($data_insert);

		$sisa_poin = $member->point - $kupon->jml_point;
		$data_update = array("point" => $sisa_poin);
		$update = $this->modelKupon->update_member($id_customer,$data_update);

		$sisa_kupon = $kupon->sisa - 1;
		$data_update2 = array("sisa" => $sisa_kupon);
		$update2 = $this->modelKupon->update_kupon($id,$data_update2);
		
		//@unlink('uploads/files/kupon_member/'.$kupon->gambar);
		echo $insert;
	}
}