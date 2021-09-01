<?php
class Rol extends MY_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('rol_model');
	}
	
	function lstRoles($numRow = 0, $dataCur = NULL) {
		$data = array();
		if(isset($dataCur)) 
			$data = $dataCur;
		
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
			array('_fltNom' => array('fld' => 'nombre', 'typ' => 'str'), 
				'_fltEst' => array('fld' => 'estado', 'typ' => 'str', 'tpb' => 'EXA')), $numRow, $data);
		
		$rs = $this->rol_model->lstRoles($filtros);
		$this->load->library('tablepaging');
		$data['lstRoles'] = $this->tablepaging->getTablaPaginada('rol/lstRoles', $rs, 10, $numRow);
		$this->load->view('seguridad/rol/lst_roles', $data);
	}
	
	function insRol() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != false) {
			if($this->rol_model->insRol()) {
				$data['msgMtto'] = 'El rol ' . $_POST['nombre'] . ' ha sido guardado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El rol ' . $_POST['nombre'] . ' no pudo ser guardado.';
				$data['msgType'] = 'ERR';
			}

			$this->lstRoles(0, $data);
		} else
			$this->load->view('seguridad/rol/det_rol', $data);
	}
	
	function loadRol() {
		$data['idrol'] = '';
		$data['nombre'] = '';
		$data['estado'] = '';
		$data['edit_emb'] = '';
		$data['edit_doc'] = '';
		$data['edit_arr'] = '';
		if(isset($_POST['idrol']) && $_POST['idrol'] != '')
			$data = $this->rol_model->loadRol($_POST['idrol']);
		$data['accion'] = $_POST['accion'];
		$this->load->view('seguridad/rol/det_rol', $data);
	}
	
	function loadOpcRol($dataCur = NULL) {
		$data = array();
		if(isset($dataCur)) 
			$data = $dataCur;
		else {
			$data['idrol'] = $_POST['idrol'];
			$data['nombre'] = $_POST['nombre'];
		}
		
		//Cargamos la lista de opciones seleccionadas y las que no estan seleccionadas
		$this->load->model('opcionmenu_model');
		$lstRes = $this->opcionmenu_model->lstOpcionesMenuAsoc($data);
		$data['lstOpcionesMenu'] = $lstRes; 
		$data['accion'] = $_POST['accion'];
		$this->load->view('seguridad/rol/opciones_rol', $data);
	}
	
	function _validate() {
		$this->load->library('form_validation');
		if($_POST['accion'] === 'UPD')
			$this->form_validation->set_rules('idrol', 'ID de rol', 'required');
		$this->form_validation->set_rules('nomSistema', 'Nombre del sistema', '');
		$this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[3]');
		$this->form_validation->set_rules('estado', 'Estado', 'required');
		$this->form_validation->set_rules('edit_emb', 'Embarcacion', '');
		$this->form_validation->set_rules('edit_doc', 'Documentos FAL', '');
		$this->form_validation->set_rules('edit_arr', 'Arribos', '');
		return $this->form_validation->run();
	}
	
	function updRol() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != false) {
			if($this->rol_model->updRol() != FALSE) {
				$data['msgMtto'] = 'El rol ' . $_POST['nombre'] . ' ha sido actualizado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El rol ' . $_POST['nombre'] . ' no pudo ser actualizado.';
				$data['msgType'] = 'ERR';
			}

			$this->lstRoles(0, $data);
		} else 
			$this->load->view('seguridad/rol/det_rol', $data);
	}
	
	function updOpcRol() {
		$this->load->model('opcionmenu_model');
		$data = array();
		$data['idrol'] = $_POST['idrol'];
		$data['nombre'] = $_POST['nombre'];
		$data['idopm'] = $_POST['idopm'];
		$data['asociado'] = $_POST['asociado'];
		if($this->opcionmenu_model->updOpcRol($data) != FALSE) {
			$data['msgMtto'] = 'Las opciones de men&uacute; del rol han sido actualizadas exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'Las opciones de men&uacute; del rol no pudieron ser actualizadas.';
			$data['msgType'] = 'ERR';
		}
		
		$this->loadOpcRol($data);
	}
	
	function delRol() {
		$data['accion'] = $_POST['accion'];
		if($this->rol_model->delRol()) {
			$data['msgMtto'] = 'El rol ' . $_POST['nombre'] . ' ha sido eliminado exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'El rol ' . $_POST['nombre'] . ' no pudo ser eliminado.';
			$data['msgType'] = 'ERR';
		}
		
		$this->lstRoles(0, $data);
	}
}

/* End of file rol.php */
/* Location: application/controllers/ */
