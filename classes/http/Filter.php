<?php

class Filter {
    
    static function isBoolean($value) {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    static function isDate($value) {
        $res = false;
        $valuesDate = explode('/', $value);
        if(count($valuesDate) == 3 && checkdate($valuesDate[1], $valuesDate[0], $valuesDate[2])){
            $res = true;
        }
        return $res;
    }

    static function isEmail($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    static function isFloat($value) {
        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }

    static function isInteger($value) {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    static function isIP($value) {
        return filter_var($value, FILTER_VALIDATE_IP);
    }

    static function isLogin($value) {
        $res = false;
        $pattern = '/^[A-Za-z][\w]{4}$/';
        if(preg_match($pattern,$value)){
            $res = true;
        }
        return $res;
    }

    static function isMaxLength($value, $length) {
        $res = false;
        if(strlen($value)<=$length){
            $res = true;
        }
        return $res;
    }

    static function isMinLength($value, $length) {
        $res = false;
        if(strlen($value)>=$length){
            $res = true;
        }
        return $res;
    }

    static function isNumber($value) {
        return is_numeric($value);
    }

    static function isTime($value) {
        $res = false;
        $pattern='/^([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])$/';
        if(preg_match($pattern,$value)){
            $res = true;
        }
        return $res;
    }

    static function isURL($value) {
        return filter_var($value, FILTER_VALIDATE_URL);
    }
    
}