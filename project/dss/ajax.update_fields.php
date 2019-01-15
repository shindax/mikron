<?php
error_reporting( 0 );
//error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemItem.php" );

global $pdo;

$id = $_POST['id'];
$field = $_POST['field'];
$value = $_POST['value'];
$field_arr = $_POST['field_arr'];
$value_arr = $_POST['value_arr'];

if( count( $field_arr ) )
{
	$query = "UPDATE dss_projects SET";
	$arr = [];

	foreach( $field_arr AS $key => $value )
		$arr[] = "`$value` = '".$value_arr[ $key ]."'";
	
	$query .= join(",", $arr )." WHERE id = $id";

	try
	{
	    $stmt = $pdo->prepare( $query );
	    $stmt->execute();
	}
	catch (PDOException $e)
	{
	      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
	}

}
else
{
	try
	{
	    $query ="
	                UPDATE dss_projects
	                SET $field = '$value'
	                WHERE id = $id";
	    $stmt = $pdo->prepare( $query );
	    $stmt->execute();
	}
	catch (PDOException $e)
	{
	      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
	}	
}


echo $query;