<link rel='stylesheet' href='/project/MyJobs/css/myCSS.css' type='text/css'>
<script type="text/javascript" src="/project/MyJobs/js/treeView.js"></script>    
<script type="text/javascript" src="/project/MyJobs/js/myJobs.js"></script>    
    
<?php
include "TaskByProjectFunctions.php";

if( isset($_GET['projid']))
    $proj_id = $_GET['projid'];

if( isset($_GET['ordid']))
{
    $intro_msg = "<H2>Редактирование задания № ".$_GET['ordid']."</H2>";
    $ord_id = $_GET['ordid'];

        $query ="
        SELECT ID_proj FROM okb_db_itrzadan 
        WHERE ID = $ord_id"; 

    $result = $mysqli -> query( $query );

    if( ! $result ) 
      exit("Ошибка обращения к БД №1 в файле CreateProjectOrder.php : ".$mysqli->error); 
  
    if( $result -> num_rows )
      $row = $result -> fetch_assoc();
   
    $proj_id = $row['ID_proj'];    
    
}
 else 
{
    $intro_msg = "<H2>Создание нового задания к проекту.</H2>";
}


    $query ="
        SELECT * FROM okb_db_projects pr
        INNER JOIN okb_db_resurs rc ON rc.ID=pr.ID_creator 
        WHERE pr.ID = $proj_id"; 

    $result = $mysqli -> query( $query );

    if( ! $result ) 
      exit("Ошибка обращения к БД №2 в файле CreateProjectOrder.php : ".$mysqli->error); 
  
    if( $result -> num_rows )
      $row = $result -> fetch_assoc();

    $str .= "<table class='tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 600px;' border='1' cellpadding='0' cellspacing='0'>\n";

    $str .= "<tr class='first'>";
    $str .= "<td colspan='2' class='field AС'><b>Информация о проекте</b></td>";
    $str .= "</tr>";

    $str .= "<tr>";
    $str .= "<td class='field first AL'>Название проекта</td>";
    $str .= "<td class='field AC'><b>{$row['name']}</b></td>";
    $str .= "</tr>";
    
    $str .= "<tr>";    
    $str .= "<td class='field first AL'>Автор</td>";    
    $str .= "<td class='field AC'>{$row['NAME']}</td>";        
    $str .= "</tr>";
    
    $str .= "<tr>";    
    $str .= "<td class='field first AL'>Дата начала план</td>";
    $str .= "<td class='field AC'>{$row['beg_date_plan']}</td>";
    $str .= "</tr>";    

    $str .= "<tr>";        
    $str .= "<td class='field first AL'>Дата начала факт</td>";    
    $str .= "<td class='field AC'>{$row['beg_date_fact']}</td>";    
    $str .= "</tr>";
    
    $str .= "<tr>";    
    $str .= "<td class='field first AL'>Дата окончания план</td>";
    $str .= "<td class='field AC'>{$row['end_date_plan']}</td>";    
    $str .= "</tr>";
    
    $str .= "<tr>";    
    $str .= "<td class='field first AL'>Дата окончания факт</td>";        
    $str .= "<td  class='field AC'>{$row['end_date_fact']}</td>";    
    $str .= "</tr>";
    
    $str .= "<tr>";    
    $str .= "<td class='field first AL'>Состояние</td>";
    $str .= "<td class='field AC'>{$row['STATUS']}</td>";    
    $str .= "</tr>";
    
    $str .= "<tr>";    
    $str .= "<td class='field first AL'>Примечание</td>";    
    $str .= "<td class='field AC'>{$row['note']}</td>";        
    $str .= "</tr>";

    $str .= "</table>";


$intro_msg .= $str ;
echo $intro_msg ;

?>