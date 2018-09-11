<?php
// $change_id - ID элемента у которого изменился статус

$result = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID='".$change_id."') ");
$name = mysql_fetch_array($result);
$name2 = $name['ID_special'];
$name3 = $name['ID_resurs'];

dbquery("UPDATE ".$db_prefix."db_resurs SET ID_special='".$name2."' where (ID='".$name3."') ");

$result5 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID='".$name3."') ");
$name5 = mysql_fetch_array($result5);

dbquery("UPDATE ".$db_prefix."db_shtat SET NAME='".$name5['NAME']."' where (ID='".$change_id."') ");

?>