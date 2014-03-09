<?php
require_once(dirname(__FILE__)."/../page.php");
class UitstappenPage extends Page{
    public function __construct(){
        parent::__construct("Uitstappen", "", "uitstappen");
        $this->buildContent();
    }
    
    private function getUitstapModal(){
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
table#UitstapOverzicht tr :hover{
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
<div class="panel-body" id="UitstapDetailsDiv">
<div id="UitstapEigenschappenDiv" style="display:none;">
<div class="panel panel-default">
<div class="panel-body">
<button type="button" class="btn btn-primary" id="btnUitstapBewerken">Uitstap Bewerken</button><br>
<form class="form">
<label class="control-label" for="VolledigeNaamKind">Kind toevoegen: </label><br>
<input type="text" value="" class="typeahead form-control" name="VolledigeNaamKind"><br>
<button type="button" id="btnKindToevoegen" class="btn btn-primary">Toevoegen</button>
<br>
</form>
</div>
</div>

<style type="text/css">
/*adapted from typeahead examples*/
typeahead, .tt-query, .tt-hint {
    border-radius: 8px 8px 8px 8px;
    padding: 8px 12px;
    width: 396px;
}
.typeahead {
    background-color: #FFFFFF;
}
.tt-query {
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
}
.tt-hint {
    color: #999999;
}
.tt-dropdown-menu {
    background-color: #FFFFFF;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 8px 8px 8px 8px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    padding: 8px 0;
    width: 422px;
}
.tt-suggestion {
    line-height: 24px;
    padding: 3px 20px;
}
.tt-suggestion.tt-cursor {
    background-color: #0097CF;
    color: #FFFFFF;
    cursor:pointer;
}
.tt-suggestion p {
    margin: 0;
}
            </style>
</div>
<div id="UitstapDeelnamesDiv">
<div class="text-center" width="100%">
<em>details van de uitstap</em>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
require(['tabel', 'tabel/kolom', 'tabel/control', 'tabel/controls_kolom', 'tabel/filter_rij', 'tabel/filter_veld', 'tabel/row_click_listener'], function(Tabel, Kolom, Control, ControlsKolom, FilterRij, FilterVeld, RowClickListener, require){
    function voeg_kind_toe(kind){
        
    };
    var suggesties = new Bloodhound({
       datumTokenizer:function(d){return Bloodhound.tokenizers.whitespace(d.value); },
       queryTokenizer: Bloodhound.tokenizers.whitespace,
       remote:{
           url:'index.php?action=data&data=kinderenSuggesties&query=%QUERY',
           filter: function(kind){
               console.log("bloodhound received this data: "+JSON.stringify(kind));
               return $.map(kind.content, function(k){
                  return { 'display_value':(k.Voornaam+" "+k.Naam), 'id':k.Id/*, 'Voogden':k.Voogden*/, 'DefaultWerkingId': k.DefaultWerkingId}; 
               });
           }
       }
    });
    suggesties.initialize();
    $('input[name="VolledigeNaamKind"]').typeahead(null, {
        displayKey:'display_value',
        source: suggesties.ttAdapter()
    }).bind('typeahead:selected', function(obj, kind, dataset_name){
        voeg_kind_toe(kind);
    });
    function wijzig_uitstap(data){
        clearUitstapForm();
        $('#UitstapModal input[name=Datum]').val(data['Datum']);
        $('#UitstapModal input[name=Omschrijving]').val(data['Omschrijving']);
        $('#UitstapModal input[name=Id]').val(data['Id']);
        $('#UitstapModal input[name=Actief]').prop('checked', data['Actief']=='1');
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
    var uitstap_deelnemers_tabel = null;
    function verwijder_deelname(data){
        
    };
    function laad_uitstap(data){
        var eigenschappen_div = $('#UitstapEigenschappenDiv').css('display', 'inline');
        eigenschappen_div.find('#btnUitstapBewerken').unbind('click').click(function(){
            wijzig_uitstap(data);
            return false;
        });
        var div = $('#UitstapDeelnamesDiv').empty();
        //add omschrijving/datum/actief
        //add table
        var tabel_div = $('<div>');
        div.append(tabel_div);
        var tabel = $('<table>').addClass('table table-hover table-bordered');
        tabel_div.append(tabel);
        var uitstap_deelnemers_kolommen = new Array();
        uitstap_deelnemers_kolommen.push(new Kolom('Naam', 'Naam'));
        uitstap_deelnemers_kolommen.push(new Kolom('Voornaam', 'Voornaam'));
        var controls = new Array();
        //controls.push(new Control('Wijzigen', 'btn btn-sm', wijzig_deelname));
        controls.push(new Control('Verwijderen', 'btn btn-sm', verwijder_deelname));
        uitstap_deelnemers_kolommen.push(new ControlsKolom(controls));
        var id = parseInt(data['Id']);
        console.log("id = "+id);
        uitstap_deelnemers_tabel = new Tabel('index.php?action=data&data=uitstapDeelnamesTabel&uitstap_id='+id, uitstap_deelnemers_kolommen);
        uitstap_deelnemers_tabel.setUp(tabel);
        uitstap_deelnemers_tabel.laadTabel();
    };
    function uitstap_clicked(row){
        row.getElement().addClass('active').siblings().removeClass('active');
        laad_uitstap(row.getData());
    };
    var k = new Array();
    k.push(new Kolom('Datum','Datum'));
    k.push(new Kolom('Omschrijving', 'Omschrijving'));
    k.push(new Kolom('Actief', 'Actief'));
    
    var uitstappen_tabel = new Tabel('index.php?action=data&data=uitstappenTabel', k);
    uitstappen_tabel.setRowClickListener(new RowClickListener(uitstap_clicked));
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
