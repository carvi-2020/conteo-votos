<?php $this->load->view('header'); ?>

<div id="content">
	<h1>Planilla > Detalle de la Planilla</h1>
	<form method="POST" id="opcionesmenu" enctype="multipart/form-data" action="<?php echo site_url('planilla'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>" />
	<input type="hidden" name="planilla_id" id="planilla_id" value="<?php echo isset($planilla_id)?$planilla_id:set_value('planilla_id');?>" />
	<center>
	<table class="tblform">
		<tr>
			<td>Codigo de la planilla: </td>
			<td><input type="text" name="codigo" id="codigo" class="textform" maxlength="5" value="<?php echo isset($codigo)?$codigo:set_value('codigo');?>" /></td>
			<td style="width:20px;"></td>
			<td>Nombre de la planilla: </td>
			<td><input type="text" name="nombre" id="nombre" class="textform" maxlength="40" value="<?php echo isset($nombre)?$nombre:set_value('nombre');?>" /></td>
		</tr>
		<tr>
			<td>Estado de la planilla: </td>
			<td>
				<?php
					if($accion === 'INS') {
				?>
					<input type="hidden" name="estado" id="estado" maxlength="3" value="ACT" />
					
					<span class="rmrcr">
					<?php 
						if($accion === 'INS') {
							echo "Estado Activo";
						} 
					?>
					</span>
				
				<?php } ?>

				<?php if($accion != 'INS') { ?>
					<input type="hidden" name="estado1" id="estado1" value="<?php echo isset($estado)?$estado:set_value('estado');?>" />
					<select name="estado" id="estado" >
						<option value="ACT">Activo</option>					
						<option value="INA">Inactivo</option>									
					</select>
				<?php } ?>
				
				
			</td>
			<td style="width:20px;"></td>
			<td>Votacion Activa</td>
			<td>
				<input type="hidden" name="votacion_id" id="votacion_id" value="<?php echo isset($votacion_id)?$votacion_id:set_value('votacion_id');?>" />
				<input type="text" name="nombre_votacion" id="nombre_votacion" readonly="true" class="textform" maxlength="10" value="<?php echo isset($nombre_votacion)?$nombre_votacion:set_value('nombre_votacion');?>" />
			</td>
		</tr>
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5" class="btnsCell">
			<?php if($accion === 'INS') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/planilla/insPlanilla') . "'"; ?>);" value="Guardar" />
			<?php } else if($accion === 'UPD') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/planilla/updPlanilla') . "'"; ?>);" value="Actualizar" />
			<?php } else if($accion === 'DEL') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionFormConfirm(<?php echo "'" . site_url('/planilla/delPlanilla') . "'"; ?>, 'Esta seguro que desea eliminar esta planilla.'); " value="Eliminar" />
				<?php }  ?>&nbsp;
				<input type="submit" class="clean-gray" onclick="return setAccionForm(<?php echo "'" . site_url('/planilla/lstPlanillas') . "'"; ?>);" value="Regresar" />
			</td>
		</tr>
	</table>
	<div id="errorsForm">
		<?php echo validation_errors(); ?>
	</div>
	</center>
	</form>
</div>

<?php if($accion != 'INS') { ?>
<script>
	$(function() {
		var ESTADO = $("#estado1").attr("value");
		
		$("#estado").val(ESTADO);
	});
</script>
<?php }  ?>

<?php $this->load->view('footer'); ?>
