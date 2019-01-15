<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////


/////////////////////////////////////////////////////////
//
// Редактирование напрямую
//
// $pageurl должен быть корректно определён до вызова db_func.php
//
/////////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }

	$echo_file_form = "";

$cur_formid = $_GET['formid'];

	if (isset($_GET["edit_lid"])) {
		$edit_lid = $_GET["edit_lid"];
		$edit_lid = explode("|",$edit_lid);

		$db = $edit_lid[0];
		$xid = $edit_lid[1];
		$lid = $edit_lid[2];

		if (db_check($db,"LID")) {
			$res = dbquery("SELECT * FROM ".$db_prefix.$db." where  (ID='".$xid."')");
			$res = mysql_fetch_array($res);
			$disabled = Array();
			OpenDisID ($res,$db,$disabled);
			if (!in_array($lid,$disabled)) {
				dbquery("Update ".$db_prefix.$db." Set LID:='$lid' where (ID='$xid')");

				// EDITTIME EDITUSER
				if ($db_cfg[$db."|EDITTIME"].""!=="") {
					dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITTIME"].":='".mktime()."' where (ID='".$xid."')");
				}
				if ($db_cfg[$db."|EDITUSER"].""!=="") {
					dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITUSER"].":='".$user["ID"]."' where (ID='".$xid."')");
				}

				// ONCHANGE
				$change_id = $xid;
				if ("".$db_cfg[$db."/LID|ONCHANGE"]!=="") include "project/".$db_cfg[$db."/LID|ONCHANGE"];
			}
		}

		redirect($pageurl."&event");
	}

	if (isset($_GET["edit_list"])) {
		$edit_slid = $_GET["edit_list"];
		$edit_slid = explode("|",$edit_slid);

		$db = $edit_slid[0];
		$xid = $edit_slid[1];
		$field = $edit_slid[2];
		$value = $edit_slid[3];

		if (db_check($db,$field)) {
			dbquery("Update ".$db_prefix.$db." Set $field:='$value' where (ID='$xid')");
			// EDITTIME EDITUSER
			if ($db_cfg[$db."|EDITTIME"].""!=="") {
				dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITTIME"].":='".mktime()."' where (ID='".$xid."')");
			}
			if ($db_cfg[$db."|EDITUSER"].""!=="") {
				dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITUSER"].":='".$user["ID"]."' where (ID='".$xid."')");
			}

			// ONCHANGE
			$change_id = $xid;
			if ("".$db_cfg[$db."/".$field."|ONCHANGE"]!=="") include "project/".$db_cfg[$db."/".$field."|ONCHANGE"];
		}else{
			if (($cur_formid=='138') or ($cur_formid=='137')){
				dbquery("Update ".$db_prefix.$db." Set $field:='$value' where (ID='$xid')");
				// EDITTIME EDITUSER
				if ($db_cfg[$db."|EDITTIME"].""!=="") {
					dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITTIME"].":='".mktime()."' where (ID='".$xid."')");
				}
				if ($db_cfg[$db."|EDITUSER"].""!=="") {
					dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITUSER"].":='".$user["ID"]."' where (ID='".$xid."')");
				}

				// ONCHANGE
				$change_id = $xid;
				if ("".$db_cfg[$db."/".$field."|ONCHANGE"]!=="") include "project/".$db_cfg[$db."/".$field."|ONCHANGE"];
			}
		}
		redirect($pageurl."&event");
	}

	if (isset($_GET["edit_state"])) {
		$edit_gl = $_GET["edit_state"];
		$edit_gl = explode("|",$edit_gl);

		$db = $edit_gl[0];
		$xid = $edit_gl[1];
		$field = $edit_gl[2];
		$value = $edit_gl[3];

		if (db_check($db,$field)) {
			dbquery("Update ".$db_prefix.$db." Set $field:='$value' where (ID='$xid')");

			$userfield = $db_cfg[$db."/".$field."|USER"]."";
			if ($userfield !== "") {
				dbquery("Update ".$db_prefix.$db." Set ".$userfield.":='".$user["ID"]."' where (ID='".$xid."')");
			}

			$datefield = $db_cfg[$db."/".$field."|DATE"]."";
			if ($datefield !== "") {
				dbquery("Update ".$db_prefix.$db." Set ".$datefield.":='".TodayInt()."' where (ID='".$xid."')");
			}

			// EDITTIME EDITUSER
			if ($db_cfg[$db."|EDITTIME"].""!=="") {
				dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITTIME"].":='".mktime()."' where (ID='".$xid."')");
			}
			if ($db_cfg[$db."|EDITUSER"].""!=="") {
				dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITUSER"].":='".$user["ID"]."' where (ID='".$xid."')");
			}

			// ONCHANGE
			$change_id = $xid;
			if ("".$db_cfg[$db."/".$field."|ONCHANGE"]!=="") include "project/".$db_cfg[$db."/".$field."|ONCHANGE"];
		}

		redirect($pageurl."&event");
	}

	if (isset($_GET["edit_multilist"])) {
		$edit_ml = $_GET["edit_multilist"];
		$edit_ml = explode("|",$edit_ml);

		$db = $edit_ml[0];
		$xid = $edit_ml[1];
		$field = $edit_ml[2];
		$value = $edit_ml[3];

		if (db_check($db,$field)) {
			$res = dbquery("SELECT $field FROM ".$db_prefix.$db." where  (ID='".$xid."')");
			if ($res = mysql_fetch_array($res)) {
				$last_val = explode("|",$res[$field]);
				
				if (isset($_GET["add_multilist"])) {
					$new_val = array();
					for ($i=0;$i < count($last_val); $i++) {
						if ($last_val[$i]!=="") $new_val[] = $last_val[$i];
					}
					$new_val = "|".implode("|",$new_val)."|".$value."|";
					dbquery("Update ".$db_prefix.$db." Set $field:='$new_val' where (ID='$xid')");
				}
				if (isset($_GET["del_multilist"])) {
					$new_val = array();
					for ($i=0;$i < count($last_val); $i++) {
						if (($last_val[$i]!==$value) && ($last_val[$i]!=="")) $new_val[] = $last_val[$i];
					}
					$new_val = "|".implode("|",$new_val)."|";
					dbquery("Update ".$db_prefix.$db." Set $field:='$new_val' where (ID='$xid')");
				}

				// EDITTIME EDITUSER
				if ($db_cfg[$db."|EDITTIME"].""!=="") {
					dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITTIME"].":='".mktime()."' where (ID='".$xid."')");
				}
				if ($db_cfg[$db."|EDITUSER"].""!=="") {
					dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITUSER"].":='".$user["ID"]."' where (ID='".$xid."')");
				}

				// ONCHANGE
				$change_id = $xid;
				if ("".$db_cfg[$db."/".$field."|ONCHANGE"]!=="") include "project/".$db_cfg[$db."/".$field."|ONCHANGE"];

			}
		}

		redirect($pageurl."&event");
	}

	if (isset($_GET["edit_file"])) {
		$edit_f = $_GET["edit_file"];
		$edit_f = explode("|",$edit_f);

		$db = $edit_f[0];
		$xid = $edit_f[1];
		$field = $edit_f[2];

		if (db_adcheck($db,$field)) {
			if (isset($_GET["del_file"])) {
				$res = dbquery("SELECT $field FROM ".$db_prefix.$db." where  (ID='".$xid."')");
				if ($res = mysql_fetch_array($res)) {
					if (file_exists("project/".$files_path."/".$db."@".$field."/".$res[$field])) {
						unlink("project/".$files_path."/".$db."@".$field."/".$res[$field]);
					}
					dbquery("Update ".$db_prefix.$db." Set $field:='' where (ID='$xid')");

					// EDITTIME EDITUSER
					if ($db_cfg[$db."|EDITTIME"].""!=="") {
						dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITTIME"].":='".mktime()."' where (ID='".$xid."')");
					}
					if ($db_cfg[$db."|EDITUSER"].""!=="") {
						dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITUSER"].":='".$user["ID"]."' where (ID='".$xid."')");
					}

					// ONCHANGE
					$change_id = $xid;
					if ("".$db_cfg[$db."/".$field."|ONCHANGE"]!=="") include "project/".$db_cfg[$db."/".$field."|ONCHANGE"];

				}
				redirect($pageurl."&event");
			}
			if (isset($_GET["add_file"])) {

				$txt = "<div class='allwaystop'>";


					$txt = $txt."<center><div class='addfwin'>";
					$txt = $txt."<H2>".$loc["dbf17"]."</H2>";
					$txt = $txt."<p>".$loc["dbf18"]."</p><br>";

						$txt = $txt."<form enctype='multipart/form-data' method='post' action='".$pageurl."&event'>";
						$txt = $txt."<input type='hidden' name='Upload_FILE' value='ok'>";
						$txt = $txt."<input type='hidden' name='DB' value='".$db."'>";
						$txt = $txt."<input type='hidden' name='ID' value='".$xid."'>";
						$txt = $txt."<input type='hidden' name='FIELD' value='".$field."'>";
						$txt = $txt."<table><tr class='cl_1'><td style='text-align: left;'><input type='file' name='USERFILE'></td>";
						$txt = $txt."<td style='text-align: right;'><input type='submit' value='".$loc["dbf15"]."' style='width: 100px; color: green;'></td></tr></table>";
						$txt = $txt."</form><br><br>";

						$txt = $txt."<form method='post' action='".$pageurl."&event'>";
						$txt = $txt."<table><tr class='cl_2'><td style='text-align: right;'><input type='submit' value='".$loc["dbf16"]."' style='width: 100px; color: red;'></td></tr></table>";
						$txt = $txt."</form>";

					$txt = $txt."<br></div></center>";

				$txt = $txt."</div>";
				$echo_file_form = $txt;
				
			}
		}
	}

	if (isset($_POST["Upload_FILE"])) {

		$db = $_POST["DB"];
		$xid = $_POST["ID"];
		$field = $_POST["FIELD"];

		if (db_adcheck($db,$field)) {
			$res = dbquery("SELECT $field FROM ".$db_prefix.$db." where  (ID='".$xid."')");
			if ($res = mysql_fetch_array($res)) {
				if ($res[$field].""=="") {

					$path = getenv(DOCUMENT_ROOT).dirname($_SERVER["PHP_SELF"])."/project/".$files_path."/".$db."@".$field;
					if (!file_exists($path)) mkdir($path);

					$ftype = explode(".",basename($_FILES['USERFILE']['name']));
					$ftype = $ftype[count($ftype)-1];
					$filename = $user["ID"].date("siHdmY").".".$ftype;
					if (move_uploaded_file($_FILES['USERFILE']['tmp_name'], $path."/".$filename)) {
						dbquery("Update ".$db_prefix.$db." Set $field:='".$filename."' where (ID='$xid')");

						// EDITTIME EDITUSER
						if ($db_cfg[$db."|EDITTIME"].""!=="") {
							dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITTIME"].":='".mktime()."' where (ID='".$xid."')");
						}
						if ($db_cfg[$db."|EDITUSER"].""!=="") {
							dbquery("Update ".$db_prefix.$db." Set ".$db_cfg[$db."|EDITUSER"].":='".$user["ID"]."' where (ID='".$xid."')");
						}

						// ONCHANGE
						$change_id = $xid;
						if ("".$db_cfg[$db."/".$field."|ONCHANGE"]!=="") include "project/".$db_cfg[$db."/".$field."|ONCHANGE"];

					} else {
						$MESSAGE = $loc["dbf19"];
					}
				} else {
					$MESSAGE = $loc["dbf19"];
				}
			} else {
				$MESSAGE = $loc["dbf19"];
			}
		} else {
			$MESSAGE = $loc["dbf19"];
		}
	}


	function FileIcon($filename) {
		$res = "uses/openf.png";
		$txt = strtolower($filename);
		$ftype = explode(".","x.".basename($txt));
		$ftype = $ftype[count($ftype)-1];
		if (file_exists("uses/ftypes/".$ftype.".png")) $res = "uses/ftypes/".$ftype.".png";
		return $res;
	}



