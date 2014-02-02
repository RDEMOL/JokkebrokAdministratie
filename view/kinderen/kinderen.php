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
                <h4 class="modal-title">Nieuw kind toevoegen</h4>
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
                        <label class="control-label" for="medische_info">Medische informatie: </label>
                        <textarea name="medische_info"></textarea>
                    </div>
                    <div class="row">
                        <label class="control-label" for="andere_info">Andere informatie: </label>
                        <textarea name="andere_info"></textarea>
                    </div>
                    <div class="row">
                        <h3>Voogd:</h3>
                    </div>
                    <div class="row">
                    <button id="btnAndereVoogd" class="btn btn-default">Nog een voogd toevoegen</button>
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
    t.setFilter(new Object());
    t.laadTabel();
    var voogd_amount = 0;
    $(document).ready(function(){
        //TODO: reset each time modal is launched
        var voegVoogdDivToe = function(){
            ++voogd_amount;
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
            //$('#btnAndereVoogd').insertBefore(el);
            el.insertBefore($('#btnAndereVoogd').parent());
        };
        $('#btnAndereVoogd').click(function(e){
            e.preventDefault();
            voegVoogdDivToe();
            return false;
        });
        var initNieuwKindModal = function(){
            console.log("init!");
            $('#nieuwKindForm').find('input[type=text], textarea').val('');
            $('#nieuwKindForm').find('select').val('0');
          voogd_amount = 0;
          $('.voogd_row').remove();
          voegVoogdDivToe();  
        };
        $('#nieuwKindModal').on('show.bs.modal', initNieuwKindModal);
        $('#nieuwKindForm').submit(function(){
            console.log("submitting!");
            console.log("form data = "+$('#nieuwKindForm').serialize()); 
            $.post('index.php?action=nieuwKind', $('#nieuwKindForm').serializeArray(), function(res){
               if(res == "1"){
                   $('#nieuwKindModal').modal('hide');
                   t.laadTabel();
               }else{
                   console.log("nieuw kind toevoegen mislukt, error code: "+res);
               }
            });
            return false;
       });
       $('#submitNieuwKind').click(function(){
           $('#nieuwKindForm').submit();
       });
    });
});
</script>
HERE;
        $this->setContent($content);
    }
}
?>
