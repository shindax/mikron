<script language='javascript'>

function SetNewValue(from_id,to_id,val,url) {


	fr_obj = document.getElementById(from_id);
	xxx = fr_obj.value*val;
	yyy = Math.ceil(xxx*100);
	to_obj = document.getElementById(to_id);
	to_obj.value = yyy/100;
	vote(to_obj,url+to_obj.value);
	}

function HLID(x) {
	obj = document.getElementById("row1_"+x);
	if(obj == null) return;	
	obj.className = "htr";
	obj = document.getElementById("row2_"+x);
	if(obj == null) return;
	obj.className = "htr";
	obj = document.getElementById("row3_"+x);
	if(obj == null) return;
	obj.className = "htr";
	}


function DHLID(x) {

	obj = document.getElementById("row1_"+x);
	if(obj == null) return;
	obj.className = "";
	obj = document.getElementById("row2_"+x);
	if(obj == null) return;
	obj.className = "";
	obj = document.getElementById("row3_"+x);
	if(obj == null) return;
	obj.className = "";
}

window.onload=
	doc_cook = document.cookie;
	get_top_scrl_full = doc_cook.substr((doc_cook.indexOf('scroll')+7), 5);
	get_top_scrl_x = get_top_scrl_full.indexOf('x');
	if (get_top_scrl_x > 0) {
		get_top_scrl_numb = get_top_scrl_full.substr(0, get_top_scrl_x);
	}else{
		get_top_scrl_numb = get_top_scrl_full;
	}
	setTimeout("document.getElementById('vpdiv').scrollTop = get_top_scrl_numb","750");
</script>

<style>
	table.tbl tr.htr {
		background: #ebf3fe;
	}
	table.tbl tr.highlite {
		background: #cbdef4;
	}
	a.acl {
		font-size: 11pt;
		text-decoration: none;
	}

.zak_link, .dse_link
{
	margin : 0;
	padding : 0;
	font-weight : bold;
}

.zak_link:hover, .dse_link:hover
{
	color: red;
}


</style>

<?php

$smena = $_GET["p1"];
if (($smena!=="1") && ($smena!=="2") && ($smena!=="3")) $smena = "1";
$pdate = $_GET["p0"]*1;
$date = IntToDate($pdate);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$pageurl_addzad = "index.php?do=show&formid=65&p0=".$pdate."&p1=".$smena."&p2=";
		$calendar_url = "index.php?do=show&formid=63&p0=".$date;
		$print_url = "index.php?do=show&formid=84&p0=".$pdate."&p1=".$smena;
		$print_resurs_url = "index.php?do=show&formid=83&p0=".$pdate."&p1=";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	include "project/calc_zak.php";

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

	$user_rightgroups = explode('|', $user['ID_rightgroups']);
	
	$redirected = false;


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Действие ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// addnewres /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_POST['addnewres'])) {
		$ids = $_POST['addnewres'];
		if ((db_adcheck("db_zadanres")) && ($editing)) {
			for ($j=0;$j < count($ids);$j++) {
				dbquery("INSERT INTO ".$db_prefix."db_zadanres (DATE, SMEN, ID_resurs) VALUES ('".$pdate."', '".$smena."', '".$ids[$j]."')");
			}
		}
		redirect($pageurl."&event","script");
		$redirected = true;
	}

// delresid /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_GET['delresid'])) {
		$dodelid = $_GET['delresid'];
		$used = true;
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where  (ID='".$dodelid."')");
		if (mysql_fetch_array($xxx)) {
			$used = false;
			$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where  (DATE='".$xxx["DATE"]."') and (SMEN='".$xxx["SMEN"]."') and (ID_resurs='".$xxx["ID_resurs"]."')");
			if (mysql_fetch_array($result)) $used = true;
		}
		if ((!$used) && (db_adcheck("db_zadanres")) && ($editing)) dbquery("DELETE from ".$db_prefix."db_zadanres where (ID='".$dodelid."')");
		redirect($pageurl."&event","script");
		$redirected = true;
	}

// Добавление заданий //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_POST["add_zadan_to_resurs"])) {

		$idresurs = $_POST["add_zadan_to_resurs"];
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID = '".$idresurs."')");
		if (($resurs = mysql_fetch_array($xxx)) && ($editing)) {

		   // Добавление заданий к заказам
			if (db_adcheck("db_zadan")) {
			$zak_zad = $_POST["zak_zad"];

			for ($j=0;$j < count($zak_zad);$j++) {
				$ID_zakdet = "0";
				$ID_zak = "0";
				$ID_park = "0";
				$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$zak_zad[$j]."')");
				if ($xxx = mysql_fetch_array($xxx)) {
					$ID_zakdet = $xxx["ID_zakdet"];
					$ID_park = $xxx["ID_park"];
					$yyy = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$ID_zakdet."')");
					if ($yyy = mysql_fetch_array($yyy)) $ID_zak = $yyy["ID_zak"];
				}
				if ($ID_zakdet!=="0") dbquery("INSERT INTO ".$db_prefix."db_zadan (SMEN, ID_park, ID_zak, ID_zakdet, ID_operitems, ID_resurs, DATE, EDIT_STATE) VALUES ('".$smena."', '".$ID_park."', '".$ID_zak."', '".$ID_zakdet."', '".$zak_zad[$j]."', '".$idresurs."', '".$pdate."', '0')");
			}
			}

		}
		redirect($pageurl."&event","script");
		$redirected = true;
	}

