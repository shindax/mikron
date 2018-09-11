<link rel='stylesheet' href='chat.css' type='text/css'>

<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>
<link rel="stylesheet" type="text/css" href="./styles.css">

<script type="text/javascript" >
	$(function(){
		var btnUpload=$('#upload');
		var cont_5=document.getElementById('contentBody_5');
		new AjaxUpload(btnUpload, {
			action: 'upload-file.php',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(jpg|pdf|png|bmp|gif)$/.test(ext))){ 
                    // extension is not allowed 
					return false;
				}
				cont_5.innerHTML = 'проверка файла...';
			},
			onComplete: function(file, response){
				//On completion clear the status
				//Add uploaded file to list
				if(response==="3"){
					cont_5.innerHTML = 'файл не верного формата';
				}
				if(response==="1"){
						cont_5.innerHTML = '';
						if (document.getElementById('vvod_chat').value == 'Введите текст') {
							document.getElementById('vvod_chat').value = '[img]./uploads/'+file+'[:img]'; 
							document.getElementById('vvod_chat').style.color = '#000';
						}else{ 
							document.getElementById('vvod_chat').value=document.getElementById('vvod_chat').value+'[img]./uploads/'+file+'[:img]';
						}
						document.getElementById('img_url').style.display='none';
				} 
				if(response==="2"){
					cont_5.innerHTML = 'Ошибка...';
				}
			}
		});
		
	});
</script>

<?php
//					$('<li></li>').appendTo('#files').html('<img src="./uploads/'+file+'" alt="" /><br />'+file).addClass('success');
//					$('<li></li>').appendTo('#files').text('Файл не загружен' + file).addClass('error');
//$sql = "some this text";
//echo substr($sql,0,strpos(trim($sql),' '));
echo "<table class='shablon' style='border-collapse: collapse; border: 0px solid black; color: #000; width: 100%;' border='1' cellpadding='0' cellspacing='0'>
<tbody><tr><td>
<table width='100%'><tbody><tr height='35px'><td></td></tr><tr height='450px'><td width='3%'></td>
<td class='swin' width='64%'>
<input disabled value='Окно чата' style='width:30%;'><input disabled value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Добро пожаловать в чат, сегодня (".date("d.m.Y").")' style='width:70%;'>
<div onmouseover='scroll_stop()' onmouseout='scroll_start()' readonly id='contentBody_2' style='resize:none; width:100%; height:400px; border: 1px solid rgb(169, 169, 169); background:#ddd; overflow-y:auto;'></div>
<input disabled value='Для корректной работы чата используйте браузер chrome или Амиго или Opera' style='font-size:11pt; font-weight:bold; color:red; width:75%;'>
<input disabled value='Показать' style='text-align:center; width:10%;'>
<input style='text-align:center; width:12%;' id='btn_filtr_4' type='button' value='Все сообщения' onclick=' if (document.getElementById(\"itr_filtr_4\").style.display==\"block\") { document.getElementById(\"itr_filtr_4\").style.display=\"none\";}else{ document.getElementById(\"itr_filtr_4\").style.display=\"block\";}'>
<div style='position:relative;'><div id='itr_filtr_4' style='background:#c6d9f1; padding:5px; border:1px solid #8ba2c2; display:none; position:absolute; left:80%;'>
<select name='2' id='sel_filtr_4' size='4'>
<option onclick='check_filtr_4(this);' style='width:150px;' value='1'>За сегодня
<option onclick='check_filtr_4(this);' style='width:150px;' value='2'>За 5 дней
<option onclick='check_filtr_4(this);' style='width:150px;' value='3'>За месяц
<option onclick='check_filtr_4(this);' style='width:150px;' value='4' Selected>Все сообщения
</select></div></div></td>
<td width='1%'></td>
<td class='swin' width='28%'>
<input disabled value='Кто в чате' style='width:100%;'>
<div onmouseover='scroll_stop2()' onmouseout='scroll_start2()' readonly id='contentBody_3' style='resize:none; width:100%; height:400px; border: 1px solid rgb(169, 169, 169); background:#ddd; overflow-y:auto;'></div>
</td>

<td width='3%'></td></tr><tr height='8px'></tr><tr height='100px'><td width='3%'></td>

