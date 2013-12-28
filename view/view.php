<?php
class View{
	protected $controller, $model;
	public function __construct($controller, $model){
		$this->model = $model;
		$this->controller = $controller;
	}
	
	public function output(){
		if($this->model->getSession()->getLoggedIn()){
			require(dirname(__FILE__)."/../public_html/template.php");	
		}else{
			require(dirname(__FILE__)."/../public_html/login.php");
		}
	}
}
?>