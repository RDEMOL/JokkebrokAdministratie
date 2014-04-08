<?php
require_once(dirname(__FILE__)."/../page.php");
require_once(dirname(__FILE__)."/../../model/werkingen/werking.class.php");

class InstellingenPage extends Page{
    public function __construct(){
        parent::__construct("Instellingen", "", "instellingen");
    }
	public function printContent(){
		include (dirname(__FILE__)."/../../public_html/pages/instellingen/content.html");
	}
}
?>
