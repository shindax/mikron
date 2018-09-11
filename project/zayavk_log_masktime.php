<?php 
echo "<script type='text/javascript'>

-function listen(){
  var strind = 0;

  prepTabs = function (t){
	for (var index1=0; index1<document.getElementsByName('transf_time').length; index1++){
		if (document.getElementsByName('transf_time')[index1].getElementsByTagName('input')[0]){
			document.getElementsByName('transf_time')[index1].getElementsByTagName('input')[0].onkeydown=maskatime;
			document.getElementsByName('transf_time')[index1].getElementsByTagName('input')[0].onclick=maskatimeclick;
		}
	}
  }

  var maskatimeclick = function (e) {
    e = window.event
    var obj = e.target || e.srcElement;
	obj.setSelectionRange(0,0);
	strind = 0;
  }
  var maskatime = function (e) {
    e = window.event
    var obj = e.target || e.srcElement;
	var cursorind = 0;
	var dlina_all = 8;
	var dlina_posle1 = obj.value.substr(strind, dlina_all - strind);
	var dlina_do1 = obj.value.substr(0, strind - 1);
	var dlina_posle = obj.value.substr(strind + 1, (dlina_all - strind) - 1);
	var dlina_do = obj.value.substr(0, strind);
	var dvoetoch1 = obj.value.substr(2, 1);
	var dvoetoch2 = obj.value.substr(5, 1);
	var idi_1 = obj.name.length, idi_2=35, idi_3;
	idi_3 = idi_1-idi_2;
	var idi = obj.name.substr(idi_2,idi_3);
	
	if (((e.keyCode > 47) && (e.keyCode < 58)) || ((e.keyCode > 95) && (e.keyCode < 106))) {
		strind = strind + 1;
		if (strind == 1) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 2) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 3) { strind = strind + 1;}
		if (strind == 4) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 5) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 6) { strind = strind + 1;}
		if (strind == 7) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 8) { obj.setSelectionRange(strind - 1,strind);}
		if (strind >= 9) { obj.setSelectionRange(7,8); strind = 8;}
	}
	if (e.keyCode == 37) {
		if (strind == 8) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 7) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 6) { strind = strind - 1;}
		if (strind == 5) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 4) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 3) { strind = strind - 1;}
		if (strind == 2) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 1) { obj.setSelectionRange(0,0);}
		if (strind <= 0) { strind = 1;}
		strind = strind - 1;
	}
	if (e.keyCode == 39) {
		strind = strind + 1;
		if (strind == 1) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 2) { strind = strind + 1;}
		if (strind == 3) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 4) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 5) { strind = strind + 1;}
		if (strind == 6) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 7) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 8) { obj.setSelectionRange(strind - 1,strind);}
		if (strind >= 9) { obj.setSelectionRange(7,7); strind = 8;}
	}
	if (e.keyCode == 35) {
		strind = 8;
	}
	if (e.keyCode == 36) {
		strind = 0;
	}
	if (e.keyCode == 20) {
	}
	if (e.keyCode == 46) {
		if (strind <= 7) { obj.value = dlina_do + '00' + dlina_posle; obj.setSelectionRange(strind,strind);}
		if (strind >= 8) { obj.value = dlina_do + '0' + dlina_posle; obj.setSelectionRange(7,7); strind = 7;}
	}
	if (e.keyCode == 8) {
		if (strind == 8) { obj.value = dlina_do1 + '00' + dlina_posle1; obj.setSelectionRange(strind - 1,strind);}
		if (strind == 7) { obj.value = dlina_do1 + '00' + dlina_posle1; obj.setSelectionRange(strind - 1,strind);}
		if (strind == 6) { strind = strind - 1;}
		if (strind == 5) { obj.value = dlina_do1 + '0:' + dlina_posle1; obj.setSelectionRange(strind - 1,strind);}
		if (strind == 4) { obj.value = dlina_do1 + '00' + dlina_posle1; obj.setSelectionRange(strind - 1,strind);}
		if (strind == 3) { strind = strind - 1;}
		if (strind == 2) { obj.value = dlina_do1 + '0:' + dlina_posle1; obj.setSelectionRange(strind - 1,strind);}
		if (strind == 1) { obj.value = dlina_do1 + '0' + dlina_posle1; obj.setSelectionRange(0,0);}
		if (strind <= 0) { strind = 1;}
		strind = strind - 1;
	}
	if (((e.keyCode > 64) && (e.keyCode < 91)) || ((e.keyCode > 185) && (e.keyCode < 193)) || ((e.keyCode > 218) && (e.keyCode < 223))) {
		obj.setSelectionRange(8,8);
		setTimeout(clearke, 100);		
	}
	function clearke() {
	  obj.value = dlina_do + dlina_posle1;
	  obj.setSelectionRange(strind,strind)
	}
	setTimeout(clearke2, 250)
	function clearke2() {
		if ((dvoetoch1 !== ':') || (dvoetoch2 !== ':')){
			obj.value = obj.value.substr(0, 2) + ':' + obj.value.substr(3, 2) + ':' + obj.value.substr(6, 2);
			obj.setSelectionRange(strind,strind);
		}
		if (obj.value.length > 8) {
			obj.value = obj.value.substr(0, 8);
			obj.setSelectionRange(strind,strind);
		}
		if ((obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = '2' + obj.value.substr(1, 7); obj.setSelectionRange(strind,strind); vote(obj , 'db_edit.php?db=db_logistic_app&field=TRANSFER_TIME&id='+idi+'&value='+obj.value);}
		if (obj.value.substr(1, 1) == '4') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind);  vote(obj , 'db_edit.php?db=db_logistic_app&field=TRANSFER_TIME&id='+idi+'&value='+obj.value);};}
		if (obj.value.substr(1, 1) == '5') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind); vote(obj , 'db_edit.php?db=db_logistic_app&field=TRANSFER_TIME&id='+idi+'&value='+obj.value);};}
		if (obj.value.substr(1, 1) == '6') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind); vote(obj , 'db_edit.php?db=db_logistic_app&field=TRANSFER_TIME&id='+idi+'&value='+obj.value);};}
		if (obj.value.substr(1, 1) == '7') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind); vote(obj , 'db_edit.php?db=db_logistic_app&field=TRANSFER_TIME&id='+idi+'&value='+obj.value);};}
		if (obj.value.substr(1, 1) == '8') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind); vote(obj , 'db_edit.php?db=db_logistic_app&field=TRANSFER_TIME&id='+idi+'&value='+obj.value);};}
		if (obj.value.substr(1, 1) == '9') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind);  vote(obj , 'db_edit.php?db=db_logistic_app&field=TRANSFER_TIME&id='+idi+'&value='+obj.value);};}
		if ((obj.value.substr(3, 1) == '6') || (obj.value.substr(3, 1) == '7') || (obj.value.substr(3, 1) == '8') || (obj.value.substr(3, 1) == '9')) { obj.value = obj.value.substr(0, 3) + '5' + obj.value.substr(4, 4); obj.setSelectionRange(strind,strind); vote(obj , 'db_edit.php?db=db_logistic_app&field=TRANSFER_TIME&id='+idi+'&value='+obj.value);}
		if ((obj.value.substr(6, 1) == '6') || (obj.value.substr(6, 1) == '7') || (obj.value.substr(6, 1) == '8') || (obj.value.substr(6, 1) == '9')) { obj.value = obj.value.substr(0, 6) + '5' + obj.value.substr(7, 1); obj.setSelectionRange(strind,strind); vote(obj , 'db_edit.php?db=db_logistic_app&field=TRANSFER_TIME&id='+idi+'&value='+obj.value);}
	}
 }
  
 window.onload = prepTabs
}()
function getUrlVars() {
   var vars = {};
   var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	   vars[key] = value;
	   });
   return vars;
}</script>";
?>