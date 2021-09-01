<?php $this->load->view('_increp/header'); ?>
	<br />
	<center><h1>Reporte de Votos por Centro de Votacion</h1></center>
	<table class="tblRep" style="width:100%;margin-top:16px;font-size:10px !important;" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th>COD. CENTRO VOTACION</th>
				<th>CENTRO DE VOTACION</th>
				<th>NUMERO DE VOTOS</th>
			</tr>
		</thead>
		<tbody>			
			<?php 
				if( is_array($votosPorCentro) ){
					foreach($votosPorCentro as $fila) { 
			?>
			<tr>
				<td class="textCenter"><?php echo $fila['codigo']; ?></td>
				<td class="textCenter"><?php echo $fila['nombre_centro']; ?></td>
				<td class="textCenter"><?php echo $fila['num_votos']; ?></td>
			</tr>
			<?php 
					}
				} 
			?>
		</tbody>
	</table>
	
	<table style="width:100%; margin-top:16px;font-size:14px !important;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:25%; text-align:center;">
				<b>Total de Votos por Todos los Centros de Votacion:</b> <?php echo isset($totalDeVotos)?$totalDeVotos:''; ?>
			</td>
		</tr>
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
