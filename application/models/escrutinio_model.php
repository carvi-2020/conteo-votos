<?php 

class Escrutinio_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function resPorPlanilla($lstFiltros = NULL) {
		
	}
	
	function votsPorCargoPlanilla( $votacion_id, $cargopost_id, $planilla_id ) {
		$votsPorCargoPlanilla = $this->db->query("SELECT * 
			FROM conteo_votacion WHERE 1 = 1 
			AND votacion_id = ?
			AND cargopost_id = ?
			AND planilla_id = ?", array( $votacion_id, $cargopost_id, $planilla_id ));
			
		if( $votsPorCargoPlanilla->num_rows() > 0 ) {
			$votsPorCargoPlanilla = $votsPorCargoPlanilla->result_array();
			$votsPorCargoPlanilla = $votsPorCargoPlanilla[0];
			
			return $votsPorCargoPlanilla;
		}else{
			return NULL;
		}
	}
}

/* End of file candidato_model.php*/
