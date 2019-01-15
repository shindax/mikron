<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
global $pdo ;

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
//    return $str ;
}

function min_to_hour( $min )
{
    $h= round($min/60,2); // Переводм в часы с дробной частью (2.62)
    $hours = floor($min / 60); // Получаем челое число часов (2 часа)
    $m=$h-$hours; // Получаем дробную часть от часов (0.62)
    $minutes= floor($m*60); // Переводи дробную часть от часов в минуты (37 минут) 
    // В итоге получаем $hours : $minutes ( 2:37 )
    $result = $hours ? $hours.":".$minutes : $minutes."м";
    return conv( $result );
}


$year = 2018;
$month = 9;
$max_day = (31 - (($month - 1) % 7 % 2) - ((($month == 2) << !!($year % 4))));

$month = $month < 10 ? "0$month" : $month;
$from = "$year-$month-01";
$to = "$year-$month-$max_day";

$query = '';
$row_arr = [ 1, 10, 20, 40, 50, 60, 70, 80 ];
$row_names = [];
$dep_names = [];
$by_enterprise = [];

foreach( $row_arr AS $key => $val )
	{
		for( $i = 1 ; $i <= $max_day ; $i ++ )
  			$by_enterprise[ $val ][ $i ] = 0 ;
  		$by_enterprise[ $val ][ 'total' ] = 0 ;
	}


  $deps = [] ;
  
  try
    {

        $query = "
                    SELECT DISTINCT otdel.ID dep_id
					FROM labor_regulations_violation_items items 
					LEFT JOIN okb_db_resurs resurs on resurs.ID = items.resource_id
					LEFT JOIN okb_db_shtat shtat on shtat.ID_resurs = items.resource_id 
					LEFT JOIN okb_db_otdel otdel on otdel.ID = shtat.ID_otdel
					WHERE 
					resurs.TID <> 1 
					ORDER BY otdel.NAME
                 ";

        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

     while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
     	foreach ( $row_arr as $key => $val )
     	{
     		 for( $i = 1 ; $i <= $max_day ; $i ++ )
     		 	$deps[ $row -> dep_id ][ $val ][ $i ] = 0 ;
            $deps[ $row -> dep_id ][ $val ][ 'total' ] = 0 ;
     	}

  try
    {

        $query = "
                    SELECT int_id, name
                    FROM labor_regulations_violation_rows
                    WHERE
                    int_id IN (".join(",", $row_arr ).")
                    ORDER BY int_id
                 ";

        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

     while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
      $row_names[ $row -> int_id ] = conv( $row -> name );

  try
    {

        $query = "
                    SELECT ID, NAME FROM `okb_db_otdel` WHERE 1
                 ";

        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

     while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
      $dep_names[ $row -> ID ] = conv( $row -> NAME );
  try
    {

        $query = "
                    SELECT 
                    DAY( items.date ) day, 
                    items.row row_id,
                    ( t_8_9 + t_9_10 + t_10_11 + t_11_12 + t_12_13 + t_13_14 + t_14_15 + t_15_16 +t_16_17 + t_17_18 + t_18_19 + t_19_20 ) total_by_day, 
                    shtat.ID_otdel dep_id
                    FROM labor_regulations_violation_items items
                    LEFT JOIN okb_db_resurs resurs on resurs.ID = items.resource_id
                    LEFT JOIN okb_db_shtat shtat on shtat.ID_resurs = items.resource_id
                    LEFT JOIN okb_db_otdel otdel on otdel.ID = shtat.ID_otdel
                    WHERE
                    items.date BETWEEN '$from' AND '$to'
                    AND
                    row IN (".join(",", $row_arr ).")
                    AND
                    resurs.TID <> 1
                    #AND ( otdel.ID = 72 OR otdel.ID = 93 )
                    ORDER BY otdel.NAME, day
                 ";

        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }


     while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
            $day = $row -> day ;
            $dep_id = $row -> dep_id ;
            $dep_id = $dep_id;
            $row_id = $row -> row_id ;
            $total = 1 * $row -> total_by_day ;

            if( !isset( $deps[ $dep_id ][ $row_id ][ 1 ] ) )
            {
              for( $i = 1 ; $i <= $max_day ; $i ++ )
                  $deps[ $dep_id ][ $row_id ][ $i ] = 0 ;
                $deps[ $dep_id ][ $row_id ][ 'total' ] = 0 ;
            }

            if( isset( $deps[ $dep_id ][ $row_id ][ $day ] ) )
            {
              $deps[ $dep_id ][ $row_id ][ $day ] += $total ;
              $deps[ $dep_id ][ $row_id ][ 'total' ] += $total ;

              $by_enterprise[ $row_id ][ $day ] += $total ;
              $by_enterprise[ $row_id ][ 'total' ] += $total ;              
            }
        }

$str = "<div class='container'>";

foreach ( $deps as $key => $val ) 
{
  $str .= "<div class='row'>
                  <div class='col-sm-10'>
                    <h4 class='badge-info'>".$dep_names[ $key ]."</h4>
                  </div>
                  <div class='col-sm-2'>
                       <button class='btn btn-big btn-primary float-right print_button' id='$key' ".( $key ? "" : "disabled").">".conv("Распечатать")."</button>
                    </div>";
    
    $str .= "<table class='tbl result_table'>";
    $str .= "<col width='15%'>";
                           
    for( $i = 1 ; $i <= $max_day ; $i ++ )
       $str .= "<col width='2%'>";

    $str .= "<col width='5%'>";

  $str .= "<tr class='first'>";
  $str .= "<td class='field'></td>";

  for( $i = 1 ; $i <= $max_day ; $i ++ )
    $str .= "<td class='field AC'>$i</td>";
  
  $str .= "<td class='field AC'>".conv("Итого")."</td>";

  $str .= "</tr>";

  foreach ( $val as $skey => $sval ) 
  {
    $str .= "<tr>";
    $str .= "<td class='field'>".$row_names[ $skey ]."</td>";

    for( $i = 1 ; $i <= $max_day ; $i ++ )
    {
      $value = $sval[ $i ];

      if( $value )
      {
        if( $skey == 1 || $skey == 10 || $skey == 20 || $skey == 30 || $skey == 40 )
          $value =  min_to_hour( $value );
      }
      else
        $value = '-';

      $str .= "<td class='field AC'>$value</td>";
    }

    $total = $sval[ 'total' ] ;
    if( $total )
    {
      if( $skey == 1 || $skey == 10 || $skey == 20 || $skey == 30 || $skey == 40 )
          $total = min_to_hour( $total );
    }
    else
      $total = '-';
      
    $str .= "<td class='field AC'>$total</td>";

    $str .= "</tr>";
  }

  $str .= "</table>";
  $str .= "</div>";
}


// Итого
$str .= "<div class='row'>
                  <div class='col-sm-10'>
                    <h4 class='badge-info'>".conv("Итого по организации")."</h4>
                  </div>
                  <div class='col-sm-2'>
                       <button class='btn btn-big btn-primary float-right print_button' id='$key' ".( $key ? "" : "disabled").">".conv("Распечатать")."</button>
                    </div>";
    
    $str .= "<table class='tbl result_table'>";
    $str .= "<col width='15%'>";
                           
    for( $i = 1 ; $i <= $max_day ; $i ++ )
       $str .= "<col width='2%'>";

    $str .= "<col width='5%'>";

  $str .= "<tr class='first'>";
  $str .= "<td class='field'></td>";

  for( $i = 1 ; $i <= $max_day ; $i ++ )
    $str .= "<td class='field AC'>$i</td>";
  
  $str .= "<td class='field AC'>".conv("Итого")."</td>";

  $str .= "</tr>";

  foreach ( $by_enterprise as $skey => $sval ) 
  {
    $str .= "<tr>";
    $str .= "<td class='field'>".$row_names[ $skey ]."</td>";

    for( $i = 1 ; $i <= $max_day ; $i ++ )
    {
      $value = $sval[ $i ];

      if( $value )
      {
        if( $skey == 1 || $skey == 10 || $skey == 20 || $skey == 30 || $skey == 40 )
          $value =  min_to_hour( $value );
      }
      else
        $value = '-';

      $str .= "<td class='field AC'>$value</td>";
    }

    $total = $sval[ 'total' ] ;
    if( $total )
    {
      if( $skey == 1 || $skey == 10 || $skey == 20 || $skey == 30 || $skey == 40 )
          $total = min_to_hour( $total );
    }
    else
      $total = '-';
      
    $str .= "<td class='field AC'>$total</td>";

    $str .= "</tr>";
  }

  $str .= "</table>";
  $str .= "</div>";

$str .= "</div>";

echo $str;
