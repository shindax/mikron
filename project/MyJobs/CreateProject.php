<script type="text/javascript" src="/project/MyJobs/js/jquery-ui.js"></script>
<script type="text/javascript" src="/project/MyJobs/js/my_autocomplete.js"></script>
<script type="text/javascript" src="/project/MyJobs/js/create_project.js"></script>

<link rel="stylesheet" href="/project/MyJobs/css/jquery-ui.css">
<link rel="stylesheet" href="/project/MyJobs/css/my_autocomplete.css">
<link rel="stylesheet" href="/project/MyJobs/css/myCSS.css">

<?php
include("CommonFunctions.php");

global $PROJECT_PAGE_ID;

$right_groups = $user['ID_rightgroups'];
$user_id = $user['ID'];
$right_groups = explode('|', $right_groups );

$can_work = 0 ;
$s_admin = 0 ;

$auth_person = 'Недостаточно прав для создания проекта';

$employeement_list = "<option value=''></option>".CreateUIEmployeementList();

$exec_list = "
    <select id='exec_list' class='combobox' name='p4'>$employeement_list</select>";

$check_list = "
    <select id='check_list' class='combobox' name='p5'>$employeement_list</select>";


foreach( $right_groups AS $val )
    {
        if( ( $val == '68' ) || ( $val == 1 ) )
          {
            $can_work = 1 ;
            $auth_person = GetPerson( GetUserResourceID( $user_id ));
            echo "<script>var can_add = 1 ;</script>";
          }
          else
                echo "<script>var can_add = 0 ;</script>";
        if( $val == '1' )
            $s_admin = 1 ;
    }
   

$user_res_id = GetUserResourceID( $user_id );
  
if( isset( $_POST['p0'] ))
{
    $proj_name  = $_POST['p0'];
    $proj_descr  = $_POST['p1'];
    
    $executor = $_POST['p4'];
    $checker = $_POST['p5'];

    $beg_date_plan_str = explode( '.', $_POST['p2']  );
    $beg_date_plan_str = $beg_date_plan_str[2].$beg_date_plan_str[1].$beg_date_plan_str[0];
    
    $end_date_plan_str = explode( '.', $_POST['p3'] ) ;
    $end_date_plan_str = $end_date_plan_str[2].$end_date_plan_str[1].$end_date_plan_str[0];
    
    $query ="INSERT INTO 
    okb_db_projects( name , beg_date_plan, end_date_plan, ID_creator, ID_executor, ID_checker, descr ) 
    VALUES( '$proj_name', '$beg_date_plan_str', '$end_date_plan_str', $user_res_id, $executor, $checker,'$proj_descr' )"; 
    $result = $mysqli -> query( $query );
    
    if( ! $result ) 
        exit("Ошибка обращения к БД №2 в файле CreateProject.php : ".$mysqli->error); 

//    header( "Location: http://".$_SERVER['HTTP_HOST']."/index.php?do=show&formid=".$PROJECT_PAGE_ID ) ;    


   $location = "http://".$_SERVER['HTTP_HOST']."/index.php?do=show&formid=".$PROJECT_PAGE_ID  ; 
   echo "<script> window.location = '$location'; </script>";
}
else 
{
echo "<h1>Создание нового проекта</h1>";
echo "<a href='http://".$_SERVER['HTTP_HOST']."/index.php?do=show&formid=".$PROJECT_PAGE_ID."'>К проектам</a><br><br>";
echo "</form>\n";
echo "<form method='post'>";

echo "<table class='tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 700px;' border='1' cellpadding='0' cellspacing='0'>\n";
echo "<tr class='first'>\n";
echo "<td width='250'></td>";
echo "<td></td>";
echo "</tr>\n";

echo "<tr><td class='Field first'><b>Название проекта</td><td class='rwField ntabg'>
<input class='my_input' id='proj_name' name='p0'></td>";
echo "</td></tr>";

echo "<tr><td class='Field first'><b>Описание проекта</td><td class='rwField ntabg'>
<textarea id='proj_descr' rows='4' name='p1'></textarea></td>";
echo "</td></tr>";

echo "<tr><td class='Field first'><b>Дата начала:</b></td><td class='rwField ntabg'>";
	Input("date","p2",'');
echo "</td></tr>\n";

echo "<tr><td class='Field first'><b>Дата окончания:</b></td><td class='rwField ntabg'>";
	Input("date","p3", '');
echo "</td></tr>\n";

echo "<tr><td class='Field first'><b>Автор:</b></td><td class='Field'>";
echo $auth_person ;
echo "</td></tr>";

echo "<tr><td class='Field first'><b>Руководитель:</b></td><td class='Field'>";
echo $exec_list ;
echo "</td></tr>";

echo "<tr><td class='Field first'><b>Контролер:</b></td><td class='Field'>";
echo $check_list ;
echo "</td></tr>";

echo  "</table>";
echo "<br><table style='width: 700px;'><tr><td style='text-align: right;'><input id='create_button' type='submit' disabled value='&nbsp;Создать проект&nbsp;'></td></tr></table>";
echo "</form>";
}
?>
