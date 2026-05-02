<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelMD extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function update_last_receive($id_toko){
		$query = "SELECT stok_store.id_produk,stok_store.id_store,max(receive_item.tanggal) as tanggal from
					stok_store 
					left outer join receive_item on receive_item.sku=stok_store.id_produk
					left outer join receive_order on receive_order.no_receive=receive_item.no_receive
					where receive_order.diterimaDi=stok_store.id_store and stok_store.id_store='$id_toko'
					group by stok_store.id_produk";
		$result = $this->db->query($query);

		foreach($result->result() as $row){
			$data_stok[] = array(
				'id_produk' => $row->id_produk,
				'last_receives' => $row->tanggal
			);
		}
		$this->db->where("id_store",$id_toko);
		$this->db->update_batch("stok_store",$data_stok,'id_produk');
	}

	function dalamPO($where,$id_toko=''){
		$this->db->select("top1000.id_produk,SUM(IF(purchase_item.qty<0,purchase_item.qty_req,purchase_item.qty)) as qty,SUM(IF(purchase_item.qty_confirmed<0,0,purchase_item.qty_confirmed)) as qty_supplier,purchase_order.no_po");
		$this->db->from("top1000");
		$this->db->join("purchase_item","purchase_item.sku = top1000.id_produk");
		$this->db->join("purchase_order","purchase_order.no_po=purchase_item.no_po");
		
        //if ($isAdmin!=1){
            $this->db->where("purchase_order.id_toko",$id_toko);
        //}
		$this->db->where("purchase_order.perfomance",0);
		$this->db->where("purchase_order.status in (0,1,4)");

		if(!empty($id_toko)){
			$this->db->where("top1000.id_toko",$id_toko);
		}
		
		$this->db->where($where);

		//$this->db->order_by("brand.brand","ASC");
		$this->db->order_by("top1000.aging","ASC");
		$this->db->group_by("top1000.id_produk");

		$query = $this->db->get();

		return $query;
	}

	function top1000_data($where,$id_toko=''){
		$this->db->select("top1000.*,ap_produk.nama_produk,stok_store.hpp");
		$this->db->from("top1000");
		$this->db->join("ap_produk","ap_produk.id_produk=top1000.id_produk");
		$this->db->join("stok_store","stok_store.id_produk=top1000.id_produk and stok_store.id_store=top1000.id_toko");
		$this->db->join("brand","brand.id_brand=ap_produk.id_brand");
		if(!empty($id_toko)){
			$this->db->where("top1000.id_toko",$id_toko);
		}
		
		$this->db->where($where);

		//$this->db->order_by("brand.brand","ASC");
		$this->db->order_by("top1000.aging","ASC");

		$query = $this->db->get()->result();

		return $query;
	}

	function top1000($status,$id_toko=''){
		$this->db->select("COUNT(id_produk) as trx");
		$this->db->from("top1000");
		//if(!empty($id_toko)){
			$this->db->where("id_toko",$id_toko);
		//}
		
		$this->db->where($status);

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}

	function recalculateTop1000($toko){
		$start 	= date('Y-m-d',strtotime("-21 days"));
		$end	= date("Y-m-d");
		
		$this->db->select(array("ap_invoice_item.id_produk","SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as t_harga_jual","SUM(ap_invoice_item.qty) as qty_terjual","stok_store.stok as stok","stok_store.last_receives","stok_store.last_sales","stok_store.expire_date"));
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","LEFT");
		$this->db->join("stok_store","stok_store.id_produk = ap_invoice_item.id_produk and stok_store.id_store='$toko'");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		
		$this->db->where("ap_invoice_item.id_store",$toko);
		$this->db->where("ap_produk.produk_jual",1);
		$this->db->where("ap_produk.konsinyasi",0);

		$this->db->group_by("ap_produk.id_produk");
		
		$this->db->order_by("t_harga_jual","DESC");

		$this->db->limit(1000, 0);
		
		$query = $this->db->get();
		return $query;
	}
	function insertBatchTop1000($data_item){
		$this->db->insert_batch("top1000",$data_item);		
	}

	function leadMD_data($status,$day){
		$time = date('Y-m-d H:i:s',strtotime("60 days ago"));
		$this->db->select("purchase_order.*,supplier.supplier,lead_time.lead_day,lead_time.waktu");
		$this->db->from("lead_time");
		$this->db->join("purchase_order","purchase_order.no_po=lead_time.no_po");
		$this->db->join("supplier","supplier.id_supplier=purchase_order.id_supplier");
		$this->db->where("lead_time.lead_day>",$day);
		$this->db->where("waktu>",$time);
		$this->db->like("lead_time.status",$status);
		$this->db->order_by("lead_time.lead_day","DESC");

		$query = $this->db->get()->result();

		return $query;
	}
	function leadMD($status,$day){
		$time = date('Y-m-d H:i:s',strtotime("60 days ago"));
		$this->db->select("COUNT(ID) as trx");
		$this->db->from("lead_time");
		$this->db->where("lead_day>",$day);
		$this->db->where("status",$status);
		$this->db->where("waktu>",$time);

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function todaybelanja($globstore,$isSuperadmin,$selected_store){
		$this->db->select("SUM(IF(purchase_item.qty<0,purchase_item.qty_req,purchase_item.qty)*purchase_item.harga) as trx");
		$this->db->from("purchase_order");
		$this->db->join("purchase_item","purchase_item.no_po=purchase_order.no_po");
		$this->db->where("purchase_order.tanggal_po",date('Y-m-d'));

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function belanja_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("purchase_order.*,supplier.supplier,users.first_name,SUM(IF(purchase_item.qty<0,purchase_item.qty_req,purchase_item.qty)*purchase_item.harga) as trx");
		$this->db->from("purchase_order");
		$this->db->join("purchase_item","purchase_item.no_po=purchase_order.no_po");
		$this->db->join("supplier","supplier.id_supplier=purchase_order.id_supplier","LEFT");
		$this->db->join("users","users.id=purchase_order.id_pic","LEFT");
		$this->db->where("purchase_order.tanggal_po",date('Y-m-d'));
		$this->db->order_by("purchase_order.tanggal_po","DESC");
		$this->db->group_by("purchase_order.no_po");

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function total_sales($idStore='',$isSuperadmin='',$id_toko=''){
		$tanggal = date("Y-m-d",strtotime("1 day ago"));
		$this->db->select(array("SUM(ap_invoice_number.total) as total","SUM(ap_invoice_number.hpp) as hpp","SUM(ap_invoice_number.diskon) as diskon","SUM(ap_invoice_number.diskon_free) as diskon_free","SUM(ap_invoice_number.poin_value) as poin_value","SUM(ap_invoice_number.diskon_otomatis) as diskon_otomatis"));
		$this->db->from("ap_invoice_number");

		if(!empty($tanggal)){
			$besok = date('Y-m-d');
			$this->db->where("ap_invoice_number.tanggal between '$tanggal 06:00:00' and '$besok 05:59:00'");
		}
        if ($isSuperadmin!=1){
            $this->db->where("ap_invoice_number.id_toko",$idStore);
        }
		if(!empty($id_toko)){
			$this->db->where("ap_invoice_number.id_toko",$id_toko);
		}
			$this->db->where("ap_invoice_number.total>",0);

		$query = $this->db->get()->result();

		foreach($query as $row){
			$total 	= $row->total;
			$diskon = $row->diskon;
			$diskon_free =$row->diskon_free;
			$reimburs = $row->poin_value;
			$diskonOtomatis = $row->diskon_otomatis;

			return 0.7*($total-($diskon+$diskon_free+$reimburs+$diskonOtomatis));
		}
	}
	function listSSR($globstore,$isSuperadmin,$selected_store){
		$dataSelect = array(
								"stok_store_ssr.id_produk","stok_store_ssr.terjual","stok_store_ssr.stok","ap_produk.id_brand","brand.brand"
						   );

		$this->db->select($dataSelect);
		$this->db->from("stok_store_ssr");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store_ssr.id_produk");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand");

		if ($isSuperadmin!=1 ){
            $this->db->where("stok_store_ssr.id_store",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("stok_store_ssr.id_store",$selected_store);
		}

		$this->db->where("ap_produk.id_brand>0");
		$this->db->where("ap_produk.produk_jual",1);
		$this->db->where("ap_produk.konsinyasi",0);
		$this->db->order_by("ap_produk.id_brand");
        
		return $this->db->get();
	}
    function exportDataMD($filterby,$start,$end,$toko){
		
        $this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan","stok_store.hpp as harga_beli","stok_store.harga as harga_jual","SUM(stok_store.hpp*ap_invoice_item.qty) as t_harga_beli","SUM(stok_store.harga*ap_invoice_item.qty) as t_harga_jual","SUM(ap_invoice_item.qty) as qty_terjual","SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.diskon,0)) as diskon","SUM(IF(ap_invoice_item.qty>0,ap_invoice_item.disc_supplier,0)) as disc_supplier","brand.brand","stok_store.stok as stok","(stok_store.last_received) as tanggal_po","stok_store.last_receives","stok_store.last_sales","stok_store.info_retur","stok_store.expire_date"));
		$this->db->from("stok_store");
		$this->db->join("ap_produk","ap_produk.id_produk = stok_store.id_produk");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand");
        $this->db->join("ap_invoice_item","ap_invoice_item.id_produk = ap_produk.id_produk and ap_invoice_item.id_store='$toko' and ap_invoice_item.tanggal BETWEEN '$start' AND '$end'","LEFT");
		
		$this->db->where("stok_store.id_store",$toko);
		$this->db->where("ap_produk.produk_jual",1);

		$this->db->group_by("ap_produk.id_produk");
		if(empty($filterby)){
			$this->db->order_by("t_harga_jual","DESC");
		}else{
			$this->db->order_by("qty_terjual","DESC");
		}
		
		$query = $this->db->get();
		return $query;
	}
	function recalculateSSR($selected_store=''){
		if(!empty($selected_store)){
			$hari = date('Y-m-d',strtotime("30 days ago"));
			$query = "INSERT into stok_store_ssr(id_produk,id_store,terjual,stok,tanggal)
				select stok_store.id_produk,stok_store.id_store,SUM(ap_invoice_item.qty),stok_store.stok,now()
					from stok_store 
					left outer join ap_invoice_item on ap_invoice_item.id_produk=stok_store.id_produk and ap_invoice_item.id_store=stok_store.id_store and ap_invoice_item.tanggal>'$hari'
					where stok_store.id_store='$selected_store'
					group by stok_store.id_produk";
			$this->db->query($query);
		}
	}
	function hapusStokSSR($selected_store){
		$this->db->where("id_store",$selected_store);
        $this->db->delete("stok_store_ssr");
	}
	function lowperformance($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(purchase_order.no_po) as trx");
		$this->db->from("purchase_order");
		$this->db->where("purchase_order.tanggal_po > '".date('Y-m-d',strtotime("-7 day"))."'");
		$this->db->where("purchase_order.perfomance < '80'");
		$this->db->where("purchase_order.perfomance > '0'");
		$this->db->where("purchase_order.perfomance_calculated",1);

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function lowperformance_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("purchase_order.*,supplier.supplier,users.first_name");
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier=purchase_order.id_supplier","LEFT");
		$this->db->join("users","users.id=purchase_order.id_pic","LEFT");
		$this->db->where("purchase_order.tanggal_po > '".date('Y-m-d',strtotime("-7 day"))."'");
		$this->db->where("purchase_order.perfomance < '80'");
		$this->db->where("purchase_order.perfomance > '0'");
		$this->db->where("purchase_order.perfomance_calculated",1);
		$this->db->order_by("purchase_order.tanggal_po","DESC");

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function prepo($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(pre_po.no_po) as trx");
		$this->db->from("pre_po");
		$this->db->where("pre_po.status",0);

        if ($isSuperadmin!=1 ){
            $this->db->where("pre_po.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("pre_po.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function prepo_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("pre_po.*,supplier.supplier,users.first_name");
		$this->db->from("pre_po");
		$this->db->join("supplier","supplier.id_supplier=pre_po.id_supplier","LEFT");
		$this->db->join("users","users.id=pre_po.id_pic","LEFT");
		$this->db->where("pre_po.status",0);
		$this->db->order_by("pre_po.tanggal_po","DESC");

        if ($isSuperadmin!=1 ){
            $this->db->where("pre_po.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("pre_po.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function waitingmd($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(purchase_order.no_po) as trx");
		$this->db->from("purchase_order");
		$this->db->where("purchase_order.status",0);

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function waitingmd_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("purchase_order.*,supplier.supplier,users.first_name");
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier=purchase_order.id_supplier","LEFT");
		$this->db->join("users","users.id=purchase_order.id_pic","LEFT");
		$this->db->where("purchase_order.status",0);
		$this->db->order_by("purchase_order.tanggal_po","DESC");

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function waitingsupplier($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(purchase_order.no_po) as trx");
		$this->db->from("purchase_order");
		$this->db->where("purchase_order.status",4);

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function waitingsupplier_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("purchase_order.*,supplier.supplier,users.first_name");
		$this->db->from("purchase_order");
		$this->db->join("supplier","supplier.id_supplier=purchase_order.id_supplier","LEFT");
		$this->db->join("users","users.id=purchase_order.id_pic","LEFT");
		$this->db->where("purchase_order.status",4);
		$this->db->order_by("purchase_order.tanggal_po","DESC");

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function waitingdelivery($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(purchase_order.no_po) as trx");
		$this->db->from("purchase_order");
		//$this->db->join("kunjungan_supplier","kunjungan_supplier.no_po=purchase_order.no_po","LEFT");
		$this->db->where("purchase_order.status",1);
		//$this->db->where("kunjungan_supplier.id_kunjungan is null",null,false);

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function waitingdelivery_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("purchase_order.*,supplier.supplier,users.first_name");
		$this->db->from("purchase_order");
		//$this->db->join("kunjungan_supplier","kunjungan_supplier.no_po=purchase_order.no_po","LEFT");
		$this->db->join("supplier","supplier.id_supplier=purchase_order.id_supplier","LEFT");
		$this->db->join("users","users.id=purchase_order.id_pic","LEFT");
		$this->db->where("purchase_order.status",1);
		//$this->db->where("kunjungan_supplier.id_kunjungan is null",null,false);
		$this->db->order_by("purchase_order.tanggal_po","DESC");

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function waitingreceive($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(purchase_order.no_po) as trx");
		$this->db->from("purchase_order");
		$this->db->join("kunjungan_supplier","kunjungan_supplier.no_po=purchase_order.no_po");
		$this->db->where("purchase_order.status",1);

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function waitingreceive_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("purchase_order.*,supplier.supplier,users.first_name");
		$this->db->from("purchase_order");
		$this->db->join("kunjungan_supplier","kunjungan_supplier.no_po=purchase_order.no_po");
		$this->db->join("supplier","supplier.id_supplier=purchase_order.id_supplier","LEFT");
		$this->db->join("users","users.id=purchase_order.id_pic","LEFT");
		$this->db->where("purchase_order.status",1);
		$this->db->order_by("purchase_order.tanggal_po","DESC");

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function todaypo($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(purchase_order.no_po) as trx");
		$this->db->from("purchase_order");
		$this->db->where("purchase_order.tanggal_po",date('Y-m-d'));

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function todaypo_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("purchase_order.*,supplier.supplier,users.first_name,brand.brand");
		$this->db->from("purchase_order");
		$this->db->join("purchase_item","purchase_item.no_po=purchase_order.no_po","LEFT");
		$this->db->join("ap_produk","ap_produk.id_produk = purchase_item.sku","LEFT");
		$this->db->join("brand","brand.id_brand = ap_produk.id_brand","LEFT");
		$this->db->join("supplier","supplier.id_supplier=purchase_order.id_supplier","LEFT");
		$this->db->join("users","users.id=purchase_order.id_pic","LEFT");
		$this->db->where("purchase_order.tanggal_po",date('Y-m-d'));
		$this->db->order_by("purchase_order.tanggal_po","DESC");
		$this->db->group_by("purchase_order.no_po");

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function todayreceive($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(receive_order.no_receive) as trx");
		$this->db->from("receive_order");
		$this->db->where("receive_order.tanggal_terima",date('Y-m-d'));

        if ($isSuperadmin!=1 ){
            $this->db->where("receive_order.diterimaDi",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("receive_order.diterimaDi",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function todayreceive_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("receive_order.*,users.first_name,purchase_order.tanggal_po");
		$this->db->from("receive_order");
		$this->db->where("receive_order.tanggal_terima",date('Y-m-d'));
		$this->db->join("users","users.id=receive_order.id_pic","LEFT");
		$this->db->join("purchase_order","purchase_order.no_po=receive_order.no_po","LEFT");
		$this->db->order_by("receive_order.tanggal_terima","DESC");

        if ($isSuperadmin!=1 ){
            $this->db->where("receive_order.diterimaDi",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("receive_order.diterimaDi",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function transferblm($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(transferstoknumber.noTransfer) as trx");
		$this->db->from("transferstoknumber");
		
		$this->db->like("transferstoknumber.Accepted",0);

        if ($isSuperadmin!=1 ){
            $this->db->where("transferstoknumber.transferTo",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("transferstoknumber.transferTo",$selected_store);
		}
		$this->db->where("DATE(transferstoknumber.tanggal)>'2025-04-28'");

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function transferblm_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("transferstoknumber.*,ap_store.store,users.first_name");
		$this->db->from("transferstoknumber");
		$this->db->like("transferstoknumber.Accepted",0);
		$this->db->join("users","users.id=transferstoknumber.idUser","LEFT");
		$this->db->join("ap_store","ap_store.id_store=transferstoknumber.transferFrom","LEFT");
		$this->db->order_by("transferstoknumber.tanggal","DESC");

        if ($isSuperadmin!=1 ){
            $this->db->where("transferstoknumber.transferTo",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("transferstoknumber.transferTo",$selected_store);
		}
		$this->db->where("DATE(transferstoknumber.tanggal)>'2025-04-28'");

		$query = $this->db->get()->result();

		return $query;
	}
	function todaytransfer($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(transferstoknumber.noTransfer) as trx");
		$this->db->from("transferstoknumber");
		$this->db->like("transferstoknumber.tanggal",date('Y-m-d'));

        if ($isSuperadmin!=1 ){
            $this->db->where("transferstoknumber.transferFrom",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("transferstoknumber.transferFrom",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function todaytransfer_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("transferstoknumber.*,ap_store.store,users.first_name");
		$this->db->from("transferstoknumber");
		$this->db->like("transferstoknumber.tanggal",date('Y-m-d'));
		$this->db->join("users","users.id=transferstoknumber.idUser","LEFT");
		$this->db->join("ap_store","ap_store.id_store=transferstoknumber.transferTo","LEFT");
		$this->db->order_by("transferstoknumber.tanggal","DESC");

        if ($isSuperadmin!=1 ){
            $this->db->where("transferstoknumber.transferFrom",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("transferstoknumber.transferFrom",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function todaytransferrec($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(transferstoknumber.noTransfer) as trx");
		$this->db->from("transferstoknumber");
		$this->db->like("transferstoknumber.tanggal_terima",date('Y-m-d'));

        if ($isSuperadmin!=1 ){
            $this->db->where("transferstoknumber.transferTo",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("transferstoknumber.transferTo",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function todaytransferrec_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("transferstoknumber.*,ap_store.store,users.first_name");
		$this->db->from("transferstoknumber");
		$this->db->like("transferstoknumber.tanggal_terima",date('Y-m-d'));
		$this->db->join("users","users.id=transferstoknumber.id_penerima","LEFT");
		$this->db->join("ap_store","ap_store.id_store=transferstoknumber.transferFrom","LEFT");
		$this->db->order_by("transferstoknumber.tanggal_terima","DESC");

        if ($isSuperadmin!=1 ){
            $this->db->where("transferstoknumber.transferTo",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("transferstoknumber.transferTo",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function todayretur($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(retur.no_retur) as trx");
		$this->db->from("retur");
		$this->db->join("purchase_order","purchase_order.no_po=retur.no_po");
		$this->db->like("retur.tanggal_retur",date('Y-m-d'));

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
	function todayretur_data($globstore,$isSuperadmin,$selected_store){
		$this->db->select("retur.*,supplier.supplier,users.first_name");
		$this->db->from("retur");
		$this->db->join("purchase_order","purchase_order.no_po=retur.no_po");
		$this->db->like("retur.tanggal_retur",date('Y-m-d'));
		$this->db->join("users","users.id=retur.id_pic","LEFT");
		$this->db->join("supplier","supplier.id_supplier=purchase_order.id_supplier","LEFT");
		$this->db->order_by("retur.tanggal_retur","DESC");

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		return $query;
	}
	function omseth1($globstore,$isSuperadmin,$selected_store){
		$this->db->select("COUNT(purchase_order.no_po) as trx");
		$this->db->from("purchase_order");
		$this->db->join("kunjungan_supplier","kunjungan_supplier.no_po=purchase_order.no_po");
		$this->db->where("purchase_order.status",1);

        if ($isSuperadmin!=1 ){
            $this->db->where("purchase_order.id_toko",$globstore);
        }
		if(!empty($selected_store)){
			$this->db->where("purchase_order.id_toko",$selected_store);
		}

		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->trx;
		}
	}
}