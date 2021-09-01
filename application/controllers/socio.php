<?php 
class Socio extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('socio_model');
	}
	
	function lstSocios($numRow = 0, $dataCur = NULL) {
				
		$data = isset($dataCur)?$dataCur:array();
		
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
		array('_fltNom' => array('fld' => 'socio_id', 'typ' => 'str'),
			'_fltIso' => array('fld' => 'nombres', 'typ' => 'str', 'tpb' => 'LKF'),
			'_fltCap' => array('fld' => 'apellidos', 'typ' => 'str', 'tpb' => 'LKF'),
			'_fltCod' => array('fld' => 'codigo', 'typ' => 'str', 'tpb' => 'LKF'),
			'_fltJvpm' => array('fld' => 'jvpm', 'typ' => 'str', 'tpb' => 'LKF'),
			'_fltDui' => array('fld' => 'dui', 'typ' => 'str', 'tpb' => 'LKF')
				), $numRow, $data);
				
		$rs = $this->socio_model->lstSocios($filtros);
		$this->load->library('tablepaging');
		$data['lstSocios'] = $this->tablepaging->getTablaPaginada('socio/lstSocios', $rs, 10, $numRow);
		$this->load->view('votacion/socio/list.php', $data);
	}
	
	function insSocio() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {	
			if($this->socio_model->insSocio() != FALSE) {
				$data['msgMtto'] = 'El socio' . $_POST['nombres'] . ' ha sido guardado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El socio' . $_POST['nombres'] . ' no pudo ser guardado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstSocios(0, $data);
		} else{
			$this->load->view('votacion/socio/master', $data);
		}
	}
	
	function ajxListSocio() {
		//Obtenemos un listado de todos los socios y los pasamos
		$filtros = array();
		$this->load->helper('filtro');
		$arrFltNm = array('_fltNom' => $_GET['qryStr']);
		$filtros = verificarFiltros($arrFltNm, 
		array('_fltNom' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
				), 0, $data);
		
		$rs = $this->socio_model->lstSocios($filtros);
		$rs = $rs->result_array();
		echo json_encode($rs);    
	}
	
	function loadSocio() {
		$data['socio_id'] = '';
		$data['nombres'] = '';
		$data['apellidos'] = '';
		$data['codigo'] = '';
		$data['jvpm'] = '';
		$data['dui'] = '';
		
		if(isset($_POST['socio_id']) && $_POST['socio_id'] != ''){
			$data = $this->socio_model->loadSocio($_POST['socio_id']);
		}
		
		if(isset($_POST['accion'])){
			$data['accion'] = $_POST['accion'];			
		}else{
			$data['accion'] = 'INS';
		}	
		
		$this->load->view('votacion/socio/master', $data);
	}
	
	function _validate() {
		$this->load->library('form_validation');
		if($_POST['accion'] === 'UPD')
			$this->form_validation->set_rules('socio_id', 'ID del socio', 'required|max_length[10]');
		
		$this->form_validation->set_rules('nombres', 'Nombre del socio', 'required|max_length[40]');
		$this->form_validation->set_rules('apellidos', 'Apellidos del socio', 'required|max_length[40]');
		$this->form_validation->set_rules('codigo', 'Codigo', 'required|max_length[90]');
		$this->form_validation->set_rules('jvpm', 'JVPM', 'required|max_length[10]');
		$this->form_validation->set_rules('dui', 'DUI', 'required|max_length[10]');
		
		return $this->form_validation->run();
	}
	
	function updSocio() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {
			if($this->socio_model->updSocio() != FALSE) {
				$data['msgMtto'] = 'El socio' . $_POST['nombres'] . ' ha sido actualizado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El socio' . $_POST['nombres'] . ' no pudo ser actualizado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstSocios(0, $data);
		} else{
			$this->load->view('votacion/socio/master', $data);
		}	
	}
	
	function delSocio() {
		$data['accion'] = $_POST['accion'];
		if($this->socio_model->delSocio() != FALSE) {
			$data['msgMtto'] = 'El socio' . $_POST['nombres'] . ' ha sido eliminado exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'El socio' . $_POST['nombres'] . ' no pudo ser eliminado.';
			$data['msgType'] = 'ERR';
		}
		$this->lstSocios(0, $data);
	}
}

/* End of file socio.php */
/* Location: application/controllers/ */