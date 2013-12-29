<?php
require_once(dirname(__FILE__)."/../page.php");
class Dashboard extends Page{
    public function __construct(){
        parent::__construct("Dashboard", "", "dashboard");
        $this->buildContent();
    }
    public function buildContent(){
        $content = "Dashboard";
        $this->setContent($content);
    }
}
?>
