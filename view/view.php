<?php
require_once(dirname(__FILE__)."/page.php");
require_once(dirname(__FILE__)."/dashboard/dashboard.php");
require_once(dirname(__FILE__)."/aanwezigheden/aanwezigheden.php");
require_once(dirname(__FILE__)."/kinderen/kinderen.php");
require_once(dirname(__FILE__)."/uitstappen/uitstappen.php");
require_once(dirname(__FILE__)."/instellingen/instellingen.php");
class View{
	protected $controller, $model;
	public function __construct($controller, $model){
		$this->model = $model;
		$this->controller = $controller;
	}
	
	public function output(){
		if($this->model->getSession()->getLoggedIn()){
		    $page = "dashboard";
		    if(isset($_GET['page'])){
		        $page = $_GET['page'];
		    }
            $p = NULL;
            switch($page){
                case "dashboard":
                    $p = new Dashboard();
                    break;
                case "aanwezigheden":
                    $p = new Aanwezigheden();
                    break;
                case "kinderen":
                    $p = new Kinderen();
                    break;
                case "instellingen":
                    $p = new Instellingen();
                    break;
                case "uitstappen":
                    $p = new Uitstappen();
                    break;
                default:
                    $p = new Page("Not found", "Page not found!", "");
                    break;
            }
            $p->output();	
		}else{
			require(dirname(__FILE__)."/../public_html/login.php");
		}
	}
}
?>