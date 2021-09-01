<?php $this->load->view('header'); ?>
	
	<script language="javascript" type="text/javascript">
		$(function() {
			var dataString2 = "<?php echo site_url('escrutinio/lstVotPlanilla/'); ?>"
			$.ajax({
				url: dataString2,
				success: function (data) {
					$('#tabla').html(data);
		    	},
				error: function (xhr, ajaxOptions, thrownError) {
					alert("A ocurrido un error y la informacion de votos por planilla no pudo refrescarse");
				}
			});	
				
			var refreshId = setInterval( function(){
				dataString = "<?php echo site_url('escrutinio/loadIframe/'); ?>"
				
				$.ajax({
					url: dataString,
					success: function (data) {
						$('#graficos').html(data);
			    	},
					error: function (xhr, ajaxOptions, thrownError) {
						alert("A ocurrido un error y la operacion no pudo realizarce");
					}
				});	
				
				//Refrescamos la tabla de votos por planilla
				$.ajax({
					url: dataString2,
					success: function (data) {
						$('#tabla').html(data);
			    	},
					error: function (xhr, ajaxOptions, thrownError) {
						alert("A ocurrido un error y la informacion de votos por planilla no pudo refrescarse");
					}
				});	
					
			}, 40000); //Ejecutar el proceso de refrescado cada 30 segundos
		});
	</script>
	
	<br/>
	<div id="tabla" style="overflow: hidden; margin: 0px 30px;">
	</div>
	
	<div id="graficos">
		<iframe id="idIframe" style="border:0px; width: 100%;" src="<?php echo site_url('escrutinio/lstVotosPorCentro/'); ?>"></iframe>	
	</div>
	
	<script type="text/javascript">
	  function iframeLoaded() {
	      var iFrameID = document.getElementById('idIframe');
	      if(iFrameID) {
	            // here you can make the height, I delete it first, then I make it again
	            iFrameID.height = "";
	            iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
	      }   
	  }
	</script> 
<?php $this->load->view('footer'); ?>