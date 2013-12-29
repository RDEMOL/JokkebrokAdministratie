<?php
require_once(dirname(__FILE__)."/../page.php");
class Uitstappen extends Page{
    public function __construct(){
        parent::__construct("Uitstappen", "", "uitstappen");
        $this->buildContent();
    }
    public function buildContent(){
        $content = <<<HERE
<style type="text/css">
tr.uitstap :hover{
    cursor:pointer;
}
</style>
<div class="row">
<div class="col-md-4">
<div class="panel panel-default">
<div class="panel-heading">
<strong>Uitstapoverzicht</strong>
</div>
<div class="panel-body">
<button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Nieuwe uitstap</button>
<table class="table table-hover table-bordered">
<tr class="uitstap"><td>Zwembad
<tr class="uitstap"><td>Bos
</table>
</div>
</div>
</div>
<div class="col-md-8">
<div class="panel panel-default">
<div class="panel-heading">
<strong>Uitstapdetails</strong>
</div>
<div class="panel-body text-center">

<em>details van de uitstap</em>

</div>
</div>
</div>
</div>
HERE;
        $this->setContent($content);
    }
}
?>
