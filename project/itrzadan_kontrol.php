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
?>