<script type="text/javascript" src="/project/service_note/js/constants.js?val=0"></script>
<script type="text/javascript" src="/project/service_note/js/jquery.monthpicker.js?val=0"></script>
<script type="text/javascript" src="/project/service_note/js/service_note.js?val=0"></script>
<script type="text/javascript" src="/project/service_note/js/jquery-ui.min.js"></script>

<link rel='stylesheet' href='/project/service_note/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/service_note/css/style.css' type='text/css'>


<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/project/service_note/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.ServiceNoteTable.php" );

// error_reporting( E_ALL );
// ini_set('display_errors', true);

error_reporting(0);
ini_set('display_errors', false);

global $user, $pdo;

$user_id = $user['ID'];
$res_id = GetResInfo( $user_id );
$can_edit = 0 ;
$can_delete = 0 ;

if( $res_id == 679 || $res_id == 620 || $res_id == 1101 || $user_id == 1 || $user_id == 305 || $user_id == 328 ) 
  $can_edit = 1 ;

if( $res_id == 620 || $user_id == 1 )
  $can_delete = 1 ;  

echo "<script>let res_id=$res_id, can_edit = $can_edit, can_delete = $can_delete</script>";

$cur_month = date("m"); 
$cur_year = date("Y"); 
$pages = GetPagesArr( $cur_year, $cur_month );
$res_list = GetResList();

$str = "<script>var images_path = '/project/".($files_path."/service_note@FILENAME/")."';
                  var user_id = $user_id;
                  </script>";

$str .= "<div class='container'>";

$head_title = "<div class='row'><div class='col-sm-12'><div class='head'>
                            <div><h2>".conv( "Служебные записки")."</h2></div>
                      </div></div></div>";

$button_group = "
<div class='row'><div class='col-sm-10'>
<div class='btn-toolbar mb-3' role='toolbar' aria-label='Toolbar with button groups'>

  <div class='btn-group mr-2' role='group' aria-label='First group'>
    <button class='btn btn-small btn-primary' type='button' id='add_note' ".( $can_edit ? '' : 'disabled').">".conv('Добавить записку')."</button>
  </div>

  <div class='input-group'>
    <div class='input-group-prepend'>
      <div class='input-group-text' id='monthGroupAddon'>".conv("Выберите месяц")."</div>
    </div>
    <input type='text' id='monthpicker' class='form-control' aria-label='Month group' aria-describedby='monthGroupAddon'>
  </div>
  
  <div class='input-group'>
    
    <div class='input-group-prepend'>
      <div class='input-group-text' id='btnGroupAddon'>".conv("Поиск по описанию в листе")."</div>
    </div>

    <input type='text' id='find_input' class='form-control' aria-label='Find group' aria-describedby='btnGroupAddon'>

  <button class='btn btn-small btn-primary' type='button' id='find_button'>".conv('Показать все')."</button>

  </div>
</div>
</div>

  <div class='col-sm-2'>
      <div class='input-group-prepend'>
        <div><span class='prfound'>".conv("Найдено записок : ")."</span><span class='found'>".count( $pages )."</span></div>
      </div>
  </div>

</div>";

$str .= "<input id='upload_file_input' type='file' accept='.pdf,.jpg,.jpeg,image/*' class='hidden' />" ;
$str .= "<div class='hidden' id='loadImg' class='hidden'><img src='project/img/loading_2.gif' width='200px'></div>";

$str .= "<div id='receivers_dialog' class='hidden' title='".conv("Получатели")."'>
      <div>
        <div>
          <select id='receivers_select_from' size='10' multiple>
          $res_list
           </select>
        </div>
        <div>
        <button id='add_to_receivers'><img class='icon' src='/uses/svg/arrow-right.svg' /></button>
        <button id='remove_from_receivers'><img class='icon' src='/uses/svg/arrow-left.svg' /></button>
        </div>
        <div>
            <select id='receivers_select_to' size='10' multiple>
            </select>
        </div>
        </div>
    </div>";

$str .= $head_title ;
$str .= $button_group;
$str .= "<div id='main_div'>";

$str .= "</div>";
$str .= "</div>"; // container



echo $str ;

