<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
//
//	Render_item
//
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
//
//	{{item}}
//	$ID_formsitem	- ID таблицы форм
//	$table_1_add	- Разрешить добавление элементов в БД таблицу 1 в соответствии с правами доступа
//	$table_1_edit	- Разрешить редактирование полей элементов в БД таблице 1 в соответствии с правами доступа
//	$table_2_add	- Разрешить добавление элементов в БД таблицу 2 в соответствии с правами доступа
//	$table_2_edit	- Разрешить редактирование полей элементов в БД таблице 2 в соответствии с правами доступа
//	$sql_where_1	- MySQL Where запрос для выборки из БД таблицы 1
//	$sql_where_2	- MySQL Where запрос для выборки из БД таблицы 2
//	$sql_orderby_1	- MySQL OrderBy запрос для выборки из БД таблицы 1
//	$sql_orderby_2	- MySQL OrderBy запрос для выборки из БД таблицы 2
//	{{/item}}
//
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////


	function OutReplacer($txt) {

		$res = str_replace("&quot;","\"",$txt);
		$res = str_replace("&#39;","'",$res);
		$res = str_replace("&#092;","\\",$res);
		$res = str_replace("&#38;","&",$res);

	    // OutFormsItem

		$res = str_replace("{{item}}","<?php\nRender_item(",$res);
		$res = str_replace("{{/item}}",");\n?>",$res);

		$res = str_replace("{{notprinted}}","<?php\nRender_notprinted(\"",$res);
		$res = str_replace("{{/notprinted}}","\");\n?>",$res);

		return $res;
	}


	$render_db = "";
	$render_row = "";
	$render_row_id = 0;
	$render_options = "";
	$render_edit = false;
	$render_item = "";
	$render_n = 0;
	$render_oct = false;
	$render_where_2 = "";
	$render_month = date("m.Y");
	$render_inc = 0;
	$render_bview = "";	// php script для отображения внизу
	$render_data = Array();		// Для 10 типа - табличный вид бюджетирование


