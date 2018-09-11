<?php

define('MAV_ERP', true);

$result = mysql_fetch_assoc(dbquery("SELECT * FROM `okb_db_hr_req` WHERE `ID` = " . $insert_id . " LIMIT 1"));

$t = iconv('utf-8', 'windows-1251', 'Новая заявка в отдел кадров');

dbquery("INSERT INTO `okb_db_request_events` VALUES (null, " . $insert_id . ", 1, 83, NOW(), 0, '" . $t . "', 'hr', 'comment' )");
dbquery("INSERT INTO `okb_db_request_events` VALUES (null, " . $insert_id . ", 1, 109, NOW(), 0, '" . $t . "', 'hr', 'comment' )");


file_put_contents('/var/www/okbmikron/www/project/1.txt', print_r($result, true));

