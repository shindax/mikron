<?php
// $change_id - ID элемента у которого изменился статус
//$change_id = $_GET['id'];
  
  $result = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID='".$change_id."') ");
  $name = mysql_fetch_array($result);
  $total_1 = $name['FF'];
  $total_2 = $name['II'];
  $total_3 = $name['OO'];
  
  dbquery("UPDATE ".$db_prefix."db_resurs SET NAME='".$total_1." ".$total_2[0].".".$total_3[0].".' where (ID='".$change_id."') ");

?>