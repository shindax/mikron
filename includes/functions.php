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
		echo "<img src='project/img/logo.png' width='600' height='auto'><br>\n";
		echo "<form name='loginform' action='index.php' method='post'>\n";
		echo "	<span class='login'>\n";
		echo "	<b>".$loc["9"]."</b><br><br>\n";
		echo "	<input type='text' name='user_login'><input type='password' name='user_pass'><br><br>\n";
		echo "	<input type='submit' value='".$loc["17"]."'>\n";
		echo "	</span>\n";
		echo "</form>
		<style>
		#social a {
			padding-left:30px;
		}
		
		</style>
		<br><br><br><br><br>
		<div id='social'>	
			<a style='font-size:13pt' href='https://okbmikron.ru' target='_blank'>https://okbmikron.ru</a><br/><br/>

			<a href='https://vk.com/public133866138' target='_blank'><img width='28' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAA5FBMVEUAAAAAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1DVbIrlAAAAS3RSTlMAAQQGBwkNDxAUGBkcHi8xNjg5OjtAQUNET1dYX2FkZmdtcXN4eYaOkZWanqiqrbK1t7m6vMDBxcjKzNHa3N7g4uTp6+/x8/f5+/3B3NB+AAAA10lEQVQYGe3B+SICURyG4fc0SlHIErLvS/YtkpCl4bv/+3F+Z0bTH3MHPA9/hGvd3rXvTyKg8SnTw5RPzy8ur6824VFmB6+qwOEdKjiCGZm4gKfAAUUlquB6Mmt4TzJ4uwoO8BZk4gjoyAAlBS8FPPcqsw08yxsAZwpqBLMKKjiZL1hS0CTVlumWagomp75lWvyaUJ54jKEt5eiQcQ/KUSdTjpVjnkxdI/pKLZJpamiZFaWmycwNlIpgVYl4nEyxpQTenhLHjKps3PQ/3vcxja6k+G2df8EPUr5d896vTA4AAAAASUVORK5CYII='/></a>
			<a href='https://www.youtube.com/channel/UCy2--LLN5vQwC_Xmx9TjLlQ' target='_blank'><img width='28' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAABa1BMVEUAAAAAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1AAJ1Bphx+ZAAAAeHRSTlMAAQIDBAUGBwgJCgsMDQ4PEBESExQVFhcYGRocHh8gISMmJygpKisvMDU2Nzg5Ojs8PT4/QEJESUtNUlRVVlldXl9iZmd1d3h5f4CFiImSl5idoKKjpqiqq7Cytbe5ury+w8jKzM7P0dXa3N7g5Obp7fHz9ff5+/1Tg/UFAAABlklEQVQ4T4XT51caQRQF8LuLNAvoKqIGQUXFWFAssSb2GmOCibGjYk80qAS5f76sb7Gxi/fDmznv/c58mDkDPEbjDJrYDuucHGM2aysChulMrgOBkAI14Eag5C1w8TN78YPcRw1HVQYKjthN0+VmtJ5+CzDCQ/hYb2fYAtRxAn4dtFkAjZ/eBzU6aC4CHOxtoFbNyaAFwBy5ipL/nDcBcKi54nLrOwccSiEolrrpr7/2z/6k7jL3zGbSqb/nBxvf5kJP83GaZ96Yl1rMyQYBfSxTc/FJUx19BlMCZvnBA3hapYnBZ7AmYFVv2/PNlyAh4KcBbN20dRIdmrrAw0qlKskLATsGQBvRQkCBl1W56mNKQOI16LqAk8rQKRTeCjh4DQaZezQMHwFMC9gzA3p4I2DLDPTE43FeCfheAJzE1J2mMSlgKQ+CKzoIb6OCnsg6VG4IGDOABy068Kr4wk07lGUuCmgy7i3zT9b7a71eZsmYAOWc5rnO/1X3b9N5woun2MprG8ORj9FY/8BALNoZaQ36K4zv+wDvErzj++AIQwAAAABJRU5ErkJggg=='/></a>
			<a href='https://rosrabota.ru/ent-vacs/37719' target='_blank'><img width='28' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADkAAAArBAMAAAAwKD0mAAAAMFBMVEUAAACKxT6KxT6KxT6KxT72kh6KxT6KxT6KxT72kh72kh72kh72kh6KxT6KxT72kh7sSgStAAAADnRSTlMA8ljbwbwapX7weaJRPUn5PE4AAAEVSURBVDjLZdAxSgNhEIbhQTRK0uQCQrCyksiCZbCwlm2sFfYCnkA8gXgTbazjARb0ComCNvm7VCk22Z3s/w4zXzkvTzNy0+9BzIZ37UpJ/a5snTftXqk/Bo8aasRv1IiHTa5samisv1Aqu4ZSwVCqwVDq4ux8j6Gbuq4/tYpcZPyl9V126+uB1j+Re6ViasaPctTV0laP12Krx6Wv4HmmVPCop7aeZPwhscqL5ufcqOD/UD2OFeyrx7EOJnIM9rVaijxpnoQ6mKWMl6FW3RFMVZoC1qrUYapSh6mVHsdgW5UuvkUOwVpTcamn0/Zym/RWFFNJrKOKdWOqUjAVCvZ1JfvNQrW/r3zlAfpVKhRMhYKpULDWLajjoFYYkJfsAAAAAElFTkSuQmCC'/></a>

		</div>
		\n";
	}



