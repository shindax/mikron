<script type="text/javascript" src="/project/MyJobs/js/my_autocomplete.js"></script>
<script type="text/javascript" src="/project/MyJobs/js/insertOneRowAfterAJAX.js"></script>

<link rel='stylesheet' href='/project/MyJobs/css/jquery-ui.css' type='text/css'>
<link rel='stylesheet' href='/project/MyJobs/css/myCSS.css' type='text/css'>
<link rel='stylesheet' href='/project/MyJobs/css/my_autocomplete.css' type='text/css'>

<?php
error_reporting( E_ALL );
//error_reporting( 0 );

$configpath = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."/config.php" );
require_once( $configpath );

require_once("CommonFunctions.php");
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

global $user, $EDIT_PROJECT_PAGE_ID;

$right_groups = $user['ID_rightgroups'];
$right_groups = explode('|', $right_groups );
$uder_id = $user['ID'];

// Для скрытия кнопок 'Добавить'
$can_add = 0 ;
foreach( $right_groups AS $val )
        if( ( $val == '68' ) || ( $val == '1' ) )
            $can_add = 1 ;


echo "<script>var can_add = $can_add ;
       var edit_order_page = $PROJECT_ORDER_DETAIL_PAGE_ID ;
       var edit_project_page = $EDIT_PROJECT_PAGE_ID ;
      </script>";
      
// *****************************************************************************************************

function MakePie( $done )
{
  if( !$done )
    $done = 0 ;
    
  $img_name = "pie_".$done."_perc";

      $w = 16;
      $h = 16;
      $img = imagecreatetruecolor($w, $h);
      imagesavealpha($img,true);    // альфа-канал для прозрачности
      imagefill($img ,0,0,IMG_COLOR_TRANSPARENT);

      $colors = [
                        imagecolorallocate($img, 0, 255, 0),
                        imagecolorallocate($img, 150, 150, 150)
                      ];

          imagefilledarc($img, $w/2 , $h/2, $w, $h, $de, $de += round($done/100 * 360), $colors[0], IMG_ARC_PIE );
          imagefilledarc($img, $w/2, $h/2, $w, $h, $de, $de += round((100 - $done)/100 * 360), $colors[1], IMG_ARC_PIE );

      if( ! file_exists ( $_SERVER['DOCUMENT_ROOT']."/uses/pie/".$img_name.".png" ) )
      	imagepng( $img, $_SERVER['DOCUMENT_ROOT']."/uses/pie/".$img_name.".png");
      return "<img style='margin: 0px 10px 0px 0px' src='/uses/pie/".$img_name.".png' />";
}


