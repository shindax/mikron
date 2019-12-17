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
   
}dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$years = [];
$query = "SELECT DISTINCT( LEFT( DATE,4 ) ) AS year
			FROM 
			`okb_db_business_trip_records` WHERE 1";
$result = dbquery( $query );

while( $row = mysql_fetch_array($result))
		$years[ $row['year'] ] = [];

foreach ( $years as $key => $value ) 
{
	$query = "	SELECT COUNT( DATE ) AS count, RIGHT( LEFT( DATE, 6 ), 2 ) AS month
				FROM `okb_db_business_trip_records` 
				WHERE LEFT( DATE, 4 ) = $key
				GROUP BY ( month )";
	$result = dbquery( $query );



	while( $row = mysql_fetch_array($result)){
		
		
		$query2 = dbquery("SELECT * FROM `okb_db_business_trip_records` 
		
						WHERE LEFT( DATE, 4 ) = $key AND RIGHT( LEFT( DATE, 6 ), 2 ) = " . $row['month']);
						
			$hasExpired = false;
			while ($row2 = mysql_fetch_assoc($query2)) {
					if (empty($row2['FILENAME']) && (dateDifference($row2['DATE'], date('Ymd')) > 7)) {
						$hasExpired = true;
					}	
			}
		
	
		$years[ $key ] [ ($row['month'] < 10 ? str_replace('0', '', $row['month']) : $row['month']) ] = ["count" => $row['count'], "hasExpired" => $hasExpired];

	}
}
	
echo json_encode( $years );
