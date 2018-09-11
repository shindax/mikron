<?php
require_once( "functions.php" );

$SelStage = conv("Выберите нужный этап");
$SelStatus = conv("Выберите нужный статус");

$from_date = conv("C даты");
$to_date = conv("По дату");
$applyFlter = conv("Применить фильтр");
$resetFlter = conv("Сбросить фильтр");

$form_div = "
<div id='form_div'>

  <div class='sel_div'>
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
  <div><input name='ord_type' type='radio' value='5'>".conv("ХЗ только")."</div>
  <div><input name='ord_type' type='radio' value='4'>".conv("БЗ только")."<br></div>
  </div>

  <div class='date_wrap_div'>
  <div class='date_div'><input id='from_date'/><span>$from_date</span></div>
  <div class='date_div'><input id='to_date'/><span>$to_date</span></div>
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

  <div id='price_div'></div>

</div>
";

$main_div_beg = "<div id='main_div'><div id='head_div'>
<h1 id='rep_head'>".conv("План / Факт. Сводная таблица")."</h1></div>";
$main_div_end = "</div>";

$table_div_beg = "<div id='table_div'>";
$table_div_end = "</div>";
