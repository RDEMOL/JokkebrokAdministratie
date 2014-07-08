<?php
require_once (dirname(__FILE__) . "/../page.php");
require_once (dirname(__FILE__) . "/../../model/speelpleindag/speelpleindag.class.php");
require_once (dirname(__FILE__) . "/../../model/werkingen/werking.class.php");
require_once (dirname(__FILE__) . "/../../model/extraatjes/extraatje.class.php");
class AanwezighedenPage extends Page {
    public function __construct() {
        parent::__construct("Aanwezigheden","","aanwezigheden");
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

    public function printContent() {
        
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
        
		//filter
        $vandaag = new SpeelpleinDag();
        $datum = $vandaag->getDatum();
		$werking = "";
		$extraatje = "";
		if(isset($_REQUEST['filter'])){
			$filter = $_REQUEST['filter'];
			if(isset($filter['Datum'])){
				$datum = $filter['Datum'];
			}
			if(isset($filter['Extraatje'])){
				$extraatje = $filter['Extraatje'];	
			}
			if(isset($filter['Werking'])){
				$werking = $filter['Werking'];
			}
		}
		
?>
<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title" id="pdfModalTitle">PDF genereren</h4>
	</div>
	<div class="modal-body">
		Geef een titel voor de lijst: <input type="text" name="pdfTitel" id="pdfTitel" value="Aanwezigheden">
		<br>
		Welke kolommen wilt u afdrukken?
		<div class="row">
		<div class="col-md-6">
		Weergeven
		<ul id="pdfSelectedFields" class="pdfFields">
		</ul>
		</div>
		<div class="col-md-6">
		Verbergen
		<ul id="pdfUnselectedFields" class="pdfFields">
		</ul>
		</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
		<button type="button" class="btn btn-primary" id="btnPDF">PDF genereren</button>
	</div>
</div>

<div class="modal fade" id="verwijderAanwezigheidModal" tabindex="-1" role="dialog" aria-labelledby="verwijderAanwezigheidModal">
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

<div class="modal fade" id="vorderingModal" tabindex="-1" role="dialog" aria-labelledby="vorderingModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
        <h4 class="modal-title">Nieuwe vordering toevoegen</h4>
	</div>
	<div class="modal-body">
		<form id="vorderingForm">
			<input type="hidden" name="Id">
			<label for="Bedrag">Bedrag:</label>
			<input type="text" name="Bedrag"><br>
			<label for="Opmerking">Opmerking:</label>
			<textarea name="Opmerking"></textarea>
		</form>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
	    <button type="button" class="btn btn-primary" id="submitVordering">Toevoegen</button>
	</div>
</div>

<div class="modal fade" id="aanwezigheidModal" tabindex="-1" role="dialog" aria-labelledby="aanwezigheidModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
        <h4 class="modal-title" id="AanwezigheidModalTitle">Nieuwe aanwezigheid toevoegen</h4>
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
<?php echo $this->getWerkingenSelect(); ?>
                <br>
                <div id="MiddagNaarHuisDiv">
                	<label for="MiddagNaarHuis">Middag naar huis: </label><input type="checkbox" name="MiddagNaarHuis">
                </div>
                <br>
                <div id="ExtraatjesDiv">
                	<h5>Extraatjes:</h5>
<?php echo $this->getExtraatjesList(); ?>
                </div>
                <div id="UitstappenDiv">
                	<h5>Uitstappen:</h5>
                	<ul id="lstUitstappen"></ul>
                </div>
                <br>
                <label class="control-label" for="Opmerkingen">Opmerkingen: </label>
                <textarea name="Opmerkingen" class="form-control"></textarea>
                <br>
                <div id="VorderingenDiv">
                	<button id="btnNieuweVordering" class="btn btn-default">Nieuwe Vordering</button>
                	<ul id="lstVorderingen"></ul>
                </div>
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
            $('input[type=checkbox]').removeAttr('checked');
            $('ul#lstVorderingen').empty();
            if(kind.Id != 0){
                for(var i = 0; i < kind.Voogden.length; ++i){
                    $('select[name="KindVoogdId"]').append($('<option>').attr('value', kind.Voogden[i].KindVoogdId).text(kind.Voogden[i].VolledigeNaam));
                }
                $('select[name="WerkingId"]').val(kind.DefaultWerkingId);
            }
        };
        function unloadKind(kind){
        	var d = new Object();
        	d.Id = 0;
        	loadKind(d);
        };
        var suggesties = new Bloodhound({
           datumTokenizer:function(d){return Bloodhound.tokenizers.whitespace(d.value); },
           queryTokenizer: Bloodhound.tokenizers.whitespace,
           remote:{
               url:'index.php?action=data&data=kinderenSuggesties&query=%QUERY',
               filter: function(kind){
                   return $.map(kind.content, function(k){
                      return { 'display_value':(k.Voornaam+" "+k.Naam), 'id':k.Id, 'Voogden':k.Voogden, 'DefaultWerkingId': k.DefaultWerkingId}; 
                   });
               }
           }
        });
        suggesties.initialize();
        $('input[name="VolledigeNaamKind"]').keydown(function(event){
                switch(event.keyCode){
                    case 13:
                    case 9:
                        return true;
                }
        		unloadKind();
        	}).typeahead(null, {
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
	    <button type="button" class="btn btn-primary" id="submitAanwezigheid">Opslaan</button>
	</div>
</div>


<div class="row">
    <button class="btn btn-large btn-primary" id="btnNieuweAanwezigheid">Aanwezigheid</button>
    <div class="pull-right">
        <button id="btnPDFModal" class="btn">Pdf tonen</button>
    </div>
</div>
<br>
<div class="row">
<table class="table table-striped table-bordered table-condensed" id="aanwezigheden_tabel">
</table>
</div>
<script>
$(document).ready(function(){
    $('input[name="Datum"]').datepicker({'format':'yyyy-mm-dd'}).on('changeDate', function(){
        $('input[name="Datum"]').datepicker('hide');
    });
});
require(['tabel', 'tabel/kolom', 'tabel/control', 'tabel/controls_kolom', 'tabel/filter_rij', 'tabel/filter_veld', 'validator'], function(Tabel, Kolom, Control, ControlsKolom, FilterRij, FilterVeld, Validator, require){
	$('#aanwezigheidModal').on('shown', function () {
//	   $('input:text:visible').next().focus();
		if($('input[name=VolledigeNaamKind]').val() == ""){
			$('input[name=VolledigeNaamKind]').focus();
		}
	});
    function clear_aanwezigheid_modal(){
        $('input[name="AanwezigheidId"]').val('0');
        $('input[name="KindId"]').val('0');
        $('input[name="VolledigeNaamKind"]').val('');
        $('form input[name="Datum"]').val($('#datum').val());
        $('select[name="KindVoogdId"]').empty().val('');
        $('select[name="WerkingId"]').val('0');
        $('input[type=checkbox].Extraatjes').prop('checked', false);
        $('input[name=MiddagNaarHuis]').prop('checked', false);
        $('textarea[name="Opmerkingen"]').val('');
        $('ul#lstVorderingen').empty();
        $('ul#lstUitstappen').empty();
    };
    function laad_aanwezigheid_uitstappen(kind_id){
    	var data = new Object();
    	if(kind_id)
    		data.KindId=kind_id;
    	$.get('index.php?action=data&data=aanwezigheidUitstappen', data, function(res){
    		var uitstappen = JSON.parse(res).content;
    		for(var i = 0; i < uitstappen.length; ++i){
    			$('ul#lstUitstappen').append(
    				$('<li>')
    					.append($('<input>').attr({'type':'hidden', 'value':uitstappen[i].Id, 'name':'Id'}))
    					.append($('<input>').attr({'type':'checkbox', 'checked':uitstappen[i].Ingeschreven, 'name':'Ingeschreven'}))
    					.append($('<span>').text(uitstappen[i].Datum+": "+uitstappen[i].Omschrijving)));
    		}
    	});
    }
    var wijzig_aanwezigheid = function(data){
    	$('#AanwezigheidModalTitle').text('Wijzig aanwezigheid');
        clear_aanwezigheid_modal();
        laad_aanwezigheid_uitstappen(data.Id);
        var d = new Object();
        d.id = data['Id'];
        $.get('?action=data&data=aanwezigheidDetails', d, function(r){
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
            $('form input[name=MiddagNaarHuis]').val(obj.MiddagNaarHuis);
            
            if(obj.Vorderingen){
            	for(var i = 0; i < obj.Vorderingen.length; ++i){
            		voeg_vordering_toe(obj.Vorderingen[i]);
            	}
            }
        });
        $('#aanwezigheidModal').modal('show');
    };
    $('#btnNieuweVordering').unbind('click').click(function(){
    	//TODO: working on this part
    	clearVorderingModal();
    	$('#vorderingModal').modal('show');
    	return false;
    });
    var verwijder_aanwezigheid = function(data){
        $('#verwijderAanwezigheidModal input[name=Id]').val(data.Id);
        $('#verwijderAanwezigheidModal').modal('show');
    };
    function nieuwe_aanwezigheid(){
    	$('#AanwezigheidModalTitle').text('Nieuwe aanwezigheid');
        clear_aanwezigheid_modal();
        laad_aanwezigheid_uitstappen();
        $('form input[name="Datum"]').val($('td input[name="Datum"]').val());
        $('#aanwezigheidModal').modal('show');
    };
    var k = new Array();
    k.push(new Kolom('Datum', 'Datum', function(data){
        console.log(JSON.stringify(data));
        var td = $('<td>');
        td.text(data['Datum']+" (gewijzigd: "+data['LastChanged'].substring(0, 16)+")");
        return td;
    }, true));
    k.push(new Kolom('Voornaam','Voornaam', null, true));
    k.push(new Kolom('Naam','Naam', null, true));
    k.push(new Kolom('Werking','Werking'));
    k.push(new Kolom('MiddagNaarHuis', 'MiddagNaarHuis', function(data){
    	var td = $('<td>');
    	if(data['MiddagNaarHuis']== "1"){
    		var middag_naar_huis = $('<a>').addClass('glyphicon glyphicon-home').attr('title', "Moeder heeft visjes gebakken en dus gaat dit kind 's middags thuis eten.").tooltip();
    		td.append(middag_naar_huis);
    	}
    	return td;
    }));
    k.push(new Kolom('Extraatjes', 'Extraatjes', function(data){
        var td = $('<td>');
        for(var i = 0; i < data['Extraatjes'].length; ++i){
            var extra = $('<a>').addClass('glyphicon glyphicon-plus').attr('title', data['Extraatjes'][i].Omschrijving).tooltip();
            td.append(extra);
        }
        return td;
    }));
    k.push(new Kolom('Info', 'Extra Info', function(data){
        var td = $('<td>');
        if(data['Belangrijk']){
            td.append(
                $('<a>').attr({ 
                        'data-original-title' : "Belangrijke informatie over het kind: "+data['Belangrijk']
                    })
                    .append($('<span>').addClass('glyphicon glyphicon-info-sign'))
                    .tooltip())
                .append('&nbsp;');
        }
        if(data['Opmerkingen']){
            td.append(
                $('<a>').attr({
                    'data-original-title': "Opmerkingen over deze aanwezigheid: "+data['Opmerkingen']
                }).append($('<span>').addClass('glyphicon glyphicon-info-sign'))
                .tooltip())
            .append('&nbsp;');
        }
		if(data['VoogdVolledigeNaam']){
			td.append(
				$('<a>').attr({
					'data-original-title':'Voogd: '+data['VoogdVolledigeNaam']
				}).append($('<span>').addClass('glyphicon glyphicon-home'))
				.tooltip())
            .append('&nbsp;');
		}
		if(data['Schulden'] != 0){
			td.append(
				$('<a>').attr({
					'data-original-title':'Het saldo van de voogd voor dit kind bedraagt: €'+data['Schulden']+".\nBij het wijzigen van de aanwezigheid of in het kindoverzicht kunt u betalingen toevoegen."
				}).append($('<span>').addClass('glyphicon glyphicon-euro'))
				.tooltip())
			.append('&nbsp;');
		}
        return td;
    }));
    var controls = new Array();
    controls.push(new Control('Wijzigen', 'btn btn-xs', wijzig_aanwezigheid));
    controls.push(new Control('Verwijderen', 'btn btn-xs', verwijder_aanwezigheid));
    k.push(new ControlsKolom(controls));
    var t = new Tabel('index.php?action=data&data=aanwezighedenTabel', k);
    var filter_velden = new Array();
    filter_velden.push(new FilterVeld('Datum', 1, 'datepicker', null, null, '<?php echo $datum; ?> '));
    filter_velden.push(new FilterVeld('VolledigeNaam', 2, 'text', null));
    filter_velden.push(new FilterVeld('Werking', 1, 'select', {options: <?php echo $werkingen_js_array; ?>}, null, '<?php echo $werking; ?>'));
    filter_velden.push(new FilterVeld('MiddagNaarHuis', 1, 'select', {options: [{"value": "", "label":"Alle"}, {"value":"1", "label":"Ja"},{"value":"0", "label":"Nee"}]}));
    filter_velden.push(new FilterVeld('Extraatjes', 1, 'select', {options:<?php echo $extraatjes_js_array; ?>}, null, '<?php echo $extraatje; ?>'));
    var andere_opties = new Array();
    var alle = new Object();
    alle.label = "Alle";
    alle.value = 0;
    andere_opties.push(alle);
    var schulden = new Object();
    schulden.label = "Schulden";
    schulden.value = "Schulden";
    andere_opties.push(schulden);
    var belangrijk = new Object();
    belangrijk.label = "Belangrijke info";
    belangrijk.value = "BelangrijkOpmerkingen";
    andere_opties.push(belangrijk);
    filter_velden.push(new FilterVeld('Andere', 1, 'select', {options: andere_opties}));
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
    function aanwezigheid_form_error(msg){
    	alert(msg);
    };
    $('#aanwezigheidForm').submit(function(){
       var aanwezigheidId = $('#aanwezigheidForm input[name="AanwezigheidId"]').val();
       var kindVoogdId = $('#aanwezigheidForm select[name="KindVoogdId"]').val();
       var werking = $('#aanwezigheidForm select[name="WerkingId"]').val();
       var opmerkingen = $('#aanwezigheidForm textarea[name="Opmerkingen"]').val();
       var d = new Object();
       d.Id = aanwezigheidId;
       d.KindVoogd = kindVoogdId;
       d.Datum = $('#aanwezigheidForm input[name="Datum"]').val();
       d.Werking = werking;
       d.MiddagNaarHuis = $('#aanwezigheidForm input[name=MiddagNaarHuis]')[0].checked?"1":"0";
       d.Opmerkingen = opmerkingen;
       d.Extraatjes = new Array();
       $('#aanwezigheidForm input[type=checkbox].Extraatjes:checked').each(function(index, e){
            d.Extraatjes.push($(e).val());
       });
       d.Vorderingen = new Array();
       $('ul#lstVorderingen li').each(function(index, e){
       		var v = new Object();
       		v.Id = $(this).find('input[name=Id]').val();
       		v.Opmerking = $(this).find('input[name=Opmerking]').val();
       		v.Bedrag = $(this).find('input[name=Bedrag]').val();
       		d.Vorderingen.push(v);
       });
       d.Uitstappen = new Array();
       $('ul#lstUitstappen li').each(function(index, e){
       		var u = new Object();
       		u.Id = $(this).find('input[name=Id]').val();
       		u.Ingeschreven = $(this).find('input[name=Ingeschreven]').is(':checked')?1:0;
       		d.Uitstappen.push(u);
       });
       if(!Validator.isPositiveInteger(d.KindVoogd)){
       		aanwezigheid_form_error("Kies een kind/voogd-combinatie");
       		return false;
       }
       if(!Validator.isGoodDate(d.Datum)){
       		aanwezigheid_form_error("Selecteer een geldige datum");
       		return false;
       }
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
       $.post('index.php?action=removeAanwezigheid', $('#verwijderAanwezigheidForm').serialize(), function(res){
            if(res.Ok){
                $('#verwijderAanwezigheidModal').modal('hide');
                t.laadTabel();
            }else{
                alert("Aanwezigheid verwijderen mislukt. Controleer dat er geen vorderingen of extraatjes zijn voor deze aanwezigheid.")
            }
       }, "json");
   });
   var pdf_fields = new Array('Datum', 'Extraatjes', 'MiddagNaarHuis', 'Info', 'Werking', 'Belangrijk', 'Opmerkingen');
   var pdf_fields_default = new Array('Voornaam', 'Naam');
   $('#btnPDFModal').click(function(){
   		$('#pdfSelectedFields').empty().unbind('sortupdate');
		$('#pdfUnselectedFields').empty().unbind('sortupdate');
		for(var i = 0; i < pdf_fields.length; ++i){
			$('#pdfUnselectedFields').append($('<li>').text(pdf_fields[i]).attr('draggable', 'true'));
		}
		$('#pdfSelectedFields').append($('<li>').text('Nummer').addClass('disabled'));
		for(var i = 0; i < pdf_fields_default.length; ++i){
			$('#pdfSelectedFields').append($('<li>').text(pdf_fields_default[i]).attr('draggable', 'true'));
		}
		$('#pdfSelectedFields, #pdfUnselectedFields').sortable({connectWith:'.pdfFields', items:':not(.disabled)'});
   		$('#pdfModal').modal('show');
   });
   $('#btnPDF').click(function(){
   		var data = new Object();
		data.kolommen = new Array();
		$('#pdfSelectedFields li').each(function(index, value){
			data.kolommen.push($(this).text());
		});
		data.action="data";
		data.data="aanwezighedenPDF";
		data.filter = t.getFilter();
		data.order = t.getSort();
		data.title = $('#pdfTitel').val();
		window.open('index.php?'+$.param(data));
		$('#pdfModal').modal('hide');
   });
   function clearVorderingModal(){
   	$('form#vorderingForm input[name=Id]').val('0');
   	$('form#vorderingForm textarea[name=Opmerking]').val('');
   	$('form#vorderingForm input[name=Bedrag]').val('0');
   }
   function wijzig_vordering(vordering_data){
   	$('form#vorderingForm input[name=Id]').val(vordering_data.Id);
   	$('form#vorderingForm textarea[name=Opmerking]').val(vordering_data.Opmerking);
   	$('form#vorderingForm input[name=Bedrag]').val(vordering_data.Bedrag);
   	$('#vorderingModal').modal('show');
   }
   function voeg_vordering_toe(vordering_data){
   	var li;
   	var found = false;
   	$('ul#lstVorderingen li').each(function(index, element){
   		if($(this).find('input[name=Id]').val() == vordering_data.Id){
   			li = $(this);
   			li.empty();
   			found = true;
   		}
   	});
   	if(!found){
   		li = $('<li>');
   	}
   	li.append($('<input>').attr({'type':'hidden', 'name':'Id'}).val(vordering_data.Id))
   		.append($('<input>').attr({'type':'hidden', 'name':'Opmerking'}).val(vordering_data.Opmerking))
   		.append($('<input>').attr({'type':'hidden', 'name':'Bedrag'}).val(vordering_data.Bedrag))
    	.append($('<span>').text(vordering_data.Bedrag+" ("+vordering_data.Opmerking+")"));
	li.append($('<button>').text('edit').click(function(){
		wijzig_vordering(vordering_data);
		return false;
	}));
	li.append($('<button>').text('remove').click(function(){
		li.remove();
	}));
   	$('ul#lstVorderingen').append(li);
   }
   $('#submitVordering').click(function(){
   	$('form#vorderingForm').submit();
   	return false;
   });
   function vordering_form_error(msg){
   		alert(msg);
   };
   $('form#vorderingForm').submit(function(){
   		var data = new Object();
   		data.Id = $('form#vorderingForm input[name=Id]').val();
   		data.Bedrag = $('form#vorderingForm input[name=Bedrag]').val();
   		data.Opmerking = $('form#vorderingForm textarea[name=Opmerking]').val();
   		if(!Validator.isPositivePayment(data.Bedrag)){
   			vordering_form_error("Vul een geldig positief bedrag in (max. 2 cijfers na de decimale punt).");
   			return false;
   		}
   		$('#vorderingModal').modal('hide');
   		voeg_vordering_toe(data);
   		return false;
   });
	$('#btnNieuweAanwezigheid').focus();
	$('#aanwezigheidModal, #verwijderAanwezigheidModal, #pdfModal').on('hidden', function(){
		$('#btnNieuweAanwezigheid').focus();
	});
});
</script>
<?php
}

}
?>
