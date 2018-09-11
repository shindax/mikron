<link rel='stylesheet' href='/project/MyJobs/css/myCSS.css' type='text/css'>
<script type="text/javascript" src="/project/MyJobs/js/uploadProjectFiles.js"></script>
<script type="text/javascript" src="/project/MyJobs/js/editProject.js"></script>

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
include "TaskByProjectFunctions.php";

$configpath = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."/config.php" );
require_once( $configpath );

global $files_path ;

$user_id = $user['ID'];
$user_res_id = GetUserResourceID( $user_id );

$right_groups = $user['ID_rightgroups'];
$right_groups = explode('|', $right_groups );

$can_work = 0 ;
$can_edit = '' ;
$edit_field_class = "rwField ntabg";

foreach( $right_groups AS $val )
        if( ( $val == '68' ) && ( $val == '1' ) )
            $can_work = 1 ;

$proj_id = $_GET['id'];

//$max_date_arr = explode( '.', $_GET['mindate'] );
//$max_date = $max_date_arr[2].'-'.$max_date_arr[1].'-'.$max_date_arr[0];
        
if( isset( $_POST['p0'] ))
{
  $name = $_POST['p0'] ;
  $descr =  $_POST['p1'] ;

  $beg_date_new = $_POST['p2'] ;
  $beg_date_arr = explode('-', $beg_date_new );
  $beg_date = $beg_date_arr[0].$beg_date_arr[1].$beg_date_arr[2];
  
  $end_date_new = $_POST['p3'] ;
  $end_date_arr = explode('-', $end_date_new );
  $end_date = $end_date_arr[0].$end_date_arr[1].$end_date_arr[2];

  $comment = $_POST['p4'] ;

  dbquery("UPDATE ".$db_prefix."db_itrzadan_statuses SET ID_edo='".$itrid."' where (ID='".$insert_id."') ");

// Change logging start

  $query ="
        SELECT * FROM okb_db_projects
        WHERE ID = $proj_id"; 

    $result = $mysqli -> query( $query );

    if( ! $result ) 
        exit("Ошибка обращения к БД №1 в файле EditProject.php : ".$mysqli->error); 
 
      $row = $result -> fetch_assoc();
      $beg_date_plan_old = $row['beg_date_plan'];
      $beg_date_plan_old = substr( $beg_date_plan_old , 0, 4 )."-".substr( $beg_date_plan_old , 4, 2 )."-".substr( $beg_date_plan_old , 6, 2 );
      
      $end_date_plan_old = $row['end_date_plan'];      
      $end_date_plan_old = substr( $end_date_plan_old , 0, 4 )."-".substr( $end_date_plan_old , 4, 2 )."-".substr( $end_date_plan_old , 6, 2 );      

      $today = date("Y-m-d H:i:s", time());

//      echo "<script>alert('ID : ".$proj_id."\\nUser id : ".$user_res_id."\\ntoday is :".$today."\\nbegin date old : ".$beg_date_plan_old."\\nend date old : ".$end_date_plan_old."\\nbegin date new : ".$beg_date_new."\\nend date new : ".$end_date_new."');</script>";

  $query ="INSERT INTO okb_db_project_orders_date_changes_history 
           VALUES(
           NULL,
           $proj_id,
           0,
           '$today',
           '$beg_date_plan_old',
           '$end_date_plan_old',
           '$beg_date_new',
           '$end_date_new',
           $user_res_id,
           $user_res_id,
           '$comment'
           )";

    $result = $mysqli -> query( $query );

    if( ! $result ) 
        exit("Ошибка обращения к БД №2 в файле EditProject.php : ".$mysqli->error); 


   echo "<script>$('#date_change_comment').val('');</script>";

// Change logging end

// Data update   

   $query ="
   UPDATE okb_db_projects SET name='$name', descr='$descr' , beg_date_plan='$beg_date' , end_date_plan='$end_date' WHERE ID = $proj_id"; 

    $result = $mysqli -> query( $query );

    if( ! $result ) 
        exit( "Ошибка обращения к БД №2 в файле EditProject.php : ".$mysqli->error ); 

// *******************************************************************************


    echo "<script>history.go(-1)</script>";
    
//    header( "Location: http://".$_SERVER['HTTP_HOST']."/index.php?do=show&formid=".$PROJECT_PAGE_ID ) ;        
   
}
    
    $query ="
        SELECT * FROM okb_db_projects pr
        INNER JOIN okb_db_resurs rc ON rc.ID=pr.ID_creator 
        WHERE pr.ID = $proj_id"; 

    $result = $mysqli -> query( $query );

    if( ! $result ) 
        exit("Ошибка обращения к БД №3 в файле EditProject.php : ".$mysqli->error); 
  
  if( $result -> num_rows )
  {
  
      $row = $result -> fetch_assoc();
      $name = $row['name'];
      $descr = $row['descr'];
      
      $beg_date_plan = $row['beg_date_plan'];
      $end_date_plan = $row['end_date_plan'];      

      if( $beg_date_plan != '' )
      {
        $beg_date_plan_str = substr( $beg_date_plan, 6, 2 ).'.'.substr( $beg_date_plan, 4, 2 ).'.'.substr( $beg_date_plan, 0, 4 );
        $beg_date_plan_for_input_str = substr( $beg_date_plan, 0, 4 ).'-'.substr( $beg_date_plan, 4, 2 ).'-'.substr( $beg_date_plan, 6, 2 );
      }

      if( $end_date_plan != '' )
      {
        $end_date_plan_str = substr( $end_date_plan, 6, 2 ).'.'.substr( $end_date_plan, 4, 2 ).'.'.substr( $end_date_plan, 0, 4 );
        $end_date_plan_for_input_str = substr( $end_date_plan, 0, 4 ).'-'.substr( $end_date_plan, 4, 2 ).'-'.substr( $end_date_plan, 6, 2 );      
      }

      
      $creator = $row['ID_creator'];
      $executor = $row['ID_executor'];
      $checker = $row['ID_checker'];
      if( $user_res_id != $creator )
      {
          $can_edit = 'disabled';
          $edit_field_class = 'rwField';
      }
      
      $image_path = "/project/$files_path/db_projects@filename/".$row['filename'];

      $img_present = 0;
      
      if( strlen( $row['filename'] ) )
      {
          $img = "<img src='uses/film.png' data-image='$image_path' data-id='$proj_id' class='view_file' title='Посмотреть файл'>
                <img src='uses/addf_img.png' data-id='$proj_id' class='load_file hidden' title='Загрузить файл'>
                <img src='uses/del.png' data-image='$image_path' data-id='$proj_id' class='del_file' title='Удалить файл'>";
      }
           else
      {


          $can_edit = '';
      
          if( $can_edit == 'disabled')
            $img = "<img src='uses/film.png' data-image='$image_path' data-id='$proj_id' class='view_file hidden' title='Посмотреть файл'>
                <img src='uses/addf_dis.png' data-id='$proj_id' class='load_file_dis' title='Загрузить файл'>
                <img src='uses/del.png' data-image='$image_path' data-id='$proj_id' class='del_file hidden' title='Удалить файл'>";
             else
            $img = "<img src='uses/film.png' data-image='$image_path' data-id='$proj_id' class='view_file hidden' title='Посмотреть файл'>
                <img src='uses/addf_img.png' data-id='$proj_id' class='load_file' title='Загрузить файл'>
                <img src='uses/del.png' data-image='$image_path' data-id='$proj_id' class='del_file hidden' title='Удалить файл'>";
             
      }
  }


