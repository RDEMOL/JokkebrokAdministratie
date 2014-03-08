<?php
require_once(dirname(__FILE__)."/../record.class.php" );
class Uitstap extends Record{
    protected function setLocalData($data){
        $this->KindId = $data->Kind;
        $this->UitstapId = $data->Uitstap;
    }
    public function getJSONData(){
        $db = new Database();
        $query = $db->getPDO()->prepare("SELECT * FROM UitstapKind WHERE Id= :id ");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);        
    }
     protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO UitstapKind (Datum, Omschrijving, Actief) VALUES (:datum, :omschrijving, :actief)");
        $query->bindParam(':uitstap_id', $this->UitstapId, PDO::PARAM_INT);
        $query->bindParam(':kind_id', $this->KindId, PDO::PARAM_INT);
        $query->execute();
        return Database::getPDO()->lastInsertId();
     }
     protected function update(){
        $query = Database::getPDO()->prepare('UPDATE UitstapKind SET Datum=:datum, Omschrijving=:omschrijving, Actief=:actief WHERE Id=:id');
        $query->bindParam(':uitstap_id', $this->UitstapId, PDO::PARAM_STR);
        $query->bindParam(':kind_id', $this->KindId, PDO::PARAM_INT);
        $res = $query->execute();
        return $res;
     }
     protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM UitstapKind WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
     }
     protected function delete(){
        $query = Database::getPDO()->prepare("DELETE FROM UitstapKind WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
     }
}
?>