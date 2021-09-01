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
				var chart;
	        	var legend;
	        	
				var chartData = [  
					<?php  
					$cnt = 0;
					$comma = 0;
					if( is_array($votosPorCentro) ){
						foreach($votosPorCentro as $filaTabla) {
							if( $comma == 1 ){
								echo ",";
							}					
					?>
							{        
								centro: "<?php echo $filaTabla['nombre_centro']; ?>",
								votos: "<?php echo $filaTabla['num_votos']; ?>"
							}      
					<?php
							$comma = 1;
							$cnt++;
						}
					}
					?>      
				];
						
				//Dando propiedades al grafico
		        AmCharts.ready(function () {
		            // PIE CHART
		            chart = new AmCharts.AmPieChart();
		            chart.dataProvider = chartData;
		            chart.titleField = "centro";
		            chart.valueField = "votos";
		            chart.outlineColor = "#FFFFFF";
		            chart.outlineAlpha = 0.8;
		            chart.outlineThickness = 1;
		            chart.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[votos]]</b> ([[percents]]%)</span>";
		            // this makes the chart 3D
		            chart.depth3D = 15;
		            chart.angle = 30;
		
		            // WRITE
		            chart.write("chartdiv");
		        });			
			}
			
			crearGrafico();
		</script>
		
		<div style="overflow:hidden; margin:25px;">
			<h1>Votacion por Centro de Votacion:</h1>
			<div style="position:relative; overflow:hidden;">
				<div style="position:absolute; background:#FFFFFF; left:0px; top:0px; z-index:5; width:300px; height:20px;"></div>
				<div id="chartdiv" style="width:100%; height:400px;"></div>
			</div>
		</div>
	</div> <!-- Fin graficos -->
	
	<script language="javascript" type="text/javascript">
		$(function() {
			$("#graficos").fadeIn(0, function() {
					
			});
			parent.iframeLoaded();
		});
	</script>	