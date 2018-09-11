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

//echo "<script>alert( $valid_date );</script>";

$mysqli -> query( "SET NAMES utf8" );

$query = 
"SELECT zad_res.SMEN, zad_res.DATE, rs.NAME name, zad_res.ID_resurs 
FROM okb_db_zadanres zad_res
INNER JOIN okb_db_resurs rs ON rs.ID = zad_res.ID_resurs 
WHERE zad_res.DATE = $date AND zad_res.SMEN = $shift GROUP BY zad_res.ID_resurs ORDER BY zad_res.SMEN, rs.NAME";

$result = $mysqli -> query( $query );
  if( ! $result ) 
    exit("Ошибка обращения к БД в функции GetDateProdShift getDataAJAX.php Query :<br>$query<br>".$mysqli->error); 
  
  if( $result -> num_rows )
  {
      while ( $row = $result -> fetch_object() )
      {
		 $res_id = $row -> ID_resurs ;
		 
		 $query2 = "SELECT * FROM `okb_db_zadan` WHERE DATE = '$date' AND ID_resurs = $res_id AND SMEN = '$shift'";
		 $result2 = $mysqli -> query( $query2 );
		 $fact = 0 ;
		 
//		 while( $row2 = $result2 -> fetch_assoc() )
//			$fact += $row2['FACT'] ;

		 while( $row2 = $result2 -> fetch_assoc() )
			$fact += (float) $row2['NORM'] ;
		
		$fact *= 1 ;
		
//		 if( ( $valid_date && $fact > 0 && $fact != '' ) || $valid_date <= 0 )
				$res_arr[] = array( 'name' => $row -> name , 'hour' => $fact , 'res_id' => $row2['ID_resurs'] );
      }
  }

  return $res_arr;
}


function GetSuffix( $cnt )
{
  $cnt_suff = '';

    switch( $cnt % 10 )
    {
        case 1 : 
                 if( $cnt == 11 )
                    $cnt_suff = 'работников';
                      else
                        $cnt_suff = 'работник';
                 break;

        case 3 : 
                 if( $cnt == 13 )
                    $cnt_suff = 'работников';
                      else
                        $cnt_suff = 'работника';
                 break;
       
        case 2 : 
                 if( $cnt == 12 )
                    $cnt_suff = ' работников';
                      else
                        $cnt_suff = 'работника';
                 break;

        
        case 4 : 
                 if( $cnt == 14 )
                    $cnt_suff = ' работников';
                      else
                        $cnt_suff = 'работника';
                 break;
        
        
        default :
                 $cnt_suff = ' работников'; break ;        
    }
//  return $cnt_suff ;
  
  return iconv("UTF-8", "Windows-1251", $cnt_suff  );
  
}

?>