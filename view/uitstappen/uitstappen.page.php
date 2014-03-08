<?php
require_once(dirname(__FILE__)."/../page.php");
class UitstappenPage extends Page{
    public function __construct(){
        parent::__construct("Uitstappen", "", "uitstappen");
        $this->buildContent();
    }
    public function getUitstapModal(){
        $content = <<<HERE
<div class="modal fade" id="UitstapModal" tabindex="-1" role="dialog" aria-labelledby="UitstapModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="modal-title">Nieuwe uitstap toevoegen</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline">
                    <input type="hidden" name="Id" value="0">
                    <div class="row">
                        <label class="control-label" for="Datum">Datum: </label>
                        <input type="text" value="" name="Datum">
                    </div>
                    <div class="row">
                        <label for="Omschrijving" class="control-label">Omschrijving: </label>
                        <input type="text" name="Omschrijving" value="">
                    </div>
                    <div class="row">
                        <label for="Actief" class="control-label">Actief: </label>
                        <input type="checkbox" name="Actief" checked>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <button type="button" class="btn btn-primary" id="btnUitstapOpslaan">Opslaan</button>
            </div>
        </div>
    </div>
</div>
HERE;
        return $content;
    }
    public function buildContent(){
        $content = $this->getUitstapModal();
        $content .= <<<HERE
<style type="text/css">
tr.uitstap :hover{
    cursor:pointer;
}
</style>
<div class="row">
<div class="col-md-4">
<div class="panel panel-default">
<div class="panel-heading">
<strong>Uitstapoverzicht</strong>
</div>
<div class="panel-body">
<button class="btn btn-primary" id="btnNieuweUitstap"><span class="glyphicon glyphicon-plus"></span>Nieuwe uitstap</button><br>
<table class="table table-hover table-bordered" id="UitstapOverzicht">
</table>
</div>
</div>
</div>
<div class="col-md-8">
<div class="panel panel-default">
<div class="panel-heading">
<strong>Uitstapdetails</strong>
</div>
<div class="panel-body text-center">

<em>details van de uitstap</em>

</div>
</div>
</div>
</div>
<script>
require(['tabel', 'tabel/kolom', 'tabel/control', 'tabel/controls_kolom', 'tabel/filter_rij', 'tabel/filter_veld'], function(Tabel, Kolom, Control, ControlsKolom, FilterRij, FilterVeld, require){
    function wijzig_uitstap(data){
        clearUitstapForm();
        $('#UitstapModal input[name=Omschrijving]').val(data['Omschrijving']);
        $('#UitstapModal input[name=Id]').val(data['Id']);
        $('#UitstapModal').modal('show');
    };
    function verwijder_uitstap(data){
        console.log("verwijder uitstap: "+JSON.stringify(data));
        $('#VerwijderUitstapModal input[name=Id]').val(data['Id']);
        $('#VerwijderUitstapModal').modal('show');
    };
    function clearUitstapForm(){
      $('#UitstapModal input[name=Omschrijving]').val('');
      $('#UitstapModal input[name=Id]').val('0');
    }
    function nieuwe_uitstap(){
        clearUitstapForm();
      $('#UitstapModal').modal('show');  
    };
    var k = new Array();
    k.push(new Kolom('Datum','Datum'));
    k.push(new Kolom('Omschrijving', 'Omschrijving'));
    k.push(new Kolom('Actief', 'Actief'));
    
    var uitstappen_tabel = new Tabel('index.php?action=data&data=uitstappenTabel', k);
    uitstappen_tabel.setUp($('table#UitstapOverzicht'));
    $('#btnNieuweUitstap').click(function(){
       nieuwe_uitstap(); 
    });
    $(document).ready(function(){
        uitstappen_tabel.laadTabel();
    });
    $('#btnUitstapOpslaan').click(function(){
        $('#UitstapModal form').submit();
    });
    $('#UitstapModal form').submit(function(){
        console.log("serialized gives: "+$('#UitstapModal form').serialize());
       $.post('index.php?action=updateUitstap', $('#UitstapModal form').serialize(), function(r){
           r = $.trim(r);
           console.log("update uitstap result: "+r);
           if(r == "1"){
                uitstappen_tabel.laadTabel();
                $('#UitstapModal').modal('hide');
           }else{
               console.log("update Uitstap mislukt");
           }
       });
       return false;
    });
    $('#btnVerwijderUitstap').click(function(){
       console.log("data: "+$('#VerwijderUitstapForm').serialize());
       $.post('index.php?action=removeUitstap', $('#VerwijderUitstapForm').serialize(), function(res){
           res = $.trim(res);
            if(res == "1"){
                $('#VerwijderUitstapModal').modal('hide');
                uitstappen_tabel.laadTabel();
            }else{
                console.log("uitstap verwijderen mislukt, error code: "+res);
            }
       });
   });
});
</script>
HERE;
        $this->setContent($content);
    }
}
?>
