<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class TablePaging {
		
	var $linkPrimero = 'Primero';
	var $linkUltimo = 'Ultimo';
	var $numLinksNum = 8;
	
	public function getTablaPaginada($urlLink, $arrData, $rowsPerPage, $currentRow = 0) {
		$obj =& get_instance();
		$nvoArrData = array();
		$tmpArr = $arrData->result_array();
		//Inicializamos libreria de paginacion y parametros base
		$obj->load->library('pagination');
		$config['base_url'] = $obj->config->item('base_url').$urlLink;
		$config['total_rows'] = $arrData->num_rows();
		$config['per_page'] = $rowsPerPage;
		$config['first_link'] = $this->linkPrimero;
		$config['last_link'] = $this->linkUltimo;
		$config['num_links'] = $this->numLinksNum / 2;
		$obj->pagination->initialize($config);
		$data['links'] = $obj->pagination->create_links();
		$data['fromRow'] = $currentRow;
		if($currentRow === 0)
			$data['toRow'] = $rowsPerPage;
		else
			$data['toRow'] = $currentRow + $rowsPerPage;
		
		if($data['toRow'] > $config['total_rows'])
			$data['toRow'] = $config['total_rows'];
		//Extraemos de los registros unicamente las filas que hay que mostrar en pantalla
		for($cont = $data['fromRow']; $cont < $data['toRow']; $cont++) {
			$nvoArrData[] = $tmpArr[$cont];
		}
		$data['arrData'] = $nvoArrData;
		return $data;
	}
	
	
}
/* End of file rendpaginacion.php */