<style>
.project_edit_table, .project_change_history_table
{
  border-collapse: collapse; 
  border: 0px solid black; 
  text-align: left; 
  color: #000; 
  width: 700px;
}
.project_change_history_table
{
  width: 1200px;
  table-layout: fixed !IMPORTANT;
}
.changed
{
  background: cyan ;
}

#date_change_comment
{
  width: 100%;
}

.label
{
  margin: 5px 0 !IMPORTANT;
  padding : 0 0 !IMPORTANT;
}

</style>
<?php
require_once("CommonFunctions.php");
//error_reporting( E_ALL );

global $user_id, $itrid;

$query = "SELECT * FROM `okb_db_project_orders_date_changes_history` WHERE `id_order` = $itrid ORDER BY id DESC";
$result = $mysqli -> query( $query );


$str .=   "<div id='dialog-confirm' title='Изменение даты'>
           <p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>Комментарий к изменению даты</p>
           <input id='date_change_comment'></input>
           </div>";


$str .=  "<br><br><h3>История изменения дат</h3>";
$str .=  "<table class='tbl' id='order_change_history_table' border='1' cellpadding='0' cellspacing='0'>\n";
$str .=  "<tr class='first'>\n";
$str .=  "<td width='6%'>Дата изменения</td>";
$str .=  "<td width='6%'>Дата начала исх.</td>";
$str .=  "<td width='6%'>Дата окончания исх.</td>";
$str .=  "<td width='6%'>Дата начала нов.</td>";
$str .=  "<td width='6%'>Дата окончания нов.</td>";
$str .=  "<td width='10%'>Инициатор изменения</td>";
$str .=  "<td>Причина изменения</td>";
$str .=  "</tr>";

if( ! $result ) 
  exit("Ошибка обращения к БД в файле : ". __FILE__ ." строка : ".__LINE__." ".$mysqli->error ); 


        if( $result -> num_rows )
            while( $row = $result -> fetch_object() )
            {
              $ch_day = date_format( date_create( $row -> change_date ),"d.m.Y");
              
              $beg_date_old = date_format( date_create( $row -> begin_date_old ),"d.m.Y");
              $end_date_old = date_format( date_create( $row -> end_date_old ),"d.m.Y"); 
              $beg_date_new = date_format( date_create( $row -> begin_date_new ),"d.m.Y") ;
              $end_date_new = date_format( date_create( $row -> end_date_new ),"d.m.Y"); 
              $id_request = $row -> id_request ;
              $comment = $row -> comment ;
              $user_name = GetPerson( $row -> id_request );
            
              $str .=  "<tr>";
              $str .=  "<td class='field'>$ch_day</td>";
              $str .=  "<td class='field'>$beg_date_old</td>";
              $str .=  "<td class='field'>$end_date_old</td>";

              $changed = $beg_date_old == $beg_date_new ? '' : 'changed';
              $str .=  "<td class='field $changed'>$beg_date_new</td>";
              
              $changed = $end_date_old == $end_date_new ? '' : 'changed';              
              $str .=  "<td class='field $changed'>$end_date_new</td>";
              
              $str .=  "<td class='field'>$user_name</td>";
              $str .=  "<td class='field'>$comment</td>";
              $str .=  "</tr>\n";
            }

$str .=   "</table>";

echo $str ;

?>