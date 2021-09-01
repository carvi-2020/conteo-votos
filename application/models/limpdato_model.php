<?php 

class Limpdato_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function delVotsAbst(){
		try {	
			$rs = $this->db->query("DELETE FROM det_votacion_socio WHERE tipo_voto = 3", array());
			return TRUE;
		} catch(Exception $ex) {
			return FALSE;
		}
	}
	
	function delVotsNmls(){
		try {	
			$rs = $this->db->query("DELETE FROM det_votacion_socio WHERE tipo_voto = 1", array());
			return TRUE;
		} catch(Exception $ex) {
			return FALSE;
		}
	}
	
	function delVotsNulos(){
		try {	
			$rs = $this->db->query("DELETE FROM det_votacion_socio WHERE tipo_voto = 2", array());
			return TRUE;
		} catch(Exception $ex) {
			return FALSE;
		}
	}
	
	function delPdronElec(){
		try {	
			$rs = $this->db->query("DELETE FROM padron_electoral WHERE 1 = 1", array());
			return TRUE;
		} catch(Exception $ex) {
			return FALSE;
		}
	}
	
	function delCandidatos(){
		try {	
			$rs = $this->db->query("DELETE FROM candidato WHERE 1 = 1", array());
			return TRUE;
		} catch(Exception $ex) {
			return FALSE;
		}
	}
	
	function delPlanillas(){
		try {	
			$rs = $this->db->query("DELETE FROM planilla WHERE 1 = 1", array());
			return TRUE;
		} catch(Exception $ex) {
			return FALSE;
		}			
	}
	
	function delCentroVot(){
		try {	
			$rs = $this->db->query("DELETE FROM centro_votacion WHERE 1 = 1", array());
			return TRUE;
		} catch(Exception $ex) {
			return FALSE;
		}						
	}
	
	function delCargosPos(){
		try {	
			$rs = $this->db->query("DELETE FROM cargo_postulado WHERE 1 = 1", array());
			return TRUE;
		} catch(Exception $ex) {
			return FALSE;
		}				
	}																								
}
/* End of file planilla_model.php*/
