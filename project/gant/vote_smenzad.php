<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$start_time = microtime(true);
	include "includes.php";
	$opened = explode("|",$opened);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// $_GET["id_oper"]
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$user_right_groups = explode('|', $user['ID_rightgroups']);

	$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$_GET["id_oper"]."')");
	$operitem = mysql_fetch_array($result);

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID='".$operitem["ID_zakdet"]."')");
	$izd = mysql_fetch_array($result);

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak='".$izd["ID_zak"]."') and (PID='0')");
	$first_zakdet = mysql_fetch_array($result);

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$izd["ID_zak"]."')");
	$zak = mysql_fetch_array($result);

	// Òèï çàêàçà
	$tid = FVal($zak,"db_zak","TID");

	$oper_more = "";
	$oper_txt = FVal($operitem,"db_operitems","MORE");
	if ($oper_txt!=="") $oper_more = "<div style='padding: 5px 0px 5px 0px; margin: 0px;'><i style='color: #950407;'>".$oper_txt."</i></div>";
	$operitem_name = utftxt("<b>".$izd["OBOZ"]."</b> - ".$izd["NAME"]."<br>".FVal($operitem,"db_operitems","ID_oper")."<br>".$oper_more."<b>".$izd["RCOUNT"]."</b> øò - <b>".$operitem["NORM_ZAK"]."</b> Í/×");
	$zak_name = utftxt("<b>".$tid." ".$zak["NAME"]."</b> - ".$first_zakdet["OBOZ"]."<br>".$first_zakdet["NAME"]);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function OutDates() {
		for ($j=0;$j < 28;$j++) {
			$date = TodayAddDaysGANT($j);
			$hg = "";
			if (WWDate($date)=="Âñ") $hg = "style='background: #ff56bc URL(img/bgtop3.gif) repeat-x;'";
			if ($date==$today) $hg = "style='background: #ffd560 URL(img/bgtop2.gif) repeat-x;'";
			echo "<td class='GNT' $hg><span>".utftxt(OutDate($date))."</span></td>";
		}
		echo "<td class='GNT' style='width: 15px;'></td>";
	}

	function OutPlan() {
		global $db_prefix, $operitem;
		for ($j=0;$j < 28;$j++) {
			$date = TodayAddDaysGANT($j);
			$val = "&nbsp;";
			$result = dbquery("SELECT DATE, NORM FROM ".$db_prefix."db_planzad where (ID_operitems = '".$operitem["ID"]."') and (DATE='".$date."')");
			if ($zadan = mysql_fetch_array($result)) $val = "<b>".$zadan["NORM"]."</b>";
			echo "<td class='GNT'><span>".$val."</span></td>";
		}
		echo "<td class='GNT' style='width: 15px; background: #c6d9f1;'></td>";
	}

	function OutResursDates($item) {
		global $db_prefix, $operitem, $izd;

		$resource_id = 0;

		echo '<div style="width:500px;float:left;" class="tr1" data-id="' . $item['ID'] .'"><table><tr style="background: ' . $item['bg_color'] . '">';
		
		for ($j=0;$j < 28;$j++) {
			$bg = "style='background: #fff;'";
			$date = TodayAddDaysGANT($j);
			$val = "&nbsp;<br>&nbsp;";
			$xxx = dbquery("SELECT PLAN, SMEN FROM ".$db_prefix."db_tabel where (DATE='".$date."') and (TID='0') and (ID_resurs='".$item["ID"]."')");
			if ($tabel = mysql_fetch_array($xxx)) {
				$URL_NUM = "edit_zadan.php?tp=num&resurs=".$item["ID"]."&date=".$date."&smen=".$tabel["SMEN"]."&operitem=".$operitem["ID"]."&val=";
				$URL_NORM = "edit_zadan.php?tp=norm&resurs=".$item["ID"]."&date=".$date."&smen=".$tabel["SMEN"]."&operitem=".$operitem["ID"]."&val=";

				$calculator = "";
				$from_id = "num_".$item["ID"]."_".$date;
				$to_id = "norm_".$item["ID"]."_".$date;
				$nxx = 0;
				if ($izd["RCOUNT"]>0) $nxx = $operitem["NORM_ZAK"]/$izd["RCOUNT"];

				$calculator = "<a href='javascript:void(0);' onClick=\"SetNewValue('".$from_id."','".$to_id."',".$nxx.",'".$URL_NORM."')\" title='".utftxt("Ïåðåñ÷èòàòü Í/×")."'><img src='img/clc.gif' alt='".utftxt("Ïåðåñ÷èòàòü Í/×")."'></a><br>";

				$num_val = 0;
				$norm_val = 0;
				$inzadan = 0;

				$zzz = dbquery("SELECT NORM, NUM, ID_operitems FROM ".$db_prefix."db_zadan where (DATE='".$date."') and (ID_resurs='".$item["ID"]."')");
				while ($zad = mysql_fetch_array($zzz)) {

					$inzadan = $inzadan + ($zad["NORM"]*1);
					if ($zad["ID_operitems"]==$operitem["ID"]) {
						$num_val = $zad["NUM"];
						$norm_val = $zad["NORM"];
					}
				}

				$num = "<input type='text' class='inp' name='".$from_id."' id='".$from_id."' value='".$num_val."' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, '".$from_id."', event)\" onChange='vredact(this , \"$URL_NUM\"+this.value);'><br>";
				$norm = "<input type='text' class='inp' name='".$to_id."' id='".$to_id."' value='".$norm_val."' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, '".$to_id."', event)\" onChange='vredact(this , \"$URL_NORM\"+this.value);'><br>";
				$val = $num.$calculator.$norm."<b>" . ($inzadan > 0 ? "<a href='#' onclick='ShowResourceOrders(" . $date . ", " . $tabel['SMEN'] . ", " . $item['ID'] . ");' style='color:blue'>".$inzadan."</a>" : $inzadan) . "</b>/".$tabel["PLAN"]."<br>".$tabel["SMEN"];
				$bg = "";

				$resource_id = $item["ID"];
			}
			echo "<td class='GNT' $bg><span>".$val."</span></td>";

		}
		
		echo '</tr></table></div>';
	}

	function OutResursDatesView($item) {
		global $db_prefix, $operitem, $izd;
		
		echo '<div style="width:500px;float:left;" class="tr1" data-id="' . $item['ID'] .'"><table><tr style="background: ' . $item['bg_color'] . '">';
		
		for ($j=0;$j < 28;$j++) {
			$bg = "style='background: #fff;'";
			$date = TodayAddDaysGANT($j);
			$val = "&nbsp;<br>&nbsp;";
			$xxx = dbquery("SELECT PLAN, SMEN FROM ".$db_prefix."db_tabel where (DATE='".$date."') and (TID='0') and (ID_resurs='".$item["ID"]."')");
			if ($tabel = mysql_fetch_array($xxx)) {
				$URL_NUM = "edit_zadan.php?tp=num&resurs=".$item["ID"]."&date=".$date."&smen=".$tabel["SMEN"]."&operitem=".$operitem["ID"]."&val=";
				$URL_NORM = "edit_zadan.php?tp=norm&resurs=".$item["ID"]."&date=".$date."&smen=".$tabel["SMEN"]."&operitem=".$operitem["ID"]."&val=";

				$calculator = "";
				$from_id = "num_".$item["ID"]."_".$date;
				$to_id = "norm_".$item["ID"]."_".$date;
				$nxx = 0;
				if ($izd["RCOUNT"]>0) $nxx = $operitem["NORM_ZAK"]/$izd["RCOUNT"];

				
				$num_val = 0;
				$norm_val = 0;
				$inzadan = 0;

				$zzz = dbquery("SELECT NORM, NUM, ID_operitems FROM ".$db_prefix."db_zadan where (DATE='".$date."') and (ID_resurs='".$item["ID"]."')");
				while ($zad = mysql_fetch_array($zzz)) {

					$inzadan = $inzadan + ($zad["NORM"]*1);
					if ($zad["ID_operitems"]==$operitem["ID"]) {
						$num_val = $zad["NUM"];
						$norm_val = $zad["NORM"];
					}
				}

				$num = $num_val."<br>";
				$norm = $norm_val."<br>";
				$val = $num.$norm."<b>".$inzadan."</b>/".$tabel["PLAN"]."<br>".$tabel["SMEN"];
				$bg = "";

			}
			echo "<td class='GNT' $bg><span>".$val."</span></td>";
		}
		
		echo '</tr></table></div>';
	}

	function OutResurs($item) {
		global $operitem, $db_prefix, $user_right_groups;
		
		$OPER_IDS = explode("|",$item["OPER_IDS"]);

		if (in_array($operitem["ID_oper"],$OPER_IDS)) {
			echo "<table style='width:100px;float:left;background: " . $item['bg_color'] . "' data-id="  . $item['ID'] . " class='tr2'><tr>";
			echo "
			<td class='GNT'>
				<div style='width: 252px;  text-align: left; padding-left: 15px; overflow: hidden; white-space: nowrap;'>
					".utftxt($item["NAME"]) . (in_array('43', $user_right_groups) || in_array('54', $user_right_groups) || in_array('1', $user_right_groups) ? "<div style='float:right'><input type='checkbox' name='resursIDS' value='" . $item['ID'] . "'/></div>" : '') . "
				</div>
			</td></tr></table>";
			
			if (db_adcheck("db_zadan")) {
				OutResursDates($item);
			} else {
				OutResursDatesView($item);
			}
		}
	}

	function OutResurs2($item) {
		global $operitem, $db_prefix, $user_right_groups;

		$OPER_IDS = explode("|",$item["OPER_IDS"]);

		if (!in_array($operitem["ID_oper"],$OPER_IDS)) {
			echo "<table style='width:100px;float:left;background: " . $item['bg_color'] . "' data-id="  . $item['ID'] . " class='tr2'><tr>";
			echo "<td class='GNT'><div style='width: 252px;  text-align: left; padding-left: 15px; overflow: hidden; white-space: nowrap;'>
				".utftxt($item["NAME"]) . (in_array('43', $user_right_groups) || in_array('54', $user_right_groups) || in_array('1', $user_right_groups) ? "<div style='float:right'><input type='checkbox' name='resursIDS' value='" . $item['ID'] . "'/></div>" : '') ."
			</div></td>";

			echo "</tr></table>";
			
			if (db_adcheck("db_zadan")) {
				OutResursDates($item);
			} else {
				OutResursDatesView($item);
			}
			
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////


	echo "<center>";

	echo "<div id='resource_orders' style='display:none;width:450px;text-align:right;position:absolute;right:30px;'>";
	echo "<br><a href=\"javascript:void(0);\" onClick=\"CloseResources();\">Çàêðûòü</a><br><br>";
	echo "<div style='border: 5px solid #fff; background: #fff;border:1px solid #000;width:450px;' id='resource_orders_content'></div></div>";
	
	echo "<div id='resource_tabel'  style='display: none; width: 400px; text-align: right;float:left;margin-left:30px'>";
	echo "<br><a href=\"javascript:void(0);\" onClick=\"CloseResourcesTabel();\">Çàêðûòü</a><br><br>";
	echo "<div style='border: 5px solid #fff; background: #fff' id='resource_tabel_content'></div></div>";
	
	echo "
	<div style='display: block; width: 800px; text-align: right;'>
	<br>
	<a href=\"javascript:void(0);\" onClick=\"CloseSmen()\">".utftxt("Çàêðûòü")."</a><br/><br/>
	<div style='border: 5px solid #fff; background: #fff;' id='smenzad_inner'>
	<div style='width:800px;'>
	<table style='height:80px;width:100px;float:left'>
		<tr class='RTOP' style='background: #c6d9f1 URL(img/bgtop.gif) repeat-x;'>
			<td>
				<div style='width: 250px;  text-align: left; padding-left: 15px; white-space: nowrap;'>
					".$zak_name. (in_array('43', $user_right_groups) || in_array('54', $user_right_groups) || in_array('1', $user_right_groups) ? "<div style='float:right;border:1px solid #000;border-radius:5px;'><a style='color:blue;font-weight:700'href='javascript:void(0)' onclick='ShowResourceTabel(" . $_GET['id_oper'] . ")'>&nbsp;+&nbsp;</a></div>" : '') . "
				</div>
			</td>
		</tr>
		<tr class='RTOP'>
			<td>
				<div style='width: 250px; text-align: left; padding-left: 15px; overflow: hidden; white-space: nowrap;'>
					".$operitem_name."
				</div>
				</td>
		</tr>
		</table>
		<div style='height: 115px;float:left;width:500px;overflow-x:scroll' id='scrollme' onscroll='scroll()'>
			<table>
				<tr class='RTOP' style='background: #c6d9f1 URL(img/bgtop.gif) repeat-x;'>";
		
		OutDates();
		echo "		</tr>";

	// ÇÀÏËÀÍÈÐÎÂÀÍÍÎ
		echo "		<tr style='background: #fff'>";
		OutPlan();
		echo "
					</tr>
				</table>
			</div>
		</div>
		<div style='height: 500px; overflow-y:scroll'>
		<table style='width:100px;float:left;'>";

	// ÎÒÄÅËÛ Ó×ÀÂÑÒÂÓÞÙÈÅ Â ÑÇ
		$result = dbquery("SELECT ID, INSZ FROM ".$db_prefix."db_otdel where (INSZ = '1') order by ID");
		$where = Array();
		while ($otdel = mysql_fetch_array($result)) {
			$where[] = $otdel["ID"];
		}
		$where = "(ID_otdel='".implode("') or (ID_otdel='",$where)."')";

	// ÐÅÑÓÐÑÛ Â ÎÒÄÅËÀÕ
		$result = dbquery("SELECT ID, ID_resurs FROM ".$db_prefix."db_shtat where ".$where." order by ID");
		$where = Array();
		while ($shtat = mysql_fetch_array($result)) {
			$where[] = $shtat["ID_resurs"];
		}
		$where = "where (ID='".implode("') or (ID='",$where)."')";

	// ÂÛÂÎÄ ÐÅÑÓÐÑÎÂ
		$result = dbquery("SELECT * FROM ".$db_prefix."db_resurs ".$where." order by binary(NAME)");
		while ($resurs = mysql_fetch_array($result)) {
			$resurs['bg_color'] = '#bcd0e9';
			
			OutResurs($resurs);
		}

		$result = dbquery("SELECT * FROM ".$db_prefix."db_resurs ".$where." order by binary(NAME)");
		while ($resurs = mysql_fetch_array($result)) {
			$resurs['bg_color'] = '#e5effb';
			
			OutResurs2($resurs);
		}

		echo "</table></div></div></div></div></center>";
	
?>