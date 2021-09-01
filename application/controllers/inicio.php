<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$arrUsr = (array)$this->session->userdata('usuario');
		if(isset($arrUsr) && $arrUsr != NULL) 
			$this->load->view('welcome');
		else
			$this->load->view('inicioD');
	}
}

/* End of file inicio.php */
/* Location: ./application/controllers/welcome.php */