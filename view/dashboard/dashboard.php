<?php
require_once(dirname(__FILE__)."/../page.php");
class Dashboard extends Page{
    public function __construct(){
        parent::__construct("Dashboard", "", "dashboard");
        $this->buildContent();
    }
    public function buildContent(){
        $content = <<<HERE
<div class="row">
<div class="col-md-6">
<table class="table table-striped table-bordered">
<thead><tr><th><th>a<th>b<th>c</thead>
<tr><th>d<td>e<td>e<td>f
<tr><th>d<td>e<td>e<td>f
<tr><th>d<td>e<td>e<td>f
</table>
</div>
<div class="col-md-6">
<table class="table table-striped table-bordered">
<thead><tr><th><th>a<th>b<th>c</thead>
<tr><th>d<td>e<td>e<td>f
<tr><th>d<td>e<td>e<td>f
<tr><th>d<td>e<td>e<td>f
</table>
</div>
</div>
HERE;
        $this->setContent($content);
    }
}
?>
