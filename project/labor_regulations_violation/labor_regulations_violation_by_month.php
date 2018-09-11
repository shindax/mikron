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

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

$str  = "<div class='container'>";
$str .= 	"<div class='row'>
				 <div class='col-sm-7'>
					<h2>".conv("Нарушения трудового распорядка за ")."<input id='monthpicker' /></h2>
				</div>
                <div class='col-sm-5 radio_div'>
                    <div class='selected'><input type='radio' name='type' value='0' checked><span>".conv("Все")."</span></div>
                    <div><input type='radio' name='type' value='1'><span>".conv("С нарушениями")."</span></div>
                    <div><input type='radio' name='type' value='2'><span>".conv("Без нарушений")."</span></div>
                </div>
			 </div>";

//$cp = new LaborRegulationsViolationItemByMonth( $pdo, 277, 8, 2018 );
//debug( $cp -> HasViolations() );
//$str .= $cp -> GetTable();

$str .= 	"<div class='table_div col-sm-12'></div>";


echo $str ;

       //debug( $cp -> GetData() );

