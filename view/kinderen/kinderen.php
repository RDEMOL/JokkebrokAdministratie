<?php
require_once(dirname(__FILE__)."/../page.php");
class KinderenPage extends Page{
    public function __construct(){
        parent::__construct("Kinderen", "", "kinderen");
        $this->buildContent();
    }
    private function getNieuwKindModal(){
        $content = <<<HERE
<div class="modal fade" id="nieuwKindModal" tabindex="-1" role="dialog" aria-labelledby="nieuwKindModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
<h4 class="modal-title">Nieuw Kind toevoegen</h4>
</div>
<div class="modal-body">
<form class="form-inline">
<div class="row">
<label class="control-label" for="voornaam">Voornaam: </label>
<input type="text" value="">
</div>
<div class="row">
<label for="Naam" class="control-label">Naam: </label>
<input type="text" value="">
</form>
</div>
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
</script>
HERE;
        $this->setContent($content);
    }
}
?>
