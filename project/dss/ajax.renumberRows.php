<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;

$values = $_POST['values'];
$ids = [];

try
{
	$query ="   
				UPDATE `dss_projects` 
				SET `ord` = 
				(case `id`";

	foreach( $values AS $key => $value )				
		{
			if( ! $key )
				continue ;
			$query .= " when $value then $key";
			$ids[] = $value ;
		}

	$query .= " end)
				WHERE `id` in ( ".join( ",", $ids )." )";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage().". Query : $query");
}

echo $query;
