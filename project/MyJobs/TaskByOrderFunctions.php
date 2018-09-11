<?php

function GetColor( $color )
{
    $colors = array( 
    '#D3D3D3',
    '#FFE4E1',        
    '#FFE4B5',
    '#FFDAB9',        
    '#EEE8AA',        
    '#F0E68C',        
    '#BDB76B',
    );
    return $colors[ $color ];
}

function FindKey( $zak_arr , $val, $search_key )
{
    foreach( $zak_arr AS $key => $value )
        if( $value[ $search_key ] == $val )
            return $key ;
    return NULL ;
}

function sortByField( $a, $b, $field ) 
{
    if ( $a[ $field ] == $b[ $field ] ) 
                return 0;
     return ($a[ $field ] < $b[ $field ]) ? -1 : 1 ;
}        

function UsortByField( $a, $b, $field ) 
{
    if ( $a[ $field ] == $b[ $field ] ) 
                return 0;
     return ($a[ $field ] < $b[ $field ]) ? 1 : -1 ;
}        


function sortByName( $a, $b ) 
{
//    $field = 'zak_name'; 
//    if ( $a[ $field ] == $b[ $field ] ) 
//                return 0;
//     return ($a[ $field ] < $b[ $field ]) ? -1 : 1 ;
    return sortByField( $a, $b, 'zak_name' );
}


function UsortByName( $a, $b ) 
{
//    $field = 'zak_name'; 
//    if ( $a[ $field ] == $b[ $field ] ) 
//                return 0;
//     return ($a[ $field ] < $b[ $field ]) ? 1 : -1 ;
    return UsortByField( $a, $b, 'zak_name' );
}


function sortByZakID( $a, $b ) 
{
//    $field = 'zak_id'; 
//    if ( $a[ $field ] == $b[ $field ] ) 
//                return 0;
//     return ($a[ $field ] < $b[ $field ]) ? -1 : 1 ;
    return sortByField( $a, $b, 'zak_id' );
}
 

function sortByZadanID( $a, $b ) 
{
//    $field = 'zadan_id'; 
//    if ( $a[ $field ] == $b[ $field ] ) 
//                return 0;
//     return ($a[ $field ] < $b[ $field ]) ? -1 : 1 ;
    return sortByField( $a, $b, 'zadan_id' );
}


function sortByDatePlan( $a, $b ) 
{
//    $field = 'date_plan'; 
//    if ( $b[ $field ] == $a[ $field ] ) 
//                return 0;
//     return ($a[ $field ] < $b[ $field ]) ? -1 : 1 ;
    return sortByField( $a, $b, 'date_plan' );
}

// Получить имя пользователя по ID из таблицы okb_db_resurs
function GetDateByStatus( $id, $status )
{
    if( $status == 'Завершено' )
        $status = 'Выполнено';
    $query = "
        SELECT 
        DATE_FORMAT( DATA, '%d.%m.%Y' ) date
        FROM okb_db_itrzadan_statuses
        WHERE ID_edo=".$id." AND STATUS='".$status."'";
    
        $result = dbquery( $query );
        $row = mysql_fetch_assoc( $result );
        
    return $row['date'];
}

// Получить имя пользователя по ID из таблицы okb_db_resurs
function GetPerson( $person_id )
{
   if( $person_id == '')
       return '';
    $query = "
        SELECT 
        persons.NAME person_name 
        FROM okb_db_resurs persons  
        WHERE ID=".$person_id;
    
        $result = dbquery( $query );
        $row = mysql_fetch_assoc( $result );
        
    return $row['person_name'];
}

