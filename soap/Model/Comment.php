<?php
// require 'Abstract.php';

class Model_Comment extends Model_Abstract{
	
	protected $filename = "comments.xml";
	protected $name = "comments";
	protected $row = "comment";
	protected $data_path;


	/**
	* @param int $place_id
	* @return string[] $comment
	*/
	public function find($place_id){
		$res = $this->findBy(array("place_id" => $place_id));
		if(empty($res))
			return array();
		else
			return $res;
	}
	
	/**
	* @param int $place_id
	* @return string[][] $comments
	*/
	public function findByPlaceId($place_id){
		$res = $this->findBy(array("place_id" => $place_id));
		if(empty($res))
			return array();
		else
			return $res;
	}

	/**
	* @param string $author
	* @param string $content
	* @param int $rate
	* @param int $place_id
	* @return string[] $comment
	*/
	public function insert($author,$content,$rate,$place_id){
		
		$data = array(
			"author" => $author,
			"content" => $content,
			"rate" => $rate,
			"place_id" => $place_id
		);

		return $this->add($data);
	}
}

//** try **//
// $t = new Model_Comment();
// var_dump($t->findBy(array("place_id" => 1)));