<?php
$p6 = $_GET['p6'];
if ($p6){
$db = db_itrzadan;
$pid = (isset($_GET["pid"]) ? $_GET["pid"] : 0);
$lid = (isset($_GET["lid"]) ? $_GET["lid"] : 0);
$addf = (isset($_GET["addf"]) ? $_GET["addf"] : "");
$addv = (isset($_GET["addv"]) ? $_GET["addv"] : "");
$addf2 = (isset($_GET["addf2"]) ? $_GET["addf2"] : "");
$addv2 = (isset($_GET["addv2"]) ? $_GET["addv2"] : "");
$add_error = "";
$insert_id = CreateElement($db,$pid,$lid,$addf,$addv,$addf2,$addv2);
$insert_id2 = CreateElement(db_itrzadan_statuses,$pid,$lid);
$result = dbquery("SELECT * FROM ".$db_prefix."db_itr_vremitr where (ID='".$p6."') ");
$name = mysql_fetch_array($result);
$result2 = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID='".$insert_id."') ");
$name2 = mysql_fetch_array($result2);
$result3 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID_users='".$user['ID']."') ");
$name3 = mysql_fetch_array($result3);
dbquery("UPDATE ".$db_prefix."db_itrzadan SET DATE_PLAN='".$name['DATE_PLAN']."' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET ID_zak='".$name['ID_zak']."' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET TIME_PLAN='".$name['TIME_PLAN']."' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET STARTDATE='".$name['STARTDATE']."' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET STARTTIME='".$name['STARTTIME']."' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET ID_users='".$name3['ID']."' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET ID_users2='".$name['ID_users2']."' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET ID_users3='".$name['ID_users3']."' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET TXT='".$name['TXT']."' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET EUSER='".$name3['ID']."' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET TIP_JOB='".$name['TIP_JOB']."' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET STATUS='Новое' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan SET TIP_FAIL='9' where (ID='".$insert_id."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET STATUS='Новое' where (ID='".$insert_id2."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET USER='".$name3['ID']."' where (ID='".$insert_id2."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET ID_edo='".$name2['ID']."' where (ID='".$insert_id2."') ");
dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET TIME='".$name2['ETIME']."' where (ID='".$insert_id2."') ");

echo "
<script type='text/javascript'>
document.location.href = 'index.php?do=show&formid=118';
</script>";
}

echo "
<script type='text/javascript'>
var archx = getUrlVars()['arch'];
var dd1 = document.getElementsByName('status');
var dd2 = document.getElementsByName('itrdate');
var dd8 = document.getElementsByName('itr_prior_c');
var now = new Date()
var sss1 = 1, ssum;
ssum = sss1 + now.getMonth();
for (var ff = 0; ff < dd1.length; ff++){
	if(dd8[ff].selectedIndex==1){
		var cells_c = dd8[ff].parentNode.parentNode.cells.length;
		for (var a_a = 0; a_a < cells_c; a_a++){
			dd8[ff].parentNode.parentNode.cells[a_a].setAttribute('style','color:#ff0000; font-size:125%; font-weight:bold;');
		}
	}
	if(dd8[ff].selectedIndex==2){
		var cells_c = dd8[ff].parentNode.parentNode.cells.length;
		for (var a_a = 0; a_a < cells_c; a_a++){
			dd8[ff].parentNode.parentNode.cells[a_a].setAttribute('style','color:#44cf44; font-size:125%; font-weight:bold;');
		}
	}
	if(dd8[ff].selectedIndex==3){
		var cells_c = dd8[ff].parentNode.parentNode.cells.length;
		for (var a_a = 0; a_a < cells_c; a_a++){
			dd8[ff].parentNode.parentNode.cells[a_a].setAttribute('style','color:#A7A9F5; font-size:125%; font-weight:bold;');
		}
	}
	if(dd8[ff].selectedIndex==4){
		var cells_c = dd8[ff].parentNode.parentNode.cells.length;
		for (var a_a = 0; a_a < cells_c; a_a++){
			dd8[ff].parentNode.parentNode.cells[a_a].setAttribute('style','color:#D0CC38; font-size:125%; font-weight:bold;');
		}
	}
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
?>