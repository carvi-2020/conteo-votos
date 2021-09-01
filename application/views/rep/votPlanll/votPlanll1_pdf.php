<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">

<?php

$query_select = " SELECT * FROM planilla where estado ='ACT' ";
$result_select = mysql_query($query_select) or die(mysql_error());

$lstPlanillax = array();
while($row = mysql_fetch_array($result_select)) {
$lstPlanillax[]=$row;
}

foreach($lstPlanillasx as $row) {
//echo $row['nombre'];
}

?>

	<table class="tbl-info-det" style="font-size: 12px;" width="100%" cellpadding="1" cellspacing="0" border="0">
	<!--<table class="tblPaging">-->
		<thead>
			<tr>
				<th rowspan="2">Cargos postulados</th>
				<th colspan="<?php echo count($lstPlanillax); ?>">Planillas</th>
				<th colspan="3">Tipo de voto</th>
			</tr>
			<tr>
				<?php 
					if( is_array($lstPlanillax) ){
						foreach($lstPlanillax as $fila1) {
				?>
					<th><?php echo $fila1['nombre']; ?></th>
				<?php 
						}
					} 
				?>
				<th>Abstenciones</th>
				<th>Nulos</th>
				<th>V&aacute;lidos</th>
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
	
	<script language="javascript" type="text/javascript">
		parent.iframeLoaded();
	</script>	
	
<?php $this->load->view('_increp/footer'); ?>