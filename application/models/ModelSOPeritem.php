<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelSOPeritem extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	function getIdBrand($idProduk){
		$this->db->select("id_brand");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$idProduk);
		$query = $this->db->get()->row();
		return $query->id_brand;
	}
	function getIdKategori($idProduk){
		$this->db->select("id_kategori");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$idProduk);
		$query = $this->db->get()->row();
		return $query ? $query->id_kategori : null;
	}
	function getIdToko($no_so){
		$this->db->select("id_toko");
		$this->db->from("so_peritem");
		$this->db->where("no_so",$no_so);
		$query = $this->db->get()->row();
		return $query->id_toko;
	}
	function totalBrand(){
		$this->db->from("brand");
		return $this->db->count_all_results();
	}
	/** Distinct (id_brand, id_kategori) pairs with SO in year for store — must match rekapMatrixSoRows() filters */
	function countRekapDistinctPairs($id_toko, $tahun, $search = ''){
		$y = (int) $tahun;
		$this->db->select('so_peritem.id_brand, IFNULL(so_peritem.id_kategori, -2147483648) AS kid', false);
		$this->db->from('so_peritem');
		$this->db->join('brand', 'brand.id_brand = so_peritem.id_brand');
		$this->db->join('ap_kategori', 'ap_kategori.id_kategori = so_peritem.id_kategori', 'left');
		if ($id_toko !== '' && $id_toko !== null){
			$this->db->where('so_peritem.id_toko', $id_toko);
		}
		$this->db->where('so_peritem.tanggal_so >=', sprintf('%04d-01-01 00:00:00', $y));
		$this->db->where('so_peritem.tanggal_so <', sprintf('%04d-01-01 00:00:00', $y + 1));
		$this->db->where('so_peritem.type', 0);
		if ($search !== null && $search !== ''){
			$this->db->group_start();
			$this->db->like('brand.brand', $search);
			$this->db->or_like('ap_kategori.kategori', $search);
			$this->db->group_end();
		}
		$this->db->group_by('so_peritem.id_brand, IFNULL(so_peritem.id_kategori, -2147483648)', false);
		return $this->db->get()->num_rows();
	}
	/** All SO lines for rekap matrix (brand + kategori + month); latest tanggal_so first for month cell */
	function rekapMatrixSoRows($id_toko, $tahun, $search = ''){
		$y = (int) $tahun;
		$this->db->select('brand.brand, brand.id_brand, so_peritem.id_kategori, IFNULL(ap_kategori.kategori, "") AS nama_kategori, so_peritem.no_so, LEFT(so_peritem.tanggal_so, 7) AS bulan, so_peritem.tanggal_so', false);
		$this->db->from('so_peritem');
		$this->db->join('brand', 'brand.id_brand = so_peritem.id_brand');
		$this->db->join('ap_kategori', 'ap_kategori.id_kategori = so_peritem.id_kategori', 'left');
		if ($id_toko !== '' && $id_toko !== null){
			$this->db->where('so_peritem.id_toko', $id_toko);
		}
		$this->db->where('so_peritem.tanggal_so >=', sprintf('%04d-01-01 00:00:00', $y));
		$this->db->where('so_peritem.tanggal_so <', sprintf('%04d-01-01 00:00:00', $y + 1));
		$this->db->where('so_peritem.type', 0);
		if ($search !== null && $search !== ''){
			$this->db->group_start();
			$this->db->like('brand.brand', $search);
			$this->db->or_like('ap_kategori.kategori', $search);
			$this->db->group_end();
		}
		$this->db->order_by('so_peritem.tanggal_so', 'DESC');
		return $this->db->get();
	}
	/** Brands that have at least one (brand,kategori) pair with stock and no SO in last 30 days for this store */
	function listBrandsEligible($idStore){
		$cutoff = date('Y-m-d H:i:s', strtotime('30 days ago'));
		$idStore = (int) $idStore;
		$query = "SELECT DISTINCT b.id_brand, b.brand
			FROM brand b
			WHERE EXISTS (
				SELECT 1 FROM ap_produk p
				INNER JOIN stok_store ss ON ss.id_produk = p.id_produk AND ss.id_store = ".$this->db->escape($idStore)."
				WHERE p.id_brand = b.id_brand AND p.status = 1
				AND NOT EXISTS (
					SELECT 1 FROM so_peritem sp
					WHERE sp.id_toko = ".$this->db->escape($idStore)."
					AND sp.id_brand = b.id_brand
					AND sp.tanggal_so >= ".$this->db->escape($cutoff)."
					AND (
						(p.id_kategori IS NOT NULL AND sp.id_kategori IS NOT NULL AND sp.id_kategori = p.id_kategori)
						OR (p.id_kategori IS NULL AND sp.id_kategori IS NULL)
					)
				)
			)
			ORDER BY b.brand";
		return $this->db->query($query);
	}
	function listBrand($idStore){
		return $this->listBrandsEligible($idStore);
	}
	/** Top-level kategori eligible for brand+store (pair not SO'd in last 30 days) */
	function listKategoriEligibleForBrand($idStore, $idBrand){
		$cutoff = date('Y-m-d H:i:s', strtotime('30 days ago'));
		$idStore = (int) $idStore;
		$idBrand = (int) $idBrand;
		$query = "SELECT DISTINCT k.id_kategori, k.kategori
			FROM ap_kategori k
			INNER JOIN ap_produk p ON p.id_kategori = k.id_kategori AND p.id_brand = ".$this->db->escape($idBrand)." AND p.status = 1
			INNER JOIN stok_store ss ON ss.id_produk = p.id_produk AND ss.id_store = ".$this->db->escape($idStore)."
			WHERE NOT EXISTS (
				SELECT 1 FROM so_peritem sp
				WHERE sp.id_toko = ".$this->db->escape($idStore)."
				AND sp.id_brand = ".$this->db->escape($idBrand)."
				AND sp.tanggal_so >= ".$this->db->escape($cutoff)."
				AND sp.id_kategori = k.id_kategori
			)
			ORDER BY k.kategori";
		return $this->db->query($query);
	}
	function SO_item_hasil($start,$end,$toko){
		$this->db->select(array("so_peritem_data.revisi","ap_produk.nama_produk","brand.brand","IFNULL(ap_kategori.kategori,'') as kategori_so","so_peritem_data.no_so","so_peritem_data.stok_after","so_peritem_data.stok_before","ap_produk.satuan","so_peritem_data.harga","so_peritem_data.min","so_peritem_data.max","(so_peritem_data.harga*so_peritem_data.stok_after) as total","ap_produk.id_produk","ap_produk_price.harga as harga_jual"));
		$this->db->from("so_peritem_data");
		$this->db->join("ap_produk","ap_produk.id_produk = so_peritem_data.sku","left");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk","left");
		$this->db->join("so_peritem","so_peritem.no_so = so_peritem_data.no_so");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = so_peritem.id_kategori","left");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");
		$this->db->where("so_peritem_data.tanggal BETWEEN '$start' and '$end'");
		$this->db->where("so_peritem.id_toko",$toko);
		$this->db->where("so_peritem_data.stok_after!=so_peritem_data.stok_before");
		$this->db->order_by("ap_produk.nama_produk");
		$this->db->group_by("so_peritem_data.no_so");
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}
	function SO_item_hasil_null($start,$end,$toko,$brand=''){
		if(!empty($brand)) $whr = "and ap_produk.id_brand='$brand'";
		else $whr = '';
		$query = "SELECT ap_produk.nama_produk,brand.brand,ap_produk.satuan,ap_produk.id_produk
		from stok_store 
		join ap_produk on ap_produk.id_produk=stok_store.id_produk
		left join brand on brand.id_brand=ap_produk.id_brand
		where (stok_store.last_SO < '$start' or stok_store.last_SO > '$end')
		and stok_store.id_store='$toko'
		and ap_produk.status=1
		$whr
		group by ap_produk.id_produk
		order by ap_produk.nama_produk";
		return $this->db->query($query);
	}

	function get_bahan_baku_select2_ajax($term){
		$this->db->select(array("sku","nama_bahan"));
		$this->db->from("bahan_baku");
		$this->db->like("bahan_baku.sku",$term);
		$this->db->or_like("bahan_baku.nama_bahan",$term);
		$this->db->where("status",1); //CHOOOSE ACTIVE
		$this->db->where("del",1); //CHOOSE ACTIVE
		$this->db->where("type",0);
		$this->db->or_where("type",1);
		$this->db->order_by("sku","ASC");
		return $this->db->get();
	}

	function hapusCartSO($idUser){
		$this->db->where("idUser",$idUser);
        $this->db->delete("cc_cartSO_peritem");
	}

	function brandFromCart($idUser, $idStore){
		$this->db->select("ap_produk.id_brand");
		$this->db->from("cc_cartSO_peritem");
		$this->db->join("ap_produk", "ap_produk.id_produk = cc_cartSO_peritem.idProduk");
		$this->db->where("cc_cartSO_peritem.idUser", $idUser);
		$this->db->group_start();
		$this->db->where("cc_cartSO_peritem.store", $idStore);
		$this->db->or_where("cc_cartSO_peritem.store IS NULL", null, false);
		$this->db->group_end();
		$this->db->order_by("cc_cartSO_peritem.id", "ASC");
		$this->db->limit(1);
		$q = $this->db->get();
		if ($q->num_rows() < 1) {
			return null;
		}
		return $q->row()->id_brand;
	}

	function produkAjax($q, $id_brand = ''){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk"));
		$this->db->from("ap_produk");
		if ($id_brand === null || $id_brand === '' || (int)$id_brand < 1) {
			$this->db->where("1=0", null, false);
			return $this->db->get();
		}
		$this->db->where("ap_produk.id_brand", (int)$id_brand);
		$this->db->group_start();
		$this->db->like("ap_produk.id_produk",$q);
		$this->db->or_like("ap_produk.nama_produk",$q);	
		$this->db->or_like("ap_produk.qr_code",$q);	
		$this->db->group_end();
		$this->db->group_start();
		$this->db->where("ap_produk.status",1);
		$this->db->or_where("ap_produk.status",0);
		$this->db->group_end();
		return $this->db->get();
	}
	function produkAjaxSupplier($idBrand,$idToko,$idKategori){
		$this->db->select(array("ap_produk.id_produk","stok_store.hpp","stok_store.min","stok_store.max","stok_store.stok as pesan"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk","left");
		$this->db->where("ap_produk.id_brand",$idBrand);
		if ((int)$idKategori >= 1) {
			$this->db->where("ap_produk.id_kategori",$idKategori);
		}
		$this->db->where("stok_store.id_store",$idToko);
		$this->db->where("ap_produk.status",1);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function getIdCart($idProduk,$idUser){
		$this->db->select("id");
		$this->db->from("cc_cartSO_peritem");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		$query = $this->db->get()->row();
		return $query->id;
	}

	function totalPeritem($idUser,$idProduk){
		$this->db->select("(harga*stok_after) as total");
		$this->db->from("cc_cartSO_peritem");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		$query = $this->db->get()->row();
		return $query->total; 
	}

	function totalCartPeruser($idUser){
		$this->db->select("SUM(harga*stok_after) as total");
		$this->db->from("cc_cartSO_peritem");
		$this->db->where("idUser",$idUser);
		$this->db->group_by("cc_cartSO_peritem.idUser");
		$query = $this->db->get()->row();
		return $query->total;
	}

	function hargaBeliProduk($idProduk,$idStore='7'){
		$this->db->select("hpp");
        $this->db->from("ap_produk_price");
		$this->db->where("id_produk",$idProduk);
        $this->db->where("id_toko",$idStore);
		$query = $this->db->get()->row(); 
		return $query->hpp;
	}
	function hargaJualProduk($idProduk,$idStore='7'){
		$this->db->select("harga");
        $this->db->from("ap_produk_price");
		$this->db->where("id_produk",$idProduk);
        $this->db->where("id_toko",$idStore);
		$query = $this->db->get()->row(); 
		return $query->harga;
	}

	function stokProduk($idProduk,$idStore='7'){
		$this->db->select(array("SUM(qty) as stok"));
        $this->db->from("stok_store_kartu");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_store",$idStore);
		$this->db->group_by("id_produk");
		$this->db->limit(1);
		$query = $this->db->get()->row(); 
		return $query;
	}

	function viewCartPO($idUser,$store){
		$stokBeforeSub = "(SELECT COALESCE(SUM(ssk.qty),0) FROM stok_store_kartu ssk WHERE ssk.id_produk = cc_cartSO_peritem.idProduk AND ssk.id_store = ".$this->db->escape($store).") AS stok_before";
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan",$stokBeforeSub,"cc_cartSO_peritem.stok_after","cc_cartSO_peritem.min","cc_cartSO_peritem.max","cc_cartSO_peritem.harga","cc_cartSO_peritem.id"));
		$this->db->from("cc_cartSO_peritem");
		$this->db->join("ap_produk","ap_produk.id_produk = cc_cartSO_peritem.idProduk");
		$this->db->where("cc_cartSO_peritem.idUser",$idUser);
		$this->db->where("cc_cartSO_peritem.store",$store);
		//$this->db->order_by("ap_produk.id","DESC");
		$this->db->order_by("ap_produk.nama_produk");
		return $this->db->get();
	}

	function cekCartPO($idProduk,$idUser){
		$this->db->from("cc_cartSO_peritem");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function currentQtyCart($idProduk,$idUser){
		$this->db->select("stok_after");
		$this->db->from("cc_cartSO_peritem");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->row();
		return $query->stok_after;
	}

	function SO_item($no_so){
		$this->db->select(array("ap_produk.nama_produk","so_peritem_data.no_so","so_peritem_data.revisi","so_peritem_data.stok_after","so_peritem_data.stok_before","ap_produk.satuan","so_peritem_data.harga","so_peritem_data.harga_jual","so_peritem_data.min","so_peritem_data.max","(so_peritem_data.harga*so_peritem_data.stok_after) as total","ap_produk.id_produk"));
		$this->db->from("so_peritem_data");
		$this->db->join("ap_produk","ap_produk.id_produk = so_peritem_data.sku","left");
		$this->db->where("so_peritem_data.no_so",$no_so);
		$this->db->order_by("ap_produk.nama_produk");
		return $this->db->get();
	}
	function SO_item_selisih($no_so){
		$this->db->select(array("ap_produk.nama_produk","so_peritem_data.no_so","so_peritem_data.revisi","so_peritem_data.stok_after","so_peritem_data.stok_before","ap_produk.satuan","so_peritem_data.harga","so_peritem_data.harga_jual","so_peritem_data.min","so_peritem_data.max","(so_peritem_data.harga*so_peritem_data.stok_after) as total","ap_produk.id_produk"));
		$this->db->from("so_peritem_data");
		$this->db->join("ap_produk","ap_produk.id_produk = so_peritem_data.sku","left");
		$this->db->where("so_peritem_data.no_so",$no_so);
		$this->db->where("(so_peritem_data.stok_after-so_peritem_data.stok_before) <> 0");
		$this->db->order_by("ap_produk.nama_produk");
		$this->db->order_by("so_peritem_data.revisi");
		return $this->db->get();
	}

	function received_item($no_receive){
		$this->db->select("*");
		$this->db->from("receive_item");
		$this->db->join("ap_produk","ap_produk.id_produk = receive_item.sku","left");
		$this->db->where("no_receive",$no_receive);
		return $this->db->get();
	}

	function infoPurchase($no_po){
		$this->db->select(array("so_peritem.tanggal_so","so_peritem.keterangan","so_peritem.id_toko","ap_store.store","IFNULL(ap_kategori.kategori,'') as nama_kategori"));
		$this->db->from("so_peritem");
		$this->db->join("ap_store","ap_store.id_store = so_peritem.id_toko","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = so_peritem.id_kategori","left");
		$this->db->where("so_peritem.no_so",$no_po);
		$this->db->group_by("so_peritem.no_so");
		$query = $this->db->get()->row();
		return $query;
	}

	function emailSupplier($idSupplier){
		$this->db->select("email");
		$this->db->from("supplier");
		$this->db->where("id_supplier",$idSupplier);
		$query = $this->db->get()->row();

		return $query->email;
	}

	function cekEmailIfExist($idSupplier){
		$this->db->select("email");
		$this->db->from("supplier");
		$this->db->where("id_supplier",$idSupplier);
		$query = $this->db->get();

		foreach($query->result() as $row){
			$email = $row->email;
		
			if($email==''){
				return 0;
			} else {
				return 1;
			}
		}
	}

	function insertPONumber($data_masuk){
		$this->db->insert("so_peritem",$data_masuk);
	}

	function insertPOItem($data_bahan){
		$this->db->insert_batch("so_peritem_data",$data_bahan);
	}

	function updateStokItem($sku,$dataUpdate,$idStore){
		$this->db->where("id_store",$idStore);
		$this->db->where('id_produk',$sku);
		$this->db->update("stok_store",$dataUpdate);
	}

	function stokItem($idStore,$idProduk){
		$this->db->select("SUM(qty) as stok");
		$this->db->from("stok_store_kartu");
		$this->db->where("id_store",$idStore);
		$this->db->where("id_produk",$idProduk);
		$query = $this->db->get()->row();
		$stok = is_object($query) ? $query->stok:0;
		return $stok;
	}

	function deleteCartPO($idUser){
		$this->db->delete("cc_cartSO_peritem",array("idUser" => $idUser));
	}

	function insertCartPO($dataCart){
		$this->db->insert("cc_cartSO_peritem",$dataCart);
	}
	function insertBatchDataStok($data_insert){
		$this->db->insert_batch("stok_store",$data_insert);
	}

	function updateQtyCart($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where('idUser',$idUser);
		$this->db->update("cc_cartSO_peritem",$dataUpdate);
	}

	function updateHargaCart($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where('idUser',$idUser);
		$this->db->update("cc_cartSO_peritem",$dataUpdate);
	}

	function hapusCart($idProduk,$idUser){
		$this->db->delete("cc_cartSO_peritem",array("idProduk" => $idProduk, "idUser" => $idUser));
	}

	function getStore($idStore){
		$this->db->select("store");
		$this->db->from("ap_store");
		$this->db->where("id_store",$idStore);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->store;
		}
	}

	function tanggal_so($no_so){
		$this->db->select("tanggal_so");
		$this->db->from("so_peritem");
		$this->db->where("no_so",$no_so);
		$query = $this->db->get()->row();

		return $query->tanggal_so;
	}
	function updateRevisi($idProduk,$no_so,$dataUpdate){
		$this->db->where("sku",$idProduk);
		$this->db->where('no_so',$no_so);
		$this->db->update("so_peritem_data",$dataUpdate);
	}
}