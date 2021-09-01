<?php $this->load->view('header'); ?>

<div id="content">
	<h1>Candidato > Detalle de la Candidato</h1>
	<form method="POST" id="opcionesmenu" enctype="multipart/form-data" action="<?php echo site_url('candidato'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>" />
	<input type="hidden" name="candidato_id" id="candidato_id" value="<?php echo isset($candidato_id)?$candidato_id:set_value('candidato_id');?>" />
	<center>
	<table class="tblform">
		<tr>
			<td>Socio: </td>
			<td>
				<input type="hidden" name="socio_id" id="socio_id" value="<?php echo isset($socio_id)?$socio_id:set_value('socio_id');?>" />
				<input type="text" name="nombre_socio" id="nombre_socio" class="textform" maxlength="10" value="<?php echo isset($nombre_socio)?$nombre_socio:set_value('nombre_socio');?>" />
				<td style="width:20px;"></td>
				<td></td>
				<td></td>
			</td>
		</tr>
		<tr>
			<td>Nombres del candidato: </td>
			<td><input type="text" name="nombres" id="nombres" class="textform" maxlength="40" value="<?php echo isset($nombres)?$nombres:set_value('nombres');?>" /></td>
			<td style="width:20px;"></td>
			<td>Apellidos del candidato: </td>
			<td><input type="text" name="apellidos" id="apellidos" class="textform" maxlength="40" value="<?php echo isset($apellidos)?$apellidos:set_value('apellidos');?>" /></td>
		</tr>
		<tr>
			<td>Cargo al que se postula: </td>
			<td>
				<input type="hidden" name="cargopost_id_hd" id="cargopost_id_hd" value="<?php echo isset($cargopost_id)?$cargopost_id:set_value('cargopost_id');?>" />
				<select name="cargopost_id" id="cargopost_id" >
					<?php 
						//Mostrando los cargos postulados
						if( is_array($lstCargosPostulados) ){
							foreach($lstCargosPostulados as $fila) { 
					?>
						<option value="<?php echo $fila['cargopost_id'] ?>"><?php echo $fila['nombre'] ?></option>
					<?php
							} 
						} 
					?>					
				</select>
			</td>
			<td style="width:20px;"></td>
			<td>Planilla de votacion: </td>
			<td>
				<input type="hidden" name="planilla_id_hd" id="planilla_id_hd" value="<?php echo isset($planilla_id)?$planilla_id:set_value('planilla_id');?>" />
				<select name="planilla_id" id="planilla_id" >
					<?php 
						//Mostrando los cargos postulados
						if( is_array($lstPlanillas) ){
							foreach($lstPlanillas as $fila) { 
					?>
						<option value="<?php echo $fila['planilla_id'] ?>"><?php echo $fila['nombre'] ?></option>
					<?php
							} 
						} 
					?>					
				</select>
			</td>
		</tr>
		<tr>
			<td>Votacion activa: </td>
			<td>
				<input type="hidden" name="votacion_id" id="votacion_id" value="<?php echo isset($votacion_id)?$votacion_id:set_value('votacion_id');?>" />
				<input type="text" name="nombre_votacion" id="nombre_votacion" readonly="true" class="textform" maxlength="10" value="<?php echo isset($nombre_votacion)?$nombre_votacion:set_value('nombre_votacion');?>" />
			</td>
			<td style="width:20px;"></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5" class="btnsCell">
			<?php if($accion === 'INS') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/candidato/insCandidato') . "'"; ?>);" value="Guardar" />
			<?php } else if($accion === 'UPD') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/candidato/updCandidato') . "'"; ?>);" value="Actualizar" />
			<?php } else if($accion === 'DEL') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionFormConfirm(<?php echo "'" . site_url('/candidato/delCandidato') . "'"; ?>, 'Esta seguro que desea eliminar este candidato.'); " value="Eliminar" />
				<?php }  ?>&nbsp;
				<input type="submit" class="clean-gray" onclick="return setAccionForm(<?php echo "'" . site_url('/candidato/lstCandidatos') . "'"; ?>);" value="Regresar" />
			</td>
		</tr>
	</table>
	<div id="errorsForm">
		<?php echo validation_errors(); ?>
	</div>
	</center>
	</form>
</div>

<script>
	$(function() {
		var CARGO = $("#cargopost_id_hd").attr("value");
		var PLANILLA = $("#planilla_id_hd").attr("value");
		
		$("#planilla_id").val(PLANILLA);
		$("#cargopost_id").val(CARGO);
	});
	
		$( "#nombre_socio" ).autocomplete({      
		source: "<?php echo site_url('/cargopostulado/ajxSocios') ?>",
      	minLength: 2,      
			focus: function( event, ui ) {        
				$( "#nombre_socio" ).val( ui.item.label );        
				return false;      
			},      
			select: function( event, ui ) {
				$( "#socio_id" ).val( ui.item.value );
				$( "#nombre_socio" ).val( ui.item.label );
				setNombreCompSoc( ui.item.nombres, ui.item.apellidos );
				return false;      
			}    
		})    
		.data( "ui-autocomplete" )._renderItem = function( ul, item ) {      
			return $( "<li>" )
			.append( "<a><strong>Codigo: </strong>" + item.codigo + " <strong>Nombres: </strong>" +  item.label + "</strong></a>" )
			.appendTo( ul );    
		};
		
	function setNombreCompSoc( nombres, apellidos ){
		$("#nombres").val( nombres );
		$("#apellidos").val( apellidos );
	}
	
</script>

<?php $this->load->view('footer'); ?>
