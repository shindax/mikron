<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$id = $_POST['id'];

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

            try
            {
                $query = "
                            DELETE
                            FROM
                            coordination_pages
                            WHERE id = $id
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }
            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
            }

            try
            {
                $query = "
                            DELETE
                            FROM
                            coordination_page_items
                            WHERE page_id = $id
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }
            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
            }

       
echo $query ;