<td class='swin' colspan='3'>
<textarea id='vvod_chat' maxlength='250' rows='1' onkeydown='if(window.event.keyCode==\"13\"){ vote2(\"mikron_chat_edit.php?p1=\"+document.getElementById(\"btn_filtr_3\").name+\"&user=".$user['ID']."&value=\"+this.value); showContent(\"mikron_chat.php?p1=".$user['ID']."&p2=\"+document.getElementById(\"btn_filtr_4\").name); setTimeout(\"cleare()\", 100); setTimeout(\"scroll2()\", 150);}' maxlength='350' style='resize:none; width:100%; height:20px;}' style='resize:none; width:100%; height:100%;' onfocus='if (this.value == \"Введите текст\") {this.value = \"\"; this.style.color = \"#000\";}' onblur='if (this.value == \"\") {this.value = \"Введите текст\"; this.style.color = \"#777\";}'></textarea>
<br><br><input id='btn_filtr_3' type='button' value='Написать всем' onclick=' if (document.getElementById(\"itr_filtr_3\").style.display==\"block\") { document.getElementById(\"itr_filtr_3\").style.display=\"none\";}else{ document.getElementById(\"itr_filtr_3\").style.display=\"block\";}'>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<-- Кому пишем</span><span style='float:right;'>
<div id='img_url' style='display:none; position:fixed; left:35%; top:45%;'><table><tbody><tr id='img_url_1'><td class='swin'>
<span>Введите URL картинки (.bmp, .png, .gif, .jpg, .pdf) <b id='contentBody_4' style='float:right; color:blue;'></b></span><br>
<input id='img_url_inp' style='width:350px;' type='text'>
<input id='img_url_btn' type='button' value='ok' onclick='showContent3(\"mikron_chat_img.php?p1=\"+document.getElementById(\"img_url_inp\").value);'>
<input type='button' value='cancel' onclick='document.getElementById(\"img_url\").style.display=\"none\";'>
<br><br><br>
<span>Или выберите из лок.сети (.bmp, .png, .gif, .jpg, .pdf) <b id='contentBody_5' style='float:right; color:blue;'></b></span><br>
<div id='mainbody' >
	<div id='upload' ><span>Выбрать файл<span></div>
