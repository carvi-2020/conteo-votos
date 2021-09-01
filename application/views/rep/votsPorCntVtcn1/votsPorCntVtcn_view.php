<?php $this->load->view('header'); ?>
<div id="content">
<h1>Reporte de Votos por Centro de Votacion</h1>
<script type="text/javascript" language="Javascript">
	$(function() {
	});
	
	function limpiar () {
	}
</script>

<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<style type="text/css">
	.select{ border:1px solid #CCCCCC; padding:2px; font:10px/11px Arial, Helvetica, sans-serif; }
</style>
<form method="POST" id="reportes" action="<?php echo site_url('reporte'); ?>">
<input type="hidden" name="gen_pdf" id="gen_pdf" value="<?php echo isset($gen_pdf)?$gen_pdf:set_value('gen_pdf');?>" />
<center>
	<table class="tblform" cellpadding="2">
		<tr>
			<td>
				<center>
					<input type="submit" class="clean-gray" value="Generar reporte" style="width:150px; " id="btnRep" 
						onclick="printReport(urlSitio + 'reporte/repVotsPorCntVtcnPdf1');" />
				</center>
			</td>
			<td>
				<!--<center>
					<input type="submit" class="clean-gray" value="Limpiar" style="width:150px; " id="btnClean" 
						onclick="limpiar(); return false;" />
				</center>-->					
			</td>
		</tr>
	</table>
	</center>
	</form>
</div>

<?php $this->load->view('footer'); ?>