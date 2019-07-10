<link rel='stylesheet' href='/project/labor_regulations_violation/css/bootstrap.min.css'>
<link rel='stylesheet' href='/project/labor_regulations_violation/css/print.css?v=2'>

<style>

*
{
  margin-left : 0px !IMPORTANT;
  padding : 0 !IMPORTANT;
  -webkit-print-color-adjust: exact;
  -webkit-region-break-inside: avoid;  
}

.container
{
  /*margin: -100px !IMPORTANT;*/
  width : 1250px !IMPORTANT;*/  
  padding:0 !IMPORTANT;
}

div.table_div table.tbl
{
  width : 1250px !IMPORTANT;*/
  margin: 0px !IMPORTANT;
  padding:0 !IMPORTANT;
}

 .chief_name, .chief_name u
 {
  font-size: 18px;
 }

.center
{
  text-align: center !IMPORTANT;
  padding : 0 10px 0 10px !IMPORTANT;  
  margin : 0 10px 0 10px !IMPORTANT;  
}

.fio
{
  text-align: center !IMPORTANT;
  border-bottom: 1px solid black;
  padding : 0 10px 0 10px !IMPORTANT;
  margin : 0 10px 0 10px !IMPORTANT;
}

 .sign
 {
  font-size: 10px;
 }

.up
{
    margin-top : 0px !IMPORTANT;
}

.page_of
{
  text-align: right  !IMPORTANT;
}

.row
{
  display: flex;
}

.col-sm-10
{
  width : 80% !IMPORTANT;
}

.col-sm-2
{
  width : 16% !IMPORTANT;
}

.more 
{
    display: block ;
    page-break-after: always;
}


tr.first, tr.first td.field
{
  background: #9dbdfc !IMPORTANT ;
}

tr.even
{
  background: #FFEFD5 !IMPORTANT ;
}

div.page
  {
    width : 100%;
    page-break-before: always;
    page-break-inside: avoid;
  }


.col-sm-1
{
  width : 8%;
}

.col-sm-2
{
  width : 16%;
}

.col-sm-3
{
  width : 24%;
}

.col-sm-10
{
  width : 80%;
}

.result_table
{
  table-layout: fixed !IMPORTANT;
  width : 100% !IMPORTANT;
}

.field
{
  vertical-align: middle !IMPORTANT;
}

.AL
{
  padding-left : 10px !IMPORTANT;
}

h4
{
  margin-bottom : 10px !IMPORTANT;
}

.prn_span
{
 margin : 0px 10px !IMPORTANT; 
}

</style>


<?php
error_reporting( E_ALL );
error_reporting( 0 );
require_once( "classes/db.php" );

global $pdo ;

function conv( $str )
{
  global $dbpasswd;
  if( strlen( $dbpasswd ) )
    return iconv( "UTF-8", "Windows-1251",  $str );
      else return $str ; 
}

function min_to_hour( $min )
{
    $sign = $min < 0 ? "-" : "" ;
    $min = abs( $min );
    $hours = intval( $min / 60 );
    $minutes= $min - $hours * 60;
    $result = $hours ? $hours.":". ( $minutes < 10 ? "0".$minutes : $minutes ) : $minutes.conv("м");
    return $sign.$result;
}

$user_id = $user["ID"];

echo "<script>var res_id = $res_id</script>";

$gdep_id = $_GET['p0'] ;
$chief_name = "";

$year = $_GET['p1'] ;
$month = $_GET['p2'] ;
$str = "";

$max_day = (31 - (($month - 1) % 7 % 2) - ((($month == 2) << !!($year % 4))));

$month = $month < 10 ? "0$month" : $month;
$from = "$year-$month-01";
$to = "$year-$month-$max_day";

$query = '';
$row_arr = [ 1, 10, 20, 40, 50, 60, 70, 80 ];
$row_names = [];
$dep_names = [];
$dep_names[0] = conv("Всего по организации");
$by_enterprise = [];
$norm_plan_arr = [];

$month_names = [
  'январь',
  'февраль',
  'март',
  'апрель',
  'май',
  'июнь',
  'июль',
  'август',
  'сентябрь',
  'октябрь',
  'ноябрь',
  'декабрь'
];

