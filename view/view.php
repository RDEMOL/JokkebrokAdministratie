<?php
require_once (dirname(__FILE__) . "/page.php");
require_once (dirname(__FILE__) . "/dashboard/dashboard.php");
require_once (dirname(__FILE__) . "/aanwezigheden/aanwezigheden.php");
require_once (dirname(__FILE__) . "/kinderen/kinderen.php");
require_once (dirname(__FILE__) . "/uitstappen/uitstappen.php");
require_once (dirname(__FILE__) . "/instellingen/instellingen.php");
require_once (dirname(__FILE__) . "/../model/kinderen/kinderen.php");
require_once (dirname(__FILE__) . "/../model/aanwezigheden/aanwezigheden.php");

class View {
    protected $controller,$model;
    public function __construct($controller,$model) {
        $this->model = $model;
        $this->controller = $controller;
    }

    public function output() {
        if($this->model->getSession()->getLoggedIn()) {
            if(isset($_GET['action']) && $_GET['action'] == 'data') {
                if(!isset($_GET['data']))
                    return;
                switch($_GET['data']){
                    case 'kinderenTabel':
                        $kinderen_model = new Kinderen();
                        $filter = null;
                        if(isset($_POST['filter'])){
                            $filter = $_POST['filter'];
                        }
                        $result = array();
                        $result['content'] = $kinderen_model->getTabelJSONData($filter);
                        echo json_encode($result);
                        break;
                    case 'aanwezighedenTabel':
                        $aanwezigheden_model = new Aanwezigheden();
                        $filter = null;
                        if(isset($_POST['filter'])){
                            $filter = $_POST['filter'];
                        }
                        $result = array();
                        $result['content'] = $aanwezigheden_model->getTabelJSONData($filter);
                        echo json_encode($result);
                        break;
                }
            } else {
                $page = "dashboard";
                if(isset($_GET['page'])) {
                    $page = $_GET['page'];
                }
                $p = NULL;
                switch($page) {
                    case "dashboard":
                        $p = new DashboardPage();
                        break;
                    case "aanwezigheden":
                        $p = new AanwezighedenPage();
                        break;
                    case "kinderen":
                        $p = new KinderenPage();
                        break;
                    case "instellingen":
                        $p = new InstellingenPage();
                        break;
                    case "uitstappen":
                        $p = new UitstappenPage();
                        break;
                    default:
                        $p = new Page("Not found","Page not found!","");
                        break;
                }
                $p->output();
            }
        } else {
            require (dirname(__FILE__) . "/../public_html/login.php");
        }
    }

}
?>