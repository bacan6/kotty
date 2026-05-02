
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Brand extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,3);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Brand";
		$this->loadViews("brand/body_brand",$this->global,NULL,"brand/footerBrand");
	}

	function data_brand(){
		$data['brand'] = $this->db->get("brand");
		$this->load->view("brand/data_brand",$data);
	}

	function add_brand_sql(){
		$nama_brand 		= $_POST['nama_brand'];

		$uploadPath = 'uploads/files/brand/'; 
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
								"brand"		=> $nama_brand,
								'gambar'=> $uploaded_data['file_name'],
							);

		$this->db->insert("brand",$data_insert);
		$affect = $this->db->affected_rows();
		echo $affect;
		}
	}

	function form_edit_brand(){
		$id = $_POST['id'];
		$data['brand'] = $this->db->get_where("brand",array("id_brand" => $id));
		$this->load->view("brand/form_edit_brand",$data);
	}

	function edit_brand_sql(){
		
		$nama_brand 			= $_POST['nama_brand'];
		$id 			= $_POST['id'];

		if(!empty($_FILES['gambar']['name'])){

			$uploadPath = 'uploads/files/brand/'; 
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
				"brand"		=> $nama_brand,
				'gambar'=> $nm_file,
			);

		$this->db->where("id_brand",$id);
		$this->db->update("brand",$data_update);
		$affect = $this->db->affected_rows();
		echo $affect;
	}

	function hapus_brand(){
		$id = $_POST['id'];
		$this->db->delete("brand",array("id_brand" => $id));
	}
}