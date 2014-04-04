<?php
require_once (dirname(__FILE__) . "/../page.php");
require_once (dirname(__FILE__) . "/../../model/speelpleindag/speelpleindag.class.php");
require_once (dirname(__FILE__) . "/../../model/werkingen/werking.class.php");
require_once (dirname(__FILE__) . "/../../model/extraatjes/extraatje.class.php");
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
    private function getExtraatjesList(){
        $extraatjes = Extraatje::getExtraatjes();
        $result = "<ul>";
        foreach($extraatjes as $e){
            $result .= "<li><label class=\"checkbox-inline\"><input class=\"Extraatjes\" type=\"checkbox\" name=\"Extraatjes[]\" value=\"".$e->getId()."\"></input>".$e->getOmschrijving()."</label></li>\n";
        }
        $result .= "</ul>";
        return $result;
    }

    private function getAanwezigheidModal() {
        $werkingen_select = $this->getWerkingenSelect();
        $extraatjes_list = $this->getExtraatjesList();
        $content = <<<HERE
<div class="modal fade" id="aanwezigheidModal" tabindex="-1" role="dialog" aria-labelledby="aanwezigheidModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="modal-title">Nieuwe aanwezigheid toevoegen</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline" id="aanwezigheidForm">
                    <div class="row">
                    
                        <input type="hidden" name="AanwezigheidId" value="0">
                        <input type="hidden" name="KindId" value="0">
                        
                        <label class="control-label" for="Datum">Datum: </label>
                        <input type="text" name="Datum" value="" >
                        <br>
                        
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
                        <div id="ExtraatjesDiv">
                        $extraatjes_list
                        </div>
                        <br>
                        <label class="control-label" for="Opmerkingen">Opmerkingen: </label>
                        <textarea name="Opmerkingen" class="form-control"></textarea>
                    </div>
                </form>
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
            <script>
            $(document).ready(function(e){
                function loadKind(kind){
                    $('input[name="KindId"]').val(kind.Id);
                    $('select[name="KindVoogdId"]').empty();
                    $('textarea[name="OpmerkingId"]').empty();
                    if(kind.Id != 0){
                        for(var i = 0; i < kind.Voogden.length; ++i){
                            $('select[name="KindVoogdId"]').append($('<option>').attr('value', kind.Voogden[i].KindVoogdId).text(kind.Voogden[i].VolledigeNaam));
                        }
                        $('select[name="WerkingId"]').val(kind.DefaultWerkingId);
                    }
                };
                var suggesties = new Bloodhound({
                   datumTokenizer:function(d){return Bloodhound.tokenizers.whitespace(d.value); },
                   queryTokenizer: Bloodhound.tokenizers.whitespace,
                   remote:{
                       url:'index.php?action=data&data=kinderenSuggesties&query=%QUERY',
                       filter: function(kind){
                           console.log("bloodhound received this data: "+JSON.stringify(kind));
                           return $.map(kind.content, function(k){
                              return { 'display_value':(k.Voornaam+" "+k.Naam), 'id':k.Id, 'Voogden':k.Voogden, 'DefaultWerkingId': k.DefaultWerkingId}; 
                           });
                       }
                   }
                });
                suggesties.initialize();
                $('input[name="VolledigeNaamKind"]').typeahead(null, {
                    displayKey:'display_value',
                    source: suggesties.ttAdapter()
                }).bind('typeahead:selected', function(obj, kind, dataset_name){
                    loadKind(kind);
                });
                $('#aanwezigheidForm .tt-hint').addClass('form-control');
            });
            </script>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <button type="button" class="btn btn-primary" id="submitAanwezigheid">Toevoegen</button>
            </div>
            <script>
            
            </script>
        </div>
    </div>
</div>
HERE;
        return $content;
    }

    private function getVerwijderenModal(){
        $content = <<<HERE
<div class="modal fade" id="verwijderAanwezigheidModal" tabindex="-1" role="dialog" aria-labelledby="verwijderAanwezigheidModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="buton" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="verwijderAanwezigheidModalTitle">Aanwezigheid verwijderen</h4>
            </div>
            <div class="modal-body">
                <form id="verwijderAanwezigheidForm">
                    <input type="hidden" name="Id">
                </form>
                <p>Bent u zeker dat u deze aanwezigheid wilt verwijderen?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-primary" id="btnVerwijderAanwezigheid">Verwijderen</button>
            </div>
        </div>
    </div>
</div>
HERE;
        return $content;
    }

    public function buildContent() {
        
        $werkingen = Werking::getWerkingen();
        $werkingen_js_array = array();
        $werkingen_js_array[] = array('value'=>'', 'label'=>'Alle');
        foreach($werkingen as $w){
            $werkingen_js_array[] = array('value' => $w->getId(), 'label' => $w->getAfkorting());
        }
        $werkingen_js_array = json_encode($werkingen_js_array);
        
        $extraatjes = Extraatje::getExtraatjes();
        $extraatjes_js_array = array();
        $extraatjes_js_array[] = array('value'=>'', 'label'=>'Alle');
        foreach($extraatjes as $e){
            $extraatjes_js_array[] = array('value'=>$e->getId(), 'label'=>$e->getOmschrijving());
        }
        $extraatjes_js_array = json_encode($extraatjes_js_array);
        
        $vandaag = new SpeelpleinDag();
        $datum = $vandaag->getDatum();
        $content = $this->getAanwezigheidModal();
        $content .= $this->getVerwijderenModal();
        $content .= <<<HERE
        
<div class="row">
    <button class="btn btn-large btn-primary" id="btnNieuweAanwezigheid">Aanwezigheid</button>
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
    //$('#datum').datepicker({'format':'yyyy-mm-dd'}).data('datepicker');
    $('input[name="Datum"]').datepicker({'format':'yyyy-mm-dd'}).on('changeDate', function(){
        $('input[name="Datum"]').datepicker('hide');
    });
});
require(['tabel', 'tabel/kolom', 'tabel/control', 'tabel/controls_kolom', 'tabel/filter_rij', 'tabel/filter_veld'], function(Tabel, Kolom, Control, ControlsKolom, FilterRij, FilterVeld, require){
    function clearAanwezigheidModal(){
        $('input[name="AanwezigheidId"]').val('0');
        $('input[name="KindId"]').val('0');
        $('input[name="VolledigeNaamKind"]').val('');
        $('form input[name="Datum"]').val($('#datum').val());
        $('select[name="KindVoogdId"]').empty().val('');
        $('select[name="WerkingId"]').val('0');
        $('input[type=checkbox].Extraatjes').prop('checked', false);
        $('textarea[name="Opmerkingen"]').val('');
    };
    var wijzig_aanwezigheid = function(data){
        var d = new Object();
        d.id = data['Id'];
        $.get('?action=data&data=aanwezigheidDetails', d, function(r){
            console.log("response: "+r);
            var obj = JSON.parse(r);
            $('input[name="AanwezigheidId"]').val(obj.Id);
            $('input[name="KindId"]').val(obj.KindId);
            $('input[name="VolledigeNaamKind"]').val(obj.KindVolledigeNaam);
            $('form input[name="Datum"]').val(obj.Datum);
            $('form input[name="Datum"]').datepicker('update');
            for(var i = 0; i < obj.KindVoogden.length; ++i){
                $('select[name="KindVoogdId"]').append($('<option>').attr('value', obj.KindVoogden[i].Id).text(obj.KindVoogden[i].VolledigeNaam));
            }
            for(var i = 0; i < obj.Extraatjes.length; ++i){
                $('input[type=checkbox].Extraatjes[value='+obj.Extraatjes[i]+']').prop('checked', true);
            }
            $('select[name="KindVoogdId"]').val(obj.KindVoogdId);
            $('select[name="WerkingId"]').val(obj.Werking);
            $('textarea[name="Opmerkingen"]').val(obj.Opmerkingen);
        });
        clearAanwezigheidModal();
        $('#aanwezigheidModal').modal('show');
    };
    var verwijder_aanwezigheid = function(data){
        console.log("Verwijder aanwezigheid: "+JSON.stringify(data));
        $('#verwijderAanwezigheidModal input[name=Id]').val(data.Id);
        $('#verwijderAanwezigheidModal').modal('show');
    };
    function nieuwe_aanwezigheid(){
        clearAanwezigheidModal();
        $('form input[name="Datum"]').val($('td input[name="Datum"]').val());
        $('#aanwezigheidModal').modal('show');  
    };
    var k = new Array();
    k.push(new Kolom('Datum', 'Datum', null, true));
    k.push(new Kolom('Voornaam','Voornaam', null, true));
    k.push(new Kolom('Naam','Naam', null, true));
    k.push(new Kolom('Werking','Werking'));
    k.push(new Kolom('Extraatjes', 'Extraatjes', function(data){
        var td = $('<td>');
        console.log("kolom extraatjes: "+data['Extraatjes'].length);
        for(var i = 0; i < data['Extraatjes'].length; ++i){
            var extra = $('<a>').addClass('glyphicon glyphicon-plus').attr('title', data['Extraatjes'][i].Omschrijving).tooltip();
            td.append(extra);
        }
        return td;
    }));
    //TODO: insert extraatjes
    k.push(new Kolom('Info', 'Extra Info', function(data){
        var td = $('<td>');
        if(data['Belangrijk']){
            td.append(
                $('<a>').attr({ 
                        'data-original-title' : data['Belangrijk']
                    })
                    .append($('<span>').addClass('glyphicon glyphicon-info-sign'))
                    .tooltip())
                .append('&nbsp;');
        }
        if(data['Opmerkingen']){
            td.append(
                $('<a>').attr({
                    'data-original-title': data['Opmerkingen']
                }).append($('<span>').addClass('glyphicon glyphicon-info-sign'))
                .tooltip());
        }
        return td;
    }));
    var controls = new Array();
    controls.push(new Control('Wijzigen', 'btn btn-sm', wijzig_aanwezigheid));
    controls.push(new Control('Verwijderen', 'btn btn-sm', verwijder_aanwezigheid));
    k.push(new ControlsKolom(controls));
    var t = new Tabel('index.php?action=data&data=aanwezighedenTabel', k);
    var filter_velden = new Array();
    filter_velden.push(new FilterVeld('Datum', 1, 'datepicker', '$datum'));
    filter_velden.push(new FilterVeld('VolledigeNaam', 2, 'text', null));
    filter_velden.push(new FilterVeld('Werking', 1, 'select', {options:$werkingen_js_array}));
    filter_velden.push(new FilterVeld('Extraatjes', 1, 'select', {options:$extraatjes_js_array}));
    t.setFilterRij(new FilterRij(filter_velden,t));
    t.setUp($('#aanwezigheden_tabel'));
    $(document).ready(function(){
        t.laadTabel();
    });
    $('#submitAanwezigheid').click(function(){
        $('#aanwezigheidForm').submit();
        return false;
    });
    $('#btnNieuweAanwezigheid').click(function(){
        nieuwe_aanwezigheid();
    });
    $('#aanwezigheidForm').submit(function(){
       var aanwezigheidId = $('input[name="AanwezigheidId"]').val();
       var kindVoogdId = $('select[name="KindVoogdId"]').val();
       var werking = $('select[name="WerkingId"]').val();
       var opmerkingen = $('textarea[name="Opmerkingen"]').val();
       var d = new Object();
       d.Id = aanwezigheidId;
       d.KindVoogd = kindVoogdId;
       d.Datum = $('input[name="Datum"]').val();
       d.Werking = werking;
       d.Opmerkingen = opmerkingen;
       //var serialized = $('#aanwezigheidForm').serialize();
       //d.Extraatjes = serialized.Extraatjes;
       d.Extraatjes = new Array();
       $('#aanwezigheidForm input[type=checkbox].Extraatjes:checked').each(function(index, e){
           
           console.log("checked: val = "+$(e).val());
            d.Extraatjes.push($(e).val());
       });
       $.post('?action=updateAanwezigheid', d, function(res){ 
           res = $.trim(res);
           if(res == "1"){
               $('#aanwezigheidModal').modal('hide');
               t.laadTabel();
           }else{
               console.log("kind update mislukt, error code: '"+res+"'");
           }
       });
       return false;
    });
    $('#btnVerwijderAanwezigheid').click(function(){
       console.log("sending delete request to server");
       console.log("data: "+$('#verwijderAanwezigheidForm').serialize());
       $.post('index.php?action=removeAanwezigheid', $('#verwijderAanwezigheidForm').serialize(), function(res){
           res = $.trim(res);
            if(res == "1"){
                $('#verwijderAanwezigheidModal').modal('hide');
                t.laadTabel();
            }else{
                console.log("Aanwezigheid verwijderen mislukt, error code: "+res);
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
