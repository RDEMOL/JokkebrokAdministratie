<?php
require_once(dirname(__FILE__)."/../record.class.php");
class Werking extends Record{
	protected function setLocalData($data){
		$this->Omschrijving = $data->Omschrijving;
		$this->Afkorting = $data->Afkorting;
		$this->Beginjaar = $data->Beginjaar;
		$this->Eindjaar = $data->Eindjaar;
	}
	protected function insert(){
		$query = Database::getPDO()->prepare("INSERT INTO Werking (Omschrijving, Afkorting, Beginjaar, Eindjaar) VALUES (:omschrijving, :afkorting, :beginjaar, :eindjaar)");
		$query->bindParam(':omschrijving', $this->Omschrijving, PDO::PARAM_STR);
		$query->bindParam(':afkorting', $this->Afkorting, PDO::PARAM_STR);
		$query->bindParam(':beginjaar', $this->Beginjaar, PDO::PARAM_INT);
		$query->bindParam(':eindjaar', $this->Eindjaar, PDO::PARAM_INT);
		$query->execute();
		return Database::getPDO()->lastInsertId();
	}
	protected function update(){
		$query = Database::getPDO()->prepare('UPDATE Werking SET Omschrijving=:omschrijving, Afkorting=:afkorting, Beginjaar=:beginjaar, Eindjaar=:eindjaar WHERE Id=:id');
		$query->bindParam(':omschrijving', $this->Omschrijving, PDO::PARAM_STR);
		$query->bindParam(':afkorting', $this->Afkorting, PDO::PARAM_STR);
		$query->bindParam(':beginjaar', $this->Beginjaar, PDO::PARAM_INT);
		$query->bindParam(':eindjaar', $this->Eindjaar, PDO::PARAM_INT);
		$query->bindParam(':id', $this->Id, PDO::PARAM_INT);
		$res = $query->execute();
		return $res;
		
	}
	protected function select(){
		$query = Database::getPDO()->prepare("SELECT * FROM Werking WHERE Id = :id");
		$query->bindParam(':id', $this->Id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetch(PDO::FETCH_OBJ);
	}
	protected function delete(){
		$filter = array();
		$filter['Werking']=$this->getId();
		if(count(Kind::getKinderen($filter)) > 0 || count(Aanwezigheid::getAanwezigheden($filter)) > 0){
			return false;
		}
		$query = Database::getPDO()->prepare("DELETE FROM Werking WHERE Id = :id");
		$query->bindParam(':id', $this->Id, PDO::PARAM_INT);
		return $query->execute();
	}
	public static function getWerkingen(){
		$query = Database::getPDO()->prepare("SELECT * FROM Werking WHERE 1");
		$query->execute();
		$werkingen = array();
		while($rs = $query->fetch(PDO::FETCH_OBJ)){
			$werkingen[] = new Werking($rs, true);
		}
		return $werkingen;
	}
	public static function getJSONWerkingen(){
		$werkingen = Werking::getWerkingen();
		$result = array();
		foreach($werkingen as $w){
			$result[] = $w->getJSONData();
		}
		return $result;
	}
	public function getOmschrijving(){
		return $this->Omschrijving;
	}
	public function getAfkorting(){
		return $this->Afkorting;
	}
	public function getBeginjaar(){
		return $this->Beginjaar;
	}
	public function getEindjaar(){
		return $this->Eindjaar;
	}
	public function getJSONData(){
		$obj = new stdClass();
		$obj->Id = $this->getId();
		$obj->Omschrijving = $this->getOmschrijving();
		$obj->Afkorting = $this->getAfkorting();
		$obj->Beginjaar = $this->getBeginjaar();
		$obj->Eindjaar = $this->getEindjaar();
		return $obj;	
	}
}
?>