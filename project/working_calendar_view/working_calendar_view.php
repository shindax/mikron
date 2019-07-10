<script type="text/javascript" src="/project/working_calendar_view/js/constants.js"></script>
<script type="text/javascript" src="/project/working_calendar_view/js/date.js"></script>

<script type="text/javascript" src="/project/working_calendar_view/js/jquery.monthpicker.js"></script>
<script type="text/javascript" src="/project/working_calendar_view/js/working_calendar_view.js"></script>

<script src="/vendor/highcharts/highcharts.js"></script>
<script src="/vendor/highcharts/modules/exporting.js"></script>

<link rel='stylesheet' href='/project/working_calendar_view/css/style.css' type='text/css'>
<link rel='stylesheet' href='/project/working_calendar_view/css/bootstrap.min.css' type='text/css'>

<?php
error_reporting( E_ALL );
ini_set('display_errors', true);

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.OrdersCalendarMonthView.php" );

function conv( $str )
{
    return iconv("UTF-8","Windows-1251",  $str );
}

$head_title = "<div class='head'>
                          <div>
                            <div><h2>".conv( "Просмотр рабочего календаря")."</h2></div>
                            <div class='work_cal_view_setup'>
                                     <span class='label'>".conv( "Месяц").":</span><input type='text' id='monthpicker' readonly='readonly'>
                                     <span class='label'>".conv( "Подразделение").":</span>
                                     <select id='department' >
   <option value='0' data-id='0'>...</option>

  <option value='142' data-id='142'>".conv("Бюро запуска новых рабочих центров")."</option>
  <option value='140' data-id='140'>".conv("Бюро экспериментального производства")."</option>
  <option value='118' data-id='118'>".conv("Группа концептуального проектирования")."</option>  
  <option value='91' data-id='91'>".conv("Конструкторский отдел")."</option>
  <option value='112' data-id='112'>".conv("Отдел метрологии")."</option>
  <option value='138' data-id='138'>".conv("Отдел стандартизации и сертификации")."</option>
  <option value='139' data-id='139'>".conv("Отдел технической документации")."</option>
  <option value='105' data-id='105'>".conv("Отдел технического контроля")."</option>
  <option value='109' data-id='109'>".conv("Отдел технической подготовки производства")."</option>  
  <option value='7' data-id='7'>".conv("Служба технического директора")."</option>
  <option value='103' data-id='103'>".conv("Технологический отдел")."</option>

                                     </select>
                                     <span class='label'>".conv( "Сотрудник").":</span><select id='employee' ></select>
                            </div>
                          </div>

                          <div id='pie_div'><img id='user_chart_img' class='chart' src=''></div>
                      </div>";


$content_begin = "<div class='container' id='wrap'>";
$content_end = "</div><!--div class='container'-->";
$waiting_img = "<img id='loadImg' src='project/img/loading_2.gif' />";

echo $head_title ;
echo $content_begin;
echo $content_end;
echo $waiting_img;

// global $pdo;
// $var = new BaseOrdersCalendar( $pdo , [192], 2019, 1 );
// $str = $var -> GetTable( 1, 2019 );
// echo $str;

// _debug( $var -> GetData(), 1 );

