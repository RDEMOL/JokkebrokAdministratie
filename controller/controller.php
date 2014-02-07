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
                    $data = json_decode(json_encode($_POST), FALSE);
                    $k = new Kind($data);
                    echo $k->updateDatabase();
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

}
?>