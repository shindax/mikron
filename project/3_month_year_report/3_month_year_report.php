<script type="text/javascript" src="/project/3_month_year_report/js/constants.js"></script>
<script type="text/javascript" src="/project/3_month_year_report/js/jquery.monthpicker.js"></script>
<script type="text/javascript" src="/project/3_month_year_report/js/date.js"></script>
<script type="text/javascript" src="/project/3_month_year_report/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/project/3_month_year_report/js/3_month_year_report.js"></script>

<link rel='stylesheet' href='/project/3_month_year_report/css/bootstrap.min.css'>
<link rel='stylesheet' href='/project/3_month_year_report/css/style.css'>

<?php

error_reporting( E_ALL );
require_once( "classes/db.php" );

global $user, $pdo ;
$user_id = $user["ID"];

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$str  = "<div class='container'>";
$str .= 	"<div class='row'>
				 <div class='col-sm-7'>
					<h2>".conv("Отчет по сменам за 3 месяца включая ")."<input id='monthpicker' /></h2>
				</div>
                <div class='col-sm-5 radio_div'>
                    <div class='selected'><input type='radio' name='type' value='1' checked><span>".conv("Рабочие специальности")."</span></div>
                    <div><input type='radio' name='type' value='0'><span>".conv("ИТР")."</span></div>
                </div>
			 </div>";

$str .= 	"<div class='table_div col-sm-12'></div>";

echo $str ;


