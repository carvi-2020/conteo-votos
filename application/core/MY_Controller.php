<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->library('session');
		session_start();
	}
	
	var $excOpciones = array('ajx', 'inicioSesion', 'cerrarSesion', 'goToLogin', 'lov', 'noautorizado');
	
	function in_array_match($regex, $array) {
	    if (!is_array($array))
	        trigger_error('Argument 2 must be array');
	    foreach ($array as $v) {
	        $match = strpos($regex, $v) ;
	        if ($match != false && $match >= 0) {
	            return true;
				break;
	        }
	    }
	    return false;
	}
	

}