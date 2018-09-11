<?php

$result = dbquery("SELECT ID FROM ".$db_prefix."db_itrzadan where (ID='".$render_row['ID']."') ");
$name = mysql_fetch_array($result);
$name2 = $name['ID'];
?>