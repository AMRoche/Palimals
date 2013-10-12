<?php
function performQuery($retrieve) {
	global $creds;
	$mysqli = new mysqli($creds["domain"], $creds["username"], $creds["pass"], $creds["db"]);
	if ($mysqli -> connect_errno) {
		echo("{'status':false,'response':'Our database borked.'}");
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
	$query = "SELECT * FROM user WHERE " . $close;
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
		echo("{'status':false,'response':'Our database borked.'}");
		return false;
	}
}

function inDB($input, $type) {
	global $creds;
	$mysqli = new mysqli($creds["domain"], $creds["username"], $creds["pass"], $creds["db"]);
	if ($mysqli -> connect_errno) {
		echo("{'status':false,'response':'Our database borked.'}");
		return false;
	}
	$mysqli -> set_charset("utf8");
	$stupidThing = $mysqli -> real_escape_string($input);
	if ($sql = $mysqli -> query("SELECT COUNT(*) FROM user WHERE " . $type . " LIKE '" . $stupidThing . "'")) {
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
		echo("{'status':false,'response':'Our database borked.'}");
		return false;
	}
}

function writeToDB($inputArr, $table) {

	global $creds;
	$mysqli = new mysqli($creds["domain"], $creds["username"], $creds["pass"], $creds["db"]);
	if ($mysqli -> connect_errno) {
		echo("{'status':false,'response':'Our database borked.'}");
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
		echo("{'status':true,'response':'You've got an account!.'}");
		return true;
	} else {
		echo("{'status':false,'response':'" . $mysqli -> error . "'}");
		return false;

	}
}

function randomString() {
	mt_srand(microtime(true) * 100000 + memory_get_usage(true));
	//echo(md5(uniqid(mt_rand(), true)));
	return md5(uniqid(mt_rand(), true));
}
?>