// okoperid /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_GET['okoperid'])) {
		$id = $_GET['okoperid'];

		if (db_check("db_operitems","STATE")) {
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (ID_operitems = '".$id."') and (EDIT_STATE = '0')");
		if (!mysql_fetch_array($result)) {

		   // Обновили операцию
			dbquery("Update ".$db_prefix."db_operitems Set STATE:='1' where (ID = '".$id."')");

		   // пересчитали заказ
			// ??	CalculateOperitem($xxxzad["ID_operitems"]);
		}
		}

		redirect($pageurl."&event","script");
		$redirected = true;
	}

// addbytabel /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_GET['addbytabel'])) {
		if ((db_adcheck("db_zadanres")) && ($editing)) {

		   ///////////////////////////////////////////
			$resurs_IDs = Array();
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (INSZ = '1')");
			while($otdel = mysql_fetch_array($xxx)) {
				$xxxs = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID_otdel = '".$otdel["ID"]."' AND presense_in_shift_orders = 1)");
				while($shtat = mysql_fetch_array($xxxs)) {
					if (!in_array($shtat["ID_resurs"],$resurs_IDs)) $resurs_IDs[] = $shtat["ID_resurs"];
				}
			}
		   ///////////////////////////////////////////

			$ids = array();
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (SMEN='".$smena."') and (DATE='".$pdate."') and (TID='0')");
			
			
			while ($res = mysql_fetch_array($xxx)) {
			   if (in_array($res["ID_resurs"],$resurs_IDs)) {
				$xxres = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (SMEN='".$smena."') and (DATE='".$pdate."') and (ID_resurs='".$res["ID_resurs"]."')");
				if (!mysql_fetch_array($xxres)) {
					dbquery("INSERT INTO ".$db_prefix."db_zadanres (DATE, SMEN, ID_resurs) VALUES ('".$pdate."', '".$smena."', '".$res["ID_resurs"]."')");
				}
			   }
			}
		}
		redirect($pageurl."&event","script");
		$redirected = true;
	}





