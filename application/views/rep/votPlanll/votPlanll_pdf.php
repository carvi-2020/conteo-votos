<?php $this->load->view('_increp/header'); ?>
	<br/>
	<center><h1>Reporte de Votaciones por Planilla</h1></center>

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

	<strong>
		<?php
			if( isset($centrovot) ){
				echo "Centro de Votacion: " . $centrovot['nombre'];
			} 
		?>
	</strong>
	
	<table class="tblRep" style="width:100%;margin-top:16px;font-size:10px !important;" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th>CARGO</th>
				<?php 
					/*if( is_array($lstPlanillas) ){
						foreach($lstPlanillas as $fila1) {*/
				if( is_array($lstPlanillasx) ){
					foreach($lstPlanillasx as $fila1) {
				?>
					<th><?php echo $fila1['nombre']; ?></th>
				<?php 
						}
					} 
				?>
				<th>ABSTENCIONES</th>
				<th>VOTO NULO</th>
				<th>VOTOS VALIDOS</th>
			</tr>
		</thead>
		<tbody>			
			<?php 
				if( is_array($tabla) ){
					foreach($tabla as $itemTabla) {
			?>
				<tr>
					<?php 
						if( is_array($itemTabla) ){
							foreach($itemTabla as $itemSubTabla) {
					?>
						<td class="textCenter"><?php echo $itemSubTabla; ?></td>
					<?php 
							}
						} 
					?>
				</tr>
			<?php 
					}
				} 
			?>
		</tbody>
	</table>
	
	<table style="margin:60px auto 0px auto;" width="70%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td style="border-top: 1px solid #333333;"></td>
			<td width="45"></td>
			<td style="border-top: 1px solid #333333;"></td>
		</tr>
		<tr>
			<td align="center">JEFE DE INFORM&Aacute;TICA</td>
			<td></td>
			<td align="center">DIRECTOR DEL COMIT&Eacute;</td>
		</tr>
	</table>
	
<?php $this->load->view('_increp/footer'); ?>