<?php 

class Votacion_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function lstVotaciones($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT *
			FROM votacion WHERE 1 = 1 " . $fltWhere . " ORDER BY nombre ASC", array());
		return $rs;
	}
	
	function loadVotacion($votacion_id) {
		$data = array();
		$rs = $this->db->query("SELECT * 
			FROM votacion WHERE votacion_id = ? ", array($votacion_id));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}
	
	function getVotacionAct() {
		$data = array();
		$rs = $this->db->query("SELECT votacion_id, anio, nombre, periodo 
			FROM votacion WHERE estado = 'ACT'", array());
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}
	
	function limpiarVotacionesAnt() {
		$this->db->query("DELETE FROM votacion_socio ", array());
		$this->db->query("DELETE FROM conteo_votacion ", array());
		$this->db->query("DELETE FROM padron_electoral ", array());
		$this->db->query("DELETE FROM candidato ", array());
		//$this->db->query("DELETE FROM planilla ", array());
		//$this->db->query("DELETE FROM centro_votacion ", array());
		//$this->db->query("DELETE FROM votacion ", array());
		//$this->db->query("DELETE FROM socio ", array());
	}
	
	function insVotacion() {		
		try {
			$data = array();

			$data['anio'] = isset($_POST['anio'])?$_POST['anio']:NULL;
			$data['nombre'] = isset($_POST['nombre'])?$_POST['nombre']:NULL;
			$data['estado'] = isset($_POST['estado'])?$_POST['estado']:NULL;
			$data['situacion'] = isset($_POST['situacion'])?$_POST['situacion']:NULL;
			
			if( $data['estado'] == 'ACT' ){
				//Si el estado de la votacion actual es "activo" se actualizan las demas votaciones a inactivas
				$this->db->query("UPDATE votacion SET estado = 'INA' WHERE 1 = 1", array());
			}
			
			$this->db->insert("votacion", $data);
				
			$resCli = $this->db->query('SELECT MAX(votacion_id) votacion_id FROM votacion ', array());
			$resCli = $resCli->result_array();
			$resCli = $resCli[0];
				
			return $resCli['votacion_id'];
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	
	function updVotacion() {
		try {
			$data['anio'] = isset($_POST['anio'])?$_POST['anio']:NULL;
			$data['nombre'] = isset($_POST['nombre'])?$_POST['nombre']:NULL;
			$data['estado'] = isset($_POST['estado'])?$_POST['estado']:NULL;
			$data['situacion'] = isset($_POST['situacion'])?$_POST['situacion']:NULL;
			
			if( $data['estado'] == 'ACT' ){
				//Si el estado de la votacion actual es "activo" se actualizan las demas votaciones a inactivas
				$this->db->query("UPDATE votacion SET estado = 'INA' WHERE 1 = 1", array());
			}
			
			$this->db->where('votacion_id', $_POST['votacion_id']);
			$this->db->update("votacion", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delVotacion() {
		try {
			$this->db->where("votacion_id", $_POST['votacion_id']);
			$this->db->delete("votacion");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file votacion_model.php*/
