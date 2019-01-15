<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;

$ids = $_POST['ids'];
$base_id = $_POST['base_id'];

function conv( $str )
{
   global $dbpasswd;
    
    if( strlen( $dbpasswd ) )
        return iconv( "UTF-8", "Windows-1251",  $str );
        else
          return $str;
}

try
{
    $query ="   UPDATE dss_projects
                SET base_id = $base_id
                WHERE
                id IN ( ".join( ",", $ids )." )
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage().". Query : $query");
}

echo $query;
