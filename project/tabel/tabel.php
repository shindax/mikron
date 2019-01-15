<script type="text/javascript" src="/project/tabel/js/tabel.js"></script>

<style>
div.wdc {
	display: block;
	padding: 0px;
	margin: 0px;
	width: 25px;
}
div.wn {
	display: block;
	padding: 0px;
	margin: 5px 0px 0px 0px;
	width: 25px;
}
div.umm {
	display: block;
	padding: 0px;
	margin: 0px;
	font: normal 9px Verdana;
}
TD.DIW_TD {
	border: 1px solid black;
	padding: 10px;
	background: #ddd;

}
TD.DIWW_TD { 
	border: 1px solid black;
	padding: 0px;
	margin: 0px;
}
SPAN.DI_DD {
	display: block;
	padding: 5px;
	margin: 0px;
	font: bold 16px Arial;
	color: #555;
	background: #f5f5f5;
}
SPAN.DI_DD2 {
	display: block;
	padding: 5px;
	margin: 0px;
	font: bold 16px Arial;
	color: #555;
	background: #ffeac8;
}
SPAN.DI_DDHL {
	display: block;
	padding: 5px;
	margin: 0px;
	font: bold 16px Arial;
	color: #555;
	background: #7ab4ff;
}
SPAN.DI_DDHL2 {
	display: block;
	padding: 5px;
	margin: 0px;
	font: bold 16px Arial;
	color: #555;
	background: #b7d6fe;
}
A.DD {
	background: none;
	padding-left: 14px;
	padding-right: 14px;
	margin: 0px;
	text-decoration: none;
	font: normal 11px Arial;
}
A.DD:hover {
	background: #ff967a;
	text-decoration: none;
}
A.DD2 {
	background: URL(DB/images/hl.png) no-repeat;
	padding-left: 14px;
	padding-right: 14px;
	margin: 0px;
	text-decoration: none;
	font: normal 11px Arial;
}
A.DD2:hover {
	background: #ff967a;
	text-decoration: none;
}
A.lnk {
	text-decoration: none;
}
img.tr {
	position: absolute;
	margin: -30px 0px 0px 210px;
}
td.pfc {
	padding-left: 0px;
	padding-right: 0px;
}

td.error_fact 
{
  background : pink ;
}


A.lnk {
	text-decoration: none;
	font: normal 16px "Lucida Console";
}
table.tbl td {
	vertical-align: middle;
	text-align: center;
}
div.popup b {
	margin: 0px;
	padding: 0px;
}
input.rinp {
	display: block;
	width: 12px;
	height: 12px;
	float: left;
	margin: 0px 5px 0px 0px;
	padding: 0px;
}

/* shindax */
.td_doc_issued
{
  background-color : yellow !IMPORTANT;
}

.td_doc_not_issued
{
  background-color : #F08080 !IMPORTANT;
}
.td_doc_returned
{
  background-color : #8FBC8F !IMPORTANT;
}

.doc_issue_div
{
  margin-left:20px;
}

.td_highlight
{
 color:blue !important;
 
}


.td_highlight_nn
{
 color:#c91212 !important;
 
}

.plan-fact
{
	float:right;
  padding-top:3px;
}

.plan-fact span
{
	font-size: 10px;
}

</style>
<script language='javascript'>

function selall(sel) {
	for(var i=0;i<document.mainform.elements.length;i++) {
		if (document.mainform.elements[i].name=="resursIDS[]") document.mainform.elements[i].checked = sel.checked;
	}
}

function ShowPopupForm(date,resurs,state,ss) {

		document.popupform.date.value = date;
		document.popupform.resurs.value = resurs;
		ev = event || window.event;

		vpdiv = document.getElementById("vpdiv");

		x = document.getElementById("popupform_div");

		tdv = document.getElementById("today_div");
		bdv = document.getElementById("before_div");
		cdv = document.getElementById("clear_div");
		pdv = document.getElementById("plan_div");
		adv = document.getElementById("after_div");
		dpf = document.getElementById("delpf_div");

		smval = document.getElementById("smval");
		smval.value = ss;

		tdv.style.display = "none";
		bdv.style.display = "none";
		cdv.style.display = "none";
		pdv.style.display = "none";
		adv.style.display = "none";
		dpf.style.display = "none";

		if (state=="b") {
			bdv.style.display = "block";
			cdv.style.display = "block";
			pdv.style.display = "block";
		}
		if (state=="t") {
			bdv.style.display = "block";
			tdv.style.display = "block";
			adv.style.display = "block";
		}
		if (state=="ty") {
			bdv.style.display = "block";
			tdv.style.display = "block";
			pdv.style.display = "block";
		}
		if (state=="ti") {
			tdv.style.display = "block";
			adv.style.display = "block";
		}
		if (state=="a") {
			adv.style.display = "block";
		}
		if (state=="all") {
			bdv.style.display = "block";
			tdv.style.display = "block";
			adv.style.display = "block";
			dpf.style.display = "block";
		}

		x.style.display = "block";
		x.style.left = (vpdiv.scrollLeft+ev.clientX-240)+"px";
		x.style.top = (vpdiv.scrollTop+ev.clientY-64)+"px";
		ev.cancelBubble = true;
}

function HidePopup() {
		x = document.getElementById("popupform_div");
		x.style.display = "none";
}

</script>
<?php
require_once( "classes/db.php" );
require_once( "classes/class.LaborRegulationsViolationItemByMonth.php" );

