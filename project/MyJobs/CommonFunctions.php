<?php
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");
require_once("page_ids.php");

//error_reporting( E_ALL );
error_reporting( 0 );

function InOrdList( $user_id, $ord_id )
{
    $list = GetOrdEmployeesList( $ord_id );
    $total_list = array_merge( $list['creator_list'], $list['executor_list'], $list['checker_list'] );
    $total_list  = array_values( array_unique( $total_list ));    
    
    if(  array_search( $user_id, $total_list ) === false )
          return false;
            else
              return true;
}

function InProjList( $user_id, $proj_id )
{
    $list = GetProjEmployeesList( $proj_id );
    $total_list = array_merge( $list['creator_list'], $list['executor_list'] );
    $total_list  = array_values( array_unique( $total_list ));    
    
    if(  array_search( $user_id, $total_list ) === false )
          return false;
            else
              return true;
}

function GetOrdEmployeesList( $ord_id )
{
   global $mysqli;
   
   $emp_list = array(
       'creator_list' => array(),       
       'executor_list' => array(),
       'checker_list' => array()       
   );
   
   $ret_val = '' ;
      
   $query = "SELECT `ID_users`, `ID_users2`, `ID_users3` FROM okb_db_itrzadan WHERE ID = $ord_id";

        $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Ошибка №1 обращения к БД в функции GetOrdEmployeesList : ".$mysqli->error); 

        if( $result -> num_rows )
            while( $row = $result -> fetch_object() )
            {
                $emp_list['creator_list'][] = $row -> ID_users ;
                $emp_list['executor_list'][] = $row -> ID_users2 ;
                $emp_list['checker_list'][] = $row -> ID_users3;
            }
     
     $emp_list['creator_list'] = array_values( array_unique( $emp_list['creator_list'] )) ;
     $emp_list['executor_list'] = array_values( array_unique( $emp_list['executor_list'] )) ;
     $emp_list['checker_list']  = array_values( array_unique( $emp_list['checker_list'] ));
            
    return $emp_list ;
}


function GetProjEmployeesList( $proj_id )
{
   global $mysqli;
   
   $emp_list = array(
       'creator_list' => array(),       
       'executor_list' => array(),
       'checker_list' => array()       
   );
   
   $ret_val = '' ;
      
   $query = "SELECT `ID_users`, `ID_users2`, `ID_users3` FROM okb_db_itrzadan WHERE ID_proj = $proj_id";

        $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Ошибка №1 обращения к БД в функции GetProjEmployeesList : ".$mysqli->error); 

        if( $result -> num_rows )
            while( $row = $result -> fetch_object() )
            {
                $emp_list['creator_list'][] = $row -> ID_users ;
                $emp_list['executor_list'][] = $row -> ID_users2 ;
                $emp_list['checker_list'][] = $row -> ID_users3;
            }
            
   $query = "SELECT * FROM okb_db_projects WHERE ID = $proj_id";

        $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Ошибка №2 обращения к БД в функции GetProjEmployeesList : ".$mysqli->error); 

        if( $result -> num_rows )
            $row = $result -> fetch_object() ;

     $emp_list['creator_list'][] = $row -> ID_creator ;
     $emp_list['executor_list'][] = $row -> ID_executor ;
     $emp_list['checker_list'][] = $row -> ID_checker ;
     
     $emp_list['creator_list'] = array_values( array_unique( $emp_list['creator_list'] )) ;
     $emp_list['executor_list'] = array_values( array_unique( $emp_list['executor_list'] )) ;
     $emp_list['checker_list']  = array_values( array_unique( $emp_list['checker_list'] ));
            
    return $emp_list ;
}


function GetMinDate( $d1,  $d2 )
{        
//$date1 = new DateTime($d1);
//$date2 = new DateTime($d2);

$date1 = date_create($d1);
$date2 = date_create($d2);
if( $date1 >= $date2 )
    return $d2 ;
        else
            return $d1 ;
}

function GetMaxDate( $d1,  $d2 )
{        
$date1 = date_create($d1);
$date2 = date_create($d2);
if( $date1 >= $date2 )
    return $d1 ;
        else
            return $d2 ;
}

