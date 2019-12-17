<script type="text/javascript" src="/project/projects/js/projects.js"></script>
<script type="text/javascript" src="/project/projects/js/editProjectAJAX.js"></script>
<script type="text/javascript" src="/project/projects/js/uploadProjectFiles.js"></script>
<script type="text/javascript" src="/project/projects/js/sortByProjects.js"></script>
<script type="text/javascript" src="/project/projects/js/my_autocomplete.js"></script>
<script type="text/javascript" src="/project/projects/js/insertOneRowAfterAJAX.js"></script>

<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("pie.php");
require_once("makeChart.php");

// *****************************************************************************************************

function conv( $str )
{
  global $dblocation ;

  if( $dblocation == "127.0.0.1" )
    $result = iconv("UTF-8", "Windows-1251", $str );
      else
        $result = iconv("UTF-8", "Windows-1251", $str );
//        $result = $str ;

  return $result;
}

// *****************************************************************************************************

function MakeDepartmentChart( $arr, $id )
{
  global $mysqli;

   if( $id )

   $query = "
                 SELECT cal.order_id order_id, cal.hour_count hour_count, zad.ID_proj proj_id
                 FROM okb_db_working_calendar cal
                 INNER JOIN okb_db_itrzadan zad on cal.order_id = zad.id
                 WHERE
                 user_id in ( SELECT ID_resurs FROM `okb_db_shtat` WHERE ID_otdel=$id )
                 AND
                 hour_count <> 0 ";


  else

   $query = "
                 SELECT cal.order_id order_id, cal.hour_count hour_count, zad.ID_proj proj_id
                 FROM okb_db_working_calendar cal
                 INNER JOIN okb_db_itrzadan zad on cal.order_id = zad.id
                 WHERE hour_count <> 0
                 ";


   $result = $mysqli -> query( $query );

        if( ! $result )
            exit("Database access error in ".__FILE__." at ".__LINE__." line: ".$mysqli->error);

 $projects = [];

         if( $result -> num_rows )
             while ( $row = $result -> fetch_object() )
             {
                 $proj_id = $row -> proj_id ;
                 $projects[ $proj_id ] +=  $row -> hour_count;
             }

 $orders = [];

 foreach( $arr AS $key => $item )
 {
    $divid = $item['DivID'];
    $orders[ $key ][ 'hour_count' ] =  $projects[ $divid ];
    $orders[ $key ][ 'order_name' ] = iconv("Windows-1251", "UTF-8", $item['DivName'] );
 }
  if( $id == 0 || $id == '0' )
    $id = 'all';


           MakeChart(
                                            $_SERVER['DOCUMENT_ROOT']."/uses/working_calendar_img/project_hours_$id",
                                            $orders
                            );

}

// *****************************************************************************************************
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

function GetUsersList( $department  )
{
  global $mysqli;

  $user_id_arr = [];

  $query ="SELECT * FROM okb_db_shtat WHERE ID_otdel = $department";
  $users = $mysqli -> query( $query );

  if( ! $users )
      exit("Database access error in ".__FUNCTION__." : ".__FILE__." : ".__LINE__." : ".$mysqli->error);

  if( $users -> num_rows )
      while ( $users_row = $users -> fetch_object() )
        $user_id_arr [] = $users_row -> ID_resurs;

  return $user_id_arr;
}

