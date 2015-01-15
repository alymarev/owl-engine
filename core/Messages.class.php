<?php
/**
 * Created by PhpStorm.
 * User: лымарев
 * Date: 09.11.2014
 * Time: 18:50
 */

class Messages {
    private $data;

    function __construct($data)
    {
        $this->data = parse_ini_file($data);
    }

    public function get($name){
        return $this->data[$name];
    }


} 