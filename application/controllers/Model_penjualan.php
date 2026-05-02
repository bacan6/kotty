<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Model_penjualan extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	function cekInvoiceItemQty($sku,$no_invoice){
		$this->db->select("qty");
		$this->db->from("ap_invoice_item");
		$this->db->where("id_produk",$sku);
		$this->db->where("no_invoice",$no_invoice);
		$query = $this->db->get()->row();
		return $query->qty;
	}
	function penjualan_perbarang($start,$end,$id_produk){
		$this->db->select("*");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_item.id_produk",$id_produk);
		return $this->db->get()->result();
	}

	function produk_search($q,$id_store){
		$query = "SELECT stok_store.id_produk, ap_produk.nama_produk,ap_produk_price.harga
				  FROM stok_store
				  LEFT JOIN ap_produk ON ap_produk.id_produk = stok_store.id_produk
                  LEFT JOIN ap_produk_price ON ap_produk_price.id_produk = stok_store.id_produk and ap_produk_price.id_toko='$id_store'
				  WHERE (stok_store.id_store = '$id_store' AND ap_produk.status='1') AND (ap_produk.nama_produk LIKE '$q%' OR ap_produk.id_produk LIKE '$q%')
				  GROUP BY ap_produk.id_produk"; 

		return $this->db->query($query);
	}

	function produkSearchRetur($q,$id_store){
		$query = "SELECT stok_store.id_produk, ap_produk.nama_produk
				  FROM stok_store
				  LEFT JOIN ap_produk ON ap_produk.id_produk = stok_store.id_produk
				  WHERE (stok_store.id_store = '$id_store' AND ap_produk.status='1') AND (ap_produk.nama_produk LIKE '$q%' OR ap_produk.id_produk LIKE '$q%')
				  GROUP BY ap_produk.id_produk";

		return $this->db->query($query);
	}

	function list_kasir(){
		$this->db->select("*");
		$this->db->from("user");
		$this->db->join("user_access","user_access.id_user = user.id_user","left");
		$this->db->where("user_access.access_level",18);
		$this->db->where("user_access.status",1);
		$this->db->group_by("user.id_user");
		return $this->db->get()->result();
	}

	function listKasir(){
		$this->db->select("*");
		$this->db->from("users");
		return $this->db->get()->result();
	}

	function cek_status_kasir($id_kasir,$tanggal){
		$this->db->from("closing_modal");
		$this->db->where("id_user",$id_kasir);
		$this->db->where("DATE(tanggal)",$tanggal);
		return $this->db->count_all_results();
	}

	function cekClose($idKasir,$tanggal){
		$this->db->from("closing_id");
		$this->db->where("id_kasir",$idKasir);
		$this->db->where("tanggal",$tanggal);
		return $this->db->count_all_results();
	}

	function modal_kasir($id_kasir,$tanggal){
		$this->db->select(array("modal","tanggal"));
		$this->db->from("closing_modal");
		$this->db->where("id_user",$id_kasir);
		$this->db->where("DATE(tanggal)",$tanggal);
		$query = $this->db->get()->result();

		return $query;
	}

	function list_debit(){
		$this->db->select("*");
		$this->db->from("ap_payment_account");
		$this->db->where("id_payment_type",2);
		return $this->db->get()->result();
	}

	function list_kredit(){
		$this->db->select("*");
		$this->db->from("ap_payment_account");
		$this->db->where("id_payment_type",3);
		return $this->db->get()->result();
	}

	function nilaiClosingCash($id,$tanggal){
		$this->db->select("value");
		$this->db->from("closing_account");
		$this->db->where("id_kasir",$id);
		$this->db->where("tanggal",$tanggal);
		$this->db->where("payment_type",1);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->value;
		}
	}

	function cash_value($id,$tanggal){
		$this->db->select(array("SUM(total) as total","SUM(diskon+diskon_free+diskon_otomatis+poin_reimburs) as diskon"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic",$id);
		$this->db->where("DATE(tanggal)",$tanggal);
		$this->db->where("tipe_bayar",1);
		return $this->db->get()->result();
	}

	function transfer_value($id,$tanggal){
		$this->db->select(array("SUM(total) as total","SUM(diskon+diskon_free+diskon_otomatis+poin_reimburs) as diskon"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic",$id);
		$this->db->where("DATE(tanggal)",$tanggal);
		$this->db->where("tipe_bayar",4);
		return $this->db->get()->result();
	}

	function nilaiClosingTransfer($id,$tanggal){
		$this->db->select("value");
		$this->db->from("closing_account");
		$this->db->where("id_kasir",$id);
		$this->db->where("tanggal",$tanggal);
		$this->db->where("payment_type",4);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->value;
		}
	}

	function debit_value($id_account,$id_pic,$tanggal){
		$this->db->select(array("SUM(total) as total","SUM(diskon+diskon_free+diskon_otomatis+poin_reimburs) as diskon"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic",$id_pic);
		$this->db->where("tipe_bayar",2);
		$this->db->where("sub_account",$id_account);
		$this->db->where("DATE(tanggal)",$tanggal);
		return $this->db->get()->result();
	}

	function debitValueClosing($idAccount,$idKasir,$tanggal){
		$this->db->select("value");
		$this->db->from("closing_account");
		$this->db->where("payment_type",2);
		$this->db->where("account",$idAccount);
		$this->db->where("id_kasir",$idKasir);
		$this->db->where("tanggal",$tanggal);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->value;
		}
	}

	function kredit_value($id_account,$id_pic,$tanggal){
		$this->db->select(array("SUM(total+surcharge) as total","SUM(diskon+diskon_free+diskon_otomatis+poin_reimburs) as diskon"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic",$id_pic);
		$this->db->where("tipe_bayar",3);
		$this->db->where("sub_account",$id_account);
		$this->db->where("DATE(tanggal)",$tanggal);
		return $this->db->get()->result();
	}

	function kreditValueClosing($idAccount,$idKasir,$tanggal){
		$this->db->select("value");
		$this->db->from("closing_account");
		$this->db->where("payment_type",3);
		$this->db->where("account",$idAccount);
		$this->db->where("id_kasir",$idKasir);
		$this->db->where("tanggal",$tanggal);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->value;
		}
	}

	function retur_value($id,$tanggal){
		$this->db->select("SUM(ap_retur_item.harga*ap_retur_item.qty) as nilai_retur");
		$this->db->from("ap_retur_item");
		$this->db->join("ap_retur","ap_retur.no_retur = ap_retur_item.no_retur","left");
		$this->db->where("ap_retur.pic",$id);
		$this->db->where("ap_retur_item.tanggal",$tanggal);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->nilai_retur;
		}
	}

	function diskon_cash_value($id,$tanggal){
		$this->db->select(array("SUM(diskon) as diskon","SUM(diskon_free) as diskon_free","SUM(diskon_otomatis) as diskon_otomatis","SUM(poin_reimburs) as poin_reimburs"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic",$id);
		$this->db->where("DATE(tanggal)",$tanggal);
		$this->db->where("tipe_bayar",1);
		$query = $this->db->get()->result();
		foreach($query as $row){
			return $row->diskon+$row->diskon_free+$row->diskon_otomatis+$row->poin_reimburs;
		}
	}

	function diskon_debit_value($account,$id,$tanggal){
		$this->db->select(array("SUM(diskon) as diskon","SUM(diskon_free) as diskon_free","SUM(diskon_otomatis) as diskon_otomatis","SUM(poin_reimburs) as poin_reimburs"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic",$id);
		$this->db->where("DATE(tanggal)",$tanggal);
		$this->db->where("tipe_bayar",2);
		$this->db->where("sub_account",$account);
		$query = $this->db->get()->result();
		foreach($query as $row){
			return $row->diskon+$row->diskon_free+$row->diskon_otomatis+$row->poin_reimburs;
		}
	}

	function jamClosing($id,$tanggal){
		$this->db->select("jam");
		$this->db->from("closing_id");
		$this->db->where("id_kasir",$id);
		$this->db->where("tanggal",$tanggal);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->jam;
		}
	}

	function noClosing($id,$tanggal){
		$this->db->select("id_closing");
		$this->db->from("closing_id");
		$this->db->where("id_kasir",$id);
		$this->db->where("tanggal",$tanggal);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->id_closing;
		}
	}

	function getProdukPrice($sku,$idStore){
		$this->db->select("harga");
		$this->db->from("ap_produk_price");
		$this->db->where("id_produk",$sku);
		$this->db->where("id_toko",$idStore);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->harga;
		}
	}

	function getProdukAndPrice($sku,$idStore){
		$this->db->select(array("ap_produk.id_kategori","ap_produk.id_produk","ap_produk.nama_produk","ap_produk_price.harga","ap_produk_price.hpp"));
		$this->db->from("ap_produk");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk","left");
		$this->db->where("id_toko",$idStore);
		$this->db->where("ap_produk.id_produk",$sku);
		return $this->db->get();
	}

	function cekStokPerStore($sku,$idStore){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$sku);
		$this->db->where("id_store",$idStore);
		$this->db->group_by("stok_store.id_produk");
		$query = $this->db->get()->row();
		return $query->stok;
	}

	function getProdukData($sku,$idStore){
		$this->db->select(array("stok_store.stok","ap_produk.id_produk","ap_produk_price.hpp","ap_produk_price.harga"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk","left");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk","left");
		$this->db->where("ap_produk_price.id_toko",$idStore);
		$this->db->where("stok_store.id_store",$idStore);
		$this->db->where("ap_produk.id_produk",$sku);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get()->result();
	}

	function getProdukDataWarehouse($sku){
		$this->db->select("ap_produk.stok");
		$this->db->from("ap_produk");
		$this->db->where("ap_produk.id_produk",$sku);
		return $this->db->get()->result();
	}

	function dataCart($idUser,$idStore='7'){
		$this->db->select(array("ap_cart.id","ap_cart.id_produk","ap_cart.quantity as qty","ap_cart.harga","ap_produk_price.hpp","ap_cart.diskon","ap_produk.nama_produk"));
		$this->db->from("ap_cart");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_cart.id_produk","left");
        $this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk","left");
		$this->db->where("ap_produk_price.id_toko",$idStore);
		$this->db->where("ap_cart.id_user",$idUser);
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("ap_cart.id","DESC");
		return $this->db->get()->result();
	}

	function dataCartPending($idPending){
		$this->db->select(array("ap_cart_temp.id","ap_cart_temp.id_produk","ap_cart_temp.quantity as qty","ap_cart_temp.harga","ap_cart_temp.hpp","ap_cart_temp.diskon","ap_produk.nama_produk"));
		$this->db->from("ap_cart_temp");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_cart_temp.id_produk","left");
		$this->db->where("ap_cart_temp.noCart",$idPending);
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("ap_cart_temp.id","DESC");
		return $this->db->get()->result();
	}

	function cekCartIfExist($sku,$idUser){
		$this->db->from("ap_cart");
		$this->db->where("id_produk",$sku);
		$this->db->where("id_user",$idUser);
		return $this->db->count_all_results();
	}

	function cekCartIfExistPending($sku,$noCart){
		$this->db->from("ap_cart_temp");
		$this->db->where("id_produk",$sku);
		$this->db->where("noCart",$noCart);
		return $this->db->count_all_results();
	}

	function cekQtyCart($sku,$idUser){
		$this->db->select("quantity");
		$this->db->from("ap_cart");
		$this->db->where("id_produk",$sku);
		$this->db->where("id_user",$idUser);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->quantity;
		}
	}

	function cekQtyCartPending($sku,$noCart){
		$this->db->select("quantity");
		$this->db->from("ap_cart_temp");
		$this->db->where("id_produk",$sku);
		$this->db->where("noCart",$noCart);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->quantity;
		}
	}

	function cekDiskonBefore($sku,$idUser){
		$this->db->select("diskon");
		$this->db->from("ap_cart");
		$this->db->where("id_produk",$sku);
		$this->db->where("id_user",$idUser);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function cekDiskonBeforePending($sku,$noCart){
		$this->db->select("diskon");
		$this->db->from("ap_cart_temp");
		$this->db->where("id_produk",$sku);
		$this->db->where("noCart",$noCart);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function totalPurchase($idUser){
		$this->db->select("SUM(harga*quantity) as total");
		$this->db->from("ap_cart");
		$this->db->where("id_user",$idUser);
		$this->db->group_by("id_user");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->total;
		}
	}
	function totalPurchaseHPP($idUser){
		$this->db->select("SUM(hpp*quantity) as total");
		$this->db->from("ap_cart");
		$this->db->where("id_user",$idUser);
		$this->db->group_by("id_user");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->total;
		}
	}

	function totalPurchasePending($idPending){
		$this->db->select("SUM(harga*quantity) as total");
		$this->db->from("ap_cart_temp");
		$this->db->where("noCart",$idPending);
		$this->db->group_by("noCart");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->total;
		}
	}
	function totalPurchaseHPPPending($idPending){
		$this->db->select("SUM(hpp*quantity) as hpp");
		$this->db->from("ap_cart_temp");
		$this->db->where("noCart",$idPending);
		$this->db->group_by("noCart");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->hpp;
		}
	}

	function totalByRow($idUser,$sku){
		$this->db->select("(quantity*harga) as totalByRow");
		$this->db->from("ap_cart");
		$this->db->where("id_produk",$sku);
		$this->db->where("id_user",$idUser);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalByRow;
		}
	}

	function totalByRowTemp($noCart,$sku){
		$this->db->select("(quantity*harga) as totalByRow");
		$this->db->from("ap_cart_temp");
		$this->db->where("id_produk",$sku);
		$this->db->where("noCart",$noCart);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalByRow;
		}
	}

	function diskonPeritemPanel($idUser){
		$this->db->select("SUM(diskon) as diskon");
		$this->db->from("ap_cart");
		$this->db->where("id_user",$idUser);
		$this->db->group_by("id_user");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function diskonPeritemPanelPending($idPending){
		$this->db->select("SUM(diskon) as diskon");
		$this->db->from("ap_cart_temp");
		$this->db->where("noCart",$idPending);
		$this->db->group_by("noCart",$idPending);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function getDiskonMember($idUser){
		$this->db->select("diskon");
		$this->db->from("ap_cart_diskon_member");
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function getDiskonMemberPending($noCart){
		$this->db->select("diskon");
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart",$noCart);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function cekIfPoinExist($idUser){
		$this->db->from("ap_cart_diskon_member");
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function cekMember($idMember){
		$this->db->select("id_customer");
		$this->db->from("ap_customer");
		$this->db->where("id_customer",$idMember);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->id_customer;
		}
	}

	function cekIfPoinExistPending($noCart){
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart",$noCart);
		return $this->db->count_all_results();
	}

	function getIdMemberDiskon($idUser){
		$this->db->select("idMember");
		$this->db->from("ap_cart_diskon_member");
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->idMember;
		}
	}

	function getIdMemberDiskonPending($noCart){
		$this->db->select("idMember");
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart",$noCart);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->idMember;
		}
	}

	function poinReimburs($idUser){
		$this->db->select("poinReimburs");
		$this->db->from("ap_cart_diskon_member");
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->poinReimburs;
		}		
	}

	function poinValue($idUser){
		$this->db->select("poinValue");
		$this->db->from("ap_cart_diskon_member");
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->poinValue;
		}
	}

	function poinReimbursPending($noCart){
		$this->db->select("poinReimburs");
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart",$noCart);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->poinReimburs;
		}		
	}

	function poinValuePending($noCart){
		$this->db->select("poinValue");
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart",$noCart);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->poinValue;
		}
	}

	function cekIfOngkirExist($idUser){
		$this->db->from("ap_cart_ongkir");
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function cekIfOngkirExistPending($noCart){
		$this->db->from("ap_cart_ongkir_temp");
		$this->db->where("noCart",$noCart);
		return $this->db->count_all_results();
	}

	function viewOngkir($idUser){
		$this->db->select("ap_cart_ongkir.ongkir");
		$this->db->from("ap_cart_ongkir");
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->ongkir;
		}
	}
	
	function viewOngkirPending($idPending){
		$this->db->select("ap_cart_ongkir_temp.ongkir");
		$this->db->from("ap_cart_ongkir_temp");
		$this->db->where("noCart",$idPending);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->ongkir;
		}
	}


	function cekIfDiskonExist($idUser){
		$this->db->from("ap_cart_diskon");
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function cekIfSurchargeExist($idUser){
		$this->db->from("ap_cart_surcharge");
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function cekIfDiskonExistPending($noCart){
		$this->db->from("ap_cart_diskon_temp");
		$this->db->where("noCart",$noCart);
		return $this->db->count_all_results();
	}

	function cekIfSurchargeExistPending($noCart){
		$this->db->from("ap_cart_surcharge_temp");
		$this->db->where("noCart",$noCart);
		return $this->db->count_all_results();
	}

	function viewDiskon($idUser){
		$this->db->select("diskon");
		$this->db->from("ap_cart_diskon");
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function viewSurcharge($idUser){
		$this->db->select("surcharge");
		$this->db->from("ap_cart_surcharge");
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->surcharge;
		}
	}

	function viewDiskonPending($idPending){
		$this->db->select("diskon");
		$this->db->from("ap_cart_diskon_temp");
		$this->db->where("noCart",$idPending);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->diskon;
		}
	}

	function viewSurchargePending($idPending){
		$this->db->select("surcharge");
		$this->db->from("ap_cart_surcharge_temp");
		$this->db->where("noCart",$idPending);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->surcharge;
		}
	}

	function cekNoPending($idUser){
		$today = date('Y-m-d');

		$this->db->from("ap_cart_temp_no");
		$this->db->where("idUser",$idUser);
		$this->db->where("DATE(tanggal)",$today);
		return $this->db->count_all_results();
	}

	function cekNoReturPerstore(){
		$tanggal = date("Y-m-d");

		$this->db->from("returstore");
		$this->db->where("DATE(tanggal)",$tanggal);
		return $this->db->count_all_results();
	}

	function infoReturPerstore($noRetur){
		$this->db->select(array("returstore.NoRetur","returstore.tanggal","users.first_name","ap_store.store"));
		$this->db->from("returstore");
		$this->db->join("users","users.id = returstore.id_user");
		$this->db->join("ap_store","ap_store.id_store = returstore.idStoreFrom");
		$this->db->where("returstore.NoRetur",$noRetur);
		return $this->db->get()->result();
	}

	function returItem($noRetur){
		$this->db->select(array("returstoreitem.sku","ap_produk.nama_produk","returstoreitem.qty"));
		$this->db->from("returstoreitem");
		$this->db->join("ap_produk","ap_produk.id_produk = returstoreitem.sku");
		$this->db->where("returstoreitem.NoRetur",$noRetur);
		return $this->db->get()->result();
	}

	function dataReturPerstore(){
		$this->db->select(array("returstore.NoRetur","returstore.tanggal","users.first_name","ap_store.store"));
		$this->db->from("returstore");
		$this->db->join("users","users.id = returstore.id_user");
		$this->db->join("ap_store","ap_store.id_store = returstore.idStoreFrom");
		return $this->db->get()->result();		
	}

	function cekNoMemberIfDuplicate($noMember){
		$this->db->from("ap_customer");
		$this->db->where("id_customer",$noMember);
		return $this->db->count_all_results();
	}
	function cekNoCartTempIfDuplicate($noCart){
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart",$noCart);
		return $this->db->count_all_results();
	}

	function setAdjusment($id,$tanggal){
		$this->db->select(array("ap_invoice_number.diskon_otomatis","ap_invoice_number.tanggal","ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("DATE(ap_invoice_number.tanggal)",$tanggal);
		$this->db->where("ap_invoice_number.id_pic",$id);
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get()->result();
	}

	function setAdjusmentFilter($id,$tanggal,$search){
		$this->db->select(array("ap_invoice_number.diskon_otomatis","ap_invoice_number.tanggal","ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("DATE(ap_invoice_number.tanggal)",$tanggal);
		$this->db->where("ap_invoice_number.id_pic",$id);
		$this->db->like("ap_invoice_number.no_invoice",$search);
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get()->result();
	}

	function readPaymentType($no_invoice){
		$this->db->select(array("ap_payment_type.payment_type","ap_payment_account.account"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.no_invoice",$no_invoice);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->payment_type." ".$row->account;
		}
	}

	function paymentTypeSelection(){
		$this->db->select("*");
		$this->db->from("ap_payment_type");
		$this->db->where("id != 5");
		return $this->db->get();
	}

	function oldStokWarehouse($idProduk,$idStore){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_store",$idStore);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->stok;
		}
	}

	function getIdStore($no_invoice){
		$this->db->select("id_toko");
		$this->db->from("ap_invoice_number");
		$this->db->where("no_invoice",$no_invoice);
		$query = $this->db->get()->row();
		return $query->id_toko;
	}

	function invoiceInfo($noInvoice){
		$this->db->select(array("ap_customer.nama","ap_customer.kontak","ap_customer.alamat","ap_invoice_number.no_invoice","ap_invoice_number.tanggal","ap_ekspedisi.ekspedisi","ap_invoice_number.nama_penerima","ap_invoice_number.alamat","ap_invoice_number.kontak_pengiriman","ae_provinsi.nama_provinsi","ae_kabupaten.nama_kabupaten","ae_kecamatan.kecamatan","ap_invoice_number.tanggal","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.jumlah_bayar"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ap_ekspedisi","ap_ekspedisi.id_ekspedisi = ap_invoice_number.id_ekspedisi","left");
		$this->db->join("ae_provinsi","ae_provinsi.id_provinsi = ap_invoice_number.id_provinsi","left");
		$this->db->join("ae_kabupaten","ae_kabupaten.kabupaten_id = ap_invoice_number.id_kabupaten","left");
		$this->db->join("ae_kecamatan","ae_kecamatan.id_kecamatan = ap_invoice_number.id_kecamatan","left");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.no_invoice",$noInvoice);
		return $this->db->get()->row();
	}

	function customerRow($idCustomer){
		$this->db->select(array("ap_customer.nama","ap_customer.kontak","ap_customer.alamat","ap_customer.id_provinsi","ap_customer.id_kabupaten","ap_customer.id_kecamatan"));
		$this->db->from("ap_customer");
		$this->db->where("ap_customer.id_customer",$idCustomer);
		return $this->db->get()->row();
	}

	function invoiceRetur($noInvoice){
		$this->db->select(array("ap_retur.no_retur","ap_retur.tanggal","ap_retur.keterangan"));
		$this->db->from("ap_retur");
		$this->db->where("ap_retur.no_invoice",$noInvoice);
		return $this->db->get()->result();
	}

	function returItemSale($noRetur){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_retur_item.qty","ap_retur_item.harga","ap_retur_item.diskon"));
		$this->db->from("ap_retur_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_retur_item.id_produk","left");
		$this->db->where("ap_retur_item.no_retur",$noRetur);
		return $this->db->get()->result();
	}

	function currentQtyPeritem($id){
		$this->db->select("quantity as qty");
		$this->db->from("ap_cart");
		$this->db->where("id",$id);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function maxQTYCart($idProduk,$idUser){
		$this->db->select("quantity as qty");
		$this->db->from("ap_cart");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_user",$idUser);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function hargaOnCart($id){
		$this->db->select(array("harga","diskon","quantity as qty"));
		$this->db->from("ap_cart");
		$this->db->where("id",$id);
		$query = $this->db->get()->result();
		return $query;
	}

	function hargaOnCartTemp($id){
		$this->db->select(array("harga","diskon","quantity as qty"));
		$this->db->from("ap_cart_temp");
		$this->db->where("id",$id);
		$query = $this->db->get()->result();
		return $query;
	}

	function hargaOnInvoice($id){
		$this->db->select(array("total","hpp"));
		$this->db->from("ap_invoice_number");
		$this->db->where("no_invoice",$id);
		$query = $this->db->get()->result();
		return $query;
	}

	function cekDiskonOtomatis($id){
		$this->db->select(array("diskon_otomatis"));
		$this->db->from("ap_invoice_number");
		$this->db->where("no_invoice",$id);
		$query = $this->db->get()->row();

		return $query->diskon_otomatis;
	}

	function cekDiskon($sku){
		$this->db->select("diskon");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$sku);
		$query = $this->db->get()->row();

		return $query->diskon;
	}

	function insertReturPenjualanSQL($data_retur){
		$this->db->insert("ap_retur",$data_retur);
	}

	function updateStokPerstore($idStore,$sku,$dataUpdate){
		$this->db->where("id_store",$idStore);
		$this->db->where("id_produk",$sku);
		$this->db->update("stok_store",$dataUpdate);
	}

	function updateInvoiceItemRetur($no_invoice,$sku,$dataUpdate){
		$this->db->where("no_invoice",$no_invoice);
		$this->db->where("id_produk",$sku);
		$this->db->update("ap_invoice_item",$dataUpdate);
	}

	function updateInvoiceNumberRetur($no_invoice,$dataUpdate){
		$this->db->where("no_invoice",$no_invoice);
		$this->db->update("ap_invoice_number",$dataUpdate);
	}

	function insertCartTemp($dataPending){
		$this->db->insert("ap_cart_temp_no",$dataPending);
	}

	function inserCartTempItem($data_item,$idUser){
		$this->db->insert_batch("ap_cart_temp",$data_item);

		//delete all data on db cart
		$this->db->delete("ap_cart",array("id_user" => $idUser));
		$this->db->delete("ap_cart_diskon",array("idUser" => $idUser));
		$this->db->delete("ap_cart_diskon_member",array("idUser" => $idUser));
		$this->db->delete("ap_cart_ongkir",array("idUser" => $idUser));
	}

	function hapusCart($sku,$idUser){
		$this->db->delete("ap_cart",array("id_produk" => $sku, "id_user" => $idUser));
	}

	function insertCart($dataCart){
		$this->db->insert("ap_cart",$dataCart);
	}

	function updateCartPendingTemp($noCart,$sku,$dataCartUpdate){
		$this->db->where("noCart",$noCart);
		$this->db->where("id_produk",$sku);
		$this->db->update("ap_cart_temp",$dataCartUpdate);
	}

	function insertCartPendingTemp($dataCart){
		$this->db->insert("ap_cart_temp",$dataCart);
	}

	function updateDiskon($sku,$idUser,$dataUpdate){
		$this->db->where("id_produk",$sku);
		$this->db->where("id_user",$idUser);
		$this->db->update("ap_cart",$dataUpdate);
	}

	function updateQtyCart($sku,$idUser,$dataUpdate){
		$this->db->where("id_produk",$sku);
		$this->db->where("id_user",$idUser);
		$this->db->update("ap_cart",$dataUpdate);
	}

	function updateQtyCartPending($sku,$noCart,$dataUpdate){
		$this->db->where("id_produk",$sku);
		$this->db->where("noCart",$noCart);
		$this->db->update("ap_cart_temp",$dataUpdate);
	}

	function updateDiskonPending($noCart,$dataUpdate){
		$this->db->where("noCart",$noCart);
		$this->db->update("ap_cart_diskon_temp",$dataUpdate);
	}

	function updateSurchargePending($noCart,$dataUpdate){
		$this->db->where("noCart",$noCart);
		$this->db->update("ap_cart_surcharge_temp",$dataUpdate);
	}

	function insertDiskonPending($dataInsert){
		$this->db->insert("ap_cart_diskon_temp",$dataInsert);
	}

	function insertSurchargePending($dataInsert){
		$this->db->insert("ap_cart_surcharge_temp",$dataInsert);
	}

	function updateCartDiskon($idUser,$dataUpdate){
		$this->db->where("idUser",$idUser);
		$this->db->update("ap_cart_diskon",$dataUpdate);
	}

	function updateCartSurcharge($idUser,$dataUpdate){
		$this->db->where("idUser",$idUser);
		$this->db->update("ap_cart_surcharge",$dataUpdate);
	}

	function updateCartSurchargePending($noCart,$dataUpdate){
		$this->db->where("noCart",$noCart);
		$this->db->update("ap_cart_surcharge_temp",$dataUpdate);
	}

	function insertCartDiskon($dataInsert){
		$this->db->insert("ap_cart_diskon",$dataInsert);
	}

	function insertCartSurcharge($dataInsert){
		$this->db->insert("ap_cart_surcharge",$dataInsert);
	}

	function insertCartSurchargePending($dataInsert){
		$this->db->insert("ap_cart_surcharge_temp",$dataInsert);
	}

	function updateCartDiskonPending($sku,$noCart,$dataUpdate){
		$this->db->where("id_produk",$sku);
		$this->db->where("noCart",$noCart);
		$this->db->update("ap_cart_temp",$dataUpdate);
	}

	function hapusCartPending($idProduk,$noCart){
		$this->db->delete("ap_cart_temp",array("id_produk" => $idProduk, "noCart" => $noCart));
	}

	function saveDiskonMember($dataDiskon){
		$this->db->insert("ap_cart_diskon_member",$dataDiskon);
	}

	function saveDiskonMemberPending($dataDiskon){
		$this->db->insert("ap_cart_diskon_member_temp",$dataDiskon);
	}

	function deleteDiscMember($idUser){
		$this->db->delete("ap_cart_diskon_member",array("idUser" => $idUser));
	}

	function deleteDiscMemberPending($noCart){
		$this->db->delete("ap_cart_diskon_member_temp",array("noCart" => $noCart));
	}

	function insertPoin($idUser,$dataUpdate){
		$this->db->where("idUser",$idUser);
		$this->db->update("ap_cart_diskon_member",$dataUpdate);
	}

	function insertPoinPending($noCart,$dataUpdate){
		$this->db->where("noCart",$noCart);
		$this->db->update("ap_cart_diskon_member_temp",$dataUpdate);
	}

	function updateOngkir($idUser,$dataUpdate){
		$this->db->where("idUser",$idUser);
		$this->db->update("ap_cart_ongkir",$dataUpdate);
	}

	function insertOngkir($dataInsert){
		$this->db->insert("ap_cart_ongkir",$dataInsert);
	}

	function updateOngkirPending($noCart,$dataUpdate){
		$this->db->where("noCart",$noCart);
		$this->db->update("ap_cart_ongkir_temp",$dataUpdate);
	}

	function insertOngkirPending($dataInsert){
		$this->db->insert("ap_cart_ongkir_temp",$dataInsert);
	}

	function updatePoinReimburs($id_customer,$poin){
		$this->db->where("id_customer",$id_customer);
		$this->db->update("ap_customer",$poin);
	}

	function insertApInvoiceNumber($data_penjualan){
		$this->db->insert("ap_invoice_number",$data_penjualan);
	}

	function insertPiutangInvoice($data_piutang){
		$this->db->insert("ap_piutang_pay",$data_piutang);	
	}

	function updateStokStore($sku,$id_store,$data_update){
		$this->db->where("id_produk",$sku);
		$this->db->where("id_store",$id_store);
		$this->db->update("stok_store",$data_update);
	}

	function insertBatch($data_item){
		$this->db->insert_batch("ap_invoice_item",$data_item);		
	}

	function hapusTrx($idUser){
		$this->db->delete("ap_cart",array("id_user" => $idUser));
		$this->db->delete("ap_cart_diskon",array("idUser" => $idUser));
		$this->db->delete("ap_cart_diskon_member",array("idUser" => $idUser));
		$this->db->delete("ap_cart_ongkir",array("idUser" => $idUser));
	}

	function hapusSurcharge($idUser){
		$this->db->delete("ap_cart_surcharge",array("idUser" => $idUser));
	}

	function hapusSurchargePending($noCart){
		$this->db->delete("ap_cart_surcharge_temp",array("noCart" => $noCart));
	}

	function hapusTrxTemp($noCart){
		//hapus cart
		$this->db->delete("ap_cart_temp",array("noCart" => $noCart));
		$this->db->delete("ap_cart_diskon_temp",array("noCart" => $noCart));
		$this->db->delete("ap_cart_diskon_member_temp",array("noCart" => $noCart));
		$this->db->delete("ap_cart_ongkir_temp",array("noCart" => $noCart));
	}

	function updateCartTempStatus($noCart,$dataCartTempNo){
		$this->db->where("cartNo",$noCart);
		$this->db->update("ap_cart_temp_no",$dataCartTempNo);
	}

	function simpanDataMember($dataCustomer){
		$this->db->insert("ap_customer",$dataCustomer);
		$affect = $this->db->affected_rows(); 
		return $affect;
	}
}	

