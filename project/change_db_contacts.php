<?php
// $change_id - ID элемента у которого изменился статус

$result11 = dbquery("SELECT * FROM ".$db_prefix."db_contacts where (ID='".$change_id."') ");
$name11 = mysql_fetch_array($result11);
$name12 = $name11['ID_shtat'];
$name13 = $name11['ID_SPECIAL'];

$result_5 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID='".$name12."') ");
$name_5 = mysql_fetch_array($result_5);
$name2_5 = $name_5['ID_resurs'];

$result = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID='".$name2_5."') ");
$name = mysql_fetch_array($result);
$name2 = $name['NAME'];

$result22 = dbquery("SELECT * FROM ".$db_prefix."db_special where (ID='".$name13."') ");
$name22 = mysql_fetch_array($result22);
$name23 = $name22['NAME'];

dbquery("UPDATE ".$db_prefix."db_contacts SET SPECIAL='".$name23."' where (ID='".$change_id."') ");
dbquery("UPDATE ".$db_prefix."db_contacts SET ID_resurs='".$name['ID']."' where (ID='".$change_id."') ");
dbquery("UPDATE ".$db_prefix."db_contacts SET FIO='".$name2."' where (ID='".$change_id."') ");

?>