<?php
require_once (dirname(__FILE__)."/../record.class.php");
require_once (dirname(__FILE__)."/../kindvoogden/kindvoogd.class.php");
class Betaling extends Record{
	protected function setLocalData($data){
        $this->KindVoogdId = $data->KindVoogd;
        $this->Opmerking = $data->Opmerking;
        $this->Bedrag = $data->Bedrag;
		$this->Datum = $data->Datum;
    }
	public function getKindVoogdId(){
		return $this->KindVoogdId;
	}
	public function getKindVoogd(){
		return new KindVoogd($this->getKindVoogdId());
	}
	public function getBedrag(){
		return $this->Bedrag;
	}
	public function getOpmerking(){
		return $this->Opmerking;
	}
	public function getDatum(){
		return $this->Datum;
	}
	protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Betaling (KindVoogd, Opmerking, Bedrag, Datum) VALUES (:kind_voogd_id, :opmerking, :bedrag, :datum)");
        $query->bindParam(':kind_voogd_id', $this->KindVoogdId, PDO::PARAM_INT);
        $query->bindParam(':opmerking', $this->Opmerking, PDO::PARAM_STR);
        $query->bindParam(':bedrag', $this->Bedrag, PDO::PARAM_STR);
        $query->bindParam(':datum', $this->Datum, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }
    protected function update(){
        $query = Database::getPDO()->prepare("UPDATE Betaling SET KindVoogd=:kind_voogd_id, Opmerking=:opmerking, bedrag=:bedrag, Datum=:datum WHERE Id=:id");
        $query->bindParam(':kind_voogd_id', $this->KindVoogdId, PDO::PARAM_INT);
        $query->bindParam(':opmerking', $this->Opmerking, PDO::PARAM_STR);
        $query->bindParam(':bedrag', $this->Bedrag, PDO::PARAM_STR);
        $query->bindParam(':datum', $this->Datum, PDO::PARAM_STR);
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Betaling WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
        $query = Database::getPDO()->prepare("DELETE FROM Betaling WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }
	
	protected static function getFilterSQL($filter){
		$sql = "";
		if(isset($filter['KindVoogd'])){
			$sql .= " AND KindVoogd = :kind_voogd_id ";
		}
		return $sql;
	}
	protected static function applyFilterParameters($query, $filter){
		if(isset($filter['KindVoogd'])){
			$query->bindParam(':kind_voogd_id', $filter['KindVoogd'], PDO::PARAM_INT);
		}
	}
	protected static function getFilterJoinsSQL($filter){
		$slq = "";
		return $sql;
	}
	public static function getBetalingen($filter){
		$sql = "SELECT * FROM Betaling ";
		$sql .= static::getFilterJoinsSQL($filter);
		$sql .= "WHERE 1 ";
        $sql .= static::getFilterSQL($filter);
        $query = Database::getPDO()->prepare($sql);
        static::applyFilterParameters($query, $filter);
        $query->execute();
        $vorderingen = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $vorderingen[] = new Betaling($rs);
        }
        return $vorderingen;
	}
	public function getJSONData(){
		$result = array();
		$result['Id']=$this->getId();
		$result['Bedrag']=$this->getBedrag();
		$result['KindVoogdId']=$this->getAanwezigheidId();
		$result['Opmerking']=$this->getOpmerking();
		$result['Datum']=$this->getDatum();
		return $result;
	}
}
?>