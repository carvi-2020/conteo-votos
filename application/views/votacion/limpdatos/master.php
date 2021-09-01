<?php $this->load->view('header'); ?>

<div id="content">
	<h1>Cargo Postulado > Detalle del Cargo Postulado</h1>
	<form method="POST" id="opcionesmenu" action="<?php echo site_url('limpdato'); ?>">
	<center>
	<table class="tblform">
		<tr>
			<td>Accion: </td>
			<td>
				<select name="accion" id="accion" >
					<option value="VOTABS">Votos Abstenidos</option>					
					<option value="VOTNULL">Votos Nulos</option>									
					<option value="VOTPNLLS">Votos de Planillas</option>
					<option value="PADELEC">Padron Electoral</option>
					<option value="CAND">Candidatos</option>
					<option value="PNLLS">Planillas</option>
					<option value="CNTRSVOTS">Centros de Votacion</option>
					<option value="CARPOS">Cargos Postulados</option>
				</select>
			</td>
			<td style="width:20px;"></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="5" class="btnsCell">
			<input type="submit" class="clean-gray" 
				onclick="mostrarPopUp(); return false;" 
				value="Limpiar Datos" />
			</td>
		</tr>
	</table>
	<div id="errorsForm">
		<?php echo validation_errors(); ?>
	</div>
	
	<div class="pop-up-div-bg"></div>
	<div id="pop-up-div" class="pop-up-div">
		<center>
			<h1>Está seguro que desea realizar esta accion?</h1>
			<table cellpadding="5" cellspacing="0" border="0">
				<tr>
					<td>
						<input type="submit" class="clean-gray" 
							onclick="return setAccionForm(<?php echo "'" . site_url('/limpdato/limpDatos') . "'"; ?>);" 
							value="Limpiar Datos" />
					</td>
					<td>
						<input type="submit" class="clean-gray bnts-cntls" 
							onclick="ocultarPopUp(); return false;" value="Cancelar" />
					</td>
				</tr>
			</table>
		</center>
	</div>
	
	</center>
	</form>
</div>

<script type="text/javascript">
	function mostrarPopUp(){
		$(".pop-up-div-bg").show();
		$("#pop-up-div").show();
	}
	
	function ocultarPopUp(){
		$(".pop-up-div-bg").hide();
		$("#pop-up-div").hide();
	}	
</script>

<?php $this->load->view('footer'); ?>
