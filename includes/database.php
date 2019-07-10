<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


error_reporting(0);
ini_set('display_errors', false);

/////////////////////////////////////////////////////////////////////////////////////
//
// Соединение и запросы к БД
//
/////////////////////////////////////////////////////////////////////////////////////

function dbquery_old($query) {
	global $dbquery_index, $db_prefix;

	$dbquery_index = $dbquery_index + 1;
	$result = @mysql_query($query);
	if (!$result) {
		$txt = explode($db_prefix,$query);
		if (count($txt)>1) {
			$txt = explode(" ",$txt[1]);
			$txt = $txt[0];
		} else {
			$txt = "";
		}
		echo $txt.": ".mysql_error()."<br>";
		return false;
	} else {
		return $result;
	}
}

function dbquery($query) 
{
	global $dbquery_index, $db_prefix;

	$dbquery_index += 1;
	$result = mysql_query($query);
	if(!$result) 
	  {
		$txt = explode($db_prefix,$query);
		if (count($txt)>1) 
		{
			$txt = explode(" ",$txt[1]);
			$txt = $txt[0];
		} 
      else 
        $txt = "";

//		$out_str = $txt.": ".mysql_error();
		$out_str = "MySQL error in ".__FUNCTION__." function in file : ".__DIR__."\\".__FILE__." at ".__LINE__." line. $txt : ".mysql_error();
		
		// echo $out_str."<br>";
		
		$out_str .= "\n";
		
		  $file = $_SERVER['DOCUMENT_ROOT']."/mysql_error_log.txt";

    file_put_contents( $file ,$out_str . ' : ' .  $query, FILE_APPEND );
		return false;
	} 
    else 
      return $result;
}

function dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset) {
	$db_connect = mysql_connect($db_host, $db_user, $db_pass);
	$db_select = mysql_select_db($db_name);
	if (!$db_connect) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to establish connection to MySQL</b><br>".mysql_errno()." : ".mysql_error()."</div>");
	} elseif (!$db_select) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to select MySQL database</b><br>".mysql_errno()." : ".mysql_error()."</div>");
	}
	if (($db_connect) && ($db_select)) {
		mysql_query("set character_set_client='".$db_charset."'");
		mysql_query("set character_set_connection='".$db_charset."'");
		mysql_query("set character_set_database = '".$db_charset."'");
		mysql_query("set character_set_results='".$db_charset."'");
		mysql_query("set character_set_server = '".$db_charset."'");
	}
}

function dbarraynum($query) {
	$result = mysql_fetch_row($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbrows($query) {
	$result = mysql_num_rows($query);
	return $result;
}


?>