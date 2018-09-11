<?php
error_reporting( E_ALL );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");
require_once("class.coop_orders.php");

date_default_timezone_set("Asia/Krasnoyarsk");

global $mysqli;


$today = date("Ymd", time());
$month = date("m", time());
$year = date("Y", time());

$name_date = date(".m.Y", time());

$user_id                 = $_POST['user_id'];
$order_name              = $_POST['order_name'];
$order_id                = $_POST['order_id'];
$dse_name                = iconv( 'utf-8','cp1251', $_POST['dse_name'] );                 
$dse_draw                = iconv( 'utf-8','cp1251', $_POST['dse_draw'] );
$proc_kind_name          = iconv( 'utf-8','cp1251', $_POST['proc_kind_name'] );
$proc_type_name          = iconv( 'utf-8','cp1251', $_POST['proc_type_name'] );
$exec_date               = str_replace( '-', '', $_POST['exec_date']);
$material_type_name      = iconv( 'utf-8','cp1251', $_POST['material_type_name'] );
$other_material_name     = iconv( 'utf-8','cp1251', $_POST['other_material_name'] );
$count                   = $_POST['count'];
$labor_times_for_item    = $_POST['labor_times_for_item'];
$notes                   = iconv( 'utf-8','cp1251', $_POST['notes'] );
$aim_select              = $_POST['aim_select'];

$proc_type = "$proc_kind_name - $proc_type_name";

if( strlen( $other_material_name ) )
  $material_name = $other_material_name ;
    else
       $material_name = $material_type_name ;

$query ="SELECT ID, NAME FROM okb_db_koop_req WHERE 1 ORDER BY ID DESC LIMIT 1"; 

$result = $mysqli -> query( $query );

if( ! $result ) 
 {
    exit("Database access error in ".__FILE__." in line ".__LINE__.": ".$mysqli->error); 
    $error = true ;
 }

if( $result -> num_rows )
 {
  $row = $result -> fetch_object();
  $name = $row -> NAME ;
  $name_arr = explode('.', $name );
  if( $month == $name_arr[1] && $year == $name_arr[2] )
    $num = $name_arr[0] + 1 ;
    else
      $num  = 1 ;
  
  switch( strlen( $num ) )
  {
    case 1: $num = "00$num"; break;
    case 2: $num = "0$num"; break;
    default : break ;
  }

  $name = $num.$name_date ;
 }


$query ="INSERT INTO okb_db_koop_req
                (
                   NAME, OBOZ, CDATE, COUNT, NAZN, ID_zak, VIDRABOT, TXT, ID_users, DATE, material, comment, PLAN_NCH
                ) 
        VALUES( 
                '$name', '$dse_draw', '$today', $count, $aim_select, $order_id, '$proc_type', '$dse_name', $user_id, $exec_date, '$material_name','$notes', $labor_times_for_item
               )"; 
               
//file_put_contents( 'c:\sites\mic.ru\www\project\orders\coop_orders\ajax_log.txt', $query );
               
$result = $mysqli -> query( $query );
$insert_id = $mysqli -> insert_id ;

if( ! $result ) 
 {
    exit("Database access error in ".__FILE__." in line ".__LINE__.": ".$mysqli->error); 
    $error = true ;
 }


  $query = "SELECT cr.ID, cr.NAME, cr.OBOZ, cr.COUNT, cr.CDATE, cr.DATE, cr.STATE, cr.ID_zak, cr.TXT, cr.VIDRABOT, cr.material, cr.comment, cr.NAZN, cr.PLAN_NCH, 
            rc.FIO res_NAME, 
            zak.NAME ord_name, zak.TID ord_type
            FROM okb_db_koop_req cr 
            INNER JOIN okb_users rc ON rc.ID = cr.ID_users
            INNER JOIN okb_db_zak zak ON zak.ID = cr.ID_zak
            WHERE cr.ID = $insert_id ORDER BY CDATE DESC";

//file_put_contents( 'c:\sites\mic.ru\www\project\orders\coop_orders\ajax_log.txt', $query );
            
  $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Database access error in ".__FILE__." in line ".__LINE__.": ".$mysqli->error); 

        if( $result -> num_rows )
            
            {
                $row = $result -> fetch_object();
                $el = new CoopOrder( 
                    $row -> ID,
                    $row -> NAME,
                    $row -> OBOZ,
                    $row -> COUNT,
                    $row -> PLAN_NCH,
                    $row -> CDATE,
                    $row -> DATE,
                    $row -> STATE,
                    $row -> ID_zak,
                    $row -> ord_name,
                    $row -> ord_type,
                    $row -> TXT,
                    $row -> res_NAME,
                    $row -> VIDRABOT,
                    $row -> material,
                    $row -> comment,
                    $row -> NAZN
                );
            }

  $str = $el -> GetHtmlBody();

//echo $str ;
echo iconv( 'cp1251', 'utf-8', $str );
?>
