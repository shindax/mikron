<?php 
error_reporting( E_ALL );
error_reporting( 0 );

$itrid = $_GET['id']; 
$nam1 = dbquery("SELECT MAX(ID) FROM ".$db_prefix."db_itrzadan_statuses where ((ID_edo='".$itrid."') and (STATUS='Выполнено')) "); 
$nam2 = mysql_fetch_row($nam1); 
$nam3 = $nam2[0];

$nam11 = dbquery("SELECT MAX(ID) FROM ".$db_prefix."db_itrzadan_statuses where ((ID_edo='".$itrid."') and (STATUS='Принято к исполнению')) "); 
$nam22 = mysql_fetch_row($nam11); 
$nam33 = $nam22[0];

$nam1_1 = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID='".$itrid."') "); 
$nam2_1 = mysql_fetch_array($nam1_1); 
$nam3_1 = $nam2_1['ID_users'];
$nam3_2 = $nam2_1['ID_users2'];
$nam3_3 = $nam2_1['ID_users3'];
$namm2 = $nam2_1['TIP_FAIL'];
$namm2_5 = $nam2_1['ID_zak'];
$namm2_6 = $nam2_1['ID_zapr'];
$namm3 = $nam2_1['STATUS'];

$nam1_2 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID_users='".$user['ID']."') "); 
$nam2_2 = mysql_fetch_array($nam1_2); 
$nam4_1 = $nam2_2['ID'];

echo "<script type='text/javascript'>
var row = document.getElementById('itrstatuses');

if ('$nam33' == '') {
  document.getElementsByName('datestartfact')[0].innerText = '';
}else{
  var datefact = document.getElementsByName('datestartfact')[0].innerText;
  timefact = datefact.substr(11)
  datefact = datefact.substr(0, 10)
  document.getElementsByName('timestartfact')[0].innerText = timefact;
  document.getElementsByName('datestartfact')[0].innerText = datefact;
}

if ('$nam3' == '') { 
  document.getElementsByName('dateendfact')[0].innerText = '';
}else{
  var datefact = document.getElementsByName('dateendfact')[0].innerText;
  timefact = datefact.substr(11)
  datefact = datefact.substr(0, 10)
  document.getElementsByName('timeendfact')[0].innerText = timefact;
  document.getElementsByName('dateendfact')[0].innerText = datefact;
}

if ('$namm3' == 'Принято к исполнению') {document.getElementById('itrstatus1').checked=true;}
if ('$namm3' == 'Выполнено') {document.getElementById('itrstatus2').checked=true;}
if ('$namm3' == 'Принято') {document.getElementById('itrstatus3').checked=true;}
if ('$namm3' == 'На доработку') {document.getElementById('itrstatus4').checked=true;}
if ('$namm3' == 'Аннулировано') {document.getElementById('itrstatus5').checked=true;}
if ('$namm3' == 'Завершено') {document.getElementById('itrstatus6').checked=true;}

if ('$nam3_1' !== '$nam4_1') {
	if (document.getElementsByName('db_itrzadan_KOMM1_edit_$itrid')[0]) {
		document.getElementsByName('db_itrzadan_KOMM1_edit_$itrid')[0].parentNode.className='Field';		
		document.getElementsByName('db_itrzadan_KOMM1_edit_$itrid')[0].parentNode.innerHTML = document.getElementsByName('db_itrzadan_KOMM1_edit_$itrid')[0].value;
		//document.getElementsByName('db_itrzadan_KOMM1_edit_$itrid')[0].disabled=true;		
		//document.getElementsByName('db_itrzadan_KOMM1_edit_$itrid')[0].style.display='none';		
	}
  row.deleteCell(5);
  row.deleteCell(4);
}else{
	document.getElementsByName('db_itrzadan_KOMM1_edit_$itrid')[0].parentNode.className='rwField ntabg';		
}


