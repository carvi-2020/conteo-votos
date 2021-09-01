<?php 

class Pais_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function lstPaises($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT idpai, nombre, iso3, capital, gentilicio 
			FROM fl_pais WHERE 1 = 1 " . $fltWhere . " ORDER BY nombre ASC", array());
		return $rs;
	}
	
	function loadPais($idpai) {
		$data = array();
		$rs = $this->db->query("SELECT idpai, nombre, iso3, iso2, capital, 
			gentilicio, referencia_mapa, moneda  
			FROM fl_pais WHERE idpai = ? ", array($idpai));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}
	
	function insPais() {		
		try {
			$data = array();
			$data['nombre'] = $_POST['nombre'];
			$data['iso3'] = $_POST['iso3'];
			$data['iso2'] = isset($_POST['iso3'])?$_POST['iso3']:NULL;
			$data['capital'] = isset($_POST['capital'])?$_POST['capital']:NULL;
			$data['gentilicio'] = isset($_POST['gentilicio'])?$_POST['gentilicio']:NULL;
			$data['referencia_mapa'] = isset($_POST['referencia_mapa'])?$_POST['referencia_mapa']:NULL;
			$data['moneda'] = isset($_POST['moneda'])?$_POST['moneda']:NULL;		
			
			$this->db->insert("fl_pais", $data);
				
			$resCli = $this->db->query('SELECT MAX(idpai) idpai FROM fl_pais ', array());
			$resCli = $resCli->result_array();
			$resCli = $resCli[0];
				
			return $resCli['idpai'];
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	
	function updPais() {
		try {
			$data['nombre'] = $_POST['nombre'];
			$data['iso3'] = $_POST['iso3'];
			$data['iso2'] = isset($_POST['iso3'])?$_POST['iso3']:NULL;
			$data['capital'] = isset($_POST['capital'])?$_POST['capital']:NULL;
			$data['gentilicio'] = isset($_POST['gentilicio'])?$_POST['gentilicio']:NULL;
			$data['referencia_mapa'] = isset($_POST['referencia_mapa'])?$_POST['referencia_mapa']:NULL;
			$data['moneda'] = isset($_POST['moneda'])?$_POST['moneda']:NULL;
			
			$this->db->where('idpai', $_POST['idpai']);
			$this->db->update("fl_pais", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delPais() {
		try {
			$this->db->where("idpai", $_POST['idpai']);
			$this->db->delete("fl_pais");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file pais_model.php*/
