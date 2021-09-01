<?php $this->load->view('header'); ?>
<div id="content">
	<h1>Usuarios del sistema > Detalle del usuario</h1>
	<form method="POST" id="usuarios" action="<?php echo site_url('usuario'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>" />
	<center>
	<table class="tblform">
		<tr>
			<td>ID: </td>
			<td><input type="text" name="idusr" id="idusr" class="textform elemdis" readonly="true"
			value="<?php echo isset($idusr)?$idusr:set_value('idusr');?>" /></td>
			<td style="width:20px;"></td>
			<td>Nombre de usuario: </td>
			<td><input type="text" name="nomusuario" id="nomusuario" class="textform" maxlength="20"
			value="<?php echo isset($nomusuario)?$nomusuario:set_value('nomusuario');?>" /> <span class="frmreq">*</span></td>
		</tr>
		<tr>
			<td>Nombre completo: </td>
			<td>
				<input type="text" name="nomcompleto" id="nomcompleto" class="textform" maxlength="50" 
					value="<?php echo isset($nomcompleto)?$nomcompleto:set_value('nomcompleto');?>" /> <span class="frmreq">*</span>
			</td>
			<td></td>
			<td>Clave: </td>
			<td>
				<input type="password" name="clave" id="clave" class="textform" maxlength="20" />
			</td>
		</tr>
		<tr>
			<td>Estado: </td>
			<td><?php echo form_dropdown('estado', array('ACT' => 'Activo', 'INA' => 'Inactivo'), isset($estado)?$estado:set_value('estado'), 'class="comboform"');?>
			</td>
			<td></td>
			<td>Rol: </td>
			<td>
				<input type="text" name="nomrol" id="nomrol" class="textform" maxlength="50" readonly="true" style="width:150px;"
					value="<?php echo isset($nomrol)?$nomrol:set_value('nomrol');?>" />
				<input type="hidden" name="idrol" id="idrol" value="<?php echo isset($idrol)?$idrol:set_value('idrol');?>" />
				<input type="button" class="lovbtn" alt="Desplegar lista de valores" title="Desplegar lista de valores"
					onclick="displayLOV(<?php echo "'".site_url('lov/lovRoles')."'"; ?>, 'rol', new Array('idrol','nomrol'));" />
				<input type="button" class="rstbtn" title="Limpiar campo"
					onclick="document.getElementById('idrol').value='';document.getElementById('nomrol').value='';" />
			</td>
		</tr>
		
		<tr>
			<td colspan="5" class="btnsCell">
			<?php if($accion === 'INS') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionForm('/insUsuario'); "value="Guardar" />
			<?php } else if($accion === 'UPD') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionForm('/updUsuario'); "value="Actualizar" />
			<?php } else if($accion === 'DEL') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionFormConfirm(urlSitio + 'usuario/delUsuario', 'eliminar este usuario'); " value="Eliminar" />
				<?php }  ?>&nbsp;
				<input type="submit" class="clean-gray" onclick="return addAccionForm('/lstUsuarios'); " value="Regresar" />
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
