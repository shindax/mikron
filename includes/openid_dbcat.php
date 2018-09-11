<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


	// Render для БД каталога
	function Render_openid_dbcat($row, $item, $table_1_edit = false, $sql_orderby_1 = "", $n = 0, $sql_where_1_in = "", $table_2_add = false, $table_2_edit = false, $sql_where_2_in = "", $sql_orderby_2 = "") {
		global $db_prefix, $db_cfg, $render_db, $render_row, $render_options, $render_edit, $render_item, $render_oct, $render_n, $render_row_id, $render_inc;

		$openid = destripinput($item["OPENID_1"]);
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
		for ($j=0;$j < count($openid);$j++) {
			if ($openid[$j]=="-") $num_rows += 1;
		}
		$outdel = $table_2_add;

		$show_del = $table_2_add;
		$doadd = $table_2_add;

		if ($item["FIELD_1"].""!=="") {
			if ($row[$item["FIELD_1"]]!==$item["SQL_1"]) $doadd = false;
		}

		echo "	<tr class='cltree'>\n";
		if ($table_2_add) {
			if ($doadd) AddCatField($item["DB_TABLE_2"],$item["FIELD_2"],$row["ID"],$num_rows);
			if (!$doadd) AddCatNull($item["DB_TABLE_2"],$num_rows);
		}
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
				if ($outdel) DelNull($item["DB_TABLE_2"],$num_rows);
				$outdel = false;
				echo "	</tr>\n";
				echo "	<tr>\n";
			}
			if ($openid[$j]=="-H-") {
				if ($outdel) DelNull($item["DB_TABLE_2"],$num_rows);
				$outdel = false;
				echo "	</tr>\n";
				echo "	<tr class='first'>\n";
			}
		}
		if ($outdel) DelNull($item["DB_TABLE_2"],$num_rows);
		echo "	</tr>\n";


		// Вывод childs здесь только по дереву DB_TABLE_1
		if ($item["TID"]==6) {
		if ($render_oct) {
			if (substr_count($item["SQL_2"], "{desc}")==0) {
			$sql_where_1 = "where ";
			if ($sql_where_1_in!=="") $sql_where_1 = "where (".$sql_where_1_in.") and ";
			$render_oct = false;
			if ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") {
				$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_where_1."(PID='".$row["ID"]."') ".$sql_orderby_1);
				while($rowch = mysql_fetch_array($result)) Render_openid_dbcat($rowch, $item, $table_1_edit, $sql_orderby_1, ($n+1), $sql_where_1_in, $table_2_add, $table_2_edit, $sql_where_2_in, $sql_orderby_2);
			}
			}

			$sql_where_2 = "where ";
			if ($sql_where_2_in!=="") $sql_where_2 = "where (".$sql_where_2_in.") and ";
			if ($db_cfg[$item["DB_TABLE_2"]."|TYPE"]=="tree") $sql_where_2 = $sql_where_2."(PID='0') and ";
			$render_oct = false;
			$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_2"]." ".$sql_where_2."(".$item["FIELD_2"]."='".$row["ID"]."') ".$sql_orderby_2);
			while($rowch = mysql_fetch_array($result)) Render_openid_dbcatch($rowch, $item, ($n+1), $table_2_add, $table_2_edit, $sql_where_2_in, $sql_orderby_2);

			if (substr_count($item["SQL_2"], "{desc}")>0) {
			$sql_where_1 = "where ";
			if ($sql_where_1_in!=="") $sql_where_1 = "where (".$sql_where_1_in.") and ";
			$render_oct = false;
			if ($db_cfg[$item["DB_TABLE_1"]."|TYPE"]=="tree") {
				$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_1"]." ".$sql_where_1."(PID='".$row["ID"]."') ".$sql_orderby_1);
				while($rowch = mysql_fetch_array($result)) Render_openid_dbcat($rowch, $item, $table_1_edit, $sql_orderby_1, ($n+1), $sql_where_1_in, $table_2_add, $table_2_edit, $sql_where_2_in, $sql_orderby_2);
			}
			}
		}
		}
	}

	function Render_openid_dbcatch($row, $item,  $n = 0, $table_2_add = false, $table_2_edit = false, $sql_where_2_in = "", $sql_orderby_2 = "") {
		global $db_prefix, $db_cfg, $render_db, $render_row, $render_options, $render_edit, $render_item, $render_oct, $render_n, $render_row_id, $render_inc;

		$openid = destripinput($item["OPENID_2"]);
		$openid = explode("\n", $openid);

		$render_db = $item["DB_TABLE_2"];
		$render_row = $row;
		$render_row_id = $row["ID"];
		$render_edit = $table_2_edit;
		$render_item = $item;
		$render_n = $n;
		$render_oct = false;
		$render_inc = $render_inc + 1;

		$num_rows = 1;
		for ($j=0;$j < count($openid);$j++) {
			if ($openid[$j]=="-") $num_rows = $num_rows + 1;
		}
		$outdel = $table_2_add;

		$show_del = $table_2_add;

		echo "	<tr>\n";

		//if ($table_2_add) AddCatNull($item["DB_TABLE_2"],$num_rows);
		$dotreeadd = false;
		if ($db_cfg[$item["DB_TABLE_2"]."|TYPE"]=="tree") $dotreeadd = true;
		if ($db_cfg[$item["DB_TABLE_2"]."|TYPE"]=="ltree") $dotreeadd = true;
		if ($table_2_add) {
			if ($dotreeadd) AddTreeField($row,$item["DB_TABLE_2"],$num_rows);
			if (!$dotreeadd) AddCatNull($item["DB_TABLE_2"],$num_rows);
		}
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
				if ($outdel) DelField($row,$item["DB_TABLE_2"],$table_2_add,$num_rows,$show_del);
				$outdel = false;
				echo "	</tr>\n";
				echo "	<tr>\n";
			}
			if ($openid[$j]=="-H-") {
				if ($outdel) DelField($row,$item["DB_TABLE_2"],$table_2_add,$num_rows,$show_del);
				$outdel = false;
				echo "	</tr>\n";
				echo "	<tr class='first'>\n";
			}
		}
		if ($outdel) DelField($row,$item["DB_TABLE_2"],$table_2_add,$num_rows,$show_del);
		echo "	</tr>\n";


		// Вывод childs здесь только по дереву DB_TABLE_1
		if ($item["TID"]==6) {
		if ($render_oct) {
			$sql_where_2 = "where ";
			if ($sql_where_2_in!=="") $sql_where_2 = "where (".$sql_where_2_in.") and ";
			$render_oct = false;
			$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_2"]." ".$sql_where_2."(PID='".$row["ID"]."') ".$sql_orderby_2);
			while($row = mysql_fetch_array($result)) Render_openid_dbcatch($row, $item,  ($n+1), $table_2_add, $table_2_edit, $sql_where_2_in, $sql_orderby_2);
		}
		}
	}




	// Render для каталога во многих БД
	function Render_openid_dbxcat($row, $item, $table_1_edit = false, $sql_orderby_1 = "", $n = 0, $sql_where_1_in = "", $table_2_add = false, $table_2_edit = false, $sql_where_2_in = "", $sql_orderby_2 = "", $dbnum = 0) {
		global $db_prefix, $db_cfg, $render_db, $render_row, $render_options, $render_edit, $render_item, $render_oct, $render_n, $render_row_id, $render_inc;

		$openid = destripinput($item["OPENID_1"]);
		$openid = explode("\n", $openid);


		$db_next = "";
		$db_field = "";
		$db_tables = explode("|",$item["DB_TABLE_1"]);
		$db_count = count($db_tables);
		$db_table = $db_tables[0];
		if ($dbnum>0) {
			$db_table = explode("/",$db_tables[$dbnum]);
			$db_table = $db_table[0];
		}
		if ($dbnum<$db_count-1) {
			$db_next = explode("/",$db_tables[$dbnum+1]);
			$db_field = $db_next[1];
			$db_next = $db_next[0];
		}

		$render_db = $db_table;
		$render_row = $row;
		$render_row_id = $row["ID"];
		$render_edit = $table_1_edit;
		$render_item = $item;
		$render_n = $n;
		$render_oct = false;
		$render_inc = $render_inc + 1;

		$num_rows = 1;
		$outdel = $table_2_add;

		$show_del = $table_2_add;
		$doadd = false;

		if ($item["FIELD_1"].""==$db_table) $doadd = $table_2_add;

		echo "	<tr class='cltree'>\n";
		if ($table_2_add) {
			if ($doadd) AddCatField($item["DB_TABLE_2"],$item["FIELD_2"],$row["ID"],$num_rows);
			if (!$doadd) AddCatNull($item["DB_TABLE_2"],$num_rows);
		}
		$j = $dbnum;
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
		if ($outdel) DelNull($item["DB_TABLE_2"],$num_rows);
		echo "	</tr>\n";


		// Вывод childs по всем входящим
		if ($item["TID"]==11) {
		if ($render_oct) {

			// Входящие этого каталога
				if ($db_cfg[$render_db."|TYPE"]=="tree") {
					$sql_where_1 = "where ";
					if ($sql_where_1_in!=="") $sql_where_1 = "where (".$sql_where_1_in.") and ";
					$render_oct = false;
					$result = dbquery("SELECT * FROM ".$db_prefix.$render_db." ".$sql_where_1."(PID='".$row["ID"]."') ".$sql_orderby_1);
					while($rowch = mysql_fetch_array($result)) Render_openid_dbxcat($rowch, $item, $table_1_edit, $sql_orderby_1, ($n+1), $sql_where_1_in, $table_2_add, $table_2_edit, $sql_where_2_in, $sql_orderby_2, $dbnum);
				}

			// Входящие след. каталога
				if ($db_field!=="") { 
					$render_oct = false;

					$pidand = "";
					if (($db_cfg[$db_next."|TYPE"]=="tree") || ($db_cfg[$db_next."|TYPE"]=="ltree")) {
						$pidand = "and (PID='0')";
					}

					$result = dbquery("SELECT * FROM ".$db_prefix.$db_next." WHERE (".$db_field."='".$row["ID"]."') ".$pidand." ".$sql_orderby_1);
					while($rowch = mysql_fetch_array($result)) Render_openid_dbxcat($rowch, $item, $table_1_edit, $sql_orderby_1, ($n+1), $sql_where_1_in, $table_2_add, $table_2_edit, $sql_where_2_in, $sql_orderby_2, ($dbnum+1));
				}

			// Входящие DB2

			$sql_where_2 = "where ";
			if ($sql_where_2_in!=="") $sql_where_2 = "where (".$sql_where_2_in.") and ";
			$render_oct = false;
			$result = dbquery("SELECT * FROM ".$db_prefix.$item["DB_TABLE_2"]." ".$sql_where_2."(".$item["FIELD_2"]."='".$row["ID"]."') ".$sql_orderby_2);
			while($rowch = mysql_fetch_array($result)) Render_openid_dbcatch($rowch, $item, ($n+1), $table_2_add, $table_2_edit, $sql_where_2_in, $sql_orderby_2);
		}
		}
	}


// Доделать добавление ltree в ДБ2
?>