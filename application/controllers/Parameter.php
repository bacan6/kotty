<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Parameter extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelParameter"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],1,7);
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function satuan(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,27);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "SOLUSI POS - Satuan";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("parameter/body_satuan",$this->global,NULL,"parameter/footerSatuan");
			}
		}
	}

	function add_satuan_sql(){
		$uom 		= $_POST['uom'];
		$keterangan = $_POST['keterangan'];

		$data_insert = array(
								"satuan"		=> $uom,
								"keterangan"	=> $keterangan
							);

		$this->modelParameter->insertSatuan($data_insert);
	}

	function data_uom(){
		$data['satuan'] = $this->db->get("satuan");
		$this->load->view("parameter/data_uom",$data);
	}

	function hapus_uom(){
		$id = $_POST['id'];

		$this->modelParameter->hapusSatuan($id);
	}

	function kategori(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,28);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "SOLUSI POS - Kategori";
				$this->loadViews("parameter/body_kategori",$this->global,NULL,"parameter/foooterKategori");
			}
		}
	}

	function kategori_produk_jual(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,29);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['kategori'] = $this->db->get("ap_kategori")->result();
				$this->global['pageTitle'] = "SOLUSI POS - Kategori Produk Jual";
				$this->loadViews("parameter/kategori_produk_jual",$this->global,$data,"footer_empty");
			}
		}
	}


	function kategori_level_1(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,29);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "SOLUSI POS - Kategori Level 1";
				$this->loadViews("parameter/kategori_level_1",$this->global,NULL,"footer_empty");
			}
		}	
	}

	function edit_kategori_level_1(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,29);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "SOLUSI POS - Edit Kategori Level 1";
				$this->loadViews("parameter/kategori_level_1",$this->global,NULL,"footer_empty");
			}
		}
	}

	function edit_kategori_level2(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,29);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$id = $this->input->get("id");
				$kategori = $this->db->get_where("ap_kategori_1", array("id" => $id))->result();

				foreach($kategori as $row){
					$data['namaKategori'] = $row->kategori_level_1;
				}

				$this->global['pageTitle'] = "SOLUSI POS - Kategori Level 2";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("parameter/edit_kategori_level_2",$this->global,$data,"footer_empty");
			}
		}
	}

	function kategoriLevel2EditSQL(){
		$id  			= $this->input->post("id_kategori");
		$kategori 		= $this->input->post("kategori");
		$idBaru 		= $this->input->post("idBaru");

		$dataUpdate 	= array(
									"id" => $idBaru,
									"kategori_level_1" => $kategori
							   );

		$query = $this->modelParameter->kategoriLevel2EditSQL($id,$dataUpdate);

		$dataUpdateL3 	= array(
									"id_kategori_1" => $idBaru
							   );

		$query2 = $this->modelParameter->kategoriLevel2EditSQL_updateLevel3($id,$dataUpdateL3);

		if($query > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Diubah";
            $message .= "</div>";
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Gagal Diubah";
            $message .= "</div>";
		}

		$this->session->set_flashdata("message",$message);
		redirect("parameter/kategori_produk_jual");
	}

	function hapusKategoriLevel2SQL(){
		$id 	= $this->input->get("id");

		$affect = $this->modelParameter->hapusKategoriLevel2($id);

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Dihapus";
            $message .= "</div>";
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Gagal Dihapus";
            $message .= "</div>";
		}

		$this->session->set_flashdata("message",$message);
		redirect("parameter/kategori_produk_jual");
	}

	function editKategoriLevel3(){
		$id = $this->input->get("id");
		$kategori = $this->db->get_where("ap_kategori_2", array("id" => $id))->result();

		foreach($kategori as $row){
			$data['namaKategori'] = $row->kategori_3;
		}

		$this->global['pageTitle'] = "SOLUSI POS - Kategori Level 3";
		$this->loadViews("parameter/editKategoriLevel3",$this->global,$data,"footer_empty");
	}

	function kategoriLevel3EditSQL(){
		$idKategori 	= $this->input->post("id_kategori");
		$kategori 		= $this->input->post("kategori");
		$idBaru 		= $this->input->post("idBaru");

		$dataUpdate = array(
								"id" => $idBaru,
								"kategori_3" => $kategori
						   );

		$affect = $this->modelParameter->kategoriLevel3EditSQL($idKategori,$dataUpdate);
	
		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Diubah";
            $message .= "</div>";
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Gagal Diubah";
            $message .= "</div>";
		}

		$this->session->set_flashdata("message",$message);
		redirect("parameter/kategori_produk_jual");
	}

	function hapusKategori3(){
		$id = $this->input->get("id");
		$affect = $this->modelParameter->hapusKategori3($id);
		
		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Dihapus";
            $message .= "</div>";
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Gagal Dihapus";
            $message .= "</div>";
		}

		$this->session->set_flashdata("message",$message);
		redirect("parameter/kategori_produk_jual");
	}

	function kategori_level_1_submit(){
		$kategori = $_POST['kategori'];

		$data_insert = array(
								"kategori"	=> $kategori
							);

		$affect = $this->modelParameter->kategoriLevel1Submit($data_insert);

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Ditambah";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Gagal Ditambahkan";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		}

		redirect("parameter/kategori_produk_jual");
	}

	function kategori_level_2_submit(){
		$kategori 	= $_POST['kategori'];
		$id_level_1 = $_POST['id_level_1'];
		$id 		= $_POST['IDkategori'];//

		$data_insert = array(
								"id"					=> $id,
								"id_kategori"			=> $id_level_1,	
								"kategori_level_1"		=> $kategori
							);

		$affect = $this->modelParameter->kategoriLevel2Submit($data_insert);

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Ditambah";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Gagal Ditambahkan";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		}

		redirect("parameter/kategori_produk_jual");
	}

	function kategori_level_3_submit(){
		$kategori 	= $_POST['kategori'];
		$id_level_1 = $_POST['id_level_1']; 
		$id 		= $_POST['IDkategori'];

		$data_insert = array(
								"id"			=> $id,
								"id_kategori_1"	=> $id_level_1,	
								"kategori_3"	=> $kategori
							);

		$affect = $this->modelParameter->kategoriLevel3Submit($data_insert);

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Ditambah";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Gagal Ditambahkan";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		}

		redirect("parameter/kategori_produk_jual");
	}

	function kategori_level_1_edit(){
		$kategori = $_POST['kategori'];
		$id 	  = $_POST['id_kategori'];

		$data_update = array(
								"kategori"	=> $kategori
							);

		$affect = $this->modelParameter->kategoriLevel1Edit($id,$data_update);

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Diubah";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Gagal Diubah";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		}

		redirect("parameter/kategori_produk_jual");
	}

	function kategori_level_1_hapus(){
		$id 	  = $_GET['id'];

		$affect = $this->modelParameter->kategoriLevel1Hapus($id);

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Dihapus";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Gagal Dihapus";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		}

		redirect("parameter/kategori_produk_jual");
	}

	function kategori_level_2(){
		$data['kategori'] = $this->db->get("ap_kategori")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Kategori Level 2";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("parameter/kategori_level_2",$this->global,$data,"footer_empty");
	}

	function kategori_level_3(){
		$data['kategori'] = $this->db->get("ap_kategori_1")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Kategori Level 3";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("parameter/kategori_level_3",$this->global,$data,"footer_empty");
	}

	function data_kategori(){
		$data['kategori'] = $this->db->get("kategori");
		$this->load->view("parameter/data_kategori",$data);
	}

	function add_kategori_sql(){
		$kategori = $_POST['kategori'];

		$data_insert = array(
								"kategori"	=> $kategori
							);
		$this->modelParameter->addKategoriSQL($data_insert);
	}

	function hapus_kategori(){
		$id = $_POST['id'];

		$this->modelParameter->hapusKategori($id);
	}

	function form_kategori(){
		$id = $_POST['id'];
		$data['get_kategori'] = $this->db->get_where("kategori",array("id_kategori" => $id));
		$this->load->view("parameter/form_kategori",$data);
	}

	function edit_kategori_sql(){
		$id   		= $_POST['id'];
		$kategori 	= $_POST['kategori'];

		$data_update = array(
								"kategori"	=> $kategori
							);

		$this->modelParameter->editKategoriSQL($id,$data_update);
	}

	function keterangan_waste(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,30);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "SOLUSI POS - Kategori Waste";
				$this->loadViews("parameter/body_keterangan_waste",$this->global,NULL,"parameter/footerWaste");
			}
		}
	}

	function data_waste(){
		$data['waste'] = $this->db->get("keterangan_waste");
		$this->load->view("parameter/data_waste",$data);
	}

	function add_kategori_waste(){
		$waste 		= $_POST['waste'];

		$data_insert = array(
								"keterangan"	=> $waste
							);
		$this->modelParameter->addKategoriWaste($data_insert);
	}

	function hapus_waste(){
		$id = $_POST['id'];

		$this->modelParameter->hapusWaste($id);
	}

	function form_edit_waste(){
		$id = $_POST['id'];
		$data['waste'] = $this->db->get_where("keterangan_waste",array("id_keterangan" => $id));
		$this->load->view("parameter/form_edit_waste",$data);
	}

	function update_waste_sql(){
		$id 		= $_POST['id'];
		$keterangan = $_POST['keterangan'];

		$data_update = array(
								"keterangan" => $keterangan
							);

		$this->modelParameter->updateWasteSQL($id,$data_update);
	}


	function list_kabupaten(){
		$id = $_POST['id'];

		$kabupaten = $this->db->get_where("ae_kabupaten", array("id_provinsi" => $id));

		foreach($kabupaten->result() as $dt){
			echo "<option value='".$dt->kabupaten_id."'>".$dt->nama_kabupaten."</option>";
		}
	}

	function list_kecamatan(){
		$id  = $_POST['id'];

		$kecamatan = $this->db->get_where("ae_kecamatan",array("kabupaten_id" => $id));

		foreach($kecamatan->result() as $dt){
			echo "<option value='".$dt->id_kecamatan."'>".$dt->kecamatan."</option>";
		}
	}

	
}
