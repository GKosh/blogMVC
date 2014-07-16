

<?php
class article extends view{

	public $data = array();
	
	public function __construct(){
	

	}
	
	public function dispatch(){
	$this->model->data['Menu']="<ul>";
	foreach ($this->model->data['articlesList'] as $k=>$article){
	 $this->model->data['Menu'].='<li><a href="'. PAGE_URL . $k.'">' .$article['name'].'</a>';
	 }
	$this->model->data['Menu'].="</ul>";
	
	$this->setLayout();
	}	
	
	
}



