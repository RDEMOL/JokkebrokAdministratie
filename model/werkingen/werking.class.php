<?php
require_once(dirname(__FILE__)."/../record.class.php");
class Werking extends Record{
    protected function setLocalData($data){
        $this->Omschrijving = $data->Omschrijving;
        $this->Afkorting = $data->Afkorting;
    }
    protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Werking (Omschrijving, Afkorting) VALUES (:omschrijving, :afkorting)");
        $query->bindParam(':omschrijving', $this->Omschrijving, PDO::PARAM_STR);
        $query->bindParam(':afkorting', $this->Afkorting, PDO::PARAM_STR);
        $query->execute();
        return $query->lastInsertId();
    }
    protected function update(){
        
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Werking WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
        
    }
    public static function getWerkingen(){
        $query = Database::getPDO()->prepare("SELECT * FROM Werking WHERE 1");
        $query->execute();
        $werkingen = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $werkingen[] = new Werking($rs, true);
        }
        return $werkingen;
    }
    public function getOmschrijving(){
        return $this->Omschrijving;
    }
    public function getAfkorting(){
        return $this->Afkorting;
    }
}
?>