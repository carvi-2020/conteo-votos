<?php $this->load->view('header'); ?>

<div id="content">
<h1>Usuarios del sistema</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<form method="POST" id="usuarios" action="<?php echo site_url('usuario/loadUsuario'); ?>">
<center>
<input type="hidden" name="idusr" id="idusr" />
<input type="hidden" name="accion" id="accion" />
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
			<th></th>
			<th></th>
			<th>
				<input type="submit" class="clean-gray" value="Filtrar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='FLT'; return setAccionForm('<?php echo site_url('usuario/lstUsuarios'); ?>');" />
				<input type="submit" class="clean-gray" value="Limpiar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='CLN'; return setAccionForm('<?php echo site_url('usuario/lstUsuarios'); ?>');" />
			</th>
		</tr>	
		<tr>
			<th>ID</th>
			<th>Nombre de usuario</th>
			<th>Nombre completo</th>
			<th>Rol</th>
			<th>Estado</th>
			<th>&Uacute;ltimo acceso</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($lstUsuarios['arrData'] as $fila) { ?>
		<tr>
			<td class="textCenter"><?php echo $fila['idusr']; ?></td>
			<td class="textCenter"><?php echo $fila['nomusuario']; ?></td>
			<td class="textCenter"><?php echo $fila['nomcompleto']; ?></td>
			<td class="textCenter"><?php echo $fila['nomrol']; ?></td>
			<td class="textCenter"><?php echo $fila['estado']; ?></td>
			<td class="textCenter"><?php echo $fila['ultimologin']; ?></td>
			<td class="actionCol">
				<div class="dosIconos" >
					<a href="#" onclick="actualizar('idusr', <?php echo $fila['idusr']; ?>, urlSitio + 'usuario/loadUsuario');" >
						<div class="imgLnk lnkUpd" title="Actualizar" ></div >
					</a>
					<div class="sepr"></div>
					<a href="#" onclick="eliminar('idusr', <?php echo $fila['idusr']; ?>, urlSitio + 'usuario/loadUsuario');">
						<div class="imgLnk lnkDel" title="Eliminar" ></div >
					</a>
				</div>
			</td>
		</tr>
						
		<?php } ?>
		<tr>
			<td colspan="7"><div class="paging"><?php echo $lstUsuarios['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	
	<input type="submit" class="clean-gray"
			onclick="document.getElementById('accion').value ='INS'; return true;" 
			value="Nuevo usuario" />
	</center>
	</form>
</div>

<?php $this->load->view('footer'); ?>