// error_reporting( E_ALL );
setlocale(LC_ALL, 'en_US.UTF-8');


	function TodayAddDays($x) {
		$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
		return date("d.m.Y",$theday+($x*86400));
	}

	function str_replace_once($search, $replace, $text){ 
	   $pos = strpos($text, $search); 
	   return $pos!==false ? substr_replace($text, $replace, $pos, strlen($search)) : $text; 
	}

	$user_id = $user["ID"];

	$user_dep_id = 0 ;

	$query = "SELECT okb_db_shtat.ID_otdel FROM okb_users
			LEFT JOIN okb_db_resurs ON okb_db_resurs.ID_users = okb_users.ID
			LEFT JOIN okb_db_shtat ON okb_db_shtat.ID_resurs = okb_db_resurs.ID
			WHERE okb_users.ID = $user_id";
	
	$dep_id_query = dbquery($query);
	
	if( $dep_id_fetch = mysql_fetch_assoc($dep_id_query) )
		$user_dep_id = $dep_id_fetch['ID_otdel'];

	$redirected = false;

	$DI_WName = Array('','Пн','Вт','Ср','Чт','Пт','Сб','Вс');
	$DI_MName = Array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');

	$today = TodayDate();
	$today = explode(".",$today);
	$today_DD = $today[0];
	$today = $today[2]*10000+$today[1]*100+$today[0];

	switch ($user['ID'])
	{
		case '43': /* Бормотов В. А. */
			$today_m = explode(".", TodayAddDays(-30));
			break;
		case '31':
			$today_m = explode(".", TodayAddDays(-15));
			break;
		case '169':
			$today_m = explode(".", TodayAddDays(-30));
			break;
		default:
			$today_m = explode(".", TodayAddDays(-5));
	}
	
	$today_m = $today_m[2]*10000+$today_m[1]*100+$today_m[0];

	$today_m8 = explode(".",TodayAddDays(-30));
	$today_m8 = $today_m8[2]*10000+$today_m8[1]*100+$today_m8[0];

	$DI_Date = TodayDate();
	if (isset($_GET["p0"])) 
		$DI_Date = $_GET["p0"];
	
	
	$dep_emp_only = 0;

	if (isset($_GET["p1"])) 
		$dep_emp_only = $_GET["p1"];
	
	$txtdd = $DI_Date;
	$DI_Date = explode(".",$DI_Date);

	$DI_YY = $DI_Date[2];
	$DI_LYY = $DI_YY;
	$DI_NYY = $DI_YY;
	$MY = $DI_Date[1].".".$DI_Date[2];

	$DI_MM = $DI_Date[1]-1;
	$DI_LMM = $DI_MM-1;
	if ($DI_LMM<0) $DI_LMM = 11;
	$DI_NMM = $DI_MM+1;
	if ($DI_NMM>11) $DI_NMM = 0;

	if ($DI_MM==0) $DI_LYY = $DI_YY-1;
	if ($DI_MM==11) $DI_NYY = $DI_YY+1;

	$DI_DD = 1;

	$lastM = $DI_MM;
	$yy = $DI_YY;
	if ($lastM<1) {
		$lastM = 12+$lastM;
		$yy = $yy - 1;
	}
	$lastM = $DI_DD.".".$lastM.".".$yy;

	$nextM = $DI_MM+2;
	$yy = $DI_YY;
	if ($nextM>12) {
		$nextM = $nextM-12;
		$yy = $yy + 1;
	}
	$nextM = $DI_DD.".".$nextM.".".$yy;

	$lastY = $DI_DD.".".($DI_MM+1).".".($DI_YY-1);
	$nextY = $DI_DD.".".($DI_MM+1).".".($DI_YY+1);

	$churl = "index.php?do=show&formid=".$_GET["formid"];

	$tabelvo = "index.php?do=show&formid=26&p0=".$txtdd;
	$tabelprint = "index.php?do=show&formid=81&p0=".$txtdd;
	$tabelopozd = "index.php?do=show&formid=78&p0=".$txtdd;
	$tabelprogul = "index.php?do=show&formid=79&p0=".$txtdd;
	$tabelfailed = "index.php?do=show&formid=".$_GET["formid"]."&p0=".$txtdd;

	$tabelplan = "index.php?do=show&formid=80&p0=".$txtdd;
	$tabelplanall = "index.php?do=show&formid=80&p2=1&p0=".$txtdd;

  $lastday = mktime(0, 0, 0, $DI_MM + 2  , 0, $DI_YY);
  $month = $DI_MM + 1 ;
  $month = $month > 9 ? $month : "0$month";
  $from_day = $DI_YY.$month."01";
  $to_day = $DI_YY.$month.strftime("%d", $lastday);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	ФУНКЦИИ
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function DI_MNum($Mon, $Year) {
		$nn = Array(31,28,31,30,31,30,31,31,30,31,30,31);
		$x = 28;
		$y = (Round($Year/4))*4;
		if ($y==$Year) $x = 29;
		$ret = $nn[$Mon];
		if ($Mon==1) $ret = $x;
		return $ret;
	}

	function DI_FirstDay($Mon,$Year) {
		$x0 = 365;
		$Y = $Year-1;
		$days = $Y*$x0+floor($Y/4)+6;
		for ($j=0; $j<$Mon; $j=$j+1) {
			$days = $days+DI_MNum($j,$Year);
		}
		$week = $days-(7*Round(($days/7)-0.5));
		return $week;
	}

	function DI_WeekDay($Day,$Mon,$Year) {
		$res = DI_FirstDay($Mon,$Year);
		for ($j=1; $j<$Day; $j=$j+1) {
			$res = $res + 1;
			if ($res>6) $res=0;
		}
		return $res;
	}

	function even_week($Day,$Mon,$Year) {
		$x0 = 365;
		$Y = $Year-1;
		$days = $Y*$x0+floor($Y/4)+6;
		for ($j=0; $j<$Mon; $j=$j+1) {
			$days = $days+DI_MNum($j,$Year);
		}
		$days = $days + $Day;
		$weeks = ceil($days/7);

		$res = false;
		if (2*ceil($weeks/2) == $weeks) $res = true;
		return $res;
	}

	function outnums($x,$y) {
		$res = $x."<br>".$y;
		if ($res == "0<br>0") $res = "";
		return $res;
	}





