<?php 

class Planilla_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function lstPlanillas($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT * 
			FROM planilla WHERE 1 = 1 " . $fltWhere . " ORDER BY planilla_id ASC", array());
		return $rs;
	}

	/* Mantenimiento: hacer una lista para mostrar la lista de planillas que existen 13/11/2015 */
	function lstPlanillasx($lstFiltros = NULL) {
		$fltWhere = '';

		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}

		$rs = $this->db->query("SELECT *
			FROM planilla WHERE 1 = 1  AND estado='ACT'  " . $fltWhere . " ORDER BY planilla_id ASC", array());
		return $rs;
	}
	
	function loadPlanilla($planilla_id) {
		$data = array();
		$rs = $this->db->query("SELECT *
			FROM planilla WHERE planilla_id = ? ORDER BY planilla_id ASC", array($planilla_id));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}
	
	function insPlanilla() {		
		try {
			$data = array();

			$data['codigo'] = isset($_POST['codigo'])?$_POST['codigo']:NULL;
			$data['nombre'] = isset($_POST['nombre'])?$_POST['nombre']:NULL;
			$data['estado'] = isset($_POST['estado'])?$_POST['estado']:NULL;		
			$data['votacion_id'] = isset($_POST['votacion_id'])?$_POST['votacion_id']:NULL;
			
			$this->db->insert("planilla", $data);
				
			$resCli = $this->db->query('SELECT MAX(planilla_id) planilla_id FROM planilla ', array());
			$resCli = $resCli->result_array();
			$resCli = $resCli[0];
				
			return $resCli['planilla_id'];
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	
	function updPlanilla() {
		try {
			$data['codigo'] = isset($_POST['codigo'])?$_POST['codigo']:NULL;
			$data['nombre'] = isset($_POST['nombre'])?$_POST['nombre']:NULL;
			$data['estado'] = isset($_POST['estado'])?$_POST['estado']:NULL;
			$data['votacion_id'] = isset($_POST['votacion_id'])?$_POST['votacion_id']:NULL;
			
			$this->db->where('planilla_id', $_POST['planilla_id']);
			$this->db->update("planilla", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delPlanilla() {
		try {
			$this->db->where("planilla_id", $_POST['planilla_id']);
			$this->db->delete("planilla");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file planilla_model.php*/