</div>
</td></tr><tr id='img_url_2'><td class='swin'>
<span>Введите URL<b id='contentBody_6' style='float:right; color:blue;'></b></span><br>
<input id='img_url_inp_3' style='width:350px;' type='text'>
<input id='img_url_btn_3' type='button' value='ok' onclick='document.getElementById(\"img_url_inp_3\").value=document.getElementById(\"img_url_inp_3\").value.replace(new RegExp(\"&\",\"g\"),\"%26\"); if (document.getElementById(\"vvod_chat\").value == \"Введите текст\") {document.getElementById(\"vvod_chat\").value = \"[a]\"+document.getElementById(\"img_url_inp_3\").value+\"[:a]\"; document.getElementById(\"vvod_chat\").style.color = \"#000\";}else{ document.getElementById(\"vvod_chat\").value=document.getElementById(\"vvod_chat\").value+\"[a]\"+document.getElementById(\"img_url_inp_3\").value+\"[:a]\";};document.getElementById(\"img_url_inp_3\").value=\"\";document.getElementById(\"img_url\").style.display=\"none\";'>
<input type='button' value='cancel' onclick='document.getElementById(\"img_url\").style.display=\"none\";'>
</td></tr></tbody></table></div>
<img width='29px' src='project/img/url.png' style='cursor:pointer;' onclick='document.getElementById(\"img_url\").style.display=\"block\";document.getElementById(\"img_url_1\").style.display=\"none\"; document.getElementById(\"img_url_2\").style.display=\"block\";'>
<img src='project/img/bord.png'>
<img width='22px' src='project/img/img.png' style='cursor:pointer;' onclick='document.getElementById(\"img_url\").style.display=\"block\";document.getElementById(\"img_url_1\").style.display=\"block\";document.getElementById(\"img_url_2\").style.display=\"none\";'>
<img src='project/img/bord.png'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<img src='project/img/bord.png'>
<img src='project/smails/1 (1).gif' style='cursor:pointer;' onclick='if (document.getElementById(\"vvod_chat\").value == \"Введите текст\") {document.getElementById(\"vvod_chat\").value = \":oo:\"; document.getElementById(\"vvod_chat\").style.color = \"#000\";}else{ document.getElementById(\"vvod_chat\").value=document.getElementById(\"vvod_chat\").value+\":oo:\"}'>
<img src='project/smails/1 (2).gif' style='cursor:pointer;' onclick='if (document.getElementById(\"vvod_chat\").value == \"Введите текст\") {document.getElementById(\"vvod_chat\").value = \":nice:\"; document.getElementById(\"vvod_chat\").style.color = \"#000\";}else{ document.getElementById(\"vvod_chat\").value=document.getElementById(\"vvod_chat\").value+\":nice:\"}'>
<img src='project/smails/1 (3).gif' style='cursor:pointer;' onclick='if (document.getElementById(\"vvod_chat\").value == \"Введите текст\") {document.getElementById(\"vvod_chat\").value = \":allok:\"; document.getElementById(\"vvod_chat\").style.color = \"#000\";}else{ document.getElementById(\"vvod_chat\").value=document.getElementById(\"vvod_chat\").value+\":allok:\"}'>
<img src='project/smails/1 (4).gif' style='cursor:pointer;' onclick='if (document.getElementById(\"vvod_chat\").value == \"Введите текст\") {document.getElementById(\"vvod_chat\").value = \":)\"; document.getElementById(\"vvod_chat\").style.color = \"#000\";}else{ document.getElementById(\"vvod_chat\").value=document.getElementById(\"vvod_chat\").value+\":)\"}'>
<img src='project/smails/1 (5).gif' style='cursor:pointer;' onclick='if (document.getElementById(\"vvod_chat\").value == \"Введите текст\") {document.getElementById(\"vvod_chat\").value = \":mut:\"; document.getElementById(\"vvod_chat\").style.color = \"#000\";}else{ document.getElementById(\"vvod_chat\").value=document.getElementById(\"vvod_chat\").value+\":mut:\"}'>
<img src='project/smails/1 (6).gif' style='cursor:pointer;' onclick='if (document.getElementById(\"vvod_chat\").value == \"Введите текст\") {document.getElementById(\"vvod_chat\").value = \":hmm:\"; document.getElementById(\"vvod_chat\").style.color = \"#000\";}else{ document.getElementById(\"vvod_chat\").value=document.getElementById(\"vvod_chat\").value+\":hmm:\"}'>
<img src='project/smails/1 (7).gif' style='cursor:pointer;' onclick='if (document.getElementById(\"vvod_chat\").value == \"Введите текст\") {document.getElementById(\"vvod_chat\").value = \":(\"; document.getElementById(\"vvod_chat\").style.color = \"#000\";}else{ document.getElementById(\"vvod_chat\").value=document.getElementById(\"vvod_chat\").value+\":(\"}'>
<img src='project/smails/1 (8).gif' style='cursor:pointer;' onclick='if (document.getElementById(\"vvod_chat\").value == \"Введите текст\") {document.getElementById(\"vvod_chat\").value = \":yxa:\"; document.getElementById(\"vvod_chat\").style.color = \"#000\";}else{ document.getElementById(\"vvod_chat\").value=document.getElementById(\"vvod_chat\").value+\":yxa:\"}'></span>
<div style='position:relative;'><div id='itr_filtr_3' style='background:#c6d9f1; padding:5px; border:1px solid #8ba2c2; display:none; position:absolute; left:110px; top:-20px;'>
<select name='0' id='sel_filtr_3' size='8'>
<option onclick='check_filtr_3(this);' style='width:150px;' value='0'>Написать всем";
$xxx3 = dbquery("SELECT * FROM okb_users where (STATE!='1') ORDER BY IO");
while($res3 = mysql_fetch_array($xxx3)){
	if ($res3['ID']!=='1') {
	$res_nam = $res3['IO'];
	echo "<option onclick='check_filtr_3(this);' style='width:150px;' value='".$res3['ID']."'>".$res_nam;
}}
echo "</select></div></div>";
echo "<br><span>Данная стадия развития предусматривает не сильно развитый функционал.<br>
<br><b style='color:blue; font-size:150%;'>СПРАВКА</b><br>
<b>1.</b> Чтобы вставить гиперссылку из названия подвкладки меню, просто введите название любой подвкладки в шапке меню (название самой вкладки не является гиперссылкой, только подвкладка. Вводить нужно 1 в 1 точность).
<br>
<b>2.</b> Чтобы вставить обычную гиперссылку, нажмите на URL.
<br>
<b>3.</b> Картинки загружаемые из локальной сети <b>НЕ</b> должны иметь названия с различными знаками \"|&\^%$?/ - это может привести к ошибке \"неверный формат.\"<br>Только Русские / Латинские буквы и цифры и пробелы и скобки ().
</span></td>

<td width='3%'></td></tr></tbody></table></td></tr></tbody></table>";
echo "<script type='text/javascript'>
var win_activ = 1;
var defolt_restext = '';
var doctit = 0;

setInterval('showContent2(\"mikron_chat_users.php?p1=".$user['ID']."&p2=\"+win_activ)', 3000);
setTimeout('document.getElementById(\"vvod_chat\").value=\"Введите текст\"; document.getElementById(\"vvod_chat\").style.color = \"#777\";', 1000);
setInterval('showContent(\"mikron_chat.php?p1=".$user['ID']."&p2=\"+document.getElementById(\"btn_filtr_4\").name)', 3000);

