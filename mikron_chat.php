<?php
error_reporting( E_ERROR );
	define("MAV_ERP", TRUE);
	
include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$p_1 = $_GET['p1'];
$p_2 = $_GET['p2'];

if ($p_2=='1') { $chtime = "AND (CHTIME>".mktime(0,0,0,date("m"),date("d"),date("Y")).")";}
if ($p_2=='2') { $chtime = "AND (CHTIME>".mktime(0,0,0,date("m"),date("d")-5,date("Y")).")";}
if ($p_2=='3') { $chtime = "AND (CHTIME>".mktime(0,0,0,date("m")-1,date("d"),date("Y")).")";}
if ($p_2=='4') { $chtime = "";}

$arr_nam = array();
$result3 = dbquery("SELECT * FROM okb_users ORDER BY IO");
while ($name3 = mysql_fetch_array($result3)){
	$arr_nam[$name3['ID']] = $name3['IO'];
}

$patterns = array();
$replacements = array();

	$patterns[0] = "/:oo:/";
	$replacements[0] = "<img style='width:16px; height:16px;' src='project/smails/1 (1).gif'>";
	$patterns[1] = "/:nice:/";
	$replacements[1] = "<img style='width:16px; height:16px;' src='project/smails/1 (2).gif'>";
	$patterns[2] = "/:allok:/";
	$replacements[2] = "<img style='width:16px; height:16px;' src='project/smails/1 (3).gif'>";
	$patterns[3] = "/:good:/";
	$replacements[3] = "<img style='width:16px; height:16px;' src='project/smails/1 (4).gif'>";
	$patterns[4] = "/:mut:/";
	$replacements[4] = "<img style='width:16px; height:16px;' src='project/smails/1 (5).gif'>";
	$patterns[5] = "/:hmm:/";
	$replacements[5] = "<img style='width:16px; height:16px;' src='project/smails/1 (6).gif'>";
	$patterns[6] = "/:zloi:/";
	$replacements[6] = "<img style='width:16px; height:16px;' src='project/smails/1 (7).gif'>";
	$patterns[7] = "/:yxa:/";
	$replacements[7] = "<img style='width:16px; height:16px;' src='project/smails/1 (8).gif'>";
	$patterns[8] = "/\[img\]/";
	$replacements[8] = "<br><img onclick='window.open(this.src)' style='cursor:pointer; max-width:350px;' src='";
	$patterns[9] = "/\[:img\]/";
	$replacements[9] = "'><br>";
	$patterns[10] = "/:\)/";
	$replacements[10] = "<img style='width:16px; height:16px;' src='project/smails/1 (4).gif'>";
	$patterns[11] = "/:\(/";
	$replacements[11] = "<img style='width:16px; height:16px;' src='project/smails/1 (6).gif'>";
	$patterns[12] = "/\[a\]/";
	$replacements[12] = "<a style='cursor:pointer;' onclick='window.open(this.innerText);'>";
	$patterns[13] = "/\[:a\]/";
	$replacements[13] = "</a>";
	
$result5_1 = dbquery("SELECT * FROM okb_forms WHERE ID_formgroups!=0 AND SHOWALL=1");
while ($name5_1 = mysql_fetch_array($result5_1)){
	$patterns[] = "/".$name5_1['NAME']."/";
	$replacements[] = "<a target='_blank' href='index.php?do=show&formid=".$name5_1['ID']."'>".$name5_1['NAME']."</a>";
}
	
$result5 = dbquery("SELECT * FROM okb_db_online_chat_curid WHERE (((ID_users='".$p_1."') OR ((ID_users2='".$p_1."') OR (ID_users2='0'))) ".$chtime.") ORDER BY CHTIME");
while ($name5 = mysql_fetch_array($result5)){
	if ($name5['ID_users2']!=='0') { $id_users2 = " <b style='color:red;'> => ".$arr_nam[$name5['ID_users2']].": </b> ";}else{ $id_users2="";}
	$words = $id_users2.$name5['WORDS'];
	try
	{ # »спользование ExceptionТов решает вопросы с выводом ошибок и их последующую обработку
		echo "(".date("Y.m.d  H:i:s", $name5['CHTIME']).") <b style='color:blue;'>".$name5['NICK']."</b>: ".$words."<br>", PHP_EOL;
	}
	catch (Exception $e)
	{ $i = 1; }

}
?>