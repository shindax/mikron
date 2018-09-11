<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>
<link rel="stylesheet" type="text/css" href="./project/print/styles.css">

<script type="text/javascript" >
	function add_img(obj_id){
		var btnUpload=document.getElementById(obj_id);
		new AjaxUpload(btnUpload, {
			action: 'upload-file_MTK_perehod.php?usid='+<?php Global $user; echo $user['ID'];?>,
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(jpg|pdf|png|bmp|gif)$/.test(ext))){
					alert ("неверный формат изображения или название файла.")
					return false;
				}
			},
			onComplete: function(file, response){
				if(response=="3"){
					alert ("неверный формат изображения или название файла.")
				}
				if(response=="2"){
					alert ("Ошибка загрузки.")
				}
				if((response!=="3")&&(response!=="2")){
					// произвести действие после проверки файла
					addrow_perehod_img(response, obj_id.substr(7));
				} 
			}
		});
	var uplfls = document.getElementsByName("uploadfile").length;
	for (var aa = 1; aa < uplfls; aa++){
		document.getElementsByName("uploadfile")[aa-1].remove();
	}
	};
</script>

<?php
	if (!defined("MAV_ERP")) { die("Access Denied"); }
 Global $user;
// переменные по стилям
$fld = " class='Field'";
$rfld = " class='rwField ntabg'";
$stl_s = " style='";
$stl_e = "'";
$stl_br = "border-right:3px solid black;";

/////////////////////////////////////////////////////
// карта эскизов
/////////////////////////////////////////////////////
$result3 = dbquery("SELECT * FROM okb_db_zakdet where ID='".$render_row['ID_zakdet']."' ");
$name3 = mysql_fetch_array($result3);
if ($name3['MTK_OK']=='0') {
	echo "<table width='1218px'><tbody>
	<tr>
	<td width='150px'><span onclick='addrow_perehod_row(".$render_row['ID'].");' class='upload'>Добавить переход</span></td><td".$fld.$stl_s."width:120px;".$stl_e.">
	<div id='upload_".$render_row['ID']."' name='upload' class='upload'><span onmouseover='add_img(this.parentNode.id)'>Добавить эскиз</span>
	</div></td><td>Карта эскизов:"; 
	$result_4 = dbquery("SELECT * FROM okb_db_mtk_perehod_img where ID_operitems='".$render_row['ID']."' order by TID ");
	while ($name_4 = mysql_fetch_array($result_4)){
		echo "<img style='float:none;cursor:pointer;' src='project/img/img.png' onclick='
		document.getElementById(\"big_img_per\").style.display=\"block\";
		document.getElementById(\"big_img_per_del\").style.display=\"block\";
		document.getElementById(\"big_img_per_del\").innerHTML=\"<img onclick=check_del_img(".$render_row['ID'].",`".$name_4['IMG']."`); src=project/img/perehod_img_del.png style=cursor:pointer;>\";
		document.getElementById(\"big_img_per\").innerHTML=\"<img onclick=hide_bigimg(); style=max-width:750px;max-height:500px;cursor:pointer;border-width:6px;border-style:solid;border-color:#000;  src=project/63gu88s920hb045e/db_mtk_perehod@IMAGES/".$name_4['IMG'].">\"'>";
	}
	echo "</td></tr></tbody></table></span>";
}else{
	echo "<table width='1218px'><tbody>
	<tr><td>Карта эскизов:"; 
	$result_4 = dbquery("SELECT * FROM okb_db_mtk_perehod_img where ID_operitems='".$render_row['ID']."' order by TID ");
	while ($name_4 = mysql_fetch_array($result_4)){
		echo "<img style='float:none;cursor:pointer;' src='project/img/img.png' onclick='
		document.getElementById(\"big_img_per\").style.display=\"block\";
		document.getElementById(\"big_img_per\").innerHTML=\"<img onclick=hide_bigimg(); style=max-width:750px;max-height:500px;cursor:pointer;border-width:6px;border-style:solid;border-color:#000;  src=project/63gu88s920hb045e/db_mtk_perehod@IMAGES/".$name_4['IMG'].">\"'>";
	}
	echo "</td></tr></tbody></table></span>";
}

// запомнить кто добавил картинку в переход операции МТК
echo "<script type='text/javascript'>
function check_del_img(id_oper, name_img){
	if(confirm('Вы действительно хотите удалить эскиз?')){
		document.getElementById(\"curloading\").style.display=\"block\"; 
		delrow_perehod_img(id_oper, name_img);
	}
}
function hide_bigimg(){
	document.getElementById(\"big_img_per\").style.display=\"none\";
	document.getElementById(\"big_img_per_del\").style.display=\"none\";
}
function vote2(obj){
	var req = getXmlHttp();
	req.open('GET', 'MTK_perehod_change.php?id='+obj+'&p1=".$user['ID']."');
	req.send(null);
}

function addrow_perehod_img(val, obj_id){
document.getElementById(\"curloading\").style.display=\"block\"; 
	var req = getXmlHttp();

	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.status == 200) {
				if (req.responseText !== 'dublicat'){
					location.href = document.location;
				}else{
					alert ('Изображение с таким именем уже существует.');
				}
			}else{
				alert ('Ошибка добавления изображения');
			}
		}
	}
	req.open('GET', 'MTK_perehod_add_img.php?id='+obj_id+'&val='+val+'&p1=".$user['ID']."', true);
	req.send(null);
	
}
function addrow_perehod_row(id_op){
document.getElementById(\"curloading\").style.display=\"block\"; 
	var req = getXmlHttp();

	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.status == 200) {
				location.href = document.location;
			}else{
				alert ('Ошибка добавления перехода');
			}
		}
	}
	req.open('GET', 'MTK_perehod_add_row.php?id='+id_op+'&p1=".$user['ID']."', true);
	req.send(null);
	
}

function delrow_perehod_img(id_oper, name_img){
	var req = getXmlHttp();

	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.status == 200) {
				location.href = document.location;
			}else{
				alert ('Ошибка удаления перехода');
			}
		}
	}

	req.open('GET', 'MTK_perehod_del_img.php?id='+id_oper+'&p1='+name_img, true);
	req.send(null);
}

</script>";
?>