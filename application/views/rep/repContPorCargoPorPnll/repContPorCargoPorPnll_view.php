<?php $this->load->view('header'); ?>
<div id="content">
<h1>Reporte de Conteo por Cargo por Planilla</h1>

<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<style type="text/css">
	.select{ border:1px solid #CCCCCC; padding:2px; font:10px/11px Arial, Helvetica, sans-serif; }
	.bnts-cntls{ width:100px !important; padding:0px !important; height:23px; font:11px/12px Arial, Helvetica, sans-serif !important; }
</style>

	<?php


	$query_select = " SELECT * FROM planilla where estado ='ACT' ";
	$result_select = mysql_query($query_select) or die(mysql_error());

	$lstPlanillasx = array();
	while($row = mysql_fetch_array($result_select)) {
		$lstPlanillasx[]=$row;
	}

	foreach($lstPlanillasx as $row) {
		//echo $row['nombre'];
	}
?>

<form method="POST" id="reportes" action="<?php echo site_url('reporte'); ?>">
<input type="hidden" name="gen_pdf" id="gen_pdf" value="<?php echo isset($gen_pdf)?$gen_pdf:set_value('gen_pdf');?>" />
<input type="hidden" name="html_content" id="html_content" value="" />
<center>
	<table class="tblform" cellpadding="2">
		<tr>
			<td>Planilla: </td>
			<td>
				<select class="select" name="planilla_id" id="planilla_id" >
					<?php 
						//Mostrando las planillas
						/*if( is_array($lstPlanillas) ){
							foreach($lstPlanillas as $fila) {*/
					if( is_array($lstPlanillasx) ){
						foreach($lstPlanillasx as $fila) {
					?>
						<option value="<?php echo $fila['planilla_id'] ?>"><?php echo $fila['nombre'] ?></option>
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
		<tr>
			<td colspan="5">
				<center>
					<input type="submit" class="clean-gray" value="Consultar Informacion" style="width:150px;" id="btnRep" 
						onclick="consultarResults(); return false;" />
					<!--<input type="submit" class="clean-gray" value="Generar reporte" style="width:150px; " id="btnRep" 
						onclick="printReport(urlSitio + 'reporte/repContPorCargoPorPnllHtml');" />-->
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
	<br/>
	<div class="infoReport">
		
	</div>
	</center>
	</form>
</div>

<script>
	var html_content = "";
	
	function consultarResults(){
		dataString = "<?php echo site_url('reporte/repContPorCargoPorPnllHtml/'); ?>"
		+ "/" + $("#planilla_id").attr("value");
		//alert( dataString );
		//Enviando la informacion al carrito via ajax itemsCart
		$.ajax({
			url: dataString,
			success: function (data) {
				//Obtenemos la cantidad de productos del carrito
				$( '.infoReport' ).html(data);
	    	},
			error: function (xhr, ajaxOptions, thrownError) {
				alert("A ocurrido un error y la no pudo obtenerse la informacion.");
			}
		});
	}

	function limpiar () {
		//Limpiar filtros
	}
	
	function genRepContPorCargo(){
		
		html_content = '';
		html_content += '<table style="width:100%; margin-top:16px;font-size:14px !important;" cellpadding="0" cellspacing="0">';
		html_content += '<tr>';
		html_content += '<td style="width:25%; text-align:center;"><b>Junta Directiva del Periodo:</b>';
		html_content +=	$( "#nom_periodo" ).val();
		html_content += '</tr>';
		html_content += '</table>';
		
		
		html_content += '<table class="tblRep" style="width:100%;margin-top:16px;font-size:10px !important;" cellpadding="0" cellspacing="0">';
		html_content += '<thead>';
		html_content += '<tr>';
		html_content += '<th>PUESTO</th>';
		html_content += '<th>NOMBRE</th>';
		html_content += '<th>VOTOS</th>';
		html_content += '</tr>';
		html_content += '</thead>';
		html_content += '<tbody>';
		for( var i=0; i<tabla.length; i++ ){
			html_content += '<tr>';
			html_content += '<td class="textCenter">';
			html_content +=	$( "#puesto" + tabla[i].puesto_id ).val();
			html_content += '</td>';
			html_content += '<td class="textCenter">';
			html_content +=	$( "#candidato" + tabla[i].puesto_id ).val();
			html_content += '</td>';
			html_content += '<td class="textCenter">';
			html_content +=	$( "#votos" + tabla[i].puesto_id ).val();
			html_content += '</td>';
			html_content += '</tr>';
		}
		html_content += '</tbody>';
		html_content += '</table>';
		//html_content = encodeURIComponent(html_content);
		//html_content = escape(html_content);
		//alert(html_content);
		 
		$("#html_content").val( html_content );
		printReport(urlSitio + 'reporte/repContPorCargoPorPnllPdf/' );
	}	
</script>

<?php $this->load->view('footer'); ?>