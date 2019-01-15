<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemDiscussion.php" );

global $pdo;
$id = $_POST['id'];
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
    $query ="   SELECT team
                FROM `dss_projects`
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
    $team_arr = json_decode( $row -> team );

if( empty($team_arr) )
    $team_arr = [];

if( in_array( $res_id, $team_arr ) )
    $can_add = true ;
    else
        $can_add = false ;
try
{
    $query ="   SELECT disc.id id, disc.project_id project_id, disc.text text, disc.solved solved,res.NAME name, prj.team
                FROM `dss_discussions` disc
                LEFT JOIN okb_db_resurs res ON res.ID = disc.res_id
                LEFT JOIN dss_projects prj ON prj.id = disc.project_id                
                WHERE
                disc.parent_id = 0 
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
$themes = [] ;

while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    $themes[] = [ 'solved' => strlen( $row -> solved ) ? 1 : 0 , 'id' => $row -> id , 'text' => conv( $row -> text ), 'auth' => conv( $row -> name ) ];

foreach( $themes AS $theme )
{
    $disc = new DecisionSupportSystemDiscussion( $pdo,  $res_id, $theme['id'] );
    $new_disc = $disc -> HasNewMessages();

    $str .= "<div class='disc_theme' data-solved='".$theme['solved']."' data-id='".$theme['id']."' data-project_id='".$theme['id']."'><div><span>".$theme['text']."</span><span class= 'auth_span'>".$theme['auth']."</span>";
    
    if( $theme['solved'] )
        $str .= "<span>[*]</span>";
            else
                $str .= "<span>[ ]</span>";
    
    $str .= "</div>";

    if( $new_disc )
    {
        $new_disc --;
        if( $new_disc == 0 )
            $new_disc = '';
        $str .= "<div><img class='conv_icon' src='/uses/svg/conversation.svg' title='".conv("Есть новые сообщения")."' /><span class='conv_icon_span'>$new_disc</span></div>";
    }

    $str .= "</div>";
}

if( $can_add )
    $str .= "<span class='can_add'></span>";   

echo $str;