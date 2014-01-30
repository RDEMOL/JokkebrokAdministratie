<?php
session_start();
setlocale (LC_TIME, "nl_BE.UTF8");
if(!$coming_from_public_html){
    header('Location: public_html/index.php');
    exit;
}
require_once(dirname(__FILE__)."/model/model.php");
require_once(dirname(__FILE__)."/controller/controller.php");
require_once(dirname(__FILE__)."/view/view.php");
$model = new Model();
$controller = new Controller($model);
$view = new View($controller, $model);
$controller->execute();
$view->output();
?>
