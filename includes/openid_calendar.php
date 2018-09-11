<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }

	function Render_calc_calendar_render_data($item) {
		global $db_cfg, $db_prefix, $render_month, $render_data;



	   // Рассчёт прямых вхождений
	   /////////////////////////////////////////

		$my = explode(".",$render_month);
		$firstday = date("d",mktime(0, 0, 0, $my[0], 1, $my[1]))*1;
		$lastday = date("d",mktime(0, 0, 0, $my[0]+1, 0, $my[1]))*1;

		$cfgstr = explode("|",$item["SQL_1"]);

		if (count($cfgstr)>3) {
		   for ($d=$firstday;$d <= $lastday;$d++) {
			$xdate = $my[1]*10000+$my[0]*100+$d;
			$result = dbquery("SELECT * FROM ".$db_prefix.$cfgstr[0]." where (".$cfgstr[2]."='".$xdate."') ");
			while($row = mysql_fetch_array($result)) {

				$coef = 1;


			     // v1 умножение на коэффициент $row[$cfgstr[5]]
			     ///////////////////////////////////////////////////////////////////////////

				if ($cfgstr[4]*1==1) {
					$coef = $row[$cfgstr[5]]*1;
				}




				$render_data[$row[$cfgstr[1]]."d".$d] = $row[$cfgstr[3]]*$coef+$render_data[$row[$cfgstr[1]]."d".$d]*1;
				$render_data[$d."s"] = $render_data[$d."s"]*1 + $row[$cfgstr[3]]*$coef;
				$render_data["summ"] = $render_data["summ"]*1 + $row[$cfgstr[3]]*$coef;

				if ($render_data[$row["ID"]."d".$d]*1==0) $render_data[$row["ID"]."d".$d] = "";
			}
			if ($render_data[$d."s"]*1==0) $render_data[$d."s"] = "";
		   }
		}

		if ($render_data["summ"]*1==0) $render_data["summ"] = "";




	   // Рассчёт суммирование по дереву если дерево
	   /////////////////////////////////////////


		function FFCalc($row, $item, $cfgstr, $my, $firstday, $lastday) {
			global $db_cfg, $db_prefix, $render_data;

			$result = dbquery("SELECT ID, PID".$TF." FROM ".$db_prefix.$item["DB_TABLE_1"]." where (PID='".$row["ID"]."') ");
			while($rowx = mysql_fetch_array($result)) {
				FFCalc($rowx, $item, $cfgstr, $my, $firstday, $lastday);
				for ($d=$firstday;$d <= $lastday;$d++) {
					$xdate = $my[1]*10000+$my[0]*100+$d;
					$render_data[$row["ID"]."d".$d] = $render_data[$row["ID"]."d".$d]*1 + $render_data[$rowx["ID"]."d".$d]*1;
					if ($render_data[$row["ID"]."d".$d]*1==0) $render_data[$row["ID"]."d".$d] = "";
				}
			}
			$summ = 0;
			for ($d=$firstday;$d <= $lastday;$d++) {
				$xdate = $my[1]*10000+$my[0]*100+$d;
				$summ = $summ + $render_data[$row["ID"]."d".$d]*1;
			}
			if ($summ==0) $summ = "";
			$render_data[$row["ID"]."i"] = $summ;
		}




	     if (count($cfgstr)>3) {
	     if  (($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") or ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="ltree"))  {
		$result = dbquery("SELECT ID, PID".$TF." FROM ".$db_prefix.$item["DB_TABLE_1"]." where (PID='0') ");
		while($row = mysql_fetch_array($result)) {
			FFCalc($row, $item, $cfgstr, $my, $firstday, $lastday);
		}
	     }
	     }

	}

	function Render_calendar_itog($item) {
		global $loc, $render_month, $render_data, $notrowurl;

		$my = explode(".",$render_month);
		$firstday = date("d",mktime(0, 0, 0, $my[0], 1, $my[1]))*1;
		$lastday = date("d",mktime(0, 0, 0, $my[0]+1, 0, $my[1]))*1;

		echo "	<tr class='cltreef brow'>\n";
		echo "		<td class='Field' colspan='".$item["COLSPAN"]."' style='text-align: right;'>".$loc["itog"].":</td>\n";
		$ddm = $my[1]*10000+$my[0]*100;
		for ($d=$firstday;$d <= $lastday;$d++) {
			$ddd = $ddm+$d;
			$stl = "HND";
			if ($_GET["row"]=="0x".$ddd) $stl = "HNDHL";
			echo "		<td class='Field AR MIN ".$stl."'  onclick='document.location.href=\"".$notrowurl."&row=0x".$ddd."&event\";'>".FormatRealCell($render_data[$d."s"])."</td>\n";
		}
		echo "		<td class='Field AR HND' onclick='document.location.href=\"".$notrowurl."&row=allx".$ddm."&event\";'>".FormatRealCell($render_data["summ"])."</td>\n";
		echo "	</tr>\n";
	}

	function Render_openid_calendar($row, $item, $table_1_edit = false, $sql_where_1_in = "", $sql_orderby_1 = "", $n = 0) {
		global $db_prefix, $notrowurl, $db_cfg, $render_db, $render_row, $render_options, $render_edit, $render_item, $render_oct, $render_n, $render_row_id, $render_inc, $render_month, $render_data;

		$openid = destripinput($item["OPENID_1"]);
		$openid = explode("\n", $openid);

		$render_db = $item["DB_TABLE_1"];
		$render_row = $row;
		$render_row_id = $row["ID"];
		$render_edit = $table_1_edit;
		$render_item = $item;
		$render_n = $n;
		$render_oct = false;
		$render_inc = $render_inc + 1;

		$num_rows = 1;
		for ($j=0;$j < count($openid);$j++) {
			if ($openid[$j]=="-") $num_rows = $num_rows + 1;
		}

		$my = explode(".",$render_month);
		$firstday = date("d",mktime(0, 0, 0, $my[0], 1, $my[1]))*1;
		$lastday = date("d",mktime(0, 0, 0, $my[0]+1, 0, $my[1]))*1;

		$outdates = true;
		$rowspan = "";
		if ($num_rows>1) $rowspan = "rowspan='".$num_rows."'";

		$trcl = "";
		$links = true;
		if ($item["FIELD_1"].""!=="") {
			if ($row[$item["FIELD_1"]]*1==0) {
				$links = false;
				$trcl = "class='cltree brow'";
			}
		}
		$e = "";
		if ($links) $e = "e"; 

		echo "	<tr ".$trcl.">\n";
		for ($j=0;$j < count($openid);$j++) {
			if (($openid[$j]!=="") && ($openid[$j]!=="-") && ($openid[$j]!=="-H-")) {
				$td = explode("|",$openid[$j]);
				$render_options = str_replace("{{id}}",$render_row_id,$td[1]);
				$addclass = $td[2];
				$code = $td[0];
				$ncode = str_replace("{{field}}","<?php\nRender_field(",$code);
				$ncode = str_replace("{{octfield}}","<?php\nRender_octfield(",$ncode);
				$ncode = str_replace("{{wfield}}","<?php\nRender_field_where(",$ncode);
				if ($code!==$ncode) {
					$ncode = str_replace("{{id}}","\$render_row_id",$ncode);
					$ncode = str_replace("{{/wfield}}",");\n?>",$ncode);
					$ncode = str_replace("{{/octfield}}",");\n?>",$ncode);
					$ncode = str_replace("{{/field}}",");\n?>",$ncode);
					$ncode = str_replace("{{/}}",");\n?>",$ncode);
					eval(" ?>".$ncode."<?php ");
				} else {
					EvalCode_txt($code,$render_options,$addclass);
				}
			}
			if ($openid[$j]=="-") {
				if ($outdates) {
					$ddm = $my[1]*10000+$my[0]*100;
					for ($d=$firstday;$d <= $lastday;$d++) {
						$ddd = $ddm+$d;

						$class = "Field AR MIN HND".$e;
						if ($_GET["row"]==$row["ID"]."x".$ddd) $class = "Field AR MIN HNDHL".$e;

						echo "<td class = '".$class."' ".$rowspan." onclick='document.location.href=\"".$notrowurl."&row=".$row["ID"]."x".$ddd."&event\";'>".FormatRealCell($render_data[$row["ID"]."d".$d])."</td>";
					}
					echo "<td class='Field AR HND' ".$rowspan." onclick='document.location.href=\"".$notrowurl."&row=".$row["ID"]."x".$ddm."xm&event\";'><b>".FormatRealCell($render_data[$row["ID"]."i"])."</b></td>";
				}
				$outdates = false;
				echo "	</tr>\n";
				echo "	<tr>\n";
			}
		}
		if ($outdates) {
			$ddm = $my[1]*10000+$my[0]*100;
			for ($d=$firstday;$d <= $lastday;$d++) {
				$ddd = $ddm+$d;

				$class = "Field AR MIN HND".$e;
				if ($_GET["row"]==$row["ID"]."x".$ddd) $class = "Field AR MIN HNDHL".$e;

				echo "<td class = '".$class."' ".$rowspan." onclick='document.location.href=\"".$notrowurl."&row=".$row["ID"]."x".$ddd."&event\";'>".FormatRealCell($render_data[$row["ID"]."d".$d])."</td>";
			}
			echo "<td class='Field AR HND' ".$rowspan." onclick='document.location.href=\"".$notrowurl."&row=".$row["ID"]."x".$ddm."xm&event\";'><b>".FormatRealCell($render_data[$row["ID"]."i"])."</b></td>";
		}
		echo "	</tr>\n";

		if ($render_oct) {
			$sql_where_1 = "where ";
			if ($sql_where_1_in!=="") $sql_where_1 = "where (".$sql_where_1_in.") and ";
			$render_oct = false;
			$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_where_1."(PID='".$row["ID"]."') ".$sql_orderby_1);
			while($row = mysql_fetch_array($result)) Render_openid_calendar($row, $item, $table_1_edit, $sql_where_1_in, $sql_orderby_1, $n+1);
		}
	}



?>