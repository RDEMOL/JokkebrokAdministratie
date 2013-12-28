<?php
require_once(dirname(__FILE__)."/session/session.php");
class Model{
	private $session;
	public function __construct(){
		$this->session = new Session($this);
	}
	public function logged_in(){
		return false;
	}
	public function getSession(){
		return $this->session;
	}
}
?>
