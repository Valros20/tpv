<?php

class Router {
    
    private $routes = array();

    function __construct() {
        $this->routes['index'] = new Route('MemberModel', 'MemberView', 'MemberController');
        $this->routes['ajax'] = new Route('MemberModel', 'AjaxView', 'AjaxController');
        //add routes
    }

    function getRoute($route) {
        if (!isset($this->routes[$route])) {
            return $this->routes['index'];
        }
        return $this->routes[$route];
    }
}