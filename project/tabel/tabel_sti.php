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
		obj_s = document.getElementById("s"+obj_id);
		obj_tid = document.getElementById("t"+obj_id);
		obj_td = document.getElementById("td"+obj_id);

		xxx = xx;

		val = obj_inp.value*1;
		tval = obj_tid.value*1;

		
		if (val != 0) {
			obj_td.style.backgroundColor = "#fff";
			obj_inp.style.color = "#000";
			obj_s.disabled = false;
		} else {
			obj_td.style.backgroundColor = "#ffeac8";
			
			obj_inp.style.color = "#ccc";
			obj_s.disabled = true;
		}
	}

</script>
<?php


	$months_select = '
	<select name="auto_month" style="width:120px;text-align:center;">
		<option value="1">январь</option>
		<option value="2">февраль</option>
		<option value="3">март</option>
		<option value="4">апрель</option>
		<option value="5">май</option>
		<option value="6">июнь</option>
		<option value="7">июль</option>
		<option value="8">август</option>
		<option value="9">сентябрь</option>
		<option value="10">октябрь</option>
		<option value="11">ноябрь</option>
		<option value="12">декабрь</option>
	</select>
	';

//	$days_in_month = date('t', strtotime("$year-$month-01"));
	
	$days = '<select name="auto_start_from">';
	
	for ($i = 1; $i <= 31; ++$i) {
		$days .= '<option value="' . $i .  '">' . sprintf("%02d", $i) . '</option>';
	}
	
	$days .= '</select>';
	
	echo '<form method="post" action="">Установить ' . $months_select . ' ' . (isset($_GET['p0']) ? $_GET['p0'] : date('Y')) . ' года
	графиком работы 
	<input value="3" name="auto_workdays" style="width:20px" type="text"/> через
	<input value="3" style="width:20px" name="auto_skipdays" type="text"/> начиная с ' . $days . ',
	смена <select name="auto_smen">
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	</select> план-часов 
	<input type="text" name="auto_hours" value="8" style="width:30px;"/>
	<input type="submit" value="Заполнить"/></form><br/>';
	


