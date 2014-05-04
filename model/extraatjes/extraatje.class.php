<?php
require_once(dirname(__FILE__)."/../record.class.php");
class Extraatje extends Record{
    protected function setLocalData($data){
        $this->Omschrijving = $data->Omschrijving;
    }
    protected function insert(){
        $query = Database::getPDO()->prepare("INSERT INTO Extraatje (Omschrijving) VALUES (:omschrijving)");
        $query->bindParam(':omschrijving', $this->Omschrijving, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }
    protected function update(){
        $query = Database::getPDO()->prepare("UPDATE Extraatje SET Omschrijving=:omschrijving WHERE Id=:id");
        $query->bindParam(':omschrijving', $this->Omschrijving, PDO::PARAM_STR);
        $query->bindParam(':id', $this->getId(), PDO::PARAM_INT);
        $query->execute();
    }
    protected function select(){
        $query = Database::getPDO()->prepare("SELECT * FROM Extraatje WHERE Id = :id");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    protected function delete(){
    	if(count($this->getExtraatjeAanwezigheden()) > 0){
    		return false;
    	}
        $query = Database::getPDO()->prepare("DELETE FROM Extraatje WHERE Id = :id");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }
	public function getExtraatjeAanwezigheden(){
		$filter = array();
		$filter['Extraatje'] = $this->getId();
		return ExtraatjeAanwezigheid::getExtraatjeAanwezigheden($filter);
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