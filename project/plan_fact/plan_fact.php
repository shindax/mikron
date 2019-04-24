<script type="text/javascript" src="/project/plan_fact/js/scrollify.js"></script>
<script type="text/javascript" src="/project/plan_fact/js/drop_down_select.js"></script>
<script type="text/javascript" src="/project/plan_fact/js/adjust_calendars.js"></script>
<script type="text/javascript" src="/project/plan_fact/js/plan_fact.js?v=2"></script>
<script type="text/javascript" src="/project/plan_fact/js/sorting.js"></script>

<link rel='stylesheet' href='/project/plan_fact/css/style.css?v=2' type='text/css'>
<link rel='stylesheet' href='/project/plan_fact/css/drop_down.css' type='text/css'>
<link rel='stylesheet' href='/project/plan_fact/css/jquery-ui.css' type='text/css'>
<link rel='stylesheet' href='/project/plan_fact/css/scrollify.css' type='text/css'>

<?php

error_reporting( E_ALL );
error_reporting( 0 );
ini_set('display_errors', true);

//error_reporting(0);
//ini_set('display_errors', false);

require_once( "dialogs.php" );
require_once( "functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.PlanFactCollector.php" );

$date = date("Y-m-d");
$month = + date("m");
$year = date("Y");
$can_edit = 0 ;

if( $month <= 9 )
    $month = "0".$month;

$user_id = $user["ID"];

if(  $user_id == 128 || $user_id == 1 )
    $can_edit = 1;

echo "<script>var month='$month'; var year=$year; var can_edit = $can_edit; var user_id = $user_id; </script>";

echo $main_div_beg ;
echo $waiting_img;
echo $form_div ;

echo $change_status_dialog;
echo $change_list_dialog;
echo $change_date_dialog;

echo $table_div_beg;

$orders = new PlanFactCollector( $user_id, $dblocation, $dbname, $dbuser, $dbpasswd );

$query = 0 ;
$zak_list = 0 ;

if( isset( $_GET[ 'list' ]) )
  {
      $zak_list = $_GET[ 'list' ] ;
      
      if( $zak_list == "undefined" )
      {
          $query = $orders -> getQuery();
          $query .= "  WHERE okb_db_zak.ID   IN  ( 0 ) ";
      }
        else
        {
          $zak_arr = explode(",", $zak_list );
          $zak_arr = array_diff( $zak_arr, array(''));

          if( $zak_list[ strlen( $zak_list ) - 1 ] == ',')
              $zak_list[ strlen( $zak_list ) - 1 ] = '';

          $query = $orders -> getQuery();
          $query .= "  WHERE okb_db_zak.ID   IN  ( ". ( join(",", $zak_arr ) )." ) ";
      }
  }


//$orders -> recalcStages();

$orders -> collectRawData( $query );
echo $orders -> getTable();

echo $table_div_end;
echo $main_div_end ;

echo "<script>var input_id = 0, rec_id = 0; </script>";

if( isset( $_GET[ 'id' ]) && isset( $_GET[ 'rec_id' ]) ) :
  $id = $_GET[ 'id' ];
  $rec_id = $_GET[ 'rec_id' ];
?>

<script>
    input_id = <?= $id ?> ;
    rec_id = <?= $rec_id ?> ;
</script>

<?php
endif;
