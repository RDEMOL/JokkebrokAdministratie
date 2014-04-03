<?php
require_once(dirname(__FILE__)."/../page.php");
require_once(dirname(__FILE__)."/../../model/werkingen/werking.class.php");
require_once(dirname(__FILE__)."/../../model/extraatjes/extraatje.class.php");
require_once(dirname(__FILE__)."/../../model/speelpleindag/speelpleindag.class.php");
require_once(dirname(__FILE__)."/../../model/extraatjes/extraatje_aanwezigheid.class.php");
require_once(dirname(__FILE__)."/../../model/uitstappen/uitstap.class.php");


class DashboardPage extends Page{
    public function __construct(){
        parent::__construct("Dashboard", "", "dashboard");
        $this->buildContent();
    }
    private function getAanwezighedenContent(){
        $vandaag = new SpeelpleinDag();
        $werkingen = Werking::getWerkingen();
        $werkingen_amount = count($werkingen);
        $extraatjes = Extraatje::getExtraatjes();
        $werkingen_ths = "";
        foreach($werkingen as $w){
            $werkingen_ths .= "<th>".$w->getOmschrijving()."</th>";
        }
        $werkingen_extraatjes_tbody = "";
        $extraatje_index = 0;
        foreach($extraatjes as $e){
            ++$extraatje_index;
            $current_line = "<tr>";
            if($extraatje_index == 1){
                $current_line.="<td rowspan=\"".count($extraatjes)."\">Extra's</td>";
            }
            $current_line .= "<td>".$e->getOmschrijving()."</td>";
            foreach($werkingen as $w){
                $filter = array();
                $filter['Datum']=$vandaag->getDatumForDatabase();
                $filter['Werking']=$w->getId();
                $filter['Extraatje']=$e->getId();
                $amount  = ExtraatjeAanwezigheid::countExtraatjeAanwezigheden($filter);
                $current_line .= "<td>$amount</td>";
            }
            $filter = array();
            $filter['Datum']=$vandaag->getDatumForDatabase();
            $filter['Extraatje']=$e->getId();
            $amount = ExtraatjeAanwezigheid::countExtraatjeAanwezigheden($filter);
            $current_line .= "<td>$amount</td>";
            $current_line .= "</tr>";
            $werkingen_extraatjes_tbody .= $current_line;
        }
        $werkingen_footer = "<tr><th colspan='2'>Aanwezige kinderen";
        $sum = 0;
        foreach($werkingen as $w){
            $filter = array();
            $filter['Datum']=$vandaag->getDatumForDatabase();
            $filter['Werking']=$w->getId();
            $amount = Aanwezigheid::countAanwezigheden($filter);
            $werkingen_footer.="<th>".$amount;
            $sum += $amount;
        }
        $werkingen_footer.= "<th>$sum</tr>";
        $content = <<<HERE
<table class="table table-bordered">
<thead>
    <tr>
        <th colspan="2" rowspan="2">
        <th colspan="$werkingen_amount" class="text-center">Werking
        <th rowspan="2">Totaal
    </tr>
    <tr>
        $werkingen_ths
    </tr>
</thead>
<tbody>
$werkingen_extraatjes_tbody
</tbody>
$werkingen_footer
</table>
HERE;
        return $content;
    }
	private function getUitstappenContent(){
		$uitstappen_rows = "";
		$uitstappen = Uitstap::getUitstappen();
		foreach($uitstappen as $u){
			$uitstappen_rows.="<tr><td><input name=\"Id\" type=\"hidden\" value=\"".$u->getId()."\">".$u->getId()."</td><td>".$u->getOmschrijving()."</td><td>".$u->getAantalDeelnemers()."</td></tr>\n";
		}
		$content = <<<HERE
<style type="text/css">
table#UitstapOverzicht tr :hover{
    cursor:pointer;
}
</style>
<table class="table table-bordered table-hover" id="UitstapOverzicht">
<thead>
	<tr>
		<th>Datum</th>
		<th>Beschrijving</th>
		<th>#</th>
	</tr>
</thead>
<tbody>
$uitstappen_rows
</tbody>
</table>
<script>

function uitstap_clicked(id){
	window.location.href = '?page=uitstappen&UitstapId='+id;
}
$('table#UitstapOverzicht').on("click", "tr", function(){
	console.log("click!");
	uitstap_clicked($(this).find('input[name=Id]').val());	
});
</script>
HERE;
		return $content;		
	}
    public function buildContent(){
        $aanwezighedenContent = $this->getAanwezighedenContent();
		$uitstappenContent = $this->getUitstappenContent();
        $content = <<<HERE
<div class="row">
<div class="col-md-6">
<div class="panel panel-default">
<div class="panel-heading"><strong>Aanwezigheden</strong></div>
<div class="panel-body">
$aanwezighedenContent
</div>
</div>
</div>
<div class="col-md-6">
<div class="panel panel-default">
<div class="panel-heading"><strong>Uitstappen</strong></div>
<div class="panel-body">
$uitstappenContent
</div>
</div>
</div>
</div>
HERE;
        $this->setContent($content);
    }
}
?>
