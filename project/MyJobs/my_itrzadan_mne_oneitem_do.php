<?php
require_once("CommonFunctions.php");
error_reporting( 0 );

global $PROJECT_PAGE_ID;

$itrid      = $_GET['id'];
$itrstat    = $_GET['p8'];
$itrstat2   = $_GET['p9'];
   
$tithead = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID='".$itrid."') ");
$tithead = mysql_fetch_array( $tithead );

$host = $_SERVER['HTTP_HOST'];
$user_id = $user['ID'];
$user_res_id = GetUserResourceID( $user_id );
$new_date = GetLastDateByStatusChange( $itrid );

echo "<H2 id='title' data-id='$itrid'>Задание №".$itrid."</H2>";
echo "<a class='link'>Вернуться в проекты</a><br><br>";

$creator = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID='".$itrid."') ");
$creator = mysql_fetch_array($creator);
$creator = $creator['ID_users'];

//$executor = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID='".$itrid."') ");
//$executor = mysql_fetch_array($executor );
//$executor = $executor['ID_users2'];

$checker = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID='".$itrid."') ");
$checker = mysql_fetch_array($checker);
$checker = $checker['ID_users3'];


if( $checker == $creator || $user_res_id == $creator )
    $rework_str = 'Задание от меня - ';
        else
            $rework_str = 'Контроль выполнения - ' ;

//echo "<script>localStorage.setItem('cell_new_comment', \'Zzz\' );</script>";
        
if ($itrstat2 == '1') 
    {
	$db = db_itrzadan_statuses;
	$pid = (isset($_GET["pid"]) ? $_GET["pid"] : 0);
	$lid = (isset($_GET["lid"]) ? $_GET["lid"] : 0);
	$addf = (isset($_GET["addf"]) ? $_GET["addf"] : "");
	$addv = (isset($_GET["addv"]) ? $_GET["addv"] : "");
	$addf2 = (isset($_GET["addf2"]) ? $_GET["addf2"] : "");
	$addv2 = (isset($_GET["addv2"]) ? $_GET["addv2"] : "");
	$add_error = "";

	$insert_id = CreateElement($db,$pid,$lid,$addf,$addv,$addf2,$addv2);

	$resh1 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID_users='".$user['ID']."') ");
	$nnam1 = mysql_fetch_array($resh1);
	$resh2 = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan_statuses where (ID='".$insert_id."') ");
	$nnam2 = mysql_fetch_array($resh2);

	dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET ID_edo='".$itrid."' where (ID='".$insert_id."') ");
	dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET USER='".$nnam1['ID']."' where (ID='".$insert_id."') ");

	if ($itrstat == '1') {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Принято к исполнению' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Принято к исполнению', TIT_HEAD='Задание мне - ' where (ID='".$itrid."') ");
	}
	if ($itrstat == '2') 
    {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Выполнено' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Выполнено', TIT_HEAD='Задание мне - ' where (ID='".$itrid."') ");
	}
	if ($itrstat == '3') {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Принято' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Принято', TIT_HEAD='Контроль выполнения - ' where (ID='".$itrid."') ");
	}
	if ($itrstat == '4') {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='На доработку' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='На доработку', TIT_HEAD='$rework_str' where (ID='".$itrid."') ");
	}
	if ($itrstat == '5') {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Аннулировано' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Аннулировано', TIT_HEAD='Задание от меня - ' where (ID='".$itrid."') ");
	}
	if ($itrstat == '6') {
		dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Завершено' where (ID='".$insert_id."') ");
		dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Завершено', TIT_HEAD='Задание от меня - ' where (ID='".$itrid."') ");
		if ($tithead['ID_zapr']!=='0') {
			dbquery("UPDATE ".$db_prefix."db_zapros_all SET STATUS='Выполнено' where (ID='".$tithead['ID_zapr']."') ");
			dbquery("UPDATE ".$db_prefix."db_zapros_all SET DATE_FACT='".$nnam2['DATA']."' where (ID='".$tithead['ID_zapr']."') ");
			dbquery("UPDATE ".$db_prefix."db_zapros_all SET TIME_FACT='".$nnam2['TIME']."' where (ID='".$tithead['ID_zapr']."') ");
		}
	}
	
	dbquery("UPDATE ".$db_prefix."db_itrzadan SET EUSER='".$nnam1['ID']."' where (ID='".$itrid."') ");
	dbquery("UPDATE ".$db_prefix."db_itrzadan SET ETIME='".$nnam2['TIME']."' where (ID='".$itrid."') ");

} // if ($itrstat2 == '1') 


echo "
     <script>
     var host = '$host';
     var page = '$PROJECT_PAGE_ID'; 
     var id = '$itrid' ;
     </script>"
?>
<script>

function ret()
{
    var stat = $('td[name="status"]').text();
        localStorage.setItem("cell_state", stat );
    var new_date = $('td[name="dateendfact"]').text();
        localStorage.setItem("cell_new_date", new_date );
    var new_comm = $('input[name^="db_itrzadan_KOMM1_edit_"]').val();
        localStorage.setItem('cell_new_comment', new_comm );
        document.location.href = "http://"+ host + "/index.php?do=show&formid=" + page + "&id=" + id ;
}

$( function()
{
    $('.link').click( ret );
});

</script>

