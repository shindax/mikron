<?php
  $dblocation = "localhost";   
  $dbname = "okbdb"; 
  $dbuser = "root"; 
  $dbpasswd = ""; 
  $mysqli = new mysqli($dblocation, $dbuser, $dbpasswd, $dbname); 
  
  if ( mysqli_connect_errno() ) 
        exit("Connection error in db_config .$mysqli->error"); 
 
  $mysqli->query("SET NAMES 'cp1251'"); 
  ?>
