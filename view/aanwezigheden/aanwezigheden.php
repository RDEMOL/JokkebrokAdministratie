<?php
require_once(dirname(__FILE__)."/../page.php");
class Aanwezigheden extends Page{
    public function __construct(){
        parent::__construct("Aanwezigheden", "", "aanwezigheden");
        $this->buildContent();
    }
    public function buildContent(){
        $content = "Aanwezigheden";
        $this->setContent($content);
    }
}
?>
