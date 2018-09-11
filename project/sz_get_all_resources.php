<?php
	define("MAV_ERP", TRUE);
	
	header('Content-type: text/plain; charset=windows-1251');
	
	include "../config.php";
	include "../includes/database.php";
	
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

	
			$res_arr_f = array();
			
			$query = dbquery("SELECT * FROM ".$db_prefix."db_zadanres
								WHERE (DATE = '" . $_GET['date'] . "') and (SMEN = '" . $_GET['smena'] . "')
								ORDER by ORD");
								
			while($row = mysql_fetch_array($query)){
				$res_arr_f[] = $row['ID_resurs'];
			}
			
			print_r($res_arr_f);
			
			$query = dbquery("SELECT * FROM ".$db_prefix."db_shtat 
								LEFT JOIN ".$db_prefix."db_resurs ON ".$db_prefix."db_resurs.ID = ".$db_prefix."db_shtat.ID_resurs
								WHERE ((ID_resurs != '0') and ((ID_otdel = '18') or (ID_otdel = '19') or (ID_otdel = '21') or (ID_otdel = '22')))
								");


			$fruits_1 = array();
								
			while($row = mysql_fetch_assoc($query)){
				$fruits_1[$row["ID"]] = $row["NAME"];
			}

			$query = dbquery("SELECT * FROM ".$db_prefix."db_shtat
								LEFT JOIN ".$db_prefix."db_resurs ON ".$db_prefix."db_resurs.ID = ".$db_prefix."db_shtat.ID_resurs
								WHERE ((ID_resurs != '0') and (ID_otdel != '18') and (ID_otdel != '19') and (ID_otdel != '21') and (ID_otdel != '22'))");

			$fruits_2 = array();
			
			while($row = mysql_fetch_assoc($query)){
				$fruits_2[$row["ID"]] = $row["NAME"];
			}
									
			asort($fruits_1);
			asort($fruits_2);
						
			//echo "<option style='color:red;' value='0' name>--- (производство)";
			
			foreach ($fruits_1 as $key => $value) {
				if (in_array($key, $res_arr_f)) echo "<option value='".$key."' name='nam_sel_cur_krz'>".$value;
			}
			
			//echo "<option style='color:red;' value='0'>--- (остальной персонал)";
			
			foreach ($fruits_2 as $key => $value) {
				if (in_array($key, $res_arr_f)) echo "<option value='".$key."' name='nam_sel_cur_krz'>".$value;
			}	
			
	