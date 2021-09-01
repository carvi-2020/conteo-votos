<?php 

class Padronelectoral_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function lstPadroneselectorales($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT pdrele_id, DATE_FORMAT(fecha_hora, '%d/%m/%Y %l:%i %p') fecha_hora,    
			(SELECT CONCAT(socio.nombres, ' ', socio.apellidos) FROM socio WHERE 1 = 1 AND socio.socio_id = padron_electoral.socio_id) AS nombre_socio,
			(SELECT socio.codigo FROM socio WHERE 1 = 1 AND socio.socio_id = padron_electoral.socio_id) AS codigo_socio,
			(SELECT socio.jvpm FROM socio WHERE 1 = 1 AND socio.socio_id = padron_electoral.socio_id) AS jvpm_socio,
			(SELECT socio.dui FROM socio WHERE 1 = 1 AND socio.socio_id = padron_electoral.socio_id) AS dui_socio,
			(SELECT nombre FROM centro_votacion WHERE 1 = 1 AND centro_votacion.centrovot_id = padron_electoral.centrovot_id) AS nombre_centro  
			FROM padron_electoral WHERE 1 = 1 " . $fltWhere . " ORDER BY pdrele_id ASC", array());
		return $rs;
	}
	
	function loadPadronelectoral($pdrele_id) {
		$data = array();
		$rs = $this->db->query("SELECT *,
			(SELECT CONCAT(socio.nombres, ' ', socio.apellidos) FROM socio WHERE 1 = 1 AND socio.socio_id = padron_electoral.socio_id) AS nombre_socio,
			(SELECT nombre FROM centro_votacion WHERE 1 = 1 AND centro_votacion.centrovot_id = padron_electoral.centrovot_id) AS nombre_centro,
			(SELECT votacion.nombre FROM votacion WHERE votacion.votacion_id = padron_electoral.votacion_id) AS nombre_votacion
			FROM padron_electoral WHERE pdrele_id = ? ORDER BY pdrele_id ASC", array($pdrele_id));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}
	
	function socioYaRegPadron( $idSocio, $centrovot_id ){
		$data = array();
		$rs = $this->db->query("SELECT 1
			FROM padron_electoral WHERE 1 = 1 
			AND socio_id = ? 
			AND centrovot_id = ?  
			ORDER BY pdrele_id ASC", array($idSocio, $centrovot_id));
			
		if($rs->num_rows() > 0) 
			return TRUE;
		
		return FALSE;		
	}
	
	function obtHistorialPadron( $idSocio){
		$data = array();
		$rs = $this->db->query("SELECT DATE_FORMAT(fecha_hora, '%d/%m/%Y %l:%i %p') fecha_hora, socio_id, centrovot_id, votacion_id, 
			(SELECT nombre FROM centro_votacion WHERE 1 = 1 AND centro_votacion.centrovot_id = padron_electoral.centrovot_id) AS nombre_centro 
			FROM padron_electoral WHERE 1 = 1 
			AND socio_id = ? 
			ORDER BY pdrele_id ASC", array($idSocio));
			
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			return $data;
		}
		
		return NULL;	
	}	
	
	function insPadronelectoral() {		
		try {
			$data = array();
			date_default_timezone_set('America/El_Salvador');
			$data['socio_id'] = isset($_POST['socio_id'])?$_POST['socio_id']:NULL;
			$data['centrovot_id'] = isset($_POST['centrovot_id'])?$_POST['centrovot_id']:NULL;
			$data['votacion_id'] = isset($_POST['votacion_id'])?$_POST['votacion_id']:NULL;
			
			//Obteniendo la fecha y hora del sistema en formato my sql
			$data['fecha_hora'] = $nowFormat = date('Y-m-d H:i:s');
			
			$this->db->insert("padron_electoral", $data);
				
			$resCli = $this->db->query('SELECT MAX(pdrele_id) pdrele_id FROM padron_electoral ', array());
			$resCli = $resCli->result_array();
			$resCli = $resCli[0];
				
			return $resCli['pdrele_id'];
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	
	function updPadronelectoral() {
		try {
			$data['socio_id'] = isset($_POST['socio_id'])?$_POST['socio_id']:NULL;
			$data['centrovot_id'] = isset($_POST['centrovot_id'])?$_POST['centrovot_id']:NULL;
			
			$this->db->where('pdrele_id', $_POST['pdrele_id']);
			$this->db->update("padron_electoral", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delPadronelectoral() {
		try {
			$this->db->where("pdrele_id", $_POST['pdrele_id']);
			$this->db->delete("padron_electoral");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file planilla_model.php*/
