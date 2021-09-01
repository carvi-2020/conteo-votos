<?php 
class Login extends MY_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('login_model');
	}
	
	function goToLogin($data = NULL) {
		$this->load->view('inicioD', $data);
		//$this->load->view('welcome');
	}
	
	function bienvenido() {
		$this->load->view('welcome');
	}
	
	function noautorizado() {
		$this->load->view('noautorizado');
	}
	
	function inicioSesion() {
		$data = array();
		$this->load->library('encrypt');
		$this->load->library('session');
		$this->load->library('cart');
		
		//echo $this->encrypt->sha1('admin');
		//Validamos la informacion de entrada
		if($this->_validate() != false) {
			//Validamos si existe el usuario
			
			$dtUsuario = $this->login_model->verificarUsuario(array($_POST['_usr24'], $this->encrypt->sha1($_POST['_pwd24'])));
			if(isset($dtUsuario) && $dtUsuario['nomusuario'] == $_POST['_usr24']) {
				//Verificamos que este activo el usuario y que no este bloqueado
				if($dtUsuario['estado']!='BLQ' && $dtUsuario['estado']!='INA') {
					//Validamos si tiene un rol de usuario y que este activo
					$menuUsuario = $this->login_model->getMenuUsuario($dtUsuario['idusr']);
					if(isset($menuUsuario) && count($menuUsuario) > 0) {
						//Seteamos los datos en sesion y preparamos el acceso
							$this->session->set_userdata('4pp', 'VOTA');
							$this->session->set_userdata('usuario', $dtUsuario);
							$_SESSION['menu'] = $menuUsuario;
							
							//Inicializando el carrito de compras
							//Las primeras 4 opciones son obligatorias
							/*$data = array(
				               'id'      => 'sku_123ABC',
				               'qty'     => 1,
				               'price'   => 39.95,
				               'name'    => 'T-Shirt',
				               'options' => array('Size' => 'L', 'Color' => 'Red'),
				               'moreOpt'    => 'More Option'
				            );
							$this->cart->insert($data);*/ 
							
						//Actualizamos la ultima fecha y hora de acceso y reseteamos los intentos de login
						$this->login_model->updIngresoUsr(array('idusr' => $dtUsuario['idusr']));
						
						$this->bienvenido();
					} else {
						$data['msgMtto'] = 'El usuario no tiene asociado ningun rol de usuario o el rol no tiene ninguna opcion de menu asociada.';
						$data['msgType'] = 'ERR';
						$this->load->view('inicioD', $data);
					}
				} else {
					$data['msgMtto'] = 'El usuario ingresado actualmente est&aacute; bloqueado o inactivo.';
					$data['msgType'] = 'ERR';
					$this->load->view('inicioD', $data);
				}
			} else {
				$data['msgMtto'] = 'El nombre o clave de usuario ingresados son incorrectos.';
				$data['msgType'] = 'ERR';
				$this->load->view('inicioD', $data);
			}
		} else 
			$this->load->view('inicioD', array());
	}

	function cerrarSesion($dataSn = NULL) {
		//session_destroy();
		$this->session->unset_userdata('usuario');
		$this->session->unset_userdata('menu');
		$this->session->unset_userdata('4pp');
		$this->session->sess_destroy();
		if($dataSn == NULL) {
			$data['msgMtto'] = 'Ha finalizado la sesi&oacute;n en el sistema exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data = $dataSn;
		}
		 
		$this->load->view('inicioD', $data);
	}
	
	function _validate() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('_usr24', 'Nombre de usuario', 'required');
		$this->form_validation->set_rules('_pwd24', 'Clave de acceso', 'required');
		
		return $this->form_validation->run();
	}
	
}

/* End of file roles.php */
/* Location: application/controllers/ */
