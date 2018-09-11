<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$start_time = microtime(true);
	include "includes.php";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function FormatReal($num,$x) {
		$ret = number_format( $x, $num, '.', ' ');
		if ($x==floor($x)) $ret = number_format($x, 0, '.', ' ');
		return $ret;
	}

	function OutDATA($stl,$x,$y) {

		$percent = "#";
		if ($y>0) $percent = FormatReal(2,$x/$y);
		$res = "<b>".FormatReal(1,$x)."</b><br>".$percent."<br><b>".FormatReal(1,$y)."</b>";
		if ($res=="<b>0</b><br>#<br><b>0</b>") $res="<br>-<br>";
		echo "<td class='GNT2$stl'><span>".$res."</span></td>";
	}

	function OutItemDATA($stl,$day) {
		global $item_plan, $item_fact, $item_inplan;

		$x = $item_fact[$day];
		$y = $item_plan[$day];

		$percent = "#";
		if ($y>0) $percent = FormatReal(2,$x/$y);
		$res = "<b>".FormatReal(1,$x)."</b><br>".$percent."<br><b>".FormatReal(1,$y)."</b>";
		if ($item_inplan[$day]==0) {
			if ($res=="<b>0</b><br>#<br><b>0</b>") $res="<br>-<br>";
		}
		echo "<td class='GNT2$stl'><span>".$res."</span></td>";
	}

	function OutItemRES($day) {
		global $item_plan_res, $item_smen_res, $item_all_res;

		$x = $item_plan_res[$day];
		$y = $item_smen_res[$day];
		$z = $item_all_res[$day];
		$xx = $z - $y;

		$res = "<b>".FormatReal(1,$xx)."</b><br>".FormatReal(1,$x)." / ".FormatReal(1,$y)."<br><b>".FormatReal(1,$z)."</b>";
		if ($res=="<b>0</b><br>0 / 0<br><b>0</b>") $res="<br>-<br>";

		echo "<td class='GNT2'><span>".$res."</span></td>";
	}

$resurs_ids = Array();
$resurs_opers = Array();

$tabel_plan = Array();
$tabel_fact = Array();

$item_plan = Array();
$item_fact = Array();
$item_inplan = Array();

