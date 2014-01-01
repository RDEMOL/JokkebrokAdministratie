<?php
require_once(dirname(__FILE__)."/../page.php");
require_once(dirname(__FILE__)."/../../model/werkingen/werkingen.php");

class Instellingen extends Page{
    public function __construct(){
        parent::__construct("Instellingen", "", "instellingen");
        $this->buildContent();
    }
    private function getWerkingenContent(){
        $content = <<<HERE
<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>Omschrijving
    <th>Afkorting
    <th>
</tr>
<tbody>    
HERE;
        $werkingen_ = new Werkingen();
        $werkingen = $werkingen_->getWerkingen();
        foreach($werkingen as $w){
            $content .= "<tr><td>".$w->getNaam()."</td><td>".$w->getAfkorting()."</td><td><button class='btn btn-sm'>Wijzigen</button>&nbsp;<button class='btn btn-sm'>Verwijderen</button></td></tr>";
        }
        $content .= "</tbody></table>";
        $content .= <<<HERE
<button class="btn btn-large btn-primary">Nieuwe werking toevoegen</button>
<script>

</script>
HERE;
        return $content;
    }
    private function getExtraatjesContent(){
        $content = <<<HERE
<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>Omschrijving
    <th>
</tr>
</thead>
<tbody>
HERE;
        $extraatjes_ = new Extraatjes();
        $extraatjes = $extraatjes_->getExtraatjes();
        foreach($extraatjes as $e){
            $content .= "<tr><td>".$e->getNaam()."</td><td><button class='btn btn-sm'>Wijzigen</button>&nbsp;<button class='btn btn-sm'>Verwijderen</button></tr>";
        }
        $content .= <<<HERE
</tbody>
</table>
<button class="btn btn-large btn-primary">Nieuw extraatje toevoegen</button>
<script>
</script>
HERE;
        return $content;
    }
    public function buildContent(){
        $werkingen_content = $this->getWerkingenContent();
        $extraatjes_content = $this->getExtraatjesContent();
        $content = <<<HERE
<div class="row">
<div class="col-md-6">
<div class="panel panel-default">
<div class="panel-heading"><strong>Werkingen</strong></div>
<div class="panel-body">
$werkingen_content
</div>
</div>
</div>
<div class="col-md-6">
<div class="panel panel-default">
<div class="panel-heading"><strong>Extraatjes</strong></div>
<div class="panel-body">
$extraatjes_content
</div>
</div>
</div>
</div>
HERE;
        $this->setContent($content);
    }
}
?>
