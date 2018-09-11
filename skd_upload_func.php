<html>
<head>
  <title>resault</title>
</head>
<body>
<?php
   if($_FILES["filename"]["size"] > 1024*3*1024)
   {
     echo ("veri high size in MB");
     exit;
   }
   // Проверяем загружен ли файл
   if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
   {
     // Если файл загружен успешно, перемещаем его
     // из временной директории в конечную
     move_uploaded_file($_FILES["filename"]["tmp_name"], "./project/63gu88s920hb045e/db_files_SCA/source/".$_FILES["filename"]["name"]);
	 echo "all good";
   } else {
      echo("ERROR upload");
   }
?>
</body>
</html>