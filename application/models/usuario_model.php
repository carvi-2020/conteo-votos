<?php 

class Usuario_model extends CI_model {
	function __construct() {
		parent::__construct();	
		$this->load->model('utils_model');	
	}
	
	function lstUsuarios($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT idusr, nomusuario, DATE_FORMAT(ultimologin, '%d/%m/%Y %k:%i:%s') ultimologin,
			CASE estado WHEN 'ACT' THEN 'Activo' WHEN 'INA' THEN 'Inactivo' END AS estado, nomcompleto, intentoslogin,
			(SELECT nombre FROM rol rl WHERE rl.idrol = usuario.idrol) nomrol
			FROM usuario WHERE 1 = 1 " . $fltWhere . " ORDER BY nomusuario ASC", array());
		return $rs;
	}
	
	function loadUsuario($idusr) {
		$data = array();
		$rs = $this->db->query("SELECT idusr, nomusuario, DATE_FORMAT(ultimologin, '%d/%m/%Y %k:%i:%s') ultimologin,
			estado, nomcompleto, intentoslogin, idrol, 
			(SELECT nombre FROM rol rl WHERE rl.idrol = usuario.idrol) nomrol
			
			FROM usuario WHERE idusr = ?  ", array($idusr));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}
	
	function insUsuario() {
		$this->load->library('encrypt');
		
		try {
			$data = array();
			$data[] = $_POST['nomusuario'];
			$data[] = $this->encrypt->sha1($_POST['clave']);
			$data[] = $_POST['nomcompleto'];
			$data[] = $_POST['estado'];
			$data[] = $_POST['idrol'];
			$this->db->query("INSERT INTO usuario(nomusuario, clave, nomcompleto, 
				estado, idrol) VALUES (?, ?, ?, ?, ?)", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}

	function cambiarClaveUsr($data = NULL) {
		try {
			$data['clave'] = $data['clave'];
			$this->db->where('idusr', $data['idusr']);
			$this->db->update("usuario", $data);
			return TRUE;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return FALSE;
		}	
	}
	
	function updUsuario() {
		$this->load->library('encrypt');
		try {			
			$data = array('nomusuario' => $_POST['nomusuario'], 
							'nomcompleto' => $_POST['nomcompleto'],
							'estado' => $_POST['estado'],
							'idrol' => $this->utils_model->getData($_POST['idrol']));
			if($_POST['clave'] != null && $_POST['clave'] != '')
				$data['clave'] = $this->encrypt->sha1($_POST['clave']);
							
			$where = "idusr = " . $_POST['idusr'] . " "; 
			$str = $this->db->update_string('usuario', $data, $where);
			$this->db->query($str);
			
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delUsuario() {
		try {
			$this->db->where("idusr = ", $_POST['idusr']);
			$this->db->delete("usuario");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file rolmodel.php*/
