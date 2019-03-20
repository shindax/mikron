<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemDiscussion.php" );

global $pdo, $dbpasswd;

$id = $_POST['id'];
$message = $_POST['message'];
$res_id = $_POST['res_id'];

$arr = isset( $_POST['arr'] ) ? $_POST['arr'] : [];
$json_arr = [];

if( count( $arr ) )
    foreach ( $arr AS $val ) 
        $json_arr[ $val ] = false ;

$json_list = json_encode( $json_arr );

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
                SET solved = 1
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

try
{
    $query ="   SELECT id 
                FROM dss_decisions
                WHERE
                discussion_id = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage().". Query : $query");
}

if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
    try
    {
        $query ="   UPDATE dss_decisions
                    SET res_id = $res_id, description = '$message', confirmator='$json_list'
                    WHERE
                    discussion_id = $id
                ";

        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
        die("Can't get data: " . $e->getMessage().". Query : $query");
    }
}
else
{
    try
    {
        $query ="   INSERT INTO dss_decisions
                    ( res_id, description, discussion_id, confirmator )
                    VALUES ( $res_id, '$message', $id, '$json_list')
                ";

        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
        die("Can't get data: " . $e->getMessage().". Query : $query");
    }
}

$disc = new DecisionSupportSystemDiscussion( $pdo,  $res_id, $id );
$str = $disc -> GetHtml() ;
$disc -> MakeNotification( DECISION_SUPPORT_DECISION_MAKING, ' предложил решение',' предложила решение');

$disc -> MakeNotification( DECISION_SUPPORT_DECISION_CONFIRM_REQUEST , ' запросил подтерждение',' запросила подтерждение');

echo $str;

