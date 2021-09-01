<?php
class Usuario extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('usuario_model');
		$this->load->library('session');
	}
	
	function lstUsuarios($numRow = 0, $dataCur = NULL) {
		$data = isset($dataCur)?$dataCur:array();
		
		/* $this->load->library('encrypt');
		$encrypted_string = $this->encrypt->sha1('admin');
		$data['llave'] = $encrypted_string;*/
		
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
		array('_fltNom' => array('fld' => 'idusr', 'typ' => 'str'),
			'_fltIso' => array('fld' => 'nomusuario', 'typ' => 'str', 'tpb' => 'LKF'),
			'_fltCap' => array('fld' => 'nomcompleto', 'typ' => 'str', 'tpb' => 'LKF')
				), $numRow, $data);
				
		$rs = $this->usuario_model->lstUsuarios($filtros);
		$this->load->library('tablepaging');
		$data['lstUsuarios'] = $this->tablepaging->getTablaPaginada('usuario/lstUsuarios', $rs, 10, $numRow);
		$this->load->view('seguridad/usuario/lst_usuarios', $data);
	}
	
	function insUsuario() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != false) {
			if($this->usuario_model->insUsuario()) {
				$data['msgMtto'] = 'El usuario ' . $_POST['nomusuario'] . ' ha sido guardado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El usuario ' . $_POST['nomusuario'] . ' no pudo ser guardado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstUsuarios(0, $data);
		} else
			$this->load->view('seguridad/usuario/det_usuario', $data);
	}
	
	function loadCambioClave() {
		$this->load->view('seguridad/usuario/cambio_clave');
	}
	
	function updClave() {
		if($this->_validateClv() != FALSE) {
			$this->load->library('encrypt');
			$this->load->model('login_model');
			$dtUsr = $this->session->userdata('usuario');
			$data = array();
			$data['idusr'] = $dtUsr['idusr'];
			$data['clave'] = $this->encrypt->sha1($_POST['clave_nva']);
			
			//Verificamos primero si la clave es correcta
			$dtUsuario = $this->login_model->verificarUsuario(array($dtUsr['nomusuario'], $this->encrypt->sha1($_POST['clave_act'])));
			
			if(isset($dtUsuario) && $dtUsuario['nomusuario'] == $dtUsr['nomusuario']) {
				if($this->usuario_model->cambiarClaveUsr($data) != FALSE) {
					$data['msgMtto'] = 'La contrase&ntilde;a ha sido cambiada exitosamente.';
					$data['msgType'] = 'MSG';
				} else {
					$data['msgMtto'] = 'La contrase&ntilde;a no pudo ser cambiada.';
				$data['msgType'] = 'ERR';
				}
			} else {
				$data['msgMtto'] = 'La contrase&ntilde;a actual ingresada no es v&aacute;lida.';
				$data['msgType'] = 'ERR';
			}
		}
		
		$this->load->view('seguridad/usuario/cambio_clave', $data);
	}
	
	function loadUsuario() {
		$data['idusr'] = '';
		$data['nomusuario'] = '';
		$data['clave'] = '';
		$data['nomcompleto'] = '';
		$data['estado'] = '';
		$data['nomitt'] = '';
		$data['intentoslogin'] = '';
		$data['ultimologin'] = '';
		$data['idrol'] = '';
		$data['nomrol'] = '';
		if(isset($_POST['idusr']))
			$data = $this->usuario_model->loadUsuario($_POST['idusr']);
		
		if(isset($_POST['accion']))
			$data['accion'] = $_POST['accion'];
		else
			$data['accion'] = 'INS';
		$this->load->view('seguridad/usuario/det_usuario', $data);
	}
	
	function _validateClv() {
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('clave_act', 'contrase&ntilde;a actual', 'required|max_length[20]');
		$this->form_validation->set_rules('clave_nva', 'nueva contrase&ntilde;a', 'required|max_length[20]');
		$this->form_validation->set_rules('clave_nva_compr', 'confirmaci&oacute;n de contrase&ntilde;a', 'required|max_length[20]');
		return $this->form_validation->run();
	}
	
	function _validate() {
		$this->load->library('form_validation');
		if($_POST['accion'] === 'UPD')
			$this->form_validation->set_rules('idusr', 'ID de usuario', 'required');
		if($_POST['accion'] === 'INS')	
			$this->form_validation->set_rules('clave', 'clave', 'required|min_length[6]');
			
		$this->form_validation->set_rules('nomusuario', 'nombre de usuario', 'required|max_length[20]');
		$this->form_validation->set_rules('estado', 'Estado', 'required');
		$this->form_validation->set_rules('nomcompleto', 'nombre completo', '');
		$this->form_validation->set_rules('nomrol', 'nombre del rol', '');
		$this->form_validation->set_rules('idrol', 'ID de rol', '');
		//$this->form_validation->set_rules('nomitt', 'instituci&oacute;n', 'required');
		return $this->form_validation->run();
	}
	
	function updUsuario() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != false) {
			if($this->usuario_model->updUsuario()) {
				$data['msgMtto'] = 'El usuario ' . $_POST['nomusuario'] . ' ha sido actualizado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El usuario ' . $_POST['nomusuario'] . ' no pudo ser actualizado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstUsuarios(0, $data);
		} else 
			$this->load->view('seguridad/usuario/det_usuario', $data);
	}
	
	function delUsuario() {
		$data['accion'] = $_POST['accion'];
		if($this->usuario_model->delUsuario()) {
			$data['msgMtto'] = 'El usuario ' . $_POST['nomusuario'] . ' ha sido eliminado exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'El usuario ' . $_POST['nomusuario'] . ' no pudo ser eliminado.';
			$data['msgType'] = 'ERR';
		}
		$this->lstUsuarios(0, $data);
	}
}

/* End of file usuario.php */
/* Location: application/controllers/ */
