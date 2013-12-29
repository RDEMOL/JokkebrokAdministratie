<?php
require_once(dirname(__FILE__)."/../page.php");
class Uitstappen extends Page{
    public function __construct(){
        parent::__construct("Uitstappen", "", "uitstappen");
        $this->buildContent();
    }
    public function buildContent(){
        $content = "Uitstappen";
        $this->setContent($content);
    }
}
?>
