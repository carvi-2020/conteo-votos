<?php $this->load->view('header'); ?>
<div id="content">
<h1>Reporte de Votaciones de Socios</h1>
<script type="text/javascript" language="Javascript">

	$(function() {
		$("#fecha_inicio").datepicker({
			yearRange: "-150:+30", 
			dateFormat: 'dd/mm/yy',     
			changeMonth: true,      
			changeYear: true    
		});
		
		$("#fecha_fin").datepicker({ 
			yearRange: "-150:+30",
			dateFormat: 'dd/mm/yy',     
			changeMonth: true,      
			changeYear: true
		});
	});
	
	function limpiar () {
		$('#fecha_inicio').val("");		  
		$('#fecha_fin').val("");
		$('#centrovot_id').val("");
	}
</script>

<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<style type="text/css">
	.select{ border:1px solid #CCCCCC; padding:2px; font:10px/11px Arial, Helvetica, sans-serif; }
</style>
<form method="POST" id="reportes" action="<?php echo site_url('reporte'); ?>">
<input type="hidden" name="gen_pdf" id="gen_pdf" value="<?php echo isset($gen_pdf)?$gen_pdf:set_value('gen_pdf');?>" />
<center>
	<table class="tblform">
		<tr>
			<td>Fecha Inicial: </td>
			<td><input type="text" name="fecha_inicio" id="fecha_inicio" class="textform" maxlength="25" value="" /></td>
			<td style="width:20px;"></td>
			<td>Fecha final: </td>
			<td><input type="text" name="fecha_fin" id="fecha_fin" class="textform" maxlength="25" value="" /></td>
		</tr>		
		<tr>
			<td>Centro de Votacion: </td>
			<td>
				<select class="select" name="centrovot_id" id="centrovot_id" >
					<option value="">Seleccione</option>
					<?php 
						//Mostrando los centros de votacion
						if( is_array($lstCentroVotaciones) ){
							foreach($lstCentroVotaciones as $fila) { 
					?>
						<option value="<?php echo $fila['centrovot_id'] ?>"><?php echo $fila['nombre'] ?></option>
					<?php
							} 
						} 
					?>					
				</select>
			</td>
			<td style="width:20px;"></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<table class="tblform" cellpadding="2">
		<tr>
			<td>
				<center>
					<input type="submit" class="clean-gray" value="Generar reporte" style="width:150px; " id="btnRep" 
						onclick="printReport(urlSitio + 'reporte/repVotnsSociosPdf');" />
				</center>
			</td>
			<td>
				<center>
					<input type="submit" class="clean-gray" value="Limpiar" style="width:150px; " id="btnClean" 
						onclick="limpiar(); return false;" />
				</center>					
			</td>
		</tr>
	</table>
	</center>
	</form>
</div>

<?php $this->load->view('footer'); ?>