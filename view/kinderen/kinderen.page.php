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
<select name="DefaultWerkingId" class="form-control">
$opties
</select>
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
                        <label class="control-label" for="DefaultWerkingId">Werking*: </label>
                        $werkingen_select
                    </div> 
                    <div class="row">
                        <i>*Deze werking is de standaardinstelling bij de aanwezigheden</i>
                    </div>
                    <div class="row">
                        <label class="control-label" for="MedischeInfo">Medische informatie: </label>
                        <textarea name="MedischeInfo"></textarea>
                    </div>
                    <div class="row">
                        <label class="control-label" for="AndereInfo">Andere informatie: </label>
                        <textarea name="AndereInfo"></textarea>
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
        $content = $this->getVerwijderKindModal()."\n".$this->getKindModal();
        $content .= <<<HERE
<div class="row">
    <button class="btn btn-large btn-primary" id="btnNieuwKind">Nieuw kind</button>
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
require(['tabel', 'tabel/kolom', 'tabel/control'], function(Tabel, Kolom, Control, require){
    var voogd_amount = 0;
    var voegVoogdDivToe = function(){
        ++voogd_amount;
        $('#nieuwKindForm input[name="voogd_amount"]').val(voogd_amount);
        console.log("nieuwe voogd = "+voogd_amount);
        var el = $('<div>').addClass('row voogd_row')
            .append($('<label>').addClass('control-label').attr('for', 'voogdVoornaam'+voogd_amount).text('Voornaam: '))
            .append($('<input>').attr('name', 'voogdVoornaam'+voogd_amount))
            .append($('<br>'))
            .append($('<label>').addClass('control-label').attr('for', 'voogdNaam'+voogd_amount).text('Naam: '))
            .append($('<input>').attr('type', 'text').attr('name', 'voogdNaam'+voogd_amount))
            .append($('<br>'))
            .append($('<label>').addClass('control-label').attr('for', 'voogdOpmerkingen'+voogd_amount).text('Opmerkingen: '))
            .append($('<textarea>').attr('type', 'text').attr('name', 'voogdOpmerkingen'+voogd_amount))
            .append($('<br>'));
        el.insertBefore($('#btnAndereVoogd').parent());
    };
    function wijzig_kind(data){
        console.log("wijzigen: "+JSON.stringify(data));
        voogd_amount = 0;
        $('.voogd_row').remove();
        $('#kindForm input[name=Id]').val(data.Id);
        $('#kindForm input[name=Voornaam]').val(data.Voornaam);
        $('#kindForm input[name=Naam]').val(data.Naam);
        $('#kindForm input[name=Geboortejaar]').val(data.Geboortejaar);
        $('#kindForm select[name=DefaultWerkingId]').val(data.DefaultWerkingId);
        $('#kindForm textarea[name=MedischeInfo]').val(data.MedischeInfo);
        $('#kindForm textarea[name=AndereInfo]').val(data.AndereInfo);
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
    k.push(new Kolom('Voornaam','Voornaam'));
    k.push(new Kolom('Naam','Naam'));
    k.push(new Kolom('Werking','Werking'));
    k.push(new Kolom('Info', 'Extra Info', function(data){
        var td = $('<td>');
        if(data['MedischeInfo']){
            td.append(
                $('<a>').attr({ 
                        'data-original-title' : data['MedischeInfo']
                    })
                    .append($('<span>').addClass('glyphicon glyphicon-plus'))
                    .tooltip());
            td.append('&nbsp;');
        }
        if(data['AndereInfo']){
            td.append(
                $('<a>').attr({ 
                        'data-original-title' : data['AndereInfo']
                    })
                    .append($('<span>').addClass('glyphicon glyphicon-info-sign'))
                    .tooltip());
        }
        return td;
    }));
    k.push(new Kolom('controls', ''));
    var t = new Tabel('index.php?action=data&data=kinderenTabel', k);
    t.setUp($('#kinderen_tabel'));
    t.setFilter(new Object());
    var controls = new Array();
    controls.push(new Control('Wijzigen', 'btn btn-sm', wijzig_kind));
    controls.push(new Control('Verwijderen', 'btn btn-sm', verwijder_kind));
    t.setControls(controls);
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
    });
});
</script>
HERE;
        $this->setContent($content);
    }

}
?>
