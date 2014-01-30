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