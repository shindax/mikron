<?php
require_once("CommonFunctions.php");

$user_id    = $_POST['user_id'];
$proj_id    = $_POST['proj_id'];
$parent_id  = $_POST['parent_id'] ; 
$offset     = $_POST['offset'] ; 
$level      = $_POST['level'] ; 
$in_name    = $_POST['in_name'] ; 
$row_id     = $_POST['row_id'] ; 
$padding = ( $offset == '5px' ) ? '5px' : '10px';

$from_date = '';
$to_date = '';

if( $proj_id == $parent_id  ) // Родительская запись - проект
{
   $query = "SELECT `beg_date_plan`, `end_date_plan`FROM okb_db_projects WHERE ID = $proj_id";

        $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Ошибка №1 обращения к БД в функции InsertOneRowAJAX: ".$mysqli->error); 

        if( $result -> num_rows )
            if ( $row = $result -> fetch_object() )
            {
                $from_date = substr( $row -> beg_date_plan,0,4 ).'-'.substr( $row -> beg_date_plan,4,2 ).'-'.substr( $row -> beg_date_plan,6,2 );
                $to_date = substr( $row -> end_date_plan,0,4 ).'-'.substr( $row -> end_date_plan,4,2 ).'-'.substr( $row -> end_date_plan,6,2 );
                
                $title = "Минимальная дата: ".substr( $row -> beg_date_plan,6,2 ).'.'.substr( $row -> beg_date_plan,4,2 ).'.'.substr( $row -> beg_date_plan,0,4 )."\nМаксимальная дата : ".substr( $row -> end_date_plan,6,2 ).'.'.substr( $row -> end_date_plan,4,2 ).'.'.substr( $row -> end_date_plan,0,4 );
            }
}
else
{
   $query = "SELECT `STARTDATE`, `DATE_PLAN`FROM okb_db_itrzadan WHERE ID_proj = $proj_id AND ID=$parent_id";

        $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Ошибка №2 обращения к БД в функции InsertOneRowAJAX : ".$mysqli->error); 

        if( $result -> num_rows )
            if ( $row = $result -> fetch_object() )
            {
                $from_date = substr( $row -> STARTDATE,0,4 ).'-'.substr( $row -> STARTDATE,4,2 ).'-'.substr( $row -> STARTDATE,6,2 );
                $to_date = substr( $row -> DATE_PLAN,0,4 ).'-'.substr( $row -> DATE_PLAN,4,2 ).'-'.substr( $row -> DATE_PLAN,6,2 );
                
                $title = "Минимальная дата: ".substr( $row -> STARTDATE,6,2 ).'.'.substr( $row -> STARTDATE,4,2 ).'.'.substr( $row -> STARTDATE,0,4 )."\nМаксимальная дата : ".substr( $row -> DATE_PLAN,6,2 ).'.'.substr( $row -> DATE_PLAN,4,2 ).'.'.substr( $row -> DATE_PLAN,0,4 );
            }
}
        
//$person      = CreateExecutorLookupComboDataList() ;
$auth_person = CreateExecutorLookupCombo( GetUserResourceID( $user_id ));

$employeement_list = "<option value=''></option>".CreateUIEmployeementList();

$exec_list = "
    <select id='one_row_executor' class='combobox'>$employeement_list</select>
    <ul id='one_row_executor_ul'></ul>";

$check_list = "
    <select id='one_row_checker' class='combobox'>$employeement_list</select>";

$res_user_id = GetUserResourceID( $user_id );

$bullet = "<span style='padding-left:$offset;margin-right:5px'>&#9899;</span>";
$name = $bullet."<input class='one_row_data' id='one_row_order_name' name='SpanName' />";

$date_of_beg_plan = '';
$date_of_perf_plan = '';
$date_of_perf_fact = '';

$str = "
    <tr id='temp_row' data-rowid='$row_id'>
    <td class='field AR'>
    </td>
    <td class='field AC' style='padding-left:0px'><img title='Удалить' id='del_one_row_button' src='/uses/del.png'/></td>    
    <td class='field AL rwField ntabg' style='padding-left:$padding'>
    $name
    </td>
    <td class='field AC rwField ntabg'><input id='one_row_date1' type='date' title='$title' min='$from_date' max='$to_date' class='one_row_data one_row_data_err' value=''/></td>      
    <td class='field AC rwField ntabg'><input id='one_row_date2' type='date' title='$title' min='$from_date' max='$to_date' class='one_row_data one_row_data_err' value=''/></td>
    <td class='field AC'><input id='one_row_date3' type='text' class='one_row_data' value='' disabled/></td>     
    <td class='field AC'>
    <select id='one_row_creator' style='width:100%' size='1' disabled>$auth_person</select>
    </td>
    <td class='field AC'></td>
    <td class='field ntabg'>
    <div id='ui-widget_executor' class='ui-widget'>$exec_list</div>
    </td>
    <td class='field ntabg'>
    <div id='ui-widget_checker' class='ui-widget'>$check_list</div>    
    </td>
    <td class='field ntabg' colspan='2'>
    <textarea id='onerowtextarea' class='onerowtextarea' rows='2'></textarea></td>
    </td>
    <td class='field AC'>
    <input id='onerowbutton' type='button' style='width:100%' disabled onclick='addOrder( $proj_id , $user_id, $parent_id, $level, \"$in_name\", \"$row_id\" )' value='Добавить'></input>
    </td>
    </tr>";

//  echo iconv("Windows-1251", "UTF-8", $str );
  echo $str ;

?>

