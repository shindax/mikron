<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.LaborRegulationsViolationItem.php" );

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
$day = 1 * $_POST['day'];
$shift = 1 * $_POST['shift'];
$can_edit = 1 * $_POST['can_edit'];

$month = $month < 10 ? "0$month" : $month;
$day = $day < 10 ? "0$day" : $day;
$datestring = $year.$month.$day;
$date = "$year-$month-$day";
$dep_conf = [];

  try
    {
        $query = "SELECT * 
                  FROM `labor_regulations_violation_confirmation`
                  WHERE 
                  DATE_FORMAT( date,'%Y') = $year
                  AND
                  DATE_FORMAT( date,'%m') = $month
                  AND
                  DATE_FORMAT( date,'%d') = $day
                  AND
                  shift = $shift
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
                SELECT resurs.NAME resurs_name, resurs.ID resurs_id, otdel.ID otdel_id, otdel.NAME otdel_name
                FROM `okb_db_zadanres` zadan
                LEFT JOIN okb_db_resurs resurs ON resurs.ID = zadan.ID_resurs
                LEFT JOIN okb_db_shtat shtat ON shtat.ID_resurs = zadan.ID_resurs
                LEFT JOIN okb_db_otdel otdel ON shtat.ID_otdel = otdel.ID
                WHERE 
                zadan.SMEN = $shift
                AND
                zadan.DATE = $datestring
                GROUP BY resurs.ID
                ORDER BY otdel.NAME, resurs.NAME
                 ";


                    $stmt = $pdo->prepare( $query );
                    $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    $data = [];

    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    {
        if( 1 * $row -> resurs_id )
        {
          $data[ $row -> otdel_id ]['name'] = conv( $row -> otdel_name );
          $data[ $row -> otdel_id ]['id'] = conv( $row -> otdel_id );
          $data[ $row -> otdel_id ]['childs'][] = $row -> resurs_id ;
          $data[ $row -> otdel_id ]['confirmed'] = $dep_conf[ $row -> otdel_id ] ;
        }
    }    

    $str = "";

  foreach( $data AS $key => $val )
    {
      $conf = $val['confirmed'] ? "checked" : "";
      $dep_name = $val['name'];
      if( strlen( $dep_name ) == 0 )
        $dep_name = conv("Отстутствуют в штатном расписании");

      $str .= "<div class='row'>
                <div class='col-sm-9'><h4 class='badge-info'>$dep_name</h4></div>
                <div class='col-sm-1 ack_info_div $conf'>
                <span>".conv("Подтверждено")."</span>
                <input class='ack_radio' type='radio' data-dep_id='$key' $conf/>
                </div>
                <div class='col-sm-2'>
          <button class='btn btn-big btn-primary float-right print_button' id='{$val['id']}' ".( $val['id'] ? "" : "disabled").">".conv("Распечатать")."</button>
        </div>
              </div>";
      foreach( $val['childs'] AS $ckey => $cval ) 
      {
        $cp = new LaborRegulationsViolationItem( $pdo, $cval, $date, $shift, $val['confirmed'] ? 0 : $can_edit );
        $str .= $cp -> GetTable();
      }

    }

if( strlen( $dbpasswd ))
  echo $str;
    else
      echo iconv("Windows-1251", "UTF-8", $str );
      
 