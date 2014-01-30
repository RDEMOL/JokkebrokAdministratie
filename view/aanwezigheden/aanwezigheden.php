<?php
require_once (dirname(__FILE__) . "/../page.php");
require_once(dirname(__FILE__)."/../../model/speelpleindag/speelpleindag.php");
class AanwezighedenPage extends Page {
    public function __construct() {
        parent::__construct("Aanwezigheden","","aanwezigheden");
        $this->buildContent();
    }

    public function buildContent() {
        $vandaag = new SpeelpleinDag();
        $datum = $vandaag->getDatum();
        $content = <<<HERE
<div class="row">
    <button class="btn btn-primary">Nieuwe aanwezigheid</button>
     <label for="datum">Datum:</label>
        <input id="datum" name="datum" type="text" value="$datum"></input>
        <button id="btnVandaag" class="btn btn-sm">Vandaag</button>
    <div class="pull-right">
        <button id="btnPdf" class="btn">Pdf tonen</button>
    </div>
</div>
<br>
<div class="row">
<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>Voornaam
    <th>Naam
    <th>Werking
    <th>Drankje
    <th>Ijsje
    <th>Middag
    <th>Medische info
    <th>
    <th>
</tr>
</thead>
<tbody>
<tr>
    <td>Jonas
    <td>Peeters
    <td>Tieners
    <td>Ja
    <td>Nee
    <td>Thuis
    <td>Hooikoorts
    <td><button class="btn btn-sm">Verwijderen</button>
    <td><button class="btn btn-sm">Wijzigen</button>
</tr>
<tr>
    <td>Jelle
    <td>Peeters
    <td>Tieners
    <td>Ja
    <td>Nee
    <td>Thuis
    <td>Hooikoorts
    <td><button class="btn btn-sm">Verwijderen</button>
    <td><button class="btn btn-sm">Wijzigen</button>
</tr>
<tr>
    <td>Jasper
    <td>Peeters
    <td>Tieners
    <td>Ja
    <td>Nee
    <td>Thuis
    <td>Hooikoorts
    <td><button class="btn btn-sm">Verwijderen</button>
    <td><button class="btn btn-sm">Wijzigen</button>
</tr>
</tbody>
</table>
</div>
<script>
$(document).ready(function(){
$('#datum').datepicker().data('datepicker');
});
</script>
HERE;
        $this->setContent($content);
    }

}
?>
