<?php
class SoapCli{
	protected $cli = null;

	public function __construct(){
		/**
		 * Récupération des informations de configuration
		**/
		$config = parse_ini_file("../config/application.ini");

		$this->cli = new SoapClient(null, array(
					      'location' => $config["serveur_soap_uri"],
					      'uri'      => $config["serveur_soap_uri"],
					      'trace'    => 1 ));

	}

	public function addComment(array $params){
		try {
			
			$return = $this->cli->__soapCall("insert",$params);
			return $return;

		} catch (SoapFault $e) {
			var_dump($e->getMessage());
			var_dump($e->getTrace());
		}
	}

	public function getCommentsFromPlaceId($place_id){
		try {
			//array("place_id" => $place_id)
			$return = $this->cli->__soapCall("find", array($place_id));
			if(empty($return)) return array();
			return objectToArray($return)["Struct"];

		} catch (SoapFault $e) {
			var_dump($e->getMessage());
			var_dump($e->getTrace());
		}
	}
}

/**
 * Utils : Classe Xml Génération du render à partir d'un tableau associatif
**/

class Xml{

	public static function render($array){
		$xml = "<?xml version=\"1.0\"?>\n<data>";
		foreach ($array as $key => $value) {
			if(is_string($value)){
				if(is_int($key))
					$key = "integer-".$key;
				$xml .= "\t\t<".$key.">".$value."</".$key.">\n";
			}
			else{
				$xml .= "<entry>\n";
				foreach ($value as $subkey => $subvalue) {
					if(is_int($subkey))
						$subkey = "integer-".$subkey;
					$xml .= "\t\t<".$subkey.">".$subvalue."</".$subkey.">\n";
				}
				$xml .= "</entry>\n";
			}
		}
		$xml .= "\n</data>";
		return $xml;
	}
}
 function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}

/**

Sample 

$ob = new SoapCli();
$ob->addComment(
	array(
		"author" => "fabien",
		"content" => "lorem ipsum para bellum",
		"rate" => rand(0,10),
		"place_id" => rand(0,10000)
	)
);

**/