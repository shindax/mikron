<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

//error_reporting( E_ALL );
error_reporting( 0 );

$id = $_POST['id'];
$path = $_POST['path'];
$path = str_replace("\\","\\\\", $path );

           try
            {
                $query = "
                            UPDATE 
							coordination_pages
                            SET 
                            doc_path = '$path'
                            WHERE
                            id = $id
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
            }

echo $query;
 