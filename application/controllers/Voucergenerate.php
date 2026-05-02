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
		$this->loadViews("voucergenerate/body_voucer",$this->global,NULL,"voucergenerate/footerVoucer");
	}

	function data_voucer(){
		
		$data['voucer'] = $this->db->get("voucher_generate");
		$this->load->view("voucergenerate/data_voucer",$data);
	}


	function add_voucer_sql(){

		$nama_voucer 		= $_POST['nama_voucer'];
		$berlaku_mulai 			= $_POST['berlaku_mulai'];
		$berlaku_selesai 		= $_POST['berlaku_selesai'];
		$jml_voucher 				= $_POST['jumlah'];
		$nilai 			= $_POST['nilai'];

			$huruf="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	        $id=substr(str_shuffle($huruf), 0, 8).date('s');

			$data_insert = array(
					'id_generate'=>$id,
				 	'nm_voucher' => $nama_voucer,
				  	'berlaku_mulai'=> date("Y-m-d H:i:s",strtotime($berlaku_mulai)),
				   	'berlaku_selesai'=> date("Y-m-d H:i:s",strtotime($berlaku_selesai)),
				    'jml_voucher'=> $jml_voucher,
				    'nilai'=> $nilai,
					'id_user' => $this->global['idUser'],
					'id_toko' => $this->global['idStore']);

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
				);

				$this->db->insert("voucher_item",$data_item);
				$affect = $this->db->affected_rows();
			} 

			echo $affect;
		
	}

	function form_edit_voucer(){
		$id = $_POST['id'];
		$data['voucer'] = $this->db->get_where("voucher_generate",array("id_generate" => $id));
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
		$nama_voucer 		= $_POST['nama_voucer'];
		$berlaku_mulai 			= $_POST['berlaku_mulai'];
		$berlaku_selesai 		= $_POST['berlaku_selesai'];
		$jml_voucher 				= $_POST['jumlah'];
		$nilai 			= $_POST['nilai'];

	
			$data_update = array(
			 	'nm_voucher' => $nama_voucer,
			  	'berlaku_mulai'=> date("Y-m-d H:i:s",strtotime($berlaku_mulai)),
			   	'berlaku_selesai'=> date("Y-m-d H:i:s",strtotime($berlaku_selesai)),
			    'nilai'=> $nilai,
				'id_user' => $this->global['idUser'],
				'id_toko' => $this->global['idStore']
			);

			$data_item = array(
			  	'berlaku_mulai'=> date("Y-m-d H:i:s",strtotime($berlaku_mulai)),
			   	'berlaku_selesai'=> date("Y-m-d H:i:s",strtotime($berlaku_selesai)),
			    'nilai'=> $nilai,
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
									  ->setCellValue('E'.$i,$row->nilai);
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
}