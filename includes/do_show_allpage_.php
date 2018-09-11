<?php

// shindax 01.09.2016. Везде к обращению WHERE ID_users2='exp' добавлено OR ID_users2 LIKE '%exp|%'

	$cur_form = $_GET["formid"];

	$res2_1 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID_users='".$user['ID']."') ");
	$row2_1 = mysql_fetch_array($res2_1);
	$total2_1 = $row2_1['ID'];
	$res1_1 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where ( ( (ID_users2='".$total2_1."') OR ( ID_users2 LIKE '%".$total2_1."|%') )and (STATUS='Новое')) ");
	$row1_1 = mysql_fetch_row($res1_1);
	$total1_1 = $row1_1[0];
	$res1_5 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_zapros_all where ((STATUS='Отправлен') and (ID_users2_plan='".$total2_1."'))");
	$row1_5 = mysql_fetch_row($res1_5);
	$total1_5 = $row1_5[0];
	$block_id = $user['ID'];
	
if ($total1_5 > 0) {
	if ($total1_1 > 0) {
		$show_itr = 3;
	}else{
		$show_itr = 2;
	}
}else{
	if ($total1_1 > 0) {
		$show_itr = 1;
	}else{
		$show_itr = 0;
	}	
}

$cur_for28 = $_GET['formid'];
if ($cur_for28!=='28'){ 
	$animat_pm = "";
	$minch_2 = dbquery("SELECT COUNT(*) FROM okb_db_online_chat_curid WHERE ( ( (ID_users2='".$user['ID']."') OR ( ID_users2 LIKE '%".$user['ID']."|%') ) AND (CHTIME>'".$user["MINI_CHAT"]."')) ");
	$minch_3 = mysql_fetch_row($minch_2);
	if ($minch_3[0]>0){
		$animat_pm = "<script type='text/javascript'>setInterval('document.getElementById(\"border_pm_msg_us\").setAttribute(\"style\", \"margin-left:10px; border-radius: 6px; border:1px solid #888; background:#\"+(Math.floor(Math.random() * (999999 - 100000 + 1)) + 100000)+\";\")',1250)</script>";
	}
}
echo $animat_pm;

