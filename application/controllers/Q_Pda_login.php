<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Q_Pda_login extends CI_Controller{
	function __construct(){
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		$this->load->helper("url");
		$this->load->library('session');
		$this->load->database();
	}

	function auth(){
		$username = $this->input->post("username");
		$pass = $this->input->post("password");

		$password=md5($pass);
		$token = md5(uniqid());

		$where=array(
			'username' => $username,
			'password_pda' => $password
		);
		
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where($where);
		$query = $this->db->get();
		$user = $query->row();

		if(!empty($user)){
			
			$this->db->set('token',$token);
			$this->db->where($where);
			$this->db->update('users');

			$data['user'] = $user->username;
			$data['first'] = $user->first_name;
			$data['last'] = $user->last_name;
			$data['id'] = $user->id;
			$data['toko'] = $user->toko;
			$data['pesan'] = "Login berhasil.";
			$data['status'] = "success";
			$data['token'] = $token;
		}else{
			$data['pesan'] = "Login gagal, pastikan username dan password sudah benar.";
			$data['status'] = "error";
		}

		echo json_encode($data);
	}

}