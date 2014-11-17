<?php 
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) or strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' or (empty($_POST) && empty($_GET))) {
	header("HTTP/1.0 404 Not Found");
	exit();
}

require "../soap/client.php";
//HEADER XML
// header ("Content-Type:text/xml");

$return = array("status" => "error", "message" => "parameter missing");
//Check action
if(!empty($_POST["action"]) and $_POST["action"] == "addComment"){
	if(empty($_POST["placeid"]) or empty($_POST["comment"]) or empty($_POST["rate"]) or empty($_POST["author"])){
		echo Xml::render($return);exit;
	}

	$return = addComment(array(
		"author" => $_POST["author"],
		"content" => $_POST["comment"],
		"rate" => (int) $_POST["rate"],
		"place_id" => (int) $_POST["placeid"],
	));
	// $return = addComment(array(
	// 	"author" => "fabien",
	// 	"content" => "lorem ipsum para bellum",
	// 	"rate" => rand(0,10),
	// 	"place_id" => rand(0,10000)
	// ));
}
elseif(!empty($_GET["action"]) and $_GET["action"] == "getComments"){
	if(empty($_GET["placeid"])){
		echo Xml::render($return);
		exit;
	}
	$return = getCommentsFromPlaceId((int)$_GET["placeid"]);
}

/*
"townid" : town_id,
			"comment" : comment,
			"rate" : rate,
			"author" : author,
*/
echo Xml::render($return);


//**** SECTION FUNCTION AND TOOLS ***//

function addComment($params){
	$ob = new SoapCli(); //var_dump($params);exit;
	$r = $ob->addComment($params);
	return $r;
}

function getCommentsFromPlaceId($place_id){
	$ob = new SoapCli();
	$r = $ob->getCommentsFromPlaceId($place_id);
	return $r;
}