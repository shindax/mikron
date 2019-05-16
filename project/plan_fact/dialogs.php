<?php
require_once( "functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.PlanFactCollector.php" );

$SelStage = conv("Выберите нужный этап");
$SelStatus = conv("Выберите нужный статус");

$from_date = conv("C даты");
$to_date = conv("По дату");
$applyFlter = conv("Применить фильтр");
$resetFlter = conv("Сбросить фильтр");
$printFlter = conv("Распечатать\nотфильтрованные\nданные");

// Элемент для AJAX, ожидание ответа от сервера
$waiting_img = "<img id='loadImg' src='project/img/loading_2.gif' />";

//       <img src='uses/del.png'/>
$form_div = "
<div id='form_div' class='hidden'>
<img src='/uses/upload.svg' class='show_img hidden' title='".conv("Показать фильтр")."'/>
  <div class='sel_div'>
  <img src='/uses/download.svg' class='hide_img' title='".conv("Скрыть фильтр")."' />
  <dl data-changed='0' id='dropdownStage' class='dropdown'>
     <dt>
    <a href='#'>
      <span class='hida'>$SelStage</span>
      <span class='multiSel'></span>
      <span class='arrow'>&#9660;</span>
    </a>
    </dt>
    <dd>
        <div class='mutliSelect'>".getStagesList()."</div>
   </dd>
</dl>
  <dl data-changed='0' id='dropdownStatus' class='dropdown'>
     <dt>
    <a href='#'>
      <span class='hida'>$SelStatus</span>
      <span class='multiSel'></span>
      <span class='arrow'>&#9660;</span>
    </a>
    </dt>

    <dd>
        <div class='mutliSelect'>".getStatusesList()."</div>
    </dd>
</dl>
  </div>

  <div class='ord_type_div'>
  <div><input name='ord_type' type='radio' value='1'>".conv("ОЗ и др.")."</div>
  <div><input name='ord_type' type='radio' value='2'>".conv("ХЗ только")."</div>
  <div><input name='ord_type' type='radio' value='3'>".conv("БЗ только")."<br></div>
  <div><input name='ord_type' type='radio' value='4'>".conv("Без КД")."<br></div>
  </div>

  <div class='radio_div'>
  <div><input name='radio' type='radio' value='1'>".conv("Дата заключения договора")."</div>
  <div><input name='radio' type='radio' value='2'>".conv("Дата открытия заказа")."</div>
  <div><input name='radio' type='radio' value='3'>".conv("Дата планируемой отгрузки")."<br></div>
  <div><input name='radio' type='radio' value='4'>".conv("Начало производства")."<br></div>  
  <div><input name='radio' type='radio' value='5'>".conv("Окончание производства")."<br></div>    
  </div>

  <div class='date_wrap_div'>
  <div class='date_div'><input id='from_date'/><span>$from_date</span></div>
  <div class='date_div'><input id='to_date'/><span>$to_date</span></div>
  </div>

  <div class='but_filter_div noborders'>
  <button id='print_filter_button' class='ui-button ui-widget ui-corner-all ui-button-icon-only' title='$printFlter'>
    <span class='ui-icon ui-icon-print'></span>
  </button>
  </div>

  <div class='but_filter_div noborders'>
  <button id='reset_date_filter_button' class='ui-button ui-widget ui-corner-all ui-button-icon-only' title='$applyFlter'>
  <span class='ui-icon ui-icon-close'></span>
  </button>
  </div>

  <div class='but_filter_div'>
  <button id='reset_filter_button' class='ui-button ui-widget ui-corner-all ui-button-icon-only' title='$resetFlter'>
  <span class='ui-icon ui-icon-closethick'></span>
  </button>
  </div>

  <div id='find_div'><input id='find' /></div>

  <!--div class='radio_div'>
  <div><input name='sort' type='radio' value='1'>".conv("Сортировать по этапу")."</div>
  <div><input name='sort' type='radio' value='2'>".conv("Сортировать по статусу")."</div>
  </div-->

</div>
";

$main_div_beg = "<div id='main_div'><div id='head_div'>
<h1 id='rep_head'>".conv("Заказы в работе: План / Факт")."</h1></div>";
$main_div_end = "</div>";

$table_div_beg = "<div id='table_div'>";
$table_div_end = "</div>";

$confirm_prepare_datalist = join(",", getResponsiblePersonsID( PlanFactCollector::PREPARE_GROUP ));
$confirm_equipment_datalist = join(",", getResponsiblePersonsID( PlanFactCollector::EQUIPMENT_GROUP ));
$confirm_cooperation_datalist = join(",", getResponsiblePersonsID( PlanFactCollector::COOPERATION_GROUP ));
$confirm_production_datalist = join(",", getResponsiblePersonsID( PlanFactCollector::PRODUCTION_GROUP ));
$confirm_commertion_datalist = join(",", getResponsiblePersonsID( PlanFactCollector::COMMERTION_GROUP ));

