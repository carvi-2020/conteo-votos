<?php 

class Login_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function verificarUsuario($flt) {
		$data = null;
		
		$rs = $this->db->query("SELECT ur.idusr, ur.nomusuario, ur.estado, 
			ur.nomcompleto, rl.nombre nomrol, rl.idrol idrol, edit_emb, edit_arr, edit_doc 
			FROM usuario ur, rol rl WHERE ur.idrol = rl.idrol AND ur.nomusuario = ? AND clave = ?", $flt);
			
		if($rs->num_rows() > 0) {
			foreach($rs->result_array() as $row) {
				$data['idusr'] = $row['idusr'];
				$data['nomusuario'] = $row['nomusuario'];
				$data['estado'] = $row['estado'];
				$data['nomcompleto'] = $row['nomcompleto'];
				$data['idrol'] = $row['idrol'];
				$data['nomrol'] = $row['nomrol'];
				$data['edit_arr'] = $row['edit_arr'];
				$data['edit_emb'] = $row['edit_emb'];
				$data['edit_doc'] = $row['edit_doc'];
			}
		}
		return $data;
	}
	
	function getMenuUsuario($idusr) {
		$data = array();
		//Consultamos los menus principales en base al rol que tengan
		/*$rs = $this->db->query("SELECT om.idopm idopm, om.etiqueta etiqueta, om.url url 
				FROM opcion_menu om, 
				rol rl, rol_opm ro, usuario ur WHERE rl.idrol = ro.idrol AND ro.idopm = om.idopm AND ur.idrol = rl.idrol 
				AND ur.idusr = ? AND om.opcion_padre IS NULL ORDER BY om.orden ASC ", array($idusr));*/
		$rs = $this->db->query("SELECT DISTINCT om.idopm idopm, om.etiqueta etiqueta, om.url url 
				FROM opcion_menu om WHERE EXISTS (SELECT 1 FROM opcion_menu om2, 
				rol rl, rol_opm ro, usuario ur WHERE rl.idrol = ro.idrol AND ro.idopm = om2.idopm AND ur.idrol = rl.idrol 
				AND ur.idusr = ? AND om2.opcion_padre = om.idopm ) AND om.opcion_padre IS NULL ORDER BY om.orden ASC ", array($idusr));
		if($rs->num_rows() > 0) {
			foreach($rs->result_array() as $row) {
				//Obtenemos las sub opciones
				$rsSub = $this->db->query("SELECT om.idopm idopm, om.etiqueta etiqueta, om.url url FROM opcion_menu om, 
				rol rl, rol_opm ro, usuario ur WHERE rl.idrol = ro.idrol AND ro.idopm = om.idopm AND ur.idrol = rl.idrol 
				AND ur.idusr = ? AND om.opcion_padre = ? ORDER BY om.orden ASC ", array($idusr, $row['idopm'])); 
				
				$opcHijos = NULL;
				if($rsSub->num_rows() > 0) {
					foreach($rsSub->result_array() as $rowH) {
						$opcHijos[] = array('idopm' => $rowH['idopm'],
								'etiqueta' => $rowH['etiqueta'],
								'url' => $rowH['url'],
								'padre' => $row['idopm']);
					}
				}
				$data[] = array('idopm' => $row['idopm'],
								'etiqueta' => $row['etiqueta'],
								'url' => $row['url'],
								'hijos' => $opcHijos);
			}
		}
		return $data;
	}
	
	function updIngresoUsr($data = NULL) {
		try {
			$data['intentoslogin'] = 0;
			$data['ultimologin'] = date('Y-m-d H:i:s');
			$this->db->where('idusr', $data['idusr']);
			$this->db->update("usuario", $data);
			return TRUE;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return FALSE;
		}	
	}
	
}

/* End of file login_model.php*/
