<link rel="stylesheet" href="/project/noncomplete_execution_causes_responsible_persons/css/jquery-ui.min.css">
<link rel="stylesheet" href="/project/noncomplete_execution_causes_responsible_persons/css/theme.css">
<link rel="stylesheet" href="/project/noncomplete_execution_causes_responsible_persons/css/bootstrap.min.css">
<link rel="stylesheet" href="/project/noncomplete_execution_causes_responsible_persons/css/style.css?ver2">

<script type="text/javascript" src="/uses/jquery-ui.js"></script>
<script type="text/javascript" src="/project/noncomplete_execution_causes_responsible_persons/js/noncomplete_execution_causes_responsible_persons.js"></script>

<script type='text/javascript' charset='utf-8' src='vendor/highcharts/highcharts.js'></script>

<?php
require_once( "functions.php" );

$user_id = $user['ID'];
$user_id = 4;

$res = GetResInfo( $user_id );
$res_id = $res['id'];
$data = GetData( $res_id );

$head_title = "<div class='head'>
                            <div><h2>".conv( "Нарушения в сменных заданиях")."</h2></div>
                      </div><hr>";


$charts = "<div class='chart_container'>
              <div id='total_chart' class='hidden'></div>
              <div id='personal_chart' class='hidden'></div>
           </div><hr>";

$str = "<script>var user_id = $user_id;</script>";
$str .= "<script>var res_id = $res_id;</script>";

$str .= $head_title ;
$str .= $charts;

$str .= "<div id='accordion' class='hidden widget'>";

$even_odd = [ 'odd', 'even' ];
$viol_count_str = conv("Количество нарушений : ");
foreach ( $data AS $value ) 
{
	$ord_id = $value['ord_id'];
	$ord_name = $value['ord_name'];
	$ord_dse_name = $value['ord_dse_name'];
	$count = $value['count'];

	$str .= "<h3 class=''>".conv("Заказ ")."<a class='a_link' target='_blank' href='index.php?do=show&formid=39&id=$ord_id'>$ord_name</a> $ord_dse_name $viol_count_str $count</h3>";
	$str .= "<div class = 'my_pan'>";

	$line = 1 ;
	$shift_str = conv("Сменное задание :");
	$operation_str = conv("Операция :");
	$equipment_str = conv("Оборудование :");	
	$cause_str = conv("Причина :");	
	$shutter_str = conv("Выставил причину :");		
	$accept_str = conv("Принял/отклонил :");		

	$plan_str = conv("План Н/Ч:");
	$fact_str = conv("Факт Н/Ч:");

	$worker_str = conv("Ресурс:");

	foreach ( $value['tasks'] AS $skey => $svalue ) 
	{
			$dse_id = $svalue['dse_id'];
			$dse_name = $svalue['dse_name'];
			$dse_draw = $svalue['dse_draw'];
			
			$shutter_name = $svalue['shutter_name'];
			$shut_date = $svalue['shut_date'];
			$res_name = $svalue['res_name'];

			$shift = $svalue['shift'];
			$date = $svalue['date'];
			$bin_date = $svalue['bin_date'];
			
			$operation = $svalue['operation'];
			$unit_name = $svalue['unit_name'];
			$unit_type = $svalue['unit_type'];

			$cause = $svalue['cause'];

			$plan = $svalue['norm_plan'];
			$fact = $svalue['norm_fact'];

			$link = "index.php?do=show&formid=158&p0=$bin_date&p1=$shift#$skey";
			$str .= "<div class = 'pad ".( $even_odd[ $line % 2 ] )."'>";
			$str .= "<div>$line</div>
					 <div><a class='dse_link' target='_blank' href='index.php?do=show&formid=52&id=$dse_id'>$dse_name $dse_draw</a>
					 </div>
					 <div>
					 <a target='_blank' class='shift_link' href='$link'>$shift_str<br>$date ".conv("см:")." $shift</a>
					 </div>
					 <div>
						$cause_str<br>$cause
					 </div>					 
					 <div>
						$plan_str $plan<br>$fact_str $fact
					 </div>					 
					 <div>
						$worker_str<br>$res_name
					 </div>
					 <div>
						$operation_str<br>$operation
					 </div>
					 <div>
						$equipment_str<br>$unit_name : $unit_type
					 </div>
					 <div>
						$shutter_str<br>$shutter_name $shut_date
					 </div>
					 <div>
						$accept_str<br>
						<select class='accept_select'>
						<option>...</option>
						<option>".conv("Принял")."</option>
						<option>".conv("Отклонил")."</option>
						</select>
					 </div>
					 " ;
			$str .= "</div>";
			$line ++ ;
	}

	$str .= "</div>";
}

$str .= "</div>";

echo $str ;