////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////







	function Render_notprinted($txt) {
		global $print_mode;

		if ($print_mode=="off") echo $txt;
	}

	function check($field, $db = "", $row = "") {
		global $render_db, $render_row, $render_edit;

		if ($db=="") $db = $render_db;
		if ($row=="") $row = $render_row;

		$res = true;
		if (!$render_edit) $res = false;
		if (!db_check($db,$field)) $res = false;
		if (!db_check_activ($row,$db,$field)) $res = false;
		return $res;
	}

	function Render_field($field,$rw = false,$html = "",$show_formlink = 0,$show_formlink_bythis = 0) {
		global $db_prefix, $db_cfg, $render_db, $render_row, $render_options, $loc, $render_edit;

		$pic = '';
		if ($show_formlink!==0) $pic = "<a href='index.php?do=show&formid=".$show_formlink."&id=".$render_row["ID"]."' title='".$loc["26"]."'><img src='uses/view.gif' alt='".$loc["26"]."'></a> ";

		if (($show_formlink_bythis!==0) && ($show_formlink==0)) {
			$pic = "<a href='index.php?do=show&formid=".$show_formlink_bythis."&id=".$render_row[$field]."' title='".$loc["26"]."'><img src='uses/view.gif' alt='".$loc["26"]."'></a> ";
		}
		$do_edit = $rw;
		if (!$render_edit) $do_edit = false;
		Field($render_row,$render_db,$field,$do_edit,"",$pic.$html,$render_options);
	}

	function Render_field_where($db,$field,$rw = false,$html = "",$where = "") {
		global $db_prefix, $db_cfg, $render_options, $loc, $render_edit, $render_row_id, $render_row;

		$do_edit = $rw;
		if (!$render_edit) $do_edit = false;
		$result = dbquery("SELECT * FROM ".$db_prefix.$db." where ".$where." limit 0,1");
		$row = mysql_fetch_array($result);
		Field($row,$db,$field,$do_edit,"",$html,$render_options);
	}

	function Render_octfield($field,$rw = false,$html = "",$show_formlink = 0,$lhtml = "") {
		global $db_cfg, $render_db, $render_row;
		if ($db_cfg[$render_db."|TYPE"]=="ltree") {
			if ($render_row["LID"]*1==0) {
				Render_octfield_standart($field,$rw,$html,$show_formlink);
			} else {
				Render_octfield_standart("LID",$rw,$lhtml,$show_formlink);
			}
		} else {
			Render_octfield_standart($field,$rw,$html,$show_formlink);
		}
	}

	function Render_octfield_standart($field,$rw = false,$html = "",$show_formlink = 0) {
		global $db_prefix, $db_cfg, $render_db, $render_row, $render_where_2, $render_options, $loc, $render_edit, $showed_form, $render_item, $render_n, $render_oct;

		// Смотрим childs по дереву $render_db
		$ischilds = false;
		if ($db_cfg[$render_db."|TYPE"]=="tree") {
			$result = dbquery("SELECT ID FROM ".$db_prefix.$render_db." where (PID='".$render_row["ID"]."') limit 0,1");
			if (mysql_fetch_array($result)) $ischilds = true;
		}
		if ($db_cfg[$render_db."|TYPE"]=="ltree") {
			$result = dbquery("SELECT ID FROM ".$db_prefix.$render_db." where (PID='".$render_row["ID"]."') limit 0,1");
			if (mysql_fetch_array($result)) $ischilds = true;
		}

		// Юзать с рендерами для двух таблиц !!!! ссылки на DB_TABLE_1 из DB_TABLE_2
		if (($render_item["TID"]==6) && ($render_item["DB_TABLE_1"]==$render_db)) {

			$were2 = "where ";
			if ($render_where_2.""!=="") $were2 = "where (".$render_where_2.") and";
			$result = dbquery("SELECT ID FROM ".$db_prefix.$render_item["DB_TABLE_2"]." ".$were2." (".$render_item["FIELD_2"]."='".$render_row["ID"]."') limit 0,1");
			if (mysql_fetch_array($result)) $ischilds = true;
		}
		// Смотрим childs если TID 11
		if ($render_item["TID"]==11) {

			// входящие элементы DB_TABLE_2
			if ($render_item["FIELD_1"]==$render_db) {

				$were2 = "where ";
				if ($render_where_2.""!=="") $were2 = "where (".$render_where_2.") and";
				$result = dbquery("SELECT ID FROM ".$db_prefix.$render_item["DB_TABLE_2"]." ".$were2." (".$render_item["FIELD_2"]."='".$render_row["ID"]."') limit 0,1");
				if (mysql_fetch_array($result)) $ischilds = true;
			}

			// Входящие каталоги
			$db_tables = explode("|",$render_item["DB_TABLE_1"]);
			$db_count = count($db_tables);

			if ($db_count>1) {

				$db_field = "";
				$db_next = "";

				for ($j=0;$j < $db_count-1;$j++) {
				
					$db_xxx = explode("/",$db_tables[$j]);

					if ($db_xxx[0]==$render_db) {
						$db_next = explode("/",$db_tables[$j+1]);
						$db_field = $db_next[1];
						$db_next = $db_next[0];
					}
				}

				if ($db_next!=="") {
					$result = dbquery("SELECT ID FROM ".$db_prefix.$db_next." WHERE (".$db_field."='".$render_row["ID"]."') limit 0,1");
					if (mysql_fetch_array($result)) $ischilds = true;
				}
			}
		}

		$oct = OCT_Link($render_db."_".$showed_form["ID"]."_".$render_row["ID"],$render_n,$ischilds);
		
		$pichtml = "<img src='uses/view.gif' alt='".$loc["26"]."'>";
		if ($html!=="") $pichtml = $html;

		$pic = "";
		if ($show_formlink!==0) {
			$pic = "<!--|--><a href='index.php?do=show&formid=".$show_formlink."&id=".$render_row["ID"]."' title='".$loc["26"]."'>".$pichtml."</a> ";
			if (($db_cfg[$render_db."|TYPE"]=="ltree") && ($render_row["LID"]*1!==0)) {
				$pic = "<!--|--><a href='index.php?do=show&formid=".$show_formlink."&id=".$render_row["LID"]."' title='".$loc["26"]."'>".$pichtml."</a> ";
			}
		}
		$do_edit = $rw;
		if (!$render_edit) $do_edit = false;
		$fhtml = "";
		if (($html!=="") && ($show_formlink==0)) $fhtml = "<!--|-->".$html;
		Field($render_row,$render_db,$field,$do_edit,"",$oct[0].$pic.$fhtml,$render_options);

		$render_oct = $oct[1];
	}

	function OCT_Link($val,$n,$ischilds) {
		global $pageurl, $opened;

		$isopened = substr_count($opened, "|".$val."|")>0;

		$ml = $n*10;
		$pic = "<img style='margin-left: ".$ml."px;' src='uses/none.png'>";
		if ((!$isopened) && ($ischilds)) $pic = "<a href='".$pageurl."&open=".$val."'><img style='margin-left: ".$ml."px;' src='uses/collapse.png'></a>";
		if (($isopened) && ($ischilds)) $pic = "<a href='".$pageurl."&close=".$val."'><img style='margin-left: ".$ml."px;' src='uses/expand.png'></a>";

		return array($pic,(($isopened) && ($ischilds)));
	}

	function Render_val($field) {
		global $db_prefix, $db_cfg, $render_db, $render_row;

		echo FVal($render_row,$render_db,$field);
	}

	function Render_if($field,$val,$ech) {
		global $db_prefix, $db_cfg, $render_db, $render_row;

		if ($render_row[$field]==$val) echo $ech;
	}

	function Render_wval($db,$field,$where) {
		global $db_prefix, $db_cfg, $render_db, $render_row;

		$xres = dbquery("SELECT ID, ".$field." FROM ".$db_prefix.$db." where (".$where.")");
		$xres = mysql_fetch_array($xres);

		echo FVal($xres,$db,$field);
	}

	function Render_out($x) {

		echo $x;
	}



	// Eval для текста (НЕ для FIELD)
	/////////////////////////////////////////////////

	function EvalCode_txt($code,$render_options,$addclass = "") {
		global $render_row_id, $render_row, $render_inc;

		$row = $render_row;
		$inc = $render_inc;
		$ncode = str_replace('{{/}}',");\n?>",$code);
		$ncode = str_replace('{{val}}',"<?php\nRender_val(",$ncode);
		$ncode = str_replace('{{/val}}',");\n?>",$ncode);
		$ncode = str_replace('{{wval}}',"<?php\nRender_wval(",$ncode);
		$ncode = str_replace('{{/wval}}',");\n?>",$ncode);
		$ncode = str_replace('{{out}}',"<?php\nRender_out(",$ncode);
		$ncode = str_replace('{{/out}}',");\n?>",$ncode);
		$ncode = str_replace('{{id}}',$render_row_id,$ncode);
		$class = 'Field';
		$addclass =  str_replace('{{/}}',");\n?>",$addclass);
		$addclass = str_replace('{{val}}',"<?php\nRender_val(",$addclass);
		$addclass = str_replace('{{/val}}',");\n?>",$addclass);
		$addclass = str_replace('{{out}}',"<?php\nRender_out(",$addclass);
		$addclass = str_replace('{{/out}}',");\n?>",$addclass);
		
		if ($addclass.'' !== '') 
			$class = 'Field '.$addclass;
		
		eval(" ?><td class='".$class."' ".$render_options.">\n".$ncode."</td>\n<?php ");
	}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//	HEADERS
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





	function Render_header_standart($item, $table_1_add = false, $table_1_addline = false) {
		global $db_prefix, $db_cfg, $id, $print_mode;

		$header = destripinput($item["HEADER"]);
		$header = explode("\n",$header);

		$num_rows = 1;
		$header_count = count($header);
		for ($j=0;$j < $header_count;$j++) {
			if ($header[$j]=="-") $num_rows += 1;
		}
		$outdel = $table_1_add;

		$addf = "";
		$addv = "";
		if ($item["TID"]==9) {
			$addf = $item["FIELD_1"];
			$addv = $id;
		}
		$tblwidth = $item["WIDTH"]."px";
		if ($item["WIDTH"]*1==0) $tblwidth = "100%";
		if (($print_mode=="on") && ($item["WIDTH"]*1>1000)) $tblwidth = "100%";

		if ($table_1_addline) AddLineLink($item["DB_TABLE_1"],$addf,$addv);
		
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: ".$tblwidth.";' border='1' cellpadding='0' cellspacing='0'>\n";
		echo "	<thead>\n";
		echo "	<tr class='first'>\n";
		if ($table_1_add) AddTreeHeader($item["DB_TABLE_1"],$num_rows);
		$header_count = count($header);

		for ($j=0;$j < $header_count;$j++) {
			if (($header[$j]!=="") && ($header[$j]!=="-")) {
				$td = explode("|",$header[$j]);
				echo "		<td ".$td[1]." >".$td[0]."</td>\n";
			}
			if ($header[$j]=="-") {
				if ($outdel) DelHeader($item["DB_TABLE_1"],$num_rows);
				$outdel = false;
				echo "	</tr>\n";
				echo "	<tr class='first'>\n";
			}
		}
		if ($outdel) DelHeader($item["DB_TABLE_1"],$num_rows);
		echo "	</tr>\n";
		echo "	</thead>\n";
	}

	function Render_header_calendar($item, $table_1_add = false, $table_1_addline = false) {
		global $db_prefix, $db_cfg, $id, $render_month, $loc, $print_mode, $WW_Name;

		$header = destripinput($item["HEADER"]);
		$header = explode("\n",$header);

		$my = explode(".",$render_month);
		$firstday = date("d",mktime(0, 0, 0, $my[0], 1, $my[1]))*1;
		$lastday = date("d",mktime(0, 0, 0, $my[0]+1, 0, $my[1]))*1;

		$tblwidth = "width: ".($item["WIDTH"]*1)."px;";
		if ($item["WIDTH"]*1==0) $tblwidth = "";
		if (($print_mode=="on") && ($item["WIDTH"]*1>1000)) $tblwidth = "width: 100%;";

		$num_rows = 1;
		$header_count = count($header);
		for ($j=0;$j < $header_count;$j++) {
			if ($header[$j]=="-") $num_rows += 1;
		}
		$outdel = $table_1_add;
		$outdates = true;
		$rowspan = "";
		if ($num_rows>1) $rowspan = "rowspan='".$num_rows."'";

		if ($table_1_addline) AddLineLink($item["DB_TABLE_1"]);
		
		echo "<div style='".$tblwidth."'></div>";
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000;' border='1' cellpadding='0' cellspacing='0'>\n";
		echo "	<thead>\n";
		echo "	<tr class='first'>\n";
		if ($table_1_add) AddTreeHeader($item["DB_TABLE_1"],$num_rows);
		$header_count = count($header);

		for ($j=0;$j < $header_count;$j++) {
			if (($header[$j]!=="") && ($header[$j]!=="-")) {
				$td = explode("|",$header[$j]);
				echo "		<td ".$td[1]." >".$td[0]."</td>\n";
			}
			if ($header[$j]=="-") {
				if ($outdates) {
					for ($d=$firstday;$d <= $lastday;$d++) {
						$dtxt = $d;
						if ($d<10) $dtxt = "0".$d;
						$wd = date("w",mktime(0, 0, 0, $my[0], $d, $my[1]))*1;
						$ss = "";
						if (($wd==0) or ($wd==6)) $ss = " class='SS'";
						echo "<td ".$rowspan.$ss.">".$dtxt."<br>".$WW_Name[$wd]."</td>";
					}
					echo "<td class='MIN' ".$rowspan.">".$loc["itog"]."</td>";
				}
				if ($outdel) DelHeader($item["DB_TABLE_1"],$num_rows);
				$outdel = false;
				$outdates = false;
				echo "	</tr>\n";
				echo "	<tr class='first'>\n";
			}
		}
		if ($outdates) {
			for ($d=$firstday;$d <= $lastday;$d++) {
				$dtxt = $d;
				if ($d<10) $dtxt = "0".$d;
				$wd = date("w",mktime(0, 0, 0, $my[0], $d*1, $my[1]))*1;
				$ss = "";
				if (($wd==0) or ($wd==6)) $ss = " class='SS'";
				echo "<td ".$rowspan.$ss.">".$dtxt."<br>".$WW_Name[$wd]."</td>";
			}
			echo "<td class='MIN' ".$rowspan.">".$loc["itog"]."</td>";
		}
		if ($outdel) DelHeader($item["DB_TABLE_1"],$num_rows);
		echo "	</tr>\n";
		echo "	</thead>\n";
	}

	function Render_header_dbcat($item, $table_2_add = false) {
		global $db_prefix, $db_cfg, $print_mode;

		$header = destripinput($item["HEADER"]);
		$header = explode("\n",$header);

		$num_rows = 1;
		for ($j=0;$j < count($header);$j++) {
			if ($header[$j]=="-") $num_rows = $num_rows + 1;
		}
		$outdel = $table_2_add;
		$tblwidth = $item["WIDTH"]."px";
		if ($item["WIDTH"]*1==0) $tblwidth = "100%";
		if (($print_mode=="on") && ($item["WIDTH"]*1>1000)) $tblwidth = "100%";

		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: ".$tblwidth.";' border='1' cellpadding='0' cellspacing='0'>\n";
		echo "	<thead>\n";
		echo "	<tr class='first'>\n";
		if ($table_2_add) AddCatHeader($item["DB_TABLE_2"],$num_rows);
		$header_count = count($header);
		for ($j=0;$j < $header_count;$j++) {
			if (($header[$j]!=="") && ($header[$j]!=="-")) {
				$td = explode("|",$header[$j]);
				echo "		<td ".$td[1]." >".$td[0]."</td>\n";
			}
			if ($header[$j]=="-") {
				if ($outdel) DelHeader($item["DB_TABLE_2"],$num_rows);
				$outdel = false;
				echo "	</tr>\n";
				echo "	<tr class='first'>\n";
			}
		}
		if ($outdel) DelHeader($item["DB_TABLE_2"],$num_rows);
		echo "	</tr>\n";
		echo "	</thead>\n";
	}








