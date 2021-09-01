<?php $this->load->view('header'); ?>

<div id="content">
	<h1>Centro de Votacion > Detalle del Centro de Votacion</h1>
	<form method="POST" id="opcionesmenu" enctype="multipart/form-data" action="<?php echo site_url('centrovotacion'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>" />
	<input type="hidden" name="centrovot_id" id="centrovot_id" value="<?php echo isset($centrovot_id)?$centrovot_id:set_value('centrovot_id');?>" />
	<center>
	<table class="tblform">
		<tr>
		<td>Codigo del Centro de Votacion: </td>
		<td><input type="text" name="codigo" id="codigo" class="textform" maxlength="5" value="<?php echo isset($codigo)?$codigo:set_value('codigo');?>" /></td>
		<td style="width:20px;"></td>
		<td>Nombre del Centro de Votacion: </td>
		<td><input type="text" name="nombre" id="nombre" class="textform" maxlength="30" value="<?php echo isset($nombre)?$nombre:set_value('nombre');?>" /></td>		
		</tr>
		<tr>
		<td>Estado del Centro de Votacion: </td>
		<td>
			<span class="rmrcr">
			<?php 
				if($accion === 'INS') {
					echo "Estado Activo"; 
			?>
				<input type="hidden" name="estado" id="estado" value="ACT" />
			<?php } ?>
			</span>
			
			<?php if($accion === 'UPD') { ?>
				<input type="hidden" name="estado1" id="estado1" value="<?php echo isset($estado)?$estado:set_value('estado');?>" />
				<select name="estado" id="estado" >
					<option value="ACT">Activo</option>					
					<option value="INA">Inactivo</option>									
				</select>
			<?php } ?>
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
						onclick="return setAccionForm(<?php echo "'" . site_url('/centrovotacion/insCentroVotacion') . "'"; ?>);" value="Guardar" />
			<?php } else if($accion === 'UPD') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/centrovotacion/updCentroVotacion') . "'"; ?>);" value="Actualizar" />
			<?php } else if($accion === 'DEL') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionFormConfirm(<?php echo "'" . site_url('/centrovotacion/delCentroVotacion') . "'"; ?>, 'Esta seguro que desea eliminar esta centro de votacion.'); " value="Eliminar" />
				<?php }  ?>&nbsp;
				<input type="submit" class="clean-gray" onclick="return setAccionForm(<?php echo "'" . site_url('/centrovotacion/lstCentroVotaciones') . "'"; ?>);" value="Regresar" />
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
		$("#estado").val(ESTADO);
	});
</script>
<?php }  ?>

<?php $this->load->view('footer'); ?>
