<?php
require_once(dirname(__FILE__)."/../record.class.php");
class Aanwezigheid extends Record{
    protected function setLocalData($data){
        $this->Datum = $data->Datum;
        $this->KindVoogdId = $data->KindVoogdId;
        $this->WerkingId = $data->WerkingId;
        $this->Opmerkingen = $data->Opmerkingen;
    }
    protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Aanwezigheid (Datum, KindVoogdId, WerkingId, Opmerkingen) VALUES (:datum, :kind_voogd_id, :werking_id, :opmerkingen)");
        $query->bindParam(':datum', $this->Datum, PDO::PARAM_STR);
        $query->bindParam(':kind_voogd_id', $this->KindVoogdId, PDO::PARAM_INT);
        $query->bindParam(':werking_id', $this->WerkingId, PDO::PARAM_INT);
        $query->bindParam(':opmerkingen', $this->Opmerkingen, PDO::PARAM_STR);
        $query->execute();
        return $query->lastInsertId();
    }
    protected function update(){
        throw new Exception("Update Aanwezigheid not implemented yet");
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Aanwezigheid WHERE Id = :id");
        $query->bindParam(':id', $this->getId(), PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
        
    }
    protected static function getFilterSQL($filter){
        $sql = "";
        if(isset($filter['Datum'])){
            $sql .= "AND Datum = :datum ";
        }
        if(isset($filter['WerkingId'])){
            $sql .= "AND WerkingId = :werking_id ";
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
        $sql = "SELECT * FROM Aanwezigheid WHERE 1 ";
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
    public function getKindVoogdId(){
        return $this->KindVoogdId;
    }
    public function getWerkingId(){
        return $this->WerkingId;
    }
    public function getOpmerkingen(){
        return $this->Opmerkingen;
    }
    public function getJSONData(){
        //TODO: collect from local data
        $query = Database::getPDO()->prepare("SELECT A.Id as Id, K.Voornaam as Voornaam, K.Naam as Naam, K.MedischeInfo as MedischeInfo, K.AndereInfo as AndereInfo, W.Afkorting as Werking FROM Aanwezigheid A LEFT JOIN KindVoogd KV ON A.KindVoogdId=KV.Id LEFT JOIN Kind K ON K.Id=KV.Kind LEFT JOIN Werking W ON A.WerkingId=W.Id WHERE A.Id= :id ");
        $query->bindParam(':id', $this->getId(), PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);     
    }
}
?>

<?php
/*require_once(dirname(__FILE__)."/../../helpers/database/database.php");

class Aanwezigheid{
    public function __construct($id){
        $this->id = $id;
    }
    public function getId(){
        return $this->id;
    }
    public function getJSONData(){
        $db = new Database();
        $query = $db->getPDO()->prepare("SELECT A.Id as id, K.Voornaam as voornaam, K.Naam as naam, K.MedischeInfo as medische_info, K.AndereInfo as andere_info, W.afkorting as werking FROM Aanwezigheid A LEFT JOIN KindVoogd KV ON A.KindVoogd=KV.Id LEFT JOIN Kind K ON K.Id=KV.Kind LEFT JOIN Werking W ON A.Werking=W.Id WHERE A.Id= :id ");
        $query->bindParam(':id', $this->getId(), PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);        
    }
}*/
?>