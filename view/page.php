<?php
class Page {
	private $title,$content,$current_tab;
	public function __construct($title,$content,$current_tab) {
		$this->title = $title;
		$this->content = $content;
		$this->current_tab = $current_tab;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setCurrentTab($tab) {
		$this->current_tab = $tab;
	}

	public function getCurrentTab() {
		return $this->current_tab;
	}

	public function setContent($content) {
		$this->content = $content;
	}

	public function printContent() {
		echo $this->content;
	}

	public function output() {
		require (dirname(__FILE__) . "/../public_html/template.php");
	}

}
?>
