<?php 
class CargoPostulado extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('cargopostulado_model');
	}
	
	function lstCargosPostulados($numRow = 0, $dataCur = NULL) {
				
		$data = isset($dataCur)?$dataCur:array();
		
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
		array('_fltNom' => array('fld' => 'cargopost_id', 'typ' => 'str'),
			'_fltIso' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'EXA')
				), $numRow, $data);
				
		$rs = $this->cargopostulado_model->lstCargosPostulados($filtros);
		$this->load->library('tablepaging');
		$data['lstCargosPostulados'] = $this->tablepaging->getTablaPaginada('cargopostulado/lstCargosPostulados', $rs, 20, $numRow);
		$this->load->view('votacion/cargoPostulado/list.php', $data);
	}
	
	function ajxSocios() {
		$res = array();
		$numRow = 0;
		
		$filtros = array();
		$arrDataFiltro = array();
		$detFiltro = array();
		
		$this->load->helper('filtroajax/filtro');
		
		$arrDataFiltro['_fltCod'] = $_GET['term'];
		
		$defFiltro['_fltCod'] = array('fld' => 'codigo', 'typ' => 'str', 'tpb' => 'LKF', 'det' => 'AND');
		
		$rs = $this->cargopostulado_model->lstSocios($filtros);
		
		$filtros = verificarFiltros($arrDataFiltro, $defFiltro, 0, $data);
		$rs = $this->cargopostulado_model->lstSocios($filtros);
		
		//Les cambiamos los labels a label y value
		foreach ($rs as $itm) {
			$nwitm = array(
				'label' => $itm['nombres'] . ' ' . $itm['apellidos']
				,'value' => $itm['socio_id']
				,'nombres' => $itm['nombres']
				,'apellidos' => $itm['apellidos']
				,'codigo' => $itm['codigo']
				,'jvpm' => $itm['jvpm']);
				
			$res[] = $nwitm;
		}
		
		echo json_encode($res);    
    }	
	
	function insCargoPostulado() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {	
			if($this->cargopostulado_model->insCargoPostulado() != FALSE) {
				$data['msgMtto'] = 'El Cargo Postulado' . $_POST['nombre'] . ' ha sido guardado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El Cargo Postulado' . $_POST['nombre'] . ' no pudo ser guardado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstCargosPostulados(0, $data);
		} else
			$this->load->view('votacion/cargoPostulado/master', $data);
	}
	
	function ajxListCargoPostulado() {
		//Obtenemos un listado de todos los cargos postulados y los pasamos
		$filtros = array();
		$this->load->helper('filtro');
		$arrFltNm = array('_fltNom' => $_GET['qryStr']);
		$filtros = verificarFiltros($arrFltNm, 
		array('_fltNom' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
				), 0, $data);
		
		$rs = $this->cargopostulado_model->lstCargosPostulados($filtros);
		$rs = $rs->result_array();
		echo json_encode($rs);    
	}
	
	function loadCargoPostulado() {
		$data['cargopost_id'] = '';
		$data['nombre'] = '';
		
		if(isset($_POST['cargopost_id']) && $_POST['cargopost_id'] != '') 
			$data = $this->cargopostulado_model->loadCargoPostulado($_POST['cargopost_id']);
		
		if(isset($_POST['accion']))
			$data['accion'] = $_POST['accion'];
		else
			$data['accion'] = 'INS';
		$this->load->view('votacion/cargoPostulado/master', $data);
	}
	
	function _validate() {
		$this->load->library('form_validation');
		if($_POST['accion'] === 'UPD')
			$this->form_validation->set_rules('cargopost_id', 'ID del Cargo', 'required');
		
		$this->form_validation->set_rules('nombre', 'Nombre del cargo', 'required|max_length[40]');
		$this->form_validation->set_rules('orden', 'Orden del cargo', 'required|max_length[2]');
		
		return $this->form_validation->run();
	}
	
	function updCargoPostulado() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {
			if($this->cargopostulado_model->updCargoPostulado() != FALSE) {
				$data['msgMtto'] = 'El Cargo Postulado' . $_POST['nombre'] . ' ha sido actualizado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'El Cargo Postulado' . $_POST['nombre'] . ' no pudo ser actualizado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstCargosPostulados(0, $data);
		} else 
			$this->load->view('votacion/cargoPostulado/master', $data);
	}
	
	function delCargoPostulado() {
		$data['accion'] = $_POST['accion'];
		if($this->cargopostulado_model->delCargoPostulado() != FALSE) {
			$data['msgMtto'] = 'El Cargo Postulado' . $_POST['nombre'] . ' ha sido eliminado exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'El Cargo Postulado' . $_POST['nombre'] . ' no pudo ser eliminado.';
			$data['msgType'] = 'ERR';
		}
		$this->lstCargosPostulados(0, $data);
	}
}

/* End of file cargoPostulado.php */
/* Location: application/controllers/ */
