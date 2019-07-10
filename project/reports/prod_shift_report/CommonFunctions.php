<?php
//require_once('db_config.php');
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");
error_reporting( E_ALL );

function IsValiDate( $date2 )
{
	
  $year1 = date("Y");
  $year2 = substr($date2,0,4);  
  
  $month1 = date("m");
  $month2 = substr($date2,4,2);
  
  $day1 = date("d");
  $day2 = substr($date2,6,2);

  $can = 1 ;
  
  if( $year1 == $year2 && $month1 == $month2 )
    if( $day2 == ( $day1 - 1 ) || ( $day2 == $day1  ) || $day2 == ( $day1 + 1 ) || ( $day2 == ( $day1 + 2 ) ) || ( $day2 == ( $day1 + 3 ) ))
      $can = 0 ;
  
  return $can ;
}

function GetDateProdShift( $date, $shift )
{
global $mysqli ;    

$valid_date = IsValiDate( $date ); 
$res_arr = array();

$mysqli -> query( "SET NAMES utf8" );

$query ="
          SELECT 
                zad_res.SMEN, 
                zad_res.DATE, 
                rs.NAME name, 
                zad_res.ID_resurs 
          FROM okb_db_zadanres zad_res
          INNER JOIN okb_db_resurs rs ON rs.ID = zad_res.ID_resurs 
          WHERE 
                zad_res.DATE = $date 
                AND 
                zad_res.SMEN = $shift 
                GROUP BY zad_res.ID_resurs 
                ORDER BY zad_res.SMEN, rs.NAME";


$result = $mysqli -> query( $query );
  if( ! $result ) 
    exit("Ошибка обращения к БД в функции GetDateProdShift getDataAJAX.php Query :<br>$query<br>".$mysqli->error); 
  
  if( $result -> num_rows )
  {
      while ( $row = $result -> fetch_object() )
      {
		 $res_id = $row -> ID_resurs ;
		 
		 $query2 = "
                  SELECT zadan.*
                  FROM `okb_db_zadan` AS zadan
                  WHERE 
                  zadan.DATE = '$date' 
                  AND 
                  zadan.ID_resurs = $res_id 
                  AND 
                  zadan.SMEN = '$shift'
                  ";
		 
     $result2 = $mysqli -> query( $query2 );
		 $fact = 0 ;
		 
		 while( $row2 = $result2 -> fetch_assoc() )
			$fact += (float) $row2['NORM'] ;
		
		$fact *= 1 ;

     $query2 = "
                  SELECT 
                  dep.ID AS dep_id, 
                  dep.NAME AS dep_name,
                  dep.master_res_id AS master_res_id,
                  res.NAME AS master_name
                  FROM okb_db_shtat AS shtat
                  LEFT JOIN okb_db_otdel AS dep ON dep.ID = shtat.ID_otdel
                  LEFT JOIN okb_db_resurs AS res ON res.ID = dep.master_res_id
                  WHERE shtat.ID_resurs = $res_id
                  ORDER BY master_name
                  ";
     
     $result2 = $mysqli -> query( $query2 );
     $row2 = $result2 -> fetch_assoc();

     $dep_id = $row2['dep_id'];
     $dep_name = conv( $row2['dep_name'] );
     $master_res_id = $row2['master_res_id'];
     $master_name = conv( $row2['master_name'] );

				$res_arr[] = 
          [
            'name' => $row -> name , 
            'hour' => $fact , 
            'res_id' => $res_id,
            'dep_id' => $dep_id,
            'dep_name' => $dep_name,
            'master_res_id' => $master_res_id,
            'master_name' => $master_name
          ];
      }
  }

  return $res_arr;
}


function GetSuffix( $cnt )
{
  $cnt_suff = '';

    switch( $cnt )
    {
        case 1 : 
                 if( $cnt == 11 )
                    $cnt_suff = 'сотрудников';
                      else
                        $cnt_suff = 'сотрудник';
                 break;

        case 2 : 
                 if( $cnt == 12 )
                    $cnt_suff = ' сотрудников';
                      else
                        $cnt_suff = 'сотрудника';
                 break;

        case 3 : 
                 if( $cnt == 13 )
                    $cnt_suff = 'сотрудников';
                      else
                        $cnt_suff = 'сотрудника';
                 break;
      
        case 4 : 
                 if( $cnt == 14 )
                    $cnt_suff = ' сотрудников';
                      else
                        $cnt_suff = 'сотрудника';
                 break;
        
        
        default :
                 $cnt_suff = ' сотрудников'; break ;        
    }
      
     return $cnt_suff ;
  // return iconv("UTF-8", "Windows-1251", $cnt_suff  );
  
}

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}