if ('$nam3_2' !== '$nam4_1') {
	if (document.getElementsByName('db_itrzadan_KOMM2_edit_$itrid')[0]) {
		document.getElementsByName('db_itrzadan_KOMM2_edit_$itrid')[0].parentNode.className='Field';		
		document.getElementsByName('db_itrzadan_KOMM2_edit_$itrid')[0].parentNode.innerHTML = document.getElementsByName('db_itrzadan_KOMM2_edit_$itrid')[0].value;
		//document.getElementsByName('db_itrzadan_KOMM2_edit_$itrid')[0].disabled=true;		
		//document.getElementsByName('db_itrzadan_KOMM2_edit_$itrid')[0].style.display='none';		
	}
	if (document.getElementsByName('db_itrzadan_DOCISP_edit_$itrid')[0]) {
		document.getElementsByName('db_itrzadan_DOCISP_edit_$itrid')[0].parentNode.className='Field';		
		document.getElementsByName('db_itrzadan_DOCISP_edit_$itrid')[0].parentNode.innerHTML = document.getElementsByName('db_itrzadan_DOCISP_edit_$itrid')[0].value;
		//document.getElementsByName('db_itrzadan_DOCISP_edit_$itrid')[0].disabled=true;		
		//document.getElementsByName('db_itrzadan_DOCISP_edit_$itrid')[0].style.display='none';		
	}
  row.deleteCell(1);
  row.deleteCell(0);
}else{
	document.getElementsByName('db_itrzadan_KOMM2_edit_$itrid')[0].parentNode.className='rwField ntabg';		
	document.getElementsByName('db_itrzadan_DOCISP_edit_$itrid')[0].parentNode.className='rwField ntabg';		
}
if ('$nam3_3' !== '$nam4_1') {
	if (document.getElementsByName('db_itrzadan_KOMM3_edit_$itrid')[0]) {
		document.getElementsByName('db_itrzadan_KOMM3_edit_$itrid')[0].parentNode.className='Field';		
		document.getElementsByName('db_itrzadan_KOMM3_edit_$itrid')[0].parentNode.innerHTML = document.getElementsByName('db_itrzadan_KOMM3_edit_$itrid')[0].value;
		//document.getElementsByName('db_itrzadan_KOMM3_edit_$itrid')[0].disabled=true;		
		//document.getElementsByName('db_itrzadan_KOMM3_edit_$itrid')[0].style.display='none';		
	}
  if ('$nam3_1' !== '$nam4_1') {
    if ('$nam3_2' !== '$nam4_1') {
		row.deleteCell(1);
		row.deleteCell(0);
	}
    if ('$nam3_2' == '$nam4_1') {
		row.deleteCell(3);
		row.deleteCell(2);
	}
  }
  if ('$nam3_1' == '$nam4_1') {
    if ('$nam3_2' !== '$nam4_1') {
		row.deleteCell(0);
	}
    if ('$nam3_2' == '$nam4_1') {
		row.deleteCell(2);
	}
  }
}else{
	document.getElementsByName('db_itrzadan_KOMM3_edit_$itrid')[0].parentNode.className='rwField ntabg';		
}

var lencell = document.getElementsByName('itrstatuses').length;
var lencell1 = 1;
lencell = lencell - lencell1;
if (document.getElementsByName('itrstatuses')[lencell]) {document.getElementsByName('itrstatuses')[lencell].style.borderRight = '0px';}

