<?php
class OpcionMenu extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('opcionmenu_model');
	}
	
	function lstOpcionesMenu($numRow = 0, $dataCur = NULL) {
		$data = isset($dataCur)?$dataCur:array();
		
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
		array('_fltNom' => array('fld' => 'idopm', 'typ' => 'str'),
			'_fltIso' => array('fld' => 'etiqueta', 'typ' => 'str', 'tpb' => 'LKF'),
			'_fltCap' => array('fld' => 'url', 'typ' => 'str', 'tpb' => 'LKF')
				), $numRow, $data);
				
		$rs = $this->opcionmenu_model->lstOpcionesMenu($filtros);
		$this->load->library('tablepaging');
		$data['lstOpcionesMenu'] = $this->tablepaging->getTablaPaginada('opcionmenu/lstOpcionesMenu', $rs, 10, $numRow);
		$this->load->view('seguridad/opcionmenu/lst_opcionesmenu', $data);
	}
	
	function insOpcionMenu() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {
			if($this->opcionmenu_model->insOpcionMenu() != FALSE) {
				$data['msgMtto'] = 'La opci&oacute;n de men&uacute; ' . $_POST['etiqueta'] . ' ha sido guardada exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'La opci&oacute;n de men&uacute; ' . $_POST['etiqueta'] . ' no pudo ser guardada.';
				$data['msgType'] = 'ERR';
			}
			$this->lstOpcionesMenu(0, $data);
		} else
			$this->load->view('seguridad/opcionmenu/det_opcionmenu', $data);
	}
	
	function loadOpcionMenu() {
		$data['idopm'] = '';
		$data['etiqueta'] = '';
		$data['url'] = '';
		if(isset($_POST['idopm']))
			$data = $this->opcionmenu_model->loadOpcionMenu($_POST['idopm']);
		
		if(isset($_POST['accion']))
			$data['accion'] = $_POST['accion'];
		else
			$data['accion'] = 'INS';
		$this->load->view('seguridad/opcionmenu/det_opcionmenu', $data);
	}
	
	function _validate() {
		$this->load->library('form_validation');
		if($_POST['accion'] === 'UPD')
			$this->form_validation->set_rules('idopm', 'ID de la opci&oacute;n de men&uacute;', 'required');
		$this->form_validation->set_rules('etiqueta', 'etiqueta', 'required|max_length[48]');
		$this->form_validation->set_rules('url', 'URL', '');
		$this->form_validation->set_rules('orden', 'orden', '');
		$this->form_validation->set_rules('nombre_padre', 'opci&oacute;n padre', '');
		$this->form_validation->set_rules('opcion_padre', 'opci&oacute;n padre', '');
		
		return $this->form_validation->run();
	}
	
	function updOpcionMenu() {
		$data['accion'] = $_POST['accion'];
		
		if($this->_validate() != false) {
			if($this->opcionmenu_model->updOpcionMenu() != FALSE) {
				$data['msgMtto'] = 'La opci&oacute;n de men&uacute; ' . $_POST['etiqueta'] . ' ha sido actualizada exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'La opci&oacute;n de men&uacute; ' . $_POST['etiqueta'] . ' no pudo ser actualizada.';
				$data['msgType'] = 'ERR';
			}
			$this->lstOpcionesMenu(0, $data);
		} else 
			$this->load->view('seguridad/opcionmenu/det_opcionmenu', $data);
	}
	
	function delOpcionMenu() {
		$data['accion'] = $_POST['accion'];
		if($this->opcionmenu_model->delOpcionMenu() != FALSE) {
			$data['msgMtto'] = 'La opci&oacute;n de men&uacute; ' . $_POST['etiqueta'] . ' ha sido eliminada exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'La opci&oacute;n de men&uacute; ' . $_POST['etiqueta'] . ' no pudo ser eliminada.';
			$data['msgType'] = 'ERR';
		}
		$this->lstOpcionesMenu(0, $data);
	}
}

/* End of file usuario.php */
/* Location: application/controllers/ */
