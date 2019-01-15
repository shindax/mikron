<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemDiscussion.php" );

global $pdo;

$id = $_POST['id'];
$message = $_POST['message'];
$res_id = $_POST['res_id'];

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
    $query ="   UPDATE dss_discussions
                SET solved = '$message'
                WHERE
                id = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage().". Query : $query");
}

$disc = new DecisionSupportSystemDiscussion( $pdo,  $res_id, $id );
$str = $disc -> GetHtml() ;
$disc -> MakeNotification( DECISION_SUPPORT_DECISION_MAKING, ' предложил решение',' предложила решение');

echo $str;
