<?php
/**
 * Created by PhpStorm.
 * User: лымарев
 * Date: 08.11.2014
 * Time: 23:12
 */

class FormProcessor {
    private  $request,$message;

    function __construct($request, $message)
    {
        $this->message = $message;
        $this->request = $request;
    }

    public function  process($message_name, $obj, $fields, $checks = array(), $success = "")
    {
        try {

            if (is_null($this->checks($message_name, $checks))) return null;
            foreach ($fields as $field) {
                if (is_array($field)) {
                    $f = $field[0];
                    $v = $field[1];
                    if (strpos($f, "()") !== false) {
                        $f = str_replace("()", "", $f);
                        $obj->$f($v);
                    } else $obj->$f = $v;
                } else $obj->$field = $this->request->$field;
            }
            if ($obj->save()) {
                if ($success) $this->setSessionMessage($message_name, $success);
                return $obj;
            }
        } catch (Exception $e) {
            $this->setSessionMessage($message_name, $this->getError($e));
            return null;
        }
    }

    public function checks($message_name, $checks){
        try{
            for($i = 0; $i < count($checks);$i++){
                $equal = isset($checks[$i][3])?$checks[$i][3]:true;
                if ($equal && $checks[$i][0] != $checks[$i][1]) throw new Exception($checks[$i][2]);
                elseif (!$equal && $checks[$i][0] == $checks[$i][1]) throw new Exception($checks[$i][2]);
            }
            return true;
        }catch (Exception $e){
            $this->setSessionMessage($message_name, $this->getError($e));
            return null;
        }
    }

    public function setSessionMessage($to, $message)
    {
        if (!session_id()) session_start();
        $_SESSION['message'] = array($to => $message);
    }

    private function getError($e){
        if($e instanceof ValidatorException){
            $error = current($e->getErrors());
            return $error[0];
        }
        elseif($message = $e->getMessage()) return $message;
        return "UNKNOWN_ERROR";
    }

    public function auth($message, $object, $method, $login, $password)
    {
        try {
            $user = $object::$method($login, $password);
            return $user;
        } catch (Exception $e) {
            $this->setSessionMessage($message, $this->getError($e));
            return false;
        }
    }

    public function getSessionMessage($to){
        if(!session_id()) session_start();
        if(!empty($_SESSION["message"]) && !empty($_SESSION['message'][$to])){
            $message = $_SESSION['message'][$to];
            unset($_SESSION['message'][$to]);
            return $this->message->get($message);
        }
        return false;
    }

    public function uploadIMG($message_name, $file, $max_size, $dir, $source_name = false)
    {
        try {
            $name = File::uploadIMG($file, $max_size, $dir, false, $source_name);
            return $name;
        } catch (Exception $e) {
            $this->setSessionMessage($message_name, $this->getError($e));
            return false;
        }
    }


}