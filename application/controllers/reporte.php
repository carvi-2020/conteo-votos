<?php
class Reporte extends MY_Controller {

	function __construct() {
		parent::__construct();
	}

	function firstOfMonth() {
		return date("d/m/Y", strtotime(date('m') . '/01/' . date('Y')));
	}

	function lastOfMonth() {
		return date("d/m/Y", strtotime('-1 second', strtotime('+1 month', strtotime(date('m') . '/01/' . date('Y')))));
	}
	
	function genPdfFile($pathFile, $data) {
			ini_set("memory_limit","1024M");
			
			$tipo_papel = 'LEGAL'; 
			$orientacion = 'P';
			if(isset($data['tipo_papel']) && $data['tipo_papel'] != NULL)
				$tipo_papel = $data['tipo_papel'];
			if(isset($data['orientacion']) && $data['orientacion'] != NULL)
				$orientacion = $data['orientacion'];
			
			$html = $this -> load -> view($pathFile, $data, true);
			
			$this->load->library('mpdf/mpdf');
			
			$mpdf=new mPDF('',$tipo_papel,0,'',10,10,15,15,16,13, $orientacion); 

			$mpdf->setFooter('Pag. {PAGENO} de {nb}');
			$mpdf->SetDisplayMode('fullpage');
			$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
			
			$stylesheet = file_get_contents( site_url('css/style.css') ); 
			$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
			
			/*$stylesheet = file_get_contents( site_url('css/mpdfstyletables.css') );
			$mpdf->WriteHTML($stylesheet,1);*/
			
			$stylesheet = file_get_contents( site_url('css/styleRep.css') );
			$mpdf->WriteHTML($stylesheet,1);
			
			$mpdf->WriteHTML($html,2);
			
			$mpdf->Output('mpdf.pdf','I');
	}	

	function repContPorCargoDet($numRow = 0, $dataCur = NULL) {
		$data = isset($dataCur)?$dataCur:array();
		$this->load->view('rep/repContPorCargoDet/repContPorCargoDet_view.php', $data);
	}
	
	function repContPorCargoDetPdf() {
		$data = array();
		if (isset($_POST['html_content']) && isset($_POST['gen_pdf']) ) {
			$data['contenido'] = $_POST['html_content'];
			$this->genPdfFile('rep/repContPorCargoDet/repContPorCargoDet_pdf.php', $data);
		}
	}

