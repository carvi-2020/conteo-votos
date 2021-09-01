</div>
<script type="text/php">

if ( isset($pdf) ) {
	$font = Font_Metrics::get_font("Arial");
	//if (!isset($font)) { Font_Metrics::get_font("sans-serif"); }  
  
	$size = 8;
	$sizeEnt = 9;
	$color = array(0,0,0);
	$text_height = Font_Metrics::get_font_height($font, $size);
	
	$fechaFull = date("d/m/Y, h:i A");
	
	
	$foot = $pdf->open_object();
	  
	$w = $pdf->get_width();
	$h = $pdf->get_height();
	  
	$pdf->page_text(30, 20, "Colegio Medico de El Salvador", $font, $sizeEnt, array(0,0,0));
	$pdf->page_text($w - 30 - Font_Metrics::get_text_width($fechaFull, $font, $size), 20, $fechaFull, $font, $size, array(0,0,0));
	
	$y = $h - 2 * $text_height - 24;
	$y += $text_height;
	
	$pdf->close_object();
	$pdf->add_object($foot, "all");
	 
	$text = "{PAGE_NUM} de {PAGE_COUNT}";  
	
	// Center the text
	$width = Font_Metrics::get_text_width("01 de 02", $font, $size);
	$pdf->page_text($w / 2 - $width / 2, $y, $text, $font, $size, $color);
  
}
</script>
</body>
</html>