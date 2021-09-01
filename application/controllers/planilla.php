<?php 
class Planilla extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('planilla_model');
		$this->load->model('votacion_model');
	}
	
	function lstPlanillas($numRow = 0, $dataCur = NULL) {
				
		$data = isset($dataCur)?$dataCur:array();
		
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
		array('_fltNom' => array('fld' => 'planilla_id', 'typ' => 'str'),
			'_fltIso' => array('fld' => 'codigo', 'typ' => 'str', 'tpb' => 'LKF'),
			'_fltCap' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
				), $numRow, $data);
				
		$rs = $this->planilla_model->lstPlanillas($filtros);
		$this->load->library('tablepaging');
		$data['lstPlanillas'] = $this->tablepaging->getTablaPaginada('planilla/lstPlanillas', $rs, 20, $numRow);
		$this->load->view('votacion/planilla/list.php', $data);
	}


	function lstPlanillasx($numRow = 0, $dataCur = NULL) {

		$data = isset($dataCur)?$dataCur:array();

		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST,
			array('_fltNom' => array('fld' => 'planilla_id', 'typ' => 'str'),
				'_fltIso' => array('fld' => 'codigo', 'typ' => 'str', 'tpb' => 'LKF'),
				'_fltCap' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
			), $numRow, $data);

		$rs = $this->planilla_model->lstPlanillasx($filtros);
		$this->load->library('tablepaging');
		$data['lstPlanillasx'] = $this->tablepaging->getTablaPaginada('planilla/lstPlanillasx', $rs, 20, $numRow);
		$this->load->view('votacion/votacionSocio/masterIns.php', $data);
	}

	
	function insPlanilla() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {	
			if($this->planilla_model->insPlanilla() != FALSE) {
				$data['msgMtto'] = 'La planilla' . $_POST['nombre'] . ' ha sido guardado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'La planilla' . $_POST['nombre'] . ' no pudo ser guardado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstPlanillas(0, $data);
		} else{
			//Obteniendo la votacion activa
			$votacion = $this->votacion_model->getVotacionAct();
			if( $votacion != NULL ){
				$data['nombre_votacion'] = $votacion['nombre'];
				$data['votacion_id'] = $votacion['votacion_id'];	
			}
		
			$this->load->view('votacion/planilla/master', $data);
		}	
	}
	
	function ajxListPlanilla() {
		//Obtenemos un listado de todos los planillas y los pasamos
		$filtros = array();
		$this->load->helper('filtro');
		$arrFltNm = array('_fltNom' => $_GET['qryStr']);
		$filtros = verificarFiltros($arrFltNm, 
		array('_fltNom' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
				), 0, $data);
		
		$rs = $this->planilla_model->lstPlanillas($filtros);
		$rs = $rs->result_array();
		echo json_encode($rs);    
	}
	
	function loadPlanilla() {
		$data['planilla_id'] = '';
		$data['codigo'] = '';
		$data['nombre'] = '';
		$data['estado'] = '';
		
		if(isset($_POST['planilla_id']) && $_POST['planilla_id'] != ''){
			$data = $this->planilla_model->loadPlanilla($_POST['planilla_id']);
			
			//Si es actualizacion o borrado se obtiene la votacion guardada
			$votacion = $this->votacion_model->loadVotacion( $data['votacion_id'] );
			$data['nombre_votacion'] = $votacion['nombre'];
		}else{
			//Obteniendo la votacion activa
			$votacion = $this->votacion_model->getVotacionAct();
			if( $votacion != NULL ){
				$data['nombre_votacion'] = $votacion['nombre'];
				$data['votacion_id'] = $votacion['votacion_id'];	
			}
		}	
		
		if(isset($_POST['accion']))
			$data['accion'] = $_POST['accion'];
		else
			$data['accion'] = 'INS';
		
		$this->load->view('votacion/planilla/master', $data);
	}
	
	function _validate() {
		$this->load->library('form_validation');
		if($_POST['accion'] === 'UPD')
			$this->form_validation->set_rules('planilla_id', 'ID de la categoria', 'required');
		
		$this->form_validation->set_rules('codigo', 'Codigo de la planilla', 'required|max_length[5]');
		$this->form_validation->set_rules('nombre', 'Nombre de la planilla', 'required|max_length[40]');
		$this->form_validation->set_rules('estado', 'Estado de la planilla', 'required|max_length[3]');
		$this->form_validation->set_rules('estado1', 'Estado de la planilla', '');
		$this->form_validation->set_rules('votacion_id', 'ID votacion activa', '');
		$this->form_validation->set_rules('nombre_votacion', 'Nombre de la votacion', '');
		
		return $this->form_validation->run();
	}
	
	function updPlanilla() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {
			if($this->planilla_model->updPlanilla() != FALSE) {
				$data['msgMtto'] = 'La planilla' . $_POST['nombre'] . ' ha sido actualizado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'La planilla' . $_POST['nombre'] . ' no pudo ser actualizado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstPlanillas(0, $data);
		} else{
			$this->load->view('votacion/planilla/master', $data);
		} 
			
	}
	
	function delPlanilla() {
		$data['accion'] = $_POST['accion'];
		if($this->planilla_model->delPlanilla() != FALSE) {
			$data['msgMtto'] = 'La planilla' . $_POST['nombre'] . ' ha sido eliminado exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'La planilla' . $_POST['nombre'] . ' no pudo ser eliminado.';
			$data['msgType'] = 'ERR';
		}
		$this->lstPlanillas(0, $data);
	}
}

/* End of file planilla.php */
/* Location: application/controllers/ */
