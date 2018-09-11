<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


/////////////////////////////////////////////////////////////////////////////////////
//
// LOGIN
//
/////////////////////////////////////////////////////////////////////////////////////


// shindax 01.09.2016. Везде к обращению WHERE ID_users2='exp' добавлено OR ID_users2 LIKE '%exp|%'

	function Login_form() {
		global $loc;

		echo "\n<!-- Login form -->\n";
		echo "<img src='project/img/logo.png'><br>\n";
		echo "<form name='loginform' action='index.php' method='post'>\n";
		echo "	<span class='login'>\n";
		echo "	<b>".$loc["9"]."</b><br><br>\n";
		echo "	<input type='text' name='user_login'><input type='password' name='user_pass'><br><br>\n";
		echo "	<input type='submit' value='".$loc["17"]."'>\n";
		echo "	</span>\n";
		echo "</form>\n";
	}



/////////////////////////////////////////////////////////////////////////////////////
//
// topbar menu
//
/////////////////////////////////////////////////////////////////////////////////////

	function bar() {
		global $user, $loc, $pageurl, $user_lastform, $copy_state;

		echo "\n<!-- Bar -->\n";
		$copy = "";
		if ($copy_state) $copy = " copybar";
		echo "<div class='bar".$copy."'><div>\n";

		$prturl = str_replace ("index.php","print.php", $pageurl);
		$clock = "<span>".date("d.m.Y  H:i")."</span>";
		$user_str = "<span></span>";
		if ($user!==0) $user_str = "<span>".$loc["1"].": <a href='index.php?do=profile' title='".$loc["19"]."'><b>".$user["IO"]."</b></a></span>\n	<span><a href='index.php?unlogin'>".$loc["2"]."</a></span>\n";

		echo "	";
		if ($user!==0) {
//			$itr_c_r = dbquery("SELECT COUNT(ID) FROM okb_db_itrzadan WHERE (( ID_users2=(SELECT ID FROM okb_db_resurs WHERE ID_users=".$user['ID'].")) OR ) AND (STATUS!='Завершено') AND (STATUS!='Аннулировано') AND (STATUS!='Принято') AND (STATUS!='Выполнено') ");
      $itr_c_r = dbquery("SELECT COUNT(ID) FROM okb_db_itrzadan WHERE (( ID_users2=(SELECT ID FROM okb_db_resurs WHERE ID_users=".$user['ID']." )) OR ( ID_users2 LIKE '%(SELECT ID FROM okb_db_resurs WHERE ID_users=".$user['ID'].")|%' ) ) AND (STATUS!='Завершено') AND (STATUS!='Аннулировано') AND (STATUS!='Принято') AND (STATUS!='Выполнено')");
			
			
			$itr_c_r_n = mysql_fetch_row($itr_c_r);
			$itr_c_r_2 = dbquery("SELECT COUNT(ID) FROM okb_db_itrzadan WHERE (ID_users=(SELECT ID FROM okb_db_resurs WHERE ID_users=".$user['ID'].")) AND (STATUS='Принято') ");
			$itr_c_r_n_2 = mysql_fetch_row($itr_c_r_2);
			$itr_c_r_3 = dbquery("SELECT COUNT(ID) FROM okb_db_itrzadan WHERE (ID_users3=(SELECT ID FROM okb_db_resurs WHERE ID_users=".$user['ID'].")) AND (STATUS='Выполнено') ");
			$itr_c_r_n_3 = mysql_fetch_row($itr_c_r_3);
		}
		echo "<a><img class='nav' title='".$loc["23"]."' src='style/home.png' onclick='location.href=\"index.php?do=show&formid=117\"' style='cursor:pointer; margin-right: 1px;'>
		<b title='Сколько заданий ещё в работе' class='nav' onclick='location.href=\"index.php?do=show&formid=117\"' style='cursor:pointer; text-align:left; margin-right:0px; color:#55bb55; float:left;'>".$itr_c_r_n[0]."</b><b class='nav' style='cursor:pointer; text-align:left; margin-right:0px; color:#000; float:left;'>&nbsp;/&nbsp;</b>
		<b title='Сколько заданий принято от контроля' class='nav' onclick='location.href=\"index.php?do=show&formid=118\"' style='cursor:pointer; text-align:left; margin-right:0px; color:#66AAFF; float:left;'>".$itr_c_r_n_2[0]."</b><b class='nav' style='cursor:pointer; text-align:left; margin-right:0px; color:#000; float:left;'>&nbsp;/&nbsp;</b>
		<b title='Сколько заданий на контроль' class='nav' onclick='location.href=\"index.php?do=show&formid=119\"' style='cursor:pointer; text-align:left; margin-right:27px; color:#63008A; float:left;'>".$itr_c_r_n_3[0]."</b>
		</a>\n";
		//echo "	<a href='".$user_lastform."' title='".$loc["12"]."'><img class='nav' src='style/back.png'></a>\n";
		echo "	<a href='".$pageurl."&event'  title='".$loc["11"]."'><img class='nav' src='style/refresh.png'></a>\n";
		if ($user!==0) {
			echo "	<a href='".$prturl."' title='".$loc["21"]."' target='_blank'><img class='nav' src='style/print.png'></a>\n";
			echo "	<span class='popup' title='".$loc["25"]."' onClick='chClass(this,\"hpopup\",\"popup\");'><img class='nav' src='style/help.png'>\n";
			echo "	<div class='hlppopup' style='position: fixed;' onClick='window.event.cancelBubble = true;'>\n";
			show_help();
			echo "\n	</div>";
			echo "</span>\n";
			
			$minch_1[0] = 0;
			$minch_3[0] = 0;
			$cur_for28 = $_GET['formid'];
			if ($cur_for28!=='28'){ 
				$minch = dbquery("SELECT COUNT(*) FROM okb_db_online_chat_curid WHERE (CHTIME>'".$user["MINI_CHAT"]."') AND (ID_users2='0') ");
				$minch_1 = mysql_fetch_row($minch);
				$minch_2 = dbquery("SELECT COUNT(*) FROM okb_db_online_chat_curid WHERE ( (( ID_users2='".$user['ID']."' ) OR ( ID_users2 LIKE '%".$user['ID']."|%' )) AND (CHTIME>'".$user["MINI_CHAT"]."')) ");
				$minch_3 = mysql_fetch_row($minch_2);
				if ($minch_1[0]>0){
					$color_b_1 = "blue";
				}else{
					$color_b_1 = "black";
				}
				if ($minch_3[0]>0){
					$color_b_3 = "red";
				}else{
					$color_b_3 = "black";
				}
			}
				echo "<a href='index.php?do=show&formid=28'><span style='margin-left:10px; border-radius: 6px; border:1px solid #888;'><b>&nbsp;ALL&nbsp;&nbsp;&nbsp;</b><b style='color:".$color_b_1.";'>".$minch_1[0]."&nbsp;</b></span><span id='border_pm_msg_us' style='margin-left:10px; border-radius: 6px; border:1px solid #888;'><b>&nbsp;PM&nbsp;&nbsp;&nbsp;</b><b style='color:".$color_b_3.";'>".$minch_3[0]."&nbsp;</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img style='height:13px;' src='project/img/group.png'> Онлайн чат ОКБ Микрон</a>\n";
		}

		echo "	".$user_str."	".$clock."\n";
		echo "</div></div>\n";
	}

	function show_help() {
		global $loc, $do, $showed_form, $lang;

		if ($do!=="show") include "./locale/".$lang."/".$do."_hlp.php";
		if ($do=="show") {
			if ($showed_form["HLP"]!=="") echo "\n\n<?-- help -->\n".$showed_form["HLP"]."\n\n<?-- ///// -->\n\n";
			if ($showed_form["HLP"]=="") echo "\n\n<?-- help -->\n<h4>".$loc["30"]."</h4>\n\n<?-- ///// -->\n\n";
		}
	}

	function admin_menu() {
		global $user, $loc;

		if ($user["USERSEDIT"]=="1") {
		echo "	<div class='menuitem' onMouseOver='this.className=\"menuitemhover\";' onMouseOut='this.className=\"menuitem\";'>".$loc["3"]."\n";
		echo "		<br><div class='submenu'>\n";
			echo "			<a href='?do=users'>".$loc["4"]."</a>\n";
		if ($user["ID"]=="1") {
			echo "			<a href='?do=rightgroups'>".$loc["22"]."</a>\n";
			echo "			<div class='hr'></div>\n";
			echo "			<a href='?do=formgroups'>".$loc["10"]."</a>\n";
			echo "			<a href='?do=forms'>".$loc["6"]."</a>\n";
			echo "			<a href='?do=formsitems'>".$loc["7"]."</a>\n";
			echo "			<div class='hr'></div>\n";
			echo "			<a href='?do=dbconf'>".$loc["24"]."</a>\n";
			echo "			<a href='?do=backup'>".$loc["8"]."</a>\n";
			echo "			<div class='hr'></div>\n";
			echo "			<a href='?do=help'>".$loc["13"]."</a>\n";
		}
		echo "		</div>\n";
		echo "	</div>\n";
		}
	}

	function menu() {
		global $user, $db_prefix, $top_offset;

		echo "\n<!-- Menu 0 -->\n";
		echo "<div class='menu'>\n";
		echo "	<div class='fld'></div>\n";

		$num = 0;
 
		if ($user!==0) {

			// ADMIN menu
			admin_menu();
			$num = $num + 1;

			// Вычисление остальных меню
			$formgroups_ids = array();
			$formgroups_showed = array();
			$forms_ids = array();
			$bar_id = "0";

			$xxx = dbquery("SELECT ID, NAME, BARID FROM ".$db_prefix."formgroups order by BARID, ORD");
			while($res = mysql_fetch_array($xxx)) $formgroups_ids[] = $res["ID"]."|".$res["NAME"]."|".$res["BARID"];

			$xxx = dbquery("SELECT ID, NAME, ID_formgroups, GROUPID, SHOWALL FROM ".$db_prefix."forms order by ID_formgroups, ORD");
			while($res = mysql_fetch_array($xxx)) {
				if (showform_check($res)) {
					$forms_ids[] = $res["ID"]."|".$res["NAME"]."|".$res["ID_formgroups"]."|".$res["GROUPID"];
					$formgroups_showed[] = $res["ID_formgroups"];
				}
			}

			for ($i=0;$i < count($formgroups_ids);$i++) {
				$fg_item = explode("|",$formgroups_ids[$i]);
				if (in_array($fg_item[0],$formgroups_showed)) {
					$new_bar_id = $fg_item[2];
					if ($new_bar_id !== $bar_id) {
						echo "	<div class='fld2'></div>\n";
						echo "</div>\n";
						echo "\n<!-- Menu $new_bar_id -->\n";
						echo "<div class='menu'>\n";
						echo "	<div class='fld'></div>\n";
						$bar_id = $new_bar_id;
						$num = $num + 1;
					}
					echo "	<div class='menuitem' onMouseOver='this.className=\"menuitemhover\";' onMouseOut='this.className=\"menuitem\";'>".$fg_item[1]."\n";
					echo "		<br><div class='submenu'>\n";
					$groupid = "0";
					for ($j=0;$j < count($forms_ids);$j++) {
						$f_item = explode("|",$forms_ids[$j]);
						if ($f_item[2]==$fg_item[0]) {
							if ($f_item[3]!==$groupid) {
								$groupid = $f_item[3];
								if ($j!==0) echo "			<div class='hr'></div>\n";
							}
							echo "			<a href='?do=show&formid=".$f_item[0]."'>".$f_item[1]."</a>\n";
						}
					}
					echo "		</div>\n";
					echo "	</div>\n";
				}
			}
		}

		echo "	<div class='fld2'></div>\n";
		echo "</div>\n";

		
		$top_offset = 27+(25*$num);
		
	}



