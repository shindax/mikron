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
</style>
<?php


	if (!defined("MAV_ERP")) { die("Access Denied"); }

	$step = 1;

	$date1 = $_GET["p0"];
	$date2 = $_GET["p1"];
	$pdate1 = DateToInt($date1);
	$pdate2 = DateToInt($date2);

	if (($pdate1>0) && ($pdate2>=$pdate1)) $step = 2;


///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////

	function ConvertToVertical($str) {

		$res = "";
		for ($i=0;$i < strlen($str);$i++) {
			$xx = $str[strlen($str)-$i-1];
			if ($xx==" ") $xx = "<br>";
			$res = $res."<div class='VCD'>".$xx."</div>";
		}
		return $res;
	}

	function FReal($x) {
		$ret = number_format( $x, 2, ',', ' ');
		if ($x==floor($x)) $ret = number_format($x, 0, ',', ' ');
		return $ret;
	}

	// ���� �/�, ���� �/�, ����. �
	function OutNF($norm,$pnorm,$fact) {
		$res = FReal($norm)."<br>".FReal($pnorm)."<br>".FReal($fact);
		if ($res == "0<br>0<br>0") $res = "";
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


	echo "<h2>����� �� ��������� �� ������</h2>";

	echo "<table class='tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 700px;' border='1' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='250'>��������</td>";
	echo "<td>��������</td>";
	echo "</tr>\n";

	echo "<tr><td class='Field first'><b>���� ���������:</b></td><td class='rwField ntabg'>";
	Input("date","p0",TodayDate());
	echo "</td></tr>\n";

	echo "<tr><td class='Field first'><b>���� ��������:</b></td><td class='rwField ntabg'>";
	Input("date","p1",TodayDate());
	echo "</td></tr>\n";

	echo "</table>\n";

	$prturl = str_replace ("index.php","print.php", $pageurl);
	echo "<br><table style='width: 700px;'><tr><td style='text-align: right;'><input type='button' value='������ (������ ��� ������)' onclick='location.href=\"print.php?do=show&formid=140&p0=\"+document.getElementById(\"p0_Input\").value+\"&p1=\"+document.getElementById(\"p1_Input\").value'></td>
	<td style='text-align: right;'><input type='button' value='������ (���� + ����)' onclick='location.href=\"index.php?do=show&formid=185&p0=\"+document.getElementById(\"p0_Input\").value+\"&p1=\"+document.getElementById(\"p1_Input\").value'></td>
	<td style='text-align: right;'><input type='submit' value='������'></td></tr></table>";
}

