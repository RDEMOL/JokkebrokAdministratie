<?php
require_once (dirname(__FILE__)."/../record.class.php");
require_once (dirname(__FILE__)."/../aanwezigheden/aanwezigheid.class.php");
class Vordering extends Record{
	protected function setLocalData($data){
		$this->AanwezigheidId = $data->Aanwezigheid;
		$this->Opmerking = $data->Opmerking;
		$this->Bedrag = $data->Bedrag;
	}
	public function getAanwezigheidId(){
		return $this->AanwezigheidId;
	}
	public function getAanwezigheid(){
		return new Aanwezigheid($this->getAanwezigheidId());
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
		$this->getAanwezigheid()->getKindVoogd()->updateSaldo();
		return Database::getPDO()->lastInsertId();
	}
	protected function update(){
		$query = Database::getPDO()->prepare("UPDATE Vordering SET Aanwezigheid=:aanwezigheid_id, Opmerking=:opmerking, bedrag=:bedrag WHERE Id=:id");
		$query->bindParam(':aanwezigheid_id', $this->AanwezigheidId, PDO::PARAM_INT);
		$query->bindParam(':opmerking', $this->Opmerking, PDO::PARAM_STR);
		$query->bindParam(':bedrag', $this->Bedrag, PDO::PARAM_STR);
		$query->bindParam(':id', $this->Id, PDO::PARAM_INT);
		$res = $query->execute();
		$this->getAanwezigheid()->getKindVoogd()->updateSaldo();
		return $res;
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
		if(isset($filter['KindVoogd'])){
			$sql .= " AND A.KindVoogd = :kind_voogd_id ";
		}
		return $sql;
	}
	protected static function applyFilterParameters($query, $filter){
		if(isset($filter['Aanwezigheid'])){
			$query->bindParam(':aanwezigheid_id', $filter['Aanwezigheid'], PDO::PARAM_INT);
		}
		if(isset($filter['KindVoogd'])){
			$query->bindParam(':kind_voogd_id', $filter['KindVoogd'], PDO::PARAM_INT);
		}
	}
	protected static function getFilterJoinsSQL($filter){
		$slq = "";
		if(isset($filter['KindVoogd'])){
			$sql .= "LEFT JOIN Aanwezigheid A ON Vordering.Aanwezigheid=A.Id ";
		}
		return $sql;
	}
	public static function getVorderingen($filter){
		$sql = "SELECT * FROM Vordering ";
		$sql .= static::getFilterJoinsSQL($filter);
		$sql .= "WHERE 1 ";
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