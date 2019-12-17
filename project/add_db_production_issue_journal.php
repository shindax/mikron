<?php
// $insert_id - ID нового элемента

	if (!defined("MAV_ERP")) { die("Access Denied"); }

 dbquery("UPDATE okb_db_production_issue_journal SET user_applicant = " . $user['ID'] . "   where (ID='".$insert_id."') ");
 dbquery("UPDATE okb_db_production_issue_journal SET date =  NOW()  where (ID='".$insert_id."') ");




?>