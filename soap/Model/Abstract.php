<?php

class Model_Abstract {
	
	protected $filename = "";
	protected $name = "";
	protected $row = "";
	protected $data_path;

	protected $xml;
	protected $xmlObject;

	public function __construct(){
		$this->setDataPath(dirname(__FILE__)."/../Data/");

		if(!file_exists($this->getFilePath())){
			file_put_contents($this->getFilePath(), '<?xml version="1.0"?><'.$this->name.'></'.$this->name.'>');
		}
	}

	protected function getDataPath(){
		return $this->data_path;
	}

	protected function setDataPath($path){
		if(!is_dir(realpath($path)))
			throw new Exception("Le path ne pointe pas sur un dossier valide : ".realpath($path), 1);
		
		$this->data_path = realpath($path);
	}


	protected function getFilePath(){
		return $this->getDataPath()."/".$this->filename;
	}

	protected function save(array $data){

	}

	protected function add(array $data){
		$xmlObject = $this->getXmlObject();
		$rows = $xmlObject->xpath('//'.$this->name);
		$rows = $rows[0];
		$row = $rows->addChild($this->row);
		
		foreach ($data as $key => $value) {
			$row->addChild($key, $value);
		}

		//DEB Prettify output
		$dom = new DOMDocument("1.0");
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xmlObject->asXML());
		//END Prettify output
		if(is_file($this->getDataPath()."/".$this->name.'.xsd')){
			//Si une xsd existe, on controle l'intégrité du xml.
			$isValid = $dom->schemaValidate($this->getDataPath()."/".$this->name.'.xsd');
			if(!$isValid){
				throw new Exception("le xml ne respect pas la xsd : ".$this->getDataPath()."/".$this->name.'.xsd', 1);
				
			}
		}

		file_put_contents($this->getFilePath(), $dom->saveXML());
		return $data;
	}

	protected function remove(){

	}

	public function findBy(array $params){
		$res = array();
		$xmlObject = $this->getXmlObject();
		foreach ($params as $key => $value) {
			foreach($xmlObject->xpath('//'.$this->row.'['.$key.'="'.$value.'"]') as $data) {
				$res[] = $data;
			}
		}
		return $res;
	}

	protected function getXmlObject(){
		if(empty($this->xmlObject))
			$this->xmlObject = new SimpleXMLElement($this->getFilePath(),null,true);

		return $this->xmlObject;
	}

	protected function getXml(){
		if(empty($this->xml)){
			$this->xml = file_get_contents($this->getFilePath());
		}
		return $this->xml;
	}
}