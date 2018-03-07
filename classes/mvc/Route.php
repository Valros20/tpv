<?php

class Route {

    private $model;
    private $view;
    private $controller;

    function __construct($model, $view, $controller) {
        $this->model = $model;
        $this->view = $view;
        $this->controller = $controller;
    }

    function getController() {
        return $this->controller;
    }

    function getModel() {
        return $this->model;
    }

    function getView() {
        return $this->view;
    }

}