<?php
// $change_id - ID элемента у которого изменился статус
$result = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID='".$change_id."') ");
$name = mysql_fetch_array($result);
$result = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_resurs where (ID_users='".$name['ID_users']."') ");
$name = mysql_fetch_row($result);
$name2 = $name[0];

if ($name2 >'1'){
dbquery("UPDATE ".$db_prefix."db_resurs SET ID_users='0' where (ID='".$change_id."') ");	
$pageurl = $pageurl."&p1=1";
}
?>