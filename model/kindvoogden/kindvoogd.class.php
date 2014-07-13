<?php
require_once(dirname(__FILE__)."/../record.class.php");
require_once(dirname(__FILE__)."/../voogden/voogd.class.php");
require_once(dirname(__FILE__)."/../kinderen/kind.class.php");
require_once(dirname(__FILE__)."/../betalingen/betaling.class.php");
require_once(dirname(__FILE__)."/../betalingen/vordering.class.php");
class KindVoogd extends Record{
	protected function setLocalData($data){
		$this->VoogdId = $data->Voogd;
		$this->KindId = $data->Kind;
		$this->Saldo = $data->Saldo;
	}
	public function getVoogd(){
		return new Voogd($this->VoogdId);
	}
	public function getKind(){
		return new Kind($this->KindId);
	}
	public function getVoogdId(){
		return $this->VoogdId;
	}
	public function getKindId(){
		return $this->KindId;
	}
	protected function insert(){
		$query = Database::getPDO()->prepare("INSERT INTO KindVoogd (Kind, Voogd) VALUES (:kind, :voogd)");
		$query->bindParam(':kind', $this->KindId, PDO::PARAM_INT);
		$query->bindParam(':voogd', $this->VoogdId, PDO::PARAM_INT);
		$query->execute();
		return Database::getPDO()->lastInsertId();
	}
	protected function update(){
		$query = Database::getPDO()->prepare('UPDATE KindVoogd SET Kind=:kind, Voogd=:voogd WHERE Id=:id');
		$query->bindParam(':kind', $this->KindId, PDO::PARAM_INT);
		$query->bindParam(':voogd', $this->VoogdId, PDO::PARAM_INT);
		$query->bindParam(':id', $this->Id, PDO::PARAM_INT);
		$res = $query->execute();
		$this->updateSaldo();
		return $res;
	}
	protected function select(){
		$query = Database::getPDO()->prepare("SELECT * FROM KindVoogd WHERE Id = :id");
		$query->bindParam(':id', $this->Id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetch(PDO::FETCH_OBJ);
	}
	protected function delete(){
		if(count($this->getAanwezigheden()) > 0){
			return false;
		}
		$query = Database::getPDO()->prepare("DELETE FROM KindVoogd WHERE Id = :id");
		$query->bindParam(':id', $this->Id, PDO::PARAM_INT);
		return $query->execute();
	}
	public function getJSONData(){
		$query = Database::getPDO()->prepare("SELECT Id, Voogd as VoogdId, Kind as KindId FROM KindVoogd WHERE Id=:id");
		$id = $this->getId();
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$obj = $query->fetch(PDO::FETCH_OBJ);
		return $obj; 
	}
	public function getVorderingen(){
		$filter = array('KindVoogd'=>$this->Id);
		return Vordering::getVorderingen($filter);
	}
	public function getBetalingen(){
		$filter = array('KindVoogd'=>$this->getId());
		return Betaling::getBetalingen($filter);
	}
	public function getSaldo(){
		return $this->Saldo;
	}
	public function getHeeftSchulden(){
		return $this->getSaldo() != 0;
	}
	public function updateSaldo(){
		$betalingen = $this->getBetalingen();
		$vorderingen = $this->getVorderingen();
		$saldo = 0;
		foreach($betalingen as $b){
			$saldo += $b->getBedrag();
		}
		foreach($vorderingen as $v){
			$saldo -= $v->getBedrag();
		}
		$this->Saldo = $saldo;
		$sql = "UPDATE KindVoogd SET Saldo=:saldo WHERE Id=:kind_voogd_id ";
		$query = Database::getPDO()->prepare($sql);
		$query->bindParam(':kind_voogd_id', $this->Id, PDO::PARAM_INT);
		$query->bindParam(':saldo', $this->Saldo);
		$query->execute();
	}
	public function getAanwezigheden(){
		$filter = array('KindVoogd'=> $this->getId());
		$aanwezigheden = Aanwezigheid::getAanwezigheden($filter);
		return $aanwezigheden;
	}
}
?>
