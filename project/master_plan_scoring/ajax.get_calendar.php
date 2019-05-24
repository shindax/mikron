<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.LaborRegulationsViolationItemByMonth.php" );
 // error_reporting( E_ALL );
// error_reporting( E_ERROR );
error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

$month = $_POST['month'];
$year = $_POST['year'];
$user_id = $_POST['user_id'];
$res_id = $_POST['res_id'];

$disabled = 'disabled';

if( $user_id == 13 )
    $disabled = '';

function conv( $str )
{
    global $dbpasswd;
    if( !strlen( $dbpasswd ))
        return $str ; 
        else
            return iconv( "UTF-8", "Windows-1251",  $str );
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

function remove_excess( &$data, $arr )
{
    foreach( $arr AS $value )
        unset( $data[ $value ] );
}

function get_plan( $year, $month, $max_day, $res_id )
{
    global $pdo;
        $res = array_fill( 1, $max_day, 0.0 );

    try
    {
        $query = "
                  SELECT plan, score
                  FROM master_plan_evaluation
                  WHERE 
                  year = $year
                  AND
                  month = $month 
                  AND
                  master_res_id = $res_id
                 ";
        $stmt = $pdo->prepare( $query );
        $stmt -> execute();

    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    {
        $score = $row -> score;
        $res = json_decode( $row -> plan, true );
    }

    return [ $res, $score ];
}

const MASTER_ID = [ 214 , 13 ] ; // Трифонова, Рыбкина
    
$editable =  "";
$score = 0;
$hidden = "hidden";

if( in_array( $user_id, MASTER_ID ) )
{
    $editable =  "editable";
    $hidden = "";
}

try
{
    $query = "
              SELECT int_id, name, type
              FROM labor_regulations_violation_rows
              WHERE 
              int_id IN (1,10,20,30,40)
             ";
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();

}

catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
}

$rows = [];

while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    $rows[ $row -> int_id ] = [ 'name' => conv( $row -> name ), 'type' => conv( $row -> type ) ];

try
{
    $query = "
              SELECT otdel.id dep_id, otdel.master_res_id master_res_id, res.NAME master_name, shtat.ID_resurs res_id, otdel.NAME dep_name
              FROM okb_db_otdel otdel
              LEFT JOIN okb_db_resurs res ON res.ID = otdel.master_res_id
              LEFT JOIN okb_db_shtat shtat ON shtat.ID_otdel = otdel.id
              WHERE 
              master_res_id <> 0
              AND
              shtat.ID_resurs <> 0
              AND
              shtat.ID_resurs <> 500
              ORDER BY master_name
             ";

    $stmt = $pdo->prepare( $query );
    $stmt -> execute();

}

catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
}

$masters = [];
$deps = [];

while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
{
    $masters[ $row -> master_res_id ][ 'name' ] = conv( $row -> master_name );
    $masters[ $row -> master_res_id ][ $row -> dep_id ][] = $row -> res_id ;
    $deps[ $row -> dep_id ] = conv( $row -> dep_name ); 
}

$max_day =  LaborRegulationsViolationItemByMonth :: GetMaxDay( $month, $year );

$str  = "";