function GetProjectsList( $department = 0, $make_chart = 0 )
{
  global $mysqli;
  $arr = array();
   $where = " WHERE ISNULL( okb_db_projects.STATUS ) ";


   if( $department )
   {
      $users_arr = GetUsersList( $department );
     
      $in = "AND zadan.ID_users2 IN (";
      $or = "OR okb_db_projects.ID_executor IN (";
      
      foreach( $users_arr AS $user_id )
        $in .= "$user_id,";

      foreach( $users_arr AS $user_id )
        $or .= "$user_id,";


// First pass.

      $in = substr( $in, 0, -1 )." ) ";
      $or = substr( $or, 0, -1 )." ) ";      
      
      
      
      $query ="
                SELECT DISTINCT( okb_db_projects.ID )
                FROM
                okb_db_projects
                LEFT JOIN okb_db_itrzadan zadan ON zadan.ID_proj = okb_db_projects.ID
                $where $in $or
      ";

       $projects = $mysqli -> query( $query );

       if( ! $projects )
           exit("Database accsess error in ".__FUNCTION__." file  ".__FILE__." : ".$mysqli->error."<br>$query");

       // Если имеется хотя бы одна запись, выводим список
       if( $projects -> num_rows )
           while ( $projects_row = $projects -> fetch_assoc())
               $arr[] = $projects_row['ID'];

       $in = " AND NULL ";
       if( count ( $arr ) )
       {
           $in = " AND ID IN ( ";
           foreach ($arr AS $id)
               $in .= "$id,";
           $in = substr($in, 0, -1) . ") ";
       }


       $query ="
                SELECT *
                FROM
                okb_db_projects
                $where $in ORDER BY name";
   }
        else
             $query ="SELECT * FROM okb_db_projects ".$where." ORDER BY name";

  $projects = $mysqli -> query( $query );

  if( ! $projects )
      exit("Database accsess error in ".__FUNCTION__." file  ".__FILE__." : ".$mysqli->error."<br>$query");

  $arr = [];

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


//  if( $make_chart )
//    MakeDepartmentChart( $arr, $id );

  return $arr ;
}

