<style>
	a.acl {
		text-decoration: none;
		color: black;
	}
	a.acl:hover {
		text-decoration: none;
		color: blue;
	}
</style>
<?php


	if (!defined("MAV_ERP")) { die("Access Denied"); }

	$step = 1;

	$zak_IDs = $_GET["p0"];

	$url = "index.php?do=show&formid=23&p0=";

	if (count($zak_IDs)>0) $step = 2;


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
			$xx = $str[$i];
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


	echo "<h2>Состояние выполнения заказов - выбор заказов (Аннулированные)</h2><br/><a style='float:right' href='/?do=show&formid=31'>В работе</a><br/><br/>";

	render_item(80,false,false,false,false,"(EDIT_STATE='2')","","order by ORD","");


}

if ($step==2) {

   // Шапка
	$arr_zak_ids = "";
	foreach ($zak_IDs as $k1 => $v1) {
		if ($k1==(count($zak_IDs)-1)) {
			$arr_zak_ids = $arr_zak_ids.$v1;
		}else{
			$arr_zak_ids = $arr_zak_ids.$v1."|";
		}
	}

	echo "<h2>Состояние выполнения заказов (Аннулированные)</h2><input type=button value='Экспортировать в Excel' onclick='window.open(\"project/print/stat_8_2_2.php?p2=".$arr_zak_ids."\");'><br><br>";


	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "	<thead>\n";
	echo "<tr class='first'>\n";
	echo "<td colspan='2'>Заказ</td>\n";
	echo "<td rowspan='2'>Наименование ДСЕ</td>\n";
	echo "<td rowspan='2'>Чертёж</td>\n";
	echo "<td colspan='2'>Операция</td>\n";
	echo "<td rowspan='2'>Оборуд.</td>\n";
	echo "<td colspan='2'>План</td>\n";
	echo "<td colspan='2'>Факт</td>\n";
	echo "<td colspan='2'>Осталось</td>\n";
	echo "<td rowspan='2'>Затр. часы</td>\n";
	echo "</tr>\n";

	echo "<tr class='first'>\n";
	echo "<td>Вид</td>\n";
	echo "<td>Номер</td>\n";
	echo "<td>Номер</td>\n";
	echo "<td>Наименование</td>\n";
	echo "<td>Шт</td>\n";
	echo "<td>Н/Ч</td>\n";
	echo "<td>Шт</td>\n";
	echo "<td>Н/Ч</td>\n";
	echo "<td>Шт</td>\n";
	echo "<td>Н/Ч</td>\n";
	echo "</tr>\n";
	echo "	</thead>\n";

	echo "	<tbody>\n";


	function FF($x) {
		$res = $x;
		if ($x==0) $res = "";
		return $res;
	}

	function OutMTK($izd) {
		global $db_prefix, $sf, $snf, $snn, $url;

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zakdet='".$izd["ID"]."') order by ORD");
		while ($oper = mysql_fetch_array($xxx)) {

			$num = 0;
			$f = 0;
			$nf = 0;

			$yyy = dbquery("SELECT FACT, NORM_FACT, NUM_FACT FROM ".$db_prefix."db_zadan where (ID_operitems='".$oper["ID"]."') order by ID");
			while ($zad = mysql_fetch_array($yyy)) {
				$num = $num + $zad["NUM_FACT"]*1;
				$f = $f + $zad["FACT"]*1;
				$nf = $nf + $zad["NORM_FACT"]*1;
			}

			$sf = $sf + $f;
			$snf = $snf + $nf;
			$snn = $snn + $oper["NORM_ZAK"]*1;
			$rcount = $izd["RCOUNT"]*1;
			$rnorm = $oper["NORM_ZAK"]*1;

			echo "<tr>\n";
			echo "<td class='Field'></td>\n";
			echo "<td class='Field'></td>\n";
			echo "<td class='Field'></td>\n";
			echo "<td class='Field'></td>\n";
			echo "<td class='Field'>".$oper["ORD"]."</td>\n";
			echo "<td class='Field AL'>".FVal($oper,"db_operitems","ID_oper")."</td>\n";
			echo "<td class='Field AL'>".FVal($oper,"db_operitems","ID_park")."</td>\n";
			echo "<td name='replac2' class='Field'>".$rcount."</td>\n";
			echo "<td name='replac2' class='Field'>".$rnorm."</td>\n";
			echo "<td name='replac2' class='Field'><a class='acl' href='".$url.$oper["ID"]."' target='_blank'>".FF($num)."</a></td>\n";
			echo "<td name='replac2' class='Field'><a class='acl' href='".$url.$oper["ID"]."' target='_blank'>".FF($nf)."</a></td>\n";			
			echo "<td name='replac2' class='Field'>".round(FF($rcount-$num),2)."</td>\n";
			echo "<td name='replac2' class='Field'>".round(FF($rnorm-$nf),2)."</td>\n";
			echo "<td name='replac2' class='Field'><a class='acl' href='".$url.$oper["ID"]."' target='_blank'>".FF($f)."</a></td>\n";
			echo "</tr>\n";

		}
	}

	function OutIZD($izd,$n) {
		global $db_prefix;

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (PID='".$izd["ID"]."')");
		while ($chld = mysql_fetch_array($xxx)) {

		$xxx3 = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$izd["ID_zak"]."')");
		$zak2 = mysql_fetch_array($xxx3);


			echo "<tr>\n";
			echo "<td class='Field'><b>".FVal($zak2,"db_zak","TID")."</b></td>\n";
			echo "<td class='Field'><b>".FVal($zak2,"db_zak","NAME")."</b></td>\n";
			echo "<td class='Field AL'><b>".$n." ".$chld["NAME"]."</b></td>\n";
			echo "<td class='Field AL' colspan='11'>".$chld["OBOZ"]."</td>\n";
			echo "</tr>\n";

			OutMTK($chld);

			OutIZD($chld,$n." ... /");

		}
	}

	for ($j=0;$j < count($zak_IDs);$j++) {

		$snn = 0;
		$sf = 0;
		$snf = 0;

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$zak_IDs[$j]."')");
		$zak = mysql_fetch_array($xxx);

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak='".$zak_IDs[$j]."') and (PID='0')");
		$izd = mysql_fetch_array($xxx);

		echo "<tr>\n";
		echo "<td class='Field'><b>".FVal($zak,"db_zak","TID")."</b></td>\n";
		echo "<td class='Field'><b>".FVal($zak,"db_zak","NAME")."</b></td>\n";
		echo "<td class='Field AL'><b>".$izd["NAME"]."</b></td>\n";
		echo "<td class='Field AL' colspan='11'>".$izd["OBOZ"]."</td>\n";
		echo "</tr>\n";

		// Выдали по сути первый ДСЕ
		OutMTK($izd);
		OutIZD($izd," ... /");

		echo "<tr>\n";
		echo "<td class='Field' colspan='8' style='text-align: right;'><b>Итого:</b></td>\n";
		echo "<td name='replac2' class='Field'><b>$snn</b></td>\n";
		echo "<td class='Field'><b></b></td>\n";
		echo "<td name='replac2' class='Field'><b>$snf</b></td>\n";
		echo "<td class='Field'><b></b></td>\n";
		echo "<td name='replac2' class='Field'><b>".($snn-$snf)."</b></td>\n";
		echo "<td name='replac2' class='Field'><b>$sf</b></td>\n";
		echo "</tr>\n";
	}

	echo "	</tbody>\n";
	echo "</table>\n";
			
echo "<script type='text/javascript'>
for (var ss=0; ss < document.getElementsByName('replac2').length; ss++){
	var sy;
	var sk = 0;
	var ss2 = document.getElementsByName('replac2')[ss].innerText.length;
	for (var st=0; st < ss2; st++){
		if (document.getElementsByName('replac2')[ss].innerText.substr(st, 1) == '.') {
			sy = st;
			sk = 1;
		}
	}
	if (sk == 1) {
		var sh = document.getElementsByName('replac2')[ss].innerText.substr(0, sy);
		var sj = document.getElementsByName('replac2')[ss].innerText.substr((sy+1), ss2)
		document.getElementsByName('replac2')[ss].innerText = sh + ',' + sj;
	}
	sk = 0;
}
</script>";
}
?>
