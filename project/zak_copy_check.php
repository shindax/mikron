<?php

	$zakdet_IDS = array();
	$zakdet_LIDS = array();

	function OpenID_zakdet($zakdet_ID) {
		global $db_prefix, $zakdet_IDS, $zakdet_LIDS;

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (PID = '".$zakdet_ID."') order by ORD");
		while($res = mysql_fetch_array($xxx)) {
			if ($res["LID"]=="0") $zakdet_IDS[] = $res["ID"];
			if ($res["LID"]!=="0") $zakdet_LIDS[] = $res["LID"];
			OpenID_zakdet($res["ID"]);
		}
	}

	function Check_LIDS() {
		global $zakdet_IDS, $zakdet_LIDS;

		$ret = true;
		for ($i=0;$i < count($zakdet_LIDS);$i++) {
			if (!in_array($zakdet_LIDS[$i],$zakdet_IDS)) $ret = false;
		}
		return $ret;
	}
	
	OpenID_zakdet($from_ID);

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID='".$from_ID."')");
	$from_zakdet = mysql_fetch_array($result);
	$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID='".$to_ID."')");
	$to_zakdet = mysql_fetch_array($result);

	echo "</form>";
	echo "<form action='index.php?do=show&formid=39&id=".$to_zakdet["ID_zak"]."&event' method='post'>";

	if (Check_LIDS()) {
		echo "<h3>Из ДСЕ: ".FVal($from_zakdet,"db_zakdet","ID_zak")." <span>".$from_zakdet["NAME"]." - ".$from_zakdet["OBOZ"]."</span></h3>";
		echo "<h3>В ДСЕ: ".FVal($to_zakdet,"db_zakdet","ID_zak")." <span>".$to_zakdet["NAME"]." - ".$to_zakdet["OBOZ"]."</span></h3>";
		echo "<br><br><span style='margin-left: 100px;'><button name='btn' value='chancel'>Отмена</button></span>";
		echo "<input type='hidden' name='copy_dse_to_zakdet' value='".$to_ID."'>";
		echo "<span style='margin-left: 300px;'><button name='copy_dse_from_zakdet' value='".$from_ID."'>Копировать</button></span>";
	} else {
		echo "<h3 style='color: red;'>Обнаружены ссылки на ДСЕ не входящие в копируемую ДСЕ.</h3>";
		echo "<br><br><span style='margin-left: 100px;'><button name='btn' value='chancel'>Отмена</button></span>";
	}
?>