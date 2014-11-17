<?php

class places extends Controller_Rest{

	protected $typeReturn = "xml";

	public function get(){

		$modelPlace = new Model_Place();
		// $modelPlace->fetchAllWithTown();exit;
		if(empty($_GET["townid"]))
			return $modelPlace->fetchAllWithTown();
		else
			return $modelPlace->findByWithTown(array(":town_id" => $_GET["townid"]));
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