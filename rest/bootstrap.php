<?php

/**
* 
*/
class boostrap extends Bootstrap_Abstract
{

	/***
	* Toute méthode déclaré public et commençant par init seront automatiquement appelé dans le frontController
	* Exemple : 
	* public function initSomeAction();
	***/


	public function initDataBase(){
        $db = DataBase::instance();
        $config = parse_ini_file(PUBLIC_PATH."/config/application.ini");
        $db->configMaster($config["host"],$config["dbname"],$config["username"],$config["password"]);
        $db->configSlave($config["host"],$config["dbname"],$config["username"],$config["password"]);
	}

	public function initAuthentification(){
		$token = Application::getInstance()->getParam("token");
		if(Application::getIstance()->getController() !== "authentification"){
			if(!empty($token) or !$this->isAuth($token)){
				throw new RouterException("Invalid Authentification", 401); // Code erreur :  "Unauthorized"
			}
		}
	}

	protected function isAuth($token){
		return Registry::getInstance()->isRegistered($token); //return true : false;
	}

	protected function auth($login, $passord){
		$modelAgent = new Model_Agent();
		$token = $modelAgent->auth($login,$passord);
		return $token;
	}
}