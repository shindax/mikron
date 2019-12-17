<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//
//////////////////////////////////////////////////////

	define("MAV_ERP", TRUE);



	function TodayDate() {
		$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
		$value=date("d.m.Y",$theday);
		return $value;
	}

// ПОЕХАЛИ


	include "config.php";
	include "includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	include "includes/cookie.php";
	include "includes/config.php";

	
	if ($_GET['db'] == 'db_logistic_app') {
		$date = mysql_result(dbquery("SELECT `DATE` FROM `okb_db_logistic_app` WHERE `ID` = " . (int) $_GET['id']), 0);

		if ((time() - $date) > 1800) {
		//	die();
		}
	}

	$db = $_GET['db'];
	$field = $_GET['field'];
	$id = $_GET['id'];
	$val = $_GET['value'];
	$Name = $db."/".$field;
	$chbx = $_GET['setchbx'];

	if ($db_cfg[$Name]=="boolean") {
		$xx = "";
		if ($val=="true") $xx = 1;
		if ($val=="false") $xx = 0;
		$val = $xx;
	}

	if ($db_cfg[$Name]=="date") {
		$dd = explode(".",$val);
		$date = $dd[2]*10000+$dd[1]*100+$dd[0];
		$val = $date;
	}

	if ($db_cfg[$Name]=="dateplan") {
		$xxx = dbquery("Select * from ".$db_prefix.$db." where (ID='$id')");
		$row = mysql_fetch_array($xxx);

		$newval = explode("|",$row[$field]);

		if ($chbx=="1") {
		   // Если изменяем статус
			$xx = "";
			if ($val=="true") $xx = 1;
			if ($val=="false") $xx = 0;
			$newval[0] = $xx;
			$newval[] = TodayDate()."#".$user["ID"]."#".TodayDate();
			$val = implode("|",$newval);
		}

		if ($chbx=="0") {
		   // Если добавляем дату
			if ($newval[0]=="") $newval[0]="0";
			$newval[] = TodayDate()."#".$user["ID"]."#".$val;
			$val = implode("|",$newval);
		}



	}

function txt($text) {
	$text = stripslashes($text);
	$search = array("@%1@", "@%2@", "@%3@", "@%4@", "@%5@", "@%6@", "@%7@", "@%8@", "@%9@");
	$replace = array("&#39;", "&quot;", "(", ")", "\n", "&#38;", "#", "&#092;", "+");
	$text = str_replace($search, $replace, $text);
	return $text;
}

	$val = txt($val);
	
	if ((db_check($db,$field)) && ($id*1>0)){
		dbquery("Update ".$db_prefix."$db Set $field:='$val' where (ID='$id')");
		if ($db_cfg[$db."|EDITTIME"].""!=="") {
			dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITTIME"].":='".mktime()."' where (ID='".$id."')");
		}
		if ($db_cfg[$db."|EDITUSER"].""!=="") {
			dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITUSER"].":='".$user["ID"]."' where (ID='".$id."')");
		}

		// ONCHANGE
		$change_id = $id;
		if ("".$db_cfg[$db."/".$field."|ONCHANGE"]!=="") include "project/".$db_cfg[$db."/".$field."|ONCHANGE"];

		echo "Saved to database: \"Update $db Set $field:='$val' where (ID='$id')\"";


// shindax 22.11.2017 Синхронизация медосмотров

		if ( $db == 'db_safety_job' && $field == 'E1_2') 
		{
      $query = "UPDATE ".$db_prefix."db_resurs SET DATE_LMO = $val WHERE ID IN ( SELECT `ID_RESURS` FROM `okb_db_safety_job` WHERE ID = $id )";
			dbquery( $query );
		}

		if ( $db == 'db_safety_job' && $field == 'E2_2') 
		{
      $query = "UPDATE ".$db_prefix."db_resurs SET DATE_NMO = $val WHERE ID IN ( SELECT `ID_RESURS` FROM `okb_db_safety_job` WHERE ID = $id )";
			dbquery( $query );
		}

// shindax 22.11.2017 
 

			if ($db == 'db_operitems' && $field == 'NORM') {
				dbquery("UPDATE okb_db_mtk_perehod SET EUSER = " . $user['ID'] . ", ETIME = " . time() . ' WHERE ID_operitems = ' . $id);
			}


		if ($_GET['db'] == 'db_files_2' && $_GET['field'] == 'DATE') {
			list($day, $month, $year) = explode('.', $_GET['value']);
			
			$id = mysql_result(dbquery("SELECT `ID` FROM `okb_db_files_2_cat` WHERE `NAME` LIKE '%" . $year ."%' LIMIT 1"), 0);

			
			dbquery("UPDATE `okb_db_files_2` SET `ID_files_2_cat` = " . $id . " WHERE `ID` = " . $_GET['id']);
		}
	}else{
	if (($db=='db_zapros_all') or ($db=='db_itrzadan')){
		dbquery("Update ".$db_prefix."$db Set $field:='$val' where (ID='$id')");
		if ($db_cfg[$db."|EDITTIME"].""!=="") {
			dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITTIME"].":='".mktime()."' where (ID='".$id."')");
		}
		if ($db_cfg[$db."|EDITUSER"].""!=="") {
			dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITUSER"].":='".$user["ID"]."' where (ID='".$id."')");
		}

		// ONCHANGE
		$change_id = $id;
		if ("".$db_cfg[$db."/".$field."|ONCHANGE"]!=="") include "project/".$db_cfg[$db."/".$field."|ONCHANGE"];

		echo "Saved to database: \"Update $db Set $field:='$val' where (ID='$id')\"";
	}
	}

// ЛОГ
if (!db_check($db,$field)) echo "<b>Access denied</b>";
echo "<br><br>";
echo "cfg[NAME] = $Name<br>";
echo "DB Name = $db<br>";
echo "Field = $field<br>";
echo "ID = $id<br>";
echo "Value = \"".$val."\"<br>";
?>