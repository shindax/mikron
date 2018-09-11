<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("project/MyJobs/page_ids.php");
global $PROJECT_ORDER_MONITORING_PAGE_ID;

$today = date("Ymd");
$name11 = dbquery("SELECT ID FROM ".$db_prefix."db_resurs where (ID_users='".$user['ID']."') ");
$name22 = mysql_fetch_array($name11);
$name33 = $name22['ID'];

////////////////////////////      ИТР задания
////////////////////////////////////////////////
// мне - новое
$res1_1 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where ((ID_users2='".$name33."') and (ID_users2!='0') and (STATUS!='завершено') and (STATUS!='аннулировано') and (STATUS='новое')) ");
$row1_1 = mysql_fetch_row($res1_1);
$total1_1 = $row1_1[0];
// мне - всего
$res1 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where ((ID_users2='".$name33."') and (ID_users2!='0') and (STATUS!='завершено') and (STATUS!='аннулировано'))");
$row1 = mysql_fetch_row($res1);
$total1 = $row1[0];
// мне - просрочено
$res1_3 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where ((ID_users2='".$name33."') and (ID_users2!='0') and (STATUS!='завершено') and (STATUS!='аннулировано') and ('".$today."' > DATE_PLAN)) ");
$row1_3 = mysql_fetch_row($res1_3);
$total1_3 = $row1_3[0];
// от меня - принято
$res2_1 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where ((ID_users='".$name33."') and (STATUS!='завершено') and (STATUS!='аннулировано') and (STATUS='принято')) ");
$row2_1 = mysql_fetch_row($res2_1);
$total2_1 = $row2_1[0];
// от меня - всего
$res2 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where ((ID_users='".$name33."') and (STATUS!='завершено') and (STATUS!='аннулировано')) ");
$row2 = mysql_fetch_row($res2);
$total2 = $row2[0];
// от меня - просрочено
$res2_2 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where ((ID_users='".$name33."') and (STATUS!='завершено') and (STATUS!='аннулировано') and ('".$today."' > DATE_PLAN)) ");
$row2_2 = mysql_fetch_row($res2_2);
$total2_2 = $row2_2[0];
// контроь - выполнено
$res3_1 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where ((ID_users3='".$name33."') and (STATUS!='завершено') and (STATUS!='аннулировано') and (STATUS='выполнено')) ");
$row3_1 = mysql_fetch_row($res3_1);
$total3_1 = $row3_1[0];
// контроь - всего
$res3 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where ((ID_users3='".$name33."') and (STATUS!='завершено') and (STATUS!='аннулировано')) ");
$row3 = mysql_fetch_row($res3);
$total3 = $row3[0];
// контроь - просрочено
$res3_2 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where ((ID_users3='".$name33."') and (STATUS!='завершено') and (STATUS!='аннулировано') and ('".$today."' > DATE_PLAN)) ");
$row3_2 = mysql_fetch_row($res3_2);
$total3_2 = $row3_2[0];
  
$zadmy = "Задания мне";
$zadotmy = "Задания от меня";
$zadkv = "Контроль выполнения";
$zadinfo = "Информационные сообщения";

////////////////////////////      ИТР Запросы
////////////////////////////////////////////////
// мне - новых
$zapr_1 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_zapros_all where ((ID_users2_plan='".$name33."') and (STATUS='Отправлен') and (TIT_HEAD='0')) ");
$zapr1_1 = mysql_fetch_row($zapr_1);
$zapr1_2 = $zapr1_1[0];
// мне - всего
$zapr_2 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_zapros_all where ((ID_users2_plan='".$name33."') and (STATUS!='Не отправлен') and (STATUS!='Выполнено') and (TIT_HEAD='0')) ");
$zapr2_1 = mysql_fetch_row($zapr_2);
$zapr2_2 = $zapr2_1[0];
// мне - просрочено
$zapr_3 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_zapros_all where ((ID_users2_plan='".$name33."') and (STATUS!='Не отправлен') and (STATUS!='Выполнено') and (TIT_HEAD='0') and ('".$today."' > DATE_PLAN)) ");
$zapr3_1 = mysql_fetch_row($zapr_3);
$zapr3_2 = $zapr3_1[0];
// от меня - выполнено
$zapr_4 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_zapros_all where ((ID_users='".$name33."') and (STATUS='Выполнено') and (TIT_HEAD='0')) ");
$zapr4_1 = mysql_fetch_row($zapr_4);
$zapr4_2 = $zapr4_1[0];
// от меня - всего
$zapr_5 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_zapros_all where ((ID_users='".$name33."') and (STATUS!='Не отправлен') and (STATUS!='Выполнено') and (TIT_HEAD='0')) ");
$zapr5_1 = mysql_fetch_row($zapr_5);
$zapr5_2 = $zapr5_1[0];
// от меня - просрочено
$zapr_6 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_zapros_all where ((ID_users='".$name33."') and (STATUS!='Не отправлен') and (STATUS!='Выполнено') and (TIT_HEAD='0') and ('".$today."' > DATE_PLAN)) ");
$zapr6_1 = mysql_fetch_row($zapr_6);
$zapr6_2 = $zapr6_1[0];

