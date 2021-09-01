<?php $this->load->view('header'); ?>

<div id="content">
<h1>Opciones de men&uacute;</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<form method="POST" id="opcionesmenu" action="<?php echo site_url('opcionmenu/loadOpcionMenu'); ?>">
<input type="hidden" name="idopm" id="idopm" />
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
			<th></th>
			<th>
				<input type="submit" class="clean-gray" value="Filtrar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='FLT'; return setAccionForm('<?php echo site_url('opcionmenu/lstOpcionesMenu'); ?>');" />
				<input type="submit" class="clean-gray" value="Limpiar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='CLN'; return setAccionForm('<?php echo site_url('opcionmenu/lstOpcionesMenu'); ?>');" />
			</th>
		</tr>	
		<tr>
			<th>ID</th>
			<th>Etiqueta</th>
			<th>URL</th>
			<th>Opci&oacute;n padre</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($lstOpcionesMenu['arrData'] as $fila) { ?>
		<tr>
			<td class="textCenter"><?php echo $fila['idopm']; ?></td>
			<td class="textCenter"><?php echo $fila['etiqueta']; ?></td>
			<td class="textCenter"><?php echo $fila['url']; ?></td>
			<td class="textCenter"><?php echo $fila['nombre_padre']; ?></td>
			<td class="actionCol">
				<div class="dosIconos" >
					<a href="#" onclick="actualizar('idopm', <?php echo $fila['idopm']; ?>, urlSitio + 'opcionmenu/loadOpcionMenu');" >
						<div class="imgLnk lnkUpd" title="Actualizar" ></div >
					</a>
					<div class="sepr"></div>
					<a href="#" onclick="eliminar('idopm', <?php echo $fila['idopm']; ?>, urlSitio + 'opcionmenu/loadOpcionMenu');">
						<div class="imgLnk lnkDel" title="Eliminar" ></div >
					</a>
				</div>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="5"><div class="paging"><?php echo $lstOpcionesMenu['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	
	<input type="submit" class="clean-gray"
			onclick="document.getElementById('accion').value ='INS'; return true;" 
			value="Nueva opci&oacute;n"/>
	</center>
	</form>
</div>

<?php $this->load->view('footer'); ?>