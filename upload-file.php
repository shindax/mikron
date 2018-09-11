<?php
$uploaddir = './uploads/'; 
$file = $uploaddir . basename($_FILES['uploadfile']['name']); 
 
$ext = substr($_FILES['uploadfile']['name'],strpos($_FILES['uploadfile']['name'],'.'),strlen($_FILES['uploadfile']['name'])-1); 
$filetypes = array('.pdf','.jpg','.gif','.bmp','.png','.PDF','.JPG','.BMP','.GIF','.PNG');
 
if(!in_array($ext,$filetypes)){
	echo "3";}
else{ 
	if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) { 
	  echo "1"; 
	} else {
		echo "2";
	}
}
 

?>