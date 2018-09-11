<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//
//////////////////////////////////////////////////////

	define("MAV_ERP", TRUE);

// ПОЕХАЛИ

	include "config.php";
	include "locale/".$lang."/lang.php";
	include "includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	//include "includes/cookie.php";
	include "includes/config.php";
	include "db_func.php";
	
	$maxnumrows = 15;

///////////////////////////////////////////////////////////////

	function decodeurl($code) {
		$text = stripslashes($code);
		$search = array("@1@", "@2@", "@3@", "@4@");
		$replace = array("=", "?", "&", ".");
		$text = str_replace($search, $replace, $text);
		return $text;
	}

	function utftxt($str) {
		return iconv($html_charset,"UTF-8",$str);
	}

	function chrtxt($str) {
		return iconv($html_charset,$db_charset,$str);
	}


	$db = $_GET['db'];
	$id = $_GET['id'];
	$field = $_GET['field'];
	$val = $_GET['value'];
	$url = $_GET['url'];

	$SLURL = decodeurl($url)."&edit_list=".$db."|".$id."|".$field."|";



///////////////////////////////////////////////////////////////


// ПОИСК
	$sdb = $db_cfg[$db."/".$field."|LIST"];

	if ($db_cfg[$sdb."|LIST_SEARCH"].""!=="") {

		$listfield = $db_cfg[$sdb."|LIST_FIELD"]."";
		$listprefix = $db_cfg[$sdb."|LIST_PREFIX"]."";
		$listfield = explode("|",$listfield);

		$find_fields = explode("|",$db_cfg[$sdb."|LIST_SEARCH"]);

		$search = trim(strip_tags(chrtxt($val)));
		$search = substr($search, 0, 64);
		if (strlen($search)<2) $search = "";
		$search = ereg_replace(" +", " ", $search);

		//echo utftxt($search)."<br>";

		if (($search!=="") && ($search!==" ")) {

			$sql = array();
			foreach($find_fields as $flx){
				$sql[] = "($flx LIKE '%{$search}%')";
			}
			$find_where = "WHERE (".implode(" OR ", $sql).")";

			// Сортировка v1
			//$orderby = "length(".$find_fields[0]."), binary(".$find_fields[0].")";

			// Сортировка v2
			//$orderby1 = "length(".implode("), length(",$find_fields).")";
			//$orderby2 = "binary(".implode("), binary(",$find_fields).")";
			//$orderby = $orderby1.", ".$orderby2;

			// Сортировка v3
			$orderby = "";
			$ox = "";
			foreach($find_fields as $flx){
				$orderby = $orderby.$ox;
				$orderby = $orderby."length($flx), binary($flx)";
				$ox = ", ";
			}

			//echo $orderby."<br>";

			$result = dbquery("SELECT * FROM ".$db_prefix.$sdb." ".$find_where." order by ".$orderby." limit 0,".$maxnumrows);
			$numrows = mysql_num_rows($result);
			while($row = mysql_fetch_array($result)) {

				$val = FVal($row,$sdb,$listfield[0]);
				for ($j=1;$j < count($listfield);$j++) {
					$val = $val.$listprefix.FVal($row,$sdb,$listfield[$j]);
				}

				//echo utftxt("<div class='hr'></div><a href='javascript:void(0);' onclick='if (confirm(\"".$loc["dbf21"]." - ".$val." ?\")) parent.location=\"".$SLURL.$row["ID"]."\";'>".$val."</a>");
				echo utftxt("<div class='hr'></div><a href='javascript:void(0);' onclick='parent.location=\"".$SLURL.$row["ID"]."\";'>".$val."</a>");

			}
			if ($numrows>=$maxnumrows) echo utftxt("<div class='hr'></div><center>- - -</center>");
		}
	}

?>