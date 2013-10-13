<?php
function updateDB($table,$toGetBy,$toUpdate){
	//UPDATE table_name SET field1=new-value1, field2=new-value2 [WHERE Clause]

	
if(sizeof($toGetBy) == 0 || sizeof($toUpdate) == 0){
		echo('{"status":false,"response":"You didn"t supply a password."}');	
	return false;
}	
	global $creds;
	$mysqli = new mysqli($creds["domain"], $creds["username"], $creds["pass"], $creds["db"]);
	if ($mysqli -> connect_errno) {
		echo('{"status":false,"response":"Our database borked."}');
		return false;
	}
	$mysqli -> set_charset("utf8");
	if(array_key_exists("username",$toGetBy)){
		if(inDB("user",$toGetBy["username"],"username") != false){
			echo('{"status":false,"response":"Your username isn\'t in the array."}');	
			return false;
		}
	}

	if(array_key_exists("password",$toUpdate) && array_key_exists("username",$toGetBy)){
		$returnArr = performQuery("user",array("username"=>$toGetBy["username"]));
	//	var_dump($returnArr);
		if (CRYPT_SHA512 == 1) {
			$toUpdate["password"] = crypt($toUpdate["password"], $returnArr["salt"]);
		}
	}
	$close = "";
	foreach ($toGetBy as $a => $res) {
		if (strlen($close) > 0) {
			$close .= " AND ";
		}
		$close .= $a . " LIKE " . '"' . $res . '"';
	}
	
	$updates = "";
	foreach ($toUpdate as $a => $res) {
		if (strlen($updates) > 0) {
			$updates .= " , ";
		}
		if (gettype($res) == "string" && $res != "true" && $res != "false" && $a!="last_login") {
			$updates .= $a.'="' . $mysqli -> real_escape_string($res) . '"';
		} else {
			if ($a == "last_login") {
				$updates .=$a."= CURRENT_TIMESTAMP";
			} else {
				$updates .= $a."=".$val;
			}
		}
	}
	
	$query = "UPDATE ".$table." SET ".$updates." WHERE ".$close;
	//echo($query);
	if ($sql = $mysqli -> query($query)) {
		echo('{"status":true,"response":"Value Updated!."}');
		return true;
	} else {
		echo('{"status":false,"response":"' . $mysqli -> error . '}');
		return false;
	}
	return false;
}

function performQueryMultiple($table, $retrieve) {
	global $creds;
	$mysqli = new mysqli($creds["domain"], $creds["username"], $creds["pass"], $creds["db"]);
	if ($mysqli -> connect_errno) {
		echo('{"status":false,"response":"Our database borked"}');
		return false;
	}
	$mysqli -> set_charset("utf8");
		$close = "";
	foreach ($retrieve as $a => $res) {
		if (strlen($close) > 0) {
			$close .= " AND ";
		}
		$close .= $a . " LIKE " . '"' . $res . '"';
	}
	$query = "SELECT * FROM ".$table." WHERE " . $close;
	$arr = array();
	if ($sql = $mysqli -> query($query)) {
		while($row = mysqli_fetch_array($sql,MYSQL_ASSOC)){
			array_push($arr,$row);
		}
		return $arr;
		//* free result set */
//		mysqli_free_result($row);
	} else {
		echo('{"status":false,"response":"Our database borked."}');
		return false;
	}
}

function performQuery($table, $retrieve) {
	global $creds;
	$mysqli = new mysqli($creds["domain"], $creds["username"], $creds["pass"], $creds["db"]);
	if ($mysqli -> connect_errno) {
		echo('{"status":false,"response":"Our database borked"}');
		return false;
	}
	$mysqli -> set_charset("utf8");
		$close = "";
	foreach ($retrieve as $a => $res) {
		if (strlen($close) > 0) {
			$close .= " AND ";
		}
		$close .= $a . " LIKE " . '"' . $res . '"';
	}
	$query = "SELECT * FROM ".$table." WHERE " . $close;
	$arr = array();
	if ($sql = $mysqli -> query($query)) {
		while($row = mysqli_fetch_array($sql,MYSQL_ASSOC)){
			array_push($arr,$row);
		}
		if(sizeof($arr)>0){
		$arr = $arr[0]; //refactor this eventually		
		}
		return $arr;
		//* free result set */
//		mysqli_free_result($row);
	} else {
		echo('{"status":false,"response":"Our database borked."}');
		return false;
	}
}

function inDB($table,$input, $type) {
	global $creds;
	$mysqli = new mysqli($creds["domain"], $creds["username"], $creds["pass"], $creds["db"]);
	if ($mysqli -> connect_errno) {
		echo('{"status":false,"response":"Our database borked."}');
		return false;
	}
	$mysqli -> set_charset("utf8");
	$stupidThing = $mysqli -> real_escape_string($input);
	if ($sql = $mysqli -> query("SELECT COUNT(*) FROM ".$table." WHERE " . $type . " LIKE '" . $stupidThing . "'")) {
		$row = mysqli_fetch_row($sql);
		if ($row[0] == "0") {
			$mysqli -> close();
			return true;
		} else {
			$mysqli -> close();
			return false;
		}
		//* free result set */
		mysqli_free_result($row);
	} else {
		echo('{"status":false,"response":"Our database borked."}');
		return false;
	}
}

function writeToDB($table,$inputArr) {

	global $creds;
	$mysqli = new mysqli($creds["domain"], $creds["username"], $creds["pass"], $creds["db"]);
	if ($mysqli -> connect_errno) {
		echo('{"status":false,"response":"Our database borked"}');
		return false;
	}
	$mysqli -> set_charset("utf8");
	$categories = "";
	$values = "";
	foreach ($inputArr as $in => $val) {
		$categories .= $in;
		if (gettype($val) == "string" && $val != "true" && $val != "false") {
			$values .= '"' . $mysqli -> real_escape_string($val) . '"';
		} else {
			if ($in == "last_login") {
				$values .= "CURRENT_TIMESTAMP";
			} else {
				$values .= $val;
			}
		}
		if (strlen($categories) > 0 && array_search($in, array_keys($inputArr)) != sizeof($inputArr) - 1) {$categories .= ",";
		}
		if (strlen($values) > 0 && array_search($in, array_keys($inputArr)) != sizeof($inputArr) - 1) {$values .= ",";
		}
	}
	if ($sql = $mysqli -> query("INSERT INTO " . $table . " (" . $categories . ") VALUES (" . $values . ")")) {
		echo('{"status":true,"response":"You\'ve got an account!."}');
		return true;
	} else {
		echo('{"status":false,"response":"' . $mysqli -> error . '"}');
		return false;

	}
}

function randomString() {
	mt_srand(microtime(true) * 100000 + memory_get_usage(true));
	//echo(md5(uniqid(mt_rand(), true)));
	return md5(uniqid(mt_rand(), true));
}
?>