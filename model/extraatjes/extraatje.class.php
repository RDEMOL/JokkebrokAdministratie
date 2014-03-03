<?php
require_once(dirname(__FILE__)."/../record.class.php");
class Extraatje extends Record{
    protected function setLocalData($data){
        $this->Omschrijving = $data->Omschrijving;
    }
    protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Extraatje (Voornaam, Naam, Geboortejaar, DefaultWerking, MedischeInfo, AndereInfo) VALUES (:voornaam, :naam, :geboortejaar, :default_werking_id, :medische_info, :andere_info)");
        $query->bindParam(':omschrijving', $this->Omschrijving, PDO::PARAM_STR);
        $query->execute();
        return $query->lastInsertId();
    }
    protected function update(){
        
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Extraatje WHERE Id = :id");
        $query->bindParam(':id', $this->getId(), PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
        
    }

    public static function getExtraatjes(){
        $query = Database::getPDO()->prepare("SELECT * FROM Extraatje WHERE 1");
        $query->execute();
        $kinderen = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $kinderen[] = new Extraatje($rs);
        }
        return $kinderen;
    }
    public function getOmschrijving(){
        return $this->Omschrijving;
    }
}
?>


<?php
/*require_once(dirname(__FILE__)."/../../helpers/database/database.php");
class Extraatje{
    private $id, $naam;
    public function __construct($id){
        $this->id = $id;
        $this->init();
    }
    private function init(){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT Omschrijving as naam FROM Extraatje WHERE Id = :id');
        $query->bindParam(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        $this->naam = $result->naam;
    }
    public function getNaam(){
        return $this->naam;
    }
    public function getId(){
        return $this->id;
    }
    public function getAmountOnDay($speelpleindag){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT COUNT(ea.Id) as aantal FROM ExtraatjeAanwezigheid ea LEFT JOIN Aanwezigheid a ON ea.Aanwezigheid = a.Id WHERE a.Datum = :datum');
        $query->bindParam(':datum', $speelpleindag->getDatum(), PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->aantal;
    }
    public function getAmountOnDayWithWerking($speelpleindag, $werking){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT COUNT(ea.Id) as aantal FROM ExtraatjeAanwezigheid ea LEFT JOIN Aanwezigheid a ON ea.Aanwezigheid = a.Id WHERE a.Datum = :datum AND a.Werking = :werking');
        $query->bindParam(':datum', $speelpleindag->getDatum(), PDO::PARAM_STR);
        $query->bindParam(':werking', $werking->getId(), PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->aantal;
    }
}*/
?>