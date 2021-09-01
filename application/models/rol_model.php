<?php 

class Rol_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function lstRoles($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT idrol, nombre, edit_emb, edit_doc, edit_arr, 
			CASE estado WHEN 'ACT' THEN 'Activo' WHEN 'INA' THEN 'Inactivo' END AS estado 
			FROM rol WHERE 1 = 1 " . $fltWhere . " ORDER BY idrol ASC", array());
		return $rs;
	}
	
	function lstRolesEst($lstFiltros = NULL, $estado = NULL) {
		
		$fltWhere = '';
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		if($estado != null)
			$fltWhere .= " AND estado = '" . $estado . "' ";
		
		$rs = $this->db->query("SELECT idrol, nombre, edit_emb, edit_doc, edit_arr, 
			CASE estado WHEN 'ACT' THEN 'Activo' WHEN 'INA' THEN 'Inactivo' END AS estado 
			FROM rol WHERE 1 = 1 " . $fltWhere . " ORDER BY idrol ASC", array());
		return $rs;
	}
	
	function loadRol($idrol) {
		$data = array();
		$rs = $this->db->query("SELECT idrol, nombre, estado, edit_emb, edit_doc, edit_arr 
							FROM rol WHERE idrol = ? ", array($idrol));
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}
	
	function insRol() {
		try {
			$data = array();
			$data[] = $_POST['nombre'];
			$data[] = $_POST['estado'];
			$data[] = NULL;
			$data[] = NULL;
			$data[] = NULL;
			$this->db->query("INSERT INTO rol (nombre, estado, edit_emb, edit_doc, edit_arr) " . " VALUES (?, ?, ?, ?, ?)", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	
	function updRol() {
		try {
			$data['nombre'] = $_POST['nombre'];
			$data['estado'] = $_POST['estado'];
			$data['edit_emb'] = NULL;
			$data['edit_doc'] = NULL;
			$data['edit_arr'] = NULL;
			$this->db->where('idrol', $_POST['idrol']);
			$this->db->update("rol", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delRol() {
		try {
			$this->db->where("idrol =", $_POST['idrol']);
			$this->db->delete("rol");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file rol_model.php*/