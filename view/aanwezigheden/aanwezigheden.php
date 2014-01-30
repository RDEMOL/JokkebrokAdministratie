<?php
require_once (dirname(__FILE__) . "/../page.php");
require_once (dirname(__FILE__) . "/../../model/speelpleindag/speelpleindag.php");
class AanwezighedenPage extends Page {
    public function __construct() {
        parent::__construct("Aanwezigheden","","aanwezigheden");
        $this->buildContent();
    }

    private function getNieuweAanwezigheidModal() {
        $content = <<<HERE
<div class="modal fade" id="nieuweAanwezigheidModal" tabindex="-1" role="dialog" aria-labelledby="nieuweAanwezigheidModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="modal-title">Nieuwe aanwezigheid toevoegen</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline">
                    <div class="row">
                        <label class="control-label" for="voornaam">Voornaam + naam: </label>
                        <input type="text" value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <button type="button" class="btn btn-primary">Toevoegen</button>
            </div>
        </div>
    </div>
</div>
HERE;
        return $content;
    }

    public function buildContent() {
        $vandaag = new SpeelpleinDag();
        $datum = $vandaag->getDatum();
        $content = $this->getNieuweAanwezigheidModal();
        $content .= <<<HERE
        
<div class="row">
    <button class="btn btn-large btn-primary" data-toggle="modal" data-target="#nieuweAanwezigheidModal">Nieuwe aanwezigheid</button>
     <label for="datum">Datum:</label>
        <input id="datum" name="datum" type="text" value="$datum"></input>
        <button id="btnVandaag" class="btn btn-sm">Vandaag</button>
    <div class="pull-right">
        <button id="btnPdf" class="btn">Pdf tonen</button>
    </div>
</div>
<br>
<div class="row">
<table class="table table-striped table-bordered" id="aanwezigheden_tabel">
</table>
</div>
<script>
$(document).ready(function(){
    $('#datum').datepicker().data('datepicker');
});
require(['tabel', 'tabel/kolom'], function(Tabel, Kolom, require){
    var k = new Array();
    k.push(new Kolom('voornaam','Voornaam'));
    k.push(new Kolom('naam','Naam'));
    k.push(new Kolom('werking','Werking'));
    //TODO: insert extraatjes
    k.push(new Kolom('medische_info','Medische info'));
    k.push(new Kolom('andere_info', 'Andere info'));
    k.push(new Kolom('controls', ''));
    var t = new Tabel('index.php?action=data&data=aanwezighedenTabel', k);
    t.setUp($('#aanwezigheden_tabel'));
    var filter = new Object();
    t.laadTabel(filter);
});
</script>
HERE;
        $this->setContent($content);
    }

}
?>
