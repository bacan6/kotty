<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Promotion extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model(array("model1","model_promotion"));
		$this->load->library("session");

		$this->isLoggedIn($this->global['idUser'],1,8);
	}

	function diskon(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,32);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['jumlah_produk'] = $this->model_promotion->jumlah_produk();
				$data['jumlah_produk_diskon'] = $this->model_promotion->jumlah_produk_diskon();
				$this->global['pageTitle'] = "SOLUSI POS - Diskon Promosi";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("promotion/body_promotion",$this->global,$data,"promotion/footerPromotion");
			}
		}
	}

	function datatablesProduk(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->model_promotion->totalProduk();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->model_promotion->daftar_produk_diskon_search($length,$start,$search);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->model_promotion->daftar_produk_diskon($length,$start);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,$dt['id_produk'],$dt['nama_produk'],$dt['kategori'],number_format($dt['harga'],'0',',','.'),number_format($dt['harga_jual'],'0',',','.'),"<label class='label label-primary'><a href='".base_url('promotion/set_to_diskon?sku='.$dt['id_produk'])."' style='color:white;'>Set to Promotion</a></label>");
		$nomor_urut++;
		}

		echo json_encode($output);
	}

	function set_to_diskon(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,32);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$sku = $_GET['sku'];

				$data_update = array(
										"diskon" => 1
									);

				$affect = $this->model_promotion->setToDiskon($sku,$data_update);

				if($affect > 0){
					$message = "<div class='alert alert-success' role='alert'>";
					$message.= "Berhasil Mengeset Produk Diskon";
					$message.= "</div>";
				} else {
					$message = "<div class='alert alert-danger' role='alert'>";
					$message.= "Gagal Set  Produk Diskon";
					$message.= "</div>";
				}

				$this->session->set_flashdata("message",$message);

				redirect("promotion/diskon");
			}
		}
	}

	function discount_produk(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,32);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['jumlah_produk'] = $this->model_promotion->jumlah_produk();
				$data['jumlah_produk_diskon'] = $this->model_promotion->jumlah_produk_diskon();
				$this->global['pageTitle'] = "SOLUSI POS - Daftar Produk Diskon";
				$this->loadViews("promotion/body_discount_produk",$this->global,$data,"promotion/footerPromotion");
			}
		}
	}

	function datatablesProdukDiscount(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->model_promotion->totalProdukDiskon();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->model_promotion->daftar_produk_diskon_enableSearch($length,$start,$search,$this->global['idStore']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->model_promotion->daftar_produk_diskon_enable($length,$start,$this->global['idStore']);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,$dt['id_produk'],$dt['nama_produk'],$dt['kategori'],number_format($dt['harga'],'0',',','.'),number_format($dt['harga_jual'],'0',',','.'),"<label class='label label-success'><a href='".base_url('promotion/set_rules?sku='.$dt['id_produk'])."' style='color:white;'>Set Rules</a></label> <label class='label label-danger'><a href='".base_url('promotion/hapus_diskon?sku='.$dt['id_produk'])."' style='color:white;'>Hapus</a></label>");
		$nomor_urut++;
		}

		echo json_encode($output);
	}

	function set_rules(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,32);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['jumlah_produk'] = $this->model_promotion->jumlah_produk();
				$data['jumlah_produk_diskon'] = $this->model_promotion->jumlah_produk_diskon();
				$sku = $_GET['sku'];		
				$data['data_diskon'] = $this->model_promotion->rules_discount_produk($sku,$this->global['idStore']);
				$data['infoProduk'] = $this->model_promotion->infoProduk($sku,$this->global['idStore']);

				$this->global['pageTitle'] = "SOLUSI POS - Atur Rules Diskon";
				$this->loadViews("promotion/body_set_rules",$this->global,$data,"promotion/footer_set_rules");
			}
		}
	}

	function data_form(){
		$no = $_GET['no'];

		$data['no'] = $no;
		$this->load->view("promotion/data_form",$data);
	}

	function submit_promotion(){
		$sku = $_POST['sku'];
		
		$count =  count($_POST['qty']);

		$this->model_promotion->deleteRulesDiscount($sku,$this->global['idStore']);

		for($i=0;$i<$count;$i++){
			$qty 		= $_POST['qty'][$i];
			$discount 	= $_POST['discount'][$i];
			$date_start = $_POST['date_start'][$i];
			$date_end 	= $_POST['date_end'][$i];

			$data_produk[] = array(
							 	  		"id_produk"		=> $sku,
							 	  		"qty"			=> $qty,
							 	  		"discount"		=> $discount,
							 	  		"date_start" 	=> $date_start,
							 	  		"date_end"		=> $date_end,
                                        "id_toko"       => $this->global['idStore']
							 	  );
		}

		$affect = $this->model_promotion->insertBatchPromotionDiskon($data_produk);

		if($affect > 0){
			$message = "<div class='alert alert-success' role='alert'>";
			$message.= "Berhasil Mengeset Produk Diskon";
			$message.= "</div>";
		} else {
			$message = "<div class='alert alert-danger' role='alert'>";
			$message.= "Gagal Set  Produk Diskon";
			$message.= "</div>";
		}

		$this->session->set_flashdata("message",$message);

		redirect("promotion/set_rules?sku=".$sku);
	}

	function hapus_promo(){
		$id = $_GET['id'];
		$sku = $_GET['sku'];

		$affect = $this->model_promotion->hapusPromo($id);
		
		if($affect > 0){
			$message = "<div class='alert alert-success' role='alert'>";
			$message.= "Diskon Berhasil Dihapus";
			$message.= "</div>";
		} else {
			$message = "<div class='alert alert-danger' role='alert'>";
			$message.= "GDiskon Gagal Dihapus";
			$message.= "</div>";
		}

		$this->session->set_flashdata("message",$message);

		redirect("promotion/set_rules?sku=".$sku);
	}

	function hapus_diskon(){
		$sku = $_GET['sku'];

		$data_update = array(
								"diskon" => 0
							);

		$this->model_promotion->updateStatusDiskon($sku,$data_update);
		redirect("promotion/discount_produk");
	}

	function poin(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,31);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['get_poin'] = $this->db->get("poin");
				$this->global['pageTitle'] = "SOLUSI POS - Setting Poin";
				$this->loadViews("promotion/body_parameter_poin",$this->global,$data,"footer_empty");
			}
		}
	}

	function submit_setting_poin(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,31);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$poin_pembelian 	= $_POST['poin_pembelian'];
				$nilai_pembelian 	= $_POST['nilai_pembelian'];
				$poin_pengeluaran 	= $_POST['poin_pengeluaran'];
				$nilai_pengeluaran  = $_POST['nilai_pengeluaran'];

				$data_update 		= array(
												"poin_pembelian"	=> $poin_pembelian,
												"nilai_pembelian"	=> $nilai_pembelian,
												"poin_pengeluaran"	=> $poin_pengeluaran,
												"nilai_pengeluaran"	=> $nilai_pengeluaran
										   );

				$affect = $this->model_promotion->updatePointSetting($data_update);

				if($affect >0 ){
					$message = "<div class='alert alert-success'>";
					$message.= "Data Berhasil Diubah";
					$message.= "</div>";
					$this->session->set_flashdata("message",$message);
				}
				
				redirect("promotion/poin");
			}
		}
	}

}