// Диалог подтверждения переноса даты
$time_shift_confirm_dialog  = 
"
<div id='time_shift_confirm_dialog' title='".conv("Подверждение переноса даты")."'>
  <p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>
  ".conv("Перенос даты будет подвержден. Вы уверены?")."</p>
</div>
";

// Диалог при изменении статуса
$change_status_dialog  = "
<div id='status_change_dialog' class='hidden' title='".conv("Изменение статуса").".'>

  <div id='confirm_head_span_div' class='hidden'><span class='confirm_head_span'>".conv("Подтверждение смежных служб")."</span></div>

  <div class='confirm_checkbox_div_wrap'>
  
  <div class='confirm_checkbox_div hidden'>
  <input data-list='$confirm_prepare_datalist' data-dir='".(PlanFactCollector::PREPARE_GROUP)."' class='confirm_checkbox' data-id='' data-field='' id='confirm_prepare_checkbox' type='checkbox'><span>".conv("Подготовка производства")."</span></div>
  
  <div class='confirm_checkbox_div hidden'>
  <input data-list='$confirm_equipment_datalist' data-dir='".( PlanFactCollector::EQUIPMENT_GROUP )."' class='confirm_checkbox' data-id='' data-field='' id='confirm_equipment_checkbox' type='checkbox'><span>".conv("Комплектация")."</span></div>
  
  <div class='confirm_checkbox_div hidden'>
  <input  data-list='$confirm_cooperation_datalist' data-dir='".( PlanFactCollector::COOPERATION_GROUP )."' class='confirm_checkbox' data-id='' data-field='' id='confirm_cooperation_checkbox' type='checkbox'><span>".conv("Кооперация")."</span></div>
  
  <div class='confirm_checkbox_div hidden'>
  <input  data-list='$confirm_production_datalist' data-dir='".( PlanFactCollector::PRODUCTION_GROUP )."' class='confirm_checkbox' data-id='' data-field='' id='confirm_production_checkbox' type='checkbox'><span>".conv("Производство")."</span></div>
  
  <div class='confirm_checkbox_div hidden'>
  <input data-list='$confirm_commertion_datalist' data-dir='".( PlanFactCollector::COMMERTION_GROUP )."' class='confirm_checkbox' data-id='' data-field='' id='confirm_commertion_checkbox' type='checkbox'><span>".conv("Коммерция")."</span></div>
  </div>

  <div id='status_change_dialog_note'><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span><span>".conv("Статус будет изменен. Вы уверены?")."</span></div>

  <div>
  </div>
</div>";

// Диалог перечня изменений дат
$change_list_dialog  = "<div id='change_list_dialog' class='hidden' title='".conv("Список изменений").".'>
  <div id='change_list_flex_capt'>
  <table id='change_list_table_fix'>

  <col width='3%'>
  <col width='5%'>
  <col width='10%'>
  <col width='5%'>
  <col width='10%'>  
  <col width='1%'>   
  <col width='20%'>

  <tr class='first'>
  <td class='AC'>".conv("№")."</td>
  <td class='AC'>".conv("Дата<br>изменения")."</td>
  <td class='AC'>".conv("Инициатор")."</td>
  <td class='AC'>".conv("Новая дата")."</td>
  <td class='AC'>".conv("Причина")."</td>  
  <td class='AC'>".conv("Подтв")."</td>
  <td class='AC'>".conv("Комментарий")."</td>
  </tr></table></div>
  <div id='change_list_table_div'></div>
</div>";

// ".date("d.m.Y")."
// Диалог изменения даты
$change_date_dialog  = "<div id='change_date_dialog' class='hidden' title='".conv("Изменение даты").".'>
                        <span class='change_date_dialog_span'>".conv("Новая дата : ")."</span><span class='change_date_dialog_span' id='change_date_current_date_span'></span>
                        <div id='change_date_dialog_calendar'></div>
                        <div id='change_date_dialog_calendar_info'>
                        <span class='change_date_dialog_span'>".conv("Причина изменения")."</span>
                        <select id='change_date_dialog_select'>
                          <option value='0'>...</option>
                          <option value='1'>1.</option>
                          <option value='2'>2.</option>
                          </select>
                        <span>".conv("Комментарий")."</span>
                        <textarea id='change_date_dialog_textarea'></textarea>
                        </div>
                        </div>";


