<?php
class Lov extends MY_Controller {
		
	function __construct() {
		parent::__construct();	
	}
	
	var $data = array();
	
	function inicializarLOV($libLOV) {
		if(isset($_GET['objs'])) 
			$this->session->set_flashdata('objs', $_GET['objs']);
		else if($this->session->flashdata('objs') != null) 
			$this->session->keep_flashdata('objs');
			
		if(isset($_GET['jsFunction'])) 
			$this->session->set_flashdata('jsFunction', $_GET['jsFunction']);
		else if($this->session->flashdata('jsFunction') != null) 
			$this->session->keep_flashdata('jsFunction');
		
		$data = array();
		$this->load->library('tablepaging');
		$this->load->model($libLOV);
	}
	
	function lovRoles($numRow = 0) {
		$this->inicializarLOV('rol_model');
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
			array('_fltNom' => array('fld' => 'nombre', 'typ' => 'str')), $numRow, $data);
		$rs = $this->rol_model->lstRolesEst($filtros, 'ACT');
		$data['lstRoles'] = $this->tablepaging->getTablaPaginada('lov/lovRoles', $rs, 10, $numRow);
		$this->load->view('lovs/lst_roles', $data);
	}
	
	function lovSucursales($numRow = 0) {
		$this->inicializarLOV('sucursal_model');
		$rs = $this->sucursal_model->lstSucursales();
		$data['lstSucursales'] = $this->tablepaging->getTablaPaginada('lov/lovSucursales', $rs, 10, $numRow);
		$this->load->view('lovs/lst_sucursales', $data);
	}

	function lovInstituciones($numRow = 0) {
		$this->inicializarLOV('institucion_model');
		$rs = $this->institucion_model->lstInstituciones();
		$data['lstInstituciones'] = $this->tablepaging->getTablaPaginada('lov/lovInstituciones', $rs, 10, $numRow);
		$this->load->view('lovs/lst_instituciones', $data);
	}
	
	
	function lovOpcionesMenuPapa($numRow = 0) {
		$this->inicializarLOV('opcionmenu_model');
		$rs = $this->opcionmenu_model->lstOpcionesMenuPapa();
		$data['lstOpcionesPapa'] = $this->tablepaging->getTablaPaginada('lov/lovOpcionesMenuPapa', $rs, 10, $numRow);
		$this->load->view('lovs/lst_opcionespapa', $data);
	}
	
	function lovClientes($numRow = 0) {
		$this->inicializarLOV('cliente_model');
		$rs = $this->cliente_model->cargarLista();
		$data['resultList'] = $this->tablepaging->getTablaPaginada('lov/lovClientes', $rs, 10, $numRow);
		$this->load->view('lovs/lst_clientes', $data);
	}
		
}

/* End of file lov.php */
/* Location: application/controllers/ */
