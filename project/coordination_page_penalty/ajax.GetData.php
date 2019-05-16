<?php
// error_reporting( E_ALL );
// ini_set('display_errors', true);

error_reporting( 0 );
ini_set('display_errors', false );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.CoordinationPagePenaltyTable.php" );

global $pdo;

function conv( $str )
{
    //return iconv( "UTF-8", "Windows-1251",  $str );
    return $str ;
}

$year_from = $_POST['year_from'];
$month_from = $_POST['month_from'];
$day_from = $_POST['day_from'];

$year_to = $_POST['year_to'];
$month_to = $_POST['month_to'];
$day_to = $_POST['day_to'];

$str = "";
$substr = "";

if( $year_from )
$where = "timestamp >= '$year_from-$month_from-$day_from'";

if( $year_to )
$where = "timestamp <= '$year_to-$month_to-$day_to'";

if( $year_from && $year_to )
$where = "timestamp BETWEEN '$year_from-$month_from-$day_from' AND '$year_to-$month_to-$day_to'";

$final_data = [];

try
  {
          $query = "SELECT ord, caption
                    FROM  coordination_pages_rows
                    WHERE 1
                    ORDER BY ord
                    " ;
          $stmt = $pdo ->prepare( $query );
          $stmt -> execute();
      }
      catch (PDOException $e)
      {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
      }

     while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
       $final_data[ $row -> ord ] = [ 'caption' => $row -> caption, 
                                      'penalty_rate' => 0, 
                                      'total_minutes' => 0, 
                                      'minutes_to_penalty' => 0 ];
  try
  {
          $query = "SELECT `krz2_id`
                    FROM  `coordination_pages`
                    WHERE $where
                    ORDER BY `timestamp`
                    " ;
          $stmt = $pdo ->prepare( $query );
          $stmt -> execute();
      }
      catch (PDOException $e)
      {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
      }

    $pages = 0 ;

     while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
     {
        $pages ++;

        if( $row -> krz2_id == 0 )
          continue ;
        $el = new CoordinationPagePenaltyTable( $pdo, $row -> krz2_id );
        $data = $el -> GetData();

        foreach ( $data AS $key => $val ) 
        {

          if( $val['from'] == "0000-00-00 00:00:00" && $val['to'] == "0000-00-00 00:00:00" )
          {
            $val['diff']['total'] = 0 ;
            $val['penalty'] = 0 ;
          }
          if( $val['from'] != "0000-00-00 00:00:00" && $val['to'] == "0000-00-00 00:00:00" )
          {
            $val['penalty'] = 0 ;
            $val['diff']['total'] = 0 ;
          }

          $final_data[ $key ][ 'penalty_rate' ] = $val['penalty_rate'];
          $final_data[ $key ][ 'penalty_rate2' ] = $val['penalty_rate2'];
          $final_data[ $key ][ 'penalty_rate3' ] = $val['penalty_rate3'];
         
          $total_minutes = $val['diff']['total'] - $val['minutes_to_penalty'];
          if( $total_minutes < 0 )
            $total_minutes = 0 ;
          
          $final_data[ $key ][ 'total_minutes' ] += $total_minutes ;
          $final_data[ $key ][ 'penalty' ] += $val['penalty'];
          $final_data[ $key ][ 'penalty2' ] += $val['penalty2'];
          $final_data[ $key ][ 'penalty3' ] += $val['penalty3'];

          if( $val['penalty'] || $val['penalty2'] || $val['penalty3'] )
          {
              $final_data[ $key ]['pages'][] = 

            "<a href='index.php?do=show&formid=30&id={$val['krz_id']}' target='_blank'>{$val['krz_name']}</a> ( ". CoordinationPagePenaltyTable :: DecodeMinutes( $total_minutes, false ).")";
          }
        }

        $substr .= $el -> GetTable();
    }

  if( $pages )
  {
    $str .= CoordinationPagePenaltyTable :: GetFinalTable( $final_data );
    $str .= $substr;
  }
  else
  {
   $str .= "<h3>Нет данных</h3>";
  }

if( strlen( $dbpasswd ) )
  echo iconv("UTF-8", "Windows-1251", $str );
    else
      echo $str;
    