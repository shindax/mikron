<?php
  $dblocation = "127.0.0.1"; 
  $dbname = "okbdb"; 
  $dbuser = "okbmikron"; 
  $dbpasswd = "fm2TU9IMTB_hnI0Z"; 
   $mysqli = new mysqli($dblocation, $dbuser, $dbpasswd, $dbname); 
  
  if ( mysqli_connect_errno() ) 
        exit("Connection error in db_config .$mysqli->error"); 
 
  $mysqli->query("SET NAMES 'cp1251'"); 
  ?>
