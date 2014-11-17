<?php

class continents extends Controller_Rest{

	protected $typeReturn = "xml";

	public function get(){
		$modelContinent = new Model_Continent();
		return $modelContinent->fetchAll(array("name" => "asc"));
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