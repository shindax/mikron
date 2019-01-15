<script type="text/javascript" src="/project/master_evaluation/js/constants.js"></script>
<script type="text/javascript" src="/project/master_evaluation/js/jquery.monthpicker.js"></script>
<script type="text/javascript" src="/project/master_evaluation/js/date.js"></script>
<script type="text/javascript" src="/project/master_evaluation/js/master_evaluation.js"></script>

<script type="text/javascript" src="/project/master_evaluation/js/jquery-ui.min.js"></script>

<link rel='stylesheet' href='/project/master_evaluation/css/bootstrap.min.css'>
<link rel='stylesheet' href='/project/master_evaluation/css/style.css'>

<?php

//error_reporting( E_ALL );
error_reporting( 0 );

require_once( "classes/db.php" );
require_once( "classes/class.LaborRegulationsViolationItemByMonth.php" );

global $user, $pdo ;
$user_id = $user["ID"];

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

function GetResInfo( $user_id )
{
    global $user, $pdo;

    try
    {
       $query ="SELECT ID, NAME FROM `okb_db_resurs` WHERE ID_users = $user_id";
       $stmt = $pdo -> prepare( $query );
       $stmt->execute();
    }

    catch (PDOException $e)
    {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
    }

    $res_id = 0 ;
    
    if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
      $res_id = $row -> ID;

    return $res_id ;
 }

$user_id = $user['ID'];
$res_id = GetResInfo( $user_id );

$str  = "<script>var user_id = $user_id</script>";
$str  .= "<script>var res_id = $res_id</script>";

$str  .= "<div class='container'>";
$str .= 	"<div class='row'>
				 <div class='col-sm-12'>
					<h2>".conv("Оценка мастеров за ")."<input id='monthpicker' /></h2>
				</div>
			 </div>";

$str .= 	"<div class='table_div col-sm-12'></div>";
$str .="<img ='*'' class='hidden' id='loadImg' src='project/img/loading_2.gif' />";

echo $str ;

