<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_penjualan extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	function cekBuyXbayarN($idStore){
		$tanggal = date('Y-m-d');
		$jam = date('H:i:s');
		$hari = date('w');
		$HariID = '.'.$hari.'.';

		$this->db->select("no_promo, jumlah_bayar, jumlah_gratis, discount_percent, discount_rp, id_toko, allowMultipleBundle");
		$this->db->from("promo_buy1get3_new");
		$this->db->where("promo_buy1get3_new.id_toko", $idStore);
		$this->db->where("'$tanggal' BETWEEN tanggalMulai AND tanggalSelesai");
		$this->db->where("'$jam' BETWEEN JamMulai AND JamSelesai");
		$this->db->where("LOCATE('$HariID', HariID) > 0");
		$this->db->order_by("promo_buy1get3_new.discount_rp", "desc");
		$this->db->order_by("promo_buy1get3_new.discount_percent", "desc");

		return $this->db->get()->result();
	}
	function viewVoucherNoPromo($idUser)
	{
		$this->db->select("no_promo");
		$this->db->from("ap_cart_diskon_voucher");
		$this->db->where("idUser", $idUser);
		$query = $this->db->get()->result();

		$no_promo = [];
		foreach ($query as $row) {
			$no_promo[] = $row->no_promo;
		}
		return json_encode($no_promo);
	}
	function cekBundling($idStore)
	{
		$tanggal = date('Y-m-d');
		$jam = date('H:i:s');
		$hari = date('w');
		$HariID = '.' . $hari . '.';
		$this->db->select(array("no_promo", "bundling_products", "id_toko", "diskon"));
		$this->db->from("promo_bundling");
		$this->db->where("promo_bundling.id_toko", $idStore);
		$this->db->where("'$tanggal' BETWEEN tanggalMulai and tanggalSelesai", NULL, FALSE);
		$this->db->where("'$jam' BETWEEN JamMulai and JamSelesai", NULL, FALSE);
		$this->db->where("LOCATE('$HariID', HariID)>0", NULL, FALSE);
		$this->db->where("promo_bundling.status", 1);
		$query = $this->db->get()->result();
		return $query;
	}
	function tipeBayar()
	{
		$this->db->select("*");
		$this->db->from("ap_payment_type");
		$this->db->order_by("urutan", "ASC");
		return $this->db->get();
	}
	function cekPass($username)
	{
		$this->db->select(array("user_approver.pass"));
		$this->db->from("user_approver");
		$this->db->where("user_approver.username", $username);
		$this->db->limit(1);
		$query = $this->db->get()->row();
		return $query->pass;
	}
	function deleteApprover($ip)
	{
		$this->db->delete("ap_cart_approved", array("ip" => $ip));
	}
	function insertCartApproved($data)
	{
		$this->db->insert("ap_cart_approved", $data);
	}
	function getUserFinger($user_id)
	{
		$this->db->select(array("user_approver.finger_data"));
		$this->db->from("user_approver");
		$this->db->like("user_approver.username", $user_id);
		$this->db->limit(1);
		return $this->db->get()->row();
	}
	function cekPriceOnCartBuy1($no_promo, $idUser, $id_produk)
	{
		$this->db->select(array("harga"));
		$this->db->from("ap_cart");
		$this->db->join("ap_produk_buy1get3_rules", "ap_produk_buy1get3_rules.id_produk= ap_cart.id_produk");
		$this->db->where("ap_produk_buy1get3_rules.no_promo", $no_promo);
		$this->db->where("ap_cart.quantity>=", 1);
		$this->db->where("ap_cart.id_user", $idUser);
		$this->db->order_by("ap_cart.harga", "ASC");
		$this->db->limit(1, 0);
		$query = $this->db->get()->row();
		return $query->harga;
	}
	function viewSurchargeSet($SubAccount, $type_bayar)
	{
		$this->db->select("surcharge");
		$this->db->from("ap_payment_account");
		$this->db->where("id_payment_account", $SubAccount);
		$this->db->where("id_payment_type", $type_bayar);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->surcharge;
		}
	}

	function updateVoucherSeri($seri_voucher, $data)
	{
		$this->db->where("id_voucher", $seri_voucher);
		$this->db->update("voucher_item", $data);
	}

	function insertVoucherFisik($data_voucher)
	{
		$this->db->insert("ap_cart_voucher_fisik", $data_voucher);
	}
	function hapusCartVoucherFisik($idUser)
	{
		$this->db->delete("ap_cart_voucher_fisik", array("idUser" => $idUser));
	}
	function cekVoucherFisik($seri_voucher)
	{
		$this->db->select(array(
			"voucher_item.id_voucher",
			"voucher_item.nilai",
			"voucher_item.nilai_tipe",
			"voucher_item.brand_ids",
			"voucher_item.produk_ids",
			"voucher_generate.minimal_belanja",
		));
		$this->db->from("voucher_item");
		$this->db->join("voucher_generate", "voucher_generate.id_generate = voucher_item.id_generate");
		$this->db->where("voucher_item.id_voucher", $seri_voucher);
		$this->db->where("voucher_item.terpakai", 0);
		$this->db->where("'" . date('Y-m-d H:i:s') . "' between voucher_item.berlaku_mulai and voucher_item.berlaku_selesai");
		return $this->db->get()->result();
	}

	/** Physical voucher row(s) by seri; used after checkout / on return (no terpakai/date filter). */
	function getVoucherFisikRowsForRecalc($seri_voucher)
	{
		$seri_voucher = trim((string) $seri_voucher);
		if ($seri_voucher === '' || (isset($seri_voucher[0]) && $seri_voucher[0] === '[')) {
			return array();
		}
		$this->db->select(array(
			"voucher_item.id_voucher",
			"voucher_item.nilai",
			"voucher_item.nilai_tipe",
			"voucher_item.brand_ids",
			"voucher_item.produk_ids",
			"voucher_generate.minimal_belanja",
		));
		$this->db->from("voucher_item");
		$this->db->join("voucher_generate", "voucher_generate.id_generate = voucher_item.id_generate");
		$this->db->where("voucher_item.id_voucher", $seri_voucher);
		return $this->db->get()->result();
	}

	/** Sum qty*harga_jual on invoice lines matching voucher brand/SKU scope (same rules as eligibleSubtotalVoucherFisik). */
	function eligibleSubtotalVoucherFisikOnInvoice($no_invoice, $brand_ids_csv = null, $produk_ids_csv = null)
	{
		$brands = array();
		if ($brand_ids_csv !== null && trim((string) $brand_ids_csv) !== '') {
			$brands = array_values(array_filter(array_map('intval', explode(',', $brand_ids_csv))));
		}
		$produks = array();
		if ($produk_ids_csv !== null && trim((string) $produk_ids_csv) !== '') {
			foreach (explode(',', $produk_ids_csv) as $p) {
				$p = trim((string) $p);
				if ($p !== '') {
					$produks[] = $p;
				}
			}
		}

		$this->db->select("COALESCE(SUM(ap_invoice_item.qty * ap_invoice_item.harga_jual), 0) AS eligible", false);
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk", "ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->where("ap_invoice_item.no_invoice", $no_invoice);

		if (count($brands) === 0 && count($produks) === 0) {
			// all lines
		} elseif (count($brands) > 0 && count($produks) === 0) {
			$this->db->where_in("ap_produk.id_brand", $brands);
		} elseif (count($brands) === 0 && count($produks) > 0) {
			$this->db->where_in("ap_invoice_item.id_produk", $produks);
		} else {
			$this->db->where_in("ap_invoice_item.id_produk", $produks);
			$this->db->where_in("ap_produk.id_brand", $brands);
		}

		$row = $this->db->get()->row();
		return $row ? (float) $row->eligible : 0.0;
	}

	/**
	 * Recompute physical voucher discount from ap_invoice_number + ap_invoice_item (same rules as viewVoucherFisik).
	 * diskon_free on invoice already bundles promo + buy1get3 like penjualan_sql.
	 */
	function computePhysicalVoucherDiscountFromInvoiceState($no_invoice, $seri_voucher)
	{
		$rows = $this->getVoucherFisikRowsForRecalc($seri_voucher);
		if (empty($rows)) {
			return 0.0;
		}

		$this->db->select(array(
			"ongkir",
			"diskon",
			"diskon_free",
			"diskon_otomatis",
			"poin_value",
		));
		$this->db->from("ap_invoice_number");
		$this->db->where("no_invoice", $no_invoice);
		$inv = $this->db->get()->row();
		if (!$inv) {
			return 0.0;
		}

		$this->db->select("COALESCE(SUM(ap_invoice_item.qty * ap_invoice_item.harga_jual), 0) AS subtotal", false);
		$this->db->from("ap_invoice_item");
		$this->db->where("ap_invoice_item.no_invoice", $no_invoice);
		$rsub = $this->db->get()->row();
		$subtotal = $rsub ? (float) $rsub->subtotal : 0.0;

		$ongkir = floatval($inv->ongkir ?: 0);
		$diskonPeritem = floatval($inv->diskon_otomatis ?: 0);
		$diskonMember = floatval($inv->diskon ?: 0);
		$diskonPromosi = floatval($inv->diskon_free ?: 0);
		$poinReimburs = floatval($inv->poin_value ?: 0);

		$grandTotal = ($subtotal + $ongkir) - ($diskonPeritem + $diskonMember + $diskonPromosi + $poinReimburs);
		$grandTotalNoPerItem = ($subtotal + $ongkir) - ($diskonMember + $diskonPromosi + $poinReimburs);

		$diskonVoucher = 0.0;
		foreach ($rows as $row) {
			$mb = (isset($row->minimal_belanja) && $row->minimal_belanja !== null && $row->minimal_belanja !== '') ? floatval($row->minimal_belanja) : 0;
			if ($mb > 0 && $grandTotal < $mb) {
				continue;
			}

			$tipe = (isset($row->nilai_tipe) && $row->nilai_tipe === 'percent') ? 'percent' : 'rp';
			$bcsv = isset($row->brand_ids) ? $row->brand_ids : null;
			$pcsv = isset($row->produk_ids) ? $row->produk_ids : null;
			$hasScope = (trim((string) $bcsv) !== '' || trim((string) $pcsv) !== '');

			$eligibleBase = $this->eligibleSubtotalVoucherFisikOnInvoice($no_invoice, $bcsv, $pcsv);
			if ($hasScope && $eligibleBase <= 0) {
				continue;
			}

			$nilaiNum = floatval($row->nilai);
			if ($tipe === 'percent') {
				$diskonAmount = floor($eligibleBase * $nilaiNum / 100);
			} else {
				$diskonAmount = min($nilaiNum, $eligibleBase);
			}

			if ($diskonAmount <= 0) {
				continue;
			}

			$simulasi = $grandTotalNoPerItem - $diskonVoucher - $diskonAmount;
			if ($simulasi < 0) {
				continue;
			}

			$diskonVoucher += $diskonAmount;
		}

		return $diskonVoucher;
	}

	/** Sum qty*harga for cart lines matching voucher brand/SKU scope (gross). */
	function eligibleSubtotalVoucherFisik($idUser, $brand_ids_csv = null, $produk_ids_csv = null)
	{
		$brands = array();
		if ($brand_ids_csv !== null && trim((string) $brand_ids_csv) !== '') {
			$brands = array_values(array_filter(array_map('intval', explode(',', $brand_ids_csv))));
		}
		$produks = array();
		if ($produk_ids_csv !== null && trim((string) $produk_ids_csv) !== '') {
			foreach (explode(',', $produk_ids_csv) as $p) {
				$p = trim((string) $p);
				if ($p !== '') {
					$produks[] = $p;
				}
			}
		}

		$this->db->select("COALESCE(SUM(ap_cart.quantity * ap_cart.harga), 0) AS eligible", false);
		$this->db->from("ap_cart");
		$this->db->join("ap_produk", "ap_produk.id_produk = ap_cart.id_produk");
		$this->db->where("ap_cart.id_user", $idUser);

		if (count($brands) === 0 && count($produks) === 0) {
			// all lines
		} elseif (count($brands) > 0 && count($produks) === 0) {
			$this->db->where_in("ap_produk.id_brand", $brands);
		} elseif (count($brands) === 0 && count($produks) > 0) {
			$this->db->where_in("ap_cart.id_produk", $produks);
		} else {
			$this->db->where_in("ap_cart.id_produk", $produks);
			$this->db->where_in("ap_produk.id_brand", $brands);
		}

		$row = $this->db->get()->row();
		return $row ? (float) $row->eligible : 0.0;
	}

	/** Zero ap_cart.diskon only on lines in voucher scope (same rules as eligibleSubtotalVoucherFisik); empty scope = all lines. */
	function clearCartDiskonPeritemVoucherScope($idUser, $brand_ids_csv = null, $produk_ids_csv = null)
	{
		$brands = array();
		if ($brand_ids_csv !== null && trim((string) $brand_ids_csv) !== '') {
			$brands = array_values(array_filter(array_map('intval', explode(',', $brand_ids_csv))));
		}
		$produks = array();
		if ($produk_ids_csv !== null && trim((string) $produk_ids_csv) !== '') {
			foreach (explode(',', $produk_ids_csv) as $p) {
				$p = trim((string) $p);
				if ($p !== '') {
					$produks[] = $p;
				}
			}
		}

		if (count($brands) === 0 && count($produks) === 0) {
			$this->db->where("id_user", $idUser);
			$this->db->update("ap_cart", array("diskon" => 0));
			return;
		}

		$this->db->select("ap_cart.id");
		$this->db->from("ap_cart");
		$this->db->join("ap_produk", "ap_produk.id_produk = ap_cart.id_produk");
		$this->db->where("ap_cart.id_user", $idUser);

		if (count($brands) > 0 && count($produks) === 0) {
			$this->db->where_in("ap_produk.id_brand", $brands);
		} elseif (count($brands) === 0 && count($produks) > 0) {
			$this->db->where_in("ap_cart.id_produk", $produks);
		} else {
			$this->db->where_in("ap_cart.id_produk", $produks);
			$this->db->where_in("ap_produk.id_brand", $brands);
		}

		$rows = $this->db->get()->result();
		if (empty($rows)) {
			return;
		}
		$ids = array();
		foreach ($rows as $r) {
			$ids[] = $r->id;
		}
		$this->db->where_in("id", $ids);
		$this->db->where("id_user", $idUser);
		$this->db->update("ap_cart", array("diskon" => 0));
	}

	/** Active struk earn rules for store (voucher_generate.voucher_struk=1). */
	function listVoucherStrukRulesActive($id_toko)
	{
		$now = date('Y-m-d H:i:s');
		$this->db->from("voucher_generate");
		$this->db->where("voucher_struk", 1);
		$this->db->where("id_toko", (string) $id_toko);
		$this->db->where("start_voucher_struk <=", $now);
		$this->db->where("end_voucher_struk >=", $now);
		return $this->db->get()->result();
	}

	/** Gross subtotal on invoice lines matching struk "dapat voucher" brand/SKU/subkategori/kategori scope (same rules as eligibleSubtotalVoucherFisik for brand/SKU). */
	function invoiceStrukQualifyingSubtotal($no_invoice, $brand_ids_csv = null, $produk_ids_csv = null, $subkategori_ids_csv = null, $kategori_id_voucher_struk = null)
	{
		$brands = array();
		if ($brand_ids_csv !== null && trim((string) $brand_ids_csv) !== '') {
			$brands = array_values(array_filter(array_map('intval', explode(',', $brand_ids_csv))));
		}
		$produks = array();
		if ($produk_ids_csv !== null && trim((string) $produk_ids_csv) !== '') {
			foreach (explode(',', $produk_ids_csv) as $p) {
				$p = trim((string) $p);
				if ($p !== '') {
					$produks[] = $p;
				}
			}
		}
		$subs = array();
		if ($subkategori_ids_csv !== null && trim((string) $subkategori_ids_csv) !== '') {
			$subs = array_values(array_filter(array_map('intval', explode(',', $subkategori_ids_csv))));
		}
		$has_sub = count($subs) > 0;
		$kid = (int) $kategori_id_voucher_struk;
		$has_kat = $kid > 0;

		$this->db->select("COALESCE(SUM(ap_invoice_item.qty * ap_invoice_item.harga_jual), 0) AS eligible", false);
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk", "ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->where("ap_invoice_item.no_invoice", $no_invoice);

		if (count($brands) === 0 && count($produks) === 0) {
			if ($has_sub) {
				$this->db->where_in("ap_produk.id_subkategori", $subs);
			}
			if ($has_kat) {
				$this->db->where("ap_produk.id_kategori", $kid);
			}
		} elseif (count($brands) > 0 && count($produks) === 0) {
			$this->db->where_in("ap_produk.id_brand", $brands);
			if ($has_sub) {
				$this->db->where_in("ap_produk.id_subkategori", $subs);
			}
			if ($has_kat) {
				$this->db->where("ap_produk.id_kategori", $kid);
			}
		} elseif (count($brands) === 0 && count($produks) > 0) {
			$this->db->where_in("ap_invoice_item.id_produk", $produks);
			if ($has_sub) {
				$this->db->where_in("ap_produk.id_subkategori", $subs);
			}
			if ($has_kat) {
				$this->db->where("ap_produk.id_kategori", $kid);
			}
		} else {
			$this->db->where_in("ap_invoice_item.id_produk", $produks);
			$this->db->where_in("ap_produk.id_brand", $brands);
			if ($has_sub) {
				$this->db->where_in("ap_produk.id_subkategori", $subs);
			}
			if ($has_kat) {
				$this->db->where("ap_produk.id_kategori", $kid);
			}
		}

		$row = $this->db->get()->row();
		return $row ? (float) $row->eligible : 0.0;
	}

	function countVoucherItemByGenerate($id_generate)
	{
		$this->db->from("voucher_item");
		$this->db->where("id_generate", $id_generate);
		return (int) $this->db->count_all_results();
	}

	function strukVoucherAlreadyIssuedForInvoice($no_invoice, $id_generate)
	{
		$this->db->from("voucher_item");
		$this->db->where("issued_no_invoice", $no_invoice);
		$this->db->where("id_generate", $id_generate);
		return $this->db->count_all_results() > 0;
	}

	function voucherItemIdExists($id_voucher)
	{
		$this->db->from("voucher_item");
		$this->db->where("id_voucher", $id_voucher);
		return $this->db->count_all_results() > 0;
	}

	/** Insert struk voucher_item when invoice qualifies; idempotent per (no_invoice, id_generate). */
	function issueVoucherStrukForInvoice($no_invoice, $id_toko)
	{
		$rules = $this->listVoucherStrukRulesActive($id_toko);
		if (empty($rules)) {
			return;
		}

		$huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		foreach ($rules as $rule) {
			$id_gen = $rule->id_generate;
			if ($this->strukVoucherAlreadyIssuedForInvoice($no_invoice, $id_gen)) {
				continue;
			}

			$bget = isset($rule->brand_ids_voucher_struk) ? $rule->brand_ids_voucher_struk : null;
			$pget = isset($rule->produk_ids_voucher_struk) ? $rule->produk_ids_voucher_struk : null;
			$sget = isset($rule->subkategori_ids_voucher_struk) ? $rule->subkategori_ids_voucher_struk : null;
			$kget = isset($rule->kategori_id_voucher_struk) ? $rule->kategori_id_voucher_struk : null;
			$qual = $this->invoiceStrukQualifyingSubtotal($no_invoice, $bget, $pget, $sget, $kget);
			$scopeGet = (trim((string) $bget) !== '' || trim((string) $pget) !== '' || trim((string) $sget) !== '' || (int) $kget > 0);
			if ($scopeGet && $qual <= 0) {
				continue;
			}

			$min_get = (isset($rule->min_get_voucher_struk) && $rule->min_get_voucher_struk !== null && $rule->min_get_voucher_struk !== '')
				? (float) $rule->min_get_voucher_struk : 0.0;
			if ($min_get > 0 && $qual < $min_get) {
				continue;
			}

			$jml = isset($rule->jml_voucher) ? (int) $rule->jml_voucher : 0;
			if ($jml > 0 && $this->countVoucherItemByGenerate($id_gen) >= $jml) {
				continue;
			}

			$kode = '';
			for ($t = 0; $t < 25; $t++) {
				$cand = substr(str_shuffle($huruf), 0, 8);
				if (!$this->voucherItemIdExists($cand)) {
					$kode = $cand;
					break;
				}
			}
			if ($kode === '') {
				continue;
			}

			$data_item = array(
				"id_voucher" => $kode,
				"id_generate" => $id_gen,
				"berlaku_mulai" => $rule->berlaku_mulai,
				"berlaku_selesai" => $rule->berlaku_selesai,
				"nilai" => $rule->nilai,
				"nilai_tipe" => isset($rule->nilai_tipe) && $rule->nilai_tipe === 'percent' ? 'percent' : 'rp',
				"brand_ids" => (isset($rule->brand_ids) && trim((string) $rule->brand_ids) !== '') ? $rule->brand_ids : null,
				"produk_ids" => (isset($rule->produk_ids) && trim((string) $rule->produk_ids) !== '') ? $rule->produk_ids : null,
				"terpakai" => 0,
				"issued_no_invoice" => $no_invoice,
			);

			$this->db->insert("voucher_item", $data_item);
		}
	}

	function getVoucherStrukReceiptForInvoice($no_invoice)
	{
		$this->db->select("voucher_item.*, voucher_generate.nm_voucher, voucher_generate.minimal_belanja");
		$this->db->from("voucher_item");
		$this->db->join("voucher_generate", "voucher_generate.id_generate = voucher_item.id_generate");
		$this->db->where("voucher_item.issued_no_invoice", $no_invoice);
		$this->db->order_by("voucher_item.id_voucher", "ASC");
		return $this->db->get()->result();
	}

	/** Short human text for receipt (Indonesian). */
	function voucherStrukReceiptDescription($row)
	{
		$lines = array();
		$tipe = (isset($row->nilai_tipe) && $row->nilai_tipe === 'percent') ? 'percent' : 'rp';
		$nilai = isset($row->nilai) ? (float) $row->nilai : 0;
		if ($tipe === 'percent') {
			$lines[] = 'Diskon ' . (floor($nilai) == $nilai ? (string) (int) $nilai : number_format($nilai, 2, ',', '')) . '%';
		} else {
			$lines[] = 'Diskon Rp ' . number_format($nilai, 0, ',', '.');
		}

		$scope = array();
		if (isset($row->brand_ids) && trim((string) $row->brand_ids) !== '') {
			$ids = array_filter(array_map('intval', explode(',', $row->brand_ids)));
			if (!empty($ids)) {
				$this->db->select("brand");
				$this->db->from("brand");
				$this->db->where_in("id_brand", $ids);
				$bn = array();
				foreach ($this->db->get()->result() as $b) {
					$bn[] = $b->brand;
				}
				if (!empty($bn)) {
					$scope[] = 'Brand: ' . implode(', ', $bn);
				}
			}
		}
		if (isset($row->produk_ids) && trim((string) $row->produk_ids) !== '') {
			$ids = array();
			foreach (explode(',', $row->produk_ids) as $p) {
				$p = trim((string) $p);
				if ($p !== '') {
					$ids[] = $p;
				}
			}
			if (!empty($ids)) {
				$this->db->select("id_produk, nama_produk");
				$this->db->from("ap_produk");
				$this->db->where_in("id_produk", $ids);
				$this->db->limit(12);
				$pn = array();
				foreach ($this->db->get()->result() as $p) {
					$pn[] = $p->id_produk . ' ' . $p->nama_produk;
				}
				if (!empty($pn)) {
					$scope[] = 'SKU: ' . implode('; ', $pn);
				}
			}
		}
		if (!empty($scope)) {
			$lines[] = implode(' | ', $scope);
		}

		if (isset($row->berlaku_selesai) && $row->berlaku_selesai) {
			$lines[] = 'Berlaku s/d ' . date('d-m-Y H:i', strtotime($row->berlaku_selesai));
		}

		return implode("\n", $lines);
	}

	function diskonVoucherFisik($idUser)
	{
		$this->db->select("SUM(diskon) as diskon");
		$this->db->from("ap_cart_voucher_fisik");
		$this->db->where("idUser", $idUser);
		$this->db->group_by("idUser");
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}

	function insertDiskonVoucher($data_voucher)
	{
		$this->db->insert("ap_cart_diskon_voucher", $data_voucher);
	}

	function cekVoucher($idCustomer)
	{
		$this->db->select(array("k_voucer_belanja_member.id_voucer_member", "k_voucer_belanja_member.nominal", "k_voucer_belanja_member.poin"));
		$this->db->from("k_voucer_belanja_member");
		$this->db->join("k_voucer_belanja", "k_voucer_belanja.kode=k_voucer_belanja_member.kode");
		$this->db->where("k_voucer_belanja_member.id_customer", $idCustomer);
		$this->db->where("k_voucer_belanja_member.status", 'In');
		$this->db->where("'" . date('Y-m-d H:i:s') . "' between k_voucer_belanja.tgl_mulai and k_voucer_belanja.tgl_selesai");
		return $this->db->get()->result();
	}
	function diskonVoucher($idUser)
	{
		$this->db->select("SUM(diskon) as diskon");
		$this->db->from("ap_cart_diskon_voucher");
		$this->db->where("idUser", $idUser);
		$this->db->group_by("idUser");
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}
	function dataVoucher($idUser)
	{
		$this->db->select(array("no_promo", "diskon"));
		$this->db->from("ap_cart_diskon_voucher");
		$this->db->where("idUser", $idUser);
		return $this->db->get()->result();
	}

	function updateVoucherMember($no_promo, $id_customer, $dataupdate)
	{
		$this->db->where("id_voucer_member", $no_promo);
		$this->db->where("id_customer", $id_customer);
		$this->db->update("k_voucer_belanja_member", $dataupdate);
	}

	function insertSetoran($data_setor)
	{
		$this->db->insert("setoran_kasir", $data_setor);
	}
	function verifyApproval($user, $pass)
	{
		$this->db->select("user_approver.username as uname");
		$this->db->from("user_approver");
		$this->db->where("user_approver.username", $user);
		$this->db->where("user_approver.pass", $pass);
		$query = $this->db->get()->row();
		$setuju = empty($query->uname) ? 0 : 1;

		$respon = array("setuju" => $setuju);
		return $respon;
	}
	function verifyApprovalPass($user, $pass)
	{
		$this->db->select("user_approver.username as uname");
		$this->db->from("user_approver");
		$this->db->where("user_approver.username", $user);
		$this->db->where("user_approver.pass", md5($pass));
		$query = $this->db->get()->row();
		$setuju = empty($query->uname) ? 0 : 1;

		$respon = array("setuju" => $setuju);
		return $respon;
	}
	function cekHarga($sku, $idUser)
	{
		$this->db->select("ap_cart.harga as harga");
		$this->db->from("ap_cart");
		$this->db->where("ap_cart.id_produk", $sku);
		$this->db->where("ap_cart.id_user", $idUser);
		$query = $this->db->get()->row();
		return $query->harga;
	}
	function cekBuy1Get3Exists($no_promo, $idUser)
	{
		$this->db->select("ap_cart_diskon_buy1get3.no_promo as no_promo");
		$this->db->from("ap_cart_diskon_buy1get3");
		$this->db->where("ap_cart_diskon_buy1get3.no_promo", $no_promo);
		$this->db->where("ap_cart_diskon_buy1get3.idUser", $idUser);
		$query = $this->db->get()->row();
		return $query->no_promo;
	}
	function cekBuy1Get3ExistsPending($no_promo, $noCart)
	{
		$this->db->select("no_promo");
		$this->db->from("ap_cart_diskon_buy1get3_temp");
		$this->db->where("no_promo", $no_promo);
		$this->db->where("noCart", $noCart);
		$query = $this->db->get()->row();
		return $query->no_promo;
	}
	function cekIdBrand($sku)
	{
		$this->db->select("id_brand");
		$this->db->from("ap_produk");
		$this->db->where("id_produk", $sku);
		$query = $this->db->get()->row();
		return $query->id_brand;
	}
	function sumCartBrand($id_brand, $idUser)
	{
		$this->db->select("SUM(ap_cart.quantity*ap_cart.harga) as harga");
		$this->db->from("ap_cart");
		$this->db->join("ap_produk", "ap_produk.id_produk=ap_cart.id_produk");
		$this->db->where("ap_produk.id_brand", $id_brand);
		$this->db->where("ap_cart.id_user", $idUser);
		$query = $this->db->get()->row();
		return $query->harga;
	}
	function countCartBrand($id_brand, $idUser)
	{
		$this->db->select("SUM(ap_cart.quantity) as quantity");
		$this->db->from("ap_cart");
		$this->db->join("ap_produk", "ap_produk.id_produk=ap_cart.id_produk");
		$this->db->where("ap_produk.id_brand", $id_brand);
		$this->db->where("ap_cart.id_user", $idUser);
		$query = $this->db->get()->row();
		return $query->quantity;
	}
	function updateCartBrandPrice($id_brand, $diskon = 0, $idUser)
	{
		$intDisc = ($diskon / 100) + 0;
		// $query = "UPDATE ap_cart,ap_produk set ap_cart.diskon=($intDisc*(ap_cart.harga*ap_cart.quantity))
		// 			where ap_cart.id_produk=ap_produk.id_produk and 
		// 			ap_produk.id_brand='$id_brand' and ap_cart.id_user='$idUser'";
		$query = "INSERT ignore into ap_cart_diskon(idUser,diskon) values ('$idUser','$diskon') 
					ON DUPLICATE KEY UPDATE diskon = '$diskon'";
		$this->db->query($query);
		return 1;
	}
	function updateCartBrandPercent($id_brand, $diskon = 0, $idUser)
	{
		$intDisc = ($diskon / 100) + 0;
		$query = "UPDATE ap_cart,ap_produk set ap_cart.diskon=($intDisc*(ap_cart.harga*ap_cart.quantity))
					where ap_cart.id_produk=ap_produk.id_produk and 
					ap_produk.id_brand='$id_brand' and ap_cart.id_user='$idUser'";
		$this->db->query($query);
		return $intDisc;
	}
	function cekQuotaDiskon($sku, $start, $end, $JamMulai, $JamSelesai, $setJam, $diskon, $idStore)
	{
		$this->db->select("SUM(ap_invoice_item.qty) as qty");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_invoice_number", "ap_invoice_number.no_invoice=ap_invoice_item.no_invoice");
		$this->db->where("ap_invoice_item.id_produk", $sku);
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' and '$end'");
		if ($setJam == 1) {
			$this->db->where("DATE_FORMAT(ap_invoice_number.tanggal, '%H:%i') BETWEEN '$JamMulai' AND '$JamSelesai'");
		}
		$this->db->where("ap_invoice_number.id_toko", $idStore);
		$this->db->where("ap_invoice_item.diskon=(ap_invoice_item.qty*$diskon)");
		$query = $this->db->get()->row();
		return $query->qty;
	}
	function cekQuotaDiskonTebusMurah($sku, $start, $end, $JamMulai, $JamSelesai, $setJam, $diskon, $idStore)
	{
		$this->db->select("SUM(ap_invoice_item.qty) as qty");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_invoice_number", "ap_invoice_number.no_invoice=ap_invoice_item.no_invoice");
		$this->db->where("ap_invoice_item.id_produk", $sku);
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' and '$end'");
		if ($setJam == 1) {
			$this->db->where("DATE_FORMAT(ap_invoice_number.tanggal, '%H:%i') BETWEEN '$JamMulai' AND '$JamSelesai'");
		}
		$this->db->where("ap_invoice_number.id_toko", $idStore);
		$this->db->where("ap_invoice_item.diskon=(ap_invoice_item.qty*$diskon)");
		$query = $this->db->get()->row();
		return $query->qty;
	}
	function cekQuotaDiskonGroup($id_produk_list, $start, $end, $JamMulai, $JamSelesai, $setJam, $diskon, $idStore)
	{
		$this->db->select("SUM(ap_invoice_item.qty) as qty");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_invoice_number", "ap_invoice_number.no_invoice=ap_invoice_item.no_invoice");
		$this->db->where_in("ap_invoice_item.id_produk", $id_produk_list);
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' and '$end'");
		if ($setJam == 1) {
			//$this->db->where("ap_invoice_number.tanggal BETWEEN '$start $JamMulai' and '$end $JamSelesai'");
			$this->db->where("DATE_FORMAT(ap_invoice_number.tanggal, '%H:%i') BETWEEN '$JamMulai' AND '$JamSelesai'");
		}
		$this->db->where("ap_invoice_number.id_toko", $idStore);
		$this->db->where("ap_invoice_item.tebusmurah>0");
		$query = $this->db->get()->row();
		return $query->qty;
	}
	function cekBuy1Get3Promo($sku)
	{
		$jam = date('H:m:s');
		$tanggal = date('Y-m-d');
		$this->db->select(array(
			"tipe",
			"no_promo",
			"(paid_item+free_item) as qty_diskon",
			"free_item",
			"discount"
		));
		$this->db->from("ap_produk_buy1get3_rules");
		$this->db->where("id_produk", $sku);
		$this->db->where(" '$tanggal' BETWEEN date_start and date_end");
		$this->db->where(" '$jam' BETWEEN JamMulai and JamSelesai");
		$this->db->limit(1);
		$query = $this->db->get()->row();
		return $query;
	}
	function cekBuy1Get3Qty($no_promo, $idUser, $id_produk)
	{
		$this->db->select(array("SUM(ap_cart.quantity) as qty_beli"));
		$this->db->from("ap_cart");
		$this->db->join("ap_produk_buy1get3_rules", "ap_produk_buy1get3_rules.id_produk= ap_cart.id_produk");
		$this->db->where("ap_produk_buy1get3_rules.no_promo", $no_promo);
		$this->db->where("ap_cart.quantity>=", 1);
		$this->db->where("ap_cart.id_produk!='$id_produk'");
		$this->db->where("ap_cart.id_user", $idUser);
		$query = $this->db->get()->row();
		return $query->qty_beli;
	}
	function cekBuy1Get3QtyIns($no_promo, $idUser, $id_produk)
	{
		$this->db->select(array("SUM(ap_cart.quantity) as qty_beli"));
		$this->db->from("ap_cart");
		$this->db->join("ap_produk_buy1get3_rules", "ap_produk_buy1get3_rules.id_produk= ap_cart.id_produk", "left");
		$this->db->where("ap_produk_buy1get3_rules.no_promo", $no_promo);
		$this->db->where("ap_cart.quantity>=", 1);
		$this->db->where("ap_cart.id_user", $idUser);
		$query = $this->db->get()->row();
		return $query->qty_beli;
	}
	function cekBuy1Get3QtyPending($no_promo, $noCart, $id_produk)
	{
		$this->db->select(array("SUM(ap_cart_temp.quantity) as qty_beli"));
		$this->db->from("ap_cart_temp");
		$this->db->join("ap_produk_buy1get3_rules", "ap_produk_buy1get3_rules.id_produk= ap_cart_temp.id_produk");
		$this->db->where("ap_produk_buy1get3_rules.no_promo", $no_promo);
		$this->db->where("ap_cart_temp.quantity>=", 1);
		$this->db->where("ap_cart_temp.id_produk!='$id_produk'");
		$this->db->where("ap_cart_temp.noCart", $noCart);
		$query = $this->db->get()->row();
		return $query->qty_beli;
	}
	function total_struk($id, $tanggal)
	{
		$this->db->select("COUNT(no_invoice) as total_struk");
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic", $id);
		$this->db->where("DATE(tanggal)", $tanggal);
		$this->db->where("tipe_bayar", 1);

		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->total_struk;
		}
	}
	function cekQuotaDiskonRp($no_promo, $start, $end, $idStore)
	{
		$this->db->select("SUM(ap_invoice_item.disc_supplier) as qty");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk_discount_rules", "ap_produk_discount_rules.id_produk=ap_invoice_item.id_produk");
		$this->db->where("ap_produk_discount_rules.no_promo", $no_promo);
		$this->db->where("ap_produk_discount_rules.id_toko", $idStore);
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' and '$end'");
		$this->db->where("ap_invoice_item.disc_supplier>0");
		$query = $this->db->get()->row();
		return $query->qty;
	}
	function cekQuotaDiskonBuy1Get3($no_promo, $start, $end, $idStore)
	{
		$this->db->select("SUM(ap_invoice_item.buy1get3) as qty");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk_buy1get3_rules", "ap_produk_buy1get3_rules.id_produk=ap_invoice_item.id_produk");
		$this->db->where("ap_produk_buy1get3_rules.no_promo", $no_promo);
		$this->db->where("ap_produk_buy1get3_rules.id_toko", $idStore);
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' and '$end'");
		$this->db->where("ap_invoice_item.buy1get3>0");
		$query = $this->db->get()->row();
		return $query->qty;
	}
	function cekInvoiceItemQty($sku, $no_invoice)
	{
		$this->db->select("qty");
		$this->db->from("ap_invoice_item");
		$this->db->where("id_produk", $sku);
		$this->db->where("no_invoice", $no_invoice);
		$query = $this->db->get()->row();
		return $query->qty;
	}
	function penjualan_perbarang($start, $end, $id_produk)
	{
		$this->db->select("*");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk", "ap_produk.id_produk = ap_invoice_item.id_produk", "left");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_item.id_produk", $id_produk);
		return $this->db->get()->result();
	}

	function produk_search($q, $id_store)
	{
		$query = "SELECT stok_store.id_produk, ap_produk.nama_produk,stok_store.harga,stok_store.stok
				  FROM stok_store
				  LEFT OUTER JOIN ap_produk ON ap_produk.id_produk = stok_store.id_produk
				  WHERE (stok_store.id_store = '$id_store') AND (ap_produk.nama_produk LIKE '%$q%' OR ap_produk.id_produk LIKE '%$q%' OR ap_produk.qr_code LIKE '$q')
				  GROUP BY ap_produk.id_produk";

		return $this->db->query($query);
	}

	function produkSearchRetur($q, $id_store)
	{
		$query = "SELECT stok_store.id_produk, ap_produk.nama_produk
				  FROM stok_store
				  LEFT JOIN ap_produk ON ap_produk.id_produk = stok_store.id_produk
				  WHERE (stok_store.id_store = '$id_store' AND ap_produk.status='1') AND (ap_produk.nama_produk LIKE '%$q%' OR ap_produk.id_produk LIKE '%$q%')
				  GROUP BY ap_produk.id_produk";

		return $this->db->query($query);
	}

	function list_kasir()
	{
		$this->db->select("*");
		$this->db->from("user");
		$this->db->join("user_access", "user_access.id_user = user.id_user", "left");
		$this->db->where("user_access.access_level", 18);
		$this->db->where("user_access.status", 1);
		$this->db->group_by("user.id_user");
		return $this->db->get()->result();
	}

	function listKasir($store = '')
	{
		$this->db->select("*");
		$this->db->from("users");
		if (!empty($store)) {
			$this->db->where("toko", $store);
			$this->db->where("active", 1);
		}
		return $this->db->get()->result();
	}

	function cek_status_kasir($id_kasir, $tanggal)
	{
		$this->db->from("closing_modal");
		$this->db->where("id_user", $id_kasir);
		$this->db->where("DATE(tanggal)", $tanggal);
		return $this->db->count_all_results();
	}

	function cekClose($idKasir, $tanggal)
	{
		$this->db->from("closing_id");
		$this->db->where("id_kasir", $idKasir);
		$this->db->where("tanggal", $tanggal);
		return $this->db->count_all_results();
	}

	function modal_kasir($id_kasir, $tanggal)
	{
		$this->db->select(array("modal", "tanggal"));
		$this->db->from("closing_modal");
		$this->db->where("id_user", $id_kasir);
		$this->db->where("DATE(tanggal)", $tanggal);
		$query = $this->db->get()->result();

		return $query;
	}

	function list_debit()
	{
		$this->db->select("*");
		$this->db->from("ap_payment_account");
		$this->db->where("id_payment_type", 2);
		return $this->db->get()->result();
	}

	function list_kredit()
	{
		$this->db->select("*");
		$this->db->from("ap_payment_account");
		$this->db->where("id_payment_type", 3);
		return $this->db->get()->result();
	}

	function nilaiClosingCash($id, $tanggal)
	{
		$this->db->select("value");
		$this->db->from("closing_account");
		$this->db->where("id_kasir", $id);
		$this->db->where("tanggal", $tanggal);
		$this->db->where("payment_type", 1);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->value;
		}
	}

	function cash_value($id, $tanggal)
	{
		$this->db->select(array("SUM(total) as total", "SUM(voucher) as voucher", "SUM(diskon+diskon_free+diskon_otomatis) as diskon", "SUM(poin_value) as diskon_poin"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic", $id);
		$this->db->where("DATE(tanggal)", $tanggal);
		$this->db->where("tipe_bayar", 1);
		return $this->db->get()->result();
	}

	function voucher_value($id, $tanggal)
	{
		$this->db->select(array("SUM(voucher) as voucher"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic", $id);
		$this->db->where("DATE(tanggal)", $tanggal);
		//$this->db->where("tipe_bayar",1);
		$query = $this->db->get()->result();
		foreach ($query as $row) {
			return $row->voucher;
		}
	}

	function transfer_value($id, $tanggal)
	{
		$this->db->select(array("SUM(total) as total", "SUM(diskon+diskon_free+diskon_otomatis+poin_value) as diskon"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic", $id);
		$this->db->where("DATE(tanggal)", $tanggal);
		$this->db->where("tipe_bayar", 4);
		return $this->db->get()->result();
	}

	function nilaiClosingTransfer($id, $tanggal)
	{
		$this->db->select("value");
		$this->db->from("closing_account");
		$this->db->where("id_kasir", $id);
		$this->db->where("tanggal", $tanggal);
		$this->db->where("payment_type", 4);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->value;
		}
	}

	function debit_value($id_account, $id_pic, $tanggal)
	{
		$this->db->select(array("SUM(total) as total", "SUM(diskon+diskon_free+diskon_otomatis+poin_value) as diskon"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic", $id_pic);
		$this->db->where("tipe_bayar", 2);
		$this->db->where("sub_account", $id_account);
		$this->db->where("DATE(tanggal)", $tanggal);
		return $this->db->get()->result();
	}

	function debitValueClosing($idAccount, $idKasir, $tanggal)
	{
		$this->db->select("value");
		$this->db->from("closing_account");
		$this->db->where("payment_type", 2);
		$this->db->where("account", $idAccount);
		$this->db->where("id_kasir", $idKasir);
		$this->db->where("tanggal", $tanggal);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->value;
		}
	}

	function kredit_value($id_account, $id_pic, $tanggal)
	{
		$this->db->select(array("SUM(total+surcharge) as total", "SUM(diskon+diskon_free+diskon_otomatis+poin_value) as diskon"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic", $id_pic);
		$this->db->where("tipe_bayar", 3);
		$this->db->where("sub_account", $id_account);
		$this->db->where("DATE(tanggal)", $tanggal);
		return $this->db->get()->result();
	}

	function kreditValueClosing($idAccount, $idKasir, $tanggal)
	{
		$this->db->select("value");
		$this->db->from("closing_account");
		$this->db->where("payment_type", 3);
		$this->db->where("account", $idAccount);
		$this->db->where("id_kasir", $idKasir);
		$this->db->where("tanggal", $tanggal);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->value;
		}
	}

	function retur_value($id, $tanggal)
	{
		$this->db->select("SUM(ap_retur_item.harga*ap_retur_item.qty) as nilai_retur");
		$this->db->from("ap_retur_item");
		$this->db->join("ap_retur", "ap_retur.no_retur = ap_retur_item.no_retur", "left");
		$this->db->where("ap_retur.pic", $id);
		$this->db->where("ap_retur_item.tanggal", $tanggal);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->nilai_retur;
		}
	}

	function retur_count($id, $tanggal)
	{
		$this->db->select("COUNT(ap_retur.no_retur) as nilai_retur");
		$this->db->from("ap_retur");
		$this->db->where("ap_retur.pic", $id);
		$this->db->where("DATE(ap_retur.tanggal)", $tanggal);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->nilai_retur;
		}
	}

	function tebusmurahCashValue($id, $tanggal)
	{
		$this->db->select(array("SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual) as harga", "SUM(ap_invoice_item.tebusmurah) as diskon"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item", "ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->where("ap_invoice_number.id_pic", $id);
		$this->db->where("DATE(ap_invoice_number.tanggal)", $tanggal);
		$this->db->where("ap_invoice_number.tipe_bayar", 1);
		$this->db->where("ap_invoice_item.tebusmurah>0");
		$query = $this->db->get()->row();

		return $query;

	}
	function tebusmurahDebitValue($account, $id, $tanggal)
	{
		$this->db->select(array("SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual) as harga", "SUM(ap_invoice_item.tebusmurah) as diskon"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item", "ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->where("ap_invoice_number.id_pic", $id);
		$this->db->where("DATE(ap_invoice_number.tanggal)", $tanggal);
		$this->db->where("ap_invoice_number.tipe_bayar", 2);
		$this->db->where("ap_invoice_number.sub_account", $account);
		$this->db->where("ap_invoice_item.tebusmurah>0");
		$query = $this->db->get()->row();

		return $query;

	}
	function tebusmurahKreditValue($account, $id, $tanggal)
	{
		$this->db->select(array("SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual) as harga", "SUM(ap_invoice_item.tebusmurah) as diskon"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item", "ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->where("ap_invoice_number.id_pic", $id);
		$this->db->where("DATE(ap_invoice_number.tanggal)", $tanggal);
		$this->db->where("ap_invoice_number.tipe_bayar", 3);
		$this->db->where("ap_invoice_number.sub_account", $account);
		$this->db->where("ap_invoice_item.tebusmurah>0");
		$query = $this->db->get()->row();

		return $query;

	}
	function tebusmurahTransferValue($id, $tanggal)
	{
		$this->db->select(array("SUM(ap_invoice_item.qty*ap_invoice_item.harga_jual) as harga", "SUM(ap_invoice_item.tebusmurah) as diskon"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_invoice_item", "ap_invoice_item.no_invoice = ap_invoice_number.no_invoice");
		$this->db->where("ap_invoice_number.id_pic", $id);
		$this->db->where("DATE(ap_invoice_number.tanggal)", $tanggal);
		$this->db->where("ap_invoice_number.tipe_bayar", 4);
		$this->db->where("ap_invoice_item.tebusmurah>0");
		$query = $this->db->get()->row();

		return $query;

	}

	function diskon_cash_value($id, $tanggal)
	{
		$this->db->select(array("SUM(diskon) as diskon", "SUM(diskon_free) as diskon_free", "SUM(diskon_otomatis) as diskon_otomatis", "SUM(poin_value) as poin_value"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic", $id);
		$this->db->where("DATE(tanggal)", $tanggal);
		$this->db->where("tipe_bayar", 1);
		$query = $this->db->get()->result();
		foreach ($query as $row) {
			return $row->diskon + $row->diskon_free + $row->diskon_otomatis + $row->poin_value;
		}
	}

	function diskon_debit_value($account, $id, $tanggal)
	{
		$this->db->select(array("SUM(diskon) as diskon", "SUM(diskon_free) as diskon_free", "SUM(diskon_otomatis) as diskon_otomatis", "SUM(poin_value) as poin_value"));
		$this->db->from("ap_invoice_number");
		$this->db->where("id_pic", $id);
		$this->db->where("DATE(tanggal)", $tanggal);
		$this->db->where("tipe_bayar", 2);
		$this->db->where("sub_account", $account);
		$query = $this->db->get()->result();
		foreach ($query as $row) {
			return $row->diskon + $row->diskon_free + $row->diskon_otomatis + $row->poin_value;
		}
	}

	function jamClosing($id, $tanggal)
	{
		$this->db->select("jam");
		$this->db->from("closing_id");
		$this->db->where("id_kasir", $id);
		$this->db->where("tanggal", $tanggal);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->jam;
		}
	}

	function noClosing($id, $tanggal)
	{
		$this->db->select("id_closing");
		$this->db->from("closing_id");
		$this->db->where("id_kasir", $id);
		$this->db->where("tanggal", $tanggal);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->id_closing;
		}
	}

	function getProdukPrice($sku, $idStore)
	{
		$this->db->select("harga");
		$this->db->from("ap_produk_price");
		$this->db->where("id_produk", $sku);
		$this->db->where("id_toko", $idStore);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->harga;
		}
	}

	function getProdukAndPrice($sku, $idStore)
	{
		$this->db->select(array("ap_produk.id_kategori", "ap_produk.id_produk", "ap_produk.nama_produk", "ap_produk_price.harga", "ap_produk_price.hpp"));
		$this->db->from("ap_produk");
		$this->db->join("ap_produk_price", "ap_produk_price.id_produk = ap_produk.id_produk", "left");
		$this->db->where("id_toko", $idStore);
		$this->db->where("ap_produk.id_produk", $sku);
		return $this->db->get();
	}

	function cekStokPerStore($sku, $idStore)
	{
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk", $sku);
		$this->db->where("id_store", $idStore);
		$this->db->group_by("stok_store.id_produk");
		$query = $this->db->get()->row();
		return $query->stok;
	}

	function getProdukData($sku, $idStore)
	{
		$this->db->select(array("stok_store.stok", "ap_produk.id_produk", "ap_produk_price.hpp", "ap_produk_price.harga", "ap_produk_price.harga_member"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk", "ap_produk.id_produk = stok_store.id_produk");
		$this->db->join("ap_produk_price", "ap_produk_price.id_produk = stok_store.id_produk and ap_produk_price.id_toko='$idStore'");
		$this->db->where("stok_store.id_store", $idStore);
		$this->db->group_start();
		$this->db->where("stok_store.id_produk", $sku);
		// $this->db->or_where("ap_produk.qr_code",$sku);
		$this->db->group_end();
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get()->result();
	}

	function getProdukDataWarehouse($sku)
	{
		$this->db->select("ap_produk.stok");
		$this->db->from("ap_produk");
		$this->db->where("ap_produk.id_produk", $sku);
		return $this->db->get()->result();
	}

	function dataCart($idUser, $idStore = '7')
	{
		$this->db->select(array("ap_cart.no_promo", "ap_cart.id", "ap_cart.id_produk", "ap_cart.quantity as qty", "ap_cart.harga", "ap_produk_price.hpp", "ap_cart.diskon", "ap_cart.disc_supplier", "ap_cart.tebusmurah", "ap_cart.buy1get3", "ap_produk.nama_produk"));
		$this->db->from("ap_cart");
		$this->db->join("ap_produk", "ap_produk.id_produk = ap_cart.id_produk");
		$this->db->join("ap_produk_price", "ap_produk_price.id_produk = ap_produk.id_produk");
		$this->db->where("ap_produk_price.id_toko", $idStore);
		$this->db->where("ap_cart.id_user", $idUser);
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("ap_cart.id", "DESC");
		return $this->db->get()->result();
	}

	function dataCartPending($idPending)
	{
		$this->db->select(array("ap_cart_temp.id", "ap_cart_temp.id_produk", "ap_cart_temp.quantity as qty", "ap_cart_temp.harga", "ap_cart_temp.hpp", "ap_cart_temp.diskon", "ap_cart_temp.disc_supplier", "ap_cart_temp.tebusmurah", "ap_produk.nama_produk"));
		$this->db->from("ap_cart_temp");
		$this->db->join("ap_produk", "ap_produk.id_produk = ap_cart_temp.id_produk", "left");
		$this->db->where("ap_cart_temp.noCart", $idPending);
		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("ap_cart_temp.id", "DESC");
		return $this->db->get()->result();
	}

	function cekCartIfExist($sku, $idUser)
	{
		$this->db->from("ap_cart");
		$this->db->where("id_produk", $sku);
		$this->db->where("id_user", $idUser);
		return $this->db->count_all_results();
	}

	function cekCartIfExistPending($sku, $noCart)
	{
		$this->db->from("ap_cart_temp");
		$this->db->where("id_produk", $sku);
		$this->db->where("noCart", $noCart);
		return $this->db->count_all_results();
	}

	function cekSales($idUser, $sku)
	{
		$this->db->select("(SUM(quantity*harga)-SUM(diskon)) as sales");
		$this->db->from("ap_cart");
		$this->db->where("id_user", $idUser);
		$this->db->where("id_produk !=" . $sku);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->sales;
		}
	}
	function cekQtyBuy1Get3($sku, $idUser, $no_promo, $idStore)
	{
		$this->db->select("SUM(ap_cart.quantity) as qty");
		$this->db->from("ap_cart");
		$this->db->join("ap_produk_buy1get3_rules", "ap_produk_buy1get3_rules.id_produk=ap_cart.id_produk");
		$this->db->where("ap_cart.id_user", $idUser);
		$this->db->where("ap_cart.id_produk !=" . $sku);
		$this->db->where("ap_produk_buy1get3_rules.id_toko", $idStore);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->qty;
		}
	}
	function cekLowestBuy1Get3($idUser, $no_promo, $idStore)
	{
		$this->db->select("MIN(ap_cart.harga) as price");
		$this->db->from("ap_cart");
		$this->db->join("ap_produk_buy1get3_rules", "ap_produk_buy1get3_rules.id_produk=ap_cart.id_produk");
		$this->db->where("ap_cart.id_user", $idUser);
		$this->db->where("ap_produk_buy1get3_rules.id_toko", $idStore);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->price;
		}
	}
	function cekSalesTemp($noCart, $sku)
	{
		$this->db->select("(SUM(quantity*harga)-SUM(diskon)) as sales");
		$this->db->from("ap_cart_temp");
		$this->db->where("noCart", $noCart);
		$this->db->where("id_produk !=" . $sku);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->sales;
		}
	}

	function cekTebusMurah($sku)
	{
		$this->db->select("id_produk");
		$this->db->from("ap_produk_tebusmurah_rules");
		$this->db->where("id_produk", $sku);
		$this->db->where("'" . date('Y-m-d') . "' between date_start and date_end");
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->id_produk;
		}
	}
	function cekBuy1Get3($sku)
	{
		$this->db->select("id_produk");
		$this->db->from("ap_produk_buy1get3_rules");
		$this->db->where("id_produk", $sku);
		$this->db->where("'" . date('Y-m-d') . "' between date_start and date_end");
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->id_produk;
		}
	}

	function cekQtyCart($sku, $idUser)
	{
		$this->db->select("quantity");
		$this->db->from("ap_cart");
		$this->db->where("id_produk", $sku);
		$this->db->where("id_user", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->quantity;
		}
	}

	function cekQtyCartPending($sku, $noCart)
	{
		$this->db->select("quantity");
		$this->db->from("ap_cart_temp");
		$this->db->where("id_produk", $sku);
		$this->db->where("noCart", $noCart);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->quantity;
		}
	}

	function cekDiskonBefore($sku, $idUser)
	{
		$this->db->select("diskon");
		$this->db->from("ap_cart");
		$this->db->where("id_produk", $sku);
		$this->db->where("id_user", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}
	function cekDiskonSupplierBefore($sku, $idUser)
	{
		$this->db->select("disc_supplier");
		$this->db->from("ap_cart");
		$this->db->where("id_produk", $sku);
		$this->db->where("id_user", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->disc_supplier;
		}
	}
	function tebusmurahBefore($sku, $idUser)
	{
		$this->db->select("tebusmurah");
		$this->db->from("ap_cart");
		$this->db->where("id_produk", $sku);
		$this->db->where("id_user", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->tebusmurah;
		}
	}

	function cekDiskonBeforePending($sku, $noCart)
	{
		$this->db->select("diskon");
		$this->db->from("ap_cart_temp");
		$this->db->where("id_produk", $sku);
		$this->db->where("noCart", $noCart);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}
	function cekDiskonSupplierBeforePending($sku, $noCart)
	{
		$this->db->select("disc_supplier");
		$this->db->from("ap_cart_temp");
		$this->db->where("id_produk", $sku);
		$this->db->where("noCart", $noCart);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->disc_supplier;
		}
	}

	function totalPurchase($idUser)
	{
		$this->db->select("SUM(harga*quantity) as total");
		$this->db->from("ap_cart");
		$this->db->where("id_user", $idUser);
		$this->db->group_by("id_user");
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->total;
		}
	}
	function totalPurchaseHPP($idUser)
	{
		$this->db->select("SUM(hpp*quantity) as total");
		$this->db->from("ap_cart");
		$this->db->where("id_user", $idUser);
		$this->db->group_by("id_user");
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->total;
		}
	}

	function totalPurchasePending($idPending)
	{
		$this->db->select("SUM(harga*quantity) as total");
		$this->db->from("ap_cart_temp");
		$this->db->where("noCart", $idPending);
		$this->db->group_by("noCart");
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->total;
		}
	}
	function totalPurchaseHPPPending($idPending)
	{
		$this->db->select("SUM(hpp*quantity) as hpp");
		$this->db->from("ap_cart_temp");
		$this->db->where("noCart", $idPending);
		$this->db->group_by("noCart");
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->hpp;
		}
	}

	function totalByRow($idUser, $sku)
	{
		$this->db->select("(quantity*harga) as totalByRow");
		$this->db->from("ap_cart");
		$this->db->where("id_produk", $sku);
		$this->db->where("id_user", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->totalByRow;
		}
	}

	function totalByRowTemp($noCart, $sku)
	{
		$this->db->select("(quantity*harga) as totalByRow");
		$this->db->from("ap_cart_temp");
		$this->db->where("id_produk", $sku);
		$this->db->where("noCart", $noCart);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->totalByRow;
		}
	}

	function diskonPeritemPanel($idUser)
	{
		$this->db->select("SUM(diskon) as diskon");
		$this->db->from("ap_cart");
		$this->db->where("id_user", $idUser);
		$this->db->group_by("id_user");
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}

	// ap_cart_diskon_buy1get3
	function diskonBuy1Get3($idUser)
	{
		$this->db->select("SUM(diskon) as diskon");
		$this->db->from("ap_cart_diskon_buy1get3");
		$this->db->where("idUser", $idUser);
		$this->db->group_by("idUser");
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}

	function diskonBuy1Get3Pending($noCart)
	{
		$this->db->select("SUM(diskon) as diskon");
		$this->db->from("ap_cart_diskon_buy1get3_temp");
		$this->db->where("noCart", $noCart);
		$this->db->group_by("noCart");
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}

	function diskonPeritemPanelPending($idPending)
	{
		$this->db->select("SUM(diskon) as diskon");
		$this->db->from("ap_cart_temp");
		$this->db->where("noCart", $idPending);
		$this->db->group_by("noCart", $idPending);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}

	function getDiskonMember($idUser)
	{
		$this->db->select("diskon");
		$this->db->from("ap_cart_diskon_member");
		$this->db->where("idUser", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}

	function getDiskonMemberPending($noCart)
	{
		$this->db->select("diskon");
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart", $noCart);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}

	function cekIfPoinExist($idUser)
	{
		$this->db->from("ap_cart_diskon_member");
		$this->db->where("idUser", $idUser);
		return $this->db->count_all_results();
	}

	function cekMember($idMember)
	{
		$this->db->select("id_customer");
		$this->db->from("ap_customer");
		$this->db->where("id_customer", $idMember);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->id_customer;
		}
	}

	function cekKategoriMember($idMember)
	{
		$this->db->select("kategori");
		$this->db->from("ap_customer");
		$this->db->where("id_customer", $idMember);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->kategori;
		}
	}

	function cekIfPoinExistPending($noCart)
	{
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart", $noCart);
		return $this->db->count_all_results();
	}

	function getIdMemberDiskon($idUser)
	{
		$this->db->select("idMember");
		$this->db->from("ap_cart_diskon_member");
		$this->db->where("idUser", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->idMember;
		}
	}

	function getIdMemberDiskonPending($noCart)
	{
		$this->db->select("idMember");
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart", $noCart);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->idMember;
		}
	}

	function poinReimburs($idUser)
	{
		$this->db->select("poinReimburs");
		$this->db->from("ap_cart_diskon_member");
		$this->db->where("idUser", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->poinReimburs;
		}
	}

	function poinValue($idUser)
	{
		$this->db->select("poinValue");
		$this->db->from("ap_cart_diskon_member");
		$this->db->where("idUser", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->poinValue;
		}
	}

	function poinReimbursPending($noCart)
	{
		$this->db->select("poinReimburs");
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart", $noCart);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->poinReimburs;
		}
	}

	function poinValuePending($noCart)
	{
		$this->db->select("poinValue");
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart", $noCart);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->poinValue;
		}
	}

	function cekIfOngkirExist($idUser)
	{
		$this->db->from("ap_cart_ongkir");
		$this->db->where("idUser", $idUser);
		return $this->db->count_all_results();
	}

	function cekIfOngkirExistPending($noCart)
	{
		$this->db->from("ap_cart_ongkir_temp");
		$this->db->where("noCart", $noCart);
		return $this->db->count_all_results();
	}

	function viewOngkir($idUser)
	{
		$this->db->select("ap_cart_ongkir.ongkir");
		$this->db->from("ap_cart_ongkir");
		$this->db->where("idUser", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->ongkir;
		}
	}

	function viewOngkirPending($idPending)
	{
		$this->db->select("ap_cart_ongkir_temp.ongkir");
		$this->db->from("ap_cart_ongkir_temp");
		$this->db->where("noCart", $idPending);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->ongkir;
		}
	}


	function cekIfDiskonExist($idUser)
	{
		$this->db->from("ap_cart_diskon");
		$this->db->where("idUser", $idUser);
		return $this->db->count_all_results();
	}

	function cekIfSurchargeExist($idUser)
	{
		$this->db->from("ap_cart_surcharge");
		$this->db->where("idUser", $idUser);
		return $this->db->count_all_results();
	}

	function cekIfDiskonExistPending($noCart)
	{
		$this->db->from("ap_cart_diskon_temp");
		$this->db->where("noCart", $noCart);
		return $this->db->count_all_results();
	}

	function cekIfSurchargeExistPending($noCart)
	{
		$this->db->from("ap_cart_surcharge_temp");
		$this->db->where("noCart", $noCart);
		return $this->db->count_all_results();
	}

	function viewDiskon($idUser)
	{
		$this->db->select("diskon");
		$this->db->from("ap_cart_diskon");
		$this->db->where("idUser", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}

	function viewSurcharge($idUser)
	{
		$this->db->select("surcharge");
		$this->db->from("ap_cart_surcharge");
		$this->db->where("idUser", $idUser);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->surcharge;
		}
	}

	function viewDiskonPending($idPending)
	{
		$this->db->select("diskon");
		$this->db->from("ap_cart_diskon_temp");
		$this->db->where("noCart", $idPending);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->diskon;
		}
	}

	function viewSurchargePending($idPending)
	{
		$this->db->select("surcharge");
		$this->db->from("ap_cart_surcharge_temp");
		$this->db->where("noCart", $idPending);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->surcharge;
		}
	}

	function cekNoPending($idUser)
	{
		$today = date('Y-m-d');

		$this->db->from("ap_cart_temp_no");
		$this->db->where("idUser", $idUser);
		$this->db->where("DATE(tanggal)", $today);
		return $this->db->count_all_results();
	}

	function cekNoReturPerstore()
	{
		$tanggal = date("Y-m-d");

		$this->db->from("returstore");
		$this->db->where("DATE(tanggal)", $tanggal);
		return $this->db->count_all_results();
	}

	function infoReturPerstore($noRetur)
	{
		$this->db->select(array("returstore.NoRetur", "returstore.tanggal", "users.first_name", "ap_store.store"));
		$this->db->from("returstore");
		$this->db->join("users", "users.id = returstore.id_user");
		$this->db->join("ap_store", "ap_store.id_store = returstore.idStoreFrom");
		$this->db->where("returstore.NoRetur", $noRetur);
		return $this->db->get()->result();
	}

	function returItem($noRetur)
	{
		$this->db->select(array("returstoreitem.sku", "ap_produk.nama_produk", "returstoreitem.qty"));
		$this->db->from("returstoreitem");
		$this->db->join("ap_produk", "ap_produk.id_produk = returstoreitem.sku");
		$this->db->where("returstoreitem.NoRetur", $noRetur);
		return $this->db->get()->result();
	}

	function dataReturPerstore()
	{
		$this->db->select(array("returstore.NoRetur", "returstore.tanggal", "users.first_name", "ap_store.store"));
		$this->db->from("returstore");
		$this->db->join("users", "users.id = returstore.id_user");
		$this->db->join("ap_store", "ap_store.id_store = returstore.idStoreFrom");
		return $this->db->get()->result();
	}

	function cekNoMemberIfDuplicate($noMember)
	{
		$this->db->from("ap_customer");
		$this->db->where("id_customer", $noMember);
		return $this->db->count_all_results();
	}
	function cekNoCartTempIfDuplicate($noCart)
	{
		$this->db->from("ap_cart_diskon_member_temp");
		$this->db->where("noCart", $noCart);
		return $this->db->count_all_results();
	}

	function setAdjusment($id, $tanggal)
	{
		$this->db->select(array("ap_invoice_number.diskon_otomatis", "ap_invoice_number.tanggal", "ap_payment_type.payment_type", "ap_payment_account.account", "ap_invoice_number.no_invoice", "ap_invoice_number.tipe_bayar", "ap_invoice_number.total", "ap_invoice_number.ongkir", "ap_invoice_number.diskon", "ap_invoice_number.diskon_free", "ap_invoice_number.poin_value as poin_reimburs", "((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type", "ap_payment_type.id = ap_invoice_number.tipe_bayar", "left");
		$this->db->join("ap_payment_account", "ap_payment_account.id_payment_account = ap_invoice_number.sub_account", "left");
		$this->db->where("DATE(ap_invoice_number.tanggal)", $tanggal);
		$this->db->where("ap_invoice_number.id_pic", $id);
		$this->db->order_by("ap_invoice_number.tanggal", "DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get()->result();
	}

	function setAdjusmentFilter($id, $tanggal, $search)
	{
		$this->db->select(array("ap_invoice_number.diskon_otomatis", "ap_invoice_number.tanggal", "ap_payment_type.payment_type", "ap_payment_account.account", "ap_invoice_number.no_invoice", "ap_invoice_number.tipe_bayar", "ap_invoice_number.total", "ap_invoice_number.ongkir", "ap_invoice_number.diskon", "ap_invoice_number.diskon_free", "ap_invoice_number.poin_value as poin_reimburs", "((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type", "ap_payment_type.id = ap_invoice_number.tipe_bayar", "left");
		$this->db->join("ap_payment_account", "ap_payment_account.id_payment_account = ap_invoice_number.sub_account", "left");
		$this->db->where("DATE(ap_invoice_number.tanggal)", $tanggal);
		$this->db->where("ap_invoice_number.id_pic", $id);
		$this->db->like("ap_invoice_number.no_invoice", $search);
		$this->db->order_by("ap_invoice_number.tanggal", "DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get()->result();
	}

	function readPaymentType($no_invoice)
	{
		$this->db->select(array("ap_payment_type.payment_type", "ap_payment_account.account"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type", "ap_payment_type.id = ap_invoice_number.tipe_bayar", "left");
		$this->db->join("ap_payment_account", "ap_payment_account.id_payment_account = ap_invoice_number.sub_account", "left");
		$this->db->where("ap_invoice_number.no_invoice", $no_invoice);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->payment_type . " " . $row->account;
		}
	}

	function paymentTypeSelection()
	{
		$this->db->select("*");
		$this->db->from("ap_payment_type");
		$this->db->where("id != 5");
		return $this->db->get();
	}

	function oldStokWarehouse($idProduk, $idStore)
	{
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk", $idProduk);
		$this->db->where("id_store", $idStore);
		$query = $this->db->get()->result();

		foreach ($query as $row) {
			return $row->stok;
		}
	}

	function getIdStore($no_invoice)
	{
		$this->db->select("id_toko");
		$this->db->from("ap_invoice_number");
		$this->db->where("no_invoice", $no_invoice);
		$query = $this->db->get()->row();
		return $query->id_toko;
	}

	function invoiceInfo($noInvoice)
	{
		$this->db->select(array("ap_customer.nama", "ap_customer.kontak", "ap_customer.alamat", "ap_invoice_number.no_invoice", "ap_invoice_number.tanggal", "ap_ekspedisi.ekspedisi", "ap_invoice_number.nama_penerima", "ap_invoice_number.alamat", "ap_invoice_number.kontak_pengiriman", "ae_provinsi.nama_provinsi", "ae_kabupaten.nama_kabupaten", "ae_kecamatan.kecamatan", "ap_invoice_number.tanggal", "ap_invoice_number.total", "ap_invoice_number.ongkir", "ap_invoice_number.diskon", "ap_invoice_number.diskon_free", "ap_invoice_number.poin_value as poin_reimburs", "ap_payment_type.payment_type", "ap_payment_account.account", "ap_invoice_number.jumlah_bayar"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer", "ap_customer.id_customer = ap_invoice_number.id_customer", "left");
		$this->db->join("ap_ekspedisi", "ap_ekspedisi.id_ekspedisi = ap_invoice_number.id_ekspedisi", "left");
		$this->db->join("ae_provinsi", "ae_provinsi.id_provinsi = ap_invoice_number.id_provinsi", "left");
		$this->db->join("ae_kabupaten", "ae_kabupaten.kabupaten_id = ap_invoice_number.id_kabupaten", "left");
		$this->db->join("ae_kecamatan", "ae_kecamatan.id_kecamatan = ap_invoice_number.id_kecamatan", "left");
		$this->db->join("ap_payment_type", "ap_payment_type.id = ap_invoice_number.tipe_bayar", "left");
		$this->db->join("ap_payment_account", "ap_payment_account.id_payment_account = ap_invoice_number.sub_account", "left");
		$this->db->where("ap_invoice_number.no_invoice", $noInvoice);
		return $this->db->get()->row();
	}

	function customerRow($idCustomer)
	{
		$this->db->select(array("ap_customer.nama", "ap_customer.kontak", "ap_customer.alamat", "ap_customer.id_provinsi", "ap_customer.id_kabupaten", "ap_customer.id_kecamatan"));
		$this->db->from("ap_customer");
		$this->db->where("ap_customer.id_customer", $idCustomer);
		return $this->db->get()->row();
	}


	function invoiceRetur($noInvoice)
	{
		$this->db->select(array("ap_retur.no_retur", "ap_retur.tanggal", "ap_retur.keterangan"));
		$this->db->from("ap_retur");
		$this->db->where("ap_retur.no_invoice", $noInvoice);
		return $this->db->get()->result();
	}

	function returItemSale($noRetur)
	{
		$this->db->select(array("ap_produk.id_produk", "ap_produk.nama_produk", "ap_retur_item.qty", "ap_retur_item.harga", "ap_retur_item.diskon"));
		$this->db->from("ap_retur_item");
		$this->db->join("ap_produk", "ap_produk.id_produk = ap_retur_item.id_produk", "left");
		$this->db->where("ap_retur_item.no_retur", $noRetur);
		return $this->db->get()->result();
	}

	function currentQtyPeritem($id)
	{
		$this->db->select("quantity as qty");
		$this->db->from("ap_cart");
		$this->db->where("id", $id);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function maxQTYCart($idProduk, $idUser)
	{
		$this->db->select("quantity as qty");
		$this->db->from("ap_cart");
		$this->db->where("id_produk", $idProduk);
		$this->db->where("id_user", $idUser);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function hargaOnCart($id)
	{
		$this->db->select(array("harga", "diskon", "quantity as qty"));
		$this->db->from("ap_cart");
		$this->db->where("id", $id);
		$query = $this->db->get()->result();
		return $query;
	}

	function hargaOnCartTemp($id)
	{
		$this->db->select(array("harga", "diskon", "quantity as qty"));
		$this->db->from("ap_cart_temp");
		$this->db->where("id", $id);
		$query = $this->db->get()->result();
		return $query;
	}

	function hargaOnInvoice($id)
	{
		$this->db->select(array("total", "hpp"));
		$this->db->from("ap_invoice_number");
		$this->db->where("no_invoice", $id);
		$query = $this->db->get()->result();
		return $query;
	}

	function cekDiskonOtomatis($id)
	{
		$this->db->select(array("diskon_otomatis"));
		$this->db->from("ap_invoice_number");
		$this->db->where("no_invoice", $id);
		$query = $this->db->get()->row();

		return $query->diskon_otomatis;
	}

	function cekDiskon($sku)
	{
		$this->db->select("diskon");
		$this->db->from("ap_produk");
		$this->db->where("id_produk", $sku);
		$query = $this->db->get()->row();

		return $query->diskon;
	}

	function insertReturPenjualanSQL($data_retur)
	{
		$this->db->insert("ap_retur", $data_retur);
	}

	function updateStokPerstore($idStore, $sku, $dataUpdate)
	{
		$this->db->where("id_store", $idStore);
		$this->db->where("id_produk", $sku);
		$this->db->update("stok_store", $dataUpdate);
	}

	function updateInvoiceItemRetur($no_invoice, $sku, $dataUpdate)
	{
		$this->db->where("no_invoice", $no_invoice);
		$this->db->where("id_produk", $sku);
		$this->db->update("ap_invoice_item", $dataUpdate);
	}

	function updateInvoiceNumberRetur($no_invoice, $dataUpdate)
	{
		$this->db->where("no_invoice", $no_invoice);
		$this->db->update("ap_invoice_number", $dataUpdate);
	}

	function insertCartTemp($dataPending)
	{
		$this->db->insert("ap_cart_temp_no", $dataPending);
	}

	function inserCartTempItem($data_item, $idUser)
	{
		$this->db->insert_batch("ap_cart_temp", $data_item);

		//delete all data on db cart
		$this->db->delete("ap_cart", array("id_user" => $idUser));
		$this->db->delete("ap_cart_diskon", array("idUser" => $idUser));
		$this->db->delete("ap_cart_diskon_member", array("idUser" => $idUser));
		$this->db->delete("ap_cart_ongkir", array("idUser" => $idUser));
		$this->db->delete("ap_cart_diskon_buy1get3", array("idUser" => $idUser));
		$this->db->delete("ap_cart_diskon_voucher", array("idUser" => $idUser));
	}

	function hapusCart($sku, $idUser)
	{
		$this->db->delete("ap_cart", array("id_produk" => $sku, "id_user" => $idUser));
		$this->db->delete("ap_cart_diskon_buy1get3", array("id_produk" => $sku, "idUser" => $idUser));
	}

	function hapusCartVoucher($idUser)
	{
		$this->db->delete("ap_cart_diskon_voucher", array("idUser" => $idUser));
	}

	function hapusCartBuy1Get3($idUser)
	{
		$this->db->delete("ap_cart_diskon_buy1get3", array("idUser" => $idUser));
	}

	function insertCart($dataCart)
	{
		$this->db->insert("ap_cart", $dataCart);
	}

	function updateCartPendingTemp($noCart, $sku, $dataCartUpdate)
	{
		$this->db->where("noCart", $noCart);
		$this->db->where("id_produk", $sku);
		$this->db->update("ap_cart_temp", $dataCartUpdate);
	}

	function insertCartPendingTemp($dataCart)
	{
		$this->db->insert("ap_cart_temp", $dataCart);
	}

	function updateDiskon($sku, $idUser, $dataUpdate)
	{
		$this->db->where("id_produk", $sku);
		$this->db->where("id_user", $idUser);
		$this->db->update("ap_cart", $dataUpdate);
	}

	function updateQtyCart($sku, $idUser, $dataUpdate)
	{
		$this->db->where("id_produk", $sku);
		$this->db->where("id_user", $idUser);
		$this->db->update("ap_cart", $dataUpdate);
	}

	function updateQtyCartPending($sku, $noCart, $dataUpdate)
	{
		$this->db->where("id_produk", $sku);
		$this->db->where("noCart", $noCart);
		$this->db->update("ap_cart_temp", $dataUpdate);
	}

	function updateDiskonPending($noCart, $dataUpdate)
	{
		$this->db->where("noCart", $noCart);
		$this->db->update("ap_cart_diskon_temp", $dataUpdate);
	}

	function updateSurchargePending($noCart, $dataUpdate)
	{
		$this->db->where("noCart", $noCart);
		$this->db->update("ap_cart_surcharge_temp", $dataUpdate);
	}

	function insertDiskonPending($dataInsert)
	{
		$this->db->insert("ap_cart_diskon_temp", $dataInsert);
	}

	function insertSurchargePending($dataInsert)
	{
		$this->db->insert("ap_cart_surcharge_temp", $dataInsert);
	}

	function updateCartDiskon($idUser, $dataUpdate)
	{
		$this->db->where("idUser", $idUser);
		$this->db->update("ap_cart_diskon", $dataUpdate);
	}

	function updateCartSurcharge($idUser, $dataUpdate)
	{
		$this->db->where("idUser", $idUser);
		$this->db->update("ap_cart_surcharge", $dataUpdate);
	}

	function updateCartSurchargePending($noCart, $dataUpdate)
	{
		$this->db->where("noCart", $noCart);
		$this->db->update("ap_cart_surcharge_temp", $dataUpdate);
	}

	function insertCartDiskon($dataInsert)
	{
		$this->db->insert("ap_cart_diskon", $dataInsert);
	}

	function insertCartSurcharge($dataInsert)
	{
		$this->db->insert("ap_cart_surcharge", $dataInsert);
	}

	function insertCartSurchargePending($dataInsert)
	{
		$this->db->insert("ap_cart_surcharge_temp", $dataInsert);
	}

	function updateCartDiskonPending($sku, $noCart, $dataUpdate)
	{
		$this->db->where("id_produk", $sku);
		$this->db->where("noCart", $noCart);
		$this->db->update("ap_cart_temp", $dataUpdate);
	}

	function hapusCartPending($idProduk, $noCart)
	{
		$this->db->delete("ap_cart_temp", array("id_produk" => $idProduk, "noCart" => $noCart));
	}

	function saveDiskonMember($dataDiskon)
	{
		$this->db->insert("ap_cart_diskon_member", $dataDiskon);
	}

	function saveDiskonMemberPending($dataDiskon)
	{
		$this->db->insert("ap_cart_diskon_member_temp", $dataDiskon);
	}

	function deleteDiscMember($idUser)
	{
		$this->db->delete("ap_cart_diskon_member", array("idUser" => $idUser));
	}

	function deleteDiscMemberPending($noCart)
	{
		$this->db->delete("ap_cart_diskon_member_temp", array("noCart" => $noCart));
	}

	function insertPoin($idUser, $dataUpdate)
	{
		$this->db->where("idUser", $idUser);
		$this->db->update("ap_cart_diskon_member", $dataUpdate);
	}

	function insertPoinPending($noCart, $dataUpdate)
	{
		$this->db->where("noCart", $noCart);
		$this->db->update("ap_cart_diskon_member_temp", $dataUpdate);
	}

	function updateOngkir($idUser, $dataUpdate)
	{
		$this->db->where("idUser", $idUser);
		$this->db->update("ap_cart_ongkir", $dataUpdate);
	}

	function insertOngkir($dataInsert)
	{
		$this->db->insert("ap_cart_ongkir", $dataInsert);
	}

	function updateOngkirPending($noCart, $dataUpdate)
	{
		$this->db->where("noCart", $noCart);
		$this->db->update("ap_cart_ongkir_temp", $dataUpdate);
	}

	function insertOngkirPending($dataInsert)
	{
		$this->db->insert("ap_cart_ongkir_temp", $dataInsert);
	}

	function updatePoinReimburs($id_customer, $poin)
	{
		$this->db->where("id_customer", $id_customer);
		$this->db->update("ap_customer", $poin);
	}

	function insertApInvoiceNumber($data_penjualan)
	{
		$this->db->insert("ap_invoice_number", $data_penjualan);
	}

	function insertPiutangInvoice($data_piutang)
	{
		$this->db->insert("ap_piutang_pay", $data_piutang);
	}

	function updateStokStore($sku, $id_store, $data_update)
	{
		$this->db->where("id_produk", $sku);
		$this->db->where("id_store", $id_store);
		$this->db->update("stok_store", $data_update);
	}

	function insertBatch($data_item)
	{
		$this->db->insert_batch("ap_invoice_item", $data_item);
	}

	function hapusTrx($idUser)
	{
		$this->db->delete("ap_cart", array("id_user" => $idUser));
		$this->db->delete("ap_cart_diskon", array("idUser" => $idUser));
		$this->db->delete("ap_cart_diskon_member", array("idUser" => $idUser));
		$this->db->delete("ap_cart_ongkir", array("idUser" => $idUser));
		$this->db->delete("ap_cart_diskon_buy1get3", array("idUser" => $idUser));
		$this->db->delete("ap_cart_diskon_voucher", array("idUser" => $idUser));
	}


	function hapusSurcharge($idUser)
	{
		$this->db->delete("ap_cart_surcharge", array("idUser" => $idUser));
	}

	function hapusSurchargePending($noCart)
	{
		$this->db->delete("ap_cart_surcharge_temp", array("noCart" => $noCart));
	}

	function hapusTrxTemp($noCart)
	{
		//hapus cart
		$this->db->delete("ap_cart_temp", array("noCart" => $noCart));
		$this->db->delete("ap_cart_diskon_temp", array("noCart" => $noCart));
		$this->db->delete("ap_cart_diskon_member_temp", array("noCart" => $noCart));
		$this->db->delete("ap_cart_ongkir_temp", array("noCart" => $noCart));
		$this->db->delete("ap_cart_diskon_buy1get3_temp", array("noCart" => $noCart));
		$this->db->delete("ap_cart_diskon_voucher_temp", array("noCart" => $noCart));
	}

	function updateCartTempStatus($noCart, $dataCartTempNo)
	{
		$this->db->where("cartNo", $noCart);
		$this->db->update("ap_cart_temp_no", $dataCartTempNo);
	}

	function simpanDataMember($dataCustomer)
	{
		$this->db->insert("ap_customer", $dataCustomer);
		$affect = $this->db->affected_rows();
		return $affect;
	}
	function insertBuy1Get3($dataInsert)
	{
		$this->db->insert("ap_cart_diskon_buy1get3", $dataInsert);
	}
	function updateBuy1Get3($no_promo, $idUser, $dataupdate)
	{
		$this->db->where("no_promo", $no_promo);
		$this->db->where("idUser", $idUser);
		$this->db->update("ap_cart_diskon_buy1get3", $dataupdate);
	}
	function updateBuy1Get3Pending($no_promo, $noCart, $dataupdate)
	{
		$this->db->where("no_promo", $no_promo);
		$this->db->where("noCart", $noCart);
		$this->db->update("ap_cart_diskon_buy1get3_temp", $dataupdate);
	}
	function insertBuy1Get3Pending($dataInsert)
	{
		$this->db->insert("ap_cart_diskon_buy1get3_temp", $dataInsert);
	}

	/** no_promo dari ap_produk_discount_rules (mirror ambil_nilai_diskon Penjualan) */
	public function resolveDiscountRuleNoPromo($sku, $idStore, $qty)
	{
		$qty = (float) $qty;
		$this->db->order_by('(discount+disc_supplier)', 'ASC');
		$this->db->where('id_produk', $sku);
		$this->db->where('id_toko', (int) $idStore);
		$this->db->where('date_start <=', date('Y-m-d'));
		$nilaiDiskon = $this->db->get('ap_produk_discount_rules');
		$noPromo = 0;
		if ($nilaiDiskon->num_rows() < 1) {
			return 0;
		}
		foreach ($nilaiDiskon->result() as $row) {
			if ($qty < (float) $row->qty) {
				continue;
			}
			if (strtotime(date('Y-m-d')) < strtotime($row->date_start) || strtotime(date('Y-m-d')) > strtotime($row->date_end)) {
				continue;
			}
			$diskon = null;
			if ((int) $row->setHari === 1) {
				$HariID = explode('.', (string) $row->HariID);
				if (in_array((string) date('w'), $HariID, true)) {
					if ((int) $row->setJam === 1) {
						if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
							$diskon = (float) $row->discount + (float) $row->disc_supplier;
						} else {
							$diskon = 0;
						}
					} else {
						$diskon = (float) $row->discount + (float) $row->disc_supplier;
					}
				} else {
					$diskon = 0;
				}
			} elseif ((int) $row->setJam === 1 && (int) $row->setHari !== 1) {
				if (strtotime(date('H:i:s')) >= strtotime($row->JamMulai) && strtotime(date('H:i:s')) <= strtotime($row->JamSelesai)) {
					$diskon = (float) $row->discount + (float) $row->disc_supplier;
				} else {
					$diskon = 0;
				}
			} else {
				$diskon = (float) $row->discount + (float) $row->disc_supplier;
			}
			if ($diskon !== null && $diskon > 0 && property_exists($row, 'no_promo')) {
				$noPromo = (int) $row->no_promo;
			}
		}
		return $noPromo;
	}

	/** Header: ap_buy1get3_new_rules via voucher (cart_diskon_voucher) atau legacy cart_diskon_buy1get3 */
	public function getHeaderNoPromoForInvoice($idUser, $dvM, $dvF, $diskonBuy1get3Val)
	{
		$dvM = (float) $dvM;
		$dvF = (float) $dvF;
		if ($dvM >= $dvF && $dvM > 0) {
			$r = $this->db->select('no_promo')->from('ap_cart_diskon_voucher')->where('idUser', (int) $idUser)->limit(1)->get()->row();
			if ($r && (int) $r->no_promo > 0) {
				return (int) $r->no_promo;
			}
		}
		if ((float) $diskonBuy1get3Val > 0) {
			$r = $this->db->select('no_promo')->from('ap_cart_diskon_buy1get3')->where('idUser', (int) $idUser)->limit(1)->get()->row();
			if ($r && (int) $r->no_promo > 0) {
				return (int) $r->no_promo;
			}
		}
		return 0;
	}
}