if( $can_edit == 'disabled')
{
 $beg_date_plan = $beg_date_plan_str ;
 $end_date_plan = $end_date_plan_str ;
}
  else
  {
   $max_date_str = $end_date_plan_str;
   $min_date_str = $beg_date_plan_str;
   $max_date = $end_date_plan_for_input_str;
   $min_date = $beg_date_plan_for_input_str;
      
   $beg_date_plan = "<input name='p2' id='edit_project_beg_date' data-proj_beg='$beg_date_plan_for_input_str' type='date' title='Дата начала проекта\nмаксимальная дата : $max_date_str' max= '$max_date' class='one_row_data' class='one_row_data' value='$beg_date_plan_for_input_str' $can_edit/>";  
   $end_date_plan = "<input name='p3' id='edit_project_end_date' data-proj_beg='$beg_date_plan_for_input_str' type='date' title='Дата окончания проекта\nМинимальная дата : $min_date_str' min='$min_date' class='one_row_data' value='$end_date_plan_for_input_str' $can_edit/>";
  }


echo "<h1>Редактирование проекта</h1>";
//echo "<a href='http://".$_SERVER['HTTP_HOST']."/index.php?do=show&formid=".$PROJECT_PAGE_ID."'>К проектам</a><br><br>";

echo "<a class='link' onclick='window.history.back();'>К списку</a><br><br>";

