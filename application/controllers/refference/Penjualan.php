<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Penjualan extends BaseController
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1", "model_penjualan", "Accounting_model"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'], 2, 7);
	}

	function index()
	{
		$this->session->set_userdata('penjualan_sql_accessed', 0);

		$data['pageTitle'] = 'Solusi POS - Penjualan';
		$data['permitAccess'] = $this->global['permitAccess'];
		$data['permitAccessSub'] = $this->global['permitAccessSub'];
		$data['navigation'] = $this->model1->callNavigation();
		$this->load->view("navigation", $data);
		$data['produk'] = $this->model1->get_produk_select2();
		//$data['provinsi'] = $this->db->get("ae_provinsi");
		$data['sales'] = $this->db->get("tim_sales");
		$data['payment_type'] = $this->db->get("ap_payment_type");
		$idUser = $this->global['idUser'];
		$this->model_penjualan->hapusCartVoucherFisik($idUser);

		$_SESSION['tolakDuplikat'] = '';

		if (!empty($_GET['idPending'])) {

			$idPending = $this->input->get("idPending");
			$cart_pending = $this->db->get_where("ap_cart_temp", array("noCart" => $idPending));
			$rows = $cart_pending->num_rows();
			if ($rows > 0) {
				$this->model_penjualan->hapusTrx($this->global['idUser']);
			}
			foreach ($cart_pending->result() as $dt) {
				$dataCart = array(
					"id_produk" => $dt->id_produk,
					"quantity" => $dt->quantity,
					"id_user" => $this->global['idUser'],
					"harga" => $dt->harga,
					"hpp" => $dt->hpp,
					"diskon" => $dt->diskon,
					"disc_supplier" => $dt->disc_supplier,
					"tebusmurah" => $dt->tebusmurah
				);
				$this->model_penjualan->insertCart($dataCart);
			}
			// $data['ongkir'] = $this->model_penjualan->viewOngkirPending($idPending);
			// $data['diskonPromosi'] = $this->model_penjualan->viewDiskonPending($idPending);
			// $data['group_customer'] = $this->db->get("ap_customer_group");
			// $data['provinsi'] = $this->db->get("ae_provinsi");
			// $data['idPending'] = $_GET['idPending'];
			// $data['idStore'] = $this->global['idStore'];
			// $this->load->view("penjualan/bodyPendingExec_new",$data);
			// $this->load->view("penjualan/footerPendingExec",$data);
		}
		//cek closing	
		$cekClosing = $this->model1->cekClosing($this->global['idUser']);

		if ($cekClosing < 1) {
			$data['ongkir'] = $this->model_penjualan->viewOngkir($idUser);
			$data['diskonPromosi'] = $this->model_penjualan->viewDiskon($idUser);
			$data['group_customer'] = $this->db->get("ap_customer_group");
			$data['provinsi'] = $this->db->get("ae_provinsi");
			$data['ekspedisi'] = $this->db->get("ap_ekspedisi")->result();
			$data['idStore'] = $this->global['idStore'];
			$data['idUser'] = $this->global['idUser'];
			$data['sync_url'] = base_url('penjualan/penjualan_sql');
			$data['products_url'] = base_url('penjualan/export_to_json?inline=1');
			$data['users_store_url'] = base_url('penjualan/export_users_store_json?inline=1');
			$data['payment_accounts'] = $this->_payment_accounts();
			if ($this->global['idUser'] == 1)
				$this->load->view("penjualan/body_penjualan_new", $data);
			else
				$this->load->view("penjualan/body_penjualan_new", $data);
		} else {
			$this->load->view("penjualan/accessClosed");
		}

		$this->load->view("penjualan/footer_penjualan", isset($data) ? $data : array());
	}

	function invoice_setorankasir()
	{
		//error_reporting(E_ALL);ini_set('display_errors', true);
		$no_setor = $_GET['no_setor'];
		$id_store = $this->model1->id_store_setoran($no_setor);
		$data['receipt'] = $this->db->get_where("ap_store", array("id_store" => $id_store));
		$data['setoran_item'] = $this->model1->setoran_item($no_setor);

		$idKasir = $this->model1->getIdKasirSetor($no_setor);
		$tanggal = $this->model1->tanggal_setor($no_setor);
		$jam = $this->model1->jam_setor($no_setor);
		$data['setorBefore'] = $this->model1->setorBefore($idKasir, $tanggal, $jam, $no_setor);

		$this->db->select("ap_payment_account.*,ap_payment_type.payment_type,
			SUM(ap_invoice_number.total-ap_invoice_number.diskon-ap_invoice_number.diskon_free-ap_invoice_number.diskon_otomatis-ap_invoice_number.poin_value+ap_invoice_number.surcharge) as penjualan");
		$this->db->from("ap_payment_account");
		$this->db->join("ap_payment_type", "ap_payment_type.id = ap_payment_account.id_payment_type");
		$this->db->join("ap_invoice_number", "ap_invoice_number.tipe_bayar = ap_payment_account.id_payment_type and ap_invoice_number.sub_account=ap_payment_account.id_payment_account", "LEFT");
		$this->db->where("ap_payment_type.id>1");
		$this->db->where("ap_invoice_number.id_pic", $idKasir);
		$this->db->like("ap_invoice_number.tanggal", $tanggal);
		$this->db->order_by("ap_payment_account.id_payment_type", "ASC");
		$this->db->order_by("ap_payment_account.id_payment_account", "ASC");
		$this->db->group_by("ap_payment_account.id_payment_account", "ASC");

		$channel = $this->db->get();
		$data['channel'] = $channel->result();


		$data['nama_kasir'] = $this->model1->nama_kasir($idKasir);
		$data['cash'] = $this->model_penjualan->cash_value($idKasir, $tanggal);
		$data['retur'] = $this->model_penjualan->retur_value($idKasir, $tanggal) + 0;
		$data['struk'] = $this->model_penjualan->total_struk($idKasir, $tanggal);


		$this->global['pageTitle'] = "SolusiPOS.my.id - Invoice Setoran Kasir";

		$this->loadViews("penjualan/body_invoice_setoran", $this->global, $data, "penjualan/footerInvoiceSetoran");


	}

	function setoranList()
	{
		$idUser = $this->global['idUser'];
		//$pending = $this->db->get_where("ap_cart_temp_no",array("status" => 0,"tanggal like '".date('Y-m-d')."%'"));
		$this->db->select("setoran_kasir.*,users.first_name");
		$this->db->from("setoran_kasir");
		$this->db->join("users", "users.id = setoran_kasir.id_user");
		$this->db->order_by("setoran_kasir.no_setor", "DESC");
		$this->db->limit('100');
		$pending = $this->db->get();
		$data['pendingList'] = $pending->result();
		$this->global['pageTitle'] = "SolusiPOS.my.id - Daftar Setoran Kasir";
		$this->loadViews("penjualan/daftar_setoran", $this->global, $data, "footer_empty");
	}

	function setoran_kasir()
	{
		$this->global['pageTitle'] = "SolusiPOS.my.id - Setor Kasir";
		$data['cash'] = $this->model_penjualan->cash_value($this->global['idUser'], date('Y-m-d'));
		$data['retur'] = $this->model_penjualan->retur_value($this->global['idUser'], date('Y-m-d'));

		$data['setorBefore'] = $this->model1->setorBefore($this->global['idUser'], date('Y-m-d'));
		// $this->db->select("ap_payment_account.*,ap_payment_type.payment_type");
		// $this->db->from("ap_payment_account");
		// $this->db->join("ap_payment_type","ap_payment_type.id = ap_payment_account.id_payment_type");
		// $this->db->where("ap_payment_type.id>1");
		// //$this->db->where("ap_cart_temp_no.tanggal like '2022-02-27%'");
		// $this->db->order_by("ap_payment_account.id_payment_type","ASC");
		// $this->db->order_by("ap_payment_account.id_payment_account","ASC");

		// $channel = $this->db->get();
		// $data['channel'] = $channel->result();
		$this->loadViews("penjualan/body_setor_kasir", $this->global, $data, "penjualan/footersetorankasir");
	}

	function setorankasirsql()
	{
		// --- 1. PROSES SIMPAN DATA (EXISTING) ---
		$tanggal = date('Y-m-d');
		$count_setor = $this->model1->count_setor($tanggal) + 1;
		$back_id = sprintf("%03d", $count_setor);
		$id_user = sprintf("%03d", $this->global['idUser']);
		$no_setor = "ST-" . date('ymd') . '-' . $id_user . '-' . $back_id;

		$data_setor = array(
			"no_setor" => $no_setor,
			"id_user" => $this->global['idUser'],
			"tanggal" => date('Y-m-d'),
			"jam_setor" => date('H:i:s'),
			"id_toko" => $this->global['idStore'],
			"n100k" => $_POST['n100k'],
			"n75k" => $_POST['n75k'],
			"n50k" => $_POST['n50k'],
			"n20k" => $_POST['n20k'],
			"n10k" => $_POST['n10k'],
			"n5k" => $_POST['n5k'],
			"n2k" => $_POST['n2k'],
			"n1kp" => $_POST['n1kp'], // Asumsi 1.000
			"n1kc" => $_POST['n1kc'], // Asumsi 1.000
			"n500" => $_POST['n500'],
			"n200" => $_POST['n200'],
			"n100" => $_POST['n100'],
			"penggantian" => $_POST['penggantian'],
			"voucher" => $_POST['voucher'],
			"catatan" => $_POST['catatan']
		);
		$this->model_penjualan->insertSetoran($data_setor);

		// =========================================================================
		// START: AUTO JOURNAL (SETORAN KASIR -> ADMIN)
		// =========================================================================

		// 1. Hitung Total Fisik Uang (Rupiah)
		// Kita harus mengalikan jumlah lembar dengan nilai pecahannya
		$total_uang_fisik = 
			($_POST['n100k'] * 100000) +
			($_POST['n75k']  * 75000) +
			($_POST['n50k']  * 50000) +
			($_POST['n20k']  * 20000) +
			($_POST['n10k']  * 10000) +
			($_POST['n5k']   * 5000) +
			($_POST['n2k']   * 2000) +
			($_POST['n1kp']  * 1000) +
			($_POST['n1kc']  * 1000) +
			($_POST['n500']  * 500) +
			($_POST['n200']  * 200) +
			($_POST['n100']  * 100);

		// Catatan: 'penggantian' (expense receipt) dan 'voucher' biasanya 
		// TIDAK menambah Kas Admin, jadi tidak kita masukkan ke jurnal debit Kas Admin.
		
		if ($total_uang_fisik > 0) {
			// 2. Ambil Mapping Akun
			$acc_map = $this->Accounting_model->get_mapping_list();

			$entries = [];

			// A. DEBIT: KAS ADMIN STORE (Uang Masuk ke Brankas/Admin)
			// DEBIT: Ke Akun Perantara (Bukan Kas Utama dulu)
			$entries[] = $this->Accounting_model->entry_line($acc_map['KAS_ADMIN_PENDING'], $total_uang_fisik, 0);
    
			// KREDIT: Kasir Pending (Membersihkan saldo kasir)
			$entries[] = $this->Accounting_model->entry_line($acc_map['KAS_KASIR_PENDING'], 0, $total_uang_fisik);

			// 3. Eksekusi Jurnal
			$header = $this->Accounting_model->journal_header(
				$this->global['idStore'],
				$no_setor,
				"Setoran Kasir - " . $id_user . " (" . $_POST['catatan'] . ")",
				'Cash Transfer'
			);

			$this->Accounting_model->create_journal_entry($header, $entries);
		}
		// =========================================================================

		echo $no_setor;
	}



	function getProdukPrice()
	{
		$sku = $_POST['sku'];
		$idStore = $this->global['idStore'];

		$this->load->model('model_penjualan');

		$harga_produk = $this->model_penjualan->getProdukPrice($sku, $idStore);

		echo $harga_produk;
	}


	function select2_customer()
	{
		$query = $_POST['query'];

		$customer = $this->model1->get_customer_select2($query);
		echo "<option>" . '--Pilih Customer--' . "</option>";
		foreach ($customer->result() as $dt) {
			echo "<option value='" . $dt->id_customer . "' data-diskon='" . $dt->diskon . "'>" . $dt->nama . "</option>";
		}
	}

	function get_diskon_customer()
	{
		$id = $_POST['id'];
		$diskon_customer = $this->model1->get_diskon_customer($id);

		echo $diskon_customer;
	}

	function list_kabupaten()
	{
		$id = $_POST['id'];

		$kabupaten = $this->db->get_where("ae_kabupaten", array("id_provinsi" => $id));

		foreach ($kabupaten->result() as $dt) {
			echo "<option value='" . $dt->kabupaten_id . "'>" . $dt->nama_kabupaten . "</option>";
		}
	}

	function list_kecamatan()
	{
		$id = $_POST['id'];

		$kecamatan = $this->db->get_where("ae_kecamatan", array("kabupaten_id" => $id));

		foreach ($kecamatan->result() as $dt) {
			echo "<option value='" . $dt->id_kecamatan . "'>" . $dt->kecamatan . "</option>";
		}
	}

	function get_alamat_customer()
	{
		$id = $_GET['id'];

		$customer = $this->db->get_where("ap_customer", array("id_customer" => $id));

		foreach ($customer->result() as $row) {
			$array_data[] = array(
				"alamat" => $row->alamat,
				"idProvinsi" => $row->id_provinsi,
				"idKabupaten" => $row->id_kabupaten,
				"idKecamatan" => $row->id_kecamatan,
				"kontak" => $row->kontak
			);
		}

		echo json_encode($array_data);

	}

	function returPenjualanSQL()
	{
		// --- 1. INISIALISASI & VALIDASI AWAL ---
		$no_invoice = $_POST['noInvoice'];
		$tanggal    = date('Y-m-d');
		$idStore    = $this->global['idStore'];
		$idUser     = $this->global['idUser'];

		// Validasi Invoice Exist
		$cek_inv = $this->db->get_where('ap_invoice_number', ['no_invoice' => $no_invoice])->row();
		if (!$cek_inv) {
			echo "Error: Invoice tidak ditemukan."; return;
		}
		
		// Tentukan Tipe Bayar Asli (Penting untuk Jurnal Kredit)
		$tipe_bayar_asal = $cek_inv->tipe_bayar; // 5 = Piutang, Lainnya = Tunai

		$count_retur = $this->model1->count_retur($tanggal) + 1;
		$no_retur    = "RN-" . date('ymd') . '-' . sprintf("%02d", $idUser) . '-' . sprintf("%03d", $count_retur);

		// Ambil Mapping Akun (Pindahkan ke atas)
		$acc_map = $this->Accounting_model->get_mapping_list();

		// --- 2. PERSIAPAN DATA LOGISTIK ---
		$dataProduk = $_POST['dataProduk'];
		$decodeJSON = json_decode(stripcslashes($dataProduk));
		
		// Variabel Penampung Akuntansi
		$total_retur_dpp  = 0;
		$total_retur_ppn  = 0;
		$total_retur_hpp  = 0;
		$total_cash_back  = 0; // Uang/Piutang yang dikembalikan ke pelanggan
		$ppn_rate         = 0.11;

		$data_item  = [];
		$data_kartu = [];
		$jumlah_retur_valid = 0;

		foreach ($decodeJSON as $dt) {
			$sku        = $dt->idProduk;
			$qty        = $dt->qty;
			$harga_jual = $dt->hargaJual; // Harga Jual Satuan (Bruto)
			$diskon     = $dt->diskon;    // Diskon per item (Rupiah)
			$hpp        = $dt->hpp;       // HPP Satuan

			// Cek apakah item ini benar ada di invoice tersebut (Security Check)
			$inv_qty = $this->model_penjualan->cekInvoiceItemQty($sku, $no_invoice);
			
			if ($qty > 0 && $inv_qty >= $qty) { // Pastikan qty retur tidak melebihi qty beli
				
				// A. Logika Database Transaksional (Sesuai kode asli Anda)
				$data_item[] = [
					"no_retur"  => $no_retur,
					"id_produk" => $sku,
					"qty"       => $qty,
					"harga"     => $harga_jual,
					"tanggal"   => date('Y-m-d'),
					"diskon"    => ($qty * $diskon)
				];

				$data_kartu[] = [
					"id_store"     => $idStore,
					"id_produk"    => $sku,
					"qty"          => $qty, // Masuk kembali (+)
					"harga"        => $harga_jual,
					"hpp"          => $hpp,
					"tanggal"      => date('Y-m-d H:i:s'),
					"tipe"         => 'Retur Penjualan',
					"no_transaksi" => $no_retur,
					"id_pic"       => $idUser
				];

				// Update Stok Gudang
				$stokLama = $this->model_penjualan->cekStokPerStore($sku, $idStore);
				$this->model_penjualan->updateStokPerstore($idStore, $sku, ["stok" => $stokLama + $qty]);

				// Update Invoice Asli (Mengurangi jumlah terjual di inv lama)
				$this->model_penjualan->updateInvoiceItemRetur($no_invoice, $sku, [
					"qty"    => $inv_qty - $qty,
					"diskon" => (($inv_qty - $qty) * $diskon)
				]);

				// --- B. LOGIKA AKUNTANSI (VALIDATED) ---
				
				// 1. Hitung Nilai Netto Item yang Diretur
				// Harga Jual Bruto - Diskon yang diberikan saat jual
				$subtotal_gross = $qty * $harga_jual;
				$subtotal_netto = $subtotal_gross - ($qty * $diskon);

				// 2. Cek Status PPN Produk
				$prod = $this->db->select('is_ppn')->get_where('ap_produk', ['id_produk' => $sku])->row();
				$is_item_ppn = ($prod && $prod->is_ppn == 1) ? true : false;

				if ($is_item_ppn) {
					// Pecah Netto menjadi DPP dan PPN
					$dpp_item = round($subtotal_netto / (1 + $ppn_rate), 4);
					$ppn_item = $subtotal_netto - $dpp_item;
				} else {
					$dpp_item = $subtotal_netto;
					$ppn_item = 0;
				}

				// Akumulasi ke Variabel Header
				$total_retur_dpp += $dpp_item;
				$total_retur_ppn += $ppn_item;
				$total_retur_hpp += ($qty * $hpp); // HPP dikembalikan
				$total_cash_back += $subtotal_netto; // Total yang harus dikembalikan ke cust

				$jumlah_retur_valid++;

				// UPDATE INVOICE NUMBER

				$total = $qty*$harga_jual;
				$subhpp = $qty*$hpp;

				$cekHarga = $this->model_penjualan->hargaOnInvoice($no_invoice);

				foreach($cekHarga as $row){
					$totalNew = $row->total - $total;
					$hppNew = $row->hpp - $subhpp;			
				}
				
				$cekDiskonOtomatis = $this->model_penjualan->cekDiskonOtomatis($no_invoice);
				$diskon_otomatis = $cekDiskonOtomatis - ($qty*$diskon);
				$diskon_otomatis = $cekDiskonOtomatis<0 || $diskon_otomatis<0? 0:$diskon_otomatis;
				$dataUpdate3 = array(
					"total" 			=> $totalNew,
					"hpp" 				=> $hppNew,
					"diskon_otomatis"	=> $diskon_otomatis
				);

				$this->model_penjualan->updateInvoiceNumberRetur($no_invoice,$dataUpdate3);
			}
		}

		// --- 3. EKSEKUSI DATABASE & JURNAL ---
		
		if ($jumlah_retur_valid > 0) {
			
			// Simpan Header Retur
			$data_retur = array(
				"no_retur"   => $no_retur,
				"no_invoice" => $no_invoice,
				"pic"        => $idUser,
				"tanggal"    => date('Y-m-d H:i'),
				"keterangan" => "Retur dari Inv: " . $no_invoice,
				"id_toko"    => $idStore
			);
			$this->model_penjualan->insertReturPenjualanSQL($data_retur);

			// Simpan Detail & Kartu Stok
			$this->db->insert_batch("ap_retur_item", $data_item);
			if (!empty($data_kartu)) {
				$this->model1->insertKartuStok($data_kartu);
			}

			// Update Total Invoice Asli (Opsional: Hati-hati mengubah histori)
			// Saya sarankan logika ini dipisah/diminimalisir, tapi jika memang flow Anda begini:
			// (Kode updateInvoiceNumberRetur Anda tetap bisa dipakai di sini)
			// ...

			// =====================================================================
			// AUTO JOURNAL (VALIDATED)
			// =====================================================================
			$entries = [];

			// A. JURNAL BALIK PENJUALAN (Mengurangi Omzet & Pajak)
			
			// Debit: Retur Penjualan (Kontra Pendapatan)
			if ($total_retur_dpp > 0) {
				$entries[] = $this->Accounting_model->entry_line($acc_map['RETUR_PENJUALAN'], $total_retur_dpp, 0);
			}

			// Debit: PPN Keluaran (Mengurangi Hutang Pajak)
			if ($total_retur_ppn > 0) {
				$entries[] = $this->Accounting_model->entry_line($acc_map['PPN_KELUARAN'], $total_retur_ppn, 0);
				$this->Accounting_model->log_tax($no_retur, $acc_map['PPN_KELUARAN'], $total_retur_ppn, 11, 'Retur Penjualan - ' . $no_retur);
			}

			// Kredit: Piutang / Kas (Mengurangi Aset)
			// Cek tipe bayar invoice aslinya
			if ($tipe_bayar_asal == 5) { 
				// Jika dulu belinya Ngutang, maka potong Piutang-nya
				$entries[] = $this->Accounting_model->entry_line($acc_map['PIUTANG'], 0, $total_cash_back);
			} else {
				// Jika dulu belinya Tunai, kita kembalikan uang (atau potong Kasir Pending)
				$entries[] = $this->Accounting_model->entry_line($acc_map['KAS_KASIR_PENDING'], 0, $total_cash_back);
			}

			// B. JURNAL BALIK PERSEDIAAN (Barang Masuk Lagi)
			
			if ($total_retur_hpp > 0) {
				// Debit: Persediaan (Aset Bertambah)
				$entries[] = $this->Accounting_model->entry_line($acc_map['PERSEDIAAN'], $total_retur_hpp, 0);
				
				// Kredit: HPP (Beban Berkurang)
				$entries[] = $this->Accounting_model->entry_line($acc_map['HPP'], 0, $total_retur_hpp);
			}

			// Eksekusi Jurnal
			if (!empty($entries)) {
				$header = $this->Accounting_model->journal_header(
					$idStore,
					$no_retur,
					"Retur Penjualan - Ref Inv: " . $no_invoice,
					'Sales Return'
				);
				$this->Accounting_model->create_journal_entry($header, $entries);
			}
			// =====================================================================

			echo $no_retur;
		} else {
			echo "Error: Tidak ada item valid untuk diretur.";
		}
	}

	function invoiceRetur()
	{
		$noInvoice = $_POST['noInvoice'];
		$data['invoiceRetur'] = $this->model_penjualan->invoiceRetur($noInvoice);
		$this->load->view("penjualan/invoiceRetur", $data);
	}

	function printInvoiceRetur()
	{
		$noRetur = $this->input->get('noRetur');
		$no_invoice = $this->db->get_where("ap_retur", array('no_retur' => $noRetur))->row()->no_invoice;
		$id_store = $this->model1->id_store_invoice($no_invoice);
		$data['receipt'] = $this->db->get_where("ap_store", array("id_store" => $id_store));
		$data['invoiceItem'] = $this->model_penjualan->returItemSale($noRetur);
		$this->load->view("penjualan/invoiceReturCetak", $data);
	}

	function data_penjualan()
	{
		$this->global['pageTitle'] = "SOLUSI POS - Data Penjualan";
		$this->loadViews("penjualan/body_data_penjualan", $this->global, NULL, "penjualan/footerDataPenjualan");
	}

	function suratJalan()
	{
		$noInvoice = $this->input->get('no_invoice');
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['header'] = $this->db->get_where("ap_store", array("id_store" => $idStore))->row();
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);
		$this->global['pageTitle'] = "SOLUSI POS - Surat Jalan";
		$this->loadViews("penjualan/suratJalan", $this->global, $data, "footer_empty");
	}

	function shippingLabel()
	{
		$noInvoice = $this->input->get('no_invoice');
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['header'] = $this->db->get_where("ap_store", array("id_store" => $idStore))->row();
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);

		$this->load->library('ciqrcode');

		$qr['data'] = $noInvoice;
		$qr['level'] = 'H';
		$qr['size'] = '4';
		$qr['savename'] = FCPATH . "qr/" . $noInvoice . ".png";
		$this->ciqrcode->generate($qr);



		$this->global['pageTitle'] = "SOLUSI POS - Shipping Label";
		$this->loadViews("penjualan/shippingLabel", $this->global, $data, "footer_empty");
	}

	function invoiceA4()
	{
		$noInvoice = $this->input->get('no_invoice');
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['header'] = $this->db->get_where("ap_store", array("id_store" => $idStore))->row();
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);
		$data['qty_barang'] = $this->model1->qty_barang_struk($noInvoice);
		$data['item_barang'] = $this->model1->item_barang_struk($noInvoice);

		$this->global['pageTitle'] = "SOLUSI POS - Invoice Penjualan";
		$this->loadViews("penjualan/invoiceA4", $this->global, $data, "footer_empty");
	}

	function datatableDaftarPenjualan()
	{
		$draw = $_REQUEST['draw'];
		$length = $_REQUEST['length'];
		$start = $_REQUEST['start'];
		$search = $_REQUEST['search']["value"];

		$total = $this->model1->total_penjualan_all($this->global['idStore'], $this->global['idUser']);
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();

		if ($search != "") {
			$query = $this->model1->daftarPenjualan($length, $start, $search, $this->global['idStore'], $this->global['idUser']);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->model1->daftarPenjualan($length, $start, $search, $this->global['idStore'], $this->global['idUser']);
		}

		$nomor_urut = $start + 1;
		foreach ($query->result_array() as $dt) {
			$output['data'][] = array($nomor_urut, "<a href='" . base_url('penjualan/invoice_penjualan?no_invoice=' . $dt['no_invoice']) . "'>" . $dt['no_invoice'] . "</a>", $dt['payment_type'] . " " . $dt['account'], date_format(date_create($dt['tanggal']), 'd/m/y H:i'), number_format($dt['total'], '0', ',', '.'), number_format($dt['ongkir'], '0', ',', '.'), number_format($dt['diskon'], '0', ',', '.'), number_format($dt['diskon_free'], '0', ',', '.'), number_format($dt['poin_value'], '0', ',', '.'), number_format($dt['diskon_otomatis'], '0', ',', '.'), number_format(($dt['total'] + $dt['ongkir']) - ($dt['diskon'] + $dt['diskon_free'] + $dt['poin_value'] + $dt['diskon_otomatis']), '0', ',', '.'));
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_penjualan()
	{
		$no_invoice = $_GET['no_invoice'];
		$id_store = $this->model1->id_store_invoice($no_invoice);
		$data['receipt'] = $this->db->get_where("ap_store", array("id_store" => $id_store));
		$data['no_invoice'] = $this->model1->invoice_ket($no_invoice);
		$data['invoice_item'] = $this->model1->invoice_item($no_invoice);

		$idKasir = $this->model1->getIdKasir($no_invoice);

		$data['nama_kasir'] = $this->model1->nama_kasir($idKasir);
		$data['item_barang'] = $this->model1->item_barang_struk($no_invoice);
		$data['qty_barang'] = $this->model1->qty_barang_struk($no_invoice);
		$data['tipe_bayar'] = $this->model1->tipe_bayar_struk($no_invoice);
		$data['dual_payment'] = $this->model1->tipe_bayar_dual($no_invoice);

		$data['tipe_kustomer'] = $this->model1->tipe_kustomer($no_invoice);

		$this->load->library('ciqrcode');

		$qr['data'] = $no_invoice;
		$qr['level'] = 'H';
		$qr['size'] = '10';
		$qr['savename'] = FCPATH . "qr/" . $no_invoice . ".png";
		//$this->ciqrcode->generate($qr);


		$this->global['pageTitle'] = "SOLUSI POS - Invoice Penjualan";

		$this->loadViews("penjualan/body_invoice_penjualan", $this->global, $data, "penjualan/footerInvoicePenjualan");


	}

	function tempo_form()
	{
		$this->load->view("penjualan/jatuh_tempo");
	}

	function use_profil_address()
	{
		$data['provinsi'] = $this->db->get("ae_provinsi");

		$id_customer = $_POST['id'];
		$data['customer'] = $this->db->get_where("ap_customer", array("id_customer" => $id_customer));

		$this->load->view("penjualan/body_use_profil_address", $data);
	}

	function data_customer_poin()
	{
		$idUser = $this->global['idUser'];
		$cekIfPoinExist = $this->model_penjualan->cekIfPoinExist($idUser);

		$data['idUser'] = $this->global['idUser'];

		if ($cekIfPoinExist > 0) {
			$idMember = $this->model_penjualan->getIdMemberDiskon($idUser);
			$id = $idMember;
			$data['customer_poin'] = $this->model1->data_customer_poin($id);
			$data['poinValue'] = $this->model_penjualan->poinValue($idUser);
			$data['kategori_member'] = $this->model_penjualan->cekKategoriMember($id);
			$this->load->view("penjualan/reimbursment_point", $data);
		} else {
			if (!empty($_POST['id'])) {
				$id = $_POST['id'];
				$data['customer_poin'] = $this->model1->data_customer_poin($id);
				$data['kategori_member'] = $this->model_penjualan->cekKategoriMember($id);
				$data['poinValue'] = 0;
				$this->load->view("penjualan/reimbursment_point", $data);
			}
		}
	}

	function data_customer_poinPending()
	{
		$noCart = $_POST['noCart'];
		$cekIfPoinExist = $this->model_penjualan->cekIfPoinExistPending($noCart);
		$data['idUser'] = $this->global['idUser'];

		if ($cekIfPoinExist > 0) {
			$idMember = $this->model_penjualan->getIdMemberDiskonPending($noCart);
			$id = $idMember;
			$data['customer_poin'] = $this->model1->data_customer_poin($id);
			$data['poinValue'] = $this->model_penjualan->poinValuePending($noCart);
			$data['kategori_member'] = $this->model_penjualan->cekKategoriMember($id);
			$this->load->view("penjualan/reimbursment_pointPending", $data);
		} else {
			if (!empty($_POST['id'])) {
				$id = $_POST['id'];
				$data['customer_poin'] = $this->model1->data_customer_poin($id);
				$data['kategori_member'] = $this->model_penjualan->cekKategoriMember($id);
				$data['poinValue'] = 0;
				$this->load->view("penjualan/reimbursment_pointPending", $data);
			}
		}
	}

	function get_max_poin()
	{
		$id_customer = $_POST['id'];

		$max_poin = $this->model1->poin_lama($id_customer);

		echo $max_poin;
	}

	function sub_account()
	{
		$id = $_POST['id'];
		$dual = $_POST['dual'];

		$query = $this->db->get_where("ap_payment_account", array("id_payment_type" => $id));

		$data['sub_account'] = $query;
		$data['dual'] = $dual;

		$rows = $query->num_rows();

		if ($rows > 0) {
			$this->load->view("sub_account", $data);
		}
	}

	function cekStokPerStore()
	{
		$sku = $_POST['sku'];
		$idStore = $this->global['idStore'];
		$qty = $_POST['qty'];
		$id = $_POST['id'];

		$cekStok = $this->model_penjualan->cekStokPerStore($sku, $idStore);

		if ($qty <= $cekStok) {
			echo "StokEnough";
		} else {
			$currentCart = $this->model_penjualan->currentQtyPeritem($id);
			//echo $currentCart;
			echo "StokEnough";
		}
	}

	function ajax_customer()
	{
		$q = $_GET['term'];

		$customer = $this->model1->customer_search($q);

		$data_array = array();

		foreach ($customer->result() as $row) {
			$data_array[] = array(
				"id" => $row->id_customer,
				"text" => $row->nama . " / " . $row->no_kartu
			);
		}

		echo json_encode($data_array);
	}

	function ajax_produk()
	{
		$q = $_GET['term'];

		$id_store = $this->global['idStore'];


		$customer = $this->model_penjualan->produk_search($q, $id_store);

		$data_array = array();

		foreach ($customer->result() as $row) {
			$data_array[] = array(
				"id" => $row->id_produk,
				"text" => $row->id_produk . " / " . $row->nama_produk . " / Rp" . number_format($row->harga, 0, ',', '.') . " Qty: " . $row->stok
			);
		}

		echo json_encode($data_array);
	}

	function cek_diskon()
	{
		$sku = $_POST['sku'];
		$cek_diskon = $this->model1->cek_diskon($sku);

		echo $cek_diskon;
	}

	function ambil_nilai_diskon()
	{
		$sku = $_POST['sku'];
		$qty = $_POST['qty'];

		$idStore = $this->global['idStore'];
		$this->db->order_by('(discount+disc_supplier)', 'ASC');
		$nilaiDiskon = $this->db->get_where("ap_produk_discount_rules", array("id_produk" => $sku, "id_toko" => $idStore, "date_start <= '" . date('Y-m-d') . "'"));
		$countRulesIfExist = $nilaiDiskon->num_rows();

		if ($countRulesIfExist > 0) {
			foreach ($nilaiDiskon->result() as $row) {
				if ($qty >= $row->qty) {
					if (strtotime(date('Y-m-d')) >= strtotime($row->date_start) && strtotime(date('Y-m-d')) <= strtotime($row->date_end)) {
						if ($row->setHari == 1) {
							$HariID = explode(".", $row->HariID);
							if (in_array(date('w'), $HariID)) {
								if ($row->setJam == 1) {
									if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
										$diskon = $row->discount + $row->disc_supplier;
									} else
										$diskon = 0;
								} else
									$diskon = $row->discount + $row->disc_supplier;
							} else
								$diskon = 0;
						} else if ($row->setJam == 1 && $row->setHari != 1) {
							if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
								$diskon = $row->discount + $row->disc_supplier;
							} else
								$diskon = 0;
						} else
							$diskon = $row->discount + $row->disc_supplier;
					} else {
						$diskon = 0;
					}
				}
			}
			return $diskon;

		} else {
			return 0;
		}
	}

	function pendingTrx()
	{
		$idUser = $this->global['idUser'];
		$cekNoPending = $this->model_penjualan->cekNoPending($idUser);

		//generate pending number
		$noPendingTrx = "TN/" . date('ymd') . "/" . sprintf("%02d", $idUser) . "/" . sprintf("%02d", $cekNoPending + 1);

		//insert into pending number table
		$dataPending = array(
			"cartNo" => $noPendingTrx,
			"idUser" => $idUser,
			"tanggal" => date('Y-m-d H:i:s')
		);

		$this->model_penjualan->insertCartTemp($dataPending);

		//insert data to ap_cart_temp
		$viewCart = $this->model_penjualan->dataCart($idUser, $this->global['idStore']);

		foreach ($viewCart as $row) {
			$sku = $row->id_produk;
			$harga = $row->harga;
			$hpp = $row->hpp;
			$qty = $row->qty;
			$diskon_item = $row->diskon;

			$data_item[] = array(
				"id_produk" => $sku,
				"quantity" => $qty,
				"noCart" => $noPendingTrx,
				"harga" => $harga,
				"diskon" => $diskon_item,
				"hpp" => $hpp

			);
		}

		$this->model_penjualan->inserCartTempItem($data_item, $idUser);
	}

	function pendingList()
	{
		$idUser = $this->global['idUser'];
		$data['pendingList'] = $this->db->get_where("ap_cart_temp_no", array("idUser" => $idUser, "status" => 0))->result();
		$this->global['pageTitle'] = "SOLUSI POS - Daftar Penjualan Tunda";
		$this->loadViews("penjualan/daftar_tunggu", $this->global, $data, "footer_empty");
	}

	function hapus_pending()
	{
		$id_pending = $_GET['id_pending'];

		$this->db->delete("ap_order_temp_no", array("id_pending" => $id_pending));
		$this->db->delete("ap_order_temp", array("id_pending" => $id_pending));

		$affect = $this->db->affected_rows();

		if ($affect > 0) {
			$message = "<div class='alert alert-success alert-dismissable'>";
			$message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
			$message .= "Data Berhasil Dihapus";
			$message .= "</div>";

			$this->session->set_flashdata("message", $message);
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
			$message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
			$message .= "Data Gagal Dihapus";
			$message .= "</div>";

			$this->session->set_flashdata("message", $message);
		}

		redirect("penjualan/daftar_tunggu");
	}

	function retur()
	{
		$this->global['pageTitle'] = "SOLUSI POS - Retur Penjualan";
		$this->loadViews("penjualan/body_retur", $this->global, NULL, "footer_empty");
	}

	function retur_search()
	{

		$no_invoice = $_GET['no_invoice'];

		$data['data_invoice'] = $this->model1->invoice_item($no_invoice);


		$data['no_invoice'] = $no_invoice;
		$data['retur_item'] = $this->model1->retur_item_invoice($no_invoice);



		$data['supplier'] = $this->db->get("supplier")->result();
		$this->global['pageTitle'] = "SOLUSI POS - Retur";
		$this->loadViews("penjualan/body_retur_search", $this->global, $data, "penjualan/footerRetur");
	}

	function getDataProduk()
	{


		$sku = $_POST['sku'];
		$harga_jual = $_POST['harga_jual'];
		$idStore = $this->global['idStore'];
		$dataProduk = $this->model_penjualan->getProdukData($sku, $idStore);

		foreach ($dataProduk as $row) {
			$harga = $row->harga;
			if ($harga_jual > 0) {
				$harga = $harga_jual == 2 ? $row->harga2 : $harga;
				$harga = $harga_jual == 3 ? $row->harga3 : $harga;
				$harga = $harga_jual == 4 ? $row->harga4 : $harga;
				$harga = $harga_jual == 5 ? $row->harga5 : $harga;
			}
			//$stok = ;
			$arrayData[] = array(
				"harga" => $harga,
				"stok" => $row->stok,
				"hpp" => $row->hpp,
				"id_produk" => $row->id_produk
			);
		}

		echo json_encode($arrayData);
	}

	function cekDiskon($sku)
	{
		//0 tidak ada diskon
		//1 ada diskon 

		$cekDiskon = $this->model_penjualan->cekDiskon($sku);

		return $cekDiskon;
	}

	function ambilNilaiDiskon($sku, $qty)
	{
		date_default_timezone_set('Asia/Jakarta');
		$idStore = $this->global['idStore'];
		$this->db->order_by('(discount+disc_supplier)', 'ASC');
		$nilaiDiskon = $this->db->get_where("ap_produk_discount_rules", array("id_produk" => $sku, "id_toko" => $idStore, "'" . date('Y-m-d') . "' between date_start and date_end"));
		$countRulesIfExist = $nilaiDiskon->num_rows();
		$diskon = 0;

		if ($countRulesIfExist > 0) {
			foreach ($nilaiDiskon->result() as $row) {
				if ($qty >= $row->qty) {
					if (strtotime(date('Y-m-d')) >= strtotime($row->date_start) && strtotime(date('Y-m-d')) <= strtotime($row->date_end)) {
						// $terjual = $this->model_penjualan->cekQuotaDiskon($sku,$row->date_start,$row->date_end);
						$terjual = $row->quota_terpakai;
						if ($row->quota > 0 and $terjual < $row->quota) {
							if ($row->setHari == 1) {
								$HariID = explode(".", $row->HariID);
								if (in_array(date('w'), $HariID)) {
									if ($row->setJam == 1) {
										if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
											$diskon = $row;
										}
									} else
										$diskon = $row;
								}
							} else if ($row->setJam == 1 && $row->setHari != 1) {
								if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
									$diskon = $row;
								}
							} else
								$diskon = $row;
						} else if ($row->quota == 0) {
							if ($row->setHari == 1) {
								$HariID = explode(".", $row->HariID);
								if (in_array(date('w'), $HariID)) {
									if ($row->setJam == 1) {
										if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
											$diskon = $row;
										}
									} else
										$diskon = $row;
								}
							} else if ($row->setJam == 1 && $row->setHari != 1) {
								if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
									$diskon = $row;
								}
							} else
								$diskon = $row;
						}

					}
				}
			}
			return $diskon;

		} else {
			return 0;
		}
	}

	function diskonSupplier($sku, $qty)
	{
		$idStore = $this->global['idStore'];
		$nilaiDiskon = $this->db->get_where("ap_produk_discount_rules", array("id_produk" => $sku, "id_toko" => $idStore, "'" . date('Y-m-d') . "' between date_start and date_end"));
		$countRulesIfExist = $nilaiDiskon->num_rows();

		if ($countRulesIfExist > 0) {
			foreach ($nilaiDiskon->result() as $row) {
				if ($qty >= $row->qty) {
					if (strtotime(date('Y-m-d')) >= strtotime($row->date_start) && strtotime(date('Y-m-d')) <= strtotime($row->date_end)) {
						// $terjual = $this->model_penjualan->cekQuotaDiskon($sku,$row->date_start,$row->date_end);
						$terjual = $row->quota_terpakai;
						if ($row->quota > 0 and $terjual < $row->quota) {
							if ($row->setHari == 1) {
								$HariID = explode(".", $row->HariID);
								if (in_array(date('w'), $HariID)) {
									if ($row->setJam == 1) {
										if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
											$diskon = $row->disc_supplier;
										} else
											$diskon = 0;
									} else
										$diskon = $row->disc_supplier;
								} else
									$diskon = 0;
							} else if ($row->setJam == 1 && $row->setHari != 1) {
								if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
									$diskon = $row->disc_supplier;
								} else
									$diskon = 0;
							} else
								$diskon = $row->disc_supplier;
						} else if ($row->quota == 0) {
							if ($row->setHari == 1) {
								$HariID = explode(".", $row->HariID);
								if (in_array(date('w'), $HariID)) {
									if ($row->setJam == 1) {
										if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
											$diskon = $row->disc_supplier;
										} else
											$diskon = 0;
									} else
										$diskon = $row->disc_supplier;
								} else
									$diskon = 0;
							} else if ($row->setJam == 1 && $row->setHari != 1) {
								if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
									$diskon = $row->disc_supplier;
								} else
									$diskon = 0;
							} else
								$diskon = $row->disc_supplier;
						} else
							$diskon = 0;
					} else {
						$diskon = 0;
					}
				}
			}
			return $diskon;

		} else {
			return 0;
		}
	}

	function diskonBrand($id_brand)
	{
		// $act = 1 -> Insert, $act = 2 -> Update
		$idStore = $this->global['idStore'];
		$idUser = $this->global['idUser'];
		$id_customer = $this->model_penjualan->getIdMemberDiskon($idUser);
		$nilaiDiskon = $this->db->get_where("ap_promo_brand_rules", array("id_brand" => $id_brand, "id_toko" => $idStore, "'" . date('Y-m-d') . "' between date_start and date_end"));
		$countRulesIfExist = $nilaiDiskon->num_rows();

		if ($countRulesIfExist > 0) {
			foreach ($nilaiDiskon->result() as $row) {
				if ($row->rules_type == 'Count2Price' || $row->rules_type == 'Count2Percent') {
					$sum_cart = $this->model_penjualan->countCartBrand($id_brand, $this->global['idUser']);
				} else if ($row->rules_type == 'Sum2Price' || $row->rules_type == 'Sum2Percent') {
					$sum_cart = $this->model_penjualan->sumCartBrand($id_brand, $this->global['idUser']);
				}

				$sum_cart++;

				if ($sum_cart >= $row->minBelanja) {
					if (strtotime(date('Y-m-d')) >= strtotime($row->date_start) && strtotime(date('Y-m-d')) <= strtotime($row->date_end)) {
						if ($row->setHari == 1) {
							$HariID = explode(".", $row->HariID);
							if (in_array(date('w'), $HariID)) {
								if ($row->setJam == 1) {
									if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
										if ($row->rules_type == 'Count2Price' || $row->rules_type == 'Sum2Price') {
											$diskon = array();
											$diskon['tipe'] = 'price';
											if ($row->khusus_member == 1) {
												if (!empty($id_customer)) {
													$diskon['nilai'] = $this->model_penjualan->updateCartBrandPrice($id_brand, $row->discount, $this->global['idUser']);
												}
											} else {
												$diskon['nilai'] = $this->model_penjualan->updateCartBrandPrice($id_brand, $row->discount, $this->global['idUser']);
											}
										} else if ($row->rules_type == 'Count2Percent' || $row->rules_type == 'Sum2Percent') {
											$diskon = array();
											$diskon['tipe'] = 'percent';
											if ($row->khusus_member == 1) {
												if (!empty($id_customer)) {
													$diskon['nilai'] = $this->model_penjualan->updateCartBrandPercent($id_brand, $row->discount, $this->global['idUser']);
												}
											} else {
												$diskon['nilai'] = $this->model_penjualan->updateCartBrandPercent($id_brand, $row->discount, $this->global['idUser']);
											}
										}
									} else
										$diskon = false;
								} else {
									if ($row->rules_type == 'Count2Price' || $row->rules_type == 'Sum2Price') {
										$diskon = array();
										$diskon['tipe'] = 'price';
										if ($row->khusus_member == 1) {
											if (!empty($id_customer)) {
												$diskon['nilai'] = $this->model_penjualan->updateCartBrandPrice($id_brand, $row->discount, $this->global['idUser']);
											}
										} else {
											$diskon['nilai'] = $this->model_penjualan->updateCartBrandPrice($id_brand, $row->discount, $this->global['idUser']);
										}
									} else if ($row->rules_type == 'Count2Percent' || $row->rules_type == 'Sum2Percent') {
										$diskon = array();
										$diskon['tipe'] = 'percent';
										if ($row->khusus_member == 1) {
											if (!empty($id_customer)) {
												$diskon['nilai'] = $this->model_penjualan->updateCartBrandPercent($id_brand, $row->discount, $this->global['idUser']);
											}
										} else {
											$diskon['nilai'] = $this->model_penjualan->updateCartBrandPercent($id_brand, $row->discount, $this->global['idUser']);
										}
									}
								}
							} else
								$diskon = false;
						} else if ($row->setJam == 1 && $row->setHari != 1) {
							if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
								if ($row->rules_type == 'Count2Price' || $row->rules_type == 'Sum2Price') {
									$diskon = array();
									$diskon['tipe'] = 'price';
									if ($row->khusus_member == 1) {
										if (!empty($id_customer)) {
											$diskon['nilai'] = $this->model_penjualan->updateCartBrandPrice($id_brand, $row->discount, $this->global['idUser']);
										}
									} else {
										$diskon['nilai'] = $this->model_penjualan->updateCartBrandPrice($id_brand, $row->discount, $this->global['idUser']);
									}
								} else if ($row->rules_type == 'Count2Percent' || $row->rules_type == 'Sum2Percent') {
									$diskon = array();
									$diskon['tipe'] = 'percent';
									if ($row->khusus_member == 1) {
										if (!empty($id_customer)) {
											$diskon['nilai'] = $this->model_penjualan->updateCartBrandPercent($id_brand, $row->discount, $this->global['idUser']);
										}
									} else {
										$diskon['nilai'] = $this->model_penjualan->updateCartBrandPercent($id_brand, $row->discount, $this->global['idUser']);
									}
								}
							} else
								$diskon = false;
						} else {
							if ($row->rules_type == 'Count2Price' || $row->rules_type == 'Sum2Price') {
								$diskon = array();
								$diskon['tipe'] = 'price';
								if ($row->khusus_member == 1) {
									if (!empty($id_customer)) {
										$diskon['nilai'] = $this->model_penjualan->updateCartBrandPrice($id_brand, $row->discount, $this->global['idUser']);
									}
								} else {
									$diskon['nilai'] = $this->model_penjualan->updateCartBrandPrice($id_brand, $row->discount, $this->global['idUser']);
								}
							} else if ($row->rules_type == 'Count2Percent' || $row->rules_type == 'Sum2Percent') {
								$diskon = array();
								$diskon['tipe'] = 'percent';
								if ($row->khusus_member == 1) {
									if (!empty($id_customer)) {
										$diskon['nilai'] = $this->model_penjualan->updateCartBrandPercent($id_brand, $row->discount, $this->global['idUser']);
									}
								} else {
									$diskon['nilai'] = $this->model_penjualan->updateCartBrandPercent($id_brand, $row->discount, $this->global['idUser']);
								}
							}
						}

					} else {
						$diskon = false;
					}
				}
			}
			return $diskon;
		} else {
			return false;
		}
	}

	function diskonKategori($id_kategori)
	{
		// $act = 1 -> Insert, $act = 2 -> Update
		$idStore = $this->global['idStore'];
		$idUser = $this->global['idUser'];
		$id_customer = $this->model_penjualan->getIdMemberDiskon($idUser);
		$nilaiDiskon = $this->db->get_where("ap_promo_kategori_rules", array("id_kategori" => $id_kategori, "id_toko" => $idStore, "'" . date('Y-m-d') . "' between date_start and date_end"));
		$countRulesIfExist = $nilaiDiskon->num_rows();

		if ($countRulesIfExist > 0) {
			foreach ($nilaiDiskon->result() as $row) {
				if ($row->rules_type == 'Count2Price' || $row->rules_type == 'Count2Percent') {
					$sum_cart = $this->model_penjualan->countCartKategori($id_kategori, $this->global['idUser']);
				} else if ($row->rules_type == 'Sum2Price' || $row->rules_type == 'Sum2Percent') {
					$sum_cart = $this->model_penjualan->sumCartKategori($id_kategori, $this->global['idUser']);
				}

				$sum_cart++;

				if ($sum_cart >= $row->minBelanja) {
					if (strtotime(date('Y-m-d')) >= strtotime($row->date_start) && strtotime(date('Y-m-d')) <= strtotime($row->date_end)) {
						if ($row->setHari == 1) {
							$HariID = explode(".", $row->HariID);
							if (in_array(date('w'), $HariID)) {
								if ($row->setJam == 1) {
									if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
										if ($row->rules_type == 'Count2Price' || $row->rules_type == 'Sum2Price') {
											$diskon = array();
											$diskon['tipe'] = 'price';
											if ($row->khusus_member == 1) {
												if (!empty($id_customer)) {
													$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPrice($id_kategori, $row->discount, $this->global['idUser']);
												}
											} else {
												$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPrice($id_kategori, $row->discount, $this->global['idUser']);
											}
										} else if ($row->rules_type == 'Count2Percent' || $row->rules_type == 'Sum2Percent') {
											$diskon = array();
											$diskon['tipe'] = 'percent';
											if ($row->khusus_member == 1) {
												if (!empty($id_customer)) {
													$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPercent($id_kategori, $row->discount, $this->global['idUser']);
												}
											} else {
												$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPercent($id_kategori, $row->discount, $this->global['idUser']);
											}
										}
									} else
										$diskon = false;
								} else {
									if ($row->rules_type == 'Count2Price' || $row->rules_type == 'Sum2Price') {
										$diskon = array();
										$diskon['tipe'] = 'price';
										if ($row->khusus_member == 1) {
											if (!empty($id_customer)) {
												$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPrice($id_kategori, $row->discount, $this->global['idUser']);
											}
										} else {
											$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPrice($id_kategori, $row->discount, $this->global['idUser']);
										}
									} else if ($row->rules_type == 'Count2Percent' || $row->rules_type == 'Sum2Percent') {
										$diskon = array();
										$diskon['tipe'] = 'percent';
										if ($row->khusus_member == 1) {
											if (!empty($id_customer)) {
												$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPercent($id_kategori, $row->discount, $this->global['idUser']);
											}
										} else {
											$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPercent($id_kategori, $row->discount, $this->global['idUser']);
										}
									}
								}
							} else
								$diskon = false;
						} else if ($row->setJam == 1 && $row->setHari != 1) {
							if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
								if ($row->rules_type == 'Count2Price' || $row->rules_type == 'Sum2Price') {
									$diskon = array();
									$diskon['tipe'] = 'price';
									if ($row->khusus_member == 1) {
										if (!empty($id_customer)) {
											$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPrice($id_kategori, $row->discount, $this->global['idUser']);
										}
									} else {
										$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPrice($id_kategori, $row->discount, $this->global['idUser']);
									}
								} else if ($row->rules_type == 'Count2Percent' || $row->rules_type == 'Sum2Percent') {
									$diskon = array();
									$diskon['tipe'] = 'percent';
									if ($row->khusus_member == 1) {
										if (!empty($id_customer)) {
											$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPercent($id_kategori, $row->discount, $this->global['idUser']);
										}
									} else {
										$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPercent($id_kategori, $row->discount, $this->global['idUser']);
									}
								}
							} else
								$diskon = false;
						} else {
							if ($row->rules_type == 'Count2Price' || $row->rules_type == 'Sum2Price') {
								$diskon = array();
								$diskon['tipe'] = 'price';
								if ($row->khusus_member == 1) {
									if (!empty($id_customer)) {
										$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPrice($id_kategori, $row->discount, $this->global['idUser']);
									}
								} else {
									$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPrice($id_kategori, $row->discount, $this->global['idUser']);
								}
							} else if ($row->rules_type == 'Count2Percent' || $row->rules_type == 'Sum2Percent') {
								$diskon = array();
								$diskon['tipe'] = 'percent';
								if ($row->khusus_member == 1) {
									if (!empty($id_customer)) {
										$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPercent($id_kategori, $row->discount, $this->global['idUser']);
									}
								} else {
									$diskon['nilai'] = $this->model_penjualan->updateCartKategoriPercent($id_kategori, $row->discount, $this->global['idUser']);
								}
							}
						}

					} else {
						$diskon = false;
					}
				}
			}
			return $diskon;
		} else {
			return false;
		}
	}

	function insertCart()
	{
		$sku = $_POST['sku'];
		$harga = $_POST['harga'];
		$qty = $_POST['qty'];
		$hpp = $_POST['hpp'];
		$idUser = $this->global['idUser'];
		$idStore = $this->global['idStore'];
		$id_brand = $this->model_penjualan->cekIdBrand($sku);
		$id_kategori = $this->model_penjualan->cekIdKategori($sku);

		// Diskon Brand
		$cekDiskonBrand = $this->diskonBrand($id_brand);


		$nilaiDiskon = 0;
		if ($cekDiskonBrand) {
			if ($cekDiskonBrand['tipe'] == 'percent') {
				$nilaiDiskon = ($cekDiskonBrand['nilai'] * $harga) * $qty;
			} else
				$nilaiDiskon = 0;

			$cekDiskon = 0;
		} else
			$cekDiskon = $this->cekDiskon($sku);

		// Diskon Kategori
		$cekDiskonKategori = $this->diskonKategori($id_kategori);
		if ($cekDiskonKategori && $nilaiDiskon == 0) {
			if ($cekDiskonKategori['tipe'] == 'percent') {
				$nilaiDiskon = ($cekDiskonKategori['nilai'] * $harga) * $qty;
			} else
				$nilaiDiskon = 0;

			$cekDiskon = 0;
		} else
			$cekDiskon = $this->cekDiskon($sku);



		$disc_supplier = 0;
		$disc_tebusmurah = 0;
		$no_promo = '';
		if ($cekDiskon == 1 || $nilaiDiskon == 0) {
			$cekTebusMurah = $this->model_penjualan->cekTebusMurah($sku); //

			if ($cekTebusMurah == $sku) {
				$nilai = $this->ambilNilaiDiskon($sku, $qty);
				if (is_object($nilai)) {
					$nilaiDiskon = ($nilai->discount + $nilai->disc_supplier) * $qty;
					$disc_supplier = $nilai->disc_supplier * $qty;
					$no_promo = $nilai->no_promo;
				} else
					$nilaiDiskon = $nilai * $qty;

				//echo $nilaiDiskon;
				if ($nilaiDiskon == 0) {
					$tm = $this->diskonTebusMurah($sku, $qty);
					$nilaiDiskon = $tm['diskon'];
					$disc_tebusmurah = $tm['diskon'];
					if ($tm['diskon'] > 0 && trim((string) ($tm['no_promo'] ?? '')) !== '') {
						$no_promo = $tm['no_promo'];
					}
				}

				// $nilaiDiskon = $this->diskonTebusMurah($sku,$qty);
				// $disc_tebusmurah = $nilaiDiskon;
				// //echo $nilaiDiskon;
				// if ($nilaiDiskon==0){
				// 	$nilai = $this->ambilNilaiDiskon($sku,$qty);
				// 	if (is_object($nilai)){
				// 		$nilaiDiskon = ($nilai->discount + $nilai->disc_supplier)*$qty;
				// 		$disc_supplier = $nilai->disc_supplier*$qty;
				// 	}else $nilaiDiskon = $nilai*$qty;
				// }
			} else {
				$nilai = $this->ambilNilaiDiskon($sku, $qty);
				if (is_object($nilai)) {
					$nilaiDiskon = ($nilai->discount + $nilai->disc_supplier) * $qty;
					$disc_supplier = $nilai->disc_supplier * $qty;
					$no_promo = $nilai->no_promo;
				} else
					$nilaiDiskon = $nilai * $qty;
			}
		}

		//cek if data exist on cart
		$cekDataCart = $this->model_penjualan->cekCartIfExist($sku, $idUser);
		//MAX STOK ON CART
		//$maxQTY = $this->model_penjualan->cekStokPerStore($sku,$idStore);
		$maxQTY = 1001;


		$qtyCart = $this->model_penjualan->cekQtyCart($sku, $idUser);
		$diskonBefore = $this->model_penjualan->cekDiskonBefore($sku, $idUser);
		$diskonSupplierBefore = $this->model_penjualan->cekDiskonSupplierBefore($sku, $idUser) + 0;
		$tebusmurahBefore = $this->model_penjualan->tebusmurahBefore($sku, $idUser) + 0;

		if ($cekDataCart > 0) {

			$qtyAdd = $qtyCart + 1;

			if ($qtyAdd > $maxQTY) {
				echo 1;
			} else {
				//delete first
				$this->model_penjualan->hapusCart($sku, $idUser);

				$dataCart = array(
					"id_produk" => $sku,
					"quantity" => $qtyCart + 1,
					"id_user" => $idUser,
					"harga" => $harga,
					"hpp" => $hpp,
					"diskon" => $diskonBefore + $nilaiDiskon,
					"disc_supplier" => $diskonSupplierBefore + $disc_supplier,
					"tebusmurah" => $tebusmurahBefore + $disc_tebusmurah,
					"no_promo" => $no_promo
				);

				$this->model_penjualan->insertCart($dataCart);
				echo 1;
			}

		} else {
			$dataCart = array(
				"id_produk" => $sku,
				"quantity" => $qty,
				"id_user" => $idUser,
				"harga" => $harga,
				"hpp" => $hpp,
				"diskon" => $nilaiDiskon,
				"disc_supplier" => $disc_supplier,
				"tebusmurah" => $disc_tebusmurah,
				"no_promo" => $no_promo
			);

			$this->model_penjualan->insertCart($dataCart);
			echo 2;
		}
	}

	function insertCartPending()
	{
		$sku = $_POST['sku'];
		$harga = $_POST['harga'];
		$qty = $_POST['qty'];
		$hpp = $_POST['hpp'];

		$idUser = $this->global['idUser'];
		$idStore = $this->global['idStore'];

		$noCart = $_POST['noCart'];

		$cekDiskon = $this->cekDiskon($sku);

		$disc_supplier = 0;
		$disc_tebusmurah = 0;
		if ($cekDiskon == 1) {
			$cekTebusMurah = $this->model_penjualan->cekTebusMurah($sku); //

			if ($cekTebusMurah == $sku) {
				$nilai = $this->ambilNilaiDiskon($sku, $qty);

				if (is_object($nilai)) {
					$nilaiDiskon = ($nilai->discount + $nilai->disc_supplier) * $qty;
					$disc_supplier = $nilai->disc_supplier * $qty;
				} else
					$nilaiDiskon = $nilai * $qty;


				if ($nilaiDiskon == 0) {
					$nilaiDiskon = $this->diskonTebusMurahTemp($sku, $qty, $noCart);
					$disc_tebusmurah = $nilaiDiskon;
				}
				// $nilaiDiskon = $this->diskonTebusMurahTemp($sku,$qty,$noCart);
				// $disc_tebusmurah = $nilaiDiskon;
				// if ($nilaiDiskon==0){
				// 	$nilai = $this->ambilNilaiDiskon($sku,$qty);
				// 	if (is_object($nilai)){
				// 		$nilaiDiskon = ($nilai->discount + $nilai->disc_supplier)*$qty;
				// 		$disc_supplier = $nilai->disc_supplier*$qty;
				// 	}else $nilaiDiskon = $nilai*$qty;
				// }
				//echo $nilaiDiskon;
			} else {
				$nilai = $this->ambilNilaiDiskon($sku, $qty);
				if (is_object($nilai)) {
					$nilaiDiskon = ($nilai->discount + $nilai->disc_supplier) * $qty;
					$disc_supplier = $nilai->disc_supplier * $qty;
				} else
					$nilaiDiskon = $nilai * $qty;
			}
		} else {
			$nilaiDiskon = 0;
		}

		//cek if data exist on cart


		$cekDataCart = $this->model_penjualan->cekCartIfExistPending($sku, $noCart);

		if ($cekDataCart > 0) {
			$qtyCart = $this->model_penjualan->cekQtyCartPending($sku, $noCart);
			$diskonBefore = $this->model_penjualan->cekDiskonBeforePending($sku, $noCart);
			$diskonSupplierBefore = $this->model_penjualan->cekDiskonSupplierBeforePending($sku, $noCart);

			$dataCartUpdate = array(
				"quantity" => $qtyCart + 1,
				"diskon" => $diskonBefore + $nilaiDiskon,
				"disc_supplier" => $diskonSupplierBefore + $disc_supplier
			);

			$this->model_penjualan->updateCartPendingTemp($noCart, $sku, $dataCartUpdate);
		} else {
			$dataCart = array(
				"id_produk" => $sku,
				"quantity" => $qty,
				"noCart" => $noCart,
				"harga" => $harga,
				"hpp" => $hpp,
				"diskon" => $nilaiDiskon,
				"disc_supplier" => $disc_supplier
			);

			$this->model_penjualan->insertCartPendingTemp($dataCart);
		}
	}

	function diskonTebusMurah($sku, $qty)
	{
		$idUser = $this->global['idUser'];
		$cekSales = $this->model_penjualan->cekSales($idUser, $sku) + 0;
		$cekQty = $qty;
		//echo $cekSales;
		$cekEligibility = $this->db->get_where("ap_produk_tebusmurah_rules", array(
			"id_produk" => $sku,
			"nominal_belanja <= '$cekSales' and '" . date('Y-m-d') . "' between date_start and date_end"
		));

		$countRulesIfExist = $cekEligibility->num_rows();
		//echo $countRulesIfExist;
		if ($countRulesIfExist > 0) {
			$diskon = 0;
			foreach ($cekEligibility->result() as $row) {
				$kelipatan = floor($cekSales / $row->nominal_belanja);
				$maksimum = $kelipatan * $row->max_qty;
				if ($row->nominal_belanja <= $cekSales && strtotime(date('Y-m-d')) >= strtotime($row->date_start) && strtotime(date('Y-m-d')) <= strtotime($row->date_end)) {
					// $terjual = $this->model_penjualan->cekQuotaDiskon($sku,$row->date_start,$row->date_end);
					$terjual = $row->quota_terpakai;
					if ($row->quota > 0 and $terjual < $row->quota) {
						if ($row->setHari == 1) {
							$HariID = explode(".", $row->HariID);
							if (in_array(date('w'), $HariID)) {
								if ($row->setJam == 1) {
									if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
										$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
									} else
										$diskon = 0;
								} else
									$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
							} else
								$diskon = 0;
						} else if ($row->setJam == 1 && $row->setHari != 1) {
							if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
								$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
							} else
								$diskon = 0;
						} else
							$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
					} else if ($row->quota == 0) {
						if ($row->setHari == 1) {
							$HariID = explode(".", $row->HariID);
							if (in_array(date('w'), $HariID)) {
								if ($row->setJam == 1) {
									if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
										$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
									} else
										$diskon = 0;
								} else
									$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
							} else
								$diskon = 0;
						} else if ($row->setJam == 1 && $row->setHari != 1) {
							if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
								$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
							} else
								$diskon = 0;
						} else
							$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
					} else
						$diskon = 0;
				} else {
					$diskon = 0;
				}
				$no_promo = '';
				if ($diskon > 0 && isset($row) && isset($row->no_promo)) {
					$no_promo = $row->no_promo;
				}
			}
			
			return array('diskon' => (float) $diskon, 'no_promo' => $no_promo);

		} else {
			return array('diskon' => 0.0, 'no_promo' => '');
		}
	}
	function diskonTebusMurahTemp($sku, $qty, $noCart)
	{
		$idUser = $this->global['idUser'];
		$cekSales = $this->model_penjualan->cekSalesTemp($noCart, $sku) + 0;
		$cekQty = $qty;
		//echo $cekSales;
		$cekEligibility = $this->db->get_where("ap_produk_tebusmurah_rules", array(
			"id_produk" => $sku,
			"date_start <= '" . date('Y-m-d') . "'"
		));

		$countRulesIfExist = $cekEligibility->num_rows();
		//echo $countRulesIfExist;
		if ($countRulesIfExist > 0) {
			foreach ($cekEligibility->result() as $row) {
				$kelipatan = floor($cekSales / $row->nominal_belanja);
				$maksimum = $kelipatan * $row->max_qty;
				if ($row->nominal_belanja <= $cekSales && strtotime(date('Y-m-d')) >= strtotime($row->date_start) && strtotime(date('Y-m-d')) <= strtotime($row->date_end)) {
					// $terjual = $this->model_penjualan->cekQuotaDiskon($sku,$row->date_start,$row->date_end);
					$terjual = $row->quota_terpakai;
					if ($row->quota > 0 and $terjual < $row->quota) {
						if ($row->setHari == 1) {
							$HariID = explode(".", $row->HariID);
							if (in_array(date('w'), $HariID)) {
								if ($row->setJam == 1) {
									if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
										$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
									} else
										$diskon = 0;
								} else
									$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
							} else
								$diskon = 0;
						} else if ($row->setJam == 1 && $row->setHari != 1) {
							if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
								$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
							} else
								$diskon = 0;
						} else
							$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
					} else if ($row->quota == 0) {
						if ($row->setHari == 1) {
							$HariID = explode(".", $row->HariID);
							if (in_array(date('w'), $HariID)) {
								if ($row->setJam == 1) {
									if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
										$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
									} else
										$diskon = 0;
								} else
									$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
							} else
								$diskon = 0;
						} else if ($row->setJam == 1 && $row->setHari != 1) {
							if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
								$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
							} else
								$diskon = 0;
						} else
							$diskon = ($cekQty > $maksimum) ? $maksimum * $row->discount : $cekQty * $row->discount;
					} else
						$diskon = 0;
				} else {
					$diskon = 0;
				}
			}
			return $diskon;

		} else {
			return 0;
		}
	}

	function viewCart()
	{
		$idUser = $this->global['idUser'];
		$idMember = $this->model_penjualan->getIdMemberDiskon($idUser);
		$id = $idMember;
		$data['kategori_member'] = $this->model_penjualan->cekKategoriMember($id);
		$data['dataCart'] = $this->model_penjualan->dataCart($idUser, $this->global['idStore']);
		$data['idUser'] = $idUser;
		$this->load->view("penjualan/viewCart", $data);
	}

	function currentQTYPeritem()
	{
		$id = $_POST['id'];
		$qty = $this->model_penjualan->currentQtyPeritem($id);
		echo $qty;
	}

	function viewCartPending()
	{
		$idpending = $_POST['noCart'];
		$data['dataCart'] = $this->model_penjualan->dataCartPending($idpending);
		$data['idpending'] = $idpending;
		$idMember = $this->model_penjualan->getIdMemberDiskonPending($idpending);
		$id = $idMember;
		$data['kategori_member'] = $this->model_penjualan->cekKategoriMember($id);
		$data['idUser'] = $this->global['idUser'];
		$this->load->view("penjualan/viewCartPending", $data);
	}

	function verifyApproval()
	{
		$user = $_POST['user'];
		$password = $_POST['pw'];
		$setuju = $this->model_penjualan->verifyApproval($user, $password);
		echo json_encode($setuju);
	}

	function updateDiskon()
	{
		$sku = $_POST['idProduk'];
		$diskon = $_POST['diskon'];
		$idUser = $this->global['idUser'];

		$ifPercent = strripos($diskon, "%");
		$totalPurchase = $this->model_penjualan->totalByRow($idUser, $sku);

		//jika terdapat tanda persen maka konversi ke nilai persen
		if ($ifPercent > 0) {
			$intDiskon = str_replace('%', '', $diskon);
			$diskon = ($intDiskon / 100) * $totalPurchase;

			$dataUpdate = array(
				"diskon" => $diskon
			);

			$this->model_penjualan->updateDiskon($sku, $idUser, $dataUpdate);
		} else {
			if ($diskon < 10) {
				$diskon = ($diskon / 100) * $totalPurchase;
			}
			$dataUpdate = array(
				"diskon" => $diskon
			);

			$this->model_penjualan->updateDiskon($sku, $idUser, $dataUpdate);
		}

		//harga on cart
		$id = $_POST['id'];
		$dataProduk = $this->model_penjualan->hargaOnCart($id);

		foreach ($dataProduk as $row) {
			$arrayData[] = array(
				"harga" => $row->harga,
				"diskon" => $row->diskon,
				"qty" => $row->qty
			);
		}

		echo json_encode($arrayData);
	}

	function updateQtyCart()
	{
		//error_reporting(0);ini_set('display_errors',0);
		$sku = $_POST['idProduk'];
		$qty = $_POST['qty'];
		$idUser = $this->global['idUser'];
		$harga = $this->model_penjualan->cekHarga($sku, $idUser);
		$id_brand = $this->model_penjualan->cekIdBrand($sku);
		$id_kategori = $this->model_penjualan->cekIdKategori($sku);

		//cek diskon
		$cekDiskonBrand = $this->diskonBrand($id_brand);
		$nilaiDiskon = 0;
		if ($cekDiskonBrand) {
			if ($cekDiskonBrand['tipe'] == 'percent') {
				$nilaiDiskon = ($cekDiskonBrand['nilai'] * $harga) * $qty;
			} else
				$nilaiDiskon = 0;
			$cekDiskon = 0;
		} else
			$cekDiskon = $this->cekDiskon($sku);

		// Diskon Kategori
		$cekDiskonKategori = $this->diskonKategori($id_kategori);
		if ($cekDiskonKategori && $nilaiDiskon == 0) {
			if ($cekDiskonKategori['tipe'] == 'percent') {
				$nilaiDiskon = ($cekDiskonKategori['nilai'] * $harga) * $qty;
			} else
				$nilaiDiskon = 0;

			$cekDiskon = 0;
		} else
			$cekDiskon = $this->cekDiskon($sku);

		$disc_supplier = 0;
		$disc_tebusmurah = 0;
		$no_promo = '';
		if ($cekDiskon == 1 && $nilaiDiskon == 0) {
			$cekTebusMurah = $this->model_penjualan->cekTebusMurah($sku); //

			if ($cekTebusMurah == $sku) {
				$tm = $this->diskonTebusMurah($sku, $qty);
				$nilaiDiskon = $tm['diskon'];
				$disc_tebusmurah = $tm['diskon'];
				if ($tm['diskon'] > 0 && trim((string) ($tm['no_promo'] ?? '')) !== '') {
					$no_promo = $tm['no_promo'];
				}
				if ($nilaiDiskon == 0) {
					$nilai = $this->ambilNilaiDiskon($sku, $qty);
					if (is_object($nilai)) {
						$nilaiDiskon = ($nilai->discount + $nilai->disc_supplier) * $qty;
						$disc_supplier = $nilai->disc_supplier * $qty;
						$no_promo = $nilai->no_promo;
					} else
						$nilaiDiskon = $nilai * $qty;
				}
				//echo $nilaiDiskon;
			} else {
				$nilai = $this->ambilNilaiDiskon($sku, $qty);
				if (is_object($nilai)) {
					$nilaiDiskon = ($nilai->discount + $nilai->disc_supplier) * $qty;
					$disc_supplier = $nilai->disc_supplier * $qty;
					$no_promo = $nilai->no_promo;
				} else
					$nilaiDiskon = $nilai * $qty;
			}
		}

		if ($disc_tebusmurah > 0) {
			$dataUpdate = array(
				"quantity" => $qty,
				"diskon" => $nilaiDiskon,
				"tebusmurah" => $disc_tebusmurah,
				"no_promo" => $no_promo
			);
		} else {
			$dataUpdate = array(
				"diskon" => $nilaiDiskon,
				"quantity" => $qty,
				"disc_supplier" => $disc_supplier,
				"no_promo" => $no_promo
			);
		}


		$this->model_penjualan->updateQtyCart($sku, $idUser, $dataUpdate);

		//harga on cart
		$id = $_POST['id'];
		$dataProduk = $this->model_penjualan->hargaOnCart($id);

		foreach ($dataProduk as $row) {
			$arrayData[] = array(
				"harga" => $row->harga,
				"diskon" => $row->diskon
			);
		}

		echo json_encode($arrayData);
	}

	function updateQtyCartPending()
	{
		$sku = $_POST['idProduk'];
		$qty = $_POST['qty'];
		$noCart = $_POST['noCart'];

		//cek diskon
		$cekDiskon = $this->cekDiskon($sku);

		$disc_supplier = 0;
		$disc_tebusmurah = 0;
		if ($cekDiskon == 1) {
			$cekTebusMurah = $this->model_penjualan->cekTebusMurah($sku); //

			if ($cekTebusMurah == $sku) {
				$nilaiDiskon = $this->diskonTebusMurahTemp($sku, $qty, $noCart);
				$disc_tebusmurah = $nilaiDiskon;
				if ($nilaiDiskon == 0) {
					$nilai = $this->ambilNilaiDiskon($sku, $qty);
					if (is_object($nilai)) {
						$nilaiDiskon = ($nilai->discount + $nilai->disc_supplier) * $qty;
						$disc_supplier = $nilai->disc_supplier * $qty;
					} else
						$nilaiDiskon = $nilai * $qty;
				}
			} else {
				$nilai = $this->ambilNilaiDiskon($sku, $qty);
				if (is_object($nilai)) {
					$nilaiDiskon = ($nilai->discount + $nilai->disc_supplier) * $qty;
					$disc_supplier = $nilai->disc_supplier * $qty;
				} else
					$nilaiDiskon = $nilai * $qty;
			}
		} else {
			$nilaiDiskon = 0;
		}
		if ($disc_tebusmurah > 0) {
			$dataUpdate = array(
				"quantity" => $qty,
				"diskon" => $nilaiDiskon,
				"tebusmurah" => $disc_tebusmurah
			);
		} else {
			$dataUpdate = array(
				"diskon" => $nilaiDiskon,
				"quantity" => $qty,
				"disc_supplier" => $disc_supplier
			);
		}

		$this->model_penjualan->updateQtyCartPending($sku, $noCart, $dataUpdate);

		//harga on cart
		$id = $_POST['id'];
		$dataProduk = $this->model_penjualan->hargaOnCartTemp($id);

		foreach ($dataProduk as $row) {
			$arrayData[] = array(
				"harga" => $row->harga,
				"diskon" => $row->diskon
			);
		}

		echo json_encode($arrayData);

	}

	function insertDiskonPending()
	{
		$diskon = $_POST['diskon'];
		$noCart = $_POST['noCart'];

		//cek diskon
		$cekIfDiskonExist = $this->model_penjualan->cekIfDiskonExistPending($noCart);

		if ($cekIfDiskonExist > 0) {
			$dataUpdate = array(
				"diskon" => $diskon
			);

			$this->model_penjualan->updateDiskonPending($noCart, $dataUpdate);
		} else {
			$dataInsert = array(
				"noCart" => $noCart,
				"diskon" => $diskon
			);

			$this->model_penjualan->insertDiskonPending($dataInsert);
		}
	}

	function insertDiskon()
	{
		$diskon = $_POST['diskon'];
		$idUser = $this->global['idUser'];

		//cek diskon
		$cekIfDiskonExist = $this->model_penjualan->cekIfDiskonExist($idUser);
		$totalPurchase = $this->model_penjualan->totalPurchase($idUser);
		$diskonPeritem = $this->model_penjualan->diskonPeritemPanel($idUser);

		$ifPercent = strripos($diskon, "%");

		if ($cekIfDiskonExist > 0) {
			$dataUpdate = array(
				"diskon" => $diskon
			);

			$this->model_penjualan->updateCartDiskon($idUser, $dataUpdate);
		} else {
			$dataInsert = array(
				"idUser" => $idUser,
				"diskon" => $diskon
			);

			$this->model_penjualan->insertCartDiskon($dataInsert);
		}
	}





	function updateDiskonPending()
	{
		$sku = $_POST['idProduk'];
		$diskon = $_POST['diskon'];
		$noCart = $_POST['noCart'];

		$ifPercent = strripos($diskon, "%");
		$totalPurchase = $this->model_penjualan->totalByRowTemp($noCart, $sku);

		if ($ifPercent > 0) {
			$intDiskon = str_replace('%', '', $diskon);
			$diskon = ($intDiskon / 100) * $totalPurchase;

			$dataUpdate = array(
				"diskon" => $diskon
			);

			$this->model_penjualan->updateCartDiskonPending($sku, $noCart, $dataUpdate);
		} else {
			$dataUpdate = array(
				"diskon" => $diskon
			);

			$this->model_penjualan->updateCartDiskonPending($sku, $noCart, $dataUpdate);
		}

		//harga on cart
		$id = $_POST['id'];
		$dataProduk = $this->model_penjualan->hargaOnCartTemp($id);

		foreach ($dataProduk as $row) {
			$arrayData[] = array(
				"harga" => $row->harga,
				"diskon" => $row->diskon,
				"qty" => $row->qty
			);
		}

		echo json_encode($arrayData);
	}

	function totalPurchase()
	{
		$idUser = $this->global['idUser'];
		$totalPurchase = $this->model_penjualan->totalPurchase($idUser);

		echo number_format($totalPurchase, '0', ',', '.');
	}

	function totalPurchasePending()
	{
		$idPending = $_POST['noCart'];
		$totalPurchase = $this->model_penjualan->totalPurchasePending($idPending);

		echo number_format($totalPurchase, '0', ',', '.');
	}

	function diskonPeritemPanel()
	{
		$idUser = $this->global['idUser'];
		$diskonPeritemPanel = $this->model_penjualan->diskonPeritemPanel($idUser);

		if ($diskonPeritemPanel > 0) {
			$msg = "<td><i class='fa fa-bullhorn'></i> Diskon Peritem</td>";
			$msg .= "<td align='right'>" . number_format($diskonPeritemPanel, '0', ',', '.') . "</td>";
			echo $msg;
		}
	}

	function diskonPeritemPanelPending()
	{
		$idPending = $_POST['noCart'];
		$diskonPeritemPanel = $this->model_penjualan->diskonPeritemPanelPending($idPending);

		if ($diskonPeritemPanel > 0) {
			$msg = "<td><i class='fa fa-bullhorn'></i> Diskon Peritem</td>";
			$msg .= "<td align='right'>" . number_format($diskonPeritemPanel, '0', ',', '.') . "</td>";
			echo $msg;
		}
	}

	function hapusCart()
	{
		$idProduk = $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$this->model_penjualan->hapusCart($idProduk, $idUser);
	}

	function hapusCartPending()
	{
		$idProduk = $_POST['idProduk'];
		$noCart = $_POST['noCart'];

		$this->model_penjualan->hapusCartPending($idProduk, $noCart);
	}

	function saveDiskonMember()
	{
		$totalDiskon = $_POST['totalDiskon'];
		$idUser = $this->global['idUser'];
		$idCustomer = $_POST['idCustomer'];

		$dataDiskon = array(
			"idUser" => $idUser,
			"idMember" => $idCustomer,
			"diskon" => $totalDiskon
		);

		$this->model_penjualan->saveDiskonMember($dataDiskon);
	}

	function saveDiskonMemberPending()
	{
		$totalDiskon = $_POST['totalDiskon'];
		$noCart = $_POST['noCart'];
		$idCustomer = $_POST['idCustomer'];

		$cek = $this->model_penjualan->cekNoCartTempIfDuplicate($noCart);
		if ($cek > 0)
			$this->model_penjualan->deleteDiscMemberPending($noCart);

		$dataDiskon = array(
			"noCart" => $noCart,
			"idMember" => $idCustomer,
			"diskon" => $totalDiskon,
			"poinReimburs" => 0,
			"poinValue" => 0
		);

		$this->model_penjualan->saveDiskonMemberPending($dataDiskon);
	}

	function diskonMemberDisplay()
	{
		$idUser = $this->global['idUser'];
		$getDiskonMember = $this->model_penjualan->getDiskonMember($idUser);

		if ($getDiskonMember > 0) {
			$msg = "<td><i class='fa fa-money'></i> Diskon Member</td>";
			$msg .= "<td align='right'>" . number_format($getDiskonMember, '0', ',', '.') . "</td>";

			echo $msg;
		}
	}

	function diskonMemberDisplayPending()
	{
		$noCart = $_POST['noCart'];
		$getDiskonMember = $this->model_penjualan->getDiskonMemberPending($noCart);

		if ($getDiskonMember > 0) {
			$msg = "<td><i class='fa fa-money'></i> Diskon Member</td>";
			$msg .= "<td align='right'>" . number_format($getDiskonMember, '0', ',', '.') . "</td>";

			echo $msg;
		}
	}

	function deleteDiscMember()
	{
		$idUser = $_POST['idUser'];

		$this->model_penjualan->deleteDiscMember($idUser);
	}

	function deleteDiscMemberPending()
	{
		$noCart = $_POST['noCart'];

		$this->model_penjualan->deleteDiscMemberPending($noCart);
	}

	function get_nilai_reimburs()
	{
		$poin = $_POST['poin'];

		$nilai_reimburs = $this->model1->nilai_reimburs();

		foreach ($nilai_reimburs->result() as $row) {
			$nilai_poin = $row->poin_pengeluaran;
			$nilai_pengeluaran = $row->nilai_pengeluaran;
		}

		$nominal_poin = ($poin / $nilai_poin) * $nilai_pengeluaran;

		echo $nominal_poin;
	}

	function insertPoin()
	{
		$poinVal = $_POST['poinVal'];
		$nilaiReimburs = $_POST['nilaiReimburs'];
		$idUser = $this->global['idUser'];

		$dataUpdate = array(
			"poinReimburs" => $nilaiReimburs,
			"poinValue" => $poinVal
		);

		$this->model_penjualan->insertPoin($idUser, $dataUpdate);
	}

	function insertPoinPending()
	{
		$poinVal = $_POST['poinVal'];
		$nilaiReimburs = $_POST['nilaiReimburs'];
		$noCart = $_POST['noCart'];

		$dataUpdate = array(
			"poinReimburs" => $nilaiReimburs,
			"poinValue" => $poinVal
		);

		$this->model_penjualan->insertPoinPending($noCart, $dataUpdate);
	}

	function viewNilaiReimburs()
	{
		$idUser = $this->global['idUser'];

		$poinReimburs = $this->model_penjualan->poinReimburs($idUser);
		$poinValue = $this->model_penjualan->poinValue($idUser);

		if ($poinReimburs > 0) {

			$msg = "<td><i class='fa fa-tree'></i> Poin Reimbursment</td>";
			$msg .= "<td  align='right'>" . number_format($poinReimburs, '0', ',', '.') . "</td>";

			echo $msg;

		}
	}

	function viewNilaiReimbursPending()
	{
		$noCart = $_POST['noCart'];

		$poinReimburs = $this->model_penjualan->poinReimbursPending($noCart);
		$poinValue = $this->model_penjualan->poinValuePending($noCart);

		if ($poinReimburs > 0) {

			$msg = "<td><i class='fa fa-tree'></i> Poin Reimbursment</td>";
			$msg .= "<td  align='right'>" . number_format($poinReimburs, '0', ',', '.') . "</td>";

			echo $msg;

		}
	}

	function urlPoin()
	{
		$idUser = $this->global['idUser'];

		$poinReimburs = $this->model_penjualan->poinReimburs($idUser);
		$poinValue = $this->model_penjualan->poinValue($idUser);

		echo $poinValue;
	}

	function insertOngkir()
	{
		$ongkir = $_POST['ongkir'];
		$idUser = $this->global['idUser'];

		//cek ongkir exist
		$cekIfOngkirExist = $this->model_penjualan->cekIfOngkirExist($idUser);

		if ($cekIfOngkirExist > 0) {
			$dataUpdate = array(
				"ongkir" => $ongkir,
			);

			$this->model_penjualan->updateOngkir($idUser, $dataUpdate);
		} else {
			$dataInsert = array(
				"idUser" => $idUser,
				"ongkir" => $ongkir
			);

			$this->model_penjualan->insertOngkir($dataInsert);
		}
	}

	function insertOngkirPending()
	{
		$ongkir = $_POST['ongkir'];
		$noCart = $_POST['noCart'];

		//cek ongkir exist
		$cekIfOngkirExist = $this->model_penjualan->cekIfOngkirExistPending($noCart);

		if ($cekIfOngkirExist > 0) {
			$dataUpdate = array(
				"ongkir" => $ongkir,
			);

			$this->model_penjualan->updateOngkirPending($noCart, $dataUpdate);
		} else {
			$dataInsert = array(
				"noCart" => $noCart,
				"ongkir" => $ongkir
			);

			$this->model_penjualan->insertOngkirPending($dataInsert);
		}
	}

	function viewOngkir()
	{

		$idUser = $this->global['idUser'];

		$viewOngkir = $this->model_penjualan->viewOngkir($idUser);

		if ($viewOngkir > 0) {
			$msg = "<td><i class='fa fa-car'></i> Ongkir</td>";
			$msg .= "<td align='right'>" . number_format($viewOngkir, '0', ',', '.') . "</td>";

			echo $msg;
		}

	}

	function viewOngkirPending()
	{
		$noCart = $_POST['noCart'];

		$viewOngkir = $this->model_penjualan->viewOngkirPending($noCart);

		if ($viewOngkir > 0) {
			$msg = "<td><i class='fa fa-car'></i> Ongkir</td>";
			$msg .= "<td align='right'>" . number_format($viewOngkir, '0', ',', '.') . "</td>";

			echo $msg;
		}
	}



	function viewDiskon()
	{
		$idUser = $this->global['idUser'];

		$viewDiskon = $this->model_penjualan->viewDiskon($idUser);

		if ($viewDiskon > 0) {
			$msg = "<td><i class='fa fa-bullhorn'></i> Diskon Promosi</td>";
			$msg .= "<td align='right'>" . number_format($viewDiskon, '0', ',', '.') . "</td>";

			echo $msg;
		}
	}

	function viewSurcharge()
	{
		$idUser = $this->global['idUser'];
		$_POST['type'] = (!isset($_POST['type'])) ? 0 : $_POST['type'];
		$type_bayar = $_POST['type'];

		$subtotal = $this->model_penjualan->totalPurchase($idUser);
		$diskonPeritem = $this->model_penjualan->diskonPeritemPanel($idUser);
		$ongkir = $this->model_penjualan->viewOngkir($idUser);
		$diskonMember = $this->model_penjualan->getDiskonMember($idUser);
		$diskonPromosi = $this->model_penjualan->viewDiskon($idUser);
		$poinReimburs = $this->model_penjualan->poinReimburs($idUser);

		$grandTotal = ($subtotal + $ongkir) - ($diskonPeritem + $diskonMember + $diskonPromosi + $poinReimburs);




		//cek diskon
		$cekIfSurchargeExist = $this->model_penjualan->cekIfSurchargeExist($idUser);

		//$ifPercent = strripos($diskon,"%");

		if ($type_bayar != '2' && $type_bayar != '3' && $type_bayar != '4') {
			$surcharge = 0;
			$this->model_penjualan->hapusSurcharge($idUser);
		} else {
			$subAccount = $_POST['subAccount'];
			$surchargeSet = $this->model_penjualan->viewSurchargeSet($subAccount);

			$surcharge = $surchargeSet * $grandTotal;

			if ($cekIfSurchargeExist > 0) {
				$dataUpdate = array(
					"surcharge" => $surcharge
				);

				$this->model_penjualan->updateCartSurcharge($idUser, $dataUpdate);
			} else {
				$dataInsert = array(
					"idUser" => $idUser,
					"surcharge" => $surcharge
				);

				$this->model_penjualan->insertCartSurcharge($dataInsert);
			}
		}



		if ($surcharge > 0) {
			$msg = "<td><i class='fa fa-credit-card'></i> Surcharge</td>";
			$msg .= "<td align='right'>" . number_format($surcharge, '0', ',', '.') . "</td>";

			echo $msg;
		} else
			echo "";
	}

	function viewSurchargePending()
	{
		$noCart = $_POST['noCart'];
		$_POST['type'] = (!isset($_POST['type'])) ? 0 : $_POST['type'];
		$type_bayar = $_POST['type'];

		$subtotal = $this->model_penjualan->totalPurchasePending($noCart);
		$diskonPeritem = $this->model_penjualan->diskonPeritemPanelPending($noCart);
		$ongkir = $this->model_penjualan->viewOngkirPending($noCart);
		$diskonMember = $this->model_penjualan->getDiskonMemberPending($noCart);
		$diskonPromosi = $this->model_penjualan->viewDiskonPending($noCart);
		$poinReimburs = $this->model_penjualan->poinReimbursPending($noCart);

		$grandTotal = ($subtotal + $ongkir) - ($diskonPeritem + $diskonMember + $diskonPromosi + $poinReimburs);




		//cek diskon
		$cekIfSurchargeExist = $this->model_penjualan->cekIfSurchargeExistPending($noCart);

		//$ifPercent = strripos($diskon,"%");

		if ($type_bayar != '3') {
			$surcharge = 0;
			$this->model_penjualan->hapusSurchargePending($noCart);
		} else {
			$surcharge = 0.02 * $grandTotal;
			if ($cekIfSurchargeExist > 0) {
				$dataUpdate = array(
					"surcharge" => $surcharge
				);

				$this->model_penjualan->updateCartSurchargePending($noCart, $dataUpdate);
			} else {
				$dataInsert = array(
					"noCart" => $noCart,
					"surcharge" => $surcharge
				);

				$this->model_penjualan->insertCartSurchargePending($dataInsert);
			}
		}



		if ($surcharge > 0 && $type_bayar == 3) {
			$msg = "<td><i class='fa fa-credit-card'></i> Surcharge 2%</td>";
			$msg .= "<td align='right'>" . number_format($surcharge, '0', ',', '.') . "</td>";

			echo $msg;
		} else
			echo "";
	}

	function viewDiskonPending()
	{
		$noCart = $_POST['noCart'];

		$viewDiskon = $this->model_penjualan->viewDiskonPending($noCart);

		if ($viewDiskon > 0) {
			$msg = "<td><i class='fa fa-bullhorn'></i> Diskon Promosi</td>";
			$msg .= "<td align='right'>" . number_format($viewDiskon, '0', ',', '.') . "</td>";

			echo $msg;
		}
	}

	function viewVoucher()
	{
		$idUser = $this->global['idUser'];

		$allowMultipleDiscount = 0; 

		$cekPromo = $this->model_penjualan->cekBuyXbayarN($this->global['idStore']);
			$best_promo = null;
			$max_discount = 0;

			if ($cekPromo) {
				foreach ($cekPromo as $cek) {
					if ($this->global['idStore'] != $cek->id_toko) {
						continue;
					}
					$allowMultipleDiscount = $cek->allowMultipleBundle; // 0 = diskon max 1 bundle walau qty bisa banyak bundle; 1 = ikut jumlah bundle

					// Ambil Group Series
					$this->db->select("DISTINCT(group_series) as group_series");
					$this->db->from("ap_buy1get3_new_rules");
					$this->db->where("no_promo", $cek->no_promo);
					$queryGroups = $this->db->get();

					if ($queryGroups->num_rows() == 0) {
						continue;
					}

					$requiredGroups = $queryGroups->result_array();
					$allGroupsMatched = true;
					$current_promo_total_qty = 0;
					$current_promo_total_harga = 0;
					$promo_items = array();
					$groups_pools = array();

					foreach ($requiredGroups as $group) {
						// Ambil item cart yang sesuai dengan Group Series & Promo ini
						$this->db->select("ap_cart.quantity, ap_cart.harga");
						$this->db->from("ap_cart");
						$this->db->join("ap_buy1get3_new_rules", "ap_buy1get3_new_rules.id_produk = ap_cart.id_produk", "inner");
						$this->db->where("ap_cart.id_user", $idUser);
						$this->db->where("ap_cart.quantity > 0");
						$this->db->where("ap_buy1get3_new_rules.no_promo", $cek->no_promo);
						$this->db->where("ap_buy1get3_new_rules.group_series", $group['group_series']);
						$groupItems = $this->db->get()->result_array();

						// Syarat: Setiap Group Series HARUS ada minimal 1 item di cart
						if (count($groupItems) == 0) {
							$allGroupsMatched = false;
							break;
						}

						$pool_lines = array();
						foreach ($groupItems as $item) {
							$current_promo_total_qty += $item['quantity'];
							$current_promo_total_harga += ($item['quantity'] * $item['harga']);
							$promo_items[] = array(
								'harga'    => $item['harga'],
								'quantity' => $item['quantity']
							);
							$pool_lines[] = array(
								'harga'    => $item['harga'],
								'quantity' => $item['quantity']
							);
						}
						$groups_pools[] = $pool_lines;
					}

					// Syarat Final: Total Qty dari semua group harus >= jumlah_bayar
					if ($allGroupsMatched && $current_promo_total_qty >= $cek->jumlah_bayar) {
						$potensi_diskon = 0;

						// Jika ada discount_percent atau discount_rp — basis diskon di-cap per bundle (bukan total qty semua group)
						if (($cek->discount_percent > 0) || ($cek->discount_rp > 0)) {
							$base_diskon_harga = $this->_buyxPromoBaseDiskonHarga($groups_pools, (int) $cek->jumlah_bayar, (float) $current_promo_total_harga, (int) $allowMultipleDiscount);
							$potensi_diskon = ($base_diskon_harga * ((float) $cek->discount_percent / 100)) + (float) $cek->discount_rp;
						}
						// Jika tidak ada discount_percent dan discount_rp, tapi ada jumlah_gratis
						elseif ($cek->jumlah_gratis > 0 && !empty($promo_items) && $current_promo_total_qty >= $cek->jumlah_bayar + $cek->jumlah_gratis) {
							// Hitung diskon senilai N barang termurah (N = jumlah_gratis)
							usort($promo_items, function ($a, $b) {
								if ($a['harga'] == $b['harga']) {
									return 0;
								}
								return ($a['harga'] < $b['harga']) ? -1 : 1;
							});

							$sisa_gratis = (int) $cek->jumlah_gratis;
							$diskon_gratis = 0;

							foreach ($promo_items as $item) {
								if ($sisa_gratis <= 0) {
									break;
								}

								$ambil_qty = min($item['quantity'], $sisa_gratis);
								$diskon_gratis += ($ambil_qty * $item['harga']);
								$sisa_gratis -= $ambil_qty;
							}

							$potensi_diskon = $diskon_gratis;
						}

						// Cari promo dengan nilai diskon TERBESAR
						if ($potensi_diskon > $max_discount) {
							$max_discount = $potensi_diskon;
							$best_promo = $cek;
						}
					}
				}
			}

		$diskon_fisik = floatval($this->model_penjualan->diskonVoucherFisik($idUser) ?: 0);

		if ($max_discount > 0 && $best_promo != null) {
			if ($max_discount > $diskon_fisik) {
				$this->model_penjualan->hapusCartVoucherFisik($idUser);
				$this->model_penjualan->hapusCartVoucher($idUser);
				$data = array(
					"idUser"   => $idUser,
					"no_promo" => $best_promo->no_promo,
					"diskon"   => $max_discount
				);
				$this->model_penjualan->insertDiskonVoucher($data);

				$msg = "<td><i class='fa fa-credit-card'></i> Promo Hemat</td>";
				$msg .= "<td align='right'>" . number_format($max_discount, '0', ',', '.') . "</td>";
				echo $msg;

				return;
			}
			$this->model_penjualan->hapusCartVoucher($idUser);
			return;
		}

		$this->model_penjualan->hapusCartVoucher($idUser);
	}

	// Basis nilai diskon Promo Hemat (per bundle); multi-group: jb % G != 0 → pakai total harga lama
	private function _buyxPromoBaseDiskonHarga(array $groups_pools, $jumlah_bayar, $fallback_total_harga, $allow_multiple_discount = 1)
	{
		$G = count($groups_pools);
		$jb = (int) $jumlah_bayar;
		$allow_multiple_discount = (int) $allow_multiple_discount;
		if ($G < 1 || $jb < 1) {
			return (float) $fallback_total_harga;
		}

		$work = array();
		foreach ($groups_pools as $lines) {
			$lines = array_values($lines);
			usort($lines, function ($a, $b) {
				$ha = (float) $a['harga'];
				$hb = (float) $b['harga'];
				if ($ha == $hb) {
					return 0;
				}
				return ($ha < $hb) ? -1 : 1;
			});
			$copy = array();
			foreach ($lines as $ln) {
				$copy[] = array(
					'harga'    => $ln['harga'],
					'quantity' => (int) $ln['quantity'],
				);
			}
			$work[] = $copy;
		}

		if ($G === 1) {
			$total_qty = 0;
			foreach ($work[0] as $ln) {
				$total_qty += (int) $ln['quantity'];
			}
			$num_sets = (int) floor($total_qty / $jb);
			if ($allow_multiple_discount === 0) {
				$num_sets = min($num_sets, 1);
			}
			if ($num_sets < 1) {
				return (float) $fallback_total_harga;
			}
			$need = $num_sets * $jb;

			return $this->_buyxConsumeCheapestUnits($work[0], $need);
		}

		if ($jb % $G !== 0) {
			return (float) $fallback_total_harga;
		}

		$perGroup = (int) ($jb / $G);
		$num_sets = PHP_INT_MAX;
		foreach ($work as $lines) {
			$qty_g = 0;
			foreach ($lines as $ln) {
				$qty_g += (int) $ln['quantity'];
			}
			$num_sets = min($num_sets, (int) floor($qty_g / $perGroup));
		}
		if ($allow_multiple_discount === 0) {
			$num_sets = min($num_sets, 1);
		}
		if ($num_sets < 1) {
			return (float) $fallback_total_harga;
		}

		$base = 0.0;
		for ($s = 0; $s < $num_sets; $s++) {
			for ($gi = 0; $gi < $G; $gi++) {
				$take = $perGroup;
				while ($take > 0 && !empty($work[$gi])) {
					$q = (int) $work[$gi][0]['quantity'];
					if ($q <= 0) {
						array_shift($work[$gi]);
						continue;
					}
					$ambil = min($q, $take);
					$base += $ambil * (float) $work[$gi][0]['harga'];
					$work[$gi][0]['quantity'] = $q - $ambil;
					$take -= $ambil;
					if ($work[$gi][0]['quantity'] <= 0) {
						array_shift($work[$gi]);
					}
				}
				if ($take > 0) {
					return (float) $fallback_total_harga;
				}
			}
		}

		return $base;
	}

	private function _buyxConsumeCheapestUnits(array $lines, $need)
	{
		$pool = array();
		foreach ($lines as $ln) {
			$pool[] = array(
				'harga'    => $ln['harga'],
				'quantity' => (int) $ln['quantity'],
			);
		}
		$base = 0.0;
		$need = (int) $need;
		while ($need > 0 && !empty($pool)) {
			$q = (int) $pool[0]['quantity'];
			if ($q <= 0) {
				array_shift($pool);
				continue;
			}
			$ambil = min($q, $need);
			$base += $ambil * (float) $pool[0]['harga'];
			$pool[0]['quantity'] = $q - $ambil;
			$need -= $ambil;
			if ($pool[0]['quantity'] <= 0) {
				array_shift($pool);
			}
		}

		return $base;
	}

	function viewVoucherFisik()
	{
		$idUser = $this->global['idUser'];
		$seri_voucher = trim((string) $this->input->post('seri_voucher'));

		if ($seri_voucher === '') {
			$this->model_penjualan->hapusCartVoucherFisik($idUser);
			echo '';
			return;
		}

		$subtotal = $this->model_penjualan->totalPurchase($idUser);
		$diskonPeritem = $this->model_penjualan->diskonPeritemPanel($idUser);
		$diskonBuy1Get3 = $this->model_penjualan->diskonBuy1Get3($idUser);
		$ongkir = $this->model_penjualan->viewOngkir($idUser);
		$diskonMember = $this->model_penjualan->getDiskonMember($idUser);
		$diskonPromosi = $this->model_penjualan->viewDiskon($idUser);
		$poinReimburs = $this->model_penjualan->poinReimburs($idUser);

		$grandTotal = ($subtotal + $ongkir) - ($diskonPeritem + $diskonMember + $diskonPromosi + $poinReimburs + $diskonBuy1Get3);

		$this->model_penjualan->hapusCartVoucherFisik($idUser);

		$diskon_promo_hemat = floatval($this->model_penjualan->diskonVoucher($idUser) ?: 0);

		$cekVoucher = $this->model_penjualan->cekVoucherFisik($seri_voucher);

		$diskonVoucher = 0;
		$rows_insert = array();
		$msg = '';
		if (empty($cekVoucher->num_rows())) {
			$msg .= "<script>alert('Voucher tidak ditemukan.');</script>";
		} else {
			foreach ($cekVoucher->result() as $row) {
				if ($row->terpakai == 1) {
					$msg .= "<script>alert('Voucher sudah digunakan.');</script>";
				} elseif ($grandTotal < $row->minimal_belanja) {
					$msg .= "<script>alert('Minimal belanja harus Rp " . number_format($row->minimal_belanja, 0, ',', '.') . "');</script>";
				} else {

					$date_now = date("Y-m-d H:i:s");
					$start_date = strtotime($row->berlaku_mulai);
					$end_date = strtotime($row->berlaku_selesai);
					$todays_date = strtotime($date_now);

					if ($todays_date >= $start_date && $todays_date <= $end_date) {

						$simulasi = $grandTotal - $diskonVoucher - $row->nilai;
						if ($simulasi >= 0) {
							$diskonVoucher += $row->nilai;
							$rows_insert[] = array(
								"idUser" => $idUser,
								"seri_voucher" => $row->id_voucher,
								"diskon" => $row->nilai
							);
						} else {
							$msg .= "<script>alert('Diskon Voucher 0.');</script>";
						}

					} else {
						$msg .= "<script>alert('Voucher sudah tidak berlaku.');</script>";
					}

				}
			}
		}

		if ($diskonVoucher > 0 && !empty($rows_insert)) {
			if ($diskonVoucher > $diskon_promo_hemat) {
				$this->model_penjualan->hapusCartVoucher($idUser);
				foreach ($rows_insert as $data) {
					$this->model_penjualan->insertVoucherFisik($data);
				}
				$msg .= "<td><i class='fa fa-credit-card'></i> F.Voucher</td>";
				$msg .= "<td align='right'>" . number_format($diskonVoucher, '0', ',', '.') . "</td>";
			} else {
				$msg .= "<script>alert('Promo Hemat lebih besar atau sama dengan voucher fisik.');</script>";
			}
		}

		echo $msg;

	}

	function viewGrandTotal()
	{
		$idUser = $this->global['idUser'];

		$subtotal = $this->model_penjualan->totalPurchase($idUser);
		$diskonPeritem = $this->model_penjualan->diskonPeritemPanel($idUser);
		$ongkir = $this->model_penjualan->viewOngkir($idUser);
		$diskonMember = $this->model_penjualan->getDiskonMember($idUser);
		$diskonPromosi = $this->model_penjualan->viewDiskon($idUser);
		$surcharge = $this->model_penjualan->viewSurcharge($idUser);
		$poinReimburs = $this->model_penjualan->poinReimburs($idUser);
		$diskonBuy1Get3 = $this->model_penjualan->diskonBuy1Get3($idUser);
		$diskonVoucher = floatval($this->model_penjualan->diskonVoucher($idUser) ?: 0);
		$diskonVoucherFisik = floatval($this->model_penjualan->diskonVoucherFisik($idUser) ?: 0);
		$diskonVoucherTunggal = max($diskonVoucher, $diskonVoucherFisik);

		$grandTotal = ($subtotal + $ongkir) - ($diskonPeritem + $diskonMember + $diskonPromosi + $poinReimburs + $diskonBuy1Get3 + $diskonVoucherTunggal) + $surcharge;

		echo number_format($grandTotal, '0', ',', '.');
	}

	function totalKeseluruhan()
	{
		$idUser = $this->global['idUser'];

		$subtotal = $this->model_penjualan->totalPurchase($idUser);
		$diskonPeritem = $this->model_penjualan->diskonPeritemPanel($idUser);
		$ongkir = $this->model_penjualan->viewOngkir($idUser);
		$diskonMember = $this->model_penjualan->getDiskonMember($idUser);
		$diskonPromosi = $this->model_penjualan->viewDiskon($idUser);
		$poinReimburs = $this->model_penjualan->poinReimburs($idUser);
		$surcharge = $this->model_penjualan->viewSurcharge($idUser);
		$diskonVoucher = floatval($this->model_penjualan->diskonVoucher($idUser) ?: 0);
		$diskonVoucherFisik = floatval($this->model_penjualan->diskonVoucherFisik($idUser) ?: 0);
		$diskonVoucherTunggal = max($diskonVoucher, $diskonVoucherFisik);
		$diskonBuy1Get3 = $this->model_penjualan->diskonBuy1Get3($idUser);

		$grandTotal = ($subtotal + $ongkir) - ($diskonPeritem + $diskonMember + $diskonPromosi + $poinReimburs + $diskonBuy1Get3 + $diskonVoucherTunggal) + $surcharge;

		echo $grandTotal;
	}

	function viewGrandTotalPending()
	{
		$noCart = $_POST['noCart'];

		$subtotal = $this->model_penjualan->totalPurchasePending($noCart);
		$diskonPeritem = $this->model_penjualan->diskonPeritemPanelPending($noCart);
		$ongkir = $this->model_penjualan->viewOngkirPending($noCart);
		$diskonMember = $this->model_penjualan->getDiskonMemberPending($noCart);
		$diskonPromosi = $this->model_penjualan->viewDiskonPending($noCart);
		$poinReimburs = $this->model_penjualan->poinReimbursPending($noCart);
		$surcharge = $this->model_penjualan->viewSurchargePending($noCart);

		$grandTotal = ($subtotal + $ongkir) - ($diskonPeritem + $diskonMember + $diskonPromosi + $poinReimburs) + $surcharge;

		echo number_format($grandTotal, '0', ',', '.');
	}

	function penjualan_sql()
	{
		$sync_offline = $this->input->post('sync_offline') === '1' || $this->input->post('sync_offline') === 1;
		if ($sync_offline) {
			$this->_penjualan_sql_sync_offline();
			return;
		}

		if ($this->session->userdata('penjualan_sql_accessed') == 1) {
			header('HTTP/1.1 403 Forbidden');
			echo 'Access denied. Open Penjualan page first.';
			return;
		}
		$this->session->set_userdata('penjualan_sql_accessed', 1);

		$idUser = $this->global['idUser'];

		$poinReimburs = $this->model_penjualan->poinReimburs($idUser);
		$poinValue = $this->model_penjualan->poinValue($idUser);

		$id_customer = $this->model_penjualan->getIdMemberDiskon($idUser);
		$kategori_member = $this->model_penjualan->cekKategoriMember($id_customer);
		$ongkir = $this->model_penjualan->viewOngkir($idUser) > 0 ? $this->model_penjualan->viewOngkir($idUser) : 0;
		$type_bayar = $this->input->post("type_bayar");
		$keterangan = $this->input->post("keterangan");
		$id_sales = $this->input->post("id_sales");

		$surcharge = $this->model_penjualan->viewSurcharge($idUser);
		if (empty($surcharge)) {
			$surcharge = 0;
		}

		$alamat = $this->input->post("alamatPenerima");
		$provinsi = $this->input->post("provinsi");
		$kabupaten = $this->input->post("kabupaten");
		$kecamatan = $this->input->post("kecamatan");
		$ekspedisi = $this->input->post("ekspedisi");
		$namaPenerima = $this->input->post("namaPenerima");
		$noHPPenerima = $this->input->post("noHPPenerima");


		$total = $this->model_penjualan->totalPurchase($idUser);
		$hpp = $this->model_penjualan->totalPurchaseHPP($idUser);
		$diskon = $this->model_penjualan->getDiskonMember($idUser); //diskon member
		$diskon_promosi = $this->model_penjualan->viewDiskon($idUser) > 0 ? $this->model_penjualan->viewDiskon($idUser) : 0;
		/**$no_hp 			 = $this->input->post("no_hp");**/
		$value_poin = $this->model_penjualan->poinReimburs($idUser) > 0 ? $this->model_penjualan->poinReimburs($idUser) : 0;
		$point_reimburs = $this->model_penjualan->poinValue($idUser) > 0 ? $this->model_penjualan->poinValue($idUser) : 0;
		$jumlah_bayar = $this->input->post("jumlah_bayar");
		$diskon_otomatis = $this->model_penjualan->diskonPeritemPanel($idUser);

		$diskon_buy1get3_val = $this->model_penjualan->diskonBuy1Get3($idUser);
		$diskon_buy1get3_val = empty($diskon_buy1get3_val) ? 0 : floatval($diskon_buy1get3_val);
		$dv_m = floatval($this->model_penjualan->diskonVoucher($idUser) ?: 0);
		$dv_f = floatval($this->model_penjualan->diskonVoucherFisik($idUser) ?: 0);
		$diskon_voucher_total = max($dv_m, $dv_f);
		$seri_voucher = trim((string) $this->input->post("seri_voucher"));
		$seri_voucher_invoice = ($dv_f > $dv_m && $seri_voucher !== '') ? $seri_voucher : '';
		$diskon_promosi = floatval($diskon_promosi) + $diskon_buy1get3_val;

		$tanggal = date('Y-m-d');
		$id_user = $this->global['idUser'];

		$count_invoice = $this->model1->count_invoice($tanggal) + 1;

		$no_inv = "INV" . date('y') . date('m') . date('d') . $id_user . sprintf('%04d', $count_invoice);

		//DAPATKAN NILAI POIN
		$poin_pembelian = $this->model1->poin_pembelian();
		$nilai_pembelian = $this->model1->nilai_pembelian();
		$total_transaksi = $total - ($diskon + $diskon_promosi + $value_poin + $diskon_otomatis + $diskon_voucher_total);

		$poin = floor(($total_transaksi / $nilai_pembelian) * $poin_pembelian);

		//UPDATE POIN CUSTOMER ALGORITHM

		//dapatkan poin lama
		$poin_lama = $this->model1->poin_lama($id_customer);
		//$ulang_tahun = $this->model1->ulang_tahun($id_customer);


		//update poin customer setelah transaksi 
		//if($kategori_member==2){
		// if ($ulang_tahun == date('m-d')){
		// $data_poin = array(
		// 					"point" => $poin_lama+($poin*2)
		// 			); 
		// }else{
		$data_poin = array(
			"point" => $poin_lama + $poin
		);
		// }
		// }else{
		// 	$data_poin = array(
		// 						"point" => 0
		// 				);
		// }



		$this->model_penjualan->updatePoinReimburs($id_customer, $data_poin);

		if ($this->input->post("sub_account") == null) {
			$sub_account = "";
		} else {
			$sub_account = $this->input->post("sub_account");
		}

		//kurangi poin jika nilai reimbursment lebih dari 0
		if ($point_reimburs > 0) {
			$old_poin = $this->model1->poin_lama($id_customer);

			$kurang_poin = array(
				"point" => $old_poin - $point_reimburs
			);

			$this->model_penjualan->updatePoinReimburs($id_customer, $kurang_poin);
		}

		$diskon = empty($diskon) ? 0 : $diskon;

		$header_no_promo = $this->model_penjualan->getHeaderNoPromoForInvoice($idUser, $dv_m, $dv_f, $diskon_buy1get3_val);

		$data_penjualan = array(
			"no_invoice" => $no_inv,
			"tipe_bayar" => $type_bayar,
			"sub_account" => $sub_account,
			"jatuh_tempo" => $this->input->post("jatuh_tempo"),
			"total" => $total,
			"surcharge" => $surcharge,
			"ongkir" => $ongkir,
			"diskon" => $diskon + 0,
			"diskon_free" => $diskon_promosi,
			"hpp" => $hpp,
			"poin_value" => $value_poin,
			"poin" => $poin,
			"poin_reimburs" => $point_reimburs,
			"diskon_otomatis" => $diskon_otomatis,
			"voucher" => $diskon_voucher_total,
			"no_promo" => $header_no_promo,
			"seri_voucher" => ($seri_voucher_invoice === '' ? 0 : $seri_voucher_invoice),
			"jumlah_bayar" => $jumlah_bayar,
			"id_pic" => $id_user,
			"id_customer" => $id_customer,
			"keterangan" => $keterangan,
			"tanggal" => date('Y-m-d H:i:s'),
			"alamat" => $alamat,
			"id_provinsi" => $provinsi,
			"id_kabupaten" => $kabupaten,
			"id_kecamatan" => $kecamatan,
			"kontak_pengiriman" => $noHPPenerima,
			"nama_penerima" => $namaPenerima,
			"id_ekspedisi" => $ekspedisi,
			"id_sales" => $id_sales,
			"id_toko" => $this->global['idStore']
		);

		$this->model_penjualan->insertApInvoiceNumber($data_penjualan);

		$affect = $this->db->affected_rows();

		if ($affect > 0 && $dv_f > $dv_m && $seri_voucher !== '') {
			$this->model_penjualan->updateVoucherSeri($seri_voucher, array("terpakai" => 1));
		}

		// Proses tipe dual payment
		if ($type_bayar == 6) {
			$type_bayar_dual1 = $this->input->post("type_bayar_dual1");
			$type_bayar_dual2 = $this->input->post("type_bayar_dual2");
			$subAccount1 = $this->input->post("subAccount1");
			$subAccount2 = $this->input->post("subAccount2");
			$jumlah_bayar_dual1 = $type_bayar_dual1 == 1 ? $total_transaksi + $surcharge - $this->input->post("jumlah_bayar_dual2") : $this->input->post("jumlah_bayar_dual1");
			$jumlah_bayar_dual2 = $type_bayar_dual2 == 1 ? $total_transaksi + $surcharge - $this->input->post("jumlah_bayar_dual1") : $this->input->post("jumlah_bayar_dual2");

			$data_dual_payment[] = array(
				"no_invoice" => $no_inv,
				"payment_type" => $type_bayar_dual1,
				"payment_account" => $subAccount1,
				"total" => $jumlah_bayar_dual1,
				"id_pic" => $id_user,
				"tanggal" => date('Y-m-d H:i:s'),
				"id_toko" => $this->global['idStore']
			);
			$data_dual_payment[] = array(
				"no_invoice" => $no_inv,
				"payment_type" => $type_bayar_dual2,
				"payment_account" => $subAccount2,
				"total" => $jumlah_bayar_dual2,
				"id_pic" => $id_user,
				"tanggal" => date('Y-m-d H:i:s'),
				"id_toko" => $this->global['idStore']
			);

			$this->model_penjualan->insertApInvoicePayment($data_dual_payment);
		} else {
			$data_dual_payment[] = array(
				"no_invoice" => $no_inv,
				"payment_type" => $type_bayar,
				"payment_account" => $sub_account,
				"total" => $total_transaksi,
				"id_pic" => $id_user,
				"tanggal" => date('Y-m-d H:i:s'),
				"id_toko" => $this->global['idStore']
			);

			$this->model_penjualan->insertApInvoicePayment($data_dual_payment);

		}

		//cek tipe bayar, jika 1 = piutang dan sisipkan ke tabel piutag
		if ($type_bayar == 5) {
			$data_piutang = array(
				"no_invoice" => $no_inv,
				"status" => 0, //0 = TERBAYAR, 1 = LUNAS,
				"jatuh_tempo" => $this->input->post("jatuh_tempo")
			);

			$this->db->insert("ap_piutang", $data_piutang);

			//masukan dp 
			$cek_piutang_payment = $this->model1->cek_piutang_payment() + 1;

			$no_seri = "TRX-" . date('y') . date('m') . sprintf('%04d', $cek_piutang_payment);

			$data_piutang = array(
				"no_seri" => $no_seri,
				"no_invoice" => $no_inv,
				"id_pic" => $this->global['idUser'],
				"id_payment" => $type_bayar,
				"account" => $sub_account,
				"tanggal" => date('Y-m-d'),
				"nominal" => $jumlah_bayar,
				"keterangan" => $keterangan
			);

			$this->model_penjualan->insertPiutangInvoice($data_piutang);
		}

		// view cart
		$viewCart = $this->model_penjualan->dataCart($idUser, $this->global['idStore']);
		$quota_lines = array();

		foreach ($viewCart as $row) {
			$sku = $row->id_produk;
			$harga = $row->harga;
			$hpp = $row->hpp;
			$qty = $row->qty;
			$diskon_item = $row->diskon;
			$disc_supplier = $row->disc_supplier;
			$tebusmurah = $row->tebusmurah;
			$line_no_promo = isset($row->no_promo) ? trim((string) $row->no_promo) : '';
			if ($line_no_promo === '') {
				$rPromo = (int) $this->model_penjualan->resolveDiscountRuleNoPromo($sku, $this->global['idStore'], $qty);
				$line_no_promo = ($rPromo > 0) ? (string) $rPromo : '';
			}
			$quota_lines[] = array('sku' => $sku, 'qty' => $qty, 'no_promo' => $line_no_promo);

			$data_item[] = array(
				"no_invoice" => $no_inv,
				"id_produk" => $sku,
				"hpp" => $hpp,
				"harga_jual" => $harga,
				"diskon" => $diskon_item,
				"disc_supplier" => $disc_supplier,
				"tebusmurah" => $tebusmurah,
				"no_promo" => $line_no_promo,
				"qty" => $qty,
				"tanggal" => date('Y-m-d'),
				"id_toko" => $this->global['idStore']
			);
			$data_kartu[] = array(
				"id_store" => $this->global['idStore'],
				"id_produk" => $sku,
				"qty" => '-' . $qty,
				"harga" => $harga,
				"hpp" => $hpp,
				"tanggal" => date('Y-m-d H:i:s'),
				"tipe" => 'Penjualan',
				"no_transaksi" => $no_inv,
				"id_pic" => $this->global['idUser']
			);

			$id_store = $this->global['idStore'];
			//kurangi stok di gudang utama
			$stok_lama = $this->model1->get_stok_lama_produk_store($sku, $id_store);
			$new_stok = $stok_lama - $qty;

			//UPDATE STOK BARU
			$data_update = array(
				"stok" => $new_stok,
				"last_sales" => date('Y-m-d')
			);

			$this->model_penjualan->updateStokStore($sku, $id_store, $data_update);
		}

		$this->model_penjualan->insertBatch($data_item, $idUser);
		$this->model1->insertKartuStok($data_kartu);
		$this->model_penjualan->hapusTrx($idUser);

		// =========================================================================
        // START: AUTO JOURNAL (PER-ITEM VAT SUPPORT)
        // =========================================================================

        // --- CONFIG ---
        $ppn_rate = 0.11;

        // 1. Mapping Akun
        $acc_map = $this->Accounting_model->get_mapping_list(); 
        $acc = (object)[
            'penjualan'    => $acc_map['PENJUALAN'], 
            'persediaan'   => $acc_map['PERSEDIAAN'], 
            'hpp'          => $acc_map['HPP'], 
            'ongkir'       => $acc_map['ONGKIR'], 
            'surcharge'    => $acc_map['SURCHARGE'],
            'piutang'      => $acc_map['PIUTANG'], 
            'diskon'       => $acc_map['DISKON'], 
            'ppn_keluaran' => $acc_map['PPN_KELUARAN'], 
            'kas_default'  => $acc_map['KAS_KASIR_PENDING'] 
        ];

        // 2. HITUNG ULANG DPP & PPN BERDASARKAN ISI KERANJANG
        $total_dpp_penjualan = 0;
        $total_ppn_keluaran  = 0;

        // Kita loop kembali viewCart untuk memisahkan PPN
        foreach ($viewCart as $item) {
            // Cek status PPN produk di master data
            $prod = $this->db->select('is_ppn')->get_where('ap_produk', ['id_produk' => $item->id_produk])->row();
            $is_ppn = ($prod && $prod->is_ppn == 1) ? true : false;

            $subtotal_item = $item->qty * $item->harga;

            if ($is_ppn) {
                // Harga include PPN: Pecah jadi DPP dan PPN
                $dpp_item = round($subtotal_item / (1 + $ppn_rate), 4);
                $ppn_item = $subtotal_item - $dpp_item;

                $total_dpp_penjualan += $dpp_item;
                $total_ppn_keluaran  += $ppn_item;
            } else {
                // Non-PPN: Seluruhnya masuk DPP (Pendapatan)
                $total_dpp_penjualan += $subtotal_item;
            }
        }

        // 3. LOGIKA POTONGAN & ENTRIES
        $total_potongan = $diskon + $diskon_promosi + $value_poin + $diskon_otomatis + $diskon_voucher_total;
        $entries = [];

        // --- SISI DEBIT 1: KAS / PIUTANG (Net Cash) ---
        if ($type_bayar == 6) { // Dual Payment
             foreach ($data_dual_payment as $pay) {
                $map = $this->db->get_where('ap_payment_account', ['id_payment_account' => $pay['payment_account']])->row();
                $entries[] = $this->Accounting_model->entry_line($map ? $map->account_id : $acc->kas_default, $pay['total'], 0);
            }
        } elseif ($type_bayar == 5) { // Piutang
            $nett = ($total + $surcharge + $ongkir) - $total_potongan;
            $sisa_piutang = $nett - $jumlah_bayar;
            if ($jumlah_bayar > 0) {
                $map = $this->db->get_where('ap_payment_account', ['id_payment_account' => $sub_account])->row();
                $entries[] = $this->Accounting_model->entry_line($map ? $map->account_id : $acc->kas_default, $jumlah_bayar, 0);
            }
            if ($sisa_piutang > 0) {
                $entries[] = $this->Accounting_model->entry_line($acc->piutang, $sisa_piutang, 0);
            }
        } else { // Cash Regular
            $nilai_kas = ($total + $surcharge + $ongkir) - $total_potongan;
            $map = $this->db->get_where('ap_payment_account', ['id_payment_account' => $sub_account])->row();
            $entries[] = $this->Accounting_model->entry_line($map ? $map->account_id : $acc->kas_default, $nilai_kas, 0);
        }

        // --- SISI DEBIT 2: POTONGAN (EXPENSE) ---
        if ($total_potongan > 0) {
            $entries[] = $this->Accounting_model->entry_line($acc->diskon, $total_potongan, 0);
        }

        // --- SISI KREDIT 1: PENDAPATAN (DPP Campuran) ---
        // Mencatat pendapatan murni dari barang PPN + harga full barang Non-PPN
        $entries[] = $this->Accounting_model->entry_line($acc->penjualan, 0, $total_dpp_penjualan); 

        // --- SISI KREDIT 2: PPN KELUARAN ---
        if ($total_ppn_keluaran > 0) {
            $entries[] = $this->Accounting_model->entry_line($acc->ppn_keluaran, 0, $total_ppn_keluaran);

			$this->Accounting_model->log_tax($no_inv, $acc->ppn_keluaran, $total_ppn_keluaran, 11, 'Penjualan Retail - ' . $no_inv . ($type_bayar == 5 ? ' (Piutang)' : ''));
        }

        // --- SISI KREDIT 3: LAINNYA ---
        if ($ongkir > 0) $entries[] = $this->Accounting_model->entry_line($acc->ongkir, 0, $ongkir);
        if ($surcharge > 0) $entries[] = $this->Accounting_model->entry_line($acc->surcharge, 0, $surcharge);

        // --- SISI PERSEDIAAN & HPP (HPP tidak dipengaruhi PPN jual) ---
        if ($hpp > 0) {
            $entries[] = $this->Accounting_model->entry_line($acc->hpp, $hpp, 0);
            $entries[] = $this->Accounting_model->entry_line($acc->persediaan, 0, $hpp);
        }

        // Eksekusi Jurnal
        $header = $this->Accounting_model->journal_header(
            $this->global['idStore'],
            $no_inv,
            'Penjualan Retail - ' . $no_inv . ($type_bayar == 5 ? ' (Piutang)' : ''),
            'Sales'
        );
        $res = $this->Accounting_model->create_journal_entry($header, $entries);
        
        // =========================================================================
        // END AUTO JOURNAL
        // =========================================================================

		$idStoreQuota = (int) $this->global['idStore'];
		foreach ($quota_lines as $ql) {
			$no_promo_q = isset($ql['no_promo']) ? trim((string) $ql['no_promo']) : '';
			if ($no_promo_q === '') {
				continue;
			}
			$sku_q = $ql['sku'];
			$qty_safe = floatval($ql['qty']);
			$this->db->set('quota_terpakai', 'quota_terpakai+' . $qty_safe, false);
			$this->db->where('id_produk', $sku_q);
			$this->db->where('id_toko', $idStoreQuota);
			$this->db->where('no_promo', $no_promo_q);
			$this->db->update('ap_produk_discount_rules');

			$this->db->set('quota_terpakai', 'quota_terpakai+' . $qty_safe, false);
			$this->db->where('id_produk', $sku_q);
			$this->db->where('id_toko', $idStoreQuota);
			$this->db->where('no_promo', $no_promo_q);
			$this->db->update('ap_produk_tebusmurah_rules');
		}

		echo $no_inv;


	}

	function cancelTrx()
	{
		$idUser = $this->global['idUser'];

		$this->model_penjualan->hapusTrx($idUser);
		redirect("penjualan");
	}

	function penjualanSqlPending()
	{

		$noCart = $_POST['noCart'];

		$poinReimburs = $this->model_penjualan->poinReimbursPending($noCart);
		$poinValue = $this->model_penjualan->poinValuePending($noCart);

		$id_customer = $this->model_penjualan->getIdMemberDiskonPending($noCart);
		$kategori_member = $this->model_penjualan->cekKategoriMember($id_customer);
		$ongkir = $this->model_penjualan->viewOngkirPending($noCart) > 0 ? $this->model_penjualan->viewOngkirPending($noCart) : 0;

		$type_bayar = $this->input->post("type_bayar");
		$keterangan = $this->input->post("keterangan");

		$alamat = $this->input->post("alamatPenerima");
		$provinsi = $this->input->post("provinsi");
		$kabupaten = $this->input->post("kabupaten");
		$kecamatan = $this->input->post("kecamatan");
		$ekspedisi = $this->input->post("ekspedisi");
		$namaPenerima = $this->input->post("namaPenerima");
		$noHPPenerima = $this->input->post("noHPPenerima");

		$total = $this->model_penjualan->totalPurchasePending($noCart);
		$hpp = $this->model_penjualan->totalPurchaseHPPPending($noCart);
		$diskon = $this->model_penjualan->getDiskonMemberPending($noCart); //diskon member
		$diskon_promosi = $this->model_penjualan->viewDiskonPending($noCart) > 0 ? $this->model_penjualan->viewDiskonPending($noCart) : 0;
		$value_poin = $this->model_penjualan->poinReimbursPending($noCart) > 0 ? $this->model_penjualan->poinReimbursPending($noCart) : 0;
		$point_reimburs = $this->model_penjualan->poinValuePending($noCart) > 0 ? $this->model_penjualan->poinValuePending($noCart) : 0;
		$jumlah_bayar = $this->input->post("jumlah_bayar");
		$diskon_otomatis = $this->model_penjualan->diskonPeritemPanelPending($noCart);

		$tanggal = date('Y-m-d');
		$id_user = $this->global['idUser'];

		$count_invoice = $this->model1->count_invoice($tanggal) + 1;

		$no_inv = "INV" . date('y') . date('m') . date('d') . $id_user . sprintf('%04d', $count_invoice);

		//DAPATKAN NILAI POIN
		$poin_pembelian = $this->model1->poin_pembelian();
		$nilai_pembelian = $this->model1->nilai_pembelian();
		$total_transaksi = $total - ($diskon + $diskon_promosi + $value_poin);

		$poin = floor(($total_transaksi / $nilai_pembelian) * $poin_pembelian);

		//UPDATE POIN CUSTOMER 

		//dapatkan poin lama
		$poin_lama = $this->model1->poin_lama($id_customer);
		$ulang_tahun = $this->model1->ulang_tahun($id_customer);

		//update poin customer setelah transaksi 
		if ($kategori_member == 2) {
			if ($ulang_tahun == date('Y-m-d')) {
				$data_poin = array(
					"point" => $poin_lama + ($poin * 2)
				);
			} else {
				$data_poin = array(
					"point" => $poin_lama + $poin
				);
			}
		} else {
			$data_poin = array(
				"point" => 0
			);
		}

		$this->model_penjualan->updatePoinReimburs($id_customer, $data_poin);

		//kurangi poin jika nilai reimbursment lebih dari 0
		if ($point_reimburs > 0) {
			$old_poin = $this->model1->poin_lama($id_customer);

			$kurang_poin = array(
				"point" => $old_poin - $point_reimburs
			);

			$this->model_penjualan->updatePoinReimburs($id_customer, $kurang_poin);
		}

		if ($this->input->post("sub_account") == null) {
			$sub_account = "";
		} else {
			$sub_account = $this->input->post("sub_account");
		}

		$data_penjualan = array(
			"no_invoice" => $no_inv,
			"tipe_bayar" => $type_bayar,
			"sub_account" => $sub_account,
			"jatuh_tempo" => $this->input->post("jatuh_tempo"),
			"total" => $total,
			"ongkir" => $ongkir,
			"diskon" => $diskon + 0,
			"diskon_free" => $diskon_promosi,
			"poin_value" => $value_poin,
			"poin" => $poin,
			"hpp" => $hpp,
			"poin_reimburs" => $point_reimburs,
			"diskon_otomatis" => $diskon_otomatis,
			"jumlah_bayar" => $jumlah_bayar,
			"id_pic" => $id_user,
			"id_customer" => $id_customer,
			"keterangan" => $keterangan,
			"tanggal" => date('Y-m-d H:i:s'),
			"alamat" => $alamat,
			"id_provinsi" => $provinsi,
			"id_kabupaten" => $kabupaten,
			"id_kecamatan" => $kecamatan,
			"kontak_pengiriman" => $noHPPenerima,
			"nama_penerima" => $namaPenerima,
			"id_ekspedisi" => $ekspedisi,
			"id_toko" => $this->global['idStore']
		);

		$this->model_penjualan->insertApInvoiceNumber($data_penjualan);

		$affect = $this->db->affected_rows();

		//dapatkan tipe customer
		$tipe_customer = $this->model1->tipe_customer($id_customer);



		//cek tipe bayar, jika 1 = piutang dan sisipkan ke tabel piutag

		if ($type_bayar == 5) {
			$data_piutang = array(
				"no_invoice" => $no_inv,
				"status" => 0, //0 = TERBAYAR, 1 = LUNAS,
				"jatuh_tempo" => $this->input->post("jatuh_tempo")
			);
			$this->model_penjualan->insertPiutangInvoice($data_piutang);
		}

		// view cart
		$viewCart = $this->model_penjualan->dataCartPending($noCart);

		foreach ($viewCart as $row) {
			$sku = $row->id_produk;
			$harga = $row->harga;
			$hpp = $row->hpp;
			$qty = $row->qty;
			$diskon_item = $row->diskon;
			$disc_supplier = $row->disc_supplier;
			$tebusmurah = $row->tebusmurah;

			$data_item[] = array(
				"no_invoice" => $no_inv,
				"id_produk" => $sku,
				"hpp" => $hpp,
				"harga_jual" => $harga,
				"diskon" => $diskon_item,
				"disc_supplier" => $disc_supplier,
				"tebusmurah" => $tebusmurah,
				"qty" => $qty,
				"tanggal" => date('Y-m-d')
			);

			$id_store = $this->global['idStore'];

			//kurangi stok di gudang utama
			$stok_lama = $this->model1->get_stok_lama_produk_store($sku, $id_store);
			$new_stok = $stok_lama - $qty;

			//UPDATE STOK BARU
			$data_update = array(
				"stok" => $new_stok
			);

			$this->model_penjualan->updateStokStore($sku, $id_store, $data_update);
		}


		$this->model_penjualan->insertBatch($data_item);
		$this->model_penjualan->hapusTrxTemp($noCart);

		$dataCartTempNo = array(
			"status" => 1
		);

		$this->model_penjualan->updateCartTempStatus($noCart, $dataCartTempNo);
		echo $no_inv;
	}

	function spinner()
	{
		echo "<img src='" . base_url('assets/loading.gif') . "'/>";
	}

	function cekNoMemberIfDuplicate()
	{
		$noMember = $_POST['noMember'];
		$statement = $this->model_penjualan->cekNoMemberIfDuplicate($noMember);

		if ($statement > 0) {
			echo 1;
		} else {
			echo 0;
		}
	}

	function simpanMember()
	{
		$noMember = $_POST['noMember'];
		$cek = $this->model_penjualan->cekNoMemberIfDuplicate($noMember);
		if ($cek > 0) {
			echo 0;

		} else {
			$namaCustomer = $_POST['namaCustomer'];
			$kontak = $_POST['kontak'];
			$email = $_POST['email'];
			$tanggalLahir = $_POST['tanggalLahir'];
			$kategoriCustomer = $_POST['kategoriCustomer'];
			$diskonMember = $_POST['diskonMember'];
			$alamat = $_POST['alamat'];
			$provinsi = $_POST['provinsi'];
			$kabupaten = $_POST['kabupaten'];
			$kecamatan = $_POST['kecamatan'];

			$dataCustomer = array(
				"id_customer" => $noMember,
				"nama" => $namaCustomer,
				"kontak" => $kontak,
				"email" => $email,
				"tanggal_lahir" => $tanggalLahir,
				"diskon" => $diskonMember,
				"tanggal_gabung" => date('Y-m-d'),
				"point" => 0,
				"alamat" => $alamat,
				"id_provinsi" => $provinsi,
				"id_kabupaten" => $kabupaten,
				"id_kecamatan" => $kecamatan,
				"kategori" => $kategoriCustomer,
				"no_kartu" => $noMember,
			);

			$simpan = $this->model_penjualan->simpanDataMember($dataCustomer);
			echo $simpan;
		}

	}

	function viewAlamatCustomer()
	{
		$idCustomer = $_POST['idCustomer'];
		$data['provinsi'] = $this->db->get("ae_provinsi");
		$data['ekspedisi'] = $this->db->get("ap_ekspedisi")->result();
		$data['dataCustomer'] = $this->model_penjualan->customerRow($idCustomer);
		$idUser = $this->global['idUser'];
		$data['ongkir'] = $this->model_penjualan->viewOngkir($idUser);
		$this->load->view("penjualan/viewAlamatCustomer", $data);
	}

	function emptyAlamatCust()
	{
		$data['provinsi'] = $this->db->get("ae_provinsi");
		$data['ekspedisi'] = $this->db->get("ap_ekspedisi")->result();
		$idUser = $this->global['idUser'];
		$data['ongkir'] = $this->model_penjualan->viewOngkir($idUser);
		$this->load->view("penjualan/alamatEmpty", $data);
	}

	function export_to_json()
	{
		$id_store = $this->input->get('id_store') ? $this->input->get('id_store') : $this->global['idStore'];
		$product_data = $this->model1->get_product_data($id_store);
		header('Content-Type: application/json');
		if (empty($product_data)) {
			echo json_encode(array('message' => 'Tidak ada data produk untuk diekspor.'));
			return;
		}
		if ($this->input->get('inline') != '1') {
			header('Content-Disposition: attachment; filename="products.json"');
		}
		echo json_encode($product_data, JSON_PRETTY_PRINT);
	}

	function export_users_store_json()
	{
		$id_store = $this->input->get('id_store') ? $this->input->get('id_store') : $this->global['idStore'];
		header('Content-Type: application/json');
		$users = $this->db->query(
			'SELECT id, username, active, first_name, last_name, toko FROM users WHERE toko = ? AND active = 1',
			array($id_store)
		)->result_array();
		$store = $this->db->query(
			'SELECT id_store, store, alamat, otp_pwa FROM ap_store WHERE id_store = ?',
			array($id_store)
		)->row_array();
		if (empty($store)) {
			echo json_encode(array('message' => 'Tidak ada data toko untuk diekspor.'));
			return;
		}
		if ($this->input->get('inline') != '1') {
			header('Content-Disposition: attachment; filename="users_store.json"');
		}
		echo json_encode(array('users' => $users, 'store' => $store), JSON_PRETTY_PRINT);
	}

	private function _payment_accounts()
	{
		$this->db->select('id_payment_account, id_payment_type, account, surcharge, urutan');
		$this->db->from('ap_payment_account');
		$this->db->order_by('id_payment_type', 'ASC');
		$this->db->order_by('urutan', 'ASC');
		$q = $this->db->get();
		$out = array();
		foreach ($q->result() as $r) {
			$out[] = array(
				'id_payment_account' => (string) $r->id_payment_account,
				'id_payment_type' => (string) $r->id_payment_type,
				'account' => $r->account,
				'surcharge' => isset($r->surcharge) ? (string) $r->surcharge : '0',
				'urutan' => isset($r->urutan) ? (string) $r->urutan : '0',
				'id_account_journal' => '0',
			);
		}
		return $out;
	}

	private function _penjualan_sql_sync_offline()
	{
		header('Content-Type: text/plain; charset=utf-8');
		$idUserPost = $this->input->post('idUser');
		$idStorePost = $this->input->post('idStore');
		if ((string) $idUserPost !== (string) $this->global['idUser'] || (string) $idStorePost !== (string) $this->global['idStore']) {
			echo 'Error: Sinkronisasi ditolak (sesi tidak cocok).';
			return;
		}
		$txJson = $this->input->post('transaction_json');
		if (empty($txJson)) {
			echo 'Error: Data transaksi kosong.';
			return;
		}
		$tx = json_decode($txJson, true);
		if (empty($tx) || empty($tx['transaction_id']) || empty($tx['items']) || !is_array($tx['items'])) {
			echo 'Error: Format transaksi tidak valid.';
			return;
		}
		$no_inv = $tx['transaction_id'];
		if ($this->db->where('no_invoice', $no_inv)->count_all_results('ap_invoice_number') > 0) {
			echo $no_inv;
			return;
		}
		$type_bayar = (int) $tx['payment_type'];
		if ($type_bayar === 6) {
			echo 'Error: Dual payment tidak didukung untuk sync offline.';
			return;
		}
		if ($type_bayar === 5) {
			echo 'Error: Piutang tidak didukung untuk sync offline.';
			return;
		}

		$id_user = (int) $this->global['idUser'];
		$id_store = (int) $this->global['idStore'];
		$viewCart = array();
		$hpp = 0;
		foreach ($tx['items'] as $item) {
			$qty = isset($item['quantity']) ? (int) $item['quantity'] : 0;
			if ($qty < 1) {
				continue;
			}
			$hppItem = isset($item['hpp']) ? (float) $item['hpp'] : 0;
			$hpp += $hppItem * $qty;
			$viewCart[] = (object) array(
				'id_produk' => trim($item['sku']),
				'harga' => (float) $item['price'],
				'hpp' => $hppItem,
				'qty' => $qty,
				'diskon' => isset($item['diskon']) ? (float) $item['diskon'] : 0,
				'disc_supplier' => isset($item['disc_supplier']) ? (float) $item['disc_supplier'] : 0,
				'tebusmurah' => isset($item['tebusmurah']) ? (float) $item['tebusmurah'] : 0,
				'no_promo' => isset($item['no_promo']) ? (int) $item['no_promo'] : 0,
			);
		}
		if (count($viewCart) === 0) {
			echo 'Error: Tidak ada item valid.';
			return;
		}

		$total = (float) $tx['total'];
		$diskon = isset($tx['diskon']) ? (float) $tx['diskon'] : 0;
		$diskon_promosi = 0;
		$diskon_otomatis = 0;
		$value_poin = 0;
		$point_reimburs = 0;
		$ongkir = 0;
		$surcharge = 0;
		$sub_account = isset($tx['payment_account']) && $tx['payment_account'] !== '' ? $tx['payment_account'] : '';
		$keterangan = isset($tx['keterangan']) ? $tx['keterangan'] : '';
		$grandTotal = ($diskon > 0) ? ($total - $diskon) : $total;
		$jumlah_bayar = $grandTotal;
		$id_customer = !empty($tx['member']) ? trim($tx['member']) : null;

		$voucher_off = isset($tx['voucher']) ? (float) $tx['voucher'] : 0;
		$diskon_voucher_total_off = $voucher_off;
		$dv_m_off = $voucher_off;
		$dv_f_off = 0;
		$diskon_buy1get3_off = isset($tx['diskon_buy1get3']) ? (float) $tx['diskon_buy1get3'] : 0;
		$header_no_promo_off = isset($tx['no_promo']) ? (int) $tx['no_promo'] : 0;
		if ($header_no_promo_off < 1) {
			$header_no_promo_off = (int) $this->model_penjualan->getHeaderNoPromoForInvoice($id_user, $dv_m_off, $dv_f_off, $diskon_buy1get3_off);
		}

		$total_transaksi = $total - ($diskon + $diskon_promosi + $value_poin + $diskon_otomatis);
		$poin_pembelian = $this->model1->poin_pembelian();
		$nilai_pembelian = $this->model1->nilai_pembelian();
		$poin = ($nilai_pembelian > 0 && $id_customer) ? floor(($total_transaksi / $nilai_pembelian) * $poin_pembelian) : 0;

		$data_penjualan = array(
			'no_invoice' => $no_inv,
			'tipe_bayar' => $type_bayar,
			'sub_account' => $sub_account,
			'jatuh_tempo' => '',
			'total' => $total,
			'surcharge' => $surcharge,
			'ongkir' => $ongkir,
			'diskon' => $diskon + 0,
			'diskon_free' => $diskon_promosi,
			'hpp' => $hpp,
			'poin_value' => $value_poin,
			'poin' => $poin,
			'poin_reimburs' => $point_reimburs,
			'diskon_otomatis' => $diskon_otomatis,
			'voucher' => $voucher_off,
			'no_promo' => $header_no_promo_off,
			'seri_voucher' => 0,
			'jumlah_bayar' => $jumlah_bayar,
			'id_pic' => $id_user,
			'id_sales' => 0,
			'id_customer' => $id_customer,
			'keterangan' => $keterangan,
			'tanggal' => date('Y-m-d H:i:s'),
			'alamat' => '',
			'id_provinsi' => '',
			'id_kabupaten' => '',
			'id_kecamatan' => '',
			'kontak_pengiriman' => '',
			'nama_penerima' => '',
			'id_ekspedisi' => '',
			'status' => 0,
			'no_resi' => '',
			'Uploaded' => 0,
			'id_toko' => $id_store,
		);

		$data_dual_payment = array(
			array(
				'no_invoice' => $no_inv,
				'payment_type' => $type_bayar,
				'payment_account' => $sub_account,
				'total' => $total_transaksi,
				'id_pic' => $id_user,
				'tanggal' => date('Y-m-d H:i:s'),
				'id_toko' => $id_store,
			),
		);

		$data_item = array();
		$data_kartu = array();
		foreach ($viewCart as $row) {
			$sku = $row->id_produk;
			$harga = $row->harga;
			$hppRow = $row->hpp;
			$qty = $row->qty;
			$diskon_item = $row->diskon;
			$disc_supplier = $row->disc_supplier;
			$tebusmurah = $row->tebusmurah;
			$line_no_promo = isset($row->no_promo) ? (int) $row->no_promo : 0;
			if ($line_no_promo < 1) {
				$line_no_promo = (int) $this->model_penjualan->resolveDiscountRuleNoPromo($sku, $id_store, $qty);
			}

			$data_item[] = array(
				'no_invoice' => $no_inv,
				'id_produk' => $sku,
				'hpp' => $hppRow,
				'harga_jual' => $harga,
				'diskon' => $diskon_item,
				'disc_supplier' => $disc_supplier,
				'tebusmurah' => $tebusmurah,
				'no_promo' => $line_no_promo,
				'qty' => $qty,
				'tanggal' => date('Y-m-d'),
				'id_toko' => $id_store,
			);
			$data_kartu[] = array(
				'id_store' => $id_store,
				'id_produk' => $sku,
				'qty' => '-' . $qty,
				'harga' => $harga,
				'hpp' => $hppRow,
				'tanggal' => date('Y-m-d H:i:s'),
				'tipe' => 'Penjualan',
				'no_transaksi' => $no_inv,
				'id_pic' => $id_user,
			);
		}

		$ppn_rate = 0.11;
		$acc_map = $this->Accounting_model->get_mapping_list();
		$acc = (object) array(
			'penjualan' => $acc_map['PENJUALAN'],
			'persediaan' => $acc_map['PERSEDIAAN'],
			'hpp' => $acc_map['HPP'],
			'ongkir' => $acc_map['ONGKIR'],
			'surcharge' => $acc_map['SURCHARGE'],
			'piutang' => $acc_map['PIUTANG'],
			'diskon' => $acc_map['DISKON'],
			'ppn_keluaran' => $acc_map['PPN_KELUARAN'],
			'kas_default' => $acc_map['KAS_KASIR_PENDING'],
		);

		$total_dpp_penjualan = 0;
		$total_ppn_keluaran = 0;
		foreach ($viewCart as $item) {
			$prod = $this->db->select('is_ppn')->get_where('ap_produk', array('id_produk' => $item->id_produk))->row();
			$is_ppn = ($prod && $prod->is_ppn == 1) ? true : false;
			$subtotal_item = $item->qty * $item->harga;
			if ($is_ppn) {
				$dpp_item = round($subtotal_item / (1 + $ppn_rate), 4);
				$ppn_item = $subtotal_item - $dpp_item;
				$total_dpp_penjualan += $dpp_item;
				$total_ppn_keluaran += $ppn_item;
			} else {
				$total_dpp_penjualan += $subtotal_item;
			}
		}

		$total_potongan = $diskon + $diskon_promosi + $value_poin + $diskon_otomatis;
		$entries = array();
		$nilai_kas = ($total + $surcharge + $ongkir) - $total_potongan;
		$map = $this->db->get_where('ap_payment_account', array('id_payment_account' => $sub_account))->row();
		$entries[] = $this->Accounting_model->entry_line($map ? $map->account_id : $acc->kas_default, $nilai_kas, 0);

		if ($total_potongan > 0) {
			$entries[] = $this->Accounting_model->entry_line($acc->diskon, $total_potongan, 0);
		}
		$entries[] = $this->Accounting_model->entry_line($acc->penjualan, 0, $total_dpp_penjualan);
		if ($total_ppn_keluaran > 0) {
			$entries[] = $this->Accounting_model->entry_line($acc->ppn_keluaran, 0, $total_ppn_keluaran);
		}
		if ($ongkir > 0) {
			$entries[] = $this->Accounting_model->entry_line($acc->ongkir, 0, $ongkir);
		}
		if ($surcharge > 0) {
			$entries[] = $this->Accounting_model->entry_line($acc->surcharge, 0, $surcharge);
		}
		if ($hpp > 0) {
			$entries[] = $this->Accounting_model->entry_line($acc->hpp, $hpp, 0);
			$entries[] = $this->Accounting_model->entry_line($acc->persediaan, 0, $hpp);
		}

		$journalHdr = $this->Accounting_model->journal_header(
			$this->global['idStore'],
			$no_inv,
			'Penjualan Retail Offline - ' . $no_inv,
			'Sales'
		);

		$this->db->trans_start();

		if (!$this->model_penjualan->insertApInvoiceNumber($data_penjualan)) {
			$this->db->trans_rollback();
			$this->_log_sync_offline_db_error('insertApInvoiceNumber');
			echo 'Error: Gagal simpan header invoice (ap_invoice_number).';
			return;
		}
		if ($this->db->affected_rows() < 1) {
			$this->db->trans_rollback();
			$this->_log_sync_offline_db_error('insertApInvoiceNumber affected_rows');
			echo 'Error: Header invoice tidak tersimpan.';
			return;
		}

		if ($id_customer) {
			$poin_lama = $this->model1->poin_lama($id_customer);
			$data_poin = array('point' => $poin_lama + $poin);
			$this->model_penjualan->updatePoinReimburs($id_customer, $data_poin);
		}

		if (!$this->model_penjualan->insertApInvoicePayment($data_dual_payment)) {
			$this->db->trans_rollback();
			$this->_log_sync_offline_db_error('insertApInvoicePayment');
			echo 'Error: Gagal simpan pembayaran (ap_invoice_payment).';
			return;
		}

		foreach ($viewCart as $row) {
			$sku = $row->id_produk;
			$qty = $row->qty;
			$stok_lama = $this->model1->get_stok_lama_produk_store($sku, $id_store);
			$new_stok = $stok_lama - $qty;
			$data_update = array(
				'stok' => $new_stok,
				'last_sales' => date('Y-m-d'),
			);
			$this->model_penjualan->updateStokStore($sku, $id_store, $data_update);
		}

		if (!$this->model_penjualan->insertBatch($data_item)) {
			$this->db->trans_rollback();
			$this->_log_sync_offline_db_error('insertBatch ap_invoice_item');
			echo 'Error: Gagal simpan detail item (ap_invoice_item).';
			return;
		}

		if (!$this->model1->insertKartuStok($data_kartu)) {
			$this->db->trans_rollback();
			$this->_log_sync_offline_db_error('insertKartuStok');
			echo 'Error: Gagal simpan kartu stok.';
			return;
		}

		$jr = $this->Accounting_model->create_journal_entry($journalHdr, $entries);
		if (empty($jr['status'])) {
			$this->db->trans_rollback();
			log_message('error', 'penjualan_sql_sync_offline journal: ' . (isset($jr['message']) ? $jr['message'] : ''));
			echo 'Error: Gagal buat jurnal akuntansi.';
			return;
		}

		if ($total_ppn_keluaran > 0) {
			$this->Accounting_model->log_tax($no_inv, $acc->ppn_keluaran, $total_ppn_keluaran, 11, 'Penjualan Retail Offline - ' . $no_inv);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->_log_sync_offline_db_error('trans_complete');
			echo 'Error: Transaksi database gagal (rollback).';
			return;
		}

		echo $no_inv;
	}

	private function _log_sync_offline_db_error($ctx)
	{
		$err = $this->db->error();
		if (!empty($err['message'])) {
			log_message('error', 'penjualan_sql_sync_offline ' . $ctx . ': ' . $err['message']);
		}
	}
}
