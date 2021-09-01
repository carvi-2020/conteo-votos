<?php 

class VotacionSocio_model extends CI_Model {
	function __construct() {
		parent::__construct();		
	}
	
	function lstVotacionesSocio($lstFiltros = NULL) {
		$fltWhere = '';
		
		if($lstFiltros != null) {
			$this->load->helper('filtro');
			$fltWhere = formarCondicionFiltro($lstFiltros);
		}
		
		$rs = $this->db->query("SELECT votsoc_id, DATE_FORMAT(fecha_hora, '%d/%m/%Y %l:%i %p') fecha_hora,
			(SELECT nombre FROM centro_votacion WHERE centro_votacion.centrovot_id = votacion_socio.centrovot_id) AS nombre_centro
			FROM votacion_socio WHERE 1 = 1 " . $fltWhere . " ORDER BY fecha_hora ASC", array());
		return $rs;
	}
	
	function verfcrVotSocio( $idSocio, $votacion_id, $idCentroVot ) {
		$tieneVotacion = FALSE;
		//Verificar si el socio tiene una votacion registrada para el centro de votacion seleccionado
		$rs = $this->db->query("SELECT *,
			(SELECT nombre FROM centro_votacion WHERE centro_votacion.centrovot_id = votacion_socio.centrovot_id) AS nombre_centro
			FROM votacion_socio WHERE 1 = 1 
			AND EXISTS (SELECT * FROM det_votacion_socio WHERE votsoc_id = votacion_socio.votsoc_id AND votacion_id = ?)
			AND centrovot_id = ?", array( $votacion_id, $idCentroVot ));
			
		if($rs->num_rows() > 0) {
			$rs = $rs->result_array();
			$rs = $rs[0];
			return $rs;
		}
			
		return NULL;
	}
	
	function lstDetVotacion($votsoc_id) {
		$rs = $this->db->query("SELECT *
			FROM det_votacion_socio WHERE 1 = 1 AND votsoc_id = ? ORDER BY votsocdet_id ASC", array($votsoc_id));
			
		if($rs->num_rows() > 0) {
			return $rs;
		}
		return NULL;
	}	
	
	function loadVotacionSocio($votsoc_id) {
		$data = array();
		$rs = $this->db->query("SELECT *
			FROM votacion_socio WHERE votsoc_id = ? ", array($votsoc_id));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
			return $data;
		}
		return NULL;
	}
	
	function getVtacnActDet( $votsoc_id ){
		$data = array();
		
		$rs = $this->db->query("SELECT *
			FROM det_votacion_socio WHERE votsoc_id = ? ", array($votsoc_id));
			
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
			
			$votacion_id = $data['votacion_id'];
			//Obtener los datos de la votacion activa
			$rs1 = $this->db->query("SELECT *
				FROM votacion WHERE votacion_id = ? ", array($votacion_id));
			if($rs1->num_rows() > 0) {
				$rs1 = $rs1->result_array();
				$rs1 = $rs1[0];
				
				return $rs1;
			}
		}
		
		return NULL;
	}
	
	function loadSocio( $idSocio ) {
		$data = array();
		$rs = $this->db->query("SELECT *
			FROM socio WHERE socio_id = ? ", array( $idSocio ));
		
		if($rs->num_rows() > 0) {
			$data = $rs->result_array();
			$data = $data[0];
		}
		return $data;
	}
	
	function updConteVotacion( $cargopost_id, $planilla_id, $votacion_id, $tipo_voto ){

		$data = array();
		$datacv = array();

		$datacv['planilla_id'] = $planilla_id;
		$datacv['votacion_id'] = $votacion_id;
		$datacv['cargopost_id'] = $cargopost_id;
		
		try{
			if( isset($planilla_id) ){
				if( $planilla_id > 0 ){
					$planilla_id = $planilla_id;
				}else{
					$planilla_id = NULL;
				}	
			}else{
				$planilla_id = NULL;
			}
			
			if( isset($planilla_id) ){
			$contsVtacns = $this->db->query('SELECT * FROM conteo_votacion WHERE 1 = 1 
			AND planilla_id = ?
			AND votacion_id = ?
			AND cargopost_id = ?', array( $planilla_id, $votacion_id, $cargopost_id ));
			}else{
				$contsVtacns = $this->db->query('SELECT * FROM conteo_votacion WHERE 1 = 1 
					AND planilla_id IS NULL 
					AND votacion_id = ?
					AND cargopost_id = ?', array( $votacion_id, $cargopost_id ));
			}
			
			if($contsVtacns->num_rows() > 0) {
				$contsVtacns = $contsVtacns->result_array();
				$contsVtacns = $contsVtacns[0];
				
				$contvot_id = $contsVtacns['contvot_id'];

				/* Actualizar por tipo de voto */
				if( $tipo_voto == 1 ){
				$data['votos'] = $contsVtacns['votos'];
				$data['votos'] = $data['votos'] + 1;

				/* Vamos a guardar en 1 el tipo y cero los demas */
				$datacv['votos'] = 1;    // la copia de la tabla conteo_votos para el campo votos
				$datacv['nulos'] = 0;
				$datacv['abstenciones']=0;
				}else{
					if( $tipo_voto == 2 ){
						$data['nulos'] = $contsVtacns['nulos'];
						$data['nulos'] = $data['nulos'] + 1;
						/* Vamos a guardar en 1 el tipo y los demas a cero */
						$datacv['nulos'] = 1;
						$datacv['votos'] = 0;
						$datacv['abstenciones'] = 0;
					}else{
						if( $tipo_voto == 3 ){
							$data['abstenciones'] = $contsVtacns['abstenciones'];
							$data['abstenciones'] = $data['abstenciones'] + 1;
							/* Vamos a guardar en 1 el tipo y cero en los demas */
							$datacv['abstenciones'] = 1;
							$datacv['votos'] = 0;
							$datacv['nulos'] = 0;

						}
					}
				}
				
				$this->db->where('contvot_id', $contvot_id);
				$this->db->update("conteo_votacion", $data);

				/* Vamos a insertar registros en la tabla temporal temp_votos*/

				   $datacv['estado']="PEN";
				   $this->db->insert("temp_votacion", $datacv);
				/* fin de insertar registros */

				
				return $contvot_id;
			}else{    // Sino hay registros en conteo votacion entonces inserta
				$data['planilla_id'] = $planilla_id;
				$data['votacion_id'] = $votacion_id;
				$data['cargopost_id'] = $cargopost_id;
				
				if( $tipo_voto == 1 ){
				    $data['votos'] = 1;
					$data['nulos'] = 0;
					$data['abstenciones'] = 0;

					$datacv['votos'] = 1;
					$datacv['nulos'] = 0;
					$datacv['abstenciones'] = 0;

				}else{
					if( $tipo_voto == 2 ){
						$data['votos'] = 0;
						$data['nulos'] = 1;
						$data['abstenciones'] = 0;

						$datacv['votos'] = 0;
						$datacv['nulos'] = 1;
						$datacv['abstenciones'] = 0;
					}else{
						if( $tipo_voto == 3 ){
							$data['votos'] = 0;
							$data['nulos'] = 0;
							$data['abstenciones'] = 1;

							$datacv['votos'] = 0;
							$datacv['nulos'] = 0;
							$datacv['abstenciones'] = 1;
						}
					}
				}
				
				$this->db->insert("conteo_votacion", $data);

				/* Vamos a insertar registros en la tabla temporal temp_votos*/

				$datacv['estado']="PEN";
				$this->db->insert("temp_votacion", $datacv);
				/* fin de insertar registros */
					
				$resCli = $this->db->query('SELECT MAX(contvot_id) contvot_id FROM conteo_votacion ', array());
				$resCli = $resCli->result_array();
				$resCli = $resCli[0];

				return $resCli['contvot_id'];
			}
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}


	/* Aqui es donde guarda el detalle de la votacion */
	function guardarDetVot( $votsoc_id, $cargopost_id, $planilla_id, $tipo_voto, $votacion_id ) {
		try {
			$data = array();
			
			$data['votsoc_id'] = isset($votsoc_id)?$votsoc_id:NULL;
			$data['cargopost_id'] = isset($cargopost_id)?$cargopost_id:NULL;
			
			//if(isset($planilla_id) && trim($planilla_id) != 'undefined' && trim($planilla_id) != '')
			if( isset($planilla_id) ){
				if( $planilla_id > 0 ){
					$data['planilla_id'] = $planilla_id;	
				}else{
					$data['planilla_id'] = NULL;
				}	
			}else{
				$data['planilla_id'] = NULL;
			}	
			
			$data['tipo_voto'] = isset($tipo_voto)?$tipo_voto:NULL;
			$data['votacion_id'] = isset($votacion_id)?$votacion_id:NULL;
			
			$this->db->insert("det_votacion_socio", $data);
					
			$resCli = $this->db->query('SELECT MAX(votsocdet_id) votsocdet_id FROM det_votacion_socio ', array());
			$resCli = $resCli->result_array();
			$resCli = $resCli[0];
			
			$data1 = array();
			$data1['tn_det'] = '1';
			
			$this->db->where('votsoc_id', $votsoc_id);
			$this->db->update("votacion_socio", $data1);   //Actualizacion
				
			return $resCli['votsocdet_id'];
		} catch(Exception $ex) {
			//echo $ex->getMessage();
			return FALSE;
		}
	}

	function estadoAnterior( $op , $opp){
		return $op;
	}

	function insVotacionSocioAjx( $centrovot_id, $votacion_id ) {
		$socio_id = "";
		date_default_timezone_set('America/El_Salvador');
		try {
			$data = array();

			$data['centrovot_id'] = isset($centrovot_id)?$centrovot_id:NULL;
			$data['votacion_id'] = isset($votacion_id)?$votacion_id:NULL;
			
			//Obteniendo la fecha y hora del sistema
			$data['fecha_hora'] = $nowFormat = date('Y-m-d H:i:s');
			
			//Obteniendo el usuario activo
			$this->load->library('session');
			$usuario = $this->session->userdata('usuario');
			if( isset($usuario['idusr']) ){
				$data['usuario_id'] = $usuario['idusr'];	
			}else{
				$data['usuario_id'] = 0;
			}
			
			$data['tn_det'] = '0';
			
			$this->db->insert("votacion_socio", $data);  // Guarda en votacion socio pero no en det_votacion_socio
				
			$resCli = $this->db->query('SELECT MAX(votsoc_id) votsoc_id FROM votacion_socio ', array());
			$resCli = $resCli->result_array();
			$resCli = $resCli[0];
				
			return $resCli['votsoc_id'];
		} catch(Exception $ex) {
			return FALSE;
		}
	}
	
	function insVotacionSocio() {		
		try {
			$data = array();
			date_default_timezone_set('America/El_Salvador');
			$data['centrovot_id'] = isset($_POST['centrovot_id'])?$_POST['centrovot_id']:NULL;
			$data['votacion_id'] = isset($_POST['votacion_id'])?$_POST['votacion_id']:NULL;
						
			//Obteniendo la fecha y hora del sistema
			$data['fecha_hora'] = $nowFormat = date('Y-m-d H:i:s');
			
			//Obteniendo el usuario activo
			$this->load->library('session');
			$usuario = $this->session->userdata('usuario');
			if( isset($usuario['idusr']) ){
				$data['usuario_id'] = $usuario['idusr'];	
			}else{
				$data['usuario_id'] = 0;
			}
			
			$data['tn_det'] = '0';
			
			$this->db->insert("votacion_socio", $data);
				
			$resCli = $this->db->query('SELECT MAX(votsoc_id) votsoc_id FROM votacion_socio ', array());
			$resCli = $resCli->result_array();
			$resCli = $resCli[0];
				
			return $resCli['votsoc_id'];
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	
	function updVotacionSocio() {
		try {
			$data['centrovot_id'] = isset($_POST['centrovot_id'])?$_POST['centrovot_id']:NULL;
			
			$this->db->where('votsoc_id', $_POST['votsoc_id']);
			$this->db->update("votacion_socio", $data);
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
	
	function delVotacionSocio() {
		try {
			$this->db->where("votsoc_id", $_POST['votsoc_id']);
			$this->db->delete("votacion_socio");
			return true;
		} catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}	
	}
}

/* End of file votacionsocio_model.php*/