if (!$redirected) {

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Вывод списка ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function OpenID($item) {
		global $db_prefix, $editing, $pageurl_addzad, $pdate, $smena, $date, $pageurl, $print_resurs_url, $ID_resurs_mults;



	   // СУММЫ ПЛАН И ФАКТ ///////////////////////////////////////////

			$plan_n = 0;
			$fact_n = 0;
			$fact = 0;

		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$item["ID_resurs"]."') and (SMEN = '".$smena."') order by ORD");
		while($res = mysql_fetch_array($result)) {
			$plan_n += (1*$res["NORM"]);
			$fact_n += (1*$res["NORM_FACT"]);
			$fact += (1*$res["FACT"]);
		}

	   // Строка
		echo "<tr class='highlite' style='height: 25px;'>";

	   // Сортировка
		Field($item,"db_zadanres","ORD",$editing,"style='width: 30px;'","","rowspan='2'");

	   // Ресурс
		echo "<td class='Field' style='text-align: left;' colspan='5' rowspan='2'><b style='margin-right: 30px;'>".FVal($item,"db_zadanres","ID_resurs")."</b>";
		if (($editing) && (db_adcheck("db_zadan"))) {
			echo "<a href='".$pageurl_addzad.$item["ID_resurs"]."'>Планировать по операциям</a> | <a href='index.php?do=show&formid=112&p0=".$pdate."&p1=".$smena."&p2=".$item["ID_resurs"]."'>Планировать по оборудованию</a> | ";
		}
		echo "<a href='".$print_resurs_url.$smena."&p2=".$item["ID_resurs"]."' target='_blank'>Распечатать</a>";
		echo " | <a href='index.php?do=show&formid=211&p0=".$_GET['p0']."&p1=".$_GET['p1']."&p2=".$item['ID_resurs']."' target='_blank'>Распечатать (новая)</a>";
		echo "</td>";

		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field' rowspan='2'><b>".$plan_n."</b></td>";
		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field'></td>";
		echo "<td class='Field'><b>".$fact_n."</b></td>";
		echo "<td class='Field'><b>".$fact."</b></td>";
		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field' colspan='2' rowspan='2'><a href='".$pageurl."&event'><- Обновить</a></td>";
		echo "<td class='Field' rowspan='2'><input type='checkbox' id='sel_all_".$item["ID_resurs"]."' onchange='sel_all_zad(".$item["ID_resurs"].", this);'></td>";


	   // Действие
		$showdel = "";
		if ((db_adcheck("db_zadanres")) && ($editing)) {
			$used = false;
			$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where  (DATE='".$item["DATE"]."') and (SMEN='".$item["SMEN"]."') and (ID_resurs='".$item["ID_resurs"]."')");
			if (mysql_fetch_array($result)) $used = true;
			$ID_resurs_mults = $item['ID_resurs'];
			if (!$used) 
			{
				$showdel = "<img onclick='if (confirm(\"Уверены, что хотите удалить ресурс из списка ?\")) parent.location=\"$pageurl&delresid=".$item["ID"]."\";' style='cursor: hand;' alt='Удалить' src='uses/del.png'>";
			}
			else
			{
				$showdel = "";
			}
		}
		echo "<td class='Field' rowspan='2'>".$showdel."</td>";

		echo "</tr>\n";


	   // Спец задания

			$in_tabel = false;
			$result_tabel = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (DATE = '".$pdate."') and (ID_resurs = '".$item["ID_resurs"]."')");
			if ($tabel = mysql_fetch_array($result_tabel)) $in_tabel = true;

		echo "<tr class='highlite'>";
		echo "<td class='Field' colspan='2'><b>Спец. зад.</b></td>";

			if (!$in_tabel) echo "<td class='Field'></td>";
			if ($in_tabel) Field($tabel,"db_tabel","SPEC",$editing,"",""," style='max-width: 50px;' ");

		echo "</tr>\n";

		// Коэффициент трудоёмкости
		
			$used = 0;
			$itognorm = 0;
			$itognormfact = 0;
			$itogfact = 0;
			$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where  (DATE='".$item["DATE"]."') and (SMEN='".$item["SMEN"]."') and (ID_resurs='".$item["ID_resurs"]."') and (EDIT_STATE='1')");
			while($usres=mysql_fetch_array($result)) {
				$result2 = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$usres['ID_operitems']."')");
				$usres2=mysql_fetch_array($result2);
				$itognorm+=$usres['NORM'];
				$itognormfact+=$usres['NORM_FACT'];
				$itogfact+=$usres['FACT'];
				$used = 1;
			}
			if($used == 1) {
				if ($itogfact == '0'){
					$itogfact2 = '0';
				}else{
					$itogfact2 = round(($itognormfact/$itogfact),2);
				}
				echo "<tr><td class='Field' colspan='2' style='text-align:right;background:#cbdef4;'><b>Итого:</b>
				</td><td class='Field' style='background:#cbdef4;'>Коэффициент трудоёмкости <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(".$itogfact2.")</b></td></tr>";
			}

	   // ЗАДАНИЯ НА РЕСУРС ///////////////////////////////////////////

		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$item["ID_resurs"]."') and (SMEN = '".$smena."') order by ORD");
		while($res = mysql_fetch_array($result)) {
			OpenZadanID($res);
		}

	}


	function OpenZadanID($item) {
		global $db_prefix, $editing, $modering, $editingplan, $pageurl, $ID_resurs_mults;
		
	   // Строка
		echo "<tr id='row1_".$item["ID"]."' onmouseover=\"HLID(".$item["ID"].");\" onmouseout=\"DHLID(".$item["ID"].");\">";


		$result = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID = '".$item["ID_zak"]."')");
		$zak = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$item["ID_zakdet"]."')");
		$izd = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$item["ID_operitems"]."')");
		$oper = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak = '".$item["ID_zak"]."') and (PID = '0')");
		$first_dse = mysql_fetch_array($result);

		$tid = FVal($zak,"db_zak","TID");
		$name = $tid." ".$zak["NAME"];


	   // №
		Field($item,"db_zadan","ORD",$editing,"style='width: 30px;'","","rowspan='2'");

	   // ID
		echo "<td class='Field' style='text-align: center;' rowspan='2'><b>".$item["ID"]."</b></td>";

	   // Заказ / ДСЕ 
		// echo "<td class='Field' style='text-align: left;'><span style='color: #004e7a;'><b>".$name."</b> ".$zak["DSE_NAME"]."</span><br>".$izd["OBOZ"]." ".$izd["NAME"]."</td>";


		echo "<td class='Field' style='text-align: left;'>
		<a title='Перейти к заказу' class='zak_link' href='index.php?do=show&formid=39&id={$item['ID_zak']}' target='_blank'>$name {$zak["ID_zak"]}</a><a title='Перейти к параметрам ДСЕ' class='dse_link' href='index.php?do=show&formid=52&id={$izd["ID"]}' target='_blank'>{$zak["DSE_NAME"]}<br>{$izd["OBOZ"]} {$izd["NAME"]}</a>
		</td>";


	   // №
		Field($oper,"db_operitems","ORD",false,"","","");

	   // Операция
		$pic = "";
		if (($item["EDIT_STATE"]=="1") && ($oper["STATE"]=="0")) {
			$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (ID_operitems = '".$item["ID_operitems"]."') and (EDIT_STATE = '0')");
			if (!mysql_fetch_array($result)) {
				$pic = "<img onclick='if (confirm(\"Перевести операцию в статус - выполнено?\")) parent.location=\"$pageurl&ID_zak=".$item["ID_zak"]."&okoperid=".$item["ID_operitems"]."\";' style='cursor: hand; margin-right: 5px;' alt='Перевести операцию в статус - выполнено' src='uses/ok.png'>";
			}
		}
		Field($item,"db_zadan","ID_operitems",false,"",$pic,"");

	   // Оборудование
		Field($item,"db_zadan","ID_park",$editing,"","","");

	   // Кол-во операций на заказ
		$rcount = $oper["NUM_ZAK"]*1 - $oper["NUM_ZADEL"]*1;
		if ($oper["BRAK"]*1==1) $rcount = $oper["NUM_ZAK"]*1;

	   // План
	    $nummsumm_expl = explode("|", $item['NUMSUMM_PLAN']);
		if (($editing) && (db_adcheck("db_zadan"))) {
			echo "<td class='Field'><input style='width:45px;' onchange='add_numsumm(".$item['ID_resurs'].",".$item['ID_operitems'].",0,this.value);' value='".$nummsumm_expl[0]."'>/<input style='width:45px;' onchange='add_numsumm(".$item['ID_resurs'].",".$item['ID_operitems'].",1,this.value);' value='".$nummsumm_expl[1]."'></td>";
		}else{
			echo "<td class='Field'>".$nummsumm_expl[0]."/".$nummsumm_expl[1]."</td>";
		}
		$nxx = 0;
		if ($rcount>0) $nxx = $oper["NORM_ZAK"]/$rcount;
		$calculator = "";
		$from_id = "num_".$item["ID"];
		$to_id = "norm_".$item["ID"];
		$churl = "db_edit.php?db=db_zadan&field=NORM&id=".$item["ID"]."&value=";
		if ($editingplan) $calculator="<a href='javascript:void(0);' onClick=\"SetNewValue('".$from_id."','".$to_id."',".$nxx.",'".$churl."')\" title='Пересчитать Н/Ч'><img src='project/img/calc.png' alt='Пересчитать Н/Ч'></a>";
		Field($item,"db_zadan","NUM",$editingplan," id='".$from_id."' ",$calculator," style='max-width: 60px;' ");
		Field($item,"db_zadan","NORM",$editingplan," id='".$to_id."' ",""," style='max-width: 50px;' ");

	   // На заказ
		$ost = 0;
		if ($oper["NORM_ZAK"]>0) $ost = $rcount*(($oper["NORM_ZAK"]-$oper["NORM_FACT"])/$oper["NORM_ZAK"]);
		$ost = number_format( $ost, 0, '.', ' ');
		echo "<td class='Field'><center><b>".($oper["NORM_ZAK"]-$oper["NORM_FACT"])." (".$ost.")</b><br>".$oper["NORM_ZAK"]." (".$rcount.")<br>".round(($oper["NORM"])/(60),2)."</center></td>";
		
	   // Факт
		$nxx = 0;
		if ($rcount>0) $nxx = $oper["NORM_ZAK"]/$rcount;
		$calculator = "";
		$from_id = "fnum_".$item["ID"];
		$to_id = "fnorm_".$item["ID"];
		$churl = "db_edit.php?db=db_zadan&field=NORM_FACT&id=".$item["ID"]."&value=";
		if ($modering) $calculator="<a href='javascript:void(0);' onClick=\"SetNewValue('".$from_id."','".$to_id."',".$nxx.",'".$churl."')\" title='Пересчитать Н/Ч'><img src='project/img/calc.png' alt='Пересчитать Н/Ч'></a>";
		Field($item,"db_zadan","NUM_FACT",$modering,"id='".$from_id."' ",$calculator," style='max-width: 60px;' ");
		Field($item,"db_zadan","NORM_FACT",$modering," id='".$to_id."' ",""," style='max-width: 50px;' ");
		Field($item,"db_zadan","FACT",$modering,"",""," style='max-width: 50px;' ");
		Field($item,"db_zadan","ID_zadanrcp",$modering,"style='width: 140px;'","","");

	   // Цехи
	   // Цехи
		if (($editing) && (db_adcheck("db_zadan"))) {
			echo "<td class='Field'><select onchange=\"vote(this , 'db_edit.php?db=db_zadan&field=CEH1&id=".$item['ID']."&value='+this.options[this.options.selectedIndex].value);\">";
			echo "<option value=0"; if ($item['CEH1']=="0") { echo " selected";} echo ">---";
			echo "<option value=1"; if ($item['CEH1']=="1") { echo " selected";} echo ">А-1";
			echo "<option value=2"; if ($item['CEH1']=="2") { echo " selected";} echo ">А-2";
			echo "<option value=3"; if ($item['CEH1']=="3") { echo " selected";} echo ">А-3";
			echo "<option value=4"; if ($item['CEH1']=="4") { echo " selected";} echo ">А-4";
			echo "<option value=5"; if ($item['CEH1']=="5") { echo " selected";} echo ">А-5";
			echo "<option value=6"; if ($item['CEH1']=="6") { echo " selected";} echo ">А-6";
			echo "<option value=7"; if ($item['CEH1']=="7") { echo " selected";} echo ">Б-1";
			echo "<option value=8"; if ($item['CEH1']=="8") { echo " selected";} echo ">Б-2";
			echo "<option value=9"; if ($item['CEH1']=="9") { echo " selected";} echo ">Б-3";
			echo "<option value=10"; if ($item['CEH1']=="10") { echo " selected";} echo ">Б-4";
			echo "<option value=11"; if ($item['CEH1']=="11") { echo " selected";} echo ">В-1";
			echo "<option value=12"; if ($item['CEH1']=="12") { echo " selected";} echo ">В-2";
			echo "<option value=13"; if ($item['CEH1']=="13") { echo " selected";} echo ">В-3";
			echo "<option value=14"; if ($item['CEH1']=="14") { echo " selected";} echo ">В-4";
			echo "<option value=15"; if ($item['CEH1']=="15") { echo " selected";} echo ">Д-1";
			echo "<option value=16"; if ($item['CEH1']=="16") { echo " selected";} echo ">Д-2";
			echo "<option value=17"; if ($item['CEH1']=="17") { echo " selected";} echo ">Д-3";
			echo "<option value=18"; if ($item['CEH1']=="18") { echo " selected";} echo ">Д-4";
			echo "</select></td>";
		}else{
			echo "<td class='Field'></td>";
		}
		if (($editing) && (db_adcheck("db_zadan"))) {
			echo "<td class='Field'><select onchange=\"vote(this , 'db_edit.php?db=db_zadan&field=CEH2&id=".$item['ID']."&value='+this.options[this.options.selectedIndex].value);\">";
			echo "<option value=0"; if ($item['CEH2']=="0") { echo " selected";} echo ">---";
			echo "<option value=1"; if ($item['CEH2']=="1") { echo " selected";} echo ">А-1";
			echo "<option value=2"; if ($item['CEH2']=="2") { echo " selected";} echo ">А-2";
			echo "<option value=3"; if ($item['CEH2']=="3") { echo " selected";} echo ">А-3";
			echo "<option value=4"; if ($item['CEH2']=="4") { echo " selected";} echo ">А-4";
			echo "<option value=5"; if ($item['CEH2']=="5") { echo " selected";} echo ">А-5";
			echo "<option value=6"; if ($item['CEH2']=="6") { echo " selected";} echo ">А-6";
			echo "<option value=7"; if ($item['CEH2']=="7") { echo " selected";} echo ">Б-1";
			echo "<option value=8"; if ($item['CEH2']=="8") { echo " selected";} echo ">Б-2";
			echo "<option value=9"; if ($item['CEH2']=="9") { echo " selected";} echo ">Б-3";
			echo "<option value=10"; if ($item['CEH2']=="10") { echo " selected";} echo ">Б-4";
			echo "<option value=11"; if ($item['CEH2']=="11") { echo " selected";} echo ">В-1";
			echo "<option value=12"; if ($item['CEH2']=="12") { echo " selected";} echo ">В-2";
			echo "<option value=13"; if ($item['CEH2']=="13") { echo " selected";} echo ">В-3";
			echo "<option value=14"; if ($item['CEH2']=="14") { echo " selected";} echo ">В-4";
			echo "<option value=15"; if ($item['CEH2']=="15") { echo " selected";} echo ">Д-1";
			echo "<option value=16"; if ($item['CEH2']=="16") { echo " selected";} echo ">Д-2";
			echo "<option value=17"; if ($item['CEH2']=="17") { echo " selected";} echo ">Д-3";
			echo "<option value=18"; if ($item['CEH2']=="18") { echo " selected";} echo ">Д-4";
			echo "</select></td>";
		}else{
			echo "<td class='Field'></td>";
		}

	   // Действие
		$showdel = "<img onclick='if (confirm(\"Уверены, что хотите удалить задание ID: ".$item["ID"]."?\")) vote5(this,".$item["ID"].",".$item["ID_operitems"].", ".$item['ID_resurs'].");' style='cursor: hand;' alt='Удалить' src='uses/del.png'> ";
		echo "<td class='Field'><input type='checkbox' name='cur_zad_sel' name2='parent_res_".$item['ID_resurs']."' name3='".$item["EDIT_STATE"]."' name4='".$item["ID_operitems"]."' id='item_zad_".$item["ID"]."'><input type='hidden' name='zadan_id' value='".$item["ID"]."'/></td>
		<td class='Field'>";
		if ($item["EDIT_STATE"]=="0") {
			echo "<table><tr><td style='text-align: left; padding-right: 5px;'>";
		if (($editing) && (db_adcheck("db_zadan"))) echo $showdel;
			echo "</td><td style='text-align: right; padding-left: 5px;'>";
		if (($modering) && (db_adcheck("db_zadan"))) echo " <a style='cursor:pointer;' onclick='reload_page(); vote6(this,".$item["ID"].",".$item["ID_operitems"].", ".$item['ID_resurs'].");'><img alt='Готово' src='uses/ok.png'></a>";
			echo "</td></tr></table>";
		} else {
			if ((db_adcheck("db_zadan")) && ($oper["STATE"]=="0")) echo " <a style='cursor:pointer;' onclick='reload_page(); vote7(this,".$item["ID"].",".$item["ID_operitems"].", ".$item['ID_resurs'].");'><img alt='Возобновить' src='uses/restore.png'></a>";
		}
		echo "</td>";

		echo "</tr>\n";
		echo "<tr id='row3_".$item["ID"]."' onmouseover=\"HLID(".$item["ID"].");\" onmouseout=\"DHLID(".$item["ID"].");\">
		<td style='width:125px;' class='Field'><span style='margin-right: 10px;'>Инициатор:</span>";
		Field($item,"db_zadan","MORE",$editing,"","<span style='margin-right: 10px;'>Примечание:</span>","colspan='11'");
		
		echo "<td class='Field' colspan='15' class='td_change_resource'>" . (db_adcheck("db_zadan") ? "<div style='float:right'><button onclick='return false;' class='button_change_resource'>Изменить ресурс<input type='hidden' name='item_id' value='".$item["ID_resurs"]."'/><input type='hidden' name='zadan_id' value='".$item["ID"]."'/><img src='/uses/view.gif'/></button></div>" : '') . "</td></tr>\n";
	}
	
	echo '<script type="text/javascript">
		$(document).on("click", ".button_change_resource", function () {
			if ($("#select_resources").css("display") == "none") {
				$("#div_select_resources, #select_resources, #input_new_resource").show();
				$("#input_new_resource").focus();
				$(this).hide();

				return;
			}
						
			
			if (($(this).parent().find("input").length - 2) == 0) {
				$(".button_change_resource").css("display", "block");
				$("#div_select_resources").remove();
				$(this).parent().append("<div id=\'div_select_resources\'><input type=\'text\' size=\'27\' id=\'input_new_resource\'/><div style=\'position:relative\'><select id=\'select_resources\' size=\'15\' style=\'width:100%;display:block;position:absolute;\'></select></div></div>");
				$(this).hide();
				
				$("#input_new_resource").focus();
				
				$("#select_resources").load("/project/sz_get_all_resources.php?date=" + ' . $_GET['p0'] . ' + "&smena=" + ' . $_GET['p1'] . ');
			}
		});

		$(document).on("keyup", "#input_new_resource", function () {
			find_list_cur_krz($(this).val());
		});
		
		$(document).on("change", "#select_resources", function () {
			var resurs = $("input[type=checkbox]");

			var zadan_array = [];

			var check_count = 0;
			
			resurs.each(function (key, value) {
				var zadan_id = $(this).parent().parent().find("input[name=zadan_id]");
				
				if (zadan_id.val() != null) {
					var zadan_checkbox = zadan_id.parent().find("input[type=checkbox]");
	
					if(zadan_checkbox.is(":checked")) {
						zadan_array.push(zadan_id.val());
						
						++check_count;
					}	
				}
			});
			
			var selected_resource = $("#select_resources option:selected").val();
			
			if (check_count > 0) {
				$.post("/project/sz_change_resource.php", { mode : "multiple", ids : zadan_array, to_resource : selected_resource }, function (data) {
					//alert(data);
					window.location.reload();
				});
			} else {
				$.post("/project/sz_change_resource.php", { mode : "single", id : $(this).parent().parent().parent().find("input[name=zadan_id]").val(), to_resource : selected_resource }, function (data) {
					//alert(data);
					window.location.reload();
				});
			}
		});
	
		$(document).on("click", window, function() {
			$("#select_resources, #input_new_resource").hide();	
			$(".button_change_resource").show();
			$("#div_select_resources").remove();
		});
	
		$(document).on("click", "#print_archivarius", function() {
			var resurs = $("input[type=checkbox]");

			var zadan_array = [];

			resurs.each(function (key, value) {
				var zadan_id = $(this).parent().parent().find("input[name=zadan_id]");
				
				if (zadan_id.val() != null) {
					var zadan_checkbox = zadan_id.parent().find("input[type=checkbox]");
	
					if(zadan_checkbox.is(":checked")) {
						zadan_array.push(zadan_id.val());
					}	
				}
			});
			
			$(this).attr("href", "/index.php?do=show&formid=219&p1=' . $_GET['p1'] . '&p0=' . $_GET['p0'] . '&zadan_ids=" + zadan_array.join(","));
		});

		$(document).on("click", "#div_select_resources, .td_change_resource, .button_change_resource", function(event){
			event.stopPropagation();
		});

		function find_list_cur_krz(val){
			if (val.length > 1)
			{
				var nam_sel_cur_krz = document.getElementsByName(\'nam_sel_cur_krz\');
			  
				for (var d_d = 0; d_d < nam_sel_cur_krz.length; d_d++ )
				{
					if (nam_sel_cur_krz[d_d].innerHTML.toLowerCase().indexOf( val.toLowerCase() ) == -1)
					{
						nam_sel_cur_krz[d_d].style.display=\'none\';
					} else {
						nam_sel_cur_krz[d_d].style.display=\'block\';
					}
				}
			}
			  
			if (val.length < 2){
				for (var d_d = 0; d_d < nam_sel_cur_krz.length; d_d++){
					nam_sel_cur_krz[d_d].style.display=\'block\';
				}
			}
		}
	</script>';
	
   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   // ФОРМА /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		echo "</form>\n";

		$usedres[] = "0";
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE='".$pdate."') and (SMEN='".$smena."') order by ORD");
		while($res = mysql_fetch_array($xxx)) {
			$usedres[] = $res["ID_resurs"];
		}

		echo "<table style='width: 100%; padding: 0px;' cellpadding='0' cellspacing='0'><tr><td id='links_btn' style='text-align: left;'>\n";
			if (($editing) && (db_adcheck("db_zadanres"))) {
				echo "<div name='add_pers' class='links'>";
				echo "<span class='popup' onClick='chClass(this,\"hpopup\",\"popup\");'>Добавить ресурс";
					echo "<div class='popup' onClick='window.event.cancelBubble = true;'>";
					echo "<form method='post' action='".$pageurl."' style='padding: 0px; margin: 0px;'>";
					echo "<SELECT name='addnewres[]' style='height: 300px;' MULTIPLE>";
						$xxx2 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where ((ID_resurs != '0') and ((ID_otdel = '18') or (ID_otdel = '19') or (ID_otdel = '21') or (ID_otdel = '22'))) ");
						$fruits_1 = array();
						while($res2 = mysql_fetch_array($xxx2)){
							$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID = '".$res2['ID_resurs']."') ");
							$res = mysql_fetch_array($xxx);
							$fruits_1[$res["ID"]] = $res["NAME"];
						}
						$xxx3 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where ((ID_resurs != '0') and (ID_otdel != '18') and (ID_otdel != '19') and (ID_otdel != '21') and (ID_otdel != '22')) ");
						$fruits_2 = array();
						while($res3 = mysql_fetch_array($xxx3)){
							$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID = '".$res3['ID_resurs']."') ");
							$res = mysql_fetch_array($xxx);
							$fruits_2[$res["ID"]] = $res["NAME"];
						}