function GetZakName( $zak_id )
{
   global $db_prefix ;
    
   if( $zak_id == '' || $zak_id == '0' || $zak_id == 0 )
       return 0;

   $query = "
              SELECT
              okb_db_zak_type.zak_type zak_type,
              NAME, 
              DSE_NAME,
              PID 
              FROM ".$db_prefix."db_zak zak 
              INNER JOIN okb_db_zak_type ON zak.TID = okb_db_zak_type.ID 
              where zak.ID=".$zak_id ;
      
    $result = dbquery( $query );
    $zak = mysql_fetch_array( $result );
    $zak_name = $zak["NAME"];
    $zak_prefix = $zak["zak_type"];
    $zak_dse_name = $zak["DSE_NAME"];
    $zak_parent = $zak["PID"];    
   
    if( !strlen( $zak_name ) )
        $zak_name = 'ID заказа : '.$zak_id ;
    
    return Array( 
                    'zak_id' => $zak_id, 
                    'zak_parent' => $zak_parent,
                    'zak_prefix' => $zak_prefix ,        
                    'zak_name' => $zak_name , 
                    'zak_dse_name' => $zak_dse_name
                );
}

function GetZakList( $id_person = 0 , $not_equal = 11111 )
{
   global $db_prefix ;    
   
   if( $id_person )
       $where = ' ID_users="'.$id_person.'" OR ID_users2="'.$id_person.'" ';
        else
            $where = ' 1 ';
               
   $zak_list = array();
   
   $query = "
              SELECT 
              ID_zak id 
              FROM ".$db_prefix."db_itrzadan  
              where ".$where." AND TIP_FAIL<>".$not_equal." 
              GROUP BY ID_zak              
              ORDER BY ID_zak 
            ";
      
    $result = dbquery( $query );
    while( $row = mysql_fetch_array( $result ))
    {
        $zak_item = GetZakName( $row['id'] );
        $zak_item['childs'] = Array();
        
        if( $zak_item )
             $zak_list[] = $zak_item ;
    }
 return $zak_list ;   
}


function GetTaskList( $zak_list , $id_person = 0 , $not_equal = 11111 )
{
   global $db_prefix ;    
  
   if( $id_person )
       $user_filter = ' AND ( ID_users='.$id_person.' OR ID_users2='.$id_person.' )';
       else
           $user_filter = '';
   
   foreach ( $zak_list AS $key => $value )
   {
   $zak_id = $value['zak_id'];
   $query = "
              SELECT 
              ID, TXT, ID_users, ID_users2, ID_users3, TIP_FAIL, ID_edo,
              DATE_FORMAT( DATE_PLAN, '%d.%m.%Y' ) date_plan, STATUS 
              FROM ".$db_prefix."db_itrzadan 
              where ID_zak=".$zak_id." AND TIP_FAIL<>".$not_equal." ".$user_filter." ORDER BY DATE_PLAN";
      
    $result = dbquery( $query );
    while( $row = mysql_fetch_array( $result ))
    {
        $zak_list[$key]['childs'][] = 
          Array(
                'zadan_id'          => $row['ID'],              
                'zadan_descr'       => $row['TXT'],
                'zadan_creator'     => $row['ID_users'],
                'zadan_executor'    => $row['ID_users2'],
                'zadan_checker'     => $row['ID_users3'],
                'zadan_start_date'  => $row['date_plan'],
                'zadan_status'      => $row['STATUS'],
                'zadan_parent'      => $row['TIP_FAIL'] == 5 ? $row['ID_edo'] : 0 ,
                'zadan_file_type'   => $row['TIP_FAIL'],
                'zadan_id_edo'      => $row['ID_edo']
               );
    }

// Сортировка внутри группы
    usort( $zak_list[$key]['childs'], 'sortByZadanID' );
    
  }
 return $zak_list ;   
}