if (isset($_POST['auto_hours'])) {
	$year = (isset($_GET['p0']) ? $_GET['p0'] : date('Y'));
	$month = sprintf('%02d', $_POST['auto_month']);
	$start_from_day = $_POST['auto_start_from'];
	
	$days_in_month = date('t', strtotime("$year-$month-01"));

	dbquery('DELETE FROM okb_db_tab_sti WHERE DATE >= ' . $year . $month . sprintf('%02d', $start_from_day) . " AND DATE <= " . $year . $month . sprintf('%02d', $days_in_month) . '
	AND ID_tab_st = ' . $_GET['id']);

	$arr = range($start_from_day, $days_in_month);

    foreach( $arr as $key => $value ) {
        if ( ( $key % ($_POST['auto_skipdays'] + $_POST['auto_skipdays']) ) <= $_POST['auto_workdays'] - 1 ) {
			dbquery( "INSERT INTO `okb_db_tab_sti` (NAME, HOURS, DATE, TID, SMEN, ID_tab_st)
			VALUES('', " . $_POST['auto_hours'] . ", '" . $year . $month . sprintf('%02d', $value) . "', 0, " . $_POST['auto_smen'] . ", " . $_GET['id'] . ")"); 
		}
        else {
			dbquery( "INSERT INTO `okb_db_tab_sti` (NAME, HOURS, DATE, TID, SMEN, ID_tab_st)
			VALUES('', 0, '" . $year . $month . sprintf('%02d', $value) . "', 1, " . "0, " . $_GET['id'] . ")"); 
		}
    }
}



$MM_Name = Array('','Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
$WD_Name = Array('','Пн','Вт','Ср','Чт','Пт','Сб','Вс');

$edit = db_adcheck("db_tab_sti");

$tab_st = dbquery("SELECT * FROM ".$db_prefix."db_tab_st where (ID='".$id."')");
if ($tab_st = mysql_fetch_array($tab_st)) {
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	$URLs = "project/tabel/tabel_stie.php?ID_tab_st=".$tab_st["ID"]."&date=";
	$chdurl = "index.php?do=show&formid=71&id=".$id."&p0=";

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

	if ((isset($_GET["calc"])) && ($edit)) {

		$docalc = $edit;
		if ($tab_st["CICL"]*1==0) $docalc = false;
		if ($tab_st["FDATE"]*1==0) $docalc = false;
		if ($tab_st["NSMEN"]*1==0) $docalc = false;
		if ($tab_st["NSMEN"]*1>1) {
			if ($tab_st["SCICL"]*1==0) $docalc = false;
		}

		$dd = $tab_st["FDATE"]*1;
		$FD_Y = floor($dd/10000);
		$FD_M = floor(($dd-($FD_Y*10000))/100);
		$FD_D = $dd-($FD_Y*10000)-($FD_M*100);

		$first_day = floor(mktime (0,0,0,$FD_M,$FD_D,$FD_Y)/86400);

	/////////////////////////////////////////////////////////////////////////////
	if ($docalc) {

		$cicl = $tab_st["CICL"]*1;
		$smen = 0;
		$sncicl = $tab_st["SNCICL"]*1;
		if ($sncicl==0) $sncicl=1;

		dbquery("DELETE from ".$db_prefix."db_tab_sti where (ID_tab_st='".$tab_st["ID"]."') and (DATE>'".$YY."0100') and (DATE<'".$YY."1232')");
		for($m=1;$m<=12;$m++) {
			$days = date("d",mktime (0,0,0,$m+1,0,$YY))*1;
			for($d=1;$d<=$days;$d++) {
				
				$this_day = floor(mktime (0,0,0,$m,$d,$YY)/86400);
				$dx = $this_day - $first_day;
				$ncicl = floor($dx/$cicl);
				$ddx = $dx-($ncicl*$cicl)+1;
				if ($tab_st["NSMEN"]*1==1) $smen = 1;
				if ($tab_st["NSMEN"]*1>1) {
					if ($tab_st["SCICL"]*1==1) {
						if ($tab_st["NSMEN"]*1==2) $smen = $ncicl-floor($ncicl/2)*2+1;
						if ($tab_st["NSMEN"]*1==3) {
							$smen = $ncicl-floor($ncicl/3)*3+1;
							if ($sncicl==2) {
								$sxx = $smen;
								if ($sxx==2) $smen = 3;
								if ($sxx==3) $smen = 2;
							}
						}
					}
					if ($tab_st["SCICL"]*1==2) {
						if ($tab_st["NSMEN"]*1==2) $smen = $ddx-floor(($ddx-1)/2)*2;
						if ($tab_st["NSMEN"]*1==3) {
							$smen = $ddx-floor(($ddx-1)/3)*3;
							if ($sncicl==2) {
								$sxx = $smen;
								if ($sxx==2) $smen = 3;
								if ($sxx==3) $smen = 2;
							}
						}
					}
				}

				if ($smen==1) {
					$hours = $tab_st["PLAN1"];
					if ($ddx>$tab_st["WD1"]*1) $hours = 0;
				}

				if ($smen==2) {
					$hours = $tab_st["PLAN2"];
					if ($ddx>$tab_st["WD2"]*1) $hours = 0;
				}

				if ($smen==3) {
					$hours = $tab_st["PLAN3"];
					if ($ddx>$tab_st["WD3"]*1) $hours = 0;
				}


				$ddd = date("Ymd",mktime (0,0,0,$m,$d,$YY))*1;
				if (($hours>0) && ($smen>0)) dbquery("INSERT INTO ".$db_prefix."db_tab_sti (DATE, ID_tab_st, HOURS, TID, SMEN) VALUES ('".$ddd."', '".$tab_st["ID"]."', '".$hours."', '0', '".$smen."')");
			}
		}

		// Если используем производственный календарь
		if ($tab_st["ID_tab_pc"]*1>0) {
			for($m=1;$m<=12;$m++) {
				$days = date("d",mktime (0,0,0,$m+1,0,$YY))*1;
				for($d=1;$d<=$days;$d++) {
					$ddd = date("Ymd",mktime (0,0,0,$m,$d,$YY))*1;
					$tab_pci = dbquery("SELECT * FROM ".$db_prefix."db_tab_pci where (ID_tab_pc='".$tab_st["ID_tab_pc"]."') and (DATE='".$ddd."')");
					if ($tab_pci = mysql_fetch_array($tab_pci)) {
						if ($tab_pci["TID"]*1==1) {
							dbquery("DELETE from ".$db_prefix."db_tab_sti where (ID_tab_st='".$tab_st["ID"]."') and (DATE='$ddd')");
							dbquery("INSERT INTO ".$db_prefix."db_tab_sti (DATE, ID_tab_st, HOURS, TID, SMEN) VALUES ('".$ddd."', '".$tab_st["ID"]."', '0', '1', '0')");	
						}
						if ($tab_pci["TID"]*1==2) {
							dbquery("Update ".$db_prefix."db_tab_sti Set HOURS:=HOURS-1 where (ID_tab_st='".$tab_st["ID"]."') and (DATE='$ddd')");
							dbquery("Update ".$db_prefix."db_tab_sti Set TID:='2' where (ID_tab_st='".$tab_st["ID"]."') and (DATE='$ddd')");	
						}
					}
				}
			}
		}

	}
	/////////////////////////////////////////////////////////////////////////////

		redirect($pageurl,"script");
		$redirected = true;
	}
















// Вывод дня
////////////////////////////////////////////////////////////

	function Day_OUT($theday) {
		global $db_prefix, $edit, $URLs, $tab_st;


		$ddd = date("Ymd",$theday)*1;
		$wd = date("w",$theday)*1;
		if ($wd==0) $wd=7;

		$URL=$URLs.$ddd."&value=";

		$val = 0;
		$tid = 0;

		$tab_sti = dbquery("SELECT * FROM ".$db_prefix."db_tab_sti where (ID_tab_st='".$tab_st["ID"]."') and (DATE='".$ddd."')");
		if ($tab_sti = mysql_fetch_array($tab_sti)) {
			$val = $tab_sti["HOURS"]*1;
			$smen = $tab_sti["SMEN"]*1;
			$tid = $tab_sti["TID"]*1;
		}

		$clr = "mon";
		if ($val==0) $clr="sun";
		if ($edit) $clr=$clr."e";

		$editor = "<br>&nbsp;<br>&nbsp;";
		if (($val>0) && ($tid==0)) $editor = "<br><b>".$val."</b><br>".$smen;
		if ($tid==1) $editor = "<b class='b'>В</b><br>&nbsp;<br>&nbsp;";
		if ($edit) {
			$sclr = "000000";
			$dsbld = "";
			if ($val==0) {
				$sclr = "aaaaaa";
				$smen = 0;
				$dsbld = "DISABLED";
			}

			$editor = "<SELECT NAME='ti_".$theday."' id='ti_".$theday."' onChange='vote(this , \"$URL\"+this.value+\"&tid\"); TabCCC(\"i_".$theday."\",1);'>";
			$editor = $editor."<OPTION style='color: #aaa;' VALUE='0' ";
				if ($tid==0) $editor = $editor."SELECTED";
			$editor = $editor.">---";
			$editor = $editor."<OPTION style='color: #000;' VALUE='1' ";
				if ($tid==1) $editor = $editor."SELECTED";
			$editor = $editor.">В";
			$editor = $editor."</SELECT>";

			$editor = $editor."<input type='text' style='color: #".$sclr.";' name='i_".$theday."' id='i_".$theday."' value='".$val."'  onChange='vote(this , \"$URL\"+this.value);  TabCCC(\"i_".$theday."\",0);' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, 'i_".$theday."', event)\">";

			$editor = $editor."<SELECT style='color: #".$sclr.";' NAME='si_".$theday."' id='si_".$theday."' onChange='vote(this , \"$URL\"+this.value+\"&smen\"); if (this.value*1>0) { this.style.color = \"000000\"; } else { this.style.color = \"aaaaaa\"; }' ".$dsbld.">";
			$editor = $editor."<OPTION style='color: #aaa;' VALUE='0' ";
				if ($smen==0) $editor = $editor."SELECTED";
			$editor = $editor.">---";
			$editor = $editor."<OPTION style='color: #000;' VALUE='1' ";
				if ($smen==1) $editor = $editor."SELECTED";
			$editor = $editor.">1";
			$editor = $editor."<OPTION style='color: #000;' VALUE='2' ";
				if ($smen==2) $editor = $editor."SELECTED";
			$editor = $editor.">2";
			$editor = $editor."<OPTION style='color: #000;' VALUE='3' ";
				if ($smen==3) $editor = $editor."SELECTED";
			$editor = $editor.">3";
			$editor = $editor."</SELECT>";
		}

		echo "<td class='".$clr."' data-url='$URL' id='tdi_".$theday."'><i>".date("d",$theday)."</i><br><input type='checkbox' name='days'/><div class='FFx'>".$editor."</div></td>";
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




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



   if (!$redirected) {


	echo "<table style='width: 100%; padding: 0px;' cellpadding='0' cellspacing='0'><tr><td style='text-align: left;'>\n";
		echo "<div class='links'>";
		echo "<a class='lnk' href='".$chdurl.$Last_YY."'><--</a> ".$YY." год <a class='lnk' href='".$chdurl.$Next_YY."'>--></a>";
		echo "</div>";
	echo "</td><td style='text-align: right;'>";
		/*if ($edit) {
		echo "<span class='popup' onClick='ShowHide(\"popup_0\");'>Автозаполнение</span><span class='ltpopup'><div id='popup_0' class='ltpopup'>";
		echo "<a href='javascript:void(0);' onclick='if (confirm(\"Произвести автозаполнение?\")) parent.location=\"$pageurl&yy=$YY&calc\";'>Заполнить</a>";
		echo "</div></span>";
		}*/
	echo "</td></tr></table><br>";



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


$result = dbquery("SELECT * FROM okb_db_resurs WHERE `ID_tab_st` = " . $_GET['id']);


echo '<br/><div style="display:block">';

while ($row = mysql_fetch_assoc($result)) {
	echo $row['NAME'] . '<br/>';
}

echo '</div>';


?>
<script type="text/javascript">

$("input[type=checkbox][name=days]").change(function () {
	var checked_days = $("input[type=checkbox][name=days]:checked");

	if (checked_days.length > 0) {
		$("input[type=text][name^=i_]").off();
		$("select[name^=si_]").off();
		$("select[name^=ti_]").off();
		
		$("input[type=text][name^=i_]").on("change", function () {
			var value = $(this).val();
			
			var i = $(this);
			
			checked_days.each(function () {
				var td = $(this).closest("td");
				var input = td.find("input[type=text][name^=i_]");
				
				input.val(value);
				
				$.get(td.data("url") + value);
				
				TabCCC(input.attr("name"), 0);
			})
		});	
		
		$("select[name^=si_]").on("change", function () {
			var value = $(this).val();

			var i = $(this);
			
			checked_days.each(function () {
				var td = $(this).closest("td");
				var select = td.find("select[name^=si_]");
				
				select.val(value);
				
				$.get(td.data("url") + value + "&smen");
				
				TabCCC(td.find("input[type=text][name^=i_]").attr("name"), 0);
			})
		});
		
		$("select[name^=ti_]").on("change", function () {
			var value = $(this).val();

			var i = $(this);
			
			checked_days.each(function () {
				var td = $(this).closest("td");
				var select = td.find("select[name^=ti_]");
				
				select.val(value);
				
				$.get(td.data("url") + value + "&tid");
				
				if (value == 1) {
					td.find("input[type=text][name^=i_]").val("");
					td.find("select[name^=si_]").val("");
				}
				
				TabCCC(td.find("input[type=text][name^=i_]").attr("name"), 0);
			})
		});
	} else {
		$("input[type=text][name^=i_]").off();
	}
});

</script>