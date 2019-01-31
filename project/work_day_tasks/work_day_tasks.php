<script type="text/javascript" src="/project/work_day_tasks/js/constants.js"></script>
<!--script type="text/javascript" src="/project/work_day_tasks/js/bootstrap.min.js"></script-->
<script type="text/javascript" src="/project/work_day_tasks/js/work_day_tasks.js"></script>

<link rel='stylesheet' href='/project/work_day_tasks/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/work_day_tasks/css/style.css' type='text/css'>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.User.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.BaseOrdersCalendar.php" );

global $pdo;

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

error_reporting( E_ALL );


//<option value='91,104,118,141,147,148,149,150,151,152' data-id='91'>".conv("Конструкторский отдел")."</option>
//<option value='103' data-id='103'>".conv("Технологический отдел")."</option>

$str = "<div class='head'>
                          <div>
                            <div><h2>".conv( "Просмотр перечня задач за период")."</h2></div>
                            <div class='work_cal_view_setup'>
                                     <span class='label'>".conv( "Дата с")." : </span><input type='text' id='datepicker' readonly='readonly'>
                                     <span class='label'> ".conv( "по")." : </span><input type='text' id='datepicker_second' readonly='readonly'>
                                     <span class='label'>".conv( "Подразделение").":</span>
                                     <select id='department' >

                                           <option value='0' data-id='0'>...</option>

                                           <option value='7' data-id='7'>".conv("Служба технического директора")."</option>

                                           <option value='153' data-id='153'>".conv("Отдел главного сварщика")."</option>

                                           <option value='138' data-id='138'>".conv("Отдел стандартизации и сертификации")."</option>

                                           <option value='112' data-id='112'>".conv("Отдел метрологии")."</option>

                                           <option value='105' data-id='105'>".conv("Отдел технического контроля")."</option>

                                           <option value='103' data-id='103'>".conv("Технологический отдел")."</option>

                                           <option value='91' data-id='91'>".conv("Конструкторский отдел")."</option>
                                           
                                           <option value='139' data-id='139'>".conv("Отдел технической документации")."</option>

                                          <option value='118' data-id='118'>".conv("Группа концептуального проектирования")."</option>

                                          <option value='109' data-id='109'>".conv("Отдел технической подготовки производства")."</option>

                                          <option value='140' data-id='140'>".conv("Бюро экспериментального производства")."</option>

                                          <option value='142' data-id='142'>".conv("Бюро запуска новых рабочих центров")."</option>

                                     </select>
                            </div>
                          </div>

                          <div id='pie_div'><img id='user_chart_img' class='chart' src=''></div>
                      </div>";


$str .= "<div class='container'>";

$str .=  "<div class='row'>
          <div class='col-sm-12' style='text-align:right'>
              <hr>
              <button class='btn btn-small btn-primary hidden' type='button' id='print_btn'>".conv('Распечатать')."</button>
          </div></div>";

$str .= "<div class='row' id='table_div'>";
$str .= "</div>"; // "<div class='row'>"

echo $str ;

$base_cal = new BaseOrdersCalendar( $pdo,[ 942 ] ,2018 ,12, 1, ['year'=> 2018, 'month'=>12, 'day'=> 15 ] );

//debug( $base_cal -> GetDayTypes(), true );

// $user_data = $base_cal -> GetChartData();
//$user_data = $base_cal -> GetDayHourData();
//$user_data = $base_cal -> GetData();

// debug( $user_data, true );


