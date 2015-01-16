<?php
require_once(dirname(__FILE__) . "/../../config/config.inc.php");

class Database
{
    private static $initialized = false;
    private static $pdo;

    private static function initConnection()
    {
        try {
            Database::$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=UTF8', DB_USER, DB_PASS);
            Database::$initialized = true;
        } catch (PDOException $ex) {
            echo "message: " . $ex->getMessage();
            echo "Error! Kan geen verbinding maken met de database";
            die();
        }
    }

    public static function getPDO()
    {
        if (!Database::$initialized) {
            Database::initConnection();
        }
        return Database::$pdo;
    }

    public function getDatabaseDump()
    {
        $command = 'mysqldump --user=' . DB_USER . ' --password=' . DB_PASS . ' --host=' . DB_HOST . ' ' . DB_NAME;
        return shell_exec($command);
    }
}

?>