// Построить и вывести иерархическое дерево заказов
function CreateChildTree( $item_chield , $in_name, $spacing , $color )
{
    $child_space = 10;
    
    $zadan_id = $item_chield['zadan_id'];
    $name = $item_chield['zadan_descr'];
    $executor = GetPerson( $item_chield['zadan_executor']);
    $creator = GetPerson( $item_chield['zadan_creator']);
    $checker = GetPerson( $item_chield['zadan_checker']);    
    
    $start_date = $item_chield['zadan_start_date'];
    $state = $item_chield['zadan_status'];

    $state_class = '';
    $fact_date = GetDateByStatus( $zadan_id, $state );
    
    $childs = $item_chield['childs'];
    $chield_count = count ( $childs );

    if( $state == 'Аннулировано' )
        $state_class = 'state_annulated';
   
    if( $state == 'Завершено' )
        $state_class = 'state_executed';

    if( $state == 'Просмотрено' )
        $state_class = 'state_viewed';
    
    if( $state == 'Выполнено')
        $state_class = 'state_completed';
    
    if( $state == 'Принято к исполнению')
        $state_class = 'state_accepted_to_work';

    if( $state == 'На доработку')
        $state_class = 'state_rework';

    if( $state == 'Принято')
        $state_class = 'state_accepted';
   
    $row_class = '';    

    if( $chield_count )
        {
            $name = '<span style="padding-left:'.$offset.'px" class="collspan">&#9658;</span>'.$name ;
            $row_class = 'collapsed';
        }
         else
            $name = '<span style="padding-left:'.$offset.'px">&#9899;&nbsp;&nbsp;</span>'.$name ;

    
// Уникальный id для строки        
    $id = uniqid( $in_name , 1 ); 

//    $link = '<a class="link" target="_blank" href="/index.php?do=show&formid=122&id='.$zadan_id.'"><u><i><b>'.$zadan_id.'</b></i></u></a>';
    $link = '<a class="link" target="_blank" href="/index.php?do=show&formid=122&id='.$zadan_id.'">'.$zadan_id.'</a>';

//    $link = '<a class="link" target="_blank" href="/index.php?do=show&formid=122&id='.$zadan_id.'">'.$zadan_id.' '.$color.' </a>';    
    
//    <tr class='".$row_class."' name='".$in_name."' id='".$id."' style='display:none'>
    $col = GetColor( $color );
    $str = "
    <tr class='".$row_class."' name='".$in_name."' id='".$id."' style='display:none; background-color:".$col."'>
    <td class='field AR'>".$link."</td>
    <td name='".$id."' class='field' style='padding-left:".$spacing."px'>".$name."</td>
    <td class='field AC'>$start_date</td>    
    <td class='field AC'>$fact_date</td>     
    <td class='field AC'>".$creator."</td>
    <td class='field AC'>".$executor."</td>
    <td class='field AC'>".$checker."</td>
    <td class='field AC ".$state_class."'>".$state."</td>        
    </tr>";
   
    $color ++ ;
    
    // Если есть вложенные записи, то рекурсивный вызов для обхода всего дерева.
    if( $chield_count )
        foreach( $childs AS $ichild )
            $str .= CreateChildTree( $ichild , $id , $spacing + $child_space , $color );
   
    return $str ;
}

function CreateMainRow( $task , $line , $in_id = 0 , $offset = 0 )
{
  if( $line == 0 )
  {
      $line = '&#10149;';
      $lineclass = 'field AC';
  }
  else
    $lineclass = 'field AC';
  
  $name = $task['zak_prefix'].' '.$task['zak_name'].' '.$task['zak_dse_name'];
//  $link = '<a class="link" target="_blank" href="/index.php?do=show&formid=39&id='.$task['zak_id'].'"><img src="/uses/view.png"></img></a>' ;

  $link = '<img onclick="ZakView('.$task['zak_id'].')" src="/uses/view.png"></img>' ;  
  $childs = $task['childs'] ;
  $chield_count = count( $childs ) ;
  
    if( $chield_count )
        {
            $name = '<span style="padding-left:'.$offset.'px" class="collspan">&#9658;</span>'.$link.$name ;
            $row_class = 'collapsed';
        }

$str = '';
        
    // Уникальный id для строки        
    //    $id = md5(uniqid(rand(),1));
  if( $task['zak_parent'] )
  {
     $id = uniqid( $in_id, 1 );
     $str = '<tr class="'.$row_class.'" id="'.$id.'" style="background-color:">
             <td name="'.$id.'" class="'.$lineclass.'">'.$line.'</td>
             <td colspan="7" name="'.$id.'" class="field"> '.$name.'</td>
             </tr>';
   }
     else
        {
            $id = uniqid(rand(),1);
            //$name .= ' id:'. $id ;
            $str = '<tr class="'.$row_class.'" id="'.$id.'">
                    <td name="'.$id.'" class="field AC">'.$line ++.'</td>
                    <td colspan="7" name="'.$id.'" class="field"> '.$name.'</td>
                    </tr>';
        }

      foreach( $childs AS $child_item )
      {
          if( isset( $child_item['zak_id']) )
             $str .= CreateMainRow( $child_item , 0, $id, $offset + 10 );
                else
                  $str .= CreateChildTree( $child_item , $id , $offset + 20 , 0 );
      }
   
      return $str ;
}

