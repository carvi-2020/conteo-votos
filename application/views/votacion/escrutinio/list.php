	<script language="javascript" type="text/javascript" src="<?php echo site_url('js/jquery-1.7.2.min.js') ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css') ?>">
	
	<style type="text/css">
		::selection{ background-color: #E13300; color: white; }
		::moz-selection{ background-color: #E13300; color: white; }
		::webkit-selection{ background-color: #E13300; color: white; }
		#graficos{ overflow:hidden;}
	</style>
	
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/graphics.css') ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo site_url('js/amcharts/amcharts.js') ?>"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url('js/amcharts/pie.js') ?>"></script>
	
	<div id="graficos" style="display: none;">		
		
		<script type="text/javascript">
			function crearGrafico(){
				<?php 
					if( is_array($lstPlanillas) ){
						foreach($lstPlanillas as $filaPlanilla) { 
				?>
					var chart<?php echo $filaPlanilla['planilla_id']; ?>;
		        	var legend<?php echo $filaPlanilla['planilla_id']; ?>;
		        	
					var chartData<?php echo $filaPlanilla['planilla_id']; ?> = [  
						<?php  
						$cnt = 0;
						$comma = 0;
						if( is_array($tabla) ){
							foreach($tabla as $filaTabla) {
								if( is_array($filaTabla) ){
									if( $filaPlanilla['planilla_id'] == $filaTabla[0] ){
										if( $comma == 1 ){
											echo ",";
										}					
						?>
										{        
											cargo: "<?php echo $filaTabla[2]; ?>",
											votos: "<?php echo $filaTabla[3]; ?>"
										}      
						<?php
										$comma = 1;
										$cnt++;
									}
								}
							}
						}
						?>      
					];
						
					//Dando propiedades al grafico
			        AmCharts.ready(function () {
			            // PIE CHART
			            chart = new AmCharts.AmPieChart();
			            chart.dataProvider = chartData<?php echo $filaPlanilla['planilla_id']; ?>;
			            chart.titleField = "cargo";
			            chart.valueField = "votos";
			            chart.outlineColor = "#FFFFFF";
			            chart.outlineAlpha = 0.8;
			            chart.outlineThickness = 1;
			            chart.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[votos]]</b> ([[percents]]%)</span>";
			            // this makes the chart 3D
			            chart.depth3D = 15;
			            chart.angle = 30;
			
			            // WRITE
			            chart.write("chartdiv<?php echo $filaPlanilla['planilla_id']; ?>");
			        });			
				<?php 
						}//Fin foreach planilla
					}//Fin if planilla 
				?>
			}
			
			crearGrafico();
		</script>
		
		<div style="overflow:hidden; margin:25px;">
		<?php 
			if( is_array($lstPlanillas) ){
				foreach($lstPlanillas as $filaPlanilla) { 
		?>
			<h1>Votacion para la Planilla: <span style="color:#5C86A0;"><?php echo $filaPlanilla['nombre']; ?></span></h1>
			<div style="position:relative; overflow:hidden;">
				<div style="position:absolute; background:#FFFFFF; left:0px; top:0px; z-index:5; width:300px; height:20px;"></div>
				<div id="chartdiv<?php echo $filaPlanilla['planilla_id']; ?>" style="width:100%; height:400px;"></div>
			</div>
		<?php 
				}
			} 
		?>
		</div>
	</div> <!-- Fin graficos -->
	
	<script language="javascript" type="text/javascript">
		$(function() {
			$("#graficos").fadeIn(0, function() {
					
			});
			parent.iframeLoaded();
		});
	</script>	