<?php
require_once(dirname(__FILE__)."/../page.php");
require_once(dirname(__FILE__)."/../../model/werkingen/werking.class.php");

class InstellingenPage extends Page{
    public function __construct(){
        parent::__construct("Instellingen", "", "instellingen");
        $this->buildContent();
    }
    private function getVerwijderExtraatjeModal(){
        $content = <<<HERE
<div class="modal fade" id="VerwijderExtraatjeModal" tabindex="-1" role="dialog" aria-labelledby="VerwijderExtraatjeModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="buton" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="VerwijderExtraatjeModalTitle">Extraatje verwijderen</h4>
            </div>
            <div class="modal-body">
                <form id="VerwijderExtraatjeForm">
                    <input type="hidden" name="Id">
                </form>
                <p>Bent u zeker dat u dit extraatje wilt verwijderen?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-primary" id="btnVerwijderExtraatje">Verwijderen</button>
            </div>
        </div>
    </div>
</div>
HERE;
        return $content;
    }
    private function getExtraatjeModal(){
        $content = <<<HERE
<div class="modal fade" id="ExtraatjeModal" tabindex="-1" role="dialog" aria-labelledby="ExtraatjeModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="modal-title">Extraatje</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline">
                <input type="hidden" name="Id" value="0"></input>
                    <div class="row">
                        <label class="control-label" for="Omschrijving">Omschrijving: </label>
                        <input type="text" name="Omschrijving" value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <button type="button" class="btn btn-primary" id="btnExtraatjeOpslaan">Opslaan</button>
            </div>
        </div>
    </div>
</div>
<script>

</script>
HERE;
        return $content;
    }
    private function getNieuweWerkingModal(){
        $content = <<<HERE
<div class="modal fade" id="nieuweWerkingModal" tabindex="-1" role="dialog" aria-labelledby="nieuweWerkingModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="modal-title">Nieuwe Werking toevoegen</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline">
                    <div class="row">
                        <label class="control-label" for="afkorting">Afkorting: </label>
                        <input type="text" value="">
                    </div>
                    <div class="row">
                        <label for="omschrijving" class="control-label">Omschrijving: </label>
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
    private function getWerkingenContent(){
        $nieuweWerkingModal = $this->getNieuweWerkingModal();
        $content = <<<HERE
$nieuweWerkingModal
<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>Omschrijving
    <th>Afkorting
    <th>
</tr>
<tbody>    
HERE;
        $werkingen = Werking::getWerkingen();
        foreach($werkingen as $w){
            $content .= "<tr><td>".$w->getOmschrijving()."</td><td>".$w->getAfkorting()."</td><td><button class='btn btn-sm'>Wijzigen</button>&nbsp;<button class='btn btn-sm'>Verwijderen</button></td></tr>";
        }
        $content .= "</tbody></table>";
        $content .= <<<HERE
<button class="btn btn-large btn-primary" data-toggle="modal" data-target="#nieuweWerkingModal">Nieuwe werking toevoegen</button>
<script>

</script>
HERE;
        return $content;
    }
    private function getExtraatjesContent(){
        $content = $this->getExtraatjeModal();
        $content .= $this->getVerwijderExtraatjeModal();
        $content .= <<<HERE
<table class="table table-striped table-bordered" id="Extraatjes">

<!--<tbody>
HERE;
        $extraatjes = Extraatje::getExtraatjes();
        foreach($extraatjes as $e){
            $content .= "<tr><td>".$e->getOmschrijving()."</td><td><button class='btn btn-sm'>Wijzigen</button>&nbsp;<button class='btn btn-sm'>Verwijderen</button></tr>";
        }
        $content .= <<<HERE
</tbody>-->
</table>
<button class="btn btn-large btn-primary" id="btnNieuwExtraatje">Nieuw extraatje toevoegen</button>
<script>
require(['tabel', 'tabel/kolom', 'tabel/control', 'tabel/controls_kolom', 'tabel/filter_rij', 'tabel/filter_veld'], function(Tabel, Kolom, Control, ControlsKolom, FilterRij, FilterVeld, require){
    function wijzig_extraatje(data){
        clearExtraatjeForm();
        $('#ExtraatjeModal input[name=Omschrijving]').val(data['Omschrijving']);
        $('#ExtraatjeModal input[name=Id]').val(data['Id']);
        $('#ExtraatjeModal').modal('show');
    };
    function verwijder_extraatje(data){
        console.log("verwijder extraatje: "+JSON.stringify(data));
        $('#VerwijderExtraatjeModal input[name=Id]').val(data['Id']);
        $('#VerwijderExtraatjeModal').modal('show');
    };
    function clearExtraatjeForm(){
      $('#ExtraatjeModal input[name=Omschrijving]').val('');
      $('#ExtraatjeModal input[name=Id]').val('0');
    }
    function nieuw_extraatje(){
        clearExtraatjeForm();
      $('#ExtraatjeModal').modal('show');  
    };
    var k = new Array();
    //k.push(new Kolom('Id','Id'));
    k.push(new Kolom('Omschrijving','Omschrijving'));
    var controls = new Array();
    controls.push(new Control('Wijzigen', 'btn btn-sm', wijzig_extraatje));
    controls.push(new Control('Verwijderen', 'btn btn-sm', verwijder_extraatje));
    k.push(new ControlsKolom(controls));
    var extraatjes_tabel = new Tabel('index.php?action=data&data=extraatjesTabel', k);
    extraatjes_tabel.setUp($('table#Extraatjes'));
    $('#btnNieuwExtraatje').click(function(){
       nieuw_extraatje(); 
    });
    $(document).ready(function(){
        extraatjes_tabel.laadTabel();
    });
    $('#btnExtraatjeOpslaan').click(function(){
        $('#ExtraatjeModal form').submit();
    });
    $('#ExtraatjeModal form').submit(function(){
       $.post('index.php?action=updateExtraatje', $('#ExtraatjeModal form').serialize(), function(r){
           r = $.trim(r);
           console.log("update extraatje result: "+r);
           if(r == "1"){
                extraatjes_tabel.laadTabel();
                $('#ExtraatjeModal').modal('hide');
           }else{
               console.log("update Extraatje mislukt");
           }
       });
       return false;
    });
    $('#btnVerwijderExtraatje').click(function(){
       console.log("data: "+$('#VerwijderExtraatjeForm').serialize());
       $.post('index.php?action=removeExtraatje', $('#VerwijderExtraatjeForm').serialize(), function(res){
           res = $.trim(res);
            if(res == "1"){
                $('#VerwijderExtraatjeModal').modal('hide');
                extraatjes_tabel.laadTabel();
            }else{
                console.log("extraatje verwijderen mislukt, error code: "+res);
            }
       });
   });
});
</script>
HERE;
        return $content;
    }
    public function buildContent(){
        $werkingen_content = $this->getWerkingenContent();
        $extraatjes_content = $this->getExtraatjesContent();
        $content = <<<HERE
<div class="row">
<div class="col-md-6">
<div class="panel panel-default">
<div class="panel-heading"><strong>Werkingen</strong></div>
<div class="panel-body">
$werkingen_content
</div>
</div>
</div>
<div class="col-md-6">
<div class="panel panel-default">
<div class="panel-heading"><strong>Extraatjes</strong></div>
<div class="panel-body">
$extraatjes_content
</div>
</div>
</div>
</div>
<script>

</script>
HERE;
        $this->setContent($content);
    }
}
?>
