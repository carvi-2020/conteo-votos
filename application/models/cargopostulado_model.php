<?php 

class cargopostulado_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function lstCargosPostulados($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT * 
			FROM cargo_postulado WHERE 1 = 1 " . $fltWhere . " ORDER BY orden ASC", array());
		return $rs;
	}
	
	function lstSocios($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtroajax/filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT * FROM socio 
			WHERE 1 = 1 " . $fltWhere . " ORDER BY socio_id ASC", array());
			
		$rs = $rs->result_array();
		return $rs;
	}
	
	function loadCargoPostulado($cargopost_id) {
		$data = array();
		$rs = $this->db->query("SELECT *
			FROM cargo_postulado WHERE cargopost_id = ? ORDER BY orden ASC", array($cargopost_id));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}
	
	function insCargoPostulado() {		
		try {
			$data = array();

			$data['cargopost_id'] = isset($_POST['cargopost_id'])?$_POST['cargopost_id']:NULL;
			$data['nombre'] = isset($_POST['nombre'])?$_POST['nombre']:NULL;
			$data['orden'] = isset($_POST['orden'])?$_POST['orden']:NULL;
			
			$this->db->insert("cargo_postulado", $data);
				
			$resCli = $this->db->query('SELECT MAX(cargopost_id) cargopost_id FROM cargo_postulado ', array());
			$resCli = $resCli->result_array();
			$resCli = $resCli[0];
				
			return $resCli['cargopost_id'];
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	
	function updCargoPostulado() {
		try {
			$data['cargopost_id'] = isset($_POST['cargopost_id'])?$_POST['cargopost_id']:NULL;
			$data['nombre'] = isset($_POST['nombre'])?$_POST['nombre']:NULL;
			$data['orden'] = isset($_POST['orden'])?$_POST['orden']:NULL;
			
			$this->db->where('cargopost_id', $_POST['cargopost_id']);
			$this->db->update("cargo_postulado", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delCargoPostulado() {
		try {
			$this->db->where("cargopost_id", $_POST['cargopost_id']);
			$this->db->delete("cargo_postulado");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file cargo_postulado_model.php*/
