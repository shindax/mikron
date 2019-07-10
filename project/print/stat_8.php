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
error_reporting( 0 );

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
			$ret .= "<b class='error'>".FReal($z)."</b>";
		} else {
			$ret .= FReal($z);
		}
		return $ret;
	}

	function ConvertToVertical($str) {

		$res = "";
		$str_len = strlen($str);
		for ($i=0;$i < $str_len;$i++) {
			$xx = $str[$i];
			if ($xx==" ") $xx = "<br>";
			$res .= "<div class='VCD'>".$xx."</div>";
		}
		return $res;
	}

///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////



if ($step==1) 
{

	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";


	echo "<h2>Состояние выполнения заказов - выбор заказов</h2><br/> <a style='float:right' href='/?do=show&formid=230'>Аннулированные</a><br/><a style='float:right' href='/?do=show&formid=231'>+Материал</a> <br/><br/>";

	render_item(80,false,false,false,false,"(EDIT_STATE='0') and (INSZ='1')","","order by ORD","");


} // if ($step==1) 

if ($step==2) 
{

   // Шапка
	$arr_zak_ids = "";
	foreach ($zak_IDs as $k1 => $v1) {
		if ($k1==(count($zak_IDs)-1)) {
			$arr_zak_ids .= $v1;
		}else{
			$arr_zak_ids .= $v1."|";
		}
	}

	echo "<h2>Состояние выполнения заказов</h2><input type=button value='Экспортировать в Excel' onclick='window.open(\"project/print/stat_8_2_2.php?p2=".$arr_zak_ids."\");'><br><br>";


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
	echo "<td colspan='2'>Кооп</td>\n";	
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

	function OutMTK($izd) 
	{
		global $db_prefix, $sf, $snf, $snn, $url, $coop_total_cnt, $coop_total_norm_hours;

		$xxx = dbquery("SELECT * 
						FROM ".$db_prefix."db_operitems oper
						WHERE (ID_zakdet='".$izd["ID"]."') order by ORD");

		while ($oper = mysql_fetch_array($xxx)) 
		{
	 
			$num_data = mysql_fetch_assoc(dbquery("SELECT SUM(NUM_FACT) as num, SUM(FACT) as f, SUM(NORM_FACT) as nf,ID_resurs as id_resurs FROM okb_db_zadan WHERE (ID_operitems='".$oper["ID"]."')  and (EDIT_STATE='1') "));
			
			$coop_data = mysql_fetch_assoc(dbquery("SELECT * FROM okb_db_operations_with_coop_dep WHERE oper_id =".$oper["ID"]));

			$num = $num_data['num'];
			$f = $num_data['f'];
			$nf = $num_data['nf'];
		
			$sf += $f;
			$snf += $nf;
			$snn += $oper["NORM_ZAK"]*1;
			$rcount = $izd["RCOUNT"]*1;
			$rnorm = $oper["NORM_ZAK"]*1;
		 	$oper_id = $oper["ID"];

		 	$loc_coop_cnt = $coop_data["count"];
		 	$loc_coop_norm_hours = $coop_data["norm_hours"];

			$coop_total_cnt += $loc_coop_cnt;
			$coop_total_norm_hours += $loc_coop_norm_hours;

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
					echo "<td name='replac2' class='Field'><a class='acl' href='".$url.$oper["ID"]."' target='_blank'>".round(FF($num), 2)."</a></td>\n";
					echo "<td name='replac2' class='Field'><a class='acl' href='".$url.$oper["ID"]."' target='_blank'>".round(FF($nf), 2)."</a></td>\n";

					echo "<td class='Field AC'>".( $loc_coop_cnt == '' ? '-' : $loc_coop_cnt )."</td>";
					echo "<td class='Field AC'>".( $loc_coop_norm_hours == '' ? '-' : number_format ( $loc_coop_norm_hours, 2, ",", "`"))."</td>";
									
					echo "<td name='replac2' class='Field'>".round(FF($rcount - $num - $loc_coop_cnt),2)."</td>\n";
					echo "<td name='replac2' class='Field'>".(round(( $rnorm - $nf - $loc_coop_norm_hours ),2) == '-0' ? 0 : round(( $rnorm - $nf - $loc_coop_norm_hours ),2))."</td>\n";
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
			echo "<td class='Field AL' colspan='13'>".$chld["OBOZ"]."</td>\n";
			echo "</tr>\n";

			OutMTK($chld);

			OutIZD($chld,$n." ... /");

		}
	}
	$zak_ids_count = count($zak_IDs);
	
	for ($j=0;$j < $zak_ids_count;$j++) 
	{

		$snn = 0;
		$sf = 0;
		$snf = 0;

		$coop_total_cnt = 0 ;
		$coop_total_norm_hours = 0;

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$zak_IDs[$j]."')");
		$zak = mysql_fetch_array($xxx);

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak='".$zak_IDs[$j]."') and (PID='0')");
		$izd = mysql_fetch_array($xxx);

		echo "<tr>\n";
		echo "<td class='Field'><b>".FVal($zak,"db_zak","TID")."</b></td>\n";
		echo "<td class='Field'><b>".FVal($zak,"db_zak","NAME")."</b></td>\n";
		echo "<td class='Field AL'><b>".$izd["NAME"]."</b></td>\n";
		echo "<td class='Field AL' colspan='13'>".$izd["OBOZ"]."</td>\n";
		echo "</tr>\n";

		// Выдали по сути первый ДСЕ
		OutMTK($izd);
		OutIZD($izd," ... /");

		echo "<tr>\n";
		echo "<td class='Field' colspan='8' style='text-align: right;'><b>Итого:</b></td>\n";
		echo "<td name='replac2' class='Field'><b>$snn</b></td>\n";
		echo "<td class='Field'><b></b></td>\n";
		echo "<td name='replac2' class='Field'><b>$snf</b></td>\n";

		echo "<td class='Field'></td>";
		echo "<td class='Field'></td>";

		echo "<td class='Field'><b></b></td>\n";
		echo "<td name='replac2' class='Field'><b>".($snn - $snf )."</b></td>\n";
		echo "<td name='replac2' class='Field'><b>$sf</b></td>\n";

		echo "</tr>\n";
	
	} // for ($j=0;$j < $zak_ids_count;$j++) 

	echo "	</tbody>\n";
	echo "</table>\n";
			
echo "<script type='text/javascript'>

for (var ss=0; ss < document.getElementsByName('replac2').length; ss++)
{
	var sy;
	var sk = 0;
	var ss2 = document.getElementsByName('replac2')[ss].innerText.length;
	for (var st=0; st < ss2; st++)
	{
		if (document.getElementsByName('replac2')[ss].innerText.substr(st, 1) == '.') 
		{
			sy = st;
			sk = 1;
		}
	}
	if (sk == 1) 
	{
		var sh = document.getElementsByName('replac2')[ss].innerText.substr(0, sy);
		var sj = document.getElementsByName('replac2')[ss].innerText.substr((sy+1), ss2)
		document.getElementsByName('replac2')[ss].innerText = sh + ',' + sj;
	}
	sk = 0;
}
</script>";
} // if ($step==2) 
?>
