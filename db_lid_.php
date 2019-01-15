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
		return iconv("windows-1251","UTF-8",$str);
	}

	$db = $_GET['db'];
	$id = $_GET['id'];
	$val = $_GET['value'];
	$url = $_GET['url'];

	$LIDURL = decodeurl($url)."&edit_lid=".$db."|".$id."|";



///////////////////////////////////////////////////////////////


function OpenDisID_x ($item,$db) {
	global $db_prefix, $disabled;

	$disabled[] = $item["ID"];

	$result = dbquery("SELECT * FROM ".$db_prefix.$db." where (ID='".$item["PID"]."')");
	while($res = mysql_fetch_array($result)) OpenDisID_x ($res,$db);

	$result = dbquery("SELECT * FROM ".$db_prefix.$db." where (LID='".$item["ID"]."')");
	while($res = mysql_fetch_array($result)) OpenDisID_x ($res,$db);
}



			$res = dbquery("SELECT * FROM ".$db_prefix.$db." where (ID='".$id."')");
			$res = mysql_fetch_array($res);
			$disabled = Array();
			OpenDisID_x ($res,$db,$disabled);

// ПОИСК

	if ( $db_cfg[$db."|LID_SEARCH"].""!=="") 
	{

		$listfield = $db_cfg[$db."|LID_FIELD"];
		$listfield = explode("|",$listfield);

		$lid_master = "";
		if ($db_cfg[$db."|LID_MASTER"].""!=="") {
			$thislid = dbquery("SELECT * FROM ".$db_prefix.$db." where (ID='".$id."')");
			if ($thislid = mysql_fetch_array($thislid)) {
				$lid_master = " and (".$db_cfg[$db."|LID_MASTER"]."='".$thislid[$db_cfg[$db."|LID_MASTER"]]."')";
			}
		}

		$find_fields = explode("|",$db_cfg[$db."|LID_SEARCH"]);

		$search = trim(strip_tags($val));
		$search = substr($search, 0, 64);
		if (strlen($search)<2) $search = "";
	//	$search = preg_replace(" +", " ", $search);

		//echo "<br>".utftxt($search)."<br>";

		if (($search!=="") && ($search!==" ")) 
		{

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
				$orderby .= $ox;
				$orderby .= "length($flx), binary($flx)";
				$ox = ", ";
			}

			//echo $orderby."<br>";
			
			if ($db == 'db_zakdet') {
				$result = dbquery("SELECT * FROM ".$db_prefix.$db." ".$find_where.$lid_master." group by NAME order by NAME ASC limit 0,".$maxnumrows);
			} else {
				
				$result = dbquery("SELECT * FROM ".$db_prefix.$db." ".$find_where.$lid_master." order by ".$orderby." limit 0,".$maxnumrows);
			}
			
			$numrows = mysql_num_rows($result);
			while($row = mysql_fetch_array($result)) 
			{
				if (!in_array($row["ID"],$disabled)) 
				{

					$val = FVal($row,$db,$listfield[0]);
					$listfield_count = count($listfield);
					for ($j=1;$j < $listfield_count;$j++) {
						$val .= " - ".FVal($row,$db,$listfield[$j]);
					}

					$val = str_replace(array(')', '(', '\'', '"', '&quot;'), '', $val);
					$str= "<div class='hr'></div><a href='javascript:void(0);' onclick='if (confirm(\"".$loc["dbf21"]." - " .$val . "  ?\")) parent.location=\"".$LIDURL.$row["ID"]."\";'>".$val."</a>";
					 
					echo utftxt( $str );
		
				}
			}

			if ($numrows>=$maxnumrows) 
				 echo utftxt("<div class='hr'></div><center>- - -</center>");
		}
	}

?>