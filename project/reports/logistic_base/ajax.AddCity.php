<?php
header('Content-Type: text/html');
error_reporting( 0 );

error_reporting( E_ALL );
ini_set('display_errors', true);

require_once( "functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
global $pdo ;

$user_id  = $_POST[ 'user_id' ];

try
{
   $query = "INSERT INTO okb_db_logistic_rates (id) VALUES( NULL )" ;
   $stmt = $pdo->prepare( $query );
   $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}

$id = $pdo -> lastInsertId();

$query = "SELECT *, DATE_FORMAT( actuality, '%d.%m.%Y') AS date
          FROM `okb_db_logistic_rates`
          WHERE id = $id
            ";
try
{
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Error in :".__FILE__." file, in ".__FUNCTION__." function, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

if ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    $str = getTableRow( $row, 0, $user_id );

 echo $str;