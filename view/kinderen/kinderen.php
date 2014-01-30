<?php
require_once(dirname(__FILE__)."/../page.php");
require_once(dirname(__FILE__)."/../../model/werkingen/werkingen.php");
class KinderenPage extends Page{
    public function __construct(){
        parent::__construct("Kinderen", "", "kinderen");
        $this->buildContent();
    }
    private function getWerkingenSelect(){
        $opties = "";
        $werkingen_ = new Werkingen();
        $werkingen = $werkingen_->getWerkingen();
        foreach($werkingen as $w){
            $opties .= "<option value=\"".$w->getId()."\">".$w->getAfkorting()." - ".$w->getNaam()."</option>";
        }
        $content = <<<HERE
<select name="werking" class="form-control">
$opties
</select>
HERE;
        return $content;
    }
    private function getNieuwKindModal(){
        $werkingen_select = $this->getWerkingenSelect();
        $content = <<<HERE
<div class="modal fade" id="nieuwKindModal" tabindex="-1" role="dialog" aria-labelledby="nieuwKindModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="modal-title">Nieuw Kind toevoegen</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline" id="nieuwKindForm">
                    <div class="row">
                        <label class="control-label" for="voornaam">Voornaam: </label>
                        <input type="text" name="voornaam" value="">
                    </div>
                    <div class="row">
                        <label for="Naam" class="control-label">Naam: </label>
                        <input type="text" name="naam" value="">
                    </div>
                    <div class="row">
                        <label class="control-label" for="geboortejaar">Geboortejaar: </label>
                        <input type="text" name="geboortejaar" value="">
                    </div>
                    <div class="row">
                        <label class="control-label" for="werking">Werking*: </label>
                        $werkingen_select
                    </div> 
                    <div class="row">
                        <i>*Deze werking is de standaardinstelling bij de aanwezigheden</i>
                    </div>
                    <div class="row">
                        TODO: insert voogd informatie
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <button type="button" class="btn btn-primary" id="submitNieuwKind">Toevoegen</button>
            </div>
        </div>
    </div>
</div>
HERE;
        return $content;
    }
    public function buildContent(){
        $content = $this->getNieuwKindModal();
        $content .= <<<HERE
<div class="row">
    <button class="btn btn-large btn-primary" data-toggle="modal" data-target="#nieuwKindModal">Nieuw kind</button>
    <div class="pull-right">
        <button id="btnPdf" class="btn">Pdf tonen</button>
    </div>
</div>
<br>
<div class="row">
<table class="table table-striped table-bordered" id="kinderen_tabel">
</table>
</div>
<script>
require(['tabel', 'tabel/kolom'], function(Tabel, Kolom, require){
    var k = new Array();
    k.push(new Kolom('voornaam','Voornaam'));
    k.push(new Kolom('naam','Naam'));
    k.push(new Kolom('werking','Werking'));
    k.push(new Kolom('medische_info','Medische info'));
    k.push(new Kolom('andere_info', 'Andere info'));
    k.push(new Kolom('controls', ''));
    var t = new Tabel('index.php?action=data&data=kinderenTabel', k);
    t.setUp($('#kinderen_tabel'));
    var filter = new Object();
    t.laadTabel(filter);
});
$(document).ready(function(){
   $('#nieuwKindForm').submit(function(){
        console.log("submitting!");
        console.log("form data = "+$('#nieuwKindForm').serialize()); 
        $.post('index.php?action=nieuwKind', $('#nieuwKindForm').serializeArray(), function(res){
           console.log("got this from the server: "+res); 
        });
        return false;
   });
   $('#submitNieuwKind').click(function(){
       $('#nieuwKindForm').submit();
   });
});
</script>
HERE;
        $this->setContent($content);
    }
}
?>
