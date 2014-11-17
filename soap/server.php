<?php
/**
 *
 * @param string $class_name
 */

require 'vendor/autoload.php';

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

ini_set("display_errors", 0);
ini_set("default_socket_timeout",120);


use WSDL\WSDLCreator;
$wsdl = new WSDL\WSDLCreator('Model_Comment', $config["serveur_soap_uri"]);
$wsdl->setNamespace("http://esgi.local/");

//On récupère le WSDL
if (isset($_GET['wsdl'])) {
    $wsdl->renderWSDL();
    exit;
}


/**
 * Récupération des informations de configuration
**/
$config = parse_ini_file("../config/application.ini");

/**
 * Initialisation du serveur Soap
**/
try {
	$server = new SoapServer($config["serveur_soap_uri"]."?wsdl", array(
      "uri" => $config["serveur_soap_uri"],
      // 'location' => $wsdl->getLocation()
    ));
	$server->setClass('Model_Comment');
	$server->handle();
} catch (SoapFault $e) {
	print $e->faultstring;
}
