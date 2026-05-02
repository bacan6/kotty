<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Q_Stock_opname extends CI_Controller{
	function __construct(){
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelStockOpname","modelSOPeritem","modelLabelHarga"));
		$this->load->database();
	}

	function cari_produk(){
		$id = $this->input->post('id');
		$toko = $this->input->post('toko');

		$this->db->select('ap_produk.nama_produk,ap_produk.id_produk,stok_store.harga,stok_store.stok');
		$this->db->from('ap_produk');
		$this->db->join('stok_store','stok_store.id_produk=ap_produk.id_produk');
		//$this->db->join('ap_produk_price','stok_store.id_produk=ap_produk.id_produk');
		$this->db->where('ap_produk.id_produk', $id);
		$this->db->where('stok_store.id_store', $toko);
		//$this->db->where('ap_produk_price.id_toko', $toko);
		$this->db->limit(1);
		$query = $this->db->get();
		$produk  = $query->result();

		echo json_encode($produk);
	}

	function label_harga(){
		$id = $this->input->post('id');
		$toko = $this->input->post('toko');
		$idUser = $this->input->post('iduser');
		$harga ='';

		$this->db->select('ap_produk.nama_produk,ap_produk.id_produk,stok_store.harga,stok_store.stok');
		$this->db->from('ap_produk');
		$this->db->join('stok_store','stok_store.id_produk=ap_produk.id_produk');
		//$this->db->join('ap_produk_price','stok_store.id_produk=ap_produk.id_produk');
		$this->db->where('ap_produk.id_produk', $id);
		$this->db->where('stok_store.id_store', $toko);
		//$this->db->where('ap_produk_price.id_toko', $toko);
		$this->db->limit(1);
		$query = $this->db->get();
		$produk  = $query->result();

		foreach($produk as $row){
			$harga = $row->harga;
		}

		$dataCart = array(
								"idProduk"		=> $id,
								"qty"			=> '1',
								"idUser" 		=> $idUser,
								"harga"			=> $harga
						     );

		$this->modelLabelHarga->insertCartPO($dataCart);

		echo json_encode($produk);
	}

	

	function simpan_so(){

		$idproduk = $this->input->post("idproduk");
		$stokbefore = $this->input->post("stokbefore");
		$stok = $this->input->post("stok");
		$iduser = $this->input->post("iduser");
		$harga = $this->input->post("harga");
		$token = $this->input->post("token");
		$id_toko = $this->input->post("toko");
		

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('token',$token);
		$query=$this->db->get();

		if(!empty($query->num_rows())){

		$where=array(
			'idProduk' => $idproduk,
			'store' => $id_toko,
			'idUser' => $iduser
		);

		$this->db->select('*');
		$this->db->from('cc_cartSO_peritem');
		$this->db->where($where);
		$query = $this->db->get();

		$stok_before=$this->modelSOPeritem->stokProduk($idproduk,$id_toko);

		if(empty($query->num_rows())){
			$stokafter = $stok;
			$datai = array(
		        'id' => '',
		        'idProduk' => $idproduk,
		        'stok_before' => $stok_before->stok,
		        'min' => '0',
		        'max' => '0',
		        'stok_after' => $stokafter,
		        'idUser' => $iduser,
		        'harga' => $stok_before->hpp,
				'tanggal' => date('Y-m-d H:i:s'),
				'store'	=> $id_toko
			);

			$this->db->insert('cc_cartSO_peritem', $datai);
			$save = $this->db->affected_rows();

		}else{
			$cart=$query->row();
			$stokafter = $cart->stok_after+$stok;
			$datae = array(
		        'stok_before' => $stok_before->stok,
		        'min' => '0',
		        'max' => '0',
		        'stok_after' => $stokafter,
		        'idUser' => $iduser,
		        'harga' => $stok_before->hpp
			);

			$this->db->set($datae);
			$this->db->where($where);
			$this->db->update('cc_cartSO_peritem');
			$save = $this->db->affected_rows();
		}
		
		if($save){
			$data['pesan'] = "Menyimpan data berhasil.";
			$data['status'] = "sukses";
		}else{
			$data['pesan'] = "Gagal menyimpan data, cobalah beberapa saat lagi.";
			$data['status'] = "error";
		}
	}else{

		$data['pesan'] = "Terjadi kesalahan, silahkan login kembali.";
		$data['status'] = "error";
	}
		echo json_encode($data);
	}
}