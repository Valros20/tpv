<?php

class Controller {

    private $model;
    private $session;

    function __construct(Model $model) {
        $this->model = $model;
        $this->session = new Session(Constants::SESSIONNAME);
        if($this->isLogged()) {
            $user = $this->getUser();
            $this->getModel()->setData('login', $user->getLogin());
        }
        $this->getModel()->setData('base', Constants::BASE);
    }

    function getModel() {
        return $this->model;
    }
    
    function getSession() {
        return $this->session;
    }

    function getUser() {
        return $this->getSession()->getUser();
    }

    function index() {
        $this->getModel()->setData('index', 'index');
    }
    
    function isAdministrator() {
        return $this->isLogged() && $this->getUser()->getLogin() === 'admin'; //CAMBIAR A getLogin() = admin
    }
    
    function isAdvanced() {
        return $this->isLogged() && $this->getUser()->getRole() === 'advanced';
    }

    function isLogged() {
        return $this->getSession()->isLogged();
    }

}