<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// 
Class ModelLaporan extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	function topBrand($start,$end,$toko,$kategori,$subkategori,$subkategori2){
        $this->db->select(array("ap_invoice_number.no_resi","SUM(ap_invoice_item.hpp*ap_invoice_item.qty) as t_harga_beli","SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as t_harga_jual","SUM(ap_invoice_item.qty) as qty_terjual","SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.diskon,0)) as diskon","SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.disc_supplier,0)) as disc_supplier","brand.brand"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");
		
		
        
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		
		if(!empty($toko)){
			$this->db->where("ap_invoice_number.id_toko",$toko);
		}

		if(!empty($kategori)){
			$this->db->where("ap_produk.id_kategori",$kategori);
		}

		if(!empty($subkategori)){
			$this->db->where("id_subkategori",$subkategori);
		}

		if(!empty($subkategori2)){
			$this->db->where("id_subkategori_2",$subkategori2);
		}

		$this->db->group_by("brand.id_brand");
		$this->db->order_by("t_harga_jual","DESC");
		$this->db->limit(50);
		$query = $this->db->get();
		return $query;
	}
	function penjualanPerkriteriaProdukPisahINV($start,$end,$toko,$tempat,$customer,$kategori,$subkategori,$subkategori2,$id_supplier='',$id_brand=''){
		
        $this->db->select(array("ap_invoice_number.no_resi","ap_invoice_number.no_invoice","ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan","ap_invoice_item.hpp as harga_beli","ap_invoice_item.harga_jual as harga_jual","(ap_invoice_item.hpp*ap_invoice_item.qty) as t_harga_beli","(ap_invoice_item.harga_jual*ap_invoice_item.qty) as t_harga_jual","(ap_invoice_item.qty) as qty_terjual","(IF(ap_invoice_item.qty>0,ap_invoice_item.diskon,0)) as diskon","(IF(ap_invoice_item.qty>0,ap_invoice_item.disc_supplier,0)) as disc_supplier","brand.brand"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		//$this->db->join("bahan_baku","bahan_baku.sku = ap_produk.id_produk","left");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
		//$this->db->join("ap_produk_supplier","ap_produk_supplier.id_produk=ap_produk.id_produk and ap_produk_supplier.id_toko=ap_invoice_number.id_toko");
        //$this->db->join("supplier","supplier.id_supplier = ap_produk_supplier.id_supplier");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");
		//}
        
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_number.id_toko",$toko);
		

		if(!empty($tempat)){
			$this->db->where("ap_produk.tempat",$tempat);
		}

		if(!empty($customer)){
			$this->db->where("ap_invoice_number.id_customer",$customer);
		}

		if(!empty($kategori)){
			$this->db->where("ap_produk.id_kategori",$kategori);
		}

		if(!empty($subkategori)){
			$this->db->where("id_subkategori",$subkategori);
		}

		if(!empty($subkategori2)){
			$this->db->where("id_subkategori_2",$subkategori2);
		}
        
        if(!empty($id_supplier)){
			//$this->db->where("ap_produk.id_supplier",$id_supplier);
		}
		if (!empty($id_brand) && is_array($id_brand)) {
			$brands_to_filter = array_filter($id_brand);
			if (!empty($brands_to_filter)) {
				$this->db->where_in('ap_produk.id_brand', $brands_to_filter);
			}
		} elseif (!empty($id_brand)) {
			$this->db->where('ap_produk.id_brand', $id_brand);
		}

		//$this->db->group_by("ap_invoice_number.no_invoice","ap_invoice_number.id_produk");
		$this->db->order_by("ap_invoice_number.no_invoice","ASC");
		$query = $this->db->get();
		return $query;
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

	function terjualToko($idProduk,$toko,$start,$end){
		$this->db->select("SUM(ap_invoice_item.qty) as terjual");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
        
		$this->db->where("DATE(ap_invoice_item.tanggal) BETWEEN '$start' AND '$end'");
		$this->db->where("ap_invoice_item.id_produk",$idProduk);
		$this->db->where("ap_invoice_number.id_toko",$toko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->terjual;
		}
		
	}
	function penjualanPerkasirDetail($start,$end,$idKasir,$idStore='',$idPayment='',$isAdmin=''){
		$this->db->select(array("ap_invoice_number.keterangan","ap_invoice_number.tanggal","ap_invoice_number.voucher","ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.no_invoice","ap_produk.nama_produk","ap_produk.id_produk","ap_produk.satuan","brand.brand","ap_invoice_item.harga_jual","ap_invoice_item.diskon","ap_invoice_item.disc_supplier","ap_invoice_item.qty"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
		$this->db->where("ap_invoice_number.id_pic",$idKasir);
        
        if ($isAdmin!=1){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($idPayment)) $this->db->where("ap_invoice_number.tipe_bayar",$idPayment);

		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		//$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get();
	}
	function list_payment(){
		$this->db->select(array("id","payment_type"));
		$this->db->from("ap_payment_type");
		return $this->db->get()->result();
	}
	function list_brand($idUser=''){
		$this->db->select("brand");
		$this->db->from("users");

        $this->db->where("id",$idUser);
        
		
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->brand;
		}
	}
	function akumulasiPenjualanProdukBrand($brand='',$start,$end,$idStore='',$idUser='',$isAdmin='0'){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","stok_store.stok","ap_invoice_item.harga_jual as harga_jual","ap_invoice_item.hpp as hpp","SUM(ap_invoice_item.qty) as qty_terjual"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		//$this->db->join("bahan_baku","bahan_baku.sku = ap_produk.id_produk","left");
		$this->db->join("stok_store","stok_store.id_produk = ap_produk.id_produk and stok_store.id_store='$idStore'");
        //if ($isAdmin!=1){
            $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        //}
		$brand = substr($brand,1,-1);
		$this->db->where("ap_produk.id_brand in ($brand)");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->order_by("qty_terjual","DESC");
		$this->db->group_by("ap_invoice_item.id_produk");
		$query = $this->db->get();
		return $query;
	}
	function penjualanPerkriteriaProdukBrand($brand='',$start,$end,$toko,$tempat,$customer,$kategori,$subkategori,$subkategori2,$id_supplier='',$id_brand=''){
		
        $this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan","ap_invoice_item.hpp as harga_beli","ap_invoice_item.harga_jual as harga_jual","SUM(ap_invoice_item.hpp*ap_invoice_item.qty) as t_harga_beli","SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as t_harga_jual","SUM(ap_invoice_item.qty) as qty_terjual","SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.diskon,0)) as diskon","SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.disc_supplier,0)) as disc_supplier","brand.brand","stok_store.stok as stok","(stok_store.last_received) as tanggal_po","stok_store.last_receives","stok_store.last_sales"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		//$this->db->join("bahan_baku","bahan_baku.sku = ap_produk.id_produk","left");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
		//$this->db->join("ap_produk_supplier","ap_produk_supplier.id_produk=ap_produk.id_produk and ap_produk_supplier.id_toko=ap_invoice_number.id_toko");
        //$this->db->join("supplier","supplier.id_supplier = ap_produk_supplier.id_supplier");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");
        $this->db->join("stok_store","stok_store.id_produk = ap_produk.id_produk and id_store='$toko'");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		
		if(!empty($toko)){
			$this->db->where("ap_invoice_number.id_toko",$toko);
		}

		if(!empty($tempat)){
			$this->db->where("ap_produk.tempat",$tempat);
		}

		if(!empty($customer)){
			$this->db->where("ap_invoice_number.id_customer",$customer);
		}

		if(!empty($kategori)){
			$this->db->where("ap_produk.id_kategori",$kategori);
		}

		if(!empty($subkategori)){
			$this->db->where("id_subkategori",$subkategori);
		}

		if(!empty($subkategori2)){
			$this->db->where("id_subkategori_2",$subkategori2);
		}
        
        if(!empty($id_supplier)){
			//$this->db->where("ap_produk.id_supplier",$id_supplier);
		}
		if(!empty($id_brand)){
			$this->db->where("ap_produk.id_brand",$id_brand);
		}

		$brand = substr($brand,1,-1);
		$this->db->where("ap_produk.id_brand in ($brand)");

		$this->db->group_by("stok_store.id_produk");
		$this->db->order_by("qty_terjual","DESC");
		$query = $this->db->get();
		return $query;
	}
    
    function totalInvToko($idStore=''){

		$this->db->select(array("SUM(stok_store.stok*stok_store.hpp) as nilai"));
		$this->db->from("ap_produk");
		$this->db->join("stok_store","stok_store.id_produk = ap_produk.id_produk");
		 
        $this->db->where("ap_produk.status",1);
        $this->db->where("stok_store.id_store",$idStore);
		
		
		return $this->db->get()->result();
	}
	function hutangJatuhTempo($idToko=''){
        
    //$whr =($idUser>1 && $idUser!='36')? "AND purchase_order.id_pic='$idUser'":'';
    $whr ="AND purchase_order.id_toko='$idToko'";
        
		$query = "SELECT supplier.supplier,hutang.no_tagihan,purchase_order.jatuh_tempo,total_hutang,total_retur,purchase_order.tanggal_po, users.first_name, total_terbayar,receive_order.diskon
				  FROM hutang
				  LEFT JOIN purchase_order ON purchase_order.no_po = hutang.no_tagihan
				  LEFT JOIN retur ON retur.no_po = hutang.no_tagihan
				  LEFT JOIN supplier ON supplier.id_supplier = purchase_order.id_supplier
				  LEFT JOIN receive_order ON receive_order.no_po = hutang.no_tagihan
				  LEFT JOIN users ON users.id = purchase_order.id_pic
				  LEFT JOIN (SELECT SUM(receive_item.qty*receive_item.price) as total_hutang,receive_item.no_receive
							 FROM receive_item
							 LEFT JOIN receive_order ON receive_order.no_receive = receive_item.no_receive
							 GROUP BY receive_order.no_po) as receiveItemJoin ON receiveItemJoin.no_receive = receive_order.no_receive
				  LEFT JOIN (SELECT SUM(retur_item.harga*retur_item.qty) as total_retur,retur_item.no_retur
				  			 FROM retur_item
				  			 LEFT JOIN retur ON retur.no_retur = retur_item.no_retur
				  			 GROUP BY retur.no_po) as returJoin ON returJoin.no_retur = retur.no_retur
				  LEFT JOIN (SELECT SUM(hutang_order.pembayaran) as total_terbayar,hutang_order.no_po
				  			FROM hutang_order
				  			LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_po
				  			GROUP BY hutang_order.no_po) as hutangOrderJoin ON hutangOrderJoin.no_po = hutang.no_tagihan
				  WHERE ( hutang.status_hutang = 0 OR hutang.status_hutang = 1 ) AND purchase_order.jatuh_tempo <= current_date()
                  $whr
				  GROUP BY hutang.no_tagihan 
				  ORDER BY purchase_order.jatuh_tempo ASC";
		return $this->db->query($query);
	}

	function hutangJatuhTempoFilter($supplier,$idToko=''){
        //$whr =($idUser>1 && $idUser!='36')? "AND purchase_order.id_pic='$idUser'":'';
        
        $whr ="AND purchase_order.id_toko='$idToko'";
		$query = "SELECT supplier.supplier,hutang.no_tagihan,purchase_order.jatuh_tempo,total_hutang,total_retur,purchase_order.tanggal_po, users.first_name, total_terbayar,receive_order.diskon
				  FROM hutang
				  LEFT JOIN purchase_order ON purchase_order.no_po = hutang.no_tagihan
				  LEFT JOIN retur ON retur.no_po = hutang.no_tagihan
				  LEFT JOIN supplier ON supplier.id_supplier = purchase_order.id_supplier
				  LEFT JOIN receive_order ON receive_order.no_po = hutang.no_tagihan
				  LEFT JOIN users ON users.id = purchase_order.id_pic
				  LEFT JOIN (SELECT SUM(receive_item.qty*receive_item.price) as total_hutang,receive_item.no_receive
							 FROM receive_item
							 LEFT JOIN receive_order ON receive_order.no_receive = receive_item.no_receive
							 GROUP BY receive_order.no_po) as receiveItemJoin ON receiveItemJoin.no_receive = receive_order.no_receive
				  LEFT JOIN (SELECT SUM(retur_item.harga*retur_item.qty) as total_retur,retur_item.no_retur
				  			 FROM retur_item
				  			 LEFT JOIN retur ON retur.no_retur = retur_item.no_retur
				  			 GROUP BY retur.no_po) as returJoin ON returJoin.no_retur = retur.no_retur
				  LEFT JOIN (SELECT SUM(hutang_order.pembayaran) as total_terbayar,hutang_order.no_po
				  			FROM hutang_order
				  			LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_po
				  			GROUP BY hutang_order.no_po) as hutangOrderJoin ON hutangOrderJoin.no_po = hutang.no_tagihan
				  WHERE ( hutang.status_hutang = 0 OR hutang.status_hutang = 1 ) AND purchase_order.jatuh_tempo <= current_date() AND purchase_order.id_supplier = '$supplier'
                  $whr
				  GROUP BY hutang.no_tagihan 
				  ORDER BY supplier.supplier DESC, purchase_order.jatuh_tempo ASC";
		return $this->db->query($query);
	}

	function hutang_ditagih($supplier='',$idToko=''){
		$query = "SELECT supplier.supplier,hutang.no_tagihan,purchase_order.jatuh_tempo,total_hutang,total_retur,purchase_order.tanggal_po, users.first_name, total_terbayar, receive_order.diskon,purchase_order.keterangan
				  FROM hutang
				  LEFT JOIN purchase_order ON purchase_order.no_po = hutang.no_tagihan
				  LEFT JOIN retur ON retur.no_po = hutang.no_tagihan
				  LEFT JOIN supplier ON supplier.id_supplier = purchase_order.id_supplier
				  LEFT JOIN receive_order ON receive_order.no_po = hutang.no_tagihan
				  LEFT JOIN users ON users.id = purchase_order.id_pic
				  LEFT JOIN (SELECT SUM(receive_item.qty*receive_item.price) as total_hutang,receive_item.no_receive
							 FROM receive_item
							 LEFT JOIN receive_order ON receive_order.no_receive = receive_item.no_receive
							 GROUP BY receive_order.no_po) as receiveItemJoin ON receiveItemJoin.no_receive = receive_order.no_receive
				  LEFT JOIN (SELECT SUM(retur_item.harga*retur_item.qty) as total_retur,retur_item.no_retur
				  			 FROM retur_item
				  			 LEFT JOIN retur ON retur.no_retur = retur_item.no_retur
				  			 GROUP BY retur.no_po) as returJoin ON returJoin.no_retur = retur.no_retur
				  LEFT JOIN (SELECT SUM(hutang_order.pembayaran) as total_terbayar,hutang_order.no_po
				  			FROM hutang_order
				  			LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_po
				  			GROUP BY hutang_order.no_po) as hutangOrderJoin ON hutangOrderJoin.no_po = hutang.no_tagihan
				  WHERE (hutang.status_hutang = 0 OR hutang.status_hutang = 1)";
			
			if(!empty($supplier)){
				$query .= "AND purchase_order.id_supplier='$supplier'";	  
			}
            //if($idUser>1 && $idUser!=36){
				$query .= "AND purchase_order.id_toko='$idToko'";	  
			//}

			$query .= "GROUP BY hutang.no_tagihan 
				  ORDER BY supplier.supplier DESC, purchase_order.jatuh_tempo ASC";
		return $this->db->query($query);
	}

	function hutang_ditagih_filter($supplier,$tanggalPO,$jatuhTempo,$idToko=''){
		$query = "SELECT supplier.supplier,hutang.no_tagihan,purchase_order.jatuh_tempo,total_hutang,total_retur,purchase_order.tanggal_po, users.first_name, total_terbayar, receive_order.diskon
				  FROM hutang
				  LEFT JOIN purchase_order ON purchase_order.no_po = hutang.no_tagihan
				  LEFT JOIN retur ON retur.no_po = hutang.no_tagihan
				  LEFT JOIN supplier ON supplier.id_supplier = purchase_order.id_supplier
				  LEFT JOIN receive_order ON receive_order.no_po = hutang.no_tagihan
				  LEFT JOIN users ON users.id = purchase_order.id_pic
				  LEFT JOIN (SELECT SUM(receive_item.qty*receive_item.price) as total_hutang,receive_item.no_receive
							 FROM receive_item
							 LEFT JOIN receive_order ON receive_order.no_receive = receive_item.no_receive
							 GROUP BY receive_order.no_po) as receiveItemJoin ON receiveItemJoin.no_receive = receive_order.no_receive
				  LEFT JOIN (SELECT SUM(retur_item.harga*retur_item.qty) as total_retur,retur_item.no_retur
				  			 FROM retur_item
				  			 LEFT JOIN retur ON retur.no_retur = retur_item.no_retur
				  			 GROUP BY retur.no_po) as returJoin ON returJoin.no_retur = retur.no_retur
				  LEFT JOIN (SELECT SUM(hutang_order.pembayaran) as total_terbayar,hutang_order.no_po
				  			FROM hutang_order
				  			LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_po
				  			GROUP BY hutang_order.no_po) as hutangOrderJoin ON hutangOrderJoin.no_po = hutang.no_tagihan
				  WHERE ( hutang.status_hutang = 0 OR hutang.status_hutang = 1 )"; 
		
		if(!empty($supplier)){
			$query .= "AND purchase_order.id_supplier='".$supplier."'";
		}

		if(!empty($tanggalPO)){
			$query .= "AND purchase_order.tanggal_po like '".substr($tanggalPO,0,7)."%'";
		}

		if(!empty($jatuhTempo)){
			$query .= "AND purchase_order.jatuh_tempo='".$jatuhTempo."'";
		}
        //if($idUser>1){
            $query .= "AND purchase_order.id_toko='$idToko'";	  
        //}

		$query .= "GROUP BY hutang.no_tagihan 
				  ORDER BY supplier.supplier DESC, purchase_order.jatuh_tempo ASC";
		return $this->db->query($query);
	}	

	function akumulasiPenjualan($start,$end){
		$this->db->select(array("ap_store.store","ap_invoice_number.diskon_otomatis","ap_invoice_number.tanggal","ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->join("ap_store","ap_store.id_store = ap_invoice_number.id_toko","left");
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get();
	}

	function laporanPenjualanPerkriteria($start, $end,$idKasir,$toko,$idCustomer,$typeBayar,$subAccount){
		$this->db->select(array("ap_store.store","ap_invoice_number.diskon_otomatis","ap_invoice_number.tanggal","ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total","ap_invoice_number.nama_penerima"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->join("ap_store","ap_store.id_store = ap_invoice_number.id_toko","left");
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");

		if(!empty($idKasir)){
			$this->db->where("ap_invoice_number.id_pic",$idKasir);
		}

		if(!empty($toko)){
			$this->db->where("ap_invoice_number.id_toko",$toko);
		}

		if(!empty($idCustomer)){
			$this->db->where("ap_invoice_number.id_customer",$idCustomer);
		}

		if(!empty($typeBayar)){
			$this->db->where("ap_invoice_number.tipe_bayar",$typeBayar);
		}

		if(!empty($subAccount)){
			$this->db->where("ap_invoice_number.sub_account",$subAccount);
		}

		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get();
	}

	function penjualan_percustomer($start,$end,$id_customer,$idStore='',$idUser='',$isAdmin='0'){
		$this->db->select(array("ap_store.store","ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.tanggal","ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value+ap_invoice_number.diskon_otomatis)) as grand_total","ap_invoice_number.diskon_otomatis"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->join("ap_store","ap_store.id_store = ap_invoice_number.id_toko","left");
		$this->db->where("ap_invoice_number.id_customer",$id_customer);
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
        
        if($isAdmin!=1){
            //$this->db->where("ap_store.id_store",$idStore);
        }
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get();
	}

	function list_kasir(){
		$this->db->select(array("users.id as id_user","users.first_name as nama_user"));
		$this->db->from("users");
		return $this->db->get()->result();
	}

	function penjualanPerbarang($start,$end,$id_produk,$idToko){
		$this->db->select(array("ap_invoice_item.no_invoice","ap_invoice_item.diskon","ap_invoice_item.tanggal","ap_invoice_item.qty","ap_invoice_item.harga_jual","(ap_invoice_item.qty*ap_invoice_item.harga_jual) as total"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice");
		$this->db->where("ap_invoice_number.id_toko",$idToko);
		$this->db->where("id_produk",$id_produk);
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		return $this->db->get();
	}

	function salesPerkategori($start,$end,$idKategori,$idStore='',$idUser=''){
		$this->db->select(array("SUM(ap_invoice_item.diskon) as diskon","ap_produk.id_produk","ap_produk.nama_produk","ap_invoice_item.hpp","ap_invoice_item.harga_jual","SUM(ap_invoice_item.qty) as qty","SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as total","ap_invoice_item.tanggal"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
        $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice");
        $this->db->join("ap_store","ap_store.id_store = ap_invoice_number.id_toko","left");
		
        
        //if($idUser>1 && $idUser!=36 && $idUser!=22 && $idUser!=51){
            $this->db->where("ap_store.id_store",$idStore);
        //}
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$idKategori);
		$this->db->order_by("ap_produk.nama_produk");
		$this->db->group_by("ap_produk.id_produk");
		return $this->db->get();
	}

	function penjualanPertoko($start,$end,$idToko){
		$this->db->select(array("ap_invoice_number.diskon_otomatis","ap_invoice_number.tanggal","ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.hpp","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
		$this->db->where("ap_invoice_number.id_toko",$idToko);
		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get();
	}

	function storeName($idToko){
		$this->db->select("store");
		$this->db->from("ap_store");
		$this->db->where("id_store",$idToko);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->store;
		}
	}

	function penjualanPerkasir($start,$end,$idKasir,$idStore='',$idUser='',$isAdmin='0',$idPayment=''){
		$this->db->select(array("ap_invoice_number.diskon_otomatis","ap_invoice_number.tanggal", "ap_invoice_number.surcharge", "ap_invoice_number.voucher","ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.no_invoice","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.diskon_otomatis+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
		$this->db->where("ap_invoice_number.id_pic",$idKasir);
        
        if ($isAdmin!=1){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($idPayment)) $this->db->where("ap_invoice_number.tipe_bayar",$idPayment);

		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get();
	}

	function akumulasiPenjualanProduk($start,$end,$idStore='',$idUser='',$isAdmin='0'){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","stok_store.stok","ap_invoice_item.harga_jual as harga_jual","ap_invoice_item.hpp as hpp","SUM(ap_invoice_item.qty) as qty_terjual"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		//$this->db->join("bahan_baku","bahan_baku.sku = ap_produk.id_produk","left");
		$this->db->join("stok_store","stok_store.id_produk = ap_produk.id_produk and stok_store.id_store='$idStore'");
        //if ($isAdmin!=1){
            $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        //}
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->order_by("qty_terjual","DESC");
		$this->db->group_by("ap_invoice_item.id_produk");
		$query = $this->db->get();
		return $query;
	}
	// akumulasiPenjualanSupplier
	function akumulasiPenjualanSupplier($start,$end,$idStore='',$idUser='',$isAdmin='0'){
		$this->db->select(array("brand.id_brand","brand.brand","stok_store.stok","(stok_store.stok*ap_invoice_item.hpp) as stokhpp","(stok_store.stok*ap_invoice_item.harga_jual) as stokharga","SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as harga_jual","SUM(ap_invoice_item.diskon-ap_invoice_item.disc_supplier) as disc_toko","SUM(ap_invoice_item.disc_supplier) as disc_supplier","SUM((ap_invoice_item.hpp*ap_invoice_item.qty)-ap_invoice_item.disc_supplier) as hpp","SUM(ap_invoice_item.qty) as qty_terjual","SUM(ap_invoice_item.diskon) as diskon"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		// if($idStore==6 || $idStore=7){
		// 	$this->db->join("stok_store","stok_store.id_produk = ap_invoice_item.id_produk and stok_store.id_store in ('6','7')","left");
		// }else{
			$this->db->join("stok_store","stok_store.id_produk = ap_invoice_item.id_produk and stok_store.id_store='$idStore'","left");
		// }
		
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");
        //if ($isAdmin!=1){
            $this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice","left");
			// if($idStore==6 || $idStore=7){
            	// $this->db->where_in("ap_invoice_number.id_toko",array('6','7'));
			// }else{
				$this->db->where("ap_invoice_number.id_toko",$idStore);
			// }
        //}
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->order_by("SUM((ap_invoice_item.hpp*ap_invoice_item.qty)-ap_invoice_item.disc_supplier)","DESC");
		$this->db->group_by("brand.id_brand");
		$query = $this->db->get();
		return $query;
	}

	function akumulasiPembelianBrand($start,$end,$toko,$idStore='',$idUser='',$isAdmin='0'){
		$this->db->select(array("brand.id_brand","brand.brand","SUM(ap_produk_price.harga*receive_item.qty) as hargajual","SUM(receive_item.price*receive_item.qty) as harga","SUM(receive_item.qty) as qty"));
		$this->db->from("receive_item");
		$this->db->join("ap_produk","ap_produk.id_produk = receive_item.sku");
		$this->db->join("ap_produk_price","ap_produk_price.id_produk = receive_item.sku and id_toko='$toko'");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand");
        $this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive","left");
        $this->db->where("receive_order.diterimaDi",$toko);
		$this->db->where("receive_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->order_by("SUM(receive_item.price*receive_item.qty)","DESC");
		$this->db->group_by("brand.id_brand");
		$query = $this->db->get();
		return $query;
	}

	function penjualanPerkriteriaProduk($start,$end,$toko,$tempat,$customer,$kategori,$subkategori,$subkategori2,$id_supplier='',$id_brand='',$timeStart='',$timeEnd=''){
		//,"(stok_store.last_received) as tanggal_po","stok_store.last_receives","stok_store.last_sales"
        $this->db->select(array("ap_invoice_number.no_resi","ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan","ap_invoice_item.hpp as harga_beli","ap_invoice_item.harga_jual as harga_jual","SUM(ap_invoice_item.hpp*ap_invoice_item.qty) as t_harga_beli","SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as t_harga_jual","SUM(ap_invoice_item.qty) as qty_terjual","SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.diskon,0)) as diskon","SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.disc_supplier,0)) as disc_supplier","brand.brand",
		"(SELECT SUM(ssk.qty) FROM stok_store_kartu ssk 
      WHERE ssk.id_produk = ap_produk.id_produk 
        AND ssk.id_store = '$toko') AS stok"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		//$this->db->join("bahan_baku","bahan_baku.sku = ap_produk.id_produk","left");
		$this->db->join("ap_invoice_number","ap_invoice_number.no_invoice = ap_invoice_item.no_invoice");
		//$this->db->join("ap_produk_supplier","ap_produk_supplier.id_produk=ap_produk.id_produk and ap_produk_supplier.id_toko=ap_invoice_number.id_toko");
        //$this->db->join("supplier","supplier.id_supplier = ap_produk_supplier.id_supplier");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");
		
		//$this->db->join("stok_store","stok_store.id_produk = ap_produk.id_produk and stok_store.id_store='$toko'");
		//}
        
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start $timeStart' AND '$end $timeEnd'");
		$this->db->where("ap_invoice_number.id_toko",$toko);
		
		// if(!empty($toko)){
		// 	$this->db->where("stok_store.id_store",$toko);
		// }

		if(!empty($tempat)){
			$this->db->where("ap_produk.tempat",$tempat);
		}

		if(!empty($customer)){
			$this->db->where("ap_invoice_number.id_customer",$customer);
		}

		if(!empty($kategori)){
			$this->db->where("ap_produk.id_kategori",$kategori);
		}

		if(!empty($subkategori)){
			$this->db->where("id_subkategori",$subkategori);
		}

		if(!empty($subkategori2)){
			$this->db->where("id_subkategori_2",$subkategori2);
		}
        
        if(!empty($id_supplier)){
			//$this->db->where("ap_produk.id_supplier",$id_supplier);
		}
		if (!empty($id_brand) && is_array($id_brand)) {
			// Pastikan nilai 'Semua' (nilai kosong) diabaikan jika pengguna memilih opsi lain
			// Jika elemen pertama adalah string kosong (opsi --Semua--), Anda mungkin perlu menghapusnya
			$brands_to_filter = array_filter($id_brand);
			
			if (!empty($brands_to_filter)) {
				// Jika ada Brand ID yang valid untuk difilter, gunakan where_in
				$this->db->where_in('ap_produk.id_brand', $brands_to_filter);
			}
		}

		$this->db->group_by("ap_produk.id_produk");
		$this->db->order_by("qty_terjual","DESC");
		$query = $this->db->get();
		return $query;
	}
	function penjualanPerkriteriaProdukTidakTerjual($start,$end,$toko,$tempat,$customer,$kategori,$subkategori,$subkategori2,$id_supplier='',$id_brand=''){
		
        $this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan","stok_store.hpp as harga_beli","stok_store.harga as harga_jual","CONCAT('0') as t_harga_beli","CONCAT('0') as t_harga_jual","CONCAT('0') as qty_terjual","CONCAT('0') as diskon","CONCAT('0') as disc_supplier","brand.brand",
		"(SELECT SUM(ssk.qty) FROM stok_store_kartu ssk 
      WHERE ssk.id_produk = ap_produk.id_produk 
        AND ssk.id_store = '$toko') AS stok"
		,"(stok_store.last_received) as tanggal_po","stok_store.last_receives","stok_store.last_sales"));
		$this->db->from("ap_produk");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","left");
        $this->db->join("stok_store","stok_store.id_produk = ap_produk.id_produk","left");
		
		$this->db->where(" not exists(SELECT ap_invoice_item.id_produk from ap_invoice_item join ap_invoice_number on ap_invoice_number.no_invoice=ap_invoice_item.no_invoice where ap_invoice_number.id_toko='$toko' and `ap_invoice_item`.`tanggal` BETWEEN '$start' AND '$end' and ap_invoice_item.id_produk=ap_produk.id_produk)");
		//}
		
		$this->db->where("ap_produk.status",1);
		
		if(!empty($toko)){
			$this->db->where("stok_store.id_store",$toko);
		}

		if(!empty($tempat)){
			$this->db->where("ap_produk.tempat",$tempat);
		}


		if(!empty($kategori)){
			$this->db->where("ap_produk.id_kategori",$kategori);
		}

		if(!empty($subkategori)){
			$this->db->where("id_subkategori",$subkategori);
		}

		if(!empty($subkategori2)){
			$this->db->where("id_subkategori_2",$subkategori2);
		}
        
		if (!empty($id_brand) && is_array($id_brand)) {
			// Pastikan nilai 'Semua' (nilai kosong) diabaikan jika pengguna memilih opsi lain
			// Jika elemen pertama adalah string kosong (opsi --Semua--), Anda mungkin perlu menghapusnya
			$brands_to_filter = array_filter($id_brand);
			
			if (!empty($brands_to_filter)) {
				// Jika ada Brand ID yang valid untuk difilter, gunakan where_in
				$this->db->where_in('ap_produk.id_brand', $brands_to_filter);
			}
		}

		$this->db->group_by("stok_store.id_produk");
		$this->db->order_by("ap_produk.nama_produk");
		$query = $this->db->get();
		return $query;
	}

	function penjualanPertempat($start,$end,$idTempat){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.harga","SUM(ap_invoice_item.qty) as qtyTerjual","SUM(ap_invoice_item.diskon) as diskon"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->where("ap_produk.tempat",$idTempat);
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->group_by(array("ap_invoice_item.id_produk","ap_invoice_item.harga_jual"));
		return $this->db->get();
	}

	function namaTempat($id){
		$this->db->select("stand");
		$this->db->from("ap_stand");
		$this->db->where("id_stand",$id);
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->stand;
		}
	}

	function penjualan_perkategori_customer($start,$end,$kategori,$idToko){
		$this->db->select(array("ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.diskon_otomatis","ap_invoice_number.tanggal","ap_invoice_number.no_invoice","ap_customer.nama","ap_invoice_number.tipe_bayar","ap_invoice_number.total","ap_invoice_number.ongkir","ap_invoice_number.diskon","ap_invoice_number.diskon_free","ap_invoice_number.poin_value","((ap_invoice_number.total+ap_invoice_number.ongkir)-(ap_invoice_number.diskon+ap_invoice_number.diskon_free+ap_invoice_number.poin_value)) as grand_total"));
		$this->db->from("ap_invoice_number");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ap_customer_group","ap_customer_group.id_group = ap_customer.kategori","left");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.tanggal BETWEEN '$start 00:00:00' AND '$end 23:59:59'");
		$this->db->where("ap_customer_group.id_group",$kategori);

		if(!empty($idToko)){
			$this->db->where("ap_invoice_number.id_toko",$idToko);
		}

		$this->db->order_by("ap_invoice_number.tanggal","DESC");
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get();
	}

	function invoiceDetail($no_invoice){
		$this->db->select(array("ap_invoice_number.no_invoice","users.first_name as nama_user","ap_store.store","ap_customer.nama","ap_payment_type.payment_type","ap_payment_account.account","ap_invoice_number.tanggal","ap_invoice_number.keterangan","ap_invoice_number.diskon_free","ap_invoice_number.diskon","ap_invoice_number.ongkir","ap_invoice_number.total","ap_invoice_number.surcharge"));
		$this->db->from("ap_invoice_number");
		$this->db->join("users","users.id = ap_invoice_number.id_pic","left");
		$this->db->join("ap_customer","ap_customer.id_customer = ap_invoice_number.id_customer","left");
		$this->db->join("ap_store","ap_store.id_store = ap_invoice_number.id_toko","left");
		$this->db->join("ap_payment_type","ap_payment_type.id = ap_invoice_number.tipe_bayar","left");
		$this->db->join("ap_payment_account","ap_payment_account.id_payment_account = ap_invoice_number.sub_account","left");
		$this->db->where("ap_invoice_number.no_invoice",$no_invoice);
		$this->db->group_by("ap_invoice_number.no_invoice");
		return $this->db->get()->row();
	}

	function invoiceItem($no_invoice){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_invoice_item.qty","ap_invoice_item.harga_jual","ap_produk.id_produk","ap_produk.id_produk","ap_invoice_item.diskon"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->where("ap_invoice_item.no_invoice",$no_invoice);
		$this->db->group_by("ap_invoice_item.id_produk");
		return $this->db->get()->result();
	}

	function ajaxProduk($q){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk"));
		$this->db->from("ap_produk");
		$this->db->like("ap_produk.id_produk",$q);
		$this->db->or_like("ap_produk.nama_produk",$q);
		return $this->db->get();
	}

	function cekAksesPertoko($idStore,$idUser){
		$this->db->select('status');
		$this->db->from("user_access_store");
		$this->db->where("id_store",$idStore);
		$this->db->where("id_user",$idUser);
		$query = $this->db->get()->row();
		return $query->status;
		//return 1;
	}

	function rowDataMutasi($dateStart,$dateEnd,$idStore){
		$this->db->from("sp_no_bahan_keluar");
		$this->db->where("date(tanggal_keluar) BETWEEN '$dateStart' AND '$dateEnd'");
		if(!empty($idStore)){
			$this->db->where("store_tujuan",$idStore);
		}
		return $this->db->count_all_results();
	}

	function rowDataTransferStok($dateStart,$dateEnd,$transferFrom,$transferTo){
		$this->db->from("transferstoknumber");
		$this->db->where("date(tanggal) BETWEEN '$dateStart' AND '$dateEnd'");

		if(!empty($transferFrom)){
			$this->db->where("transferFrom",$transferFrom);
		}

		if(!empty($transferTo)){
			$this->db->where("transferTo",$transferTo);
		}

		return $this->db->count_all_results();
	}

	function rowDataTransferStokPeritem($dateStart,$dateEnd,$transferFrom,$transferTo,$idProduk){
		$this->db->from("transferstokitem");
		$this->db->join("transferstoknumber","transferstoknumber.noTransfer = transferstokitem.noTransfer");
		$this->db->join("ap_produk","ap_produk.id_produk = transferstokitem.idProduk");
		$this->db->where("date(transferstoknumber.tanggal) BETWEEN '$dateStart' AND '$dateEnd'");

		if(!empty($transferFrom)){
			$this->db->where("transferstoknumber.transferFrom",$transferFrom);
		}

		if(!empty($transferTo)){
			$this->db->where("transferstoknumber.transferTo",$transferTo);
		}

		if(!empty($idProduk)){
			$this->db->where("transferstokitem.idProduk",$idProduk);
		}

		return $this->db->count_all_results();
	}

	function viewMutasi($limit,$start,$search='',$dateStart='',$dateEnd='',$idStore=''){
		$this->db->select(array("sp_no_bahan_keluar.no_bahan_keluar","sp_no_bahan_keluar.tanggal_keluar","ap_store.store","sp_no_bahan_keluar.nama_penerima"));
		$this->db->from("sp_no_bahan_keluar");
		$this->db->join("ap_store","ap_store.id_store = sp_no_bahan_keluar.store_tujuan","left");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("date(sp_no_bahan_keluar.tanggal_keluar) BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($idStore)){
			$this->db->where("sp_no_bahan_keluar.store_tujuan",$idStore);
		}

		if(!empty($search)){
			$this->db->like("sp_no_bahan_keluar.no_bahan_keluar",$search);
		}

		$this->db->order_by("sp_no_bahan_keluar.tanggal_keluar","DESC");
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function viewTransferStok($limit,$start,$search='',$dateStart='',$dateEnd='',$transferFrom='',$transferTo=''){
		$this->db->select("transferstoknumber.*,users.first_name");
		$this->db->from("transferstoknumber");
		$this->db->join("users","users.id=transferstoknumber.id_penerima","left");
		
		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("date(transferstoknumber.tanggal) BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($transferFrom)){
			$this->db->where("transferstoknumber.transferFrom",$transferFrom);
		}

		if(!empty($transferTo)){
			$this->db->where("transferstoknumber.transferTo",$transferTo);
		}

		if(!empty($search)){
			$this->db->like("transferstoknumber.noTransfer",$search);
		}

		$this->db->order_by("transferstoknumber.tanggal","DESC");
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function viewTransferStokPeritem($limit,$start,$search='',$dateStart='',$dateEnd='',$transferFrom='',$transferTo='',$idProduk=''){
		$this->db->select(array("transferstoknumber.noTransfer","transferstoknumber.tanggal","ap_produk.id_produk","ap_produk.nama_produk","transferstoknumber.transferFrom","transferstoknumber.transferTo","transferstokitem.qty","transferstokitem.qty_rec"));
		$this->db->from("transferstokitem");
		$this->db->join("transferstoknumber","transferstoknumber.noTransfer = transferstokitem.noTransfer");
		$this->db->join("ap_produk","ap_produk.id_produk = transferstokitem.idProduk");
		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("date(transferstoknumber.tanggal) BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($transferFrom)){
			$this->db->where("transferstoknumber.transferFrom",$transferFrom);
		}

		if(!empty($transferTo)){
			$this->db->where("transferstoknumber.transferTo",$transferTo);
		}

		if(!empty($idProduk)){
			$this->db->where("transferstokitem.idProduk",$idProduk);
		}

		if(!empty($search)){
			$this->db->like("transferstoknumber.noTransfer",$search);
			$this->db->or_like("ap_produk.nama_produk",$search);
		}

		$this->db->order_by("transferstoknumber.tanggal","DESC");
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function totalQtyTransferStok($dateStart,$dateEnd,$transferFrom,$transferTo,$idProduk){
		$this->db->select("SUM(qty) as qty");
		$this->db->from("transferstokitem");
		$this->db->join("transferstoknumber","transferstoknumber.noTransfer = transferstokitem.noTransfer");
		$this->db->join("ap_produk","ap_produk.id_produk = transferstokitem.idProduk");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("date(transferstoknumber.tanggal) BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($transferFrom)){
			$this->db->where("transferstoknumber.transferFrom",$transferFrom);
		}

		if(!empty($transferTo)){
			$this->db->where("transferstoknumber.transferTo",$transferTo);
		}

		if(!empty($idProduk)){
			$this->db->where("transferstokitem.idProduk",$idProduk);
		}

		return $this->db->get()->row()->qty;
	}

	function rowDataMutasiPeritem($dateStart,$dateEnd,$idStore,$idProduk){
		$this->db->from("sp_bahan_keluar");
		$this->db->where("sp_bahan_keluar.tanggal_keluar BETWEEN '$dateStart' AND '$dateEnd'");
		$this->db->join("sp_no_bahan_keluar","sp_no_bahan_keluar.no_bahan_keluar = sp_bahan_keluar.no_bahan_keluar");
		if(!empty($idStore)){
			$this->db->where("sp_no_bahan_keluar.store_tujuan",$idStore);
		}
		if(!empty($idProduk)){
			$this->db->where("sp_bahan_keluar.sku",$idProduk);
		}
		return $this->db->count_all_results();
	}
	function viewMutasiPeritem($limit,$start,$search='',$dateStart='',$dateEnd='',$idStore='',$idProduk=''){
		$this->db->select(array("sp_bahan_keluar.no_bahan_keluar","sp_no_bahan_keluar.tanggal_keluar","ap_produk.id_produk","ap_produk.nama_produk","sp_bahan_keluar.qty","ap_store.store"));
		$this->db->from("sp_bahan_keluar");
		$this->db->join("sp_no_bahan_keluar","sp_no_bahan_keluar.no_bahan_keluar = sp_bahan_keluar.no_bahan_keluar");
		$this->db->join("ap_store","ap_store.id_store = sp_no_bahan_keluar.store_tujuan","left");
		$this->db->join("ap_produk","ap_produk.id_produk = sp_bahan_keluar.sku");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("sp_bahan_keluar.tanggal_keluar BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($idStore)){
			$this->db->where("sp_no_bahan_keluar.store_tujuan",$idStore);
		}

		if(!empty($idProduk)){
			$this->db->where("sp_bahan_keluar.sku",$idProduk);
		}

		if(!empty($search)){
			$this->db->like("sp_bahan_keluar.no_bahan_keluar",$search);
			$this->db->or_like("ap_produk.nama_produk",$search);
		}

		$this->db->order_by("sp_no_bahan_keluar.tanggal_keluar","DESC");
		$this->db->limit($limit,$start);
		return $this->db->get();
	}

	function totalQTYMutasi($dateStart,$dateEnd,$idStore,$idProduk){
		$this->db->select("SUM(sp_bahan_keluar.qty) as qty");
		$this->db->from("sp_bahan_keluar");
		$this->db->join("sp_no_bahan_keluar","sp_no_bahan_keluar.no_bahan_keluar = sp_bahan_keluar.no_bahan_keluar");
		$this->db->join("ap_store","ap_store.id_store = sp_no_bahan_keluar.store_tujuan","left");
		$this->db->join("ap_produk","ap_produk.id_produk = sp_bahan_keluar.sku");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("sp_bahan_keluar.tanggal_keluar BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($idStore)){
			$this->db->where("sp_no_bahan_keluar.store_tujuan",$idStore);
		}

		if(!empty($idProduk)){
			$this->db->where("sp_bahan_keluar.sku",$idProduk);
		}

		if(!empty($search)){
			$this->db->like("sp_no_bahan_keluar.no_bahan_keluar",$search);
		}

		$query = $this->db->get()->row();
		return $query->qty;
	}

	function rowPenerimaanBarang($dateStart,$dateEnd,$tempatPenerimaan='',$supplier='',$brand=''){
		$this->db->select(array("receive_order.no_receive","receive_order.no_po","receive_order.tanggal_terima","receive_order.received_by as penerima","receive_order.checked_by as pemeriksa","supplier.supplier","ap_store.store","receive_order.diterimaDi"));
		$this->db->from("receive_order");
		$this->db->join("supplier","supplier.id_supplier = receive_order.id_supplier","left");
		$this->db->join("ap_store","ap_store.id_store = receive_order.diterimaDi","left");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("receive_order.tanggal_terima BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($tempatPenerimaan)){
			$this->db->where("receive_order.diterimaDi",$tempatPenerimaan);
		}

		if(!empty($supplier)){
			$this->db->where("receive_order.id_supplier",$supplier);
		}
		if(!empty($brand)){
			$this->db->join("receive_item","receive_item.no_receive= receive_order.no_receive");
			$this->db->join("ap_produk","ap_produk.id_produk= receive_item.sku");
			$this->db->where("ap_produk.id_brand",$brand);
		}

		$this->db->where("receive_order.type",NULL);

		if(!empty($search)){
			$this->db->like("receive_order.no_receive",$search);
			$this->db->or_like("receive_order.no_po",$search);
		}

		$this->db->group_by("receive_order.no_receive");
		return $this->db->count_all_results();
	}

	function rowPenerimaanBarangPeritem($dateStart,$dateEnd,$tempatPenerimaan='',$supplier='',$idProduk){
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive");
		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("receive_item.tanggal BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($tempatPenerimaan)){
			$this->db->where("receive_order.diterimaDi",$tempatPenerimaan);
		}

		if(!empty($supplier)){
			$this->db->where("receive_order.id_supplier",$supplier);
		}

		$this->db->where("type",NULL);

		return $this->db->count_all_results();
	}

	function viewPenerimaanBarang($limit,$start,$search='',$dateStart,$dateEnd,$tempatPenerimaan='',$supplier='',$brand=''){
		$this->db->select(array("receive_order.no_receive","receive_order.no_po","receive_order.tanggal_terima","receive_order.received_by as penerima","receive_order.checked_by as pemeriksa","supplier.supplier","ap_store.store","receive_order.diterimaDi","SUM(receive_item.price*receive_item.qty) as harga"));
		$this->db->from("receive_order");
		$this->db->join("purchase_order","purchase_order.no_po = receive_order.no_po");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->join("ap_store","ap_store.id_store = receive_order.diterimaDi","left");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("receive_order.tanggal_terima BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($tempatPenerimaan)){
			$this->db->where("receive_order.diterimaDi",$tempatPenerimaan);
		}

		if(!empty($supplier)){
			$this->db->where("receive_order.id_supplier",$supplier);
		}
		$this->db->join("receive_item","receive_item.no_receive= receive_order.no_receive");
		$this->db->join("ap_produk","ap_produk.id_produk= receive_item.sku");
		if(!empty($brand)){
			$this->db->where("ap_produk.id_brand",$brand);
		}

		$this->db->where("receive_order.type",NULL);

		if(!empty($search)){
			$this->db->like("receive_order.no_receive",$search);
			$this->db->or_like("receive_order.no_po",$search);
		}

		$this->db->limit($limit,$start);

		$this->db->group_by("receive_order.no_receive");
		$this->db->order_by("receive_order.tanggal_terima","DESC");
		return $this->db->get();
	}

	function qtyPeritemPenerimaan($dateStart,$dateEnd,$tempatPenerimaan,$supplier,$idProduk){
		$this->db->select("SUM(receive_item.qty) as qty");
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive");
		$this->db->join("ap_produk","ap_produk.id_produk = receive_item.sku","left");
		$this->db->join("supplier","supplier.id_supplier = receive_order.id_supplier","left");
		$this->db->join("ap_store","ap_store.id_store = receive_order.diterimaDi","left");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("receive_item.tanggal BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($tempatPenerimaan)){
			$this->db->where("receive_order.diterimaDi",$tempatPenerimaan);
		}

		if(!empty($supplier)){
			$this->db->where("receive_order.id_supplier",$supplier);
		}

		if(!empty($idProduk)){
			$this->db->where("ap_produk.id_produk",$idProduk);
		}

		$this->db->where("receive_order.type",NULL);

		if(!empty($search)){
			$this->db->like("receive_order.no_receive",$search);
			$this->db->or_like("receive_order.no_po",$search);
		}
		return $this->db->get()->row()->qty;
	}

	function viewPenerimaanBarangPeritem($limit,$start,$search='',$dateStart,$dateEnd,$tempatPenerimaan='',$supplier='',$idProduk){
		$this->db->select(array("receive_order.no_receive","receive_order.no_po","receive_item.tanggal","receive_order.received_by as penerima","receive_order.checked_by as pemeriksa","supplier.supplier","ap_store.store","receive_order.diterimaDi","ap_produk.nama_produk","ap_produk.id_produk","receive_item.qty"));
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive");
		$this->db->join("ap_produk","ap_produk.id_produk = receive_item.sku","left");
		$this->db->join("supplier","supplier.id_supplier = receive_order.id_supplier","left");
		$this->db->join("ap_store","ap_store.id_store = receive_order.diterimaDi","left");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("receive_item.tanggal BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($tempatPenerimaan)){
			$this->db->where("receive_order.diterimaDi",$tempatPenerimaan);
		}

		if(!empty($supplier)){
			$this->db->where("receive_order.id_supplier",$supplier);
		}

		if(!empty($idProduk)){
			$this->db->where("ap_produk.id_produk",$idProduk);
		}

		$this->db->where("receive_order.type",NULL);

		if(!empty($search)){
			$this->db->like("receive_order.no_receive",$search);
			$this->db->or_like("receive_order.no_po",$search);
		}

		$this->db->limit($limit,$start);

		$this->db->order_by("receive_order.tanggal_terima","DESC");
		return $this->db->get();
	}

	function purchaseItem($no_po){
		$this->db->select(array("ap_produk.nama_produk","purchase_item.qty","ap_produk.satuan","purchase_item.harga","(purchase_item.harga*purchase_item.qty) as total","ap_produk.id_produk"));
		$this->db->from("purchase_item");
		$this->db->join("ap_produk","ap_produk.id_produk = purchase_item.sku","left");
		$this->db->where("purchase_item.no_po",$no_po);
		return $this->db->get()->result();
	}

	function delivered_qty($no_po,$sku){
		$this->db->select("SUM(qty) as qty");
		$this->db->from("receive_item");
		$this->db->join("receive_order","receive_order.no_receive = receive_item.no_receive","left");
		$this->db->where("receive_order.no_po",$no_po);
		$this->db->where("receive_item.sku",$sku);
		$query = $this->db->get();
		foreach($query->result() as $row){
			return $row->qty;
		}
	}

	function returItem($noPO,$idProduk){
		$this->db->select("SUM(qty) as qty");
		$this->db->from("retur_item");
		$this->db->join("retur","retur.no_retur = retur_item.no_retur");
		$this->db->where("retur.no_po",$noPO);
		$this->db->where("retur_item.sku",$idProduk);
		$this->db->group_by("retur_item.sku");
		$query = $this->db->get()->result();
		foreach($query as $row){
			return $row->qty;
		}
	}

	function viewReportPurchaseOrder($dateStart,$dateEnd,$supplier,$status,$idToko=''){
		$dataSelect = array(
								"purchase_order.no_po","purchase_order.tanggal_po","purchase_order.tanggal_kirim","supplier.supplier","users.first_name","purchase_order.status","purchase_order.keterangan"
						   );

		$this->db->select($dataSelect);
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier");
		$this->db->join("users","users.id = purchase_order.id_pic","left");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("purchase_order.tanggal_po BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($supplier)){
			$this->db->where("purchase_order.id_supplier",$supplier);	
		}
        
        //if($idUser>1){
			$this->db->where("purchase_order.id_toko",$idToko);	
		//}

		if(!empty($status)){
			$this->db->where("purchase_order.status",$status);
		}

		$this->db->order_by("purchase_order.tanggal_po","DESC");
		$this->db->order_by("purchase_order.no_po","DESC");
		$this->db->where('type',0);
		return $this->db->get()->result();
	}

	function viewReportReturPembelian($dateStart,$dateEnd,$supplier){
		$this->db->select(array("retur.no_po","retur.tanggal_retur","supplier.supplier","users.first_name"));
		$this->db->from("retur");
		$this->db->join("purchase_order","purchase_order.no_po = retur.no_po","left");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->join("users","users.id = retur.id_pic","left");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("DATE(retur.tanggal_retur) BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($supplier)){
			$this->db->where("purchase_order.id_supplier",$supplier);
		}

		$this->db->group_by("retur.no_po");
		return $this->db->get()->result();
	}	

	function hutangTerbayar($noTagihan){
		$this->db->select_sum("pembayaran");
		$this->db->from("hutang_order");
		$this->db->where("no_po",$noTagihan);
		$query = $this->db->get()->row();
		return $query->pembayaran;
	}

	function tagihanHutang(){
		$query = "SELECT purchase_order.no_po, purchase_order.jatuh_tempo, purchase_order.tanggal_po,supplier.supplier,total_po, total_terbayar, purchase_order.keterangan, users.first_name, purchase_order.status, purchase_order.nilai_ppn
				  FROM purchase_order 
				  LEFT JOIN (SELECT SUM(purchase_item.harga*purchase_item.qty) as total_po, purchase_item.no_po
				  			 FROM purchase_item
				  			 GROUP BY purchase_item.no_po)
				  			 as purchase_item_join ON purchase_item_join.no_po = purchase_order.no_po
				  LEFT JOIN (SELECT SUM(hutang_order.pembayaran) as total_terbayar, hutang_order.no_po
				  			 FROM hutang_order
				  			 GROUP BY hutang_order.no_po ) as terbayar_join ON terbayar_join.no_po = purchase_order.no_po 
				  LEFT JOIN hutang ON hutang.no_tagihan = purchase_order.no_po
				  LEFT JOIN supplier ON purchase_order.id_supplier = supplier.id_supplier
				  LEFT JOIN users ON users.id = purchase_order.id_pic
				  WHERE purchase_order.status != '0' AND purchase_order.status != '2' AND hutang.status_hutang != '2'
				  GROUP BY purchase_order.no_po";
		return $this->db->query($query);	
	}


	function viewReportWaste($dateStart,$dateEnd,$idProduk){
		$this->db->select(array("waste.keterangan","waste.tanggal_waste","keterangan_waste.keterangan as jenis","ap_produk.id_produk","ap_produk.nama_produk","waste_item.harga","waste_item.qty","(waste_item.harga*waste_item.qty) as total"));
		$this->db->from("waste_item");
		$this->db->join("waste","waste.no_waste = waste_item.no_waste","left");
		$this->db->join("keterangan_waste","waste.id_keterangan = keterangan_waste.id_keterangan","left");
		
		$this->db->join("ap_produk","ap_produk.id_produk = waste_item.sku","left");
		$this->db->where("waste_item.tanggal BETWEEN '$dateStart' AND '$dateEnd'");

		if(!empty($idProduk)){
			$this->db->where("waste_item.sku",$idProduk);
		}

		return $this->db->get()->result();
	}

	function dataRetur($noPO){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","retur_item.qty","retur_item.harga","ap_produk.satuan","(retur_item.qty*retur_item.harga) as total","retur_item.tanggal"));
		$this->db->from("retur_item");
		$this->db->join("ap_produk","ap_produk.id_produk = retur_item.sku");
		$this->db->join("retur","retur.no_retur = retur_item.no_retur");
		$this->db->where("retur.no_po",$noPO);
		return $this->db->get()->result();
	}

	function hutang_harian($no_tagihan){
		$query = "SELECT supplier.supplier,hutang.no_tagihan,purchase_order.jatuh_tempo,total_hutang,total_retur, total_terbayar
				  FROM hutang
				  LEFT JOIN purchase_order ON purchase_order.no_po = hutang.no_tagihan
				  LEFT JOIN retur ON retur.no_po = hutang.no_tagihan
				  LEFT JOIN supplier ON supplier.id_supplier = purchase_order.id_supplier
				  LEFT JOIN receive_order ON receive_order.no_po = hutang.no_tagihan
				  LEFT JOIN (SELECT SUM(receive_item.qty*receive_item.price) as total_hutang,receive_item.no_receive
							 FROM receive_item
							 LEFT JOIN receive_order ON receive_order.no_receive = receive_item.no_receive
							 GROUP BY receive_order.no_po) as receiveItemJoin ON receiveItemJoin.no_receive = receive_order.no_receive
				  LEFT JOIN (SELECT SUM(retur_item.harga*retur_item.qty) as total_retur,retur_item.no_retur
				  			 FROM retur_item
				  			 LEFT JOIN retur ON retur.no_retur = retur_item.no_retur
				  			 GROUP BY retur.no_po) as returJoin ON returJoin.no_retur = retur.no_retur
				  LEFT JOIN (SELECT SUM(hutang_order.pembayaran) as total_terbayar,hutang_order.no_po
				  			FROM hutang_order
				  			LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_po
				  			GROUP BY hutang_order.no_po) as hutangOrderJoin ON hutangOrderJoin.no_po = hutang.no_tagihan
				  WHERE hutang.no_tagihan = '$no_tagihan' AND purchase_order.jatuh_tempo = current_date()
				  GROUP BY hutang.no_tagihan 
				  ";
		$result = $this->db->query($query);

		foreach($result->result() as $row){
			return $row->total_hutang-($row->total_retur+$row->total_terbayar);
		}
	}

	function hutang_7_hari($no_tagihan){
		$query = "SELECT supplier.supplier,hutang.no_tagihan,purchase_order.jatuh_tempo,total_hutang,total_retur, total_terbayar
				  FROM hutang
				  LEFT JOIN purchase_order ON purchase_order.no_po = hutang.no_tagihan
				  LEFT JOIN retur ON retur.no_po = hutang.no_tagihan
				  LEFT JOIN supplier ON supplier.id_supplier = purchase_order.id_supplier
				  LEFT JOIN receive_order ON receive_order.no_po = hutang.no_tagihan
				  LEFT JOIN (SELECT SUM(receive_item.qty*receive_item.price) as total_hutang,receive_item.no_receive
							 FROM receive_item
							 LEFT JOIN receive_order ON receive_order.no_receive = receive_item.no_receive
							 GROUP BY receive_order.no_po) as receiveItemJoin ON receiveItemJoin.no_receive = receive_order.no_receive
				  LEFT JOIN (SELECT SUM(retur_item.harga*retur_item.qty) as total_retur,retur_item.no_retur
				  			 FROM retur_item
				  			 LEFT JOIN retur ON retur.no_retur = retur_item.no_retur
				  			 GROUP BY retur.no_po) as returJoin ON returJoin.no_retur = retur.no_retur
				  LEFT JOIN (SELECT SUM(hutang_order.pembayaran) as total_terbayar,hutang_order.no_po
				  			FROM hutang_order
				  			LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_po
				  			GROUP BY hutang_order.no_po) as hutangOrderJoin ON hutangOrderJoin.no_po = hutang.no_tagihan
				  WHERE hutang.no_tagihan = '$no_tagihan' AND (purchase_order.jatuh_tempo > current_date() AND date_sub(purchase_order.jatuh_tempo,INTERVAL 7 day) <= current_date())
				  GROUP BY hutang.no_tagihan 
				  ";
		$result = $this->db->query($query);

		foreach($result->result() as $row){
			return $row->total_hutang-($row->total_retur+$row->total_terbayar);
		}
	}

	function hutang_14_hari($no_tagihan){
		$query = "SELECT supplier.supplier,hutang.no_tagihan,purchase_order.jatuh_tempo,total_hutang,total_retur, total_terbayar
				  FROM hutang
				  LEFT JOIN purchase_order ON purchase_order.no_po = hutang.no_tagihan
				  LEFT JOIN retur ON retur.no_po = hutang.no_tagihan
				  LEFT JOIN supplier ON supplier.id_supplier = purchase_order.id_supplier
				  LEFT JOIN receive_order ON receive_order.no_po = hutang.no_tagihan
				  LEFT JOIN (SELECT SUM(receive_item.qty*receive_item.price) as total_hutang,receive_item.no_receive
							 FROM receive_item
							 LEFT JOIN receive_order ON receive_order.no_receive = receive_item.no_receive
							 GROUP BY receive_order.no_po) as receiveItemJoin ON receiveItemJoin.no_receive = receive_order.no_receive
				  LEFT JOIN (SELECT SUM(retur_item.harga*retur_item.qty) as total_retur,retur_item.no_retur
				  			 FROM retur_item
				  			 LEFT JOIN retur ON retur.no_retur = retur_item.no_retur
				  			 GROUP BY retur.no_po) as returJoin ON returJoin.no_retur = retur.no_retur
				  LEFT JOIN (SELECT SUM(hutang_order.pembayaran) as total_terbayar,hutang_order.no_po
				  			FROM hutang_order
				  			LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_po
				  			GROUP BY hutang_order.no_po) as hutangOrderJoin ON hutangOrderJoin.no_po = hutang.no_tagihan
				  WHERE hutang.no_tagihan = '$no_tagihan' AND (date_sub(purchase_order.jatuh_tempo,INTERVAL 7 day) > current_date() AND date_sub(purchase_order.jatuh_tempo,INTERVAL 14 day) <= current_date())
				  GROUP BY hutang.no_tagihan 
				  ";
		$result = $this->db->query($query);

		foreach($result->result() as $row){
			return $row->total_hutang-($row->total_retur+$row->total_terbayar);
		}
	}

	function hutang_less_25($no_tagihan){
		$query = "SELECT supplier.supplier,hutang.no_tagihan,purchase_order.jatuh_tempo,total_hutang,total_retur, total_terbayar
				  FROM hutang
				  LEFT JOIN purchase_order ON purchase_order.no_po = hutang.no_tagihan
				  LEFT JOIN retur ON retur.no_po = hutang.no_tagihan
				  LEFT JOIN supplier ON supplier.id_supplier = purchase_order.id_supplier
				  LEFT JOIN receive_order ON receive_order.no_po = hutang.no_tagihan
				  LEFT JOIN (SELECT SUM(receive_item.qty*receive_item.price) as total_hutang,receive_item.no_receive
							 FROM receive_item
							 LEFT JOIN receive_order ON receive_order.no_receive = receive_item.no_receive
							 GROUP BY receive_order.no_po) as receiveItemJoin ON receiveItemJoin.no_receive = receive_order.no_receive
				  LEFT JOIN (SELECT SUM(retur_item.harga*retur_item.qty) as total_retur,retur_item.no_retur
				  			 FROM retur_item
				  			 LEFT JOIN retur ON retur.no_retur = retur_item.no_retur
				  			 GROUP BY retur.no_po) as returJoin ON returJoin.no_retur = retur.no_retur
				  LEFT JOIN (SELECT SUM(hutang_order.pembayaran) as total_terbayar,hutang_order.no_po
				  			FROM hutang_order
				  			LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_po
				  			GROUP BY hutang_order.no_po) as hutangOrderJoin ON hutangOrderJoin.no_po = hutang.no_tagihan
				  WHERE hutang.no_tagihan = '$no_tagihan' AND (date_sub(purchase_order.jatuh_tempo,INTERVAL 14 day) > current_date() AND date_sub(purchase_order.jatuh_tempo,INTERVAL 25 day) <= current_date())
				  GROUP BY hutang.no_tagihan 
				  ";
		$result = $this->db->query($query);

		foreach($result->result() as $row){
			return $row->total_hutang-($row->total_retur+$row->total_terbayar);
		}
	}

	function hutang_25($no_tagihan){
		$query = "SELECT supplier.supplier,hutang.no_tagihan,purchase_order.jatuh_tempo,total_hutang,total_retur, total_terbayar
				  FROM hutang
				  LEFT JOIN purchase_order ON purchase_order.no_po = hutang.no_tagihan
				  LEFT JOIN retur ON retur.no_po = hutang.no_tagihan
				  LEFT JOIN supplier ON supplier.id_supplier = purchase_order.id_supplier
				  LEFT JOIN receive_order ON receive_order.no_po = hutang.no_tagihan
				  LEFT JOIN (SELECT SUM(receive_item.qty*receive_item.price) as total_hutang,receive_item.no_receive
							 FROM receive_item
							 LEFT JOIN receive_order ON receive_order.no_receive = receive_item.no_receive
							 GROUP BY receive_order.no_po) as receiveItemJoin ON receiveItemJoin.no_receive = receive_order.no_receive
				  LEFT JOIN (SELECT SUM(retur_item.harga*retur_item.qty) as total_retur,retur_item.no_retur
				  			 FROM retur_item
				  			 LEFT JOIN retur ON retur.no_retur = retur_item.no_retur
				  			 GROUP BY retur.no_po) as returJoin ON returJoin.no_retur = retur.no_retur
				  LEFT JOIN (SELECT SUM(hutang_order.pembayaran) as total_terbayar,hutang_order.no_po
				  			FROM hutang_order
				  			LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_po
				  			GROUP BY hutang_order.no_po) as hutangOrderJoin ON hutangOrderJoin.no_po = hutang.no_tagihan
				  WHERE hutang.no_tagihan = '$no_tagihan' AND date_sub(purchase_order.jatuh_tempo,INTERVAL 25 day) > current_date()
				  GROUP BY hutang.no_tagihan 
				  ";
		$result = $this->db->query($query);

		foreach($result->result() as $row){
			return $row->total_hutang-($row->total_retur+$row->total_terbayar);
		}
	}

	function hutang_lebih_tempo($no_tagihan){
		$query = "SELECT supplier.supplier,hutang.no_tagihan,purchase_order.jatuh_tempo,total_hutang,total_retur, total_terbayar
				  FROM hutang
				  LEFT JOIN purchase_order ON purchase_order.no_po = hutang.no_tagihan
				  LEFT JOIN retur ON retur.no_po = hutang.no_tagihan
				  LEFT JOIN supplier ON supplier.id_supplier = purchase_order.id_supplier
				  LEFT JOIN receive_order ON receive_order.no_po = hutang.no_tagihan
				  LEFT JOIN (SELECT SUM(receive_item.qty*receive_item.price) as total_hutang,receive_item.no_receive
							 FROM receive_item
							 LEFT JOIN receive_order ON receive_order.no_receive = receive_item.no_receive
							 GROUP BY receive_order.no_po) as receiveItemJoin ON receiveItemJoin.no_receive = receive_order.no_receive
				  LEFT JOIN (SELECT SUM(retur_item.harga*retur_item.qty) as total_retur,retur_item.no_retur
				  			 FROM retur_item
				  			 LEFT JOIN retur ON retur.no_retur = retur_item.no_retur
				  			 GROUP BY retur.no_po) as returJoin ON returJoin.no_retur = retur.no_retur
				  LEFT JOIN (SELECT SUM(hutang_order.pembayaran) as total_terbayar,hutang_order.no_po
				  			FROM hutang_order
				  			LEFT JOIN hutang ON hutang.no_tagihan = hutang_order.no_po
				  			GROUP BY hutang_order.no_po) as hutangOrderJoin ON hutangOrderJoin.no_po = hutang.no_tagihan
				  WHERE hutang.no_tagihan = '$no_tagihan'AND purchase_order.jatuh_tempo < current_date()
				  GROUP BY hutang.no_tagihan 
				  ";
		$result = $this->db->query($query);

		foreach($result->result() as $row){
			return $row->total_hutang-($row->total_retur+$row->total_terbayar);
		}
	}

	function laporanHutangTerbayar($dateStart,$dateEnd,$supplier,$tipeBayar,$noPO,$noPayment,$idUser){
		$this->db->select(array("hutang_order.no_payment","hutang_order.no_po","users.first_name","hutang_order.tanggal_pembayaran","hutang_order.keterangan","hutang_order.pembayaran","payment_type_debt.paymentType","supplier.supplier"));
		$this->db->from("hutang_order");
		$this->db->join("purchase_order","purchase_order.no_po = hutang_order.no_po","left");
		$this->db->join("supplier","supplier.id_supplier = purchase_order.id_supplier","left");
		$this->db->join("users","users.id = hutang_order.id_pic","left");
		$this->db->join("payment_type_debt","payment_type_debt.id = hutang_order.id_payment","left");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("DATE(hutang_order.tanggal_pembayaran) BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($supplier)){
			$this->db->where("purchase_order.id_supplier",$supplier);
		}
        
        

		if(!empty($tipeBayar)){
			$this->db->where("hutang_order.id_payment",$tipeBayar);
		}

		if(!empty($noPO)){
			$this->db->where("hutang_order.no_po",$noPO);
		}

		if(!empty($noPayment)){
			$this->db->where("hutang_order.no_payment",$noPayment);
		}

		$this->db->group_by("hutang_order.no_payment");
		$this->db->order_by("hutang_order.tanggal_pembayaran","DESC");
		return $this->db->get();
	}

	function viewReportPenjualanPeritem($dateStart,$dateEnd,$idProduk,$noInvoice,$store){
		$this->db->select(array("ap_retur_item.no_retur","ap_retur.no_invoice","ap_retur_item.tanggal","ap_produk.id_produk","ap_produk.nama_produk","ap_retur_item.harga","ap_retur_item.diskon","ap_retur_item.qty","users.first_name"));
		$this->db->from("ap_retur_item");
		$this->db->join("ap_retur","ap_retur.no_retur = ap_retur_item.no_retur");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_retur_item.id_produk","left");
		$this->db->join("users","users.id = ap_retur.pic","left");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("ap_retur_item.tanggal BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($idProduk)){
			$this->db->where("ap_retur_item.id_produk",$idProduk);
		}

		if(!empty($noInvoice)){
			$this->db->where("ap_retur.no_invoice",$noInvoice);
		}

		if(!empty($store)){
			$this->db->where("ap_retur.id_toko",$store);
		}

		return $this->db->get()->result();
	}

	function viewReportSetoranKasir($dateStart,$dateEnd,$store){
		$this->db->select(array("setoran_kasir.no_setor","setoran_kasir.tanggal","setoran_kasir.jam_setor","users.first_name"));
		$this->db->from("setoran_kasir");
		$this->db->join("users","users.id = setoran_kasir.id_user","left");

		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("setoran_kasir.tanggal BETWEEN '$dateStart' AND '$dateEnd'");
		}

		if(!empty($store)){
			$this->db->where("setoran_kasir.id_toko",$store);
		}

		return $this->db->get()->result();
	}

	function viewReportSalesDibatalkan($dateStart,$dateEnd,$idToko=''){

		
		$dataSelect = array(
			"i.no_invoice","i.id_produk","p.nama_produk","a.hpp","a.harga as harga_jual","i.qty","n.tanggal","u.username","s.store"
						   );

		$this->db->select($dataSelect);
		$this->db->from("ap_invoice_item i");
		$this->db->join("ap_invoice_number n","n.no_invoice=i.no_invoice","left");
		$this->db->join("ap_produk_price a","a.id_produk=i.id_produk and a.id_toko=n.id_toko","left");
		$this->db->join("users u","u.id =n.id_pic","left");
		$this->db->join("ap_store s","s.id_store=n.id_toko","left");
		$this->db->join("ap_produk p","p.id_produk=i.id_produk","left");
		if(!empty($dateStart) && !empty($dateEnd)){
			$this->db->where("i.tanggal BETWEEN '$dateStart' AND '$dateEnd'");
		}
        
        if($idToko>0){
			$this->db->where("n.id_toko",$idToko);	
		}
		$this->db->where("i.qty",0);

		$this->db->order_by("n.id_toko","ASC");
		$this->db->order_by("n.id_pic","DESC");
		$this->db->order_by("n.tanggal","DESC");
		return $this->db->get()->result();
	}
}

