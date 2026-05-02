<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Store extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,70);
	}

	function index(){
		$data['toko'] = $this->db->get("ap_store");
		$this->global['pageTitle'] = "SOLUSI POS - Store";
		$this->loadViews("parameter/body_store",$this->global,$data,"footer_empty");
	}

	function edit_toko(){
		$data['toko'] = $this->db->get_where("ap_store",array("id_store" => $_GET['id']));
		$this->global['pageTitle'] = "SOLUSI POS - Edit Store";
		$this->loadViews("parameter/edit_toko",$this->global,$data,"footer_empty");
	}

	function tambah_toko(){
		$this->global['pageTitle'] = "SOLUSI POS - Edit Store";
		//$this->loadViews("parameter/tambah_toko",$this->global,NULL,"footer_empty");
	}

	function add_toko_sql(){
		$nama_toko 	= $_POST['nama_toko'];
		$alamat 	= $_POST['alamat'];
		$footer 	= $_POST['footer'];
		$kontak 	= $_POST['kontak'];

		$data_toko = array(
								"store"		=> $nama_toko,
								"alamat"	=> $alamat,
								"footer"	=> $footer,
								"kontak"	=> $kontak
						  );

		//$this->db->insert("ap_store",$data_toko);
		$affect = $this->db->affected_rows();

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Ditambah";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
			redirect("store");
		}
	}

	function edit_toko_sql(){
		$nama_toko 	= $_POST['nama_toko'];
		$alamat 	= $_POST['alamat'];
		$footer 	= $_POST['footer'];
		$id 	 	= $_POST['id'];
		$kontak 	= $_POST['kontak'];

		$uploadPath = 'uploads/files/store/'; 
        $config['upload_path'] 		= $uploadPath; 
        $config['allowed_types'] 	= 'jpg|jpeg|png|gif'; 
        $config['overwrite']   		= true;
        $config['remove_spaces']    = true;
        $config['encrypt_name']     = true;
        $config['max_size']         = 3000;

        $this->load->library('upload', $config);
        $this->upload->initialize($config); 

      	if (!$this->upload->do_upload('gambar')){
              $data['error'] = $this->upload->display_errors();
			  $data_toko = array(
								"store"		=> $nama_toko,
								"alamat"	=> $alamat,
								"footer"	=> $footer,
								"kontak"	=> $kontak
						  );
        }else{
			$uploaded_data = $this->upload->data();
			$data_toko = array(
								"store"		=> $nama_toko,
								"alamat"	=> $alamat,
								"footer"	=> $footer,
								"kontak"	=> $kontak,
								"gambar"	=> $uploaded_data['file_name']
						  );

		}

		

		$this->db->where("id_store",$id);
		$this->db->update("ap_store",$data_toko);
		$affect = $this->db->affected_rows();

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
                $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
                $message .= "Data Berhasil Diubah";
                $message .= "</div>";

				$this->session->set_flashdata("message",$message);
			redirect("store");
		}
	}

	function hapus_toko(){
		$id = $_GET['id'];

		$this->db->delete("ap_store",array("id_store" => $id));

		$affect = $this->db->affected_rows();

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
                $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
                $message .= "Data Berhasil Dihapus";
                $message .= "</div>";

				$this->session->set_flashdata("message",$message);
		}
		redirect("store");
	}

}