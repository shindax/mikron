<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemDiscussion.php" );

global $pdo;
$id = $_POST['id'];

function conv( $str )
{
    return $str ; // iconv( "UTF-8", "Windows-1251",  $str );
}

try
{
    $query ="   SELECT disc.id id, disc.project_id project_id, disc.text text, disc.solved solved,res.NAME name
                FROM `dss_discussions` disc
                LEFT JOIN okb_db_resurs res ON res.ID = disc.res_id
                WHERE
                parent_id = 0 
                AND
                disc.project_id = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage().". Query : $query");
}

$str = "";
while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
    $solved = strlen( $row -> solved ) ? 1 : 0 ;
    
    $str .= "<div data-solved='$solved' data-id='".$row -> id."' data-project_id='".$row -> project_id."'><span>".conv( $row -> text )."</span><span class= 'auth_span'>".conv( $row -> name )."</span>";
    if( $solved )
        $str .= "<span>[*]</span>";
            else
                $str .= "<span>[ ]</span>";
    $str .= "</div>";
}

echo $str; // .$query;