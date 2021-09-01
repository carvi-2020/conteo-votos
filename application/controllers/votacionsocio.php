<?php 
class VotacionSocio extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('votacionsocio_model');
		$this->load->model('centrovotacion_model');
		$this->load->model('votacion_model');
		$this->load->model('planilla_model');
		$this->load->model('cargopostulado_model');
	}
	
	function lstVotacionesSocio($numRow = 0, $dataCur = NULL) {
				
		$data = isset($dataCur)?$dataCur:array();
		
		$filtros = array();
		$this->load->helper('filtro');
		$filtros = verificarFiltros($_POST, 
		array('_fltNom' => array('fld' => 'votsoc_id', 'typ' => 'str'),
			'_fltIso' => array('fld' => 'socio_id', 'typ' => 'str', 'tpb' => 'LKF'),
			'_fltCap' => array('fld' => 'fecha_hora', 'typ' => 'str', 'tpb' => 'LKF')
				), $numRow, $data);
				
		$rs = $this->votacionsocio_model->lstVotacionesSocio($filtros);
		$this->load->library('tablepaging');
		$data['lstVotacionesSocio'] = $this->tablepaging->getTablaPaginada('votacionsocio/lstVotacionesSocio', $rs, 10, $numRow);
		$this->load->view('votacion/votacionSocio/list', $data);
	}
	
	function guardarMaestro( $centrovot_id, $votacion_id ){
		//actualizarPrc();  // actualizar en el prc
		$res = $this->votacionsocio_model->insVotacionSocioAjx( $centrovot_id, $votacion_id );
		//echo "votacion";
		/******************Actualizamos*************************/
		$query="UPDATE temp_votacion set estado ='PRC' WHERE estado='PEN' ";
		mysql_query($query) or die(mysql_error());
		/******************************************************/

		if( $res != FALSE )
			echo $res;
		else
			echo "FALSE";
	}

	/* Guardar el detalle de la votacion */
	function guardarDetVot( $votsoc_id, $cargopost_id, $planilla_id, $tipo_voto, $votacion_id, $cargonm ) {
		/*  Lo primero que tengo que hacer es procesar en estado de la tabla temporal */
		//deleteTemp();

		/* Llama la funcion de guardado final */
		$votsocdet_id = $this->votacionsocio_model->guardarDetVot( $votsoc_id, $cargopost_id, $planilla_id, $tipo_voto, $votacion_id );
		//$votsocdet_id = TRUE;
		if( $votsocdet_id != FALSE ) {
			echo "La votacion para el cargo <strong>" . $cargonm . "</strong> se ha guardado correctamente <br />";
		}else{
			echo "Ha habido un problema para guardar la votacion del cargo: <strong>" . $cargonm . "</strong><br />";
		}
		
		//Actualizar el conteo de la votacion
		$this->votacionsocio_model->updConteVotacion( $cargopost_id, $planilla_id, $votacion_id, $tipo_voto );
	}

	function actualizarPrc(){
		$query="UPDATE temp_votacion set estado ='PRC' WHERE estado='PEN' ";
		mysql_query($query) or die(mysql_error());
		return true;
	}

	/*function deleteTemp(){
		$query="DELETE FROM temp_votacion";
		mysql_query($query) or die(mysql_error());

		return true;
	}*/

	function estadoAnterior(){

		/* Aca tenemos que poner las instrucciones sql para  hacer el respectivo descuento */
			// Traer los registros que actualmente estan pendientes: que seria los de la ultima votacion

		    // Correr los registros en un for y compararlos con los id de la tabla votacion socio.
			// primero correr el for de la tabla temporal tiene que haber 11 registros
			// Luego hacer una consulta de cada registro que tenga los mismos valores en los campos y hacerle la resta
		    // Hacer pruebas visuales en el masterIns



		$query_select_tmp = " SELECT * FROM temp_votacion where estado ='PEN' ";
		$result_select_tmp = mysql_query($query_select_tmp) or die(mysql_error());

		$lstTempVotacion = array();
		while($row = mysql_fetch_array($result_select_tmp)) {
			$lstTempVotacion[]=$row;
		}

		foreach($lstTempVotacion as $row) {

			/* Aquie tengo que corregir un error interno de version para condiciones despues del where */
			$query_select_cv = "SELECT * FROM conteo_votacion where planilla_id=$row[planilla_id] and votacion_id=$row[votacion_id] and
 			cargopost_id=$row[cargopost_id]";

			$result_select_cv = mysql_query($query_select_cv) or die(mysql_error());
			$lstConteoVotacion = array();

			while ($row2 = mysql_fetch_array($result_select_cv)) {
				$lstConteoVotacion[] = $row2;
			}

			$restar1=0; // resta de votos
			$restar2=0; // resta de nulos
			$restar3=0; // resta de abstenciones

			foreach($lstConteoVotacion as $row3) {

				/*echo $row3['votos'];
                echo $row3['nulos'];
                echo $row3['abstenciones'];
                                    echo "<br />";*/
				/* Aca se tiene que validar para ser el descuento */
				if($row['planilla_id']==$row3['planilla_id'] and $row['votacion_id']==$row3['votacion_id'] and $row['cargopost_id']==$row3['cargopost_id']){
					$restar1= $row3['votos']-$row['votos'];
					$restar2= $row3['nulos']-$row['nulos'];
					$restar3= $row3['abstenciones']-$row['abstenciones'];

					/*echo $restar1;
                    echo $restar2;
                    echo $restar3;*/

				/*	echo $row3['votos']."-".$row['votos']."= ".$restar1;
					echo "<br />";
					echo $row3['nulos']."-".$row['nulos']."= ".$restar2;
					echo "<br />";
					echo $row3['abstenciones']."-".$row['abstenciones']."= ".$restar3;
					echo "<br />";
					echo "<br />";*/

					/* Vamos ir haciendo un update a al conteo_votacion */
					$query_select_up = " UPDATE conteo_votacion set votos=$restar1, nulos=$restar2, abstenciones=$restar3 where
  							planilla_id=$row[planilla_id] and votacion_id=$row[votacion_id] and cargopost_id=$row[cargopost_id]";
					mysql_query($query_select_up) or die(mysql_error());

				}




			}



		}

		/* Aqui se necesitaria ir a borrar los registros de det_votacion_socio */
		/*$query_select_max="select max(votsoc_id) from votacion_socio;";

		/* Aqui se necesitaria ir a borrar los registros de det_votacion_socio */
		$query_select_max="SELECT MAX(votsoc_id) as maximo from votacion_socio order by votsoc_id ASC;";

		$resultQ=mysql_query($query_select_max);
		$row=array();

		$row=mysql_fetch_array($resultQ);
		$numeroMax=$row["maximo"]; // En esta línea es el error.

		echo $numeroMax; // resource id #54

		$query_select_tmp = " SELECT * FROM det_votacion_socio WHERE votsoc_id=$numeroMax ORDER BY votsocdet_id DESC ";
		$result_select_tmp = mysql_query($query_select_tmp) or die(mysql_error());

		$lstTempVotacion = array();
		while($row = mysql_fetch_array($result_select_tmp)) {
			$lstTempVotacion[]=$row;
		}

		foreach($lstTempVotacion as $row) {
			//echo $row['votsocdet_id'];
			$query_select_del="DELETE FROM det_votacion_socio WHERE votsocdet_id= $row[votsocdet_id] ";
			mysql_query($query_select_del);
			echo "<br />";
		}


		/* $query_select_del="DELETE FROM det_votacion_socio WHERE votsoc_id=$max";
        mysql_query($query_select_del); */

		/* Para finalizar borramos el ultimo registro de votacion_socio */
		$query_select_del2=" DELETE FROM votacion_socio WHERE votsoc_id= $numeroMax ";
		mysql_query($query_select_del2);



		/* Solo es para pruebas */
		/*$query_select3="INSERT INTO temp_votacion (planilla_id, votacion_id, cargopost_id, votos, nulos, abstenciones, estado)
                             VALUES ( 1, 1, 1, 0, 0, 0, 'PEN')";
		mysql_query($query_select3) or die(mysql_error());*/

		return true;
	}
	
	function insVotacionSocio() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {
			$votsoc_id = $this->votacionsocio_model->insVotacionSocio();	
			if($votsoc_id != FALSE) {
				
				$data = $this->votacionsocio_model->loadVotacionSocio($votsoc_id);
			
				$data['msgMtto'] = 'La votacion ha sido guardado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'La votacion no pudo ser guardado.';
				$data['msgType'] = 'ERR';
			}			
			
			//Obtener listado de Centros de Votacion
			$lstCentroVotaciones = $this->centrovotacion_model->comboCentrosVotacion();
			$data['lstCentroVotaciones'] = $lstCentroVotaciones;
			
			//Obtener listado de Socios
			$lstSocios = $this->centrovotacion_model->lstSocios();
			$lstSocios = $lstSocios->result_array();
			$data['lstSocios'] = $lstSocios;
			
			//Obteniendo el listado de las planillas
			$lstPlanillas = $this->planilla_model->lstPlanillas();
			$lstPlanillas = $lstPlanillas->result_array();
			$data['lstPlanillas'] = $lstPlanillas;
			
			//Obteniendo la votacion activa
			$votacion = $this->votacion_model->getVotacionAct();
			if( $votacion != NULL ){
				$data['nombre_votacion'] = $votacion['nombre'];
				$data['votacion_id'] = $votacion['votacion_id'];	
			}
			
			//Obteniendo el listado de cargos
			$lstCargosPostulados = $this->cargopostulado_model->lstCargosPostulados();
			$lstCargosPostulados = $lstCargosPostulados->result_array();
			$data['lstCargosPostulados'] = $lstCargosPostulados;
			
			$data['accion'] = 'UPD';
			$this->load->view('votacion/votacionSocio/master', $data);
			//$this->lstVotacionesSocio(0, $data);
		} else{
			//Obtener listado de Centros de Votacion
			$lstCentroVotaciones = $this->centrovotacion_model->comboCentrosVotacion();
			$data['lstCentroVotaciones'] = $lstCentroVotaciones;
			
			//Obtener listado de Socios
			$lstSocios = $this->centrovotacion_model->lstSocios();
			$lstSocios = $lstSocios->result_array();
			$data['lstSocios'] = $lstSocios;
			
			//Obteniendo la votacion activa
			$votacion = $this->votacion_model->getVotacionAct();
			if( $votacion != NULL ){
				$data['nombre_votacion'] = $votacion['nombre'];
				$data['votacion_id'] = $votacion['votacion_id'];	
			}
			
			//Obteniendo el listado de las planillas
			$lstPlanillas = $this->planilla_model->lstPlanillas();
			$lstPlanillas = $lstPlanillas->result_array();
			$data['lstPlanillas'] = $lstPlanillas;
			
			//Obteniendo el listado de cargos
			$lstCargosPostulados = $this->cargopostulado_model->lstCargosPostulados();
			$lstCargosPostulados = $lstCargosPostulados->result_array();
			$data['lstCargosPostulados'] = $lstCargosPostulados;
				
			if( $data['accion'] == 'INS' ){
				$this->load->view('votacion/votacionSocio/masterIns', $data);
			}else{
			$this->load->view('votacion/votacionSocio/master', $data);
		}	
	}	
	}	
	
	function ajxListVotacionSocio() {
		//Obtenemos un listado de todos los votacionesSocio y los pasamos
		$filtros = array();
		$this->load->helper('filtro');
		$arrFltNm = array('_fltNom' => $_GET['qryStr']);
		$filtros = verificarFiltros($arrFltNm, 
		array('_fltNom' => array('fld' => 'nombre', 'typ' => 'str', 'tpb' => 'LKF')
				), 0, $data);
		
		$rs = $this->votacionsocio_model->lstVotacionesSocio($filtros);
		$rs = $rs->result_array();
		echo json_encode($rs);    
	}
	
	function loadVotacionSocio() {
		$data['votsoc_id'] = '';
		$data['socio_id'] = '';
		$data['usuario_id'] = '';
		$data['fecha_hora'] = '';
		$data['centrovot_id'] = '';
			
		$votacion = NULL;
		
		if(isset($_POST['votsoc_id']) && $_POST['votsoc_id'] != ''){
			$data = $this->votacionsocio_model->loadVotacionSocio($_POST['votsoc_id']);
			$votacion = $this->votacionsocio_model->getVtacnActDet( $_POST['votsoc_id'] );
			
			if( $votacion != NULL ){
				$data['nombre_votacion'] = $votacion['nombre'];
				$data['votacion_id'] = $votacion['votacion_id'];
			}
		}else{
			//Obteniendo la votacion activa
			$votacion = $this->votacion_model->getVotacionAct();
			if( $votacion != NULL ){
				$data['nombre_votacion'] = $votacion['nombre'];
				$data['votacion_id'] = $votacion['votacion_id'];	
			} 
		}	
		
		if(isset($_POST['accion']))
			$data['accion'] = $_POST['accion'];
		else
			$data['accion'] = 'INS';
		
		//Obtener listado de Centros de Votacion
		$lstCentroVotaciones = $this->centrovotacion_model->comboCentrosVotacion();
		$data['lstCentroVotaciones'] = $lstCentroVotaciones;
		
		//Obtener listado de Socios
		$lstSocios = $this->centrovotacion_model->lstSocios();
		$lstSocios = $lstSocios->result_array();
		$data['lstSocios'] = $lstSocios;
		
		//Obteniendo el listado de las planillas
		$lstPlanillas = $this->planilla_model->lstPlanillas();
		$lstPlanillas = $lstPlanillas->result_array();
		$data['lstPlanillas'] = $lstPlanillas;
		
		//Obteniendo el listado de cargos
		$lstCargosPostulados = $this->cargopostulado_model->lstCargosPostulados();
		$lstCargosPostulados = $lstCargosPostulados->result_array();
		$data['lstCargosPostulados'] = $lstCargosPostulados;
		
		//Obteniendo el detalle de la votacion
		$lstDetVotacion = $this->votacionsocio_model->lstDetVotacion( $data['votsoc_id'] );
		if( $lstDetVotacion != NULL ){
			$lstDetVotacion = $lstDetVotacion->result_array();	
		}
		$data['lstDetVotacion'] = $lstDetVotacion;	
		
		if(isset($_GET['centrovot_id']))
			$data['centrovot_id'] = $_GET['centrovot_id'];
			
		if( $data['accion'] == 'INS' ){
			$this->load->view('votacion/votacionSocio/masterIns', $data);
		}else{
			$this->load->view('votacion/votacionSocio/master', $data);
		}
		
	}
	
	function _validate() {
		$this->load->library('form_validation');
		if($_POST['accion'] === 'UPD')
			$this->form_validation->set_rules('votsoc_id', 'ID del voto', 'required');
		
		$this->form_validation->set_rules('socio_id', 'Socio que vota', '');
		$this->form_validation->set_rules('centrovot_id', 'Centro de votacion desde el cual vota', 'required|max_length[10]');
		$this->form_validation->set_rules('tn_det', 'Tiene detalle', '');
		$this->form_validation->set_rules('votacion_id', 'Votacion activa', 'required|max_length[10]');
		$this->form_validation->set_rules('nombre_votacion', 'Votacion activa', '');
		
		return $this->form_validation->run();
	}
	
	function updVotacionSocio() {
		$data['accion'] = $_POST['accion'];
		if($this->_validate() != FALSE) {
			if($this->votacionsocio_model->updVotacionSocio() != FALSE) {
				$data['msgMtto'] = 'La votacion ha sido actualizado exitosamente.';
				$data['msgType'] = 'MSG';
			} else {
				$data['msgMtto'] = 'La votacion no pudo ser actualizado.';
				$data['msgType'] = 'ERR';
			}
			$this->lstVotacionesSocio(0, $data);
		}else{
			//Obtener listado de Centros de Votacion
			$lstCentroVotaciones = $this->centrovotacion_model->comboCentrosVotacion();
			$data['lstCentroVotaciones'] = $lstCentroVotaciones;
			
			//Obtener listado de Socios
			$lstSocios = $this->centrovotacion_model->lstSocios();
			$lstSocios = $lstSocios->result_array();
			$data['lstSocios'] = $lstSocios;
			
			//Obteniendo el listado de las planillas
			$lstPlanillas = $this->planilla_model->lstPlanillas();
			$lstPlanillas = $lstPlanillas->result_array();
			$data['lstPlanillas'] = $lstPlanillas;
			
			//Obteniendo el listado de cargos
			$lstCargosPostulados = $this->cargopostulado_model->lstCargosPostulados();
			$lstCargosPostulados = $lstCargosPostulados->result_array();
			$data['lstCargosPostulados'] = $lstCargosPostulados;
			
			//Obteniendo el detalle de la votacion
			$lstDetVotacion = $this->votacionsocio_model->lstDetVotacion( $data['votsoc_id'] );
			if( $lstDetVotacion != NULL ){
				$lstDetVotacion = $lstDetVotacion->result_array();	
			}
			$data['lstDetVotacion'] = $lstDetVotacion;
			
			$this->load->view('votacion/votacionSocio/master', $data);
		}
	}

	function verfcrVotSocio( $idSocio = 0, $idCentroVot = 0 ) {
		//Obteniendo la votacion activa
		$votacion = $this->votacion_model->getVotacionAct();
		if( $votacion != NULL ){
			$data['nombre_votacion'] = $votacion['nombre'];
			$data['votacion_id'] = $votacion['votacion_id'];	
		}else{
			$data['nombre_votacion'] = "";
			$data['votacion_id'] = 0;
		}
		
		$votacionSocio = $this->votacionsocio_model->verfcrVotSocio( $idSocio, $data['votacion_id'], $idCentroVot );
		if( $votacionSocio != NULL ){
			echo "Este socio ya ha votado el <strong>" . $votacionSocio['fecha_hora'] . "</strong> en el centro de votacion: <strong>" . $votacionSocio['nombre_centro'] . "</strong>";
		}else{
			echo "NULL";
		}
	}
	
	function delVotacionSocio() {
		$data['accion'] = $_POST['accion'];
		if($this->votacionsocio_model->delVotacionSocio() != FALSE) {
			$data['msgMtto'] = 'La votacion ha sido eliminado exitosamente.';
			$data['msgType'] = 'MSG';
		} else {
			$data['msgMtto'] = 'La votacion no pudo ser eliminado.';
			$data['msgType'] = 'ERR';
		}
		$this->lstVotacionesSocio(0, $data);
	}
	
	function loadInfoSocio( $idSocio ){
		$socio = $this->votacionsocio_model->loadSocio($idSocio);
		if( isset( $socio ) ){
	?>
		<h1>Informacion del Socio</h1>
		<table class="tbl-info-det" width="70%" cellpadding="1" cellspacing="0" border="0">
			<thead>
				<tr>
					<th>Correlativo</th>
					<th>Nombres</th>
					<th>Apellidos</th>
					<th>Codigo</th>
					<th>JVPM</th>
					<th>DUI</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $socio['socio_id']; ?></td>
					<td><?php echo $socio['nombres']; ?></td>
					<td><?php echo $socio['apellidos']; ?></td>
					<td><?php echo $socio['codigo']; ?></td>
					<td><?php echo $socio['jvpm']; ?></td>
					<td><?php echo $socio['dui']; ?></td>
				</tr>	
			</tbody>
		</table>
	<?php
		}
	}
	
	function cambOrgnSoc( $origen ){
		$lstSocios = $this->centrovotacion_model->lstSocios();
		$lstSocios = $lstSocios->result_array();
		$data['lstSocios'] = $lstSocios;
	?>
	<script language="javascript" type="text/javascript" >
		$(function() {
			
			var socios = [  
				<?php  $cnt = 0;
					$comma = 0;
					if( is_array($lstSocios) ){
					foreach($lstSocios as $fila) {
						
					if( $comma == 1 ){
						echo ",";
					}					
				?>    
				{        
					value: "<?php echo $fila['socio_id'] ?>",
					label: "<?php
								if( $origen == 'codigo' ){
									echo $fila['codigo'];	
								}else{
									if( $origen == 'jvpm' ){
										echo $fila['jvpm'];
									}else{
										if( $origen == 'nombres' ){
											echo $fila['nombres'];
										}else{
											if( $origen == 'apellidos' ){
												echo $fila['apellidos'];
											}else{
												if( $origen == 'dui' ){
													echo $fila['dui'];
												}else{
													if( $origen == 'mixto' ){
														echo $fila['nombres'] . " " . $fila['apellidos'];
													}	
												}
											}
										}
									}
								}
							?>"
				}      
				<?php
					$comma = 1;
					$cnt++;
					}
				}
				?>      
			];
			
			$( "#nombre_socio" ).autocomplete({      
				minLength: 0,      
				source: socios,      
				focus: function( event, ui ) {        
					$( "#nombre_socio" ).val( ui.item.label );        
					return false;      
				},      
				select: function( event, ui ) {
					$( "#socio_id" ).val( ui.item.value );
					$( "#nombre_socio" ).val( ui.item.label );
					loadInfoSocio( ui.item.value );
					verfcrVotSocio( ui.item.value );
					return false;      
				}    
			})    
			.data( "ui-autocomplete" )._renderItem = function( ul, item ) {      
				return $( "<li>" )
				.append( "<a><strong>" + item.label + "</strong></a>" )
				.appendTo( ul );    
			};
		});
	</script>
	<?php
	}	
}

/* End of file votacion_socio.php */
/* Location: application/controllers/ */
