<?php
require_once(dirname(__FILE__)."/../../helpers/database/database.php");
require_once(dirname(__FILE__)."/extraatje.php");
class Extraatjes{
    public function __construct(){
        
    }  
    public function getAmount(){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT COUNT(*) as amount FROM Extraatje');
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ)->amount;
    }
    public function getExtraatjes(){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT Id as id FROM Extraatje');
        $query->execute();
        $extraatjes = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $extraatjes[] = new Extraatje($rs->id);
        }
        return $extraatjes;
    }
}
?>
