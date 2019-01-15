<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemItem.php" );

global $pdo;

$parent_id = $_POST['parent_id'];
$level = $_POST['level'] + DecisionSupportSystemItem :: LEVEL_SHIFT;
$base_id = $_POST['base_id'];
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
    $query ="
             SELECT team FROM `dss_projects` WHERE id = $parent_id";
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}

catch (PDOException $e)
{
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

$row = $stmt->fetch( PDO::FETCH_OBJ ); 
$team = $row -> team ;

try
{
    $query ="
             SELECT MAX(ord) ord FROM `dss_projects` WHERE parent_id = $parent_id";
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}

catch (PDOException $e)
{
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

$row = $stmt->fetch( PDO::FETCH_OBJ ); 
$ord = 1 + $row -> ord ;

try
{
    $query ="
             INSERT INTO `dss_projects` 
             (`id`, `base_id`, `parent_id`, `ord`, `name`, `description`, `creator_id`, `create_date`, `team`, `pictures`, `timestamp`) VALUES
             ( NULL,  $base_id, $parent_id, $ord, '', '', $res_id, NOW(), '$team', '[]', NOW())
                ";
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}

catch (PDOException $e)
{
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}
$id = $pdo -> lastInsertId();

if( $parent_id == 0 )
{
	$level = 0 ;
	try
	{
	    $query ="
	             UPDATE `dss_projects` 
	             SET `base_id` = $id
                 WHERE `id`= $id
	                ";
	    $stmt = $pdo->prepare( $query );
	    $stmt->execute();
	}

	catch (PDOException $e)
	{
	      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
	}

}

$dss_item = new DecisionSupportSystemItem( $pdo, $res_id, $id, $level );
$str = conv( $dss_item -> GetTableRow('','Field') );
echo $str;