foreach ( $masters AS $id => $mvalue ) 
{
    $violatons = [];
    $res = get_plan( $year, $month, $max_day, $id );
    
    $plan = $res[0];
    $score = $res[1];

    $str .= "<hr>";

    $loc_dep = [];
    $name = $mvalue['name'];

    $str .= "<h3>".conv( "Мастер : ")."$name</h3>";
    $data = [] ;

        foreach ( $mvalue AS $depkey => $depvalue )
        {
            if( $depkey != 'name')
                $loc_dep[] = $deps[ $depkey ];

            if( is_array( $depvalue ) ) 
            foreach ( $depvalue AS $key => $value ) 
            {
                $cp = new LaborRegulationsViolationItemByMonth( $pdo, $value, $month, $year );
                $loc_data = $cp -> GetData() ;

            if( is_array( $loc_data ) ) 
                foreach( $loc_data AS $lkey => $lvalue )
                    for( $i = 1 ; $i <= $max_day ; $i ++ )
                    {   
                        $data[ $lkey ][ 'total' ] += $loc_data[ $lkey ][ $i ] ;
                        $data[ $lkey ][ $i ] += $loc_data[ $lkey ][ $i ];
                    }
            }
        }

        $str .= "<h5>".conv( "Подразделения : ").join(", ", $loc_dep )."</h5>";

    try
    {
        $query =    "SELECT id, description
                    FROM master_evaluation_type
                    WHERE 1
                 ";

        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    $evaluation_type = [];

    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        $evaluation_type[ $row -> id ] = conv( $row -> description );

    try
    {
        $query =    "SELECT DAY( date ) day, score, eval_type
                    FROM master_evaluation
                    WHERE
                    master_res_id = $id
                    AND 
                    date >= '$year-$month-01'
                    AND
                    date <= '$year-$month-31'
                 ";

        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    $evaluations = [];

    $evaluations_final_total = 0 ;
    $evaluations_final_count = 0 ;    
    $evaluations_final = [];    
    for( $i = 1 ; $i <= $max_day ; $i ++ )
        $evaluations_final[ $i ] = 0 ;

    $evaluations_total = [];
    $evaluations_count = [];    

    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    {
        $evaluations[ $row -> eval_type ][ $row -> day ] = $row -> score ;
        $evaluations_total[ $row -> eval_type ] += $row -> score ;
        $evaluations_count[ $row -> eval_type ] ++;
    }

    $str .= LaborRegulationsViolationItemByMonth :: GetTemplateTableHead( $id, conv("ФИО мастера"), $max_day );

    $rowspan = 10; // count( $rows ) ;

    $total_avg = 0 ;
    $total_sgi_avg = 0 ;    
    $score_avg = 0 ;    

    foreach( $data AS $key => $value )
    {
        switch( $key )
        {
            case 1 :
            case 10 :
            case 20 :
            case 30 : $total_avg += $value['total']; break ;
            case 40 : $total_sgi_avg += $value['total']; break ;
            default : $score_avg += $value['total'];
        }
    }

    $total_avg = $total_avg ? min_to_hour( $total_avg ) : '-' ;
    $total_sgi_avg = $total_sgi_avg ? min_to_hour( $total_sgi_avg ) : '-' ;    
    $score_avg = $score_avg ? $score_avg : '-' ;    

    remove_excess( $data, [50,60,70,80,90] );

    foreach( $data AS $key => $value )
    {
        if( $key == 1 ||$key == 10 ||$key == 20 ||$key == 30 ||$key == 40 )
            $total = $value['total'] ? min_to_hour( $value['total'] ): '-';
            else
                $total = $value['total'] ? $value['total'] : '-';

        $str .= "<tr data-id='$key'>";
        
        if( $rowspan )
            $str .= "<td class='Field AC' rowspan='$rowspan'>$name</td>";

        $str .= "<td class='Field AC'>".$rows[$key]['name']."</td>";
        $str .= "<td class='Field AC'>".$rows[$key]['type']."</td>";        

        for( $i = 1 ; $i <= $max_day ; $i ++ )
        {
            if( $key == 1 ||$key == 10 ||$key == 20 ||$key == 30 ||$key == 40 )
            {
                $val = $value[ $i ] ? min_to_hour( $value[ $i ] ) : "-";
                $violatons[ $i ] += $value[ $i ];
            }
                    else
                        $val = $value[ $i ] ? $value[ $i ] : "-";
            

            $str .= "<td class='Field AC'><span data-viol='{$value[ $i ]}'>$val</span></td>";
        }

        $str .= "<td class='Field AC'>$total</td>";

        if( $key == 1 ) 
            $str .= "<td class='Field AC' rowspan='4'>$total_avg</td>";

        if( $key == 40 ) 
            $str .= "<td class='Field AC'>$total_sgi_avg</td>";

        $str .= "</tr>";
        $rowspan = 0 ;
    }

///////////////////////////////////////////////////////////////////////////////
        
        $str .= "<tr><td class='Field AR' colspan='2'>".conv( "Запланировано по участку человеко-часов : " ) ."</td>";

        $total_plan = 0 ;
        for( $i = 1 ; $i <= $max_day ; $i ++ )
        {
            $total_plan += $plan[ $i ] ;
            $str .= "<td class='Field'>
                     <input $disabled type='number' class='day_plan_input' data-day='$i' data-viol='{$violatons[$i]}' value='{$plan[ $i ]}'/>
                     </td>";
        }

        $str .= "<td class='Field AC' colspan='2'><span class='plan_mid'>".number_format( $total_plan/$max_day, 1 )."</span></td>";

        $str .= "</tr>";

///////////////////////////////////////////////////////////////////////////////
        $str .= "<tr><td class='Field AR' colspan='2'>".conv( "Отработано по заказу человеко-часов с учетом простоев :" ) ."</td>";
        
        $total_norm_minus_viol = 0 ;

        for( $i = 1 ; $i <= $max_day ; $i ++ )
        {
            $norm_minus_viol = $plan[ $i ] * 60 - $violatons[$i];
            $total_norm_minus_viol += $norm_minus_viol;
            $norm_minus_viol_str = min_to_hour( $norm_minus_viol );
            $str .= "<td class='Field'>
                        <span  class='norm_minus_viol_span' data-viol='$norm_minus_viol' data-day='$i'>$norm_minus_viol_str
                        </span>
                    </td>";
        }

        $str .= "<td class='Field AC' colspan='2'><span class='mid_norm_minus_viol_span'>".min_to_hour( number_format( $total_norm_minus_viol / $max_day, 0 ))."</span></td>";

        $str .= "</tr>";

///////////////////////////////////////////////////////////////////////////////
        $str .= "<tr><td class='Field AR' colspan='2'>".conv( "Оценка дисциплины :" ) ."</td>";

        $total_score = 0 ;
        
        for( $i = 1 ; $i <= $max_day ; $i ++ )
        {

            // $score = $plan[ $i ] ? ( $plan[ $i ] * 60 - $violatons[$i] ) * 5 / ( $plan[ $i ] * 60 ) : 0 ;
            
            $total_score += $score;
            $score = number_format( $score, 1 );
            $str .= "<td class='Field AC'>
                        <span class='score_span' data-day='$i'>$score</span>
                    </td>";
        }

        $str .= "<td class='Field AC' colspan='2'><span class='mid_score_span'>".number_format( $total_score/$max_day, 2 )."</span></td>";

        $str .= "</tr>";


    $str .= LaborRegulationsViolationItemByMonth :: GetTemplateTableEnd();

}

echo $str ;
