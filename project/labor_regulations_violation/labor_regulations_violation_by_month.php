<script type="text/javascript" src="/project/labor_regulations_violation/js/constants.js"></script>
<script type="text/javascript" src="/project/labor_regulations_violation/js/jquery.monthpicker.js"></script>
<script type="text/javascript" src="/project/labor_regulations_violation/js/date.js"></script>
<script type="text/javascript" src="/project/labor_regulations_violation/js/labor_regulations_violation_by_month.js"></script>

<script type="text/javascript" src="/project/labor_regulations_violation/js/jquery-ui.min.js"></script>

<script type="text/javascript" src="/project/master_plan_scoring/js/master_plan_scoring.js"></script>

<link rel='stylesheet' href='/project/labor_regulations_violation/css/bootstrap.min.css'>
<link rel='stylesheet' href='/project/labor_regulations_violation/css/style.css'>
<link rel='stylesheet' href='/project/master_plan_scoring/css/style.css'>


<?php

error_reporting( E_ALL );

require_once( "classes/db.php" );
require_once( "classes/class.LaborRegulationsViolationItemByMonth.php" );

global $user, $pdo ;
$user_id = $user["ID"];

if( $user_id == 13 )
    echo "<script>var can_edit_norm_plan = 1</script>";
     else
        echo "<script>var can_edit_norm_plan = 0</script>";

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

$user_id = $user["ID"];
$res_id = GetResInfo( $user_id );

echo "<script>var res_id = $res_id</script>";

$str  = "<div class='container'>";
$str .= 	"<div class='row'>
				 <div class='col-sm-5'>
					<h2>".conv("Нарушения трудового распорядка за ")."<input id='monthpicker' /></h2>
				</div>
                <div class='col-sm-7 radio_div'>
                    <div class='selected'><input type='radio' name='type' value='0' checked><span>".conv("Все")."</span></div>
                    <div><input type='radio' name='type' value='1'><span>".conv("С нарушениями")."</span></div>
                    <div><input type='radio' name='type' value='4'><span>".conv("С нарушениями сокр.")."</span></div>                    
                    <div><input type='radio' name='type' value='2'><span>".conv("Без нарушений")."</span></div>
                    <div><input type='radio' name='type' value='5'><span>".conv("Итог по мастерам")."</span></div>                    
                    <div><input type='radio' name='type' value='3'><span>".conv("Итог")."</span></div>
                </div>
			 </div>";

$str .= 	"<div class='table_div col-sm-12'></div>";
$str .= "<div id='loadImg' class='hidden-xs-up'><img src='project/img/loading_2.gif' width='200px'></div>";

echo $str ;

