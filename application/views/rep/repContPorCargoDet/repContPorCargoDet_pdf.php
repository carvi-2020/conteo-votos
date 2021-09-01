<?php $this->load->view('_increp/header'); ?>
	<br />
<!--<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/tblpaging.css') ?>">-->
	<center><h1>Reporte de Conteo por Cargo</h1></center>
	
	<?php echo $contenido; ?>
	
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
	<!--<table style="width:100%; margin-top:16px;font-size:14px !important;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:25%; text-align:center;"><b>Junta Directiva del Periodo:</b> <?php echo isset($periodo)?$periodo:''; ?></td>
		</tr>
	</table>-->
	<!--<table class="tblPaging" style="width:93%; margin-top:16px;" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th>PUESTO</th>
				<th>NOMBRE</th>
				<th>VOTOS</th>
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
						if( isset($itemTabla[0]) && isset($itemTabla[1]) && isset($itemTabla[2]) && isset($itemTabla[3]) ){
				?>
					<td class="textCenter">
						<input type="hidden" name="idcargo<?php echo $itemTabla[0]; ?>" id="idcargo<?php echo $itemTabla[0]; ?>" 
							value="<?php echo $itemTabla[0]; ?>" />
							
						<input type="hidden" name="puesto<?php echo $itemTabla[0]; ?>" id="puesto<?php echo $itemTabla[0]; ?>" 
							value="<?php echo $itemTabla[1]; ?>" />
						<?php echo $itemTabla[1]; ?>
					</td>
					<td class="textCenter">
						<input type="text" class="textform" name="candidato<?php echo $itemTabla[0]; ?>" id="candidato<?php echo $itemTabla[0]; ?>" 
							value="<?php echo $itemTabla[2]; ?>" />
					</td>
					<td class="textCenter">
						<input type="hidden" name="votos<?php echo $itemTabla[0]; ?>" id="votos<?php echo $itemTabla[0]; ?>" 
							value="<?php echo $itemTabla[3]; ?>" />
						<?php echo $itemTabla[3]; ?>
					</td>
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
	</table>-->
	
<?php $this->load->view('_increp/footer'); ?>
