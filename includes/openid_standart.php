<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


	function Render_openid_standart($row, $item, $table_1_add = false, $table_1_edit = false, $sql_orderby_1 = "", $n = 0, $sql_where_1_in = "") {
		global $db_prefix, $db_cfg, $render_db, $render_row, $render_options, $render_edit, $render_item, $render_oct, $render_n, $render_row_id, $render_inc;

		$openid = destripinput($item['OPENID_1']);
		$openid = explode("\n", $openid);

		$render_db = $item['DB_TABLE_1'];
		$render_row = $row;
		$render_row_id = $row['ID'];
		$render_row_id2 = $row['ID_special'];
		$render_edit = $table_1_edit;
		$render_item = $item;
		$render_n = $n;
		$render_oct = false;
		$render_inc += 1;

		
		$num_rows = 1;
		
		$openid_count = count($openid);
		for ($j=0;$j < $openid_count;++$j) {
			if ($openid[$j]=='-') $num_rows += 1;
		}
		$outdel = $table_1_add;

		$show_del = $table_1_add;
		if (($item['TID']*1==2) && ($n==0)) $show_del = false;

		echo "	<tr data-proj='".$row['ID_proj']."' data-zak='".$row['ID_zak']."' data-user-id='" . $row['ID_users'] . "' data-id='" . $row['ID'] . "'>\n";

		if ($table_1_add) AddTreeField($row,$item['DB_TABLE_1'],$num_rows);
		for ($j=0;$j < $openid_count;++$j) {
			if (($openid[$j]!=='') && ($openid[$j]!=='-') && ($openid[$j]!=='-H-')) {
				$td = explode("|",$openid[$j]);
				$render_options = str_replace('{{id}}', $render_row_id,$td[1]);
				$render_options = str_replace('{{ID_special}}',$render_row_id2,$td[1]);
				$addclass = $td[2];
				$code = $td[0];
				$ncode = str_replace('{{field}}',"<?php\nRender_field(",$code);
				$ncode = str_replace('{{octfield}}',"<?php\nRender_octfield(",$ncode);
				$ncode = str_replace('{{wfield}}',"<?php\nRender_field_where(",$ncode);
				if ($code!==$ncode) {
					$ncode = str_replace('{{id}}',"\$render_row_id",$ncode);
					$ncode = str_replace('{{ID_special}}',"\$render_row_id2",$ncode);
					$ncode = str_replace('{{/wfield}}',");\n?>",$ncode);
					$ncode = str_replace('{{/octfield}}',");\n?>",$ncode);
					$ncode = str_replace('{{/field}}',");\n?>",$ncode);
					$ncode = str_replace('{{/}}',");\n?>",$ncode);
					eval(' ?>'.$ncode.'<?php ');
				} else 
				{
					EvalCode_txt($code,$render_options,$addclass);
				}
			}
			if ($openid[$j]=='-') {
				if ($outdel) DelField($row,$item['DB_TABLE_1'],$table_1_add,$num_rows,$show_del);
				$outdel = false;
				echo "	</tr>\n";
				echo "	<tr data-user-id='" . $row['ID_users'] . "' data-id='" . $row['ID'] . "'>\n";
			}
			if ($openid[$j]=='-H-') {
				if ($outdel) DelField($row,$item['DB_TABLE_1'],$table_1_add,$num_rows,$show_del);
				$outdel = false;
				echo "	</tr>\n";
				echo "	<tr class='first'>\n";
			}
		}
		if ($outdel) DelField($row,$item["DB_TABLE_1"],$table_1_add,$num_rows,$show_del);
		echo "	</tr>\n";


		// Вывод childs здесь только по дереву DB_TABLE_1
		// 1, 2, 4, 5, 9
		if (($item["TID"]==1) or ($item["TID"]==2) or ($item["TID"]==3) or ($item["TID"]==9)) {
		if ($render_oct) {
			$sql_where_1 = "where ";
			if ($sql_where_1_in!=="") $sql_where_1 = "where (".$sql_where_1_in.") and ";
			$render_oct = false;
			$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_where_1."(PID='".$row["ID"]."') ".$sql_orderby_1);
			while($row = mysql_fetch_array($result)) Render_openid_standart($row, $item, $table_1_add, $table_1_edit, $sql_orderby_1, ($n+1), $sql_where_1_in);
		}
		}
	}


	function Render_openid_wstandart($row, $item, $table_1_add = false, $table_1_edit = false, $sql_orderby_1 = "", $n = 0, $sql_where_1_in = "") {
		global $db_prefix, $db_cfg, $render_db, $render_row, $render_options, $render_edit, $render_item, $render_oct, $render_n, $render_row_id, $render_inc;

		$render_var = 1;
		if ($item["FIELD_1"]!=="") {
			$render_var = 0;
			if ($item["FIELD_2"]=="") $render_var = 2;
			if ($row[$item["FIELD_1"]]==$item["SQL_1"]) $render_var = 1;
		}
		if ($item["FIELD_2"]!=="") {
			if ($row[$item["FIELD_2"]]==$item["SQL_2"]) $render_var = 2;
		}

		if ($render_var == 1) {
			$openid = destripinput($item["OPENID_1"]);
		}
		if ($render_var == 2) {
			$openid = destripinput($item["OPENID_2"]);
		}
		$openid = explode("\n", $openid);

		$render_db = $item["DB_TABLE_1"];
		$render_row = $row;
		$render_row_id = $row["ID"];
		$render_edit = $table_1_edit;
		$render_item = $item;
		$render_n = $n;
		$render_oct = false;
		$render_inc += 1;

		$num_rows = 1;
		$openid_count = count($openid);
		for ($j=0;$j < $openid_count;$j++) {
			if ($openid[$j]=="-") $num_rows += 1;
		}
		$outdel = $table_1_add;

		$show_del = $table_1_add;
		if (($item["TID"]*1==2) && ($n==0)) $show_del = false;

		echo "	<tr>\n";
		if ($table_1_add) AddTreeField($row,$item["DB_TABLE_1"],$num_rows);
		$openid_count = count($openid);

		for ($j=0;$j < $openid_count;$j++) {
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
				if ($outdel) DelField($row,$item["DB_TABLE_1"],$table_1_add,$num_rows,$show_del);
				$outdel = false;
				echo "	</tr>\n";
				echo "	<tr>\n";
			}
			if ($openid[$j]=="-H-") {
				if ($outdel) DelField($row,$item["DB_TABLE_1"],$table_1_add,$num_rows,$show_del);
				$outdel = false;
				echo "	</tr>\n";
				echo "	<tr class='first'>\n";
			}
		}
		if ($outdel) DelField($row,$item["DB_TABLE_1"],$table_1_add,$num_rows,$show_del);
		echo "	</tr>\n";


		// Вывод childs
		if ($item["TID"]==8) {
		if ($render_oct) {
			$sql_where_1 = "where ";
			if ($sql_where_1_in!=="") $sql_where_1 = "where (".$sql_where_1_in.") and ";
			$render_oct = false;
			$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_where_1."(PID='".$row["ID"]."') ".$sql_orderby_1);
			while($row = mysql_fetch_array($result)) Render_openid_wstandart($row, $item, $table_1_add, $table_1_edit, $sql_orderby_1, ($n+1), $sql_where_1_in);
		}
		}
	}


?>