function CreateTree( $task_list )
{
    $str .= "<H2>Задания по заказам</H2>
<table width='1200px' class='rdtbl tbl'>
<thead>
<tr class='first'>
<td width='2%'>№</td>
<td width='15%'>Наименование задания</td>
<td width='3%'>Дата<br>выполнения<br>план</td>
<td width='3%'>Дата<br>выполнения<br>факт</td>
<td width='5%'>Автор</td>
<td width='5%'>Исполнитель</td>
<td width='5%'>Контролер</td>
<td width='4%'>Статус</td>
</tr></thead>";

$line = 1 ;

foreach( $task_list AS $task )
    $str .= CreateMainRow( $task , $line ++ );
    
    return $str .= "</table>";

}

function CreateSubordinate( $task_list )
{
    $new_list = Array();
    
    
    foreach( $task_list AS $mainkey => $item )
    {
        $temp_list = array();
        

    if( $item['zak_name'] == '16-020' ) // 1729
        {
            $zak_cnt = count ( $zak_arr['childs'] );
            $stop_here = 1 ;
        }
    else
         $stop_here = 10 ;
        
        
        foreach( $item['childs'] AS $key => $value )
        {
            if( $value['zadan_parent'] == 0 )
            {
                $temp_list[] = $item['childs'][$key] ;
                unset( $item['childs'][$key]);
            }
        }
        
        reset( $item['childs'] );
        
        foreach( $item['childs'] AS $key => $value )
        {
            $parent_id = $value['zadan_parent'];
            $index = FindKey( $temp_list, $parent_id, 'zadan_id' );
            if( $index !== NULL )
            {
                $temp_list[$index]['childs'][] = $value ;
            }
        }
     
        $task_list[$mainkey]['childs'] = $temp_list ;
    }
    
    foreach( $task_list AS $key => $value )
    {
        if( $value['zak_parent'] == 0 )
        {
            $new_list[] = $value ;
            unset( $task_list[$key] );
        }
    }

    $new_list_count = count( $new_list );
    $i = 0 ;
    
    foreach( $task_list AS $key => $value )
    {
        $zak_id = $value['zak_id'] ;
        $parent_id = $value['zak_parent'];
        
        for( $i = 0 ; $i < $new_list_count ; $i ++ )
            if( $new_list[$i]['zak_id'] == $parent_id )
                {
                    $new_list[$i]['childs'][] = $value ;
                    unset( $task_list[$key] );
                }
    }
    
   $new_list = array_merge( $new_list, $task_list );
   usort( $new_list, 'sortByName');
   
   return $new_list ;
}