function GetDuration( $from,  $to )
{
    $date_from = explode('.', $from);
    $date_till = explode('.', $to);
 
    $time_from = mktime(0, 0, 0, $date_from[1], $date_from[0], $date_from[2]);
    $time_till = mktime(0, 0, 0, $date_till[1], $date_till[0], $date_till[2]);
        
    $diff = ($time_till - $time_from)/60/60/24;
    
    return $diff ;
}

function GetSplitDate( $date )
{
   if( $date == 0 )
    return '';
    
  $year = substr( $date, 0, 4 );
  $month = substr( $date, 4, 2 );
  $day = substr( $date, 6, 2 );
  return $day.'.'.$month.'.'.$year ;
}

// Создать список работников
function GetUserID( $user_name )
{
    global $mysqli ;
    
    $query ="SELECT ID_resurs FROM okb_db_shtat WHERE NAME='$user_name'"; 
        
    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit( "Ошибка обращения к БД в функции GetUserID : ".$mysqli -> error ); 
    
    if( $result -> num_rows )
        $row = $result -> fetch_assoc();
    
    else 
      return $query;


    return $row['ID_resurs'] ;
}


// Создать список работников
function GetUserDivision( $user_id = 0 )
{
    global $mysqli ;
    
    $query =" SELECT ID_otdel FROM okb_db_shtat WHERE ID = $user_id "; 
        
    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit( "Ошибка обращения к БД в функции GetUserDivision : ".$mysqli -> error ); 
    
    if( $result -> num_rows )
        $row = $result -> fetch_assoc();

    return $row['ID_otdel'] ;
}



// Создать список работников
function CreateExecutorLookupCombo( $user_id = 0, $division = 0 )
{
//    global $EDIT_PROJECT_PAGE_ID, 
//    global $NEW_PROJECT_ORDER_PAGE_ID;
    global $mysqli ;
    
    $query =" SELECT * FROM okb_db_shtat WHERE 1 "; 
    
    if( $division )
          $query .=" AND ID_otdel=$division "; 

    $query .=" ORDER BY NAME";
        
    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit( "Ошибка обращения к БД в функции CreateExecutorLookupCombo : ".$mysqli -> error ); 
    
    $person = "<datalist id='person_list'><option selected value='0' disabled>---</option>";  
    
    if( $result -> num_rows )
        while( $row = $result -> fetch_assoc() )
            {
                $person .= "<option ";
                if( $row['ID_resurs'] == $user_id )                    
                   $person .= " selected ";
                
                $person .= " value='".$row['ID_resurs']."'>".$row['NAME']."</option>";
            }
            
      return $person.'</datalist>' ;
}


function CreateUIEmployeementList()
{
    global $mysqli ;
    
    $query ="
        SELECT * FROM okb_db_shtat
            WHERE 
            (
                ( ID_otdel=18 AND BOSS=1 )
                OR
                ( ID_otdel=19 AND BOSS=1 )
                OR
                ( ID_otdel=21 AND BOSS=1 )
                OR
                ( ID_otdel=22 AND BOSS=1 )
            )
            OR
            ( ID_otdel NOT IN ( 18,19,21,22 ) ) 
            AND ID_resurs <> 0
            "; 
    
    $query .=" ORDER BY NAME";
        
    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit( "Ошибка обращения к БД в функции CreateUIEmployeementList : ".$mysqli -> error ); 
    
//    $list = "<option value=''></option>";  
      $list = '';
    
    if( $result -> num_rows )
        while( $row = $result -> fetch_assoc() )
            if( $row['NAME'] != 'Вакансия ..' )
                $list .= "<option value='{$row['ID_resurs']}'>{$row['NAME']}</option>";
          
      return $list ;
}



