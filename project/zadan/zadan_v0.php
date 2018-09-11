<style>
	table.tbl tr.highlite {
		background: #cbdef4;
	}
	table.tbl tr.highlite2 {
		background: #ebf3fe;
	}
	a.acl {
		font-size: 11pt;
		text-decoration: none;
	}
	table.tbl tr.f0 {
		background: #fff;
	}
	table.tbl tr.f1 {
		background: #ddffdd;
	}
	table.tbl tr.f2 {
		background: #88ff88;
	}
	table.tbl tr.pr td {
		border-color: #009900;
		border-width: 2px;
	}
	table.tbl tr.pr td.xx {
		background: right bottom URL(project/zadan/xbg.png) no-repeat;
	}
	table.tbl td.brak {
		background: right bottom URL(project/img/brak.png) no-repeat;
	}
</style>

<?php

$ID_resurs = $_GET["p2"];
$smena = $_GET["p1"];
if (($smena!=="1") && ($smena!=="2") && ($smena!=="3")) $smena = "1";
$pdate = $_GET["p0"]*1;
$date = IntToDate($pdate);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$pageurl_2 = "index.php?do=show&formid=65&p0=".$pdate."&p1=".$smena."&p2=".$ID_resurs;
		$back_url = "index.php?do=show&formid=64&p0=".$pdate."&p1=".$smena;


	$editing = false;
	$modering = false;
	$editingplan = false;

	$today = explode(".",$today_0);
	$today = $today[2]*10000+$today[1]*100+$today[0];

	$real_today = $today;

	if ($pdate>=$today) $editingplan = true;

	if ($pdate<$today) $modering = true;
	if ($pdate==$today) $modering = true;

	$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
	$today_m=date("d.m.Y",$theday-(8*86400));
	$today = explode(".",$today_m);
	$today = $today[2]*10000+$today[1]*100+$today[0];

	if ($pdate>$today) $editing = true;
	if ($pdate==$today) $editing = true;

	if ($pdate<$today) $modering = false;

	if (db_check("db_zadan","MEGA_REDACTOR")) $editing = true;
	if (db_check("db_zadan","MEGA_REDACTOR")) $editingplan = true;
	if (db_check("db_zadan","MEGA_REDACTOR")) $modering = true;


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$resurs = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID = '".$ID_resurs."')");
if (($resurs = mysql_fetch_array($resurs)) && (isset($_GET["p0"])) && (isset($_GET["p1"]))){

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function OpenZakID($item) {
		global $pageurl, $db_prefix, $zak, $opened;


		$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak = '".$item["ID"]."') and (PID = '0')");
		$first_dse = mysql_fetch_array($result);
		
	   // Цвет
		echo "<tr class='highlite' style='height: 30px;'>";

	   // №
		echo "<td class='Field'></td>";

	   // Заказ / ДСЕ / Операция
		$pic = "";
		if (substr_count($opened, "|z".$item["ID"]."|")==0) $pic = "<a href='".$pageurl."&open=z".$item["ID"]."'><img style='margin: 3px 5px 0px 0px;' src='uses/collapse.png'></a> ";
		if (substr_count($opened, "|z".$item["ID"]."|")>0)  $pic = "<a href='".$pageurl."&close=z".$item["ID"]."'><img style='margin: 3px 5px 0px 0px;' src='uses/expand.png'></a> ";

		echo "<td class='Field' style='text-align: left;' colspan='3'>".$pic."<b style='margin-right: 10px;'>".FVal($item,"db_zak","TID")." ".$item["NAME"]."</b> ".$item["DSE_NAME"]."</td>";

	   // Действие
		echo "<td class='Field'></td>";

		echo "</tr>\n";

	   // Вывод ДСЕ
		if (substr_count($opened, "|z".$item["ID"]."|")>0) {

			$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zakdet = '".$first_dse["ID"]."') and (STATE = '0') and (CHANCEL = '0') order by ORD");
			while($res = mysql_fetch_array($result)) {
				OpenOperitemsID($res,1);
			}
			$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak = '".$item["ID"]."') and (PID = '".$first_dse["ID"]."')");
			while($res = mysql_fetch_array($result)) {
				OpenIzdID($res,1);
			}
		}
	}

	function OpenIzdID($item,$n) {
		global $pageurl, $db_prefix, $izd, $opened;

		
	   // Цвет
		echo "<tr class='highlite2'>";

	   // №
		echo "<td class='Field'></td>";

	   // Заказ / ДСЕ / Операция
		$ml = $n*10;
		$pic = "<img style='margin: 3px 5px 0px ".$ml."px;' src='uses/expand.png'> ";
		$txt = "";
		for ($i=0;$i < $n;$i++) $txt = $txt.".. / ";
		echo "<td class='Field' style='text-align: left;'>".$pic." <b>".$txt.$item["OBOZ"]."</b> ".$item["NAME"]."</td>";

	   // Оборудование
		echo "<td class='Field'></td>";

	   // На заказ
		echo "<td class='Field'></td>";

	   // Действие
		echo "<td class='Field'></td>";

		echo "</tr>\n";

	   // Вывод заданий и child

			$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zakdet = '".$item["ID"]."') and (STATE = '0') and (CHANCEL = '0') order by ORD");
			while($res = mysql_fetch_array($result)) {
				OpenOperitemsID($res,$n+1);
			}
			$xx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (PID='".$item["ID"]."') and (LID = '0') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				OpenIzdID($res,$n+1);
			}
	}

	function xxOpenOperitemsID($item,$n) {
	}

	function OpenOperitemsID($item,$n) {
		global $pageurl, $db_prefix, $dboper, $dbpark, $prioritet;
		
	   // Цвет

		$cl = "f0";
		if ($item["NORM_FACT"]*1>0) $cl = "f1";
		if ($item["STATE"]*1>0) $cl = "f2";
		$cl2 = "";
		if (in_array($item["ID_oper"],$prioritet)) $cl2 = " pr";
		echo "<tr class='$cl$cl2'>";

	   // №
		echo "<td class='Field'>".$item["ORD"]."</td>";

	   // Заказ / ДСЕ / Операция
		$ml = $n*10+10;
		echo "<td class=\"Field\" style = \"text-align:left; padding-left: ".$ml."px;\"><b>".$dboper[$item["ID_oper"]]."</b> <i>".$item["MORE"]."</i></td>";

	   // Оборудование
		echo "<td class='Field'>".$dbpark[$item["ID_park"]]."</td>";

	   // На заказ
		$rcount = $item["NUM_ZAK"]*1 - $item["NUM_ZADEL"]*1;
		if ($item["BRAK"]*1==1) $rcount = $item["NUM_ZAK"]*1;
		$ost = 0;
		if ($item["NORM_ZAK"]>0) $ost = $rcount*(($item["NORM_ZAK"]-$item["NORM_FACT"])/$item["NORM_ZAK"]);
		$ost = number_format( $ost, 0, '.', ' ');
		$brak = "";
		if ($item["BRAK"]*1==1) $brak = " brak";
		echo "<td class='Field".$brak."'><b>".($item["NORM_ZAK"]-$item["NORM_FACT"])." (".$ost.")</b><br>".$item["NORM_ZAK"]." (".$rcount.")</td>";

	   // Действие
		echo "<td class='Field xx'>";
		if (db_adcheck("db_zadan")) {
			if ($item["STATE"]*1==0) echo "<input type='checkbox' name='zak_zad[]' value='".$item["ID"]."'>";
		}
		echo "</td>";

		echo "</tr>\n";
	}

	function OpenLastID($i) {
		global $pageurl, $db_prefix, $editing;

		$item = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$i."')");
		$item = mysql_fetch_array($item);

	   // Цвет
		echo "<tr>";

	   // Заказ / ДСЕ
		$izd = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$item["ID_zakdet"]."')");
		$izd = mysql_fetch_array($izd);
		$zak = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID = '".$izd["ID_zak"]."')");
		$zak = mysql_fetch_array($zak);
		//$DSE = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak = '".$izd["ID_zak"]."') and (PID = '0')");
		//$DSE = mysql_fetch_array($DSE);

		echo "<td class='Field' style='text-align: left;'><b style='margin-right: 10px;'>".FVal($zak,"db_zak","TID")." ".$zak["NAME"]."</b> ".$zak["DSE_NAME"]." / ".$izd["OBOZ"]." ".$izd["NAME"]."</td>";

	   // №
		Field($item,"db_operitems","ORD",false,"","","");

	   // Операция
		Field($item,"db_operitems","ID_oper",false,"","","");

	   // Оборудование
		Field($item,"db_operitems","ID_park",false,"","","");

	   // На заказ
		Field($item,"db_operitems","NORM_ZAK",false,"","","");

	   // Действие
		echo "<td class='Field'>";
		if (db_adcheck("db_zadan")) echo "<input type='checkbox' name='zak_zad[]' value='".$i."'>";
		echo "</td>";

		echo "</tr>\n";
	}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	echo "</form>\n";

	echo "<div class='links'><a href='".$back_url."'>Назад</a></div><br><br>";

	echo "<form id='form1x' method='post' action='".$back_url."'>";


   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //
   // РЕСУРС И ДАТА
   //
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	echo "<table style='width: 100%; padding: 0px;' cellpadding='0' cellspacing='0'><tr><td style='text-align: left;'>\n";
		echo "<h2>".$resurs["NAME"]."<span><br>";

		$result = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID_resurs = '".$ID_resurs."')");
		while($shtat = mysql_fetch_array($result)) {
			echo "<br>".FVal($shtat,"db_shtat","ID_special")." ".FVal($shtat,"db_shtat","ID_speclvl");
		}

		echo "</span></h2>";

	echo "</td><td style='text-align: right;'>";
		echo "<div class='links'>";
		echo $smena." смена ".$date;
		echo "<br><br>";
		echo "<input type='submit' value='Добавить выделенные'>";
		echo "<input type='hidden' name='add_zadan_to_resurs' value='".$ID_resurs."'>";
		echo "</div>";
	echo "</td></tr></table>";

   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //
   // БЛОК ДОБАВЛЕНИЯ ЗАДАНИЙ
   //
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if ($editing) {

   ///// ПРОДОЛЖЕНИЕ НЕЗАКОНЧЕННЫХ ОПЕРАЦИЙ

	   // ВЫЧИСЛЕНИЕ ТРЕБУЕМЫХ ОПЕРАЦИЙ ///////////////////////////////////////////////////////////////
		$usedoper[] = "0";
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$ID_resurs."')");
		while($res = mysql_fetch_array($result)) {
			$usedoper[] = $res["ID_operitems"];
		}

		$pdate2 = explode(".",$date);
		$pdate2[0] = $pdate2[0] - 7;
		if ($pdate2[0]<1) {
			$pdate2[0] = 30 + $pdate2[0];
			$pdate2[1] = $pdate2[1] - 1;
			if ($pdate2[1]<1) {
				$pdate2[1] = 12 + $pdate2[2];
				$pdate2[2] = $pdate2[2] - 1;
			
			}
		}
		$pdate2 = $pdate2[2]*10000+$pdate2[1]*100+$pdate2[0];


		$now_is_used[] = "0";
		$collected[] = "0";
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (ID_resurs = '".$ID_resurs."') and (DATE<'$pdate') and (DATE>'$pdate2') order by DATE desc");
		while($res = mysql_fetch_array($result)) {
			if (!in_array($res["ID_operitems"],$now_is_used)) {
				$now_is_used[] = $res["ID_operitems"];
				$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$res["ID_operitems"]."')");
				if ($xxx = mysql_fetch_array($xxx)) {
					if (($xxx["STATE"]=="0") && (!in_array($xxx["ID"],$usedoper))) $collected[] = $xxx["ID"];
				}
			}
		}

		if (count($collected)>1) {
	   // ПОДПИСЬ ///////////////////////////////////////////////////////////////
		echo "<table style='width: 100%; padding: 0px;' cellpadding='0' cellspacing='0'><tr><td style='text-align: left;'>\n";
			echo "<h2>Продолжить работу над операциями</h2>";
		echo "</td><td style='text-align: right;'>";
		echo "</td></tr></table><br>";

	   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>\n";
		echo "<td>Заказ / ДСЕ</td>\n";
		echo "<td width='20'>№<br>МТК</td>\n";
		echo "<td width='180'>Операция</td>\n";
		echo "<td width='100'>Оборудование</td>\n";
		echo "<td width='50'>На заказ,<br>Н/Ч</td>\n";
		echo "<td width='17'>&nbsp;</td>\n";
		echo "</tr>\n";
		echo "</thead>";

		echo "<tbody>";
		for ($j=1;$j < count($collected);$j++) {
			OpenLastID($collected[$j]);
		}
		echo "</tbody>";

		echo "</table>\n";
		echo "<br><br><br>";
		}

   ///// ЗАДАНИЯ ИЗ ОПЕРАЦИЙ К ДСЕ ЗАКАЗОВ

	   // ПОДПИСЬ ///////////////////////////////////////////////////////////////
		echo "<table style='width: 100%; padding: 0px;' cellpadding='0' cellspacing='0'><tr><td style='text-align: left;'>\n";
			echo "<h2>Добавление новых заданий</h2>";
		echo "</td><td style='text-align: right;'>";

		echo "</td></tr></table><br>";

	   // ПРИГОТОВЛЕНИЯ ///////////////////////////////////////////////////////////////

		$prioritet = explode("|",$resurs["OPER_IDS"]);

		$addopened = "";
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zak where (EDIT_STATE = '0') and (INSZ = '1')");
		while($res = mysql_fetch_array($result)) {
			$addopened = $addopened."|z".$res["ID"]."|";
		}

		$dboper = Array();
		$result = dbquery("SELECT * FROM ".$db_prefix."db_oper");
		while($res = mysql_fetch_array($result)) {
			$dboper[$res["ID"]] = FVal($res,"db_oper","NAME")." - ".FVal($res,"db_oper","TID");
		}

		$dbpark = Array();
		$result = dbquery("SELECT * FROM ".$db_prefix."db_park");
		while($res = mysql_fetch_array($result)) {
			$dbpark[$res["ID"]] = FVal($res,"db_park","MARK");
		}

	   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>\n";
		echo "<td width='40'>№<br>МТК</td>\n";
		echo "<td>Заказ / ДСЕ / Операция<br><a href='".$pageurl."&addopened=".$addopened."'><img style='margin-left: 20px;' src='uses/collapse.png'></a> <a href='".$pageurl."&closeall'><img style='margin-left: 20px;' src='uses/expand.png'></a></td>\n";
		echo "<td width='120'>Оборудование</td>\n";
		echo "<td width='120'>На заказ<br><b>осталось</b> / всего,<br>Н/Ч (шт)</td>\n";
		echo "<td width='24'>&nbsp;</td>\n";
		echo "</tr>\n";
		echo "</thead>";

	   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
		echo "<tbody>";
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zak where (EDIT_STATE = '0') and (INSZ = '1') order by ORD");
		while($res = mysql_fetch_array($result)) {
			OpenZakID($res);
		}
		echo "</tbody>";

		echo "</table>\n";
		echo "<br><br><br>";

	}


	echo "<div class='links'>";
		echo "<a href='".$back_url."'>Назад</a><br><br>";
	echo "</div>";
}


?>