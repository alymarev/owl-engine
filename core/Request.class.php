<?php
/**
 * Created by PhpStorm.
 * User: лымарев
 * Date: 08.11.2014
 * Time: 21:26
 */

class Request {
    private $data;

    function __construct()
    {
        $this->data = $this->xss($_REQUEST);
    }

    public function __get($name){
        if(isset($this->data[$name])) return $this->data[$name];
    }

    private function  xss($data){
        if(is_array($data)){
            $esc = array();
            foreach($data as $key => $value){
                $esc[$key] = $this->xss($value);
            }
            return $esc;
        }
        return trim(htmlspecialchars($data));
    }

    public function getRequest(){
        return $this->data;
    }
} 