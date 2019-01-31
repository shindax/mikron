<?php
	$locate = "/project/reports/logistic_base";
?>

<link rel='stylesheet' href='<?= $locate ?>/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='<?= $locate ?>/css/jquery-ui.css' type='text/css'>
<link rel='stylesheet' href='<?= $locate ?>/css/style.css' type='text/css'>

<script type="text/javascript" src="<?= $locate ?>/js/adjust_calendar.js"></script>
<script type="text/javascript" src="<?= $locate ?>/js/logistic_base.js"></script>

<?php
require_once( "classes/db.php" );
require_once( "classes/common_functions.php" );
require_once( "functions.php" );

$req_id = isset( $_GET['p0'] ) ? $_GET['p0'] : 0 ;
$user_id = $user['ID'];
echo "<script>var user_id = $user_id</script>";

$str = "<div class='container'>";

$str .= getPageCaption( $user_id );
$str .= getTable( $user_id );

$str .= "</div>";

$str .= "<div id='delete-town-dialog-confirm' title='".conv("Удаление города")."'>
  <p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>".conv("Город будет удален. Вы уверены?")."</p>
</div>";

echo $str;

