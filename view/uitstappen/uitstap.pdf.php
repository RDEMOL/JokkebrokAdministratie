<?php
require_once (dirname(__FILE__)."/../../helpers/pdf/pdf_generator.class.php");
class UitstapPDF extends PDFGenerator{
	public function __construct($uitstap){
		parent::__construct();
		$this->uitstap = $uitstap;		
	}
	protected function getUitstapHeader(){
		$content = "<h1>".$this->uitstap->getOmschrijving()."</h1>";
		$content .= "<b>Datum: </b>".$this->uitstap->getDatum()."<br>";
		$content .= "<b>Aantal deelnemers: </b>".$this->uitstap->getAantalDeelnemers()."<br>";
		return $content;
	}
	protected function getContent(){
		$content = "";
		$content .= $this->getUitstapHeader();
		$kolommen = array('Nummer', 'Naam', 'Voornaam', 'Werking', 'Geboortejaar');
		$data = array();
		$deelnemers = $this->uitstap->getDeelnemers();
		foreach($deelnemers as $d){
			$data[]=$d->getJSONData();
		}
		$content .= $this->getTable($data, $kolommen);
		return $content;
	}
}
?>