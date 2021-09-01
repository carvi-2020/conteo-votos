<?php $this->load->view('header'); ?>
<div class="info" id="errMtto2" style="display:none;">
	El voto ha sido guardado exitosamente
</div>
<div class="error" id="errMtto3" style="display:none;">
	Hubo un problema al intentar guardar el voto
</div>
<script language="javascript" type="text/javascript" src="<?php echo site_url('js/shortcut.js') ?>"></script>

<style type="text/css">
	.cntnr-head{ overflow:hidden; background:#F4F4F4; border:1px solid #CCCCCC; padding:12px 0px;}
	.nobk{ background:none;}
	.bnts-cntls{ width:100px !important; padding:0px !important; height:23px; font:11px/12px Arial, Helvetica, sans-serif !important; }
	#nombre_socio{ display:none;}
	.msgShortcut{
		width:700px; overflow:hidden; 
		background:#D12727; border:1px solid #E8849D; 
		color:#FFFFFF; text-align:center; 
		display:block; margin:0px !important; width:100%; 
		padding:2px 0px; font-weight:bold;} 
</style>

<div class="msgShortcut">
		<center>
			<table width="50%" cellpadding="0" cellspacing="5" border="0">
				<tr>
					<!--<td align="center">F2: GUARDAR VOTACION</td>
					<td align="center"> || </td>-->
					<td align="center">F4: CONFIRMAR DETALLE</td>
					<!--<td align="center"> || </td>
					<td align="center">F7: GUARDAR DETALLE</td>
					<td align="center"> || </td>
					<td align="center">F9: NUEVA VOTACION</td>-->
				</tr>
			</table>
		</center>
</div>
	
<div id="content">
	
	<h1>Votacion del Socio > Emision del Voto</h1>
	<form method="POST" id="opcionesmenu" enctype="multipart/form-data" action="<?php echo site_url('votacionsocio'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>" />
	<input type="hidden" name="conf_det" id="conf_det" value="0" />
	<input type="hidden" name="votsoc_id" id="votsoc_id" value="<?php echo isset($votsoc_id)?$votsoc_id:set_value('votsoc_id');?>" />
	<input type="hidden" name="socio_id" id="socio_id" value="<?php echo isset($socio_id)?$socio_id:set_value('socio_id');?>" />
	<input type="hidden" name="tn_det" id="tn_det" value="<?php echo isset($tn_det)?$tn_det:set_value('tn_det');?>" />
	<center>

	<?php


	$query_select = " SELECT * FROM planilla where estado ='ACT' ";
	$result_select = mysql_query($query_select) or die(mysql_error());

	$lstPlanillasx = array();
	while($row = mysql_fetch_array($result_select)) {
		$lstPlanillasx[]=$row;
	}

	foreach($lstPlanillasx as $row) {
		//echo $row['nombre'];
	}


	/*$query_select_tmp = " SELECT * FROM temp_votacion where estado ='PEN' ";
	$result_select_tmp = mysql_query($query_select_tmp) or die(mysql_error());

	$lstTempVotacion = array();
	while($row = mysql_fetch_array($result_select_tmp)) {
		$lstTempVotacion[]=$row;
	}

	foreach($lstTempVotacion as $row) {

		/* Aquie tengo que corregir un error interno de version para condiciones despues del where
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
					/* Aca se tiene que validar para ser el descuento
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

						/* Vamos ir haciendo un update a al conteo_votacion
						$query_select_up = " UPDATE conteo_votacion set votos=$restar1, nulos=$restar2, abstenciones=$restar3 where
  							planilla_id=$row[planilla_id] and votacion_id=$row[votacion_id] and cargopost_id=$row[cargopost_id]";
						mysql_query($query_select_up) or die(mysql_error());

					}




                }



	}
*/

	/* Aqui se necesitaria ir a borrar los registros de det_votacion_socio */
	/*$query_select_max="SELECT MAX(votsoc_id) as maximo from votacion_socio order by votsoc_id ASC;";

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
	}*/


    /* $query_select_del="DELETE FROM det_votacion_socio WHERE votsoc_id=$max";
    mysql_query($query_select_del); */

	/* Para finalizar borramos el ultimo registro de votacion_socio */
	/* $query_select_del2=" DELETE FROM votacion_socio WHERE votsoc_id= $numeroMax ";
	mysql_query($query_select_del2); */

	?>


	<table class="tblform">
		<tr>
			
		</tr>
	</table>
	</center>
		
	
	<center>
		<table class="tblform">
			<tr>
				<td>Votacion Activa: </td>
				<td>
					<input type="hidden" name="votacion_id" id="votacion_id" value="<?php echo isset($votacion_id)?$votacion_id:set_value('votacion_id');?>" />
					<input type="text" name="nombre_votacion" id="nombre_votacion" readonly="true" class="textform" maxlength="10" value="<?php echo isset($nombre_votacion)?$nombre_votacion:set_value('nombre_votacion');?>" />
				</td>
				<td>Centro de votaci&oacute;n: </td>
				<td>
					
					<?php echo form_dropdown('centrovot_id', $lstCentroVotaciones, 
					isset($centrovot_id)?$centrovot_id:set_value('centrovot_id'), ' id="centrovot_id" class="comboform" style="width:260px;"');?>
					
				</td>
			</tr>
		</table>
	</center>
	
	</form>
	
	<div <?php if($accion === 'DEL') { ?> style="display:none;" <?php } ?> >
	
	<center>
	<div.
		<?php 
			if($accion === 'UPD') {
				if( $tn_det == 1 ){
		?>  
			style="display:none;" 
		<?php
				} 
			} 
		?> >
		<!--
		<input type="submit" class="clean-gray bnts-cntls" id="regVotBtn" 
		onclick="guardarDetVot(); return false;" value="Registrar voto" />
		-->
		<br /><br />
		<form name=formulariox id="id_formx" action="javascript:  guardarDetVot()">

		<input type="submit" class="clean-gray bnts-cntls" id="botn_guardar"  name="guardar"
		onclick="confirmar(); return false;" value="Registrar voto" />
		</form>
		<br /><br />
		<br /><br />

        <!--
		<input type="submit" class="clean-gray bnts-cntls" id="regVotBtn"
			   onclick="reinvertirVotos(); return false;" value="Revisar" />
			  -->

		<!--   -->
			<form name=miformulario id="id_form" method="post" action="javascript:  reinvertirVotos()">
			<input type="submit" name="Revisar" onclick="pregunta(); return false;" class="clean-gray bnts-cntls" id="Revisar" value="Revisar" />
			</form>

		<br /><br />
		<!--     Solo para ayuda temporal
		<a href="javascript:location.reload()">Actualizar</a>
		-->
        <!--
		<form action="" method="post"  id="id_form" name="miformulario" >


			<input type="submit" onclick="pregunta(); return false;" value="Revisar" name="Revisar">
		</form>
		-->

	</div.>
	</div>	
	
	<table width="100%" cellpadding="4" cellspacing="0" border="0">
		<tr>
			<td style="width:50%;">
				<table class="tbl-info-det" width="100%" cellpadding="1" cellspacing="0" border="0" style="font-size: 12px;">
		<thead>
			<tr>
							<th rowspan="3">Cargos postulados</th>
				<th colspan="<?php echo count($lstPlanillasx); ?>">Planillas</th>
				<th colspan="2">Tipo de voto</th>
			</tr>
			<tr>
				<?php 
					if( is_array($lstPlanillasx) ){
						foreach($lstPlanillasx as $fila) {
				?>
					<th><?php echo $fila['nombre']; ?>
						<input type="radio" id="pnll<?php echo $fila['planilla_id']; ?>" 
							name="pnll" value="<?php echo $fila['planilla_id']; ?>" />
						
					</th>
				<?php
						} 
					} 
				?>
				<th>Abstenci&oacute;n
					<input type="radio" id="allabst" name="allabst" value="1" />
				</th>
				<th>Nulo
					<input type="radio" id="allnulo" name="allnulo" value="1" />
				</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				if( is_array($lstCargosPostulados) ){
					foreach($lstCargosPostulados as $fila0) { 
			?>
			<tr>
				<td>
					<input type="hidden" name="cargo<?php echo $fila0['cargopost_id']; ?>" id="cargo<?php echo $fila0['cargopost_id']; ?>" value="<?php echo $fila0['cargopost_id']; ?>" />
					<input type="hidden" name="cargonm<?php echo $fila0['cargopost_id']; ?>" id="cargonm<?php echo $fila0['cargopost_id']; ?>" value="<?php echo $fila0['nombre']; ?>" />
					<?php echo $fila0['nombre']; ?>
				</td>
				<?php 
					if( is_array($lstPlanillasx) ){
						foreach($lstPlanillasx as $fila) {
				?>
					<td>
						<input type="radio" id="pnll<?php echo $fila0['cargopost_id']; ?>_<?php echo $fila['planilla_id']; ?>" 
							name="pnll<?php echo $fila0['cargopost_id']; ?>" value="<?php echo $fila['planilla_id']; ?>"
							onclick="cambiarTipoVoto(1, <?php echo $fila0['cargopost_id']; ?>, <?php echo $fila['planilla_id']; ?>);" />
					</td>
				<?php
					//Para Planillas
						} 
					} 
				?>
				<td>
					<input type="hidden" name="tipo_voto<?php echo $fila0['cargopost_id']; ?>" id="tipo_voto<?php echo $fila0['cargopost_id']; ?>" value="3" />
					<input type="radio" id="abst<?php echo $fila0['cargopost_id']; ?>" 
						name="abst<?php echo $fila0['cargopost_id']; ?>" 
						onclick="cambiarTipoVoto(3, <?php echo $fila0['cargopost_id']; ?>, <?php echo $fila['planilla_id']; ?>);" />
				</td>
				<td>
					<input type="radio" id="nulo<?php echo $fila0['cargopost_id']; ?>" 
						name="nulo<?php echo $fila0['cargopost_id']; ?>" 
						onclick="cambiarTipoVoto(2, <?php echo $fila0['cargopost_id']; ?>, <?php echo $fila['planilla_id']; ?>);" />
				</td>
			</tr>
			<?php
				//Para Cargos
					} 
				} 
			?>	
		</tbody>
	</table>
				<!-- Tabla de los resultados de el conteo de votaciones -->
			</td>
			<td style="width:50%; vertical-align: top;">
				<iframe id="idIframe" style="border:0px; width: 100%;" 
					src="<?php echo site_url('reporte/repvotPlanllPdf1/'); ?>"></iframe>
			</td>
		</tr>
	</table>
	</center>
	</div>
	<br /><br /><br /><br />
</div>

<script type="text/javascript">
  function iframeLoaded() {
      var iFrameID = document.getElementById('idIframe');
      if(iFrameID) {
            // here you can make the height, I delete it first, then I make it again
            iFrameID.height = "";
            iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
      }      
  }
</script>

<script>


	$(function() {
		var CENTROVOT = '';//$("#centrovot_id_hd").attr("value");
		var BUSCAR_SOC = $("#flt_socio_hd").attr("value");
		
		$("#centrovot_id").val( CENTROVOT );
		$("#flt_socio").val( BUSCAR_SOC );
		
		if( BUSCAR_SOC != "" ){
			$("#searchNh").hide();
			$("#nombre_socio").show();	
		}
		
		$("#flt_socio").change(function() {
			valor = $(this).val();
			
			$("#socioInfo").hide();
			$( '#btnGuardarVot' ).hide();
			$( '.msgErrVot' ).hide();
			
			if( valor != "" ){
				cambOrgnSoc( valor );
				$("#nombre_socio").val("");
				$("#socio_id").val("");
			}else{
				$("#nombre_socio").hide();
				$("#searchNh").show();
			}
		});
		
		$("input[name=pnll]").click(function() {
			var PLANILLA = $('input[name=pnll]:checked').val();
			//alert("Planilla " + PLANILLA );
			for( var i=0; i<cargos.length; i++ ){
				$('#pnll' + cargos[i].value + '_' + PLANILLA ).prop('checked', true);
				/*$('#abst' + cargos[i].value ).prop('checked', false);
				$('#nulo' + cargos[i].value ).prop('checked', false);
				$('#tipo_voto' + cargos[i].value ).val("1");*/
				cambiarTipoVoto(1, cargos[i].value, PLANILLA, null);
			}
		});
		
		$("input[name=allabst]").click(function() {
			for( var i=0; i<cargos.length; i++ ){
				$('#abst' + cargos[i].value).prop('checked', true);
				$('#nulo' + cargos[i].value).prop('checked', false);
				cambiarTipoVoto(3, cargos[i].value, null);
			}
			
			for( var i=0; i<planillas.length; i++ )
				$('#pnll' + planillas[i].value).prop('checked', false);
				
			$('#allnulo').prop('checked', false);
		});
		
		$("input[name=allnulo]").click(function() {
			for( var i=0; i<cargos.length; i++ ){
				$('#abst' + cargos[i].value).prop('checked', false);
				$('#nulo' + cargos[i].value).prop('checked', true);
				cambiarTipoVoto(2, cargos[i].value, null);
			}
			
			for( var i=0; i<planillas.length; i++ )
				$('#pnll' + planillas[i].value).prop('checked', false);
				
			$('#allabst').prop('checked', false);
		});
		
		$("input[name=dmpnll]").click(function() {
			var PLANILLA = $('input[name=dmpnll]:checked').val();
			//alert("Planilla " + PLANILLA );
			for( var i=0; i<cargos.length; i++ ){
				$('#dmpnll' + cargos[i].value + '_' + PLANILLA ).prop('checked', true);
				/*$('#abst' + cargos[i].value ).prop('checked', false);
				$('#nulo' + cargos[i].value ).prop('checked', false);
				$('#tipo_voto' + cargos[i].value ).val("1");*/
				cambiarTipoVoto(1, cargos[i].value, PLANILLA, 'dm');
			}
		});
	});


	function recordar(){
			alert("Hola");

	}
	
	function cambiarTipoVoto(tipo_voto, cargo_id, planilla_id, prefijo) {
		if(prefijo == null || prefijo.trim() == '')
			prefijo = '';
		//Si es abstencion o nulo, debera poner a false los demas radios
		if(tipo_voto == 2 || tipo_voto == 3) {
			for( var i=0; i<planillas.length; i++ )
				$('#' + prefijo + 'pnll' + cargo_id + '_' + planillas[i].value).prop('checked', false);
			if(tipo_voto == 2)
				$('#' + prefijo + 'abst' + cargo_id).prop('checked', false);
			if(tipo_voto == 3)
				$('#' + prefijo + 'nulo' + cargo_id).prop('checked', false);
			
		} else if(tipo_voto == 1) { //Poner a false abstencion y nulo
			
			$('#' + prefijo + 'abst' + cargo_id).prop('checked', false);
			$('#' + prefijo + 'nulo' + cargo_id).prop('checked', false);
			
			if(prefijo != null && prefijo.trim() != '') 
				$('#pnll' + cargo_id + '_' + planilla_id).prop('checked', true);
				
			$('#allabst').prop('checked', false);
			$('#allnulo').prop('checked', false);
		}
		
		$('#' + prefijo + 'tipo_voto' + cargo_id).val(tipo_voto);
		
		if(prefijo != null && prefijo.trim() != '')
			cambiarTipoVoto(tipo_voto, cargo_id, planilla_id, null);
	}
	
	function mostrarPopUp(){
		$(".pop-up-div-bg").show();
		$("#pop-up-div").show();
		refrescarDetalleVotacion();
	}
	
	function ocultarPopUp(){
		$(".pop-up-div-bg").hide();
		$("#pop-up-div").hide();
	}
	
	function mostrarPopUpLoad(){
		//$(".pop-up-div-bg").show();
		//$("#pop-up-div_load").show();
	}
	
	function ocultarPopUpLoad(){
		//$(".pop-up-div-bg").hide();
		//$("#pop-up-div_load").hide();
		//Recarcar la pagina para agregar una nueva votacion
		if($("#centrovot_id").attr("value") != null && $("#centrovot_id").attr("value") != '')
			location.href="<?php echo site_url('votacionsocio/loadVotacionSocio/?centrovot_id='); ?>" + $("#centrovot_id").attr("value");
		else
			location.href="<?php echo site_url('votacionsocio/loadVotacionSocio/'); ?>";
	}
	
	var votacionIdMsc = 0;
	
	function guardarDetVot() {
		//Antes validamos que en cada cargo tiene que estar chequeada una planilla, 
		// una abstencion o un nulo, pero no puede quedar una linea sin cheque
		var marcadoTodo = true;
		for( var cntC = 0; cntC < cargos.length; cntC++ ){
			var plaChkd = false;
			for( var cntP = 0; cntP < planillas.length; cntP++ )
				if($('#pnll' + cargos[cntC].value + '_' + planillas[cntP].value).prop('checked') == true) {
					plaChkd = true;
					break;
				}
			if(plaChkd == true || 
				$('#abst' + cargos[cntC].value).prop('checked') == true ||
				$('#nulo' + cargos[cntC].value).prop('checked') == true) {
					//nothing
				} else {
					marcadoTodo = false;
					break;
				}
		}
		
		if(marcadoTodo == false) {
			window.alert('Debe de chequear todos los cargos, ya sea para una planilla, abstención o nulo');
		} else {
		
			//ocultarPopUp();
			mostrarPopUpLoad();
			
			var id_maestro = null;
			var finalReg = 0;
			
			//alert( "Centro: " + $("#centrovot_id").attr("value") + " Votacion: " + $("#votacion_id").attr("value") );
			//Guardando el maestro
			dataStringMsc = "<?php echo site_url('votacionsocio/guardarMaestro'); ?>"
			+ "/" + $("#centrovot_id").attr("value") 
			+ "/" + $("#votacion_id").attr("value");
			//alert( dataStringMsc );
			
			$.ajax({
				url: dataStringMsc,
				success: function (data) {
					id_maestro = data != "FALSE"?data:null;
		    	},
				error: function (xhr, ajaxOptions, thrownError) {
					alert("Ha ocurrido un error y la operacion no pudo realizarse");
				},
				complete: function (data2, status) {


					/* Guardando la informacion en el detalle por lo tanto llama una funcion para guardar */
					for( var i=0; i < cargos.length; i++ ){
						var dataString = "<?php echo site_url('votacionsocio/guardarDetVot'); ?>"
						+ "/" + id_maestro 
						+ "/" + $("#cargo" + cargos[i].value ).val()
						+ "/" + $('input[name=pnll' + cargos[i].value + ']:checked').val()
						+ "/" + $('#tipo_voto' + cargos[i].value ).val()
						+ "/" + $('#votacion_id').val()
						+ "/" + $('#cargonm' + cargos[i].value ).val();
						//alert(dataString);
						//Guardando la informacion del detalle
						
						
						$.ajax({
							url: dataString,
							success: function (data) {
								$('#saveDetVotState').append(data);
					    	},
							error: function (xhr, ajaxOptions, thrownError) {
								jQuery('#errMtto3').css('display', 'block');	
									jQuery('#errMtto3').css('margin-left', 
										jQuery('#container').width() - (jQuery('#errMtto3').width() + 82)) ;
									jQuery('#errMtto3').fadeOut(5000).delay(500);
							},
							complete: function (data2, status) {
								if((cargos.length - i) <= 1) {
									//Aqui es cuando ya ha terminado de guardar el voto

									
									//$("#ocultPopUpBtn").show();
									//$("#savingDetVotTtl").hide();
									//$("#savedDetVotTtl").show();
									//$("#regVotBtn").hide();
									//ocultarPopUpLoad();
								}
								
								finalReg++;
								
								if( finalReg == (cargos.length) ){
									jQuery('#errMtto2').css('display', 'block');	
									jQuery('#errMtto2').css('margin-left', 
										jQuery('#container').width() - (jQuery('#errMtto2').width() + 82)) ;
									jQuery('#errMtto2').fadeOut(5000).delay(500);
									ocultarPopUpLoad();
									
								}	
							}
						});			
					}
					
				}
			});
		}
		
	}	
	
	/*error: function (xhr, ajaxOptions, thrownError) {
		alert("Ha ocurrido un error y la operacion no pudo realizarse Y" + thrownError);
	},*/



	     /* Hacer el siguiente procedimiento:
        * temp_votacion		: actualizar o insertar segun el procedimiento. (al final siempre inserta)
        * conteo_votacion 	: actualizar
        * det_votacion_socio: borrar los registros segun el numero de votacion de votacion_socio.
        * votacion_socio	: borrar el ultimo registro de voto.
        * */
       /* Aca vamos a inscribir la nueva funcion para reinvertir los votos */
	function reinvertirVotos(){
		//alert("Estamos en la funcion reinvertirVotos()");

		dataString = "<?php echo site_url('votacionsocio/estadoAnterior'); ?>";

		$.ajax({
			url: dataString,
			success: function (data) {
				alert(data);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				//alert("A ocurrido un error y la no pudo obtenerse la informacion del socio.");
			}
		});





	//*************************************************************************************


		//Registros();
		refrescar();   // refresca la pagina para que se se actulise los datos a nivel de vista


		}

		// Para borrar los registros para la estado anterior
	function deleteRegistros(){

	}

    /* Nos sirve para refrescar la pagina actual */
	function refrescar(){
			location.reload();
		}


	<?php
       /* if(isset($_POST)){
           // if (count($_POST)>0) //Solo se ejecutará si ha enviado los datos por formulario, dar click en el boton ENVIAR
            //{

            $query_select3="INSERT INTO temp_votacion (planilla_id, votacion_id, cargopost_id, votos, nulos, abstenciones, estado)
                             VALUES ( 1, 1, 1, 0, 0, 0, 'PEN')";
                 mysql_query($query_select3) or die(mysql_error());

            //}
            }*/
       ?>

/*
$.ajax({
			url: 'votacionsocio/loadvotacionsocio',
			success: function (data) {
				//Obtenemos la cantidad de productos del carrito
				alert(data);
	    	},
			error: function (xhr, ajaxOptions, thrownError) {
				alert("A ocurrido un error y la no pudo obtenerse la informacion del socio.");
			}
		});
*/







/* No viaja a votacionsocio/estadoAnterior   debido que la pagina cuenta con una validacion en el cual si los chekbox no estan
* chekeados no se puede hacer ni una transaccion */

/*
		$.ajax({
			url: dataString,
			success: function (data) {
				//Obtenemos la cantidad de productos del carrito
				alert(data);
	    	},
			error: function (xhr, ajaxOptions, thrownError) {
				alert("A ocurrido un error y la no pudo obtenerse la informacion del socio.");
			}
		});*/



	/*  Fin de reinversion votos */


     /* Preguntas de confirmacion */
	function pregunta(){
    if (confirm('¿Seguro que quiere hacer REVISION?')){
       document.miformulario.submit()
    }
}

 function confirmar(){
    if (confirm('¿Seguro que quiere registrar los VOTOS?')){
       document.formulariox.submit()
    }
}


/*	$.ajax({
	url: "dataString",
	data: { op1:true }
	}).done(function( msg ) {
	alert( "Los datos que se recibieron: " + msg );
	}); */


	/* $.ajax({
 3     type: "POST",
 4     url: "votacionsocio.php",
 5     data: { "opp" :  true },
 6     success: function(data){
 7         alert(data);
 8     }
 9 });*/



	
	function refrescarDetalleVotacion(){
		for( var i=0; i<cargos.length; i++ ){
			var PLANILLA = $('input[name=pnll' + cargos[i].value + ']:checked').val();
			$('#dmpnll' + cargos[i].value + '_' + PLANILLA ).prop('checked', true);
						
			var TIPOVOTO = $('#tipo_voto' + cargos[i].value ).val();
			$('#dmtipo_voto' + cargos[i].value ).val( TIPOVOTO );
			
			if(TIPOVOTO == 3)
				$('#dmabst' + cargos[i].value).prop('checked', true);
			
			if(TIPOVOTO == 2)
				$('#dmnulo' + cargos[i].value).prop('checked', true);
				
			if(TIPOVOTO == 1) {
				$('#dmnulo' + cargos[i].value).prop('checked', false);
				$('#dmabst' + cargos[i].value).prop('checked', false);
			}
		}
	}
	
	function cambOrgnSoc( origen ){
		dataString = "<?php echo site_url('votacionsocio/cambOrgnSoc/'); ?>" + "/" + origen;
		//Enviando la informacion al carrito via ajax itemsCart
		$.ajax({
			url: dataString,
			success: function (data) {
				//Obtenemos la cantidad de productos del carrito
				$('#socioSource').html(data);
				$("#searchNh").hide();
				$("#nombre_socio").show();
	    	},
			error: function (xhr, ajaxOptions, thrownError) {
				alert("A ocurrido un error y la operacion no pudo realizarce");
			}
		});
	}
	
	function loadInfoSocio( idSocio ){
		dataString = "<?php echo site_url('votacionsocio/loadInfoSocio/'); ?>" + "/" + idSocio;
		//Enviando la informacion al carrito via ajax itemsCart
		$.ajax({
			url: dataString,
			success: function (data) {
				//Obtenemos la cantidad de productos del carrito
				$( '#socioInfo' ).html(data);
				$( '#socioInfo1' ).html(data);
				$("#socioInfo").show();
	    	},
			error: function (xhr, ajaxOptions, thrownError) {
				alert("A ocurrido un error y la no pudo obtenerse la informacion del socio.");
			}
		});
	}
	
	function verfcrVotSocio( idSocio ){
		//ID del socio y ID del centro de votacion
		dataString = "<?php echo site_url('votacionsocio/verfcrVotSocio/'); ?>" + "/" + idSocio + "/" +  $("#centrovot_id").attr("value");
		//Enviando la informacion via ajax
		$.ajax({
			url: dataString,
			success: function (data) {
				if( data != "NULL" ){
					$( '.msgErrVot' ).html(data);	
					$( '.msgErrVot' ).show();
					$( '#btnGuardarVot' ).hide();
				}else{
					$( '.msgErrVot' ).hide();
					$( '#btnGuardarVot' ).show();	
				}
	    	},
			error: function (xhr, ajaxOptions, thrownError) {
				alert("A ocurrido un error y la no pudo obtenerse la informacion del socio.");
			}
		});
	}
	
</script>

<?php if(isset($centrovot_id) && $centrovot_id != '' && $centrovot_id != NULL) { ?>
	<script type="text/javascript">
		window.onload=function() {
			// $('#centrovot_id').val(<?php echo $centrovot_id; ?>);
			$("#centrovot_id option[value=<?php echo "'" . $centrovot_id ."'"; ?>]").attr("selected", "true");
		}
	</script>
<?php } ?>

<!--<script language="javascript" type="text/javascript" >
	<?php if($accion != 'INS') { ?>
		loadInfoSocio( <?php echo isset($socio_id)?$socio_id:set_value('socio_id');?> );
	<?php } ?>	
</script>-->

<script language="javascript" type="text/javascript" >
	var cargos = [  
		<?php  $cnt1 = 0;
			$comma1 = 0;
			if( is_array($lstCargosPostulados) ){
			foreach($lstCargosPostulados as $fila) {
				
			if( $comma1 == 1 ){
				echo ",";
			}					
		?>    
		{        
			value: "<?php echo $fila['cargopost_id'] ?>",
			label: "<?php echo $fila['nombre']; ?>"
		}      
		<?php
			$comma1 = 1;
			$cnt1++;
			}
		}
		?>      
	];
	
	var planillas = [  
		<?php  $cnt2 = 0;
			$comma2 = 0;
			if( is_array($lstPlanillas) ){
			foreach($lstPlanillas as $fila) {
				
			if( $comma2 == 1 ){
				echo ",";
			}					
		?>    
		{        
			value: "<?php echo $fila['planilla_id'] ?>",
			label: "<?php echo $fila['nombre']; ?>"
		}      
		<?php
			$comma2 = 1;
			$cnt2++;
			}
		}
		?>      
	];
	
	<?php 
		if($accion === 'UPD') {
			if( $tn_det == 1 ){
	?>  
		 
		var detsvotacion = [  
			<?php  $cnt3 = 0;
				$comma3 = 0;
				if( is_array($lstDetVotacion) ){
				foreach($lstDetVotacion as $fila) {
					
				if( $comma3 == 1 ){
					echo ",";
				}					
			?>    
			{        
				votsocdet_id: "<?php echo $fila['votsocdet_id'] ?>",
				votsoc_id: "<?php echo $fila['votsoc_id']; ?>",
				cargopost_id: "<?php echo $fila['cargopost_id']; ?>",
				planilla_id: "<?php echo $fila['planilla_id']; ?>",
				tipo_voto: "<?php echo $fila['tipo_voto']; ?>",
				votacion_id: "<?php echo $fila['votacion_id']; ?>"
			}      
			<?php
				$comma3 = 1;
				$cnt3++;
				}
			}
			?>      
		];
			 
		for( var i=0; i<detsvotacion.length; i++ ){
			$( "#cargo" + detsvotacion[i].cargopost_id ).val( detsvotacion[i].cargopost_id );
			$('#pnll' + detsvotacion[i].cargopost_id + '_' + detsvotacion[i].planilla_id ).attr("checked", "checked");
			$('#tipo_voto' + detsvotacion[i].cargopost_id ).val( detsvotacion[i].tipo_voto );
		}
		
	<?php
			} 
		} 
	?>	
	
	/*var planillasString = "";
	var cargosString = "";
	
	for( var i=0; i<planillas.length; i++ ){
		planillasString += "Planilla: " + planillas[i].label + " " + " Valor: " + planillas[i].value + "\n";
	}   
	alert( planillasString );
	
	for( var i=0; i<cargos.length; i++ ){
		cargosString += "Cargo: " + cargos[i].label + " " + " Valor: " + cargos[i].value + "\n";
	}   
	alert( cargosString );*/
	
	//Para acciones del teclado
	shortcut.add("F2", function() {
		$('#btnGuardarVot').click();
	});
	
	shortcut.add("F4", function() {
		if( $('#conf_det').val() == 0 ){
			$('#conf_det').val("1");
			//$('#regVotBtn').click();	
			$('#btnGuardarDetVot').click();
		}else{
			if( $('#conf_det').val() == 1 ){
				$('#btnGuardarDetVot').click();
			}
		}
		
	});		
	
	shortcut.add("F7", function() {
		$('#btnGuardarDetVot').click();
	});
	
	shortcut.add("F9", function() {
		$('#btnNvaVtcn').click();
	});
</script>	

<div id="socioSource">
	<!-- Cargar el filtro de busqueda del socio segun el tipo de filtro -->
</div>	

<!-- Popup de la aplicacion -->
<?php if($accion != 'DEL') { ?>
<div class="pop-up-div-bg"></div>
<div id="pop-up-div" class="pop-up-div">
	<center>
		<h1>Está seguro que desea guardar esta votación?</h1>
	
		<div id="socioInfo1">
			<!-- Mostrar infomacion del socio -->
		</div>
		<h1>Detalle de la votacion</h1>
		<div id="detVotacionSocio">
			<table class="tbl-info-det" width="90%" cellpadding="1" cellspacing="0" border="0">
				<thead>
					<tr>
						<th rowspan="2">Cargos postulados</th>
						<th colspan="<?php echo count($lstPlanillas); ?>">Planillas</th>
						<th colspan="2">Tipo de voto</th>
					</tr>
					<tr>
						<?php 
							if( is_array($lstPlanillas) ){
								foreach($lstPlanillas as $fila) { 
						?>
							<th><?php echo $fila['nombre']; ?>
								<input type="radio" id="dmpnll<?php echo $fila['planilla_id']; ?>" 
									name="dmpnll" value="<?php echo $fila['planilla_id']; ?>" />
							</th>
						<?php
								} 
							} 
						?>
						<th>Abstenci&oacute;n</th>
						<th>Nulo</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						if( is_array($lstCargosPostulados) ){
							foreach($lstCargosPostulados as $fila0) { 
					?>
					<tr>
						<td>
							<input type="hidden" name="dmcargo<?php echo $fila0['cargopost_id']; ?>" id="dmcargo<?php echo $fila0['cargopost_id']; ?>" value="<?php echo $fila0['cargopost_id']; ?>" />
							<?php echo $fila0['nombre']; ?>
						</td>
						<?php 
							if( is_array($lstPlanillas) ){
								foreach($lstPlanillas as $fila) { 
						?>
							<td>
								<input type="radio" readonly="true" id="dmpnll<?php echo $fila0['cargopost_id']; ?>_<?php echo $fila['planilla_id']; ?>" 
								name="dmpnll<?php echo $fila0['cargopost_id']; ?>" value="<?php echo $fila['planilla_id']; ?>"
								onclick="cambiarTipoVoto(1, <?php echo $fila0['cargopost_id']; ?>, <?php echo $fila['planilla_id']; ?>, 'dm');" />
							</td>
						<?php
							//Para Planillas
								} 
							} 
						?>
						<td>
							<input type="hidden" name="dmtipo_voto<?php echo $fila0['cargopost_id']; ?>" id="dmtipo_voto<?php echo $fila0['cargopost_id']; ?>" />
							<input type="radio" id="dmabst<?php echo $fila0['cargopost_id']; ?>" 
								name="dmabst<?php echo $fila0['cargopost_id']; ?>"
								onclick="cambiarTipoVoto(3, <?php echo $fila0['cargopost_id']; ?>, <?php echo $fila['planilla_id']; ?>, 'dm');" />
						</td>
						<td>
							<input type="radio" id="dmnulo<?php echo $fila0['cargopost_id']; ?>" 
								name="dmnulo<?php echo $fila0['cargopost_id']; ?>" 
								onclick="cambiarTipoVoto(2, <?php echo $fila0['cargopost_id']; ?>, <?php echo $fila['planilla_id']; ?>, 'dm');" />
						</td>
					</tr>
					<?php
						//Para Cargos
							} 
						} 
					?>	
				</tbody>
			</table>
		</div>
		<br />
		<table cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td>
					<input type="submit" class="clean-gray bnts-cntls" id="btnGuardarDetVot"
						onclick="guardarDetVot(); return false;" value="Guardar" />&nbsp;&nbsp;
				</td>
				<td>
					<input type="submit" class="clean-gray bnts-cntls" 
						onclick="ocultarPopUp(); return false;" value="Cancelar" />
				</td>
			</tr>
		</table>
	</center>
</div>

<div id="pop-up-div_load" class="pop-up-div" style="top:120px; left:100%; margin-left: -350px;  width:300px;">
	<center>
		<div id="savingDetVotTtl">
			<h1>Guardando Informacion del Detalle</h1>
			<img src="<?php echo site_url('images/ajax-loader.gif'); ?>" />
		</div>
		<div id="savedDetVotTtl" style="display: none;">
			<h1>El Detalle se ha Terminado de Guardar</h1>
		</div>
	</center>	
	<div id="saveDetVotState" class="rmrcr" style="display: none;"></div>
	<br />
	<center>
		<div id="ocultPopUpBtn">
			<input type="submit" class="clean-gray bnts-cntls" id="btnNvaVtcn"
				onclick="ocultarPopUpLoad(); return false;" value="Aceptar" />
		</div>
	</center>
</div>
<?php } ?>

<?php $this->load->view('footer'); ?>