function GetProjectOrders( $proj_id )
{
    global $mysqli;    
	
    $tempArr = array();
    $proj_orders = array();

// Получение заданий из БД    
    $query =" SELECT 
              ID, TXT, ID_users, ID_users2, ID_users3, ID_proj , 
              DATE_FORMAT( DATE_PLAN, '%d.%m.%Y' ) date_of_perf_plan, 
              DATE_FORMAT( STARTDATE, '%d.%m.%Y' ) date_of_beg_plan,               
              STATUS, ID_edo, KOMM1,comp_perc 
              FROM okb_db_itrzadan 
              WHERE ID_proj=$proj_id ORDER BY ID"; 
    $project_orders = $mysqli -> query( $query );
  
    if( ! $project_orders ) 
      exit("Ошибка обращения к БД №1 в функции GetProjectOrders. Файл TaskByProjectFunctions.php : ".$mysqli->error); 

  
  if( $project_orders -> num_rows )
      while ( $projects_row = $project_orders -> fetch_assoc())
        {

			GetDateByStatus( $projects_row['ID'], $projects_row['STATUS']);
            GetDuration( $projects_row['date_of_beg_plan'], $projects_row['date_of_perf_plan']);
			
            $tempArr[] = array( 
                           'DivID'                             => $projects_row['ID'] , 
                           'DivName'                           => $projects_row['TXT'], 
                           'DivParentID'                       => $projects_row['ID_edo'], 
                           'DivType'                           => 'div_type_project_order', 
                           'DivDescr'                          => $projects_row['KOMM1'],
                           'comp_perc'                         => $projects_row['comp_perc'],
                           'DivFileName'                       => 0, 
                           'TotalTaskCount'                    => 0, 
                           'CompletedTaskCount'                => 0, 
                           'project_id'                        => $projects_row['ID_proj'], 
                           'project_order_creator'             => $projects_row['ID_users'], 
                           'project_order_executor'            => $projects_row['ID_users2'], 
                           'project_order_checker'             => $projects_row['ID_users3'], 
                           'project_order_date_of_beg_plan'    => $projects_row['date_of_beg_plan'], 
                           'project_order_date_of_perf_plan'   => $projects_row['date_of_perf_plan'], 
                           'project_order_date_of_perf_fact'   => GetDateByStatus( $projects_row['ID'], $projects_row['STATUS']), 
                           'project_order_status'              => $projects_row['STATUS'], 
                           'project_order_duration'            => GetDuration( $projects_row['date_of_beg_plan'], $projects_row['date_of_perf_plan']), 
                           'childs' => array()
                         );
        }

		
// Перенос заданий верхнего уровня в конечный массив
  foreach( $tempArr AS $key => $value )
  {
      if( $value['DivParentID'] == 0 )
      {
          $proj_orders[] = $value ;
          unset( $tempArr[$key] );
      }
  }

  $count = 0 ; 

  while( count( $tempArr ) && $count ++ < 10000 )
    foreach( $tempArr AS $key => $value )
      if( ( $index = FindVal( $proj_orders, $value, 'DivID', 'DivParentID')) !== NULL )
          unset( $tempArr[$key] );


// Если потеряна родительская строка
      if( $count >= 10000 )
      {
          echo "TaskByProjectFunctions 'while' overflow. ".count( $tempArr )." records loosed parents. Their id's:";
            foreach( $tempArr AS $key => $value )
              echo $value['DivID'].'<br>';
      }
  
    return $proj_orders ;
}

function GetProjectsList( $project_id = 0 )
{
  global $mysqli;
  $arr = array();
  
  if( $project_id == 0 )
     $where = " WHERE ISNULL(`STATUS`) ";
         else
            $where = " WHERE ID=$project_id  ";
     
//  SELECT * FROM okb_db_projects  WHERE `STATUS` <> 'TEST' ORDER BY name"; 
      
  $query ="SELECT * FROM okb_db_projects ".$where." ORDER BY name"; 
  
  $projects = $mysqli -> query( $query );
  
  if( ! $projects ) 
      exit("Ошибка обращения к БД №1 в функции GetProjectList. Файл TaskByProjectFunctions.php : ".$mysqli->error); 
 

  // Если имеется хотя бы одна запись, выводим список 
  if( $projects -> num_rows )
      while ( $projects_row = $projects -> fetch_assoc())
  {
          $proj_id = $projects_row['ID'];
          $descr = $projects_row['descr'];          
//          $prefix = $projects_row['prefix'] ;
          $project_name = $projects_row['name'] ;
          $project_file_name = $projects_row['filename'] ;
          		  
          $child_arr = GetProjectOrders( $proj_id );
          
          $beg_date_plan = $projects_row['beg_date_plan'];
          $beg_date_plan = substr( $beg_date_plan,6,2 ) .'.'.substr( $beg_date_plan,4,2 ).'.'.substr( $beg_date_plan,0,4 );
          $end_date_plan = $projects_row['end_date_plan'];
          $end_date_plan = substr( $end_date_plan,6,2 ) .'.'.substr( $end_date_plan,4,2 ).'.'.substr( $end_date_plan,0,4 );
          $arr[] = array( 
                        'DivID' => $proj_id, 
//                        'DivPrefix' => $prefix, 
                        'DivName' => $project_name, 
                        'DivFileName' => $project_file_name,
                        'DivDescr' => $descr,
                        'DivType' => 'div_type_project',
                        'project_creator' => $projects_row['ID_creator'],
                        'project_executor' => $projects_row['ID_executor'],              
                        'project_checker' => $projects_row['ID_checker'],
                        'childs' => $child_arr,
                        'executor_list' => array(),
                        'checker_list' => array(),              
                        'FromDate' => $beg_date_plan ,
                        'ToDate' => $end_date_plan,
                        'MaximalOrderDate' => '',              
//                        'project_duration' => GetDuration( $beg_date_plan , $end_date_plan ),
//                        'project_duration' => 0,
                        'TotalDaysInTasksCount'             => 0, 
                        'CompletedDaysInTasksCount'         => 0, 
                  );
  }
 
  return $arr ;
}

