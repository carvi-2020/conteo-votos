<?php $this->load->view('header'); ?>

<div id="content">
	<h1>Planilla > Ingreso de Socio</h1>

	<form method="POST" id="opcionesmenu" enctype="multipart/form-data" action="<?php echo site_url('socio'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>" />
	<input type="hidden" name="planilla_id" id="planilla_id" value="<?php echo isset($socio_id)?$socio_id:set_value('socio_id');?>" />

	<center>


	<table class="tblform">
		<tr>
			<td>Codigo del Socio: </td>
			<td><input type="text" name="codigo" id="codigo" class="textform" maxlength="5" value="<?php echo isset($codigo)?$codigo:set_value('codigo');?>" /></td>

			<td>Nombres: </td>
			<td><input type="text" name="nombres" id="nombres" class="textform" maxlength="20" value="<?php echo isset($nombres)?$nombres:set_value('nombres');?>" /></td>

			<td>Apellidos: </td>
			<td><input type="text" name="apellidos" id="apellidos" class="textform" maxlength="20" value="<?php echo isset($apellidos)?$apellidos:set_value('nombres');?>" /></td>
		</tr>
		<tr>
			<td>JVPM: </td>
			<td><input type="text" name="jvpm" id="jvpm" class="textform" maxlength="20" value="<?php echo isset($jvpm)?$jvpm:set_value('jvpm');?>" /></td>
			<td>DUI: </td>
			<td><input type="text" name="dui" id="dui" class="textform" maxlength="20" value="<?php echo isset($dui)?$dui:set_value('dui');?>" /></td>
				


		</tr>
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5" class="btnsCell">
			<?php if($accion === 'INS') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/socio/insSocio') . "'"; ?>);" value="Guardar" />
			<?php } else if($accion === 'UPD') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/socio/updSocio') . "'"; ?>);" value="Actualizar" />
			<?php } else if($accion === 'DEL') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionFormConfirm(<?php echo "'" . site_url('/socio/delSocio') . "'"; ?>, 'Esta seguro que desea eliminar este Socio.'); " value="Eliminar" />
				<?php }  ?>&nbsp;
				<input type="submit" class="clean-gray" onclick="return setAccionForm(<?php echo "'" . site_url('/socio/lstsocios') . "'"; ?>);" value="Regresar" />
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
