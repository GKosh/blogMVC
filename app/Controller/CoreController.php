<?php

class corecontroller extends controller
{
	
	public function __construct(array $options=array()) {
	
		require_once MDL_PATH . 'model.php';
		require_once view_PATH . 'view.php';
		
		if (empty($options)) {
	
		$this->parseURI();
		
		}
		else{
			if (isset($options["controller"])){
				$this->setcontroller($options["controller"]);
			}
			if (isset($options["action"])){
				$this->setAction($options["controller"]);
			}
			if (isset($options["controller"])){
				$this->setParams($options["params"]);
			}
			
		}
	}
	
	public function parseURI(){

	$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),"/");
	$path = preg_replace('[^a-zA-Z0-9]', "", $path);
	 if (strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
        }
	@list($controllerURI, $actionURI, $paramsURI)=explode("/", $path, 3);
        if ((isset($controllerURI))&&(($controllerURI!=""))) {
			$requestedCNTRL=$this->setcontroller($controllerURI);
        }else $this->setcontroller($this->controller);
        if (isset($actionURI)) {
            $requestedACTN=$this->setAction($actionURI);
        }
        if ((isset($paramsURI))&&($requestedCNTRL!=false)&&($requestedACTN!=false)) {

            $this->setParams(explode("/", $paramsURI));
        } else if ((isset($paramsURI))||(isset($actionURI))&&($requestedCNTRL!=false)){
			$paramsURI=$actionURI . "/" . $paramsURI ;
			 $this->setParams(explode("/", $paramsURI));
		}else $this->setParams(explode("/",$path));
	}
	
	public function setcontroller($controller) {

		$controller = ucfirst(strtolower($controller)) . "controller";
		$controllerPath =CNTRL_PATH .  $controller . ".php";
	
		if (file_exists ($controllerPath)){
	
			include_once $controllerPath;
			if (!class_exists($controller)) {
//			echo "<br>controller is not defined!";
			return false; 	
			}else{
			$this->$controller = $controller;
			return true;
		}}else{
			$controller = $this->controller;
			$controllerPath =CNTRL_PATH .  $controller . ".php";
			
			if (file_exists ($controllerPath)){
				require_once $controllerPath;
				if (!class_exists($controller)) {
//				echo "<br>controller is not defined!";
				return false;
				}else{
				$this->$controller = $controller;
					}		
				}else //echo "<br> controller is not exist!";
			return false;
			}
    }
	
	public function setAction($action) {

        $reflector = new ReflectionClass($this->controller);
        if (!$reflector->hasMethod($action)) {
//		    echo "Action is not defined!";
		 
			return false;
			}else{ 
        $this->action = $action;
		return true;
     	}
    }
     
    public function setParams(array $params) {
	   if (isset($params)){
	   $this->params = $params;
	    return $this;
		} 
    }
    
	protected function beforeFilter(){}
	
    public function run() {
	
        call_user_func(array(new $this->controller, $this->action),$this->params);
    }
}

