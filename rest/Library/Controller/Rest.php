<?php

/**
* 
*/
abstract class Controller_Rest
{
	protected $typeReturn;

	public function getTypeReturn(){
		if(empty($this->typeReturn))
			return "json";
		return $this->typeReturn;
	}

	abstract public function get();

	abstract public function post();

	abstract public function put();
	
	abstract public function delete();

}