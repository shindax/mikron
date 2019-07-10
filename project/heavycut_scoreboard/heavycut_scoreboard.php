<script type='text/javascript' charset='utf-8' src='project/heavycut_scoreboard/js/
jquery-ui.min.js'></script>

<script type='text/javascript' charset='utf-8' src='project/heavycut_scoreboard/js/heavycut_scoreboard.js'></script>

<link rel='stylesheet' href='project/heavycut_scoreboard/css/style.css' type='text/css'>
<?php

require_once( $_SERVER['DOCUMENT_ROOT'].'/classes/db.php' );
require_once( 'functions.php' );

const  MAX_SEC_IN_DAY = 24 * 60 * 60 ;
const  AVAILABLE_SEC_IN_DAY = 22 * 60 * 60 ;

$str = "<h2>".conv("Режим работы станка heavycut")."</h2>";

$str .= "<label for='from'>".conv("C")."</label>
<input type='text' id='from' name='from'>
<label for='to'>".conv("по")."</label>
<input type='text' id='to' name='to'>";

echo $str; 

?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<div id="container"></div>
