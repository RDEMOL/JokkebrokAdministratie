<?php
require_once (dirname(__FILE__)."/../record.php");
class Vordering extends Record{
	protected function setLocalData($data){
        $this->AanwezigheidId = $data->Aanwezigheid;
        $this->Opmerking = $data->Opmerking;
        $this->Bedrag = $data->Bedrag;
    }
	protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Vordering (Aanwezigheid, Opmerking, Bedrag) VALUES (:aanwezigheid_id, :opmerking, :bedrag)");
        $query->bindParam(':aanwezigheid_id', $this->AanwezigheidId, PDO::PARAM_INT);
        $query->bindParam(':opmerking', $this->Opmerking, PDO::PARAM_STR);
        $query->bindParam(':bedrag', $this->Bedrag, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }
    protected function update(){
        $query = Database::getPDO()->prepare("UPDATE Vordering SET Aanwezigheid=:aanwezigheid_id, Opmerking=:opmerking, bedrag=:bedrag WHERE Id=:id");
        $query->bindParam(':aanwezigheid_id', $this->AanwezigheidId, PDO::PARAM_INT);
        $query->bindParam(':opmerking', $this->Opmerking, PDO::PARAM_STR);
        $query->bindParam(':bedrag', $this->Bedrag, PDO::PARAM_STR);
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Vordering WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
        $query = Database::getPDO()->prepare("DELETE FROM Vordering WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }
}
?>