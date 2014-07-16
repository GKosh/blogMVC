<?php
class articlecontroller extends controller{

	public function __construct(){
	
	$this->model = $this->setmodel($this->controller);	

	}
	
	protected function beforeFilter(){
	}
	
	public function index(){
	
	$params= func_get_args();


	if ((isset($params[0][0]))&&($params[0][0]!=null)) {
		$this->model->getarticle($params[0][0]);	
	} else $this->model->getarticle(0);
	
	
	$this->view = $this->setview();
	$this->view->dispatch();
 	
	}
	
	public function format(){
	}

}