/////////////////////////////////////////////////////////////////////////////////////
//
// Работа с датами
//
/////////////////////////////////////////////////////////////////////////////////////

	function TodayDate() {
		return date("d.m.Y");
	}

	function GetMonday($dweek=0){
		return date("d.m.Y", strtotime("last Monday")+($dweek*604800));
	}

	function GetSunday($dweek=0){
		return date("d.m.Y", strtotime("Sunday")+($dweek*604800));
	}

	function TodayInt() {
		return date("Ymd")*1;
	}

	function NextYear() {
		$today = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
		return date("Y",$today)+1;
	}


/////////////////////////////////////////////////////////////////////////////////////
//
// Создание / восстановление backup
//
/////////////////////////////////////////////////////////////////////////////////////


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// MAVERP_SaveBackupFile($FILENAME,$tables,$minutes = 60)	- запись бэкапа таблиц БД в файл ".sql" и удаление лишних бэкапов
	//
	// $FILENAME - имя файла string, $tables - список таблиц без префикса array, $minutes - кол-во минут до таймаута (0 - нет таймаута вообще)
	//
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function MAVERP_SaveBackupFile($FILENAME,$tables,$minutes = 60) {	
		global $db_cfg, $db_prefix, $db_name, $backup_count;

		set_time_limit($minutes*60);

		if ($handle = fopen($FILENAME.".sql","w+")) {
		//////////////////////////////////////////////////////////////////////////////////////////

			fwrite($handle,"#----------------------------------------------------------\n");
			fwrite($handle,"# MAV ERP Solution\n");
			fwrite($handle,"# Database Name: `$db_name`\n");
			fwrite($handle,"# Table Prefix: `".$db_prefix."`\n");
			fwrite($handle,"# Date: `".date("d/m/Y H:i")."`\n");
			fwrite($handle,"#----------------------------------------------------------\n");

		dbquery("SET SQL_QUOTE_SHOW_CREATE=1");
		foreach($tables as $table) {

		   // DROP / CREATE

			$start = "\n\n\n\n\n\n#----------------------------------\n# $db_prefix$table\n#----------------------------------\n\n\n\n\n\nDROP TABLE IF EXISTS `$db_prefix$table`;\n";
			$row=dbarraynum(dbquery("SHOW CREATE TABLE $db_prefix$table"));
			fwrite($handle,$start.$row[1].";\n\n\n");

		   // DATA DUMP

			$result=dbquery("SELECT * FROM $db_prefix$table");
			if($result&&dbrows($result)){
				$column_list="";
				$num_fields=mysql_num_fields($result);
				for($i=0;$i<$num_fields;$i++){
					$column_list.=(($column_list!="")?", ":"")."`".mysql_field_name($result,$i)."`";
				}
			}
			while($row=dbarraynum($result)){
				$dump="INSERT INTO `$db_prefix$table` ($column_list) VALUES (";
				for($i=0;$i<$num_fields;$i++){
					$dump.=($i>0)?", ":"";
					if(!isset($row[$i])){
						$dump.="NULL";
					}elseif($row[$i]=="0"||$row[$i]!=""){
						$type=mysql_field_type($result,$i);
						if($type=="tinyint"||$type=="smallint"||$type=="mediumint"||$type=="int"||$type=="bigint"||$type=="timestamp"){
							$dump.=$row[$i];
						}else{
							$search_array=array('\\','\'',"\x00","\x0a","\x0d","\x1a");
							$replace_array=array('\\\\','\\\'','\0','\n','\r','\Z');
							$row[$i]=str_replace($search_array,$replace_array,$row[$i]);
							$dump.="'$row[$i]'";
						}
					}else{
					$dump.="''";
					}
				}
				$dump.=')';
				fwrite($handle,$dump.";\n");
			}

		   //////////////
		}

		//////////////////////////////////////////////////////////////////////////////////////////
		fclose($handle);

	    // Удаление лишних бэкапов

		$res_count = $backup_count*1;
		if ($res_count<10) $res_count = 10;

		$files = makefilelist("./project/".$backup_path."/", ".sql", false, true);
		for ($j=0;$j < count($files);$j++) {
			if ($j>$res_count-1) {
				unlink("./project/".$backup_path."/".$files[$j].".sql");
			}
		}

	   } else {

		die("<b style='color: red;'>FATAL ERROR:</b> fopen error on backup!!!");

	   }
	}

	function Create_auto_BACKUP() {
		global $db_cfg, $backup_path, $auto_backup_time, $backup_count;

		if ($auto_backup_time*1>0) {

			$autotime = $auto_backup_time*1;
			if ($autotime<600) $autotime = 600;

			$files = makefilelist("./project/".$backup_path."/", ".sql", false, true);
			if (count($files)>1) {
				$last_time = explode("-",$files[0]);
				$last_time = $last_time[0];
				$new_time = mktime();
				if ($new_time-$last_time>$autotime) MAVERP_SaveBackupFile("./project/".$backup_path."/".mktime()."-".date("d_m_Y",mktime()), explode("|",$db_cfg["SYSTEM"]."|".$db_cfg["PROJECT"]));
			} else {
				MAVERP_SaveBackupFile("./project/".$backup_path."/".mktime()."-".date("d_m_Y",mktime()), explode("|",$db_cfg["SYSTEM"]."|".$db_cfg["PROJECT"]));
			}
		}
	}

	function Create_BACKUP() {
		global $db_cfg, $backup_path, $auto_backup_time, $backup_count;

		MAVERP_SaveBackupFile("./project/".$backup_path."/".mktime()."-".date("d_m_Y",mktime()), explode("|",$db_cfg["SYSTEM"]."|".$db_cfg["PROJECT"]));
	}

	function Restore_BACKUP($filename) {
		global $backup_path, $db_name, $db_prefix;

		$result = file("./project/".$backup_path."/".$filename);

		if((preg_match("/# Database Name: `(.+?)`/i", $result[2], $db_name)<>0)&&(preg_match("/# Table Prefix: `(.+?)`/i", $result[3], $db_prefix)<>0)) {

			$inf_dbname = $db_name;
			$inf_tblpre = $db_prefix;
			$result = array_slice($result,7);
			$results = preg_split("/;$/m",implode("",$result));

			foreach($results as $result){
				mysql_unbuffered_query($result);
			}
		}
	}


