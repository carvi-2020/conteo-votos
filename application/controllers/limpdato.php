<?php 
class Limpdato extends MY_Controller {
		
	function __construct() {
		parent::__construct();
		$this->load->model('limpdato_model');
	}
	
	function loadLimp() {
		$data['accion'] = '';	
		$this->load->view('votacion/limpdatos/master', $data);
	}
	
	function limpDatos() {
		$data['accion'] = '';	
		$res = NULL;
		
		if(isset($_POST['accion'])){
			$data['accion'] = $_POST['accion'];			
			
			if( $data['accion'] == 'VOTABS' ){
				//Borrar votos abstenidos
				$res = $this->limpdato_model->delVotsAbst();
			}else{
				if( $data['accion'] == 'VOTNULL' ){
					//Borrar votos nulos
					$res = $this->limpdato_model->delVotsNulos();
				}else{
					if( $data['accion'] == 'VOTPNLLS' ){
						//Borrar votos normales o de planilla
						$res = $this->limpdato_model->delVotsNmls();
					}else{
						if( $data['accion'] == 'NOVOTS' ){
							
						}else{
							if( $data['accion'] == 'PADELEC' ){
								//Borrar padron electoral
								$res = $this->limpdato_model->delPdronElec();		
							}else{
								if( $data['accion'] == 'CAND' ){
									//Borrar candidatos
									$res = $this->limpdato_model->delCandidatos();	
								}else{
									if( $data['accion'] == 'PNLLS' ){
										$res = $this->limpdato_model->delPlanillas();	
									}else{
										if( $data['accion'] == 'CNTRSVOTS' ){
											$res = $this->limpdato_model->delCentroVot();	
										}else{
											if( $data['accion'] == 'CARPOS' ){
												$res = $this->limpdato_model->delCargosPos();
											}	
										}
									}
								}
							}
						}
					}
				}
			}

			if( $res == TRUE ){
				$data['msgMtto'] = 'La accion se realizo correctamente';
				$data['msgType'] = 'MSG';
			}else{
				$data['msgMtto'] = 'Ocurrio un error y la accion no pudo realizarce';
				$data['msgType'] = 'ERR';
			}
		}
		$this->load->view('votacion/limpdatos/master', $data);
	}
}

/* End of file planilla.php */
/* Location: application/controllers/ */
