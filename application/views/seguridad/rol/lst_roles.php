<?php $this->load->view('header'); ?>

<div id="content">
<h1>Roles del sistema</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<form method="POST" id="sistemas" action="<?php echo site_url('rol/loadRol'); ?>">
<center>
<input type="hidden" name="idrol" id="idrol" />
<input type="hidden" name="accion" id="accion" value="<?php echo isset($accion)?$accion:''; ?>" />
	<table class="tblPaging">
	<thead>
		<tr class="filters">
			<th></th>
			<th><input type="text" name="_fltNom" id="_fltNom" style="height:18px;"
					value="<?php echo isset($_fltNom)?$_fltNom:set_value('_fltNom');?>" /></th>
			<th>
				<?php echo form_dropdown('_fltEst', array('' => '', 'ACT' => 'Activo', 'INA' => 'Inactivo'), isset($_fltEst)?$_fltEst:set_value('_fltEst'), 'class="comboform"');?>
			</th>
			<th>
				<input type="submit" class="clean-gray" value="Filtrar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='FLT'; return setAccionForm('<?php echo site_url('rol/lstRoles'); ?>');" />
				<input type="submit" class="clean-gray" value="Limpiar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='CLN'; return setAccionForm('<?php echo site_url('rol/lstRoles'); ?>');" />
			</th>
		</tr>
		<tr>
			<th>ID</th>
			<th>Nombre</th>
			<th>Estado</th>
			<th>Accion</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($lstRoles['arrData'] as $row) { ?>
		<tr>
			<td class="textRight"><?php echo $row['idrol']; ?></td>
			<td class="textCenter"><?php echo $row['nombre']; ?></td>
			<td class="textCenter"><?php echo $row['estado']; ?></td>
			<td class="actionCol">
				<div class="dosIconos" >
					<a href="#" onclick="actualizar('idrol', <?php echo $row['idrol']; ?>, urlSitio + 'rol/loadRol');" >
						<div class="imgLnk lnkUpd" title="Actualizar" ></div >
					</a>
					<div class="sepr"></div>
					<a href="#" onclick="eliminar('idrol', <?php echo $row['idrol']; ?>, urlSitio + 'rol/loadRol');">
						<div class="imgLnk lnkDel" title="Eliminar" ></div >
					</a>
				</div>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="4"><div class="paging"><?php echo $lstRoles['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	
	<input type="submit" class="clean-gray" 
		onclick="document.getElementById('accion').value ='INS'; return true;" 
		value="Nuevo rol"/>
	</center>
	</form>
</div>
<?php $this->load->view('footer'); ?>