/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	ДЕЙСТВИЕ
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	// Массовое редактирование
	//////////////////////////////////////////////////

	if (isset($_POST["variant"])) {
	$mk_tim_dat = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
	   if (db_adcheck("db_tabel")) {

		$resursIDS_arr = $_POST["resursIDS"];
		$resursIDS = array();
		
		if (db_check("db_tabel","MEGA_REDACTOR") || $user['ID'] == 100) {
			$resursIDS = $resursIDS_arr;
		}else{
			foreach($resursIDS_arr as $key_1 => $val_1) {
				$ch_res_query = dbquery("SELECT ID, ID_tab FROM okb_db_resurs where (ID='".$val_1."')");
				$ch_res_fetch = mysql_fetch_array($ch_res_query);
				if ($ch_res_fetch['ID_tab'] == $user_id){
					$resursIDS[] = $ch_res_fetch['ID'];
				}
			}
		}
		
		$variant = $_POST["variant"];

		$DD_0 = $_POST["firstday"];
		$DD_1 = $_POST["secondday"];

		$CP_0 = $_POST["firstcopy"];
		$CP_1 = $_POST["secondcopy"];

		$pdate_0 =  $DI_YY*10000+($DI_MM+1)*100+$DD_0;
		$pdate_1 =  $DI_YY*10000+($DI_MM+1)*100+$DD_1;

		$cpdate_0 =  $DI_YY*10000+($DI_MM+1)*100+$CP_0;
		$cpdate_1 =  $DI_YY*10000+($DI_MM+1)*100+$CP_1;

		$DD_x0 = $DD_0;
		if ($today>=$pdate_0) $DD_x0 = $today_DD;






		// Простановка не с графиков работ
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($variant!=="by_st") {

		   // ЦИКЛ ПО РЕСУРСАМ
		   $resursIDS_count = count($resursIDS);
		   for ($j=0;$j < $resursIDS_count;$j++) {

			// ЦИКЛ ПО ВСЕМ ДНЯМ
			for ($d=$DD_0;$d < $DD_1+1;$d++) {

				$xdate = $DI_YY*10000+($DI_MM+1)*100+$d;

				if ($xdate>$today) {

					//////////////////// P

					// WORK
					if ($variant=="work") {
						$var_smena = $_POST["var_smena"];
						$var_time = $_POST["var_time"];
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '".$var_smena."', '".$resursIDS[$j]."', '0', '".$var_time."', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					// CLEAR
					if ($variant=="clear") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}

					// OTPUSK
					if ($variant=="otpusk") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '1', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					// ADMOTPUSK
					if ($variant=="admotpusk") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '2', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					// KOMMAND
					if ($variant=="kommand") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '10', '8', '8', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					// SEEK
					if ($variant=="seek") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '4', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					// FILED
					if ($variant=="filed") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '3', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					if ($variant=="v_7") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '7', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_15") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '15', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_8") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '8', '7', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_9") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '9', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_11") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '11', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_12") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '12', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_13") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '13', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_14") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '14', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}


					/////////////////////////////////


				} else {
				// A

					if ($variant == "nnn") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='5', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "nnpr") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='6', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "gosob") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='16', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "addopozd") {
						dbquery("Update ".$db_prefix."db_tabel Set OPOZD:='1' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "delopozd") {
						dbquery("Update ".$db_prefix."db_tabel Set OPOZD:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "otpusk") {
						$ch_dat_otp = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_otp_nam=mysql_fetch_array($ch_dat_otp);
						if (!$ch_dat_otp_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '1', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='1' , doc_issued:='0'where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "admotpusk") {
						$ch_dat_otp = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_otp_nam=mysql_fetch_array($ch_dat_otp);
						if (!$ch_dat_otp_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '2', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='2', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "kommand") {
						$ch_dat_otp = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_otp_nam=mysql_fetch_array($ch_dat_otp);
						if (!$ch_dat_otp_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '10', '0', '8', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='10', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='8' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "seek") 
					{
						$ch_dat_sek = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_sek_nam=mysql_fetch_array($ch_dat_sek);
						if (!$ch_dat_sek_nam)
						{
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '4', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='4', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");

//shindax 23.04.2018						
//						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "filed") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='3', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "work_f") {
						$var_smena_f = $_POST["var_smena_f"];
						$var_time_f = $_POST["var_time_f"];
//shindax 23.04.2018						
//						dbquery("Update ".$db_prefix."db_tabel Set TID:='0', doc_issued:='0'where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set SMEN:='".$var_smena_f."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='".$var_time_f."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "inwork") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='0', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set SMEN:='1' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_7") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='7', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_15") {
						$ch_dat_sek = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_sek_nam=mysql_fetch_array($ch_dat_sek);
						if (!$ch_dat_sek_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '15', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='15', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_8") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='8', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='7' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_9") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='9', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_11") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='11', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='8' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_12") {
						$ch_dat_sek = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_sek_nam=mysql_fetch_array($ch_dat_sek);
						if (!$ch_dat_sek_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '12', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='12', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_13") {
						$ch_dat_sek = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_sek_nam=mysql_fetch_array($ch_dat_sek);
						if (!$ch_dat_sek_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '13', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='13', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_14") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='14', doc_issued:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='8' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
				}
			}// ЦИКЛ ПО ВСЕМ ДНЯМ

			if ($cpdate_1+1>$today) {

				// COPY
				if ($variant=="copy") {
					dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$cpdate_1."')");
					$xxx = dbquery("SELECT SMEN,TID,PLAN FROM ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$cpdate_0."')");
					if ($res = mysql_fetch_assoc($xxx)) {
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$cpdate_1."', '".$res["SMEN"]."', '".$resursIDS[$j]."', '".$res["TID"]."', '".$res["PLAN"]."', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
				}
			}
		   }// ЦИКЛ ПО РЕСУРСАМ
		}


		// Простановка с графиков работ
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////


		if ($variant=="by_st") { 
			if (($today<$pdate_1+1) && ($pdate_1+1>$pdate_0)) {
				// ЦИКЛ ПО РЕСУРСАМ
				//////////////////////////////////////////////////////////////////////////////////////////
				for ($j=0;$j < count($resursIDS);$j++) {
					//$DD_x0..$DD_1

					$xdate0 = $DI_YY*10000+($DI_MM+1)*100+$DD_x0;
					$xdate1 = $DI_YY*10000+($DI_MM+1)*100+$DD_1;

					// Затираем чо было ??? надо ли затирать?
					dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE>='".$xdate0."') and (DATE<='".$xdate1."') and (TID!='1') and (TID!='2') and (TID!='12') and (TID!='10') and (TID!='13') ");

					// Записываем согласно графику работ сотрудника
					$xxx = dbquery("SELECT ID, ID_tab_st FROM ".$db_prefix."db_resurs where (ID='".$resursIDS[$j]."')");
					if ($resurs = mysql_fetch_array($xxx)) {
					    if ($resurs["ID_tab_st"]*1!==0) {
						$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tab_sti where (ID_tab_st='".$resurs["ID_tab_st"]."') and (DATE>='".$xdate0."') and (DATE<='".$xdate1."') order by DATE");
						while ($smn = mysql_fetch_array($xxx)) {
							
							if (mysql_result(dbquery("SELECT TID FROM okb_db_tabel WHERE (ID_resurs='".$resursIDS[$j]."') and DATE = '".$smn["DATE"]."'"), 0) == 0) {
						
					
							
							
								if ($smn["TID"]*1==0) dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$smn["DATE"]."', '".$smn["SMEN"]."', '".$resursIDS[$j]."', '0', '".$smn["HOURS"]."', '0', '".$user['ID']."', '".$mk_tim_dat."')");
								if ($smn["TID"]*1==1) dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$smn["DATE"]."', '0', '".$resursIDS[$j]."', '7', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
								if ($smn["TID"]*1==2) dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$smn["DATE"]."', '".$smn["SMEN"]."', '".$resursIDS[$j]."', '8', '".$smn["HOURS"]."', '0', '".$user['ID']."', '".$mk_tim_dat."')");

								}
							}
					    }
					}
				}
				//////////////////////////////////////////////////////////////////////////////////////////
			}
		}

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////


	   }
	   redirect($pageurl."&event","script");
	   $redirected = true;
	}





/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Индивидуальное редактирование на дату
	//////////////////////////////////////////////////

	if (isset($_POST["individual"])) {
	$mk_tim_dat = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
	   $resurs = $_POST["resurs"];
	   $date = $_POST["date"];
	   $var1 = $_POST["var1"];
           $xxx = dbquery("SELECT ID, ID_tab FROM ".$db_prefix."db_resurs where (ID = '".$resurs."')");
           if ($res = mysql_fetch_array($xxx)) {
	     if (($res["ID_tab"]==$user_id) or (db_check("db_tabel","MEGA_REDACTOR")) or (db_adcheck("db_tabel"))) {

		if ($date <= $today) {
			if ($var1 == "nnn") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='5', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "nnpr") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='6', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "addopozd") {
				dbquery("Update ".$db_prefix."db_tabel Set OPOZD:='1' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "delopozd") {
				dbquery("Update ".$db_prefix."db_tabel Set OPOZD:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "otpusk") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='1', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "admotpusk") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='2', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "kommand") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='10', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='8' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "seek") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='4', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");

//shindax 23.04.2018
//				dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "gosob") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='16', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "filed") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='3', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "fact") 
					{
				$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				if ($xxx = mysql_fetch_array($xxx)) {
					dbquery("Update ".$db_prefix."db_tabel Set FACT:='".$_POST["fact"]."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
					dbquery("Update ".$db_prefix."db_tabel Set SMEN:='".$_POST["fsmena"]."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
					dbquery("Update ".$db_prefix."db_tabel Set TID:='0', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
					if (($xxx['TID']=='1') or ($xxx['TID']=='2') or ($xxx['TID']=='4') or ($xxx['TID']=='7') or ($xxx['TID']=='9') or ($xxx['TID']=='12')) {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='".$xxx['TID']."' , doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
					}
					dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
					dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				} else {
					dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '".$_POST["fsmena"]."', '".$resurs."', '0', '0', '".$_POST["fact"]."', '".$user['ID']."', '".$mk_tim_dat."')");
				}
			}
			if (($var1 == "delplanfact") && (db_check("db_tabel","MEGA_REDACTOR"))){
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}

			if ($var1 == "v_7") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='7', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "v_15") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='15', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "v_8") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='8', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='7' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "v_9") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='9', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "v_11") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='11', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='8' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "v_12") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='12', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "v_13") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='13', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
			if ($var1 == "v_14") {
				dbquery("Update ".$db_prefix."db_tabel Set TID:='14', doc_issued:='0' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='8' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}
		}
		if ($date > $today) {

			if ($var1 == "otpusk") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '1', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}

			if ($var1 == "admotpusk") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '2', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}
			if ($var1 == "kommand") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '10', '8', '8', '".$user['ID']."', '".$mk_tim_dat."')");
			}

			if ($var1 == "seek") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '4', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}

			if ($var1 == "filed") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '3', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}

			if ($var1 == "clear") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
			}

			if ($var1 == "work") {
				$tid_tab = "0";
				$xxx = dbquery("SELECT TID FROM ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				$xxx2 = mysql_fetch_array($xxx);
				if ($xxx2['TID']=="1") $tid_tab="1";
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '".$_POST["smena"]."', '".$resurs."', '".$tid_tab."', '".$_POST["time"]."', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}

			if ($var1 == "v_7") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '7', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}
			if ($var1 == "v_15") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '15', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}
			if ($var1 == "v_8") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '8', '7', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}
			if ($var1 == "v_9") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '9', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}
			if ($var1 == "v_11") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '11', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}
			if ($var1 == "v_12") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '12', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}
			if ($var1 == "v_13") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '13', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}
			if ($var1 == "v_14") {
				dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resurs."') and (DATE='".$date."')");
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$date."', '1', '".$resurs."', '14', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
			}
		}

	     }
	   }
	   redirect($pageurl."&event","script");
	   $redirected = true;
	}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function OpenID($item, $dep_id = 0) 
	{
		global $DI_MM, $DI_YY, $today, $db_prefix, $today_m, $user_id, $s1num8, $s1num11, $s2num8, $s2num11, $s3num8, $s3num11, $dep_emp_only, $user, $pdo;

		$db_check = db_check("db_tabel","MEGA_REDACTOR");
		$db_adcheck = db_adcheck("db_tabel");
		$item_id_tab = $item["ID_tab"]==$user_id;
		
		$id_tab = $item["ID_tab"];

		if( $user_id != $id_tab && $dep_emp_only )
			return ;
		
		echo "<script>var user_id = $user_id ;</script>";
		echo "<script>var dep_emp_only = $dep_emp_only ;</script>";
		
	   // Цвет
		$trbg = "fff";
		if ($item["ID_tab"]==$user_id) 
			$trbg = "d5d5d5";
		
		echo "<tr class='cl_1' onmouseover='tr_row_over(this);' onmouseout='tr_row_out(this);'  data-tab-id='$id_tab' data-dep-id='$dep_id'>";

		$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
		$today_0 = date("d.m.Y",$theday);
		$today_0 = DateToInt($today_0);
		$today_p30 = date("d.m.Y",$theday+(30*86400));
		$today_p30 = DateToInt($today_p30);

		$MO = "afa";
		if ($item["DATE_NMO"]*1<$today_p30) $MO = "faa";
		if ($item["DATE_NMO"]*1<$today_0) $MO = "f44";
		if ($item["DATE_LMO"]*1==0) $MO = "f44";
		if ($item["DATE_NMO"]*1==0) $MO = "f44";

	   // Наименование
	   
	   if (strpos($item['NAME'], 'Вакансия') === false) {
			echo '<td class="Field" style="background: #'.$trbg.'; width: 150px; text-align: left;"><a target="_blank" href="/index.php?do=show&formid=47&id=' . $item['ID'] . '">' . $item['FF'] . ' ' . $item['II'] . ' ' . $item['OO'][0] . '.</a></td>';
	   } else {
			echo '<td class="Field" style="background: #'.$trbg.'; width: 150px; text-align: left;">' . $item['NAME'] . '</td>';
	   }		   
		if ($user['ID'] == 232 || $user['ID'] == 43)
			echo '<td class="Field">' . $item['SpecialName'] . '</td>';
		
	   // Мед. осмотр
		echo "<td class='Field' style='background: #".$MO.";'></td>";

	   // Кнопка выбора
		if ($db_adcheck) {
			$showkey = "";
			if ($item["TID"]*1!==1) $showkey = "<input type='checkbox' name='resursIDS[]' value='".$item["ID"]."'>";
			echo "<td class='Field' style='background: #".$trbg."; padding: 2px;'>".$showkey."</td>";
		}

		$plan = 0;
		$fact = 0;
		$xdate_0 = $DI_YY*10000+($DI_MM+1)*100+0;
		$xdate_1 = $xdate_0+DI_MNum($DI_MM,$DI_YY)+1;
		
// shindax 07.02.2017		
    $xxx = dbquery("SELECT SUM(FACT) fact FROM okb_db_tabel where (ID_resurs=".$item["ID"].") and DATE BETWEEN ".$xdate_0." + 1 and ".$xdate_1 );
		$res = mysql_fetch_assoc($xxx);
		$total_fact = $res['fact'];


//		$xxx = dbquery("SELECT DATE,TID,SMEN,OPOZD,FACT,PLAN,doc_issued FROM okb_db_tabel where (ID_resurs=".$item["ID"].") and DATE BETWEEN ".$xdate_0." + 1 and ".$xdate_1);
		$xxx = dbquery("SELECT DATE,TID,SMEN,OPOZD,FACT,PLAN,doc_issued FROM okb_db_tabel where (ID_resurs=".$item["ID"].") and DATE BETWEEN ".$xdate_0." + 1 and ".$xdate_1." GROUP BY DATE");
// shindax 07.02.2017
		
		$innerHTML = array();
		while ($res = mysql_fetch_assoc($xxx)) {
			$plan += $res['PLAN'];
			
			$innerHTML[$res['DATE']] = ($res['TID'] * 1).'|'.($res['SMEN'] * 1).'|'.($res['OPOZD'] * 1).'|'.($res['FACT'] * 1).'|'.($res['PLAN'] * 1).'|'.($res['doc_issued'] * 1);

			// Расчёт суммы факта
			if ($res['DATE'] <= $today) {
				$fact += $res['FACT'] * 1;
			}

		}

		$weekday = DI_FirstDay($DI_MM,$DI_YY);
		$dimm_count = DI_MNum($DI_MM,$DI_YY);
		for ($j=0;$j < $dimm_count;++$j) 
		{

			$hl = $DI_YY*10000+($DI_MM+1)*100+($j+1);
			$inht = explode('|', $innerHTML[$hl]);
			$ttt = $inht[4]*1;
			if ($ttt>0) {
				if ($inht[1]==1) {
					if ($ttt<=8) $s1num8[$j] += 1;
					if ($ttt>8) $s1num11[$j] += 1;
				}
				if ($inht[1]==2) {
					if ($ttt<=8) $s2num8[$j] += 1;
					if ($ttt>8) $s2num11[$j] += 1;
				}
				if ($inht[1]==3) {
					if ($ttt<=8) $s3num8[$j] += 1;
					if ($ttt>8) $s3num11[$j] += 1;
				}
			}
			$smval = '1';
			if ($inht[0]!=="") $smval = $inht[1];
			if ($smval == "0") $smval = '1';
			$bgurl = "";
			if ($inht[2]=="1") $bgurl = " URL(project/tabel/opozd.png) no-repeat";

			
			
			$use_popup = " ";
				if ($hl > $today) {
					if ($db_check) {
						$use_popup = " onClick='ShowPopupForm(\"".$hl."\",\"".$item["ID"]."\",\"b\",$smval);' style='cursor: hand;'";
					}else{
						if ($db_adcheck and $item_id_tab) $use_popup = " onClick='ShowPopupForm(\"".$hl."\",\"".$item["ID"]."\",\"b\",$smval);' style='cursor: hand;'";
					}
				}
				if (($hl <= $today) && ($hl > $today_m)) {
					if ($db_check) {
					   if ($inht[0]!=="") {
							$use_popup = " onClick='ShowPopupForm(\"".$hl."\",\"".$item["ID"]."\",\"t\",$smval);' style='cursor: hand;'";
					   } else {
							$use_popup = " onClick='ShowPopupForm(\"".$hl."\",\"".$item["ID"]."\",\"a\",$smval);' style='cursor: hand;'";
					   }
					}else{
						if ($db_adcheck and $item_id_tab){
						   if ($inht[0]!=="") {
								$use_popup = " onClick='ShowPopupForm(\"".$hl."\",\"".$item["ID"]."\",\"t\",$smval);' style='cursor: hand;'";
						   } else {
								$use_popup = " onClick='ShowPopupForm(\"".$hl."\",\"".$item["ID"]."\",\"a\",$smval);' style='cursor: hand;'";
						   }
						}					
					}
				}
				if ($hl <= $today_m) {
					//if (db_check("db_tabel","MEGA_REDACTOR")) $use_popup = " onClick='ShowPopupForm(\"".$hl."\",\"".$item["ID"]."\",\"a\",$smval);' style='cursor: hand;'";
					if ($db_check) {
					   if ($inht[0]!=="") {
						$use_popup = " onClick='ShowPopupForm(\"".$hl."\",\"".$item["ID"]."\",\"all\",$smval);' style='cursor: hand;'";
					   } else {
						$use_popup = " onClick='ShowPopupForm(\"".$hl."\",\"".$item["ID"]."\",\"a\",$smval);' style='cursor: hand;'";
					   }
					}
				}


		   // TID "ОТ|ДО| Х| Б|НН|ПР| В|ЛЧ|НВ| K|РП| У|ПК|НП"
		   // TID " 1| 2| 3| 4| 5| 6| 7| 8| 9|10|11|12|13|14"

		   // $inht -> $res["TID"]*1."|".$res["SMEN"]."|".$res["OPOZD"]."|".$res["FACT"]."|".$res["PLAN"];

			$txt = "";

			if (count($inht)>2) {
				$res["TID"] = $inht[0];




				$txt = "<b>".$inht[4]."</b><br>".$inht[1];
				if ($inht[0]*1>0 && $res['TID'] != 8) {
					$txt = "<b>".FVal($res,"db_tabel","TID")."</b>";
					if ($inht[4]*1>0) $txt .= "<br><div class='umm'><b>".$inht[4]."</b>/".($inht[1] == 0 ? 1 : $inht[1])."</div>";
				}

				if ($hl <= $today && $res['TID'] != 8) {
					$txt = "<b>".$inht[3]."</b><br>".$inht[1];
					if ($inht[0]*1>0) {
						$txt = "<b>".FVal($res,"db_tabel","TID")."</b>";
						if ($inht[3]*1>0) $txt .= "<br><div class='umm'><b>".$inht[3]."</b>/".($inht[1] == 0 ? 1 : $inht[1])."</div>";
					}
				}

			}


			// BACGROUND
			$bg = "#".$trbg;
			$xwd = date("w",mktime (0,0,0,$DI_MM+1,$j+1,$DI_YY))*1;
			if ($xwd==0) $xwd=7;
			if ($xwd>5) $bg = "#ffeac8";
			if ($hl == $today) $bg = "#7ab4ff";
			if (($hl < $today) && ($hl > $today_m)) $bg = "#b7d6fe";
			$cl = " style='background: $bg$bgurl; padding: 0px;'";
			if ($use_popup!==" ") $cl = " style='background: $bg$bgurl; padding: 0px; cursor: hand;'";


// shindax
//			echo "<td class='Field'".$cl.$use_popup.">".$txt."</td>";

// shindax				
			$tid = $inht[0] ;
			$doc_issued = $inht[5] ;
			$td_class = '';

			if( $tid == 2 || $tid == 6 ) // ДО или прогул
			  switch( $doc_issued )
			  {
				case 0: $td_class = 'td_doc_not_issued'; break ;
				case 1: $td_class = 'td_doc_returned'; break ;                        
			  }
			  		  
		  if ($tid == 1 || $tid == 4 || $tid == 12) {
			  $td_class = 'td_highlight';
		  }
		  
		  if ($tid == 5) {
			  $td_class = 'td_highlight_nn';
		  }
			echo "<td id='$hl".($item["ID"])."' data-tid='$tid' data-res_id='".($item["ID"])."' data-day='$hl' data-state='$doc_issued' class='Field $td_class tabel_td'".$cl.$use_popup.">".$txt."</td>";			

		}
// ГО ОТ  У

// shindax 07.02.2017		
    $fact = round( $fact, 1 );
    $total_fact = round( $total_fact, 1 );

	$sum_plan_hours = mysql_result(dbquery("SELECT SUM(`hours`) FROM `okb_db_tab_sti` WHERE ID_tab_st = " . $item["ID_tab_st"] . " AND DATE BETWEEN ".$xdate_0." + 1 and ".$xdate_1.""), 0);
		
	$plan = $sum_plan_hours;

	$el = new LaborRegulationsViolationItemByMonth( $pdo, $item['ID'], $DI_MM + 1, $DI_YY );
	$viol = $el -> GetViolationsByShift();

    echo "<td class='Field'>{$viol['shift_1']} : {$viol['shift2_minus']}</td>";

    if( $fact != $total_fact )
      echo "<td class='Field pfc error_fact'>$plan<br><b>$fact</b> / <b>$total_fact</b></td>";
        else
          echo "<td class='Field pfc'>$plan<br><b>$fact</b></td>";

//		echo "<td class='Field pfc'>$plan<br><b>$fact</b></td>";
// shindax 07.02.2017		

		echo "</tr>\n";
	}

	function OpenID_otdel($item,$n) {
		global $db_prefix, $DI_MM, $DI_YY, $MY, $tabelplan, $tabelplanall;


		$otstup = "";
		for ($i=0;$i < $n;$i++) $otstup = $otstup.".. / ";

// shindax 2018
		$dep_id = $item["ID"];
		$plan_fact = calcPlanFact( $dep_id );
		$dep_emp_count = mysql_result(dbquery("SELECT COUNT(ID) FROM ".$db_prefix."db_shtat where (ID_otdel = $dep_id) and (NOTTAB = '0') AND NAME != '' AND NAME != 'Вакансия ..'  "), 0);

		echo "<tr class='dep_head' data-id='$dep_id'>
		<td class='Field' colspan='".(5+DI_MNum($DI_MM,$DI_YY))."' style='text-align: left; padding-left: 40px; background: #c8daf2;'>
		<b>".$otstup.strtoupper($item["NAME"])."</b> ($dep_emp_count)
		<a style='margin-left: 20px; margin-right: 10px;' href='".$tabelplan."&p1=".$item["ID"]."' target='_blank'>План</a> | <a style='margin-left: 10px;' href='".$tabelplanall."&p1=".$item["ID"]."' target='_blank'>Общий план</a>
    <div class='plan-fact'><span>План. : ".( $plan_fact['plan'] )." Факт. : ".( $plan_fact['fact'] )."</span></div>   
		</td></tr>";

				
		$x2x = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID_otdel = '".$item["ID"]."') and (NOTTAB = '0') and (BOSS='1')");
		$r2s = mysql_fetch_array($x2x);
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID_otdel = '".$item["ID"]."') and (NOTTAB = '0') and (BOSS='0')");
		$res_ids = Array();
		$res_nams = Array();
		while($res = mysql_fetch_array($xxx)) {
			$res_ids[] = $res["ID_resurs"];
			$res_nams[] = $res["NAME"];
		}
		array_multisort($res_nams,$res_ids);
		$x2x = dbquery("SELECT okb_db_resurs.*, okb_db_special.NAME as SpecialName FROM ".$db_prefix."db_resurs 
		
			LEFT JOIN okb_db_special ON okb_db_special.ID = okb_db_resurs.ID_special

		
		where okb_db_resurs.ID='".$r2s['ID_resurs']."'");
		$r2s = mysql_fetch_array($x2x);
		if ($r2s['ID']!=='0') OpenID($r2s, $dep_id);
		foreach($res_ids as $k_2 => $v_2){
			if ($v_2!=="0"){
				$xxx = dbquery("SELECT okb_db_resurs.*, okb_db_special.NAME as SpecialName FROM ".$db_prefix."db_resurs
						LEFT JOIN okb_db_special ON okb_db_special.ID = okb_db_resurs.ID_special

				where okb_db_resurs.ID=".$v_2);
				$res = mysql_fetch_array($xxx);
				OpenID($res, $dep_id);
			}
		}
		/*$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			if (in_array($res["ID"],$res_ids)) OpenID($res);
		}*/

		/*$xxx = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID_otdel = '".$item["ID"]."') and (NOTTAB = '0')");
		$res_ids = Array();
		while($res = mysql_fetch_array($xxx)) {
			$res_ids[] = $res["ID_resurs"];
		}
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			if (in_array($res["ID"],$res_ids)) OpenID($res);
		}*/
		
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID = '".$item["ID"]."') order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			OpenID_otdel($res,$n+1);	
		}
	}





