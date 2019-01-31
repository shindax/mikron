<script type="text/javascript" src="/project/labor_regulations_violation/js/constants.js"></script>
<script type="text/javascript" src="/project/labor_regulations_violation/js/jquery.monthpicker.js"></script>
<script type="text/javascript" src="/project/labor_regulations_violation/js/date.js"></script>
<script type="text/javascript" src="/project/labor_regulations_violation/js/labor_regulations_violation_by_month.js"></script>

<script type="text/javascript" src="/project/labor_regulations_violation/js/jquery-ui.min.js"></script>

<link rel='stylesheet' href='/project/labor_regulations_violation/css/bootstrap.min.css'>
<link rel='stylesheet' href='/project/labor_regulations_violation/css/style.css'>

<?php

error_reporting( E_ALL );

require_once( "classes/db.php" );
require_once( "classes/class.LaborRegulationsViolationItemByMonth.php" );

global $user, $pdo ;
$user_id = $user["ID"];

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

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
                    <div><input type='radio' name='type' value='3'><span>".conv("Итог")."</span></div>
                </div>
			 </div>";

$str .= 	"<div class='table_div col-sm-12'></div>";
$str .= "<div id='loadImg' class='hidden-xs-up'><img src='project/img/loading_2.gif' width='200px'></div>";


echo $str ;

function GetRoundedUp2HalfHour( $val )
{
    $full = intval( $val / 30 );
    $fract = $val - $full * 30;
    $fract = $fract >= 15 ? 0.5 : 0 ;
    $result = $full * 0.5 + $fract;
    return $result;
}

// $cp = new LaborRegulationsViolationItemByMonth( $pdo, 732, 1, 2019 );
// _debug( $cp -> GetViolationsByShift() );