/////////////////////////////////////////////////////////////////////////////////////
//
// topbar menu
//
/////////////////////////////////////////////////////////////////////////////////////

	function bar() {
		global $user, $loc, $pageurl, $user_lastform, $copy_state;


		$clock = "<span id='clock_span'>".date("d.m.Y  H:i")."</span>";

		$notify = "<a class='hidden' href='index.php?do=show&formid=263&tab=plan_fact' target='_blank' id='notify_link'>Новых уведомлений</a></span>";

		$coord_page = "<a class='hidden' href='index.php?do=show&formid=263&tab=coord_page' target='_blank' id='coord_pages_link'>Новых уведомлений</a></span>";

		$dss_page = "<a class='hidden' href='index.php?do=show&formid=263&tab=dss_page' target='_blank' id='dss_pages_link'>Новых уведомлений</a></span>";


		if( isset( $user['ID'] ) )
		{
			$user_id = $user['ID'];
			$now = date("Y-m-d");

			$work_cal_request = dbquery("SELECT COUNT( * ) count FROM `okb_db_working_calendar` WHERE user_id=$user_id AND date = '$now'");
	    		$work_cal_request = dbquery("SELECT COUNT( * ) count FROM `okb_db_working_calendar` WHERE user_id=124 AND date = '2017-08-10'");
			$work_cal_row = mysql_fetch_object( $work_cal_request );
			$work_cal_asserted = $work_cal_row -> count ;
      			$clock .= "<span id='msg_span' data-asserted='$work_cal_asserted' data-show='0' class='hidden' onclick='window.open(\"http://mic.ru/index.php?do=show&formid=244\");'>Заполните рабочий календарь</span>";			
		}


		echo "\n<!-- Bar -->\n";
		$copy = "";
		if ($copy_state) $copy = " copybar";
		echo "<div class='bar".$copy."'><div>\n";

		$prturl = str_replace ("index.php","print.php", $pageurl);
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
	

	//$meteo_temp = mysql_result(dbquery("SELECT `meteo_temp_outer` FROM `okb_db_meteo` ORDER BY `meteo_id` DESC LIMIT 1"), 0);
//<span title='Температура на мачте осветительной'><img style='position:absolute;' src='/uses/thermometer.svg' width='16px' /> <b style='font-size:13px;margin-left:20px;font-weight:bold;color:000'>" . $meteo_temp .  "</b></span>
	if ($user!==0) {
			echo "    	<a href='".$prturl."' title='".$loc["21"]."' target='_blank'><img class='nav' src='style/print.png'></a>\n";
			echo "	<span class='popup' title='".$loc["25"]."' onClick='chClass(this,\"hpopup\",\"popup\");'><img class='nav' src='style/help.png'>\n";
			echo "	<div class='hlppopup' style='position: absolute;' onClick='window.event.cancelBubble = true;'>\n";
			show_help();
			echo "\n	</div>";
			echo "</span>\n";
			
		}

		echo $user_str."	".$clock." "."$notify $coord_page $dss_page";
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

			$xxx = dbquery("SELECT ID, NAME, BARID FROM ".$db_prefix."formgroups WHERE disabled=0 order by BARID, ORD");
			while($res = mysql_fetch_array($xxx)) $formgroups_ids[] = $res["ID"]."|".$res["NAME"]."|".$res["BARID"];

			$xxx = dbquery("SELECT ID, NAME, ID_formgroups, GROUPID, SHOWALL FROM ".$db_prefix."forms order by ID_formgroups, ORD");
			while($res = mysql_fetch_array($xxx)) {
				if (showform_check($res)) {
					$forms_ids[] = $res["ID"]."|".$res["NAME"]."|".$res["ID_formgroups"]."|".$res["GROUPID"];
					$formgroups_showed[] = $res["ID_formgroups"];
				}
			}
			$formgroups_ids_count = count($formgroups_ids);
			for ($i=0;$i < $formgroups_ids_count;$i++) {
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
						$num += 1;
					}
					
					
					if ($fg_item[0] == 16 || $fg_item[0] == 229 /* Заявки */) {
						$count = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $user['ID'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_status` = 0"), 0);
						echo "	<div id='request_events_menu' class='menuitem' " . ($count > 0 ? ' ' : '') . " onMouseOver='this.className=\"menuitemhover\";' onMouseOut='this.className=\"menuitem\";'><span style='color:red;font-weight:bold'>". ($count > 0 ? '(' . $count . ')' : "") . '</span> ' .$fg_item[1]."\n";
					} else {
						echo "	<div class='menuitem' onMouseOver='this.className=\"menuitemhover\";' onMouseOut='this.className=\"menuitem\";'>".$fg_item[1]."\n";
					}
					echo "		<br><div class='submenu'>\n";
					$groupid = "0";
					$forms_ids_count = count($forms_ids);
					for ($j=0;$j < $forms_ids_count;$j++) {
						$f_item = explode("|",$forms_ids[$j]);
						if ($f_item[2]==$fg_item[0]) {
							if ($f_item[3]!==$groupid) {
								$groupid = $f_item[3];
								if ($j!==0) echo "			<div class='hr'></div>\n";
							}
							
														
							if ($f_item[0] == 229 /* Заявки */) {
								$count = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $user['ID'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_status` = 0"), 0);
							
							echo "			<a id='request_events_all_menu' href='?do=show&formid=".$f_item[0]."'><span style='color:red;font-weight:bold'>" . ($count > 0 ? '(' . $count . ')' : "") . '</span> ' .$f_item[1]."</a>\n";
	continue;
							} else {
							//	echo "			<a href='?do=show&formid=".$f_item[0]."'>".$f_item[1]."</a>\n";
							}
							
							if ($f_item[0] == 82 /* Отдел ИТ */) {
								
								$count = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $user['ID'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'it' AND `request_status` = 0"), 0);
								
								
								echo "			<a id='request_events_it_menu' href='?do=show&formid=".$f_item[0]."'><span style='color:red;font-weight:bold'>" . ($count > 0 ? '(' . $count . ')' : "") . '</span> ' .$f_item[1]."</a>\n";
							} else if ($f_item[0] == 86 /* Отдел ИТ */) {
								
								$count = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $user['ID'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'ogi' AND `request_status` = 0"), 0);
								
								
								echo "			<a id='request_events_ogi_menu' href='?do=show&formid=".$f_item[0]."'><span style='color:red;font-weight:bold'>" . ($count > 0 ? '(' . $count . ')' : "") . '</span> ' .$f_item[1]."</a>\n";
							} else if ($f_item[0] == 237 /* Отдел кадров */) {
								
								$count = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $user['ID'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'hr' AND `request_status` = 0"), 0);
								
								
								echo "			<a id='request_events_hr_menu' href='?do=show&formid=".$f_item[0]."'><span style='color:red;font-weight:bold'>" . ($count > 0 ? '(' . $count . ')' : "") . '</span> ' .$f_item[1]."</a>\n";
							} else if ($f_item[0] == 87 /* ТМЦ */) {
								
								$count = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $user['ID'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'tmc' AND `request_status` = 0"), 0);
								
								
								echo "			<a id='request_events_tmc_menu' href='?do=show&formid=".$f_item[0]."'><span style='color:red;font-weight:bold'>" . ($count > 0 ? '(' . $count . ')' : "") . '</span> ' .$f_item[1]."</a>\n";
							} else if ($f_item[0] == 88 /* Заказы */) {
								
								$count = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $user['ID'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'zakreq' AND `request_status` = 0"), 0);
								
								
								echo "			<a id='request_events_zakreq_menu' href='?do=show&formid=".$f_item[0]."'><span style='color:red;font-weight:bold'>" . ($count > 0 ? '(' . $count . ')' : "") . '</span> ' .$f_item[1]."</a>\n";
							} else {
								echo "			<a href='?do=show&formid=".$f_item[0]."'>".$f_item[1]."</a>\n";
							}
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

// shindax

	function cons( $arg )
	{
		echo "<script>console.log('$arg')</script>";
	}

    function _debug( $arr )
    {
        $str = print_r($arr, true);
        echo "<pre>$str</pre>";
    }

// shindax

	Create_auto_BACKUP();
?>