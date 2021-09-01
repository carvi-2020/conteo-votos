<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function verificarFiltros($arrFlt = NULL, $arrKey = NULL, $numRow = NULL, &$data) 
{
	$filtros = array();
    if($arrFlt != NULL) {
    	foreach(array_keys($arrKey) as $llave) {
	    	if((isset($arrFlt['accion']) && $arrFlt['accion']=='FLT') || isset($numRow) )  {
				$filtros[$arrKey[$llave]['fld']] = 
					array('val' => (isset($arrFlt[$llave])?$arrFlt[$llave]:''),
							'typ' =>  $arrKey[$llave]['typ'],
							'tpb' =>  (isset($arrKey[$llave]['tpb'])?$arrKey[$llave]['tpb']:NULL) );
			}
			
			if(isset($arrFlt['accion']) && $arrFlt['accion']=='CLN') 
				$data[$llave] = '';
			else 
				$data[$llave] = isset($arrFlt[$llave])?$arrFlt[$llave]:'';
		}
		
		if(isset($arrFlt['accion']) && $arrFlt['accion']=='CLN') 
			$filtros = array();
    }
    return $filtros;
}

function formarCondicionFiltro($arrFlt = NULL) {
	$condWhere = '';
	foreach(array_keys($arrFlt) as $campo) {
		$dtFlt = $arrFlt[$campo];
		if(isset($dtFlt['val']) && $dtFlt['val'] != NULL && trim($dtFlt['val']) != '' ) {
			if($dtFlt['typ'] == 'str') {
				if(isset($dtFlt['tpb']) && $dtFlt['tpb'] != NULL) {
					if($dtFlt['tpb'] == 'EXA')
						$condWhere .= " AND " . $campo . " = '" . $dtFlt['val'] . "' ";
					if($dtFlt['tpb'] == 'LKI')
						$condWhere .= " AND UPPER(" . $campo . ") LIKE UPPER('%" . $dtFlt['val'] . "') ";
					if($dtFlt['tpb'] == 'LKF')
						$condWhere .= " AND UPPER(" . $campo . ") LIKE UPPER('" . $dtFlt['val'] . "%') ";
				} else 
					$condWhere .= " AND UPPER(" . $campo . ") LIKE UPPER('%" . $dtFlt['val'] . "%') ";
			} else if($dtFlt['typ'] == 'int') {
				if(isset($dtFlt['tpb']) && $dtFlt['tpb'] != NULL) {
					if($dtFlt['tpb'] == 'MYQ')
						$condWhere .= " AND " . $campo . " > " . $dtFlt['val'] . " ";
					else if($dtFlt['tpb'] == 'MNQ')
						$condWhere .= " AND " . $campo . " < " . $dtFlt['val'] . " ";
					else if($dtFlt['tpb'] == 'MYI')
						$condWhere .= " AND " . $campo . " >= " . $dtFlt['val'] . " ";
					else if($dtFlt['tpb'] == 'MNI')
						$condWhere .= " AND " . $campo . " <= " . $dtFlt['val'] . " ";
				} else
					$condWhere .= " AND " . $campo . " = " . $dtFlt['val'] . " ";
				
			} else if($dtFlt['typ'] == 'dat') {
				$condWhere .= " AND DATE_FORMAT(" . $campo . ", '%d/%m/%Y') = '" . $dtFlt['val'] . "' ";
				/*if(isset($dtFlt['tpb']) && $dtFlt['tpb'] != NULL) {
					if($dtFlt['tpb'] == 'MYQ')
						$condWhere .= " AND " . $campo . " > " . $dtFlt['val'] . " ";
					else if($dtFlt['tpb'] == 'MNQ')
						$condWhere .= " AND " . $campo . " < " . $dtFlt['val'] . " ";
					else if($dtFlt['tpb'] == 'MYI')
						$condWhere .= " AND " . $campo . " >= " . $dtFlt['val'] . " ";
					else if($dtFlt['tpb'] == 'MNI')
						$condWhere .= " AND " . $campo . " <= " . $dtFlt['val'] . " ";
				}*/
			}
		}
	}
	return $condWhere;
}

/* End of file filtro_helper.php */