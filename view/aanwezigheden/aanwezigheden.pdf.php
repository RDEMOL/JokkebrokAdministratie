<?php
require_once (dirname(__FILE__)."/../../helpers/pdf/pdf_generator.class.php");
require_once (dirname(__FILE__)."/../../model/aanwezigheden/aanwezigheid.class.php");
class AanwezighedenPDF extends PDFGenerator{
	public function __construct($filter, $order, $kolommen, $title){
		parent::__construct();
		$this->filter = $filter;
		$this->order = $order;
		$this->kolommen = $kolommen;
		$this->title = $title;
	}
	protected function getAanwezighedenHeader(){
		$content = "<h1>".$this->title."</h1>";
		return $content;
	}
	protected function getContent(){
		$content = "";
		$content .= $this->getAanwezighedenHeader();
		$aanwezigheden = Aanwezigheid::getAanwezigheden($this->filter, $this->order);
		$data = array();
		foreach($aanwezigheden as $a){
			$data[] = $a->getJSONData();
		}
		$content .= $this->getTable($data, $this->kolommen);
		return $content;
	}
}
?>