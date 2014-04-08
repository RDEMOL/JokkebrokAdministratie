<?php
require_once (dirname(__FILE__) . "/../page.php");
require_once (dirname(__FILE__) . "/../../model/werkingen/werking.class.php");
class KinderenPage extends Page {
    public function __construct() {
        parent::__construct("Kinderen","","kinderen");
    }
	public function printContent(){
		include (dirname(__FILE__)."/../../public_html/pages/kinderen/content.html");
	}
}
?>

