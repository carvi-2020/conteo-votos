<?php 

class CentroVotacion_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function lstCentroVotaciones($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT * 
			FROM centro_votacion WHERE 1 = 1 " . $fltWhere . " ORDER BY nombre ASC", array());
		return $rs;
	}
	
	function lstSocios($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT * 
			FROM socio WHERE 1 = 1 " . $fltWhere . " ORDER BY nombres ASC", array());
		return $rs;
	}	
	
	function loadCentroVotacion($centroVotacion_id) {
		$data = array();
		$rs = $this->db->query("SELECT *
			FROM centro_votacion WHERE centrovot_id = ? ", array($centroVotacion_id));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}
	
	function comboCentrosVotacion() {
		$lst_final = array();
		$rs = $this->db->query("SELECT centrovot_id, nombre
			FROM centro_votacion WHERE 1 = 1 AND estado = 'ACT' ", array());
		
		$data = array();
		if($rs->num_rows() > 0) 
			$data = $rs->result_array();
		
		foreach ($data as $ctv) 
			$lst_final[$ctv['centrovot_id']] = $ctv['nombre'];
		
		return $lst_final;
	}
	
	function insCentroVotacion() {		
		try {
			$data = array();

			$data['codigo'] = isset($_POST['codigo'])?$_POST['codigo']:NULL;
			$data['nombre'] = isset($_POST['nombre'])?$_POST['nombre']:NULL;
			$data['estado'] = isset($_POST['estado'])?$_POST['estado']:NULL;		
			
			$this->db->insert("centro_votacion", $data);
				
			$resCli = $this->db->query('SELECT MAX(centrovot_id) centrovot_id FROM centro_votacion ', array());
			$resCli = $resCli->result_array();
			$resCli = $resCli[0];
				
			return $resCli['centrovot_id'];
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	
	function updCentroVotacion() {
		try {
			$data['codigo'] = isset($_POST['codigo'])?$_POST['codigo']:NULL;
			$data['nombre'] = isset($_POST['nombre'])?$_POST['nombre']:NULL;
			$data['estado'] = isset($_POST['estado'])?$_POST['estado']:NULL;		
						
			$this->db->where('centrovot_id', $_POST['centrovot_id']);
			$this->db->update("centro_votacion", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delCentroVotacion() {
		try {
			$this->db->where("centrovot_id", $_POST['centrovot_id']);
			$this->db->delete("centro_votacion");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file planilla_model.php*/
