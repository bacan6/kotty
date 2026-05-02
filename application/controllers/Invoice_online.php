<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Invoice_online extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelWaste"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],3,77);
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Invoice Online";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("invoice_online/body",$this->global,null,"invoice_online/footer");
	}

	function addInvoice(){
		$no_online	 	= $_POST['no_invoice_online'];
        $cek = $this->cek_inv($no_online);
        if($cek<1 && !empty($no_online)){
            $data = array(
                "no_online" 		=> $no_online,
                "tanggal_online"  	=> date('Y-m-d H:i:s'),
                "status"			=> 0,
				"id_toko"			=> $this->global['idStore']
            );

            $this->db->insert("ap_invoice_online",$data);
        }
		
		//echo $no_online;
        $this->index();

	}

	function invoice_waste(){
		$data['header'] = $this->db->get("ap_receipt");

		$no_waste = $_GET['no_waste'];

		$data['info_waste'] = $this->model1->info_waste($no_waste);
		$data['item_waste'] = $this->model1->item_waste($no_waste);;

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Waste";
		$this->loadViews("waste/body_invoice_waste",$this->global,$data,"footer_empty");
	}

	function getDataProdukWarehouse(){
		$this->load->model("model_penjualan");
		$sku 		= $_POST['sku'];
		$dataProduk = $this->modelWaste->cek_stok_lama($sku,$this->global['idStore']);

		echo $dataProduk;
	}

	function viewInvoice(){
		$idUser = $this->global['idUser'];
        $this->db->select(array("ap_invoice_online.no_online","ap_invoice_online.no_invoice","ap_invoice_online.tanggal_online","ap_invoice_online.tanggal_invoice","ID","status"));
		$this->db->from("ap_invoice_online");
		$this->db->where("ap_invoice_online.status",0);
		$this->db->where("ap_invoice_online.id_toko",$this->global['idStore']);
		$this->db->order_by("ap_invoice_online.ID","DESC");
		$data['viewCart'] = $this->db->get()->result();
		$this->load->view("invoice_online/viewInvoice",$data);	
	}

	function daftar(){
		$this->global['pageTitle'] = "SOLUSI POS - Daftar Resi";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("invoice_online/body_daftar",$this->global,NULL,"waste/footerDaftar");
	}

	function datatables(){
        $idUser     = $this->global['idUser'];
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelWaste->totalWasteProduk();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelWaste->viewWaste($length,$start,$search);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelWaste->viewWaste($length,$start,$search);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			$output['data'][]=array($nomor_urut,"<a href='".base_url('waste/invoice_waste?no_waste='.$dt['no_waste'])."'>".$dt['no_waste']."</a>",$dt['tanggal_waste'],$dt['keterangan'],$dt['first_name'],$dt['store'],$status);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function hapusCart(){
		$id = $_POST['id'];

		$this->db->delete("ap_invoice_online",array("ID" => $id));
	}

    function cek_inv($no_online){
		$this->db->select_sum("ap_invoice_online.ID");
		$this->db->from("ap_invoice_online");
		$this->db->where("ap_invoice_online.no_online",$no_online);
		$this->db->where("ap_invoice_online.id_toko",$this->global['idStore']);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->ID;
		}
	}
}