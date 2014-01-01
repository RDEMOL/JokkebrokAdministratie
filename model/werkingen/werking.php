<?php
require_once(dirname(__FILE__)."/../../helpers/database/database.php");
class Werking{
    private $id, $naam;
    public function __construct($id){
        $this->id = $id;
        $this->init();
    }
    private function init(){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT Omschrijving as naam, Afkorting as afkorting FROM Werking WHERE Id = :id');
        $query->bindParam(':id', $this->getId(), PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        $this->naam = $result->naam;
        $this->afkorting = $result->afkorting;
    }
    public function getNaam(){
        return $this->naam;
    }
    public function getAfkorting(){
        return $this->afkorting;
    }
    public function getId(){
        return $this->id;
    }
    public function getKinderenAmountOnDay($speelpleindag){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT Count(*) aantal FROM Aanwezigheid WHERE Werking = :werking AND Datum = :datum');
        $query->bindParam(':werking', $this->getId());
        $query->bindParam(':datum', $speelpleindag->getDatum());
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->aantal;
    }
}
?>