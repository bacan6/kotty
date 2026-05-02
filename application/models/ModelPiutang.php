<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelPiutang extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function riwayatPembayaran($noInvoice){
		$this->db->select(array('ap_piutang_pay.no_seri','ap_piutang_pay.tanggal','users.first_name as nama_user','ap_payment_type.payment_type','ap_payment_account.account','ap_piutang_pay.nominal','ap_piutang_pay.keterangan'));
		$this->db->from("ap_piutang_pay");
		$this->db->join("users","users.id = ap_piutang_pay.id_pic","left");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_piutang_pay.id_payment","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_piutang_pay.account","left");
		$this->db->where("ap_piutang_pay.no_invoice",$noInvoice);
		return $this->db->get()->result();
	}

	function totalTerbayar($noInvoice){
		$this->db->select_sum("nominal");
		$this->db->from("ap_piutang_pay");
		$this->db->where("no_invoice",$noInvoice);
		$query = $this->db->get()->row();
		return $query->nominal;
	}

	function piutangTerbayar($no_invoice){
		$this->db->select_sum("nominal");
		$this->db->from("ap_piutang_pay");
		$this->db->where("no_invoice",$no_invoice);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->nominal;
		}
	}

	function dataPiutang($where){
		$this->db->select(array("ap_piutang.no_invoice","ap_customer.nama","ap_piutang.jatuh_tempo","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.poin_value","ap_invoice_number.diskon_free","ap_piutang.status","ap_invoice_number.tanggal"));
		$this->db->from("ap_piutang");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_piutang.no_invoice");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->where($where);
		$this->db->where("ap_piutang.status",0);
		$this->db->group_by("ap_piutang.no_invoice");
		$this->db->order_by("ap_piutang.jatuh_tempo","ASC");
		return $this->db->get()->result();
	}

	function dataPiutangNumRows($where){
		$this->db->from("ap_piutang");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_piutang.no_invoice");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->where($where);
		$this->db->where("ap_piutang.status",0);
		$this->db->group_by("ap_piutang.no_invoice");
		$this->db->order_by("ap_piutang.jatuh_tempo","ASC");
		return $this->db->get()->num_rows();
	}

	function statusPiutang($noInvoice){
		$this->db->select("status");
		$this->db->from("ap_piutang");
		$this->db->where("ap_piutang.no_invoice",$noInvoice);
		$query = $this->db->get()->row();
		return $query->status;
	}

	function dataPiutangLunas(){
		$this->db->select(array("ap_piutang.no_invoice","ap_customer.nama","ap_piutang.jatuh_tempo","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.poin_value","ap_invoice_number.diskon_free","ap_piutang.status","ap_invoice_number.tanggal"));
		$this->db->from("ap_piutang");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_piutang.no_invoice");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->where("ap_piutang.status",1);
		$this->db->group_by("ap_piutang.no_invoice");
		$this->db->order_by("ap_piutang.jatuh_tempo","ASC");
		return $this->db->get()->result();
	}

	function dataPiutangLunasNumRows(){
		$this->db->from("ap_piutang");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_piutang.no_invoice");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->where("ap_piutang.status",1);
		$this->db->group_by("ap_piutang.no_invoice");
		$this->db->order_by("ap_piutang.jatuh_tempo","ASC");
		return $this->db->get()->num_rows();
	}

	function diskonPeritem($noInvoice){
		$this->db->select("diskon_otomatis as diskon");
		$this->db->from("ap_invoice_number");
		$this->db->where("ap_invoice_number.no_invoice",$noInvoice);
		$query = $this->db->get()->row();
		return $query->diskon;
	}

	function updateStatusPiutangKeLunas($noInvoice,$dataUpdate){
		$this->db->where("ap_piutang.no_invoice",$noInvoice);
		$this->db->update("ap_piutang",$dataUpdate);
	}

	function insertPembayaranPiutang($data_piutang){
		$this->db->insert("ap_piutang_pay",$data_piutang);
	}

	function closeTrx($noInvoice,$data_trx){
		$this->db->where("no_invoice",$_GET['no_invoice']);
		$this->db->update("ap_piutang",$data_trx);
	}
}