function GetRow( 
                    $level , 
                    $user_id,
                    $proj_id,
                    $div_type,
                    $name, 
                    $filename,                    
                    $div_id, 
                    $row_class, 
                    $in_name, 
                    $row_id, 
                    $style,  
                    $date_of_beg_plan,
                    $date_of_perf_plan,
                    $creator_id,
                    $executor_id,
                    $checker_id,
                    $state = 0,
                    $auth_comment = '',
                    $total_task_count = 0 ,
                    $completed_task_count = 0,
                    $maximal_order_data = 0
                )
{

global $PROJECT_ORDER_DETAIL_PAGE_ID, $EDIT_PROJECT_PAGE_ID, $project_row_ind_count, $files_path;
global $mysqli ;
    
    $exec_list = explode( '|', $executor_id );
    $executor = '';
    
    for( $i = 0 ; $i < count( $exec_list ); $i ++ )
      {
        $person = GetPerson( $exec_list[ $i ] );
        if( strlen( $person ) )
          if( $i == 0 )
            $executor .= $person ;
              else
                $executor .= "<br>".$person ;
      }

    $creator  = GetPerson( $creator_id );
    $checker  = GetPerson( $checker_id );
    
    $res_user_id = GetUserResourceID( $user_id ) ;
    $user_name  = GetPerson( $res_user_id );

    $in_proj_list = InProjList( $res_user_id , $proj_id ) ;
//    $proj_can_add = ( $in_proj_list || $user_id == 1 ) ? '' : 'disabled' ;
	
    if( $div_type == 'div_type_project_order')
        $ord_can_change = ( InOrdList( $res_user_id , $div_id ) || $user_id == 1 ) ? '' : 'disabled' ;

    switch( $state )
    {
        case 'Новое'                :  $state_class = 'state_new'; break ;
        case 'Просмотрено'          :  $state_class = 'state_viewed'; break ;
        case 'Выполнено'            :  $state_class = 'state_completed'; break ;
        case 'Принято к исполнению' :  $state_class = 'state_accepted_to_work'; break ;
        case 'На доработку'         :  $state_class = 'state_rework'; break ;
        case 'Принято'              :  $state_class = 'state_accepted'; break ;
        case 'Завершено'            :  $state_class = 'state_executed'; break ;
        case 'Аннулировано'         :  $state_class = 'state_annulated'; break ;  
        default                     :                                    break ;
    }
    
   $color = $level ;
//   $spacing = $level * 10 + 10 ;
   $spacing = $level + 5 ;

   $perc = $total_task_count ? round( ( $completed_task_count / $total_task_count ) * 100 , 1 ) : 0 ;

   if( $perc > 10 )
      $prog_empty = "";
        else
          $prog_empty = "progress_empty";
   
   $create_order = "<td class='AC click-td show_hide show_hide_$user_id $proj_can_add' id='$div_id' 
                        data-userid='$user_id' data-projid='$proj_id' 
                        data-level='$level'    data-inname='$in_name' 
                        data-rowid='$row_id'>
                        <img src='/uses/plus.png'/></td>";
   
   $date_of_perf_fact = '';
    
   if( $state == 'Выполнено' || $state == 'Завершено' )
        $date_of_perf_fact = GetDateByStatus( $div_id, $state );
       
    $verbose_link = "<a class='link' data-id='$div_id' target='_self' href='http://".$_SERVER['HTTP_HOST']."/index.php?do=show&formid=$PROJECT_ORDER_DETAIL_PAGE_ID&id=$div_id'>$div_id</a>";       
       
    if( $div_type == 'div_type_project_order' )
    {
    $str = "
    <tr data-name='$project_row_ind_count' class='$row_class' name='$in_name' id='$row_id' style='$style'>
    <td class='AR'>
    $verbose_link
    </td>
    $create_order
    <td name='$row_id' class='AL' style='padding-left:".$spacing."px' title='$auth_comment'>$name
    </td>
    <td class='AC prj_ord_date_of_beg_plan_cell'>$date_of_beg_plan</td>      
    <td class='AC'>$date_of_perf_plan</td>              
    <td id='state_exec_cell_$div_id' class='AC'>$date_of_perf_fact</td>     
    <td class='AC'>$creator</td>
    <td class='AC'></td>            
    <td class='AC prj_ord_date_executor_cell'>$executor</td>
    <td class='AC'>$checker</td>
    <td id='comment_cell_$div_id' class='AC'>$auth_comment</td>        
    <td class='AC'>
        <input  
                data-user_name='$user_name' 
                data-user_id='$res_user_id' 
                data-creator_id='$creator_id' 
                data-executor_id='$executor_id' 
                data-checker_id='$checker_id' 
                data-inlist='$in_proj_list' 
                data-id='$div_id' class='ch_status_checkbox' type='checkbox' $ord_can_change /></td>
    <td id='state_cell_$div_id' class='AC $state_class' data-state='$state'>".$state."</td>        
    </tr>";
  }
  
  if( $div_type == 'div_type_project' )    
  {
    $image_path =  str_replace("//", "/", "/project/$files_path/db_projects@filename/$filename" );
    $full_path = str_replace("//", "/",  $_SERVER['DOCUMENT_ROOT'].$image_path );
   
    $project_row_ind_count ++ ;
    $total_expand_img = '';
      
      if( $total_task_count )
          $total_expand_img = "<img data-name='$project_row_ind_count' data-id='$row_id' data-state='0' title='Открыть все задания' class='coll_exp' src='uses/collapse.png' />";
          
    $link = "<div class='edit_project_div'><img data-maxdate='$maximal_order_data' id='$div_id' class='edit_project_img' src='/uses/project.png'></div>";

// Проверка файла на существование
// символ @ перед filetype добавлен для подавления сообщения 
// Warning: filetype(): Lstat failed for C:/AppServ/www/project/63gu88s920hb045e/db_projects@filename/1472100182.jpg in C:\AppServ\www\project\MyJobs\TaskByProjectFunctions.php on line 286
    if( @filetype( $full_path ) != 'file' )
    {
      
      if( GetUserResourceID( $user_id ) == $creator_id )
        $img = "<img src='uses/addf_img.png' data-id='$div_id' class='load_file' title='Загрузить файл'>
              <img src='uses/film.png' data-image='$image_path' data-id='$div_id' class='view_file hidden' title='Посмотреть файл'>";
          else
        $img = "<img src='uses/addf_dis.png' class='load_file_dis' title='Вы не можете загрузить файл'>
                <img src='uses/film.png' data-image='$image_path' data-id='$div_id' class='view_file hidden'>";

    }
          else
      $img = "<img src='uses/addf_img.png' data-id='$div_id' class='load_file hidden' title='Загрузить файл'>
              <img src='uses/film.png' data-image='$image_path' data-id='$div_id' class='view_file' title='Посмотреть файл'>";


    $str = "
    <tr data-name='$project_row_ind_count' class='project ".$row_class."' name='".$in_name."' id='".$row_id."' style='".$style."'>
    <td class='AR'>".$link."</td>
    $create_order

    <td name='$row_id' class='AL' style='padding-left:".$spacing."px'>$total_expand_img$name
    <img data-id='$row_id' data-sortname = 'reset_sort' title = 'Сбросить сортировку' class='ralign reset_sort hidden'/>
    </td>

    <td class='AC prj_beg_date_plan'>$date_of_beg_plan
    <img data-id='$row_id' data-sortname = 'prj_ord_sort_name'     class='prj_ord_sort_img' title='Сортировать задания по названию'/>
    <img data-id='$row_id' data-sortname = 'prj_ord_sort_beg_date' class='prj_ord_sort_img' title='Сортировать задания по дате начала' />
    <img data-id='$row_id' data-sortname = 'prj_ord_sort_executor' class='prj_ord_sort_img' title='Сортировать задания по исполнителю' />
    </td>

    <td class='AC'>$date_of_perf_plan</td>
    <td class='AC'></td>
    <td class='AC prj_author'>$creator</td>
    <td class='AC'>$img</td>    
    <td class='AR progress_td' colspan='5'>
    <progress value='$completed_task_count' max='$total_task_count'></progress>
    <div class='progress_count $prog_empty'>$perc</div>
    </td>        
    </tr>";

   		$query = "UPDATE okb_db_projects SET perc_of_execution='$perc' WHERE ID=$proj_id" ;
      $result = $mysqli->query( $query );
  }  
    
  return $str ;
}