foreach( $row_arr AS $key => $val )
  {
    for( $i = 1 ; $i <= $max_day ; $i ++ )
        $by_enterprise[ $val ][ $i ] = 0 ;
      $by_enterprise[ $val ][ 'total' ] = 0 ;
  }

  if( $gdep_id )
  {
     try
      {
            $query = "SELECT res.NAME chief_name
                    FROM `okb_db_otdel` otdel
                    LEFT JOIN okb_db_resurs res ON res.ID = otdel.master_res_id
                    WHERE otdel.ID = $gdep_id";

                      $stmt = $pdo->prepare( $query );
                      $stmt -> execute();
      }

      catch (PDOException $e)
      {
         die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
      }
      $chief_name = "";

      if( $stmt -> rowCount() )
      {
        $row = $stmt->fetch(PDO::FETCH_OBJ );
        $chief_name = conv( $row -> chief_name );
      }

  }

  $deps = [] ;

  try
    {
        $query = "
                    SELECT * FROM `department_month_norm_plan` 
                    WHERE 
                    year = $year
                    AND
                    month = $month
                 ";

        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

     while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        $norm_plan_arr[ $row -> dep_id ] = $row -> plan ;

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
      $dep_names[ $row -> ID ] = conv( "Подразделение : ".$row -> NAME );

    if( $gdep_id )
      $where = " AND otdel.ID = $gdep_id";

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
                    $where
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


$final_min_result = [];
$final_sgi_result = [];
$final_score_result = [];

foreach ( $deps as $key => $val ) 
{  
  $final_min_result[ $key ] = 0 ;
  $final_score_result[ $key ] = 0 ;
  foreach ( $val as $skey => $sval ) 
  {
      if( $skey == 1 || $skey == 10 || $skey == 20 || $skey == 30 ) 
        $final_min_result[ $key ] += $sval['total'];
      if( $skey == 40 ) 
        $final_sgi_result[ $key ] += $sval['total'];
      if( $skey == 50 || $skey == 60 || $skey == 70 || $skey == 80 || $skey == 90 ) 
        $final_score_result[ $key ] += $sval['total'];
  }
}

$str = "<div class='container'>";

$str .= "<div class='row'>
                   <div class='col-sm-12'>
                      <h2>".conv("Нарушения трудового распорядка за ".( $month_names[ $month - 1 ] )." $year. ").conv("Итоговая сводка.")."</h2>
                  </div>
          </div>";// <div class='row'>

    $str .= "<div class='row'>
               <div class='col-sm-10'>
                  <h4>".( $dep_names[ $gdep_id ] )."</h4>
              </div>
            </div>"; // <div class='row'>



if( $gdep_id )
{
 foreach ( $deps as $key => $val ) 
  {
    $str .= "<div class='row'>";
      
      $str .= "<table class='tbl result_table'>";
      $str .= "<col width='8%'>";
                             
      for( $i = 1 ; $i <= $max_day ; $i ++ )
         $str .= "<col width='3%'>";

    $str .= "<col width='5%'>";
    $str .= "<col width='5%'>";

    $str .= "<tr class='first'>";
    $str .= "<td class='field'></td>";

    for( $i = 1 ; $i <= $max_day ; $i ++ )
      $str .= "<td class='field AC'>$i</td>";
    
    $str .= "<td class='field AC'>".conv("Итого")."</td>";
    $str .= "<td class='field AC'>".conv("Всего")."</td>";    

    $str .= "</tr>";

    $line = 1 ;

    foreach ( $val as $skey => $sval ) 
    {
      $str .= "<tr class='".( $line % 2 ? 'even' : 'odd' )."'>";
      $str .= "<td class='field AL'>".$row_names[ $skey ]."</td>";
      $line ++ ;

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

      if( $skey == 1  )
      {
        $final = $final_min_result[ $key ] ? min_to_hour( $final_min_result[ $key ] ) : "-";
        $str .= "<td class='field AC' rowspan='3'>$final</td>";
        $viol_minutes = $final_min_result[ $key ];        
      }

      if( $skey == 40  )
      {
        $final = $final_sgi_result[ $key ] ? min_to_hour( $final_sgi_result[ $key ] ) : "-";
        $str .= "<td class='field AC'>$final</td>";
      }

      if( $skey == 50  )
      {
        $final = $final_score_result[ $key ] ? $final_score_result[ $key ] : "-";
        $str .= "<td class='field AC' rowspan='4'>$final</td>";
      }

      $str .= "</tr>";
    }


  $norm_plan = $norm_plan_arr[ $key ] ? $norm_plan_arr[ $key ] : 0 ;
  $norm_plan_minus_viol = 0;
  $score = 0;

    $norm_plan_minus_viol = $norm_plan * 60 - $viol_minutes;
    $score = $norm_plan != 0 ? number_format( $norm_plan_minus_viol * 5 / ( $norm_plan * 60 ), 2 ) : 0;

    $score = $score < 0 ? 0 : $score ;

    $norm_plan_minus_viol = $norm_plan_minus_viol ? min_to_hour( $norm_plan_minus_viol ) : 0 ;


  $str .= "<tr>
              <td class='Field AR' colspan='".( $max_day + 1 )."'><span class='prn_span'>".conv("Запланировано по участку человеко-часов :")."</span>
              </td>
              <td class='Field' colspan='2'>
              <span  class='prn_span'>$norm_plan</span></td>
          </tr>";

  $str .= "<tr>
              <td class='Field AR' colspan='".( $max_day + 1 )."'><span class='prn_span'>".conv("Отработано по заказу человеко-часов с учетом простоев :")."</span>
              </td>
              <td class='Field' colspan='2'>
              <span class='prn_span'>$norm_plan_minus_viol</span>
              </td>
          </tr>";

  $str .= "<tr>
              <td class='Field AR' colspan='".( $max_day + 1 )."'><span class='prn_span'>".conv("Оценка дисциплины :")."</span></td>
              <td class='Field' colspan='2'>
              <span class='prn_span'>$score</span></td>
          </tr>";

    $str .= "</table>";
    $str .= "</div>";
  }
}
else
{
// Итого
    
    $str .= "<table class='tbl result_table'>";
    $str .= "<col width='8%'>";
                           
    for( $i = 1 ; $i <= $max_day ; $i ++ )
       $str .= "<col width='2%'>";

  $str .= "<col width='5%'>";
  $str .= "<col width='5%'>";

  $str .= "<tr class='first'>";
  $str .= "<td class='field'></td>";

  for( $i = 1 ; $i <= $max_day ; $i ++ )
    $str .= "<td class='field AC'>$i</td>";
  
  $str .= "<td class='field AC'>".conv("Итого")."</td>";
  $str .= "<td class='field AC'>".conv("Всего")."</td>";

  $str .= "</tr>";

  $final_min_result = 0;
  $final_sgi_result = 0;  
  $final_score_result = 0;

  foreach ( $by_enterprise as $skey => $sval ) 
  {  
        if( $skey == 1 || $skey == 10 || $skey == 20 || $skey == 30 ) 
          $final_min_result += $sval['total'];
        if( $skey == 40 ) 
          $final_sgi_result += $sval['total'];
        if( $skey == 50 || $skey == 60 || $skey == 70 || $skey == 80 || $skey == 90 ) 
          $final_score_result += $sval['total'];
  }

  $line = 1 ;

  foreach ( $by_enterprise as $skey => $sval ) 
  {
    $str .= "<tr class='".( $line % 2 ? 'even' : 'odd' )."'>";
    $line ++ ;

    $str .= "<td class='field AL'>".$row_names[ $skey ]."</td>";

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

    if( $skey == 1  )
    {
      $final = $final_min_result ? min_to_hour( $final_min_result ) : "-";
      $str .= "<td class='field AC' rowspan='3'>$final</td>";
    }

    if( $skey == 40  )
    {
      $final = $final_sgi_result ? min_to_hour( $final_sgi_result ) : "-";
      $str .= "<td class='field AC'>$final</td>";
    }

    if( $skey == 50  )
    {
      $final = $final_score_result ? $final_score_result : "-";
      $str .= "<td class='field AC' rowspan='4'>$final</td>";
    }

    $str .= "</tr>";
  }

  $str .= "</table>";
  $str .= "</div>";
}

$str .= "<br>";

if( $gdep_id )
{
  $str .= "<div class='row'>
                   <div class='col-sm-10'>
                      <span class='chief_name'>".conv("Согласовано")."</span>
                  </div>
          </div>";

  $str .= "<div class='row'>
                   <div class='col-sm-3'>
                      <span class='chief_name'>".conv("Мастер")."</span>
                  </div>
                   <div class='col-sm-3 fio'>
                      <span class='chief_name'>$chief_name</span>
                  </div>
                   <div class='col-sm-1 fio'>
                      <span class='chief_name'></span>
                  </div>
                   <div class='col-sm-1 fio'>
                      <span class='chief_name'></span>
                  </div>
          </div>";

$str .= "<div class='row'>
                 <div class='col-sm-3'>
                 </div>
                 <div class='col-sm-3 center up'>
                    <span class='sign'>".conv("ФИО")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Подпись")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Дата")."</span>
                </div>
        </div>";
}


