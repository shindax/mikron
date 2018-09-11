<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 ÃËÓ¯ÌËÍÓ‚ ¿.¬.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


	function Render_datecat($item, $table_1_add, $table_1_edit, $sql_where_1_in, $sql_orderby_1 = "", $n = 0) {
		global $db_prefix, $MM_Name, $opened, $pageurl, $db_cfg;


		$sql_where_1 = "where ";
		if ($sql_where_1_in!=="") $sql_where_1 = "where (".$sql_where_1_in.") and ";
		if (($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") or ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="ltree")) {
			$sql_where_1 = $sql_where_1."(PID='0') and ";
		}

		$sql_where_dd = "";
		if (($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") or ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="ltree")) {
			$sql_where_dd = "where (PID='0')";
			if ($sql_where_1_in!=="") $sql_where_dd = $sql_where_dd." and (".$sql_where_1_in.")";
		} else {
			if ($sql_where_1_in!=="") $sql_where_dd = "where (".$sql_where_1_in.")";
		}


		$orderby = "order by ".$item["FIELD_1"];
		if ($sql_orderby_1!=="") $orderby = $sql_orderby_1;

		$MM_end = date("m")*1;
		$YY_end = date("Y")*1;
		$YY_start = $YY_end;

		$xxx = dbquery("SELECT ID, ".$item["FIELD_1"]." FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_where_dd." order by ".$item["FIELD_1"]." limit 0,1");
		if ($frow = mysql_fetch_array($xxx)) {
			$YY_start = floor($frow[$item["FIELD_1"]]/10000);
		}

		$xxx = dbquery("SELECT ID, ".$item["FIELD_1"]." FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_where_dd." order by ".$item["FIELD_1"]." desc limit 0,1");
		if ($frow = mysql_fetch_array($xxx)) {
			$YY_end = floor($frow[$item["FIELD_1"]]/10000);
			$MM_end = floor(($frow[$item["FIELD_1"]]-($YY_end*10000))/100);
		}

	   // —¿Ã¿ “¿¡À»÷¿ ///////////////////////////////////////////////////////////////
		for ($y=$YY_start;$y < $YY_end+1;$y++) {
			$oct = OCT_Link($y."y",0,true);
			echo "<tr class='cltreef'>";
			if ($table_1_add) AddTreeNull($item["DB_TABLE_1"],1);
			echo "<td colspan='".$item["COLSPAN"]."' class='Field' style='text-align: left;'><table><td width='1%'>".$oct[0]."</td><td>".$y."</td></tr></table></td>";
			if ($table_1_add) DelNull($item["DB_TABLE_1"],1);
			echo "</tr>\n";
			if ($oct[1]) {
				for ($m=1;$m < 13;$m++) {
				   if (($y<$YY_end) or ($m<=$MM_end)) {
					$sdate = $y*10000+$m*100;
					$edate = $sdate+32;
					$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_where_1." (".$item["FIELD_1"].">'".$sdate."') and (".$item["FIELD_1"]."<'".$edate."') ".$orderby);
					$oct_mm = OCT_Link($m."m".$y."y",1,mysql_num_rows($result)>0);
					echo "<tr class='cltree'>";
					if ($table_1_add) AddTreeNull($item["DB_TABLE_1"],1);
					echo "<td colspan='".$item["COLSPAN"]."' class='Field' style='text-align: left;'><table><td width='1%'>".$oct_mm[0]."</td><td>".$MM_Name[$m]."</td></tr></table></td>";
					if ($table_1_add) DelNull($item["DB_TABLE_1"],1);
					echo "</tr>";
					if ($oct_mm[1]) {
						while($row = mysql_fetch_array($result)) Render_openid_standart($row, $item, $table_1_add, $table_1_edit, $sql_orderby_1, 2, $sql_where_1_in);
					}
				   }
				}
			}
		}
	   ////////////////////////////////////////////////////////////////////////////////
	}

	function Render_yearcat($item, $table_1_add, $table_1_edit, $sql_where_1_in, $sql_orderby_1 = "", $n = 0) {
		global $db_prefix, $MM_Name, $opened, $pageurl, $db_cfg;


		$sql_where_1 = "where ";
		if ($sql_where_1_in!=="") $sql_where_1 = "where (".$sql_where_1_in.") and ";
		if (($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") or ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="ltree")) {
			$sql_where_1 = $sql_where_1."(PID='0') and ";
		}

		$sql_where_dd = "";
		if (($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") or ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="ltree")) {
			$sql_where_dd = "where (PID='0')";
			if ($sql_where_1_in!=="") $sql_where_dd = $sql_where_dd." and (".$sql_where_1_in.")";
		} else {
			if ($sql_where_1_in!=="") $sql_where_dd = "where (".$sql_where_1_in.")";
		}


		$orderby = "order by ".$item["FIELD_1"];
		if ($sql_orderby_1!=="") $orderby = $sql_orderby_1;

		$MM_end = date("m")*1;
		$YY_end = date("Y")*1;
		$YY_start = $YY_end;

		$xxx = dbquery("SELECT ID, ".$item["FIELD_1"]." FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_where_dd." order by ".$item["FIELD_1"]." limit 0,1");
		if ($frow = mysql_fetch_array($xxx)) {
			$YY_start = floor($frow[$item["FIELD_1"]]/10000);
		}

		$xxx = dbquery("SELECT ID, ".$item["FIELD_1"]." FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_where_dd." order by ".$item["FIELD_1"]." desc limit 0,1");
		if ($frow = mysql_fetch_array($xxx)) {
			$YY_end = floor($frow[$item["FIELD_1"]]/10000);
			$MM_end = floor(($frow[$item["FIELD_1"]]-($YY_end*10000))/100);
		}

	   // —¿Ã¿ “¿¡À»÷¿ ///////////////////////////////////////////////////////////////
		for ($y=$YY_start;$y < $YY_end+1;$y++) {

			$sdate = $y*10000;
			$edate = $sdate+1232;
			$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_where_1." (".$item["FIELD_1"].">'".$sdate."') and (".$item["FIELD_1"]."<'".$edate."') ".$orderby);

			$oct = OCT_Link($y."y",0,mysql_num_rows($result)>0);
			echo "<tr class='cltree'>";
			if ($table_1_add) AddTreeNull($item["DB_TABLE_1"],1);
			echo "<td colspan='".$item["COLSPAN"]."' class='Field' style='text-align: left;'><table><td width='1%'>".$oct[0]."</td><td>".$y."</td></tr></table></td>";
			if ($table_1_add) DelNull($item["DB_TABLE_1"],1);
			echo "</tr>\n";
			if ($oct[1]) {
				while($row = mysql_fetch_array($result)) Render_openid_standart($row, $item, $table_1_add, $table_1_edit, $sql_orderby_1, 1, $sql_where_1_in);
			}
		}
	   ////////////////////////////////////////////////////////////////////////////////
	}

?>