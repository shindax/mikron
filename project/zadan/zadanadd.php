<?php

$user_id = $_GET["user_id"];

// �������

	define("MAV_ERP", TRUE);

	include "../../config.php";
	include "../db_cfg.php";
	include "../../db_func.php";
	include "../../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	include "../../includes/cookie.php";


// ����� �� ���������� / ��������
	function db_adcheck($db) {
		global $db_cfg, $user, $user_rights, $print_mode;

		$res = false;
		if (is_array($user_rights)) {
			if (in_array("superadmin",$user_rights)) $res = true;
			if (in_array($db."|superadmin",$user_rights)) $res = true;
			if (in_array($db."|add",$user_rights)) $res = true;
		}

	   // ���� �������
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
// ��������������� ������� ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
					$xx3x3 = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$val_1."') and (SMEN = '".$smens_cur[$key_1]."') and (ID_resurs = '".$_GET["resurs"]."') ");
					if ($xx4x4 = mysql_fetch_array($xx3x3)){
						dbquery("INSERT INTO ".$db_prefix."db_zadan (SMEN, ID_park, ID_zak, ID_zakdet, ID_operitems, ID_resurs, DATE, EDIT_STATE) VALUES ('".$smens_cur[$key_1]."', '".$ID_park."', '".$ID_zak."', '".$ID_zakdet."', '".$_GET["idoper"]."', '".$_GET["resurs"]."', '".$val_1."', '0')");
					}else{
						dbquery("INSERT INTO ".$db_prefix."db_zadanres (SMEN, ORD, DATE, ID_resurs) VALUES ('".$smens_cur[$key_1]."', '0', '".$val_1."', '".$_GET["resurs"]."')");						
						dbquery("INSERT INTO ".$db_prefix."db_zadan (SMEN, ID_park, ID_zak, ID_zakdet, ID_operitems, ID_resurs, DATE, EDIT_STATE) VALUES ('".$smens_cur[$key_1]."', '".$ID_park."', '".$ID_zak."', '".$ID_zakdet."', '".$_GET["idoper"]."', '".$_GET["resurs"]."', '".$val_1."', '0')");						
					}
				}
			}
		}*/
		if (strlen($_GET['date'])==8)
		{
			$id_operitems = $_GET["idoper"];
			$id_resurs = $_GET["resurs"];
			$date = $_GET["date"];
			$smen = $_GET["smen"];

			$query = "INSERT INTO ".$db_prefix."db_zadan 
				(SMEN, ID_park, ID_zak, ID_zakdet, ID_operitems, ID_resurs, DATE, EDIT_STATE, EUSER) 
				VALUES 
				(	$smen, 
				 	$ID_park, 
				 	$ID_zak, 
				 	$ID_zakdet, 
				 	$id_operitems, 
				 	$id_resurs,
				 	$date,
				 	0,
					" . $user['ID'] . "
				 )";

			dbquery( $query );

			$last_insert_id = mysql_insert_id();
			$query = "INSERT INTO production_shift_actions
					( op_type, id_zadan, date, id_zak, id_zakdet, id_park, smen, id_resurs, id_oper, user_id, last_update ) 
					VALUES ( 1, $last_insert_id, $date, $ID_zak, $ID_zakdet, $ID_park, $smen, $id_resurs, $id_operitems, $user_id, NOW() )";

			dbquery( $query );
			$last_log_insert_id = mysql_insert_id();

				$xx3x3 = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE = '".$_GET["date"]."') and (SMEN = '".$_GET["smen"]."') and (ID_resurs = '".$_GET["resurs"]."') ");
				if ($xx4x4 = mysql_fetch_array($xx3x3))
				{
						$query = "UPDATE production_shift_actions SET id_zadanres = {$xx4x4['ID']} WHERE id = $last_log_insert_id";
						dbquery( $query );
				}
				else
				{
						dbquery("INSERT INTO ".$db_prefix."db_zadanres (SMEN, ORD, DATE, ID_resurs) VALUES ('".$_GET["smen"]."', '0', '".$_GET["date"]."', '".$_GET["resurs"]."')");
						$last_insert_id = mysql_insert_id();

						$query = "UPDATE production_shift_actions SET id_zadanres = $last_insert_id WHERE id = $last_log_insert_id";
						dbquery( $query );
				}
		}
	}

?>