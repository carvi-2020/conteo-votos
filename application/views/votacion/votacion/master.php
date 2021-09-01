<?php $this->load->view('header'); ?>

<div id="content">
	<h1>Votacion > Detalle de la Votacion</h1>
	<form method="POST" id="opcionesmenu" enctype="multipart/form-data" action="<?php echo site_url('votacion'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>" />
	<input type="hidden" name="votacion_id" id="votacion_id" value="<?php echo isset($votacion_id)?$votacion_id:set_value('votacion_id');?>" />
	<center>
	<table class="tblform">
		<tr>
			<td>Nombre de la Votacion: </td>
			<td><input type="text" name="nombre" id="nombre" class="textform" maxlength="20" value="<?php echo isset($nombre)?$nombre:set_value('nombre');?>" /></td>
			<td style="width:20px;"></td>
			<td>El a&ntilde;o de la votacion: </td>
			<td>
				<input type="text" name="anio" id="anio" class="textform" maxlength="5"
					onkeypress="return restrictNumDigits(event, this, 4);" 
					value="<?php echo isset($anio)?$anio:set_value('anio');?>" />
			</td>
		</tr>
		<tr>
			<td>Situacion de la votacion: </td>
			<td>
				<?php 
					if($accion === 'INS') {
						echo "Activa"; 
				?>
					<input type="hidden" name="situacion" id="situacion" value="ACT" />
				<?php } ?>
				
				<?php if($accion === 'UPD') { ?>
					<input type="hidden" name="situacion1" id="situacion1" value="<?php echo isset($situacion)?$situacion:set_value('situacion');?>" />
					<select name="situacion" id="situacion" >
						<option value="ACT">Activa</option>					
						<option value="DEF">Definida</option>
						<option value="CER">Cerrada</option>									
					</select>
				<?php } ?>		
			</td>
			<td style="width:20px;"></td>
			<td>Estado de la votacion: </td>
			<td>
				<?php 
					if($accion === 'INS') {
						echo "Estado Activo"; 
				?>
					<input type="hidden" name="estado" id="estado" value="ACT" />
				<?php } ?>
				
				<?php if($accion === 'UPD') { ?>
					<input type="hidden" name="estado1" id="estado1" value="<?php echo isset($estado)?$estado:set_value('estado');?>" />
					<select name="estado" id="estado" >
						<option value="ACT">Activo</option>					
						<option value="INA">Inactivo</option>									
					</select>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td colspan="5" align="center">
				<?php 
					if( $accion === 'UPD' ) {
						if( $estado == 'ACT' && $situacion == 'ACT' ){ 
				?>
						<input type="submit" class="clean-gray" value="Definir Votacion" />
				<?php
						} 
					} 
				?>
				
				<?php if($accion === 'INS') { ?>
					<input type="submit" class="clean-gray" value="Definir Votacion" />
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td colspan="5" class="btnsCell">
			<?php if($accion === 'INS') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/votacion/insVotacion') . "'"; ?>);" value="Guardar" />
			<?php } else if($accion === 'UPD') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/votacion/updVotacion') . "'"; ?>);" value="Actualizar" />
			<?php } else if($accion === 'DEL') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionFormConfirm(<?php echo "'" . site_url('/votacion/delVotacion') . "'"; ?>, 'Esta seguro que desea eliminar esta votacion.'); " value="Eliminar" />
				<?php }  ?>&nbsp;
				<input type="submit" class="clean-gray" onclick="return setAccionForm(<?php echo "'" . site_url('/votacion/lstVotaciones') . "'"; ?>);" value="Regresar" />
			</td>
		</tr>
	</table>
	<div id="errorsForm">
		<?php echo validation_errors(); ?>
	</div>
	</center>
	</form>
</div>

<?php if($accion === 'UPD') { ?>
<script>
	$(function() {
		var ESTADO = $("#estado1").attr("value");
		var SITUASION = $("#situacion1").attr("value");
		
		$("#estado").val(ESTADO);
		$("#situacion").val(SITUASION);
	});
</script>
<?php }  ?>

<?php $this->load->view('footer'); ?>
