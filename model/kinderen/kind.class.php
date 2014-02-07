<?php
require_once(dirname(__FILE__)."/../record.class.php");
class Kind extends Record{
    protected function setLocalData($data){
        $this->Voornaam = $data->Voornaam;
        $this->Naam = $data->Naam;
        $this->Geboortejaar = $data->Geboortejaar;
        $this->DefaultWerkingId = $data->DefaultWerkingId;
        $this->MedischeInfo = $data->MedischeInfo;
        $this->AndereInfo = $data->AndereInfo;
    }
    protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Kind (Voornaam, Naam, Geboortejaar, DefaultWerkingId, MedischeInfo, AndereInfo) VALUES (:voornaam, :naam, :geboortejaar, :default_werking_id, :medische_info, :andere_info)");
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':geboortejaar', $this->Geboortejaar, PDO::PARAM_STR);
        $query->bindParam(':default_werking_id', $this->DefaultWerkingId, PDO::PARAM_INT);
        $query->bindParam(':medische_info', $this->MedischeInfo, PDO::PARAM_STR);
        $query->bindParam(':andere_info', $this->AndereInfo, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }
    protected function update(){
        $query = Database::getPDO()->prepare('UPDATE Kind SET Naam=:naam, Voornaam=:voornaam, Geboortejaar=:geboorte_jaar, DefaultWerkingId=:default_werking_id, MedischeInfo=:medische_info, AndereInfo=:andere_info WHERE Id=:id');
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':geboorte_jaar', $this->Geboortejaar, PDO::PARAM_STR);
        $query->bindParam(':default_werking_id', $this->DefaultWerkingId, PDO::PARAM_STR);
        $query->bindParam(':medische_info', $this->MedischeInfo, PDO::PARAM_STR);
        $query->bindParam(':andere_info', $this->AndereInfo, PDO::PARAM_STR);
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $res = $query->execute();
        return $res;
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Kind WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
        $query = Database::getPDO()->prepare("DELETE FROM Kind WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }
    public static function getKinderen($filter){
        //TODO: implement filter
        $query = Database::getPDO()->prepare("SELECT * FROM Kind WHERE 1");
        $query->execute();
        $kinderen = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $kinderen[] = new Kind($rs);
        }
        return $kinderen;
    }
    public function getJSONData(){
        $query = Database::getPDO()->prepare("SELECT K.Id as Id, K.Voornaam as Voornaam, K.Naam as Naam, K.Geboortejaar as Geboortejaar, K.MedischeInfo as MedischeInfo, K.AndereInfo as AndereInfo, W.Afkorting as Werking, K.DefaultWerkingId as DefaultWerkingId FROM Kind K LEFT JOIN Werking W ON K.DefaultWerkingId=W.Id WHERE K.Id=:id");
        $query->bindParam(':id', $this->getId(), PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ); 
    }
}
?>
