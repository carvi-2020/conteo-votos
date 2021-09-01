<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class AppInterceptor {
	
	private $CI;

    function __construct() {
        $this->CI = get_instance();
    }
	
	var $dbapp;
	var $excOpciones = array('ajx', 'inicioSesion', 'cerrarSesion', 'goToLogin', 'lov'
			, 'noautorizado','cargarCatalogoProductos', 'agregarProducto', 'removerProducto', 'pblic');
	
	function inicializarVars() {
		$obj =& get_instance();
		$this->dbapp = $obj->load->database('dpr', TRUE);
	}
  
  public function validarSesion() {
  	$obj =& get_instance();
	$obj->load->library('session');
	
	if((!in_array($obj->uri->segment(2), $this->excOpciones) &&
		!in_array($obj->uri->segment(1), $this->excOpciones)) && 
		strpos('_'.str_replace('/', '', $obj->uri->segment(2)).'_', '_ajx') === false) {
			
			$condicion = 'SINSESION';
			
			$algo = $obj->session->userdata('usuario');
			if(!isset($algo) || $algo == NULL || !isset($algo['nomusuario']) || $algo['nomusuario'] == NULL || $algo['nomusuario'] == '')
				$condicion = 'SINSESION';
			else {
				$condicion = 'SESIONABIERTA';
			}
			
	  	if($condicion == 'SESIONABIERTA' && ($obj->session->userdata('4pp') == 'VOTA')) {
	  		
	  		if($obj->uri->segment(2) != 'bienvenido') {
	  			
	  		if(isset($_SESSION['menu']) && $_SESSION['menu'] != NULL && count($_SESSION['menu']) > 0) {
	  			//Validamos si la uri es de una opcion a la que tiene permiso
	  			
	  			$opcCoinci = FALSE;
	  			foreach($_SESSION['menu'] as $row) {
	  				if($opcCoinci)
						break;
					/*echo 'papa: <br />';
					echo '_'.str_replace('/', '', $row['url']).'_  y  ' . '_'.$obj->uri->segment(1) . '<br />';
					echo '_'.str_replace('/', '', $row['url']).'_  y  ' . '_'.$obj->uri->segment(2) . '<br />';*/
					
	  				if(strpos('_'.str_replace('/', '', $row['url']).'_', '_'.$obj->uri->segment(1)) !== false ||
							strpos('_'.str_replace('/', '', $row['url']).'_', $obj->uri->segment(2).'_') !== false) {
	  					$opcCoinci = TRUE;	
						break;
					}
					if(isset($row['hijos']))
					foreach($row['hijos'] as $rowSon) {
						
						/*echo 'hijo: <br />';
						echo '_'.str_replace('/', '', $rowSon['url']).'_  y  ' . '_'.$obj->uri->segment(1) . '<br />';
						echo '_'.str_replace('/', '', $rowSon['url']).'_  y  ' . '_'.$obj->uri->segment(2) . '<br />';*/
						
						if(strpos('_'.str_replace('/', '', $rowSon['url']).'_', '_'.$obj->uri->segment(1)) !== false  ||
							strpos('_'.str_replace('/', '', $rowSon['url']).'_', $obj->uri->segment(2).'_') !== false ) {
		  					$opcCoinci = TRUE;	
							break;
						} 
					}
				}
				if(!$opcCoinci) {
					
					$obj->session->set_flashdata('msgErrLog', 'Su usuario no tiene permiso para acceder a esa opci&oacute;n del sistema.');
					redirect('/login/bienvenido/NOTAUTH', 'refresh');	
					//$obj->load->view('welcome');
				}
	  		 } else {
	  			$obj->session->set_flashdata('msgErrLog', 'Su usuario no tiene permiso para acceder a esa opci&oacute;n del sistema.');
				redirect('/login/bienvenido/NOTAUTH', 'refresh');	
				//$obj->load->view('welcome');
			 }
			}
	  	} else {
	  		$obj->session->set_flashdata('msgErrLog', 'Su sesi&oacute;n ha expirado o no hab&iacute;a iniciado sesi&oacute;n en el sistema.');
			redirect('/login/goToLogin/EXPNTA', 'refresh');	
			//$obj->load->view('inicioD');
			//$obj->load->view('default');
	  	} 
  	}
  		
  }
}

/* End of file Appinterceptor.php */
/* Location: application/hooks/ */
