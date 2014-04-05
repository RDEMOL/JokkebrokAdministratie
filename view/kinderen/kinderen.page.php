<?php
require_once (dirname(__FILE__) . "/../page.php");
require_once (dirname(__FILE__) . "/../../model/werkingen/werking.class.php");
class KinderenPage extends Page {
    public function __construct() {
        parent::__construct("Kinderen","","kinderen");
        $this->buildContent();
    }

    private function getWerkingenSelect() {
        $opties = "";
        $werkingen = Werking::getWerkingen();
        foreach($werkingen as $w) {
            $opties .= "<option value=\"" . $w->getId() . "\">" . $w->getAfkorting() . " - " . $w->getOmschrijving() . "</option>";
        }
        $content = <<<HERE
<select name="DefaultWerking" class="form-control">
$opties
</select>
HERE;
        return $content;
    }
	private function getPDFModal(){
		$content = <<<HERE
<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="pdfModalTitle">PDF genereren</h4>
			</div>
			<div class="modal-body">
				Welke kolommen wilt u afdrukken?
				<div class="row">
				<div class="col-md-6">
				Weergeven
				<ul id="pdfSelectedFields" class="pdfFields">
				<li>a
				<li>b
				</ul>
				</div>
				<div class="col-md-6">
				Verbergen
				<ul id="pdfUnselectedFields" class="pdfFields">
				<li>c
				<li>d
				</ul>
				</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
				<button type="button" class="btn btn-primary" id="btnPDF">PDF genereren</button>
			</div>
		</div>
	</div>
</div>
HERE;
		return $content;
	}
    private function getVerwijderKindModal(){
        $content = <<<HERE
<div class="modal fade" id="verwijderKindModal" tabindex="-1" role="dialog" aria-labelledby="verwijderKindModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="buton" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="verwijderKindModalTitle">Kind verwijderen</h4>
            </div>
            <div class="modal-body">
                <form id="verwijderKindForm">
                    <input type="hidden" name="Id">
                </form>
                <p>Bent u zeker dat u dit kind wilt verwijderen?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-primary" id="btnVerwijderKind">Verwijderen</button>
            </div>
        </div>
    </div>
</div>
HERE;
        return $content;
    }
    private function getKindModal() {
        $werkingen_select = $this->getWerkingenSelect();
        $content = <<<HERE
<div class="modal fade" id="kindModal" tabindex="-1" role="dialog" aria-labelledby="kindModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="modal-title" id="kindModalTitle">Nieuw kind toevoegen</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline" id="kindForm">
                    <input type="hidden" name="Id" value="0">
                    <div class="row">
                        <label class="control-label" for="Voornaam">Voornaam: </label>
                        <input type="text" name="Voornaam" value="">
                    </div>
                    <div class="row">
                        <label for="Naam" class="control-label">Naam: </label>
                        <input type="text" name="Naam" value="">
                    </div>
                    <div class="row">
                        <label class="control-label" for="Geboortejaar">Geboortejaar: </label>
                        <input type="text" name="Geboortejaar" value="">
                    </div>
                    <div class="row">
                        <label class="control-label" for="DefaultWerking">Werking*: </label>
                        $werkingen_select
                    </div> 
                    <div class="row">
                        <i>*Deze werking is de standaardinstelling bij de aanwezigheden</i>
                    </div>
                    <div class="row">
                        <label class="control-label" for="Belangrijk">Belangrijk: </label>
                        <textarea name="Belangrijk"></textarea>
                    </div>
                    <div class="row">
                        <h3>Voogd:</h3>
                        <input type="hidden" name="voogd_amount" value="0">
                    </div>
                    <div class="row">
                    <button id="btnAndereVoogd" class="btn btn-default">Nog een voogd toevoegen</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <button type="button" class="btn btn-primary" id="submitKind">Opslaan</button>
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
        $content = $this->getVerwijderKindModal()."\n".$this->getKindModal();
		$content .= $this->getPDFModal();
		
        $content .= <<<HERE
<div class="row">
    <button class="btn btn-large btn-primary" id="btnNieuwKind">Nieuw kind</button>
    <div class="pull-right">
        <button id="btnPDFModal" class="btn">Pdf tonen</button>
    </div>
</div>
<br>
<div class="row">
<table class="table table-striped table-bordered" id="kinderen_tabel">
</table>
</div>
<script>
require(['tabel', 'tabel/kolom', 'tabel/control', 'tabel/controls_kolom', 'tabel/filter_rij', 'tabel/filter_veld'], function(Tabel, Kolom, Control, ControlsKolom, FilterRij, FilterVeld, require){
    var voogd_amount = 0;
    function loadVoogdData(div_id){
        var el = $('#voogdDiv'+div_id);
        var kind_voogd_id = el.find('input[name="KindVoogdId'+div_id+'"]').val();
        console.log("found kind_voogd_id = "+kind_voogd_id);
        if(!kind_voogd_id || kind_voogd_id == 0)
            return;
        console.log("good voogd id!");
        $.get('index.php?action=data&data=voogdInfo', {'kind_voogd_id':kind_voogd_id}, function(e){
            console.log("got voogd info: "+e);
            var obj = JSON.parse(e);
            el.find('input[name="VoogdId'+div_id+'"]').val(obj.VoogdId);
            el.find('input[name="VoogdVoornaam'+div_id+'"]').val(obj.Voornaam);
            el.find('input[name="VoogdNaam'+div_id+'"]').val(obj.Naam);
            el.find('textarea[name="VoogdOpmerkingen'+div_id+'"]').val(obj.Opmerkingen);
        });
    };
    function verwijderVoogdDiv(div_id){
          $('#kindForm #voogdDiv'+div_id).remove();
          for(var i = div_id+1; i < voogd_amount; ++i){
              var el = $('#voogdDiv'+parseInt(i));
              el.find('input[name="KindVoogdId'+parseInt(i)+'"]').attr('name', 'KindVoogdId'+parseInt(i-1));
              el.find('input[name="VoogdId'+parseInt(i)+'"]').attr('name', 'VoogdId'+parseInt(i-1));
              el.find('input[name="VoogdVoornaam'+parseInt(i)+'"]').attr('name', 'VoogdVoornaam'+parseInt(i-1));
              el.find('input[name="VoogdNaam'+parseInt(i)+'"]').attr('name', 'VoogdNaam'+parseInt(i-1));
              el.find('textarea[name="VoogdOpmerkingen'+parseInt(i)+'"]').attr('name', 'VoogdOpmerkingen'+parseInt(i-1));
              el.find('#div_id').attr('value', parseInt(i-1));
              el.attr('id', 'voogdDiv'+parseInt(i-1))
          }
          console.log("voogd amount = "+voogd_amount);
          voogd_amount-=1;
          $('#kindForm input[name="voogd_amount"]').val(voogd_amount);
          
          console.log("voogd amount after = "+voogd_amount);
    };
    var voegVoogdDivToe = function(kind_voogd_id){
        var el = $('<div>').attr('id','voogdDiv'+voogd_amount)
            .addClass('row voogd_row')
            .append($('<input>').attr({'type':'hidden', 'id':'div_id', 'value':voogd_amount}))
            .append($('<input>').attr({'type':'hidden', 'name':'KindVoogdId'+voogd_amount, 'value':kind_voogd_id?kind_voogd_id:'0'}))
            .append($('<label>').addClass('control-label').attr('for', 'VoogdVoornaam'+voogd_amount).text('Voornaam: '))
            .append($('<input>').attr({'type': 'hidden', 'name':'VoogdId'+voogd_amount}))
            .append($('<input>').attr('name', 'VoogdVoornaam'+voogd_amount))
            .append($('<br>'))
            .append($('<label>').addClass('control-label').attr('for', 'VoogdNaam'+voogd_amount).text('Naam: '))
            .append($('<input>').attr('type', 'text').attr('name', 'VoogdNaam'+voogd_amount))
            .append($('<br>'))
            .append($('<label>').addClass('control-label').attr('for', 'VoogdOpmerkingen'+voogd_amount).text('Opmerkingen: '))
            .append($('<textarea>').attr('type', 'text').attr('name', 'VoogdOpmerkingen'+voogd_amount))
            .append($('<button>').attr('type', 'button').text('x').click(function(){verwijderVoogdDiv($(this).parent().find('#div_id').val()); return false;}))
            .append($('<br>'));
        ++voogd_amount;
        $('#kindForm input[name="voogd_amount"]').val(voogd_amount);
        console.log("nieuwe voogd = "+voogd_amount);
        el.insertBefore($('#btnAndereVoogd').parent());
        loadVoogdData(voogd_amount-1);
    };
    function wijzig_kind(data){
        console.log("wijzigen: "+JSON.stringify(data));
        voogd_amount = 0;
        $('#kindForm input[name="voogd_amount"]').val(voogd_amount);
        $('.voogd_row').remove();
        $('#kindForm input[name=Id]').val(data.Id);
        $('#kindForm input[name=Voornaam]').val(data.Voornaam);
        $('#kindForm input[name=Naam]').val(data.Naam);
        $('#kindForm input[name=Geboortejaar]').val(data.Geboortejaar);
        $('#kindForm select[name=DefaultWerking]').val(data.DefaultWerking);
        $('#kindForm textarea[name=Belangrijk]').val(data.Belangrijk);
        for(var i = 0; i < data.KindVoogdIds.length; ++i){
            voegVoogdDivToe(data.KindVoogdIds[i]);
        }
        $('#kindModal').modal('show');
    };
    function verwijder_kind(data){
        console.log("verwijderen: "+JSON.stringify(data));
        $('#verwijderKindModal input[name=Id]').val(data.Id);
        $('#verwijderKindModal').modal('show');
    };
    function nieuw_kind(){
        console.log("nieuw kind");
        $('#kindForm').find('input[type=text], textarea').val('');
        $('#kindForm').find('select').val('0');
        $('#kindForm input[name=Id]').val('0');
        voogd_amount = 0;
        $('.voogd_row').remove();
        voegVoogdDivToe();
        $('#kindModal').modal('show');  
    };
    var k = new Array();
    k.push(new Kolom('Voornaam','Voornaam', null, true));
    k.push(new Kolom('Naam','Naam', null, true));
    k.push(new Kolom('Geboortejaar', 'Geboortejaar', null, true));
    k.push(new Kolom('Werking','Werking'));
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
    controls.push(new Control('Wijzigen', 'btn btn-sm', wijzig_kind));
    controls.push(new Control('Verwijderen', 'btn btn-sm', verwijder_kind));
    k.push(new ControlsKolom(controls));
    var t = new Tabel('index.php?action=data&data=kinderenTabel', k);
    var filter_velden = new Array();
    filter_velden.push(new FilterVeld('VolledigeNaam', 2, 'text', null));
    filter_velden.push(new FilterVeld('Geboortejaar', 1, 'text', null));
    filter_velden.push(new FilterVeld('Werking', 1, 'select', {options:$werkingen_js_array}));
    t.setFilterRij(new FilterRij(filter_velden,t));
    t.setUp($('#kinderen_tabel'));
    $(document).ready(function(){
        t.laadTabel();
        $('#btnAndereVoogd').click(function(e){
            e.preventDefault();
            voegVoogdDivToe();
            return false;
        });
        $('#btnNieuwKind').click(function(){
            nieuw_kind();
        });
        $('#kindForm').submit(function(){
            console.log("submitting!");
            console.log("form data = "+$('#kindForm').serialize());
            $.post('index.php?action=updateKind', $('#kindForm').serialize(), function(res){
               res = $.trim(res);
               if(res == "1"){
                   $('#kindModal').modal('hide');
                   t.laadTabel();
               }else{
                   console.log("kind update mislukt, error code: '"+res+"'");
               }
            });
            return false;
       });
       $('#submitKind').click(function(){
           $('#kindForm').submit();
       });
       $('#btnVerwijderKind').click(function(){
           console.log("sending delete request to server");
           console.log("data: "+$('#verwijderKindForm').serialize());
           $.post('index.php?action=removeKind', $('#verwijderKindForm').serialize(), function(res){
               res = $.trim(res);
                if(res == "1"){
                    $('#verwijderKindModal').modal('hide');
                    t.laadTabel();
                }else{
                    console.log("kind verwijderen mislukt, error code: "+res);
                }
           });
       });
	   var pdf_fields = new Array('Naam', 'Voornaam', 'Geboortejaar', 'Belangrijk', 'Werking');
	   $('#btnPDFModal').click(function(){
	   		$('#pdfSelectedFields').empty().unbind('sortupdate');
			$('#pdfUnselectedFields').empty().unbind('sortupdate');
			for(var i = 0; i < pdf_fields.length; ++i){
				$('#pdfUnselectedFields').append($('<li>').text(pdf_fields[i]).attr('draggable', 'true'));
			}
			$('#pdfSelectedFields').append($('<li>').text('Nummer').addClass('disabled'));
			$('#pdfSelectedFields, #pdfUnselectedFields').sortable({connectWith:'.pdfFields', items:':not(.disabled)'});
	   		$('#pdfModal').modal('show');
	   });
	   $('#btnPDF').click(function(){
	   		var data = new Object();
			data.kolommen = new Array();
			$('#pdfSelectedFields li').each(function(index, value){
				console.log("text = "+$(this).text());
				data.kolommen.push($(this).text());
			});
			console.log("kolommen = "+JSON.stringify(data.kolommen));
			data.action="data";
			data.data="kinderenPDF";
			data.filter = t.getFilter();
			data.order = t.getSort();
			window.open('index.php?'+$.param(data));
			$('#pdfModal').modal('hide');
	
	   });
    });
});
</script>
HERE;
        $this->setContent($content);
    }

}
?>
