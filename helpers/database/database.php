<?php
require_once(dirname(__FILE__)."/../../config/config.inc.php");
class Database{
    private $pdo;
    public function __construct(){
        $this->initConnection();
    }
    private function initConnection(){
        try{
            $this->pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=UTF8', DB_USER, DB_PASS);
        }catch(PDOException $ex){
            echo "message: ".$ex->getMessage();
            echo "Error! Kan geen verbinding maken met de database";
            die();
        }
    }
    public function getPDO(){
        return $this->pdo;
    }
}
?>