<?php
/**
 * Created by PhpStorm.
 * User: лымарев
 * Date: 08.11.2014
 * Time: 17:30
 */

abstract class AbstractValidator {
    const CODE_UNKNOWN = "UNKNOWN_ERROR";

    protected  $data;
    private $errors;

    public function __construct($data){
        $this->data = $data;
        $this->validate();
    }

    protected abstract function validate();

    public function getErrors() {
        return $this->errors;
    }

    public function isValid() {
        return count($this->errors) == 0;
    }

    protected function setError($code) {
        $this->errors[] = $code;
    }

    protected function isContainQuotes($string)
    {
        $array = array("\"", "'", "`", "&quot;", "&apos;");
        foreach ($array as $value) {
            if (strpos($string, $value) !== false) return true;
        }
        return false;
    }
}