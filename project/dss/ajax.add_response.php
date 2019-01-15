<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemDiscussion.php" );

global $pdo;

$id = $_POST['id'];
$res_id = $_POST['res_id'];
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
    $query ="   SELECT project_id, base_id, seen_by from dss_discussions
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

if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    {
        $project_id = $row -> project_id ;
        $base_id = $row -> base_id ;
        $arr = json_decode( $row -> seen_by );
        if( is_null( $arr ) )
            $arr = [];
        $arr[] = $res_id ;
        $arr = array_unique( $arr );
    }
    
try
{
    $query ="   INSERT INTO dss_discussions
                ( id, project_id, base_id, parent_id, res_id, text, seen_by, timestamp )
                VALUES
                ( NULL, $project_id, $base_id, $id, $res_id, '$message', '".json_encode( $arr )."', NOW() )
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage().". Query : $query");
}

$disc = new DecisionSupportSystemDiscussion( $pdo,  $res_id, $id );
$disc -> MakeNotification( DECISION_SUPPORT_SYSTEM_NEW_MESSAGE, ' добавил новое сообщение',' добавила новое сообщение');

echo $base_id;
