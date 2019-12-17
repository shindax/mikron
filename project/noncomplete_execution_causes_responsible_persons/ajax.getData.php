<?php
error_reporting( 0 );
require_once( "functions.php" );

$res_id = $_POST['res_id'];
$name = GetResName( $res_id );
$total_data = GetTotalData();
$res_data = GetTotalData( $res_id );


$res_arr = [ 
				["caption" => "По всей организации", "name" => "Brands", "colorByPoint" => true, "data" => $total_data ],
				["caption" => "По пользователю $name", "name" => "Brands", "colorByPoint" => true, "data" => $res_data ],
			];

echo json_encode( $res_arr );