/////////////////////////////////////////////////////////
//
// Функция получения и преобразования даты
//
/////////////////////////////////////////////////////////

	function IntToDate2($x) {
		$dd = $x*1;
		$dd_Y = floor($dd/10000);
		$dd_M = floor(($dd-($dd_Y*10000))/100);
		$dd_D = $dd-($dd_Y*10000)-($dd_M*100);
		if ($dd_M<10) $dd_M = "0".$dd_M;
		if ($dd_D<10) $dd_D = "0".$dd_D;
		$res = $dd_D.".".$dd_M.".".$dd_Y;
		if ($dd == 0) $res = "";
		return $res;
	}

	
	function IntToDate($x) {
		$year = substr( $x, 0, 4 );
		$month = substr( $x, 4, 2 );    
		$day = substr( $x, 6, 2 );
		return $x == 0 ? '' : "$day.$month.$year";
	}

	
	function DateToInt2($x) {
		$dd = explode(".",$x);
		if (count($dd)>2) {
			$dd = $dd[0]*1+$dd[1]*100+$dd[2]*10000;
		} else {
			$dd = 0;
		}
		return $dd;
	}

	
		function DateToInt($x) 
	{
	  $dd = explode(".",$x);
	  if (count($dd)>2) 
		$result = $dd[2].$dd[1].$dd[0];
		else 
		  $result = 0;

	  return $result ;
	}
		
		
	function StrDate($x) {
		global $MM_Name2;

		$dd = $x*1;
		$dd_Y = floor($dd/10000);
		$dd_M = floor(($dd-($dd_Y*10000))/100);
		$dd_D = $dd-($dd_Y*10000)-($dd_M*100);
		if ($dd_D<10) $dd_D = "0".$dd_D;

		return $dd_D." ".$MM_Name2[$dd_M]." ".$dd_Y;
	}

	function StrMonth($x) {
		global $MM_Name;

		$dd = $x*1;
		$dd_Y = floor($dd/10000);
		$dd_M = floor(($dd-($dd_Y*10000))/100);
		$dd_D = $dd-($dd_Y*10000)-($dd_M*100);
		if ($dd_D<10) $dd_D = "0".$dd_D;

		return $MM_Name[$dd_M]." ".$dd_Y;
	}

	$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
	$today_0=date("d.m.Y",$theday);
	$today_1=date("d.m.Y",$theday+(1*86400));
	$today_7=date("d.m.Y",$theday+(7*86400));








/////////////////////////////////////////////////////////
//
// Вспомогательные функции
//
/////////////////////////////////////////////////////////

	function DatePlanTable($name,$values) {
	global $db_prefix;
		$res = "";
		$res .= "<span class='ltpopup'><div id='".$name."' class='ltpopup'>";
		$res .= "<table class='PP_tbl' border='1' cellpadding='0' cellspacing='0'>\n";
		$res .= "<tr class='PP_first'><td width='17'>№</td><td>Дата изменения</td><td>Инициатор</td><td>Новая дата</td></tr>";
		for ($j=1;$j < count($values);$j++) {
			$newval = explode("#",$values[$j]);
			$res = $res."<tr><td>".$j."</td>";
			$res = $res."<td>".$newval[0]."</td>";
			$xxx = dbquery("SELECT * FROM ".$db_prefix."users where  (ID='".$newval[1]."')");
			$xxx = mysql_fetch_array($xxx);
			$res = $res."<td>".$xxx["FIO"]."</td>";
			$res = $res."<td>".$newval[2]."</td></tr>";
		}
		$res = $res."</table>";
		$res = $res."</div></span>";
		return $res;
	}














/////////////////////////////////////////////////////////
//
// Функции вывода добавления / удаления строк
//
/////////////////////////////////////////////////////////

function DelHeader($db,$rowspan = 1) {
	global $loc;

	$rstxt = "";
	if ($rowspan>1) $rstxt = " rowspan='".$rowspan."'";
	if (db_adcheck($db)) echo "<td width='20'".$rstxt."><img src='uses/nodel.png' title='".$loc["dbf6"]."'></td>\n";
}

function DelNull($db,$rowspan = 1) {
	global $loc;

	$rstxt = "";
	if ($rowspan>1) $rstxt = " rowspan='".$rowspan."'";
	if (db_adcheck($db)) echo "<td class='Field'".$rstxt.">&nbsp;</td>\n";
}

