<?php
// Диалог при удалении изображения
$delete_image_dialog  = "<div id='dialog' title='".conv("Удаление изображения").".'>
  <p><span class='ui-icon ui-icon-alert'></span>".conv("Это изображение будет удалено, Вы сможете загрузить его позже. Вы уверены?")."</p>
</div>";

// Диалог переноса даты из Проект плана в План
$replace_date_dialog  = "
<div id='replace_date_dialog' class='hidden' title='".conv("Установка новой даты").".'>
  <div><span class='ui-icon ui-icon-alert'></span><p>".conv("Вы уверены, что готовы согласовать проект плана?")."</p></div>
</div>";

// Диалог добавления комментария к дате из Проекта плана
$project_plan_date_comment_dialog  = "
<div id='project_plan_date_comment_dialog' class='hidden' title='".conv("Комментарий к дате").".'>
  <div>
  <div id='prev_comments'></div>
  <span>".conv("Введите комментарий")."</span>
  <input class='comment_input'>
  </div>
</div>";


// Диалог просмотра изображений
$view_image_dialog =  "<div class='my_popup' data-id=''>
<div class='my_close_window'><div class='x_div'>&#215;</div></div>
<div class='img_main_div'>

  <div class='left_arrow'>
  <img class='img_gal left_arr_img' src='/project/protocol_images/css/but_l_blue.png' />
  </div>
  <div class='img_div'>
  <div class='gal_caption'><span class='gal_caption_span'>1 ". conv("Zzz")." 1</span></div>
  <img id='image' width='400px' height='565' src='' />
  </div>
  <div class='right_arrow'><img class='img_gal right_arr_img' src='/project/protocol_images/css/but_r_blue.png' /></div>

  <div style = 'clear: both'></div>

  <div class='left_bot'><img class='img_gal but_load' data-id='' src='/project/protocol_images/css/but_load.png' title='".conv("Загрузить изображение'")."/></div>
  <div class='gal_info'><span class='gal_info_span'>1 ". conv("из")." 1</span></div>
  <div class='right_bot'><img class='img_gal but_del' data-id='' src='/project/protocol_images/css/but_del.png' title='".conv("Удалить текущее изображение")."' /></div>

</div>

</div>";

$date_div = "<div id='date_div'><h1 id='rep_head'>".conv("План-отчет за")."</h1>

<div class='legend_div'><span>".conv("Выберите месяц и год отчета : ")."</span></div>
<input id='ref_date' class='year' type='text' id='' title='".conv("Выберите месяц и год")."'/>
<a class='alink' target='_blank'>".conv("Распечатать")."</a>
<hr>
</div>".$replace_date_dialog.$project_plan_date_comment_dialog;


// Элемент для загрузки файлов
$load_file_element = "<input id='upload_file_input' data-id='' data-what='' type='file' accept='.jpg,image/*' class='hidden' multiple>" ;

$load_file_waiting = '<img id="loadImg" src="project/img/loading_2.gif" />';
