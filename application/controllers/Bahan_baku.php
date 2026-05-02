<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Bahan_baku extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model(array('model1','modelBahanBaku'));
		$this->load->library("session");

		$this->isLoggedIn($this->global['idUser'],2,2);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Bahan Baku";
		$this->loadViews("bahan_baku/bahan_baku",$this->global,NULL,"bahan_baku/footerBahanBaku");
	}

	function addNewBahanBaku(){
		$data['get_satuan'] = $this->db->get("satuan");
		$data['get_kategori'] = $this->db->get("kategori");
		$this->global['pageTitle'] = "SOLUSI POS - Tambah Bahan Baku";
		//$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("bahan_baku/formBahanBaku",$this->global,$data,"bahan_baku/footerBahanBaku");
	}

	function datatablesBahanBaku(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelBahanBaku->totalBahanBakuAkif();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanBaku->viewBahanBaku($length,$start,$search);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanBaku->viewBahanBaku($length,$start,$search);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			if($dt['status']==1){
				$status = "Aktif";
			} else {
				$status = "Non Aktif";
			}

			$output['data'][]=array($nomor_urut,$dt['sku'],$dt['nama_bahan'],$dt['satuan'],$dt['kategori'],number_format($dt['harga'],'0',',','.'),$status,
				'<a href="'.base_url('bahan_baku/editBahanBaku?id='.$dt['sku']).'"><i class ="fa fa-pencil"></i></a>');
		$nomor_urut++;
		}

		echo json_encode($output);
	}

	function editBahanBaku(){
		$data['get_satuan'] 	= $this->db->get('satuan');
		$data['get_kategori']  	= $this->db->get('kategori');
		$id = $this->input->get("id");
		$data['bahanBaku'] = $this->db->get_where("bahan_baku",array("sku" => $id))->row();;
		$this->global['pageTitle'] = "SOLUSI POS - Edit Bahan Baku";
		//$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("bahan_baku/editBahanBaku",$this->global,$data,"bahan_baku/footerBahanBaku");
	}

	function insertBahanBaku(){
		$namaBahan 		= $_POST['namaBahan'];
		$harga 			= $_POST['harga'];
		$kategori 		= $_POST['kategori'];
		$satuan 		= $_POST['satuan'];
		$sku 			= $_POST['sku'];

		$dataArray = array(
								"sku"				=> $sku,
								"nama_bahan"		=> $namaBahan,
								"id_kategori"		=> $kategori,
								"satuan"			=> $satuan,
								"harga"				=> $harga,
								"status"			=> 1,
								"del"				=> 1,
								"stok"				=> 0
						  );

		$this->modelBahanBaku->insertBahanBaku($dataArray);
	}

	function cekSKU(){
		$sku = $_POST['sku'];

		$cekSKU = $this->modelBahanBaku->cekSKUExist($sku);

		if($cekSKU > 0){
			echo 0;
		} else {
			echo 1;
		}
	}

	function editBahanBakuSQL(){
		$namaBahan 		= $_POST['namaBahan'];
		$harga 			= $_POST['harga'];
		$kategori 		= $_POST['kategori'];
		$satuan 		= $_POST['satuan'];
		$status 		= $_POST['status'];
		$sku 			= $_POST['sku'];

		$dataArray = array(
								"nama_bahan"		=> $namaBahan,
								"id_kategori"		=> $kategori,
								"satuan"			=> $satuan,
								"harga"				=> $harga,
								"status"			=> $status
						  );

		$this->modelBahanBaku->editBahanBaku($sku,$dataArray);
	}

	
	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}
}