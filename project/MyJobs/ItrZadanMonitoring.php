<?php
include  "project/MyJobs/page_ids.php";
global $PROJECT_ORDER_MONITORING_PAGE_ID;

$result2 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID_users='".$user['ID']."') ");
$name2 = mysql_fetch_array($result2);
$titlitr = "Мониторинг заданий по проектам";
?>

<link rel='stylesheet' href='project/dnevnik/index.css' type='text/css'>

<table class='shablon' style='border-collapse: collapse; border: 0px solid black; color: #000; width: 100%;' border='1' cellpadding='0' cellspacing='0'>
<tbody>
<tr><td colspan='2' height='30' style='vertical-align: bottom; padding: 0px 0px 5px 145px;'><div class='links'></div></td></tr>
<tr><td width='220'><div class='swin' style='width:200px;'><?php include "project/dnevnik/menu.php"; ?></div></td>
<td><div class='swin'>
<?php if(!$_GET['arch']){ $arch_3 = "&arch=1"; $link_3 = "Архив";}else{ $arch_3 = ""; $link_3 = "Задания в работе";} echo"<h2>".$titlitr."</h2><!--a href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID".$arch_3 ."'>".$link_3 ."</a--><br><br>"; ?>

<?php include "project/MyJobs/my_itrzadan_monitoring.php"; ?>

</div>
</tbody></table>

<?php
    include "project/MyJobs/my_itr_monitoring_sort_func.php";
?>
<script type='text/javascript' src='project/itrzadan_tips.js'></script>
<script type='text/javascript' src='project/MyJobs/itrzadan_group.js'></script>