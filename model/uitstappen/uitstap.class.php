<?php
require_once(dirname(__FILE__)."/../record.class.php" );
require_once(dirname(__FILE__)."/uitstap_kind.class.php");
class Uitstap extends Record{
    protected function setLocalData($data){
        $this->Datum = $data->Datum;
        $this->Omschrijving = $data->Omschrijving;
        $this->Actief = $data->Actief;
    }
	public function getOmschrijving(){
		return $this->Omschrijving;
	}
	public function getDatum(){
		return $this->Datum;
	}
    public function getJSONData(){
        $db = new Database();
        $query = $db->getPDO()->prepare("SELECT * FROM Uitstap WHERE Id= :id ");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);        
    }
     protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Uitstap (Datum, Omschrijving, Actief) VALUES (:datum, :omschrijving, :actief)");
        $query->bindParam(':datum', $this->Datum, PDO::PARAM_STR);
        $query->bindParam(':omschrijving', $this->Omschrijving, PDO::PARAM_STR);
        $query->bindParam(':actief', $this->Actief, PDO::PARAM_BOOL);
        $query->execute();
        return Database::getPDO()->lastInsertId();
     }
     protected function update(){
        $query = Database::getPDO()->prepare('UPDATE Uitstap SET Datum=:datum, Omschrijving=:omschrijving, Actief=:actief WHERE Id=:id');
        $query->bindParam(':datum', $this->Datum, PDO::PARAM_STR);
        $query->bindParam(':omschrijving', $this->Omschrijving, PDO::PARAM_STR);
        $query->bindParam(':actief', $this->Actief, PDO::PARAM_BOOL);
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $res = $query->execute();
        return $res;
     }
     protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Uitstap WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
     }
     protected function delete(){
        $query = Database::getPDO()->prepare("DELETE FROM Uitstap WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
     }
	 
	protected static function getFilterSQL($filter){
		$sql = "";
		if(isset($filter['Actief'])){
			$sql .= " AND Actief = :actief ";
		}
		return $sql;
	}
	protected static function applyFilterParameters($query, $filter){
		if(isset($filter['Actief'])){
			$query->bindParam(':actief', $filter['Actief'], PDO::PARAM_INT);
		}
	}
	protected static function getFilterJoinsSQL($filter){
		$slq = "";
		return $sql;
	}
     public static function getUitstappen($filter){
     	 $sql = "SELECT * FROM Uitstap WHERE 1 ";
		 $sql .= static::getFilterSQL($filter);
         $query = Database::getPDO()->prepare($sql);
         static::applyFilterParameters($query, $filter);
         $query->execute();
         $uitstappen = array();
         while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $uitstappen[] = new Uitstap($rs);
         }
         return $uitstappen;
     }
	 public function getAantalDeelnemers(){
	 	return count($this->getDeelnemers());
	 }
     public function getDeelnemers(){
         $filter = array('Uitstap'=>$this->getId());
         return UitstapKind::getUitstapKinderen($filter);
     }
}
?>