function CreateExecutorLookupComboDataList( $user_id = 0, $division = 0 )
{
//    global $EDIT_PROJECT_PAGE_ID, 
//    global $NEW_PROJECT_ORDER_PAGE_ID;
    global $mysqli ;
    
    $query ="
        SELECT * FROM okb_db_shtat
            WHERE 
            (
                ( ID_otdel=18 AND BOSS=1 )
                OR
                ( ID_otdel=19 AND BOSS=1 )
                OR
                ( ID_otdel=21 AND BOSS=1 )
                OR
                ( ID_otdel=22 AND BOSS=1 )
            )
            OR
            ( ID_otdel NOT IN ( 18,19,21,22 ) )
            "; 
    
    if( $division )
          $query .=" AND ID_otdel=$division "; 

    $query .=" ORDER BY NAME";
        
    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit( "Ошибка обращения к БД в функции CreateExecutorLookupCombo : ".$mysqli -> error ); 
    
    $person = "<datalist size='5' id='person_list'><option selected value='0' disabled>---</option>";  
    
    if( $result -> num_rows )
        while( $row = $result -> fetch_assoc() )
            {
                $person .= "<option id='".$row['ID_resurs']."' value='".$row['NAME']."'></option>";
            }
            
      return $person.'</datalist>' ;
}


// Функция рекурсивного поиска и вставка $in_value в массив
// $in_arr['childs'] если найдено. Сравнивается $parent_key и $child_key 
function FindVal( & $in_arr , $in_value , $parent_key, $child_key, $child_field = 'childs' )
{
    $pid = $in_value[ $child_key ];
    $retval = 0 ;
    
    foreach( $in_arr AS $key => $value )
    {
        if( $value[ $parent_key ] == $pid )
        {
            array_push( $in_arr[ $key ][ $child_field ], $in_value );
            return $key ;
        }

        if( count( $in_arr[ $key ][ $child_field ] ) )
        {
            $retval = FindVal( $in_arr[ $key ][ $child_field ], $in_value, $parent_key, $child_key, $child_field );
            if( $retval !== NULL )
                return $retval ;
        }
    }
    
    return NULL ;
}

function GetColor( $color )
{
    $colors = array( 
    '#FFE4B5',
    '#FFDAB9',        
    '#EEE8AA',        
    '#F0E68C',        
    '#BDB76B',
    '#D3D3D3',        
    '#FFE4E1',        
    );
    return $colors[ $color ];
}

function GetAuthComment( $id )
{
   global $mysqli;
  
   $ret_val = '' ;
      
   $query = "SELECT `KOMM1` FROM okb_db_itrzadan WHERE ID = $id";

        $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Ошибка обращения к БД в функции GetDateByStatus : ".$mysqli->error); 

        if( $result -> num_rows )
          {
            $row = $result -> fetch_assoc();
            $ret_val = $row['KOMM1'];
          }
       
    return $ret_val ;
}

function GetDateByStatus( $id, $status )
{
   global $mysqli;
  
   $ret_val = '' ;
      
   if( $status == 'Завершено' || $status = 'Выполнено' )
   { 
   $query = "
        SELECT 
        DATE_FORMAT( DATA, '%d.%m.%Y' ) date
        FROM okb_db_itrzadan_statuses
        WHERE ID_edo=$id AND STATUS='$status' ORDER BY DATE DESC";

        $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Ошибка обращения к БД в функции GetDateByStatus : ".$mysqli->error); 

        if( $result -> num_rows )
          {
            $row = $result -> fetch_assoc();
            $ret_val = $row['date'];
          }
    }
        
    return $ret_val ;
}

function GetLastDateByStatusChange( $id )
{
   global $mysqli;
  
   $ret_val = '' ;
   
   $query = "
        SELECT 
        DATE_FORMAT( DATA, '%d.%m.%Y' ) date
        FROM okb_db_itrzadan_statuses 
        WHERE ID_edo = $id ORDER BY DATE DESC";

        $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Ошибка обращения к БД в функции GetDateByStatus : ".$mysqli->error); 

        if( $result -> num_rows )
          {
            $row = $result -> fetch_assoc();
            $ret_val = $row['date'];
          }
    return $ret_val ;
}


