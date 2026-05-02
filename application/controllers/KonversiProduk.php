<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class KonversiProduk extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model(array('model1','modelKonversiProduk'));
		$this->load->library("session");

		$this->isLoggedIn($this->global['idUser'],2,39);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Konversi Produk ke Bahan Baku";
		$this->loadViews("konversi_produk/bodyKonversiProduk",$this->global,NULL,"konversi_produk/footerKonversiProduk");
	}

	function ajax_produk(){
		$q 			= $_GET['term'];

		$get_bahan_baku_select2 = $this->modelKonversiProduk->produkAjax($q);

		$data_array = array();

		foreach($get_bahan_baku_select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}

	function insertCart(){
		$idProduk 		= $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		//cek jika stok mencukupi
		$cekStokGudang = $this->modelKonversiProduk->cekStokGudang($idProduk);

		if($cekStokGudang->stok < 1){
			echo 0;
		} else {
			$cekCartExist = $this->modelKonversiProduk->cekCartExist($idProduk,$idUser);

			if($cekCartExist < 1){
				$dataInsert = array(
										"idProduk"		=> $idProduk,
										"qty"			=> 1,
										"hargaBeli"		=> $cekStokGudang->hpp,
										"idUser"		=> $idUser
								   );

				$this->modelKonversiProduk->insertCart($dataInsert);
				echo 1;
			} else {
				echo 2;
			}

		}
	}

	function viewCart(){
		$idUser = $this->global['idUser'];
		$data['viewCart'] = $this->modelKonversiProduk->viewCart($idUser);
		$this->load->view("konversi_produk/viewCart",$data);
	}

	function updateQtyCart(){
		$idProduk = $_POST['idProduk'];
		$qty = $_POST['qty'];
		$idUser = $this->global['idUser'];

		//update, periksa terlebih dahulu apakah stok memehuni
		$inventory = $this->modelKonversiProduk->cekStokGudang($idProduk);
		$stok = $inventory->stok;

		if($qty > $stok){
			echo 0;
			//melebihi stok gudang saat ini
		} else {
			//update cart
			$dataUpdate = array("qty" => $qty);

			$this->modelKonversiProduk->updateCart($dataUpdate,$idProduk,$qty);
			echo 1;
		}

	}

	function hapusCart(){
		$idProduk = $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$this->modelKonversiProduk->hapuCart($idProduk,$idUser);
	}

	function prosesKonversiSQL(){
		$idUser = $this->global['idUser'];
		$tanggal = date('Y-m-d');

		//insert cart number
		$cekConvertNumber = $this->modelKonversiProduk->cekConvertNumber($idUser,$tanggal);
		$noConvert = "KONV-".date('y').date('m').date('d').$idUser.sprintf('%03d',$cekConvertNumber+1);

		$dataConvertNumber = array(
									"no_convert" => $noConvert,
									"idUser" => $idUser,
									"tanggal" => $tanggal
								  );

		$this->modelKonversiProduk->insertKonversiNumber($dataConvertNumber);

		$viewCart = $this->modelKonversiProduk->viewCart($idUser);

		foreach($viewCart as $row){
			$idProduk = $row->id_produk;
			$hargaBeli = $row->hargaBeli;
			$qty = $row->qty;

			$dataConvertItem[] = array(
										"no_convert" => $noConvert,
										"idProduk" => $idProduk,
										"qty" => $qty,
										"hargaBeli" => $hargaBeli
									  );

			//kurangi stok gudang
			//pengurangan stok
			$stok_lama = $this->model1->cek_stok_lama($idProduk);

			$new_stok = array(
								"stok"		=> $stok_lama-$qty
							 );

			$this->db->where("id_produk",$idProduk);
			$this->db->update("ap_produk",$new_stok);

			//tambahkan ke bahan baku
			//cek apa sudah ada di bahah baku

			$cekBahanBakuSKU = $this->modelKonversiProduk->cekBahanBakuSKU($idProduk);

			if($cekBahanBakuSKU < 1){
				$infoProduk = $this->modelKonversiProduk->infoProduk($idProduk);

				$dataInsert = array(
										"sku"=> $idProduk,
										"nama_bahan" =>$infoProduk->nama_produk,
										"id_kategori" => 1,
										"satuan" => $infoProduk->satuan,
										"harga" => $infoProduk->hpp,
										"status" => 1,
										"del" =>1,
										"stok" => $qty
							       );

				$this->db->insert("bahan_baku",$dataInsert);
			} else {
				//data update bahan baku stok
				$stokLamaBahanBaku = $this->modelKonversiProduk->stokBahanBaku($idProduk);

				$dataUpdate = array(
										"stok" => $stokLamaBahanBaku+$qty
								   );

				$this->db->where("sku",$idProduk);
				$this->db->update("bahan_baku",$dataUpdate);
			}

		}

		$this->db->insert_batch("konversi_item",$dataConvertItem);
		$this->db->delete("cc_konversiproduk",array("idUser" => $idUser));
		echo $noConvert;
	}

	function formKonversi(){
		$noKonversi = $this->input->get("noKonversi");
		$data['infoKonversi'] = $this->modelKonversiProduk->infoKonversi($noKonversi);
		$data['itemKonversi'] = $this->modelKonversiProduk->itemKonversi($noKonversi);
		$this->global['pageTitle'] = "SOLUSI POS - Form Konversi Produk";
		$this->loadViews("konversi_produk/formKonversi",$this->global,$data,"footer_empty");
	}


}
