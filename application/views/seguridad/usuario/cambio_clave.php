<?php $this->load->view('header'); ?>
<div id="content">
	<h1>Cambio de contrase&ntilde;a</h1>
	<form method="POST" id="usuarios" action="<?php echo site_url('usuario'); ?>">
	<center>
	<table class="tblform">
		<tr>
			<td>Contrase&ntilde;a actual: </td>
			<td>
				<input type="password" name="clave_act" id="clave_act" class="textform" maxlength="20" /> <span class="frmreq">*</span>
			</td>
		</tr>
		<tr>
			<td>Nueva contrase&ntilde;a: </td>
			<td>
				<input type="password" name="clave_nva" id="clave_nva" class="textform" maxlength="20" /> <span class="frmreq">*</span>
			</td>
		</tr>
		<tr>
			<td>Repita la nueva contrase&ntilde;a: </td>
			<td>
				<input type="password" name="clave_nva_compr" id="clave_nva_compr" class="textform" maxlength="20" /> <span class="frmreq">*</span>
			</td>
		</tr>
		
		<tr>
			<td colspan="5" class="btnsCell">
				<input type="submit" class="clean-gray" 
						onclick="return setAccionForm(urlSitio + 'usuario/updClave'); "value="Cambiar contrase&ntilde;a" />
			</td>
		</tr>
	</table>
	<div id="errorsForm">
	<?php echo validation_errors(); ?>
	</div>
	</center>
	</form>
</div>
<?php $this->load->view('footer'); ?>
