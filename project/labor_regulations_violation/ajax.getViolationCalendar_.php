<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.LaborRegulationsViolationItemByMonth.php" );

//error_reporting( E_ALL );
error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

global $pdo ;
$year = 1 * $_POST['year'];
$month = 1 * $_POST['month'];
$viol_type = 1 * $_POST['viol_type'];

//$max_day = cal_days_in_month(CAL_GREGORIAN, $month, $year );
$max_day = (31 - (($month - 1) % 7 % 2) - ((($month == 2) << !!($year % 4))));

$month = $month < 10 ? "0$month" : $month;
$from = "$year-$month-01";
$to = "$year-$month-$max_day";

$query = '';

  try
    {

        $query = "
          SELECT resource_id, shtat.ID_otdel dep_id, otdel.NAME dep_name
          FROM `labor_regulations_violation_items` items
          LEFT JOIN okb_db_shtat shtat ON shtat.ID_resurs = items.resource_id
          LEFT JOIN okb_db_otdel otdel ON shtat.ID_otdel = otdel.ID          
          LEFT JOIN okb_db_resurs res ON res.ID = items.resource_id
          WHERE 
          items.date BETWEEN '$from' AND '$to'
          AND
          row IN ( 1, 10, 20, 30, 40, 50, 60, 70, 80, 90 )
          AND
          res.TID <> 1
          #AND
          #( t_8_9 + t_9_10 + t_10_11 + t_11_12 + t_12_13 + t_13_14 + t_14_15 + t_15_16 + t_17_18 + t_18_19 + t_19_20 ) <> 0
          GROUP BY resource_id
          ORDER BY dep_name
                 ";

         $stmt = $pdo->prepare( $query );
         $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    $deps = [];

    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    {
        if( is_null( $row -> dep_id ))
        {
          $deps[ 0 ]['name'] = conv( "Необходимо проверить штатное расписание" );
          $deps[ 0 ]['childs'][] = $row -> resource_id ;
        }
        else
        {
          $deps[ $row -> dep_id ]['name'] = conv( $row -> dep_name );
          $deps[ $row -> dep_id ]['childs'][] = $row -> resource_id ;
        }
    }

$str = "";

foreach ( $deps as $key => $val) 
{ 
      $items = 0 ;

      $substr = "<div class='row'>
                <div class='col-sm-10'><h4 class='badge-info'>".$val['name']."</h4>
                </div>
                  <div class='col-sm-2'>
                      <button class='btn btn-big btn-primary float-right print_button' id='$key' ".( $key ? "" : "disabled").">".conv("Распечатать")."</button>
                  </div>";
      foreach ( $val['childs'] as $skey => $sval ) 
      {
        $cp = new LaborRegulationsViolationItemByMonth( $pdo, $sval, $month, $year, $viol_type);
        $table = $cp -> GetTable();
        
        if( strlen( $table ))
        {
            $substr .= $table;
            $items ++;
        }
      }

  $substr .= "</div>";
  if( $items )
    $str .= $substr;
}

echo $str;
//echo iconv("Windows-1251", "UTF-8", $str );