////////////////////////////      Вывод текста
////////////////////////////////////////////////

echo "
<b>".$zadmy."</b><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=117'>Задания в работе [<b style='color:#BBAE00'>".$total1_1."</b> / <b style='color:#33bb33'>".$total1."</b> / <b style='color:#bb3333'>".$total1_3."</b>]</a><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=117&arch=1'>Архив</a><br>

<br>

<b>".$zadotmy."</b><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=118'>Задания в работе [<b style='color:#66AAFF'>".$total2_1."</b> / <b style='color:#33bb33'>".$total2."</b> / <b style='color:#bb3333'>".$total2_2."</b>]</a><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=118&arch=1'>Архив</a><br>

<br>

<b>".$zadkv."</b><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=119'>Задания в работе [<b style='color:#63008A'>".$total3_1."</b> / <b style='color:#33bb33'>".$total3."</b> / <b style='color:#bb3333'>".$total3_2."</b>]</a><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=119&arch=1'>Архив</a><br>

<br>";

echo "<b>Запросы мне</b><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=135'>Запросы в работе [<b style='color:#BBAE00'>".$zapr1_2."</b> / <b style='color:#33bb33'>".$zapr2_2."</b> / <b style='color:#bb3333'>".$zapr3_2."</b>]</a><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=135&arch=1'>Архив</a><br>

<br>

<b>Запросы от меня</b><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=136'>Запросы в работе [<b style='color:#63008A'>".$zapr4_2."</b> / <b style='color:#33bb33'>".$zapr5_2."</b> / <b style='color:#bb3333'>".$zapr6_2."</b>]</a><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=136&arch=1'>Архив</a><br>

<br><br>";

if ( db_adcheck('db_itr_vremitr')) 
{ 
echo "
<a href='index.php?do=show&formid=134'><b>Мониторинг заданий (заказы)</b></a><br>
<br>
<a href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'><b>Мониторинг заданий (проекты)</b></a><br>
<br>
<a href='index.php?do=show&formid=152'><b>Мониторинг заданий (остальн.)</b></a>";
}

if (($user['ID'] == 1) or ($user['ID'] == 3) or ($user['ID'] == 4) or ($user['ID'] == 16) ) 
{
	echo "<br><br><a href='index.php?do=show&formid=172'><b>Мониторинг запросов</b></a>";
}

if ( $user['ID'] == 145 ) 
{
	echo "<br><br><a href='index.php?do=show&formid=262'><b>Личный кабинет</b></a>";
}

/*<b>".$zadinfo."</b><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=120'>Новые [аа]</a><br>
<a class='tab_1 mlink' href='index.php?do=show&formid=120&arch=1'>Архив</a><br>

<br>
";*/

echo "<br><br><br><b>Справка по цветам</b><br><br>
<b style='color:#BBAE00'>000</b> - новых заданий<br>
<b style='color:#33bb33'>000</b> - заданий всего<br>
<b style='color:#66AAFF'>000</b> - заданий принято<br>
<b style='color:#63008A'>000</b> - заданий выполнено<br>
<b style='color:#bb3333'>000</b> - из них просрочено<br>
<br>
<table><tbody>
<tr><td style='background:#BBAE00;width:20px;'></td><td> - статус ''новое''</td></tr>
<tr><td style='background:#8BBB69;width:20px;'></td><td> - статус ''на доработку''</td></tr>
<tr><td style='background:#66AAFF;width:20px;'></td><td> - статус ''принято''</td></tr>
<tr><td style='background:#CA9DDC;width:20px;'></td><td> - статус ''выполнено''</td></tr>
<tr><td style='background:#FF7474;width:20px;'></td><td> - статус ''просрочено''</td></tr>
<tr><td style='background:#F7F346;width:20px;'></td><td> - статус ''принято к исп.''</td></tr>
</tbody></table>
<br>
";
?>