// Построить и вывести иерархическое дерево заказов
function CreateProjectChildTree( $item_chield , $level = 0 , $in_name = 0  )
{
  global $PROJECT_PAGE_ID ;
  global $NEW_PROJECT_PAGE_ID ;
  global $EDIT_PROJECT_PAGE_ID ;
  global $user, $user_id;  
  
    $color = $level ;
    $spacing = $level * 10 + 5 ;

    if( isset( $item_chield['project_order_creator'] ))    
        $creator_id  = $item_chield['project_order_creator'];
    
    if( isset( $item_chield['project_creator'] ))
        $creator_id  = $item_chield['project_creator'];
    
    
    if( isset( $item_chield['project_order_executor'] ))
        $executor_id = $item_chield['project_order_executor'];
    
    if( isset( $item_chield['project_executor'] ))
        $executor_id  = $item_chield['project_executor'];

    
    if( isset( $item_chield['project_order_checker'] ))
        $checker_id  = $item_chield['project_order_checker'];
    
    if( isset( $item_chield['project_checker'] ))
        $checker_id  = $item_chield['project_checker'];
    
    $date_of_perf_fact = 0 ;
    if( isset( $item_chield['project_order_date_of_perf_fact'] ))
        $date_of_perf_fact = $item_chield['project_order_date_of_perf_fact'];
    
    $div_type = $item_chield['DivType']; 
    $div_name = $item_chield['DivName'];
    $div_file_name = $item_chield['DivFileName'];
    $div_id = $item_chield['DivID'];
    $div_descr = $item_chield['DivDescr'];

    $total_task_count = $item_chield['TotalTaskCount'];
    $completed_task_count = $item_chield['CompletedTaskCount'];
	
    if( isset( $item_chield['TotalDaysInTasksCount'] ) )
        $total_days_count = $item_chield['TotalDaysInTasksCount'];

/*	
    $completed_task_count = 0 ;
    if( isset( $item_chield['CompletedDaysInTasksCount'] ) )    
        $completed_task_count = $item_chield['CompletedDaysInTasksCount'];
    */
	
	
    if( $div_type == 'div_type_project' )
    {
        $date_of_beg_plan = $item_chield['FromDate'];
        $date_of_perf_plan = $item_chield['ToDate'];        
        
//        if( $item_chield['TotalDaysInTasksCount'] )
//            $duration  = " длительность дней : ".$item_chield['TotalDaysInTasksCount'];
        $maximal_order_data = $item_chield['MaximalOrderDate'];
    }
    
    if(  $div_type == 'div_type_project_order'  )
        {
            if( $total_task_count == 1 )
            {
                $total_task_count = 0 ;
                $completed_task_count = 0 ;
            }
            
            $state = $item_chield['project_order_status'];
            $date_of_beg_plan  = $item_chield['project_order_date_of_beg_plan'];            
            $date_of_perf_plan = $item_chield['project_order_date_of_perf_plan'];            
            
//            if( $item_chield['project_order_duration'] )
//                $duration  = " длительность дней : ".$item_chield['project_order_duration'];
        }

    if( $date_of_perf_plan == '..')
        $date_of_perf_plan = '';
        
    if( isset( $item_chield['project_id'] ))
        $proj_id = $item_chield['project_id'];
            else
                $proj_id = $item_chield['DivID'];
    $user_id = $user['ID'];

    if( strlen( $div_name ) )
    {
        if( $div_type == 'div_type_project_order' )
            $name = "<span id='$div_id' class='proj_ord'>$div_name</span>" ;
        if( $div_type == 'div_type_project' )    
            $name = "<span class='proj_item' title='$div_descr'>$div_name</span>" ;
    }

    $childs = $item_chield['childs'];
    $chield_count = count ( $childs );
    $row_class = 'proj_row';    

    if( $in_name )
        {
            if( $div_type == 'div_type_project_order' )
               $style = 'background-color:'.GetColor( $color ) ;
            $color ++ ;
            $style .= ';display:none';
        }
    
    $offset = 0 ;

    $comp_perc = $item_chield['comp_perc'];
    $pie_img = '';
    $pie_img = MakePie( $comp_perc );
       
    if( $chield_count  )
        {
            $name = '<span style="padding-left:'.$offset.'px" class="collspan">&#9658;</span>'.$name ;
            $row_class = 'collapsed_proj_row';
        }
         else
//            $name = $pie_img.'<span class="ordspan" style="padding-left:'.$offset.'px">&#9899;&nbsp;&nbsp;</span>'.$name ;
            if( $in_name )
              $name = $pie_img.'<span class="ordspan" style="padding-left:'.$offset.'px"></span>'.$name ;

    // Уникальный id для строки        
//    $id = uniqid( $in_name , 1 ); 
    $id = uniqid( $in_name , 1 ); 

    if( isset( $duration ) )
        $name .= $duration ;
    
    if( !isset( $style ))
        $style = '';

    if( !isset( $state ))
        $state = '';
    
    $str = GetRow( 
                    $level , 
                    $user_id,
                    $proj_id,
                    $div_type,
                    $name, 
                    $div_file_name,                    
                    $div_id, 
                    $row_class, 
                    $in_name, 
                    $id, 
                    $style,  
                    $date_of_beg_plan,
                    $date_of_perf_plan,
                    $creator_id,
                    $executor_id,
                    $checker_id,
                    $state,
                    $div_descr,
                    $total_task_count, 
                    $completed_task_count,
                    $maximal_order_data
                );
 
    // Если есть вложенные записи, то рекурсивный вызов для обхода всего дерева.
    if( $chield_count )
        foreach( $childs AS $key => $ichild )
            $str .= CreateProjectChildTree( $ichild , $level + 1 , $id );
   
    return $str ;
}

