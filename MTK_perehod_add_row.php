<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$oper_id = $_GET['id'];
$p_1 = $_GET['p1'];

$result2_0 = dbquery("SELECT ID, ID_zak, ID_zakdet FROM okb_db_operitems WHERE (ID='".$oper_id."') ");
$name2_0 = mysql_fetch_row($result2_0);
$result2_1 = dbquery("SELECT ID FROM okb_db_zak WHERE (ID='".$name2_0[1]."') ");
$name2_1 = mysql_fetch_row($result2_1);
$result2_2 = dbquery("SELECT ID FROM okb_db_zakdet WHERE (ID='".$name2_0[2]."') ");
$name2_2 = mysql_fetch_row($result2_2);

$result3 = dbquery("SELECT MAX(TID) FROM okb_db_mtk_perehod WHERE (ID_operitems='".$oper_id."') ");
$name3 = mysql_fetch_row($result3);
dbquery("INSERT INTO okb_db_mtk_perehod (ETIME, EUSER, ID_zak, ID_zakdet, ID_operitems, TID) VALUES ('".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$p_1."', '".$name2_1[0]."','".$name2_2[0]."','".$oper_id."', '".($name3[0]+1)."')");
?>