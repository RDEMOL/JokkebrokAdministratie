<?php
require_once(dirname(__FILE__)."/../page.php");
class Instellingen extends Page{
    public function __construct(){
        parent::__construct("Instellingen", "", "instellingen");
        $this->buildContent();
    }
    public function buildContent(){
        $content = "Instellingen";
        $this->setContent($content);
    }
}
?>
