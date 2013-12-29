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
}
?>