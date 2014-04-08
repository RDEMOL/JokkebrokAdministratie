<?php
require_once (dirname(__FILE__) . "/../page.php");
class AboutPage extends Page {
    public function __construct() {
        parent::__construct("Over","","about");
    }

	public function printContent(){
		include (dirname(__FILE__) . "/../../public_html/pages/about/content.html");
	}
}
?>