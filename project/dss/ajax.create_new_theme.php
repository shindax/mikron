<?php
error_reporting( 0 );
// error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemDiscussion.php" );

global $pdo;

$id = $_POST['id'];
$res_id = $_POST['res_id'];
$theme = $_POST['theme'];
$message = $_POST['message'];

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
    $query ="   INSERT INTO dss_discussions
                ( id, project_id, base_id, parent_id, res_id, text, seen_by, date, timestamp )
                VALUES
                ( NULL, $id, $id, 0, $res_id, '$theme', '".json_encode( [ $res_id ] )."', NOW(), NOW() )
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage().". Query : $query");
}

$parent_id = $pdo -> lastInsertId();

try
{
    $query ="   UPDATE dss_discussions
                SET base_id = $parent_id
                WHERE
                id = $parent_id
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
    $query ="   INSERT INTO dss_discussions
                ( id, project_id, base_id, parent_id, res_id, text, seen_by, date, timestamp )
                VALUES
                ( NULL, $id, $parent_id, $parent_id, $res_id, '$message', '".json_encode( [ $res_id ] )."', NOW(), NOW() )
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage().". Query : $query");
}

$disc = new DecisionSupportSystemDiscussion( $pdo,  $res_id, $pdo -> lastInsertId() );
echo $disc -> MakeNotification( DECISION_SUPPORT_SYSTEM_THEME_CREATE, ' добавил тему обсуждения',' добавила тему обсуждения');
