<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Kepala_kasir extends BaseController
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model(array('model1', 'ModelKepalaKasir', 'model_crypto'));
		$this->load->library("session");
		//error_reporting(E_ALL);ini_set('display_errors',1);
		//$this->isLoggedIn($this->global['idUser'],2,72);
	}

	function index()
	{
		$this->global['pageTitle'] = "Solusi POS - Kepala Kasir";
		$this->loadViews("kepala_kasir/sales", $this->global, NULL, "kepala_kasir/footer");
	}

	function addNewUser()
	{
		$this->global['pageTitle'] = "Solusi POS - Tambah User";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("kepala_kasir/form_sales", $this->global, null, "kepala_kasir/footer");
	}

	function datatablesUser()
	{
		$draw = $_REQUEST['draw'];
		$length = $_REQUEST['length'];
		$start = $_REQUEST['start'];
		$search = $_REQUEST['search']["value"];

		$total = $this->ModelKepalaKasir->totalUserAkif();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();

		if ($search != "") {
			$query = $this->ModelKepalaKasir->viewUser($length, $start, $search);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->ModelKepalaKasir->viewUser($length, $start, $search);
		}

		$nomor_urut = $start + 1;
		foreach ($query->result_array() as $dt) {
			if ($dt['NA'] == 'N') {
				$status = "Aktif";
			} else {
				$status = "Non Aktif";
			}
			//$barcode = '<img src="'.base_url('barcode.php?w=250&f=png&s=qr&d='.base64_encode(date('ihsdYmsH').'~'.$dt['username'].'~'.$dt['pass'])).'">';
			if (empty($dt['finger_data'])) {
				$url_register = base64_encode(base_url("kepala_kasir/register?username=" . $dt['username']));
				//$btn = '<a href="finspot:FingerspotReg;'.$url_register.'" class="btn btn-primary"> Register</a>';
			} else {
				//$btn = '<a href="finspot:FingerspotReg;'.$url_register.'" class="btn btn-primary"> Register</a>';
			}
			;
			$url = base_url("kepala_kasir/register_new");
			$btn = '<a href="fpsolusi://register?username=' . $dt['username'] . '&webhook=' . $url . '" class="btn btn-primary"> Register</a>';


			$output['data'][] = array($nomor_urut, $dt['username'], $dt['Nama'], $status, $btn, '<a href="' . base_url('kepala_kasir/editUser?id=' . $dt['username']) . '"><i class ="fa fa-pencil"></i></a>');
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function verification_new()
	{
		$result = isset($_POST['result']) ? $_POST['result'] : null;

		$dataArray2 = array(
			"username" => $_POST['username'],
			"tanggal" => date('Y-m-d H:i:s'),
			"status" => 1
		);

		//$this->ModelKepalaKasir->insertLogFinger($dataArray2);

		if (isset($_POST['username'])) {
			$encrypted = $_POST['username'];
			$username = $this->model_crypto->decrypt($encrypted);

			$username = urldecode($username);

			$dataArray2 = array(
				"username" => $username,
				"tanggal" => date('Y-m-d H:i:s'),
				"status" => 1
			);

			$this->ModelKepalaKasir->insertLogFinger($dataArray2);

			if ($username) {

			} else {
				http_response_code(400);
				echo "Missing parameters.";
				exit;
			}
		} else {
			http_response_code(400);
			echo "Missing parameters.";
			exit;
		}

		if (!empty($result) && $result == 1) {
			$dataArray = array(
				"status" => 1
			);
			$ip = $_SERVER['REMOTE_ADDR'];

			$this->ModelKepalaKasir->approve($username, $ip, $dataArray);

		} else {
			http_response_code(400); // Bad request
			echo json_encode([
				'status' => 'error',
				'message' => 'Missing required parameters.'
			]);
			exit;
		}


	}

	function verification()
	{

		$user_id = $_GET['user_id'];
		$finger = $this->ModelKepalaKasir->getUserFinger($user_id);

		echo "$user_id;" . $finger->finger_data . ";SecurityKey;10;" . base_url('kepala_kasir/process_verification') . ";" . base_url('kepala_kasir/getac') . ";extraParams";
	}
	function process_verification()
	{
		$data = explode(";", $_POST['VerPas']);
		$user_id = $data[0];
		$vStamp = $data[1];
		$time = $data[2];
		$sn = $data[3];

		$fingerData = $this->ModelKepalaKasir->getUserFinger($user_id);
		$device = $this->ModelKepalaKasir->getDeviceBySn($sn);

		$salt = md5($sn . $fingerData->finger_data . $device->vc . $time . $user_id . $device->vkey);

		if (strtoupper($vStamp) == strtoupper($salt)) {

			$dataArray = array(
				"status" => 1
			);
			$ip = $_SERVER['REMOTE_ADDR'];

			$this->ModelKepalaKasir->approve($user_id, $ip, $dataArray);

			$dataArray2 = array(
				"vstamp" => $vStamp,
				"salt" => $salt,
				"tanggal" => date('Y-m-d H:i:s'),
				"status" => 1
			);

			//$this->ModelKepalaKasir->insertLogFinger($dataArray2);

		} else {

			$msg = "Parameter invalid..";
			$dataArray = array(
				"vstamp" => $vStamp,
				"salt" => $salt,
				"tanggal" => date('Y-m-d H:i:s')
			);

			// $this->ModelKepalaKasir->insertLogFinger($dataArray);

			echo base_url('kepala_kasir/messages?msg=' . $msg);

		}
	}

	function register_new()
	{
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
		$fingerprint1 = isset($_GET['fingerprint1']) ? $_GET['fingerprint1'] : null;
		$fingerprint2 = isset($_GET['fingerprint2']) ? $_GET['fingerprint2'] : null;

		//file_put_contents("webhook_log.txt", date("Y-m-d H:i:s") . PHP_EOL."{$fingerprint1}" . PHP_EOL, FILE_APPEND);

		if (!empty($username) && !empty($fingerprint1) && !empty($fingerprint2)) {
			$dataArray = array(
				"fingerprint1" => $fingerprint1,
				"fingerprint2" => $fingerprint2,
				"LoginEdit" => $this->global['idUser'],
				"TanggalEdit" => date('Y-m-d H:i:s')
			);

			$this->ModelKepalaKasir->editUser($username, $dataArray);
		} else {
			http_response_code(400);
			echo "Missing parameters.";
			exit;
		}
	}
	function register()
	{
		if (isset($_GET['username']) && !empty($_GET['username'])) {
			$user_id = $_GET['username'];

			echo "$user_id;SecurityKey;15;" . base_url('kepala_kasir/process_register') . ";" . base_url('kepala_kasir/getac');
		}
	}
	function process_register()
	{
		if (isset($_POST['RegTemp']) && !empty($_POST['RegTemp'])) {

			$data = explode(";", $_POST['RegTemp']);
			$vStamp = $data[0];
			$sn = $data[1];
			$user_id = $data[2];
			$regTemp = $data[3];

			//$device = getDeviceBySn($sn);

			$device = $this->ModelKepalaKasir->getDeviceBySn($sn);
			$salt = md5($device->ac . $device->vkey . $regTemp . $sn . $user_id);

			if (strtoupper($vStamp) == strtoupper($salt)) {

				$username = $user_id;

				$dataArray = array(
					"finger_data" => $regTemp,
					"LoginEdit" => $this->global['idUser'],
					"TanggalEdit" => date('Y-m-d H:i:s')
				);

				$this->ModelKepalaKasir->editUser($user_id, $dataArray);

				echo "empty";

			} else {
				$msg = "Parameter invalid..<br>SN:" . $sn . "<br>SALT:" . $salt . "<br>VSTAMP:" . $vStamp;
				echo base_url('kepala_kasir/messages?msg=' . $msg);
			}
		}
	}

	function getac()
	{
		$data = $this->ModelKepalaKasir->getDeviceAcSn($_GET['vc']);

		echo $data->ac . $data->sn;
	}

	function messages()
	{
		if (isset($_GET['msg']) && !empty($_GET['msg'])) {

			echo $_GET['msg'];

		}
	}

	function editUser()
	{
		$id = $this->input->get("id");
		$data['user_approver'] = $this->db->get_where("user_approver", array("username" => $id))->row();
		$data['brand'] = $this->db->get("brand")->result();
		$this->global['pageTitle'] = "Solusi POS - Edit User";
		//$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("kepala_kasir/editUser", $this->global, $data, "kepala_kasir/footer");
	}

	function insertUser()
	{
		$Nama = $_POST['Nama'];
		$username = $_POST['username'];
		$pass = md5($_POST['pass']);
		$LoginBuat = $this->global['idUser'];

		$dataArray = array(
			"Nama" => $Nama,
			"username" => $username,
			"pass" => $pass,
			"LoginBuat" => $LoginBuat,
			"TanggalBuat" => date('Y-m-d H:i:s')
		);

		$this->ModelKepalaKasir->insertUser($dataArray);
	}

	function cekUser()
	{
		$username = $_POST['username'];

		$cekUser = $this->ModelKepalaKasir->cekUserExist($username);

		if ($cekUser > 0) {
			echo 0;
		} else {
			echo 1;
		}
	}

	function editUserSQL()
	{
		$Nama = $_POST['Nama'];
		$username = $_POST['username'];
		$pass = md5($_POST['pass']);
		$LoginEdit = $this->global['idUser'];

		$dataArray = array(
			"username" => $username,
			"Nama" => $Nama,
			"pass" => $pass,
			"LoginEdit" => $LoginEdit,
			"TanggalEdit" => date('Y-m-d H:i:s')
		);

		$this->ModelKepalaKasir->editUser($username, $dataArray);
	}


	function spinner()
	{
		echo "<img src='" . base_url('assets/loading.gif') . "'/>";
	}
}