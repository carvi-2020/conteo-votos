<?php $this->load->view('header'); ?>

<style type="text/css">
	.cntnr-head{ overflow:hidden; background:#F4F4F4; border:1px solid #CCCCCC; padding:12px 0px;}
	.nobk{ background:none;}
	.bnts-cntls{ width:100px !important; padding:0px !important; height:23px; font:11px/12px Arial, Helvetica, sans-serif !important; }
	#nombre_socio{ display:none;}
</style>

<div id="content">
	<h1>Votacion del Socio > Emision del Voto</h1>
	<form method="POST" id="opcionesmenu" enctype="multipart/form-data" action="<?php echo site_url('votacionsocio'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>" />
	<input type="hidden" name="votsoc_id" id="votsoc_id" value="<?php echo isset($votsoc_id)?$votsoc_id:set_value('votsoc_id');?>" />
	<input type="hidden" name="socio_id" id="socio_id" value="<?php echo isset($socio_id)?$socio_id:set_value('socio_id');?>" />
	<input type="hidden" name="tn_det" id="tn_det" value="<?php echo isset($tn_det)?$tn_det:set_value('tn_det');?>" />
	<center>
	<table class="tblform">
		<tr>
			<td>Votacion Activa: </td>
			<td>
				<input type="hidden" name="votacion_id" id="votacion_id" value="<?php echo isset($votacion_id)?$votacion_id:set_value('votacion_id');?>" />
				<input type="text" name="nombre_votacion" id="nombre_votacion" readonly="true" class="textform" maxlength="10" value="<?php echo isset($nombre_votacion)?$nombre_votacion:set_value('nombre_votacion');?>" />
			</td>
		</tr>
	</table>
	</center>
		
	<h1>Centro de Votacion</h1>
	<center>
		<table class="tblform">
			<tr>
				<td>Centro de votaci&oacute;n: </td>
				<td>
					<input type="hidden" name="centrovot_id_hd" id="centrovot_id_hd" value="<?php echo isset($centrovot_id)?$centrovot_id:set_value('centrovot_id');?>" />
					<select name="centrovot_id" id="centrovot_id" >
						<?php 
							//Mostrando los centros de votacion
							if( is_array($lstCentroVotaciones) ){
								foreach($lstCentroVotaciones as $fila) { 
						?>
							<option value="<?php echo $fila['centrovot_id'] ?>"><?php echo $fila['nombre'] ?></option>
						<?php
								} 
							} 
						?>					
					</select>
				</td>
			</tr>
		</table>
	</center>
	
	<?php if($accion === 'INS') { ?>
		<h1>Busqueda del Socio</h1>
	<?php } ?>
	<center>
	<table class="tblform" <?php if($accion != 'INS') { ?> style="display:none;" <?php } ?>>
		<tr>
			<td>Buscar Socio Por: </td>
			<td>
				<input type="hidden" name="flt_socio_hd" id="flt_socio_hd" 
					value="<?php echo isset($flt_socio)?$flt_socio:set_value('flt_socio');?>" />
					
				<select name="flt_socio" id="flt_socio" >
					<option value="">Seleccione</option>
					<option value="codigo">Codigo</option>
					<option value="jvpm">JVPM</option>
					<option value="nombres">Nombres</option>
					<option value="apellidos">Apellidos</option>
					<option value="dui">DUI</option>
				</select>
			</td>
			<td style="width:20px;"></td>
			<td>Socio: </td>
			<td>
				<div id="searchNh" class="rmrcr">No hay metodo de busqueda definido</div>
				<input type="text" name="nombre_socio" id="nombre_socio" class="textform" maxlength="60" 
					value="<?php echo isset($nombre_socio)?$nombre_socio:set_value('nombre_socio');?>" />
			</td>
		</tr>
	</table>
	
	<div id="socioInfo">
		<!-- Mostrar infomacion del socio -->
	</div>
	</center>
	
	<br />
	<center>
	<div class="msgErrVot">
		<!-- Mostrar mensaje de error en caso de que el socio ya halla votado -->
	</div>
	<table class="tblform">
		<tr>
		<td class="btnsCell">
			<?php if($accion === 'INS') { ?>
				<input type="submit" class="clean-gray bnts-cntls" style="display:none;" id="btnGuardarVot"
						onclick="return setAccionForm(<?php echo "'" . site_url('/votacionsocio/insVotacionSocio') . "'"; ?>);" 
						value="Guardar" />
			<?php } else if($accion === 'UPD') { ?>
				<!--<input type="submit" class="clean-gray bnts-cntls" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/votacionsocio/updVotacionSocio') . "'"; ?>);" value="Actualizar" />-->
			<?php } else if($accion === 'DEL') { ?>
				<!--<input type="submit" class="clean-gray bnts-cntls" 
						onclick="return addAccionFormConfirm(<?php echo "'" . site_url('/votacionsocio/delVotacionSocio') . "'"; ?>, 'Esta seguro que desea eliminar esta votacion.'); " value="Eliminar" />-->
				<?php }  ?>&nbsp;
				<input type="submit" class="clean-gray bnts-cntls" onclick="return setAccionForm(<?php echo "'" . site_url('/votacionsocio/lstVotacionesSocio') . "'"; ?>);" value="Regresar" />
		</td>
		</tr>
	</table>
	</center>
	
	<div id="errorsForm">
		<?php echo validation_errors(); ?>
	</div>
	
	</form>
	
	<div <?php if($accion === 'INS') { ?> style="display:none;" <?php } ?> >
	<h1>Detalle del Voto</h1>
	<center>
	<div
		<?php 
			if($accion != 'INS') {
				if( $tn_det == 1 ){
		?>  
			style="display:none;" 
		<?php
				} 
			} 
		?> >
		<input type="submit" class="clean-gray bnts-cntls" id="regVotBtn" 
		onclick="mostrarPopUp(); return false;" value="Registrar Votacion" />
		<br /><br />
	</div>
	
	<table class="tbl-info-det" width="90%" cellpadding="1" cellspacing="0" border="0">
		<thead>
			<tr>
				<th>Cargo</th>
				<?php 
					if( is_array($lstPlanillas) ){
						foreach($lstPlanillas as $fila) { 
				?>
					<th><?php echo $fila['nombre']; ?></th>
				<?php
						} 
					} 
				?>
				<th>Tipo de Voto</th>
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
					if( is_array($lstPlanillas) ){
						foreach($lstPlanillas as $fila) { 
				?>
					<td>
						<input type="radio" id="pnll<?php echo $fila0['cargopost_id']; ?><?php echo $fila['planilla_id']; ?>" name="pnll<?php echo $fila0['cargopost_id']; ?>" value="<?php echo $fila['planilla_id']; ?>" />
					</td>
				<?php
					//Para Planillas
						} 
					} 
				?>
				<td>
					<select name="tipo_voto<?php echo $fila0['cargopost_id']; ?>" id="tipo_voto<?php echo $fila0['cargopost_id']; ?>" >
						<option value="3">Voto abstenido</option>
						<option value="1">Voto normal</option>
						<option value="2">Voto nulo</option>
					</select>
				</td>
			</tr>
			<?php
				//Para Cargos
					} 
				} 
			?>	
		</tbody>
	</table>
	</center>
	</div>
	<br /><br /><br /><br />
