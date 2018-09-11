<?php
	Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); //Дата в прошлом 
	Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
	Header("Pragma: no-cache"); // HTTP/1.1 
	Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
<meta http-equiv='Content-Type' content='text/html; charset=windows-1251'>

</head>
<style> 
<!--
body {
	padding: 0px;
	margin: 0px;
	background: #ccd5e8;
}
#PageTable {
	BORDER : 2px solid black;
        COLOR : #000;
        BORDER-COLLAPSE : collapse;
        Text-Align : center;
	Vertical-Align : middle;
	padding: 0px;
	margin: 0px;
}
#PageTable TD {
	BORDER : 1px solid black;
	PADDING : 5px;
	font : normal 12pt Verdana;
	height : 19px;
	background: #fff;
}
#PageTable TD.fff {
        Text-Align : left;
	color: #000;
}
#PageTable TR {
	height: 30px;
}
#PageTable TR.first {
	height: 30px;
}
#PageTable TR.first TD {
	background: #adbad8 URL(img/bgtop.gif) repeat-x;
	PADDING : 10px;
	font : bold 12pt Verdana;
	BORDER : black 2px solid;
	color: #fff;
}
#PageTable TR.first TD.low {
        font : normal 9pt Verdana;
}
#PageTable TR.hl TD {
        background: #ccd5e8;
}

-->
</style>
<body scroll="yes">
<script>

document.addEventListener('DOMContentLoaded', function(){ 	
	setTimeout(function(){
		var timer;

		if (document.body.scrollTop == 0) {
			timer = setTimeout(function(){ location.href = document.location }, 3000);
		} else {
			clearTimeout(timer);
		}
	}, 1000);
}, false);
</script>
<table ID='PageTable' border='0' cellpadding='0' cellspacing='0' width='100%'>
<thead id='thead_tr_1'>
	<tr class='first'>
	<td>Ф.И.О.</td>
	<td>Должность</td>
	<td colspan='5'>С начала месяца</td>
	<td colspan='4'>Последняя смена</td>
	</tr>
	<tr class='first'>
	<td>Ф.И.О.</td>
	<td>Должность</td>
	<td class='low'>План / Факт<br>ч</td>
	<td class='low'>План / Факт<br>Н/Ч</td>
	<td class='low'>Факт Н/Ч / Факт ч</td>
	<td class='low'>Прогулы</td>
	<td class='low'>Опоздания</td>
	<td class='low'>Дата / номер</td>
	<td class='low'>План / Факт<br>ч</td>
	<td class='low'>План / Факт<br>Н/Ч</td>
	<td class='low'>Факт Н/Ч / Факт ч</td>
	</tr>
</thead>
<thead id='thead_tr_2'>
	<tr class='first' style='position:fixed; top:0px;'>
	<td>Ф.И.О.</td>
	<td>Должность</td>
	<td colspan='5'>С начала месяца</td>
	<td colspan='4'>Последняя смена</td>
	</tr>
	<tr class='first' style='position:fixed; top:40px;'>
	<td>Ф.И.О.</td>
	<td>Должность</td>
	<td class='low'>План / Факт<br>ч</td>
	<td class='low'>План / Факт<br>Н/Ч</td>
	<td class='low'>Факт Н/Ч / Факт ч</td>
	<td class='low'>Прогулы</td>
	<td class='low'>Опоздания</td>
	<td class='low'>Дата / номер</td>
	<td class='low'>План / Факт<br>ч</td>
	<td class='low'>План / Факт<br>Н/Ч</td>
	<td class='low'>Факт Н/Ч / Факт ч</td>
	</tr>
</thead>
<?php
/////////////////////////////////////////////////////////////////////////////////////////////
//
//	Вывод на информационный монитор 1
//
/////////////////////////////////////////////////////////////////////////////////////////////

define("MAV_ERP", TRUE);
include "../../../config.php";
include "../../../includes/database.php";
include "../../db_cfg.php";
include "../../../db_func.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$count = 27;

