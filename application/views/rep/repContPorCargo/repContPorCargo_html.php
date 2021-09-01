	<table style="width:100%; margin-top:16px;font-size:14px !important;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:25%; text-align:center;">
				<b>Junta Directiva del Periodo:</b> <?php echo isset($nom_periodo)?$nom_periodo:''; ?>
				<input type="hidden" name="periodo" id="periodo" value="<?php echo isset($periodo)?$periodo:''; ?>" />
				<input type="hidden" name="nom_periodo" id="nom_periodo" value="<?php echo isset($nom_periodo)?$nom_periodo:''; ?>" />
			</td>
		</tr>
	</table>
	<!-- -->
	<table class="tblPaging" style="width:93%; margin-top:16px;" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th>PUESTO</th>
			<th>NOMBRE</th>
			<th>VOTOS</th>
		</tr>
		</thead>
		<!-- -->
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
									   value="<?php echo $itemTabla[2]; ?>" style="width:580px;" />
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
	</table>
	<br />
	<input type="submit" class="clean-gray bnts-cntls" value="Generar reporte" style="width:150px;" onclick="genRepContPorCargo(); return false;" />
	
	<script language="javascript" type="text/javascript" >
		var tabla = [  
			<?php  $cnt3 = 0;
				$comma3 = 0;
				if( is_array($tabla) ){
					foreach($tabla as $itemTabla) {
					
						if( isset($itemTabla[0]) && isset($itemTabla[1]) && isset($itemTabla[2]) && isset($itemTabla[3]) ){
							if( $comma3 == 1 ){
								echo ",";
							}					
			?>    
			{        
							puesto_id: "<?php echo $itemTabla[0]; ?>",
							puesto: "<?php echo $itemTabla[1]; ?>",
							candidato: "<?php echo $itemTabla[2]; ?>",
							votos: "<?php echo $itemTabla[3]; ?>"
			}      
			<?php
							$comma3 = 1;
							$cnt3++;
						}
					}
				}
			?>      
		];	
	</script>	