function AdjustOrders( $user_id , $not_equal = 11111 )
{
    $zak_arr = array();
    
    // Выбрать все задания выданные пользователю с заданным ID,
    // разобрать по заказам
//            SELECT ID_zak, ID, DATE_FORMAT( DATE_PLAN, '%d.%m.%Y' ) date, ID_users from_id, ID_users2 to_id
    $query = "
        SELECT ID_zak, ID, CDATE date, ID_users from_id, ID_users2 to_id, TIP_FAIL file_type 
        FROM okb_db_itrzadan 
        WHERE ID_users2=".$user_id." AND ID_users<>".$user_id." AND TIP_FAIL<>".$not_equal." ORDER BY DATE_PLAN" ;
    
        $result = dbquery( $query );
        while ( $row = mysql_fetch_assoc( $result ))
        {
            $zak_id = $row['ID_zak'];
            
            if( ! $zak_id )
                continue ;

            $zak_item = GetZakName( $zak_id );
            $zak_name = $zak_item['zak_name'];
            
            $id = $row['ID'];
            $date_plan = $row['date'];
            $from_id = $row['from_id'];
            $to_id = $row['to_id'];
            $file_type = $row['file_type'];
            
            if( !isset( $zak_arr[ $zak_id ] ))
                $zak_arr[ $zak_id ] = array( 'zak_name' => $zak_name, 'zak_id' => $zak_id , 'childs' => array());
            
            $zak_arr[$zak_id]['childs'][] = array ( 'id' => $id , 'file_type' => $file_type, 'from_id' => $from_id, 'to_id' => $to_id, 'date_plan' => $date_plan , 'childs' => array());
        }

    // Выбрать все задания выданные пользователем с заданным ID,
    // разобрать по заказам
    $query = "
        SELECT ID_zak, ID, CDATE date, ID_users from_id, ID_users2 to_id, TIP_FAIL file_type 
        FROM okb_db_itrzadan 
        WHERE ID_users=".$user_id." ORDER BY DATE_PLAN" ;
    
        $result = dbquery( $query );
        while ( $row = mysql_fetch_assoc( $result ) )
        {
            $zak_id = $row['ID_zak'];
           
            if( ! $zak_id )
                continue ;

            $zak_item = GetZakName( $zak_id );
            $zak_name = $zak_item['zak_name'];
            
            $id = $row['ID'] ;
            $date_plan = $row['date'];
            $from_id = $row['from_id'];
            $to_id = $row['to_id'];
            $file_type = $row['file_type'];
            
// Поиск в массиве выданных заданий и добавление в подчиненую запись            
            $index = FindKey( $zak_arr , $zak_id , 'zak_id' );

            if( $index !== NULL )
            {
                $element = array( 'id' => $id , 'file_type' => $file_type, 'from_id' => $from_id, 'to_id' => $to_id, 'date_plan' => $date_plan, 'childs' => array());
                ProcessElement( $zak_arr[ $zak_id ], $element );
//                usort( $zak_arr[ $zak_id ], 'sortByDatePlan');                
            }
            
            else // Если нет, то добавить отдельное задание под заказ c $zak_id
            {
                $zak_arr[ $zak_id ] = array( );
                $zak_arr[ $zak_id ] = array( 'zak_name' => $zak_name, 'zak_id' => $zak_id );
                $zak_arr[ $zak_id ]['childs'][] = array ('id' => $id , 'file_type' => $file_type, 'from_id' => $from_id, 'to_id' => $to_id, 'date_plan' => $date_plan , 'childs' => array());
            }
        }

$element = $zak_arr['1275'];

usort( $zak_arr, 'sortByZakID' );

$subkey = 0 ;

return $zak_arr ;
}

function ProcessElement( & $zak_arr, $element )
{
    $zak_id   = $zak_arr['zak_id'];
    $zak_name = $zak_arr['zak_name'];
    
    $stop_here = 0 ;
    
    $zak_cnt = count ( $zak_arr['childs'] );
    
    if( $zak_name == '16-021' ) // 1729
        {
            $zak_cnt = count ( $zak_arr['childs'] );
            $stop_here = 1 ;
        }
        else
         $stop_here = 10 ;
    
    $el_date_plan = $element['date_plan'];
    $el_from_id = $element['from_id'];
    
    foreach( $zak_arr['childs'] AS $key => $value )
    {
        $zak_date_plan = $value['date_plan'];
        $zak_from_id = $value['from_id'];
        
        if( $el_date_plan  < $zak_date_plan )
            break ;
            
        if( $el_date_plan  == $zak_date_plan )
            if( $el_from_id == $zak_from_id )
                continue ;
                else
                    {
                        $element['file_type'] = 5 ;
                        $zak_arr[ 'childs' ][ $key ]['childs'][] = $element ;                   
                        return ;
                    }
        
            if( $el_date_plan  > $zak_date_plan )
            {
                $cur_el_date_plan = $zak_arr['childs'][$key]['date_plan'];
                $next_el_date_plan = $zak_arr['childs'][$key+1]['date_plan'];
                // Делаем подчинённую запись
                if( isset( $next_el_date_plan ))
                    {
                        if( $el_date_plan >= $cur_el_date_plan && $el_date_plan < $next_el_date_plan )
                            {
                                $element['file_type'] = 5 ;
                                $zak_arr[ 'childs' ][ $key ]['childs'][] = $element ;
                                return ;
                            }
                    }
                else
                if( $el_date_plan >= $cur_el_date_plan && $el_from_id != $zak_from_id )
                    {
                        $element['file_type'] = 5 ;
                        $zak_arr[ 'childs' ][ $key ]['childs'][] = $element ;
                        return ;
                    }
                else
                    //$zak_arr[ 'childs' ][ $key ]['childs'][] = $element ;
                    continue ;
            }
    }
    
    if( ( $key + 1 ) == $zak_cnt && $el_date_plan  >= $zak_date_plan && $el_from_id != $zak_from_id )
    {
        $element['file_type'] = 5 ;
        $zak_arr[ 'childs' ][ $key ]['childs'][] = $element ;
    }
            else
            {
                $zak_arr['childs'][] = $element ;
                usort( $zak_arr['childs'], 'sortByDatePlan');                
            }
}


