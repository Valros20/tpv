<?php

class FrontController {

    private $controller;
    private $model;
    private $view;

    function __construct($routeName = null) {
        $routeName = strtolower($routeName);

        $router = new Router();
        $route = $router->getRoute($routeName);

        $modelName = $route->getModel();
        $viewName = $route->getView();
        $controllerName = $route->getController();

        $this->model = new $modelName();
        $this->view = new $viewName($this->model);
        $this->controller = new $controllerName($this->model);
    }

    function doAction($action = null) {
        $action = strtolower($action);
        if (method_exists($this->controller, $action)) {
            $this->controller->$action();//registrar
        } else {
            $this->controller->index();
        }
    }

    function doOutput($action = null) {
        return $this->view->render($action);
    }

}