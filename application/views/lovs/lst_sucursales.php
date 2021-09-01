<?php $this->load->view('_inclov/header'); ?>

<div id="content">
<h1>Puntos de Venta (POS)</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<center>
<form method="POST" id="lovsuc" action="<?php echo site_url('lov/lovSucursales'); ?>">
	<table class="tblPaging">
	<thead>
		<tr>
			<th>ID</th>
			<th>Nombre</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($lstSucursales['arrData'] as $row) { ?>
		<tr>
			<td class="textRight"><?php echo $row['idsuc']; ?></td>
			<td class="textCenter"><?php echo $row['nombre']; ?></td>
			<td class="actionCol textCenter">
				<a href="#" 
					onclick="setLOVValues(<?php echo "'".(isset($_GET['objs'])?$_GET['objs']:$this->session->flashdata('objs'))."'"; ?>, new Array(<?php echo "'".$row['idsuc']."', '".$row['nombre']."'"; ?>));
							self.close();">Seleccionar</a>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="4"><div class="paging"><?php echo $lstSucursales['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	<input type="button" class="clean-gray" onclick="self.close();" value="Cerrar Lista de Valores" />
</form>
</center>
</div>
<?php $this->load->view('_inclov/footer'); ?>
