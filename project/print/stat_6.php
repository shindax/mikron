<style>
div.VCD {
	display: block;
	-o-transform: rotate(270deg);
	-moz-transform: rotate(270deg);
	-webkit-transform: rotate(270deg);
	font-height: 16px;
	padding: 0;
	margin: 0;
	height: 12px;
	width: 12px;
}
b div.VCD {
	font-weight: bold;
}
b.error {
	background: white;
	color: red;
}
</style>
<?php


	if (!defined("MAV_ERP")) { die("Access Denied"); }

	$step = 1;

	$zak_IDs = $_GET["p0"];

	if (count($zak_IDs)>0) $step = 2;

	include "project/calc_zak.php";


///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////

	function FReal($x) {
		$ret = number_format( $x, 2, ',', ' ');
		if ($x==floor($x)) $ret = number_format($x, 0, ',', ' ');
		return $ret;
	}

	function OutFormat($x,$y) {

		$z = $x - $y;
		$ret = FReal($x)."<br>".FReal($y)."<br>";
		if ($z<0) {
			$ret = $ret."<b class='error'>".FReal($z)."</b>";
		} else {
			$ret = $ret.FReal($z);
		}
		return $ret;
	}

	function ConvertToVertical($str) {

		$res = "";
		for ($i=0;$i < strlen($str);$i++) {
			$xx = $str[strlen($str)-$i-1];
			if ($xx==" ") $xx = "<br>";
			$res = $res."<div class='VCD'>".$xx."</div>";
		}
		return $res;
	}

///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////



if ($step==1) {


	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";


	echo "<h2>Отчёт по остаточной трудоёмкости - выбор заказов</h2>";

	echo "<br><input type='submit' value='Расчёт'><span style='margin-left: 40px;'>";
	Input("boolean","calc_all",0);
	echo " - Полный перерасчёт указанных заказов (очень долго)</span><br><br>";

	render_item(80,false,false,false,false,"(EDIT_STATE='0') and (INSZ='1')","","order by ORD","");


}

if ($step==2) {

   // Шапка

	echo "<h2>Отчёт по остаточной трудоёмкости</h2>";


		$oper_IDs = Array();
		$oper_TIDs = Array();


		$opers = dbquery("SELECT ID, TID FROM ".$db_prefix."db_oper");
		while ($oper = mysql_fetch_array($opers)) {
			$oper_IDs[] = $oper["ID"];
			$oper_TIDs[] = $oper["TID"];
		}

		$ss_N = Array();
		$ss_NF = Array();

	function Open_zak_ID($zak_id) {
		global $db_prefix, $db_cfg, $ss_N, $ss_NF, $oper_IDs, $oper_TIDs, $tids;

		if ($_GET["calc_all"]=="on") CalculateZakaz($zak_id);

		$oper_N_arr = Array();
		$oper_NF_arr = Array();

		$operitems = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zak='".$zak_id."')");
		while ($oper = mysql_fetch_array($operitems)) {
			$oper_N_arr[$oper["ID_oper"]] = $oper_N_arr[$oper["ID_oper"]]*1 + $oper["NORM_ZAK"]*1;
			$oper_NF_arr[$oper["ID_oper"]] = $oper_NF_arr[$oper["ID_oper"]]*1 + $oper["NORM_FACT"]*1;
		}

		$summ_N = Array();
		$summ_NF = Array();

		for ($x=0;$x < count($oper_IDs); $x++) {
			$summ_N[$oper_TIDs[$x]] = $summ_N[$oper_TIDs[$x]]*1 + $oper_N_arr[$oper_IDs[$x]]*1;
			$summ_NF[$oper_TIDs[$x]] = $summ_NF[$oper_TIDs[$x]]*1 + $oper_NF_arr[$oper_IDs[$x]]*1;
			$ss_N[$oper_TIDs[$x]] = $ss_N[$oper_TIDs[$x]]*1 + $oper_N_arr[$oper_IDs[$x]]*1;
			$ss_NF[$oper_TIDs[$x]] = $ss_NF[$oper_TIDs[$x]]*1 + $oper_NF_arr[$oper_IDs[$x]]*1;
		}

		$result = dbquery("SELECT ID, NAME, TID, DSE_NAME, DSE_OBOZ FROM ".$db_prefix."db_zak where (ID='".$zak_id."')");
		$zak = mysql_fetch_array($result);

		$zaknum = "<b>".FVal($zak,"db_zak","TID")." ".FVal($zak,"db_zak","NAME")."</b><br>".FVal($zak,"db_zak","DSE_NAME")." - ".FVal($zak,"db_zak","DSE_OBOZ");

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			$sx = 0;
			$sy = 0;
			echo "<tr>\n";
			echo "<td class='Field AL'>".$zaknum."</td>";
			for ($x=0;$x < count($tids); $x++) {
				$sx = $sx + $summ_N[$x]*1;
				$sy = $sy + $summ_NF[$x]*1;
				echo "<td class='Field AC'>".OutFormat($summ_N[$x],$summ_NF[$x])."</td>";
			}
			echo "<td class='Field AC'><b>".OutFormat($sx,$sy)."</b></td>";
			echo "</tr>";

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	}






		$tids = explode("|","|".$db_cfg["db_oper/TID|LIST"]);
		$tids[0] = "Неуказано";

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "	<thead>\n";
	echo "<tr class='first'>\n";
	echo "<td>Заказ</td>";

		for ($x=0;$x < count($tids); $x++) echo "<td width='60' style='vertical-align: top; text-align: center;'>".ConvertToVertical($tids[$x])."</td>";;

	echo "<td width='60'><b>Итого:</b></td>";
	echo "</tr>\n";
	echo "	</thead>\n";


	for ($x=0;$x < count($zak_IDs); $x++) Open_zak_ID($zak_IDs[$x]);

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			$sx = 0;
			$sy = 0;
			echo "<tr>\n";
			echo "<td class='Field AR'><b>ИТОГО:</b></td>";
			for ($x=0;$x < count($tids); $x++) {
				$sx = $sx + $ss_N[$x]*1;
				$sy = $sy + $ss_NF[$x]*1;
				echo "<td class='Field AC'><b>".OutFormat($ss_N[$x],$ss_NF[$x])."</b></td>";
			}
			echo "<td class='Field AC'><b>".OutFormat($sx,$sy)."</b></td>";
			echo "</tr>";

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	echo "</table>";

	echo "<br><br><b>* Формат данных:</b><br><br><div style='margin-left: 30px;'>План Н/ч<br>Факт Н/Ч<br>Осталось Н/Ч</div>";
	if ($_GET["calc_all"]=="on") echo "<br><br>* Посчитано с полным перерасчётом указанных заказов";
}
?>
