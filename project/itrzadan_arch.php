<?php
$result5 = dbquery("SELECT MAX(ID) FROM okb_db_itrzadan_statuses where ((ID_edo='".$render_row['ID']."') and (STATUS='Выполнено')) ");
$name5 = mysql_fetch_row($result5);
$total5 = $name5[0];
$result5 = dbquery("SELECT * FROM okb_db_itrzadan_statuses where (ID='".$total5."') ");
$name5 = mysql_fetch_array($result5);

echo $name5['DATA'][6].$name5['DATA'][7].".".$name5['DATA'][4].$name5['DATA'][5].".".$name5['DATA'][0].$name5['DATA'][1].$name5['DATA'][2].$name5['DATA'][3];

?>