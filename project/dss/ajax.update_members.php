<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemItem.php" );

global $pdo;

$id = $_POST['id'];
$list = $_POST['list'];

try
{
    $query ="
                UPDATE dss_projects
                SET team = '[ $list ]'
                WHERE id = $id";
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$arr = [];

            try
            {
                $query ="
                            SELECT ID_resurs, NAME 
                            FROM `okb_db_shtat` 
                            WHERE ID_resurs IN ( $list )
                            ORDER BY NAME
                            ";
                $stmt = $pdo -> prepare( $query );
                $stmt -> execute();
            }
            catch (PDOException $e)
            {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }

       while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
          $arr[ $row -> ID_resurs ] = $row -> NAME;

echo json_encode( $arr );