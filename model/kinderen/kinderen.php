<?php
require_once(dirname(__FILE__)."/../../helpers/database/database.php");
require_once(dirname(__FILE__)."/kind.php");
class Kinderen{
    public function __construct(){
        
    }
    public function getKinderen($filter){
        $db = new DataBase();
        $query = $db->getPDO()->prepare('SELECT Id AS id FROM Kind');
        $query->execute();
        $kinderen = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $kinderen[] = new Kind($rs->id);
        }
        return $kinderen;
    }
    public function updateKind($data){
        if(!isset($data['id']) || $data['id'] == '-1'){
            return $this->nieuwKind($data);
        }
        $db = new Database();
        $query = $db->getPDO()->prepare('UPDATE Kind SET Naam=:naam, Voornaam=:voornaam, Geboortejaar=:geboorte_jaar, DefaultWerking=:default_werking, MedischeInfo=:medische_info, AndereInfo=:andere_info WHERE Id=:id');
        $query->bindParam(':naam', $data['naam'], PDO::PARAM_STR);
        $query->bindParam(':voornaam', $data['voornaam'], PDO::PARAM_STR);
        $query->bindParam(':geboorte_jaar', $data['geboortejaar'], PDO::PARAM_STR);
        $query->bindParam(':default_werking', $data['werking'], PDO::PARAM_STR);
        $query->bindParam(':medische_info', $data['medische_info'], PDO::PARAM_STR);
        $query->bindParam(':andere_info', $data['andere_info'], PDO::PARAM_STR);
        $query->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $res = $query->execute();
        return $res;
    }
    public function nieuwKind($data){
        $db = new DataBase();
        $query = $db->getPDO()->prepare('INSERT INTO Kind (Naam, Voornaam, Geboortejaar, DefaultWerking, MedischeInfo, AndereInfo) VALUES (:naam, :voornaam, :geboortejaar, :werking, :medische_info, :andere_info)');
        $query->bindParam(':naam', $data['naam'], PDO::PARAM_STR);
        $query->bindParam(':voornaam', $data['voornaam'], PDO::PARAM_STR);
        $query->bindParam(':geboortejaar', $data['geboortejaar'], PDO::PARAM_INT);
        $query->bindParam(':werking', $data['werking'], PDO::PARAM_INT);
        $query->bindParam(':medische_info', $data['medische_info'], PDO::PARAM_STR);
        $query->bindParam(':andere_info', $data['andere_info'], PDO::PARAM_STR);
        $res = $query->execute();
        return $res;
    }
    public function verwijderKind($data){
        $db = new Database();
        $query = $db->getPDO()->prepare('DELETE FROM Kind WHERE Id=:id');
        $query->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $res = $query->execute();
        return $res;
    }
    public function getTabelJSONData($filter){
        //This can be optimized, by executing 1 query to catch 'em all, but for now let's stick with separate queries per child
        //$db = new Database();
        //$query = $db->getPDO()->prepare('SELECT Id as id, K.Voornaam as voornaam, K.Naam as naam, K.Geboortejaar as geboortejaar, W.afkorting as werking, K.MedischeInfo as medische_info, K.AndereInfo as andere_info FROM Kinderen K LEFT JOIN Werkingen W ON W.id=K.DefaultWerking');
        //$query->execute();
        //$kinderen = array();
        //while($rs = $query->fetch(PDO::FETCH_OBJ)){
        //    $kinderen[] = new Extraatje($rs->id);
        //}
        $kinderen = $this->getKinderen($filter);
        $arr = array();
        foreach($kinderen as $k){
            $arr[]=$k->getJSONData();
        }
        return $arr;
    }
}
?>