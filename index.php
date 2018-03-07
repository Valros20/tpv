<?php

date_default_timezone_set('Europe/Madrid');

require 'classes/AutoLoader.php';

// $action = Request::read("action");
// $route = Request::read("route");

$action = '';
$route = '';
$urlParams = Request::read('urlparams');
$parametros = explode('/', $urlParams);
if(isset($parametros[0])) {
    $route = $parametros[0];
} else {
    $route = Request::read("route");
}
if(isset($parametros[1])) {
    $action = $parametros[1];
} else {
    $action = Request::read("action");
}

$frontController = new FrontController($route);

$frontController->doAction($action);
echo $frontController->doOutput($action);