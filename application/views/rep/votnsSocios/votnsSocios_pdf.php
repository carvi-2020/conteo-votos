<?php $this->load->view('_increp/header'); ?>
	<br />
	<center><h1>Reporte de Votos dobles</h1></center>

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

	<table style="width:100%; margin-top:16px;font-size:14px !important;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:25%; text-align:center;"><b>Fecha de inicio:</b> <?php echo isset($fecha_inicio)?$fecha_inicio:''; ?></td>
			<td style="width:25%; text-align:center;"><b>Fecha final:</b> <?php echo isset($fecha_fin)?$fecha_fin:''; ?></td>
			<td style="width:25%; text-align:center;"><b>Centro de Votacion:</b> <?php echo isset($centrovot_id)?$centrovot_id:''; ?></td>
		</tr>
	</table>
	<table class="tblRep" style="width:100%;margin-top:16px;font-size:10px !important;" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th>CODIGO</th>
				<th>NOMBRE COMPLETO</th>
				<th>CENTRO DE VOTACION</th>
				<th>FECHA Y HORA DE INGRESO</th>
			</tr>
		</thead>
		<tbody>			
			<?php 
				foreach($lstVtsSoc as $fila) {
				//foreach($lstPlanillasx as $fila){
			?>
			<tr>
			<!--  -->
				<td class="textCenter"><?php echo $fila['codSocio']; ?></td>
				<td class="textCenter"><?php echo $fila['socio']; ?></td>
				<td class="textCenter"><?php echo $fila['centro_votacion']; ?></td>
				<td class="textCenter"><?php echo $fila['fecha_hora']; ?></td>
				
				<!-- llamar a la base de datos 
				<td class="textCenter"><?php echo $fila[0]; ?></td>
				<td class="textCenter"><?php echo $fila[1]; ?></td>
				<td class="textCenter"><?php echo $fila[2]; ?></td>
				<td class="textCenter"><?php echo $fila[3]; ?></td>
				-->
			</tr>
			<?php 
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
