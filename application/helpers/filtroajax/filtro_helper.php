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
							'det' =>  (isset($arrKey[$llave]['det'])?$arrKey[$llave]['det']:'AND'), 
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
	$primerDet = false;
	foreach(array_keys($arrFlt) as $campo) {
		$dtFlt = $arrFlt[$campo];
		$determi = (!isset($dtFlt['det']) || $dtFlt['det'] == NULL)?' AND ':' ' . $dtFlt['det'] . ' ';
		if(isset($dtFlt['val']) && $dtFlt['val'] != NULL && trim($dtFlt['val']) != '' ) {
			if($dtFlt['typ'] == 'str') {
				if(isset($dtFlt['tpb']) && $dtFlt['tpb'] != NULL) {
					if($dtFlt['tpb'] == 'EXA')
						$condWhere .= ($primerDet?$determi:'') . $campo . " = '" . $dtFlt['val'] . "' ";
					if($dtFlt['tpb'] == 'LKI')
						$condWhere .= ($primerDet?$determi:'') . " UPPER(" . $campo . ") LIKE UPPER('%" . $dtFlt['val'] . "') ";
					if($dtFlt['tpb'] == 'LKF')
						$condWhere .= ($primerDet?$determi:'') . " UPPER(" . $campo . ") LIKE UPPER('" . $dtFlt['val'] . "%') ";
				} else 
					$condWhere .= ($primerDet?$determi:'') . " UPPER(" . $campo . ") LIKE UPPER('%" . $dtFlt['val'] . "%') ";
			} else if($dtFlt['typ'] == 'int') {
				if(isset($dtFlt['tpb']) && $dtFlt['tpb'] != NULL) {
					if($dtFlt['tpb'] == 'MYQ')
						$condWhere .= ($primerDet?$determi:'') . $campo . " > " . $dtFlt['val'] . " ";
					else if($dtFlt['tpb'] == 'MNQ')
						$condWhere .= ($primerDet?$determi:'') . $campo . " < " . $dtFlt['val'] . " ";
					else if($dtFlt['tpb'] == 'MYI')
						$condWhere .= ($primerDet?$determi:'') . $campo . " >= " . $dtFlt['val'] . " ";
					else if($dtFlt['tpb'] == 'MNI')
						$condWhere .= ($primerDet?$determi:'') . $campo . " <= " . $dtFlt['val'] . " ";
				} else
					$condWhere .= ($primerDet?$determi:'') . $campo . " = " . $dtFlt['val'] . " ";
				
			} else if($dtFlt['typ'] == 'dat') {
				$condWhere .= ($primerDet?$determi:'') . " DATE_FORMAT(" . $campo . ", '%d/%m/%Y') = '" . $dtFlt['val'] . "' ";
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
		$primerDet = true;
	}

	if(isset($condWhere) && $condWhere != NULL && $condWhere != '')
		$condWhere = ' AND (' . $condWhere . ') ';
	return $condWhere;
}

/* End of file filtro_helper.php */