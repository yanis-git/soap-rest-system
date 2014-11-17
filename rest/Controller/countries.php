<?php

class countries extends Controller_Rest{

	protected $typeReturn = "xml";

	public function get(){
		$model = new Model_Country();

		if(empty($_GET["continent_code"])){
			return $model->fetchAll(array("name" => "asc"));
		}
		else{
			$t = $model->findByContinentCode($_GET["continent_code"]);
			if(empty($t))
				throw new RouterException("Aucun pays ne correspond à ce continent code", 404);
			else
				return $t;
		}
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