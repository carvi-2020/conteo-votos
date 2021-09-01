<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Colegio Medico :: Sistema de Control de Votaciones</title>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css') ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo site_url('js/jquery-1.7.2.min.js') ?>"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url('js/utils.js') ?>"></script>
	<style type="text/css">

		::selection{ background-color: #E13300; color: white; }
		::moz-selection{ background-color: #E13300; color: white; }
		::webkit-selection{ background-color: #E13300; color: white; }
	
		body { background:#FFFFFF url('../images/login-bg.jpg') 0px 320px 	repeat-x; font:13px/20px normal Helvetica, Arial, sans-serif;}
		body, html{ width:100%; height:100%;}
		*{ margin:0px; padding:0px;}
		h1 { color: #444; background-color: transparent; border-bottom: 1px solid #A5C6F8; font-size: 19px; font-weight: normal; margin: 0 0 14px 0; padding: 14px 15px 10px 15px; }
		#loginFrm{ background:#F4F4F4; border: 1px solid #999999;
			margin:200px auto 0px auto; width:294px;
			padding:25px 7px;
			font:12px/14px Arial, Helvetica, sans-serif; 
			font-weight:bold; color:#333333; overflow:hidden;}
		#mainCnt{ overflow:hidden; }
		.txtfield{ width:160px; height:25px; font:11px/25px Arial, Helvetica, sans-serif; padding:0px 8px; color:#333333; font-style:italic; border:1px solid #999999;}
		.txtlabel{ font:12px/19px Arial, Helvetica, sans-serif; color:#666666; text-align:left; font-weight:bold;}
		.tblogin tr td{ padding-bottom:3px;}
		.btn-login{ background:#00703C; border:1px solid #76AE00; color:#FFFFFF; font:12px 14px Arial, Helvetica, sans-serif; font-weight:bold; padding:5px 22px;}
		.errorsForm{ padding:10px 15px 0px 15px; font:11px/14px Arial, Helvetica, sans-serif !important; font-weight:bold; text-align:left;}
	</style>
</head>
<body>
	<?php if(isset($msgType) && $msgType == 'MSG') { ?>
		<div class="info" id="msgMtto" style="width:400px;">
			<?php echo ($msgMtto); ?>
		</div>
		
		<script type="text/javascript" language="javascript">
			jQuery('#msgMtto').css('margin-left', 
				(jQuery(document).width() / 2) - (jQuery('#msgMtto').width() / 2) - 40 ) ;
			jQuery('#msgMtto').fadeOut(5000).delay(500);
		</script>
		
	<?php } else if(isset($msgType) && $msgType == 'ERR') { ?>
		<div class="error" id="errMtto" style="width:400px;">
			<?php echo ($msgMtto); ?>
		</div>
		<script type="text/javascript" language="javascript">
			jQuery('#errMtto').css('margin-left', 
				(jQuery(document).width() / 2) - (jQuery('#errMtto').width() / 2) - 40) ;
			jQuery('#errMtto').fadeOut(5000).delay(500);
		</script>
	<?php } else if($this->uri->segment(3) == 'EXPNTA') { ?>
		<div class="error" id="errMtto" style="width:400px;">
			<?php echo ('Su sesi&oacute;n ha expirado o est&aacute; intentando acceder al sistema sin haber iniciado sesi&oacute;n.'); ?>
		</div>
		<script type="text/javascript" language="javascript">
			jQuery('#errMtto').css('margin-left', 
				(jQuery(document).width() / 2) - (jQuery('#errMtto').width() / 2) - 40) ;
			jQuery('#errMtto').fadeOut(5000).delay(500);
		</script>
	<?php }  ?>
	
	<div id="mainCnt">
	</div>
	
	<div id="loginFrm">
		<center>
		<div><img src="<?php echo site_url('images/lock-icon-login.png'); ?>" /></div>
		<form method="POST" action="<?php echo site_url('login/inicioSesion/'); ?>">
			<table class="tblogin" cellpadding="0" cellspacing="0" border="0">
				<tr><td><p class="txtlabel">Usuario:</p></td></tr>
				<tr><td><input type="text" name="_usr24" class="txtfield" maxlength="20" /></td></tr>
				<tr><td><p class="txtlabel">Clave:</p></td></tr>
				<tr><td><input type="password" name="_pwd24" class="txtfield" maxlength="20" /></td></tr>
				<tr>
					<td style="padding-top:10px;">	
						<center><input type="submit" class="btn-login" value="Ingresar" /></center>		
					</td>
				</tr>
			</table>
			<div id="errorsForm" class="errorsForm">
				<?php echo validation_errors(); ?>
			</div>
		</form>
		</center>
		<center><div style="position:fixed; bottom:0; padding-bottom:20px; font-weight:bold;">Desarrollado por Soluciones Aplicativas S.A. de C.V.</div></center>
	</div>


</body>
</html>