	function repContPorCargoDetHtml() {
		$data = array();
		$sql = "";
		
		//Obteniendo la votacion activa
		$this->load->model('votacion_model');
		$votacion = $this->votacion_model->getVotacionAct();
		if( $votacion != NULL ){
			$data['nombre_votacion'] = $votacion['nombre'];
			$data['votacion_id'] = $votacion['votacion_id'];	
			$data['periodo'] = $votacion['anio'];
			$data['nom_periodo'] = $votacion['nombre'];
		}else{
			return;
		}
		
		//Obteniendo el listado de cargos
		$cargosPostulados = $this->db->query("SELECT *
			FROM cargo_postulado WHERE 1 = 1 ORDER BY orden ASC", array());
			
		if($cargosPostulados->num_rows() > 0) {
			$cargosPostulados = $cargosPostulados->result_array();
		}
		
		$tabla[] = array();
		if( is_array($cargosPostulados) ){
			foreach($cargosPostulados as $fila) {
				$tabla1 = array();
				$tabla1[] = $fila['cargopost_id'];
				$tabla1[] = $fila['nombre'];
				
				//Obteniendo la planilla con mas votos
				$contPorPnll = $this->db->query("SELECT planilla_id, COUNT(votsocdet_id) AS contPorPnll,
					(SELECT nombre FROM planilla WHERE 1 = 1 AND planilla.planilla_id = det_votacion_socio.planilla_id
					AND planilla.estado='ACT') AS nombre_planilla
					FROM det_votacion_socio WHERE 1 = 1 
					AND votacion_id = ? 
					AND cargopost_id = ? 
					AND planilla_id IS NOT NULL 
					GROUP BY planilla_id 
					ORDER BY contPorPnll DESC", array( $data['votacion_id'], $fila['cargopost_id'] ));
				
				//Extraemos la planilla con mayor numero de votos	
				if( $contPorPnll->num_rows() > 0 ) {
					$contPorPnll = $contPorPnll->result_array();
					//Calcalulando el porcentaje de la planilla
					$totalVot = 0;
					if( is_array($contPorPnll) ){
						foreach($contPorPnll as $filaDetVot) {
							$totalVot = $totalVot + $filaDetVot['contPorPnll'];
						}
					}
						
					$contPorPnll = $contPorPnll[0];
					
					//Obtenemos el nombre del candidato asociado a la planilla en cuestion
					$candidato = $this->db->query("SELECT *
						FROM candidato WHERE 1 = 1
						AND votacion_id = ? 
						AND planilla_id = ?
						AND cargopost_id = ?", array( $data['votacion_id'], $contPorPnll['planilla_id'], $fila['cargopost_id'] ));
					
					if( $candidato->num_rows() > 0 ) {
						$candidato = $candidato->result_array();
						//$candidato = $candidato[0];
						$candidatos = "";
						$primero = 1;
						if( is_array($candidato) ){
							foreach($candidato as $fila) {
								if( $primero == 1 ){
									$candidatos = $candidatos . $fila['nombres'] . " " . $fila['apellidos'];
									$primero = 0;
								}else{
									$candidatos = $candidatos . ", " . $fila['nombres'] . " " . $fila['apellidos'];
								}
							}
						}
						
						//Se asigna el nombre del candidato con mas votos		
						//$tabla1[] = $candidato['nombres'] . " " . $candidato['apellidos'];
						$tabla1[] = $candidatos;
						$tabla1[] = $contPorPnll['nombre_planilla'];
						$tabla1[] = $contPorPnll['contPorPnll'];
						//Calculando el porcetaje
						$tabla1[] = round(($contPorPnll['contPorPnll'] / $totalVot) * 100, 2);
						
					}else{
						//Si no existe candidato catalogarlo como N/A
						$tabla1[] = "N/A";
						$tabla1[] = $contPorPnll['nombre_planilla'];
						$tabla1[] = $contPorPnll['contPorPnll'];
						//Calculando el porcetaje
						$tabla1[] = round(($contPorPnll['contPorPnll'] / $totalVot) * 100);
					}
				}else{
					//Si no hay votos registrados para esa planilla catalogarla como N/A
					$tabla1[] = "N/A";
					$tabla1[] = "N/A";
					$tabla1[] = 0;
					$tabla1[] = 0;
				}
				
				$tabla[] = $tabla1;
			}//Fin foreach
		}

		$data['tabla'] = $tabla;
		$this->load->view('rep/repContPorCargoDet/repContPorCargoDet_html.php', $data);
	}	

	function repContPorCargoPorPnll($numRow = 0, $dataCur = NULL) {
		$data = isset($dataCur)?$dataCur:array();
		
		$this->load->model('planilla_model');
		
		//Obteniendo el listado de las planillas
		$lstPlanillas = $this->planilla_model->lstPlanillas();
		$lstPlanillas = $lstPlanillas->result_array();
		$data['lstPlanillas'] = $lstPlanillas;
		
		$this->load->view('rep/repContPorCargoPorPnll/repContPorCargoPorPnll_view.php', $data);
	}
	
	function repContPorCargoPorPnllPdf() {
		$data = array();
		if (isset($_POST['html_content']) && isset($_POST['gen_pdf']) ) {
			$data['contenido'] = $_POST['html_content'];
			$this->genPdfFile('rep/repContPorCargoPorPnll/repContPorCargoPorPnll_pdf.php', $data);	
		}
	}	

	function repContPorCargoPorPnllHtml( $planilla_id ) {
		$data = array();
		$sql = "";
		
		//Si el filtro no existe no se muestra la informacion
		if( !isset($planilla_id) ){
			return;
		}
		
		//Obteniendo la votacion activa
		$this->load->model('votacion_model');
		$votacion = $this->votacion_model->getVotacionAct();
		if( $votacion != NULL ){
			$data['nombre_votacion'] = $votacion['nombre'];
			$data['votacion_id'] = $votacion['votacion_id'];	
			$data['periodo'] = $votacion['anio'];
			$data['nom_periodo'] = $votacion['nombre'];
		}else{
			return;
		}
		
		//Obteniendo el listado de cargos
		$cargosPostulados = $this->db->query("SELECT *
			FROM cargo_postulado WHERE 1 = 1 ORDER BY orden ASC", array());
			
		if($cargosPostulados->num_rows() > 0) {
			$cargosPostulados = $cargosPostulados->result_array();
		}
		
		$tabla[] = array();
		if( is_array($cargosPostulados) ){
			foreach($cargosPostulados as $fila) {
				$tabla1 = array();
				$tabla1[] = $fila['cargopost_id'];
				$tabla1[] = $fila['nombre'];
					
				//Obteniendo la planilla con mas votos
				$contPorPnll = $this->db->query("SELECT planilla_id, COUNT(votsocdet_id) AS contPorPnll   
					FROM det_votacion_socio WHERE 1 = 1 
					AND votacion_id = ? 
					AND cargopost_id = ? 
					AND planilla_id IS NOT NULL
					AND planilla_id = ?
					GROUP BY planilla_id 
					ORDER BY contPorPnll DESC", array( $data['votacion_id'], $fila['cargopost_id'], $planilla_id ) );
				
				//Extraemos la planilla con mayor numero de votos	
				if( $contPorPnll->num_rows() > 0 ) {
					$contPorPnll = $contPorPnll->result_array();	
					$contPorPnll = $contPorPnll[0];
					
					//Obtenemos el nombre del candidato asociado a la planilla en cuestion
					$candidato = $this->db->query("SELECT * 
						FROM candidato WHERE 1 = 1
						AND votacion_id = ? 
						AND planilla_id = ?
						AND cargopost_id = ?", 
						array( $data['votacion_id'], $contPorPnll['planilla_id'], $fila['cargopost_id'] ));
					
					if( $candidato->num_rows() > 0 ) {
						$candidato = $candidato->result_array();
						$candidatos = "";
						$primero = 1;
						//$candidato = $candidato[0];
						if( is_array($candidato) ){
							foreach($candidato as $fila) {
								if( $primero == 1 ){
									$candidatos = $candidatos . $fila['nombres'] . " " . $fila['apellidos'];
									$primero = 0;
								}else{
									$candidatos = $candidatos . ", " . $fila['nombres'] . " " . $fila['apellidos'];
								}
								
							}
						}
						
						//Se asigna el nombre del candidato con mas votos		
						//$tabla1[] = $candidato['nombres'] . " " . $candidato['apellidos'];
						$tabla1[] = $candidatos;
						$tabla1[] = $contPorPnll['contPorPnll'];
					}else{
						$tabla1[] = "N/A";
						$tabla1[] = 0;
					}
				}else{
					$tabla1[] = "N/A";
					$tabla1[] = 0;
				}
				
				$tabla[] = $tabla1;
			}//Fin foreach
		}

		$data['tabla'] = $tabla;
		$this->load->view('rep/repContPorCargo/repContPorCargo_html.php', $data);
	}

	function repContPorCargo($numRow = 0, $dataCur = NULL) {
		$data = isset($dataCur)?$dataCur:array();
		$this->load->view('rep/repContPorCargo/repContPorCargo_view.php', $data);
	}
	
	function repContPorCargoPdf() {
		$data = array();
		if (isset($_POST['html_content']) && isset($_POST['gen_pdf']) ) {
			$data['contenido'] = $_POST['html_content'];
			$this->genPdfFile('rep/repContPorCargo/repContPorCargo_pdf.php', $data);
		}
	}

	function repContPorCargoHtml() {
		$data = array();

		//Obteniendo la votacion activa
		$this->load->model('votacion_model');
		$votacion = $this->votacion_model->getVotacionAct();
		if( $votacion != NULL ){
			$data['nombre_votacion'] = $votacion['nombre'];
			$data['votacion_id'] = $votacion['votacion_id'];
			$data['periodo'] = $votacion['anio'];
			$data['nom_periodo'] = $votacion['nombre'];
		}else{
			return;
		}

		//Obteniendo el listado de cargos
		$cargosPostulados = $this->db->query("SELECT *
			FROM cargo_postulado WHERE 1 = 1 ORDER BY orden ASC", array());

		if($cargosPostulados->num_rows() > 0) {
			$cargosPostulados = $cargosPostulados->result_array();
		}

		$tabla[] = array();
		if( is_array($cargosPostulados) ){
			foreach($cargosPostulados as $fila) {
				$tabla1 = array();
				$tabla1[] = $fila['cargopost_id'];
				$tabla1[] = $fila['nombre'];

				//Obteniendo la planilla con mas votos
				$contPorPnll = $this->db->query("SELECT planilla_id, COUNT(votsocdet_id) AS contPorPnll
					FROM det_votacion_socio, planilla WHERE 1 = 1
					AND det_votacion_socio.planilla_id=planilla.planilla_id
					AND planilla.estado='ACT'
					AND det_votacion_socio.votacion_id = ?
					AND det_votacion_socio.cargopost_id = ?
					AND det_votacion_socio.planilla_id IS NOT NULL
					GROUP BY det_votacion_socio.planilla_id
					ORDER BY det_votacion_socio.contPorPnll DESC", array( $data['votacion_id'], $fila['cargopost_id'] ));

				//Extraemos la planilla con mayor numero de votos
				if( $contPorPnll->num_rows() > 0 ) {
					$contPorPnll = $contPorPnll->result_array();
					$contPorPnll = $contPorPnll[0];

					//Obtenemos el nombre del candidato asociado a la planilla en cuestion
					$candidato = $this->db->query("SELECT *
						FROM candidato WHERE 1 = 1
						AND votacion_id = ?
						AND planilla_id = ?
						AND cargopost_id = ? ",
						array( $data['votacion_id'], $contPorPnll['planilla_id'], $fila['cargopost_id'] ));

					if( $candidato->num_rows() > 0 ) {
						$candidato = $candidato->result_array();
						$candidatos = "";
						$primero = 1;
						//$candidato = $candidato[0];
						if( is_array($candidato) ){
							foreach($candidato as $fila) {
								if( $primero == 1 ){
									$candidatos = $candidatos . $fila['nombres'] . " " . $fila['apellidos'];
									$primero = 0;
								}else{
									$candidatos = $candidatos . ", " . $fila['nombres'] . " " . $fila['apellidos'];
								}

							}
						}

						//Se asigna el nombre del candidato con mas votos
						//$tabla1[] = $candidato['nombres'] . " " . $candidato['apellidos'];
						$tabla1[] = $candidatos;
						$tabla1[] = $contPorPnll['contPorPnll'];
					}else{
						$tabla1[] = "N/A";
						$tabla1[] = $contPorPnll['contPorPnll'];
					}
				}else{
					$tabla1[] = "N/A";
					$tabla1[] = 0;
				}

				$tabla[] = $tabla1;
			}//Fin foreach
		}

		$data['tabla'] = $tabla;
		$this->load->view('rep/repContPorCargo/repContPorCargo_html.php', $data);
	}

	function repVotsPorCntVtcn1($numRow = 0, $dataCur = NULL) {
		$data = isset($dataCur)?$dataCur:array();
		
		$this->load->view('rep/votsPorCntVtcn1/votsPorCntVtcn_view.php', $data);
	}
	
	function repVotsPorCntVtcnPdf1( $numRow = 0, $dataCur = NULL, $url = NULL ) {
		$data = array();
		$sql = "";
		
		//Si esta cargando el form solo inicializamos listas, si no cargamos el PDF
		$votosPorCentro = $this->db->query("SELECT centrovot_id, COUNT(socio_id) AS num_votos,
			(SELECT nombre FROM centro_votacion WHERE padron_electoral.centrovot_id = centro_votacion.centrovot_id) nombre_centro,
			(SELECT codigo FROM centro_votacion WHERE padron_electoral.centrovot_id = centro_votacion.centrovot_id) codigo  
			FROM padron_electoral WHERE 1 = 1
			GROUP BY centrovot_id;", array());
			
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
		
		if (isset($_POST['gen_pdf'])) {
			$this -> genPdfFile('rep/votsPorCntVtcn1/votsPorCntVtcn_pdf', $data);
		}else{
			$this -> load -> view('rep_error.php', $data);
		}
	}
	
	function repVotsPorCntVtcn($numRow = 0, $dataCur = NULL) {
		$data = isset($dataCur)?$dataCur:array();
		
		$this->load->view('rep/votsPorCntVtcn/votsPorCntVtcn_view.php', $data);
	}	
	
	function repVotsPorCntVtcnPdf( $numRow = 0, $dataCur = NULL, $url = NULL ) {
		$data = array();
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
			(SELECT nombre FROM centro_votacion WHERE centro_votacion.centrovot_id = votacion_socio.centrovot_id)  AS nombre_centro, 
			(SELECT codigo FROM centro_votacion WHERE centro_votacion.centrovot_id = votacion_socio.centrovot_id)  AS codigo 
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
		
		if (isset($_POST['gen_pdf'])) {
			$this -> genPdfFile('rep/votsPorCntVtcn/votsPorCntVtcn_pdf', $data);
		}else{
			$this -> load -> view('rep_error.php', $data);
		}
	}	

	function repVotnsSocios($numRow = 0, $dataCur = NULL) {
		$data = isset($dataCur)?$dataCur:array();
		
		//Obtener listado de Centros de Votacion
		$this->load->model('centroVotacion_model');
		$lstCentroVotaciones = $this->centroVotacion_model->lstCentroVotaciones();
		$lstCentroVotaciones = $lstCentroVotaciones->result_array();
		$data['lstCentroVotaciones'] = $lstCentroVotaciones;
		
		$this->load->helper('fecha');
		
		$data['fecha_inicio'] = primerDiaMes( date('Y-m-d') );
		$data['fecha_fin'] = ultimoDiaMes( date('Y-m-d') );
		
		$this->load->view('rep/votnsSocios/votnsSocios_view.php', $data);
	}
	
	function repVotnsSociosPdf( $numRow = 0, $dataCur = NULL, $url = NULL ) {
		$data = array();
		$sql = "";
		$data['centrovot_id'] = $_POST['centrovot_id'];
		
		/*if( isset($_POST['centrovot_id']) ){
			if( $_POST['centrovot_id'] != NULL ){
				
				$sql = " AND centrovot_id = ?";
				//Obteniendo el centro de votacion
				
				$centroVot = $this->db->query("SELECT * 
					FROM centro_votacion WHERE 1 = 1 
					AND centrovot_id = ?", array( $_POST['centrovot_id'] ));
				$centroVot = $centroVot->result_array();
				$centroVot = $centroVot[0];
				$data['centrovot_id'] = $centroVot['nombre'];	
			}
		}*/
		
		if( isset($_POST['votosDoblesHd']) ){
			if( $_POST['votosDoblesHd'] > 0 ){
				$sql =  " AND ( (SELECT COUNT(pdrele_id) FROM padron_electoral vs 
					WHERE vs.votacion_id = ? AND vs.socio_id = padron_electoral.socio_id ) >= 2 )";
			}
		}
		
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
		/*$rs = $this->db->query("SELECT *,
			
			(SELECT centro_votacion.nombre FROM centro_votacion WHERE centro_votacion.centrovot_id = votacion_socio.centrovot_id) AS centro_votacion,
			(SELECT CONCAT(socio.nombres, ' ', socio.apellidos) FROM socio WHERE socio.socio_id = votacion_socio.socio_id) AS socio,
			(SELECT codigo FROM socio WHERE socio.socio_id = votacion_socio.socio_id) AS codSocio, 
			(SELECT COUNT(votsoc_id) FROM votacion_socio vs WHERE vs.votacion_id = ? AND vs.socio_id = votacion_socio.socio_id ) AS vecesVotos
			FROM votacion_socio WHERE 1 = 1 
			AND votacion_id = ?
			AND fecha_hora BETWEEN ? AND ? " .
			$sql . 
			" ORDER BY vecesVotos DESC, socio_id DESC", array( $data['votacion_id'], $data['votacion_id'], $this->convertFecha( $_POST['fecha_inicio'])
					,$this->convertFecha( $_POST['fecha_fin']), $data['votacion_id'], $_POST['centrovot_id'] ) );*/
					
		$rs = $this->db->query("SELECT *,
			DATE_FORMAT(fecha_hora,'%d/%m/%Y %h:%i:%s %p') AS fecha_hora,
			(SELECT CONCAT(socio.nombres, ' ', socio.apellidos) FROM socio WHERE socio.socio_id = padron_electoral.socio_id) AS socio,
			(SELECT codigo FROM socio WHERE socio.socio_id = padron_electoral.socio_id) AS codSocio,
			(SELECT centro_votacion.nombre FROM centro_votacion WHERE centro_votacion.centrovot_id = padron_electoral.centrovot_id) AS centro_votacion,
			(SELECT COUNT(pdrele_id) FROM padron_electoral vs WHERE vs.votacion_id = ? AND vs.socio_id = padron_electoral.socio_id ) AS vecesVotos 
			FROM padron_electoral WHERE 1 = 1 
			AND votacion_id = ?
			AND fecha_hora BETWEEN ? AND ?" 
			. $sql
			. " ORDER BY vecesVotos DESC, CAST(codSocio AS UNSIGNED) ASC ", 
			array( $data['votacion_id'], $data['votacion_id'], $this->convertFecha( $_POST['fecha_inicio'])
		 	, $this->convertFecha( $_POST['fecha_fin'])
			, $data['votacion_id'] ));
					
		$rs = $rs->result_array();
		$data['lstVtsSoc'] = $rs;
		
		$totalPadron = 0;
		if( is_array($rs) ){
			foreach($rs as $fila) {
				//Calculando el total de votos
				$totalPadron = $totalPadron + $fila['vecesVotos'];
			}
		}
		
		$data['total_padron'] = $totalPadron;
		$data['fecha_inicio'] = $_POST['fecha_inicio'];
		$data['fecha_fin'] = $_POST['fecha_fin'];
		
		if (isset($_POST['gen_pdf'])) {
			$this -> genPdfFile('rep/votnsSocios/votnsSocios_pdf', $data);
		}else{
			$this -> load -> view('rep_error.php', $data);
		}
	}

	function repvotPlanll($numRow = 0, $dataCur = NULL) {
		$data = isset($dataCur)?$dataCur:array();
		
		//Obtener listado de Centros de Votacion
		$this->load->model('centroVotacion_model');
		$lstCentroVotaciones = $this->centroVotacion_model->lstCentroVotaciones();
		$lstCentroVotaciones = $lstCentroVotaciones->result_array();
		$data['lstCentroVotaciones'] = $lstCentroVotaciones;
		
		$this->load->view('rep/votPlanll/votPlanll_view.php', $data);
	}
	
	function repvotPlanllPdf( $numRow = 0, $dataCur = NULL, $url = NULL ) {
		$data = array();
		$sql = "";
		$sqlFlt = "";
		$idCentroVot = 0;
		$centroVot = "";
		
		$totalAbst = 0;
		$totalNulos = 0;
		$totalNormal = 0;
		
		$this->load->model('cargoPostulado_model');
		$this->load->model('planilla_model');
		$this->load->model('votacion_model');
		
		//Obteniendo el listado de cargos
		$lstCargosPostulados = $this->cargoPostulado_model->lstCargosPostulados();
		$lstCargosPostulados = $lstCargosPostulados->result_array();
		$data['lstCargosPostulados'] = $lstCargosPostulados;
		
		//Obteniendo el listado de las planillas
		/*$lstPlanillas = $this->planilla_model->lstPlanillas();
		$lstPlanillas = $lstPlanillas->result_array();*/

		/* Modificar para que listar una planilla solo de activos */
		$query_select = " SELECT * FROM planilla where estado ='ACT' ";
		$result_select = mysql_query($query_select) or die(mysql_error());

		$lstPlanillas = array();
		while($row = mysql_fetch_array($result_select)) {
			$lstPlanillas[]=$row;
		}

		/*foreach($lstPlanillas as $row) {
			//echo $row['nombre'];
		}*/


		$data['lstPlanillas'] = $lstPlanillas;
		
		//Obteniendo la votacion activa
		$votacion = $this->votacion_model->getVotacionAct();
		if( $votacion != NULL ){
			$data['nombre_votacion'] = $votacion['nombre'];
			$data['votacion_id'] = $votacion['votacion_id'];	
		}else{
			return;
		}
		
		if( isset($_POST['centrovot_id']) ){
			if( $_POST['centrovot_id'] != NULL ){
				
				if($_POST['tipo_flt'] != 'ACU') {
					$idCentroVot = $_POST['centrovot_id'];
					$sqlFlt = " AND EXISTS(SELECT * FROM votacion_socio 
						WHERE votacion_socio.votsoc_id = det_votacion_socio.votsoc_id 
						AND votacion_socio.centrovot_id = ?)";
					
					$data['centrovot_id'] = $idCentroVot;
				}
				//Obteniendo la informacion del centro de votacion
				$centroVot = $this->db->query("SELECT nombre 
					FROM centro_votacion WHERE 1 = 1 
					AND centrovot_id = ?",
					array( $_POST['centrovot_id'] ));
				
				if($centroVot->num_rows() > 0) {
					$centroVot = $centroVot->result_array();
					$centroVot = $centroVot[0];
					$data['centrovot'] = $centroVot;
				}
			}
		}
		
		$tabla[] = array();
		
		if( is_array($lstCargosPostulados) ){
			foreach($lstCargosPostulados as $fila) {
				$tabla1 = array();
				$tabla1[] = $fila['nombre'];

				if( is_array($lstPlanillas) ){
					foreach($lstPlanillas as $fila1) {
						//Obteniendo el total de votos por planilla
						/*
						$votoNor = $this->db->query("SELECT COUNT(*) AS votoNor
						FROM det_votacion_socio,planilla WHERE 1 = 1
						AND det_votacion_socio.planilla_id=planilla.planilla_id
						AND planilla.estado='ACT'
						AND det_votacion_socio.cargopost_id = ?
						AND det_votacion_socio.votacion_id = ?
						AND (det_votacion_socio.tipo_voto = '1' OR det_votacion_socio.tipo_voto = '3') "
							. $sqlFlt,
							array( $fila['cargopost_id'], $data['votacion_id'], $idCentroVot ));*/

						$votoPnll = $this->db->query("SELECT COUNT(*) AS totalPnll
							FROM det_votacion_socio, planilla WHERE 1 = 1
							AND det_votacion_socio.planilla_id=planilla.planilla_id
							AND  planilla.estado='ACT'
							AND det_votacion_socio.cargopost_id = ?
							AND det_votacion_socio.planilla_id = ?
							AND det_votacion_socio.votacion_id = ? "
							. $sqlFlt,
							array( $fila['cargopost_id'], $fila1['planilla_id'], $data['votacion_id'], $idCentroVot ));

						if($votoPnll->num_rows() > 0) {
							$votoPnll = $votoPnll->result_array();
							$votoPnll = $votoPnll[0];
							$tabla1[] = $votoPnll['totalPnll'];
						}else{
							$tabla1[] = 0;
						}
					}//Fin foreach planilla

					/* Este incluye activas e inactivas */
					$votoAbs = $this->db->query("SELECT COUNT(*) AS votoAbs
						FROM det_votacion_socio WHERE 1 = 1
						AND cargopost_id = ?
						AND votacion_id = ?
						AND tipo_voto = '3'"
						. $sqlFlt,
						array( $fila['cargopost_id'], $data['votacion_id'], $idCentroVot ));


					/*    Este es solo para las activas:   se puede hacer de otra manera como por ejemplo validar que si en ese voto la planilla es ACT
					que la sume si no no.
					//Obteniendo totales para el tipo de voto abstenido para el cargo actual
					$votoAbs = $this->db->query("SELECT COUNT(*) AS votoAbs
						FROM det_votacion_socio, planilla WHERE 1 = 1
					    AND det_votacion_socio.planilla_id=planilla.planilla_id
						AND  planilla.estado='ACT'
						AND det_votacion_socio.cargopost_id = ?
						AND det_votacion_socio.votacion_id = ?
						AND det_votacion_socio.tipo_voto= '3'"
						. $sqlFlt,
						array( $fila['cargopost_id'], $data['votacion_id'], $idCentroVot ));*/


					/* Este incluye activas e inactivas Mejorado
					//Obteniendo totales para el tipo de voto abstenido para el cargo actual
				$votoAbs = $this->db->query("SELECT COUNT(*) AS votoAbs
						FROM det_votacion_socio INNER JOIN planilla ON det_votacion_socio.planilla_id=planilla.planilla_id WHERE 1 = 1
						AND det_votacion_socio.cargopost_id = ?
						AND det_votacion_socio.votacion_id = ?
						AND det_votacion_socio.tipo_voto = '3' "
						. $sqlFlt,
						array( $fila['cargopost_id'], $data['votacion_id'], $idCentroVot ));*/


					if($votoAbs->num_rows() > 0) {
						$votoAbs = $votoAbs->result_array();
						$votoAbs = $votoAbs[0];
						$tabla1[] = $votoAbs['votoAbs'];
						$totalAbst = $totalAbst + $votoAbs['votoAbs'];
					}else{
						$tabla1[] = 0;
					}

					//Obteniendo totales para el tipo de voto nulo para el cargo actual
					/*$votoNull = $this->db->query("SELECT COUNT(*) AS votoNull
						FROM det_votacion_socio, planilla WHERE 1 = 1
						AND det_votacion_socio.planilla_id=planilla.planilla_id
						AND  planilla.estado='ACT'
						AND det_votacion_socio.cargopost_id = ?
						AND det_votacion_socio.votacion_id = ?
						AND det_votacion_socio.tipo_voto = '2'"
						. $sqlFlt,
						array( $fila['cargopost_id'], $data['votacion_id'], $idCentroVot ));*/


					$votoNull = $this->db->query("SELECT COUNT(*) AS votoNull
						FROM det_votacion_socio WHERE 1 = 1
						AND cargopost_id = ?
						AND votacion_id = ?
						AND tipo_voto = '2'"
						. $sqlFlt,
						array( $fila['cargopost_id'], $data['votacion_id'], $idCentroVot ));

					if($votoNull->num_rows() > 0) {
						$votoNull = $votoNull->result_array();
						$votoNull = $votoNull[0];
						$tabla1[] = $votoNull['votoNull'];
						$totalNulos = $totalNulos + $votoNull['votoNull'];
					}else{
						$tabla1[] = 0;
					}

					//Obteniendo totales para el tipo de voto normal para el cargo actual
					/*$votoNor = $this->db->query("SELECT COUNT(*) AS votoNor
						FROM det_votacion_socio, planilla WHERE 1 = 1
						AND det_votacion_socio.planilla_id=planilla.planilla_id
						AND  planilla.estado='ACT'
						AND det_votacion_socio.cargopost_id = ?
						AND det_votacion_socio.votacion_id = ?
						AND (det_votacion_socio.tipo_voto = '1' OR det_votacion_socio.tipo_voto = '3') "
						. $sqlFlt,
						array( $fila['cargopost_id'], $data['votacion_id'], $idCentroVot ));*/

					$votoNor = $this->db->query("SELECT COUNT(*) AS votoNor
						FROM det_votacion_socio WHERE 1 = 1
						AND cargopost_id = ?
						AND votacion_id = ?
						AND (tipo_voto = '1' OR tipo_voto = '3') "
						. $sqlFlt,
						array( $fila['cargopost_id'], $data['votacion_id'], $idCentroVot ));

					if($votoNor->num_rows() > 0) {
						$votoNor = $votoNor->result_array();
						$votoNor = $votoNor[0];
						$tabla1[] = $votoNor['votoNor'];
						$totalNormal = $totalNormal + $votoNor['votoNor'];
					}else{
						$tabla1[] = 0;
					}
				}

				$tabla[] = $tabla1;
			}//Fin foreach cargos

			//Obteniendo totales por planilla
			$tablaTotales = array();
			$tablaTotales[] = "Totales";

			/* Aca puedo modificar para el conteo o totales */

			if( is_array($lstPlanillas) ){
				foreach($lstPlanillas as $filaPnll) {
					//Obteniendo el total de la planilla para la votacion activa
					$votoPnll = $this->db->query("SELECT COUNT(*) AS totalPnll
						FROM det_votacion_socio, planilla WHERE 1 = 1
						AND det_votacion_socio.planilla_id=planilla.planilla_id
						AND  planilla.estado='ACT'
						AND det_votacion_socio.planilla_id = ?
						AND det_votacion_socio.votacion_id = ?
						AND (det_votacion_socio.tipo_voto = 1 OR det_votacion_socio.tipo_voto = 3) ",
						array( $filaPnll['planilla_id'], $data['votacion_id'] ));

					if($votoPnll->num_rows() > 0) {
						$votoPnll = $votoPnll->result_array();
						$votoPnll = $votoPnll[0];
						$tablaTotales[] = $votoPnll['totalPnll'];
					}else{
						$tablaTotales[] = 0;
					}
				}//Fin foreach
			}//Fin if
			
			$tablaTotales[] = $totalAbst;
			$tablaTotales[] = $totalNulos;
			$tablaTotales[] = $totalNormal;
			
			//$tabla[] = $tablaTotales;
			
			$data['tabla'] = $tabla;
		}
		
		if (isset($_POST['gen_pdf'])) {
			$data['orientacion'] = 'L';
			$this->genPdfFile('rep/votPlanll/votPlanll_pdf', $data);
			//$this->load->view('rep/votPlanll/votPlanll_pdf', $data);
		}else{
			$this->load->view('rep_error.php', $data);
		}
	}

	/* Funcion que es llamada desde masterIns para mostrar el conteo 21*/
	function repvotPlanllPdf1( $numRow = 0, $dataCur = NULL, $url = NULL ) {
		$data = array();
		$sql = "";
		$sqlFlt = "";
		$idCentroVot = 0;
		
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

		//Obteniendo el listado de las planillas solamente activas
		/* Mantenimiento 16/11/2015 */
		$lstPlanillasx = $this->planilla_model->lstPlanillasx();
		$lstPlanillasx = $lstPlanillasx->result_array();
		$data['lstPlanillasx'] = $lstPlanillasx;


		
		//Obteniendo la votacion activa
		$votacion = $this->votacion_model->getVotacionAct();
		if( $votacion != NULL ){
			$data['nombre_votacion'] = $votacion['nombre'];
			$data['votacion_id'] = $votacion['votacion_id'];	
		}else{
			return;
		}
		
		if( isset($_POST['centrovot_id']) ){
			if( $_POST['centrovot_id'] != NULL ){
				$idCentroVot = $_POST['centrovot_id'];
				$sqlFlt = " AND EXISTS(SELECT * FROM votacion_socio 
					WHERE votacion_socio.votsoc_id = det_votacion_socio.votsoc_id 
					AND votacion_socio.centrovot_id = ?)";
			}
		}

		/* Aca se tiene que modificar: PARA MOSTRAR EL CONTEO DE VOTOS */
		$tabla[] = array();
		if( is_array($lstCargosPostulados) ){
			foreach($lstCargosPostulados as $fila) {
				$tabla1 = array();
				$tabla1[] = $fila['nombre'];
				
				if( is_array($lstPlanillasx) ){
					foreach($lstPlanillasx as $fila1) {
						//Obteniendo el total de votos por planilla: es decir por columna
						$votoPnll = $this->db->query("SELECT COUNT(*) AS totalPnll
							FROM det_votacion_socio WHERE 1 = 1 
							AND cargopost_id = ?
							AND planilla_id = ?
							AND votacion_id = ?"
							. $sqlFlt, 
							array( $fila['cargopost_id'], $fila1['planilla_id'], $data['votacion_id'], $idCentroVot ));
						
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
						AND tipo_voto = '3'"
						. $sqlFlt, 
						array( $fila['cargopost_id'], $data['votacion_id'], $idCentroVot ));
						
					if($votoAbs->num_rows() > 0) {
						$votoAbs = $votoAbs->result_array();
						$votoAbs = $votoAbs[0];
						$tabla1[] = $votoAbs['votoAbs'];
					}else{
						$tabla1[] = 0;
					}
					
					//Obteniendo totales para el tipo de voto nulo para el cargo actual
					// En esta ocasion no le ponemos los demas filtros para que siga contando
					$votoNull = $this->db->query("SELECT COUNT(*) AS votoNull
						FROM det_votacion_socio WHERE 1 = 1
						AND det_votacion_socio.cargopost_id = ?
						AND det_votacion_socio.votacion_id = ?
						AND det_votacion_socio.tipo_voto = '2'"
						. $sqlFlt,
						array( $fila['cargopost_id'], $data['votacion_id'], $idCentroVot ));
						
					if($votoNull->num_rows() > 0) {
						$votoNull = $votoNull->result_array();
						$votoNull = $votoNull[0];
						$tabla1[] = $votoNull['votoNull'];
					}else{
						$tabla1[] = 0;
					}
					
					//Obteniendo totales para el tipo de voto normal para el cargo actual
					$votoNor = $this->db->query("SELECT COUNT(*) AS votoNor
						FROM det_votacion_socio,planilla WHERE 1 = 1
						AND det_votacion_socio.planilla_id=planilla.planilla_id
						AND planilla.estado='ACT'
						AND det_votacion_socio.cargopost_id = ?
						AND det_votacion_socio.votacion_id = ?
						AND (det_votacion_socio.tipo_voto = '1' OR det_votacion_socio.tipo_voto = '3') "
						. $sqlFlt,
						array( $fila['cargopost_id'], $data['votacion_id'], $idCentroVot ));
						
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
		
		$this->load->view('rep/votPlanll/votPlanll1_pdf', $data);
	}


	function convertFecha($fecha) {
		if ($fecha != "") {
			//$arrDt = split("/", $fecha);
			$arrDt = preg_split("/[\/]|[-]+/", $fecha);
			$lafecha = $arrDt[2] . '-' . $arrDt[1] . '-' . $arrDt[0];
			return $lafecha;
		} else {
			return "00-00-0000";
		}
	}

}

/* End of file embarcacion.php */
/* Location: application/controllers/ */
