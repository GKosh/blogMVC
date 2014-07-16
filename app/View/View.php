<?php
class view
{
		public $data = array();
		public $model;
		
		public function __construct(){

		}
		
		public function setLayout($layout = null){
	   
		if ($layout==null)$layout="default";
		$path=view_PATH . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . $layout .".php";
		if (file_exists($path)){
			require_once $path;
		}else echo "No layout avalible";
	}
	
}