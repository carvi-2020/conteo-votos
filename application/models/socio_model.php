<?php 

class Socio_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function lstSocios($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT * 
			FROM socio WHERE 1 = 1 " . $fltWhere . " ORDER BY socio_id ASC", array());
		return $rs;
	}
	
	function loadSocio($socio_id) {
		$data = array();
		$rs = $this->db->query("SELECT * 
			FROM socio WHERE socio_id = ? ", array($socio_id));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}	
	
	function insSocio() {		
		try {
			$data = array();

			$data['nombres'] = isset($_POST['nombres'])?$_POST['nombres']:NULL;
			$data['apellidos'] = isset($_POST['apellidos'])?$_POST['apellidos']:NULL;
			$data['codigo'] = isset($_POST['codigo'])?$_POST['codigo']:NULL;
			$data['jvpm'] = isset($_POST['jvpm'])?$_POST['jvpm']:NULL;
			$data['dui'] = isset($_POST['dui'])?$_POST['dui']:NULL;
						
			$this->db->insert("socio", $data);
				
			$resCli = $this->db->query('SELECT MAX(socio_id) socio_id FROM socio ', array());
			$resCli = $resCli->result_array();
			$resCli = $resCli[0];
				
			return $resCli['socio_id'];
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	
	function updSocio() {
		try {
			$data['nombres'] = isset($_POST['nombres'])?$_POST['nombres']:NULL;
			$data['apellidos'] = isset($_POST['apellidos'])?$_POST['apellidos']:NULL;
			$data['codigo'] = isset($_POST['codigo'])?$_POST['codigo']:NULL;
			$data['jvpm'] = isset($_POST['jvpm'])?$_POST['jvpm']:NULL;
			$data['dui'] = isset($_POST['dui'])?$_POST['dui']:NULL;
			
			$this->db->where('socio_id', $_POST['socio_id']);
			$this->db->update("socio", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delSocio() {
		try {
			$this->db->where("socio_id", $_POST['socio_id']);
			$this->db->delete("socio");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file socio_model.php*/
