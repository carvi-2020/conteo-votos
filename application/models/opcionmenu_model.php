<?php 

class OpcionMenu_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function lstOpcionesMenu( $lstFiltros = NULL ){
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT idopm, etiqueta, url, orden, opcion_padre, 
			(SELECT etiqueta FROM opcion_menu opp WHERE oph.opcion_padre = opp.idopm) nombre_padre 
			FROM opcion_menu oph WHERE 1 = 1" . $fltWhere . " ORDER BY orden ASC", array());
		return $rs;
	}
	
	function lstOpcionesMenuAsoc($data = NULL) {
		//Filtramos las opciones de menu de un rol hijas
		$rs = $this->db->query("SELECT idopm, etiqueta, url, opcion_padre,
				(SELECT etiqueta FROM opcion_menu opp WHERE opcion_menu.opcion_padre = opp.idopm) nombre_padre ,
				IFNULL((SELECT 'TRUE' FROM rol_opm WHERE rol_opm.idrol = ? AND rol_opm.idopm = opcion_menu.idopm), 'FALSE') asociado
				FROM opcion_menu WHERE opcion_padre IS NOT NULL ORDER BY orden ASC ", array($data['idrol']));
		return $rs->result_array();
	}
	
	function lstOpcionesMenuPapa() {
		$rs = $this->db->query("SELECT idopm, etiqueta, url, orden
			FROM opcion_menu oph WHERE 1 = 1 AND opcion_padre IS NULL ORDER BY orden ASC", array());
		return $rs;
	}
	
	function loadOpcionMenu($idopm) {
		$data = array();
		$rs = $this->db->query("SELECT idopm, etiqueta, url, orden, opcion_padre,
			(SELECT etiqueta FROM opcion_menu opp WHERE oph.opcion_padre = opp.idopm) nombre_padre 
			FROM opcion_menu oph WHERE idopm = ? ", array($idopm));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		
		return $data;
	}
	
	function insOpcionMenu() {
		try {
			$data = array();
			$data['etiqueta'] = $_POST['etiqueta'];
			$data['url'] = $_POST['url'];
			$data['orden'] = $_POST['orden'];
			if(isset($_POST['opcion_padre']) && $_POST['opcion_padre'] != '0' && $_POST['opcion_padre'] != '')
				$data['opcion_padre'] = $_POST['opcion_padre'];
				
			$this->db->insert("opcion_menu", $data);
			
			return TRUE;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return FALSE;
		}
	}
	
	function updOpcRol($data = NULL) {
		try {
		//Iteramos sobre los idopm para ir insertando o actualizando registros
		$cnt = 0;
			foreach($data['idopm'] as $opcion) {
				$this->db->where("idopm", $opcion); 
				$this->db->where("idrol", $_POST['idrol']); 
				$this->db->delete('rol_opm');
				
				if(isset($data['asociado'][$cnt]) && $data['asociado'][$cnt] == 'TRUE') {
					$dataIns = array('idrol' => $_POST['idrol'], 
									'idopm' => $opcion,
									);
					$this->db->insert('rol_opm', $dataIns);
					
				}
				$cnt++;
			} 
			return TRUE;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return FALSE;
		}
		
	}
	
	function updOpcionMenu() {
		try {
			$data['etiqueta'] = $_POST['etiqueta'];
			$data['url'] = $_POST['url'];
			$data['orden'] = $_POST['orden'];
			if(isset($_POST['opcion_padre']) && $_POST['opcion_padre'] != '')
				$data['opcion_padre'] = $_POST['opcion_padre'];
			
			$this->db->where("idopm", $_POST['idopm']); 
			
			$this->db->update("opcion_menu", $data);
			
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delOpcionMenu() {
		try {
			$this->db->where("idopm = ", $_POST['idopm']);
			$this->db->delete("opcion_menu");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file opcionmenu_model.php*/
