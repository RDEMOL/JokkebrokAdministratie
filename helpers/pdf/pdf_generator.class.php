<?php
require_once(dirname(__FILE__)."/../../libs/mpdf/mpdf.php");
class PdfGenerator{
	protected $columns;
	protected $data;
	public function __construct($data, $columns, $filename=""){
		$this->data = $data;
		$this->columns = $columns;
		if($filename==""){
			$filename = date("Ymd-His").".pdf";
		}
		$this->filename = $filename;
	}
	protected function getContent(){
		$content = "";
		$content .= "<table>";
		$content .= "<thead>";
		$content .= "<tr>";
		foreach($this->columns as $c){
			$content.= "<th>".$c."</th>";
		}
		$content .= "</tr>";
		$content .= "</thead>";
		$content .= "<tbody>";
		$counter = 0;
		foreach($this->data as $d){
			++$counter;
			$tr = "<tr>";
			foreach($this->columns as $c){
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
	public function outputPDF(){
		$pdf = new mPDF();
		$pdf->WriteHTML($this->getContent());
		$pdf->Output($this->filename, 'D');
	}
}
?>