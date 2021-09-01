<?php $this->load->view('header'); ?>

<div id="content">
	
	<script type="text/javascript" language="javascript">
		function setAsociacion(chequeado, idCampo) {
			if(chequeado)
				document.getElementById(idCampo).value = 'TRUE';
			else
				document.getElementById(idCampo).value = 'FALSE';
		}
	</script>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
	<h1>Roles del sistema > Detalle de rol > Opciones de men&uacute; autorizadas</h1>
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
			value="<?php echo isset($nombre)?$nombre:set_value('nombre');?>" /></td>
		</tr>
		<tr>
			<td colspan="5"><center>
				<h2 style="margin:2px 0px 2px 0px;">Opciones de men&uacute; asociadas al rol</h2>
			</center></td>
		</tr>
		<tr><td colspan="5">
			<table class="tblPaging" style="width:100%;">
			<thead>
				<tr>
					<th>ID</th>
					<th>Etiqueta</th>
					<th>Opci&oacute;n padre</th>
					<th>Seleccionar</th>
				</tr>
			</thead>
			<tbody>
				<?php $cnt = 0;
					foreach($lstOpcionesMenu as $fila) { ?>
				<tr>
					<td class="textRight"><?php echo $fila['idopm']; ?></td>
					<td class="textCenter"><?php echo $fila['etiqueta']; ?></td>
					<td class="textCenter"><?php echo $fila['nombre_padre']; ?></td>
					<td class="actionCol">
						<input type="hidden" name="idopm[]" value="<?php echo $fila['idopm']; ?>" />
						<input type="hidden" name="asociado[]" id="asociado<?php echo $cnt; ?>" value="<?php echo $fila['asociado']=='TRUE'?'TRUE':'FALSE'; ?>" />
						<div class="unIcono" >
							<input type="checkbox" <?php echo ($fila['asociado']=='TRUE'?'checked="checked"':''); ?>"
								onclick="setAsociacion(this.checked, 'asociado<?php echo $cnt; ?>');" />
								
						</div>
					</td>
				</tr>
				<?php $cnt++; 
					} ?>
			</tbody>
			</table>
		</td></tr>
		<tr>
			<td colspan="5" class="btnsCell">
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(urlSitio + 'rol/updOpcRol'); "value="Actualizar" />
				&nbsp;
				<input type="submit" class="clean-gray" onclick="actualizar('idrol', <?php echo $idrol; ?>, urlSitio + 'rol/loadRol');" value="Regresar" />
			</td>
		</tr>
	</table>
	</center>
	<div id="errorsForm">
	<?php echo validation_errors(); ?>
	</div>
	</form>
</div>
<?php $this->load->view('footer'); ?>