$p = 0;
if (isset($_GET["p"])) $p = $_GET["p"];
if (isset($_GET["all"])) {
	$p = 0;
	$count = 10000;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//
//	Функции
//
/////////////////////////////////////////////////////////////////////////////////////////////

	function FReal($x) {
		$ret = number_format( $x, 1, ',', ' ');
		if ($x==floor($x)) $ret = number_format($x, 0, ',', ' ');
		return $ret;
	}
	function F2Real($x) {
		$ret = number_format( $x, 2, ',', ' ');
		if ($x==floor($x)) $ret = number_format($x, 0, ',', ' ');
		if ($x<=0.7) $ret = "<b style='color: red;'>".$ret."</b>";
		return $ret;
	}

	function FDReal($x,$y) {

		$ret = "~";
		if ($y*1!==0) $ret = F2Real($x/($y*1));
		return $ret;
	}

	function TodayDate() {
		$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
		$value=date("d.m.Y",$theday);
		return $value;
	}


	function OpenID($item) {
		global $db_prefix, $hl, $startday, $endday, $today, $tids;


	   // Цвет
		$cl = "";
		if ($hl) $cl = " class='hl'";
		$hl = !$hl;

		echo "<tr".$cl.">";
		echo "<td class='fff' style='font : bold 16pt Verdana;'>".$item["NAME"]."</td>";


		$prof = "";
		$xxxs = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID_resurs = '".$item["ID"]."')");
		if ($shtat = mysql_fetch_array($xxxs)) {
			$prof = FVal($shtat,"db_shtat","ID_special")." ".FVal($shtat,"db_shtat","ID_speclvl");
		}

		echo "<td class='fff' style='font : normal 10pt Verdana; border-right: 2px solid black;'>".$prof."</td>";


				$m_plan = 0;
				$m_n_plan = 0;
				$m_fact = 0;
				$m_n_fact = 0;
				$m_spec = 0;
				$m_prog = 0;
				$m_opozd = 0;

				$d_planfact = "";
				$d_n_planfact = "";
				$date_txt = "";
				$date = 0;
				$d_n_plan = 0;
				$d_n_fact = 0;
				$d_spec = 0;

	// ЗА МЕСЯЦ

		// Расчёты с табеля
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (ID_resurs='".$item["ID"]."') and (DATE>'".$startday."') and (DATE<'".$today."')");
			while ($tab = mysql_fetch_array($xxx)) {
				$m_plan = $m_plan + $tab["PLAN"]*1;
				$m_spec = $m_spec + $tab["SPEC"]*1;
				if (($tab["TID"]*1==7) or ($tab["TID"]*1==0)) $m_fact = $m_fact + $tab["FACT"]*1;
				if ($tab["TID"]*1==6) $m_prog = $m_prog + 1;
				if ($tab["OPOZD"]*1==1) $m_opozd = $m_opozd + 1;
			}

		// Расчёты с СЗ По заданиям к заказам
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (ID_resurs='".$item["ID"]."') and (DATE>'".$startday."') and (DATE<'".$today."')");
			while ($zad = mysql_fetch_array($xxx)) {
				$m_n_plan = $m_n_plan + $zad["NORM"]*1;
				$m_n_fact = $m_n_fact + $zad["NORM_FACT"]*1;
				//if ($zad["SPEC"]*1==1) {
				//	$m_spec = $m_spec + $zad["NORM_FACT"]*1;
				//	$m_spec_h = $m_spec_h + $zad["FACT"]*1;
				//}
			}

		// Расчёты с СЗ По заданиям к хоз. заказам
			//$xxx = dbquery("SELECT * FROM ".$db_prefix."db_hozitems where (ID_resurs='".$item["ID"]."') and (DATE>'".$startday."') and (DATE<='".$today."')");
			//while ($zad = mysql_fetch_array($xxx)) {
			//	$m_n_plan = $m_n_plan + $zad["NORM"]*1;
			//	$m_n_fact = $m_n_fact + $zad["FACT"]*1;
			//}


	// ПОСЛЕДНЯЯ СМЕНА

		$N_cl = "";

		// Расчёты с табеля
			$d_fact = 0;
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (ID_resurs='".$item["ID"]."') and (DATE<'".$today."') order by DATE desc limit 0,1");
			if ($tab = mysql_fetch_array($xxx)) {
				$date = $tab["DATE"];
				$date_txt = IntToDate($tab["DATE"]*1)." / ".$tab["SMEN"];
				$d_spec = $tab["SPEC"]*1;
				$d_planfact = FReal($tab["PLAN"]*1)." / ".FReal($tab["FACT"]*1);
				$d_fact = $tab["FACT"]*1;
				if ($tab["TID"]*1>0) $d_planfact = FReal($tab["PLAN"]*1)." / <b>".$tids[$tab["TID"]-1]."</b>";
				if ($tab["TID"]*1==6) $N_cl = " style='color: red;'";
				if ($tab["TID"]*1==1) $d_planfact = FReal($tab["PLAN"]*1)." / ".FReal($tab["FACT"]*1)." <b>".$tids[$tab["TID"]-1]."</b>";
				if ($tab["TID"]*1==2) $d_planfact = FReal($tab["PLAN"]*1)." / ".FReal($tab["FACT"]*1)." <b>".$tids[$tab["TID"]-1]."</b>";
				if ($tab["OPOZD"]*1==1) $d_planfact = FReal($tab["PLAN"]*1)." / ".FReal($tab["FACT"]*1)." <b style='color: red;'>!</b>";
			}

		// Расчёты с СЗ По заданиям к заказам
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (ID_resurs='".$item["ID"]."') and (DATE='".$date."')");
			while ($zad = mysql_fetch_array($xxx)) {
				$d_n_plan = $d_n_plan + $zad["NORM"]*1;
				$d_n_fact = $d_n_fact + $zad["NORM_FACT"]*1;
				//if ($zad["SPEC"]*1==1) {
				//	$d_spec = $d_spec + $zad["NORM_FACT"]*1;
				//	$d_spec_h = $d_spec_h + $zad["FACT"]*1;
				//}
			}




		// Подготовка для вывода
			$m_prog_txt = "";
			if ($m_prog>0) $m_prog_txt = "<b style='color: red;'>".$m_prog."</b>";
			$m_opozd_txt = "";
			if ($m_opozd>0) $m_opozd_txt = "<b style='color: red;'>".$m_opozd."</b>";
			$m_spec_txt = "";
			if ($m_spec>0) $m_spec_txt = "<b style='color: green;'>".FReal($m_spec)."</b>";
			$d_spec_txt = "";
			if ($d_spec>0) $d_spec_txt = "<b style='color: green;'>".FReal($d_spec)."</b>";

		echo "<td>".FReal($m_plan)." / ".FReal($m_fact)."</td>";
		echo "<td>".FReal($m_n_plan)." / ".FReal($m_n_fact)."</td>";
		echo "<td>".FDReal($m_n_fact,$m_fact)."</td>"; //$m_spec_txt
		echo "<td>".$m_prog_txt."</td>";
		echo "<td style='border-right: 2px solid black;'>".$m_opozd_txt."</td>";
		echo "<td".$N_cl.">".$date_txt."</td>";
		echo "<td".$N_cl.">".$d_planfact."</td>";
		echo "<td".$N_cl.">".FReal($d_n_plan)." / ".FReal($d_n_fact)."</td>";
		echo "<td".$N_cl.">".FDReal($d_n_fact,$d_fact)."</td>"; //$d_spec_txt

	}






   // Приготовления

	$tids = Array('О','А','У','Б','Ё','Н','Н/О','Н/А','Н/Б','К');
	$DI_WName = Array('Пн','Вт','Ср','Чт','Пт','Сб','Вс');
	$DI_MName = Array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');

	
	if ($p==0) {
		$count = $count-1;
		$start = 0;
	} else {
		$start = $count-1+($count*($p-1));
	}


	$hl = false;

	$today = TodayDate();
	if (isset($_GET["date"])) $today = $_GET["date"];
	$today = explode(".",$today);
	$startday = $today[2]*10000+$today[1]*100+0;
	$endday = $today[2]*10000+$today[1]*100+32;
	$today = $today[2]*10000+$today[1]*100+$today[0];

	$resurs_IDs = Array();
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (INSZ = '1')");
	while($otdel = mysql_fetch_array($xxx)) {
		$xxxs = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID_otdel = '".$otdel["ID"]."')");
		while($shtat = mysql_fetch_array($xxxs)) {
			if (!in_array($shtat["ID_resurs"],$resurs_IDs)) $resurs_IDs[] = $shtat["ID_resurs"];
		}
	}

	if ($p==0) {
   // Out TOTALS

		$hl = true;
		$m_plan = 0;
		$m_n_plan = 0;
		$m_fact = 0;
		$m_n_fact = 0;
		$m_spec = 0;
		$m_prog = 0;
		$m_opozd = 0;

		$resursxxx = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_resurs");
		while($resurs = mysql_fetch_array($resursxxx)) {
		/////////////////////////////////////////////////////////////////////////////
		   if (in_array($resurs["ID"],$resurs_IDs)) {
		   // Расчёты с табеля
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (DATE>'".$startday."') and (DATE<'".$today."') and (ID_resurs='".$resurs["ID"]."')");
			while ($tab = mysql_fetch_array($xxx)) {
				$m_plan = $m_plan + $tab["PLAN"]*1;
				$m_spec = $m_spec + $tab["SPEC"]*1;
				if (($tab["TID"]*1==7) or ($tab["TID"]*1==0)) $m_fact = $m_fact + $tab["FACT"]*1;
				if ($tab["TID"]*1==6) $m_prog = $m_prog + 1;
				if ($tab["OPOZD"]*1==1) $m_opozd = $m_opozd + 1;
			}

		   // Расчёты с СЗ По заданиям к заказам
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE>'".$startday."') and (DATE<'".$today."') and (ID_resurs='".$resurs["ID"]."')");
			while ($zad = mysql_fetch_array($xxx)) {
				$m_n_plan = $m_n_plan + $zad["NORM"]*1;
				$m_n_fact = $m_n_fact + $zad["NORM_FACT"]*1;
				//if ($zad["SPEC"]*1==1) $m_spec = $m_spec + $zad["NORM_FACT"]*1;
			}
		   }
		/////////////////////////////////////////////////////////////////////////////
		}

		// Подготовка для вывода
			$m_prog_txt = "";
			if ($m_prog>0) $m_prog_txt = "<b style='color: red;'>".$m_prog."</b>";
			$m_opozd_txt = "";
			if ($m_opozd>0) $m_opozd_txt = "<b style='color: red;'>".$m_opozd."</b>";
			$m_spec_txt = "";
			if ($m_spec>0) $m_spec_txt = "<b style='color: green;'>".$m_spec."</b>";

		echo "<tr id='total_tr1' style='height: 48px;'>";
		echo "<td colspan='2' style='border-right: 2px solid black; text-align: left; font : bold 16pt Verdana;'><b>ИТОГО ПО ПРЕДПРИЯТИЮ:</b></td>";
		echo "<td><b>".FReal($m_plan)." / ".FReal($m_fact)."</b></td>";
		echo "<td><b>".FReal($m_n_plan)." / ".FReal($m_n_fact)."</b></td>";
		echo "<td><b>".FDReal($m_n_fact,$m_fact)."</b></td>"; // $m_spec_txt
		echo "<td>".$m_prog_txt."</td>";
		echo "<td style='border-right: 2px solid black;'>".$m_opozd_txt."</td>";
		echo "<td colspan='4'></td>";
		echo "</tr>";
		echo "<tr id='total_tr2' style='position:fixed; top:90px; height: 48px;'>";
		echo "<td colspan='2' style='border-right: 2px solid black; text-align: left; font : bold 16pt Verdana;'><b>ИТОГО ПО ПРЕДПРИЯТИЮ:</b></td>";
		echo "<td><b>".FReal($m_plan)." / ".FReal($m_fact)."</b></td>";
		echo "<td><b>".FReal($m_n_plan)." / ".FReal($m_n_fact)."</b></td>";
		echo "<td><b>".FDReal($m_n_fact,$m_fact)."</b></td>"; // $m_spec_txt
		echo "<td>".$m_prog_txt."</td>";
		echo "<td style='border-right: 2px solid black;'>".$m_opozd_txt."</td>";
		echo "<td colspan='4'></td>";
		echo "</tr>";
	}


   // Вывод списка
	$where = "where (ID='".implode("') or (ID='",$resurs_IDs)."')";
	if (count($resurs_IDs)<1) $where = "where (ID='0')";
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs ".$where." order by binary(NAME) limit ".$start.",".$count);
	while($res = mysql_fetch_array($xxx)) {
		OpenID($res);
	}
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs ".$where." order by binary(NAME) limit ".$start.",".$count);
	while($res = mysql_fetch_array($xxx)) {
		OpenID($res);
	}