function check_filtr_3(obj){
	document.getElementById('itr_filtr_3').style.display='none';
	document.getElementById('btn_filtr_3').value=obj.innerHTML;
	document.getElementById('btn_filtr_3').name=obj.value;
}

function check_filtr_4(obj){
	document.getElementById('itr_filtr_4').style.display='none';
	document.getElementById('btn_filtr_4').value=obj.innerHTML;
	document.getElementById('btn_filtr_4').name=obj.value;
}

var curscrol = 0;
var curscrol2 = 0;

setInterval('scroll2()', 1500);
function cleare(){
	document.getElementById('vvod_chat').value='';
	document.getElementById('vvod_chat').innerText='';
	document.getElementById('vvod_chat').innerHTML='';
} 
function scroll_stop(){
	curscrol = 1;
} 
function scroll_start(){
	curscrol = 0;
} 
function scroll_stop2(){
	curscrol2 = 1;
} 
function scroll_start2(){
	curscrol2 = 0;
} 
function scroll2(){
	if (curscrol==0) { document.getElementById('contentBody_2').scrollTop +=5000;}
	if (curscrol2==0) { document.getElementById('contentBody_3').scrollTop +=5000;}
} 
function vote2(url) {

	var req = getXmlHttp();
	req.open('GET', url, true);
	req.send(null);
}

    function showContent(link) {  
  
        var cont_2 = document.getElementById('contentBody_2');  
    
        var http = createRequestObject();  
        if( http )   
        {  
            http.open('get', link);  
            http.setRequestHeader('Content-type', 'text/html; charset=windows-1251');
			http.onreadystatechange = function ()   
            {  
                if(http.readyState == 4)   
                {  
					if (defolt_restext!==http.responseText){
						if(win_activ==0){
							doctit = 1;
						}
					}
					if (doctit == 1){
						if (document.title.substr(0,5)=='(new)'){
							document.title=document.title.substr(5);
						}else{
							document.title='(new)'+document.title;
						}
					}
                    cont_2.innerHTML = http.responseText;  
					defolt_restext = http.responseText;
                }  
            }  
            http.send(null);      
        }  
        else   
        {  
            document.location = link;  
        }  
    } 
	
    function showContent2(link) {  
        var cont_3 = document.getElementById('contentBody_3');  
    
        var http = createRequestObject();  
        if( http )   
        {  
            http.open('get', link);  
            http.setRequestHeader('Content-type', 'text/html; charset=windows-1251');
			http.onreadystatechange = function ()   
            {  
                if(http.readyState == 4)   
                {  
                    cont_3.innerHTML = http.responseText;  
                }  
            }  
            http.send(null);      
        }  
        else   
        {  
            document.location = link;  
        }  
    } 

    function showContent3(link) {  
        var cont_4 = document.getElementById('contentBody_4');  
    	cont_4.innerHTML = 'проверка файла...';
		
        var http = createRequestObject();  
        if( http )   
        {  
            http.open('get', link);  
            http.setRequestHeader('Content-type', 'text/html; charset=windows-1251');
			http.onreadystatechange = function ()   
            {  
                if(http.readyState == 4)   
                {  
                    if (http.responseText=='2'){
						cont_4.innerHTML = 'файл не найден';
					}
                    if (http.responseText=='3'){
						cont_4.innerHTML = 'файл не верного формата';
					}
                    if (http.responseText=='1'){
						cont_4.innerHTML = '';
						if (document.getElementById(\"vvod_chat\").value == \"Введите текст\") {
							document.getElementById(\"vvod_chat\").value = \"[img]\"+document.getElementById(\"img_url_inp\").value+\"[:img]\"; 
							document.getElementById(\"vvod_chat\").style.color = \"#000\";
						}else{ 
							document.getElementById(\"vvod_chat\").value=document.getElementById(\"vvod_chat\").value+\"[img]\"+document.getElementById(\"img_url_inp\").value+\"[:img]\";
						}
						document.getElementById(\"img_url_inp\").value=\"\"; 
						document.getElementById(\"img_url\").style.display=\"none\";
					}
                }  
            }  
            http.send(null);      
        }  
        else   
        {  
            document.location = link;  
        }  
    } 
	
    function createRequestObject()   
    {  
        try { return new XMLHttpRequest() }  
        catch(e)   
        {  
            try { return new ActiveXObject('Msxml2.XMLHTTP') }  
            catch(e)   
            {  
                try { return new ActiveXObject('Microsoft.XMLHTTP') }  
                catch(e) { return null; }  
            }  
        }  
    }  

window.onfocus = function(evt){ doctit=0; if (document.title.substr(0,5)=='(new)'){ document.title=document.title.substr(5); }; win_activ=1;}
window.onblur = function(evt){ win_activ=0;}
</script>";
?>