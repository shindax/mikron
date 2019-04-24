<script type="text/javascript" src="/project/coordination_page_penalty/js/constants.js"></script>
<script type="text/javascript" src="/project/coordination_page_penalty/js/coordination_page_penalty.js?arg=0"></script>
<script type="text/javascript" src="/project/coordination_page_penalty/js/jquery-ui.min.js"></script>

<link rel='stylesheet' href='/project/coordination_page_penalty/css/style.css'>
<link rel='stylesheet' href='/project/coordination_page_penalty/css/bootstrap.min.css'>

<?php
require_once( "classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.CoordinationPagePenaltyTable.php" );
error_reporting( E_ALL );
error_reporting( E_ERROR );

$user_id = $user["ID"];
echo "<script>var user_id = $user_id</script>";
echo "<script>var debug = 0</script>";

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$str = "<h2>".conv("Нарушения в листах согласования")."</h2>";
$str .= "<div class='container'>";
$str .= "<hr>";

$str .= "<div>
                        <span class='label'>".conv( "Дата с")." : </span>
                        <input type='text' id='datepicker_from'>
						<span class='label'> ".conv( "по")." : </span>
						<input type='text' id='datepicker_to'>
         </div><hr>";
              
$str .= "<div class='row'>
                <div class='col-sm-1 offset-sm-11'>
                    <button class='btn btn-big btn-primary float-right hidden' id='print_button'>".conv("Распечатать")."</button>
                </div>
            </div>
        ";

$str .= "<img id='loadImg' src='project/img/loading_2.gif' />";
$str .= "<div class='row'>
                <div id='table_div' class='col-sm-12'></div>";

$str .= "</div>";
$str .= "</div>";
       
echo $str ;