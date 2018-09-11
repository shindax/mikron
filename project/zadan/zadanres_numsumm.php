<?php
define("MAV_ERP", TRUE);

include "../../config.php";
include "../../includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

	$res2 = dbquery("SELECT NUMSUMM_PLAN FROM okb_db_zadan where (ID_resurs = '".$_GET['p1']."') and (ID_operitems = '".$_GET['p2']."')");
	$res22 = mysql_fetch_array($res2);
	
	$res22_expl = explode("|",$res22['NUMSUMM_PLAN']);
	$res22_expl[$_GET['p3']]=$_GET['p4'];
	
	$txt_upd = $res22_expl[0]."|".$res22_expl[1];
	
	dbquery("Update okb_db_zadan SET NUMSUMM_PLAN='".$txt_upd."' where (ID_resurs = '".$_GET['p1']."') and (ID_operitems = '".$_GET['p2']."')");
	
	echo $txt_upd." = ".$_GET['p1']." = ".$_GET['p2']." = ".$_GET['p3']." = ".$_GET['p4'];
?>