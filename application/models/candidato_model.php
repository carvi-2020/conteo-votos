<?php 

class Candidato_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function lstCandidatos($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT nombres, apellidos, candidato_id,   
			(SELECT nombre FROM cargo_postulado WHERE candidato.cargopost_id = cargo_postulado.cargopost_id) nombre_cargo,
			(SELECT orden FROM cargo_postulado WHERE candidato.cargopost_id = cargo_postulado.cargopost_id) orden_cargo,
			(SELECT nombre FROM planilla WHERE candidato.planilla_id = planilla.planilla_id) nombre_planilla,
			(SELECT codigo FROM socio WHERE candidato.socio_id = socio.socio_id) cod_socio
			FROM candidato WHERE 1 = 1 " . $fltWhere . " ORDER BY candidato.planilla_id ASC, orden_cargo ASC ", array());
		return $rs;
	}
	
	function loadCandidato($candidato_id) {
		$data = array();
		$rs = $this->db->query("SELECT *, 
			(SELECT CONCAT(socio.nombres, ' ', socio.apellidos) FROM socio WHERE 1 = 1 AND socio.socio_id = candidato.socio_id) AS nombre_socio  
			FROM candidato WHERE candidato_id = ? ", array($candidato_id));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}
	
	function loadCandidatoExist( $votacion_id, $nombres, $apellidos ) {
		$data = array();
		$rs = $this->db->query("SELECT 1 FROM candidato WHERE votacion_id = ? 
			AND TRIM(UPPER(nombres)) like TRIM(UPPER(?)) 
			AND TRIM(UPPER(apellidos)) like TRIM(UPPER(?))", array($votacion_id, $nombres, $apellidos));
		
		if($rs->num_rows() > 0) {
			return TRUE;
		}else{
			return FALSE;
		}
		return $data;
	}	
	
	function insCandidato() {		
		try {
			$data = array();

			$data['nombres'] = isset($_POST['nombres'])?$_POST['nombres']:NULL;
			$data['apellidos'] = isset($_POST['apellidos'])?$_POST['apellidos']:NULL;
			$data['cargopost_id'] = isset($_POST['cargopost_id'])?$_POST['cargopost_id']:NULL;
			$data['votacion_id'] = isset($_POST['votacion_id'])?$_POST['votacion_id']:NULL;
			$data['planilla_id'] = isset($_POST['planilla_id'])?$_POST['planilla_id']:NULL;
			if( isset($_POST['socio_id']) ){
				if( $_POST['socio_id'] != "" ){
					$data['socio_id'] = isset($_POST['socio_id'])?$_POST['socio_id']:NULL;		
				}else{
					$data['socio_id'] = NULL;
				}
			}
			
			$this->db->insert("candidato", $data);
				
			$resCli = $this->db->query('SELECT MAX(candidato_id) candidato_id FROM candidato ', array());
			$resCli = $resCli->result_array();
			$resCli = $resCli[0];
				
			return $resCli['candidato_id'];
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	
	function updCandidato() {
		try {
			$data['nombres'] = isset($_POST['nombres'])?$_POST['nombres']:NULL;
			$data['apellidos'] = isset($_POST['apellidos'])?$_POST['apellidos']:NULL;
			$data['cargopost_id'] = isset($_POST['cargopost_id'])?$_POST['cargopost_id']:NULL;
			$data['votacion_id'] = isset($_POST['votacion_id'])?$_POST['votacion_id']:NULL;
			$data['planilla_id'] = isset($_POST['planilla_id'])?$_POST['planilla_id']:NULL;
			if( isset($_POST['socio_id']) ){
				if( $_POST['socio_id'] != "" ){
					$data['socio_id'] = isset($_POST['socio_id'])?$_POST['socio_id']:NULL;		
				}else{
					$data['socio_id'] = NULL;
				}
			}
			
			$this->db->where('candidato_id', $_POST['candidato_id']);
			$this->db->update("candidato", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delCandidato() {
		try {
			$this->db->where("candidato_id", $_POST['candidato_id']);
			$this->db->delete("candidato");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file candidato_model.php*/
