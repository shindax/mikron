<script type='text/javascript' charset='utf-8' src='vendor/highcharts/highcharts.js'></script>
<!-- <script type='text/javascript' charset='utf-8' src='vendor/highcharts/modules/exporting.js'></script> -->

<script type='text/javascript' charset='utf-8' src='project/heavycut_scoreboard/js/
jquery-ui.min.js'></script>

<script type='text/javascript' charset='utf-8' src='project/heavycut_scoreboard/js/heavycut_scoreboard.js'></script>

<link rel='stylesheet' href='project/heavycut_scoreboard/css/style.css' type='text/css'>

<?php
require_once( $_SERVER['DOCUMENT_ROOT'].'/classes/db.php' );
require_once( 'functions.php' );

$str = "<h2>".conv("Режим работы станка Heavycut")."</h2>";

$str .= "
<label for='from'>".conv("C")."</label>
<input type='text' id='from' name='from'>
<label for='to'>".conv("по")."</label>
<input type='text' id='to' name='to'>
<div id='container'></div>";
echo "$str"; 
