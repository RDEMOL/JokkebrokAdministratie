<?php
class SpeelpleinDag{
    private $datum;
    public function __construct($datum=NULL){
        if($datum == NULL){
            $datum = date('d-m-Y');
        }
        $this->datum = $datum;
    }
    public function getDatum(){
        return $this->datum;
    }
    public function getFullDatum(){
        setlocale (LC_TIME, "nl_BE.UTF8");
        $d = strtotime($this->datum);
        return strftime("%A %e %B %Y", $d);
    }
}
?>