<?php
dbquery("UPDATE ".$db_prefix."db_logistic_app SET TRANSFER_TIME='00:00:00' where (ID='".$insert_id."') ");
?>