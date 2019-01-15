<script type="text/javascript" src="/project/materials_use/js/materials_use.js?val=0"></script>
<script type="text/javascript" src="/project/materials_use/js/jquery-ui.min.js"></script>

<link rel='stylesheet' href='/project/materials_use/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/materials_use/css/style.css' type='text/css'>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );

error_reporting( E_ALL );
ini_set('display_errors', true);

// error_reporting(0);
// ini_set('display_errors', false);

global $user ;
$user_id = $user['ID'];

$head_title = "<div class='head'>
                            <div><h2>".conv( "Использование материалов")."</h2></div>
                      </div>";

$select_group = "
<div class='btn-toolbar mb-3' role='toolbar' aria-label='Toolbar with button groups'>

  <div class='input-group'>
    <div class='input-group-prepend'>
      <div class='label'>".conv("Материал :")."</div>
    </div>
    <select id='mat_sel'>".get_mat_options()."</select>
  </div>

  <div class='input-group'>
    <div class='input-group-prepend'>
      <div class='label'>".conv("Сортамент :")."</div>
    </div>
    <select id='sort_sel'>".get_sort_options()."</select>
  </div>

  </div>
</div>";

$str = $head_title ;
$str .= $select_group;

$str .= "<div id='main_div'>";
// $data = get_data( 206, 1802 );
// _debug( $data );
// $str .= get_table( $data );
$str .= "</div>";
echo $str ;
