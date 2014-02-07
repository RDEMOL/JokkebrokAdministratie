<?php
require_once(dirname(__FILE__)."/session/session.class.php");
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
