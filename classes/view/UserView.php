<?php

class UserView extends View {

    function __construct(Model $model) {
        parent::__construct($model);
    }
    
    private function index() {
        $data = $this->getModel()->getAllData();
        $file = 'template/' . $data['file'];
        return Util::renderTemplate($file, $data);
    }

    function render($action) {
        if(!method_exists(get_class(), $action)) {
            $action = 'index';
        }
        return $this->$action();
    }
    
}