$item_plan_res = Array();
$item_smen_res = Array();
$item_all_res = Array();

	function CalculateItemRES($id) {
		global $db_prefix, $days, $days_count, $today, $item_plan_res, $item_smen_res, $item_all_res, $resurs_ids;

		$result = dbquery("SELECT ID, ID_oper FROM ".$db_prefix."db_operitems where (ID = '".$id."')");
		if ($operitem = mysql_fetch_array($result)) {
			$id_oper = $operitem["ID_oper"];
			for ($j=0;$j < $days_count;$j++) {
				if ($days[$j]>$today) {

					$item_all_res[$days[$j]] = 0;
					$item_smen_res[$days[$j]] = 0;
					$item_plan_res[$days[$j]] = 0;

				   // Берём народ из табеля на данную дату  ////////////////////////////////////////////////////////

					$where = "((ID_resurs='".implode("') or (ID_resurs='",$resurs_ids)."'))";

					$xxx = dbquery("SELECT ID, ID_resurs, PLAN FROM ".$db_prefix."db_tabel where (DATE='".$days[$j]."') and (TID='0') and ".$where);
					while($res = mysql_fetch_array($xxx)) {
						$ryy = dbquery("SELECT ID, OPER_IDS FROM ".$db_prefix."db_resurs where (ID='".$res["ID_resurs"]."')");
						if ($ryy = mysql_fetch_array($ryy)) {
							$operids = explode("|", $ryy["OPER_IDS"]);

							// Если чел есть и может делать то:
							if (in_array($id_oper,$operids)) {

								// Суммарный ресурс по операции
								$item_all_res[$days[$j]] = $item_all_res[$days[$j]] + $res["PLAN"];

								// Занятый (запланированный) ресурс в СЗ
								$zzz = dbquery("SELECT ID, NORM FROM ".$db_prefix."db_zadan where (DATE='".$days[$j]."') and (ID_resurs='".$ryy["ID"]."')");
								while($zad = mysql_fetch_array($zzz)) {
									$item_smen_res[$days[$j]] = $item_smen_res[$days[$j]] + $zad["NORM"];
								}
							}
						}
					}

				   /////////////////////////////////////////////////////////////////////////////////////////////////

				   // Занятый (запланированный) ресурс в недосменках
					$zzz = dbquery("SELECT ID, ID_operitems, NORM FROM ".$db_prefix."db_planzad where (DATE='".$days[$j]."')");
					while($zad = mysql_fetch_array($zzz)) {
						$oop = dbquery("SELECT ID, ID_oper FROM ".$db_prefix."db_operitems where (ID='".$zad["ID_operitems"]."')");
						if ($oop = mysql_fetch_array($oop)) {
							if ($oop["ID_oper"]==$id_oper) $item_plan_res[$days[$j]] = $item_plan_res[$days[$j]] + $zad["NORM"];
						}
					}
				}
			}
		}
	}

	function CalculateTabel() {
		global $days_count, $db_prefix, $days, $tabel_plan, $tabel_fact, $resurs_ids, $today;

		for ($j=0;$j < $days_count;$j++) {
			$all_plan = 0;
			$all_fact = 0;
			$xxx = dbquery("SELECT ID, ID_resurs, PLAN FROM ".$db_prefix."db_tabel where (DATE='".$days[$j]."')");
			while($res = mysql_fetch_array($xxx)) {
				if (in_array($res["ID_resurs"],$resurs_ids)) {
					$all_plan = $all_plan + $res["PLAN"];
				}
			}

			$zzz = dbquery("SELECT ID, NORM, NORM_FACT, EDIT_STATE FROM ".$db_prefix."db_zadan where (DATE='".$days[$j]."')");
			while($zad = mysql_fetch_array($zzz)) {
				if ($days[$j]>$today) {
					$all_fact = $all_fact + $zad["NORM"];
				} else {
					if ($zad["EDIT_STATE"]=="1") $all_fact = $all_fact + $zad["NORM_FACT"];
				}
			}
			$zzz = dbquery("SELECT ID, NORM FROM ".$db_prefix."db_planzad where (DATE='".$days[$j]."')");
			while($zad = mysql_fetch_array($zzz)) {
				if ($days[$j]>$today) $all_fact = $all_fact + $zad["NORM"];
			}

			$tabel_fact[$days[$j]] = $all_fact;
			$tabel_plan[$days[$j]] = $all_plan;
		}
	}



	function CalculateItemOper($id) {
		global $db_prefix, $item_plan, $item_fact, $days_count, $days, $item_inplan, $today;

		$result = dbquery("SELECT DATE, NORM, NORM_FACT FROM ".$db_prefix."db_zadan where (ID_operitems = '".$id."') and (DATE>='".$days[0]."') and (DATE<='".$days[$days_count-1]."')");
		while ($zadx = mysql_fetch_array($result)) {
			$item_fact[$zadx["DATE"]] = $item_fact[$zadx["DATE"]] + $zadx["NORM_FACT"]*1;
			$item_plan[$zadx["DATE"]] = $item_plan[$zadx["DATE"]] + $zadx["NORM"]*1;
			$item_inplan[$zadx["DATE"]] = 1;
		}

		$result = dbquery("SELECT DATE, NORM FROM ".$db_prefix."db_planzad where (ID_operitems = '".$id."') and (DATE>'".$today."') and (DATE<='".$days[$days_count-1]."')");
		while ($zadx = mysql_fetch_array($result)) {
			$item_plan[$zadx["DATE"]] = $item_plan[$zadx["DATE"]] + $zadx["NORM"]*1;
			$item_inplan[$zadx["DATE"]] = 1;
		}
	}

	function CalculateItemIzd($id) {
		global $db_prefix;

		$xxx = dbquery("SELECT ID FROM ".$db_prefix."db_operitems where (ID_zakdet = '".$id."') order by ID");
		while($res = mysql_fetch_array($xxx)) CalculateItemOper($res["ID"]);

		$xxx = dbquery("SELECT ID FROM ".$db_prefix."db_zakdet where (PID = '".$id."') order by ID");
		while($res = mysql_fetch_array($xxx)) CalculateItemIzd($res["ID"]);
	}















	// ОТДЕЛЫ УЧАВСТВУЮЩИЕ В СЗ
		$result = dbquery("SELECT ID, INSZ FROM ".$db_prefix."db_otdel where (INSZ = '1') order by ID");
		$where = Array();
		while ($otdel = mysql_fetch_array($result)) {
			$where[] = $otdel["ID"];
		}
		$where = "(ID_otdel='".implode("') or (ID_otdel='",$where)."')";

	// РЕСУРСЫ В ОТДЕЛАХ
		$result = dbquery("SELECT ID, ID_resurs FROM ".$db_prefix."db_shtat where ".$where." order by ID");
		$where = Array();
		while ($shtat = mysql_fetch_array($result)) {
			$where[] = $shtat["ID_resurs"];
		}
		$where = "where (ID='".implode("') or (ID='",$where)."')";

	// ЗАБОР РЕСУРСОВ КОТОРЫЕ МОГУТ УЧАВСТВОВАТЬ В СЗ
		$xxx = dbquery("SELECT ID, OPER_IDS FROM ".$db_prefix."db_resurs ".$where);
		while($res = mysql_fetch_array($xxx)) {
			$resurs_opers[$res["ID"]] = $res["OPER_IDS"];
			$resurs_ids[] = $res["ID"];
		}

	// РАСЧЁТ ПО ДНЯМ $tabel_plan, $tabel_fact ДЛЯ ВЕРХНЕЙ СТРОКИ
		CalculateTabel();

	// РАСЧЁТ ДАННЫХ ДЛЯ НИЖНЕЙ СТРОКИ
		for ($j=0;$j < $days_count;$j++) {
			$item_plan[$days[$j]] = 0;
			$item_fact[$days[$j]] = 0;
			$item_inplan[$days[$j]] = 0;
		}
		$isoper = false;
		$calc_id = 0;
		$calc_tp = "";
		if (isset($_GET["sel"])) {
			$calc_tp = "i";
			if (substr_count($_GET["sel"], "o")>0) $calc_tp = "o";
			$calc_id = str_replace("i","",$_GET["sel"]);
			$calc_id = str_replace("o","",$calc_id);
		}
		if ($calc_tp=="i") CalculateItemIzd($calc_id);	// Если выделена ДСЕ
		if ($calc_tp=="o") {				// Если выделена операция МТК
			$isoper = true;
			CalculateItemOper($calc_id);
			CalculateItemRES($calc_id);
		}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	// ВЫВОД ДАННЫХ

	echo "<table>";
	echo "<tr>";
	for ($j=0;$j < $days_count;$j++) {
		$stl = "";
		if ($days[$j]==$today) $stl = " TODAY";
		OutDATA($stl,$tabel_fact[$days[$j]],$tabel_plan[$days[$j]]);
	}
	echo "<td class='GNT2'><span>&nbsp;</span></td></tr>";
	if ($calc_tp!=="") {
		echo "<tr>";
		for ($j=0;$j < $days_count;$j++) {
			$stl = "";
			if ($days[$j]==$today) $stl = " TODAY";
			if (!$isoper) OutItemDATA($stl,$days[$j]);
			if ($isoper) {
				if ($days[$j]<=$today) {
					OutItemDATA($stl,$days[$j]);
				} else {
					OutItemRES($days[$j]);
				}
			}
		}
		echo "<td class='GNT2'><span>&nbsp;</span></td>";
		echo "<td class='GNT2'><span><b>0</b><br>#<br><b>0</b></span></td></tr>";
	}
	echo "</table>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>