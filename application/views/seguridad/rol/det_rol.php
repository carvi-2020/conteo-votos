<?php $this->load->view('header'); ?>
<script type="text/javascript" language="Javascript">
		function chequear(chequeado, idCampo) {
			if(chequeado)
				document.getElementById(idCampo).value = '1';
			else
				document.getElementById(idCampo).value = '0';
		}
</script>
<div id="content">
	<h1>Roles del sistema > Detalle de rol</h1>
	<form method="POST" id="roles" action="<?php echo site_url('rol'); ?>">
	<input type="hidden" name="accion" id="accion" value="<? echo $accion;?>" />
	<center>
	<table class="tblform">
		<tr>
			<td>ID: </td>
			<td><input type="text" name="idrol" id="idrol" class="textform elemdis" readonly="true"
			value="<?php echo isset($idrol)?$idrol:set_value('idrol');?>" /></td>
			<td style="width:20px;"></td>
			<td>Nombre: </td>
			<td><input type="text" name="nombre" id="nombre" class="textform" 
			value="<?php echo isset($nombre)?$nombre:set_value('nombre');?>" /> <span class="frmreq">*</span></td>
		</tr>
		<tr>
			<td>Estado: </td>
			<td><?php echo form_dropdown('estado', array('ACT' => 'Activo', 'INA' => 'Inactivo'), isset($estado)?$estado:set_value('estado'), 'class="comboform"');?>
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="5">
				<!--<table style="width:100%;">
			<tr>
				<td colspan="3" style="text-align:center; font-weight:bold; font-size:15px;">
					Permisos de registro de arribos
				</td>
			</tr>
			<tr>
				<td style="text-align:center;font-weight:bold;">Datos de embarcaci&oacute;n</td>
				<td style="text-align:center;font-weight:bold;">Subida de documentos FAL</td>
				<td style="text-align:center;font-weight:bold;">Datos de arribo</td>
			</tr>
			<tr>
				<td style="text-align:center;">
					<input type="hidden" name="edit_emb" id="edit_emb" value="<?php echo $edit_emb=='1'?'1':'0'; ?>" />
					<input type="checkbox" <?php echo ($edit_emb=='1'?'checked="checked"':''); ?>"
								onclick="chequear(this.checked, 'edit_emb');" />
				</td>
				<td style="text-align:center;">
					<input type="hidden" name="edit_doc" id="edit_doc" value="<?php echo $edit_doc=='1'?'1':'0'; ?>" />
					<input type="checkbox" <?php echo ($edit_doc=='1'?'checked="checked"':''); ?>"
								onclick="chequear(this.checked, 'edit_doc');" />
				</td>
				<td style="text-align:center;">
					<input type="hidden" name="edit_arr" id="edit_arr" value="<?php echo $edit_arr=='1'?'1':'0'; ?>" />
					<input type="checkbox" <?php echo ($edit_arr=='1'?'checked="checked"':''); ?>"
								onclick="chequear(this.checked, 'edit_arr');" />
				</td>
			</tr>
				</table>-->
			</td>
		</tr>
		<tr>
			<td colspan="5" class="btnsCell">
			<?php if($accion === 'INS') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(urlSitio + 'rol/insRol'); "value="Guardar" />
			<?php } else if($accion === 'UPD') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(urlSitio + 'rol/updRol'); "value="Actualizar" />
			<?php } else if($accion === 'DEL') { ?>
				<input type="submit" class="clean-gray" 
						onclick="return addAccionFormConfirm(urlSitio + 'rol/delRol', 'eliminar este rol'); " value="Eliminar" />
			<?php }
			
				if(isset($idrol) && $idrol != '') {  ?>&nbsp;
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(urlSitio + 'rol/loadOpcRol'); "value="Opciones asociadas" />
			<?php } ?>
						&nbsp;
				<input type="submit" class="clean-gray" onclick="return addAccionForm('/lstRoles'); " value="Regresar" />
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
