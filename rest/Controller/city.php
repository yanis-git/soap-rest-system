<?php

class city extends Controller_Rest{

	protected $typeReturn = "xml";

	public function get(){
		//Check Parameter
		if(empty($_GET["country"]))
			throw new RouterException("parameter country missed", 409);

		if(empty($_GET["limit"]) or $_GET["limit"] > 1000)
			$limit = 1000;
		else
			$limit = (int) $_GET["limit"];

		if(empty($_GET["offset"]))
			$offset = 0;
		else
			$offset = (int) $_GET["offset"];
		


		$modelCountry = new Model_Country();
		$country = $modelCountry->findBy(array('code' =>  strtoupper($_GET["country"])));
		if(empty($country))
			throw new RouterException("country code ".$_GET["country"]." doesn't exist", 404);
		$country = $country[0];



		$modelCity = new Model_City();
		if(!empty($_GET["q"]))
			return $modelCity->search($country["id"],$_GET["q"],$limit,$offset);
		else
			return $modelCity->findByLimit(array("country_id" => $country["id"]),$limit,$offset);
	}

	public function post(){
		throw new RouterException("Method Not Allowed",405);
	}

	public function put(){
		throw new RouterException("Method Not Allowed",405);
	}

	public function delete(){
		throw new RouterException("Method Not Allowed",405);
	}
}