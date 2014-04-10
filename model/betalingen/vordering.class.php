<?php
require_once (dirname(__FILE__)."/../record.class.php");
class Vordering extends Record{
	protected function setLocalData($data){
        $this->AanwezigheidId = $data->Aanwezigheid;
        $this->Opmerking = $data->Opmerking;
        $this->Bedrag = $data->Bedrag;
    }
	public function getAanwezigheidId(){
		return $this->AanwezigheidId;
	}
	public function getBedrag(){
		return $this->Bedrag;
	}
	public function getOpmerking(){
		return $this->Opmerking;
	}
	protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Vordering (Aanwezigheid, Opmerking, Bedrag) VALUES (:aanwezigheid_id, :opmerking, :bedrag)");
        $query->bindParam(':aanwezigheid_id', $this->AanwezigheidId, PDO::PARAM_INT);
        $query->bindParam(':opmerking', $this->Opmerking, PDO::PARAM_STR);
        $query->bindParam(':bedrag', $this->Bedrag, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }
    protected function update(){
        $query = Database::getPDO()->prepare("UPDATE Vordering SET Aanwezigheid=:aanwezigheid_id, Opmerking=:opmerking, bedrag=:bedrag WHERE Id=:id");
        $query->bindParam(':aanwezigheid_id', $this->AanwezigheidId, PDO::PARAM_INT);
        $query->bindParam(':opmerking', $this->Opmerking, PDO::PARAM_STR);
        $query->bindParam(':bedrag', $this->Bedrag, PDO::PARAM_STR);
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Vordering WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
        $query = Database::getPDO()->prepare("DELETE FROM Vordering WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }
	
	protected static function getFilterSQL($filter){
		$sql = "";
		if(isset($filter['Aanwezigheid'])){
			$sql .= " AND Aanwezigheid = :aanwezigheid_id ";
		}
		return $sql;
	}
	protected static function applyFilterParameters($query, $filter){
		if(isset($filter['Aanwezigheid'])){
			$query->bindParam(':aanwezigheid_id', $filter['Aanwezigheid'], PDO::PARAM_INT);
		}
	}
	public static function getVorderingen($filter){
		$sql = "SELECT * FROM Vordering WHERE 1 ";
        $sql .= static::getFilterSQL($filter);
        $query = Database::getPDO()->prepare($sql);
        static::applyFilterParameters($query, $filter);
        $query->execute();
        $vorderingen = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $vorderingen[] = new Vordering($rs);
        }
        return $vorderingen;
	}
	public function getJSONData(){
		$result = array();
		$result['Id']=$this->getId();
		$result['Bedrag']=$this->getBedrag();
		$result['Aanwezigheid']=$this->getAanwezigheidId();
		$result['Opmerking']=$this->getOpmerking();
		return $result;
	}
}
?>