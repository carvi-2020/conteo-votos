<center>
<h1>Votaciones por Planilla</h1>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">
<table class="tblPaging">
	<thead>
		<tr>
			<th>CARGO</th>
			<?php 
				if( is_array($lstPlanillas) ){
					foreach($lstPlanillas as $fila1) { 
			?>
				<th><?php echo $fila1['nombre']; ?></th>
			<?php 
					}
				} 
			?>
			<th>VOTOS ABSTENIDOS</th>
			<th>VOTOS NULOS</th>
			<th>VOTOS V&Aacute;LIDOS</th>
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
</center>