$str .= "<div class='row'>
                 <div class='col-sm-10'>
                    <span class='chief_name'>".conv("Ознакомлены")."</span>
                </div>
        </div>";

$str .= "<div class='row'>
                 <div class='col-sm-3'>
                    <span class='chief_name'>".conv("Начальник производства")."</span>
                </div>
                 <div class='col-sm-3 fio'>
                    <span class='chief_name'>".conv("Филоненко С.А.")."</span>
                </div>
                 <div class='col-sm-1 fio'>
                    <span class='chief_name'></span>
                </div>
                 <div class='col-sm-1 fio'>
                    <span class='chief_name'></span>
                </div>
        </div>";

$str .= "<div class='row'>
                 <div class='col-sm-3'>
                 </div>
                 <div class='col-sm-3 center up'>
                    <span class='sign'>".conv("ФИО")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Подпись")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Дата")."</span>
                </div>
        </div>";

$str .= "<div class='row'>
                 <div class='col-sm-3'>
                    <span class='chief_name'>".conv("Начальник ПДО")."</span>
                </div>
                 <div class='col-sm-3 fio'>
                    <span class='chief_name'>".conv("Матикова Т.Д.")."</span>
                </div>
                 <div class='col-sm-1 fio'>
                    <span class='chief_name'></span>
                </div>
                 <div class='col-sm-1 fio'>
                    <span class='chief_name'></span>
                </div>
        </div>";

$str .= "<div class='row'>
                 <div class='col-sm-3'>
                 </div>
                 <div class='col-sm-3 center up'>
                    <span class='sign'>".conv("ФИО")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Подпись")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Дата")."</span>
                </div>
        </div>";


$str .= "</div>";

echo $str;


