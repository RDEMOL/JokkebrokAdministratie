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
                    $data = $_POST;
                    $this->updateKind($data);
                    exit;
                case 'removeKind':
                    $k = new Kind($_POST['Id']);
					$res = new stdClass();
                    $res->Ok = $k->deleteFromDatabase();
					echo json_encode($res);
                    exit;
                case 'updateAanwezigheid':
                    $data = $_POST;
                    $this->updateAanwezigheid($data);
                    exit;
                case 'removeAanwezigheid':
                    $a = new Aanwezigheid($_POST['Id']);
					$res = new stdClass();
                    $res->Ok = $a->deleteFromDatabase();
					echo json_encode($res);
                    exit;
                case 'updateExtraatje':
                    $data = new stdClass();
                    $data->Id = $_POST['Id'];
                    $data->Omschrijving = $_POST['Omschrijving'];
                    $e = new Extraatje($data);
                    $e->updateDatabase();
                    echo "1";
                    exit;
                case 'removeExtraatje':
                    $extraatje = new Extraatje($_REQUEST['Id']);
                    $res = new stdClass();
                    $res->Ok = $extraatje->deleteFromDatabase();
                    echo json_encode($res);
                    exit;
				case 'updateWerking':
					$data = new stdClass();
					$data->Id = $_POST['Id'];
					$data->Omschrijving = $_POST['Omschrijving'];
					$data->Afkorting = $_POST['Afkorting'];
					$werking = new Werking($data);
					$werking->updateDatabase();
					echo "1";
					exit;
				case 'removeWerking':
					$werking = new Werking($_REQUEST['Id']);
					$res = new stdClass();
					$res->Ok = $werking->deleteFromDatabase();
					echo json_encode($res);
					exit;
                case 'updateUitstap':
                    $data = new stdClass();
                    $data->Id = $_POST['Id'];
                    $data->Omschrijving = $_POST['Omschrijving'];
                    $data->Datum = $_POST['Datum'];
                    $data->AanwezigheidZichtbaar = isset($_POST['AanwezigheidZichtbaar']);
                    $data->DashboardZichtbaar = isset($_POST['DashboardZichtbaar']);
					
                    $uitstap = new Uitstap($data);
                    $uitstap->updateDatabase();
					
                    $res = new stdClass();
					$res->Ok = 1;
					$res->Id = $uitstap->getId();
					echo json_encode($res);
                    exit;
				case 'removeUitstap':
					$uitstap = new Uitstap($_POST['Id']);
					$res = new stdClass();
					$res->Ok = $uitstap->deleteFromDatabase();
					echo json_encode($res);
					exit;
                case 'updateDeelname':
                    $data = new stdClass();
                    if(isset($_GET['Id'])){
                        $data->Id = $_GET['Id'];
                    }else{
                        $data->Id = 0;
                    }
                    $data->Uitstap = $_GET['UitstapId'];
                    $data->Kind = $_GET['KindId'];
                    $uitstap_kind = new UitstapKind($data);
                    $uitstap_kind->updateDatabase();
                    echo "1";
                    exit;
                case 'removeDeelname':
                    $id = $_GET['Id'];
                    $uitstap_kind = new UitstapKind($id);
                    $uitstap_kind->deleteFromDatabase();
                    echo "1";
                    exit;
				case 'updateVoogd':
					$data = new stdClass();
					if(isset($_REQUEST['Id'])){
						$data->Id = $_REQUEST['Id'];
					}else{
						$data->Id = 0;
					}
					$data->Naam = $_REQUEST['Naam'];
					$data->Voornaam = $_REQUEST['Voornaam'];
					$data->Opmerkingen = $_REQUEST['Opmerkingen'];
					$voogd = new Voogd($data);
					$voogd->updateDatabase();
					$return_data = new stdClass();
					$return_data->Id = $voogd->getId();
					echo json_encode($return_data);
					exit;
				case 'updateBetaling':
					$data = new stdClass();
					$data->Id = 0;
					if(isset($_REQUEST['Id'])){
						$data->Id = $_REQUEST['Id'];
					}
					$data->KindVoogd = $_REQUEST['KindVoogd'];
					$data->Bedrag = $_REQUEST['Bedrag'];
					$data->Opmerking = $_REQUEST['Opmerking'];
					$data->Datum = $_REQUEST['Datum'];
					$betaling = new Betaling($data);
					$betaling->updateDatabase();
					echo "1";
					exit;
				case 'removeBetaling':
					$id = $_REQUEST['Id'];
					$betaling = new Betaling($id);
					$betaling->deleteFromDatabase();
					echo "1";
					exit;
				case 'removeVordering':
					$id = $_REQUEST['Id'];
					$vordering = new Vordering($id);
					$vordering->deleteFromDatabase();
					echo "1";
					exit;
				case 'updateVordering':
					$data = new stdClass();
					if(isset($_REQUEST['Id'])){
						$data->Id = $_REQUEST['Id'];
					}else{
						$data->Id = 0;
					}
					$data->Bedrag = $_REQUEST['Bedrag'];
					$data->Opmerking = $_REQUEST['Opmerking'];
					$data->Aanwezigheid = $_REQUEST['Aanwezigheid'];
					$vordering = new Vordering($data);
					$vordering->updateDatabase();
					echo json_encode($vordering->getJSONData());
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
        $stripped_data = new stdClass();
        $stripped_data->Id = $data['Id'];
        $stripped_data->Voornaam = $data['Voornaam'];
        $stripped_data->Naam = $data['Naam'];
        $stripped_data->Geboortejaar = $data['Geboortejaar'];
        $stripped_data->DefaultWerking = $data['DefaultWerking'];
        $stripped_data->Belangrijk = $data['Belangrijk'];
        $k = new Kind($stripped_data);
        $res = $k->updateDatabase();
        $voogden = $data['VoogdIds'];
		$voogd_result = $k->setVoogdIds($voogden);
        if($res && $voogd_result){
        	echo "1";
        }else{
        	echo "0";
        }
    }
    private function updateAanwezigheid($data){
        $stripped_data = new stdClass();
        $stripped_data->Id = $data['Id'];
        $stripped_data->KindVoogd = $data['KindVoogd'];
        $stripped_data->Opmerkingen = $data['Opmerkingen'];
        $stripped_data->Datum = $data['Datum'];
        $stripped_data->Werking = $data['Werking'];
		$stripped_data->MiddagNaarHuis = $data['MiddagNaarHuis'];
        $a = new Aanwezigheid($stripped_data);
        $a->updateDatabase();
        $extraatjes = array();
        if(isset($data['Extraatjes'])){
            $extraatjes = $data['Extraatjes'];
        }
        $a->setExtraatjes($extraatjes);
		$uitstappen = array();
		if(isset($data['Uitstappen'])){
			$uitstappen = $data['Uitstappen'];
		}
		$a->getKindVoogd()->getKind()->applyUitstappen($uitstappen);
		$vorderingen = array();
		if(isset($data['Vorderingen'])){
			$vorderingen_data = $data['Vorderingen'];
			foreach($vorderingen_data as $v_data){
				$v = new stdClass();
				$v->Id = $v_data['Id'];
				$v->Bedrag = $v_data['Bedrag'];
				$v->Opmerking = $v_data['Opmerking'];
				$v->Aanwezigheid = $a->getId();
				$vordering = new Vordering($v);
				$vorderingen[] = $vordering;
			}
		}
		$a->setVorderingen($vorderingen);
        echo "1";
    }

}
?>