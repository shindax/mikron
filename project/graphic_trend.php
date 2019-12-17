<script src="/vendor/highcharts/highcharts.js"></script>
<script src="/vendor/highcharts/modules/exporting.js"></script>
<script src="/vendor/highcharts/modules/accessibility.js"></script>
<script src="/js/graphic_trend.js"></script>

<style>
#container
{
	border: 3px solid navy;
	border-radius: 5px;
	margin: 10px 10px;
}
</style>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$month = date("m");
$year = date("Y");
$id = $_GET['id'];

echo "<script>var id = $id, month = $month, year = $year</script>";
echo "<div id='container'></div>";

