<?php
	$locate = "/project/reports/tack_carries";
?>

<link rel='stylesheet' href='<?= $locate ?>/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='<?= $locate ?>/css/jquery-ui.css' type='text/css'>
<link rel='stylesheet' href='<?= $locate ?>/css/style.css' type='text/css'>

<script type="text/javascript" src="<?= $locate ?>/js/tack_carries.js"></script>
<?php
require_once( "classes/db.php" );
require_once( "classes/common_functions.php" );
require_once( "functions.php" );

$req_id = isset( $_GET['p0'] ) ? $_GET['p0'] : 0 ;
$use_zak_table = isset( $_GET['p1'] ) ? $_GET['p1'] : 0 ;

$user_id = $user['ID'];
echo "<script>var user_id = $user_id</script>";

$str = "<div class='container'>";

$str .= getPageCaption( $req_id, $user_id );
$str .= getTable( $req_id, $user_id, $use_zak_table );

$str .= "</div>";

echo $str;
