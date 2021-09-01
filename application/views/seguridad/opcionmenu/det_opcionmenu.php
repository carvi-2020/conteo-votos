<?php $this->load->view('header'); ?>
<div id="content">
	<h1>Opciones de men&uacute; > Detalle de la opci&oacute;n de men&uacute;</h1>
	<form method="POST" id="opcionesmenu" action="<?php echo site_url('opcionmenu'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>" />
	<center>
	<table class="tblform">
		<tr>
			<td>ID: </td>
			<td><input type="text" name="idopm" id="idopm" class="textform elemdis" readonly="true"
			value="<?php echo isset($idopm)?$idopm:set_value('idopm');?>" /></td>
			<td style="width:20px;"></td>
			<td>Etiqueta: </td>
			<td><input type="text" name="etiqueta" id="etiqueta" class="textform" maxlength="50"
			value="<?php echo isset($etiqueta)?$etiqueta:set_value('etiqueta');?>" /> <span class="frmreq">*</span></td>
		</tr>
		<tr>
			<td>URL: </td>
			<td><input type="text" name="url" id="url" class="textform" maxlength="100"
			value="<?php echo isset($url)?$url:set_value('url');?>" /></td>
			<td></td>
			<td>Orden: </td>
			<td><input type="text" name="orden" id="orden" class="textform" maxlength="3"
			value="<?php echo isset($orden)?$orden:set_value('orden');?>" /> <span class="frmreq">*</span></td>
		</tr>
		<tr>
			<td>Opci&oacute;n padre: </td>
			<td>
				<input type="text" name="nombre_padre" id="nombre_padre" class="textform" readonly="true" style="width:150px;"
					value="<?php echo isset($nombre_padre)?$nombre_padre:set_value('nombre_padre');?>" />
				<input type="hidden" name="opcion_padre" id="opcion_padre" value="<?php echo isset($opcion_padre)?$opcion_padre:set_value('opcion_padre');?>" />
				<input type="button" class="lovbtn" title="Seleccionar opcion padre"
					onclick="displayLOV(<?php echo "'".site_url('lov/lovOpcionesMenuPapa')."'"; ?>, 'opm', new Array('opcion_padre','nombre_padre'));" />
				<input type="button" class="rstbtn" title="Limpiar campo"
					onclick="document.getElementById('nombre_padre').value='';document.getElementById('opcion_padre').value='';" />
			</td>
		
		</tr>
		<tr>
			<td colspan="5" class="btnsCell">
			<?php if($accion === 'INS') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionForm('/insOpcionMenu'); "value="Guardar" />
			<?php } else if($accion === 'UPD') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionForm('/updOpcionMenu'); "value="Actualizar" />
			<?php } else if($accion === 'DEL') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionFormConfirm(urlSitio + 'opcionmenu/delOpcionMenu', 'eliminar esta opci&oacute;n de men&uacute;'); " value="Eliminar" />
				<?php }  ?>&nbsp;
				<input type="submit" class="clean-gray" onclick="return addAccionForm('/lstOpcionesMenu'); " value="Regresar" />
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
