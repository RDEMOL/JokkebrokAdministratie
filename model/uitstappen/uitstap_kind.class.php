<?php
require_once(dirname(__FILE__) . "/../record.class.php");

class UitstapKind extends Record
{
    protected function setLocalData($data)
    {
        $this->KindId = $data->Kind;
        $this->UitstapId = $data->Uitstap;
    }

    public function getKindId()
    {
        return $this->KindId;
    }

    public function getUitstapId()
    {
        return $this->UitstapId;
    }

    public function getJSONData()
    {
        $db = new Database();
        $query = $db->getPDO()->prepare("SELECT UK.Id as Id, K.Naam as Naam, K.Voornaam as Voornaam, W.Afkorting as Werking, K.Geboortejaar as Geboortejaar FROM UitstapKind UK LEFT JOIN Kind K ON UK.Kind=K.Id LEFT JOIN Werking W ON K.DefaultWerking = W.Id WHERE UK.Id= :id ");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    protected function insert()
    {
        $query = Database::getPDO()->prepare("INSERT INTO UitstapKind (Uitstap, Kind) VALUES (:uitstap_id, :kind_id)");
        $query->bindParam(':uitstap_id', $this->UitstapId, PDO::PARAM_INT);
        $query->bindParam(':kind_id', $this->KindId, PDO::PARAM_INT);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }

    protected function update()
    {
        $query = Database::getPDO()->prepare('UPDATE UitstapKind SET Uitstap=:uitstap_id, Kind=:kind_id WHERE Id=:id');
        $query->bindParam(':uitstap_id', $this->UitstapId, PDO::PARAM_STR);
        $query->bindParam(':kind_id', $this->KindId, PDO::PARAM_INT);
        $res = $query->execute();
        return $res;
    }

    protected function select()
    {
        $query = Database::getPDO()->prepare("SELECT * FROM UitstapKind WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    protected function delete()
    {
        $query = Database::getPDO()->prepare("DELETE FROM UitstapKind WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }

    protected static function getFilterSQL($filter)
    {
        $sql = "";
        if (isset($filter['Uitstap'])) {
            $sql .= "AND Uitstap = :uitstap_id ";
        }
        if (isset($filter['Kind'])) {
            $sql .= "AND Kind = :kind_id ";
        }
        return $sql;
    }

    protected static function applyFilterParameters($query, $filter)
    {
        if (isset($filter['Uitstap'])) {
            $query->bindParam(':uitstap_id', $filter['Uitstap']);
        }
        if (isset($filter['Kind'])) {
            $query->bindParam(':kind_id', $filter['Kind']);
        }
    }

    public static function getUitstapKinderen($filter = array())
    {
        $sql = "SELECT * FROM UitstapKind WHERE 1 ";
        $sql .= static::getFilterSQL($filter);
        $query = Database::getPDO()->prepare($sql);
        static::applyFilterParameters($query, $filter);
        $query->execute();
        $uitstap_kinderen = array();
        while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
            $uitstap_kinderen[] = new UitstapKind($rs);
        }
        return $uitstap_kinderen;
    }

    public function getUitstap()
    {
        return new Uitstap($this->UitstapId);
    }

    public static function getDeelname($uitstap_id, $kind_id)
    {
        $filter = array();
        $filter['Kind'] = $kind_id;
        $filter['Uitstap'] = $uitstap_id;
        $uitstappen = static::getUitstapKinderen($filter);
        if (count($uitstappen) == 0)
            return null;
        return $uitstappen[0];
    }

    public static function addDeelname($uitstap_id, $kind_id)
    {
        if (static::getDeelname($uitstap_id, $kind_id) == null) {
            $d = new stdClass();
            $d->Uitstap = $uitstap_id;
            $d->Kind = $kind_id;
            $uk = new UitstapKind($d);
            $uk->updateDatabase();
        }
    }

    public static function removeDeelname($uitstap_id, $kind_id)
    {
        $d = static::getDeelname($uitstap_id, $kind_id);
        if ($d != null) {
            $d->deleteFromDatabase();
        }
    }
}

?>