function GetUserResourceID( $user_id )
{
   global $mysqli;
    
    if( ! $user_id )
        return 0;
    
// Определить ID пользователя в таблице ресурсов
    $query ="
    SELECT ID FROM okb_db_resurs 
    WHERE ID_users = $user_id "; 

    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit("Ошибка обращения к БД в функции GetUserResourceID. Query :<br>$query<br>".$mysqli->error); 
  
  if( $result -> num_rows )
  {
      while ( $row = $result -> fetch_assoc() )
        $user_res_id = $row['ID'];
  }
 else 
     return 0 ;

 return $user_res_id ;
}


// Получить имя пользователя по ID из таблицы okb_db_resurs

function GetPerson( $person_id )
{
  global $dblocation, $dbname, $dbuser, $dbpasswd ;

  $dblocation = "127.0.0.1"; 
  $dbname = "okbdb"; 
  $dbuser = "root"; 
  $dbpasswd = ""; 
  
  $mysqli = new mysqli( $dblocation, $dbuser, $dbpasswd, $dbname); 
  
  if ( mysqli_connect_errno() ) 
            exit( "Connection error in ".__FUNCTION__." function in ".__FILE__." file at ".__LINE__." line. Error : ".( $mysqli->error ) );   
 
  $mysqli->query("SET NAMES 'cp1251'"); 
  
   if( $person_id == '' )
       return '';
   
   $row = '';
   
    $query = "
        SELECT persons.NAME person_name 
        FROM okb_db_resurs persons  
        WHERE ID=".$person_id;
    
        $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit( "Database access error in GetPerson function: ".$mysqli-> error ); 

        if( $result -> num_rows ) 
          {
            $row = $result -> fetch_assoc();
            $row = $row['person_name'];
          }

     return $row ;
}

function CalcProjectDivTasksCount( &$arr )
{
  $cnt = array( 
                'TotalTaskCount' => 0 , 
                'CompletedTaskCount' => 0 , 
                'FromDate' => date($arr['project_order_date_of_beg_plan']), 
                'ToDate' => date($arr['project_order_date_of_perf_plan']),
                'MaximalOrderDate' => date($arr['project_order_date_of_perf_plan']),
                'TotalDaysInTasksCount' => 0, 
                'CompletedDaysInTasksCount' => 0,             
                'state' => 0, 
                'executor_list' => array(),
                'checker_list' => array()
              );
 
  if( count( $arr['childs'] ))
  {
     foreach( $arr['childs'] AS $key => $value )
        {
            $tmp_cnt = CalcProjectDivTasksCount( $arr['childs'][$key] );
            $cnt['TotalTaskCount'] += $tmp_cnt['TotalTaskCount'];
            $cnt['CompletedTaskCount'] += $tmp_cnt['CompletedTaskCount']; 
  
            $cnt['TotalDaysInTasksCount'] += $tmp_cnt['TotalDaysInTasksCount'];            
            $cnt['CompletedDaysInTasksCount'] += $tmp_cnt['CompletedDaysInTasksCount'];            
           
            $cnt['executor_list'][] = array_merge( $cnt['executor_list'], $tmp_cnt['executor_list'] );
            $cnt['checker_list'][] = array_merge( $cnt['checker_list'], $tmp_cnt['checker_list'] );
            $cnt['state'] = $tmp_cnt['state'];

            if( date( $cnt['MaximalOrderDate'] ) < date( $tmp_cnt['ToDate'] ) || $cnt['MaximalOrderDate'] == '' )
                $cnt['MaximalOrderDate'] = $tmp_cnt['ToDate'];
        }
  }

  $cnt['TotalTaskCount'] ++ ;
  $cnt['TotalDaysInTasksCount'] += GetDuration( $cnt['FromDate'], $cnt['ToDate'] );  
 
  if( $arr['project_order_status'] == 'Выполнено' || $arr['project_order_status'] == 'Принято' || $arr['project_order_status'] == 'Завершено' )
  {
      $cnt['CompletedTaskCount'] ++ ;
      $cnt['CompletedDaysInTasksCount'] += GetDuration( $cnt['FromDate'], $cnt['ToDate'] );
  }
  
  if( isset( $cnt['CompletedDaysInTasksCount'] ) &&  isset( $arr['CompletedDaysInTasksCount'] ) ) 
    $arr['CompletedDaysInTasksCount'] += $cnt['CompletedDaysInTasksCount'] ;   
 
  $cnt['executor_list'][] = $arr['project_order_executor'];
  $cnt['checker_list'][] = $arr['project_order_checker'];
    
  return $cnt ;
}

