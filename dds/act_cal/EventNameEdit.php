<link rel='stylesheet' href='project/act_cal/css/activeCalendar.css' type='text/css'>
<script type="text/javascript" src="project/act_cal/js/eventNameEdit.js"></script>

<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("db_config.php");
require_once("CommonFunctions.php");

global $user ;
global $mysqli;

$res_user_id = 1; // GetUserResourceID( $user['ID'] );

echo "<script>var res_user_id = $res_user_id ;</script>";

$str  = "<H2>�������� ���������</H2>";
$str .= "<H4>������������ �������</H4>";

$str .= "<div>";

$str .= "<div class='addline'><a class='alink'>��������</a></div>";

$str .=        "<table width='800px' class='rdtbl tbl' id='tbl'>
                <tr class='first'>
                <td width='4%'>�</td>
                <td>������������</td>
                <td>�����������</td>                
                </tr>";

    $query ="SELECT * FROM okb_db_sobitiya WHERE 1 ORDER BY ID"; 

    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit("������ ��������� � �� � ������� EventNameEdit.php Query :<br>$query<br>".$mysqli->error); 
  
  $line = 1 ;
  if( $result -> num_rows )
  {
      while ( $row = $result -> fetch_object() )
      {
        $event_name = $row -> NAME ;
        $event_value = $row -> VALUE ;
        $event_id = $row -> ID ;

        $sel = "<select id='sel_$event_id' data-id='$event_id' class='inp inp_type' >"; 
        
        if( $event_value == '������' )
          {
             $sel .= "<option class='inp' selected>������</option><option class='inp'>������</option></select>";
          }
          else
              $sel .= "<option class='inp'>������</option><option class='inp' selected>������</option></select>";
        
        $str .= "<tr><td class='field AC'>$line</td><td class='field AL'><input id='inp_$event_id' data-id='$event_id' class='inp inp_name' type='text' value='$event_name'/></td><td class='field AL'>$sel</td></tr>";
        $line ++ ;
      }
  }


$str .= "</div>";
echo $str ;
?>