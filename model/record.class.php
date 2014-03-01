<?php
require_once(dirname(__FILE__)."/../helpers/database/database.php");
abstract class Record{
    private $in_database = false;
    protected $Id;
    //new record (not in database) should have id = 0
    public function __construct($data){
        if(!is_object($data)){
            $this->Id = $data;
            $this->updateLocal();
        }else{
            $this->Id = $data->Id;
            $this->setLocalData($data);
        }
    }
    private function isInDatabase(){
        return $this->Id != 0 && $this->Id != null;
    }
    /**To implement:
     protected function setLocalData($data);
     protected function insert();
     protected function update();
     protected function select();
     protected function delete();
     */
    protected abstract function setLocalData($data);
    protected abstract function insert();
    protected abstract function update();
    protected abstract function select();
    protected abstract function delete();
    public function updateDatabase(){
        if($this->isInDatabase()){
            $this->update();
        }else{
            $this->Id = $this->insert();
        }
        return true;
    }
    public function updateLocal(){
        if(!$this->isInDatabase()){
            throw new Exception("Can't update a record that's not in the database.");
        }
        $this->setLocalData($this->select());
    }
    public function deleteFromDatabase(){
        return $this->delete();
    }
    public function getId(){
        return $this->Id;
    }
}
?>