<?php
/**
* 
*/
class Model_City extends Model_Data_Base
{
	protected $table = "town";


	public function search($country_id, $q, $limit, $offset)
	{
		return $this->_wrapper->query("SELECT * FROM ".$this->table." WHERE country_id = :country_id AND name LIKE :name LIMIT $offset,$limit",array('country_id' => $country_id, "name" => "%$q%"));
	}

}