<?php
require_once ("dbreq.php");
require_once ("dbmethods.php");

$recievedCreds = $_POST;
$results = array();
if(sizeof($recievedCreds)!=2){
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
	return false;
}
else{
	$results["password"] = $recievedCreds["password"];
}

if(inDB("user",$results["username"],"username") != false){
	echo('{"status":false,"response":"Your username isn\'t in the array."}');	
	return false;
}else{
	$resArr = performQuery("user",array("username"=>$results["username"]));
	if($resArr["password"] == crypt($results["password"], $resArr["salt"])){
		echo('{"status":true,"response":"Logged In!"}');	
		return true;
	}
	else{
		echo('{"status":false,"response":"No Log In For You!"}');	
		return false;
	}
}
?>