<?php
echo "
<script type='text/javascript'>
var archx = getUrlVars()['arch'];
var dd1 = document.getElementsByName('status');
var dd2 = document.getElementsByName('itrdate');
var now = new Date()
var sss1 = 1, ssum;
ssum = sss1 + now.getMonth();
for (var ff = 0; ff < dd1.length; ff++){
   if (dd1[ff].innerText == 'Принято к исполнению') {
      dd1[ff].style.backgroundColor = '#F7F346';
   }
   if (dd1[ff].innerText == 'Выполнено') {
      dd1[ff].style.backgroundColor = '#CA9DDC';
   }
   if (dd1[ff].innerText == 'Новое') {
      dd1[ff].style.backgroundColor = '#BBAE00';
   }
   if (dd1[ff].innerText == 'Принято') {
      dd1[ff].style.backgroundColor = '#66AAFF';
   }
   if (dd1[ff].innerText == 'На доработку') {
      dd1[ff].style.backgroundColor = '#8BBB69';
   }
   if (!archx) {
   var ddate = dd2[ff].innerText;
   var dday = ddate.substr(0, 2);
   var dmon = ddate.substr(3, 2);
   var dyer = ddate.substr(6, 4);
   if (now.getFullYear() > dyer) {
      dd2[ff].style.backgroundColor = '#FF7474';
   }
   if (ssum > dmon) {
   if (dyer <= now.getFullYear()) {	   
       dd2[ff].style.backgroundColor = '#FF7474';
   }
   }
   if (now.getDate() > dday) {
   if (dmon <= ssum) {
   if (dyer <= now.getFullYear()) {	   
      dd2[ff].style.backgroundColor = '#FF7474';
	}
	}
	}
   }
   if (archx) {
   var dd3 = document.getElementsByName('itrdatefact');
   var ddate = dd3[ff].innerText;
   var dd5 = document.getElementsByName('date_fact');
   var ddate2 = dd5[ff].innerText;
   if (ddate > 0) {
      dd3[ff].style.backgroundColor = '#FF7474';
   }
   if (ddate2.substr(0,1) == '.') {
      dd3[ff].innerText='';
	  dd5[ff].innerText='';
   }}
}
function getUrlVars() {
   var vars = {};
   var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	   vars[key] = value;
	   });
   return vars;
}</script>";
$arch = $_GET['arch'];
if (!$arch){

// shindax 30.08.2016
//$result5 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where ((ID_users2='".$name2['ID']."') and (STATUS='Новое')) ");
$result5 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_itrzadan where (( (ID_users2 LIKE '%".$name2['ID']."|%') OR (ID_users2 = '".$name2['ID']."')) and (STATUS='Новое')) ");

$name5 = mysql_fetch_row($result5);
$total5 = $name5[0];

$sdh = 0;
for ($sdg = 0; $sdg < $total5; $sdg++)
{

// shindax 30.08.2016
//  $result6 = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where ((ID_users2='".$name2['ID']."') and (STATUS='Новое') and (ID>'".$sdh."')) ");
  $result6 = dbquery("SELECT ID,ID_edo FROM ".$db_prefix."db_itrzadan where ( ( (ID_users2 LIKE '%".$name2['ID']."|%') OR (ID_users2 = '".$name2['ID']."') ) and (STATUS='Новое') and (ID>'".$sdh."')) ");

  $name6 = mysql_fetch_assoc($result6);
  $total6 = $name6['ID_edo'];
  $total6_1 = $name6['ID'];
  $sdh = $total6_1;

// shindax 30.08.2016
//  dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Просмотрено' where ((ID_users2='".$name2['ID']."') and (STATUS='Новое') and (ID<'".($sdh+1)."')) ");
  dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Просмотрено' where ( ( (ID_users2 LIKE '%".$name2['ID']."|%') OR (ID_users2='".$name2['ID']."')) and (STATUS='Новое') and (ID<'".($sdh+1)."')) ");


  $db = db_itrzadan_statuses;
  $pid = (isset($_GET["pid"]) ? $_GET["pid"] : 0);
  $lid = (isset($_GET["lid"]) ? $_GET["lid"] : 0);
  $addf = (isset($_GET["addf"]) ? $_GET["addf"] : "");
  $addv = (isset($_GET["addv"]) ? $_GET["addv"] : "");
  $addf2 = (isset($_GET["addf2"]) ? $_GET["addf2"] : "");
  $addv2 = (isset($_GET["addv2"]) ? $_GET["addv2"] : "");
  $add_error = "";
  $insert_id = CreateElement($db,$pid,$lid,$addf,$addv,$addf2,$addv2);
  dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Просмотрено' where (ID='".$insert_id."') ");
  dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET USER='".$name2['ID']."' where (ID='".$insert_id."') ");
  dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET ID_edo='".$total6_1."' where (ID='".$insert_id."') ");
}}
?>