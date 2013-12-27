<?php
class View{
	protected $controller, $model;
	public function __construct($controller, $model){
		$this->model = $model;
		$this->controller = $controller;
	}
	
	public function output(){
		require(dirname(FILE)."/../public_html/template.php");
	}
}
?>