if (!$redirected) {
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	echo "</form>";
	$tabel_mega = db_check("db_tabel","MEGA_REDACTOR");

   // Форма планирования на будущее + план / факт сегодня + план / факт вчера, позавчера + изменение Н / Ё
	echo "<div style='padding: -400px; margin: 0px; height: 1px;'><div id='popupform_div' class='popup' style='width: 250px; position: relative; left: 0px; top: 0px; padding: 10px;'><img class='tr' src='project/tabel/tr.png'><div style='text-align: right; padding: 0px; margin: 0px;'><a href='javascript:void(0);' onClick='HidePopup();' style='font-size: 10px;'>Закрыть</a></div>";
	echo "<form name='popupform' method='post' action='$pageurl'>";
	echo "<input type='hidden' name='individual' value='true'>";
	echo "<input type='hidden' name='resurs' value=''>";
	echo "<input type='hidden' name='date' value=''>";

	   // t
		echo "<div id='today_div' style='display: none;'>";
		echo "<br><input type='radio' name='var1' value='nnn'> <b>НН</b> - Отсутствует<br>";
		if ($tabel_mega) echo "<input type='radio' name='var1' value='nnpr'> <b>ПР</b> - Прогул<br>";

		// shindax
   if ($tabel_mega) 
   {	   echo  "<div id='absent_div' class='doc_issue_div' hidden>
           <input type='checkbox' id='absent_doc_issued_check' value='Документ подписан'>Документ подписан<br>            
           </div>";
		   
   }
	
	echo "<br><input type='radio' name='var1' value='addopozd'> Опоздание<br>";
		echo "<input type='radio' name='var1' value='delopozd'> Отменить опоздание<br>";
	
		echo "</div>";

	   // b
		echo "<div id='before_div' style='display: none;'>";
	if ($tabel_mega) 	echo "<br><input type='radio' name='var1' value='otpusk'> <b>ОТ</b> - Очередной отпуск<br>";
	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='admotpusk'> <b>ДО</b> - Административный отпуск<br>";
		
		// shindax
    echo  "<div id='add_vac_div' class='doc_issue_div' hidden>
            <input type='checkbox' id='add_vac_doc_issued_check' value='Документ подписан'>Документ подписан<br>
            </div>";
		
	if ($tabel_mega || $user['ID'] == 43) 	echo "<input type='radio' name='var1' value='kommand'> <b>К</b> &nbsp;&nbsp; - Командировка<br>";
	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='seek'> <b>Б</b> &nbsp;&nbsp; - Больничный отпуск<br>";
	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='filed'> <b>Х</b> &nbsp;&nbsp; - Уволен<br>";

	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='v_7'> <b>В</b> &nbsp;&nbsp; - Выходные / праздничные дни<br>";
	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='v_15'> <b>ВО</b> &nbsp;&nbsp; - В счёт очередного отпуска<br>";
	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='v_8'> <b>ЛЧ</b> &nbsp;&nbsp; - Сокращённое рабочее время<br>";
	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='v_9'> <b>НВ</b> &nbsp;&nbsp; - Дополнительный выходной день<br>";
	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='gosob'> <b>ГО</b> &nbsp;&nbsp; - Гос. обязанности<br>";
	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='v_11'> <b>РП</b> &nbsp;&nbsp; - Простой по вине работодателя<br>";
	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='v_12'> <b>У</b> &nbsp;&nbsp; - Отпуск дополнительный (оплачиваемый учебный)<br>";
	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='v_13'> <b>ПК</b> &nbsp;&nbsp; - Повышение квалификации<br>";
	if ($tabel_mega) 	echo "<input type='radio' name='var1' value='v_14'> <b>НП</b> &nbsp;&nbsp; - Простой независящий от работодателя и работника<br><br>";
		echo "</div>";

	   // b
		echo "<div id='clear_div' style='display: none;'>";
		echo "<br><input type='radio' name='var1' value='clear'> Очистить<br>";
		echo "</div>";

	   // b
		echo "<div id='plan_div' style='display: none;'>";
		echo "<br><input type='radio' name='var1' value='work'> Работает<br><span style='margin-left: 25px;'>Смена<select name='smena'>";
			echo "<option value='1' selected>1";
			echo "<option value='2'>2";
			echo "<option value='3'>3";
		echo "</select> Время, ч <input type='text' name='time' value='8' style='width: 40px;'  onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, 'time', event)\"></span>";
		echo "</div>";

	   // a
		echo "<div id='after_div' style='display: none;'>";
		echo "<br><input type='radio' name='var1' value='fact'> Работал<br><span style='margin-left: 25px;'>Смена<select id='smval' name='fsmena'>";
			echo "<option value='1' selected>1";
			echo "<option value='2'>2";
			echo "<option value='3'>3";
		echo "</select> Факт, ч <input type='text' name='fact' value='8' style='width: 40px;'  onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, 'fact', event)\"></span>";
		echo "</div>";

	   // dpf
		echo "<div id='delpf_div' style='display: none;'>";
		echo "<br><input type='radio' name='var1' value='delplanfact'> Удалить План / Факт<br>";
		echo "</div>";

	   // ПРИМЕНИТЬ
		echo "<br><br><input type='submit' value='Применить'>";

	echo "</form>";
	echo "</div></div>";



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	echo "<form name='mainform' method='post' action='$pageurl'>";
	echo "<input type='hidden' name='firstday' value='1'>";
	echo "<input type='hidden' name='secondday' value='".DI_MNum($DI_MM,$DI_YY)."'>";
	echo "<input type='hidden' name='var_time' value='8'>";
	echo "<input type='hidden' name='var_time_f' value='8'>";
	echo "<input type='hidden' name='var_smena' value='1'>";
	echo "<input type='hidden' name='var_smena_f' value='1'>";
	echo "<table style='width: 100%; margin-bottom: -20px;' cellpadding='0' cellspacing='0'><tr>";
	echo "<td style='text-align: left; width: 50%;'>\n";
	
	if( $dep_emp_only )
		$churl .= "&p1=1";
	
	echo "<a class='lnk' href='".$churl."&p0=".$lastM."'><--</a> Месяцы <a class='lnk' href='".$churl."&p0=".$nextM."'>--></a>";
	echo "</td><td style='text-align: right;'>\n";
	echo "<a class='lnk' href='".$churl."&p0=".$lastY."'><--</a> Годы <a class='lnk' href='".$churl."&p0=".$nextY."'>--></a>";
	echo "</td>";
	echo "</tr></table><br>";



	echo "<table style='width: 100%; margin-bottom: -20px;' cellpadding='0' cellspacing='0'><tr>";
		echo "<td style='text-align: left; width: 250px;'>\n";
			echo "<H3 style='margin:15px 15px 15px 0px;'>".$DI_MName[$DI_MM]." ".$DI_YY."</H3>";
		echo "</td><td style='text-align: right;'>\n";
		echo "<div style='margin:0px 0px 0px 0px;' class='links'>";
			//echo "<a href='index.php?do=show&formid=81&p0=".$txtdd."&p2=2' target='_blank' onclick=''>Табель факт (печать своей службы)</a> | ";
			echo "Табель факт (печать своей службы)<a href='index.php?do=show&formid=81&p0=".$txtdd."&p2=2&p3=1' target='_blank'>первая половна мес.</a><a href='index.php?do=show&formid=81&p0=".$txtdd."&p2=2&p3=2' target='_blank'>вторая половна мес.</a> | ";
			echo "<a style='cursor:pointer;' onclick='chech_all_or_sel();'>Табель факт (печать)</a><br>";
			
			
		$new_page_url = str_replace_once('&p1=1', '', $pageurl ); 
		$new_page_url = str_replace_once('&p1=0', '', $new_page_url ); 

		if( $dep_emp_only )
			echo "<a href='$new_page_url&p1=0'>Все сотрудники</a>";
				else
					echo "<a href='$new_page_url&p1=1'>Только табелируемые мной сотрудники</a>";
			
			echo "<a href='".$tabelvo."' target='_blank'>ВО за год</a> | ";
			echo "<a href='".$tabelopozd."' target='_blank'>Опоздавшие</a> | ";
			echo "<a href='".$tabelprogul."' target='_blank'>Прогулы</a> | ";
			echo "<a href='index.php?do=show&formid=220&p0=".$txtdd."' target='_blank'>Факт=0</a> | ";
			//echo "<a href='project/tabel/get_tabel_csv.php' target='_blank'>Экспорт в Excel</a> | ";
			echo "<a onclick='document.getElementById(\"export_csv_tabel\").style.display=\"block\";' style='cursor:pointer;'>Экспорт в Excel</a> | ";
			echo "<span style='position:relative;'><div id='export_csv_tabel' style='display:none; background:#c6d9f1; border:1px solid #8ba2c2; box-shadow:3px 4px 20px #555555; width:186px; position:absolute; top:30px; left:-160px;'>
			<center><br>C&nbsp;&nbsp;&nbsp;<input id='tab_to_csv_dat_1' type='date' value='".date("Y")."-".date("m")."-01'><br><br>По&nbsp;<input id='tab_to_csv_dat_2' type='date' value='".date("Y")."-".date("m")."-".date('t')."'><br><br>
			<input type='button' onclick='export_tabel_to_csv();' value='Экспортировать'><br><br><input type='button' value='Отмена' onclick='document.getElementById(\"export_csv_tabel\").style.display=\"none\";'><br><br></center></div></span>";
			echo "<script>
			function export_tabel_to_csv(){
				var dat_1 = document.getElementById(\"tab_to_csv_dat_1\").value.substr(0,4)+document.getElementById(\"tab_to_csv_dat_1\").value.substr(5,2)+document.getElementById(\"tab_to_csv_dat_1\").value.substr(8,2);
				var dat_2 = document.getElementById(\"tab_to_csv_dat_2\").value.substr(0,4)+document.getElementById(\"tab_to_csv_dat_2\").value.substr(5,2)+document.getElementById(\"tab_to_csv_dat_2\").value.substr(8,2);
				var month_1 = document.getElementById(\"tab_to_csv_dat_1\").value.substr(5,2);
				var month_2 = document.getElementById(\"tab_to_csv_dat_2\").value.substr(5,2);
				var year_1 = document.getElementById(\"tab_to_csv_dat_1\").value.substr(0,4);
				var year_2 = document.getElementById(\"tab_to_csv_dat_2\").value.substr(0,4);
				var selects_res = '';
				for (var v_f_a = 0; v_f_a < document.getElementsByName(\"resursIDS[]\").length; v_f_a++){
					if (document.getElementsByName(\"resursIDS[]\")[v_f_a].checked == true){
						selects_res = selects_res + document.getElementsByName(\"resursIDS[]\")[v_f_a].value + \"|\";
					}
				}
				if ((year_1 == year_2) && (month_1 == month_2)){
					if (selects_res.length > 1){
						window.open('project/tabel/get_tabel_csv.php?p1='+dat_1+'&p2='+dat_2+'&p3='+selects_res);
					}else{
						window.open('project/tabel/get_tabel_csv.php?p1='+dat_1+'&p2='+dat_2+'&p3=all');
					}
				}else{
					alert('Месяц и год должны быть одинаковым');
				}
			}
			</script>";
			if ($_GET["p3"]!=="f") echo "<a href='".$pageurl."&p3=f'>Уволеные</a>";
			if ($_GET["p3"]=="f") echo "<a href='".$tabelfailed."'>Работающие</a>";
		echo "</div>";
		echo "</td>";
	echo "</tr></table><br>";






   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////

		echo "<table class='rdtbl tbl' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>";
		echo "<td class='Field' style='padding: 2px;'><div class='wdc' style='width: 140px;'>Ресурс</div></td>";
	
		if ($user['ID'] == 232 || $user['ID'] == 43)
			echo "<td class='Field' style='text-align:center;padding: 2px;'> Должность </td>";
		
		echo "<td class='Field' style='padding-left: 2px; padding-right: 2px;'><div class='wdc' style='width: 30px;'>М<br>О</div></td>";

		if (db_adcheck("db_tabel")) {
			echo "<td class='Field' style='padding-left: 2px; padding-right: 2px;'><span class='popup'  style='margin: 0px;' onClick='ShowHide(\"popup_0\");'>>></span><br><span class='ltpopup' style='margin: 0px;'><div id='popup_0' class='ltpopup' style='position:fixed; top:140px; left:215px; width: 800px; padding: 20px;'>";

				echo "<b style='margin-right: 30px; margin-left: 50px; font-size: 14pt;'>ВЫБРАННЫМ:</b> ";

				echo "С <select onchange='document.mainform.firstday.value=this.value;'>";
					$maxDD = DI_MNum($DI_MM,$DI_YY);
					for ($j=0;$j < $maxDD;$j++) {
						echo "<option value='".($j+1)."' ";
						if ($j==0) echo "selected";
						echo ">".($j+1);
					}
				echo "</select> ПО <select onchange='document.mainform.secondday.value=this.value;'>";
					for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
						echo "<option value='".($j+1)."' ";
						if ($j+1==$maxDD) echo "selected";
						echo ">".($j+1);
					}
				echo "</select>    (выберите числа с какое по какое)<br><hr></hr>";

				echo "<table style='width: 100%; border: none;'><tr><td style='width: 50%; border: none; background: none; text-align: left; vertical-align: top;'>";

				echo "<b>ПРОСТАНОВКА ТОЛЬКО В ПЛАН</b><br><br>";

				echo "<input type='radio' class='rinp' name='variant' value='by_st'> Согласно текущих графиков работ<br>";

				echo "<br><input id='work_id' type='radio' class='rinp' name='variant' value='work'> Работает: Смена<select onchange='document.mainform.var_smena.value=this.value;'>";
					echo "<option value='1' selected>1";
					echo "<option value='2'>2";
					echo "<option value='3'>3";
				echo "</select> Время, ч <input type='text' value='8' style='width: 40px;' onchange='document.mainform.var_time.value=this.value;' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, 'var_time', event)\"><br>";

				echo "<br><input type='radio' class='rinp' name='variant' value='copy'> Копировать с <select name='firstcopy'>";
					$maxDD = DI_MNum($DI_MM,$DI_YY);
					for ($j=0;$j < $maxDD;$j++) {
						echo "<option value='".($j+1)."' ";
						if ($j==0) echo "selected";
						echo ">".($j+1);
					}
				echo "</select> на <select name='secondcopy'>";
					for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
						echo "<option value='".($j+1)."' ";
						if ($j+1==$maxDD) echo "selected";
						echo ">".($j+1);
					}
				echo "</select><br><br>";


				echo "<input type='radio' class='rinp' name='variant' value='clear'> Очистить<br><br>";

				echo "<b>ПРОСТАНОВКА ТОЛЬКО В ФАКТ</b><br><br>";
				echo "<input type='radio' class='rinp' name='variant' value='nnn'> <b>НН</b> - Отсутствует<br>";
				echo "<input type='radio' class='rinp' name='variant' value='nnpr'> <b>ПР</b> - Прогул<br>";
				echo "<input type='radio' class='rinp' name='variant' value='gosob'> <b>ГО</b> &nbsp;&nbsp; - Гос. обязанности<br>";
				echo "<input type='radio' class='rinp' name='variant' value='inwork'> Сбросить статус<br>";

				echo "<br><input id='work_f_id' type='radio' class='rinp' name='variant' value='work_f'> Работает: Смена<select onchange='document.mainform.var_smena_f.value=this.value;'>";
					echo "<option value='1' selected>1";
					echo "<option value='2'>2";
					echo "<option value='3'>3";
				echo "</select> Время, ч <input type='text' value='8' style='width: 40px;' onchange='document.mainform.var_time_f.value=this.value;' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, 'var_time_f', event)\"><br>";

				echo "</td><td style='border: none; background: none; text-align: left; vertical-align: top;'>";

				echo "<b>ПРОСТАНОВКА И В ПЛАН И В ФАКТ</b><br><br>";
				echo "<input type='radio' class='rinp' name='variant' value='otpusk'> <b>ОТ</b> - Очередной отпуск<br>";
				echo "<input type='radio' class='rinp' name='variant' value='admotpusk'> <b>ДО</b> - Административный отпуск<br>";
				echo "<input type='radio' class='rinp' name='variant' value='kommand'> <b>К</b> &nbsp;&nbsp; - Командировка<br>";
				echo "<input type='radio' class='rinp' name='variant' value='seek'> <b>Б</b> &nbsp;&nbsp; - Больничный отпуск<br>";
				echo "<input type='radio' class='rinp' name='variant' value='filed'> <b>Х</b> &nbsp;&nbsp; - Уволен<br>";
				echo "<input type='radio' class='rinp' name='variant' value='v_7'> <b>В</b> &nbsp;&nbsp; - Выходные / праздничные дни<br>";
				echo "<input type='radio' class='rinp' name='variant' value='v_15'> <b>ВО</b> &nbsp;&nbsp; - В счёт очередного отпуска<br>";
				echo "<input type='radio' class='rinp' name='variant' value='v_8'> <b>ЛЧ</b> &nbsp;&nbsp; - Сокращённое рабочее время<br>";
				echo "<input type='radio' class='rinp' name='variant' value='v_9'> <b>НВ</b> &nbsp;&nbsp; - Дополнительный выходной день<br>";
				echo "<input type='radio' class='rinp' name='variant' value='v_11'> <b>РП</b> &nbsp;&nbsp; - Простой по вине работодателя<br>";
				echo "<input type='radio' class='rinp' name='variant' value='v_12'> <b>У</b> &nbsp;&nbsp; - Отпуск дополнительный (оплачиваемый учебный)<br>";
				echo "<input type='radio' class='rinp' name='variant' value='v_13'> <b>ПК</b> &nbsp;&nbsp; - Повышение квалификации<br>";
				echo "<input type='radio' class='rinp' name='variant' value='v_14'> <b>НП</b> &nbsp;&nbsp; - Простой независящий от работодателя и работника<br><br>";

				echo "</td></tr></table>";

		 	  // TID "ОТ|ДО| Х| Б|НН|ПР| В|ЛЧ|НВ| K|РП| У|ПК|НП"
		 	  // TID " 1| 2| 3| 4| 5| 6| 7| 8| 9|10|11|12|13|14"

				echo "<hr></hr><input type='submit' style='margin-left: 50px;' value='Применить'>";
			echo "</div></span><input type='checkbox' name='selectall' onClick=\"selall(this);\"></td>";
		}

			for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
				$xwd = date("w",mktime (0,0,0,$DI_MM+1,$j+1,$DI_YY))*1;
				if ($xwd==0) $xwd=7;
				$cl = " style='padding: 2px;'";
				if ($xwd>5) $cl = " style='background: #ffeac8; padding: 2px;'";
				echo "<td class='Field'".$cl."><div class='wdc'>".($j+1)."</div><div class='wn'>".$DI_WName[$xwd]."</div></td>";
			}
			echo "<td class='Field'>Нар.</td>";
			echo "<td class='Field' style='padding: 2px;'><div class='wdc' style='width: 45px;'>План<br>Факт</div></td>";
		echo "</tr>";
		echo "</thead>";


   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
	echo "<tbody>";

	if ($_GET["p3"]!=="f") 
	{
		$query = "SELECT * FROM ".$db_prefix."db_otdel where (PID = '0')  order by binary(NAME)";
		$xxx = dbquery( $query );
		
		while($res = mysql_fetch_array($xxx)) 
		{
			OpenID_otdel($res,0);
		}
	} 
	else 
	{
		// echo "disarmed from: $from_day to: $to_day<br>";

		$query = "SELECT *, okb_db_special.NAME as SpecialName FROM ".$db_prefix."db_resurs 
		LEFT JOIN okb_db_special ON okb_db_special.ID = okb_db_resurs.ID_special
		where (TID = '1') order by binary(NAME)";
		
		$query = "
					SELECT res.*, SUM( tab.FACT ) fact
					FROM okb_db_resurs res
					LEFT JOIN okb_db_tabel tab ON tab.ID_resurs = res.ID
					WHERE 
					res.TID = '1'
					AND
					fact <> 0
					AND
					tab.DATE BETWEEN $from_day AND $to_day
					GROUP BY res.ID
					ORDER by res.NAME";
		
		$xxx = dbquery( $query );

		while( $res = mysql_fetch_array($xxx) )
			OpenID($res);
	}

	echo "<script>
	sel_print_res = '';
	sel_print_ind = 0;
	
	var arr_tr_r_bg = [];
	function tr_row_over(obj){
		for (var tr_r_c = 0; tr_r_c < obj.cells.length; tr_r_c++){
			arr_tr_r_bg[tr_r_c] = getComputedStyle(obj.cells[tr_r_c]).background;
			obj.cells[tr_r_c].style.background=\"#BCF5CE\";
		}
	}

	function tr_row_out(obj){
		for (var tr_r_c = 0; tr_r_c < obj.cells.length; tr_r_c++){
			obj.cells[tr_r_c].style.background=arr_tr_r_bg[tr_r_c];
		}
	}
	
	function chech_all_or_sel(){
		for (var s_d_l=0; s_d_l<document.getElementsByName('resursIDS[]').length; s_d_l++){
			if(document.getElementsByName('resursIDS[]')[s_d_l].checked==true){
				sel_print_res = sel_print_res + document.getElementsByName('resursIDS[]')[s_d_l].value + '|';
				sel_print_ind = 1;
			}
		}
		if (sel_print_ind == 1){
			window.open('index.php?do=show&formid=81&p0=".$txtdd."&p1='+sel_print_res);
		}
		if (sel_print_ind == 0){
			window.open('index.php?do=show&formid=81&p0=".$txtdd."');
		}
	}
	</script>";
	echo "</tbody>";
   ////////////////////////////////////////////////////////////////////////////////////


	echo "</table>";
	echo "</form>";
	echo "<form>";


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

function calcPlanFact( $dep_id )
{
	global $from_day, $to_day;

$query = "
					SELECT 
					SUM( FACT ) fact 
					FROM okb_db_tabel
					WHERE 
					DATE >=$from_day 
					AND DATE <= $to_day
					AND ID_resurs IN (
					SELECT ID_resurs FROM okb_db_shtat 
					where (ID_otdel = $dep_id ) 
					and (NOTTAB = '0') 
					AND NAME != '' 
					AND NAME != 'Вакансия ..')
				";
$result = dbquery( $query );
$row = mysql_fetch_assoc( $result );
$fact = $row['fact'];
$plan = 0 ;

$query = 			"SELECT shtat.ID_resurs id, 
					res.ID_tab_st tab_st
					FROM okb_db_shtat shtat
					LEFT JOIN okb_db_resurs res ON res.ID=shtat.ID_resurs
					where (shtat.ID_otdel = $dep_id ) 
					and (shtat.NOTTAB = '0') 
					AND shtat.NAME != '' 
					AND shtat.NAME != 'Вакансия ..'
				";

$emp_arr = [];
$result = dbquery( $query );
while( $row = mysql_fetch_assoc( $result ) )
		$emp_arr[ $row['id'] ] = $row['tab_st'];

foreach( $emp_arr AS $key => $val )
{
$query = 			"SELECT 
					SUM( HOURS ) sum
					FROM okb_db_tab_sti
					WHERE 
					DATE >=$from_day 
					AND DATE <= $to_day
					AND ID_tab_st = $val
					";

$result = dbquery( $query );
if( $row = mysql_fetch_assoc( $result ) )
		$plan += $row['sum'];
}

return ['fact' => $fact, 'plan' => $plan ];
}

function conv( $str )
{
  global $dbpasswd;
  if( strlen( $dbpasswd ) )
    return $str;
      else
        return iconv("UTF-8", "Windows-1251", $str );
}

?>

<script>
if( dep_emp_only )
{
  $('tr.dep_head').hide();

  var trs = $('tr.cl_1');

    $.each( trs , function( key, item )
    {
      var id = $( item ).attr('data-tab-id');
      var dep_id = $( item ).attr('data-dep-id');
      
      if( id != user_id )
      	$( item ).hide();
			else      
				$('tr[data-id="' + dep_id + '"]').show();
    });

 }
</script>
