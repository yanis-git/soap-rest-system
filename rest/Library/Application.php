<?php

/**
* Application Luncher
* Singleton
*/
class Application
{
	protected static $_app = null;
    protected $params = array();
	public static function getInstance()
	{
		if (self::$_app === null) {
            self::$_app = new Application();
        }

        return self::$_app;
	}

    public function getParams(){
        return $this->params;
    }

    public function hasParam($key){
        return !empty($this->params[$key]);
    }
    public function getParam($key){
        if(!$this->hasParam($key))
            throw new Exception("Param $key doesn't exist on url", 1);
        return $this->params[$key];
    }

	public function run(){
		$params = $this->parseUri();
        $this->callBootstrap();
        $this->params = $params;
		$this->callController($params["controller"]);
	}

    protected function callBootstrap(){

        $boostrap = "Bootstrap";
        $t = get_class_methods($boostrap);
        $boostrap = $boostrap::getInstance();
        foreach ($t as $method) {
            if(substr($method, 0, 4) == "init"){
                $boostrap->$method();
            }
        }

    }

	protected function callController($param){
		//Default Value
		$controller = "index";
		$action = strtolower($_SERVER["REQUEST_METHOD"]);

		if(!empty($param) and is_string($param))
			$controller = $param;
		try {
            if(!is_file(PUBLIC_PATH."/../Controller/".strtolower($controller).".php")){
                $type = "xml"; 
                throw new RouterException("Ressource not found", 404);
            }
                
			$cc = new $controller;
            $type = $cc->getTypeReturn();
			$result = $cc->$action();

		} catch (RouterException $e) {
			$result = self::error($e,$type);
		}
        catch (Exception $e) {
            $e = new RouterException("Internal Error",500);
            $result = self::error($e,$type);
        }
        finally {
            header('Access-Control-Allow-Origin: *');
			if($type == "xml"){
				header ("Content-Type:text/xml");
		        $xml = Xml::createXML('result', $result);
		        echo $xml->saveXML();
	    	}
			else{
				header('Content-Type: application/json');
				echo json_encode($result);
			}
		}
	}

	protected function parseUri(){
		$link = explode('?', $_SERVER['REQUEST_URI'], 2); // On remove les Parametres GET
		$link = $link[0];
        
		if(substr($link, -1) !== "/")
			$link = $link."/";

		$tmp = explode("/", $link);

		unset($tmp[count($tmp) - 1]);
		
		array_shift($tmp);
        $params = array();
        $params["controller"] = (empty($tmp[0]))?"":$tmp[0];
        if(!empty($tmp[0]))
            unset($tmp[0]);
        $count = count($tmp);
        
        if($count % 2 == 0){
        for ($i=1; $i <= $count -1; $i++)
            $params[$tmp[$i]] = $tmp[($i +1)];
        }

		return $params;	
	}

	public static function error(RouterException $e){
        $formated_header = self::getHeaderStatutCode($e->getCode());
        return array(
            "codeErrorHttp" => $e->getCode(),
            "header"        => $formated_header,
            "message"       => $e->getMessage(),
            "origin"        => sprintf("Fichier : %s ligne : %s",$e->getFile(),$e->getLine())
        );
    }

    public static function getHeaderStatutCode($statusCode) {
        $status_codes = array (
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            426 => 'Upgrade Required',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not Extended'
        );

        if(!array_key_exists($statusCode, $status_codes)){
            $statusCode = 500;
        }
        
        $status_string = $statusCode . ' ' . $status_codes[$statusCode];
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $status_string, true, $statusCode);
        return $status_string;
    }
}


class RouterException extends Exception
{

}