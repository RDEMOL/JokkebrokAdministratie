<?php
require_once(dirname(__FILE__)."/../../helpers/database/database.php");
require_once(dirname(__FILE__)."/aanwezigheid.php");
class Aanwezigheden{
    public function __construct(){
        
    }
    public function getAanwezigheden($filter){
        $db = new DataBase();
        $query = $db->getPDO()->prepare('SELECT Id AS id FROM Aanwezigheid');
        $query->execute();
        $aanwezigheden = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $aanwezigheden[] = new Aanwezigheid($rs->id);
        }
        return $aanwezigheden;
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
        $aanwezigheden = $this->getAanwezigheden($filter);
        $arr = array();
        foreach($aanwezigheden as $a){
            $arr[]=$a->getJSONData();
        }
        return $arr;
    }
}
?>