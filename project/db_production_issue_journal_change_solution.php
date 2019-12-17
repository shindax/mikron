<?php
// $change_id - ID элемента у которого изменился статус

 dbquery("UPDATE okb_db_production_issue_journal SET user_engineering = " . $user['ID'] . " where (ID='".$change_id."') ");
 

?>