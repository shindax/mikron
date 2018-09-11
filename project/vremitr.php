<?php
$iditem = $_GET['id'];
$tip_fail = dbquery("SELECT * FROM ".$db_prefix."db_edo_inout_files where (ID='".$iditem."') ");
$tip_fail_1 = mysql_fetch_array($tip_fail);
$tip_fail_2 = $tip_fail_1['TIP_FAIL'];
$tip_fail_3 = $tip_fail_1['ID_contacts'];
$tip_fail_4 = $tip_fail_1['ID_zak'];
$tip_fail2 = dbquery("SELECT * FROM ".$db_prefix."db_contacts where (ID='".$tip_fail_3."') ");
$tip_fail2_1 = mysql_fetch_array($tip_fail2);
$tip_fail2_2 = $tip_fail2_1['ID_resurs'];
$tip_fail3 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID='".$tip_fail2_2."') ");
$tip_fail3_1 = mysql_fetch_array($tip_fail3);
$con = $tip_fail3_1['ID'];

if ($_GET['p0']=='1') {
	$db = db_itrzadan;
	$pid = (isset($_GET["pid"]) ? $_GET["pid"] : 0);
	$lid = (isset($_GET["lid"]) ? $_GET["lid"] : 0);
	$addf = (isset($_GET["addf"]) ? $_GET["addf"] : "");
	$addv = (isset($_GET["addv"]) ? $_GET["addv"] : "");
	$addf2 = (isset($_GET["addf2"]) ? $_GET["addf2"] : "");
	$addv2 = (isset($_GET["addv2"]) ? $_GET["addv2"] : "");
	$add_error = "";

		$insert_id = CreateElement($db,$pid,$lid,$addf,$addv,$addf2,$addv2);
		$insert_id2 = CreateElement(db_itrzadan_statuses,$pid,$lid);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_edo_vremitr where (ID_contacts='".$iditem."') ");
		$name = mysql_fetch_array($result);
		$result2 = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID='".$insert_id."') ");
		$name2 = mysql_fetch_array($result2);
		$result3 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID_users='".$user['ID']."') ");
		$name3 = mysql_fetch_array($result3);
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET ID_zak='".$tip_fail_4."' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET DATE_PLAN='".$name['DATE_PLAN']."' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET TIME_PLAN='17:00:00' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STARTDATE=CDATE where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STARTTIME=CTIME where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET ID_users='".$con."' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET ID_users2='".$name['ID_users2']."' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET ID_users3='".$name['ID_users3']."' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET TXT='".$name['TXT']."' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET EUSER='".$name3['ID']."' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET ID_edo='".$iditem."' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET TIP_JOB='1' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Новое' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET TIP_FAIL='".$tip_fail_2."' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Новое' where (ID='".$insert_id2."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET USER='".$con."' where (ID='".$insert_id2."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET ID_edo='".$name2['ID']."' where (ID='".$insert_id2."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET TIME='".$name2['ETIME']."' where (ID='".$insert_id2."') ");
		dbquery("UPDATE ".$db_prefix."db_edo_vremitr SET ID_users2='0' where (ID_contacts='".$iditem."') ");
		dbquery("UPDATE ".$db_prefix."db_edo_vremitr SET ID_users3='0' where (ID_contacts='".$iditem."') ");
		dbquery("UPDATE ".$db_prefix."db_edo_vremitr SET DATE_PLAN='0' where (ID_contacts='".$iditem."') ");
		dbquery("UPDATE ".$db_prefix."db_edo_vremitr SET TXT=' ' where (ID_contacts='".$iditem."') ");
}
$usr3 = dbquery("SELECT * FROM ".$db_prefix."db_edo_vremitr where (ID_contacts='".$iditem."') ");
$usr3 = mysql_fetch_array($usr3);
$usr3_1 = $usr3['ID_users2'];
$usr3_2 = $usr3['DATE_PLAN'];
if ($usr3_2=='0') {
	dbquery("UPDATE ".$db_prefix."db_edo_vremitr SET ID_users3='".$con."' where (ID_contacts='".$iditem."') ");
}
if ($tip_fail_2 == '0') {
   $hend = 'Входящий';
   $tbl = 93;
   $hre = 110;
}
if ($tip_fail_2 == '1') {
   $hend = 'Исходящий';
   $tbl = 96;
   $hre = 111;
}
echo "<div class='links'><a href='index.php?do=show&formid=".$hre."&id=".$iditem."' title='Назад к списку'>Назад к редактированию документа</a></div>";
echo "<H2>".$hend." документ</H2>";
echo "<script type='text/javascript'>
var id = getUrlVars()['id'];
history.replaceState(0, 'New page title', 'index.php?do=show&formid=121&id=' + id + '&p4=$usr3_1');
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}</script>";
?>