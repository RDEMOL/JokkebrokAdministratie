<?php
require_once(dirname(__FILE__)."/../record.class.php");
require_once(dirname(__FILE__)."/../kindvoogden/kindvoogd.class.php");
class Kind extends Record{
    protected function setLocalData($data){
        $this->Voornaam = $data->Voornaam;
        $this->Naam = $data->Naam;
        $this->Geboortejaar = $data->Geboortejaar;
        $this->DefaultWerkingId = $data->DefaultWerkingId;
        $this->Belangrijk = $data->Belangrijk;
    }
    public function getNaam(){
        return $this->Naam;
    }
    public function getVoornaam(){
        return $this->Voornaam;
    }
    public function getDefaultWerkingId(){
        return $this->DefaultWerkingId;
    }
    protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Kind (Voornaam, Naam, Geboortejaar, DefaultWerkingId, Belangrijk) VALUES (:voornaam, :naam, :geboortejaar, :default_werking_id, :belangrijk)");
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':geboortejaar', $this->Geboortejaar, PDO::PARAM_STR);
        $query->bindParam(':default_werking_id', $this->DefaultWerkingId, PDO::PARAM_INT);
        $query->bindParam(':belangrijk', $this->Belangrijk, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }
    protected function update(){
        $query = Database::getPDO()->prepare('UPDATE Kind SET Naam=:naam, Voornaam=:voornaam, Geboortejaar=:geboorte_jaar, DefaultWerkingId=:default_werking_id, Belangrijk=:belangrijk WHERE Id=:id');
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':geboorte_jaar', $this->Geboortejaar, PDO::PARAM_STR);
        $query->bindParam(':default_werking_id', $this->DefaultWerkingId, PDO::PARAM_STR);
        $query->bindParam(':belangrijk', $this->Belangrijk, PDO::PARAM_STR);
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
    protected static function getFilterJoinsSQL($filter){
        $sql = "";
        
        return $sql;
    }
    protected static function getFilterSQL($filter){
        $sql = "";
        if(isset($filter['VolledigeNaam'])){
            $sql .= "AND (CONCAT(Naam, ' ', Voornaam) LIKE :volledige_naam ";
            $sql .= " OR CONCAT(Voornaam, ' ', Naam) LIKE :volledige_naam2) ";
        }
        if(isset($filter['WerkingId'])){
            $sql .= "AND DefaultWerkingId = :werking_id ";
        }
        return $sql;
    }
    protected static function applyFilterParameters($query, $filter){
        if(isset($filter['VolledigeNaam'])){
            $query->bindParam(':volledige_naam', $tmp = '%'.$filter['VolledigeNaam'].'%', PDO::PARAM_STR);
            $query->bindParam(':volledige_naam2', $tmp = '%'.$filter['VolledigeNaam'].'%', PDO::PARAM_STR);
        }
        if(isset($filter['WerkingId'])){
            $query->bindParam(':werking_id', $filter['WerkingId'], PDO::PARAM_INT);
        }
    }
    public static function getKinderen($filter, $max_amount=0){
        $sql = "SELECT * FROM Kind WHERE 1 ";
        $sql .= static::getFilterSQL($filter);
        if(intval($max_amount)){
            $sql .= "LIMIT ".intval($max_amount);
        }
        $query = Database::getPDO()->prepare($sql);
        static::applyFilterParameters($query, $filter);
        $query->execute();
        $kinderen = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $kinderen[] = new Kind($rs);
        }
        return $kinderen;
    }
    public function getKindVoogden(){
        //TODO: move functionality to Voogd class
        //$sql = "SELECT KindVoogd.ID as KindVoogd, Voogd.Id as Id, Voogd.Naam as Naam, Voogd.Voornaam as Voornaam, Voogd.Opmerkingen as Opmerkingen FROM Voogd LEFT JOIN KindVoogd ON KindVoogd.Voogd=Voogd.Id WHERE KindVoogd.Kind=:id";
        $sql = "SELECT * FROM KindVoogd WHERE Kind = :id";
        $query = Database::getPDO()->prepare($sql);
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $voogden = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $voogden[] = new KindVoogd($rs);
        }
        return $voogden;
    }
    public function getKindVoogdenIds(){
        $voogden = $this->getKindVoogden();
        $voogden_ids = array();
        foreach($voogden as $v){
            $voogden_ids[] = $v->getId();
        }
        return $voogden_ids;
    }
    //move to KindVoogd class (kv_id!)
    public function removeKindVoogd($v_id){
        $kv = new KindVoogd($v_id);
        $kv->deleteFromDatabase();
        /*$sql = "DELETE FROM KindVoogd WHERE Voogd = :voogd_id AND Kind = :kind_id";
        $query = Database::getPDO()->prepare($sql);
        $kind_id = $this->getId();
        $voogd_id = $v_id;
        $query->bindParam(':voogd_id', $voogd_id, PDO::PARAM_INT);
        $query->bindParam(':kind_id', $kind_id, PDO::PARAM_INT);
        $query->execute();*/
    }
    public function addVoogd($v_id){
        $data = new stdClass();
        $data->Id = 0;
        $data->Kind=$this->getId();
        $data->Voogd=$v_id;
        $v = new KindVoogd($data);
        $v->updateDatabase();
        /*$sql = "INSERT INTO KindVoogd (Voogd, Kind) VALUES(:voogd_id, :kind_id)";
        $query = Database::getPDO()->prepare($sql);
        $kind_id = $this->getId();
        $voogd_id = $v_id;
        $query->bindParam(':voogd_id', $voogd_id, PDO::PARAM_INT);
        $query->bindParam(':kind_id', $kind_id, PDO::PARAM_INT);
        $query->execute();*/
    }
    public function setVoogden($voogd_ids){
        $voogden = $this->getKindVoogden();
        foreach($voogden as $v){
            $good = false;
            foreach($voogd_ids as $v_id){
                if($v->getVoogdId() == $v_id){
                    $good = true;
                    break;
                }
            }
            if(!$good){
                $this->removeKindVoogd($v->getId());
            }
        }
        foreach($voogd_ids as $v_id){
            $good = false;
            foreach($voogden as $v){
                if($v->getId() == $v_id){
                    $good = true;
                    break;
                }
            }
            if(!$good){
                $this->addVoogd($v_id);
            }
        }
    }
    public function getJSONData(){
        $query = Database::getPDO()->prepare("SELECT K.Id as Id, K.Voornaam as Voornaam, K.Naam as Naam, K.Geboortejaar as Geboortejaar, K.Belangrijk as Belangrijk, W.Afkorting as Werking, K.DefaultWerkingId as DefaultWerkingId FROM Kind K LEFT JOIN Werking W ON K.DefaultWerkingId=W.Id WHERE K.Id=:id");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $obj = $query->fetch(PDO::FETCH_OBJ);
        $obj->VoogdIds = $this->getKindVoogdenIds();
        return $obj; 
    }
}
?>
