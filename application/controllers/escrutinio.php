<?php 
class Escrutinio extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('escrutinio_model');
		$this->load->model('planilla_model');
		$this->load->model('cargopostulado_model');
		$this->load->model('votacion_model');
	}
	
	function loadIframe() {
		?>
			<!--<iframe id="idIframe" style="border:0px; width: 100%;" src="<?php echo site_url('escrutinio/lstResults/'); ?>"></iframe>-->
			<iframe id="idIframe" style="border:0px; width: 100%;" src="<?php echo site_url('escrutinio/lstVotosPorCentro/'); ?>"></iframe>
		<?php
	}
	
	function loadEscrutinio( $numRow = 0, $dataCur = NULL ) {
		$data = isset($dataCur)?$dataCur:array();
		$this->load->view('votacion/escrutinio/default.php', $data);
	}
	
	function lstVotPlanilla( $numRow = 0, $dataCur = NULL, $url = NULL ) {
		$data = array();
		$sql = "";
		
		$this->load->model('cargoPostulado_model');
		$this->load->model('planilla_model');
		$this->load->model('votacion_model');
		
		//Obteniendo el listado de cargos
		$lstCargosPostulados = $this->cargoPostulado_model->lstCargosPostulados();
		$lstCargosPostulados = $lstCargosPostulados->result_array();
		$data['lstCargosPostulados'] = $lstCargosPostulados;
		
		//Obteniendo el listado de las planillas
		$lstPlanillas = $this->planilla_model->lstPlanillas();
		$lstPlanillas = $lstPlanillas->result_array();
		$data['lstPlanillas'] = $lstPlanillas;
		
		//Obteniendo la votacion activa
		$votacion = $this->votacion_model->getVotacionAct();
		if( $votacion != NULL ){
			$data['nombre_votacion'] = $votacion['nombre'];
			$data['votacion_id'] = $votacion['votacion_id'];	
		}else{
			return;
		}
		
		$tabla[] = array();
		if( is_array($lstCargosPostulados) ){
			foreach($lstCargosPostulados as $fila) {
				$tabla1 = array();
				$tabla1[] = $fila['nombre'];
				
				if( is_array($lstPlanillas) ){
					foreach($lstPlanillas as $fila1) {
						//Obteniendo el total de votos por planilla
						$votoPnll = $this->db->query("SELECT COUNT(*) AS totalPnll
							FROM det_votacion_socio WHERE 1 = 1 
							AND cargopost_id = ?
							AND planilla_id = ?
							AND votacion_id = ?", 
							array( $fila['cargopost_id'], $fila1['planilla_id'], $data['votacion_id'] ));
						
						if($votoPnll->num_rows() > 0) {
							$votoPnll = $votoPnll->result_array();
							$votoPnll = $votoPnll[0];
							$tabla1[] = $votoPnll['totalPnll'];
						}else{
							$tabla1[] = 0;
						}			
					}//Fin foreach planilla
					
					//Obteniendo totales para el tipo de voto abstenido para el cargo actual
					$votoAbs = $this->db->query("SELECT COUNT(*) AS votoAbs
						FROM det_votacion_socio WHERE 1 = 1 
						AND cargopost_id = ?
						AND votacion_id = ?
						AND tipo_voto = '3'", 
						array( $fila['cargopost_id'], $data['votacion_id'] ));
						
					if($votoAbs->num_rows() > 0) {
						$votoAbs = $votoAbs->result_array();
						$votoAbs = $votoAbs[0];
						$tabla1[] = $votoAbs['votoAbs'];
					}else{
						$tabla1[] = 0;
					}
					
					//Obteniendo totales para el tipo de voto nulo para el cargo actual
					$votoNull = $this->db->query("SELECT COUNT(*) AS votoNull
						FROM det_votacion_socio WHERE 1 = 1 
						AND cargopost_id = ?
						AND votacion_id = ?
						AND tipo_voto = '2'", 
						array( $fila['cargopost_id'], $data['votacion_id'] ));
						
					if($votoNull->num_rows() > 0) {
						$votoNull = $votoNull->result_array();
						$votoNull = $votoNull[0];
						$tabla1[] = $votoNull['votoNull'];
					}else{
						$tabla1[] = 0;
					}
					
					//Obteniendo totales para el tipo de voto normal para el cargo actual
					$votoNor = $this->db->query("SELECT COUNT(*) AS votoNor
						FROM det_votacion_socio WHERE 1 = 1 
						AND cargopost_id = ?
						AND votacion_id = ?
						AND (tipo_voto = '1' OR tipo_voto = '3') ", 
						array( $fila['cargopost_id'], $data['votacion_id'] ));
						
					if($votoNor->num_rows() > 0) {
						$votoNor = $votoNor->result_array();
						$votoNor = $votoNor[0];
						$tabla1[] = $votoNor['votoNor'];
					}else{
						$tabla1[] = 0;
					}					
				}				
				
				$tabla[] = $tabla1;
			}//Fin foreach cargos
			
			$data['tabla'] = $tabla;
		}
		
		$this->load->view('votacion/escrutinio/votosPorPlanillaTbl.php', $data);
	}
	
	function lstVotosPorCentro( $numRow = 0, $dataCur = NULL ) {
		$data = isset($dataCur)?$dataCur:array();
		
		$sql = "";
		
		//Obteniendo la votacion activa
		$this->load->model('votacion_model');
		$votacion = $this->votacion_model->getVotacionAct();
		if( $votacion != NULL ){
			$data['nombre_votacion'] = $votacion['nombre'];
			$data['votacion_id'] = $votacion['votacion_id'];	
		}else{
			return;
		}
		
		//Si esta cargando el form solo inicializamos listas, si no cargamos el PDF
		$votosPorCentro = $this->db->query("SELECT centrovot_id, 
			COUNT(centrovot_id) AS num_votos, 
			(SELECT nombre FROM centro_votacion WHERE centro_votacion.centrovot_id = votacion_socio.centrovot_id)  AS nombre_centro
			FROM votacion_socio WHERE 1 = 1
			AND EXISTS (SELECT * FROM det_votacion_socio WHERE votsoc_id = votacion_socio.votsoc_id AND votacion_id = ?) 
			GROUP BY centrovot_id;", array( $data['votacion_id'] ));
			
		if( $votosPorCentro->num_rows() > 0 ) {	
			$votosPorCentro = $votosPorCentro->result_array();
			$data['votosPorCentro'] = $votosPorCentro;
			
			$totalDeVotos = 0;
			if( is_array($votosPorCentro) ){
				foreach($votosPorCentro as $fila) {
					$totalDeVotos = $totalDeVotos + $fila['num_votos'];
				}
			}
			
			$data['totalDeVotos'] = $totalDeVotos;			
		}
		
		$this->load->view('votacion/escrutinio/votosPorCentro.php', $data);
	}
	
	function lstResults( $numRow = 0, $dataCur = NULL ) {
		$data = isset($dataCur)?$dataCur:array();
		
		//Obteniendo el listado de las planillas
		$lstPlanillas = $this->planilla_model->lstPlanillas();
		$lstPlanillas = $lstPlanillas->result_array();
		$data['lstPlanillas'] = $lstPlanillas;
		
		//Obteniendo el listado de cargos
		$lstCargosPostulados = $this->cargopostulado_model->lstCargosPostulados();
		$lstCargosPostulados = $lstCargosPostulados->result_array();
		$data['lstCargosPostulados'] = $lstCargosPostulados;
		
		//Obteniendo la votacion activa
		$votacion = $this->votacion_model->getVotacionAct();
		if( $votacion != NULL ){
			$data['nombre_votacion'] = $votacion['nombre'];
			$data['votacion_id'] = $votacion['votacion_id'];	
		}
		
		//$tabla[] = array();
		if( is_array($lstPlanillas) ){
			foreach($lstPlanillas as $filaPlanilla) {
				
				if( is_array($lstCargosPostulados) ){
					foreach($lstCargosPostulados as $filaCargo) {
						$tabla1 = array();
						$tabla1[] = $filaPlanilla['planilla_id'];
						$tabla1[] = $filaPlanilla['nombre'];
						
						//Obteniendo el total de votos por cargo y por planilla
						$votsPorCargoPlanilla = $this->escrutinio_model->votsPorCargoPlanilla( $data['votacion_id'], $filaCargo['cargopost_id'], $filaPlanilla['planilla_id'] );
						if( $votsPorCargoPlanilla != NULL ){
							$tabla1[] = $filaCargo['nombre'];
							$tabla1[] = $votsPorCargoPlanilla['votos'];
						}else{
							$tabla1[] = $filaCargo['nombre'];
							$tabla1[] = 0;
						}
						
						$tabla[] = $tabla1;
					}//Fin foreach cargos postulados
				}//Fin if cargos postulados
				
			}
		}//Fin planillas
		
		$data['tabla'] = $tabla;
		$this->load->view('votacion/escrutinio/list.php', $data);
	}
}

/* End of file planilla.php */
/* Location: application/controllers/ */
