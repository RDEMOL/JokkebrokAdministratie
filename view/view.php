<?php
require_once (dirname(__FILE__) . "/page.php");
require_once (dirname(__FILE__) . "/dashboard/dashboard.page.php");
require_once (dirname(__FILE__) . "/aanwezigheden/aanwezigheden.page.php");
require_once (dirname(__FILE__) . "/kinderen/kinderen.page.php");
require_once (dirname(__FILE__) . "/uitstappen/uitstappen.page.php");
require_once (dirname(__FILE__) . "/instellingen/instellingen.page.php");
require_once (dirname(__FILE__) . "/../model/kinderen/kind.class.php");
require_once (dirname(__FILE__) . "/../model/voogden/voogd.class.php");
require_once (dirname(__FILE__) . "/../model/aanwezigheden/aanwezigheid.class.php");
require_once (dirname(__FILE__) . "/../helpers/log.php");

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
                        $filter = null;
                        if(isset($_POST['filter'])){
                            $filter = $_POST['filter'];
                        }
                        $result = array();
                        $result['content']=array();
                        $kinderen = Kind::getKinderen($filter);
                        foreach($kinderen as $k){
                            $result['content'][]=$k->getJSONData();
                        }
                        echo json_encode($result);
                        break;
                    case 'kinderenSuggesties':
                        $query = $_GET['query'];
                        $result = array();
                        $result['content']=array();
                        $filter = array();
                        $filter['VolledigeNaam'] = $query;
                        $kinderen = Kind::getKinderen($filter, 10);
                        foreach($kinderen as $k){
                            $result['content'][] = array('Id'=>$k->getId(), 'Naam'=>$k->getNaam(), 'Voornaam'=>$k->getVoornaam());
                        }
                        Log::writeLog("kinderen suggesties query = ", $query);
                        Log::writeLog("kinderensuggesties", json_encode($result));
                        echo json_encode($result);
                        break;
                    case 'aanwezighedenTabel':
                        $filter = null;
                        if(isset($_POST['filter'])){
                            $filter = $_POST['filter'];
                        }
                        $result = array();
                        $result['content'] = array();
                        $aanwezigheden = Aanwezigheid::getAanwezigheden($filter);
                        foreach($aanwezigheden as $a){
                            $result['content'][] = $a->getJSONData();
                        }
                        echo json_encode($result);
                        break;
                    case 'voogdInfo':
                        $voogd = new Voogd($_GET['id']);
                        echo json_encode($voogd->getJSONData());
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