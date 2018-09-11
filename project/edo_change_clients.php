<?php
// $change_id - ID элемента у которого изменился статус

dbquery("UPDATE ".$db_prefix."db_edo_inout_files SET ID_clients_contacts='0' where (ID='".$change_id."') ");
$result = dbquery("SELECT ID_clients FROM ".$db_prefix."db_edo_inout_files where (ID='".$change_id."') ");
$name = mysql_fetch_array($result);
$result2 = dbquery("SELECT NAME FROM ".$db_prefix."db_clients where (ID='".$name['ID_clients']."') ");
$name2 = mysql_fetch_array($result2);

dbquery("UPDATE ".$db_prefix."db_edo_inout_files SET CONTRAGENT='".$name2['NAME']."' where (ID='".$change_id."') ");

?>