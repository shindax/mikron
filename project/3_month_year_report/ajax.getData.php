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
$from = 0 ;
$to = 0 ;

$radio = $_POST['radio'];
$month = $_POST['month'];
$month = $month < 10 ? "0$month" : $month ;
$year = $_POST['year'];


if( $month >= 3 )
{
  $frommonth = $month - 2 ;
  $frommonth = $frommonth < 10 ? "0$frommonth" : $frommonth ;
  $from = $year.$frommonth."01";
  $to = $year.$month."31";
}
else
{
  if( $month == 2 ) // If february
  {
    $from = ( $year - 1 )."1201";
    $to = $year.$month."31";
  }
  else  // If January
  {
    $from = ( $year - 1 )."1101";
    $to = $year.$month."31";
  }

}

$data = [];
$dep_names = [];
$day_types = [];
$day_types[0] = conv('РД');
$total = [];

for( $i = 0 ; $i < 17 ; $i ++ )
{
    $total[ $i ]['total'] = 0 ;
    $total[ $i ]['plan'] = 0 ;
    $total[ $i ]['fact'] = 0 ;
}

try
        {
            $query = "
                        SELECT day_type_id, day_type_short 
                        FROM okb_db_tabel_day_type
                        WHERE 1
                        ";

            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            $day_types[ $row -> day_type_id ] = conv( $row -> day_type_short );

        try
        {
            $query = "
                        SELECT 
                        tabel.TID tid,
                        tabel.PLAN plan,
                        tabel.FACT fact,
                        tabel.DATE date,

                        otdel.ID dep_id,
                        otdel.NAME dep_name,

                        shtat.NAME res_name

                        FROM okb_db_tabel tabel
                        LEFT JOIN okb_db_shtat shtat ON shtat.ID_resurs = tabel.ID_resurs
                        LEFT JOIN okb_db_otdel otdel ON otdel.ID = shtat.ID_otdel
                        WHERE 
                        shtat.presense_in_shift_orders = $radio
                        AND
                        tabel.DATE >= $from
                        AND
                        tabel.DATE <= $to
                        ORDER BY 
                        otdel.NAME,
                        tabel.TID
                        ";

            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
            $dep_id = $row -> dep_id ;
            $tid = $row -> tid ;
            $plan = $row -> plan ;
            $fact = $row -> fact ;
            $dep_names[ $dep_id ] = conv( $row -> dep_name );

            if( !isset( $data[ $dep_id ] ))
                for( $i = 0 ; $i < 17 ; $i ++ )
                {
                    $data[ $dep_id ][ $i ]['total'] = 0 ;
                    $data[ $dep_id ][ $i ]['plan'] = 0 ;
                    $data[ $dep_id ][ $i ]['fact'] = 0 ;
                }

            $data[ $dep_id ][ $tid ]['total'] ++;
            $data[ $dep_id ][ $tid ]['plan'] += $plan;
            $data[ $dep_id ][ $tid ]['fact'] += $fact;
        }

$str .= "<table class='tbl result_table'>";
$str .= "<col width='20%'>";
$str .= "<tr class='first'>";
$str .= "<td class='Field'>".conv("Подразделение")."</td>";

foreach( $day_types AS $value )
    $str .= "<td class='Field'>$value</td>";
$str .= "</tr>";

foreach( $data AS $key => $value )
{
    $str .= "<tr>";
    $str .= "<td class='Field'>".$dep_names[ $key ]."</td>";
    foreach( $value AS $skey => $svalue )
    {
        $total[ $skey ]['total'] += $svalue['total'];
        $total[ $skey ]['plan'] += $svalue['plan'];
        $total[ $skey ]['fact'] += $svalue['fact'];

        $str .= "<td class='Field AC'>".$svalue['total']."<br>".$svalue['plan']."<br>".$svalue['fact']."</td>";
    }
    $str .= "</tr>";
}

$str .= "<tr class='total'><td class='Field AC'>".conv("Итого")."</td>";

foreach( $total AS $key => $value )
{
  $str .= "<td class='Field AC'>".$value['total']."<br>".$value['plan']."<br>".$value['fact']."</td>";
}

$str .= "</tr></table>";

//echo $str ;
echo iconv("Windows-1251", "UTF-8", $str );
 