function AddCatNull($db,$rowspan = 1) {
	global $loc, $db_cfg;
	if (db_adcheck($db)) {	//Проверка по DB_TABLE_2
		$rstxt = "";
		if ($rowspan>1) $rstxt = " rowspan='".$rowspan."'";
		echo "<td class='nbg'".$rstxt.">&nbsp;</td>\n";
	}
}

function AddCatHeader($db,$rowspan = 1) {
	global $loc, $db_cfg;
	if (db_adcheck($db)) {	//Проверка по DB_TABLE_2
		$rstxt = "";
		if ($rowspan>1) $rstxt = " rowspan='".$rowspan."'";
		echo "<td class='nbg' width='28'".$rstxt.">&nbsp;</td>\n";
	}
}

function AddCatField($db,$adf,$adv,$rowspan = 1) {
	global $loc, $db_cfg, $pageurl;
	if (db_adcheck($db)) {	//Проверка по DB_TABLE_2
		$rstxt = "";
		if ($rowspan>1) $rstxt = " rowspan='".$rowspan."'";
		echo "<td class='nbg'".$rstxt."><a href='".$pageurl."&addnew=".$db."&addf=".$adf."&addv=".$adv."' title='".$loc["dbf9"]."'>+</a></td>\n";
	}
}

function AddTreeNull($db,$rowspan = 1) {
	global $loc, $db_cfg;
	if (db_adcheck($db)) {
		$rstxt = "";
		if ($rowspan>1) $rstxt = " rowspan='".$rowspan."'";
		if ($db_cfg[$db."|TYPE"]=="tree") echo "<td class='nbg'".$rstxt.">&nbsp;</td>\n";
		if ($db_cfg[$db."|TYPE"]=="ltree") echo "<td class='nbg'".$rstxt.">&nbsp;</td>\n";
	}
}

function AddTreeHeader($db,$rowspan = 1) {
	global $loc, $db_cfg;
	if (db_adcheck($db)) {
		$rstxt = "";
		if ($rowspan>1) $rstxt = " rowspan='".$rowspan."'";
		if ($db_cfg[$db."|TYPE"]=="tree") echo "<td class='nbg' width='28'".$rstxt.">&nbsp;</td>\n";
		if ($db_cfg[$db."|TYPE"]=="ltree") echo "<td class='nbg' width='28'".$rstxt.">&nbsp;</td>\n";
	}
}

function AddLineLink($db,$addf = "", $addv = "") {
	global $loc, $db_cfg, $pageurl;
	$addfv = "";
	if ($addf!=="") $addfv = "&addf=".$addf."&addv=".$addv;
	if (db_adcheck($db)) {
		if ($db_cfg[$db."|TYPE"]=="line") echo "<div class='addline'><a class='alink' href='".$pageurl."&addnew=".$db.$addfv."'>".$loc["dbf7"]."</a></div>\n";
		if ($db_cfg[$db."|TYPE"]=="tree") echo "<div class='addline'><a class='alink' href='".$pageurl."&addnew=".$db."&pid=0".$addfv."'>".$loc["dbf7"]."</a></div>\n";
		if ($db_cfg[$db."|TYPE"]=="ltree") echo "<div class='addline'><a class='alink' href='".$pageurl."&addnew=".$db."&pid=0&lid=0".$addfv."'>".$loc["dbf7"]."</a></div>\n";
	}
}

function AddTreeGetDeep($row,$db) {
	global $db_prefix;

	$res = 1;
	$xxx = dbquery("SELECT ID, PID FROM ".$db_prefix.$db." where (ID='".$row["PID"]."')");
	if ($obj = mysql_fetch_array($xxx)) {
		$res = AddTreeGetDeep($obj,$db) + 1;
	}

	return $res;
}

function AddTreeField($row,$db,$rowspan = 1) {
	global $loc, $db_cfg, $pageurl,$cur_formid;
	if (db_adcheck($db)) {

		$deep = true;
		if ($db_cfg[$db."|MAXDEEP"]*1>0) {
			if ($row["PID"]*1!==0) {
				// Проверяем по глубине
				$deep = false;
				if (AddTreeGetDeep($row,$db)<$db_cfg[$db."|MAXDEEP"]*1) $deep = true;
			}
		}

		$rstxt = "";
		if ($rowspan>1) $rstxt = " rowspan='".$rowspan."'";
		if ($db_cfg[$db."|TYPE"]=="tree") {
			if ($deep) echo "<td class='nbg'".$rstxt."><a href='".$pageurl."&addnew=".$db."&pid=".$row["ID"]."' title='".$loc["dbf9"]."'>+</a></td>\n";
			if (!$deep) AddTreeNull($db,$rowspan);
		}
		if ($db_cfg[$db."|TYPE"]=="ltree") {
		   if (($row["LID"]*1==0) && ($deep)) {
			echo "<td class='nbg'".$rstxt."><a href=\"javascript:void(0);\" onClick=\"ShowHide('alt_".$db."_".$row["ID"]."', this, $cur_formid);\">+</a>\n";
			echo "<span class='ltpopup'><div class='ltpopup' id=\"alt_".$db."_".$row["ID"]."\">";
			echo "<a href='".$pageurl."&addnew=".$db."&pid=".$row["ID"]."&lid=0' title='".$loc["dbf9"]."' class='add_child'>".$loc["dbf9"]."</a>";

			echo "<a href='".$pageurl."&addnew=".$db."&pid=".$row["ID"]."&lid=1' title='".$loc["dbf20"]."'>".$loc["dbf20"]."</a>";

			echo "<div class='hr'></div>";
			echo "<a href=\"javascript:void(0);\" onClick=\"Hide('alt_".$db."_".$row["ID"]."');\" title='".$loc["dbf16"]."'>".$loc["dbf16"]."</a>";
			echo "</div></span>";
			echo "</td>\n";
		   } else {
			AddTreeNull($db,$rowspan);
		   }
		}
	}
}


// Проверка использования элемента по ID и имени таблицы
function RowUsedIn($row,$db) {
	global $db_prefix, $db_cfg, $MESSAGE;

	$used = false;
	$field = $db_cfg[$db."|LIST_FIELD"];
	$delwith = explode("|","|".$db_cfg[$db."|DELWITH"]);
	$usedin = explode("|",$db_cfg[$db."|USEDIN"]);
	$usedin_count = count($usedin);
	for ($j=0;$j < $usedin_count;$j++) {
		if (($usedin[$j]!=="") && (!$used)) {
			$ui = explode("/",$usedin[$j]);
			$type_var = $db_cfg[$usedin[$j]];
			if ($type_var!=="multilist") {
				if (!in_array($usedin[$j],$delwith)) {
					$xxx = dbquery("SELECT ID FROM ".$db_prefix.$ui[0]." where (".$ui[1]."='".$row["ID"]."') LIMIT 0,1");
					if ($res = mysql_fetch_array($xxx)) $used = true;
				}
			}
			if ($type_var=="multilist") {
				$xxx = dbquery("SELECT ID FROM ".$db_prefix.$ui[0]." where (".$ui[1]." LIKE '%|".$row["ID"]."|%') LIMIT 0,1");
				if ($res = mysql_fetch_array($xxx)) $used = true;
			}
		}
	}
	return $used;
}
function RowUsedInwher($row,$db) {
	global $db_prefix, $db_cfg, $MESSAGE;

	$used = "";
	$field = $db_cfg[$db."|LIST_FIELD"];
	$delwith = explode("|","|".$db_cfg[$db."|DELWITH"]);
	$usedin = explode("|",$db_cfg[$db."|USEDIN"]);
	$usedin_count = count($usedin);
	for ($j=0;$j < $usedin_count;$j++) {
		if (($usedin[$j]!=="") && ($used=="")) {
			$ui = explode("/",$usedin[$j]);
			$type_var = $db_cfg[$usedin[$j]];
			if ($type_var!=="multilist") {
				if (!in_array($usedin[$j],$delwith)) {
					$xxx = dbquery("SELECT ID FROM ".$db_prefix.$ui[0]." where (".$ui[1]."='".$row["ID"]."') LIMIT 0,1");
					if ($res = mysql_fetch_array($xxx)) $used .= $ui[0];
				}
			}
			if ($type_var=="multilist") {
				$xxx = dbquery("SELECT ID FROM ".$db_prefix.$ui[0]." where (".$ui[1]." LIKE '%|".$row["ID"]."|%') LIMIT 0,1");
				if ($res = mysql_fetch_array($xxx)) $used .= $ui[0];
			}
		}
	}
	return $used;
}

