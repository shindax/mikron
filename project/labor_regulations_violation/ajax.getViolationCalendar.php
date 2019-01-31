<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.LaborRegulationsViolationItemByMonth.php" );

//error_reporting( E_ALL );
error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
  global $dbpasswd;
    
    if( !strlen( $dbpasswd ) )
      $str = iconv( "UTF-8", "Windows-1251",  $str );

    return $str;
}

global $pdo ;
$year = + $_POST['year'];
$month = + $_POST['month'];
$viol_type = + $_POST['viol_type'];
$user_id = + $_POST['user_id'];
$max_day = (31 - (($month - 1) % 7 % 2) - ((($month == 2) << !!($year % 4))));
$month = $month < 10 ? "0$month" : $month;
$from = "$year-$month-01";
$to = "$year-$month-$max_day";
$query = '';

$dep_conf = [];

  try
    {
        $query = "SELECT * 
                  FROM `labor_regulations_violation_confirmation` 
                  WHERE 
                  DATE_FORMAT( date,'%Y') = $year
                  AND
                  DATE_FORMAT( date,'%m') = $month
                 ";

                    $stmt = $pdo->prepare( $query );
                    $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
      $dep_conf[ $row -> dep_id ] = $row -> confirmed ;

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
          ORDER BY dep_name, res.NAME
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
          $deps[ 0 ]['name'] = conv( "Отсутствуют в штатном расписании" );
          $deps[ 0 ]['childs'][] = $row -> resource_id ;
        }
        else
        {
          $deps[ $row -> dep_id ]['name'] = conv( $row -> dep_name );
          $deps[ $row -> dep_id ]['childs'][] = $row -> resource_id ;
        }
    }

$str = "";

foreach ( $deps as $key => $val ) 
{ 
      $items = 0 ;
      
      $substr = "<div class='row'>
                <div class='col-sm-9'>
                <h4 class='badge-info'>{$val['name']}</h4>
                </div>
                  <div class='col-sm-2'>
                      <button class='btn btn-big btn-primary float-right print_button' id='$key' ".( $key ? "" : "disabled").">".conv("Распечатать")."</button>
                  </div>";

      $caption = 1 ;
      $line = 1 ;
      foreach ( $val['childs'] as $skey => $sval ) 
      {
        $cp = new LaborRegulationsViolationItemByMonth( $pdo, $sval, $month, $year, $viol_type );
        $table = $cp -> GetTable( $line, $caption );

        if( strlen( $table ))
        {
            $caption = 0 ;          
            $substr .= $table;
            $items ++;
            $line ++ ;
        }
      }

  $substr .= "</div>";
  if( $items )
    $str .= $substr;
}

if( strlen( $dbpasswd ) )
  echo $str;
    else
      echo iconv("Windows-1251", "UTF-8", $str );

