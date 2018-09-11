<?php
$usid = $_GET['usid'];
$uploaddir = './project/63gu88s920hb045e/db_MTK_perehod@IMAGES/'; 
$file = $uploaddir.mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."_".$usid.substr($_FILES['uploadfile']['name'],strpos($_FILES['uploadfile']['name'],'.'),strlen($_FILES['uploadfile']['name'])-1); 
$file2 = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."_".$usid.substr($_FILES['uploadfile']['name'],strpos($_FILES['uploadfile']['name'],'.'),strlen($_FILES['uploadfile']['name'])-1);

$ext = substr($_FILES['uploadfile']['name'],strpos($_FILES['uploadfile']['name'],'.'),strlen($_FILES['uploadfile']['name'])-1); 
$filetypes = array('.pdf','.jpg','.gif','.bmp','.png','.PDF','.JPG','.BMP','.GIF','.PNG');
 
if(!in_array($ext,$filetypes)){
	echo "3";}
else{ 
	if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) { 
	  echo $file2;
	} else {
		echo "2";
	}
}
 

?>