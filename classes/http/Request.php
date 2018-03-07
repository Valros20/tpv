<?php

class Request {
    
    static function get($name) {
        if(isset($_GET[$name])) {
            return $_GET[$name];
        }
        return null;
    }
    
    static function getpost($name) {
        $value = self::get($name);
        if($value == null) {
            $value = self::post($name);
        }    
        return $value;
    }
    
    static function post($name) {
        if(isset($_POST[$name])) {
            return $_POST[$name];
        }
        return null;
    }
    
    static function postget($name) {
        $value = self::post($name);
        if($value == null) {
            $value = self::get($name);
        }    
        return $value;
    }
    
    static function read($name) {
        return self::postget($name);
    }
    
}