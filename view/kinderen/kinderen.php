<?php
require_once(dirname(__FILE__)."/../page.php");
class Kinderen extends Page{
    public function __construct(){
        parent::__construct("Kinderen", "", "kinderen");
        $this->buildContent();
    }
    public function buildContent(){
        $content = <<<HERE
<div class="row">
    <button class="btn btn-primary">Nieuw kind</button>
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
    <td>Hooikoorts
    <td><button class="btn btn-sm">Verwijderen</button>
    <td><button class="btn btn-sm">Wijzigen</button>
</tr>
<tr>
    <td>Jonas
    <td>Peeters
    <td>Tieners
    <td>Hooikoorts
    <td><button class="btn btn-sm">Verwijderen</button>
    <td><button class="btn btn-sm">Wijzigen</button>
</tr>
<tr>
    <td>Jonas
    <td>Peeters
    <td>Tieners
    <td>Hooikoorts
    <td><button class="btn btn-sm">Verwijderen</button>
    <td><button class="btn btn-sm">Wijzigen</button>
</tr>
</tbody>
</table>
</div>
HERE;
        $this->setContent($content);
    }
}
?>
