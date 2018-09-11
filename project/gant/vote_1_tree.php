<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	include "includes.php";
	$opened = explode("|",$opened);

	$start_time = microtime(true);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////



	function ID_oper($item,$izd,$n) {

		$ml = 10*$n+10;
		$pic = "<img style='margin-left: ".$ml."px;' src='img/point.png'>";
		
		$name = utftxt(FVal($item,"db_operitems","ID_oper"));

	   // Вывод
		echo "<tr id='L_o".$item["ID"]."' class='GNT OPER_TR' style='cursor: hand;' onClick=\"OpenSmen(".$item["ID"].");\">";
		echo "<td><div class=\"hdn\">".$pic." ".$name."</div></td>";
		echo "</tr>";
	}

	function ID_zakdet($item,$n) {
		global $db_prefix, $opened;


		$ml = 10*$n+10;

		$doopen = is_opened("i".$item["ID"],$opened);
		if (is_opened("zak".$item["ID_zak"],$opened)) $doopen = true;

		if (!$doopen) $pic = "<a href=\"javascript:void(0);\" onClick=\"tree_open('i".$item["ID"]."');\"><img style='margin-left: ".$ml."px;' src='img/collapse.bmp'></a>";
		if ($doopen) $pic = "<a href=\"javascript:void(0);\" onClick=\"tree_close('i".$item["ID"]."');\"><img style='margin-left: ".$ml."px;' src='img/expand.bmp'></a>";

		$name = utftxt("<b>".$item["OBOZ"]."</b> - ".$item["NAME"]);

	   // Вывод
		echo "<tr id='L_i".$item["ID"]."' class='GNT IZD_TR' onClick=\"select('i".$item["ID"]."',0);\">";
		echo "<td><div class=\"hdn\">".$pic." ".$name."</div></td>";
		echo "</tr>";

		if ($doopen) {
			$xx = dbquery("SELECT ID, ID_oper FROM ".$db_prefix."db_operitems where (ID_zakdet='".$item["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_oper($res,$item,$n+1);
			}

			$xx = dbquery("SELECT ID, OBOZ, NAME, ID_zak FROM ".$db_prefix."db_zakdet where  (PID='".$item["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_zakdet($res,$n+1);
			}
		}
	}

	function ID_zak($item,$n) {
		global $db_prefix, $opened;

		$xxx = dbquery("SELECT ID, OBOZ, NAME, ID_zak FROM ".$db_prefix."db_zakdet where  (ID_zak='".$item["ID"]."') and (PID='0')");
		$izd = mysql_fetch_array($xxx);


		$doopen = is_opened("i".$izd["ID"],$opened);
		$zakopen = is_opened("zak".$item["ID"],$opened);
		if ($zakopen) $doopen = true;

	   // Тип заказа
		$tid = FVal($item,"db_zak","TID");

		$ml = 10*$n+10;
		if (!$doopen) $pic = "<a href=\"javascript:void(0);\" onClick=\"tree_open('i".$izd["ID"]."');\"><img style='margin-left: ".$ml."px;' src='img/collapse.bmp'></a>";
		if ($doopen) $pic = "<a href=\"javascript:void(0);\" onClick=\"tree_close('i".$izd["ID"]."');\"><img style='margin-left: ".$ml."px;' src='img/expand.bmp'></a>";

		if (!$zakopen) $pic2 = "<a href=\"javascript:void(0);\" onClick=\"tree_open('zak".$izd["ID_zak"]."');\"><img style='margin-left: ".$ml."px;' src='img/collapse2.bmp'></a>";
		if ($zakopen) $pic2 = "<a href=\"javascript:void(0);\" onClick=\"tree_close('zak".$izd["ID_zak"]."');\"><img style='margin-left: ".$ml."px;' src='img/expand2.bmp'></a>";

		$name = utftxt("<b>".$tid." ".$item["NAME"]."</b> - ".$izd["OBOZ"]."<br>".$pic2." <span class='p'>".$izd["NAME"]."</span>");

	   // Вывод
		echo "<tr id='L_i".$izd["ID"]."' class='GNT ZAK_TR' onClick=\"select('i".$izd["ID"]."',0);\">";
		echo "<td><div class=\"hdn\">".$pic." ".$name."</div></td>";
		echo "</tr>";

		if ($doopen) {
			$xx = dbquery("SELECT ID, ID_oper FROM ".$db_prefix."db_operitems where  (ID_zakdet='".$izd["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_oper($res,$izd,$n+1);
			}

			$xx = dbquery("SELECT ID, OBOZ, NAME, ID_zak FROM ".$db_prefix."db_zakdet where  (PID='".$izd["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_zakdet($res,$n+1);
			}	
		}
	}

echo "<table style='height: 20px;'>";
$xxx = dbquery("SELECT ID, TID, NAME FROM ".$db_prefix."db_zak where (EDIT_STATE='0') and (INGANT='1') order by PRIOR, ID");
while($res = mysql_fetch_array($xxx)) {
	ID_zak($res,0);
}
echo "<tr class='GNT' style='height: 50px;'><td style='border-bottom: 0px solid black;'>&nbsp;</td></tr>";
echo "</table>";





///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  STATTREE       ////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<div id='stattree' style='display: none;'>";
	$mem_usage = memory_get_peak_usage(true)/1024;
	$exec_time = microtime(true) - $start_time;
echo utftxt("Время, сек: ".number_format($exec_time, 3, ',', ' ')." &nbsp; Память, кБ: ".number_format($mem_usage, 0, ',', ' ')." &nbsp; Запросов к БД: ".$dbquery_index."");
echo "</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>