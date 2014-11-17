<?php

class place extends Controller_Rest{

	protected $typeReturn = "xml";

	public function get(){
		//Check Parameter
		$app = Application::getInstance();
		
		if(!$app->hasParam("id"))
			throw new RouterException("parameter id missed", 409);

		$id = (int) $app->getParam("id");

		$place = new Model_Place();
		$dataPlace = $place->findWithTown($id);
		if(!$dataPlace){
			throw new RouterException("this town doesn't exist.",409);
		}
		return $dataPlace;
	}

	public function post(){
		if(!$this->postIsValid($_POST))
			throw new RouterException("your parameter is not valid.",409);
		
		$city = new Model_City();
		$town = $city->find((int)$_POST["town_id"]);
		

		if(!$town){
			throw new RouterException("this town doesn't exist.",404);
		}

		$place = new Model_Place();
		$place->setFromArray(array(
			"name" => $_POST["name"],
			"address" => $_POST["address"],
			"description" => $_POST["description"],
			"longitude" => (float)$_POST["longitude"],
			"latitude" => (float)$_POST["latitude"],
			"town_id" => $town["id"]
		));

		$place->save();
		return $place->toArray();
	}

	public function put(){
		$app = Application::getInstance();
		if(!$app->hasParam("id"))
			throw new RouterException("parameter id missed", 409);

		$id =(int) $app->getParam("id");
		$place = new Model_Place();
		$dataPlace = $place->find($id);
		if(!$dataPlace){
			throw new RouterException("this town doesn't exist.",409);
		}

        if($_SERVER['CONTENT_TYPE'] !== "application/x-www-form-urlencoded")
        	throw new RouterException("you parameter must come with x-www-form-urlencoded content type.", 409);

		//HACK : récuperer les parametres put dans le fichier d'input standard. cela doit venir de "x-www-form-urlencoded"
        parse_str(file_get_contents("php://input"), $post_vars);

		$place->setFromArray($this->getSanitarizeParams($post_vars));
		$place->save();
		return $place->toArray();
	}

	public function delete(){
		throw new RouterException("Method Not Allowed",405);
	}

	public function postIsValid($params){
		$r = true;

		if(empty($params["name"]) or !is_string($params["name"])) $r = false;
		if(empty($params["description"]) or !is_string($params["description"])) $r = false;
		if(empty($params["town_id"]) or !is_integer((int)$params["town_id"])) $r = false;
		if(empty($params["longitude"]) or !is_float((float)$params["longitude"])) $r = false;
		if(empty($params["latitude"]) or !is_float((float)$params["latitude"])) $r = false;
		if(empty($params["address"]) or !is_string($params["address"])) $r = false;

		return $r;
	}

	public function getSanitarizeParams($params){
		$r = array();

		if(!empty($params["name"])) $r["name"] = $params["name"];
		if(!empty($params["description"])) $r["description"] = $params["description"];
		if(!empty($params["address"])) $r["address"] = $params["address"];
		if(!empty($params["longitude"])) $r["longitude"] = (float) $params["longitude"];
		if(!empty($params["latitude"])) $r["latitude"] = (float) $params["latitude"];
		if(!empty($params["town_id"])) $r["town_id"] = (float) $params["town_id"];

		return $r;
	}
}