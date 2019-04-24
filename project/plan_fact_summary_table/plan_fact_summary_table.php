<script type="text/javascript" src="/project/plan_fact_summary_table/js/drop_down_select.js"></script>
<script type="text/javascript" src="/project/plan_fact_summary_table/js/adjust_calendars.js"></script>
<script type="text/javascript" src="/project/plan_fact_summary_table/js/plan_fact_summary_table.js"></script>

<link rel='stylesheet' href='/project/plan_fact_summary_table/css/style.css' type='text/css'>
<link rel='stylesheet' href='/project/plan_fact_summary_table/css/drop_down.css' type='text/css'>
<link rel='stylesheet' href='/project/plan_fact_summary_table/css/jquery-ui.css' type='text/css'>

<?php

// error_reporting( E_ALL );
error_reporting( 0 );
// ini_set('display_errors', true);
ini_set('display_errors', false );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.PlanFactSummaryTable.php" );

require_once( "functions.php" );
require_once( "dialogs.php" );

$date = date("Y-m-d");
$month = + date("m");
$year = date("Y");

if( $month <= 9 )
    $month = "0".$month;

// Получить ставку штрафа за просрочку
$rate = getPenaltyRate( 1 );

$out_str ="<img ='*'' id='loadImg' src='project/img/loading_2.gif' />";

$out_str .= "<script>var month='$month'; var year=$year; var rate = $rate ;</script>";

$out_str .= $main_div_beg ;
$out_str .= $form_div ;
$out_str .= $table_div_beg;

$table = new PlanFactSummaryTable( $pdo, 1, $rate );
$out_str .= $table -> GetTable() ;
$table = new PlanFactSummaryTable( $pdo, 2, $rate );
$out_str .= $table -> GetTable() ;
$table = new PlanFactSummaryTable( $pdo, 3, $rate );
$out_str .= $table -> GetTable() ;
$table = new PlanFactSummaryTable( $pdo, 4, $rate );
$out_str .= $table -> GetTable() ;
$table = new PlanFactSummaryTable( $pdo, 5, $rate );
$out_str .= $table -> GetTable() ;


$out_str .= $table_div_end;
$out_str .= $main_div_end ;

echo $out_str ;
