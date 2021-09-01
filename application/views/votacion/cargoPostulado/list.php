<?php $this->load->view('header'); ?>

<div id="content">
<h1>Cargo Postulado</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<form method="POST" id="cargosPostulados" action="<?php echo site_url('cargopostulado/loadCargoPostulado'); ?>">
<input type="hidden" name="cargopost_id" id="cargopost_id" />
<input type="hidden" name="accion" id="accion" />
	<center>
	<table class="tblPaging">
	<thead>
		<tr class="filters">
			<th><input type="text" name="_fltNom" id="_fltNom" style="height:18px;"
					value="<?php echo isset($_fltNom)?$_fltNom:set_value('_fltNom');?>" /></th>
			<th><input type="text" name="_fltIso" id="_fltIso" style="height:18px;"
					value="<?php echo isset($_fltIso)?$_fltIso:set_value('_fltIso');?>" /></th>
			<th></th>
			<th>
				<input type="submit" class="clean-gray" value="Filtrar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='FLT'; return setAccionForm('<?php echo site_url('cargopostulado/lstCargosPostulados'); ?>');" />
				<input type="submit" class="clean-gray" value="Limpiar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='CLN'; return setAccionForm('<?php echo site_url('cargopostulado/lstCargosPostulados'); ?>');" />
			</th>
		</tr>		
		<tr>
			<th>Correlativo</th>
			<th>Nombre</th>
			<th>Orden</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($lstCargosPostulados['arrData'] as $fila) { ?>
		<tr>
			<td class="textCenter"><?php echo $fila['cargopost_id']; ?></td>
			<td class="textCenter"><?php echo $fila['nombre']; ?></td>
			<td class="textCenter"><?php echo $fila['orden']; ?></td>
			<td class="actionCol">	
				<div class="dosIconos" >
					<a href="#" onclick="actualizar('cargopost_id', <?php echo $fila['cargopost_id']; ?>, urlSitio + 'cargopostulado/loadCargoPostulado');" >
						<div class="imgLnk lnkUpd" title="Actualizar" ></div >
					</a>
					<div class="sepr"></div>
					<a href="#" onclick="eliminar('cargopost_id', <?php echo $fila['cargopost_id']; ?>, urlSitio + 'cargopostulado/loadCargoPostulado');">
						<div class="imgLnk lnkDel" title="Eliminar" ></div >
					</a>
				</div>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="4"><div class="paging"><?php echo $lstCargosPostulados['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	
	<input type="submit" class="clean-gray"
			onclick="document.getElementById('accion').value ='INS'; return true;" 
			value="Nuevo Cargo"/>
	</center>
	</form>
</div>

<?php $this->load->view('footer'); ?>