</div>

<script>
	$(function() {
		var CENTROVOT = $("#centrovot_id_hd").attr("value");
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
	});
	
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
		$(".pop-up-div-bg").show();
		$("#pop-up-div_load").show();
	}
	
	function ocultarPopUpLoad(){
		$(".pop-up-div-bg").hide();
		$("#pop-up-div_load").hide();
	}
	
	function guardarDetVot(){
		ocultarPopUp();
		mostrarPopUpLoad();
		
		for( var i=0; i<cargos.length; i++ ){
			dataString = "<?php echo site_url('votacionsocio/guardarDetVot/'); ?>"
			+ "/" + $("#votsoc_id").val() 
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
					alert("A ocurrido un error y la operacion no pudo realizarce");
				}
			});			
		}
		
		$("#ocultPopUpBtn").show();
		$("#savingDetVotTtl").hide();
		$("#savedDetVotTtl").show();
		$("#regVotBtn").hide();
		//ocultarPopUpLoad();
	}	
	
	function refrescarDetalleVotacion(){
		for( var i=0; i<cargos.length; i++ ){
			var PLANILLA = $('input[name=pnll' + cargos[i].value + ']:checked').val();
			$('#dmpnll' + cargos[i].value + '' + PLANILLA ).attr("checked", "checked");
						
			var TIPOVOTO = $('#tipo_voto' + cargos[i].value ).val();
			$('#dmtipo_voto' + cargos[i].value ).val( TIPOVOTO );
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
		dataString = "<?php echo site_url('votacionsocio/verfcrVotSocio/'); ?>" + "/" + idSocio;
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
		if($accion != 'INS') {
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
			$('#pnll' + detsvotacion[i].cargopost_id + '' + detsvotacion[i].planilla_id ).attr("checked", "checked");
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
</script>	

<div id="socioSource">
	<!-- Cargar el filtro de busqueda del socio segun el tipo de filtro -->
</div>	

<!-- Popup de la aplicacion -->
<div class="pop-up-div-bg"></div>
<div id="pop-up-div" class="pop-up-div">
	<center>
		<h1>Está seguro que desea guardar esta votación?</h1>
	
		<div id="socioInfo1">
			<!-- Mostrar infomacion del socio -->
		</div>
		<br />
		<h1>Detalle de la votacion</h1>
		<div id="detVotacionSocio">
			<table class="tbl-info-det" width="90%" cellpadding="1" cellspacing="0" border="0">
				<thead>
					<tr>
						<th>Cargo</th>
						<?php 
							if( is_array($lstPlanillas) ){
								foreach($lstPlanillas as $fila) { 
						?>
							<th><?php echo $fila['nombre']; ?></th>
						<?php
								} 
							} 
						?>
						<th>Tipo de Voto</th>
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
								<input type="radio" readonly="true" id="dmpnll<?php echo $fila0['cargopost_id']; ?><?php echo $fila['planilla_id']; ?>" name="dmpnll<?php echo $fila0['cargopost_id']; ?>" value="<?php echo $fila['planilla_id']; ?>" />
							</td>
						<?php
							//Para Planillas
								} 
							} 
						?>
						<td>
							<select readonly="true" name="dmtipo_voto<?php echo $fila0['cargopost_id']; ?>" id="dmtipo_voto<?php echo $fila0['cargopost_id']; ?>" >
								<option value="3">Voto abstenido</option>
								<option value="1">Voto normal</option>
								<option value="2">Voto nulo</option>
							</select>
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
					<input type="submit" class="clean-gray bnts-cntls" 
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

<div id="pop-up-div_load" class="pop-up-div" style="top:200px; width:550px; margin-left:-275px;">
	<center>
		<div id="savingDetVotTtl">
			<h1>Guardando Informacion del Detalle</h1>
			<img src="<?php echo site_url('images/ajax-loader.gif'); ?>" />
		</div>
		<div id="savedDetVotTtl">
			<h1>El Detalle se ha Terminado de Guardar</h1>
		</div>
	</center>	
	<div id="saveDetVotState" class="rmrcr"></div>
	<br />
	<center>		
		<div id="ocultPopUpBtn" style="display:none;">
			<input type="submit" class="clean-gray bnts-cntls" 
				onclick="ocultarPopUpLoad(); return false;" value="Aceptar" />
		</div>
	</center>
</div>

<?php $this->load->view('footer'); ?>