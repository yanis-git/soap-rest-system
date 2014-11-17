<?php

class index extends Controller_Rest{

	protected $typeReturn = "xml";

	public function get(){
		echo "get";
	}

	public function post(){
		return array("lorem" => "ipsum", "params" => array("izi" => "pizi", "dans" => "taface"));
	}

	public function put(){

	}

	public function delete(){

	}
}