<?php
require_once(dirname(__FILE__)."/../page.php");
class Kinderen extends Page{
    public function __construct(){
        parent::__construct("Kinderen", "", "kinderen");
        $this->buildContent();
    }
    public function buildContent(){
        $content = "Kinderen";
        $this->setContent($content);
    }
}
?>