?>

</table>
</body>

<script>
var set_int = setInterval('scrl_go()', '37');
setTimeout('set_thead()','100');

	
function scrl_go(){
	var scrtlgo = document.body.scrollTop;
	document.body.scrollTop = document.body.scrollTop+2;
	var scrtlgo2 = document.body.scrollTop;
	if (scrtlgo == scrtlgo2){
		clearInterval(set_int);
		location.href = document.location;
	}
}
function set_thead(){
	document.body.scrollTop = 0;
	for(var a_d=0;a_d<document.getElementById('thead_tr_1').rows[0].cells.length;a_d++){
		document.getElementById('thead_tr_2').rows[0].cells[a_d].setAttribute('style','width:'+getComputedStyle(document.getElementById('thead_tr_1').rows[0].cells[a_d]).width+';');
	}
	for(var a_d=0;a_d<document.getElementById('thead_tr_1').rows[1].cells.length;a_d++){
		document.getElementById('thead_tr_2').rows[1].cells[a_d].setAttribute('style','width:'+getComputedStyle(document.getElementById('thead_tr_1').rows[1].cells[a_d]).width+';');
	}
	for(var a_d=0;a_d<document.getElementById('total_tr1').cells.length;a_d++){
		document.getElementById('total_tr2').cells[a_d].setAttribute('style','height:48px; width:'+getComputedStyle(document.getElementById('total_tr1').cells[a_d]).width+';');
	}
}
</script>

</html>