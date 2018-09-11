<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


if ($logged) {


/////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// СПЕЦИАЛЬНЫЕ ОБРАБОТКИ ДЛЯ СИСТЕМЫ
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////


// Обработка изменений профиля

	if (isset($_POST["EditProfile"])) {
		$io = stripinput($_POST["IO"]);
		$do_redirect = false;

		$last_md5 = md5($_POST["LastPASS"]);
		$new_1_md5 = md5($_POST["NewPASS"]);
		$new_2_md5 = md5($_POST["NewPASS2"]);

		if ($_POST["NewPASS"]!=="") {
			if ($new_1_md5!==$new_2_md5) $MESSAGE = $loc["p8"];
			if ($last_md5!==$user["PASS"]) $MESSAGE = $loc["p9"];

			if ($MESSAGE == "") {
				dbquery("Update ".$db_prefix."users Set PASS:='".$new_1_md5."' where (ID='".$user["ID"]."')");
				$do_redirect = true;
			}
		}
		if ($MESSAGE=="") {
			dbquery("Update ".$db_prefix."users Set IO:='".$io."' where (ID='".$user["ID"]."')");
			$user["IO"] = $io;
		}
		if ($do_redirect) redirect($pageurl);
	}

// Обработка добавления пользователей

	if (isset($_POST["AddUSER"]) && ($user["USERSEDIT"]=="1")) {
		$login = stripinput($_POST["Login"]);

		if (strlen($login)<3) $MESSAGE = $loc["u3"];
		$xxx = dbquery("SELECT ID FROM ".$db_prefix."users where (LOGIN='".$login."')");
		if ($res = mysql_fetch_array($xxx)) $MESSAGE = $loc["u2"];

		if ($MESSAGE=="") {
			dbquery("INSERT INTO ".$db_prefix."users (LOGIN, PASS) VALUES ('".$login."', '".md5($newpass)."')");
			redirect($pageurl);
		}
	}

// Обработка сброса пароля пользователя

	if (isset($_GET["nullpass"]) && ($user["USERSEDIT"]=="1") && ($_GET["do"]=="users")) {
		$pass_id = $_GET["nullpass"]*1;
		if ($pass_id!==1) {
			dbquery("Update ".$db_prefix."users Set PASS:='".md5($newpass)."' where (ID='".$pass_id."')");
		}
		redirect($pageurl);
	}







/////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// ОБРАБОТКИ ДЛЯ ВСЕХ ТАБЛИЦ (И СИСТЕМЫ И PROJECT)
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////



// Обработка добавления к таблице


	function CreateElement($db,$pid,$lid,$addfield = "",$addval = "",$addfield2 = "",$addval2 = "") {
		global $db_prefix, $db_cfg, $loc, $add_error, $today_0, $user;

		if ($db_cfg[$db."|TYPE"]=="line") dbquery("INSERT INTO ".$db_prefix.$db." () values ()");
		if ($db_cfg[$db."|TYPE"]=="tree") dbquery("INSERT INTO ".$db_prefix.$db." (PID) values ('".$pid."')");
		if ($db_cfg[$db."|TYPE"]=="ltree") dbquery("INSERT INTO ".$db_prefix.$db." (PID, LID) values ('".$pid."', '".$lid."')");

		$insert_id = mysql_insert_id();


		if ($db_cfg[$db."|CREATEBY"].""!=="") {
			dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|CREATEBY"].":='".$user["ID"]."' where (ID='".$insert_id."')");
		}
		if ($db_cfg[$db."|CREATEDATE"].""!=="") {
			dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|CREATEDATE"].":='".DateToInt($today_0)."' where (ID='".$insert_id."')");
		}
		if ($db_cfg[$db."|CREATETIME"].""!=="") {
			dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|CREATETIME"].":='".date('H:i:s')."' where (ID='".$insert_id."')");
		}
		if (($db_cfg[$db."|BYPARENT"].""!=="") && ($pid>0)) {
			$byparent = explode("|",$db_cfg[$db."|BYPARENT"]);
			$result = dbquery("SELECT * FROM ".$db_prefix.$db." where (ID='".$pid."')");
			if ($par = mysql_fetch_array($result)) {
				$byparrent_count = count($byparent);
				for ($bp=0;$bp < $byparrent_count;$bp++) {
					dbquery("Update ".$db_prefix.$db." Set ".$byparent[$bp].":='".$par[$byparent[$bp]]."' where (ID='".$insert_id."')");
				}	
			}
		}
		if ($db_cfg[$db."|EDITTIME"].""!=="") {
			dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITTIME"].":='".mktime()."' where (ID='".$insert_id."')");
		}
		if ($db_cfg[$db."|EDITUSER"].""!=="") {
			dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITUSER"].":='".$user["ID"]."' where (ID='".$insert_id."')");
		}
		if ($addfield!=="") dbquery("UPDATE ".$db_prefix.$db." Set ".$addfield."='".$addval."' where (ID='".$insert_id."')");
		if ($addfield2!=="") dbquery("UPDATE ".$db_prefix.$db." Set ".$addfield2."='".$addval2."' where (ID='".$insert_id."')");

		if ("".$db_cfg[$db."|ADDWITH"]!=="") {
			$listid = "ID";
			$addwith = explode("|",$db_cfg[$db."|ADDWITH"]);
			$addwith_count = count($addwith);
			for ($dw=0;$dw < $addwith_count;$dw++) {
				$wadd = explode("/",$addwith[$dw]);
				if (count($wadd)==2) {
					$conferr = false;
					if ($wadd[0]==$db) $conferr = true;
					if ("".$db_cfg[$wadd[0]."|TYPE"]=="") $conferr = true;
					if (("".$db_cfg[$wadd[0]."/".$wadd[1]]=="") and ($wadd[1]!=="PID") and ($wadd[1]!=="LID")) $conferr = true;
					if ((!$conferr) and ($wadd[1]!=="PID") and ($wadd[1]!=="LID")) {
						if ($db_cfg[$wadd[0]."/".$wadd[1]."|LIST"]!==$db) $conferr = true;
						if ($db_cfg[$wadd[0]."/".$wadd[1]]=="multilist") $conferr = true;
					}
					if (!$conferr) {
						CreateElement($wadd[0],0,0,$wadd[1],$insert_id);
					} else {
						$add_error = "ERROR: ".$loc["29"];
					}
				} else {
					$add_error = "ERROR: ".$loc["29"];
				}
			}
		}
		return $insert_id;
	}



	if (isset($_GET["addnew"])) {
		$db = $_GET["addnew"];
		$pid = (isset($_GET["pid"]) ? $_GET["pid"] : 0);
		$lid = (isset($_GET["lid"]) ? $_GET["lid"] : 0);
		$addf = (isset($_GET["addf"]) ? $_GET["addf"] : "");
		$addv = (isset($_GET["addv"]) ? $_GET["addv"] : "");
		$addf2 = (isset($_GET["addf2"]) ? $_GET["addf2"] : "");
		$addv2 = (isset($_GET["addv2"]) ? $_GET["addv2"] : "");

		$add_error = "";

		if (db_adcheck($db)) {
			$insert_id = CreateElement($db,$pid,$lid,$addf,$addv,$addf2,$addv2);
			if ("".$db_cfg[$db."|ONCREATE"]!=="") include "./project/".$db_cfg[$db."|ONCREATE"];
			if ($add_error=="") {
				redirect($pageurl."&event");
			} else {
				$MESSAGE = $add_error;
			}
		} else {
			$MESSAGE = $loc["20"];
		}
	}

// Обработка удаления элемента



	function DeleteElement($row,$db) {
		global $db_prefix, $db_cfg, $del_error, $del_array, $loc;

		
		$usedin = RowUsedIn($row,$db);
		$deldeny = RowDelDeny($row,$db);
		if ((!$usedin) && (!$deldeny)) {
			if ("".$db_cfg[$db."|DELWITH"]!=="") {
				$listid = "ID";
				$delwith = explode("|",$db_cfg[$db."|DELWITH"]);
				for ($dw=0;$dw < count($delwith);$dw++) {
					$wdel = explode("/",$delwith[$dw]);
					if (count($wdel)==2) {
						$conferr = false;
						if ("".$db_cfg[$wdel[0]."|TYPE"]=="") $conferr = true;
						if (("".$db_cfg[$wdel[0]."/".$wdel[1]]=="") and ($wdel[1]!=="PID") and ($wdel[1]!=="LID")) $conferr = true;
						if ((!$conferr) and ($wdel[1]!=="PID") and ($wdel[1]!=="LID")) {
							if ($db_cfg[$wdel[0]."/".$wdel[1]."|LIST"]!==$db) $conferr = true;
						}
						if (!$conferr) {
							$result = dbquery("SELECT * FROM ".$db_prefix.$wdel[0]." where (".$wdel[1]."='".$row[$listid]."')");
							while ($chld = mysql_fetch_array($result)) {
								DeleteElement($chld,$wdel[0]);
							}
						} else {
							$del_error = "ERROR: ".$loc["28"];
						}
					} else {
						$del_error = "ERROR: ".$loc["28"];
					}
				}
			}
		} else {
			$del_error = "ERROR: ".$loc["27"];
		}
		$del_array[] = "DELETE from ".$db_prefix.$db." where (ID='".$row["ID"]."')";
	}


	if (isset($_GET["delete"])) {

		$del_error = "";
		$del_array = Array();

		$db = $_GET["db"];
		$delid = $_GET["delete"];
		if (db_adcheck($db)) {
			$result = dbquery("SELECT * FROM ".$db_prefix.$db." where (ID='".$delid."')");
			if ($row = mysql_fetch_array($result)) {
				$delet_id = $row['ID'];
				if ($db_cfg[$db."|ONDELETE"]) { include "./project/".$db_cfg[$db."|ONDELETE"];}
				DeleteElement($row,$db);
				if ($del_error=="") {
					for ($q=0;$q < count($del_array);$q++) {
						dbquery($del_array[$q]);
					}
					redirect($pageurl."&event");
				} else {
					$MESSAGE = $del_error;
				}
			}
		} else {
			$MESSAGE = $loc["20"];
		}
	}

// Оработка открытия дерева

	if (isset($_GET["open"])) {
		redirect($pageurl."&event");
	}

	if (isset($_GET["openall"])) {
		redirect($pageurl."&event");
	}

	if (isset($_GET["setopened"])) {
		redirect($pageurl."&event");
	}

	if (isset($_GET["addopened"])) {
		redirect($pageurl."&event");
	}


// Оработка закрытия дерева

	if (isset($_GET["close"])) {
		redirect($pageurl."&event");
	}

	if (isset($_GET["closeall"])) {
		redirect($pageurl."&event");
	}


}

?>