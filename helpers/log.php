<?php
require_once(dirname(__FILE__) . '/database/database.php');

class Log
{
    public static function writeLog($title, $value)
    {
        $sql = "INSERT INTO Log(Title, Value) VALUES(:title, :value)";
        $statement = Database::getPDO()->prepare($sql);
        $statement->bindParam(':title', $title);
        $statement->bindParam(':value', $value);
        $statement->execute();
    }
}

?>