if ($show_itr > 0) {
	echo "";
	if ($show_itr == 1) {
		echo "<div id='newitr_1' style='position:absolute; left:250px; top:400px; width:250px; height:124px;'>
		<div id='newitr' style='position:absolute; left:0px; top:0px; background:url(project/img/additr/additr1.png) no-repeat; width:237px; height:124px;'>
		<table><tbody><tr height='30px'><td width='57px'><img style='cursor:pointer;' id='hideitr_us2' src='project/img/hiditr.png'></td><td></td></tr><tr><td></td><td style='font-size:12pt;text-align:center;'>У вас</td></tr><tr><td></td><td style='text-align:center;'><a style='font-size:12pt;' href='index.php?do=show&formid=117'>Новых заданий <b style='font-size:14pt;color:#BBAE00;'>".$total1_1."</b></a></td></tr><tr><td></td><td style='text-align:center;'>(Исполнитель)</td></tr></tbody></table>
		</div></div>";
	}
	if ($show_itr == 2) {
		echo "<div id='newitr2_1' style='position:absolute; left:500px; top:400px; width:250px; height:124px;'>
		<div id='newitr2' style='position:absolute; left:0; top:0px; background:url(project/img/additr/additr1.png) no-repeat; width:237px; height:124px;'>
		<table><tbody><tr height='30px'><td width='57px'><img style='cursor:pointer;' id='hideitr_us3' src='project/img/hiditr.png'></td><td></td></tr><tr><td></td><td style='font-size:12pt;text-align:center;'>У вас</td></tr><tr><td></td><td style='text-align:center;'><a style='font-size:12pt;' href='index.php?do=show&formid=135'>Новых запросов <b style='font-size:14pt;color:#BBAE00;'>".$total1_5."</b></a></td></tr><tr><td></td><td style='text-align:center;'></td></tr></tbody></table>
		</div></div>";
	}
	if ($show_itr == 3) {
		echo "<div id='newitr_1' style='position:absolute; left:250px; top:400px; width:250px; height:124px;'>
		<div id='newitr' style='position:absolute; left:0px; top:0px; background:url(project/img/additr/additr1.png) no-repeat; width:237px; height:124px;'>
		<table><tbody><tr height='30px'><td width='57px'><img style='cursor:pointer;' id='hideitr_us2' src='project/img/hiditr.png'></td><td></td></tr><tr><td></td><td style='font-size:12pt;text-align:center;'>У вас</td></tr><tr><td></td><td style='text-align:center;'><a style='font-size:12pt;' href='index.php?do=show&formid=117'>Новых заданий <b style='font-size:14pt;color:#BBAE00;'>".$total1_1."</b></a></td></tr><tr><td></td><td style='text-align:center;'>(Исполнитель)</td></tr></tbody></table>
		</div></div>";
		echo "<div id='newitr2_1' style='position:absolute; left:500px; top:400px; width:250px; height:124px;'>
		<div id='newitr2' style='position:absolute; left:0; top:0px; background:url(project/img/additr/additr1.png) no-repeat; width:237px; height:124px;'>
		<table><tbody><tr height='30px'><td width='57px'><img style='cursor:pointer;' id='hideitr_us3' src='project/img/hiditr.png'></td><td></td></tr><tr><td></td><td style='font-size:12pt;text-align:center;'>У вас</td></tr><tr><td></td><td style='text-align:center;'><a style='font-size:12pt;' href='index.php?do=show&formid=135'>Новых запросов <b style='font-size:14pt;color:#BBAE00;'>".$total1_5."</b></a></td></tr><tr><td></td><td style='text-align:center;'></td></tr></tbody></table>
		</div></div>";
	}
	
	echo "</div>
	
	<script type='text/javascript'>
	function change_img() {
		var randimg = Math.floor(Math.random() * (4 - 1 + 1)) + 1;";
		
		if ($show_itr == 1) { 
			echo "document.getElementById('newitr').style.backgroundImage = 'url(project/img/additr/additr'+randimg+'.png)';";
		}
		if ($show_itr == 2) { 
			echo "document.getElementById('newitr2').style.backgroundImage = 'url(project/img/additr/additr'+randimg+'.png)';";
		}
		if ($show_itr == 3) { 
			echo "document.getElementById('newitr').style.backgroundImage = 'url(project/img/additr/additr'+randimg+'.png)';";
			echo "document.getElementById('newitr2').style.backgroundImage = 'url(project/img/additr/additr'+randimg+'.png)';";
		}
		
	echo "}
	setInterval('change_img()', 1000);
	setTimeout(function(){ if (document.getElementById('newitr')) document.getElementById('hideitr_us2').onclick = function () { document.getElementById('newitr_1').style.display = 'none';};},1000);
	setTimeout(function(){ if (document.getElementById('newitr2')) document.getElementById('hideitr_us3').onclick = function () { document.getElementById('newitr2_1').style.display = 'none';};},1000);
	</script>";
}
	echo "
	<div id='curloading' style='position:fixed; left:35%; top:40%; display:none;z-index:999'>
	<img src='project/img/loading_2.gif' width='200px'>
	<div style='position:absolute; left:18px; top:85px; width:165px; height:25px; background:#ccc'>
	</div>
	<div style='position:absolute; left:30px; top:90px;'>
	<font color='red'><b>Ждите, идёт обработка</b></font>
	</div>
	</div>
	<div style='
	position:fixed; 
	left:90%; top:15%;'>
	<table><tbody><tr><td style='width:55px;'>
	
		<div onclick='infaa()' onmouseover='getscroll1()' onmouseout='getscroll3()' id='scroll_left' style='display:none; width:41px; height:50px; background:url(project/img/scroll_left.png) no-repeat;'></div>
		
	</td><td>
		
		<div onmouseover='getscroll2()' onmouseout='getscroll3()' id='scroll_right' style='display:none; width:41px; height:50px; background:url(project/img/scroll_right.png) no-repeat;'></div>
		
	</td></tr></tbody></table>
	</div>
	<script type='text/javascript'>
		var	height_document = $(document).width(); 
		var	height_client = $(window).width();
		var scr1 = 7;
		var scr = 0;
		function infaa(){
		}
		function getscroll1(){
			scr = 1;
		}
		function getscroll2(){
			scr = 2;
		}
		function getscroll3(){
			scr = 0;
		}
		function setscr(){
			if (scr == 1){
				document.getElementById('vpdiv').scrollLeft = document.getElementById('vpdiv').scrollLeft - scr1;
				window.scrollBy( -7, 0 );			
			}
			if (scr == 2){
				document.getElementById('vpdiv').scrollLeft = document.getElementById('vpdiv').scrollLeft + scr1;
				window.scrollBy( 7, 0 );			
			}
		}			
		function checkscroll() {
			var	height_client = $(window).width();
			
			if (height_client < height_document) { 
				document.getElementById('scroll_left').style.display = 'block';
				document.getElementById('scroll_right').style.display = 'block';
			}else{
				document.getElementById('scroll_left').style.display = 'none';
				document.getElementById('scroll_right').style.display = 'none';
			}
		}
		setInterval('setscr()', 18);
		setInterval('checkscroll()', 1000);
		
if ('$block_id'!=='28'){
	window.onbeforeunload = function(evt){
		if ('$cur_form'=='28') { return 'Данное сообщение вызвано в случае случайного закрытия/ухода со страницы. Если же вы намеренно уходите/закрываете страницу, подтвердите.';}
		if ('$cur_form'=='157') { return 'Данное сообщение вызвано в случае случайного закрытия/ухода со страницы. Если же вы намеренно уходите/закрываете страницу, подтвердите.';}
		document.getElementById('curloading').style.display = 'block';
	}
}
</script>";
/*	echo "<br><br><table style='
	border: 1px solid #a2c0eb;
	background: #fff;
	padding: 10px 5px 10px 7px;
	margin-left: 5%;
	width:90%;
'><tr><td width='20px'></td><td><br>ss == ".$user['FIO']."</td></tr><tr><td></td></tr></table>";
*/?>