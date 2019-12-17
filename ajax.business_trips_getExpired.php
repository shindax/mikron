<?php
define("MAV_ERP", TRUE); 
include '../config.php';
include '../includes/database.php';
function dateDifference($date_1 , $date_2 , $differenceFormat = '%r%a' )
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
   
    $interval = date_diff($datetime1, $datetime2);
   
    return $interval->format($differenceFormat);
   
}
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
 
$query = dbquery("SELECT * FROM `okb_db_business_trip_records`");

$expired = [];

while ($row = mysql_fetch_assoc($query)) {
	if (empty($row['FILENAME']) && (dateDifference($row['DATE'], date('Ymd')) > 7)) {
		$expired[] = $row['ID'];
	}		
}

 
	
echo json_encode( $expired );
