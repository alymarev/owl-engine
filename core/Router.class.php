<?php
/**
 * Created by PhpStorm.
 * User: лымарев
 * Date: 08.11.2014
 * Time: 22:47
 */

class Router {
    public static function start(){
        $ca = URL::getControllerAndAction();
        $c_n = ucfirst($ca["controller"])."Controller";
        $a_n = "action".ucfirst($ca["action"]);
        $controller = "";
        try{
            if(class_exists($c_n)) $controller = new $c_n();
            if(method_exists($controller, $a_n)) $controller->$a_n();
        }catch (Exception $e){
            if($e->getMessage() != "ACCESS_DENIED"){
                $controller = new Controller();
                $controller->action404();
            }
        }
    }
} 