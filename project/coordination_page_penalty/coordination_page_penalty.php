<script type="text/javascript" src="/project/coordination_page_penalty/js/constants.js"></script>
<script type="text/javascript" src="/project/coordination_page_penalty/js/coordination_page_penalty.js?arg=0"></script>
<script type="text/javascript" src="/project/coordination_page_penalty/js/jquery-ui.min.js"></script>

<link rel='stylesheet' href='/project/coordination_page_penalty/css/style.css'>
<link rel='stylesheet' href='/project/coordination_page_penalty/css/bootstrap.min.css'>

<?php
require_once( "classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.CoordinationPagePenaltyTable.php" );
error_reporting( E_ALL );
error_reporting( E_ERROR );

$user_id = $user["ID"];
echo "<script>var user_id = $user_id</script>";
echo "<script>var debug = 0</script>";

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$str = "<h2>".conv("Нарушения в листах согласования")."</h2>";
$str .= "<div class='container'>";
$str .= "<hr>";

$str .= "<div>
                        <span class='label'>".conv( "Дата с")." : </span>
                        <input type='text' id='datepicker_from'>
						<span class='label'> ".conv( "по")." : </span>
						<input type='text' id='datepicker_to'>
         </div><hr>";
              
$str .= "<div class='row'>
                <div class='col-sm-1 offset-sm-11'>
                    <button class='btn btn-big btn-primary float-right hidden' id='print_button'>".conv("Распечатать")."</button>
                </div>
            </div>
        ";

$str .= "<img id='loadImg' src='project/img/loading_2.gif' />";
$str .= "<div class='row'>
                <div id='table_div' class='col-sm-12'></div>";

$str .= "</div>";
$str .= "</div>";
       
echo "<script>debug=1</script>";
$el = new CoordinationPagePenalty( $pdo, 2305 );
$data = $el -> GetData();
_debug( $data, 1 );

// $year_from = 2019;
// $month_from = 3; 
// $day_from = 1;

// $year_to = 2019;
// $month_to = 3;
// $day_to = 31;

// $str = "";
// $substr = "";

// if( $year_from )
// $where = "timestamp >= '$year_from-$month_from-$day_from'";

// if( $year_to )
// $where = "timestamp <= '$year_to-$month_to-$day_to'";

// if( $year_from && $year_to )
// $where = "timestamp BETWEEN '$year_from-$month_from-$day_from' AND '$year_to-$month_to-$day_to'";

// $final_data = [];

// try
//   {
//           $query = "SELECT ord, caption
//                     FROM  coordination_pages_rows
//                     WHERE 1
//                     ORDER BY ord
//                     " ;
//           $stmt = $pdo ->prepare( $query );
//           $stmt -> execute();
//       }
//       catch (PDOException $e)
//       {
//         die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
//       }

//      while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
//        $final_data[ $row -> ord ] = [ 'caption' => $row -> caption, 
//                                       'penalty_rate' => 0, 
//                                       'total_minutes' => 0, 
//                                       'minutes_to_penalty' => 0 ];
//   try
//   {
//           $query = "SELECT `krz2_id`
//                     FROM  `coordination_pages`
//                     WHERE $where
//                     ORDER BY `timestamp`
//                     " ;
//           $stmt = $pdo ->prepare( $query );
//           $stmt -> execute();
//       }
//       catch (PDOException $e)
//       {
//         die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
//       }

//     $pages = 0 ;

//      while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
//      {
//         $pages ++;

//         if( $row -> krz2_id == 0 )
//           continue ;
//         $el = new CoordinationPagePenaltyTable( $pdo, $row -> krz2_id );
//         $data = $el -> GetData();

//         // _debug( $data, 1 );

//         foreach ( $data AS $key => $val ) 
//         {

//           if( $val['from'] == "0000-00-00 00:00:00" && $val['to'] == "0000-00-00 00:00:00" )
//           {
//             $val['diff']['total'] = 0 ;
//             $val['penalty'] = 0 ;
//           }
//           if( $val['from'] != "0000-00-00 00:00:00" && $val['to'] == "0000-00-00 00:00:00" )
//           {
//             $val['penalty'] = 0 ;
//             $val['diff']['total'] = 0 ;
//           }

//           $final_data[ $key ][ 'penalty_rate' ] = $val['penalty_rate'];
//           $final_data[ $key ][ 'penalty_rate2' ] = $val['penalty_rate2'];
//           $final_data[ $key ][ 'penalty_rate3' ] = $val['penalty_rate3'];
         
//           $total_minutes = $val['diff']['total'] - $val['minutes_to_penalty'];
//           if( $total_minutes < 0 )
//             $total_minutes = 0 ;
          
//           $final_data[ $key ][ 'total_minutes' ] += $total_minutes ;
//           $final_data[ $key ][ 'penalty' ] += $val['penalty'];
//           $final_data[ $key ][ 'penalty2' ] += $val['penalty2'];
//           $final_data[ $key ][ 'penalty3' ] += $val['penalty3'];

//           // if( $val['penalty'] || $val['penalty2'] || $val['penalty3'] )
//           //     $final_data[ $key ]['pages'][] = 
//           //   "<a href='index.php?do=show&formid=30&id={$val['krz_id']}' target='_blank'>{$val['krz_name']}</a>";

//            if( $val['penalty'] || $val['penalty2'] || $val['penalty3'] )
//               $final_data[ $key ]['pages'][] = $val['krz_id'];
//         }

//         $substr .= $el -> GetTable();
//     }

//   if( $pages )
//   {
//     $str .= CoordinationPagePenaltyTable :: GetFinalTable( $final_data );
//     $str .= $substr;
//   }
//   else
//   {
//    $str .= "<h3>Нет данных</h3>";
//   }


// _debug( $final_data, 1 );
// if( strlen( $dbpasswd ) )
//   echo iconv("UTF-8", "Windows-1251", $str );
//     else
//       echo $str;
    
echo $str ;

