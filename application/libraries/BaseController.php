<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * Class : BaseController
 * Base Class to control over all the classes
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class BaseController extends CI_Controller {
	protected $global = array ();
	
	/**
	 * Takes mixed data and optionally a status code, then creates the response
	 *
	 * @access public
	 * @param array|NULL $data
	 *        	Data to output to the user
	 *        	running the script; otherwise, exit
	 */
	/**
     * This function used to load views
     * @param {string} $viewName : This is view name
     * @param {mixed} $headerInfo : This is array of header information
     * @param {mixed} $pageInfo : This is array of page information
     * @param {mixed} $footerInfo : This is array of footer information
     * @return {null} $result : null
     */

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library("ion_auth");
		$this->load->model('model1');
		$idUser = $this->ion_auth->user()->row()->id;
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->global['permitAccess'] = $this->model1->permitAccess($idUser);
		$this->global['permitAccessSub'] = $this->model1->permitAccessSub($idUser);
		$this->global['idUser'] = $idUser;
		$this->global['footer'] = $this->model1->footertext();
		$this->global['idStore'] = $this->model1->getIdStore($idUser);
		$this->global['isAdmin'] = $this->model1->getIsAdmin($idUser);
		$this->global['isSuperadmin'] = $this->model1->getIsSuperadmin($idUser);
	}

	/**
	 * This function is used to load the set of views
	 */
	function accessDenied(){
		$this->global ['pageTitle'] = 'Solusinformatika.com POS Inventory - Access Denied';

		$this->global['navigation'] = $this->model1->callNavigation();
		
		$this->load->view ('navigation', $this->global);
		$this->load->view ('access_denied');
		$this->load->view ('footer_empty');
	}

	/**
	 * This function is used to logged out user from system
	 */

	/**
	 * This function used to check the user is logged in or not
	 */
	function isLoggedIn($idUser,$type,$code){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($idUser,$type,$code);
			if($cekMyAccess < 1){
				$this->accessDenied();
			}
		}
	}



    function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerName = ""){

        $this->load->view('navigation',$headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view($footerName);
    }

    function permitAccess(){
    	$idUser = $this->ion_auth->user()->row()->id;
    	return $this->model1->permitAccess($idUser);
    }

	/**
	 * This function is used to check the access
	 */
	function isAdmin() {
		if ($this->role != ROLE_ADMIN) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This function is used to check the access
	 */
	function isTicketter() {
		if ($this->role != ROLE_ADMIN || $this->role != ROLE_MANAGER) {
			return true;
		} else {
			return false;
		}
	}

	
	function logout() {
		$this->session->sess_destroy ();

		redirect ( 'login' );
	}

}
