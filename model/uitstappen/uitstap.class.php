<?php
require_once(dirname(__FILE__) . "/../record.class.php");
require_once(dirname(__FILE__) . "/uitstap_kind.class.php");

class Uitstap extends Record
{
    protected function setLocalData($data)
    {
        $this->Datum = $data->Datum;
        $this->Omschrijving = $data->Omschrijving;
        $this->AanwezigheidZichtbaar = $data->AanwezigheidZichtbaar;
        $this->DashboardZichtbaar = $data->DashboardZichtbaar;
    }

    public function getOmschrijving()
    {
        return $this->Omschrijving;
    }

    public function getDatum()
    {
        return $this->Datum;
    }

    public function getJSONData()
    {
        $db = new Database();
        $query = $db->getPDO()->prepare("SELECT * FROM Uitstap WHERE Id= :id ");
        $id = $this->getId();
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    protected function insert()
    {
        $query = Database::getPDO()->prepare("INSERT INTO Uitstap (Datum, Omschrijving, AanwezigheidZichtbaar, DashboardZichtbaar) VALUES (:datum, :omschrijving, :aanwezigheid_zichtbaar, :dashboard_zichtbaar)");
        $query->bindParam(':datum', $this->Datum, PDO::PARAM_STR);
        $query->bindParam(':omschrijving', $this->Omschrijving, PDO::PARAM_STR);
        $query->bindParam(':aanwezigheid_zichtbaar', $this->AanwezigheidZichtbaar, PDO::PARAM_BOOL);
        $query->bindParam(':dashboard_zichtbaar', $this->DashboardZichtbaar, PDO::PARAM_BOOL);
        $query->execute();
        return Database::getPDO()->lastInsertId();
    }

    protected function update()
    {
        $query = Database::getPDO()->prepare('UPDATE Uitstap SET Datum=:datum, Omschrijving=:omschrijving, DashboardZichtbaar=:dashboard_zichtbaar, AanwezigheidZichtbaar=:aanwezigheid_zichtbaar WHERE Id=:id');
        $query->bindParam(':datum', $this->Datum, PDO::PARAM_STR);
        $query->bindParam(':omschrijving', $this->Omschrijving, PDO::PARAM_STR);
        $query->bindParam(':aanwezigheid_zichtbaar', $this->AanwezigheidZichtbaar, PDO::PARAM_BOOL);
        $query->bindParam(':dashboard_zichtbaar', $this->DashboardZichtbaar, PDO::PARAM_BOOL);
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $res = $query->execute();
        return $res;
    }

    protected function select()
    {
        $query = Database::getPDO()->prepare("SELECT * FROM Uitstap WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    protected function delete()
    {
        if ($this->getAantalDeelnemers() > 0) {
            return false;
        }
        $query = Database::getPDO()->prepare("DELETE FROM Uitstap WHERE Id = :id");
        $query->bindParam(':id', $this->Id, PDO::PARAM_INT);
        return $query->execute();
    }

    protected static function getFilterSQL($filter)
    {
        $sql = "";
        if (isset($filter['AanwezigheidZichtbaar'])) {
            $sql .= " AND AanwezigheidZichtbaar = :aanwezigheid_zichtbaar ";
        }
        if (isset($filter['DashboardZichtbaar'])) {
            $sql .= " AND DashboardZichtbaar = :dashboard_zichtbaar ";
        }
        return $sql;
    }

    protected static function applyFilterParameters($query, $filter)
    {
        if (isset($filter['DashboardZichtbaar'])) {
            $query->bindParam(':dashboard_zichtbaar', $filter['DashboardZichtbaar'], PDO::PARAM_BOOL);
        }
        if (isset($filter['AanwezigheidZichtbaar'])) {
            $query->bindParam(':aanwezigheid_zichtbaar', $filter['AanwezigheidZichtbaar'], PDO::PARAM_BOOL);
        }
    }

    protected static function getFilterJoinsSQL($filter)
    {
        $slq = "";
        return $sql;
    }

    protected static function getOrderSQL($order)
    {
        if ($order == null) {
            return;
        }
        $sql = "";
        if (isset($order['Datum'])) {
            $sql .= " ORDER BY Datum ";
            if ($order['Datum'] == 'asc') {
                $sql .= "ASC ";
            } else {
                $sql .= "DESC ";
            }
        }
        return $sql;
    }

    public static function getUitstappen($filter, $order = null)
    {
        $sql = "SELECT * FROM Uitstap WHERE 1 ";
        $sql .= static::getFilterSQL($filter);
        $sql .= static::getOrderSQL($order);
        $query = Database::getPDO()->prepare($sql);
        static::applyFilterParameters($query, $filter);
        $query->execute();
        $uitstappen = array();
        while ($rs = $query->fetch(PDO::FETCH_OBJ)) {
            $uitstappen[] = new Uitstap($rs);
        }
        return $uitstappen;
    }

    public function getAantalDeelnemers()
    {
        return count($this->getDeelnemers());
    }

    public function getDeelnemers()
    {
        $filter = array('Uitstap' => $this->getId());
        return UitstapKind::getUitstapKinderen($filter);
    }

    public function isIngeschreven($kind_id)
    {
        $deelnemers = $this->getDeelnemers();
        foreach ($deelnemers as $k) {
            if ($k->getKindId() == $kind_id)
                return true;
        }
        return false;
    }

    public function addKind($kind_id)
    {
        UitstapKind::addDeelname($this->getId(), $kind_id);
    }

    public function removeKind($kind_id)
    {
        UitstapKind::removeDeelname($this->getId(), $kind_id);
    }
}

?>