function GetRow(
                    $val,
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
global $mysqli ; //, $user;

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
    
    $proj_can_add = ( $in_proj_list || $user_id == 1 ) ? '' : 'disabled' ;


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
          $total_expand_img = "<img data-name='$project_row_ind_count' data-id='$row_id' data-state='0' title='".conv("Открыть проект")."' class='coll_exp' src='uses/collapse.png' />";

    $link = "<div class='edit_project_div'><img data-maxdate='$maximal_order_data' id='$div_id' class='edit_project_img' src='/uses/project.png'></div>";

// Проверка файла на существование
// символ @ перед filetype добавлен для подавления сообщения
// Warning: filetype(): Lstat failed for C:/AppServ/www/project/63gu88s920hb045e/db_projects@filename/1472100182.jpg in C:\AppServ\www\project\MyJobs\TaskByProjectFunctions.php on line 286
    if( @filetype( $full_path ) != 'file' )
    {

      if( GetUserResourceID( $user_id ) == $creator_id )
        $img = "<img src='uses/addf_img.png' data-id='$div_id' class='load_file' title='Загрузить файл'>
              <img src='uses/film.png' data-image='$image_path' data-id='$div_id' class='view_file hidden' title='".conv("Посмотреть файл")."'>";
          else
        $img = "<img src='uses/addf_dis.png' class='load_file_dis' title='".conv("Вы не можете загрузить файл")."'>
                <img src='uses/film.png' data-image='$image_path' data-id='$div_id' class='view_file hidden'>";

    }
          else
      $img = "<img src='uses/addf_img.png' data-id='$div_id' class='load_file hidden' title='".conv("Загрузить файл")."'>
              <img src='uses/film.png' data-image='$image_path' data-id='$div_id' class='view_file' title='".conv("Посмотреть файл")."'>";

    $str = "
    <tr data-name='$project_row_ind_count' class='project ".$row_class."' name='".$in_name."' id='".$row_id."' style='".$style."'>
    <td class='AR'>".$link."</td>
    $create_order

    <td name='$row_id' class='AL' style='padding-left:".$spacing."px'>$total_expand_img$name
    <img data-id='$row_id' data-sortname = 'reset_sort' title = '".conv("Сбросить сортировку")."' class='ralign reset_sort hidden'/>
    </td>

    <td class='AC prj_beg_date_plan'>$date_of_beg_plan
    <img data-id='$row_id' data-sortname = 'prj_ord_sort_name'     class='prj_ord_sort_img' title='".conv("Сортировать задания по названию")."'/>
    <img data-id='$row_id' data-sortname = 'prj_ord_sort_beg_date' class='prj_ord_sort_img' title='".conv("Сортировать задания по дате начала")."' />
    <img data-id='$row_id' data-sortname = 'prj_ord_sort_executor' class='prj_ord_sort_img' title='".conv("Сортировать задания по исполнителю")."' />
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
function CreateProjectChildTree( $user_id, $item_chield , $level = 0 , $in_name = 0  )
{
  global $PROJECT_PAGE_ID ;
  global $NEW_PROJECT_PAGE_ID ;
  global $EDIT_PROJECT_PAGE_ID ;
//  global $user;
 
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
                    $val,
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
    {
        foreach( $childs AS $key => $ichild )
            $str .= CreateProjectChildTree( $user_id, $ichild , $level + 1 , $id );
    }

    return $str ;
}

function GetDataAfterProjectTree()
{
  return "</div>";
}

function GetDataBeforeProjectTree( $id = 0 )
{

  global $PROJECT_PAGE_ID ;
  global $NEW_PROJECT_PAGE_ID ;
  global $EDIT_PROJECT_PAGE_ID ;
//  global $user, $user_id;


if( $id == 0 )
  $id = 'all';

$nav_bars = "
<ul class='pagination'>
<li class='page-item active' data-id='0'>
<a class='page-link' href='#'>".conv("Все проекты")."</a>
</li>
<li class='page-item' data-id='91'>
<a class='page-link' href='#'>".conv("КО")."</a>
</li>
<li class='page-item' data-id='43'>
<a class='page-link' href='#'>".conv("Отдел ИТ")."</a>
</li>
<li class='page-item' data-id='45'>
<a class='page-link' href='#'>".conv("Отдел продаж")."</a>
</li>
<li class='page-item' data-id='103'>
<a class='page-link' href='#'>".conv("Техотдел")."</a>
</li>
<li class='page-item' data-id='12'>
<a class='page-link' href='#'>".conv("МТС")."</a>
</li>
</ul>";

    $link = "<div class='new_proj_link'><a class='link' href='http://".$_SERVER['HTTP_HOST']."/index.php?do=show&formid=".$NEW_PROJECT_PAGE_ID."'>".conv("Создать новый проект")."</a><br><br>
             </div>";
    $total_rec = "<div><h3 id='rec_count'>".conv("Количество проектов :")."</div>";

    $str = "<div id='project_div'>

    <div><H2>".conv("Задания по проектам")."</H2></div>
    <div class='chart_div'>
        <div><img class='chart' src='/uses/working_calendar_img/project_hours_$id.png'>
        </div>
        <div>
          <select id='user_chart_select' class='hidden'>
          </select>
          <img id='user_chart_select_img' class='chart hidden' src='/uses/working_calendar_img/project_hours_all.png'>
        </div>


    </div>

    <div class='clearfix'></div>

    $link $total_rec $nav_bars

    ";

  return $str ;
}

function CreateProjectTree( $val, $arr )
{

  global $PROJECT_PAGE_ID ;
  global $NEW_PROJECT_PAGE_ID ;
  global $EDIT_PROJECT_PAGE_ID ;

   $add_td = "<td class='click-td show_hide' width='1%' style='border-right-width:0'></td>";
   $str = "<table width='1400px' class='rdtbl tbl' id='project_table'>
                <thead>
                <tr class='first'>
                <td width='1%'></td>
                $add_td
                <td width='14%' style='border-left-width:0'>
                <img data-state='0' height='15' title='".conv("Открыть все проекты")."' class='prj_coll_all_img' src='uses/collapse.png' />
                <img src='project/img5/u1.gif' val='1' id='prj_name_sort' class='prj_sort_img' title='".conv("Сортировать проекты по названию")."'/>
                ".conv("Проект")."
                </td>
                <td width='60px'>".conv("Дата<br>")."
                <img src='project/img5/c1.gif' val='0' id='prj_beg_date_sort' class='prj_sort_img' title='".conv("Сортировать проекты по дате начала")."'/>
                ".conv("начала<br>план")."</td>
                <td width='60px'>".conv("Дата<br>выполнения<br>план")."</td>
                <td width='40px'>".conv("Дата<br>выполнения<br>факт")."</td>
                <td width='5%'>
                <img src='project/img5/c1.gif' val='0' id='prj_auth_sort' class='prj_sort_img' title='".conv("Сортировать проекты по автору")."'/>
                ".conv("Автор")."</td>
                <td width='1%'></td>
                <td width='6%'>".conv("Исполнитель")."</td>
                <td width='5%'>".conv("Контролер")."</td>
                <td width='4%'>".conv("Комм. автора")."</td>
                <td width='1%'></td>
                <td width='4%'>".conv("Статус")."</td>
                </tr></thead>";


    foreach( $arr AS $item )
    {
        $str .= CreateProjectChildTree( $val, $item );
    }

    return $str .= "</table>";
}

?>


