<?php
header('Access-Control-Allow-Origin: *');
define("PUBLIC_PATH", __DIR__);

set_include_path(implode(PATH_SEPARATOR, array(
    realpath('../'),realpath('../Controller'),realpath('../Library'),get_include_path(),
)));


/**
 *
 * @param string $class_name
 */

function autoload($class_name){
  $className = explode('_', $class_name);
  $path = "";
  foreach($className as $key => $val){
    $path .= $val."/";
  }
  $path = substr($path, 0, strlen($path)-1);
  require_once($path.".php");
}

spl_autoload_register("autoload");

Application::getInstance()
				   ->run();