<!DOCTYPE HTML>
<html xmlns='http://www.w3.org/1999/xhtml'><head>
<title>VOTASOFT - Sistema de votacion y escrutinio</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/menu.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/smoothness/jquery-ui-1.9.2.custom.css') ?>">

<script type="text/javascript" src="<?php echo site_url('js/jsDatePick.min.1.3.js') ?>"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo site_url('css/jsDatePick_ltr.min.css') ?>" />
<script language="javascript" type="text/javascript" src="<?php echo site_url('js/jquery-1.7.2.min.js') ?>"></script>
<script language="javascript" type="text/javascript" src="<?php echo site_url('js/jquery-ui-1.9.2.custom.min.js') ?>"></script>
<script language="javascript" type="text/javascript" src="<?php echo site_url('js/utils.js') ?>"></script>
<script type="text/javascript" language="javascript">
	var urlSitio = "<?php echo site_url(); ?>";
	
	function printReport(direccion) {
		window.open('about:blank', 'reporte', 'width=900,height=650,resizeable=no');
	    document.forms[0].setAttribute('target', 'reporte');
	    document.forms[0].setAttribute('onsubmit', '');
	    document.forms[0].action = direccion;
	    document.forms[0].submit();
	    
	}
</script>
</head>
<body>
<div id="container">
	<?php if(isset($msgType) && $msgType == 'MSG') { ?>
		<div class="info" id="msgMtto" >
			<?php echo ($msgMtto); ?>
		</div>
		
		<script type="text/javascript" language="javascript">
			jQuery('#msgMtto').css('margin-left', 
				jQuery('#container').width() - (jQuery('#msgMtto').width() + 82)) ;
			jQuery('#msgMtto').fadeOut(5000).delay(500);
		</script>
		
	<?php } else if(isset($msgType) && $msgType == 'ERR') { ?>
		<div class="error" id="errMtto">
			<?php echo ($msgMtto); ?>
		</div>
		<script type="text/javascript" language="javascript">
			jQuery('#errMtto').css('margin-left', 
				jQuery('#container').width() - (jQuery('#errMtto').width() + 82)) ;
			jQuery('#errMtto').fadeOut(5000).delay(500);
		</script>
	<?php } else if($this->uri->segment(3) == 'NOTAUTH') { ?>
		<div class="error" id="errMtto">
			<?php echo ('Est&aacute; intentando acceder a una opci&oacute;n a la cual no tiene permiso o no est&aacute; autorizado.'); ?>
		</div>
		<script type="text/javascript" language="javascript">
			jQuery('#errMtto').css('margin-left', 
				jQuery('#container').width() - (jQuery('#errMtto').width() + 82)) ;
			jQuery('#errMtto').fadeOut(5000).delay(500);
		</script>
	<?php }  ?>
<div id="header">
	<div id="banner">
		<img src="<?php echo site_url('images/lock-icon.png'); ?>" style="float:left; width:50px; height:50px; margin:6px 10px 0px 6px;" />
		<div class="ttltxt">COLEGIO MEDICO :: SISTEMA DE VOTACIONES ONLINE</div>
	</div>
	<div id="menu">
		<ul id="appMenu" class="topmenu">			
			<li class="topfirst"><a href="<?php echo site_url('login/bienvenido'); ?>" title="Inicio">Inicio</a></li>
			
			<?php 
			if($_SESSION['menu'] != null) {
			for($cntMnu = 0; $cntMnu < count($_SESSION['menu']); $cntMnu++) {
				$varArra = $_SESSION['menu']; 
				$row = $varArra[$cntMnu];
				?>
				
				<li class="topmenu"><a href="#" title="<?php echo $row['etiqueta']; ?>"><span><?php echo $row['etiqueta']; ?></span></a>
				<?php if(isset($row['hijos'])) {
					echo '<ul>'; 
					if(isset($row['hijos'])) {
					for($cntSubMnu = 0; $cntSubMnu < count($row['hijos']); $cntSubMnu++) { 
						$rowH = $row['hijos'][$cntSubMnu];
						?>
						
						<li class="subfirst"><a href="<?php echo site_url($rowH['url']); ?>" title="<?php echo $rowH['etiqueta']; ?>">
							<?php echo $rowH['etiqueta']; ?>
						</a></li>
				<?php }
					echo '</ul>';
				}} ?>
				</li>	
			<?php }
			
			} ?>
			<li class="topmenu"><a href="<?php echo site_url('login/cerrarSesion'); ?>" title="Cerrar sesion">Cerrar sesion</a></li> <!--toplast es estilo para el ultimo -->
		</ul>
	</div>
</div>
<div id="wrapper">