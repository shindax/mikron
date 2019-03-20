<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemDiscussion.php" );

global $pdo, $dbpasswd;

$str = "";
$id = $_POST['id'];
$res_id = $_POST['res_id'];
$discussion_id = 0;

function conv( $str )
{
   global $dbpasswd;
    
    if( !strlen( $dbpasswd ) )
        return iconv( "UTF-8", "Windows-1251",  $str );
        else
          return $str;
}


try
{
    $query ="   SELECT confirmator, discussion_id
                FROM dss_decisions
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

$arr = [];
$res_arr = [];

if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    {
        $arr = ( array )json_decode( $row -> confirmator );
        $discussion_id = $row -> discussion_id;
    }


foreach( $arr AS $key => $val )
{
    if( $key == $res_id )
        {
            $res_arr[ $key ] = true;
            $val = 1 ;
        }
            else
                $res_arr[ $key ] = $val;
}

$str = json_encode( $res_arr );

try
{
    $query ="   UPDATE dss_decisions
                SET confirmator = '$str'
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

$el = new DecisionSupportSystemDiscussion( $pdo, $res_id, $discussion_id );
$conf_arr = $el -> GetConfirmators();

$str = json_encode( $conf_arr );
echo $str;

