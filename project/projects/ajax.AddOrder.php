<?php

define("MAV_ERP", TRUE);
error_reporting( 0 );
require_once( "TaskByProjectFunctions.php" );

date_default_timezone_set("Asia/Krasnoyarsk");

global $user, $mysqli;

function MakeIntFromDate( $str )
{
    $new_str = explode( '-', $str ) ;
    return $new_str[0].$new_str[1].$new_str[2] ;
}

$proj_id    = $_POST['proj_id'];
$user_id    = $_POST['user_id'];
$parent_id  = $_POST['parent_id'] ;
$level      = $_POST['level'] ;

$descr = iconv("UTF-8", "Windows-1251", $_POST['descr'] );

$in_name    = $_POST['row_id'] ;
$row_id     = uniqid( $in_name , 1 ); 

$tip_fail = 9 ;
$id_edo = 0 ;

$outstr = "project id : $proj_id\n" ;
    
if( $level )
    {
        $tip_fail = 5 ;
        $id_edo = $parent_id ;
    }

$level ++ ;
$user_res_id = GetUserResourceID( $user_id );
$creator_id = $user_res_id ;

$ord_raw_name = $_POST['ord_name'];
$ord_name = iconv("UTF-8", "Windows-1251", $ord_raw_name );

$date_of_beg_plan  = MakeIntFromDate( $_POST['date_of_beg_plan']  ) ;
$date_of_perf_plan = MakeIntFromDate( $_POST['date_of_perf_plan'] ) ;

$date_of_perf_fact = 0 ;

$executor_list  = $_POST['executors'];

$executors = '';

foreach( $executor_list AS $executor_id )
  $executors .= $executor_id ."|";

$checker_id   = $_POST['checker'] ;

$state = 'Новое' ;
//$state = iconv("Windows-1251", "UTF-8", $state );
$tit_head = 'Задание от меня - ';
//$tit_head = iconv("Windows-1251", "UTF-8", 'Задание от меня - ' );

$time = date("h.i.s") ;
$time = mktime() ;

$date = date("Ymd") ;

$query ="INSERT INTO 
        okb_db_itrzadan(    `TIP_JOB`, `TIT_HEAD`,`TIP_FAIL`, `TXT`, `KOMM1`, 
                            `ID_proj`,`ID_users`,`ID_users2`,`ID_users3`, 
                            `STARTTIME`,`STARTDATE`, `TIME_PLAN`, `DATE_PLAN`,
                            `ID_edo`, `STATUS`, 
                            `ETIME`, `EUSER`,
                            `CDATE`, `CTIME`
                        ) 
        VALUES(             1 , '$tit_head', $tip_fail, '$ord_name', '$descr', 
                                 $proj_id, $user_res_id ,'$executors', $checker_id,
                                '08:00:00', '$date_of_beg_plan','17:00:00', '$date_of_perf_plan',
                                 $id_edo, '$state',
                                '$time', $user_res_id ,
                                 $date, '$time'
              )"; 


$outstr .= $query ;

$result = $mysqli -> query( $query );

if( ! $result ) 
   exit( iconv("UTF-8", "Windows-1251", "Access error N1 to DB in AddOrderAJAX Query is: <br>$query<br>").$mysqli->error ); 

    $id = $mysqli -> insert_id ;

$query ="INSERT INTO
            okb_db_itrzadan_statuses(`DATA`, `TIME`, `STATUS`,`ID_edo`,`USER` ) 
            VALUES( '$date', '$time', '$state', $id, $user_res_id )"; 

$result = $mysqli -> query( $query );

if( ! $result ) 
   exit( "Access error N2 to DB in AddOrderAJAX Query is: <br>$query<br>".$mysqli->error ); 

// **********************************************************************************************
    
    $div_type = 'div_type_project_order';
    $name = "<span class='ordspan' style='padding-left:0px'>&#9899;&nbsp;&nbsp;</span><span id='$id' class='proj_ord'>$ord_name</span>";
    $style = 'background-color:'.GetColor( $level ).';display:table-row' ;
    $row_class = '';
    
    $str = GetRow( 
                    $level , 
                    $user_id,
                    $proj_id,
                    $div_type,
                    $name, 
                    '',
                    $id, 
                    $row_class, 
                    $in_name, 
                    $row_id,
                    $style,  
                    GetSplitDate( $date_of_beg_plan ),
                    GetSplitDate( $date_of_perf_plan ),
                    $creator_id,
                    $executors,
                    $checker_id,
                    $state,
                    $descr
                );


//  echo iconv("Windows-1251", "UTF-8", $str );
  echo $str ;
?>