// Проверка использования элемента по ID и имени таблицы
function RowDelDeny($row,$db) {
	global $db_prefix, $db_cfg, $MESSAGE, $user;

	$delright = false;
	if ($db_cfg[$db."|DELRIGHT"].""!=="") {
		$delright = true;
		if ($row[$db_cfg[$db."|DELRIGHT"]]==$user["ID"]) $delright = false;
	}
	if ($db_cfg[$db."|HOLDDEL"].""!=="") {
		$holds = explode("|",$db_cfg[$db."|HOLDDEL"]);
		$holds_count = count($holds);
		for ($j=0;$j < $holds_count;$j++) {
			if ($row[$holds[$j]]*1!==0) $delright = true;
		}
	}
	return $delright;
}

// Вывод ссылки на удаление элемента таблицы
function DelField($row,$db,$rw,$rowspan = 1,$notdeny = true) {
	global $db_prefix, $db_cfg, $loc, $pageurl, $user;

	$showdel = "";
	if ($rw==true) {

		$listfield = $db_cfg[$db."|LIST_FIELD"];
		$listprefix = $db_cfg[$db."|LIST_PREFIX"];
		$listfield = explode("|",$listfield);
		$val = FVal($row,$db,$listfield[0]);
		$listfield_count = count($listfield);
		for ($j=1;$j < $listfield_count;$j++) {
			$val .= $listprefix.FVal($row,$db,$listfield[$j]);
		}
		
		$usedin2 = "";
		$usedin = RowUsedIn($row,$db);
		if ($user['ID']==1) $usedin2 = RowUsedInwher($row,$db);
		$deldeny = RowDelDeny($row,$db);
		if (!$notdeny) $deldeny = true;

		if ($usedin) {
			$showdel = "<a href='javascript:void(0);' title='".$loc["dbf3"]."'><img src='uses/nodel.png' alt='".$loc["dbf3"]."'>".$usedin2."</a>";
		}
		if ($deldeny) {
			$showdel = "<a href='javascript:void(0);' title='".$loc["dbf8"]."'><img src='uses/nodel.png' alt='".$loc["dbf8"]."'></a>";
		}
		if ((!$usedin) && (!$deldeny)) {
			$delwith = "";
			$val = str_replace("&quot", "&apos;&apos", $val );			
			if ("".$db_cfg[$db."|DELWITH"]!=="") $delwith = $loc["dbf10"];
			$showdel = "<a href='javascript:void(0);' title='".$loc["dbf4"]."' onclick='if (confirm(\"".$loc["dbf5"]." - ".$val." ".$delwith." ?\")) parent.location=\"$pageurl&db=".$db."&delete=".$row["ID"]."\";'><img src='uses/del.png' alt='".$loc["dbf4"]."'></a>";
		}
	}
	$rstxt = "";
	if ($rowspan>1) $rstxt = " rowspan='".$rowspan."'";
	if (db_adcheck($db)) echo "<td class='Field'".$rstxt.">".$showdel."</td>";
}










/////////////////////////////////////////////////////////
//
// Функции полей БД
//
/////////////////////////////////////////////////////////


