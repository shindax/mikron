<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function GetUserName( $user_id )
{
	global $pdo;

	$user = [];
	    try
        {
            $query ="
                        SELECT
                        NAME, GENDER
                        FROM `okb_db_resurs`
                        WHERE ID_users=".$user_id;

            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        if ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        	$user = ['name' => conv( $row -> NAME ), 'gender' => $row -> GENDER ];

        return $user;
}