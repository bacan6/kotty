<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Pengeluaran2 extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelPengeluaran2"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],1,5);
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function index(){
		$this->global['pageTitle'] = "SOLUSI POS - Jurnal Toko 2";
        $this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("pengeluaran2/body_pengeluaran",$this->global,NULL,"pengeluaran2/footerPengeluaran");
	}

	function add_jurnal(){
		$Kode 		= $_POST['Kode'];
		$keterangan = $_POST['keterangan'];
        $Debet = $_POST['Debet']+0;
        $Kredit = $_POST['Kredit']+0;
        $tanggal = date('Y-m-d');
        $tanggal_long = date('Y-m-d H:i:s');
        
        $tKredit = $this->modelPengeluaran2->totalKredit($this->global['idStore']);
        $tDebet = $this->modelPengeluaran2->totalDebet($this->global['idStore']);
        // var_dump($tDebet);
        $saldo = ($tKredit[0]->Kredit - $tDebet[0]->Debet)+$Kredit-$Debet;

		$data_insert = array(
								"Kode"		=> $Kode,
								"Keterangan"	=> $keterangan,
                                "K"	=> $Kredit,
                                "D"	=> $Debet,
                                "Saldo"	=> $saldo,
                                "Tanggal"	=> $tanggal,
                                "TanggalBuat"	=> $tanggal_long,
                                "id_toko"	=> $this->global['idStore'],
                                "LoginBuat" => $this->global['idUser']
							);
		$this->modelPengeluaran2->insertJurnal($data_insert);
	}

	function data_jurnal(){
        $this->db->order_by("JurnalID","ASC");
        $this->db->where("id_toko",$this->global['idStore']);
		$data['jurnal'] = $this->db->get("jurnal_umum2");
		$this->load->view("pengeluaran2/data_jurnal",$data);
	}

	function hapus_jurnal(){
		$id = $_POST['id'];

		$this->modelPengeluaran2->hapusJurnal($id);
	}
	
}
