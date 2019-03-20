<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.CoordinationPage.php" );

error_reporting( E_ALL );
// error_reporting( 0 );

$id = $_POST['id'];
$user_id = $_POST['user_id'];
$user_name = "";
$state = $_POST['state'];
$stage = isset( $_POST['stage'] ) ? $_POST['stage'] : 0 ;

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
    $query = "
                UPDATE 
				coordination_pages
                SET frozen_by = ".( $state ? $user_id : 0 ).", frozen_at=NOW(), frozen_in=$stage
                WHERE
                krz2_id = $id
                ";

                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
}

catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}


try
{
    $query = "
SELECT FIO FROM okb_users WHERE ID = $user_id
";

                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
}

catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
}
if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    $user_name = $row -> FIO ;

$cp = new CoordinationPage( $pdo, $user_id, $id );
$str = $cp -> GetTable();
$now = new DateTime();
$time = $now->format('m.d.Y H:i');

$str .= "<span class='hidden frozen_caption'>".conv(" Заморожен ").$time.conv(" Инициатор : ").$user_name."</span>";

echo $str;
 