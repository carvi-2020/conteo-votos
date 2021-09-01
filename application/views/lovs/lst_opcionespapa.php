<?php $this->load->view('_inclov/header'); ?>

<div id="content">
<h1>Opciones de men&uacute; padres</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<center>
<form method="POST" id="lovopp" action="<?php echo site_url('lov/lovOpcionesMenuPapa'); ?>">
	<table class="tblPaging">
	<thead>
		<tr>
			<th>ID</th>
			<th>Etiqueta</th>
			<th>Orden</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($lstOpcionesPapa['arrData'] as $row) { ?>
		<tr>
			<td class="textRight"><?php echo $row['idopm']; ?></td>
			<td class="textCenter"><?php echo $row['etiqueta']; ?></td>
			<td class="textCenter"><?php echo $row['orden']; ?></td>
			<td class="actionCol textCenter">
				<a href="#" 
					onclick="setLOVValues(<?php echo "'".(isset($_GET['objs'])?$_GET['objs']:$this->session->flashdata('objs'))."'"; ?>, new Array(<?php echo "'".$row['idopm']."', '".$row['etiqueta']."'"; ?>));
							self.close();">Seleccionar</a>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="4"><div class="paging"><?php echo $lstOpcionesPapa['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	<input type="button" class="clean-gray" onclick="self.close();" value="Cerrar Lista de Valores" />
</form>
</center>
</div>
<?php $this->load->view('_inclov/footer'); ?>
