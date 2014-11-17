<?php

class Model_Place extends Model_Data_Base
{
	protected $table = "place";

	public function fetchAllWithTown(){
		$req = "SELECT place.*, town.name AS cityname FROM place INNER JOIN town WHERE town.id = place.town_id";
		return $this->_wrapper->query($req);
	}

	public function findWithTown($id){
		$req = "SELECT place.*, town.name AS cityname FROM place INNER JOIN town WHERE town.id = place.town_id AND place.id = :id";
		$res =  $this->_wrapper->query($req,array(":id" => $id));
		if(empty($res))
			return false;
		else
			return $res[0];
	}

	public function findByWithTown(array $params){
		$req = "SELECT place.*, town.name AS cityname FROM place INNER JOIN town WHERE town.id = place.town_id AND place.town_id = :town_id";
		$res =  $this->_wrapper->query($req,$params);
		return $res;
	}
}