<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Promo_bundling extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library(array('session','encryption'));
		$this->load->model(array("model1","modelPromoBundling"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],8,54);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Promo Bundling";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data = array();
		if($this->global['isSuperadmin']==1){
			$data['store'] = $this->db->get("ap_store")->result();
		}else $data['store'] = $this->db->get_where("ap_store",array('id_store' => $this->global['idStore']))->result();
		//$data='';
		$this->loadViews("promo_bundling/body",$this->global,$data,"promo_bundling/footer");
	}

	function ajax_produk(){
		$q 			= $_GET['term'];

		$get_bahan_baku_select2 = $this->modelPromoBundling->produkAjax($q);

		$data_array = array();

		foreach($get_bahan_baku_select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}

	function ajax_produk_supplier(){
		$q 			= $_POST['brand'];
		$idToko = $this->model1->getIdStore($this->global['idUser']);
		$idUser = $this->global['idUser'];
		$del = $this->modelPromoBundling->deleteCartPO($idUser);
		$_SESSION['id_brand']=$q;

		$getStok = $this->modelPromoBundling->produkAjaxSupplier($q,$idToko);

		$data_array = array();$n=0;

		foreach($getStok->result() as $row){
			$dataCart = array(
								"idProduk"		=> $row->id_produk,
								"qty"			=> $row->pesan,
								"idUser" 		=> $idUser,
								"harga"			=> 0
						     );

			$this->modelPromoBundling->insertCartPO($dataCart);
			$n++;
		}

		echo json_encode($n);
	}

	function insertPO(){
		$id_user  		= sprintf("%03d",$this->global['idUser']);
		$tanggal_po 	= date('Y-m-d');
		$tanggalMulai 	= $_POST['tanggalMulai'];
		$tanggalSelesai = $_POST['tanggalSelesai'];
		$keterangan 	= $_POST['keterangan'];
		$jamMulai  		= $_POST['jamMulai'];
		$jamSelesai  	= $_POST['jamSelesai'];
		$setJam			= $_POST['setJam'];
		$setHari		= $_POST['setHari'];
		$store 			= $_POST['store'];
		$diskon 		= $_POST['diskon'];

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

		$no_inv = 'PBD'.date('hs').$convert_date.$id_user.sprintf("%04d",$cek_tanggal+1);

		

		$viewDataPO = $this->modelPromoBundling->viewCartPO($this->global['idUser'],$this->global['idStore']);

		$bundling_products = [];
		foreach($viewDataPO->result() as $row){
			$sku 			= $row->id_produk;
			array_push($bundling_products, $sku);
			
		}

		$data_masuk = array(
								"no_promo" 			=> $no_inv,
								"tanggal_buat" 		=> $tanggal_po,
								"tanggalMulai"		=> $tanggalMulai,
								"tanggalSelesai"	=> $tanggalSelesai,
								"keterangan"		=> $keterangan,
								"JamMulai"			=> $jamMulai,
								"JamSelesai"		=> $jamSelesai,
								"setJam"			=> $setJam,
								"setHari"			=> $setHari,
								"HariID"			=> $HariID,
								"id_pic"			=> $this->global['idUser'],
                                "id_toko"			=> $store,
								"status"			=> 1,
								"diskon"			=> $diskon,
								"bundling_products"	=> json_encode($bundling_products)
							);
		
		$this->modelPromoBundling->insertPONumber($data_masuk);

		$this->modelPromoBundling->deleteCartPO($this->global['idUser']);
		echo $no_inv;

		//redirect("purchase_order/form_po?no_po=".$no_inv);
	}

	function daftar_po(){
		$this->global['pageTitle'] = "SOLUSI POS - Daftar Produk Promo Supplier";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("promo_bundling/body_daftar",$this->global,NULL,"promo_bundling/footerDaftarPO");
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function form_promo(){
		$no_po = $_GET['no_promo'];
		$data['header'] = $this->db->get("ap_receipt");
		$info_po = $this->modelPromoBundling->infoPurchase($no_po);

		$data['tanggal_buat'] 		= $info_po->tanggal_buat;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['tanggalMulai']		= $info_po->tanggalMulai;
		$data['tanggalSelesai']		= $info_po->tanggalSelesai;
		$data['store']				= $info_po->store;
		$data['bundling_products']	= $info_po->bundling_products;
		$data['setJam'] 			= $info_po->setJam==1? $info_po->JamMulai.' s/d '.$info_po->JamSelesai:'tidak diatur';

		$this->global['pageTitle'] = "SOLUSI POS - Form Promo Bundling";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("promo_bundling/body_form_po",$this->global,$data,"promo_bundling/footer_barang_masuk");
	}

	function datatablesPO(){
        $idUser     = $this->global['idUser'];
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelPromoBundling->totalPromoSupplier();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelPromoBundling->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore'],$this->global['isSuperadmin']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelPromoBundling->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore'],$this->global['isSuperadmin']);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			

			$output['data'][]=array($nomor_urut,"<a href='".base_url('promo_bundling/form_promo?no_promo='.$dt['no_promo'])."'>".$dt['no_promo']."</a>",$dt['tanggal_buat'],$dt['tanggalMulai'],$dt['tanggalSelesai'],$dt['first_name'],"<a onclick=\"if (confirm('Yakin hapus semua data yang berhubungan dengan nomor promo ini?')){hapusPromo('".$dt['no_promo']."');}\" ><i class=\"fa fa-trash\"></i></a>");
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function hapusAllPromo(){
		$id = $_POST['id'];

		$dataUpdate = array(
								"status"	=> 0
					       );
		$this->db->where("no_promo",$id);
		$this->db->update("promo_bundling",$dataUpdate);
	}

	function insertCartPO(){
		$idProduk 		= $_POST['idProduk'];
		$idUser 		= $this->global['idUser'];
		$idStore		= empty($this->global['idStore'])?7:$this->global['idStore'];
		$hargaProduk 	= $this->modelPromoBundling->hargaBeliProduk($idProduk,$idStore);

		//cek on cart if exist
		$cekCart = $this->modelPromoBundling->cekCartPO($idProduk,$idUser);

		if($cekCart < 1){
			$dataCart = array(
								"idProduk"		=> $idProduk,
								"paid_item"		=> 1,
								"idUser" 		=> $idUser,
								"harga"			=> $hargaProduk
						     );

			$this->modelPromoBundling->insertCartPO($dataCart);
			echo 0;
		} else {			
			$id = $this->modelPromoBundling->getIdCart($idProduk,$idUser);
			echo $id;
		}
	}

	function cartPO(){
		$idUser = $this->global['idUser'];
		// DISINI TAMBAH SCRIPT
		// Ambil data iduser dan supplier
		$data['viewCartPO'] = $this->modelPromoBundling->viewCartPO($idUser,$this->global['idStore']);
		$this->load->view("promo_bundling/cartPO",$data);
	}

	function updateQtyPaidCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser   	= $this->global['idUser'];
		$qty 		= $_POST['qty'];

		$dataUpdate = array(
								"paid_item"		=> $qty
					       );
		
		$this->modelPromoBundling->updateQtyCart($idProduk,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelPromoBundling->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}
	function updateQtyFreeCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$qty 		= $_POST['qty'];

		$dataUpdate = array(
								"free_item"		=> $qty
					       );
		
		$this->modelPromoBundling->updateQtyCart($idProduk,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelPromoBundling->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}
	function updateQuotaCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$quota = $_POST['quota'];

		$dataUpdate = array(
								"quota"		=> $quota
					       );
		
		$this->modelPromoBundling->updateQtyCart($idProduk,$idUser,$dataUpdate);
	}
	function updateNominalCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$nominal = $_POST['nominal'];

		$dataUpdate = array(
								"minimal_belanja"		=> $nominal
					       );
		
		$this->modelPromoBundling->updateQtyCart($idProduk,$idUser,$dataUpdate);
	}

	function totalCart(){
		$idUser   = $this->global['idUser'];
		$totalCart = $this->modelPromoBundling->totalCartPeruser($idUser);

		if($totalCart){
			echo number_format($totalCart,'0',',','.');
		} else {
			echo 0;
		}
	}

	function updateHargaCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$qty = $_POST['qty'];
		$harga = $_POST['harga']<100? ($_POST['harga']/100)*$_POST['harga_jual']:$_POST['harga'];
		$harga_jual = $_POST['harga_jual'];
		$totHarga = $qty*$harga_jual;

		$dataUpdate = array(
								"harga"		=> $harga
					       );

		$this->modelPromoBundling->updateHargaCart($idProduk,$idUser,$dataUpdate);
	
		//get total peritem
		$totalPeritem = $this->modelPromoBundling->totalPeritem($idUser,$idProduk);

		echo number_format($totHarga-$totalPeritem,'0',',','.');
	}
	

	function hapusCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$this->modelPromoBundling->hapusCart($idProduk,$idUser);
	}

}