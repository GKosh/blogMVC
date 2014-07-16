<?php
 
class controller 
{

		
	const DEFAULT_CONTRLLOER = "articlecontroller";
	const DEFAULT_ACTION = "index";
	public $model;
	public $view;
	public $data = array();
	public $controller = self::DEFAULT_CONTRLLOER;
	public $action = self::DEFAULT_ACTION;
	public $basePath = "/";
	public $params = array();

	
	
	public function __construct(){
	
	}
	
	public function set ($data=null) {
		$type=gettype($data);
		if ((isset($this->view))&&($data!=null)&&($type=="array")){
			if ((isset($this->view->data))&&(is_array($this->view->data))){
				$this->view->data = array_merge($this->view->data,$data);
				} else if (isset($this->view->data)) {
				$this->view->data = array($this->view->data);
				$this->view->data = array_merge($this->view->data,$data);
			}
	
		}
	}
	
	
	public function setview(string $view =null ,array $data=null){
	 
		
		if ($view==null)$view = str_replace("controller","",get_class($this));
		if (file_exists(view_PATH . $view . ".php")){	 
			require_once view_PATH . $view . ".php";
			$view = new $view;
			$view->model = $this->model;
			return $view; 
		}else if (class_exists($view)){
			$view = str_replace("controller","",$view);
			require_once view_PATH . $view . ".php";
			$view = new $view;
			$view->model = $this->model;
			return $view;
		} else {
			logMessage ("view of  ".$view."not exist"); 
		}
	}
	
	public function setmodel($model = null){
	   
		if ($model==null)$model= str_replace("controller","model",get_class($this));
		if (file_exists(MDL_PATH . $model . ".php")){
			require_once MDL_PATH . $model . ".php";
			return new $model;
		}else if (class_exists($model)){
			$model = str_replace("controller","model",$model);
			require_once MDL_PATH . $model . ".php";
			return new $model;
		} else {
			logMessage ("model ".$model ."not exist"); 
		}
	}
}

