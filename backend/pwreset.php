<?php
require_once ("dbreq.php");
require_once ("dbmethods.php");

$recievedCreds = $_POST;
$results = array();
if(sizeof($_POST)<2){
	echo('{"status":false,"response":"You didn\'t supply enough info."}');
	return false;
}
if (!array_key_exists("username", $recievedCreds)) {
	echo('{"status":false,"response":"You didn\'t supply a username."}');	
	return false;
}
else{
	$results["username"] = $recievedCreds["username"];
}
if (!array_key_exists("password", $recievedCreds)) {
	echo('{"status":false,"response":"You didn\'t supply a password."}');	
}
else{
	$results["password"] = $recievedCreds["password"];
}
updateDB("user",array("username"=>$results["username"]),array("password"=>$results["password"]));

?>