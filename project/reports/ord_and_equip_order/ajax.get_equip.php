<?php
error_reporting( 0 );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

function fixJSON($str)
{
    $str = preg_replace_callback('/\\\\u([a-f0-9]{4})/i', create_function('$m', 'return chr(hexdec($m[1])-1072+224);'), $str);
    return iconv('cp1251', 'utf-8', $str);
}

function CollectManualNorm( & $arr , $id )
{
    $arr[18]['norm'] += $arr[ $id ]['norm'];
    unset( $arr[ $id ] );
}

global $mysqli ;

$data = [];
$error = false;
$equipment_arr = [];
$park_arr = [];

$id_arr = join( ',', $_POST['list'] );

$query = "SELECT operitems.NORM_ZAK, operitems.ID_park, park.NAME, park.MARK   
          FROM `okb_db_zakdet` zakdet 
          INNER JOIN okb_db_operitems operitems ON operitems.ID_zakdet = zakdet.ID 
          INNER JOIN okb_db_park park ON park.ID = operitems.ID_park  
          WHERE zakdet.ID_zak IN ( $id_arr ) AND zakdet.NAME <> ''
          ORDER BY park.NAME";

$result = $mysqli -> query( $query );

if( ! $result )
    exit("Connection error in ".__FILE__." at ".__LINE__." line. <br />Query is : $query <br />".$mysqli->error);

if( $result -> num_rows )
{
    while( $row = $result -> fetch_object() )
    {
        $id_park = $row -> ID_park;
        $norm = $row -> NORM_ZAK;
        $equipment_arr[ $id_park ]['norm'] += $norm ;
        $park_arr[ $id_park ] = $id_park ;
    }
}

if( count( $park_arr ) )
{
    $park_query =
        "SELECT *   
          FROM okb_db_park 
          WHERE ID IN ( " . (join(',', $park_arr)) . " )
          ORDER BY NAME";

    $result = $mysqli->query($park_query);

    if (!$result)
        exit("Connection error in " . __FILE__ . " at " . __LINE__ . " line. <br />Query is : $park_query <br />" . $mysqli->error);

    if ($result->num_rows) {
        while ($row = $result->fetch_object()) {
            $id_park = $row->ID;
            $park_name = $row->NAME;
            $park_type = $row->MARK;
            $equipment_arr[$id_park]['id'] = $id_park;
            $equipment_arr[$id_park]['name'] = $park_name;
            $equipment_arr[$id_park]['type'] = $park_type;
        }
    }
}

if( $error )
  $data = array('error' => $error_msg ) ;

$str = '';
$norm_total = 0 ;

// Collect multiple 'manual' operations into 21 cell, and deleting other indexes
CollectManualNorm( $equipment_arr, 19 );
CollectManualNorm( $equipment_arr, 20 );
CollectManualNorm( $equipment_arr, 21 );
CollectManualNorm( $equipment_arr, 22 );
CollectManualNorm( $equipment_arr, 23 );
CollectManualNorm( $equipment_arr, 24 );
CollectManualNorm( $equipment_arr, 55 );

$line = 1 ;
foreach( $equipment_arr AS $eq )
    $data[]= ['line' => $line ++ , 'name' => fixJSON( $eq['name'] ), 'type' => fixJSON( $eq['type'] ), 'norm' => $eq['norm'], 'id' => $eq['id']];

echo json_encode( $data );
