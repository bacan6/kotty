<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Waste extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelWaste"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,12);
	}

	function index(){
		$data['keterangan_waste'] = $this->db->get("keterangan_waste");
		$data['brand'] = $this->db->get("brand");
		$data['supplier'] = $this->db->get("supplier");
		$this->global['pageTitle'] = "SOLUSI POS - Waste";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("waste/body_waste",$this->global,$data,"waste/footer_waste");
	}

	function ajax_produk(){
		$q 	= $_GET['term'];

		
		$customer = $this->modelWaste->produk_search_all($q);

		$data_array = array();

		foreach($customer->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}

	function insertWaste(){
		$id_user 			= sprintf("%03d",$this->global['idUser']);
		$tanggal_waste 		= date('Y-m-d');
		$id_keterangan	 	= $_POST['idWaste'];
		$keterangan 		= $_POST['keterangan'];
		$id_brand 			= $_POST['id_brand'];
		$id_supplier 		= $_POST['id_supplier'];
		$status 			= $_POST['status'];
		$id_toko = $this->global['idStore'];

		$cek_tanggal 		= $this->modelWaste->cek_tanggal_waste($tanggal_waste);

		$create_date 	= date_create($tanggal_waste);
		$convert_date   = date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

		$no_inv = "WS-".$convert_date.$id_user.rand(10,99).sprintf("%03d",$cek_tanggal+1);


		$data_waste = array(
								"no_waste" 			=> $no_inv,
								"tanggal_waste"  	=> $tanggal_waste,
								"id_pic"			=> $this->global['idUser'],
								"id_keterangan" 	=> $id_keterangan,
								"keterangan" 		=> $keterangan,
								"id_brand" 			=> $id_brand,
								"id_supplier" 		=> $id_supplier,
								"status" 		=> $status,
								"id_toko" => $id_toko
							);

		$this->modelWaste->insertWaste($data_waste);

		// $data_waste_from_exp = array(
		// 						"no_po" 			=> $no_inv,
		// 						"tanggal_po"  	=> $tanggal_waste,
		// 						"id_pic"			=> $this->global['idUser'],
		// 						"keterangan" 		=> $keterangan,
		// 						"id_toko" => $id_toko
		// 					);

		// $this->modelWaste->insertWastefromExp($data_waste_from_exp);
		$itemWaste = $this->modelWaste->viewCartWaste($this->global['idUser'],$this->global['idStore']);

		foreach($itemWaste as $row){
			$sku 			= $row->id_produk;
			$jumlah_waste 	= $row->qty;
			$hpp 			= $row->hpp;

			$data_item[] = array(
								"no_waste"	=> $no_inv,
								"sku"		=> $sku,
								"qty"		=> $jumlah_waste,
								"harga"		=> $hpp,
								"tanggal"	=> $tanggal_waste,
								"id_keterangan" => $id_keterangan
							  );
			// $data_exp_item[] = array(
			// 					"no_po"	=> $no_inv,
			// 					"sku"		=> $sku,
			// 					"qty"		=> "-".$jumlah_waste,
			// 					"harga"		=> $hpp,
			// 					"tanggal"	=> $tanggal_waste
			// 				  );
			$data_kartu[] = array(
									"id_store"		=> $this->global['idStore'],
									"id_produk"		=> $sku,
									"qty"			=> (-1)*$jumlah_waste,
									"hpp"			=> $hpp,
									"tanggal"		=> date('Y-m-d H:i:s'),
									"tipe"			=> 'Waste',
									"no_transaksi"	=> $no_inv,
									"id_pic"		=> $this->global['idUser']
								);

			// $stok_lama = $this->modelWaste->cek_stok_lama($sku,$id_toko);

			// $data_update[] = array(
			// 						"id_produk" => $sku,
			// 						"stok"	=> $stok_lama-$jumlah_waste
			// 					);			
		}
		

		//$this->modelWaste->updateStokBatch($data_update,$id_toko);
		$this->modelWaste->insertWasteItemBatch($data_item);
		//$this->modelWaste->insertExpiredItemBatch($data_exp_item);
		if($data_kartu){
			$this->model1->insertKartuStok($data_kartu);
		}
		$this->modelWaste->hapusCartWaste($this->global['idUser']);
		echo $no_inv;

	}

	function invoice_waste(){
		$data['header'] = $this->db->get("ap_receipt");

		$no_waste = $_GET['no_waste'];

		$data['info_waste'] = $this->model1->info_waste($no_waste);
		$data['item_waste'] = $this->model1->item_waste($no_waste);
		$data['no_waste'] = $no_waste;

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Waste";
		$this->loadViews("waste/body_invoice_waste",$this->global,$data,"footer_empty");
	}

	function insertCartWaste(){
		$sku 		= $_POST['sku'];
		$idUser 	= $this->global['idUser'];

		//cek if data exist or not

		$cekCart = $this->modelWaste->cekCartWaste($sku,$idUser);

		if($cekCart < 1){
			$dataArray = array(
									"idProduk"		=> $sku,
									"qty"			=> 1,
									"idUser"		=> $idUser
							  );

			$this->modelWaste->insertCartWaste($dataArray);
			echo 0;
		} else {
			$id = $this->modelWaste->getIdCart($sku,$idUser);
			echo $id;
		}
	}

	function getDataProdukWarehouse(){
		$this->load->model("model_penjualan");
		$sku 		= $_POST['sku'];
		$dataProduk = $this->modelWaste->cek_stok_lama($sku,$this->global['idStore']);

		echo $dataProduk;
	}

	function viewCartWaste(){
		$idUser = $this->global['idUser'];
		$data['viewCart'] = $this->modelWaste->viewCartWaste($idUser,$this->global['idStore']);
		$this->load->view("waste/viewCartWaste",$data);	
	}

	function updateQtyCart(){
		$qty = $_POST['qty'];
		$idProduk = $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$cekStokGudang = $this->modelWaste->cek_stok_lama($idProduk,$this->global['idStore']);

		if($qty > $cekStokGudang){
			//melebihi stok
			echo 0;
		} else {
			$dataUpdate = array(
									"qty"	=> $qty
							   );

			$this->modelWaste->updateQtyCartWaste($idProduk,$idUser,$dataUpdate);
			echo 1;
		}
	}

	function daftar_waste(){
		$this->global['pageTitle'] = "SOLUSI POS - Daftar Waste";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data['brand'] = $this->db->get("brand");
		$data['supplier'] = $this->db->get("supplier");
		$this->loadViews("waste/body_daftar_waste",$this->global,$data,"waste/footerDaftarWaste");
	}

	function datatablesWaste(){
        $idUser     = $this->global['idUser'];
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$_status = $_POST['status'];
		$id_brand = $_POST['id_brand'];
		$id_supplier = $_POST['id_supplier'];

		$total 			 			= $this->modelWaste->totalWasteProduk();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelWaste->viewWaste($length,$start,$search,$_status,$id_brand,$id_supplier);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelWaste->viewWaste($length,$start,$search,$_status,$id_brand,$id_supplier);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			$output['data'][]=array($nomor_urut,"<a href='".base_url('waste/invoice_waste?no_waste='.$dt['no_waste'])."'>".$dt['no_waste']."</a>",$dt['tanggal_waste'],$dt['keterangan'],$dt['first_name'],$dt['store'],$status,$dt['brand'],$dt['supplier'],$dt['Lunas'],);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function qtyOnCart(){
		$id = $_POST['id'];

		$qty = $this->db->get_where("cc_cartwaste",array("id" => $id))->row();
		echo $qty->qty;
	}

	function hapusCart(){
		$id = $_POST['id'];

		$this->modelWaste->hapusCartId($id);
	}
	function change_status(){
		$id = $_GET['no_waste'];
		$status = $_GET['status'];

		if(!empty($id) && !empty($status)){
			$data = array(
					"status"		=> $status
			);

			$this->db->where("no_waste",$id);
			$this->db->update("waste",$data);
		}
		redirect(base_url('waste/invoice_waste?no_waste='.$id));
	}
}