<?php
error_reporting( E_ALL );
error_reporting( 0 );

   $itrid = $_GET['id'];
   $itrstat = $_GET['p8'];
   $itrstat2 = $_GET['p9'];
   $tithead = $_GET['p3'];
   
	if ($tithead == '0') { 	dbquery("UPDATE ".$db_prefix."db_itrzadan SET TIT_HEAD='Задание мне - ' where (ID='".$itrid."') "); }
	if ($tithead == '1') { 	dbquery("UPDATE ".$db_prefix."db_itrzadan SET TIT_HEAD='Задание от меня - ' where (ID='".$itrid."') "); }
	if ($tithead == '2') { 	dbquery("UPDATE ".$db_prefix."db_itrzadan SET TIT_HEAD='Контроль выполнения - ' where (ID='".$itrid."') "); }

	$tithead = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID='".$itrid."') ");
	$tithead = mysql_fetch_array($tithead);

	$tithead_3 = dbquery("SELECT * FROM ".$db_prefix."db_zapros_all where (ID='".$tithead['ID_zapr']."') ");
	$tithead_3 = mysql_fetch_array($tithead_3);

	echo "<H2>".$tithead['TIT_HEAD']."задание №".$itrid."</H2>";
   
if ($itrstat2 == '1') {
	$db = db_itrzadan_statuses;
	$pid = (isset($_GET["pid"]) ? $_GET["pid"] : 0);
	$lid = (isset($_GET["lid"]) ? $_GET["lid"] : 0);
	$addf = (isset($_GET["addf"]) ? $_GET["addf"] : "");
	$addv = (isset($_GET["addv"]) ? $_GET["addv"] : "");
	$addf2 = (isset($_GET["addf2"]) ? $_GET["addf2"] : "");
	$addv2 = (isset($_GET["addv2"]) ? $_GET["addv2"] : "");
	$add_error = "";

	$insert_id = CreateElement($db,$pid,$lid,$addf,$addv,$addf2,$addv2);

	$resh1 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID_users='".$user['ID']."') ");
	$nnam1 = mysql_fetch_array($resh1);
	$resh2 = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan_statuses where (ID='".$insert_id."') ");
	$nnam2 = mysql_fetch_array($resh2);

	dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET ID_edo='".$itrid."' where (ID='".$insert_id."') ");
	dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET USER='".$nnam1['ID']."' where (ID='".$insert_id."') ");

	if ($itrstat == '1') {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Принято к исполнению' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Принято к исполнению' where (ID='".$itrid."') ");
	}
	if ($itrstat == '2') {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Выполнено' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Выполнено' where (ID='".$itrid."') ");
	}
	if ($itrstat == '3') {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Принято' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Принято' where (ID='".$itrid."') ");
	}
	if ($itrstat == '4') {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='На доработку' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='На доработку' where (ID='".$itrid."') ");
	}
	if ($itrstat == '5') {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Аннулировано' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Аннулировано' where (ID='".$itrid."') ");
	}
	if ($itrstat == '6') {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Завершено' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Завершено' where (ID='".$itrid."') ");
		if ($tithead['ID_zapr']!=='0') {
			dbquery("UPDATE ".$db_prefix."db_zapros_all SET STATUS='Выполнено' where (ID='".$tithead['ID_zapr']."') ");
			dbquery("UPDATE ".$db_prefix."db_zapros_all SET DATE_FACT='".$nnam2['DATA']."' where (ID='".$tithead['ID_zapr']."') ");
			dbquery("UPDATE ".$db_prefix."db_zapros_all SET TIME_FACT='".$nnam2['TIME']."' where (ID='".$tithead['ID_zapr']."') ");
			//if ($tithead_3['ID_itrzadan']!=='0') { dbquery("UPDATE ".$db_prefix."db_operitems SET MSG_INFO='' where (ID='".$tithead_3['ID_itrzadan']."') ");}
		}
	}
	
	dbquery("UPDATE ".$db_prefix."db_itrzadan SET EUSER='".$nnam1['ID']."' where (ID='".$itrid."') ");
	dbquery("UPDATE ".$db_prefix."db_itrzadan SET ETIME='".$nnam2['TIME']."' where (ID='".$itrid."') ");

}  
?>