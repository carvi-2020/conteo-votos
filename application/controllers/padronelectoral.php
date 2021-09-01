<?php 
class Padronelectoral extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('padronelectoral_model');
		$this->load->model('centroVotacion_model');
		$this->load->model('votacion_model');
	}
	
	function lstPadroneselectorales($numRow = 0, $dataCur = NULL) {
				
		$data = isset($dataCur)?$dataCur:array();
		
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
		array('_fltNom' => array('fld' => 'pdrele_id', 'typ' => 'str'),
			'_fltIso' => array('fld' => 'socio_id', 'typ' => 'str', 'tpb' => 'LKF'),
			'_fltCap' => array('fld' => 'centrovot_id', 'typ' => 'str', 'tpb' => 'LKF')
				), $numRow, $data);
				
		$rs = $this->padronelectoral_model->lstPadroneselectorales($filtros);
		$this->load->library('tablepaging');
		$data['lstPadroneselectorales'] = $this->tablepaging->getTablaPaginada('padronelectoral/lstPadroneselectorales', $rs, 20, $numRow);
		$this->load->view('votacion/padronelectoral/list', $data);
	}
	
	function insPadronelectoral() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {	
			if($this->padronelectoral_model->insPadronelectoral() != FALSE) {
				$data['msgMtto'] = 'El padron electoral' . ' ha sido guardado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El padron electoral' . ' no pudo ser guardado.';
				$data['msgType'] = 'ERR';
			}
			$data['centrovot_id'] = $_POST['centrovot_id'];
			$this->loadPadronelectoral($data);
		} else{
			//Obtener listado de Centros de Votacion
			$lstCentroVotaciones = $this->centroVotacion_model->lstCentroVotaciones();
			$lstCentroVotaciones = $lstCentroVotaciones->result_array();
			$data['lstCentroVotaciones'] = $lstCentroVotaciones;
		
			//Obteniendo la votacion activa
			$votacion = $this->votacion_model->getVotacionAct();
			if( $votacion != NULL ){
				$data['nombre_votacion'] = $votacion['nombre'];
				$data['votacion_id'] = $votacion['votacion_id'];	
			}
		
			$this->load->view('votacion/padronelectoral/master', $data);
		}	
	}
	
	function vrfcrVotoSocio( $idSocio, $centrovot_id ){
		$yaVoto = $this->padronelectoral_model->socioYaRegPadron( $idSocio, $centrovot_id );
		$padronSoc = $this->padronelectoral_model->obtHistorialPadron( $idSocio);
		$res = array();
		
		$res['ya_voto'] = $yaVoto;
		$res['det_padron'] = $padronSoc;
		
		echo json_encode($res);
		
	}
	
	function ajxListPadronelectoral() {
		//Obtenemos un listado de todos los padroneselectorales y los pasamos
		$filtros = array();
		$this->load->helper('filtro');
		$arrFltNm = array('_fltNom' => $_GET['qryStr']);
		$filtros = verificarFiltros($arrFltNm, 
		array('_fltNom' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
				), 0, $data);
		
		$rs = $this->padronelectoral_model->lstPadroneselectorales($filtros);
		$rs = $rs->result_array();
		echo json_encode($rs);    
	}
	
	function loadPadronelectoral($data = NULL) {
		$data['pdrele_id'] = '';
		$data['socio_id'] = '';
		//$data['centrovot_id'] = '';
		$data['nombre_votacion'] = '';
		$data['nombre_socio'] = '';
		$data['votacion_id'] = '';
		
		if(isset($_POST['pdrele_id']) && $_POST['pdrele_id'] != ''){
			$data = $this->padronelectoral_model->loadPadronelectoral($_POST['pdrele_id']);
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
		
		//Obtener listado de Centros de Votacion
		$lstCentroVotaciones = $this->centroVotacion_model->lstCentroVotaciones();
		$lstCentroVotaciones = $lstCentroVotaciones->result_array();
		$data['lstCentroVotaciones'] = $lstCentroVotaciones;
		
		$this->load->view('votacion/padronelectoral/master', $data);
	}
	
	function _validate() {
		$this->load->library('form_validation');
		if($_POST['accion'] === 'UPD')
			$this->form_validation->set_rules('pdrele_id', 'ID del padron', 'required|max_length[10]');
		
		$this->form_validation->set_rules('socio_id', 'ID del socio', 'required|max_length[40]');
		$this->form_validation->set_rules('centrovot_id', 'ID del centro de votacion', 'required|max_length[10]');
		$this->form_validation->set_rules('nombre_socio', 'Nombre del socio', '');
		$this->form_validation->set_rules('votacion_id', 'Votacion activa', '');
		$this->form_validation->set_rules('nombre_votacion', 'Nombre de la votacion activa', '');
		
		return $this->form_validation->run();
	}
	
	function updPadronelectoral() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {
			if($this->padronelectoral_model->updPadronelectoral() != FALSE) {
				$data['msgMtto'] = 'El padron electoral' . ' ha sido actualizado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El padron electoral' . ' no pudo ser actualizado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstPadroneselectorales(0, $data);
		} else{
			//Obtener listado de Centros de Votacion
			$lstCentroVotaciones = $this->centroVotacion_model->lstCentroVotaciones();
			$lstCentroVotaciones = $lstCentroVotaciones->result_array();
			$data['lstCentroVotaciones'] = $lstCentroVotaciones;
		
			$this->load->view('votacion/padronelectoral/master', $data);
		} 
			
	}
	
	function delPadronelectoral() {
		$data['accion'] = $_POST['accion'];
		if($this->padronelectoral_model->delPadronelectoral() != FALSE) {
			$data['msgMtto'] = 'El padron electoral' . ' ha sido eliminado exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'El padron electoral' . ' no pudo ser eliminado.';
			$data['msgType'] = 'ERR';
		}
		$this->lstPadroneselectorales(0, $data);
	}
}

/* End of file padronelectoral.php */
/* Location: application/controllers/ */
