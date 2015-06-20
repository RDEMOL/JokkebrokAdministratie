<?php
require_once(dirname(__FILE__) . "/../record.class.php");
require_once(dirname(__FILE__) . "/../kinderen/kind.class.php");

class Voogd extends Record
{
    protected function setLocalData($data)
    {
        $this->Voornaam = $data->Voornaam;
        $this->Naam = $data->Naam;
        $this->Opmerkingen = $data->Opmerkingen;
        $this->Telefoon = $data->Telefoon;
    }

    public function getVoornaam()
    {
        return $this->Voornaam;
    }

    public function getNaam()
    {
        return $this->Naam;
    }

    public function getJSONData()
    {
        $db = new Database();
        $query = $db->getPDO()->prepare("SELECT * FROM Voogd WHERE Id= :id ");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    public static function otherVoogdExists($id, $naam, $voornaam){
        if($naam == "" && $voornaam == "")
            return false;
        $query = Database::getPDO()->prepare("SELECT * FROM Voogd WHERE Id <> :id AND Naam = :naam AND Voornaam = :voornaam");
        $query->bindValue(":id", $id);
        $query->bindValue(":naam", $naam);
        $query->bindValue(":voornaam", $voornaam);
        $query->execute();
        return $query->rowCount() > 0;
    }
    protected function insert()
    {
        if(Voogd::otherVoogdExists($this->getId(), $this->getNaam(), $this->getVoornaam())){
            return false;
        }
        $query = Database::getPDO()->prepare("INSERT INTO Voogd (Voornaam, Naam, Opmerkingen, Telefoon) VALUES (:voornaam, :naam, :opmerkingen, :telefoon)");
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':opmerkingen', $this->Opmerkingen, PDO::PARAM_STR);
        $query->bindParam(':telefoon', $this->Telefoon, PDO::PARAM_STR);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }

    protected function update()
    {
        if(Voogd::otherVoogdExists($this->getId(), $this->getNaam(), $this->getVoornaam())){
            return false;
        }
        $query = Database::getPDO()->prepare('UPDATE Voogd SET Naam=:naam, Voornaam=:voornaam, Opmerkingen=:opmerkingen, Telefoon=:telefoon WHERE Id=:id');
        $query->bindParam(':naam', $this->Naam, PDO::PARAM_STR);
        $query->bindParam(':voornaam', $this->Voornaam, PDO::PARAM_STR);
        $query->bindParam(':opmerkingen', $this->Opmerkingen, PDO::PARAM_STR);
        $query->bindParam(':telefoon', $this->Telefoon, PDO::PARAM_STR);
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $res = $query->execute();
        return $res;
    }

    protected function select()
    {
        $query = Database::getPDO()->prepare("SELECT * FROM Voogd WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    protected function delete()
    {
        if (count($this->getKinderen()) > 0) {
            return false;
        }
        $query = Database::getPDO()->prepare("DELETE FROM Voogd WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }

    public function getKinderen()
    {
        $query = Database::getPDO()->prepare("SELECT K.Id as Id FROM Kind K LEFT JOIN KindVoogd KV ON KV.Kind=K.Id WHERE KV.Voogd = :voogd_id");
        $query->bindParam(':voogd_id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        $kinderen = array();
        while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
            $kinderen[] = new Kind($rs->Id);
        }
        return $kinderen;
    }

    protected static function getFilterSQL($filter)
    {
        $sql = "";
        if (isset($filter['VolledigeNaam'])) {
            $sql .= "AND (CONCAT(Naam, ' ', Voornaam) LIKE :volledige_naam ";
            $sql .= " OR CONCAT(Voornaam, ' ', Naam) LIKE :volledige_naam2) ";
        } else {
            if (isset($filter['Naam'])) {
                $sql .= "AND Naam LIKE :naam ";
            }
            if (isset($filter['Voornaam'])) {
                $sql .= "AND Voornaam LIKE :voornaam ";
            }
            /*if (isset($filter['Id'])) {
                $sql .= "AND Id != :id ";
            }*/
        }
        return $sql;
    }

    protected static function applyFilterParameters($query, $filter)
    {
        if (isset($filter['VolledigeNaam'])) {
            $tmp = '%' . $filter['VolledigeNaam'] . '%';
            $query->bindParam(':volledige_naam', $tmp, PDO::PARAM_STR);
            $query->bindParam(':volledige_naam2', $tmp = '%' . $filter['VolledigeNaam'] . '%', PDO::PARAM_STR);
        } else {
            if (isset($filter['Naam'])) {
                $query->bindValue(':naam', $filter['Naam']);
            }
            if (isset($filter['Voornaam'])) {
                $query->bindValue(':voornaam', $filter['Voornaam']);
            }
            /*if (isset($filter['Id'])) {
                $query->bindValue(':id', $filter['Id']);
            }*/
        }
    }

    public static function getVoogden($filter, $max_amount = 0)
    {
        $sql = "SELECT Id FROM Voogd WHERE 1 ";
        $sql .= static::getFilterSQL($filter);
        if (intval($max_amount)) {
            $sql .= " LIMIT " . intval($max_amount);
        }
        $query = Database::getPDO()->prepare($sql);
        static::applyFilterParameters($query, $filter);
        $query->execute();
        $voogden = array();
        while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
            $voogden[] = new Voogd($rs->Id);
        }
        return $voogden;
    }
}

?>