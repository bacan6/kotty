<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelProduk extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function info_KartuStok($sku,$idToko){
		$query = "SELECT stok_store_kartu.no_transaksi,ap_store.store,stok_store_kartu.id_produk,stok_store_kartu.qty,stok_store_kartu.tanggal,ap_produk.nama_produk,users.username,users.first_name,stok_store_kartu.keterangan,stok_store_kartu.tipe
		FROM `stok_store_kartu`
					left join ap_produk on ap_produk.id_produk=stok_store_kartu.id_produk
					left outer join users on users.id=stok_store_kartu.id_pic
					left join ap_store on ap_store.id_store=stok_store_kartu.id_store
					where stok_store_kartu.id_produk='$sku' and stok_store_kartu.id_store='$idToko'
					order by stok_store_kartu.tanggal";
		return $this->db->query($query);
	}

	function info_Transfer($sku,$idToko){
		$query = "SELECT transferstoknumber.noTransfer,transferstokitem.idProduk,transferstokitem.qty,transferstokitem.qty_rec,transferstoknumber.tanggal,transferstoknumber.tanggal_terima,ap_produk.nama_produk,users.username,users.first_name 
		FROM `transferstokitem`
					left join transferstoknumber on transferstoknumber.noTransfer=transferstokitem.noTransfer
					left join ap_produk on ap_produk.id_produk=transferstokitem.idProduk
					left join users on users.id=transferstoknumber.idUser
					where transferstokitem.idProduk='$sku' and transferstoknumber.transferFrom='$idToko'
					order by transferstoknumber.tanggal DESC,transferstoknumber.noTransfer DESC";
		return $this->db->query($query);
	}
	function info_TransferDiterima($sku,$idToko){
		$query = "SELECT transferstoknumber.noTransfer,transferstokitem.idProduk,transferstokitem.qty,transferstokitem.qty_rec,transferstoknumber.tanggal,transferstoknumber.tanggal_terima,ap_produk.nama_produk,users.username,users.first_name 
		FROM `transferstokitem`
					left join transferstoknumber on transferstoknumber.noTransfer=transferstokitem.noTransfer
					left join ap_produk on ap_produk.id_produk=transferstokitem.idProduk
					left join users on users.id=transferstoknumber.id_penerima
					where transferstokitem.idProduk='$sku' and transferstoknumber.transferTo='$idToko'
					order by transferstoknumber.tanggal DESC,transferstoknumber.noTransfer DESC";
		return $this->db->query($query);
	}

	function cekSKUIfExist($sku){
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$sku);
		return $this->db->count_all_results();
	}

	function produkJoin($sku){
		$this->db->select(array("ap_produk.status","ap_produk.id_produk","ap_produk.qr_code","ap_produk.nama_produk","ap_produk.isi","ap_produk.hpp as harga_beli","ap_produk.satuan","ap_produk.tempat","ap_produk.id_kategori","ap_produk.id_subkategori","ap_produk.id_subkategori_2","ap_produk.id_supplier","ap_produk.id_brand"));
		$this->db->from("ap_produk");
		$this->db->where("ap_produk.id_produk",$sku);
		//$this->db->where("ap_produk.status",1);
		//$this->db->or_where("ap_produk.status",0);
		//$this->db->or_where("ap_produk.status",2);
		return $this->db->get()->result();
	}

	function getPrice($idStore='7',$sku){
		$this->db->select("harga");
		$this->db->from("ap_produk_price");
		$this->db->where("id_toko",$idStore);
		$this->db->where("id_produk",$sku);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->harga;
		}
	}
	function getNewSKU($id_subkategori_3){
		$this->db->select("COUNT(id_produk) as sku");
		$this->db->from("ap_produk");
		$this->db->where("id_subkategori_2",$id_subkategori_3);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->sku.date('s');
		}
	}
    function getHPP($idStore='7',$sku){
		$this->db->select("hpp");
		$this->db->from("ap_produk_price");
		$this->db->where("id_toko",$idStore);
		$this->db->where("id_produk",$sku);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->hpp;
		}
	}
	function getSupplier($idStore='7',$sku){
		$this->db->select("id_supplier");
		$this->db->from("ap_produk_supplier");
		$this->db->where("id_toko",$idStore);
		$this->db->where("id_produk",$sku);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->id_supplier;
		}
	}

	function countIfStoreExist($idStore,$id_produk){
		$this->db->from("ap_produk_price");
		$this->db->where("id_toko",$idStore);
		$this->db->where("id_produk",$id_produk);
		return $this->db->count_all_results();
	}

	function countIfSupplierExist($idStore,$id_produk){
		$this->db->from("ap_produk_supplier");
		$this->db->where("id_toko",$idStore);
		$this->db->where("id_produk",$id_produk);
		return $this->db->count_all_results();
	}

	function countIfStockExist($idStore,$id_produk){
		$this->db->from("stok_store");
		$this->db->where("id_store",$idStore);
		$this->db->where("id_produk",$id_produk);
		return $this->db->count_all_results();
	}

	function info_SOitem($sku,$idToko){
		$query = "SELECT so_peritem.keterangan,so_peritem.no_so,so_peritem_data.sku,so_peritem_data.stok_before,so_peritem_data.stok_after,so_peritem_data.tanggal,ap_produk.nama_produk,users.username,users.first_name FROM `so_peritem_data`
					left join so_peritem on so_peritem.no_so=so_peritem_data.no_so
					left join ap_produk on ap_produk.id_produk=so_peritem_data.sku
					left join users on users.id=so_peritem.id_pic
					where so_peritem_data.sku='$sku' and so_peritem.id_toko='$idToko'
					order by so_peritem_data.tanggal DESC,so_peritem.no_so DESC";
		return $this->db->query($query);
	}
	function info_Sales($sku,$idToko){
		$query = "SELECT ap_invoice_number.keterangan,ap_invoice_number.no_invoice,ap_invoice_item.id_produk,ap_invoice_item.qty,ap_invoice_number.tanggal,ap_produk.nama_produk,users.username,users.first_name 
		FROM `ap_invoice_item`
					left join ap_invoice_number on ap_invoice_number.no_invoice=ap_invoice_item.no_invoice
					left join ap_produk on ap_produk.id_produk=ap_invoice_item.id_produk
					left join users on users.id=ap_invoice_number.id_pic
					where ap_invoice_item.id_produk='$sku' and ap_invoice_number.id_toko='$idToko'
					order by ap_invoice_item.tanggal DESC,ap_invoice_number.no_invoice DESC";
		return $this->db->query($query);
	}
	function info_Receive($sku,$idToko){
		$query = "SELECT receive_order.no_receive,receive_item.sku,receive_item.qty,receive_item.bonus,receive_order.tanggal_terima as tanggal,ap_produk.nama_produk,users.username,users.first_name 
		FROM `receive_item`
					left join receive_order on receive_order.no_receive=receive_item.no_receive
					left join ap_produk on ap_produk.id_produk=receive_item.sku
					left join users on users.id=receive_order.id_pic
					where receive_item.sku='$sku' and receive_order.diterimaDi='$idToko'
					order by receive_item.tanggal DESC,receive_order.no_receive DESC";
		return $this->db->query($query);
	}
	function dataStokToko($length,$start,$search,$id){
		$query = "SELECT ap_produk.id_produk,ap_produk.nama_produk,SUM(stok_store_kartu.qty) as stok,ap_kategori.kategori,ap_kategori_1.kategori_level_1,ap_kategori_2.kategori_3,ap_produk.hpp as harga_beli, ap_stand.stand
				  FROM stok_store_kartu
				  LEFT JOIN ap_produk ON ap_produk.id_produk = stok_store_kartu.id_produk
				  LEFT outer JOIN ap_kategori ON ap_kategori.id_kategori = ap_produk.id_kategori
				  LEFT outer JOIN ap_kategori_1 ON ap_kategori_1.id = ap_produk.id_subkategori
				  LEFT outer JOIN ap_kategori_2 ON ap_kategori_2.id = ap_produk.id_subkategori_2
				  LEFT outer JOIN ap_stand ON ap_stand.id_stand = ap_produk.tempat
				  WHERE stok_store_kartu.id_store = '$id' AND (ap_produk.qr_code LIKE '$search%' OR ap_produk.nama_produk LIKE '%$search%' OR ap_produk.id_produk LIKE '%$search%')
				  and ap_produk.status=1
				  GROUP BY stok_store_kartu.id_produk
				  LIMIT $start,$length";
		return $this->db->query($query);
	}

	function dataStokTokoExp($length,$start,$search,$id){
		$query = "SELECT expired_product.tanggal_po,ap_produk.id_produk,ap_produk.nama_produk,expired_product_item.qty as stok,ap_kategori.kategori,ap_kategori_1.kategori_level_1,ap_kategori_2.kategori_3,expired_product_item.harga as harga,ap_produk_price.hpp, ap_stand.stand
				  FROM expired_product_item
				  LEFT JOIN expired_product on expired_product.no_po=expired_product_item.no_po
				  LEFT outer JOIN ap_produk ON ap_produk.id_produk = expired_product_item.sku
				  LEFT outer JOIN ap_produk_price ON ap_produk_price.id_produk = expired_product_item.sku and ap_produk_price.id_toko=expired_product.id_toko
				  LEFT outer JOIN ap_kategori ON ap_kategori.id_kategori = ap_produk.id_kategori
				  LEFT outer JOIN ap_kategori_1 ON ap_kategori_1.id = ap_produk.id_subkategori
				  LEFT outer JOIN ap_kategori_2 ON ap_kategori_2.id = ap_produk.id_subkategori_2
				  LEFT outer JOIN ap_stand ON ap_stand.id_stand = ap_produk.tempat
				  WHERE expired_product.id_toko = '$id' AND (ap_produk.qr_code LIKE '$search%' OR ap_produk.nama_produk LIKE '%$search%' OR ap_produk.id_produk LIKE '%$search%')
				  and ap_produk.status!=2 and expired_product_item.sku is not null
				  order by expired_product.tanggal_po DESC,expired_product.no_po
				  LIMIT $start,$length";
		return $this->db->query($query);
	}

	function dataStokTokoExpFull($length,$start,$id){
		$query = "SELECT expired_product.tanggal_po,ap_produk.id_produk,ap_produk.nama_produk,expired_product_item.qty as stok,ap_kategori.kategori,ap_kategori_1.kategori_level_1,ap_kategori_2.kategori_3,expired_product_item.harga as harga,ap_produk_price.hpp, ap_stand.stand
				  FROM expired_product_item
				  LEFT JOIN expired_product on expired_product.no_po=expired_product_item.no_po
				  LEFT outer JOIN ap_produk ON ap_produk.id_produk = expired_product_item.sku
				  LEFT outer JOIN ap_produk_price ON ap_produk_price.id_produk = expired_product_item.sku and ap_produk_price.id_toko=expired_product.id_toko
				  LEFT outer JOIN ap_kategori ON ap_kategori.id_kategori = ap_produk.id_kategori
				  LEFT outer JOIN ap_kategori_1 ON ap_kategori_1.id = ap_produk.id_subkategori
				  LEFT outer JOIN ap_kategori_2 ON ap_kategori_2.id = ap_produk.id_subkategori_2
				  LEFT outer JOIN ap_stand ON ap_stand.id_stand = ap_produk.tempat
				  WHERE expired_product.id_toko = '$id' and expired_product_item.sku is not null
				  order by expired_product.tanggal_po DESC,expired_product.no_po
				  LIMIT $start,$length";
		return $this->db->query($query);
	}

	function dataStokTokoFull($length,$start,$id){
		$query = "SELECT ap_produk.id_produk,ap_produk.nama_produk,SUM(stok_store_kartu.qty) as stok,ap_kategori.kategori,ap_kategori_1.kategori_level_1,ap_kategori_2.kategori_3,ap_produk.hpp as harga_beli, ap_stand.stand
				  FROM stok_store_kartu
				  LEFT JOIN ap_produk ON ap_produk.id_produk = stok_store_kartu.id_produk
				  LEFT outer JOIN ap_kategori ON ap_kategori.id_kategori = ap_produk.id_kategori
				  LEFT outer JOIN ap_kategori_1 ON ap_kategori_1.id = ap_produk.id_subkategori
				  LEFT outer JOIN ap_kategori_2 ON ap_kategori_2.id = ap_produk.id_subkategori_2
				  LEFT outer JOIN ap_stand ON ap_stand.id_stand = ap_produk.tempat
				  WHERE stok_store_kartu.id_store = '$id'
				  and ap_produk.status=1
				  GROUP BY stok_store_kartu.id_produk
				  ORDER BY ap_produk.nama_produk
				  LIMIT $start,$length";
		return $this->db->query($query);
	}

	function dataStokTokoFullExport($id){
		$query = "SELECT ap_produk.id_produk,ap_produk.nama_produk,stok_store.stok as stok,ap_kategori.kategori,ap_kategori_1.kategori_level_1,ap_kategori_2.kategori_3,ap_produk_price.hpp as harga_beli,ap_produk_price.harga as harga_jual, ap_stand.stand,brand.brand
				  FROM stok_store
				  LEFT JOIN ap_produk ON ap_produk.id_produk = stok_store.id_produk
				  LEFT OUTER JOIN ap_produk_price ON ap_produk_price.id_produk = stok_store.id_produk and ap_produk_price.id_toko=stok_store.id_store
				  LEFT outer JOIN ap_kategori ON ap_kategori.id_kategori = ap_produk.id_kategori
				  LEFT outer JOIN ap_kategori_1 ON ap_kategori_1.id = ap_produk.id_subkategori
				  LEFT outer JOIN ap_kategori_2 ON ap_kategori_2.id = ap_produk.id_subkategori_2
				  LEFT outer JOIN ap_stand ON ap_stand.id_stand = ap_produk.tempat
				  LEFT outer JOIN brand ON brand.id_brand = ap_produk.id_brand
				  WHERE stok_store.id_store = '$id'
				  AND ap_produk.status='1'
				  GROUP BY stok_store.id_produk";
		return $this->db->query($query);
	}

	function dataStokTokoFullInventori($id){
		$query = "SELECT SUM(stok_store.hpp*stok_store.stok) as nilai,
						SUM(stok_store.harga*stok_store.stok) as nilaiJual
				  FROM stok_store
				  LEFT OUTER JOIN ap_produk ON ap_produk.id_produk = stok_store.id_produk
				  WHERE stok_store.id_store = '$id'
				  and ap_produk.status=1
				  and stok_store.stok<>0";
		return $this->db->query($query);
		
	}

	function totalProdukPromotion($id){
		$this->db->from("stok_store");
		$this->db->where("stok_store.id_store",$id);
		$this->db->group_by("stok_store.id_produk");
		return $this->db->count_all_results();
	}

	function totalProdukPromotionExp($id){
		$this->db->from("expired_product_item");
		$this->db->join("expired_product","expired_product.no_po = expired_product_item.no_po");
		$this->db->where("expired_product.id_toko",$id);
		return $this->db->count_all_results();
	}

	function hargaJual($idProduk,$idToko){
		$this->db->select("harga");
		$this->db->from("ap_produk_price");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_toko",$idToko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->harga;
		}
	}
	function stokToko($idProduk,$idToko){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_store",$idToko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->stok;
		}
	}
	function margin($idProduk,$idToko){
		$this->db->select("(((harga-hpp)/harga)*100) as margin");
		$this->db->from("ap_produk_price");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_toko",$idToko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->margin;
		}
	}
    function hargaBeli($idProduk,$idToko){
		$this->db->select("hpp");
		$this->db->from("ap_produk_price");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_toko",$idToko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->hpp;
		}
	}

	function jumlahBarangMasuk($idProduk,$idToko){
		$search = date('Y-m');
		$this->db->select("sum(receive_item.qty) as jumlah");
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive");
		$this->db->where("receive_item.sku",$idProduk);
		$this->db->like("receive_item.tanggal",$search);
		$this->db->where("receive_order.diterimaDi",$idToko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->jumlah;
		}
	}

	function jumlahTransferMasuk($idProduk,$idToko){
		$search = date('Y-m');
		$this->db->select("sum(transferstokitem.qty) as jumlah");
		$this->db->from("transferstokitem");
		$this->db->join("transferstoknumber","transferstoknumber.noTransfer = transferstokitem.noTransfer");
		$this->db->where("transferstokitem.idProduk",$idProduk);
		$this->db->like("transferstoknumber.tanggal",$search);
		$this->db->where("transferstoknumber.transferTo",$idToko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->jumlah;
		}
	}

	function jumlahTransferKeluar($idProduk,$idToko){
		$search = date('Y-m');
		$this->db->select("sum(transferstokitem.qty) as jumlah");
		$this->db->from("transferstokitem");
		$this->db->join("transferstoknumber","transferstoknumber.noTransfer = transferstokitem.noTransfer");
		$this->db->where("transferstokitem.idProduk",$idProduk);
		$this->db->like("transferstoknumber.tanggal",$search);
		$this->db->where("transferstoknumber.transferFrom",$idToko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->jumlah;
		}
	}

	function jumlahMutasiMasuk($idProduk,$idToko){
		$search = date('Y-m');
		$this->db->select("sum(sp_bahan_keluar.qty) as jumlah");
		$this->db->from("sp_bahan_keluar");
		$this->db->join("sp_no_bahan_keluar","sp_no_bahan_keluar.no_bahan_keluar = sp_bahan_keluar.no_bahan_keluar");
		$this->db->where("sp_bahan_keluar.sku",$idProduk);
		$this->db->like("sp_no_bahan_keluar.tanggal_keluar",$search);
		$this->db->where("sp_no_bahan_keluar.store_tujuan",$idToko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->jumlah;
		}
	}

	function jumlahPenjualan($idProduk,$idToko){
		$search = date('Y-m');
		$this->db->select("sum(ap_invoice_item.qty) as jumlah");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice");
		$this->db->where("ap_invoice_item.id_produk",$idProduk);
		$this->db->like("ap_invoice_number.tanggal",$search);
		$this->db->where("ap_invoice_number.id_toko",$idToko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->jumlah;
		}
	}

	function jumlahRetur($idProduk,$idToko){
		$search = date('Y-m');
		$this->db->select("sum(retur_item.qty) as jumlah");
		$this->db->from("retur_item");
		$this->db->join("retur","retur.no_retur = retur_item.no_retur");
		$this->db->join("purchase_order","purchase_order.no_po = retur.no_po");
		$this->db->where("retur_item.sku",$idProduk);
		$this->db->like("retur.tanggal_retur",$search);
		$this->db->where("purchase_order.id_toko",$idToko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->jumlah;
		}
	}

	function data_stok_distributor($id_store,$stand,$kategori,$subKategori,$subKategori2){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","stok_store.stok","ap_kategori.kategori","ap_kategori_1.kategori_level_1","bahan_baku.harga"));
		$this->db->from("ap_produk");
		$this->db->join("stok_store","ap_produk.id_produk = stok_store.id_produk");
		$this->db->join("ap_kategori","ap_produk.id_kategori = ap_kategori.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_produk.id_subkategori = ap_kategori_1.id","left");
		$this->db->join("bahan_baku","bahan_baku.sku = stok_store.id_produk","left");

		$this->db->where("stok_store.id_store",$id_store);

		if(!empty($stand)){
			$this->db->where("ap_produk.tempat",$stand);
		}

		if(!empty($kategori)){
			$this->db->where("ap_produk.id_kategori",$kategori);
		}

		if(!empty($subKategori)){
			$this->db->where("ap_produk.id_subkategori",$subKategori);
		}

		if(!empty($subKategori2)){
			$this->db->where("ap_produk.id_subkategori_2",$subKategori2);
		}

		$this->db->group_by("stok_store.id_produk");
		return $this->db->get();
	}

	function updateSoBatch($kode_toko,$data_stok){
		$this->db->where("id_store",$kode_toko);
		$this->db->update_batch("stok_store",$data_stok,'id_produk');
	}

	function updateSupplierBatch($kode_toko,$data_supplier){
		$this->db->where("id_toko",$kode_toko);
		$this->db->update_batch("ap_produk_supplier",$data_supplier,'id_produk');
	}

	

	function hargaJualPerToko($idStore,$idProduk){
		$this->db->select("harga");
		$this->db->from("ap_produk_price");
		$this->db->where("id_toko",$idStore);
		$this->db->where("id_produk",$idProduk);
		return $this->db->get()->row();
	}

	function viewCartMutasi($idUser){
		$this->db->select(array("ap_produk.stok","cc_cartmutasi.idProduk","ap_produk.nama_produk","ap_produk.harga as hargaBeli","cc_cartmutasi.qty","cc_cartmutasi.id"));
		$this->db->from("cc_cartmutasi");
		$this->db->join("ap_produk","ap_produk.id_produk = cc_cartmutasi.idProduk");
		$this->db->where("cc_cartmutasi.idUser",$idUser);
		$this->db->order_by("cc_cartmutasi.id","DESC");
		$this->db->group_by("cc_cartmutasi.idProduk");
		return $this->db->get()->result();
	}

	function cekCartMutasi($idProduk,$idUser){
		$this->db->from("cc_cartmutasi");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function currentQtyCart($idProduk,$idUser){
		$this->db->select("qty");
		$this->db->from("cc_cartmutasi");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function lastStok($idProduk){
		$this->db->select("stok");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$idProduk);
		$query = $this->db->get()->row(); 
		return $query->stok;
	}

	function totalProdukActive($id_kategori,$sub_kategori,$id_brand){
		$this->db->from("ap_produk");
		if (!empty($id_kategori)){
			$this->db->where("id_kategori",$id_kategori);
		}
		if (!empty($sub_kategori)){
			$this->db->where("id_subkategori",$sub_kategori);
		}
		if (!empty($id_brand)){
			$this->db->where("id_brand",$id_brand);
		}
		
            $this->db->where("ap_produk.status",1);
            //$this->db->or_where("ap_produk.status",0);
        
		return $this->db->count_all_results();
	}
	function hargaPromo($idProduk,$idStore){
		$hari = date('w');
		$tanggal = date('Y-m-d');
		$jam = date('H:i');
		$this->db->select(array("concat(LEFT(ap_produk_discount_rules.JamMulai,5),'-',LEFT(ap_produk_discount_rules.JamSelesai,5)) as Jam,(ap_produk_discount_rules.discount+ap_produk_discount_rules.disc_supplier) as disc","SUM(ap_invoice_item.qty) as qty","ap_produk_discount_rules.quota"));
		$this->db->from("ap_produk_discount_rules");
		$this->db->join("ap_invoice_item","ap_invoice_item.id_produk=ap_produk_discount_rules.id_produk and ap_invoice_item.diskon=(ap_produk_discount_rules.discount+ap_produk_discount_rules.disc_supplier) and (ap_invoice_item.tanggal between ap_produk_discount_rules.date_start and ap_produk_discount_rules.date_end)","left");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice=ap_invoice_item.no_invoice and (ap_invoice_number.tanggal BETWEEN concat(ap_produk_discount_rules.date_start,' ',ap_produk_discount_rules.JamMulai) and concat(ap_produk_discount_rules.date_end,' ',ap_produk_discount_rules.JamSelesai)) and ap_invoice_number.id_toko=ap_produk_discount_rules.id_toko","left");
		$this->db->where("ap_produk_discount_rules.id_produk",$idProduk);
		$this->db->where("ap_produk_discount_rules.id_toko",$idStore);
		$this->db->where(" '$tanggal' between ap_produk_discount_rules.date_start and ap_produk_discount_rules.date_end");
		$this->db->where("'$jam' BETWEEN ap_produk_discount_rules.JamMulai and ap_produk_discount_rules.JamSelesai");
		$this->db->where("ap_produk_discount_rules.HariID like '%$hari%'");
		$this->db->group_by("ap_produk_discount_rules.no_promo");
		$this->db->order_by("(ap_produk_discount_rules.discount+ap_produk_discount_rules.disc_supplier)","DESC");
		//$this->db->limit(1);
		$query = $this->db->get()->result(); 
		$diskon = 0;
		foreach($query as $row){
			if($diskon==0){
				if($row->quota > 0){
					if ($row->quota > $row->qty){
						$diskon = $row->disc;
					}
				}else {
					$diskon = $row->disc;
				}
			}
		}
		return $diskon;
	}
	function periodePromo($idProduk,$idStore){
		$hari = date('w');
		$tanggal = date('Y-m-d');
		$jam = date('H:i:s');
		$this->db->select("concat(date_start,' s/d ',date_end) as periode");
		$this->db->from("ap_produk_discount_rules");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_toko",$idStore);
		$this->db->where("qty",1);
		$this->db->where("date_end >= '$tanggal'");
		//$this->db->where("'$jam' BETWEEN JamMulai and JamSelesai");
		$this->db->order_by("date_end","ASC");
		$query = $this->db->get()->result(); 
		foreach($query as $row){
			return $row->periode;
		}
	}
	function tanggalPromo($idProduk,$idStore){
		$hari = date('w');
		$tanggal = date('Y-m-d');
		$jam = date('H:i:s');
		$this->db->select("concat(date_format(date_end,'%d/%m/%y')) as tgl");
		//$this->db->select("concat(date_format(date_start,'%d/%m/%y'),' s/d ',date_format(date_end,'%d/%m/%y')) as tgl");
		$this->db->from("ap_produk_discount_rules");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_toko",$idStore);
		//$this->db->where("qty",1);
		$this->db->where("date_end >= '$tanggal'");
		//$this->db->where("'$jam' BETWEEN JamMulai and JamSelesai");
		$this->db->order_by("date_end","DESC");
		$query = $this->db->get()->result(); 
		foreach($query as $row){
			return $row->tgl;
		}
	}
	function hargaPromoBrand($idProduk,$idStore){
		$hari = date('w');
		$tanggal = date('Y-m-d');
		$jam = date('H:i:s');
		$this->db->select(array("concat(date_format(ap_promo_brand_rules.date_end,'%d/%m/%y')) as tgl","ap_promo_brand_rules.rules_type","ap_promo_brand_rules.minBelanja","ap_promo_brand_rules.discount"));
		$this->db->from("ap_produk");
		$this->db->join("ap_promo_brand_rules","ap_promo_brand_rules.id_brand = ap_produk.id_brand");
		$this->db->where("ap_produk.id_produk",$idProduk);
		$this->db->where("ap_promo_brand_rules.id_toko",$idStore);
		$this->db->where("ap_produk.status",1);
		$this->db->where("'$tanggal' BETWEEN ap_promo_brand_rules.date_start and ap_promo_brand_rules.date_end");
		//$this->db->where("'$jam' BETWEEN ap_promo_brand_rules.JamMulai and ap_promo_brand_rules.JamSelesai");
		$this->db->where("ap_promo_brand_rules.HariID like '%$hari%'");
		$this->db->group_by("ap_promo_brand_rules.discount");
		$this->db->group_by("ap_produk.id_brand");
		$query = $this->db->get()->result(); 
		return $query;
	}
	function daftarProdukAll($limit,$start,$search='',$id_kategori,$sub_kategori,$idToko='',$id_brand=''){
		//$idToko = empty($idToko)?"7":$idToko;
		//var_dump($id_brand);
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan","((ap_produk_price.harga - ap_produk_price.hpp)/ap_produk_price.harga *100) as margin","ap_produk_price.harga","ap_produk_price.hpp","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","ap_produk.status","ap_produk.id_supplier"));
		$this->db->from("ap_produk");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk and ap_produk_price.id_toko='$idToko'","left");
		//$this->db->join("ap_produk_supplier","ap_produk_supplier.id_produk = ap_produk.id_produk and ap_produk_supplier.id_toko='$idToko'","left");
		//$this->db->join("stok_store","stok_store.id_produk = ap_produk.id_produk and stok_store.id_store='$idToko'","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		//$this->db->join("supplier","supplier.id_supplier = ap_produk_supplier.id_supplier","left");
		//$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");

		//$idToko
		if(!empty($idToko)){
			$this->db->where("ap_produk_price.id_toko",$idToko);
		}
		
		if(!empty($search)){
			$this->db->where("(ap_produk.nama_produk like '%$search%' or ap_produk.id_produk like '%$search%') ");
			//$this->db->or_like("ap_produk.id_produk",$search);
			//$this->db->or_like("ap_produk.qr_code",$search);
		}
		if (!empty($id_kategori)){
			$this->db->where("ap_produk.id_kategori",$id_kategori);
		}
		if (!empty($sub_kategori)){
			$this->db->where("ap_produk.id_subkategori",$sub_kategori);
		}

		if (!empty($id_brand)){
			$this->db->where("ap_produk.id_brand",$id_brand);
		}
		
		if (empty($search)){
		$this->db->where("ap_produk.status",1);
        //     $this->db->or_where("ap_produk.status",0);
		// 	$this->db->or_where("ap_produk.status",2);
		}
            
        


		$this->db->group_by("ap_produk.id_produk");
		
		$this->db->order_by("ap_produk.nama_produk");
		$this->db->limit($limit,$start);
		return $this->db->get();	
	}

	function daftarProdukAllCekHarga($limit,$start,$search='',$id_kategori,$sub_kategori,$idToko='7',$id_brand=''){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan","((ap_produk_price.harga - ap_produk_price.hpp)/ap_produk_price.harga *100) as margin","ap_produk_price.harga","ap_produk_price.hpp","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","ap_produk.status","supplier.supplier","ap_produk.id_supplier","SUM(stok_store_kartu.qty) as stok"));
		$this->db->from("ap_produk");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = ap_produk.id_produk and ap_produk_price.id_toko='$idToko'","left");
		$this->db->join("stok_store_kartu","stok_store_kartu.id_produk = ap_produk.id_produk and stok_store_kartu.id_store='$idToko'","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		$this->db->join("supplier","supplier.id_supplier = ap_produk.id_supplier","left");
		
		if(!empty($search)){
			$this->db->like("ap_produk.nama_produk",$search);
			$this->db->or_where("ap_produk.id_produk",$search);
			$this->db->or_like("ap_produk.qr_code",$search);
		}
		if (!empty($id_kategori)){
			$this->db->where("ap_produk.id_kategori",$id_kategori);
		}
		if (!empty($sub_kategori)){
			$this->db->where("ap_produk.id_subkategori",$sub_kategori);
		}
		
		if (empty($search)){
			$this->db->where("ap_produk.status",1);
            //$this->db->or_where("ap_produk.status",0);
		}
            
        


		$this->db->group_by("ap_produk.id_produk");
		
		$this->db->order_by("ap_produk.nama_produk");
		$this->db->limit($limit,$start);
		return $this->db->get();	
	}

	function exportTemplateKategori(){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","ap_produk.hpp","ap_produk.id_kategori","ap_produk.id_subkategori","ap_produk.id_subkategori_2"));
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		$this->db->where("ap_produk.status",1);
		//$this->db->or_where("ap_produk.status",0);
		$this->db->order_by("ap_kategori.kategori");
		return $this->db->get()->result();
	}
	function exportTemplateSupplier($idStore){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","brand.brand","supplier.supplier","supplier.id_supplier"));
		$this->db->from("ap_produk");
		$this->db->join("ap_produk_supplier","ap_produk_supplier.id_produk = ap_produk.id_produk and id_toko='$idStore'","left");
		$this->db->join("supplier","supplier.id_supplier = ap_produk_supplier.id_supplier","left");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");
		$this->db->where("ap_produk.status",1);
		$this->db->order_by("supplier.supplier");
		return $this->db->get()->result();
	}

	function exportTemplateMinMax($idToko){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","ap_produk.hpp","ap_produk.id_kategori","ap_produk.id_subkategori","ap_produk.id_subkategori_2","stok_store.min","stok_store.max"));
		$this->db->from("ap_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("stok_store","stok_store.id_produk = ap_produk.id_produk","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		$this->db->where("ap_produk.status",1);
		$this->db->where("stok_store.id_store",$idToko);
		//$this->db->or_where("ap_produk.status",0);
		$this->db->order_by("ap_kategori.kategori");
		return $this->db->get()->result();
	}

	function exportTemplateHargaJual($idToko,$idKategori,$subkategori,$subSubKategori,$idStand,$idBrand=''){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_kategori.kategori","ap_kategori_1.kategori_level_1","ap_kategori_2.kategori_3","ap_produk_price.harga","ap_produk_price.harga_member","ap_produk_price.id_toko","ap_stand.stand","ap_store.store"));
		$this->db->from("ap_produk_price");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_produk_price.id_produk");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left");
		$this->db->join("ap_kategori_1","ap_kategori_1.id = ap_produk.id_subkategori","left");
		$this->db->join("ap_kategori_2","ap_kategori_2.id = ap_produk.id_subkategori_2","left");
		$this->db->join("ap_stand","ap_stand.id_stand = ap_produk.tempat","left");
		$this->db->join("ap_store","ap_store.id_store = ap_produk_price.id_toko","left");

		if(!empty($idKategori)){
			$this->db->where("ap_produk.id_kategori",$idKategori);
		}

		if(!empty($subkategori)){
			$this->db->where("ap_produk.id_subkategori",$subkategori);
		}

		if(!empty($subSubKategori)){
			$this->db->where("ap_produk.id_subkategori_2",$subSubKategori);
		}

		if(!empty($idStand)){
			$this->db->where("ap_produk.tempat",$idStand);
		}
		if(!empty($idBrand)){
			$this->db->where("ap_produk.id_brand",$idBrand);
		}

		$this->db->where("ap_produk_price.id_toko",$idToko);
		$this->db->where("ap_produk.status",1);
		//$this->db->or_where("ap_produk.status",0);
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get()->result();
	}

	function insertProduk($data_upload){
		$this->db->insert("ap_produk", $data_upload);
	}

	function insertHargaJual($dataHarga){
		$this->db->insert_batch("ap_produk_price",$dataHarga);
	}
    
    function insertStock($dataStock){
		$this->db->insert_batch("stok_store",$dataStock);
	}
    function insertSupplier($dataSupplier){
		$this->db->insert("ap_produk_supplier",$dataSupplier);
	}

	function updateProduk($id_produk,$data_upload){
		$this->db->where("id_produk",$id_produk);
		$this->db->update("ap_produk", $data_upload);
	}
	function updateSkuPO($id_produk,$data_upload){
		$this->db->where("sku",$id_produk);
		$this->db->update("purchase_item", $data_upload);
	}
	function updateSkuReceived($id_produk,$data_upload){
		$this->db->where("sku",$id_produk);
		$this->db->update("receive_item", $data_upload);
	}
	function updateSkuRetur($id_produk,$data_upload){
		$this->db->where("sku",$id_produk);
		$this->db->update("retur_item", $data_upload);
	}
	function updateSupplier($id_produk,$idStore,$data_upload){
		$this->db->where("id_toko",$idStore);
		$this->db->where("id_produk",$id_produk);
		$this->db->update("ap_produk_supplier", $data_upload);
	}
	function updateSkuInvoice($id_produk,$data_upload){
		$this->db->where("id_produk",$id_produk);
		$this->db->update("ap_invoice_item", $data_upload);
	}

	function updateHargaPertoko($idStore,$id_produk,$dataHarga){
		$this->db->where("id_toko",$idStore);
		$this->db->where("id_produk",$id_produk);	
		$this->db->update("ap_produk_price",$dataHarga);
	}
	function updateStokPertoko($idStore,$id_produk,$dataHarga){
		$this->db->where("id_store",$idStore);
		$this->db->where("id_produk",$id_produk);	
		$this->db->update("stok_store",$dataHarga);
	}

	function insertNewHargaPertoko($dataHarga){
		$this->db->insert("ap_produk_price",$dataHarga);
	}

	function hapusProduk($sku,$updateDataProduk){
		// $this->db->where("id_produk",$sku);
		// $this->db->update("ap_produk",$updateDataProduk);
        
        $this->db->where("id_produk", $sku);
        $this->db->delete("ap_produk");
	}
}