echo "</form>\n";
echo "<form method='post'>";

echo "<table class='tbl project_edit_table' border='1' cellpadding='0' cellspacing='0'>\n";
echo "<tr class='first'>\n";
echo "<td width='250'></td>";
echo "<td></td>";
echo "</tr>\n";

echo "<tr><td class='Field first'><b>Название проекта</td><td class='$edit_field_class'>
<input class='my_input' id='proj_name' name='p0' value='$name' $can_edit></td>";
echo "</td></tr>";

echo "<tr><td class='Field first'><b>Описание проекта</td><td class='$edit_field_class'>
<textarea id='proj_descr' rows='4' name='p1' $can_edit>$descr</textarea></td>";
echo "</td></tr>";

echo "<tr><td class='Field first'><b>Дата начала:</b></td><td class='rwField'>";
echo $beg_date_plan ;
echo "</td></tr>\n";

echo "<tr><td class='Field first'><b>Дата окончания:</b></td><td class='rwField'>";
echo $end_date_plan ;
echo "</td></tr>\n";


echo "<tr><td class='Field first'><b>Автор:</b></td><td class='Field'>";
echo GetPerson( $creator );
echo "</td></tr>";

echo "<tr><td class='Field first'><b>Руководитель:</b></td><td class='Field'>";
echo GetPerson( $executor );
echo "</td></tr>";

echo "<tr><td class='Field first'><b>Контролер:</b></td><td class='Field'>";
echo GetPerson( $checker );
echo "</td></tr>";

echo "<tr><td class='Field first'><b>Изображение:</b></td><td class='Field'>";
echo $img; 
echo "</td></tr>";


echo  "</table>";

echo "<table style='width: 700px;'><tr><td><div class='label'>Причина изменения дат</div><input name='p4' id='date_change_comment' type='text' value='' $can_edit /></td></tr></table>";

echo "<br><table style='width: 700px;'><tr><td style='text-align: right;'><input type='submit' value='&nbsp;Сохранить&nbsp;' $can_edit></td></tr></table>";
echo "</form>";

// Элемент для загрузки файлов
echo '<input id="upload_file_input" type="file" accept=".jpg,image/*">' ;
// Элемент для AJAX, ожидание ответа от сервера
echo '<img id="loadImg" src="project/img/loading_2.gif" />';


$query = "SELECT * FROM `okb_db_project_orders_date_changes_history` WHERE id_project = $proj_id ORDER BY id DESC";
$result = $mysqli -> query( $query );

echo "<br><br><h3>История изменения дат</h3>";
echo "<table class='tbl project_change_history_table' border='1' cellpadding='0' cellspacing='0'>\n";
echo "<tr class='first'>\n";
echo "<td width='6%'>Дата изменения</td>";
echo "<td width='6%'>Дата начала исх.</td>";
echo "<td width='6%'>Дата окончания исх.</td>";
echo "<td width='6%'>Дата начала нов.</td>";
echo "<td width='6%'>Дата окончания нов.</td>";
echo "<td width='10%'>Инициатор изменения</td>";
echo "<td>Причина изменения</td>";
echo "</tr>";

if( ! $result ) 
  exit("Ошибка обращения к БД в файле : ". __FILE__ ." строка : ".__LINE__." ".$mysqli->error); 

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
            
              echo "<tr>";
              echo "<td class='field'>$ch_day</td>";
              echo "<td class='field'>$beg_date_old</td>";
              echo "<td class='field'>$end_date_old</td>";

              $changed = $beg_date_old == $beg_date_new ? '' : 'changed';
              echo "<td class='field $changed'>$beg_date_new</td>";
              
              $changed = $end_date_old == $end_date_new ? '' : 'changed';              
              echo "<td class='field $changed'>$end_date_new</td>";
              
              echo "<td class='field'>".GetPerson( $user_res_id )."</td>";
              echo "<td class='field'>$comment</td>";
              echo "</tr>\n";
            }



echo  "</table>";
?>

