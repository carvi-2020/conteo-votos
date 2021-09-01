<?php $this->load->view('header'); ?>

<div id="content">
<h1>Candidatos</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<form method="POST" id="candidatos" action="<?php echo site_url('candidato/loadCandidato'); ?>">
<input type="hidden" name="candidato_id" id="candidato_id" />
<input type="hidden" name="accion" id="accion" />
	<center>
	<table class="tblPaging">
	<thead>
		<tr>
			<th>Cargo</th>
			<th>Cod. socio</th>
			<th>Nombres</th>
			<th>Apellidos</th>
			<th>Planilla</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($lstCandidatos['arrData'] as $fila) { ?>
			<tr>
				<td class="textCenter"><?php echo $fila['nombre_cargo']; ?></td>
				<td class="textCenter"><?php echo $fila['cod_socio']; ?></td>
				<td class="textCenter"><?php echo $fila['nombres']; ?></td>
				<td class="textCenter"><?php echo $fila['apellidos']; ?></td>
				<td class="textCenter"><?php echo $fila['nombre_planilla']; ?></td>
				<td class="actionCol">	
					<div class="dosIconos" >
						<a href="#" onclick="actualizar('candidato_id', <?php echo $fila['candidato_id']; ?>, urlSitio + 'candidato/loadCandidato');" >
							<div class="imgLnk lnkUpd" title="Actualizar" ></div >
						</a>
						<div class="sepr"></div>
						<a href="#" onclick="eliminar('candidato_id', <?php echo $fila['candidato_id']; ?>, urlSitio + 'candidato/loadCandidato');">
							<div class="imgLnk lnkDel" title="Eliminar" ></div >
						</a>
					</div>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td colspan="6"><div class="paging"><?php echo $lstCandidatos['links']; ?></div></td>
		</tr>
	</tbody>
	</table>
	<br />
	
	<input type="submit" class="clean-gray"
			onclick="document.getElementById('accion').value ='INS'; return true;" 
			value="Nuevo Candidato"/>
	</center>
	</form>
</div>

<?php $this->load->view('footer'); ?>