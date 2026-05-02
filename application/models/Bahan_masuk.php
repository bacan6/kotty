<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Bahan_masuk extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelBahanMasukMaterial"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,10);
	}

	function index(){
		$data['supplier'] = $this->db->get("supplier")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Barang Masuk";
		$this->loadViews("bahan_masuk/body_bahan_masuk",$this->global,$data,"bahan_masuk/footerBahanMasuk");
	}

	function POFilter(){
		$data['tanggalPO'] = $_POST['tanggalPO'];
		$data['tanggalKirim'] = $_POST['tanggalKirim'];
		$data['supplier'] = $_POST['supplier'];
		$data['status'] = $_POST['status'];

		$this->load->view("bahan_masuk/POFilter",$data);
	}

	function datatablesPO(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];
        $idUser     = $this->global['idUser'];

		$total 			 			= $this->modelBahanMasukMaterial->totalPOProduk();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewPOProduk($length,$start,$search,$idUser,$this->global['idStore']);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-primary">Menunggu Approve</span></a>';
			} elseif($status==1){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-success">Diterima</span></a>';
			} elseif($status==2){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-danger">Ditolak</span></a>';
			} elseif($status==3){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-info">Selesai</span></a>';
			}

			$output['data'][]=array($nomor_urut,"<a href='#'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['tanggal_kirim'],$dt['supplier'],$dt['first_name'],$button);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function datatablesPOFilter(){
		$tanggalPO = $_POST['tanggalPO'];
		$tanggalKirim = $_POST['tanggalKirim'];
		$supplier = $_POST['supplier'];
		$status = $_POST['status'];

		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelBahanMasukMaterial->totalPOProdukFilter($tanggalPO,$tanggalKirim,$supplier,$status);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewPOProdukFilter($length,$start,$search,$tanggalPO,$tanggalKirim,$supplier,$status);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewPOProdukFilter($length,$start,$search,$tanggalPO,$tanggalKirim,$supplier,$status);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-primary">Menunggu Approve</span></a>';
			} elseif($status==1){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-success">Diterima</span></a>';
			} elseif($status==2){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-danger">Ditolak</span></a>';
			} elseif($status==3){
				$button = '<a href="'.base_url('bahan_masuk/good_receipt?no_po='.$dt['no_po']).'"><span class="label label-info">Selesai</span></a>';
			}

			$output['data'][]=array($nomor_urut,"<a href='#'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['tanggal_kirim'],$dt['supplier'],$dt['first_name'],$button);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function change_po_status(){
		$status 		= $_GET['status'];
		$no_po 	 		= $_GET['no_po'];

		$data_update = array(
								"status"	=> $status
							);

		$this->modelBahanMasukMaterial->changePOStatus($no_po,$data_update);
		redirect("bahan_masuk/good_receipt?no_po=".$no_po);
	}

	function proses_receive_item(){
		$id_user  		= sprintf("%03d",$this->global['idUser']);
		$received_by 	= $_POST['diterimaOleh'];
		$checked_by 	= $_POST['diperiksaOleh'];
		$tanggal_terima = $_POST['tanggalTerima'];
		$no_po 			= $_POST['noPo'];
		$id_supplier	= $_POST['idSupplier'];
		$diterimaDi 	= $_POST['diterimaDi'];
		$diskon 		= $_POST['diskon'];

		$cek_terima 	= $this->model1->cek_tanggal_receive($tanggal_terima);

		$create_date  	= date_create($tanggal_terima);
		$convert_date 	= date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

		$no_inv = 'RCV'.$convert_date.$id_user.sprintf("%03d",$cek_terima+1);


		$data_receive = array(
								"no_receive"		=> $no_inv,
								"no_po"				=> $no_po,
								"received_by"		=> $received_by,
								"checked_by"		=> $checked_by,
								"tanggal_terima"	=> $tanggal_terima,
								"id_pic"			=> $this->global['idUser'],
								"id_supplier"		=> $id_supplier,
								"diterimaDi" 		=> $diterimaDi,
								"diskon" 			=> $diskon
							);

		$this->modelBahanMasukMaterial->insertReceiveOrder($data_receive);
	
		$itemProduk = $_POST['produkItem'];
		$decodeJSON = json_decode(stripcslashes($itemProduk));

		foreach($decodeJSON as $row){
			$sku 	= $row->sku;
			$qty 	= $row->qty;
			$price 	= $row->harga;
			$hargajual 	= $row->hargajual;
			$bonus 	= $row->bonus;
			$diskon1 	= $row->diskon1;
			$diskon2 	= $row->diskon2;
			$diskon3 	= $row->diskon3;

			$data_insert[] = array(
									"no_receive" 	=> $no_inv,
									"sku"			=> $sku,
									"qty"			=> $qty,
									"price"			=> $price,
									"bonus"			=> $bonus,
									"diskon1"		=> $diskon1,
									"diskon2"		=> $diskon2,
									"diskon3"		=> $diskon3,
									"tanggal"		=> $tanggal_terima
								);

			$dataHarga[] = array(
										"hpp" 	=> $price,
										"harga" => $hargajual,
										"id_produk" => $sku
									);
			if($diterimaDi < 1){
				//stok lama produk
				$stok_lama_produk = $this->model1->get_stok_lama_produk($sku);
				$data_stok = array(
									"stok"	=> $stok_lama_produk+$qty+$bonus
								  );

				$this->modelBahanMasukMaterial->penerimaanGudang($sku,$data_stok);
			} else {
				$cek_barang = $this->model1->cek_stok_new($sku,$diterimaDi);

				if($cek_barang['count'] > 0){
					//dapatkan stok lama barang di virtual warehouse
					$newStokToko = $cek_barang['stok'] + $qty + $bonus;
					$updateDataStok[] = array(
										"stok" 	=> $newStokToko,
										"id_produk" => $sku,
									);
				} else {
					//sisipkan barang baru
					$insertDataStok[] = array(
										"id_produk" 	=> $sku,
										"id_store"		=> $diterimaDi,
										"stok"			=> $qty 
									);
				}
			}
		}

		$this->modelBahanMasukMaterial->insertBatchReceiveItem($data_insert);
		
		if ($insertDataStok){
			$this->modelBahanMasukMaterial->insertBatchDataStok($insertDataStok);
		}
		if ($updateDataStok){
			$this->modelBahanMasukMaterial->updateBatchDataStok($updateDataStok,$diterimaDi);
		}
		if ($dataHarga){
			$this->modelBahanMasukMaterial->updateBatchHarga($dataHarga,$diterimaDi);
			$this->modelBahanMasukMaterial->updateBatchHargaStok($dataHarga,$diterimaDi);
		}
		//PROSES PENERBITAN HUTANG
		//SET INSERT HUTANG 

		//cek if exist

		$cek_penerbitan_hutang = $this->model1->cek_penerbitan_hutang($no_po);

		if($cek_penerbitan_hutang < 1){
			$data_tagihan = array(
									"no_tagihan"		=> $no_po,
									"status_hutang" 	=> 0
								 );

			$this->modelBahanMasukMaterial->terbitkanStatusHutang($data_tagihan);
		}

		echo $no_inv;
		//redirect("bahan_masuk/invoice_receive?no_receive=".$no_inv."&id_supplier=".$id_supplier."&no_po=".$no_po."&arrival_date=".$tanggal_terima."&received_by=".$received_by."&checked_by=".$checked_by);
	}

	function good_receipt(){
		$no_po 	= $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelBahanMasukMaterial->purchase_item($no_po,$this->global['idStore']);
		$data['received_invoice'] = $this->model1->received_invoice($no_po);
		$data['store'] = $this->db->get("ap_store")->result();
		$data['noteInfo'] = $this->modelBahanMasukMaterial->noteInfoPO($no_po);

		$this->global['pageTitle'] = "SOLUSI POS - Goods Receipt";
		$this->loadViews("bahan_masuk/body_good_receipt",$this->global,$data,"bahan_masuk/footerBarangMasuk");
	}

	function invoice_receive(){
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);
		$data['receive_item'] = $this->modelBahanMasukMaterial->received_item($no_receive);

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Penerimaan";
		$this->loadViews("bahan_masuk/body_invoice_receive",$this->global,$data,"footer_empty");
	}

	function form_po(){
		$this->load->view("navigation");
				$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelBahanMasukMaterial->purchase_item($no_po);
		$info_po = $this->model1->info_purchase($no_po);

		foreach($info_po as $row){
			$data['tanggal_po'] 		= $row->tanggal_po;
			$data['keterangan'] 		= $row->keterangan;
			$data['supplier'] 			= $row->supplier;
			$data['alamat_sp'] 			= $row->alamat;
			$data['kontak_sp'] 			= $row->kontak;
			$data['ppn']				= $row->ppn;
			$data['nilai_ppn']			= $row->nilai_ppn;
			$data['alamat_pengiriman'] 	= $row->alamat_pengiriman;
			$data['tanggal_kirim']		= $row->tanggal_kirim;
		}
		$this->load->view("bahan_masuk/body_form_po",$data);
		$this->load->view("bahan_masuk/footer_barang_masuk");
	}

	function detailOrder(){
		$no_po = $_POST['noPo'];
		$data['no_po'] = $no_po;
		$data['purchase_item'] = $this->modelBahanMasukMaterial->purchase_item($no_po);
		$this->load->view("bahan_masuk/detailOrder",$data);
	}

	function invoiceReceive(){
		$no_po 	= $_POST['noPo'];
		$data['received_invoice'] = $this->model1->received_invoice($no_po);
		$this->load->view("bahan_masuk/invoiceReceive",$data);
	}

	function riwayatPenerimaan(){
		$noPo = $_POST['noPo'];
		$data['riwayatPenerimaan'] = $this->model1->riwayatPenerimaan($noPo);
		$this->load->view("bahan_masuk/riwayatPenerimaan",$data);
	}

	function qtyReceived(){
		$idProduk 	= $_POST['idProduk'];
		$noPo 		= $_POST['noPo'];

		$qtyReceived = $this->model1->qtyDiterima($idProduk,$noPo);
		echo $qtyReceived;
	}
}