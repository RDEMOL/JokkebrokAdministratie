<?php
require_once(dirname(__FILE__)."/../page.php");
class Instellingen extends Page{
    public function __construct(){
        parent::__construct("Instellingen", "", "instellingen");
        $this->buildContent();
    }
    public function buildContent(){
        $content = <<<HERE
<div class="row">
<div class="col-md-6">
<div class="panel panel-default">
<div class="panel-heading"><strong>Werkingen</strong></div>
<div class="panel-body">
<table class="table table-striped table-bordered">
<thead><tr><th><th>a<th>b<th>c</thead>
<tr><th>d<td>e<td>e<td>f
<tr><th>d<td>e<td>e<td>f
<tr><th>d<td>e<td>e<td>f
</table>
</div>
</div>
</div>
<div class="col-md-6">
<div class="panel panel-default">
<div class="panel-heading"><strong>Extraatjes</strong></div>
<div class="panel-body">
<table class="table table-striped table-bordered">
<thead><tr><th><th>a<th>b<th>c</thead>
<tr><th>d<td>e<td>e<td>f
<tr><th>d<td>e<td>e<td>f
<tr><th>d<td>e<td>e<td>f
</table>
</div>
</div>
</div>
</div>
HERE;
        $this->setContent($content);
    }
}
?>
