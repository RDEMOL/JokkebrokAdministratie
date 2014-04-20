<?php
require_once(dirname(__FILE__)."/../record.class.php" );
require_once(dirname(__FILE__)."/../kinderen/kind.class.php");
class Voogd extends Record{
    protected function setLocalData($data){
        $this->Voornaam = $data->Voornaam;
        $this->Naam = $data->Naam;
        $this->Opmerkingen = $data->Opmerkingen;
    }
    public function getVoornaam(){
        return $this->Voornaam;
    }
    public function getNaam(){
        return $this->Naam;
    }
    public function getJSONData(){
        $db = new Database();
        $query = $db->getPDO()->prepare("SELECT * FROM Voogd WHERE Id= :id ");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);        
    }
     protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Voogd (Voornaam, Naam, Opmerkingen) VALUES (:voornaam, :naam, :opmerkingen)");
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':opmerkingen', $this->Opmerkingen, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
     }
     protected function update(){
        $query = Database::getPDO()->prepare('UPDATE Voogd SET Naam=:naam, Voornaam=:voornaam, Opmerkingen=:opmerkingen WHERE Id=:id');
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':opmerkingen', $this->Opmerkingen, PDO::PARAM_STR);
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $res = $query->execute();
        return $res;
     }
     protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Voogd WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
     }
     protected function delete(){
        $query = Database::getPDO()->prepare("DELETE FROM Voogd WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
     }
	 public function getKinderen(){
	 	$query = Database::getPDO()->prepare("SELECT K.Id as Id FROM Kind K LEFT JOIN KindVoogd KV ON KV.Kind=K.Id WHERE KV.Voogd = :voogd_id");
		$query->bindParam(':voogd_id', $this->Id, PDO::PARAM_INT);
		$query->execute();
		$kinderen = array();
		while($rs = $query->fetch(PDO::FETCH_OBJ)){
			$kinderen[] = new Kind($rs->Id);
		}
		return $kinderen;
	 }
	 protected static function getFilterSQL($filter){
	 	$sql = "";
	 	if(isset($filter['VolledigeNaam'])){
	 		$sql .= "AND (CONCAT(Naam, ' ', Voornaam) LIKE :volledige_naam ";
            $sql .= " OR CONCAT(Voornaam, ' ', Naam) LIKE :volledige_naam2) ";
	 	}
		return $sql;
	 }
	 protected static function applyFilterParameters($query, $filter){
        if(isset($filter['VolledigeNaam'])){
        	$tmp = '%'.$filter['VolledigeNaam'].'%';
            $query->bindParam(':volledige_naam', $tmp, PDO::PARAM_STR);
            $query->bindParam(':volledige_naam2', $tmp = '%'.$filter['VolledigeNaam'].'%', PDO::PARAM_STR);
        }
    }
	 public static function getVoogden($filter, $max_amount){
	 	$sql = "SELECT Id FROM Voogd WHERE 1 ";
		$sql .= static::getFilterSQL($filter);
		if(intval($max_amount)){
			$sql .= " LIMIT ".intval($max_amount);
		}
		$query = Database::getPDO()->prepare($sql);
		static::applyFilterParameters($query, $filter);
		$query->execute();
		$voogden = array();
		while($rs = $query->fetch(PDO::FETCH_OBJ)){
			$voogden[] = new Voogd($rs->Id);
		}
		return $voogden;
	 }
}
?>