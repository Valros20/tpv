<?php

class FileUpload {
    
    private $input;
    private $name;
    private $target;
    private $size;
    private $policy;
    
    const OVERWRITE = 0;
    const KEEP = 1;
    const RENAME = 2;

    function __construct($input, $name = null, $target = '.', $size = 0, $policy = FileUpload::RENAME) {
        $this->input = $input;
        $this->name = $name;
        $this->target = $target;
        $this->size = $size;
        $this->policy = $policy;
    }

    function getName() {
        return $this->name;
    }

    private function getNewName() {
        $file = $this->name;
        $cont = 0;
        while(file_exists($this->target . '/' . $file)){
            $parts = pathinfo($this->name);
            $cont++;
            $file = $parts['filename'] . '(' . $cont . ')';
            if(isset($parts['extension'])){
                $file = $file . '.' . $parts['extension'];
            }
        }
        return $file;
    }

    function getSize() {
        return $this->size;
    }

    function getTarget() {
        return $this->target;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setSize($size) {
        $this->size = $size;
    }

    function setTarget($target) {
        $this->target = $target;
    }

    function upload() {
        if(isset($_FILES[$this->input])) {
            if($_FILES[$this->input]['error'] === UPLOAD_ERR_OK) {
                if($_FILES[$this->input]['size'] <= $this->size || $this->size === 0) {
                    if($this->name === null) {
                        $this->name = $_FILES[$this->input]['name'];
                    }
                    return $this->uploadPolicy();
                }
            }
        }    
        return false;
    }
    
    private function uploadPolicy(){
        if(FileUpload::OVERWRITE === $this->policy){
           // $extension = explode('.' , $_FILES[$this->input]['name']);
           if(file_exists($this->target . '/' . $this->name)) unlink($this->target . '/' . $this->name);
           return move_uploaded_file($_FILES[$this->input]['tmp_name'], 
                   $this->target . '/' . $this->name);
            //return move_uploaded_file($_FILES[$this->input]['tmp_name'], 
                    //        $this->target . '/' . $this->name . '.' . $extension[1]);
        } else if(FileUpload::KEEP === $this->policy){
            if(!file_exists($this->target . '/' . $this->name)){
                return move_uploaded_file($_FILES[$this->input]['tmp_name'], 
                            $this->target . '/' . $this->name);
            }
        } else if(FileUpload::RENAME === $this->policy){
            $this->name = $this->getNewName();
            return move_uploaded_file($_FILES[$this->input]['tmp_name'], 
                            $this->target . '/' . $this->name);
        }
        return false;
    }
    
}
