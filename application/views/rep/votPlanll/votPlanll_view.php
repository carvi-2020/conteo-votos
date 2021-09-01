<?php $this->load->view('header'); ?>
<div id="content">
<h1>Reporte de Votaciones por Planilla</h1>

<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<style type="text/css">
	.select{ border:1px solid #CCCCCC; padding:2px; font:10px/11px Arial, Helvetica, sans-serif; }
</style>
<form method="POST" id="reportes" action="<?php echo site_url('reporte'); ?>">
<input type="hidden" name="gen_pdf" id="gen_pdf" value="<?php echo isset($gen_pdf)?$gen_pdf:set_value('gen_pdf');?>" />
<center>
	<table class="tblform" cellpadding="2">
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
			<td>Opci&oacute;n de generaci&oacute;n:</td>
			<td>
				<select class="select" name="tipo_flt" id="tipo_flt" >
					<option value="FLT">Filtrado</option>
					<option value="ACU">Acumulado</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<center>
					<input type="submit" class="clean-gray" value="Generar reporte" style="width:150px; " id="btnRep" 
						onclick="printReport(urlSitio + 'reporte/repvotPlanllPdf');" />
				</center>
			</td>
			<!--<td>
				<center>
					<input type="submit" class="clean-gray" value="Limpiar" style="width:150px; " id="btnClean" 
						onclick="limpiar(); return false;" />
				</center>					
			</td>-->
		</tr>
	</table>
	</center>
	</form>
</div>

<?php $this->load->view('footer'); ?>