function CalcProjectsTaskCount( &$arr )
{
    foreach( $arr AS $key => $value )
    {
        $totalDaysInTasks = 0 ;
        $completedDaysInTasks = 0 ;
        $date_of_beg = '';
        $date_of_perf = '';
        
        if( isset( $arr['project_order_date_of_beg_plan'] ) )
            $date_of_beg = date($arr['project_order_date_of_beg_plan']);

        if( isset( $arr['project_order_date_of_beg_plan'] ) )
            $date_of_perf = date($arr['project_order_date_of_perf_plan']) ;
        
        $cnt = array( 
                        'TotalTaskCount' => 0 , 
                        'CompletedTaskCount' => 0 , 
                        'FromDate' => $date_of_beg, 
                        'ToDate' => $date_of_perf,
                        'MaximalOrderDate' => $date_of_perf,            
                        'TotalDaysInTasksCount' => 0, 
                        'CompletedDaysInTasksCount' => 0,             
                        'state' => 0, 
                        'executor_list' => array(),
                        'checker_list' => array(),
                                );
        foreach( $arr[$key]['childs'] AS $ekey => $evalue )
        {
            $tmp_cnt = CalcProjectDivTasksCount( $arr[$key]['childs'][$ekey] );
            $cnt['TotalTaskCount'] += $tmp_cnt['TotalTaskCount'];
            $cnt['CompletedTaskCount'] += $tmp_cnt['CompletedTaskCount'];
            
            if( date( $cnt['MaximalOrderDate'] ) < date( $tmp_cnt['MaximalOrderDate'] ) )
                $cnt['MaximalOrderDate'] = $tmp_cnt['MaximalOrderDate'];
            
            $cnt['TotalDaysInTasksCount'] += $tmp_cnt['TotalDaysInTasksCount'];
            $cnt['CompletedDaysInTasksCount'] += $tmp_cnt['CompletedDaysInTasksCount'];
            
            $cnt['executor_list'] = array_merge( $cnt['executor_list'], $tmp_cnt['executor_list']) ;
            $cnt['checker_list'] = array_merge( $cnt['checker_list'], $tmp_cnt['checker_list']) ;
        }

        $arr[$key]['TotalTaskCount'] = $cnt['TotalTaskCount'] ;
        $arr[$key]['CompletedTaskCount'] = $cnt['CompletedTaskCount'] ;

        $arr[$key]['TotalDaysInTasksCount'] = $cnt['TotalDaysInTasksCount'] ;
        $arr[$key]['CompletedDaysInTasksCount'] = $cnt['CompletedDaysInTasksCount'] ;  
        $arr[$key]['MaximalOrderDate'] = $cnt['MaximalOrderDate'] ;          

        $exec_list = array();
        arrayGathering( $cnt['executor_list'] , $exec_list );
        arrayGathering( $arr[$key]['executor_list'] , $exec_list );        
        $exec_list = array_unique ( $exec_list ) ;

        $check_list = array();
        arrayGathering( $cnt['checker_list'] , $check_list );
        arrayGathering( $arr[$key]['checker_list'] , $check_list );
        $check_list = array_unique ( $check_list ) ;

        $arr[$key]['executor_list'] = $exec_list ;
        $arr[$key]['checker_list']  = $check_list;
     }
}

function arrayGathering( $in_arr, &$end_arr )
{
    if( is_array( $in_arr ))
    {
        foreach( $in_arr AS $key => $value )
        {
            if( is_array( $value ))
                    arrayGathering( $value, $end_arr );
            else
                $end_arr[] = $value ;
        }
    }
    else
        $end_arr[] = $in_arr ;

}

?>

