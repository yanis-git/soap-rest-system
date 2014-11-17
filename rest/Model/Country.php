<?php
/**
* 
*/
class Model_Country extends Model_Data_Base
{
	protected $table = "countries";

	public function findByContinentCode($continent_code){
		return $this->findBy(array("continent_code" => $continent_code));
	}
}