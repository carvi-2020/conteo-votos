<?php $this->load->view('_inclov/header'); ?>

<div id="content">
<h1>Clientes compradores</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<center>
<form method="POST" id="lovcliente" action="<?php echo site_url('lov/lovClientes'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo isset($accion)?$accion:''; ?>" />
	<table class="tblPaging">
	<thead>
		<tr>
			<th><input type="text" name="_fltRazon" id="_fltRazon" class="fltLov"
				value="<?php echo isset($_fltRazon)?$_fltRazon:set_value('_fltRazon');?>" /></th>
			<th><input type="text" name="_fltNit" id="_fltNit" class="fltLov"
				value="<?php echo isset($_fltNit)?$_fltNit:set_value('_fltNit');?>" /></th>
			<th><input type="text" name="_fltNrc" id="_fltNrc" class="fltLov"
				value="<?php echo isset($_fltNrc)?$_fltNrc:set_value('_fltNrc');?>" /></th>
			<th></th>
			<th>
				<input type="submit" class="clean-gray" value="Filtrar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='FLT'; return setAccionForm('<?php echo site_url('lov/lovClientes'); ?>');" />
				<input type="submit" class="clean-gray" value="Limpiar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='CLN'; return setAccionForm('<?php echo site_url('lov/lovClientes'); ?>');" />
			</th>
		</tr>
		<tr>
			<th>Nombre</th>
			<th>NIT</th>
			<th>NRC</th>
			<th>Contacto</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($lstClientes['arrData'] as $row) { ?>
		<tr>
			<td class="textCenter"><?php echo $row['razon_social']; ?></td>
			<td class="textCenter"><?php echo $row['nit']; ?></td>
			<td class="textCenter"><?php echo $row['nrc']; ?></td>
			<td class="textCenter"><?php echo $row['nombre_contacto']; ?></td>
			<td class="actionCol textCenter">
				<a href="#" 
					onclick="setLOVValues(<?php echo "'".(isset($_GET['objs'])?$_GET['objs']:$this->session->flashdata('objs'))."'"; ?>, new Array(<?php echo "'".$row['cliente_id']."', '".$row['razon_social']."'"; ?>));
							self.close();">Seleccionar</a>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="5"><div class="paging"><?php echo $lstClientes['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	<input type="button" class="clean-gray" onclick="self.close();" value="Cerrar Lista de Valores" />
</form>
</center>
</div>
<?php $this->load->view('_inclov/footer'); ?>
