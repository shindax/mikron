<?php

// онеуюкх

	define("MAV_ERP", TRUE);

	include "../../config.php";
	include "../db_cfg.php";
	include "../../db_func.php";
	include "../../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	include "../../includes/cookie.php";


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



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// бяонлнцюрекэмше тсмйжхх ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// $_GET["date"]	$_GET["smen"]		$_GET["resurs"]		$_GET["idoper"]

	if (db_adcheck("db_zadan")) {

		$ID_zakdet = "0";
		$ID_zak = "0";
		$ID_park = "0";
		
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$_GET["idoper"]."')");
		$xxx = mysql_fetch_array($xxx);
		$ID_zakdet = $xxx["ID_zakdet"];
		$ID_park = $xxx["ID_park"];
		$yyy = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$ID_zakdet."')");
		$yyy = mysql_fetch_array($yyy);
		$ID_zak = $yyy["ID_zak"];
										
		/*if (strlen($_GET['date'])>8){
			$dates_cur = explode("|", $_GET['date']);
			$smens_cur = explode("|", $_GET['smen']);
			foreach($dates_cur as $key_1 => $val_1){
				if (strlen($val_1)==8){
					dbquery("DELETE FROM ".$db_prefix."db_zadan WHERE (SMEN='".$smens_cur[$key_1]."') AND (DATE='".$val_1."') AND (ID_resurs='".$_GET["resurs"]."') AND (ID_operitems='".$_GET["idoper"]."') ");						
					$xx3x3 = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$val_1."') and (SMEN = '".$smens_cur[$key_1]."') and (ID_resurs = '".$_GET["resurs"]."') ");
					if ($xx4x4 = mysql_fetch_array($xx3x3)){
					}else{
						dbquery("DELETE FROM ".$db_prefix."db_zadanres WHERE (SMEN='".$smens_cur[$key_1]."') AND (DATE='".$val_1."') AND (ID_resurs='".$_GET["resurs"]."') ");						
					}
				}
			}
		}*/
		if (strlen($_GET['date'])==8){
			dbquery("DELETE FROM ".$db_prefix."db_zadan WHERE (SMEN='".$_GET["smen"]."') AND (DATE='".$_GET["date"]."') AND (ID_resurs='".$_GET["resurs"]."') AND (ID_operitems='".$_GET["idoper"]."') ");
				$xx3x3 = dbquery("SELECT * FROM ".$db_prefix."db_zadan WHERE (SMEN='".$_GET["smen"]."') AND (DATE='".$_GET["date"]."') AND (ID_resurs='".$_GET["resurs"]."') ");
				if ($xx4x4 = mysql_fetch_array($xx3x3)){
				}else{
					dbquery("DELETE FROM ".$db_prefix."db_zadanres WHERE (SMEN='".$_GET["smen"]."') AND (DATE='".$_GET["date"]."') AND (ID_resurs='".$_GET["resurs"]."') ");						
				}
		}
	}

?>