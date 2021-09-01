<?php $this->load->view('header'); ?>

<div id="content">
<h1>Padrones Electorales</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<form method="POST" id="padroneselectorales" action="<?php echo site_url('padronelectoral/loadPadronelectoral'); ?>">
<input type="hidden" name="pdrele_id" id="pdrele_id" />
<input type="hidden" name="accion" id="accion" />
	<center>
	<table class="tblPaging">
	<thead>
		<tr class="filters">
			<th><input type="text" name="_fltNom" id="_fltNom" style="height:18px;"
					value="<?php echo isset($_fltNom)?$_fltNom:set_value('_fltNom');?>" /></th>
			<th><!--<input type="text" name="_fltIso" id="_fltIso" style="height:18px;"
					value="<?php echo isset($_fltIso)?$_fltIso:set_value('_fltIso');?>" />--></th>
			<th><!--<input type="text" name="_fltCap" id="_fltCap" style="height:18px;"
					value="<?php echo isset($_fltCap)?$_fltCap:set_value('_fltCap');?>" />--></th>
			<th></th>
			<th></th>
			<th></th>
			<th>
				<input type="submit" class="clean-gray" value="Filtrar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='FLT'; return setAccionForm('<?php echo site_url('padronelectoral/lstPadroneselectorales'); ?>');" />
				<input type="submit" class="clean-gray" value="Limpiar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='CLN'; return setAccionForm('<?php echo site_url('padronelectoral/lstPadroneselectorales'); ?>');" />
			</th>
		</tr>	
		<tr>
			<th>Cod. Socio</th>
			<th>Nombre completo</th>
			<th>JVPM</th>
			<th>DUI</th>
			<th>Centro de Votaci&oacute;n</th>
			<th>Fecha y hora</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($lstPadroneselectorales['arrData'] as $fila) { ?>
		<tr>
			<td class="textCenter"><?php echo $fila['codigo_socio']; ?></td>
			<td class="textCenter"><?php echo $fila['nombre_socio']; ?></td>
			<td class="textCenter"><?php echo $fila['jvpm_socio']; ?></td>
			<td class="textCenter"><?php echo $fila['dui_socio']; ?></td>
			<td class="textCenter"><?php echo $fila['nombre_centro']; ?></td>
			<td class="textCenter"><?php echo $fila['fecha_hora']; ?></td>
			<td class="actionCol">	
				<div class="dosIconos" >
					<a href="#" onclick="actualizar('pdrele_id', <?php echo $fila['pdrele_id']; ?>, urlSitio + 'padronelectoral/loadPadronelectoral');" >
						<div class="imgLnk lnkUpd" title="Actualizar" ></div >
					</a>
					<div class="sepr"></div>
					<!--<a href="#" onclick="eliminar('pdrele_id', <?php echo $fila['pdrele_id']; ?>, urlSitio + 'padronelectoral/loadPadronelectoral');">
						<div class="imgLnk lnkDel" title="Eliminar" ></div >
					</a>-->
				</div>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="7"><div class="paging"><?php echo $lstPadroneselectorales['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	
	<input type="submit" class="clean-gray"
			onclick="document.getElementById('accion').value ='INS'; return true;" 
			value="Nuevo registro de padr&oacute;n"/>
	</center>
	</form>
</div>

<?php $this->load->view('footer'); ?>