function UpdateOrders( $zak_list )
{
    foreach( $zak_list AS $zak_item )
{
    $zak_id = $zak_item['zak_id'];
    foreach( $zak_item['childs'] AS $zak_chield )
    {
        $id = $zak_chield['id'];
            foreach( $zak_chield['childs'] AS $zak_subchield )
            {
                $subid = $zak_subchield['id'];
                UpdateParentRefrence( $id, $subid );
            }
    }
}

}


function UpdateParentRefrence( $base_row_id , $chield_row_id )
{
    
    $query = "SELECT TIP_FAIL 
              FROM okb_db_itrzadan 
              WHERE ID=".$chield_row_id ;
     
    $result = dbquery( $query );
    $row = mysql_fetch_assoc( $result );
    
    $file_type = $row[ 'TIP_FAIL' ];
    
    if( $file_type == '2' )    
        echo 'Alert!!!' ;
        
    if( $file_type != '2' )
    {
        $query = "UPDATE okb_db_itrzadan 
                  SET TIP_FAIL='5', ID_edo=".$base_row_id."
                  WHERE ID=".$chield_row_id ;
        $result = dbquery( $query );
    }
}


function Clear5()
{
   $query = "
              SELECT ID 
              FROM okb_db_itrzadan               
              where  TIP_FAIL=5";

    $result = dbquery( $query );
    while( $row = mysql_fetch_array( $result ))
           dbquery ( "UPDATE okb_db_itrzadan SET TIP_FAIL=9 where ID=".$row['ID'] );
}


function HardSubordinate()
{
   $zak_list = array();
   $zak_arr = array();
   
   $query = "
              SELECT 
              ID, ID_zak, ID_users, ID_users2, TXT 
              FROM okb_db_itrzadan               
              where ID_zak <> 0";

    $result = dbquery( $query );
    while( $row = mysql_fetch_array( $result ))
            $zak_list[] = array(
                                    'rec_id' => $row['ID'],                
                                    'zak_id' => $row['ID_zak'],
                                    'from_id' => $row['ID_users'],
                                    'to_id' => $row['ID_users2'],
                                    'descr' => $row['TXT'],                
                                );

    $pair_count = 0 ;
    
    foreach( $zak_list AS $item )
    {
        $rec_id     = $item['rec_id'];        
        $zak_id     = $item['zak_id'];
        $from_id    = $item['from_id'];
        $to_id      = $item['to_id'];
        $descr      = $item['descr'];

        $query = "
              SELECT ID 
              FROM okb_db_itrzadan 
              where ID <> ".$rec_id." AND ID_zak=".$zak_id." AND TXT='".$descr."' AND ID_users=".$to_id ;

        $result = dbquery( $query );
        $pair_count += mysql_num_rows ( $result );
        
        while( $row = mysql_fetch_array( $result ))
            dbquery( 'UPDATE okb_db_itrzadan SET TIP_FAIL=5, ID_edo='.$rec_id.' WHERE ID='.$row['ID'] );
    }
    
   $zak_arr = array();    
   
}
?>



