<style>

	table.month {
		border-collapse: collapse;
		border-spacing: 0px;
		border: none;
		margin: 15px;
	}

	table.month td {
		vertical-align: middle;
		text-align: center;
		padding: 2px;
		border: 1px solid #555;
		background: #fff;
	}

	table.month td.wn {
		background: #C6D8F7;
		vertical-align: middle;
		width: 30px;
	}

	table.month td.sun {
		background: #ffeac8;
	}

	table.month td.mon {
		background: #FFFFFF;
	}

	table.month td.sune {
		background: #ffeac8 URL(style/rbg.png) no-repeat;
	}

	table.month td.mone {
		background: #FFFFFF URL(style/rbg.png) no-repeat;
	}

	table.month td.sunz {
		background: rgba(255, 234, 200, 0.3);
	}

	table.month td.zz {
		background: rgba(255, 255, 255, 0.5);
	}

	table.month tr.first td {
		background: #8ABBF8;
		padding: 5px;
	}

	table.month tr.first td.mtop {
		background: #C6D8F7;
		padding: 7px;
	}

	table.month td i {
		font-size: 12px;
		color: #555;
	}

	b.bclr {
		color: #004386;
	}

	b.b {
		color: #eb5918;
	}

	div.FFx {
		display: block;
		width: 50px;
		padding: 2px;
		margin: 0px;
		text-align: center;
		border: 0px;
	}

	div.FFx input {
		display: block;
		width: 48px;
		margin: 1px;
		text-align: center;
		border: 1px solid #fff;
		font-weight: bold;
		background: none;
	}

	div.FFx select {
		display: block;
		width: 48px;
		margin: 1px;
		text-align: right;
		border: 1px solid #fff;
		font-weight: bold;
		background: none;
	}

	table.shablon td {
		vertical-align: top;
	}

	A.lnk {
		text-decoration: none;
		font: normal 16px "Lucida Console";
	}

</style>
<script language='javascript'>

	function TabCCC(obj_id,xx) {
		obj_inp = document.getElementById(obj_id);
		obj_tid = document.getElementById("t"+obj_id);
		obj_td = document.getElementById("td"+obj_id);

		xxx = xx;

		val = obj_inp.value*1;
		tval = obj_tid.value*1;

		if (xx==1) {
			if ((tval==0) && (val==0)) {
				xxx = 0;
			}
			if (tval==1) {
				obj_inp.value = 0;
				val = 0;
				xxx = 0;
			}
			if (tval==2) {
				obj_inp.style.color = "000000";
				obj_td.className = "mone";
			}
		}
		if (xxx==0) {
			if (val==0) {
				obj_inp.style.color = "aaaaaa";
				obj_td.className = "sune";
				if (tval==2) obj_tid.value = 0;
			} else {
				obj_inp.style.color = "000000";
				obj_td.className = "mone";
			}
			if (xx == xxx) {
				if (tval==1) obj_tid.value = 0;
			}
		}
	}

</script>
<?php

