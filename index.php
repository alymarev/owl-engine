<?php
/**
 * Created by PhpStorm.
 * User: лымарев
 * Date: 15.01.15
 * Time: 23:21
 */


require_once "core/AbstractDataBase.class.php";
$db = new AbstractDataBase("localhost","owl_engine","","root","owl_","{?}");
echo $db->query("INSERT INTO `table` (`id`,`text`) VALUES ({?},{?})", array(1,"text"));