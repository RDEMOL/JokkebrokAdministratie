<?php
require_once(dirname(__FILE__)."/../../helpers/database/database.php");
require_once(dirname(__FILE__)."/werking.php");
class Werkingen{
    public function __construct(){
        
    }  
    public function getAmount(){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT COUNT(*) as amount FROM Werking');
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ)->amount;
    }
    public function getWerkingen(){
        $db = new Database();
        $query = $db->getPDO()->prepare('SELECT Id as id FROM Werking');
        $query->execute();
        $werkingen = array();
        while($rs = $query->fetch(PDO::FETCH_OBJ)){
            $werkingen[] = new Werking($rs->id);
        }
        return $werkingen;
    }
}
?>
