<script type="text/javascript" src="/project/entrance_control/js/constants.js?val=0"></script>
<script type="text/javascript" src="/project/entrance_control/js/jquery.monthpicker.js?val=0"></script>
<script type="text/javascript" src="/project/entrance_control/js/entrance_control.js?val=0"></script>
<script type="text/javascript" src="/project/entrance_control/js/jquery-ui.min.js"></script>

<link rel='stylesheet' href='/project/entrance_control/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/entrance_control/css/style.css' type='text/css'>


<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

//error_reporting( E_ALL );
//ini_set('display_errors', true);

error_reporting(0);
ini_set('display_errors', false);

global $user ;
$user_id = $user['ID'];

$cur_month = date("m"); 
$cur_year = date("Y"); 
$pages = GetPagesNumArr( $cur_year, $cur_month );

echo "<script>var images_path = '/project/".($files_path."/db_entrance_control@FILENAME/")."';
                  var user_id = $user_id;
                  </script>";


$head_title = "<div class='head'>
                            <div><h2>".conv( "Листы входного контроля")."</h2></div>
                      </div>";

$button_group = "
<div class='btn-toolbar mb-3' role='toolbar' aria-label='Toolbar with button groups'>

  <div class='btn-group mr-2' role='group' aria-label='First group'>
    <button class='btn btn-small btn-primary' type='button' id='add_page' disabled>".conv('Добавить лист')."</button>
  </div>

  <div class='input-group'>
    <div class='input-group-prepend'>
      <div class='input-group-text' id='monthGroupAddon'>".conv("Выберите месяц")."</div>
    </div>
    <input type='text' id='monthpicker' class='form-control' aria-label='Month group' aria-describedby='monthGroupAddon'>
  </div>
  
  <div class='input-group'>
    
    <div class='input-group-prepend'>
      <div class='input-group-text' id='btnGroupAddon'>".conv("Поиск по ДСЕ, или номеру заказа")."</div>
    </div>

    <input type='text' id='find_input' class='form-control' aria-label='Find group' aria-describedby='btnGroupAddon'>

  <button class='btn btn-small btn-primary' type='button' id='find_button' disabled>".conv('Найти')."</button>

      <div class='input-group-prepend'>
      <div><span class='found'>".conv("Найдено листов : ").count( $pages )."</span></div>
    </div>
  </div>

</div>";

echo "<input id='upload_file_input' type='file' accept='.pdf,.jpg,.jpeg,image/*' class='hidden' />" ;
echo "<div id='comment_dialog' class='hidden' data-key='' data-field=''>
                        <span class='change_date_dialog_span'>".conv("Количество : ")."</span>
                        <input id='dialog_count' />
                        <span class='change_date_dialog_span'>".conv("Комментарий")."</span>
                        <input  id='dialog_comment' />
                        </div>";

echo "<div class='hidden' id='loadImg' class='hidden'><img src='project/img/loading_2.gif' width='200px'></div>";

echo $head_title ;
echo $button_group;

echo "<div id='main_div'>";

$line = 1 ;
foreach( $pages AS $page )
{

	$ec = new EntranceControl( $pdo, $page );
	if( $user_id == 130 || $user_id == 224 )
		$ec -> EnableImageDeleting();

  $ec -> HtmlPageNum( $line ++ );
	echo $ec -> GetTable();
}

echo "</div>";

