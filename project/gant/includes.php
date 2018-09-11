<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	define("MAV_ERP", TRUE);
	$cookie_id = "GANT";
	include "../../config.php";
	include "../../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	include "../../db_func.php";
	include "../db_cfg.php";
	include "../../includes/cookie.php";
	include "includes/functions.php";

	function utftxt($str) {
		//return iconv("windows-1251","UTF-8",$str);
		return $str;
	}

// опюбю мю днаюбкемхе / сдюкемхе
	function db_adcheck($db) {
		global $db_cfg, $user, $user_rights, $print_mode;

		$res = false;
		if (is_array($user_rights)) {
			if (in_array("superadmin",$user_rights)) $res = true;
			if (in_array($db."|superadmin",$user_rights)) $res = true;
			if (in_array($db."|add",$user_rights)) $res = true;
		}

	   // яоеж рюакхжш
		if (($user["USERSEDIT"]=="1") && ($db=="users")) $res = true;
		if (($user["ID"]=="1") && ($db=="rightgroups")) $res = true;
		if (($user["ID"]=="1") && ($db=="viewgroups")) $res = true;
		if (($user["ID"]=="1") && ($db=="formgroups")) $res = true;
		if (($user["ID"]=="1") && ($db=="forms")) $res = true;
		if (($user["ID"]=="1") && ($db=="formsitem")) $res = true;

		if ($print_mode=="on") $res = false;

		return $res;
	}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>