-function listen(){
  var strind = 0;

  prepTabs = function (t){
	var idi = getUrlVars()['id'];
	history.replaceState(0, 'New page title', 'index.php?do=show&formid=122&id=' + idi);
    for (var ddd=1; ddd < 7; ddd++){
      if (document.getElementById('itrstatus'+ddd)){
        document.getElementById('itrstatus'+ddd).onclick = itrclick;
      }
    }
	for (var tdd3=0; tdd3 < 10; tdd3++){
		var tdd1 = document.getElementById('txtalig' + tdd3);
		if (tdd1) {
			var tdd2 = tdd1.getElementsByTagName('td')[1];
			if (tdd2) {
				tdd2.style.textAlign='left';			
			}
		}
	}
	var tdd1 = document.getElementById('txtalig1');
	if ('$namm2' == '0'){
		if (tdd1.getElementsByTagName('td')[1]) {
			var tdd2 = tdd1.getElementsByTagName('td')[1];
			tdd2.innerText = 'ВХ | ' + tdd2.innerText;
		}
	}
	if ('$namm2' == '1'){
		if (tdd1.getElementsByTagName('td')[1]) {
			var tdd2 = tdd1.getElementsByTagName('td')[1];
			tdd2.innerText = 'ИСХ | ' + tdd2.innerText;
		}
	}
	if ('$namm2' == '2'){
		if (tdd1.getElementsByTagName('td')[1]) {
			var tdd2 = tdd1.getElementsByTagName('td')[1];
			tdd2.innerText = 'ПР | ' + tdd2.innerText;
		}
	}
	
	if ('$namm2' == '9' ){
		if (tdd1.getElementsByTagName('td')[1]) {
			var tdd2 = tdd1.getElementsByTagName('td')[1];
			tdd2.innerText = tdd2.innerText;
		}
		tdd1.parentNode.style.display='none';
	}
	
	if ( '$namm2_5' == '0' ){
		document.getElementById('txtalig2').parentNode.style.display='none';
	}
  }

  var itrclick = function (e) {
    e = window.event
    var obj = e.target || e.srcElement;
	var idi = getUrlVars()['id'];
	var statuss = document.getElementsByName('status')[0].innerText;;
    
	if (obj.checked==true){
		if (('$namm3'=='Новое') || ('$namm3'=='Просмотрено')) {
			  if (obj.id == 'itrstatus1') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=1&p9=1';} else { this.checked=this.checked==false;}}
			  if (obj.id == 'itrstatus5') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=5&p9=1';} else { this.checked=this.checked==false;}}
			  if (obj.id == 'itrstatus2') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus3') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus4') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus6') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
		}
		if ('$namm3'=='Принято к исполнению') {
			  if (obj.id == 'itrstatus2') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=2&p9=1';} else { this.checked=this.checked==false;}}
			  if (obj.id == 'itrstatus5') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=5&p9=1';} else { this.checked=this.checked==false;}}
			  if (obj.id == 'itrstatus1') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus3') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus4') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus6') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
		}
		if ('$namm3'=='Выполнено') {
			  if (obj.id == 'itrstatus3') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=3&p9=1';} else { this.checked=this.checked==false;}}
			  if (obj.id == 'itrstatus4') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=4&p9=1';} else { this.checked=this.checked==false;}}
			  if (obj.id == 'itrstatus5') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=5&p9=1';} else { this.checked=this.checked==false;}}
			  if (obj.id == 'itrstatus1') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus2') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus6') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
		}
		if ('$namm3'=='Принято') {
			  if (obj.id == 'itrstatus6') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=6&p9=1';} else { this.checked=this.checked==false;}}
			  if (obj.id == 'itrstatus5') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=5&p9=1';} else { this.checked=this.checked==false;}}
			  if (obj.id == 'itrstatus1') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus2') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus3') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if ('$namm3_2'=='$namm4_1') {
				if (obj.id == 'itrstatus4') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=4&p9=1';} else { this.checked=this.checked==false;}}
			  }else{
				if (obj.id == 'itrstatus4') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  }
		}
		if ('$namm3'=='На доработку') {
			  if (obj.id == 'itrstatus1') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=1&p9=1';} else { this.checked=this.checked==false;}}
			  if (obj.id == 'itrstatus5') { if (confirm('Изменить статус?')) { document.location.href = 'index.php?do=show&formid=122&id=' + idi + '&p8=5&p9=1';} else { this.checked=this.checked==false;}}
			  if (obj.id == 'itrstatus6') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus2') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus3') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
			  if (obj.id == 'itrstatus4') { obj.checked=false; alert ('Вы не можете изменить статус \"' + statuss + '\" на \"' + obj.parentNode.innerText + '\".');}
		}
	}else{
		obj.checked=true;
		alert ('Уже установлен этот статус.');
	}
  }

  var maskatime = function (e) {
    e = window.event
	var idi = getUrlVars()['id'];
    var obj = e.target || e.srcElement;
	var cursorind = 0;
	var dlina_all = 8;
	var dlina_posle1 = obj.value.substr(strind, dlina_all - strind);
	var dlina_do1 = obj.value.substr(0, strind - 1);
	var dlina_posle = obj.value.substr(strind + 1, (dlina_all - strind) - 1);
	var dlina_do = obj.value.substr(0, strind);
	var dvoetoch1 = obj.value.substr(2, 1);
	var dvoetoch2 = obj.value.substr(5, 1);
	
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