/////////////////////////////////////////////////////////////////////////////////////
//
// Прочее
//
/////////////////////////////////////////////////////////////////////////////////////


	function makefilelist($folder, $filter, $sort = true, $rsort = false) {
		$res = array(); 
		$temp = opendir($folder);
		while ($file = readdir($temp)) {
                	$cc = strpos($file, $filter);
			if ($cc!==false) {
                        	$file = strtok($file, ".");
				if (!is_dir($folder.$file)) $res[] = $file;
			} 
		}
		closedir($temp);
		if ($sort) sort($res);
		if ($rsort) rsort($res);
		return $res;
	}

	function redirect($location, $type="header") {
		if ($type == "header") {
			header("Location: ".$location);
		} else {
			echo "<script type='text/javascript'>document.location.href='".$location."'</script>\n";
		}
	}

	function alert($message) {
			echo "<script type='text/javascript'>alert(\"".$message."\");</script>\n";
	}

	function stripinput($text) {
		$text = stripslashes($text);
		$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
		$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
		$text = str_replace($search, $replace, $text);
		return $text;
	}

	function destripinput($text) {
		$text = stripslashes($text);
		$replace = array("\"", "'", "\\", '\"', "\'", "<", ">", " ");
		$search = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", "&nbsp;");
		$text = str_replace($search, $replace, $text);
		return $text;
	}

	function codeurl($url) {
		$text = stripslashes($url);
		$search = array("=", "?", "&", ".");
		$replace = array("@1@", "@2@", "@3@", "@4@");
		$text = str_replace($search, $replace, $text);
		return $text;
	}

	function out_db_cfg() {
		global $db_cfg, $loc;

	$ret = "";
	if ($db_cfg["PROJECT"]!=="") {
	$ret = "<h2>".$loc["rcfg3"]."</h2>\n";
	$ret = $ret."<table class='tbl' style='width: 550px; margin-left: 0px; margin-bottom: 20px;' border='0' cellpadding='0' cellspacing='0'>\n";
	$ret = $ret."<tr class='first'>\n";
	$ret = $ret."<td width='100'><b>".$loc["rcfg1"]."</b></td>\n";
	$ret = $ret."<td><b>".$loc["rcfg2"]."</b></td>\n";
	$ret = $ret."<td width='200'><b>".$loc["rcfg4"]."</b></td>\n";
	$ret = $ret."</tr>\n";
	$db_tables = explode("|",$db_cfg["PROJECT"]);
	for ($j=0;$j < count($db_tables);$j++) {
		$ret = $ret."<tr><td class='Field first' style='cursor: hand;' onClick='ShowHide(\"dl_".$j."\"); ShowHide(\"dr_".$j."\");'><b>".$db_tables[$j]."</b></td>";
		$ret = $ret."<td class='Field'><div id='dl_".$j."' style='display: none;'>";
		$ret = $ret.$loc["rcfg5"]."<br>";
		$ret = $ret.$loc["rcfg6"]."<br>";
		$ret = $ret.$loc["rcfg7"]."<br>";
		$ret = $ret.$loc["rcfg9"]."<br><br>";
		$ret = $ret."<b>".$loc["rcfg8"]."</b><br>";
		$ret = $ret."ID<br>";
		if ($db_cfg[$db_tables[$j]."|TYPE"]=="tree") $ret = $ret."PID<br>";
		if ($db_cfg[$db_tables[$j]."|TYPE"]=="ltree") $ret = $ret."PID<br>";
		if ($db_cfg[$db_tables[$j]."|TYPE"]=="ltree") $ret = $ret."LID<br>";
		$db_fields = explode("|",$db_cfg[$db_tables[$j]."|FIELDS"]);
		for ($i=0;$i < count($db_fields);$i++) {
			$ret = $ret.$db_fields[$i]." &nbsp; &nbsp; [".$db_cfg[$db_tables[$j]."/".$db_fields[$i]]."]<br>";
		}
		$ret = $ret."</div></td>\n";
		$ret = $ret."<td class='Field'><div id='dr_".$j."' style='display: none;'>";
		$ret = $ret.$db_tables[$j]."|superadmin<br>";
		$ret = $ret.$db_tables[$j]."|add<br>";
		$ret = $ret.$db_tables[$j]."|redactor<br>";
		$ret = $ret.$db_tables[$j]."|onhold<br><br>";
		$ret = $ret."<b>==================</b><br>-<br>";
		if ($db_cfg[$db_tables[$j]."|TYPE"]=="tree") $ret = $ret."-<br>";
		if ($db_cfg[$db_tables[$j]."|TYPE"]=="ltree") $ret = $ret."-<br>";
		if ($db_cfg[$db_tables[$j]."|TYPE"]=="ltree") $ret = $ret."-<br>";
		$db_fields = explode("|",$db_cfg[$db_tables[$j]."|FIELDS"]);
		for ($i=0;$i < count($db_fields);$i++) {
			$ret = $ret.$db_tables[$j]."/".$db_fields[$i]."<br>";
		}
		$ret = $ret."</div></td></tr>\n";
	}
	$ret = $ret."</table>";
	}
	return $ret;
	}

	function FormatReal($num,$x) {
		$ret = number_format( $x, $num, '.', ' ');
		if ($x==floor($x)) $ret = number_format($x, 0, '.', ' ');
		return $ret;
	}

	function FormatRealCell($x) {

		$ret = "";
		$val = $x*1;
		if ($val!==0) {
			if ($val==floor($val)) {
				$ret = number_format($val, 0, ",", " ");
			} else {
				$ret = number_format($val, 2, ",", " ");
			}
		}
		return $ret;
	}

	Create_auto_BACKUP();
?>