asort($fruits_1);
asort($fruits_2);
    echo "<option style='color:red; width:150px;' value='0'>--- (производство)";
foreach ($fruits_1 as $keey_1 => $vaal_1) {
    echo "<option style='width:150px;' value='".$keey_1."'>".$vaal_1;
}
    echo "<option style='color:red; width:150px;' value='0'>--- (остальной персонал)";
foreach ($fruits_2 as $keey_1 => $vaal_1) {
    echo "<option style='width:150px;' value='".$keey_1."'>".$vaal_1;
}
	
	
					echo "</SELECT>";
					echo "<br><br><input type='submit' value='Добавить'>";
					echo "</form>";
					echo "</div>";
				echo "</span>\n";
				echo " | <a class='acl' href='$pageurl&addbytabel'>Из табеля</a>";
				echo "</div>";
			}
				
		echo "</td><td style='text-align: right;'>";
			echo "<div class='links'>";
			echo $smena." смена <a id='smen_dt_sz' class='acl' href='$calendar_url'>".$date."</a><br><br>";
			echo (in_array('1', $user_rightgroups) || in_array('71', $user_rightgroups) ? "<a class='acl' href='index.php?do=show&formid=219&p0=".$_GET['p0']."&p1=".$_GET['p1']."' id='print_archivarius' target='_blank'>Печать реестра смены</a>&nbsp;&nbsp;|&nbsp;&nbsp;" : '');
			echo "<a id='prnt_dt_sz2' class='acl' href='index.php?do=show&formid=210&p0=".$_GET['p0']."&p1=".$_GET['p1']."' target='_blank'>Печать сводной</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
			echo "<a id='prnt_dt_sz3' class='acl' href='index.php?do=show&formid=211&p0=".$_GET['p0']."&p1=".$_GET['p1']."' target='_blank'>Версия для печати (новая)</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
			echo "<a id='prnt_dt_sz' class='acl' href='".$print_url."' target='_blank'>Версия для печати</a>";
			echo "</div>";
		echo "</td></tr></table><br>";

	   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////

		echo "\n\n<!-- form -->\n<form id='form1x' method='post' action='".$pageurl."'>\n";

		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1650px;' border='1' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>\n";
		echo "<td rowspan='2' width='40'>№</td>\n";
		echo "<td rowspan='2' width='40'>ID</td>\n";
		echo "<td name='links_btn2'></td>\n";
		echo "<td colspan='2'>Операция</td>\n";
		echo "<td rowspan='2' width='160'>Оборудование</td>\n";
		echo "<td rowspan='2' width='55'>Деталей в работе/Сделано факт.</td>\n";
		echo "<td colspan='2'>План</td>\n";
		echo "<td rowspan='2' width='80'>На заказ<br><b>осталось</b> / всего,<br>Н/Ч (шт) / <br>норма на ед.</td>\n";
		echo "<td colspan='4'>Факт</td>\n";
		echo "<td rowspan='2' width='50'>Откуда<br>взять</td>\n";
		echo "<td rowspan='2' width='50'>Куда<br>положить</td>\n";
		echo "<td colspan='2' rowspan='2' width='50'>multiselect</td>\n";
		echo "</tr>\n";


		echo "<tr class='first'>\n";
		echo "<td width='300'>Заказ / ДСЕ</td>\n";
		echo "<td width='20'>№</td>\n";
		echo "<td>Наименование</td>\n";
		echo "<td width='60'>Кол-во</td>\n";
		echo "<td width='50'>Н/Ч</td>\n";
		echo "<td width='60'>Кол-во</td>\n";
		echo "<td width='50'>Н/Ч</td>\n";
		echo "<td width='50'>Затр.<br>время, ч</td>\n";
		echo "<td width='150'>Причина невыполнения</td>\n";
		echo "</tr>\n";
		echo "</thead>";


		echo "<tbody>";
	   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
		$RsursIDs = Array();
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE='".$pdate."') and (SMEN='".$smena."') order by ORD");
		while($res = mysql_fetch_array($xxx)) {
			$RsursIDs[] = $res["ID_resurs"];
		}
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs order by binary (NAME)");
		while($resurs = mysql_fetch_array($xxx)) {
		   if (in_array($resurs["ID"],$RsursIDs)) {
			$xxxres = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE='".$pdate."') and (SMEN='".$smena."') and (ID_resurs='".$resurs["ID"]."')");
			$res = mysql_fetch_array($xxxres);
			OpenID($res);
		   }
		}
		echo "</tbody>";

		echo "</table>\n";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

