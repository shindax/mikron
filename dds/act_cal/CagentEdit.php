<link rel='stylesheet' href='project/act_cal/css/activeCalendar.css' type='text/css'>
<script type="text/javascript" src="project/act_cal/js/cagentEdit.js"></script>

<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("db_config.php");
require_once("CommonFunctions.php");

global $user ;
global $mysqli;

$res_user_id = 1 ; // GetUserResourceID( $user['ID'] );

echo "<script>var res_user_id = $res_user_id ;</script>";

$str  = "<H2>Активный календарь</H2>";
$str .= "<H4>Добавление контрагентов</H4>";

$str .= "<div>";

$str .= "<div class='addline'><a class='alink'>Добавить</a></div>";

$str .=        "<table width='800px' class='rdtbl tbl' id='tbl'>
                <tr class='first'>
                <td width='4%'>№</td>
                <td>Имя</td>
                </tr>";

    $query ="SELECT * FROM okb_db_clients WHERE 1 ORDER BY ID"; 

    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit("Ошибка обращения к БД в функции CagentEdit.php Query :<br>$query<br>".$mysqli->error); 
  
  $line = 1 ;
  if( $result -> num_rows )
  {
      while ( $row = $result -> fetch_object() )
      {
        $cagent_name = $row -> NAME ;
        $cagent_id = $row -> ID ;

        $str .= "<tr><td class='field AC'>$line</td><td class='field AL'><input id='inp_$cagent_id' data-id='$cagent_id' class='inp inp_name' type='text' value='$cagent_name'/></td></tr>";
        $line ++ ;
      }
  }


$str .= "</div>";
echo $str ;
?>