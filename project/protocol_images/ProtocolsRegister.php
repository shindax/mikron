<script type="text/javascript" src="/project/protocol_images/js/protocol_images.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>

<link rel='stylesheet' href='/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/protocol_images/css/style.css' type='text/css'>
<link rel='stylesheet' href='/project/protocol_images/css/jquery-ui.css' type='text/css'>


<?php
ini_set('display_errors', true);
error_reporting( E_ALL );
//error_reporting( 0 );

require_once( "functions.php" );
require_once( "dialogs.php" );
require_once( "class.monthPlanReportDocumentation.php" );

$date = date("Y-m-d");
$month = + date("m");
$year = date("Y");
$can_edit = 0 ;
$can_confirm = 0;

if( $month <= 9 )
    $month = "0".$month;

if( $user["ID"] == 128 || $user["ID"] == 1 )
    $can_edit = 1;

if( $user["ID"] == 3 || $user["ID"] == 1 )
    $can_confirm = 1;

echo "<script>
        var user_id = ".$user["ID"].";
        var month='$month';
        var year=$year;
        var can_edit = $can_edit;
        var can_confirm = $can_confirm;
        var email_secretary = '".$recipientSecretary."';
        var email_boss = '".$recipientBoss."';
</script>";


$doc = new MonthPlanReportDocumentation( $dblocation, $dbname, $dbuser, $dbpasswd, $can_edit );

echo $date_div;
echo $doc -> GetData( $date );

echo $delete_image_dialog . $view_image_dialog . $load_file_element . $load_file_waiting  ;
