<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Bahan_keluar extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model(array('model1','modelProduk','modelBahanKeluar'));
		$this->load->library("session");

		$this->isLoggedIn($this->global['idUser'],2,11);
	}

	function index(){
		$data['supplier'] = $this->db->get("supplier");
		$data['store_tujuan'] = $this->db->get("ap_store")->result();
		$data['produk'] = $this->model1->get_produk_select2();
		
		$this->global['pageTitle'] = "SOLUSI POS - Barang Keluar";
		$this->loadViews("barang_keluar/body_bahan_keluar",$this->global,$data,"barang_keluar/footer_barang_keluar");
	}

	function ajax_produk(){
		$q 	= $_GET['term'];

		
		$customer = $this->model1->produk_search_all($q);

		$data_array = array();

		foreach($customer->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}

	function prosesBarangKeluar(){
		$tanggal_pengeluaran = date('Y-m-d H:i:s');
		$nama_pemohon 		 = $_POST['namaPenerima'];
		$keterangan 		 = $_POST['keterangan'];
		$id_user  			 = sprintf("%03d",$this->global['idUser']);
		$day 				 = date('d');
		$month 				 = date('m');
		$year 				 = date('Y');
		$store_tujuan 		 = $_POST['storeTujuan'];

		$cek_tanggal_pengeluaran = $this->model1->cek_tanggal_pengeluaran($day,$month,$year);

		$create_date 	= date_create($tanggal_pengeluaran);
		$convert_date 	= date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

		$no_inv = 'TR-'.$convert_date.$id_user.sprintf("%04d",$cek_tanggal_pengeluaran+1);
		
		$data_pengeluaran = array(
									"no_bahan_keluar" 		=> $no_inv,
									"id_user"				=> $this->global['idUser'],
									"tanggal_keluar"		=> $tanggal_pengeluaran,
									"store_tujuan"			=> $store_tujuan,
									"nama_penerima"			=> $nama_pemohon,
									"keterangan"			=> $keterangan
								 );

		$this->modelBahanKeluar->insertNoBahanKeluar($data_pengeluaran);

		$dataBarangCart = $this->modelProduk->viewCartMutasi($id_user);

		foreach($dataBarangCart as $row){
			$sku   = $row->idProduk;
			$qty   = $row->qty;

			$data_item = array(
									"no_bahan_keluar"	=> $no_inv,
									"sku"				=> $sku,
									"qty"				=> $qty,
									"tanggal_keluar"	=> date('Y-m-d')
							  ); 

			//insert item keluar
			$this->modelBahanKeluar->insertItemKeluar($data_item);
			
			//pengurangan stok
			$stok_lama = $this->model1->cek_stok_lama($sku);

			$new_stok[] = array(
								"id_produk" => $sku,
								"stok"		=> $stok_lama-$qty
							 );

			$cek_barang = $this->model1->cek_stok_toko($sku,$store_tujuan);

			if($cek_barang > 0){
				//dapatkan stok lama barang di virtual warehouse
				$stokToko = $this->model1->stok_lama_toko($sku,$store_tujuan);
				$newStokToko = $stokToko + $qty;
				$dataStok = array(
												"stok" 	=> $newStokToko
											);

				$this->modelBahanKeluar->stokKeluarGudang($sku,$store_tujuan,$dataStok);
			} else {
				//sisipkan barang baru
				$dataStok = array(
									"id_produk" 	=> $sku,
									"id_store"		=> $store_tujuan,
									"stok"			=> $qty 
								);

				$this->modelBahanKeluar->insertStokBaru($dataStok);
			}
		}

		$this->modelBahanKeluar->updateBatchStokGudang($new_stok);
		$this->modelBahanKeluar->hapusCartMutasi($this->global['idUser']);
		echo $no_inv;
	}

	function daftar_pengeluaran_barang(){
		$this->global['pageTitle'] = "SOLUSI POS - Daftar Pengeluaran Barang";
		//$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("barang_keluar/body_daftar_pengeluaran_barang",$this->global,NULL,"barang_keluar/footerDaftarPengeluaranBarang");
	}

	function datatablesDaftarPengeluaranBarang(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelBahanKeluar->total_pengeluaran();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanKeluar->daftarPengeluaranBarang($length,$start,$search);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanKeluar->daftarPengeluaranBarang($length,$start,$search);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			
			$output['data'][]=array($nomor_urut,"<a href='".base_url('bahan_keluar/invoice_pengeluaran_barang?no_keluaran='.$dt['no_bahan_keluar'])."'>".$dt['no_bahan_keluar']."</a>",$dt['first_name'],$dt['store'],date_format(date_create($dt['tanggal_keluar']),'d M Y'),$dt['nama_penerima'],$dt['keterangan']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_pengeluaran_barang(){
		$data['header'] = $this->db->get("ap_receipt");

		$no_keluaran = $_GET['no_keluaran'];
		$data['info'] = $this->model1->info_pengeluaran($no_keluaran);
		$data['spending_item'] = $this->model1->spending_item($no_keluaran);
		
		$this->global['pageTitle'] = "SOLUSI POS - Invoice Pengeluaran Barang";
		$this->loadViews("barang_keluar/body_invoice_pengeluaran_barang",$this->global,$data,"footer_empty");
	}

	function getDataProdukWarehouse(){
		$this->load->model("model_penjualan");
		$sku 		= $_POST['sku'];
		$dataProduk = $this->model_penjualan->oldStokWarehouse($sku);

		echo $dataProduk;
	}

	function insertCart(){
		$sku 		= $_POST['sku'];
		$idUser 	= $this->global['idUser'];

		//cek if data exist or not

		$cekCart = $this->modelProduk->cekCartMutasi($sku,$idUser);

		if($cekCart < 1){
			$dataArray = array(
									"idProduk"		=> $sku,
									"qty"			=> 1,
									"idUser"		=> $idUser
							  );

			$this->modelBahanKeluar->insertCartMutasi($dataArray);
			echo 0;
		} else {
			$id = $this->modelBahanKeluar->getIdCart($sku,$idUser);
			echo $id;
		}
	}

	function viewCart(){
		$idUser = $this->global['idUser'];
		$data['viewProduk'] = $this->modelProduk->viewCartMutasi($idUser);
		$this->load->view("barang_keluar/viewCartBarangKeluar",$data);	
	}

	function deleteCart(){
		$idProduk = $_POST['id'];
		$idUser = $this->global['idUser'];

		$rules = array(
							"idUser" 	=> $idUser,
							"idProduk"  => $idProduk
					  );

		$this->modelBahanKeluar->deleteCart($rules);
	}

	function updateCart(){
		$idProduk 	= $_POST['id'];
		$idUser  	= $this->global['idUser'];
		$qty 		= $_POST['qty'];

		$dataUpdate = array(
								"qty"		=> $qty
						   );

		$this->modelBahanKeluar->updateCart($idProduk,$idUser,$dataUpdate);
	}


}

?>
