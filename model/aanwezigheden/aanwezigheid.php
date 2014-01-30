<?php
require_once(dirname(__FILE__)."/../../helpers/database/database.php");
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
}
?>