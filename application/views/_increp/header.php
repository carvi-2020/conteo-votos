<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>
		<p style="text-align: right;">
		Colegio M&eacute;dico de El Salvador
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Generado por:
		<strong> 
		<?php
			date_default_timezone_set('America/El_Salvador');
			$this->load->library('session');
			$usuario = $this->session->userdata('usuario');
			if( isset($usuario['idusr']) ){
				echo $usuario['nomusuario'];	
			}
		?>
		</strong>
		<!-- el <strong><?php echo date('d') . "/" . date('m') . "/" . date('Y'); ?></strong> --> 
		a las <strong><?php echo date('h:i:s A'); ?></strong>
		</p>
	</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/styleRep.css') ?>">
	<script type="text/javascript" language="javascript">
		var urlSitio = "<?php echo site_url(); ?>";
	</script>
</head>
<body>
<div id="wrapper">