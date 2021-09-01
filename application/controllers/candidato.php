<?php 
class Candidato extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('candidato_model');
		$this->load->model('cargopostulado_model');
		$this->load->model('planilla_model');
		$this->load->model('votacion_model');
		$this->load->model('centrovotacion_model');
	}
	
	function lstCandidatos($numRow = 0, $dataCur = NULL) {
				
		$data = isset($dataCur)?$dataCur:array();
		
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
		array('_fltNom' => array('fld' => 'candidato_id', 'typ' => 'str'),
			'_fltIso' => array('fld' => 'nombres', 'typ' => 'str', 'tpb' => 'LKF'),
			'_fltCap' => array('fld' => 'apellidos', 'typ' => 'str', 'tpb' => 'LKF')
				), $numRow, $data);
				
		$rs = $this->candidato_model->lstCandidatos($filtros);
		$this->load->library('tablepaging');
		$data['lstCandidatos'] = $this->tablepaging->getTablaPaginada('candidato/lstCandidatos', $rs, 20, $numRow);
		$this->load->view('votacion/candidato/list.php', $data);
	}
	
	function insCandidato() {
		$data['accion'] = $_POST['accion'];
		if( $this->candidato_model->loadCandidatoExist( $_POST['votacion_id'], $_POST['nombres'], $_POST['apellidos'] ) == FALSE ){
			if($this->_validate() != FALSE) {	
				if($this->candidato_model->insCandidato() != FALSE) {
					$data['msgMtto'] = 'El candidato' . $_POST['nombres'] . ' ha sido guardado exitosamente.';
					$data['msgType'] = 'MSG';
				} else {
					$data['msgMtto'] = 'El candidato' . $_POST['nombres'] . ' no pudo ser guardado.';
					$data['msgType'] = 'ERR';
				}
				$this->lstCandidatos(0, $data);
			} else{
				//Obtener listado de cargos
				$lstCargosPostulados = $this->cargopostulado_model->lstCargosPostulados();
				$lstCargosPostulados = $lstCargosPostulados->result_array();
				$data['lstCargosPostulados'] = $lstCargosPostulados;
				
				//Obtener listado de planillas de votacion
				$lstPlanillas = $this->planilla_model->lstPlanillas();
				$lstPlanillas = $lstPlanillas->result_array();
				$data['lstPlanillas'] = $lstPlanillas;
				
				$this->load->view('votacion/candidato/master', $data);
			}
		}else{
			//Obtener listado de cargos
			$lstCargosPostulados = $this->cargopostulado_model->lstCargosPostulados();
			$lstCargosPostulados = $lstCargosPostulados->result_array();
			$data['lstCargosPostulados'] = $lstCargosPostulados;
			
			//Obtener listado de planillas de votacion
			$lstPlanillas = $this->planilla_model->lstPlanillas();
			$lstPlanillas = $lstPlanillas->result_array();
			$data['lstPlanillas'] = $lstPlanillas;
			
			//Obtener listado de Socios
			$lstSocios = $this->centrovotacion_model->lstSocios();
			$lstSocios = $lstSocios->result_array();
			$data['lstSocios'] = $lstSocios;
			
			$data['msgMtto'] = 'El candidato ' . $_POST['nombres'] . ' ' . $_POST['apellidos'] . ' ya existe.';
			$data['msgType'] = 'ERR';
			
			$this->load->view('votacion/candidato/master', $data);
		}	
	}
	
	function ajxListCandidato() {
		//Obtenemos un listado de todos los candidatos y los pasamos
		$filtros = array();
		$this->load->helper('filtro');
		$arrFltNm = array('_fltNom' => $_GET['qryStr']);
		$filtros = verificarFiltros($arrFltNm, 
		array('_fltNom' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
				), 0, $data);
		
		$rs = $this->candidato_model->lstCandidatos($filtros);
		$rs = $rs->result_array();
		echo json_encode($rs);    
	}
	
	function loadCandidato() {
		$data['candidato_id'] = '';
		$data['nombres'] = '';
		$data['apellidos'] = '';
		$data['cargopost_id'] = '';
		$data['votacion_id'] = '';
		$data['planilla_id'] = '';
		
		if(isset($_POST['candidato_id']) && $_POST['candidato_id'] != ''){
			$data = $this->candidato_model->loadCandidato($_POST['candidato_id']);
			
			//Si es actualizacion o borrado se obtiene la votacion guardada
			$votacion = $this->votacion_model->loadVotacion( $data['votacion_id'] );
			$data['nombre_votacion'] = $votacion['nombre'];
		}else{
			//Si es insercion se obtiene la votacion activa
			$votacion = $this->votacion_model->getVotacionAct();
			$data['nombre_votacion'] = $votacion['nombre'];
			$data['votacion_id'] = $votacion['votacion_id'];
		}
		
		if(isset($_POST['accion'])){
			$data['accion'] = $_POST['accion'];			
		}else{
			$data['accion'] = 'INS';
		}	
		
		//Obtener listado de cargos
		$lstCargosPostulados = $this->cargopostulado_model->lstCargosPostulados();
		$lstCargosPostulados = $lstCargosPostulados->result_array();
		$data['lstCargosPostulados'] = $lstCargosPostulados;
		
		//Obtener listado de planillas de votacion
		$lstPlanillas = $this->planilla_model->lstPlanillas();
		$lstPlanillas = $lstPlanillas->result_array();
		$data['lstPlanillas'] = $lstPlanillas;
		
		//Obtener listado de Socios
		$lstSocios = $this->centrovotacion_model->lstSocios();
		$lstSocios = $lstSocios->result_array();
		$data['lstSocios'] = $lstSocios;
		
		$this->load->view('votacion/candidato/master', $data);
	}
	
	function _validate() {
		$this->load->library('form_validation');
		if($_POST['accion'] === 'UPD')
			$this->form_validation->set_rules('candidato_id', 'ID del candidato', 'required|max_length[10]');
		
		$this->form_validation->set_rules('nombres', 'Nombres del candidato', 'required|max_length[40]');
		$this->form_validation->set_rules('apellidos', 'Apellidos del candidato', 'required|max_length[40]');
		$this->form_validation->set_rules('cargopost_id', 'Cargo al que se postula', 'required|max_length[10]');
		$this->form_validation->set_rules('planilla_id', 'Planilla de votacion', 'required|max_length[10]');
		$this->form_validation->set_rules('votacion_id', 'Votacion activa', 'required|max_length[10]');
		$this->form_validation->set_rules('nombre_votacion', 'Votacion activa', '');
		$this->form_validation->set_rules('socio_id', 'ID del socio', 'max_length[10]');
		$this->form_validation->set_rules('nombre_socio', 'Nombre del socio', '');
		
		return $this->form_validation->run();
	}
	
	function updCandidato() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {
			if($this->candidato_model->updCandidato() != FALSE) {
				$data['msgMtto'] = 'El candidato' . $_POST['nombres'] . ' ha sido actualizado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El candidato' . $_POST['nombres'] . ' no pudo ser actualizado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstCandidatos(0, $data);
		} else{
			//Obtener listado de cargos
			$lstCargosPostulados = $this->cargopostulado_model->lstCargosPostulados();
			$lstCargosPostulados = $lstCargosPostulados->result_array();
			$data['lstCargosPostulados'] = $lstCargosPostulados;
			
			//Obtener listado de planillas de votacion
			$lstPlanillas = $this->planilla_model->lstPlanillas();
			$lstPlanillas = $lstPlanillas->result_array();
			$data['lstPlanillas'] = $lstPlanillas;
			
			//Obtener listado de Socios
			$lstSocios = $this->centrovotacion_model->lstSocios();
			$lstSocios = $lstSocios->result_array();
			$data['lstSocios'] = $lstSocios;
			
			$this->load->view('votacion/candidato/master', $data);
		}	
	}
	
	function delCandidato() {
		$data['accion'] = $_POST['accion'];
		if($this->candidato_model->delCandidato() != FALSE) {
			$data['msgMtto'] = 'El candidato' . $_POST['nombres'] . ' ha sido eliminado exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'El candidato' . $_POST['nombres'] . ' no pudo ser eliminado.';
			$data['msgType'] = 'ERR';
		}
		$this->lstCandidatos(0, $data);
	}
}

/* End of file candidato.php */
/* Location: application/controllers/ */