//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//	INCLUDE
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	include "openid_calendar.php";
	include "openid_standart.php";
	include "openid_datecat.php";
	include "openid_dbcat.php";






//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//	RENDERER
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function Render_item($ID_formsitem = 0, $table_1_add = false, $table_1_edit = false, $table_2_add = false, $table_2_edit = false, $sql_where_1_in = "", $sql_where_2_in = "", $sql_orderby_1 = "", $sql_orderby_2 = "", $end_rows = "") {
		global $db_prefix;

		if ($ID_formsitem!==0) {
			$xxx = dbquery("SELECT * FROM ".$db_prefix."formsitem where (ID = '".$ID_formsitem."')");
			if ($item = mysql_fetch_array($xxx)) {
				Render_byitem($item, $table_1_add, $table_1_edit, $table_2_add, $table_2_edit, $sql_where_1_in, $sql_where_2_in, $sql_orderby_1, $sql_orderby_2, $end_rows);
			}
		}
	}

	function RenderBottomView() {
		global $show_bottom_page, $render_bview, $id, $db_prefix, $user;

		if ($show_bottom_page) {
			$pattern = OutReplacer($render_bview);
			eval(" ?>".$pattern."<?php ");
		}
	}


	function Render_byitem($item, $table_1_add = false, $table_1_edit = false, $table_2_add = false, $table_2_edit = false, $sql_where_1_in = "", $sql_where_2_in = "", $sql_orderby_1 = "", $sql_orderby_2 = "", $end_rows = "") {
		global $db_prefix, $db_cfg, $id, $render_n, $render_oct, $render_where_2, $print_mode, $show_bottom_page, $loc, $render_inc, $render_bview;


		$render_inc = 0;
		$render_n = 0;
		$render_oct = false;
		$render_where_2 = $sql_where_2_in;

		$sql_where_1 = "";
		if ($sql_where_1_in!=="") $sql_where_1 = "where (".$sql_where_1_in.")";
		$sql_where_2 = "";
		if ($sql_where_2_in!=="") $sql_where_2 = "where (".$sql_where_2_in.")";


		echo "\n\n<!-- Table_".$ID_formsitem." -->\n\n";

			     // Шапка таблицы
			     //////////////////////////////////
				if ($item["TID"]==1) Render_header_standart($item, $table_1_add, $table_1_add);
				if ($item["TID"]==2) Render_header_standart($item, $table_1_add, false);
				if ($item["TID"]==3) Render_header_standart($item, $table_1_add, $table_1_add);
				if ($item["TID"]==4) Render_header_standart($item, false, false);
				if ($item["TID"]==5) {
					$q = "";
					if (isset($_POST["q"])) $q = $_POST["q"];
					echo "<div class='searchdiv'><input class='searchinput' type='text' name='q' value='".$q."'> <input  class='searchsubmit' type='submit' value='".$loc["rd1"]."'></div>";
					Render_header_standart($item, false, false);
				}
				if ($item["TID"]==6) Render_header_dbcat($item, $table_2_add);
				if ($item["TID"]==7) Render_header_standart($item, $table_1_add, $table_1_add);
				if ($item["TID"]==8) Render_header_standart($item, $table_1_add, $table_1_add);
				if ($item["TID"]==9) Render_header_standart($item, $table_1_add, $table_1_add);
				if ($item["TID"]==10) { 
					Render_header_calendar($item, false, false);
					Render_calc_calendar_render_data($item);
					if (isset($_GET["row"])) {
						$show_bottom_page = true;
						$render_bview = $item["OPENID_2"];
					}
				}
				if ($item["TID"]==11) Render_header_dbcat($item, $table_2_add);
				if ($item["TID"]==12) Render_header_standart($item, false, false);


			     // Сама таблица
			     //////////////////////////////////
				if ($item["TID"]*1!==0) echo "	<tbody>\n";

				if ($item["TID"]==1) {	// Стандартный режим (ред. Таблица БД №1)
					$sql_fwhere_1 = $sql_where_1;
					if (($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") or ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="ltree")) {	
						$sql_fwhere_1 = "where (PID='0')";
						if ($sql_where_1_in!=="") $sql_fwhere_1 = "where (".$sql_where_1_in.") and (PID='0')";
					}
					$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_fwhere_1." ".$sql_orderby_1);
					while($row = mysql_fetch_array($result)) Render_openid_standart($row, $item, $table_1_add, $table_1_edit, $sql_orderby_1, 0, $sql_where_1_in);
				}

				if ($item["TID"]==2) {	// Входящее дерево (ред. Таблица БД №1) с обязательным корневым элементом
					$sql_fwhere_1 = "where (".$item["FIELD_1"]."='".$id."')";
					if ($sql_where_1_in!=="") $sql_fwhere_1 = "where (".$sql_where_1_in.") and (".$item["FIELD_1"]."='".$id."')";
					if (($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") or ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="ltree")) {
						$sql_fwhere_1 = "where (PID='0') and (".$item["FIELD_1"]."='".$id."')";
						if ($sql_where_1_in!=="") $sql_fwhere_1 = "where (".$sql_where_1_in.") and (".$item["FIELD_1"]."='".$id."')";
					}
					$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_fwhere_1." ".$sql_orderby_1);
					while($row = mysql_fetch_array($result)) Render_openid_standart($row, $item, $table_1_add, $table_1_edit, $sql_orderby_1, 0, $sql_where_1_in);
				}

				if ($item["TID"]==3) {	// Каталог Год/Месяц (ред. Таблица БД №1)
					Render_datecat($item, $table_1_add, $table_1_edit, $sql_where_1_in, $sql_orderby_1, 0);
				}

				if ($item["TID"]==4) {	// Поля одного элемента (ред. Таблица БД №1)
					$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." where (ID='".$id."') ");
					if ($row = mysql_fetch_array($result)) Render_openid_standart($row, $item, false, $table_1_edit);
				}

				if ($item["TID"]==5) {	// Поиск по таблице (Таблица БД №1)

					if (($item["FIELD_1"].""!=="") && (isset($_POST["q"]))) {

						$find_fields = explode("|",$item["FIELD_1"]);

						$search = trim(strip_tags($_POST["q"]));
						$search = substr($search, 0, 64);
						if (strlen($search)<3) $search = "";
						//$search = ereg_replace(" +", " ", $search);

					   if (($search!=="") && ($search!==" ")) {

						$sql = array();
						foreach($find_fields as $flx){
							$sql[] = "(".$flx." LIKE '%".$search."%')";
						}
					
						$find_where = "WHERE (".implode(" OR ", $sql).")";
						if ($sql_where_1_in!=="") $find_where = $find_where." and ".$sql_where_1_in;

						$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$find_where." ".$sql_orderby_1);
						while($row = mysql_fetch_array($result)) Render_openid_standart($row, $item, false, false, $sql_orderby_1, 0, "");
					   }
					}
				}

				if ($item["TID"]==6) {	// Каталог в БД (кат. Таблица БД №1, ред. Таблица БД №2)
					$sql_fwhere_1 = $sql_where_1;
					if (($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") or ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="ltree")) {	
						$sql_fwhere_1 = "where (PID='0')";
						if ($sql_where_1_in!=="") $sql_fwhere_1 = "where (".$sql_where_1_in.") and (PID='0')";
					}
					$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_fwhere_1." ".$sql_orderby_1);
					while($row = mysql_fetch_array($result)) Render_openid_dbcat($row, $item, $table_1_edit, $sql_orderby_1, 0, $sql_where_1_in, $table_2_add, $table_2_edit, $sql_where_2_in, $sql_orderby_2);
				}

				if ($item["TID"]==7) {	// Каталог Год (ред. Таблица БД №1)
					Render_yearcat($item, $table_1_add, $table_1_edit, $sql_where_1_in, $sql_orderby_1, 0);
				}

				if ($item["TID"]==8) {	// Стандартный режим с условием (ред. Таблица БД №1)
					$sql_fwhere_1 = $sql_where_1;
					if (($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") or ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="ltree")) {	
						$sql_fwhere_1 = "where (PID='0')";
						if ($sql_where_1_in!=="") $sql_fwhere_1 = "where (".$sql_where_1_in.") and (PID='0')";
					}
					$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_fwhere_1." ".$sql_orderby_1);
					while($row = mysql_fetch_array($result)) Render_openid_wstandart($row, $item, $table_1_add, $table_1_edit, $sql_orderby_1, 0, $sql_where_1_in);
				}

				if ($item["TID"]==9) {	// Входящее дерево (ред. Таблица БД №1)
					$sql_fwhere_1 = "where (".$item["FIELD_1"]."='".$id."')";
					if ($sql_where_1_in!=="") $sql_fwhere_1 = "where (".$sql_where_1_in.") and (".$item["FIELD_1"]."='".$id."')";
					if (($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") or ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="ltree")) {
						$sql_fwhere_1 = "where (PID='0') and (".$item["FIELD_1"]."='".$id."')";
						if ($sql_where_1_in!=="") $sql_fwhere_1 = "where (".$sql_where_1_in.") and (".$item["FIELD_1"]."='".$id."')";
					}
					$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_fwhere_1." ".$sql_orderby_1);
					while($row = mysql_fetch_array($result)) Render_openid_standart($row, $item, $table_1_add, $table_1_edit, $sql_orderby_1, 0, $sql_where_1_in);
				}

				if ($item["TID"]==10) {	// Календарный вид суммирование (ред. Таблица БД №2)
					$sql_fwhere_1 = $sql_where_1;
					if (($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") or ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="ltree")) {	
						$sql_fwhere_1 = "where (PID='0')";
						if ($sql_where_1_in!=="") $sql_fwhere_1 = "where (".$sql_where_1_in.") and (PID='0')";
					}
					$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_fwhere_1." ".$sql_orderby_1);
					while($row = mysql_fetch_array($result)) Render_openid_calendar($row, $item, $table_1_edit, $sql_where_1_in, $sql_orderby_1);
					Render_calendar_itog($item);
				}


				if ($item["TID"]==11) {	// Каталог во многих БД (кат. Таблица БД №1, ред. Таблица БД №2)

					$db_tables = explode("|",$item["DB_TABLE_1"]);

					$sql_fwhere_1 = $sql_where_1;
					if (($db_cfg[$db_tables[0]."|TYPE"]=="tree") or ($db_cfg[$db_tables[0]."|TYPE"]=="ltree")) {	
						$sql_fwhere_1 = "where (PID='0')";
						if ($sql_where_1_in!=="") $sql_fwhere_1 = "where (".$sql_where_1_in.") and (PID='0')";
					}

					$result = dbquery("SELECT * FROM ".$db_prefix.$db_tables[0]." ".$sql_fwhere_1." ".$sql_orderby_1);
					while($row = mysql_fetch_array($result)) Render_openid_dbxcat($row, $item, $table_1_edit, $sql_orderby_1, 0, $sql_where_1_in, $table_2_add, $table_2_edit, $sql_where_2_in, $sql_orderby_2);
				}

				if ($item["TID"]==12) {	// Стандартный режим без учёта tree (ред. Таблица БД №1)
					$sql_fwhere_1 = $sql_where_1;
					$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_fwhere_1." ".$sql_orderby_1);
					while($row = mysql_fetch_array($result)) Render_openid_standart($row, $item, $table_1_add, $table_1_edit, $sql_orderby_1, 0, $sql_where_1_in);
				}

			     // Конец таблицы
			     //////////////////////////////////

				if ($item["TID"]*1!==0) {
					if ($end_rows!=="") {
						echo $end_rows;
					}

					echo "	</tbody>\n";
					echo "</table>\n";
				}

		echo "\n<!-- //////// -->\n";
	}

?>