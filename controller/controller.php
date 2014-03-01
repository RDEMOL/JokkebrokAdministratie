<?php
require_once(dirname(__FILE__)."/../model/kinderen/kind.class.php");
class Controller {
    protected $model;
    public function __construct($model) {
        $this->model = $model;
    }

    public function execute() {
        if(!isset($_GET['action'])) {
            return;
        }
        $action = $_GET['action'];
        if(!$this->model->getSession()->getLoggedIn()){
            switch($action) {
                case 'login':
                    if($this->model->getSession()->login($_POST)){
                        $this->reloadPage();
                    }
                    break;
                case 'logout':
                    $this->model->getSession()->logout();
                    $this->reloadPage();
                    break;
                default:
                    return;
            }
        }else{
            switch($action) {
                case 'login':
                    if($this->model->getSession()->login($_POST)){
                        $this->reloadPage();
                    }
                    break;
                case 'logout':
                    $this->model->getSession()->logout();
                    $this->reloadPage();
                    break;
                case 'updateKind':
                    //$data = json_decode(json_encode($_POST), FALSE);
                    $data = $_POST;
                    $this->updateKind($data);
                    exit;
                case 'removeKind':
                    $k = new Kind($_POST['Id']);
                    echo $k->deleteFromDatabase();
                    exit;
                case 'newAanwezigheid':
                    $a = new Aanwezigheid($_POST);
                    echo $k->updateDatabase();
                    exit;
                case 'removeAanwezigheid':
                    $a = new Aanwezigheid($_POST['Id']);
                    echo $a->deleteFromDatabase();
                    exit;
                default:
                    return;
            }
        }
    }

    //Don't shoot the coder for putting this in Controller :(
    private function reloadPage() {
        header('Location: index.php');
        exit ;
    }
    private function updateKind($data){
        $voogd_amount = $data['voogd_amount'];
        $voogden = array();
        $voogd_ids = array();
        for($i = 0; $i < $voogd_amount; ++$i){
            $voogd_data = new stdClass();
            $voogd_data->Id = $data['voogdId'.$i];
            $voogd_data->Voornaam = $data['voogdVoornaam'.$i];
            $voogd_data->Naam = $data['voogdNaam'.$i];
            $voogd_data->Opmerkingen = $data['voogdOpmerkingen'.$i];
            $v = new Voogd($voogd_data);
            $voogden[] = $v;
            $v->updateDatabase();
            $voogd_ids[] = $v->getId();
        }
        $stripped_data = new stdClass();
        $stripped_data->Id = $data['Id'];
        $stripped_data->Voornaam = $data['Voornaam'];
        $stripped_data->Naam = $data['Naam'];
        $stripped_data->Geboortejaar = $data['Geboortejaar'];
        $stripped_data->DefaultWerkingId = $data['DefaultWerkingId'];
        $stripped_data->Belangrijk = $data['Belangrijk'];
        $k = new Kind($stripped_data);
        $res = $k->updateDatabase();
        $res2 = $k->setVoogden($voogd_ids);
        echo "1";
    }

}
?>