if ($step==2) {


   // ������� - ��� �������
   // ����� ����� �� ��������/�����/������
   // ���� - ������� ���� � ��

	$w_ids = Array();
	$w_n_s = Array();
	$w_nf_s = Array();
	$w_f_s = Array();
	$w_dates = Array();

	$yyy = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE >= '".$pdate1."') and (DATE <= '".$pdate2."') and (EDIT_STATE='1') order by DATE");
	while ($zad = mysql_fetch_array($yyy)) {

		if (!in_array($zad["DATE"],$w_dates)) $w_dates[] = $zad["DATE"];
		if (!in_array($zad["ID_resurs"],$w_ids)) $w_ids[] = $zad["ID_resurs"];

			$key = $zad["DATE"]."|".$zad["SMEN"]."|".$zad["ID_resurs"];

		$w_n_s[$key] = $w_n_s[$key]*1+$zad["NORM"]*1;
		$w_nf_s[$key] = $w_nf_s[$key]*1+$zad["NORM_FACT"]*1;
		$w_f_s[$key] = $w_f_s[$key]*1+$zad["FACT"]*1;

	}

   // ���������� �������� �� ���
	$ids = Array();
	$names = Array();
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs order by binary (NAME)");
	while ($yyy = mysql_fetch_array($xxx)) {
		if (in_array($yyy["ID"],$w_ids)) {
			$ids[] = $yyy["ID"];
			$names[] = $yyy["NAME"];
		}
	}


   // �����

	echo "<h2>����� �� ��������� �� ������</h2>";
	echo "<h3>".$date1." - ".$date2."</h3>";

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "	<thead>\n";
	echo "<tr class='first'>\n";
	echo "<td colspan='2'>���� / �����</td>";
	for ($i=0;$i < count($ids);$i++) {
		echo "<td style='vertical-align: top;'>".ConvertToVertical($names[$i])."</td>";
	}
	echo "<td style='vertical-align: top;'><b>".ConvertToVertical("����� 1 �����:")."</b></td>";
	echo "<td style='vertical-align: top;'><b>".ConvertToVertical("����� 2 �����:")."</b></td>";
	echo "<td style='vertical-align: top;'><b>".ConvertToVertical("����� 3 �����:")."</b></td>";
	echo "<td style='vertical-align: top;'><b>".ConvertToVertical("�����:")."</b></td>";
	echo "</tr>\n";
	echo "	</thead>\n";


   // ����� ���
	for ($z=0;$z < count($w_dates);$z++) {

		echo "<tr>\n";
		echo "<td class='Field' rowspan='3' style='vertical-align: middle;'><b>".IntToDate($w_dates[$z])."</b></td>\n";

		////////////////////////////////////////////////////////////
		echo "<td class='Field' width='40' style='vertical-align: middle;'><b>I</b></td>\n";
			$s_n = 0;
			$s_nf = 0;
			$s_f = 0;
		for ($i=0;$i < count($ids);$i++) {
			$key = $w_dates[$z]."|1|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
			echo "<td class='Field'>".OutNF($w_n_s[$key],$w_nf_s[$key],$w_f_s[$key])."</td>\n";
		}
		echo "<td class='Field'><b>".OutNF($s_n,$s_nf,$s_f)."</b></td>\n";
		echo "<td class='Field'></td>\n";
		echo "<td class='Field'></td>\n";
		////////////////////////////////////////////////////////////

			$ss_n = 0;
			$ss_nf = 0;
			$ss_f = 0;
		for ($i=0;$i < count($ids);$i++) {
			$key = $w_dates[$z]."|1|".$ids[$i];
			$ss_n = $ss_n + $w_n_s[$key]*1;
			$ss_nf = $ss_nf + $w_nf_s[$key]*1;
			$ss_f = $ss_f + $w_f_s[$key]*1;
			$key = $w_dates[$z]."|2|".$ids[$i];
			$ss_n = $ss_n + $w_n_s[$key]*1;
			$ss_nf = $ss_nf + $w_nf_s[$key]*1;
			$ss_f = $ss_f + $w_f_s[$key]*1;
			$key = $w_dates[$z]."|3|".$ids[$i];
			$ss_n = $ss_n + $w_n_s[$key]*1;
			$ss_nf = $ss_nf + $w_nf_s[$key]*1;
			$ss_f = $ss_f + $w_f_s[$key]*1;
		}

		echo "<td class='Field' rowspan='3' style='vertical-align: middle;'><b>".OutNF($ss_n,$ss_nf,$ss_f)."</b></td>\n";

		echo "</tr><tr>";

		////////////////////////////////////////////////////////////
		echo "<td class='Field' width='40' style='vertical-align: middle;'><b>II</b></td>\n";
			$s_n = 0;
			$s_nf = 0;
			$s_f = 0;
		for ($i=0;$i < count($ids);$i++) {
			$key = $w_dates[$z]."|2|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
			echo "<td class='Field'>".OutNF($w_n_s[$key],$w_nf_s[$key],$w_f_s[$key])."</td>\n";
		}
		echo "<td class='Field'></td>\n";
		echo "<td class='Field'><b>".OutNF($s_n,$s_nf,$s_f)."</b></td>\n";
		echo "<td class='Field'></td>\n";
		////////////////////////////////////////////////////////////

		echo "</tr><tr>";

		////////////////////////////////////////////////////////////
		echo "<td class='Field' width='40' style='vertical-align: middle;'><b>III</b></td>\n";
			$s_n = 0;
			$s_nf = 0;
			$s_f = 0;
		for ($i=0;$i < count($ids);$i++) {
			$key = $w_dates[$z]."|3|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
			echo "<td class='Field'>".OutNF($w_n_s[$key],$w_nf_s[$key],$w_f_s[$key])."</td>\n";
		}
		echo "<td class='Field'></td>\n";
		echo "<td class='Field'></td>\n";
		echo "<td class='Field'><b>".OutNF($s_n,$s_nf,$s_f)."</b></td>\n";
		////////////////////////////////////////////////////////////

		echo "</tr>\n";
	}

   // ����� ������ 1 �����
	echo "<tr>\n";
	echo "<td class='Field' colspan='2'><b>����� 1 �����:</b></td>\n";
		$ss_n = 0;
		$ss_nf = 0;
		$ss_f = 0;
	for ($i=0;$i < count($ids);$i++) {
		$s_n = 0;
		$s_nf = 0;
		$s_f = 0;
		for ($z=0;$z < count($w_dates);$z++) {
			$key = $w_dates[$z]."|1|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
		}
		echo "<td class='Field'><b>".OutNF($s_n,$s_nf,$s_f)."</b></td>\n";
		$ss_n = $ss_n + $s_n;
		$ss_nf = $ss_nf + $s_nf;
		$ss_f = $ss_f + $s_f;
	}
	echo "<td class='Field AC' colspan='4'><b>".OutNF($ss_n,$ss_nf,$ss_f)."</b></td>\n";
	echo "</tr>\n";

   // ����� ������ 2 �����
	echo "<tr>\n";
	echo "<td class='Field' colspan='2'><b>����� 2 �����:</b></td>\n";
		$ss_n = 0;
		$ss_nf = 0;
		$ss_f = 0;
	for ($i=0;$i < count($ids);$i++) {
		$s_n = 0;
		$s_nf = 0;
		$s_f = 0;
		for ($z=0;$z < count($w_dates);$z++) {
			$key = $w_dates[$z]."|2|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
		}
		echo "<td class='Field'><b>".OutNF($s_n,$s_nf,$s_f)."</b></td>\n";
		$ss_n = $ss_n + $s_n;
		$ss_nf = $ss_nf + $s_nf;
		$ss_f = $ss_f + $s_f;
	}
	echo "<td class='Field AC' colspan='4'><b>".OutNF($ss_n,$ss_nf,$ss_f)."</b></td>\n";
	echo "</tr>\n";

   // ����� ������ 3 �����
	echo "<tr>\n";
	echo "<td class='Field' colspan='2'><b>����� 3 �����:</b></td>\n";
		$ss_n = 0;
		$ss_nf = 0;
		$ss_f = 0;
	for ($i=0;$i < count($ids);$i++) {
		$s_n = 0;
		$s_nf = 0;
		$s_f = 0;
		for ($z=0;$z < count($w_dates);$z++) {
			$key = $w_dates[$z]."|3|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
		}
		echo "<td class='Field'><b>".OutNF($s_n,$s_nf,$s_f)."</b></td>\n";
		$ss_n = $ss_n + $s_n;
		$ss_nf = $ss_nf + $s_nf;
		$ss_f = $ss_f + $s_f;
	}
	echo "<td class='Field AC' colspan='4'><b>".OutNF($ss_n,$ss_nf,$ss_f)."</b></td>\n";
	echo "</tr>\n";

   // ����� ������
	echo "<tr>\n";
	echo "<td class='Field' colspan='2'><b>�����:</b></td>\n";
		$ss_n = 0;
		$ss_nf = 0;
		$ss_f = 0;
	for ($i=0;$i < count($ids);$i++) {
		$s_n = 0;
		$s_nf = 0;
		$s_f = 0;
		for ($z=0;$z < count($w_dates);$z++) {
			$key = $w_dates[$z]."|1|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
			$key = $w_dates[$z]."|2|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
			$key = $w_dates[$z]."|3|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
		}
		echo "<td class='Field'><b>".OutNF($s_n,$s_nf,$s_f)."</b></td>\n";
		$ss_n = $ss_n + $s_n;
		$ss_nf = $ss_nf + $s_nf;
		$ss_f = $ss_f + $s_f;
	}
	echo "<td class='Field AC' colspan='4'><b>".OutNF($ss_n,$ss_nf,$ss_f)."</b></td>\n";
	echo "</tr>\n";

   // ����� ������������
	echo "<tr>\n";
	echo "<td class='Field AC' colspan='2'><div><b>���� �/�</b></div><div style='border-top: 1px solid black; margin: 2px 20px 2px 20px;'><b>���� �.</b></div></td>\n";
		$ss_nf = 0;
		$ss_f = 0;
	for ($i=0;$i < count($ids);$i++) {
		$s_nf = 0;
		$s_f = 0;
		for ($z=0;$z < count($w_dates);$z++) {
			$key = $w_dates[$z]."|1|".$ids[$i];
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
			$key = $w_dates[$z]."|2|".$ids[$i];
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
			$key = $w_dates[$z]."|3|".$ids[$i];
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
		}

			$coef = "~";
			if ($s_f>0) $coef = FReal($s_nf/$s_f);

		echo "<td class='Field'><b>".$coef."</b></td>\n";
		$ss_nf = $ss_nf + $s_nf;
		$ss_f = $ss_f + $s_f;
	}

			$coef = "~";
			if ($ss_f>0) $coef = FReal($ss_nf/$ss_f);

	echo "<td class='Field AC' colspan='4'><b>".$coef."</b></td>\n";
	echo "</tr>\n";




	echo "</table>";


	echo "<br><br><b>* ������ ������:</b><br><br><div style='margin-left: 30px;'>���� �/�<br>���� �/�<br>����. �</div>";
}
?>