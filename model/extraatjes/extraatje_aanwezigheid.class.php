<?php
require_once(dirname(__FILE__)."/../record.class.php");

class ExtraatjeAanwezigheid extends Record{
    protected function setLocalData($data){
        $this->Aanwezigheid = $data->AanwezigheidId;
        $this->ExtraatjeId = $data->ExtraatjeId;
    }
    protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO ExtraatjeAanwezigheid (AanwezigheidId, ExtraatjeId) VALUES (:aanwezigheid_id, :extraatje_id)");
        $query->bindParam(':aanwezigheid_id', $this->AanwezigheidId, PDO::PARAM_STR);
        $query->bindParam(':extraatje_id', $this->ExtraatjeId, PDO::PARAM_STR);
        $query->execute();
        return $query->lastInsertId();
    }
    protected function update(){
        
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM ExtraatjeAanwezigheid WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
        
    }
    protected static function getFilterJoinsSQL($filter){
        $sql = "";
        if(isset($filter['Datum']) || isset($filter['WerkingId'])){
            $sql .= "LEFT JOIN Aanwezigheid ON Aanwezigheid.Id = ExtraatjeAanwezigheid.AanwezigheidId ";
        }
        return $sql;
    }
    protected static function getFilterSQL($filter){
        $sql = "";
        if(isset($filter['Datum'])){
            $sql .= "AND Aanwezigheid.Datum = :datum ";
        }
        if(isset($filter['WerkingId'])){
            $sql .= "AND Aanwezigheid.WerkingId = :werking_id ";
        }
        return $sql;
    }
    protected static function applyFilterParameters($query, $filter){
        if(isset($filter['Datum'])){
            $query->bindParam(':datum', $filter['Datum'], PDO::PARAM_STR);
        }
        if(isset($filter['WerkingId'])){
            $query->bindParam(':werking_id', $filter['WerkingId'], PDO::PARAM_INT);
        }
    }
    public static function countExtraatjeAanwezigheden($filter){
        $sql = "SELECT COUNT(*) as Amount FROM ExtraatjeAanwezigheid ";
        $sql .= static::getFilterJoinsSQL($filter);
        $sql .= "WHERE 1 ";
        $sql .= static::getFilterSQL($filter);
        $query = Database::getPDO()->prepare($sql);
        static::applyFilterParameters($query, $filter);
        $query->execute();
        $res = $query->fetch();
        return $res['Amount'];
    }
    public static function getExtraatjeAanwezigheden($filter){
        //TODO: check if this returns the correct column keys
        $sql = "SELECT * FROM ExtraatjeAanwezigheid ";
        $sql .= static::getFilterJoinsSQL($filter);
        $sql .= "WHERE 1 ";
        $sql .= static::getFilterSQL($filter);
        $query = Database::getPDO()->prepare($sql);
        static::applyFilterParameters($query, $filter);
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
