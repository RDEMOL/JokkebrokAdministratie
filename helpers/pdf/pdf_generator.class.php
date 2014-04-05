<?php
require_once(dirname(__FILE__)."/../../libs/mpdf/mpdf.php");
abstract class PdfGenerator{
	public function __construct($filename=""){
		if($filename==""){
			$filename = date("Ymd-His").".pdf";
		}
		$this->filename = $filename;
	}
	protected function getTable($data, $columns){
		$content = "";
		$content .= "<table>";
		$content .= "<thead>";
		$content .= "<tr>";
		foreach($columns as $c){
			$content.= "<th>".$c."</th>";
		}
		$content .= "</tr>";
		$content .= "</thead>";
		$content .= "<tbody>";
		$counter = 0;
		foreach($data as $d){
			++$counter;
			$tr = "<tr>";
			foreach($columns as $c){
				$td_content = "";
				if($c == 'Nummer'){
					$td_content = $counter;
				}else{
					$td_content = $d->$c;
				}
				$tr .= "<td>".$td_content."</td>";
			}
			$tr.="</tr>";
			$content .= $tr;
		}
		$content .= "</tbody>";
		$content .= "</table>";
		return $content;
	}
	protected abstract function getContent();
	public function outputPDF(){
		$pdf = new mPDF();
		$pdf->WriteHTML($this->getContent());
		$pdf->Output($this->filename, 'D');
		//echo $this->getContent();
	}
}
?>