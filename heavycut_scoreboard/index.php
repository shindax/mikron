<script type='text/javascript' charset='utf-8' src='.././uses/jquery.js'></script>
<script type='text/javascript' charset='utf-8' src='heavycut_scoreboard.js'></script>
<link rel='stylesheet' href='style.css' type='text/css'>
<?php

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );

const  MAX_SEC_IN_DAY = 24 * 60 * 60 ;
const  AVAILABLE_SEC_IN_DAY = 22 * 60 * 60 ;

$now = new DateTime();
$today = $now -> format('Y-m-d');

$machine_result = GetStatistics( $today, 2, true );
$machine_on_time = $machine_result['ontime'] ;
$machine_off_time = $machine_result['offtime'];

$tool_result = GetStatistics( $today, 4, true );
$tool_on_time = $tool_result['ontime'];

$machine_perc = number_format( $machine_on_time * 100 / AVAILABLE_SEC_IN_DAY);

if( $machine_on_time )
	// $tool_perc = number_format( $tool_on_time * 100 / $machine_on_time );
	$tool_perc = number_format( $tool_on_time * 100 / AVAILABLE_SEC_IN_DAY );
	else
		$tool_perc = 0;

?><!DOCTYPE html>
<html lang="en">
	<head>
	<title>test</title>
	<meta charset="windows-1251">
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700' rel='stylesheet' type='text/css'>
 </head>
 <body<?php echo ($_SERVER['REMOTE_ADDR'] != '192.168.0.46' ? ' style="zoom: 3.5;"' : ''); ?>>

 <div id='tablediv'>
 <table cellspacing="0" cellpadding="0">
	<tr>
		<td><?= conv("Ресурс"); ?></td>
		<td><?= conv("Включен"); ?></td>
		<td><?= conv("Работал"); ?></td>				
	</tr> 
	 <tr>
		<td class="plan">22<br/>
		<div class="line"></div>
		<span class='machine_time'>
			<?php echo secondsToTime( $machine_on_time ); ?>
		</span>
		</td>
		<td><span class='machine_perc'><?php echo $machine_perc; ?></span><small>%<small></td>
		<td><span class='tool_perc'><?php echo $tool_perc; ?></span><small>%</small></td>
	 </tr>
 </table>
 
 <br>
 <input type='date' value='<?= $today; ?>' id='date_input' />
 <br>

 <?php 

echo "<br><span class='date'>Today is ".$now -> format('Y-m-d H:i:s')."</span><br>";
echo "<span class='machine_on_time_str'>Machine on time : ".secondsToFullTime( $machine_on_time )." ( $machine_on_time seconds total )</span><br>";
echo "<span class='machine_off_time_str'>Machine off time : ".secondsToFullTime( $machine_off_time )." ( $machine_off_time seconds total )</span><br>";

if( isset($_GET['debug'] ))
{
}

?>

 	</div>
 </body>
</html>

