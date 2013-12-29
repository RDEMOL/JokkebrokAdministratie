<?php
require_once(dirname(__FILE__)."/../../helpers/database/database.php");
class Extraatje{
    private $id, $naam;
    public function __construct($id){
        $this->id = $id;
        $this->init();
    }
    private function init(){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT Omschrijving as naam FROM Extraatje WHERE Id = :id');
        $query->bindParam(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        $this->naam = $result->naam;
    }
    public function getNaam(){
        return $this->naam;
    }
    public function getId(){
        return $this->id;
    }
    public function getAmountOnDay($speelpleindag){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT COUNT(ea.Id) as aantal FROM ExtraatjeAanwezigheid ea LEFT JOIN Aanwezigheid a ON ea.Aanwezigheid = a.Id WHERE a.Datum = :datum');
        $query->bindParam(':datum', $speelpleindag->getDatum(), PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->aantal;
    }
    public function getAmountOnDayWithWerking($speelpleindag, $werking){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT COUNT(ea.Id) as aantal FROM ExtraatjeAanwezigheid ea LEFT JOIN Aanwezigheid a ON ea.Aanwezigheid = a.Id WHERE a.Datum = :datum AND a.Werking = :werking');
        $query->bindParam(':datum', $speelpleindag->getDatum(), PDO::PARAM_STR);
        $query->bindParam(':werking', $werking->getId(), PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->aantal;
    }
}
?>