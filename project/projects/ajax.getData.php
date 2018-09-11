<?php
header('Content-Type: text/html');
error_reporting( 0 );

$id = $_POST['id'];
$user_id = $_POST['user_id'];

require_once("CommonFunctions.php");
require_once "TaskByProjectFunctions.php";
require_once("makeChart.php");

$arr = GetProjectsList( $id );
CalcProjectsTaskCount( $arr ) ;
$tree = CreateProjectTree( $user_id, $arr );

MakeDepartmentChart( $arr, $id );

//echo iconv("Windows-1251", "UTF-8", $tree );
echo $tree ;
