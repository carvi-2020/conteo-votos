<?php $this->load->view('header'); ?>

<div id="content">
<h1>Socios</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<form method="POST" id="socios" action="<?php echo site_url('socio/loadSocio'); ?>">
<input type="hidden" name="socio_id" id="socio_id" />
<input type="hidden" name="accion" id="accion" />
	<center>
	<table class="tblPaging">
	<thead>
		<tr class="filters">
			<th><input type="text" name="_fltNom" id="_fltNom" style="height:18px;"
					value="<?php echo isset($_fltNom)?$_fltNom:set_value('_fltNom');?>" /></th>
			<th><input type="text" name="_fltIso" id="_fltIso" style="height:18px;"
					value="<?php echo isset($_fltIso)?$_fltIso:set_value('_fltIso');?>" /></th>
			<th><input type="text" name="_fltCap" id="_fltCap" style="height:18px;"
					value="<?php echo isset($_fltCap)?$_fltCap:set_value('_fltCap');?>" /></th>
			<th><input type="text" name="_fltCod" id="_fltCod" style="height:18px;"
					value="<?php echo isset($_fltCod)?$_fltCod:set_value('_fltCod');?>" /></th>
			<th><input type="text" name="_fltJvpm" id="_fltJvpm" style="height:18px;"
					value="<?php echo isset($_fltJvpm)?$_fltJvpm:set_value('_fltJvpm');?>" /></th>
			<th><input type="text" name="_fltDui" id="_fltDui" style="height:18px;"
					value="<?php echo isset($_fltDui)?$_fltDui:set_value('_fltDui');?>" /></th>
			<th>
				<input type="submit" class="clean-gray" value="Filtrar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='FLT'; return setAccionForm('<?php echo site_url('socio/lstSocios'); ?>');" />
				<input type="submit" class="clean-gray" value="Limpiar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='CLN'; return setAccionForm('<?php echo site_url('socio/lstSocios'); ?>');" />
			</th>
		</tr>	
		<tr>
			<th>Correlativo</th>
			<th>Nombres</th>
			<th>Apellidos</th>
			<th>Codigo</th>
			<th>JVPM</th>
			<th>DUI</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($lstSocios['arrData'] as $fila) { ?>
		<tr>
			<td class="textCenter"><?php echo $fila['socio_id']; ?></td>
			<td class="textCenter"><?php echo $fila['nombres']; ?></td>
			<td class="textCenter"><?php echo $fila['apellidos']; ?></td>
			<td class="textCenter"><?php echo $fila['codigo']; ?></td>
			<td class="textCenter"><?php echo $fila['jvpm']; ?></td>
			<td class="textCenter"><?php echo $fila['dui']; ?></td>
			<td class="actionCol">	
				<div class="dosIconos" >
					<!--<a href="#" onclick="actualizar('socio_id', <?php echo $fila['socio_id']; ?>, urlSitio + 'socio/loadSocio');" >
						<div class="imgLnk lnkUpd" title="Actualizar" ></div >
					</a>
					<div class="sepr"></div>
					<a href="#" onclick="eliminar('socio_id', <?php echo $fila['socio_id']; ?>, urlSitio + 'socio/loadSocio');">
						<div class="imgLnk lnkDel" title="Eliminar" ></div >
					</a>-->
				</div>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="7"><div class="paging"><?php echo $lstSocios['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	
	<input type="submit" class="clean-gray"
			onclick="document.getElementById('accion').value ='INS'; return true;" 
			value="Nuevo socio"/>
	</center>
	</form>
</div>

<?php $this->load->view('footer'); ?>