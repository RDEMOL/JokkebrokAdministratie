<?php
require_once (dirname(__FILE__) . "/../page.php");
require_once (dirname(__FILE__) . "/../../model/speelpleindag/speelpleindag.class.php");
require_once (dirname(__FILE__) . "/../../model/werkingen/werking.class.php");
class AanwezighedenPage extends Page {
    public function __construct() {
        parent::__construct("Aanwezigheden","","aanwezigheden");
        $this->buildContent();
    }

    private function getWerkingenSelect() {
        $opties = "";
        $werkingen = Werking::getWerkingen();
        foreach($werkingen as $w) {
            $opties .= "<option value=\"" . $w->getId() . "\">" . $w->getAfkorting() . " - " . $w->getOmschrijving() . "</option>";
        }
        $content = <<<HERE
<select name="WerkingId" class="form-control">
$opties
</select>
HERE;
        return $content;
    }

    private function getNieuweAanwezigheidModal() {
        $werkingen_select = $this->getWerkingenSelect();
        $content = <<<HERE
<div class="modal fade" id="nieuweAanwezigheidModal" tabindex="-1" role="dialog" aria-labelledby="nieuweAanwezigheidModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="modal-title">Nieuwe aanwezigheid toevoegen</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline" id="aanwezigheidForm">
                    <div class="row">
                        <input type="hidden" name="KindId" value="0">
                        <span>
                        <label class="control-label" for="VolledigeNaamKind">Voornaam + naam: </label>
                        <input type="text" value="" class="form-control typeahead" name="VolledigeNaamKind">
                        </span>
                        <br>
                        <label class="control-label" for="KindVoogdId">Voogd:</label>
                        <select name="KindVoogdId" class="form-control"></select>
                        <br>
                        <label class="control-label" for="WerkingId">Werking: </label>
                        $werkingen_select
                        <br>
                        <label class="control-label" for="Opmerkingen">Opmerkingen: </label>
                        <textarea name="Opmerkingen"></textarea>
                    </div>
                </form>
            </div>
            <style type="text/css">
//adapted from typeahead examples
typeahead, .tt-query, .tt-hint {
    border: 2px solid #CCCCCC;
    border-radius: 8px 8px 8px 8px;
    padding: 8px 12px;
    width: 396px;
}
.typeahead {
    background-color: #FFFFFF;
}
.typeahead:focus {
    border: 2px solid #0097CF;
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
            <script>
            $(document).ready(function(e){
                var suggesties = new Bloodhound({
                   datumTokenizer:function(d){return Bloodhound.tokenizers.whitespace(d.value); },
                   queryTokenizer: Bloodhound.tokenizers.whitespace,
                   remote:{
                       url:'index.php?action=data&data=kinderenSuggesties&query=%QUERY',
                       filter: function(kind){
                           console.log("bloodhound received this data: "+JSON.stringify(kind));
                           return $.map(kind.content, function(k){
                              return { 'display_value':(k.Voornaam+" "+k.Naam)}; 
                           });
                       }
                   }
                });
                suggesties.initialize();
                $('input[name="VolledigeNaamKind"]').typeahead(null, {
                    displayKey:'display_value',
                    source: suggesties.ttAdapter()
                });
                $('#aanwezigheidForm .tt-hint').addClass('form-control');
            });
            </script>
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
require(['tabel', 'tabel/kolom', 'tabel/control', 'tabel/controls_kolom'], function(Tabel, Kolom, Control, ControlsKolom, require){
    var wijzig_aanwezigheid = function(data){
        console.log("Wijzig aanwezigheid: "+JSON.stringify(data));  
    };
    var verwijder_aanwezigheid = function(data){
        console.log("Verwijder aanwezigheid: "+JSON.stringify(data));
    };
    var k = new Array();
    k.push(new Kolom('Voornaam','Voornaam'));
    k.push(new Kolom('Naam','Naam'));
    k.push(new Kolom('Werking','Werking'));
    //TODO: insert extraatjes
    k.push(new Kolom('Info', 'Extra Info', function(data){
        var td = $('<td>');
        if(data['Belangrijk']){
            td.append(
                $('<a>').attr({ 
                        'data-original-title' : data['Belangrijk']
                    })
                    .append($('<span>').addClass('glyphicon glyphicon-info-sign'))
                    .tooltip());
        }
        return td;
    }));
    var controls = new Array();
    controls.push(new Control('Wijzigen', 'btn btn-sm', wijzig_aanwezigheid));
    controls.push(new Control('Verwijderen', 'btn btn-sm', verwijder_aanwezigheid));
    k.push(new ControlsKolom(controls));
    var t = new Tabel('index.php?action=data&data=aanwezighedenTabel', k);
    t.setUp($('#aanwezigheden_tabel'));
    var filter = new Object();
    t.setFilter(filter);
    $(document).ready(function(){
        t.laadTabel();
    });
});
</script>
HERE;
        $this->setContent($content);
    }

}
?>
