<?php
require_once(dirname(__FILE__)."/../record.class.php");
require_once(dirname(__FILE__)."/../kindvoogden/kindvoogd.class.php");
class Aanwezigheid extends Record{
    protected function setLocalData($data){
        $this->Datum = $data->Datum;
        $this->KindVoogdId = $data->KindVoogd;
        $this->WerkingId = $data->Werking;
        $this->Opmerkingen = $data->Opmerkingen;
    }
    protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Aanwezigheid (Datum, KindVoogd, Werking, Opmerkingen) VALUES (:datum, :kind_voogd_id, :werking_id, :opmerkingen)");
        $query->bindParam(':datum', $this->Datum, PDO::PARAM_STR);
        $query->bindParam(':kind_voogd_id', $this->KindVoogdId, PDO::PARAM_INT);
        $query->bindParam(':werking_id', $this->WerkingId, PDO::PARAM_INT);
        $query->bindParam(':opmerkingen', $this->Opmerkingen, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }
    protected function update(){
        $query = Database::getPDO()->prepare("UPDATE Aanwezigheid SET Datum=:datum, KindVoogd=:kind_voogd_id, Werking=:werking_id, Opmerkingen=:opmerkingen WHERE Id=:id");
        $query->bindParam(':datum', $this->Datum, PDO::PARAM_STR);
        $query->bindParam(':kind_voogd_id', $this->KindVoogdId, PDO::PARAM_INT);
        $query->bindParam(':werking_id', $this->WerkingId, PDO::PARAM_INT);
        $query->bindParam(':opmerkingen', $this->Opmerkingen, PDO::PARAM_STR);
        $query->bindParam(':id', $this->getId(), PDO::PARAM_INT);
        return $query->execute();
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Aanwezigheid WHERE Id = :id");
        $query->bindParam(':id', $this->getId(), PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
        $query = Database::getPDO()->prepare("DELETE FROM Aanwezigheid WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }
    protected static function getFilterSQL($filter){
        $sql = "";
        if(isset($filter['VolledigeNaam'])){
            $sql .= "AND (CONCAT(K.Naam, ' ', K.Voornaam) LIKE :volledige_naam ";
            $sql .= " OR CONCAT(K.Voornaam, ' ', K.Naam) LIKE :volledige_naam2) ";
        }
        if(isset($filter['Datum'])){
            $sql .= "AND Datum = :datum ";
        }
        if(isset($filter['WerkingId'])){
            $sql .= "AND Werking = :werking_id ";
        }
        if(isset($filter['Extraatjes'])){
            $sql .= "AND EA.Extraatje = :extraatje_id ";
        }
        return $sql;
    }
    protected static function getFilterJoinsSQL($filter){
        $sql = "";
        if(isset($filter['Extraatjes'])){
            $sql .= "LEFT JOIN ExtraatjeAanwezigheid EA ON EA.Aanwezigheid = A.Id ";
        }
        return $sql;
    }
    protected static function applyFilterParameters($query, $filter){
         if(isset($filter['VolledigeNaam'])){
            $query->bindParam(':volledige_naam', $tmp = '%'.$filter['VolledigeNaam'].'%', PDO::PARAM_STR);
            $query->bindParam(':volledige_naam2', $tmp = '%'.$filter['VolledigeNaam'].'%', PDO::PARAM_STR);
        }
        if(isset($filter['Datum'])){
            $query->bindParam(':datum', $filter['Datum'], PDO::PARAM_STR);
        }
        if(isset($filter['WerkingId'])){
            $query->bindParam(':werking_id', $filter['WerkingId'], PDO::PARAM_INT);
        }
        if(isset($filter['Extraatjes'])){
            $query->bindParam(':extraatje_id', $filter['Extraatjes'], PDO::PARAM_INT);
        }
    }
    public static function countAanwezigheden($filter){
        $sql = "SELECT COUNT(*) as Amount FROM Aanwezigheid WHERE 1 ";
        $sql .= static::getFilterSQL($filter);
        $query = Database::getPDO()->prepare($sql);
        static::applyFilterParameters($query, $filter);
        $query->execute();
        $res = $query->fetch();
        return $res['Amount'];
    }
    public static function getAanwezigheden($filter){
        $sql = "SELECT A.Id as Id, A.Datum as Datum, A.KindVoogd as KindVoogd, A.Werking as Werking, A.Opmerkingen as Opmerkingen FROM Aanwezigheid A LEFT JOIN KindVoogd KV on KV.Id=A.KindVoogd LEFT JOIN Kind K ON K.Id=KV.Kind ";
        $sql .= static::getFilterJoinsSQL($filter);
        $sql .= " WHERE 1 ";
        $sql .= static::getFilterSQL($filter);
        $query = Database::getPDO()->prepare($sql);
        static::applyFilterParameters($query, $filter);
        $query->execute();
        $aanwezigheden = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $aanwezigheden[] = new Aanwezigheid($rs);
        }
        return $aanwezigheden;
    }
    public function getDatum(){
        return $this->Datum;
    }
    public function getKindVoogd(){
        return new KindVoogd($this->KindVoogdId);
    }
    public function getKindVoogdId(){
        return $this->KindVoogdId;
    }
    public function getWerkingId(){
        return $this->WerkingId;
    }
    public function getOpmerkingen(){
        return $this->Opmerkingen;
    }
    public function getExtraatjes(){
        $filter = array('AanwezigheidId'=>$this->getId());
        $extraatje_aanwezigheden = ExtraatjeAanwezigheid::getExtraatjeAanwezigheden($filter);
        Log::writeLog("extraatjes: ",count($extraatje_aanwezigheden));
        $extraatjes = array();
        foreach($extraatje_aanwezigheden as $ea){
            $extraatjes[] = $ea->getExtraatje();
        }
        return $extraatjes;
    }
    public function getJSONData(){
        //TODO: collect from local data
        $query = Database::getPDO()->prepare("SELECT A.Id as Id, K.Voornaam as Voornaam, K.Naam as Naam, K.Belangrijk as Belangrijk, W.Afkorting as Werking, A.Opmerkingen as Opmerkingen, A.Datum as Datum FROM Aanwezigheid A LEFT JOIN KindVoogd KV ON A.KindVoogd=KV.Id LEFT JOIN Kind K ON K.Id=KV.Kind LEFT JOIN Werking W ON A.Werking=W.Id WHERE A.Id= :id ");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $aanwezigheid = $query->fetch(PDO::FETCH_OBJ);
        $aanwezigheid->Extraatjes = $this->getExtraatjes();
        return $aanwezigheid;      
    }
}
?>
