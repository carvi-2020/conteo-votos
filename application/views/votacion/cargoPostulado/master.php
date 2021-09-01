<?php $this->load->view('header'); ?>

<div id="content">
	<h1>Cargo Postulado > Detalle del Cargo Postulado</h1>
	<form method="POST" id="opcionesmenu" enctype="multipart/form-data" action="<?php echo site_url('cargoPostulado'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>" />
	<input type="hidden" name="cargopost_id" id="cargopost_id" value="<?php echo isset($cargopost_id)?$cargopost_id:set_value('cargopost_id');?>" />
	<center>
	<table class="tblform">
		<tr>
			<td>Nombre del cargo: </td>
			<td><input type="text" name="nombre" id="nombre" class="textform" maxlength="22" value="<?php echo isset($nombre)?$nombre:set_value('nombre');?>" /></td>
			<td style="width:20px;"></td>
			<td>Orden Jerarquico: </td>
			<td>
				<input type="text" name="orden" id="orden" class="textform" maxlength="2"
					onkeypress="return restrictNumDigits(event, this, 4);"
					value="<?php echo isset($orden)?$orden:set_value('orden');?>" />
			</td>
		</tr>
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5" class="btnsCell">
			<?php if($accion === 'INS') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/cargopostulado/insCargoPostulado') . "'"; ?>);" value="Guardar" />
			<?php } else if($accion === 'UPD') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(<?php echo "'" . site_url('/cargopostulado/updCargoPostulado') . "'"; ?>);" value="Actualizar" />
			<?php } else if($accion === 'DEL') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionFormConfirm(<?php echo "'" . site_url('/cargopostulado/delCargoPostulado') . "'"; ?>, 'Esta seguro que desea eliminar este cargo.'); " value="Eliminar" />
				<?php }  ?>&nbsp;
				<input type="submit" class="clean-gray" onclick="return setAccionForm(<?php echo "'" . site_url('/cargopostulado/lstCargosPostulados') . "'"; ?>);" value="Regresar" />
			</td>
		</tr>
	</table>
	<div id="errorsForm">
		<?php echo validation_errors(); ?>
	</div>
	</center>
	</form>
</div>

<?php $this->load->view('footer'); ?>
