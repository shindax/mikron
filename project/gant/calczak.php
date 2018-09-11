<?php

	$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
	$dd = date("d.m.Y",$theday);
	$dd = explode(".",$dd);
	$today = $dd[0]+$dd[1]*100+$dd[2]*10000;
	

	$izd_norm_all = array();
	$izd_norm_fact = array();

	$izd_plan_s = array();
	$izd_plan_p = array();

	$oper_plan_s = array();
	$oper_plan_p = array();

	function CalcGantOperPlan() {
		global $db_prefix, $today, $oper_plan_s, $oper_plan_p;

		$xxx = dbquery("SELECT NORM, ID_operitems FROM ".$db_prefix."db_zadan where (DATE>'".$today."') order by ID");
		while($zad = mysql_fetch_array($xxx)) {
			$oper_plan_s[$zad["ID_operitems"]] = ($oper_plan_s[$zad["ID_operitems"]]*1) + ($zad["NORM"]*1);
		}

		$xxx = dbquery("SELECT NORM, ID_operitems FROM ".$db_prefix."db_planzad where (DATE>'".$today."') order by ID");
		while($zad = mysql_fetch_array($xxx)) {
			$oper_plan_p[$zad["ID_operitems"]] = ($oper_plan_p[$zad["ID_operitems"]]*1) + ($zad["NORM"]*1);
		}
	}

	function CalcGantIZD($item) {
		global $db_prefix, $today, $izd_norm_all, $izd_norm_fact, $izd_plan_s, $izd_plan_p, $oper_plan_s, $oper_plan_p;


		$izd_norm_all[$item["ID"]] = 0;
		$izd_norm_fact[$item["ID"]] = 0;
		$izd_plan_s[$item["ID"]] = 0;
		$izd_plan_p[$item["ID"]] = 0;

		$xxx = dbquery("SELECT ID, NORM_ZAK, NORM_FACT FROM ".$db_prefix."db_operitems where (ID_zakdet = '".$item["ID"]."') order by ID");
		while($res = mysql_fetch_array($xxx)) {

			$izd_norm_all[$item["ID"]] = $izd_norm_all[$item["ID"]]+($res["NORM_ZAK"]*1);
			$izd_norm_fact[$item["ID"]] = $izd_norm_fact[$item["ID"]]+($res["NORM_FACT"]*1);
			$izd_plan_s[$item["ID"]] = $izd_plan_s[$item["ID"]]+($oper_plan_s[$res["ID"]]*1);
			$izd_plan_p[$item["ID"]] = $izd_plan_p[$item["ID"]]+($oper_plan_p[$res["ID"]]*1);

		}

		$xxx = dbquery("SELECT ID FROM ".$db_prefix."db_zakdet where (PID = '".$item["ID"]."') and (LID='0')");
		while($res = mysql_fetch_array($xxx)) {
			CalcGantIZD($res);

			$izd_norm_all[$item["ID"]] = $izd_norm_all[$item["ID"]] + $izd_norm_all[$res["ID"]];
			$izd_norm_fact[$item["ID"]] = $izd_norm_fact[$item["ID"]] + $izd_norm_fact[$res["ID"]];
			$izd_plan_s[$item["ID"]] = $izd_plan_s[$item["ID"]] + $izd_plan_s[$res["ID"]];
			$izd_plan_p[$item["ID"]] = $izd_plan_p[$item["ID"]] + $izd_plan_p[$res["ID"]];

		}

		dbquery("Update ".$db_prefix."db_zakdet Set GANT_NP:='".$izd_norm_all[$item["ID"]]."' where (ID='".$item["ID"]."')");
		dbquery("Update ".$db_prefix."db_zakdet Set GANT_NF:='".$izd_norm_fact[$item["ID"]]."' where (ID='".$item["ID"]."')");
		dbquery("Update ".$db_prefix."db_zakdet Set GANT_PS:='".$izd_plan_s[$item["ID"]]."' where (ID='".$item["ID"]."')");
		dbquery("Update ".$db_prefix."db_zakdet Set GANT_PP:='".$izd_plan_p[$item["ID"]]."' where (ID='".$item["ID"]."')");
	}

/////////////////////////////////////////////////////////////////////////
//
// ÐÀÑ×¨Ò GANT
//
/////////////////////////////////////////////////////////////////////////

	dbquery("DELETE from ".$db_prefix."db_planzad where (DATE<='".$today."')");

	$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$ID_operitems."')");
	$operitem = mysql_fetch_array($result);

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID='".$operitem["ID_zakdet"]."')");
	$operizd = mysql_fetch_array($result);

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak='".$operizd["ID_zak"]."') and (PID='0')");
	$izd = mysql_fetch_array($result);

	CalcGantOperPlan();
	CalcGantIZD($izd);

?>