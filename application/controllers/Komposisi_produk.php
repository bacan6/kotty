<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Komposisi_produk extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		//cek login
		$username = $this->session->userdata("username");
		$password = $this->session->userdata("password");
		$id_user  = $this->session->userdata("id_user");

		$cek_auth = $this->model1->cek_auth($username,$password);

		if($cek_auth > 0){
			//cek hak navigasi
			$access = 4;
			$cek_status = $this->model1->cek_status_navigasi($id_user,$access);
			
			if($cek_status=='0'){
				redirect("access_denied");
			} else {
				//do nothing
			}

		} else {
			redirect("login");
		}
	}

	function index(){
		$this->load->view("navigation");

		$total_rows = $this->model1->total_produk();
		$this->load->library('pagination');
		$config['base_url'] 			= base_url('komposisi_produk/index/');
		$config['total_rows']			= $total_rows;
		$config["per_page"]				= $per_page = 10;
		$config["uri_segment"]			= 3;
		$config["full_tag_open"] 		= '<ul class="pagination">';
		$config["full_tag_close"] 		= '</ul>';
		$config["first_link"] 			= "&laquo;";
		$config["first_tag_open"] 		= "<li>";
		$config["first_tag_close"] 		= "</li>";
		$config["last_link"] 			= "&raquo;";
		$config["last_tag_open"] 		= "<li>";
		$config["last_tag_close"] 		= "</li>";
		$config['next_link'] 			= '&gt;';
		$config['next_tag_open'] 		= '<li>';
		$config['next_tag_close'] 		= '<li>';
		$config['prev_link'] 			= '&lt;';
		$config['prev_tag_open'] 		= '<li>';
		$config['prev_tag_close'] 		= '<li>';
		$config['cur_tag_open'] 		= '<li class="active"><a href="#">';
		$config['cur_tag_close'] 		= '</a></li>';
		$config['num_tag_open'] 		= '<li>';
		$config['num_tag_close'] 		= '</li>';

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		if(empty($_GET['query'])){
			$data['produk'] = $this->model1->get_produk_page($per_page,$page);
		} else {
			$query = $_GET['query'];
			$data['produk'] = $this->model1->get_produk_page_sort($per_page,$page,$query);
		}

		$this->load->view("body_komposisi_produk",$data);
		$this->load->view("footer");
	}

	function edit_produk(){
		$this->load->view("navigation");
		$data['bahan_baku'] = $this->model1->bahan_baku_komposisi();
		$this->load->view("body_edit_komposisi",$data);
		$this->load->view("footer_komposisi_produk");
	}

	function table_komposisi(){
		$id_produk = $_POST['id_produk'];
		$data['bahan'] = $this->model1->komposisi_bahan($id_produk);

		$data['id_produk'] = $id_produk;
		$this->load->view("table_komposisi",$data);
	}

	function insert_komposisi(){
		$sku 		= $_POST['sku'];
		$id_produk  = $_POST['id_produk'];

		$cek_bahan = $this->model1->cek_bahan($sku,$id_produk);

		$data_upload = array(
								"id_produk"		=> $id_produk,
								"sku"			=> $sku,
								"qty"			=> 0
							);

		if($cek_bahan < 1){
			$this->db->insert("ap_produk_bahan_baku",$data_upload);
		} else {
			//bahan belum sinkron
		}
	}

	function hapus_bahan_baku(){
		$sku 			= $_POST['sku'];
		$id_produk 		= $_POST['id_produk'];

		$this->db->delete("ap_produk_bahan_baku",array("id_produk" => $id_produk, "sku"	=> $sku));
	}

	function form_edit_bahan_baku(){
		$sku 			= $_POST['sku'];
		$id_produk 		= $_POST['id_produk'];

		$data['get_qty'] = $this->model1->get_qty_ingredient($sku,$id_produk);

		$data['sku'] 		= $sku;
		$data['id_produk'] 	= $id_produk;

		$this->load->view("form_edit_bahan_baku",$data);
	}

	function edit_qty_sql(){
		$sku  	 	= $_POST['sku'];
		$id_produk 	= $_POST['id_produk'];
		$qty 		= $_POST['qty'];

		$data_update = array(
								"qty"	=> $qty
						    );

		$this->db->where("id_produk",$id_produk);
		$this->db->where("sku",$sku);
		$this->db->update("ap_produk_bahan_baku",$data_update);
	}

}