function CreateProjectTree( $arr )
{

  global $PROJECT_PAGE_ID ;
  global $NEW_PROJECT_PAGE_ID ;
  global $EDIT_PROJECT_PAGE_ID ;
  global $user ;  

    $add_td = "<td class='click-td show_hide' width='1%' style='border-right-width:0'></td>";
    $link = "<div class='new_proj_link'><a class='link' href='http://".$_SERVER['HTTP_HOST']."/index.php?do=show&formid=".$NEW_PROJECT_PAGE_ID."'>Создать новый проект</a><br><br></div>";    
    
    $str = "<div id='project_div'><H2>Задания по проектам</H2>
                $link
                <table width='1400px' class='rdtbl tbl' id='project_table'>
                <thead>                
                <tr class='first'>
                <td width='8px'></td>
                $add_td
                <td width='14%' style='border-left-width:0'>
                <img data-state='0' height='15' title='Открыть все задания' class='prj_coll_all_img' src='uses/collapse.png' />                
                <img src='project/img5/u1.gif' val='1' id='prj_name_sort' class='prj_sort_img' title='Сортировать проекты по названию'/>
                Проект
                </td>
                <td width='60px'>Дата<br>
                <img src='project/img5/c1.gif' val='0' id='prj_beg_date_sort' class='prj_sort_img' title='Сортировать проекты по дате начала'/>
                начала<br>план</td>
                <td width='60px'>Дата<br>выполнения<br>план</td>
                <td width='40px'>Дата<br>выполнения<br>факт</td>
                <td width='5%'>
                <img src='project/img5/c1.gif' val='0' id='prj_auth_sort' class='prj_sort_img' title='Сортировать проекты по автору'/>
                Автор</td>
                <td width='1%'></td>                
                <td width='6%'>Исполнитель</td>
                <td width='5%'>Контролер</td>
                <td width='4%'>Комм. автора</td>                
                <td width='1%'></td>
                <td width='4%'>Статус</td>
                </tr></thead>";

    foreach( $arr AS $item )
        $str .= CreateProjectChildTree( $item );

    return $str .= "</table></div>";
}

?>

