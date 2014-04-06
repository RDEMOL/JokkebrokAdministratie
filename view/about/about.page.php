<?php
require_once (dirname(__FILE__) . "/../page.php");
require_once (dirname(__FILE__) . "/../../model/werkingen/werking.class.php");
class AboutPage extends Page {
    public function __construct() {
        parent::__construct("Over","","about");
        $this->buildContent();
    }

	public function buildContent(){
		$content = <<<HERE
		<p class="text-center">
Concept: Roderick Demol & Floris Kint<br>
Realisatie: Floris Kint
<br>
<br>
<a href='https://github.com/fkint/JokkebrokAdministratie' target="_blank">Deze applicatie op Github</a>
</p>

HERE;
		$this->setContent($content);
	}
}
?>