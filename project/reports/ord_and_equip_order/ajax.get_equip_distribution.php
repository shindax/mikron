<?php
error_reporting( 0 );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

global $mysqli ;

$id_arr = $_POST['list'] ;
$id_list = join( ',', $id_arr );
$park_id = $_POST['park_id'];

if( $park_id == '18' )
    $park_id .= ',19,20,21,22,23,24,55';

$data = [];
$error = false;

$ord_arr = [];

foreach( $id_arr AS $val )
    $ord_arr[ $val ] = 0 ;

//file_put_contents( 'c:\OpenServer\domains\mic.ru\project\reports\ord_and_equip_order\ajax_data_log.txt', count( $ord_arr ) );

$query = "SELECT zakdet.ID_zak, operitems.NORM, operitems.NORM_ZAK, operitems.ID_park    
          FROM `okb_db_zakdet` zakdet 
          INNER JOIN okb_db_operitems operitems ON operitems.ID_zakdet = zakdet.ID 
           WHERE zakdet.ID_zak IN ( $id_list ) AND zakdet.NAME <> '' AND operitems.ID_park IN ( $park_id )";

//file_put_contents( 'c:\OpenServer\domains\mic.ru\project\reports\ord_and_equip_order\ajax_log.txt', $query );

$result = $mysqli -> query( $query );

if( ! $result )
    exit("Connection error in ".__FILE__." at ".__LINE__." line. <br />Query is : $query <br />".$mysqli->error);

$total_norm = 0 ;
$park_name = '';
$park_type = '';

if( $result -> num_rows )
{
    while( $row = $result -> fetch_object() )
    {
        $id_zak = $row -> ID_zak;
        
        $norm_zak = $row -> NORM_ZAK;
        $norm_raw = $row -> NORM;
//        $norm = $norm_raw - $norm_zak;

        $norm = $norm_zak; // ???

        $park_name = $row -> NAME;
        $park_type = $row -> MARK;
        $total_norm += $norm ;
        $ord_arr [ $id_zak ] += $norm   ;
    }
}

$str = '';
foreach( $ord_arr AS $key => $val )
    $data[]= ['id' => $key , 'norm' => $val, 'perc' => round( ( $val / $total_norm ) * 100  , 1 )];

//file_put_contents( 'c:\OpenServer\domains\mic.ru\project\reports\ord_and_equip_order\ajax_data_log.txt', json_encode( $data ) );

if( $error )
  $data = array('error' => $error_msg ) ;

echo json_encode( $data );
