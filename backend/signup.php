<?php
require_once ("dbreq.php");
require_once ("dbmethods.php");

$recievedCreds = $_POST;
$salt = "";
$queryArr = array();

if (sizeof($recievedCreds) == 0) {
	echo('{"status":false,"response":"Nothing was supplied."}');
	return false;
} else {
	$salt = randomString();
	$queryArr["salt"] = $salt;
}
if (array_key_exists("username", $recievedCreds)) {
	//echo("OLOLOL");
	$queryArr["username"] = $recievedCreds["username"];
}
if (array_key_exists("password", $recievedCreds)) {
	if (CRYPT_SHA512 == 1) {
		$queryArr["password"] = crypt($recievedCreds["password"], $queryArr["salt"]);
	}
}
if (array_key_exists("email", $recievedCreds)) {
	$queryArr["email"] = $recievedCreds["email"];
}
if (array_key_exists("email", $recievedCreds)) {
	$queryArr["email"] = $recievedCreds["email"];
} else {
	echo('{"status":false,"response":"Your email wasn\'t supplied."}');
	return false;
}
if (array_key_exists("email_bool", $recievedCreds)) {
	$queryArr["email_bool"] = $recievedCreds["email_bool"];
} else {
	$queryArr["email_bool"] = "false";
}
$queryArr["money"] = 0;
$queryArr["last_login"] = time();
if (!array_key_exists("username", $queryArr)) {
	echo('{"status":false,"response":"Your username was not supplied."}');
	return false;
}
if (inDB("user",$queryArr["username"], "username") == false) {
	echo('{"status":false,"response":"Your username already exists."}');
	return false;
} else if(inDB("user",$queryArr["email"], "email") == false){
		echo('{"status":false,"response":"Your email address already exists."}');
	return false;
}else{
	writeToDB("user",$queryArr);
}



?>
