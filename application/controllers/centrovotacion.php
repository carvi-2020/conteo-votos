<?php 
class CentroVotacion extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('centrovotacion_model');
	}
	
	function lstCentroVotaciones($numRow = 0, $dataCur = NULL) {
				
		$data = isset($dataCur)?$dataCur:array();
		
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
		array('_fltNom' => array('fld' => 'centrovot_id', 'typ' => 'str'),
			'_fltIso' => array('fld' => 'codigo', 'typ' => 'str', 'tpb' => 'LKF'),
			'_fltCap' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
				), $numRow, $data);
				
		$rs = $this->centrovotacion_model->lstCentroVotaciones($filtros);
		$this->load->library('tablepaging');
		$data['lstCentroVotaciones'] = $this->tablepaging->getTablaPaginada('centrovotacion/lstCentroVotaciones', $rs, 10, $numRow);
		$this->load->view('votacion/centroVotacion/list', $data);
	}
	
	function insCentroVotacion() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {	
			if($this->centrovotacion_model->insCentroVotacion() != FALSE) {
				$data['msgMtto'] = 'El Centro de Votacion' . $_POST['nombre'] . ' ha sido guardado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El Centro de Votacion' . $_POST['nombre'] . ' no pudo ser guardado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstCentroVotaciones(0, $data);
		} else
			$this->load->view('votacion/centroVotacion/master', $data);
	}
	
	function ajxListCentroVotacion() {
		//Obtenemos un listado de todos los centros de votacion y los pasamos
		$filtros = array();
		$this->load->helper('filtro');
		$arrFltNm = array('_fltNom' => $_GET['qryStr']);
		$filtros = verificarFiltros($arrFltNm, 
		array('_fltNom' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
				), 0, $data);
		
		$rs = $this->centrovotacion_model->lstCentroVotaciones($filtros);
		$rs = $rs->result_array();
		echo json_encode($rs);    
	}
	
	function loadCentroVotacion() {
		$data['centrovot_id'] = '';
		$data['codigo'] = '';
		$data['nombre'] = '';
		$data['estado'] = '';
		
		if(isset($_POST['centrovot_id']) && $_POST['centrovot_id'] != '') 
			$data = $this->centrovotacion_model->loadCentroVotacion($_POST['centrovot_id']);
		
		if(isset($_POST['accion']))
			$data['accion'] = $_POST['accion'];
		else
			$data['accion'] = 'INS';
		$this->load->view('votacion/centroVotacion/master', $data);
	}
	
	function _validate() {
		$this->load->library('form_validation');
		if($_POST['accion'] === 'UPD')
			$this->form_validation->set_rules('centrovot_id', 'ID de la categoria', 'required');
		
		$this->form_validation->set_rules('codigo', 'El anio del Centro de Votacion', 'required|max_length[5]');
		$this->form_validation->set_rules('nombre', 'Nombre del Centro de Votacion', 'required|max_length[40]');
		$this->form_validation->set_rules('estado', 'Estado del Centro de Votacion', 'required|max_length[3]');
		
		return $this->form_validation->run();
	}
	
	function updCentroVotacion() {
		
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {
			if($this->centrovotacion_model->updCentroVotacion() != FALSE) {
				$data['msgMtto'] = 'El Centro de Votacion' . $_POST['nombre'] . ' ha sido actualizado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El Centro de Votacion' . $_POST['nombre'] . ' no pudo ser actualizado.';
				$data['msgType'] = 'ERR';
			}
		
			$this->lstCentroVotaciones(0, $data);
		} else {
			
			$this->load->view('votacion/centroVotacion/master', $data);
		}
	}
	
	function delCentroVotacion() {
		$data['accion'] = $_POST['accion'];
		if($this->centrovotacion_model->delCentroVotacion() != FALSE) {
			$data['msgMtto'] = 'El Centro de Votacion' . $_POST['nombre'] . ' ha sido eliminado exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'El Centro de Votacion' . $_POST['nombre'] . ' no pudo ser eliminado.';
			$data['msgType'] = 'ERR';
		}
		$this->lstCentroVotaciones(0, $data);
	}
}

/* End of file centroVotacion.php */
/* Location: application/controllers/ */
