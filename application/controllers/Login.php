<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->model(array("model1","modelPublic","model_crypto"));
		$this->load->library("ion_auth");

		// if($this->ion_auth->logged_in()){
	    //   redirect('welcome_page');
	    // }
	}

	function index(){
		$this->session->sess_destroy();
		$data['store'] = $this->db->get("ap_store");
		$this->load->view("login",$data);
	}

	function generate_uid_new(){
		$user_id 	= $_POST['user_id'];
		$finger		= $this->modelPublic->getUserFingerNew($user_id);
		$webhook	= $this->model_crypto->encrypt(base_url('login/verification_new'));
		
		$user = $this->model_crypto->encrypt($user_id);
		$base = base64_encode($user_id);

		// Buat array response
		$response = [
			'fingerprint' => $finger->fingerprint1,
			'webhook' => $webhook,
			'username' => $user,
			'base'	=> $base
		];

		// Kirim ke AJAX
		echo json_encode($response);
	}
	function verification_new(){
		if (isset($_POST['username'])) {
			$encrypted = $_POST['username'];
			$username = $this->model_crypto->decrypt($encrypted);

			if ($username) {
				$username = urldecode($username);
				
			} else {
				http_response_code(400);
				echo "Missing parameters.";
				exit;
			}
		}else{
			http_response_code(400);
			echo "Missing parameters.";
			exit;
		}

		$ip = $_SERVER['REMOTE_ADDR'];
		$dataArray = array(
				"ip_address"	=> $ip,
				"last_login"	=> strtotime("now")
			);

		$this->modelPublic->updateUser($username,$dataArray);
		
		echo base_url('login/messages?msg=done&m='.base64_encode($username));

	}

	function generate_uid(){
		$user_id 	= $_POST['user_id'];
		$value = base64_encode(base_url('login/verification?user_id='.$user_id));
		$respon = array("path" => $value);
		echo json_encode($respon);
	}

	function verification(){
		
		$user_id 	= $_GET['user_id'];
		$finger		= $this->modelPublic->getUserFinger($user_id);

		echo "$user_id;".$finger->finger_data.";SecurityKey;10;".base_url('login/process_verification').";".base_url('login/getac').";extraParams";
	}
	function process_verification(){
		$data 		= explode(";",$_POST['VerPas']);
		$user_id	= $data[0];
		$vStamp 	= $data[1];
		$time 		= $data[2];
		$sn 		= $data[3];

		$fingerData = $this->modelPublic->getUserFinger($user_id);
		$device 	= $this->modelPublic->getDeviceBySn($sn);

		$salt = md5($sn.$fingerData->finger_data.$device->vc.$time.$user_id.$device->vkey);

		if (strtoupper($vStamp) == strtoupper($salt)) {
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$dataArray = array(
				"ip_address"	=> $ip,
				"last_login"	=> strtotime("now")
			);

			$this->modelPublic->updateUser($user_id,$dataArray);
			
			echo base_url('login/messages?msg=done&m='.base64_encode($user_id));

		} else {

			$msg = "Data fingerprint tidak cocok..";

			echo base_url('login/messages?msg='.$msg);

		}
	}
	function register(){
		if (isset($_GET['username']) && !empty($_GET['username'])) {
			$user_id 	= $_GET['username'];

			echo "$user_id;SecurityKey;15;".base_url('login/process_register').";".base_url('login/getac');
		}
	}
	function register_new(){
		if (isset($_GET['data'])) {
			$encrypted = $_GET['data'];
			$decryptedJson = $this->model_crypto->decrypt($encrypted);
			$data = json_decode($decryptedJson, true);

			if ($data) {
				$username = $data['username'];
				$username = urldecode($username);
			} else {
				http_response_code(400);
				echo "Missing parameters.";
				exit;
			}
		}
		$dataArray2 = array(
					"username"	=> $username,
					"tanggal"	=> date('Y-m-d H:i:s'),
					"status"	=> 1
			);
		$this->db->insert("log_finger",$dataArray2);
		$fingerprint1 = isset($_GET['fingerprint1']) ? $_GET['fingerprint1'] : null;
		$fingerprint2 = isset($_GET['fingerprint2']) ? $_GET['fingerprint2'] : null;

		//file_put_contents("webhook_log.txt", date("Y-m-d H:i:s") . PHP_EOL."{$fingerprint1}" . PHP_EOL, FILE_APPEND);

		if(!empty($username) && !empty($fingerprint1) && !empty($fingerprint2)){
			$dataArray = array(
									"fingerprint1"	=> $fingerprint1,
									"fingerprint2"	=> $fingerprint2
							);

			$this->modelPublic->updateUser($username,$dataArray);
		}else{
			http_response_code(400);
			echo "Missing parameters.";
			exit;
		}
	}
	function process_register(){
		if (isset($_POST['RegTemp']) && !empty($_POST['RegTemp'])) {

		$data 		= explode(";",$_POST['RegTemp']);
		$vStamp 	= $data[0];
		$sn 		= $data[1];
		$user_id	= $data[2];
		$regTemp 	= $data[3];

		//$device = getDeviceBySn($sn);

		$device = $this->modelPublic->getDeviceBySn($sn);
		$salt = md5($device->ac.$device->vkey.$regTemp.$sn.$user_id);

		if (strtoupper($vStamp) == strtoupper($salt)) {

			$dataArray = array(
									"finger_data"	=> $regTemp
							);

			//$this->modelPublic->editUser($user_id,$dataArray);
			$this->modelPublic->updateUser($user_id,$dataArray);

			echo base_url('setting/user?msg=done');

		} else {
			$msg = "Parameter invalid..";
			echo base_url('setting/user?msg='.$msg);
		}
		}
	}
	function getac(){
		$data = $this->modelPublic->getDeviceAcSn($_GET['vc']);
	
		echo $data->ac.$data->sn;
	}
	function messages(){
		if (isset($_GET['msg']) && !empty($_GET['msg'])) {
	
			//echo $_GET['msg'];

			if(!empty($_GET['m']) && $_GET['msg']=='done'){
				$identity = base64_decode($_GET['m']);
				$query = $this->db->select('password, active, last_login, ip_address')
						  ->where('username', $identity)
						  ->limit(1)
						  ->order_by('id', 'desc')
						  ->get('users');
				$ip = $_SERVER['REMOTE_ADDR'];
				$time = strtotime("20 seconds ago");

				$user = $query->row();
				if ($query->num_rows() === 1 && $ip==$user->ip_address && $time < $user->last_login)
				{
					
					$loginProcess = $this->ion_auth->login($identity,$user->password,FALSE,TRUE);
					if($loginProcess){
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("welcome_page","refresh");
					}else {
						$this->session->set_flashdata('message', "<div class='alert alert-danger' role='alert'><span class='alert_icon lnr lnr-cross'></span><strong>".$this->ion_auth->errors()."</div>");
						redirect("login");
					}
				}else {
					$this->session->set_flashdata('message', "<div class='alert alert-danger' role='alert'><span class='alert_icon lnr lnr-cross'></span><strong>Parameter tidak valid!</div>");
					redirect("login");
				}
			}else {
				$this->session->set_flashdata('message', "<div class='alert alert-danger' role='alert'><span class='alert_icon lnr lnr-cross'></span><strong>".$_GET['msg']."</div>");
				redirect("login");
			}

		}
	}

	function auth(){
		$identity = $this->input->post("username");
		$password = $this->input->post("password");
		$remember = NULL;

		$loginProcess = $this->ion_auth->login($identity,$password,$remember);

		if($loginProcess){
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("welcome_page","refresh");
		} else {
			$this->session->set_flashdata('message', "<div class='alert alert-danger' role='alert'><span class='alert_icon lnr lnr-cross'></span><strong>".$this->ion_auth->errors()."</div>");
			redirect("login");
		}
	}

	function logout(){
		$this->session->sess_destroy();
		redirect('login');
	}
}