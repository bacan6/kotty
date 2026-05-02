<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
class Promo_brand_c2p extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library(array('session','encryption'));
		$this->load->model(array("model1","modelPromoBrandC2P"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],8,54);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Promo Brand Count to Percent";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data['store'] = $this->db->get("ap_store");
		$data['brand'] = $this->db->get("brand");
		$this->loadViews("promo_brand_c2p/body",$this->global,$data,"promo_brand_c2p/footer");
	}

	function insertPO(){
		//error_reporting(E_ALL);ini_set('display_errors', true);
		$id_user  		= sprintf("%03d",$this->global['idUser']);
		$tanggal_po 	= date('Y-m-d');
		$tanggalMulai 	= $_POST['tanggalMulai'];
		$tanggalSelesai = $_POST['tanggalSelesai'];
		$keterangan 	= $_POST['keterangan'];
		$brand  		= $_POST['brand'];
		$toko  			= $_POST['toko'];
		$jamMulai  		= $_POST['jamMulai'];
		$jamSelesai  	= $_POST['jamSelesai'];
		$minBelanja		= $_POST['minBelanja'];
		$discount		= $_POST['discount'];
		$setJam			= $_POST['setJam'];
		$setHari		= $_POST['setHari'];

		$arrHari		= $_POST['HariID'];
		$HariID			= ".";

		if (!empty($arrHari)){
			foreach ($arrHari as $H) {
				$HariID.= $H.'.';
			}
		}
		

		$cek_tanggal 	= $this->model1->cek_tanggal_terima_promo($tanggal_po);

		$create_date 	= date_create($tanggal_po);
		$convert_date   = date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

		$no_inv = 'PB'.$convert_date.$id_user.date('hs').sprintf("%04d",$cek_tanggal+1);

		$data_masuk = array(
								"no_promo" 			=> $no_inv,
								"tanggal_buat" 		=> date('Y-m-d H:i:s'),
								"date_start"		=> $tanggalMulai,
								"date_end"			=> $tanggalSelesai,
								"id_brand"			=> $brand,
								"keterangan"		=> $keterangan,
								"JamMulai"			=> $jamMulai,
								"JamSelesai"		=> $jamSelesai,
								"setJam"			=> $setJam,
								"setHari"			=> $setHari,
								"HariID"			=> $HariID,
								"id_pic"			=> $this->global['idUser'],
                                "id_toko"			=> $toko,
								"minBelanja"		=> $minBelanja,
								"discount"			=> $discount
							);
		
		$this->modelPromoBrandC2P->insertPONumber($data_masuk);

		echo $no_inv;

		//redirect("purchase_order/form_po?no_po=".$no_inv);
	}

	function daftar_po(){
		$this->global['pageTitle'] = "SOLUSI POS - Informasi Promo Brand Count to Percent";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("promo_brand_c2p/body_daftar",$this->global,NULL,"promo_brand_c2p/footerDaftarPO");
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function form_promo(){
		$no_po = $_GET['no_promo'];
		$data['header'] = $this->db->get("ap_receipt");
		$info_po = $this->modelPromoBrandC2P->infoPurchase($no_po);

		$data['tanggal_buat'] 		= $info_po->tanggal_buat;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['brand'] 				= $info_po->brand;
		$data['tanggalMulai']		= $info_po->date_start;
		$data['tanggalSelesai']		= $info_po->date_end;
		$data['idBrand'] 			= $info_po->id_brand;
		$data['discount'] 			= $info_po->discount;
		$data['minBelanja'] 		= $info_po->minBelanja;
		$data['store'] 				= $info_po->store;
		$data['setJam'] 			= $info_po->setJam==1? $info_po->JamMulai.' s/d '.$info_po->JamSelesai:'tidak diatur';

		$this->global['pageTitle'] = "SOLUSI POS - Form Promo Brand";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("promo_brand_c2p/body_form_po",$this->global,$data,"promo_brand_c2p/footer_barang_masuk");
	}

	function hapusAllPromo(){
		$id = $_POST['id'];
		$this->db->where("no_promo", $id);
        $this->db->delete("ap_promo_brand_rules");

	}
	function datatablesPO(){
        $idUser     = $this->global['idUser'];
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelPromoBrandC2P->totalPromoSupplier();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelPromoBrandC2P->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelPromoBrandC2P->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore']);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			

			$output['data'][]=array($nomor_urut,"<a href='".base_url('promo_brand_c2p/form_promo?no_promo='.$dt['no_promo'])."'>".$dt['no_promo']."</a>",$dt['tanggal_buat'],$dt['date_start'],$dt['date_end'],$dt['brand'],$dt['first_name'],"<a onclick=\"if (confirm('Yakin hapus semua data yang berhubungan dengan nomor promo ini?')){hapusPromo('".$dt['no_promo']."');}\" ><i class=\"fa fa-trash\"></i></a>");
			$nomor_urut++;
		}

		echo json_encode($output);
	}
}