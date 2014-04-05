<?php
require_once (dirname(__FILE__)."/../../helpers/pdf/pdf_generator.class.php");
require_once (dirname(__FILE__)."/../../model/kinderen/kind.class.php");
class KinderenPDF extends PDFGenerator{
	public function __construct($filter, $order, $kolommen){
		parent::__construct();
		$this->filter = $filter;
		$this->order = $order;
		$this->kolommen = $kolommen;		
	}
	protected function getKinderenHeader(){
		$content = "<h1>Kinderen</h1>";
		return $content;
	}
	protected function getContent(){
		$content = "";
		$content .= $this->getKinderenHeader();
		$kinderen = Kind::getKinderen($this->filter, 0, $this->order);
		$data = array();
		foreach($kinderen as $k){
			$data[] = $k->getJSONData();
		}
		$content .= $this->getTable($data, $this->kolommen);
		return $content;
	}
}
?>