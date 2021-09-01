<?php $this->load->view('header'); ?>

<div id="content">
	<h1>Padron Electoral > Detalle del Padron Electoral</h1>
	<form method="POST" id="opcionesmenu" enctype="multipart/form-data" action="<?php echo site_url('padronelectoral'); ?>">
	<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>" />
	<input type="hidden" name="pdrele_id" id="pdrele_id" value="<?php echo isset($pdrele_id)?$pdrele_id:set_value('pdrele_id');?>" />
	<center>
	<table class="tblform">
		<tr>
			<td>Votacion Activa: </td>
			<td>
				<input type="hidden" name="votacion_id" id="votacion_id" value="<?php echo isset($votacion_id)?$votacion_id:set_value('votacion_id');?>" />
				<input type="text" name="nombre_votacion" id="nombre_votacion" readonly="true" class="textform" maxlength="10" value="<?php echo isset($nombre_votacion)?$nombre_votacion:set_value('nombre_votacion');?>" />
			</td>
			<td style="width:20px;"></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Socio: </td>
			<td>
				<input type="hidden" name="socio_id" id="socio_id" value="<?php echo isset($socio_id)?$socio_id:set_value('socio_id');?>" />
				<input type="text" name="nombre_socio" id="nombre_socio" class="textform" maxlength="20" value="<?php echo isset($nombre_socio)?$nombre_socio:set_value('nombre_socio');?>" />
			</td>
			<td style="width:20px;"></td>
			<td>Centro de Votacion: </td>
			<td>
				<input type="hidden" name="centrovot_id_hd" id="centrovot_id_hd" value="<?php echo isset($centrovot_id)?$centrovot_id:set_value('centrovot_id');?>" />
				<select name="centrovot_id" id="centrovot_id" >
					<?php 
						//Mostrando los centros de votacion
						if( is_array($lstCentroVotaciones) ){
							foreach($lstCentroVotaciones as $fila) { 
					?>
						<option value="<?php echo $fila['centrovot_id'] ?>"><?php echo $fila['nombre'] ?></option>
					<?php
							} 
						} 
					?>					
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<div class="msgErrVot">
					<!-- Mensaje de error si el socio ya ha votado -->
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="5" class="btnsCell">
			<?php if($accion === 'INS') { ?>
				<input type="submit" class="clean-gray" style="display:none;" id="btnSave"
						onclick="return setAccionForm(<?php echo "'" . site_url('/padronelectoral/insPadronelectoral') . "'"; ?>);" value="Guardar" />
			<?php } else if($accion === 'UPD') { ?>
				<input type="submit" class="clean-gray" style="display:none;"
						onclick="return setAccionForm(<?php echo "'" . site_url('/padronelectoral/updPadronelectoral') . "'"; ?>);" value="Actualizar" />
			<?php } else if($accion === 'DEL') { ?>
				<input type="submit" class="clean-gray" style="display:none;"
						onclick="return addAccionFormConfirm(<?php echo "'" . site_url('/padronelectoral/delPadronelectoral') . "'"; ?>, 'Esta seguro que desea eliminar este padron.'); " value="Eliminar" />
				<?php }  ?>&nbsp;
				<input type="submit" class="clean-gray" onclick="return setAccionForm(<?php echo "'" . site_url('/padronelectoral/lstPadroneselectorales') . "'"; ?>);" value="Regresar" />
			</td>
		</tr>
	</table>
	<div id="errorsForm">
		<?php echo validation_errors(); ?>
	</div>
	</center>
	</form>
</div>

<script>
	$(function() {
		var CENTROVOT = $("#centrovot_id_hd").attr("value");
		$("#centrovot_id").val( CENTROVOT );
		
		$( "#nombre_socio" ).autocomplete({      
			source: "<?php echo site_url('/cargopostulado/ajxSocios') ?>",
	      	minLength: 1,      
	      	focus: function( event, ui ) {        
				$( "#nombre_socio" ).val( ui.item.label );        
				return false;      
			},  
			select: function( event, ui ) {
				$( "#socio_id" ).val( ui.item.value );
				$( "#nombre_socio" ).val( ui.item.label );
				
				if($( "#socio_id" ).val() != null && $( "#socio_id" ).val() != '' &&
					$( "#centrovot_id" ).val() != null && $( "#centrovot_id" ).val() != '')
					vrfcrVotoSocio( $( "#socio_id" ).val(), $( "#centrovot_id" ).val() );
				
				return false;      
			}    
		})    
		.data( "ui-autocomplete" )._renderItem = function( ul, item ) {      
			return $( "<li>" )
			.append( "<a><strong>Codigo: </strong>" + item.codigo + " <strong>Nombres: </strong>" +  item.label + "</strong></a>" )
			.appendTo( ul );    
		};
		
		$("#centrovot_id").change(function() {
			if($( "#socio_id" ).val() != null && $( "#socio_id" ).val() != '' &&
				$( "#centrovot_id" ).val() != null && $( "#centrovot_id" ).val() != '')
				vrfcrVotoSocio( $( "#socio_id" ).val(), $( "#centrovot_id" ).val() );
		});
	});
	
	function vrfcrVotoSocio( idSocio, centrovot_id ) {
		dataString = "<?php echo site_url('padronelectoral/vrfcrVotoSocio/'); ?>" + "/" + idSocio + "/" + centrovot_id;
		//Enviando la informacion al carrito via ajax itemsCart
		
		$.ajax({
			dataType: "json",
			url: dataString,
			success: function (data) {
				//alert(data.ya_voto);
				
				var msgCent = '';
				if(data.det_padron != null) {
					$.each(data.det_padron, function(index, ctrv) {
						msgCent += 'Registrado en ' + ctrv.nombre_centro + ' en la fecha ' + ctrv.fecha_hora + '<br />';
						//alert(ctrv.nombre_centro);
						//alert(msgCent);
					});
				}
				
				if(msgCent == '')
					msgCent = 'El socio no ha sido registrado en el padron electoral de ningun centro de votacion';
				
				//alert(msgCent);
				$( '.msgErrVot' ).html(msgCent);
				$( '.msgErrVot' ).show();
				
				if(data.ya_voto == true)	
					$('#btnSave').hide();
				else
					$('#btnSave').show();
	    	},
			error: function (xhr, ajaxOptions, thrownError) {
				alert("Ha ocurrido un error y la no pudo obtenerse la informacion del socio.");
			}
		});
	}
</script>

<?php $this->load->view('footer'); ?>
