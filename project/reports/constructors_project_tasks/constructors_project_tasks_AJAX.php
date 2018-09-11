<?php

error_reporting( 0 );

date_default_timezone_set("Asia/Krasnoyarsk");
require_once( "db.php" );
require_once( "functions.php" );

$indate = GetLastDayDate( GetSplitDate( $_POST['date'] ) ); 
$constr_arr = Array();

try
{
    $query = "
      SELECT
      okb_db_shtat.`NAME`,
      okb_db_shtat.ID_resurs
      FROM
      okb_db_resurs
    INNER JOIN okb_db_shtat ON okb_db_shtat.ID_resurs = okb_db_resurs.ID
    WHERE
    okb_db_shtat.ID_otdel = 91 
    AND 
    okb_db_shtat.BOSS <> 1 
    AND 
    okb_db_shtat.ID_resurs <> 0 
    ORDER BY
    okb_db_shtat.`NAME` ASC
    ";
    $stmt = $pdo->prepare( $query  );
    $stmt->execute();
}
  catch (PDOException $e) 
    {
      die("Can't get data: " . $e->getMessage());
    }  
  
while ($row = $stmt->fetch(PDO::FETCH_LAZY))
    $constr_arr [] = [ 'id' => $row['ID_resurs'] , 'name' => $row['NAME'] , 'tasks' => Array() ];

foreach ( $constr_arr AS &$const )
{
  $id = $const['id'] ;
  $name = $const['name'] ;

try
{
    $query = "
      SELECT 
      okb_db_itrzadan.TXT, 
      okb_db_itrzadan.STATUS, 
      okb_db_itrzadan.ID, 
      okb_db_itrzadan.DATE_PLAN, 
      okb_db_itrzadan.CDATE, 
      okb_db_itrzadan.KOMM1,
      okb_db_zak.NAME zak_name,
      okb_db_zak.DSE_NAME,
      okb_db_projects.name prj_name 
      FROM
      okb_db_itrzadan
      LEFT JOIN okb_db_zak ON okb_db_itrzadan.ID_zak = okb_db_zak.ID
      LEFT JOIN okb_db_projects ON okb_db_itrzadan.ID_proj = okb_db_zak.ID      
      WHERE
      okb_db_itrzadan.ID_users2 LIKE '%$id%'";
//      AND 
//      okb_db_itrzadan.DATE_PLAN > 20170101     ";

    $stmt = $pdo->prepare( $query  );
    $stmt->execute();
}
  catch (PDOException $e) 
    {
      die("Can't get data: " . $e->getMessage());
    }  

while( $row = $stmt->fetch(PDO::FETCH_LAZY) )
 {
    $taks_id = $row['ID'] ;
    $txt = $row['TXT'] ;
    $status = $row['STATUS'] ;
    $date = GetSplitDate( $row['DATE_PLAN'] );
    $beg_date = GetSplitDate( $row['CDATE'] );
    $comment = $row['KOMM1'];
    $zak_name = $row['zak_name'];
    $dse_name = $row['DSE_NAME'];
    $prj_name = $row['prj_name'];    


    if( 
        ( date_compare( $date, $indate ) == '>' ) 
        || 
        ( $status == 'Завершено' && date_compare( $date, $indate ) == '<' ) 
        ||
        ( $status == 'Аннулировано' )
       )
         continue;
       
    $const['tasks'][] = [ 
                        'id' => $taks_id , 
                        'name' => $txt , 
                        'status' => $status , 
                        'date' => $date, 
                        'beg_date' => $beg_date, 
                        'comment' => $comment,
                        'zak_name' => $zak_name,
                        'dse_name' => $dse_name,
                        'prj_name' => $prj_name
                       ];
 }
}

$table_inner = "<tr class='first'>
                <td width='15%' rowspan='2' class='Field'>ФИО</td>
                <td width='50%' rowspan='2' class='Field'>Задача</td>
                <td width='5%' rowspan='2' class='Field'>Состояние</td>                
                <td colspan='4' class='Field'>Недели</td>
                <td width='5%' rowspan='2' class='Field'>План<br>выполнения</td>
                <td rowspan='2' class='Field'>Примечание</td>
                </tr>
                <tr class='first'>
                <td width='2%' class='Field'>1</td>
                <td width='2%' class='Field'>2</td>
                <td width='2%' class='Field'>3</td>
                <td width='2%' class='Field'>4</td>
                </tr>";

$total_lines = 0 ;

foreach( $constr_arr AS $constr )
{
  $row_class = $total_lines % 2 ? '' : 'odd';
  
  $line = 1 ;
//  $name = $constr['name']. " : ". $constr['id']; 
  $name = $constr['name'];
  $tasks = $constr['tasks'];
  $row_count = count( $tasks );
  
  if( $row_count == 0 )
  {
    $table_inner .= "<tr class='nameField $row_class'><td class='Field'>$name</td>";
    $table_inner .= "<td colspan='8' class='Field'></td></tr>";
    $total_lines ++ ;                     
    continue ;                   
  }
  
  $table_inner .= "<tr data-id='$total_lines' class='nameField $row_class'><td rowspan= '$row_count' class='Field'>$name</td>";

  foreach( $tasks AS $task )
  {
    $id = $task['id'];
    $zak_name = '';
    $prj_name = '';    
    
    if( strlen( $task['zak_name'] ) )
    {
      $zak_name .= " ( зак. ".$task['zak_name']." : ".$task['dse_name']." )";
    }

    if( strlen( $task['prj_name'] ) )
    {
      $prj_name .= " ( пр. ".$task['prj_name']." )";
    }
    
    $task_name = $task['name'].$zak_name.$prj_name;
    $date = $task['date'];
    $beg_date = $task['beg_date'];
    $status = $task['status'];
    $comment = $task['comment'];

    $exec_bar = BuildBar( $task, $indate );

    if( $line != 1 )
      $table_inner .= "<tr data-parent_id='$total_lines' class='$row_class'>";


//$table_inner .= "<td class='Field'>$task_name : $id <br>$beg_date : $date : $indate
$table_inner .= "<td class='Field'>$task_name 
                   <td class='Field AC'>$status
                   </td>
                   <td class='Field ".($exec_bar[0]['class'])."'>".($exec_bar[0]['value'])."</td>
                   <td class='Field ".($exec_bar[1]['class'])."'>".($exec_bar[1]['value'])."</td>
                   <td class='Field ".($exec_bar[2]['class'])."'>".($exec_bar[2]['value'])."</td>
                   <td class='Field ".($exec_bar[3]['class'])."'>".($exec_bar[3]['value'])."</td>
                   <td class='Field AC'>$date
                   </td>
                   <td class='Field'>
                   <input data-id='$id' class='task_comment' value='$comment'></input>
                   </td>
                   </tr>
                   ";
  
    $line ++ ;                 
  }
  $total_lines ++ ;  
}

if( $dbpasswd == '' )
  echo $table_inner ;
    else
      echo conv( $table_inner );
	
?>
