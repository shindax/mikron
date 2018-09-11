<link rel="stylesheet" href="/project/MyJobs/css/jquery-ui.css">
<script type="text/javascript" src="/project/MyJobs/js/orderExecutorsEdit.js"></script>

<?php
require_once("CommonFunctions.php");
global $user_id, $db_prefix;

error_reporting( E_ALL );
error_reporting( 0 );
$employeement_list = "<option value=''></option>".CreateUIEmployeementList();

$order_id = $render_row['ID'];
$result = dbquery("SELECT ID_users, ID_users2 FROM ".$db_prefix."db_itrzadan where ID = $order_id");

$names = mysql_fetch_assoc( $result );

$names_id_arr = explode('|', $names['ID_users2'] );
$author = $names['ID_users'];

$exec_list = "
    <select id='exec_list' class='combobox'>$employeement_list</select>";

$query = "SELECT `ID` FROM `".$db_prefix."db_resurs` where `ID_users` = $user_id " ;
$res = dbquery( $query );
$res_row = mysql_fetch_assoc($res);
$res_id = $res_row['ID'];

if( $author == $res_id || $user_id == 1 )
{
echo $exec_list ;
for($i = 0 ; $i < count( $names_id_arr ) ; $i ++ )
 {
    $name = GetPerson( $names_id_arr[$i] );
    if( strlen( $name ) )
      echo "<div title='Удалить исполнителя' id='executor_".$names_id_arr[$i]."' class='executor_span'>$name<img id='del_executor_img_".$names_id_arr[$i]."' src='uses/del.png' title='Удалить исполнителя' class='del_executors_img'/></div>";
 }
}
else
{
  for($i = 0 ; $i < count( $names_id_arr ) ; $i ++ )
    echo "<div>".GetPerson( $names_id_arr[$i] )."</div>";
}


?>


