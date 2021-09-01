<?php $this->load->view('_inclov/header'); ?>

<div id="content">
<h1>Roles del sistema</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<center>
<form method="POST" id="lovrol" action="<?php echo site_url('lov/lovRoles'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo isset($accion)?$accion:''; ?>" />
	<table class="tblPaging">
	<thead>
		<tr class="filters">
			<th></th>
			<th><input type="text" name="_fltNom" id="_fltNom" 
					value="<?php echo isset($_fltNom)?$_fltNom:set_value('_fltNom');?>" /></th>
			<th>
				<input type="submit" class="clean-gray" value="Filtrar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='FLT'; return setAccionForm('<?php echo site_url('lov/lovRoles'); ?>');" />
				<input type="submit" class="clean-gray" value="Limpiar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='CLN'; return setAccionForm('<?php echo site_url('lov/lovRoles'); ?>');" />
			</th>
		</tr>
		<tr>
			<th>ID</th>
			<th>Nombre</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($lstRoles['arrData'] as $row) { ?>
		<tr>
			<td class="textRight"><?php echo $row['idrol']; ?></td>
			<td class="textCenter"><?php echo $row['nombre']; ?></td>
			<td class="actionCol textCenter">
				<a href="#" style="color:#D12727;"
					onclick="setLOVValues(<?php echo "'".(isset($_GET['objs'])?$_GET['objs']:$this->session->flashdata('objs'))."'"; ?>, new Array(<?php echo "'".$row['idrol']."', '".$row['nombre']."'"; ?>));
							self.close();">Seleccionar</a>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="3"><div class="paging"><?php echo $lstRoles['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	<input type="button" class="clean-gray" onclick="self.close();" value="Cerrar Lista de Valores" />
</form>
</center>
</div>
<?php $this->load->view('_inclov/footer'); ?>
