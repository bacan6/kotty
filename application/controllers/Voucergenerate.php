<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Voucergenerate extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],8,76);
	}

	function index(){
		$this->global['pageTitle'] = "Solusi POS - Voucher Generate";
		$data['brand'] = $this->db->get("brand");
		$data['subkategori_struk_options'] = $this->subkategori_struk_options();
		$data['kategori_struk_options'] = $this->kategori_struk_options();
		$this->loadViews("voucergenerate/body_voucer",$this->global,$data,"voucergenerate/footerVoucer");
	}

	/** Select2 AJAX SKU (same pattern as Purchase_order/ajax_produk); optional brands=filters id_brand */
	function ajax_produk_voucher(){
		$q = isset($_GET['term']) ? $_GET['term'] : '';
		$brands = isset($_GET['brands']) ? $_GET['brands'] : '';
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk"));
		$this->db->from("ap_produk");
		$this->db->group_start();
		$this->db->like("ap_produk.id_produk",$q);
		$this->db->or_like("ap_produk.nama_produk",$q);
		$this->db->group_end();
		$this->db->where("ap_produk.status",1);
		if ($brands !== '') {
			$bids = array_filter(array_map('intval', explode(',', $brands)));
			if (!empty($bids)) {
				$this->db->where_in('ap_produk.id_brand', $bids);
			}
		}
		$this->db->limit(50);
		$res = $this->db->get();
		$data_array = array();
		foreach ($res->result() as $row) {
			$data_array[] = array(
				"id" => $row->id_produk,
				"text" => $row->id_produk." / ".$row->nama_produk,
			);
		}
		header('Content-Type: application/json');
		echo json_encode($data_array);
	}

	function data_voucer(){
		$date_start = $this->input->get('date_start');
		$date_end = $this->input->get('date_end');
		$brand = $this->input->get('brand');

		$this->db->from('voucher_generate');

		if ($date_start !== null && trim((string)$date_start) !== '') {
			$ds = date('Y-m-d H:i:s', strtotime($date_start));
			$this->db->where('created_at >=', $ds);
		}
		if ($date_end !== null && trim((string)$date_end) !== '') {
			$de = date('Y-m-d H:i:s', strtotime($date_end));
			$this->db->where('created_at <=', $de);
		}
		$bid = ($brand !== null && trim((string)$brand) !== '') ? (int)$brand : 0;
		if ($bid > 0) {
			$this->db->where('FIND_IN_SET(' . $bid . ', brand_ids) >', 0, false);
		}

		$kind = $this->input->get('kind');
		if ($kind === 'cetak') {
			$this->db->where('(COALESCE(voucher_struk, 0) = 0)', null, false);
		} elseif ($kind === 'struk') {
			$this->db->where('voucher_struk', 1);
		}

		$data['voucer'] = $this->db->get();
		$this->load->view("voucergenerate/data_voucer", $data);
	}


	function add_voucer_sql(){

		$nama_voucer 		= $_POST['nama_voucer'];
		$berlaku_mulai 			= $_POST['berlaku_mulai'];
		$berlaku_selesai 		= $_POST['berlaku_selesai'];
		$jml_voucher 				= $_POST['jumlah'];
		$nilai 			= $_POST['nilai'];
		$nilai_tipe = isset($_POST['nilai_tipe']) && $_POST['nilai_tipe'] === 'percent' ? 'percent' : 'rp';
		$brand_ids = isset($_POST['brand']) && is_array($_POST['brand']) ? implode(',', array_map('intval', $_POST['brand'])) : '';
		$produk_ids = isset($_POST['id_produk']) && is_array($_POST['id_produk']) ? implode(',', array_map('trim', $_POST['id_produk'])) : '';

			$val_err = $this->validate_nilai_by_tipe($nilai_tipe, $nilai);
			if ($val_err !== null) {
				$this->json_validation_fail($val_err);
				return;
			}

			$huruf="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	        $id=substr(str_shuffle($huruf), 0, 8).date('s');

			$data_insert = array(
					'id_generate'=>$id,
				 	'nm_voucher' => $nama_voucer,
				  	'berlaku_mulai'=> date("Y-m-d H:i:s",strtotime($berlaku_mulai)),
				   	'berlaku_selesai'=> date("Y-m-d H:i:s",strtotime($berlaku_selesai)),
				    'jml_voucher'=> $jml_voucher,
				    'nilai'=> $nilai,
				    'nilai_tipe'=> $nilai_tipe,
				    'brand_ids'=> $brand_ids ?: null,
				    'produk_ids'=> $produk_ids ?: null,
					'created_at'=> date('Y-m-d H:i:s'),
					'id_user' => $this->global['idUser'],
					'id_toko' => $this->global['idStore'],
					'voucher_struk' => 0);

			$this->db->insert("voucher_generate",$data_insert);
			$affect = $this->db->affected_rows();

			for ($x = 1; $x <= $jml_voucher; $x++) {

				$huruf="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	        	$kode=substr(str_shuffle($huruf), 0, 8);

				$data_item = array(
					'id_voucher' => $kode,
					'id_generate'=>$id,
				  	'berlaku_mulai'=> date("Y-m-d H:i:s",strtotime($berlaku_mulai)),
				   	'berlaku_selesai'=> date("Y-m-d H:i:s",strtotime($berlaku_selesai)),
				    'nilai'=> $nilai,
				    'nilai_tipe'=> $nilai_tipe,
				    'brand_ids'=> $brand_ids ?: null,
				    'produk_ids'=> $produk_ids ?: null,
				);

				$this->db->insert("voucher_item",$data_item);
				$affect = $this->db->affected_rows();
			} 

			echo $affect;
		
	}

	/** Insert voucher_generate only (struk rules); no voucher_item */
	function add_voucher_struk_sql(){
		$nama_voucer = isset($_POST['nama_voucer']) ? $_POST['nama_voucer'] : '';
		$berlaku_mulai = isset($_POST['berlaku_mulai']) ? $_POST['berlaku_mulai'] : '';
		$berlaku_selesai = isset($_POST['berlaku_selesai']) ? $_POST['berlaku_selesai'] : '';
		$jml_voucher = isset($_POST['jumlah']) ? $_POST['jumlah'] : 0;
		$nilai = isset($_POST['nilai']) ? $_POST['nilai'] : '';
		$nilai_tipe = isset($_POST['nilai_tipe']) && $_POST['nilai_tipe'] === 'percent' ? 'percent' : 'rp';
		$brand_ids = isset($_POST['brand']) && is_array($_POST['brand']) ? implode(',', array_map('intval', $_POST['brand'])) : '';
		$produk_ids = isset($_POST['id_produk']) && is_array($_POST['id_produk']) ? implode(',', array_map('trim', $_POST['id_produk'])) : '';
		$brand_ids_voucher_struk = isset($_POST['brand_struk_get']) && is_array($_POST['brand_struk_get']) ? implode(',', array_map('intval', $_POST['brand_struk_get'])) : '';
		$produk_ids_voucher_struk = isset($_POST['id_produk_struk_get']) && is_array($_POST['id_produk_struk_get']) ? implode(',', array_map('trim', $_POST['id_produk_struk_get'])) : '';
		$subkategori_ids_voucher_struk = isset($_POST['subkategori_struk_get']) && is_array($_POST['subkategori_struk_get']) ? implode(',', array_map('intval', $_POST['subkategori_struk_get'])) : '';
		$kategori_id_voucher_struk = isset($_POST['kategori_struk_get']) ? (int) $_POST['kategori_struk_get'] : 0;
		$kategori_id_voucher_struk = $kategori_id_voucher_struk > 0 ? $kategori_id_voucher_struk : null;
		$minimal_belanja = isset($_POST['minimal_belanja']) ? $_POST['minimal_belanja'] : '';
		$min_get_voucher_struk = isset($_POST['min_get_voucher_struk']) ? $_POST['min_get_voucher_struk'] : '';
		$start_voucher_struk = isset($_POST['start_voucher_struk']) ? trim((string)$_POST['start_voucher_struk']) : '';
		$end_voucher_struk = isset($_POST['end_voucher_struk']) ? trim((string)$_POST['end_voucher_struk']) : '';

		$val_err = $this->validate_nilai_by_tipe($nilai_tipe, $nilai);
		if ($val_err !== null) {
			$this->json_validation_fail($val_err);
			return;
		}
		$mb_err = $this->validate_decimal_nonneg_optional($minimal_belanja, 'Minimal belanja tidak valid');
		if ($mb_err !== null) {
			$this->json_validation_fail($mb_err);
			return;
		}
		$mg_err = $this->validate_decimal_nonneg_optional($min_get_voucher_struk, 'Minimal belanja dapat voucher tidak valid');
		if ($mg_err !== null) {
			$this->json_validation_fail($mg_err);
			return;
		}
		if ($start_voucher_struk === '' || $end_voucher_struk === '') {
			$this->json_validation_fail('Periode voucher struk (mulai / selesai) wajib diisi');
			return;
		}
		$ts_start = strtotime($start_voucher_struk);
		$ts_end = strtotime($end_voucher_struk);
		if ($ts_start === false || $ts_end === false) {
			$this->json_validation_fail('Format tanggal voucher struk tidak valid');
			return;
		}

		$huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$id = substr(str_shuffle($huruf), 0, 8).date('s');

		$data_insert = array(
			'id_generate' => $id,
			'nm_voucher' => $nama_voucer,
			'berlaku_mulai' => date("Y-m-d H:i:s", strtotime($berlaku_mulai)),
			'berlaku_selesai' => date("Y-m-d H:i:s", strtotime($berlaku_selesai)),
			'jml_voucher' => $jml_voucher,
			'nilai' => $nilai,
			'nilai_tipe' => $nilai_tipe,
			'brand_ids' => $brand_ids ?: null,
			'produk_ids' => $produk_ids ?: null,
			'created_at' => date('Y-m-d H:i:s'),
			'id_user' => $this->global['idUser'],
			'id_toko' => $this->global['idStore'],
			'voucher_struk' => 1,
			'minimal_belanja' => $this->normalize_money_post($minimal_belanja),
			'min_get_voucher_struk' => $this->normalize_money_post($min_get_voucher_struk),
			'start_voucher_struk' => date('Y-m-d H:i:s', $ts_start),
			'end_voucher_struk' => date('Y-m-d H:i:s', $ts_end),
			'brand_ids_voucher_struk' => $brand_ids_voucher_struk ?: null,
			'produk_ids_voucher_struk' => $produk_ids_voucher_struk ?: null,
			'subkategori_ids_voucher_struk' => $subkategori_ids_voucher_struk ?: null,
			'kategori_id_voucher_struk' => $kategori_id_voucher_struk,
		);

		$this->db->insert("voucher_generate", $data_insert);
		echo $this->db->affected_rows();
	}

	function form_edit_voucer(){
		$id = $_POST['id'];
		$data['voucer'] = $this->db->get_where("voucher_generate",array("id_generate" => $id));
		$data['brand'] = $this->db->get("brand");
		$data['subkategori_struk_options'] = $this->subkategori_struk_options();
		$data['kategori_struk_options'] = $this->kategori_struk_options();
		$row = $data['voucer']->row();
		$data['produk_preview'] = array();
		$data['produk_preview_struk_get'] = array();
		if ($row && isset($row->produk_ids) && $row->produk_ids !== '') {
			foreach (explode(',', $row->produk_ids) as $pid) {
				$pid = trim($pid);
				if ($pid === '') continue;
				$pr = $this->db->get_where('ap_produk', array('id_produk' => $pid))->row();
				if ($pr) {
					$data['produk_preview'][$pid] = $pr->id_produk.' / '.$pr->nama_produk;
				}
			}
		}
		if ($row && isset($row->produk_ids_voucher_struk) && $row->produk_ids_voucher_struk !== '') {
			foreach (explode(',', $row->produk_ids_voucher_struk) as $pid) {
				$pid = trim($pid);
				if ($pid === '') continue;
				$pr = $this->db->get_where('ap_produk', array('id_produk' => $pid))->row();
				if ($pr) {
					$data['produk_preview_struk_get'][$pid] = $pr->id_produk.' / '.$pr->nama_produk;
				}
			}
		}
		$this->load->view("voucergenerate/form_edit_voucer",$data);
	}

	function show_voucer($id){
		$this->global['pageTitle'] = "Solusi POS - Lihat Voucher";
		$data['voucer'] = $this->db->get_where("voucher_generate",array("id_generate" => $id))->row();
		$data['id'] = $id;
		$this->loadViews("voucergenerate/body_voucer_item",$this->global,$data,"voucergenerate/footerVoucer");
	}

	function data_voucer_item($id){
		
		$data['voucer'] = $this->db->get_where("voucher_item",array("id_generate" => $id));
		$this->load->view("voucergenerate/data_voucer_item",$data);
	}

	function edit_voucer_sql(){

		$id 				= $_POST['id'];
		$gen = $this->db->get_where('voucher_generate', array('id_generate' => $id))->row();
		if (!$gen) {
			echo '0';
			return;
		}
		$is_struk = isset($gen->voucher_struk) && (int)$gen->voucher_struk === 1;

		$nama_voucer 		= $_POST['nama_voucer'];
		$berlaku_mulai 			= $_POST['berlaku_mulai'];
		$berlaku_selesai 		= $_POST['berlaku_selesai'];
		$jml_voucher 				= isset($_POST['jumlah']) ? $_POST['jumlah'] : 0;
		$nilai 			= $_POST['nilai'];
		$nilai_tipe = isset($_POST['nilai_tipe']) && $_POST['nilai_tipe'] === 'percent' ? 'percent' : 'rp';
		$brand_ids = isset($_POST['brand']) && is_array($_POST['brand']) ? implode(',', array_map('intval', $_POST['brand'])) : '';
		$produk_ids = isset($_POST['id_produk']) && is_array($_POST['id_produk']) ? implode(',', array_map('trim', $_POST['id_produk'])) : '';

			$val_err = $this->validate_nilai_by_tipe($nilai_tipe, $nilai);
			if ($val_err !== null) {
				$this->json_validation_fail($val_err);
				return;
			}

		if ($is_struk) {
			$minimal_belanja = isset($_POST['minimal_belanja']) ? $_POST['minimal_belanja'] : '';
			$min_get_voucher_struk = isset($_POST['min_get_voucher_struk']) ? $_POST['min_get_voucher_struk'] : '';
			$start_voucher_struk = isset($_POST['start_voucher_struk']) ? trim((string)$_POST['start_voucher_struk']) : '';
			$end_voucher_struk = isset($_POST['end_voucher_struk']) ? trim((string)$_POST['end_voucher_struk']) : '';
			$brand_ids_voucher_struk = isset($_POST['brand_struk_get']) && is_array($_POST['brand_struk_get']) ? implode(',', array_map('intval', $_POST['brand_struk_get'])) : '';
			$produk_ids_voucher_struk = isset($_POST['id_produk_struk_get']) && is_array($_POST['id_produk_struk_get']) ? implode(',', array_map('trim', $_POST['id_produk_struk_get'])) : '';
			$subkategori_ids_voucher_struk = isset($_POST['subkategori_struk_get']) && is_array($_POST['subkategori_struk_get']) ? implode(',', array_map('intval', $_POST['subkategori_struk_get'])) : '';
			$kategori_id_voucher_struk = isset($_POST['kategori_struk_get']) ? (int) $_POST['kategori_struk_get'] : 0;
			$kategori_id_voucher_struk = $kategori_id_voucher_struk > 0 ? $kategori_id_voucher_struk : null;

			$mb_err = $this->validate_decimal_nonneg_optional($minimal_belanja, 'Minimal belanja tidak valid');
			if ($mb_err !== null) {
				$this->json_validation_fail($mb_err);
				return;
			}
			$mg_err = $this->validate_decimal_nonneg_optional($min_get_voucher_struk, 'Minimal belanja dapat voucher tidak valid');
			if ($mg_err !== null) {
				$this->json_validation_fail($mg_err);
				return;
			}
			if ($start_voucher_struk === '' || $end_voucher_struk === '') {
				$this->json_validation_fail('Periode voucher struk (mulai / selesai) wajib diisi');
				return;
			}
			$ts_start = strtotime($start_voucher_struk);
			$ts_end = strtotime($end_voucher_struk);
			if ($ts_start === false || $ts_end === false) {
				$this->json_validation_fail('Format tanggal voucher struk tidak valid');
				return;
			}

			$data_update = array(
				'nm_voucher' => $nama_voucer,
				'berlaku_mulai'=> date("Y-m-d H:i:s", strtotime($berlaku_mulai)),
				'berlaku_selesai'=> date("Y-m-d H:i:s", strtotime($berlaku_selesai)),
				'jml_voucher' => $jml_voucher,
				'nilai'=> $nilai,
				'nilai_tipe'=> $nilai_tipe,
				'brand_ids'=> $brand_ids ?: null,
				'produk_ids'=> $produk_ids ?: null,
				'minimal_belanja' => $this->normalize_money_post($minimal_belanja),
				'min_get_voucher_struk' => $this->normalize_money_post($min_get_voucher_struk),
				'start_voucher_struk' => date('Y-m-d H:i:s', $ts_start),
				'end_voucher_struk' => date('Y-m-d H:i:s', $ts_end),
				'brand_ids_voucher_struk' => $brand_ids_voucher_struk ?: null,
				'produk_ids_voucher_struk' => $produk_ids_voucher_struk ?: null,
				'subkategori_ids_voucher_struk' => $subkategori_ids_voucher_struk ?: null,
				'kategori_id_voucher_struk' => $kategori_id_voucher_struk,
				'id_user' => $this->global['idUser'],
				'id_toko' => $this->global['idStore'],
			);

			$this->db->where("id_generate", $id);
			$this->db->update("voucher_generate", $data_update);
			echo $this->db->affected_rows();
			return;
		}

			$data_update = array(
			 	'nm_voucher' => $nama_voucer,
			  	'berlaku_mulai'=> date("Y-m-d H:i:s",strtotime($berlaku_mulai)),
			   	'berlaku_selesai'=> date("Y-m-d H:i:s",strtotime($berlaku_selesai)),
			    'nilai'=> $nilai,
			    'nilai_tipe'=> $nilai_tipe,
			    'brand_ids'=> $brand_ids ?: null,
			    'produk_ids'=> $produk_ids ?: null,
				'id_user' => $this->global['idUser'],
				'id_toko' => $this->global['idStore']
			);

			$data_item = array(
			  	'berlaku_mulai'=> date("Y-m-d H:i:s",strtotime($berlaku_mulai)),
			   	'berlaku_selesai'=> date("Y-m-d H:i:s",strtotime($berlaku_selesai)),
			    'nilai'=> $nilai,
			    'nilai_tipe'=> $nilai_tipe,
			    'brand_ids'=> $brand_ids ?: null,
			    'produk_ids'=> $produk_ids ?: null,
			);

			$this->db->where("id_generate",$id);
			$this->db->update("voucher_generate",$data_update);
			$affect = $this->db->affected_rows();

			$this->db->where("id_generate",$id);
			$this->db->update("voucher_item",$data_item);
			$affect = $this->db->affected_rows();
			echo $affect;
		
	}

	function hapus_voucer(){
		$id = $_POST['id'];
		$gen = $this->db->get_where("voucher_generate",array("id_generate" => $id))->row();
		$this->db->delete("voucher_generate",array("id_generate" => $id));
		$voucer = $this->db->get_where("voucher_item",array("id_generate" => $id))->row();
		$this->db->delete("voucher_item",array("id_generate" => $id));
	}

	function export_excel($id){

		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','KODE')
									  ->setCellValue('C1','NAMA VOUCHER')
									  ->setCellValue('D1','BERLAKU')
									  ->setCellValue('E1','NILAI');
			$objPHPExcel->getActiveSheet()->setCellValue('F1','TIPE NILAI');


		$this->db->select('voucher_item.*,voucher_generate.nm_voucher');
		$this->db->from('voucher_item');
		$this->db->join('voucher_generate', 'voucher_generate.id_generate = voucher_item.id_generate');
		$this->db->where('voucher_generate.id_generate',$id);
		$data_voucher = $this->db->get();

		$i=2;
		foreach($data_voucher->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValueExplicit('B'.$i,$row->id_voucher, PHPExcel_Cell_DataType::TYPE_STRING)
									  ->setCellValue('C'.$i,$row->nm_voucher)
									  ->setCellValue('D'.$i,$row->berlaku_mulai.'-'.$row->berlaku_selesai)
									  ->setCellValue('E'.$i,$row->nilai)
									  ->setCellValue('F'.$i,(isset($row->nilai_tipe) && $row->nilai_tipe === 'percent') ? 'percent' : 'rp');
		$i++; }

		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Arisal Yanuarafi")
								->setLastModifiedBy("Arisal Yanuarafi")
								->setTitle("Solusi POS | IT Solutions")
								->setSubject("Solusi POS | IT Solutions")
								->setDescription("Export Data")
								->setKeywords("office 2007 openxml php")
								->setCategory("Data SO");
	 
	     //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	 
	   	//sesuaikan headernya 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    //ubah nama file saat diunduh
	    header('Content-Disposition: attachment;filename=ExportDataVoucher.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
								
	}


	function export_html($id){

		$this->global['pageTitle'] = "Solusi POS - Cetak Voucher";

		$this->db->select('voucher_item.*,voucher_generate.nm_voucher');
		$this->db->from('voucher_item');
		$this->db->join('voucher_generate', 'voucher_generate.id_generate = voucher_item.id_generate');
		$this->db->where('voucher_generate.id_generate',$id);
		$voucher = $this->db->get();
		

		$data['voucher'] = $voucher;
		$data['id'] = $id;
		
		$this->loadViews("voucergenerate/body_voucer_cetak",$this->global,$data,"voucergenerate/footerVoucer");
		
		
	}

	private function validate_nilai_by_tipe($nilai_tipe, $nilai_raw){
		$t = trim((string)$nilai_raw);
		$normalized = str_replace(',', '.', preg_replace('/\s+/', '', $t));
		if ($normalized === '' || !is_numeric($normalized)) {
			return 'Nilai tidak valid';
		}
		$num = floatval($normalized);
		if ($nilai_tipe === 'percent') {
			if ($num > 100) {
				return 'Nilai persentase tidak boleh lebih dari 100';
			}
			if ($num < 0) {
				return 'Nilai persentase tidak boleh negatif';
			}
		}
		return null;
	}

	private function json_validation_fail($msg){
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(array('ok' => false, 'msg' => $msg));
	}

	private function validate_decimal_nonneg_optional($raw, $err_label){
		$t = trim((string)$raw);
		if ($t === '') {
			return null;
		}
		$normalized = str_replace(',', '.', preg_replace('/\s+/', '', $t));
		if ($normalized === '' || !is_numeric($normalized)) {
			return $err_label;
		}
		if (floatval($normalized) < 0) {
			return $err_label;
		}
		return null;
	}

	private function normalize_money_post($raw){
		$t = trim((string)$raw);
		if ($t === '') {
			return null;
		}
		$normalized = str_replace(',', '.', preg_replace('/\s+/', '', $t));
		return is_numeric($normalized) ? $normalized : null;
	}

	/** Parent kategori (ap_kategori.id_kategori) for voucher struk earn scope. */
	private function kategori_struk_options(){
		$this->db->select('ap_kategori.id_kategori, ap_kategori.kategori');
		$this->db->from('ap_kategori');
		$this->db->order_by('ap_kategori.kategori', 'ASC');
		$this->db->order_by('ap_kategori.id_kategori', 'ASC');
		$q = $this->db->get();
		$opts = array();
		foreach ($q->result() as $r) {
			$opts[] = array(
				'id' => (int) $r->id_kategori,
				'label' => $r->kategori,
			);
		}
		return $opts;
	}

	/** Labels: parent kategori — subkategori (ap_kategori_1.id for voucher struk earn scope). */
	private function subkategori_struk_options(){
		$this->db->select('ap_kategori_1.id, ap_kategori.kategori, ap_kategori_1.kategori_level_1');
		$this->db->from('ap_kategori_1');
		$this->db->join('ap_kategori', 'ap_kategori.id_kategori = ap_kategori_1.id_kategori');
		$this->db->order_by('ap_kategori.kategori', 'ASC');
		$this->db->order_by('ap_kategori_1.urutan', 'ASC');
		$this->db->order_by('ap_kategori_1.kategori_level_1', 'ASC');
		$q = $this->db->get();
		$opts = array();
		foreach ($q->result() as $r) {
			$opts[] = array(
				'id' => (int) $r->id,
				'label' => $r->kategori . ' - ' . $r->kategori_level_1,
			);
		}
		return $opts;
	}
}