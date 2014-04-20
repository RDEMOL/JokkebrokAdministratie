<?php
require_once(dirname(__FILE__)."/../record.class.php");
require_once(dirname(__FILE__)."/../kindvoogden/kindvoogd.class.php");
require_once(dirname(__FILE__)."/../uitstappen/uitstap_kind.class.php");
class Kind extends Record{
    protected function setLocalData($data){
        $this->Voornaam = $data->Voornaam;
        $this->Naam = $data->Naam;
        $this->Geboortejaar = $data->Geboortejaar;
        $this->DefaultWerkingId = $data->DefaultWerking;
        $this->Belangrijk = $data->Belangrijk;
    }
    public function getNaam(){
        return $this->Naam;
    }
    public function getVoornaam(){
        return $this->Voornaam;
    }
    public function getDefaultWerkingId(){
        return $this->DefaultWerkingId;
    }
    public function getGeboortejaar(){
        return $this->Geboortejaar;
    }
    protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Kind (Voornaam, Naam, Geboortejaar, DefaultWerking, Belangrijk) VALUES (:voornaam, :naam, :geboortejaar, :default_werking_id, :belangrijk)");
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':geboortejaar', $this->Geboortejaar, PDO::PARAM_STR);
        $query->bindParam(':default_werking_id', $this->DefaultWerkingId, PDO::PARAM_INT);
        $query->bindParam(':belangrijk', $this->Belangrijk, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }
    protected function update(){
        $query = Database::getPDO()->prepare('UPDATE Kind SET Naam=:naam, Voornaam=:voornaam, Geboortejaar=:geboorte_jaar, DefaultWerking=:default_werking_id, Belangrijk=:belangrijk WHERE Id=:id');
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':geboorte_jaar', $this->Geboortejaar, PDO::PARAM_STR);
        $query->bindParam(':default_werking_id', $this->DefaultWerkingId, PDO::PARAM_STR);
        $query->bindParam(':belangrijk', $this->Belangrijk, PDO::PARAM_STR);
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $res = $query->execute();
        return $res;
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Kind WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
        $query = Database::getPDO()->prepare("DELETE FROM Kind WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }
    protected static function getFilterJoinsSQL($filter){
        $sql = "";
        
        return $sql;
    }
    protected static function getFilterSQL($filter){
        $sql = "";
		Log::writeLog("filter", json_encode($filter));
        if(isset($filter['VolledigeNaam'])){
        	Log::writeLog("filter", "volledige naam = ".$filter['VolledigeNaam']);
            $sql .= "AND (CONCAT(Naam, ' ', Voornaam) LIKE :volledige_naam ";
            $sql .= " OR CONCAT(Voornaam, ' ', Naam) LIKE :volledige_naam2) ";
        }
        if(isset($filter['Werking'])){
            $sql .= "AND DefaultWerking = :werking_id ";
        }
        if(isset($filter['Geboortejaar'])){
        	$sql .= "AND Geboortejaar = :geboortejaar ";
        }
        return $sql;
    }
    protected static function applyFilterParameters($query, $filter){
        if(isset($filter['VolledigeNaam'])){
        	$tmp = '%'.$filter['VolledigeNaam'].'%';
            $query->bindParam(':volledige_naam', $tmp, PDO::PARAM_STR);
            $query->bindParam(':volledige_naam2', $tmp = '%'.$filter['VolledigeNaam'].'%', PDO::PARAM_STR);
        }
        if(isset($filter['Werking'])){
            $query->bindParam(':werking_id', $filter['Werking'], PDO::PARAM_INT);
        }
		if(isset($filter['Geboortejaar'])){
			$query->bindParam(':geboortejaar', $filter['Geboortejaar'], PDO::PARAM_INT);
		}
    }
	protected static function getOrderSQL($order){
		if(count($order) == 0)
			return "";
		$first = true;
		$sql = "ORDER BY";
		foreach($order as $o){
			$curr_sql = " ";
			if(!$first){
				$curr_sql = ", ";
			}
			if(!isset($o['Veld']) || !isset($o['Order'])){
				continue;
			}
			$stop = false;
			switch($o['Veld']){
				case 'Naam':
				case 'Voornaam':
				case 'Geboortejaar':
					$curr_sql .= " ".$o['Veld'];
					break;
				default:
					$stop = true;
					break;
			}
			switch(strtolower($o['Order'])){
				case 'asc':
					$curr_sql.= " ASC";
					break;
				case 'desc':
					$curr_sql .= " DESC";
					break;
				default:
					$stop =true;
					break;
			}
			if($stop)
				continue;
			$sql .= $curr_sql;
			$first = false;
		}
		if($first){
			return "";
		}
		return $sql;
	}
    public static function getKinderen($filter, $max_amount=0, $order=array()){
        $sql = "SELECT * FROM Kind WHERE 1 ";
        $sql .= static::getFilterSQL($filter);
        if(intval($max_amount)){
            $sql .= "LIMIT ".intval($max_amount)." ";
        }
        if($order){
        	$sql .= static::getOrderSQL($order);
        }
        $query = Database::getPDO()->prepare($sql);
        static::applyFilterParameters($query, $filter);
        $query->execute();
        $kinderen = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $kinderen[] = new Kind($rs);
        }
		Log::writeLog("getkinderen", "selected ".count($kinderen));
        return $kinderen;
    }
    public function getKindVoogden(){
        //TODO: move functionality to Voogd class
        //$sql = "SELECT KindVoogd.ID as KindVoogd, Voogd.Id as Id, Voogd.Naam as Naam, Voogd.Voornaam as Voornaam, Voogd.Opmerkingen as Opmerkingen FROM Voogd LEFT JOIN KindVoogd ON KindVoogd.Voogd=Voogd.Id WHERE KindVoogd.Kind=:id";
        $sql = "SELECT * FROM KindVoogd WHERE Kind = :id";
        $query = Database::getPDO()->prepare($sql);
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $voogden = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $voogden[] = new KindVoogd($rs);
        }
        return $voogden;
    }
    public function getVoogdenIds(){
        $kindvoogden = $this->getKindVoogden();
        $ids = array();
        foreach($kindvoogden as $kv){
            $ids[] = $kv->getVoogdId();
        }
        return $ids;
    }
    public function addVoogd($v_id){
        $data = new stdClass();
        $data->Id = 0;
        $data->Kind=$this->getId();
        $data->Voogd=$v_id;
        $v = new KindVoogd($data);
        $v->updateDatabase();
    }
	public function setVoogdIds($voogd_ids){
		$current_voogden = $this->getKindVoogden();
		foreach($current_voogden as $cv){
			$good = false;
			foreach($voogd_ids as $vi){
				if($vi == $cv->getId()){
					$good = true;
					break;
				}
			}
			if(!$good){
				$cv->deleteFromDatabase();
			}
		}
		foreach($voogd_ids as $vi){
			$this->addVoogd($vi);
		}
	}
	public function getHeeftSchulden(){
		$kindvoogden = $this->getKindVoogden();
		foreach($kindvoogden as $kv){
			if($kv->getSaldo() != 0.0)
			return true;
		}
		return false;
	}
    public function getJSONData(){
        $query = Database::getPDO()->prepare("SELECT K.Id as Id, K.Voornaam as Voornaam, K.Naam as Naam, K.Geboortejaar as Geboortejaar, K.Belangrijk as Belangrijk, W.Afkorting as Werking, K.DefaultWerking as DefaultWerking FROM Kind K LEFT JOIN Werking W ON K.DefaultWerking=W.Id WHERE K.Id=:id");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $obj = $query->fetch(PDO::FETCH_OBJ);
        $obj->VoogdenIds = $this->getVoogdenIds();
		$obj->Schulden = $this->getHeeftSchulden();
        return $obj; 
    }
	public function getUitstapDeelnames($filter=null){
		if($filter == null){
			$filter = array();
		}
		$filter['Kind']=$this->getId();
		return UitstapKind::getUitstapKinderen($filter);
	}
}
?>
