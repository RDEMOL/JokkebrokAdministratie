<?php
require_once(dirname(__FILE__)."/../record.class.php");
require_once(dirname(__FILE__)."/extraatje.class.php");

class ExtraatjeAanwezigheid extends Record{
    protected function setLocalData($data){
        $this->AanwezigheidId = $data->Aanwezigheid;
        $this->ExtraatjeId = $data->Extraatje;
    }
    protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO ExtraatjeAanwezigheid (Aanwezigheid, Extraatje) VALUES (:aanwezigheid_id, :extraatje_id)");
        $query->bindParam(':aanwezigheid_id', $this->AanwezigheidId, PDO::PARAM_STR);
        $query->bindParam(':extraatje_id', $this->ExtraatjeId, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }
    public function getExtraatje(){
        return new Extraatje($this->getExtraatjeId());
    }
    public function getExtraatjeId(){
        return $this->ExtraatjeId;
    }
    protected function update(){
        
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM ExtraatjeAanwezigheid WHERE Id = :id");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
        
    }
    protected static function getFilterJoinsSQL($filter){
        $sql = "";
        if(isset($filter['Datum']) || isset($filter['Werking'])){
            $sql .= "LEFT JOIN Aanwezigheid ON Aanwezigheid.Id = ExtraatjeAanwezigheid.Aanwezigheid ";
        }
        return $sql;
    }
    protected static function getFilterSQL($filter){
        $sql = "";
        if(isset($filter['Datum'])){
            $sql .= "AND Aanwezigheid.Datum = :datum ";
        }
        if(isset($filter['Werking'])){
            $sql .= "AND Aanwezigheid.Werking = :werking_id ";
        }
        if(isset($filter['AanwezigheidId'])){
            $sql .= "AND ExtraatjeAanwezigheid.Aanwezigheid = :aanwezigheid_id ";
        }
        return $sql;
    }
    protected static function applyFilterParameters($query, $filter){
        if(isset($filter['Datum'])){
            $query->bindParam(':datum', $filter['Datum'], PDO::PARAM_STR);
        }
        if(isset($filter['Werking'])){
            $query->bindParam(':werking_id', $filter['Werking'], PDO::PARAM_INT);
        }
        if(isset($filter['AanwezigheidId'])){
            $query->bindParam(':aanwezigheid_id', $filter['AanwezigheidId'], PDO::PARAM_INT);
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
        $extraatje_aanwezigheden = array();
        //Log::writeLog("extraatje aanwezigheid", "searching! aanwezigheid id = ".$filter['AanwezigheidId']." sql = ".$sql);
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            //Log::writeLog("extraatje aanwezigheid", "found!");
            $extraatje_aanwezigheden[] = new ExtraatjeAanwezigheid($rs);
        }
        return $extraatje_aanwezigheden;
    }
    public function getOmschrijving(){
        return $this->Omschrijving;
    }
    public function getAfkorting(){
        return $this->Afkorting;
    }    
}

?>