function OpenLinkID ($row,$res,$db,$option,$n,$listfield,$disabled) {
	global $db_prefix;

	$ml = $n*10;
	
	echo "<OPTION VALUE='".$res["ID"]."' ";
	if ($res["ID"]==$row["LID"]) echo "SELECTED";
	if (in_array($res["ID"],$disabled)) echo "DISABLED style='color: #aaa;'";
	echo ">";
	$val = FVal($res,$db,$listfield[0]);
	$listfield_count = count($listfield);
	for ($j=1;$j < $listfield_count;$j++) {
		$val .= " - ".FVal($res,$db,$listfield[$j]);
	}
	for ($j=0;$j < $n;$j++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	echo $val;

	$items = dbquery("SELECT * FROM ".$db_prefix.$db." where (PID='".$res["ID"]."') ".$option." order by ID");
	while($res = mysql_fetch_array($items)) {
		if ($res["LID"]=="0") OpenLinkID ($row,$res,$db,$option,$n+1,$listfield,$disabled);
	}
}


function OpenDisID ($item,$db) {
	global $db_prefix, $disabled;

	$disabled[] = $item["ID"];

	$result = dbquery("SELECT * FROM ".$db_prefix.$db." where (ID='".$item["PID"]."')");
	while($res = mysql_fetch_array($result)) OpenDisID ($res,$db);

	$result = dbquery("SELECT * FROM ".$db_prefix.$db." where (LID='".$item["ID"]."')");
	while($res = mysql_fetch_array($result)) OpenDisID ($res,$db);
}


// Вывод поля таблицы
function Field($row,$db,$field,$rw,$option,$html,$td_options) {
	global $cur_formid, $db_prefix, $db_cfg, $disabled, $pageurl, $today_0, $today_1, $today_7, $loc, $print_mode, $user, $files_path;

	if ($print_mode == 'on') $rw = false;

	$Name = $db.'_'.$field.'_edit';
	$URL = "db_edit.php?db=$db&field=$field&id=".$row['ID']."&value=";
	$LIDLOAD = "db_lid.php?db=$db&id=".$row['ID']."&url=".codeurl($pageurl)."&value=";
	$SLLOAD = "db_list.php?db=$db&field=$field&id=".$row['ID']."&url=".codeurl($pageurl)."&value=";
	$SLLOAD2 = "db_list2.php?db=$db&field=$field&id=".$row['ID']."&url=".codeurl($pageurl)."&value=";
	$MLURL = $pageurl."&edit_multilist=".$db."|".$row["ID"]."|".$field."|";
	$FILEURL = $pageurl."&edit_file=".$db."|".$row["ID"]."|".$field."|";
	$SURL = $pageurl."&edit_state=".$db."|".$row["ID"]."|".$field."|";

	$type = $db_cfg[$db.'/'.$field];
	if ($field=="ID") $type = "pinteger";
	if ($field=="PID") $type = "pinteger";
	if ($field=="LID") $type = "linklist";

	$ta_class = "ntabg";
	if ($type=="textarea") $ta_class = "tabg";
	if ($type=="html") $ta_class = "tabg";

	if (!db_check_activ($row,$db,$field)) $rw = false;

	echo "<td ".$td_options;
	$edit_right = db_check($db,$field);
	
	if (($edit_right) && ($rw==true) && ($field!=="ID")) {

		echo " class='rwField ".$ta_class."'>";

		if ($html!=="") echo "<table><tr class='cl_3 cls'><td width='1%'>".str_replace("<!--|-->","</td><td width='1%'>",$html)."</td><td>";

		if ($type=="integer") {
			echo "<input type='text' ".$option." name='".$Name."_".$row['ID']."' value='".$row[$field]."' onChange='vote(this , \"$URL\"+this.value);' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"IPMFilter(this.form, '".$Name."_".$row['ID']."', event)\">";
		}
		if ($type=="pinteger") {
			echo "<input type='text' ".$option." name='".$Name."_".$row['ID']."' value='".$row[$field]."' onChange='vote(this , \"$URL\"+this.value);' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"IPFilter(this.form, '".$Name."_".$row['ID']."', event)\">";
		}
		if ($type=="real") {
			echo "<input type='text' ".$option." name='".$Name."_".$row['ID']."' value='".$row[$field]."' onChange='vote(this , \"$URL\"+this.value);' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPMFilter(this.form, '".$Name."_".$row['ID']."', event)\">";
		}
		if ($type=="preal") {
			echo "<input type='text' ".$option." name='".$Name."_".$row['ID']."' value='".$row[$field]."' onChange='vote(this , \"$URL\"+this.value);' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, '".$Name."_".$row['ID']."', event)\">";
		}
		if ($type=="money") {
			echo "<input type='text' ".$option." name='".$Name."_".$row['ID']."' value='".$row[$field]."' onChange='vote(this , \"$URL\"+this.value);' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPMFilter(this.form, '".$Name."_".$row['ID']."', event)\">";
		}
		if ($type=="pmoney") {
			echo "<input type='text' ".$option." name='".$Name."_".$row['ID']."' value='".$row[$field]."' onChange='vote(this , \"$URL\"+this.value);' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, '".$Name."_".$row['ID']."', event)\">";
		}
		if ($type=="boolean") {
			echo "<input type='checkbox' style='margin-top: 3px;' ".$option." name='".$Name."_".$row['ID']."' ";
			if ($row[$field]==1) echo "CHECKED ";
			echo " onClick='if (confirm(\"".$loc["dbf1"]."\")) { vote(this , \"$URL\"+this.checked);} else {this.checked=this.checked==false;}'>";
		}
		
		if ($type=="tinytext") {
			echo "<input type='text' ".$option." name='".$Name."_".$row['ID']."' value='".$row[$field]."' onChange='vote(this , \"$URL\"+TXT(this.value));'>";		
		}
		
		if ($type=="text") {
			echo "<input type='text' ".$option." name='".$Name."_".$row['ID']."' value='".$row[$field]."' onChange='vote(this , \"$URL\"+TXT(this.value));'>";		
		}
		
		if ($type=="longtext") {
			$hght = 15*count(explode("\n",$row[$field]))+3;
			echo "<textarea ".$option." style='height : ".$hght.";' name='".$Name."_".$row['ID']."' onChange='vote(this , \"$URL\"+TXT(this.value));' onkeyup=\"this.style.height=(15*(this.value.split('\\n').length)+3);\">".$row[$field]."</textarea>";
		}

		if ($type=="textarea") {
			$hght = 15*count(explode("\n",$row[$field]))+3;
			echo "<textarea ".$option." style='height : ".$hght.";' name='".$Name."_".$row['ID']."' onChange='vote(this , \"$URL\"+TXT(this.value));' onkeyup=\"this.style.height=(15*(this.value.split('\\n').length)+3);\">".$row[$field]."</textarea>";
		}
		if ($type=="html") {
			$hght = 13*count(explode("\n",$row[$field]))+4;
			echo "<textarea class='tahtml' ".$option." style='height : ".$hght.";' name='".$Name."_".$row['ID']."' onChange='vote(this , \"$URL\"+TXT(this.value));' onkeyup=\"this.style.height=(13*(this.value.split('\\n').length)+4);\">".$row[$field]."</textarea>";
		}
		if ($type=="date") {
			$val = FVal($row,$db,$field);
			$sval = $val;
			if ($val=="") $sval = "---";
			if ($val=="") $val = $today_0;
			$val = explode(".",$val);
			echo "<input id='".$Name."_".$row['ID']."_Input' type='hidden' name='".$Name."_".$row['ID']."_Input' value='".$row[$field]."'>";
			echo "<span id='".$Name."_".$row['ID']."_Span' style='cursor: hand;' onClick='DI_Create(".$val[0].",".$val[1].",".$val[2].",".$val[0].",".$val[1].",".$val[2].",\"".$Name."_".$row['ID']."\",\"$URL\");'>".$sval."</span>";
		}
		if ($type=="dateplan") {
			$URL1 = "db_edit.php?db=$db&field=$field&id=".$row['ID']."&setchbx=0&value=";
			$URL2 = "db_edit.php?db=$db&field=$field&id=".$row['ID']."&setchbx=1&value=";
			$Name1 = $Name."_0_".$row['ID'];
			$Name2 = $Name."_1_".$row['ID'];
			$NamePP = $Name."_pp_".$row['ID'];

			$values = $row[$field];
			if ($values=="") $values ="0|";
			$values = explode("|",$values);
			$numval = count($values)-1;
			$lastval = $values[$numval];
			if ($lastval=="") $lastval = "##";
			$lastval = explode("#",$lastval);			

			$val = $lastval[2];
			$sval = $val;
			if ($val=="") $sval = "---";
			if ($val=="") $val = $today_0;
			$dvv = $val;
			$val = explode(".",$val);

			$bgcl = "#fff";
			$bcl = "#fff";
			if ($lastval[2]=="") {
				$bgcl = "#fff";
				$bcl = "#fff";
			} else {
				
				if (DateToInt($today_7)>DateToInt($lastval[2])) $bgcl = "#fee";
				if (DateToInt($today_7)>DateToInt($lastval[2])) $bcl = "#f44";
				if (DateToInt($today_1)>DateToInt($lastval[2])) $bgcl = "#faa";
				if (DateToInt($today_1)>DateToInt($lastval[2])) $bcl = "#f44";
				if (DateToInt($today_0)>DateToInt($lastval[2])) $bgcl = "#f44";
				if (DateToInt($today_0)>DateToInt($lastval[2])) $bcl = "#f44";
			}
			if ($values[0]=="1") {
				$bgcl = "#afa";
				$bcl = "#afa";
			}
			
			echo "<center style='background: ".$bgcl."; border: 1px solid ".$bcl."; padding: 1px; margin: -2px;'>";
			echo "<input id='".$Name1."_Input' type='hidden' name='".$Name1."_Input' value='".$dvv."'>";
			echo "<span id='".$Name1."_Span' style='cursor: hand;' onClick='DI_Create(".$val[0].",".$val[1].",".$val[2].",".$val[0].",".$val[1].",".$val[2].",\"".$Name1."\",\"$URL1\");'>".$sval."</span> ";
			echo "<br>";
			echo "<input type='checkbox' style='margin: 0px; padding: 0px; margin-top: 3px; width: 20px;' ".$option." name='".$Name2."' ";
			if ($values[0]=="1") echo "CHECKED ";
			echo " onClick='if (confirm(\"".$loc["dbf1"]."\")) { vote(this , \"$URL2\"+this.checked);} else {this.checked=this.checked==false;}'>";
			echo "<span class='dtpl' onClick='ShowHide(\"$NamePP\", this, $cur_formid)'>[".$numval."]".DatePlanTable($NamePP,$values)."</span>";
			echo "</center>\n";
		}
		if ($type=="list") {
			$out_val = FVal($row,$db,$field);
			$out_valx = str_replace(" ","",$out_val);
			if ($out_valx=="") $out_val = "&nbsp;";
			$clear_list_img = "<img id='ds_".$Name."_".$row['ID']."' style='cursor: hand; padding: 0px; margin: 2px; float: right; display: none;' src='uses/delml.png' title='".$loc["dbf22"]."' onclick='document.location = \"".$pageurl."&edit_list=".$db."|".$row["ID"]."|".$field."|0\";'>";
			if ($row[$field]*1==0) $clear_list_img = "";
			$onoverout = "onMouseOver='ShowHide(\"ds_".$Name."_".$row['ID']."\", this, $cur_formid);' onMouseOut='ShowHide(\"ds_".$Name."_".$row['ID']."\", this, $cur_formid);'";
			if ($row[$field]*1==0) $onoverout = "";

			$linkpic = "<a href=\"javascript:void(0);\" onClick=\"ShowHide('esl_".$Name."_".$row["ID"]."', this, $cur_formid); document.getElementById('inp_".$Name."_".$row["ID"]."').focus();\"><img src='uses/link.png'></a>";
			$linkpic .= "<span class='ltpopup'><div class='ltpopup' id=\"esl_".$Name."_".$row["ID"]."\" style='min-width: 220px;'><img class='limg' src='uses/line.png' onClick='Hide(\"esl_".$Name."_".$row["ID"]."\");'>";
			$linkpic .= "<input id='inp_".$Name."_".$row['ID']."' type='text' class='lid_input' name='".$Name."_".$row['ID']."' value='' onKeyUp='loadurl(\"slres_".$Name."_".$row['ID']."\", \"$SLLOAD\"+TXT(this.value));' onblur=\"setTimeout('Hide(`esl_".$Name."_".$row["ID"]."`);', 800);\">";
			$linkpic .= "<div class='lid_res hidden' id='slres_".$Name."_".$row['ID']."'></div>";
			$linkpic .= "</div></span>";

			if ($html=="") echo "<table><tr class='cl_4'><td width='7px;'>$linkpic</td><td ".$onoverout.">";
			if ($html!=="") echo "<td width='7px;'>$linkpic</td><td ".$onoverout.">";
			echo $clear_list_img.$out_val;
			if ($html=="") echo "</td></tr></table>\n";
		}
		
		if ($type=="list2") {
			$out_val = FVal($row,$db,$field);
			$out_valx = str_replace(" ","",$out_val);
			if ($out_valx=="") $out_val = "&nbsp;";
			$clear_list_img = "<img id='ds_".$Name."_".$row['ID']."' style='cursor: hand; padding: 0px; margin: 2px; float: right; display: none;' src='uses/delml.png' title='".$loc["dbf22"]."' onclick='document.location = \"".$pageurl."&edit_list=".$db."|".$row["ID"]."|".$field."|0\";'>";
			if ($row[$field]*1==0) $clear_list_img = "";
			$onoverout = "onMouseOver='ShowHide(\"ds_".$Name."_".$row['ID']."\", this, $cur_formid);' onMouseOut='ShowHide(\"ds_".$Name."_".$row['ID']."\", this, $cur_formid);'";
			if ($row[$field]*1==0) $onoverout = "";

			$linkpic = "<a href=\"javascript:void(0);\" onClick=\"ShowHide('esl_".$Name."_".$row["ID"]."', this, $cur_formid); document.getElementById('inp_".$Name."_".$row["ID"]."').focus();\"><img src='uses/link.png'></a>";
			$linkpic .= "<span class='ltpopup'><div class='ltpopup' id=\"esl_".$Name."_".$row["ID"]."\" style='min-width: 220px;'><img class='limg' src='uses/line.png' onClick='Hide(\"esl_".$Name."_".$row["ID"]."\");'>";
			$linkpic .= "<input id='inp_".$Name."_".$row['ID']."' type='text' class='lid_input' name='".$Name."_".$row['ID']."' value='' onKeyUp='loadurl(\"slres_".$Name."_".$row['ID']."\", \"$SLLOAD2\"+TXT(this.value));' onblur=\"setTimeout('Hide(`esl_".$Name."_".$row["ID"]."`);', 800);\">";
			$linkpic .= "<div class='lid_res' id='slres_".$Name."_".$row['ID']."'></div>";
			$linkpic .= "</div></span>";

			if ($html=="") echo "<table><tr class='cl_5'><td width='7px;'>$linkpic</td><td ".$onoverout.">";
			if ($html!=="") echo "<td width='7px;'>$linkpic</td><td ".$onoverout.">";
			echo $clear_list_img.$out_val;
			if ($html=="") echo "</td></tr></table>\n";
		}
				
		if ($type=="droplist") {

			$listtable = $db_cfg[$db."/".$field."|LIST"];
			$listid = "ID";
			$listfield = $db_cfg[$listtable."|LIST_FIELD"];
			$listprefix = $db_cfg[$listtable."|LIST_PREFIX"];
			$listfield = explode("|",$listfield);

			$add_where = "";
			if ($db_cfg[$db."/".$field."|LIST_EQUAL"].""!=="") {
				$xx = explode("|",$db_cfg[$db."/".$field."|LIST_EQUAL"]);
				if (count($xx)==2) {
					$thisobj = dbquery("SELECT * FROM ".$db_prefix.$db." where (ID='".$id."')");
					if ($thisobj = mysql_fetch_array($thisobj)) {
						$add_where = $xx[0]."='".$thisobj[$xx[1]]."'";
					}
				}
			}

			$list_where = "".$db_cfg[$db."/".$field."|LIST_WHERE"];
			if ($list_where!=="") {
				$list_where = "where (".$list_where.")";
				if ($add_where!=="") $list_where = $list_where." and (".$add_where.")";
			} else {
				if ($add_where!=="") $list_where = "where (".$add_where.")";
			}

			$list_order = "".$db_cfg[$db."/".$field."|LIST_ORDER"];
			if ($list_order=="") $list_order = "binary(".$listfield[0].")";

			$vals = dbquery("SELECT * FROM ".$db_prefix.$listtable." ".$list_where." order by ".$list_order);
			echo "<SELECT NAME='".$Name."_".$row['ID']."' onChange='vote(this , \"$URL\"+this.value);' ".$option.">";
				echo "<OPTION VALUE='0' ";
				if ($row[$field]=="0") echo "SELECTED";
				echo ">---";
				while($res = mysql_fetch_array($vals)) {
					echo "<OPTION VALUE='".$res[$listid]."' ";
					if ($res[$listid]==$row[$field]) echo "SELECTED";
					echo ">";
					$val = FVal($res,$listtable,$listfield[0]);
					$listfield_count = count($listfield);
					for ($j=1;$j < $listfield_count;$j++) {
						$val .= $listprefix.FVal($res,$listtable,$listfield[$j]);
					}
					echo $val;
				}
			echo "</SELECT>";
		}
		if ($type=="multilist") {

		   // Вывод уже добавленного
			$listtable = $db_cfg[$db."/".$field."|LIST"];
			$listid = "ID";
			$listfield = $db_cfg[$listtable."|LIST_FIELD"];
			$listprefix = $db_cfg[$listtable."|LIST_PREFIX"];
			$listfield = explode("|",$listfield);

			$add_where = "";
			if ($db_cfg[$db."/".$field."|LIST_EQUAL"].""!=="") {
				$xx = explode("|",$db_cfg[$db."/".$field."|LIST_EQUAL"]);
				if (count($xx)==2) {
					$thisobj = dbquery("SELECT * FROM ".$db_prefix.$db." where (ID='".$id."')");
					if ($thisobj = mysql_fetch_array($thisobj)) {
						$add_where = $xx[0]."='".$thisobj[$xx[1]]."'";
					}
				}
			}

			$list_where = "".$db_cfg[$db."/".$field."|LIST_WHERE"];
			if ($list_where!=="") {
				$list_where = "where (".$list_where.")";
				if ($add_where!=="") $list_where = $list_where." and (".$add_where.")";
			} else {
				if ($add_where!=="") $list_where = "where (".$add_where.")";
			}

			$list_order = "".$db_cfg[$db."/".$field."|LIST_ORDER"];
			if ($list_order=="") $list_order = "binary(".$listfield[0].")";

			$res = "";
			$sel[] = "";
			if ($row[$field]!=="") {
				$sel = explode("|",$row[$field]);
				$multiwhere = "where ((".$listid."='".implode("') or (".$listid."='",$sel)."'))";
				$xxxr = dbquery("SELECT * FROM ".$db_prefix.$listtable." ".$multiwhere." order by ".$list_order);
				while($xxx = mysql_fetch_array($xxxr)) {
					$val = "<div class='mldiv'><img src='uses/delml.png' style='cursor: hand; padding: 0px; margin: 2px 5px 2px 2px;' title='".$loc["dbf4"]."' onclick='document.location = \"".$MLURL.$xxx["ID"]."&del_multilist\"'> - ".FVal($xxx,$listtable,$listfield[0]);
					$listfield_count = count($listfield);
					for ($k=1;$k < $listfield_count;$k++) {
						$val .= $listprefix.FVal($xxx,$listtable,$listfield[$k]);
					}
					$val .= "</div>\n";
					$res .= $val;
				}
			}

			echo $res."<br><b style='color: #1ce23d; margin-right: 10px;'>&#8659;</b>".$loc["dbf2"]."<br>";

		   // Предложение ещё добавить
			$vals = dbquery("SELECT * FROM ".$db_prefix.$listtable." ".$list_where." order by ".$list_order);
			echo "<SELECT NAME='".$Name."_".$row['ID']."' ".$option." onChange='document.location = \"$MLURL\"+this.value+\"&add_multilist\";'>";
				echo "<OPTION VALUE='0' SELECTED>---";
				while($res = mysql_fetch_array($vals)) {
					if (!in_array($res[$listid],$sel)) {
						echo "<OPTION VALUE='".$res[$listid]."'>";
						$val = FVal($res,$listtable,$listfield[0]);
						$listfield_count = count($listfield);
						for ($j=1;$j < $listfield_count;$j++) {
							$val .= $listprefix.FVal($res,$listtable,$listfield[$j]);
						}
						echo $val;
					}
				}
			echo "</SELECT>";
		}
		if ($type=="alist") {
			$list = $db_cfg[$db."/".$field."|LIST"];
			$vals = explode("|",$list);
			echo "<SELECT NAME='".$Name."_".$row['ID']."' onChange='vote(this , \"$URL\"+this.value);' ".$option.">";
				echo "<OPTION VALUE='0' ";
				if ($row[$field]=="0") echo "SELECTED";
				echo ">---";
				$vals_count = count($vals);
				 for ($j=0;$j < $vals_count;$j++) {
					echo "<OPTION VALUE='".($j+1)."' ";
					if ($j+1==$row[$field]) echo "SELECTED";
					echo ">".$vals[$j];
				}
			echo "</SELECT>";
		}
		if ($type=="state") {
			$list = $db_cfg[$db."/".$field."|LIST"];
			$vals = explode("|",$list);
			$onchange = "if (confirm(\"".$loc["dbf1"]."\")) { document.location = \"$SURL\"+this.value;} else {this.value=".$row[$field].";}";

			echo "<SELECT NAME='".$Name."_".$row['ID']."' onChange='".$onchange."' ".$option.">";
				echo "<OPTION VALUE='0' ";
				if ($row[$field]=="0") echo "SELECTED";
				echo ">---";
				$vals_count = count($vals);
				 for ($j=0;$j < $vals_count;$j++) {
					echo "<OPTION VALUE='".($j+1)."' ";
					if ($j+1==$row[$field]) echo "SELECTED";
					echo ">".$vals[$j];
				}
			echo "</SELECT>";
		}
		if ($type=="linklist") {
			$out_val = FVal($row,$db,$field);
			$out_valx = str_replace(" ","",$out_val);
			if ($out_valx=="") $out_val = "&nbsp;";

			$linkpic = "<a href=\"javascript:void(0);\" onClick=\"ShowHide('elid_".$db."_".$row["ID"]."', this, $cur_formid);\"><img src='uses/link.png'></a>";
			$linkpic .= "<span class='ltpopup'><div class='ltpopup' id=\"elid_".$db."_".$row["ID"]."\" style='min-width: 200px;'><img class='limg' src='uses/line.png' onClick=\"Hide('elid_".$db."_".$row["ID"]."');\">";
			$linkpic .= "<input type='text' class='lid_input' name='".$Name."_".$row['ID']."' value='' onKeyUp='loadurl(\"lidres_".$Name."_".$row['ID']."\", \"$LIDLOAD\"+TXT(this.value))'>";
			$linkpic .= "<div class='lid_res' id='lidres_".$Name."_".$row['ID']."'></div>";
			$linkpic .= "</span></div>";

			if ($html=="") echo "<table><tr class='cl_6'><td width='7px;'>$linkpic</td><td>";
			if ($html!=="") echo "<td width='7px;'>$linkpic</td><td>";
			echo $out_val;
			if ($html=="") echo "</td></tr></table>";
		}
		if ($type=="file") {
			$res = "<img src='uses/addf.png' style='cursor: hand; padding: 0px; margin: 2px 10px 2px 2px;' title='".$loc["dbf12"]."' onClick='document.location = \"".$FILEURL."&add_file&event\";'>";
			if ($row[$field].""!=="") {
				$res = "<table><tr class='cl_7'><td><img src='uses/delml.png' style='cursor: hand; padding: 0px; margin: 2px 10px 2px 2px;' title='".$loc["dbf13"]."' ";
				$res .= " onClick='if (confirm(\"".$loc["dbf14"]."\")) { document.location = \"".$FILEURL."&del_file\";}'>";
				$res .= "</td><td><a href='get_file.php?filename=".$db."@".$field."/".$row[$field]."' target='_blank' title='".$loc["dbf11"]."'><img src='".FileIcon($row[$field])."'></a></td></tr></table>\n";	
			}
			echo $res;
		}
		if ($html!=="") echo "</td></tr></table>";
	} else {
		echo " class='Field'>";
		if ($html!=='') echo "<table><tr class='cl_8'><td width='7px;'>".str_replace('<!--|-->',"</td><td style='width: 18px;'>",$html).'</td><td>';
		/*$out_val = FVal($row,$db,$field);
		$out_valx = str_replace(' ','',$out_val);
		if ($out_valx=='') $out_val = '&nbsp;';*/
		echo FVal($row,$db,$field);
		if ($html!=='') echo '</td></tr></table>';
	}
	echo '</td>';
}

// Вывод значения поля
function FVal($row,$db,$field) {
	global $db_prefix, $db_cfg, $today_0, $today_1, $today_7, $files_path, $loc;
	
	$type = $db_cfg[$db."/".$field];
	if (($field=="NAME") && ($type=="")) $type = "tinytext";
	if ($field=="ID") $type = "pinteger";
	if ($field=="PID") $type = "pinteger";
	if ($field=="LID") $type = "linklist";
	$res = $row[$field];

	if ($type=="money") {
		$res = number_format($row[$field], 2, ',', ' ');
		if ($row[$field]*1 == ceil($row[$field]*1)) $res = number_format($row[$field], 0, ',', ' ');
	}
	if ($type=="pmoney") {
		$res = number_format($row[$field], 2, ',', ' ');
		if ($row[$field]*1 == ceil($row[$field]*1)) $res = number_format($row[$field], 0, ',', ' ');
	}
	if ($type=="date") {
		$res = IntToDate($row[$field]);
	}
	if ($type=="time") {
		$res = Date("d.m.Y H:i",$row[$field]);
	}
	if ($type=="time2") {
		$res = Date("d.m.Y H:i:s",$row[$field]);
	}
	if ($type=="dateplan") {
		$NamePP = $db."_".$field."_PP_".$row['ID'];
		$values = $row[$field];
		if ($values=="") $values ="0|";
		$values = explode("|",$values);
		$numval = count($values)-1;
		$lastval = $values[$numval];
		if ($lastval=="") $lastval = "##";
		$lastval = explode("#",$lastval);

		$bgcl = "#fff";
		$bcl = "#fff";
		if ($lastval[2]=="") {
			$bgcl = "#fff";
			$bcl = "#fff";
		} else {
			if (DateToInt($today_7)>DateToInt($lastval[2])) $bgcl = "#fee";
			if (DateToInt($today_7)>DateToInt($lastval[2])) $bcl = "#f44";
			if (DateToInt($today_1)>DateToInt($lastval[2])) $bgcl = "#faa";
			if (DateToInt($today_1)>DateToInt($lastval[2])) $bcl = "#f44";
			if (DateToInt($today_0)>DateToInt($lastval[2])) $bgcl = "#f44";
			if (DateToInt($today_0)>DateToInt($lastval[2])) $bcl = "#f44";
		}
		if ($values[0]=="1") {
			$bgcl = "#afa";
			$bcl = "#afa";
		}		

		$val = $lastval[2];
		$res = "<center style='background: ".$bgcl."; border: 1px solid ".$bcl."; padding: 1px; margin: -2px;'><span>".$val."</span><br><span class='dtpl' onclick='ShowHide(\"$NamePP\", this, $cur_formid)'>[".$numval."]".DatePlanTable($NamePP,$values)."</span></center>";
	}
	if ($type=="textarea") $res=str_replace("\n","<br>",$row[$field]);
	if ($type=="longtext") $res=str_replace("\n","<br>",$row[$field]);
	if ($type=="html") $res=$row[$field];
	if ($type=="boolean") {
		if ($row[$field]=="1") $res = "<img alt='Да' style='float: none;' src='uses/ok.png'>";
		if ($row[$field]=="0") $res = "";
	}
	if (($type=="list") or ($type=="droplist")) {
		$listtable = $db_cfg[$db."/".$field."|LIST"];
		$listid = "ID";
		$listfield = $db_cfg[$listtable."|LIST_FIELD"];
		$listprefix = $db_cfg[$listtable."|LIST_PREFIX"];
		$listfield = explode("|",$listfield);
		$val = "";
		if ($row[$field]!=0) {
			$res = dbquery("SELECT * FROM ".$db_prefix.$listtable." where  (".$listid."='".$row[$field]."')");
			$res = mysql_fetch_array($res);
			$val = FVal($res,$listtable,$listfield[0]);
			$listfield_count = count($listfield);
			for ($j=1;$j < $listfield_count;$j++) {
				$val .= $listprefix.FVal($res,$listtable,$listfield[$j]);
			}
		}
		$res = $val;
	}
	if ($type=="list2") {
		$listtable = $db_cfg[$db."/".$field."|LIST"];
		$listid = "ID";
		$listfield = $db_cfg[$listtable."|LIST_FIELD"];
		$listfield2 = $db_cfg[$listtable."|LIST_FIELD2"];
		$listprefix = $db_cfg[$listtable."|LIST_PREFIX"];
		$listfield = explode("|",$listfield);
		$val = "";
		if ($row[$field]!=0) {
			$res = dbquery("SELECT * FROM ".$db_prefix.$listtable." where  (".$listid."='".$row[$field]."')");
			$res = mysql_fetch_array($res);
			$val = FVal($res,$listtable,$listfield[0]);
			$listfield_count = count($listfield);
			for ($j=1;$j < $listfield_count;$j++) {
				$val .= $listprefix.FVal($res,$listtable,$listfield[$j]);
			}
		}
		$res = $val;
	}
	if ($type=="multilist") {
		$listtable = $db_cfg[$db."/".$field."|LIST"];
		$listid = "ID";
		$listfield = $db_cfg[$listtable."|LIST_FIELD"];
		$listprefix = $db_cfg[$listtable."|LIST_PREFIX"];
		$listfield = explode("|",$listfield);
		$res = "";
		$sel = explode("|",$row[$field]);
		$sel_count = count($sel);
		for ($j=0;$j < $sel_count;$j++) {
			$val = "";
			if ($sel[$j]!=="") {
				$xxx = dbquery("SELECT * FROM ".$db_prefix.$listtable." where  (".$listid."='".$sel[$j]."')");
				$xxx = mysql_fetch_array($xxx);
				$val = FVal($xxx,$listtable,$listfield[0]);
				$listfield_count = count($listfield);
				for ($k=1;$k < $listfield_count;$k++) {
					$val .= $listprefix.FVal($xxx,$listtable,$listfield[$k]);
				}
			}
			$res = $res.$val;
			if ($j<count($sel)-1) $res .= "<br>";
		}
	}
	if ($type=="alist") {
		$list = $db_cfg[$db."/".$field."|LIST"];
		$vals = explode("|",$list);
		$val = "";
		if ($row[$field]!=0) {
			$val = $vals[$row[$field]-1];
		}
		$res = $val;
	}
	if ($type=="state") {
		$list = $db_cfg[$db."/".$field."|LIST"];
		$vals = explode("|",$list);
		$val = "";
		if ($row[$field]!=0) {
			$val = $vals[$row[$field]-1];
		}
		$res = $val;
	}
	if ($type=="linklist") {
		$listfield = $db_cfg[$db."|LID_FIELD"];
		$listfield = explode("|",$listfield);
		$val = "";
		if ($row[$field]!=1) {
			$res = dbquery("SELECT * FROM ".$db_prefix.$db." where  (ID='".$row[$field]."')");
			$res = mysql_fetch_array($res);
			$val = FVal($res,$db,$listfield[0]);
			$listfield_count = count($listfield);
			for ($j=1;$j < $listfield_count;$j++) {
				$val .= " - ".FVal($res,$db,$listfield[$j]);
			}
		}
		$res = $val;
	}
	if ($type=="file") {
		$res = "---";
		if ($row[$field].""!=="") {
			$res = "<a href='get_file.php?filename=".$db."@".$field."/".$row[$field]."' target='_blank' title='".$loc["dbf11"]."'><img src='".FileIcon($row[$field])."'></a>";	
		}
	}
	Return $res;
}

function FInput($type,$Name,$val = "",$option = "",$lookinpost = true) {
	global $today_0;

		$ret = "";

		if ((isset($_POST[$Name])) && ($lookinpost)) $val = $_POST[$Name];

		if ($type=="integer") {
			$ret = "<input type='text' ".$option." name='".$Name."' value='".$val."' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"IPMFilter(this.form, '".$Name."', event)\">";
		}
		if ($type=="pinteger") {
			$ret = "<input type='text' ".$option." name='".$Name."' value='".$val."' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"IPFilter(this.form, '".$Name."', event)\">";
		}
		if ($type=="real") {
			$ret = "<input type='text' ".$option." name='".$Name."' value='".$val."' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPMFilter(this.form, '".$Name."', event)\">";
		}
		if ($type=="preal") {
			$ret = "<input type='text' ".$option." name='".$Name."' value='".$val."' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, '".$Name."', event)\">";
		}
		if ($type=="boolean") {
			$ret = "<input type='checkbox' style='margin-top: 3px;' ".$option." name='".$Name."' ";
			if ($val==1) $ret = $ret."CHECKED ";
			$ret = $ret." >";
		}
		if ($type=="tinytext") {
			$ret = "<input type='text' ".$option." name='".$Name."' value='".$val."'>";
		}
		if ($type=="longtext") {
			$hght = 15*count(explode("\n",$val))+3;
			$ret = "<textarea ".$option." style='height : ".$hght.";' name='".$Name."' onkeyup=\"this.style.height=(15*(this.value.split('\\n').length)+3);\">".$val."</textarea>";
		}
		if ($type=="textarea") {
			$hght = 15*count(explode("\n",$val))+3;
			$ret = "<textarea ".$option." style='height : ".$hght.";' name='".$Name."' onkeyup=\"this.style.height=(15*(this.value.split('\\n').length)+3);\">".$val."</textarea>";
		}
		if ($type=="html") {
			$hght = 13*count(explode("\n",$val))+4;
			$ret = "<textarea class='tahtml' ".$option." style='height : ".$hght.";' name='".$Name."' onkeyup=\"this.style.height=(13*(this.value.split('\\n').length)+4);\">".$val."</textarea>";
		}
		if ($type=="date") {
			$sval = $val;
			$ival = $val;
			if ($val=="") $sval = "---";
			if ($val=="") $val = $today_0;
			$val = explode(".",$val);
			$ret = "<input id='".$Name."_Input' type='hidden' name='".$Name."' value='".$ival."'>";
			$ret .= "<span id='".$Name."_Span' style='cursor: hand;' onClick='DI_Create(".$val[0].",".$val[1].",".$val[2].",".$val[0].",".$val[1].",".$val[2].",\"".$Name."\",\"db_none.php?val=\");'>".$sval."</span>";
		}

		return $ret;
}

function Input($type,$Name,$val = "",$option = "",$lookinpost = true) {
	echo FInput($type,$Name,$val,$option,$lookinpost);
}

function IDInput($listtable,$type,$Name,$idval = 0,$option = "",$lookinpost = true) {
	global $db_cfg, $db_prefix, $pageurl;

		if ((isset($_POST[$Name])) && ($lookinpost)) $idval = $_POST[$Name];
		$idval = $idval*1;
		
		if ($type=="list") {
			$listid = "ID";
			$listfield = $db_cfg[$listtable."|LIST_FIELD"];
			$listprefix = $db_cfg[$listtable."|LIST_PREFIX"];
			$listfield = explode("|",$listfield);
			$vals = dbquery("SELECT * FROM ".$db_prefix.$listtable." order by binary(".$listfield[0].")");
			echo "<SELECT NAME='".$Name."' ".$option.">";
				echo "<OPTION VALUE='0' ";
				if ($idval==0) echo "SELECTED";
				echo ">---";
				while($res = mysql_fetch_array($vals)) {
					echo "<OPTION VALUE='".$res[$listid]."' ";
					if ($res[$listid]==$idval) echo "SELECTED";
					echo ">";
					$val = FVal($res,$listtable,$listfield[0]);
					$listfield_count = count($listfield);
					for ($j=1;$j < $listfield_count;$j++) {
						$val .= $listprefix.FVal($res,$listtable,$listfield[$j]);
					}
					echo $val;
				}
			echo "</SELECT>";
		}
}

?>
