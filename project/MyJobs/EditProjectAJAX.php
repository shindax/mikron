<link rel='stylesheet' href='/project/MyJobs/css/myCSS.css' type='text/css'>
<script type="text/javascript" src="/project/MyJobs/js/treeView.js"></script>

<?php
header('Content-Type: text/html; charset=windows-1251');
require_once( "TaskByProjectFunctions.php" );

$proj_id = $_GET['proj_id'];

error_reporting(E_ALL & ~E_NOTICE);

$arr = GetProjectsList( $proj_id );

//$str = PrintTableHead( $proj_id ). CreateEditedProjectChildTree( $arr[0] )."</table></div>";

echo iconv("Windows-1251", "UTF-8", $str );

?>
