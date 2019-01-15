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
      <div class='label'>".conv("Категория материалов :").get_mat_cat_count( 0 )."</div>
    </div>
    <select id='mat_cat_sel'>".get_mat_cat_options()."</select>
  </div>

  <div class='input-group'>
    <div class='input-group-prepend'>
      <div class='label'>".conv("Подкатегория материалов :")."<span id='submat_cat_span'>0</span></div>
    </div>
    <select id='submat_cat_sel'><option value='0'>...</option></select>
  </div>

  <div class='input-group'>
    <div class='input-group-prepend'>
      <div class='label'>".conv("Материал :")."<span id='mat_span'>0</span></div>
    </div>
    <select id='mat_sel'><option value='0'>...</option></select>
  </div>

  </div>
</div>";

$select_group .= "
<div class='btn-toolbar mb-3' role='toolbar' aria-label='Toolbar with button groups'>
  <div class='input-group'>
    <div class='input-group-prepend'>
      <div class='label'>".conv("Категория сортамента :").get_sort_cat_count( 0 )."</div>
    </div>
    <select id='sort_cat_sel'>".get_sort_cat_options()."</select>
  </div>

  <div class='input-group'>
    <div class='input-group-prepend'>
      <div class='label'>".conv("Подкатегория сортамента :")."<span id='subsort_cat_span'>0</span></div>
    </div>
    <select id='subsort_cat_sel'><option value='0'>...</option></select>
  </div>

  <div class='input-group'>
    <div class='input-group-prepend'>
      <div class='label'>".conv("Сортамент :")."<span id='sort_span'>0</span></div>
    </div>
    <select id='sort_sel'><option value='0'>...</option><option value='1'>1</option></select>
  </div>
   
  </div>
</div>";

echo $head_title ;
echo $select_group;

echo "<div id='main_div'>";
echo "</div>";

