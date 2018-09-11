<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  ÓÑÒÀÍÎÂÊÈ      ////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$oper_row_height = 24;
	$oper_row_height_sel = 60;
	$oper_row_height_span = 52;

	$day_start = -14;
	$day_end = 65;

	$zak_tr_style = "background: #bcd0e9; height: 32px;\n";
	$izd_tr_style = "background: #e5effb; height: 24px;\n";
	$oper_tr_style = "background: #ffffff; height: ".$oper_row_height."px;\n";

	$zak_iwork_style = "background: #11509e; color: #fff; height: 17px;\n";
	$izd_iwork_style = "background: #329ab4; color: #fff; height: 17px;\n";
	$oper_iwork_style = "background: #2f9e11; color: #fff; height: 17px;\n";

	$zak_iplan_style = "background: #3a7aca; color: #fff; height: 17px;\n";
	$izd_iplan_style = "background: #6da6b4; color: #fff; height: 17px;\n";
	$oper_iplan_style = "background: #75a369; color: #fff; height: 17px;\n";

	$span_redact_style = "background: #2f9e11; color: #fff; height: ".$oper_row_height_span."px;\n";
	$span_predact_style = "background: #75a369; color: #fff; height: ".$oper_row_height_span."px;\n";

	$span_inebg = "d21285";

	$L_width = 250;
	$svod_width = 212;

	$br_width = 5;
	$br = $br_width."px solid #666";



///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  FUNCTIONS      ////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  ÌÀÑÑÈÂÛ ÊÎÍÑÒÀÍÒ //////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$DI_MName = Array('','ßíâ','Ôåâ','Ìàðò','Àïð','Ìàé','Èþíü','Èþëü','Àâã','Ñåíò','Îêò','Íîÿ','Äåê');
	$DI_WName = Array('Âñ','Ïí','Âò','Ñð','×ò','Ïò','Ñá');



///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  ÐÀÁÎÒÀ Ñ ÄÀÒÀÌÈ + ìàññèâ $days[]  /////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function WWDate($x) {
		global $DI_WName;

		$dd = $x*1;
		$dd_Y = floor($dd/10000);
		$dd_M = floor(($dd-($dd_Y*10000))/100);
		$dd_D = $dd-($dd_Y*10000)-($dd_M*100);
		$ret = date("w",mktime (0,0,0,$dd_M,$dd_D,$dd_Y));
		return $DI_WName[$ret];
	}

	function OutDate($x) {
		global $DI_MName;

		$dd = $x*1;
		$dd_Y = floor($dd/10000);
		$dd_M = floor(($dd-($dd_Y*10000))/100);
		$dd_D = $dd-($dd_Y*10000)-($dd_M*100);
		$res = "<span class='dt'>".$DI_MName[$dd_M]."</span>".$dd_D."<span class='dt'>".$dd_Y."</span>";
		if ($dd == 0) $res = "";
		return $res;
	}

	function TodayAddDaysGANT($x) {
		$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
		$dd = date("d.m.Y",$theday+($x*86400));
		$dd = explode(".",$dd);
		$dd = $dd[0]+$dd[1]*100+$dd[2]*10000;
		return $dd*1;
	}


	$today = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
	$year=date("Y",$today)+1;
	$YY = date("Y",$today);
	$MM = date("m",$today);


	$today = TodayAddDaysGANT(0);
	$day = $YY*10000+$MM*100+1;
	$page_url = "index.php?";
	if (isset($_GET["mm"])) {
		$MM = $_GET["mm"];
		if (isset($_GET["yy"])) $YY = $_GET["yy"];
		$day = $YY*10000+$MM*100+1;
		$page_url = "index.php?mm=".$MM."&yy=".$YY."&";
	}

	function DayAddDays($x) {
		global $day;

		$dd = $day*1;
		$dd_Y = floor($dd/10000);
		$dd_M = floor(($dd-($dd_Y*10000))/100);
		$dd_D = $dd-($dd_Y*10000)-($dd_M*100);

		$theday = mktime (6,6,6,$dd_M ,$dd_D ,$dd_Y);
		$dd = date("d.m.Y",$theday+($x*86400));
		$dd = explode(".",$dd);
		$dd = $dd[0]+$dd[1]*100+$dd[2]*10000;
		return $dd*1;
	}

	$days = Array();
	$days_count = $day_end + 1 - $day_start;
	for ($j=$day_start;$j < $day_end+1;$j++) $days[] = DayAddDays($j);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  ÐÀÁÎÒÀ Ñ COOKIES  /////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function is_opened($x,$arr) {
		$res = false;
		if (in_array($x,$arr)) $res = true;
		if (in_array("all",$arr)) $res = true;
		return $res;
	}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>