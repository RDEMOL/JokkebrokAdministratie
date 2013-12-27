<?php
require_once(dirname(__FILE__)."/model/model.php");
require_once(dirname(__FILE__)."/controller/controller.php");
require_once(dirname(__FILE__)."/view/view.php");
$model = new Model();
$controller = new Controller($model);
$view = new View($controller, $model);
$view->output();
?>
