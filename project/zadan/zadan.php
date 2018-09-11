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


		$inwork = Array();
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (SMEN = '".$smena."') and (ID_resurs = '".$ID_resurs."')");
		while($res = mysql_fetch_array($result)) {
			$inwork[] = $res["ID_operitems"];
		}
		$inwork = implode("|", $inwork);

$all_p_o = 1;
if ($_GET['p4']==1){
	$all_p_o = 0;
}	
echo "
<script language='javascript'>

	function loaddata(obj_id, id_zak, opened) {

		var req = getXmlHttp();

		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					obj = document.getElementById(obj_id);
					obj.innerHTML = req.responseText;
				}
			}
		}

		req.open('GET', 'project/zadan/zadanid.php?p4=".$all_p_o."&idzak='+id_zak+'&date=$pdate&smen=$smena&resurs=$ID_resurs'+opened, true);
		req.send(null);
	}
	
	function addzadan(obj_id, id_oper) {					
					if(document.getElementById(obj_id)){
						obj = document.getElementById(obj_id);
						obj.innerHTML = '........';
					}
					if(obj_id){
						obj_id.innerHTML = '........';
					}
					
		var req = getXmlHttp();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					if(document.getElementById(obj_id)){
						obj = document.getElementById(obj_id);
						obj.innerHTML = '<img src=\"uses/ok.png\" style=\"cursor:pointer;\" onclick=\"if (confirm(`Вернуть?`)){ delzada('+obj_id+', '+id_oper+');}\">';
					}
					if(obj_id){
						obj_id.innerHTML = '<img src=\"uses/ok.png\" style=\"cursor:pointer;\" onclick=\"if (confirm(`Вернуть?`)){ delzada('+obj_id.id+', '+id_oper+');}\">';
					}
				}
			}
		}
		req.open('GET', 'project/zadan/zadanadd.php?date=".$pdate."&smen=".$smena."&resurs=".$ID_resurs."&idoper='+id_oper, true);
		req.send(null);
	}

	function delzada(obj_id, id_oper) {
					if(document.getElementById(obj_id)){
						obj = document.getElementById(obj_id);
						obj.innerHTML = '........';
					}
					if(obj_id){
						obj_id.innerHTML = '........';
					}
					
		var req = getXmlHttp();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					if(document.getElementById(obj_id)){
						obj.innerHTML = '<a href=\"javascript:void(0);\" onClick=\"addzadan('+obj_id+','+id_oper+');\">>>></a>';
					}
					if(obj_id){
						obj_id.innerHTML = '<a href=\"javascript:void(0);\" onClick=\"addzadan('+obj_id.id+','+id_oper+');\">>>></a>';
					}
				}
			}
		}
		req.open('GET', 'project/zadan/zadandel.php?date=".$pdate."&smen=".$smena."&resurs=".$ID_resurs."&idoper='+id_oper, true);
		req.send(null);
	}

</script>
";

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


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
		echo "<td class='Field' id='loi_".$i."'>";
		if (db_adcheck("db_zadan")) echo "<a href='javascript:void(0);' onClick='addzadan(\"loi_".$i."\",".$i.");'>>>></a>";
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

	echo "<form id='form1x'>";


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
		echo "<td><a href='".$back_url."' style='float:left; text-decoration: none;'><<< Назад</a>Заказ / ДСЕ</td>\n";
		echo "<td width='20'>№<br>МТК</td>\n";
		echo "<td width='180'>Операция</td>\n";
		echo "<td width='100'>Оборудование</td>\n";
		echo "<td width='50'>На заказ,<br>Н/Ч</td>\n";
		echo "<td width='100'></td>\n";
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

	   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>\n";
		echo "<td width='30'>№<br>МТК</td>\n";
		if ($_GET['p4']){
			echo "<td><a href='".$back_url."' style='float:left; text-decoration: none;'><<< Назад</a>Заказ / ДСЕ / Операция<a style='cursor:pointer; float:right;' onclick='location.href=\"index.php?do=show&formid=65&p0=".$_GET['p0']."&p1=".$_GET['p1']."&p2=".$_GET['p2']."\"'>Показать только приоритетные</a></td>\n";
		}else{
			echo "<td><a href='".$back_url."' style='float:left; text-decoration: none;'><<< Назад</a>Заказ / ДСЕ / Операция<a style='cursor:pointer; float:right;' onclick='location.href=\"index.php?do=show&formid=65&p0=".$_GET['p0']."&p1=".$_GET['p1']."&p2=".$_GET['p2']."&p4=1\"'>Показать все операции</a></td>\n";
		}
		echo "<td width='120'>Оборудование</td>\n";
		echo "<td width='100'>Сообщение ПП</td>\n";
		echo "<td width='100'></td>\n";
		echo "</tr>\n";
		echo "</thead>";

	   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
		echo "<tbody>";
		echo "<tr><td colspan='6' style='padding: 0px; border: 0px solid #FFF;'>";
		$result = dbquery("SELECT  ID, TID, NAME, DSE_NAME  FROM ".$db_prefix."db_zak where (EDIT_STATE = '0') and (INSZ = '1') order by ORD");
		while($res = mysql_fetch_array($result)) {
			echo "<div id='zak_".$res["ID"]."'></div>\n";
			echo "<script language='javascript'>\n";
			echo "	loaddata(\"zak_".$res["ID"]."\", ".$res["ID"].", \"&opened\");\n";
			echo "</script>\n";
		}
		echo "</td></tr>";
		echo "</tbody>";

		echo "</table>\n";
		echo "<br><br><br>";

	}
	echo "<div class='links'>";
		echo "<a href='".$back_url."'>Назад</a><br><br>";
	echo "</div>";
}

$title = " ".$smena." см.  ".$date;

?>
<script>
	function zapr_pp(obj, id_zak, id_dse, id_op){
		if(confirm("Послать запрос в КТО?")){
			obj.parentNode.parentNode.parentNode.parentNode.parentNode.className='Field';
			obj.parentNode.parentNode.getElementsByTagName('textarea')[0].disabled=true;
			obj.style.display='none';
			vote(obj,'MSG_INFO_operitems.php?id='+id_op+'&value='+obj.parentNode.parentNode.getElementsByTagName('textarea')[0].value);
			vote(obj,'zapros_MTK_PP.php?p1='+id_op+'&p2='+id_dse+'&p3='+id_zak);
		}
	}	
</script>