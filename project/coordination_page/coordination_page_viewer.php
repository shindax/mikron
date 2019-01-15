<script type="text/javascript" src="/project/coordination_page/js/coordination_page_viewer.js"></script>

<script type="text/javascript" src="/project/coordination_page/js/bootstrap.min.js"></script>

<link rel='stylesheet' href='/project/coordination_page/css/bootstrap.min.css'>
<link rel='stylesheet' href='/project/coordination_page/css/style.css'>
<link rel='stylesheet' href='/project/coordination_page/css/jquery-ui.css'>

<?php
require_once( "classes/db.php" );

global $user;
$can_delete_arr = [ 1,145,39 ];
$user_id = $user['ID'];

echo "<script>var can_delete_arr = [".join( ",", $can_delete_arr )."]</script>";
echo "<script>var user_id = $user_id</script>";

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$str = "<h2>".conv("Листы согласования" )."</h2>";
$str .= "<div class='container'>";
$str .= "<hr>";

$str .= "<div class='row'>
                <div class='col-sm-1'>
                    <button class='btn btn-big btn-primary head_button' data-id='in_work'>".conv("В работе")."</button>
                </div>
                <div class='col-sm-1'>
                    <button class='btn btn-big btn-secondary head_button' data-id='completed'>".conv("Завершенные")."</button>
                </div>
            </div><hr>
        ";

$str .= "<div class='row'>
                <div class='col-sm-12' id='table_div'>";
$str .= "</div></div>";

$str .= "</div>";


$str .= "<div id='delete_page_dialog' title='".conv("Удалить лист?")."' class='hidden'>
  <p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>".conv("Лист согласоввания будет удален. Вы уверены?")."</p>
</div>";

echo $str ;

