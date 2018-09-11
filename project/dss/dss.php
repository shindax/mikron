<script type="text/javascript" src="/project/dss/js/dss.js"></script>
<script type="text/javascript" src="/project/dss/js/jquery-ui.min.js"></script>

<link rel='stylesheet' href='/project/dss/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/dss/css/style.css' type='text/css'>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DesigionSupportSystemItem.php" );
error_reporting( E_ALL );
// error_reporting( E_ERROR );

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

global $user ;
$user_id = $user['ID'];
$str = "";

$head_title = "<div class='head'>
                            <div><h2>".conv( "Система принятия решений")."</h2></div>
                      </div>";

$str .= $head_title ;

$dss_item = new DesigionSupportSystemItem( $pdo, 1 );
//debug( $dss_item -> GetData(), 1 );

$str  = "<div class='container'>";
$str .=   "<div class='row'>";

$str .= "<div class='col-sm-12'>";
$str .= "<table class='tbl dss_table'>";

$str .= "<col width='30%'>";
$str .= "<col width='30%'>";
$str .= "<col width='10%'>";
$str .= "<col width='10%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";

$str .= "<tr class='first'>";
$str .= "<td class='Field'>".conv("Проект")."</td>";
$str .= "<td class='Field'>".conv("Описание")."</td>";
$str .= "<td class='Field'>".conv("Автор")."</td>";
$str .= "<td class='Field'>".conv("Дата создания")."</td>";
$str .= "<td class='AC Field'><div><img class='head_icon' src='/uses/svg/settings-4.svg' /></div></td>";
$str .= "<td class='AC Field'><div><img class='head_icon' src='/uses/svg/speech-bubble-right-4.svg' /></div></td>";
$str .= "<td class='AC Field'><div><img class='head_icon' src='/uses/svg/users.svg' /></div></td>";
$str .= "<td class='AC Field'><div><img class='head_icon' src='/uses/svg/camera.svg' /></div></td>";
$str .= "</tr>";
$str .= conv( $dss_item -> GetTableRow('','Field') );
$str .= "</table>";
$str .= "</div><!--div class='col-sm-12'-->";
$str .= "</div><!--div class='row'-->";
$str .= "</div><!--div class='container'-->";

echo $str ;
