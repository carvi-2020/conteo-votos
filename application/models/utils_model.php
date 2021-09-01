<?php 

class Utils_model extends CI_Model {
	
	function __construct() {
		parent::__construct();		
	}
	
	function getData($dato, $defValue = NULL) {
		$variable = $dato;
		if(isset($variable) && $variable != NULL && $variable != '') 
			return $variable;
		else 
			return $defValue;
	}
	
}

/* End of file super_model.php*/
