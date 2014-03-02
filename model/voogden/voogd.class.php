<?php
require_once(dirname(__FILE__)."/../record.class.php" );
    class Voogd extends Record{
    protected function setLocalData($data){
        $this->Voornaam = $data->Voornaam;
        $this->Naam = $data->Naam;
        $this->Opmerkingen = $data->Opmerkingen;
    }
    public function getVoornaam(){
        return $this->Voornaam;
    }
    public function getNaam(){
        return $this->Naam;
    }
    public function getJSONData(){
        $db = new Database();
        $query = $db->getPDO()->prepare("SELECT * FROM Voogd WHERE Id= :id ");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);        
    }
     protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Voogd (Voornaam, Naam, Opmerkingen) VALUES (:voornaam, :naam, :opmerkingen)");
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':opmerkingen', $this->Opmerkingen, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
     }
     protected function update(){
        $query = Database::getPDO()->prepare('UPDATE Voogd SET Naam=:naam, Voornaam=:voornaam, Opmerkingen=:opmerkingen WHERE Id=:id');
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':opmerkingen', $this->Opmerkingen, PDO::PARAM_STR);
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $res = $query->execute();
        return $res;
     }
     protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Voogd WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
     }
     protected function delete(){
        $query = Database::getPDO()->prepare("DELETE FROM Voogd WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
     }
}
?>