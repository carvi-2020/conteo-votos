<?php $this->load->view('header'); ?>

<div id="content">
<h1>Planillas</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<form method="POST" id="planillas" action="<?php echo site_url('planilla/loadPlanilla'); ?>">
<input type="hidden" name="planilla_id" id="planilla_id" />
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
					onclick="document.getElementById('accion').value ='FLT'; return setAccionForm('<?php echo site_url('planilla/lstPlanillas'); ?>');" />
				<input type="submit" class="clean-gray" value="Limpiar" style="height:20px; padding: 2px 0; width:60px;"
					onclick="document.getElementById('accion').value ='CLN'; return setAccionForm('<?php echo site_url('planilla/lstPlanillas'); ?>');" />
			</th>
		</tr>	
		<tr>
			<th>Correlativo</th>
			<th>Codigo</th>
			<th>Nombre</th>
			<th>Estado</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($lstPlanillas['arrData'] as $fila) { ?>
		<tr>
			<td class="textCenter"><?php echo $fila['planilla_id']; ?></td>
			<td class="textCenter"><?php echo $fila['codigo']; ?></td>
			<td class="textCenter"><?php echo $fila['nombre']; ?></td>
			<td class="textCenter">
				<?php if( $fila['estado'] == 'ACT' ){ echo 'Estado Activo'; } ?>
				<?php if( $fila['estado'] == 'INA' ){ echo 'Estado Inactivo'; } ?>
			</td>
			<td class="actionCol">	
				<div class="dosIconos" >
					<a href="#" onclick="actualizar('planilla_id', <?php echo $fila['planilla_id']; ?>, urlSitio + 'planilla/loadPlanilla');" >
						<div class="imgLnk lnkUpd" title="Actualizar" ></div >
					</a>
					<div class="sepr"></div>
					<a href="#" onclick="eliminar('planilla_id', <?php echo $fila['planilla_id']; ?>, urlSitio + 'planilla/loadPlanilla');">
						<div class="imgLnk lnkDel" title="Eliminar" ></div >
					</a>
				</div>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="5"><div class="paging"><?php echo $lstPlanillas['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	
	<input type="submit" class="clean-gray"
			onclick="document.getElementById('accion').value ='INS'; return true;" 
			value="Nueva planilla"/>
	</center>
	</form>
</div>

<?php $this->load->view('footer'); ?>