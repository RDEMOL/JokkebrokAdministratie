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
require_once (dirname(__FILE__) . "/../model/uitstappen/uitstap.class.php");
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
						$order = array();
						if(isset($_POST['order'])){
							$order = $_POST['order'];
						}
                        $result = array();
                        $result['content']=array();
                        $kinderen = Kind::getKinderen($filter, 0, $order);
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
                            $voogden = $k->getKindVoogden();
                            $kid = $k->getId();
                            $voogden_namen_ids = array();
                            foreach($voogden as $v){
                                $voogd = $v->getVoogd();
                                $voogden_namen_ids[] = array('KindVoogdId'=>$v->getId(), 'VolledigeNaam'=>($voogd->getVoornaam()." ".$voogd->getNaam()));
                            }
                            $result['content'][] = array('Id'=>$k->getId(), 'Naam'=>$k->getNaam(), 'Voornaam'=>$k->getVoornaam(), 'DefaultWerkingId'=>$k->getDefaultWerkingId(), 'Voogden'=>$voogden_namen_ids);
                        }
                        echo json_encode($result);
                        break;
					case 'uitstapDetails':
						$u = new Uitstap($_POST['Id']);
						$result['content'] = $u->getJSONData();
						echo json_encode($result);
						break;
                    case 'uitstappenTabel':
                        $result = array();
                        $result['content']=array();
                        $filter = null;
                        $uitstappen = Uitstap::getUitstappen($filter);
                        foreach($uitstappen as $u){
                            $result['content'][]=$u->getJSONData();
                        }
                        echo json_encode($result);
                        break;
                    case 'uitstapDeelnamesTabel':
                        $id = $_GET['uitstap_id'];
                        $uitstap = new Uitstap($id);
                        $deelnemers = $uitstap->getDeelnemers();
                        $result = array();
                        $result['content']=array();
                        foreach($deelnemers as $d){
                            $result['content'][] = $d->getJSONData();
                        }
                        echo json_encode($result);
                        break;
                    case 'aanwezighedenTabel':
                        $filter = null;
                        if(isset($_POST['filter'])){
                            $filter = $_POST['filter'];
                        }
						$order = array();
						if(isset($_POST['order'])){
							$order = $_POST['order'];
						}
                        $result = array();
                        $result['content'] = array();
                        $aanwezigheden = Aanwezigheid::getAanwezigheden($filter, $order);
                        foreach($aanwezigheden as $a){
                            $result['content'][] = $a->getJSONData();
                        }
                        echo json_encode($result);
                        break;
                    case 'aanwezigheidDetails':
                        $id = $_GET['id'];
                        $aanwezigheid = new Aanwezigheid($id);
                        $kindvoogd = $aanwezigheid->getKindVoogd();
                        $kind = $kindvoogd->getKind();
                        $voogden = $kind->getKindVoogden();
                        $result = array();
                        $result['Id']=$aanwezigheid->getId();                        
                        $result['KindId']=$kind->getId();
                        $result['Geboortejaar']=$kind->getGeboortejaar();
                        $result['KindVolledigeNaam']=$kind->getVoornaam()." ".$kind->getNaam();
                        $result['Opmerkingen'] = $aanwezigheid->getOpmerkingen();
                        $result['Datum']=$aanwezigheid->getDatum();
                        $result['KindVoogdId']=$kindvoogd->getId();
                        $result['KindVoogden']=array();
                        foreach($voogden as $v){
                            $voogd = $v->getVoogd();
                            $result['KindVoogden'][] = array('Id'=>$v->getId(), 'VolledigeNaam'=>($voogd->getVoornaam()." ".$voogd->getNaam()));
                        }
                        $result['Werking']=$aanwezigheid->getWerkingId();
                        $result['Extraatjes']=array();
                        $extraatjes = $aanwezigheid->getExtraatjes();
                        foreach($extraatjes as $e){
                            $result['Extraatjes'][]=$e->getId();
                        }
                        echo json_encode($result);
                        break;
                    case 'extraatjesTabel':
                        $extraatjes = Extraatje::getExtraatjes();
                        $result = array();
                        $result['content']=array();
                        foreach($extraatjes as $e){
                            $result['content'][]=array('Id'=>$e->getId(), 'Omschrijving'=>$e->getOmschrijving());
                        }
                        echo json_encode($result);
                        Log::writeLog("result encoded ",json_encode($result));
                        break;
                    case 'voogdInfo':
                        $kvoogd = new KindVoogd($_GET['kind_voogd_id']);
                        $voogd = $kvoogd->getVoogd();
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