/////////////////////////     изменение титульника по названию смены
$title = "СЗ. ".$smena."см. ".$date;

echo "<script language='javascript'>
var per_clik_page;
function add_numsumm(id_res, id_oper, n_arr, val){
	var req = getXmlHttp();
	req.open('GET', 'project/zadan/zadanres_numsumm.php?p1='+id_res+'&p2='+id		+'&p3='+n_arr+'&p4='+val);
	req.send(null);
}
function sel_all_zad(id_res, obj){
	if (obj.checked==true){
		var all_checked = document.getElementsByName('cur_zad_sel').length;
		for (var a_a=0; a_a < all_checked; a_a++){
			if (document.getElementsByName('cur_zad_sel')[a_a].getAttribute(\"name2\")=='parent_res_'+id_res){
				document.getElementsByName('cur_zad_sel')[a_a].checked=true;
			}
		}
	}
	if (obj.checked==false){
		var all_checked = document.getElementsByName('cur_zad_sel').length;
		for (var a_a=0; a_a < all_checked; a_a++){
			if (document.getElementsByName('cur_zad_sel')[a_a].getAttribute(\"name2\")=='parent_res_'+id_res){
				document.getElementsByName('cur_zad_sel')[a_a].checked=false;
			}
		}
	}
}
function reload_page(){
	clearTimeout(per_clik_page);
	per_clik_page = setTimeout('reload_page2()',500);
}
function reload_page2(){
	location.href='".$pageurl."';
}
function vote5(obj, zadan_id, operit_id, id_res){
	var l_zad_res = document.getElementsByName('cur_zad_sel').length;
	var arr_zadan_id = '';
	var arr_operit_id = '';
	var ind_count = 0;
	for (var a_b=0; a_b < l_zad_res; a_b++){
		if (document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name3')=='0'){			
			if (document.getElementsByName('cur_zad_sel')[a_b].checked==true){
				arr_zadan_id = arr_zadan_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('id').substr(9)+'|';
				arr_operit_id = arr_operit_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name4')+'|';
				
				var cur_tr_id = document.getElementsByName('cur_zad_sel')[a_b].parentNode.parentNode.getAttribute('id').substr(5);
				document.getElementById('row3_'+cur_tr_id).setAttribute('style','display:none;');
				document.getElementById('row1_'+cur_tr_id).setAttribute('style','display:none;');
				ind_count = ind_count + 1;
			}
		}
	}
	
	if (ind_count==0) {
		arr_zadan_id = zadan_id;
		arr_operit_id = operit_id;
		
		document.getElementById('row3_'+zadan_id).setAttribute('style','display:none;');
		document.getElementById('row1_'+zadan_id).setAttribute('style','display:none;');
	}
	
	var req = getXmlHttp();
	req.open('GET', 'zadanres_delzad.php?id='+arr_zadan_id+'&operitems='+arr_operit_id);
	req.send(null);
}
function vote6(obj, zadan_id, operit_id, id_res){
	obj.setAttribute('style','display:none;')

	var l_zad_res = document.getElementsByName('cur_zad_sel').length;
	var arr_zadan_id = '';
	var arr_operit_id = '';
	var ind_count = 0;
	for (var a_b=0; a_b < l_zad_res; a_b++){
		if (document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name3')=='0'){			
			if (document.getElementsByName('cur_zad_sel')[a_b].checked==true){
				arr_zadan_id = arr_zadan_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('id').substr(9)+'|';
				arr_operit_id = arr_operit_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name4')+'|';
				
				ind_count = ind_count + 1;
			}
		}
	}
	
	if (ind_count==0) {
		arr_zadan_id = zadan_id;
		arr_operit_id = operit_id;
	}
		
	var req = getXmlHttp();
	req.open('GET', 'zadanres_okzad.php?id='+arr_zadan_id+'&operitems='+arr_operit_id);
	req.send(null);
}
function vote7(obj, zadan_id, operit_id, id_res){
	obj.setAttribute('style','display:none;')
	
	var l_zad_res = document.getElementsByName('cur_zad_sel').length;
	var arr_zadan_id = '';
	var arr_operit_id = '';
	var ind_count = 0;
	for (var a_b=0; a_b < l_zad_res; a_b++){
		if (document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name3')=='1'){			
			if (document.getElementsByName('cur_zad_sel')[a_b].checked==true){
				arr_zadan_id = arr_zadan_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('id').substr(9)+'|';
				arr_operit_id = arr_operit_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name4')+'|';
				
				ind_count = ind_count + 1;
			}
		}
	}
	
	if (ind_count==0) {
		arr_zadan_id = zadan_id;
		arr_operit_id = operit_id;
	}
		
	var req = getXmlHttp();
	req.open('GET', 'zadanres_restzad.php?id='+arr_zadan_id+'&operitems='+arr_operit_id);
	req.send(null);
}
if (document.getElementsByClassName('highlite')){
	var trall = document.getElementsByClassName('highlite').length;
	for (var trind = 0; trind < (trall/2); trind++){
		if (document.getElementsByClassName('highlite')[(trind*2)].getElementsByTagName('td')[0]){
			var tdval = 1, tdsum;
			tdsum=tdval+trind;
			document.getElementsByClassName('highlite')[(trind*2)].getElementsByTagName('td')[0].innerText = tdsum;
		}
	}
}
window.onload = function(){
	document.getElementsByName('links_btn2')[1].innerHTML = document.getElementById('links_btn').innerHTML;
	document.getElementsByName('add_pers')[1].style.margin='0px 0px 0px 0px';
	document.getElementsByName('add_pers')[1].getElementsByTagName('span')[0].setAttribute('style', 'font-size:9pt;');
	document.getElementsByName('add_pers')[1].getElementsByTagName('a')[0].setAttribute('style', 'font-size:9pt;');
	document.getElementsByName('links_btn2')[1].innerHTML = document.getElementsByName('links_btn2')[1].innerHTML + '<a href=\"'+document.getElementById(`smen_dt_sz`).href+'\">'+document.getElementById(`smen_dt_sz`).innerText+'</a> | <a target=\"_blank\" href=\"'+document.getElementById(`prnt_dt_sz2`).href+'\">Сводная</a> | <a target=\"_blank\" href=\"'+document.getElementById(`prnt_dt_sz3`).href+'\">Печать (new)</a> | <a target=\"_blank\" href=\"'+document.getElementById(`prnt_dt_sz`).href+'\">Версия для печати</a>';
}
</script>";

?>