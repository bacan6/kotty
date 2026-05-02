<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class PenerimaanWorkOrder extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelWorkOrder"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,38);
	}

	function index(){
		$data['supplier'] = $this->db->get("supplier")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Penerimaan Work Order";
		$this->loadViews("penerimaan_work_order/bodyPenerimaanWorkOrder",$this->global,$data,"penerimaan_work_order/footer");
	}

	function formWO(){
		$noWO = $this->input->get("noWO");
		$data['infoStore'] = $this->db->get("ap_receipt")->row();
		$data['infoWO'] = $this->modelWorkOrder->infoWO($noWO);
		$data['bahanBakuOrder'] = $this->modelWorkOrder->daftarBahanBakuOrder($noWO);
		$data['daftarPesananOrder'] = $this->modelWorkOrder->daftarPesananOrder($noWO);
		$daftarBiaya = $this->modelWorkOrder->daftarBiaya($noWO);

		$jenisBiaya = $daftarBiaya->nama_biaya;
		$biaya = $daftarBiaya->biaya;

		$array1 = json_decode($jenisBiaya);
		$array2 = json_decode($biaya);

		$data['payment'] = array_combine($array1, $array2);

		$this->global['pageTitle'] = "SOLUSI POS - Form Work Order";
		$this->loadViews("work_order/formWO",$this->global,$data,"work_order/footerWO");
	}

	function datatablesPenerimaanWO(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelWorkOrder->totalWO();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelWorkOrder->viewWorkOrder($length,$start,$search);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelWorkOrder->viewWorkOrder($length,$start,$search);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<a href="'.base_url().'"><span class="label label-primary">On Process</span></a>';
			} elseif($status==1){
				$button = '<a href="'.base_url().'"><span class="label label-info">Diterima</span></a>';
			} elseif($status==2){
				$button = '<a href="'.base_url().'"><span class="label label-success">Selesai</span></a>';
			} elseif($status==3){
				$button = '<a href="'.base_url().'"><span class="label label-danger">Batal</span></a>';
			}

			$output['data'][]=array($nomor_urut,"<a href='".base_url('penerimaanWorkOrder/formWO?noWO='.$dt['no_order'])."'>".$dt['no_order']."</a>",$dt['tanggalWO'],$dt['tanggalPenyelesaian'],$dt['supplier'],$dt['pemohon'],$button,"<a href='".base_url('penerimaanWorkOrder/receiveOrder?noWO='.$dt['no_order'])."' class='btn btn-primary' title='Penerimaan Work Order'><i class='fa fa-rotate-left'></i></a>");
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function datatablesPenerimaanWOFilter(){
		$tanggalWO = $_POST['tanggalWO'];
		$tanggalPenyelesaian = $_POST['tanggalPenyelesaian'];
		$vendor = $_POST['vendor'];
		$status = $_POST['status'];

		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelWorkOrder->totalWOFilter($tanggalWO,$tanggalPenyelesaian,$vendor,$status);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelWorkOrder->viewWorkOrderFilter($length,$start,$search,$tanggalWO,$tanggalPenyelesaian,$vendor,$status);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelWorkOrder->viewWorkOrderFilter($length,$start,$search,$tanggalWO,$tanggalPenyelesaian,$vendor,$status);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<a href="'.base_url().'"><span class="label label-primary">On Process</span></a>';
			} elseif($status==1){
				$button = '<a href="'.base_url().'"><span class="label label-info">Diterima</span></a>';
			} elseif($status==2){
				$button = '<a href="'.base_url().'"><span class="label label-success">Selesai</span></a>';
			} elseif($status==3){
				$button = '<a href="'.base_url().'"><span class="label label-danger">Batal</span></a>';
			}

			$output['data'][]=array($nomor_urut,"<a href='".base_url('penerimaanWorkOrder/formWO?noWO='.$dt['no_order'])."'>".$dt['no_order']."</a>",$dt['tanggalWO'],$dt['tanggalPenyelesaian'],$dt['supplier'],$dt['pemohon'],$button,"<a href='".base_url('penerimaanWorkOrder/receiveOrder?noWO='.$dt['no_order'])."' class='btn btn-primary' title='Penerimaan Work Order'><i class='fa fa-rotate-left'></i></a>");
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function filterDatatables(){
		$data['tanggalWO']	= $_POST['tanggalWO'];
		$data['tanggalPenyelesaian'] = $_POST['tanggalPenyelesaian'];
		$data['vendor'] = $_POST['vendor'];
		$data['status'] = $_POST['status'];

		$this->load->view("penerimaan_work_order/filterDatatables",$data);
	}

	function receiveOrder(){
		$noWO = $this->input->get("noWO");
		$data['infoWO'] = $this->modelWorkOrder->infoWO($noWO);
		$data['bahanBakuOrder'] = $this->modelWorkOrder->daftarBahanBakuOrder($noWO);
		$data['daftarPesananOrder'] = $this->modelWorkOrder->daftarPesananOrder($noWO);
		$data['store'] = $this->db->get("ap_store")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Penerimaan Work Order";
		$this->loadViews("penerimaan_work_order/bodyReceiveOrder",$this->global,$data,"penerimaan_work_order/footerReceiveOrder");	
	}

	function daftarPesanan(){
		$noWO = $_POST['noWO'];
		$data['daftarPesananOrder'] = $this->modelWorkOrder->daftarPesananOrder($noWO);
		$data['noWO'] = $noWO;
		$this->load->view("penerimaan_work_order/daftarPesanan",$data);
	}

	function daftarMaterial(){
		$noWO = $_POST['noWO'];
		$data['bahanBakuOrder'] = $this->modelWorkOrder->daftarBahanBakuOrder($noWO);
		$data['noWO'] = $noWO;
		$this->load->view("penerimaan_work_order/daftarMaterial",$data);
	}

	function submitAdjust(){
		$keterangan 	= $_POST['keterangan'];
		$dataItem 		= $_POST['dataItem'];
		$tanggal 		= date('Y-m-d');
		$noWO 			= $_POST['noWO'];
		$idUser 		= $this->global['idUser'];

		$cekAdjustDate  = $this->modelWorkOrder->cekAdjustDate($tanggal);

		$noAdjust = "WOADJ-".date('y').date('m').date('d').sprintf('%02d',$idUser).sprintf('%03d',$cekAdjustDate+1);

		$dataAdjust = array(
								"no_adjusment" 			=> $noAdjust,
								"noWO"					=> $noWO,
								"tanggal"				=> $tanggal,
								"id_user"				=> $idUser,
								"keterangan"			=> $keterangan
						   );

		$this->modelWorkOrder->submitAdjusmentWO($dataAdjust);

		$itemLoop = json_decode($dataItem);

		foreach($itemLoop as $row){
			$sku = $row->sku;
			$qty = $row->qty;

			$stokBahanBaku = $this->modelWorkOrder->currentStokBahanBaku($sku);

			if($qty > 0 OR $qty < 0){

				$dataUpdate[] = array(
										"no_adjusment"		=> $noAdjust,
										"sku"				=> $sku,
										"qty"				=> $qty
								   	);

				//update stok bahan baku
				$currentStok[] = array(
										"sku"	=> $sku,
										"stok"	=> $stokBahanBaku-$qty
								    );
			}
		}

		$this->modelWorkOrder->insertBatchWOAdjustment($dataUpdate,$currentStok);
	}

	function receiveWOResultSQL(){
		$id_user  		= sprintf("%03d",$this->global['idUser']);
		$received_by 	= $_POST['diterimaOleh'];
		$checked_by 	= $_POST['diperiksaOleh'];
		$tanggal_terima = date('Y-m-d');
		$no_po 			= $_POST['noWO'];
		$id_supplier	= $_POST['idSupplier'];
		$diterimaDi 	= $_POST['diterimaDi'];

		$cek_terima 	= $this->modelWorkOrder->cekTanggalReceiveWO($tanggal_terima);

		$no_inv = 'RDWO-'.date('y').date('m').date('d').$id_user.sprintf("%03d",$cek_terima+1);


		$data_receive = array(
								"no_receive"		=> $no_inv,
								"no_po"				=> $no_po,
								"received_by"		=> $received_by,
								"checked_by"		=> $checked_by,
								"tanggal_terima"	=> $tanggal_terima,
								"id_pic"			=> $this->global['idUser'],
								"id_supplier"		=> $id_supplier,
								"diterimaDi" 		=> $diterimaDi,
								"type"				=> 2
							);

		$this->modelWorkOrder->receiveWOResultSQL($data_receive);		

		$itemProduk = $_POST['listProduk'];
		$decodeJSON = json_decode(stripcslashes($itemProduk));

		foreach($decodeJSON as $row){
			$sku 	= $row->idProduk;
			$qty 	= $row->qty;

			$harga = $this->modelWorkOrder->hargaBeliProduk($sku);

			$data_insert[] = array(
									"no_receive" 	=> $no_inv,
									"sku"			=> $sku,
									"qty"			=> $qty,
									"price"			=> $harga,
									"tanggal"		=> $tanggal_terima
								);
			

			if($diterimaDi < 1){
				//stok lama produk
				$stok_lama_produk = $this->model1->get_stok_lama_produk($sku);


				$data_stok[] = array(
									"id_produk"	=> $sku,
									"stok"		=> $stok_lama_produk+$qty
								  );

			} else {
				$cek_barang = $this->model1->cek_stok_toko($sku,$diterimaDi);

				if($cek_barang > 0){
					//dapatkan stok lama barang di virtual warehouse

					$stok_virtual = $this->model1->stok_lama_toko($sku,$diterimaDi);

					$new_stok_virtual = $stok_virtual + $qty;

					$data_vr = array(
													"stok" 	=> $new_stok_virtual
												);

					$this->modelWorkOrder->receiveWorkOrder($sku,$diterimaDi,$data_vr);
				} else {
					//sisipkan barang baru
					$data_vr = array(
										"id_produk" 	=> $sku,
										"id_store"		=> $diterimaDi,
										"stok"			=> $qty 
									);

					$this->modelWorkOrder->insertStokStore($data_vr);
				}
			}



		}

		$this->db->insert_batch("receive_item",$data_insert);

		if($diterimaDi < 1){
			$this->db->update_batch("ap_produk",$data_stok,"id_produk");
		}
		
		//PROSES PENERBITAN HUTANG
		//SET INSERT HUTANG 

		//cek if exist

		/**$cek_penerbitan_hutang = $this->model1->cek_penerbitan_hutang($no_po);

		if($cek_penerbitan_hutang < 1){
			$data_tagihan = array(
									"no_tagihan"		=> $no_po,
									"status_hutang" 	=> 0
								 );

			$this->db->insert("hutang",$data_tagihan);
		}**/

		echo $no_inv;
		//redirect("bahan_masuk/invoice_receive?no_receive=".$no_inv."&id_supplier=".$id_supplier."&no_po=".$no_po."&arrival_date=".$tanggal_terima."&received_by=".$received_by."&checked_by=".$checked_by);
	}

	function invoiceReceive(){
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->modelWorkOrder->dataReceive($no_receive);
		$data['receive_item'] = $this->modelWorkOrder->received_item($no_receive);
		$this->global['pageTitle'] = "SOLUSI POS - Invoice Penerimaan Work Order";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("penerimaan_work_order/invoiceReceive",$this->global,$data,"footer_empty");
	}

	function invoiceAdjusment(){
		$data['header'] = $this->db->get("ap_receipt");
		$noAdj = $this->input->get("noAdj");
		$data['infoAdj'] = $this->modelWorkOrder->infoAdj($noAdj);
		$data['itemAdj'] = $this->modelWorkOrder->itemAdj($noAdj);

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Adjusment Work Order";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("penerimaan_work_order/invoiceAdjusment",$this->global,$data,"footer_empty");
	}

	function riwayatPenerimaan(){
		$noWO = $_POST['noWO'];
		$data['riwayatPenerimaan'] = $this->modelWorkOrder->riwayatPenerimaan($noWO);
		$this->load->view("penerimaan_work_order/riwayatPenerimaan",$data);
	}

	function riwayatAdjusment(){
		$noWO = $_POST['noWO'];
		$data['riwayatAdjusment'] = $this->modelWorkOrder->riwayatAdjusment($noWO);
		$this->load->view("penerimaan_work_order/riwayatAdjusment",$data);
	}

	function changeStatus(){
		$status = $_POST['status'];
		$noWO 	= $_POST['noWO'];

		if($status=='diterima'){
			$key = 1;
		} elseif($status=='selesai'){
			$key = 2;
		} elseif($status=='batal'){
			$key = 3;
		}

		$dataUpdate = array(
								"status"	=> $key
						   );

		$this->db->where("no_order",$noWO);
		$this->db->update('work_order',$dataUpdate);

		echo base_url('penerimaanWorkOrder/receiveOrder?noWO='.$noWO);
	}

	function maxStokAdj(){
		$sku = $_POST['sku'];

		$stok = $this->modelWorkOrder->currentStokBahanBaku($sku);
		echo $stok;
	}
}