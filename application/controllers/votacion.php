<?php 
class Votacion extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('votacion_model');
	}
	
	function lstVotaciones($numRow = 0, $dataCur = NULL) {
				
		$data = isset($dataCur)?$dataCur:array();
		
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
		array('_fltNom' => array('fld' => 'votacion_id', 'typ' => 'str'),
			'_fltIso' => array('fld' => 'anio', 'typ' => 'str', 'tpb' => 'EXA'),
			'_fltCap' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
				), $numRow, $data);
				
		$rs = $this->votacion_model->lstVotaciones($filtros);
		$this->load->library('tablepaging');
		$data['lstVotaciones'] = $this->tablepaging->getTablaPaginada('votacion/lstVotaciones', $rs, 10, $numRow);
		$this->load->view('votacion/votacion/list', $data);
	}
	
	function insVotacion() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {	
			if($this->votacion_model->insVotacion() != FALSE) {
				$data['msgMtto'] = 'La votacion' . $_POST['nombre'] . ' ha sido guardado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'La votacion' . $_POST['nombre'] . ' no pudo ser guardado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstVotaciones(0, $data);
		} else
			$this->load->view('votacion/votacion/master', $data);
	}
	
	function ajxListVotacion() {
		//Obtenemos un listado de todos los votaciones y los pasamos
		$filtros = array();
		$this->load->helper('filtro');
		$arrFltNm = array('_fltNom' => $_GET['qryStr']);
		$filtros = verificarFiltros($arrFltNm, 
		array('_fltNom' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
				), 0, $data);
		
		$rs = $this->votacion_model->lstVotaciones($filtros);
		$rs = $rs->result_array();
		echo json_encode($rs);    
	}
	
	function limpiarVotaciones() {
		$data = $this->votacion_model->limpiarVotacionesAnt();
		$data['msgMtto'] = 'Los datos de las votaciones fueron limpiados.';
		$data['msgType'] = 'MSG';
		$this->lstVotaciones(0, $data);
	}
	
	function loadVotacion() {
		$data['votacion_id'] = '';
		$data['anio'] = '';
		$data['nombre'] = '';
		$data['estado'] = '';
		$data['situacion'] = '';
		
		if(isset($_POST['votacion_id']) && $_POST['votacion_id'] != '') 
			$data = $this->votacion_model->loadVotacion($_POST['votacion_id']);
		
		if(isset($_POST['accion']))
			$data['accion'] = $_POST['accion'];
		else
			$data['accion'] = 'INS';
		$this->load->view('votacion/votacion/master', $data);
	}
	
	function _validate() {
		$this->load->library('form_validation');
		if($_POST['accion'] === 'UPD')
			$this->form_validation->set_rules('votacion_id', 'ID de la votacion', 'required');
		
		$this->form_validation->set_rules('anio', 'El año de la Votacion', 'required|max_length[5]');
		$this->form_validation->set_rules('nombre', 'Nombre de la Votacion', 'required|max_length[20]');
		$this->form_validation->set_rules('estado', 'Define si la Votacion esta activa', 'required|max_length[3]');
		$this->form_validation->set_rules('situacion', 'Situacion de la Votacion', 'required|max_length[3]');
		$this->form_validation->set_rules('estado1', 'Define si la Votacion esta activa', '');
		$this->form_validation->set_rules('situacion1', 'Situacion de la Votacion', '');
		
		return $this->form_validation->run();
	}
	
	function updVotacion() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {
			if($this->votacion_model->updVotacion() != FALSE) {
				$data['msgMtto'] = 'La votacion' . $_POST['nombre'] . ' ha sido actualizado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'La votacion' . $_POST['nombre'] . ' no pudo ser actualizado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstVotaciones(0, $data);
		} else 
			$this->load->view('votacion/votacion/master', $data);
	}
	
	function delVotacion() {
		$data['accion'] = $_POST['accion'];
		if($this->votacion_model->delVotacion() != FALSE) {
			$data['msgMtto'] = 'La votacion' . $_POST['nombre'] . ' ha sido eliminado exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'La votacion' . $_POST['nombre'] . ' no pudo ser eliminado.';
			$data['msgType'] = 'ERR';
		}
		$this->lstVotaciones(0, $data);
	}
}

/* End of file votacion.php */
/* Location: application/controllers/ */
