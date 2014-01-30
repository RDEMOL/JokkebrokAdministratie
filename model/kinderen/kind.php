<?php
require_once(dirname(__FILE__)."/../../helpers/database/database.php");
class Kind{
    public function __construct($id){
        $this->id = $id;
    }
    public function getId(){
        return $this->id;
    }
    public function getJSONData(){
        $db = new Database();
        $query = $db->getPDO()->prepare("SELECT K.Id as id, K.Voornaam as voornaam, K.Naam as naam, K.GeboorteJaar as geboorte_jaar, K.MedischeInfo as medische_info, K.AndereInfo as andere_info, W.afkorting as werking FROM Kind K LEFT JOIN Werking W ON K.DefaultWerking=W.Id WHERE K.Id= :id ");
        $query->bindParam(':id', $this->getId(), PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);        
    }
}
?>