$MM_Name = Array('','Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
$WD_Name = Array('','Пн','Вт','Ср','Чт','Пт','Сб','Вс');

$edit = db_adcheck("db_tab_pci");

$tab_pc = dbquery("SELECT * FROM ".$db_prefix."db_tab_pc where (ID='".$_GET["id"]."')");
if ($tab_pc = mysql_fetch_array($tab_pc)) {
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	$URLs = "project/tabel/tabel_pcie.php?ID_tab_pc=".$tab_pc["ID"]."&date=";
	$chdurl = "index.php?do=show&formid=70&id=".$id."&p0=";

// Получаем год
////////////////////////////////////////////////////////////

	$YY = date("Y");
	if (isset($_GET["p0"])) $YY = $_GET["p0"]*1;
	if ($YY<date("Y")) $edit = false;
	$Last_YY = $YY-1;
	$Next_YY = $YY+1;



// Автозаполнение
////////////////////////////////////////////////////////////

	$redirected = false;

	if ((isset($_GET["do_st_1"])) && ($edit)) {


		dbquery("DELETE from ".$db_prefix."db_tab_pci where (ID_tab_pc='".$tab_pc["ID"]."') and (DATE>'".$YY."0100') and (DATE<'".$YY."1232')");

		for($m=1;$m<=12;$m++) {
			$days = date("d",mktime (0,0,0,$m+1,0,$YY))*1;
			$wd = date("w",mktime (0,0,0,$m,1,$YY))*1;
			if ($wd==0) $wd=7;
			for($d=1;$d<=$days;$d++) {
				$ddd = date("Ymd",mktime (0,0,0,$m,$d,$YY))*1;
				if ($wd<6) dbquery("INSERT INTO ".$db_prefix."db_tab_pci (DATE, ID_tab_pc, HOURS, TID) VALUES ('".$ddd."', '".$tab_pc["ID"]."', '8', '0')");
				if ($wd==7) $wd=0;
				$wd=$wd+1;
			}
		}

		redirect($pageurl,"script");
		$redirected = true;
	}






// Вывод дня
////////////////////////////////////////////////////////////

	function Day_OUT($theday) {
		global $db_prefix, $edit, $URLs, $tab_pc;


		$ddd = date("Ymd",$theday)*1;
		$wd = date("w",$theday)*1;
		if ($wd==0) $wd=7;

		$URL=$URLs.$ddd."&value=";

		$val = 0;
		$tid = 0;

		$tab_pci = dbquery("SELECT * FROM ".$db_prefix."db_tab_pci where (ID_tab_pc='".$tab_pc["ID"]."') and (DATE='".$ddd."')");
		if ($tab_pci = mysql_fetch_array($tab_pci)) {
			$val = $tab_pci["HOURS"]*1;
			$tid = $tab_pci["TID"]*1;
		}

		$clr = "mon";
		if (($val==0) && ($tid!==2)) $clr="sun";
		if ($edit) $clr=$clr."e";

		$editor = "<br>&nbsp;";
		if (($val>0) && ($tid==0)) $editor = "<br><b>".$val."</b>";
		if ($tid==1) $editor = "<b class='b'>В</b><br>&nbsp;";
		if ($tid==2) $editor = "<b class='b'>ЛЧ</b><br><b>".$val."</b>";
		if ($edit) {
			$sclr = "000000";
			if (($val==0) && ($tid!==2)) {
				$sclr = "aaaaaa";
			}

			$editor = "<SELECT NAME='ti_".$theday."' id='ti_".$theday."' onChange='vote(this , \"$URL\"+this.value+\"&tid\"); TabCCC(\"i_".$theday."\",1);'>";
			$editor = $editor."<OPTION style='color: #aaa;' VALUE='0' ";
				if ($tid==0) $editor = $editor."SELECTED";
			$editor = $editor.">---";
			$editor = $editor."<OPTION style='color: #000;' VALUE='1' ";
				if ($tid==1) $editor = $editor."SELECTED";
			$editor = $editor.">В";
			$editor = $editor."<OPTION style='color: #000;' VALUE='2' ";
				if ($tid==2) $editor = $editor."SELECTED";
			$editor = $editor.">ЛЧ";
			$editor = $editor."</SELECT>";


			$editor = $editor."<input type='text' style='color: #".$sclr.";' name='i_".$theday."'  id='i_".$theday."' value='".$val."'  onChange='vote(this , \"$URL\"+this.value); TabCCC(\"i_".$theday."\",0);' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, 'i_".$theday."', event)\">";

		}

		echo "<td class='".$clr."' id='tdi_".$theday."'><i>".date("d",$theday)."</i><br><div class='FFx'>".$editor."</div></td>";
	}





// Вывод месяца
////////////////////////////////////////////////////////////

	function Month_OUT($m) {
		global $YY, $MM_Name, $WD_Name;

		$days = date("d",mktime (0,0,0,$m+1,0,$YY))*1;

		echo "<table class='month'><tr class='first'><td class='mtop' colspan='8'><b class='bclr'>".$MM_Name[$m]." ".$YY."</b></td></tr><tr class='first'><td>№</td>";
		for($i=1;$i<8;$i++) {
			echo "<td>".$WD_Name[$i]."</td>";
		}
		echo "</tr><tr>";

		$wd = date("w",mktime (0,0,0,$m,1,$YY))*1;
		if ($wd==0) $wd=7;				// $wd 1..7

	    // Номер первой недели
	    //////////////////////////////////////////////////

		$WNUM = date("W",mktime (0,0,0,$m,1,$YY))*1;
		echo "<td class='wn'>".$WNUM."</td>";

	    // Пустые дни до
	    //////////////////////////////////////////////////

		for($i=1;$i<$wd;$i++) {
			$clr = " class='zz'";
			if ($i>5) $clr=" class='sunz'";
			echo "<td".$clr."></td>";
		}

	    // Дни месяца
	    //////////////////////////////////////////////////

		for($i=1;$i<=$days;$i++) {
			Day_OUT(mktime (0,0,0,$m,$i,$YY));
			if ($wd==7) {
				echo "</tr>";
				$wd=0;
			    if ($i<$days) {
				echo "<tr>";
				$WNUM = $WNUM + 1;
				echo "<td class='wn'>".$WNUM."</td>";
			    }
			}
			$wd=$wd+1;
		}

	    // Пустые дни после
	    //////////////////////////////////////////////////

		if ($wd!==1) for($i=$wd;$i<=7;$i++) {
			$clr = " class='zz'";
			if ($i>5) $clr=" class='sunz'";
			echo "<td".$clr."></td>";
		}

		echo "</tr></table>";
	}










// Вывод производственного календаря
////////////////////////////////////////////////////////////
   if (!$redirected) {

	echo "<H2>".$tab_pc["NAME"]."</H2>";

	echo "<table style='width: 100%; padding: 0px;' cellpadding='0' cellspacing='0'><tr><td style='text-align: left;'>\n";
		echo "<div class='links'>";
		echo "<a class='lnk' href='".$chdurl.$Last_YY."'><--</a> ".$YY." год <a class='lnk' href='".$chdurl.$Next_YY."'>--></a>";
		echo "</div>";
	echo "</td><td style='text-align: right;'>";
		if ($edit) {
		echo "<span class='popup' onClick='ShowHide(\"popup_0\");'>Автозаполнение</span><span class='ltpopup'><div id='popup_0' class='ltpopup'>";
		echo "<a href='javascript:void(0);' onclick='if (confirm(\"Произвести автозаполнение?\")) parent.location=\"$pageurl&yy=$YY&do_st_1\";'>5 дней 40 ч/нед</a>";
		echo "</div></span>";
		}
	echo "</td></tr></table>";

	echo "<table class='shablon'>";

	for($i=0;$i<=5;$i++) {

		echo "<tr><td>";
			Month_OUT(2*$i+1);
		echo "</td><td>";
			Month_OUT(2*$i+2);
		echo "</td></tr>";

	}

	echo "</table>";

   }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

?>