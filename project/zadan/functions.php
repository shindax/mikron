<?php

// error_reporting( E_ALL );
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );


function conv( $str )
{
	global $dbpasswd;

	if( !strlen( $dbpasswd ) )
	  return $str;
		else
        	return iconv( "UTF-8", "Windows-1251",  $str );
}

function GetNoncompleteExecutionCausesSelect( $id )
{
	global $pdo ;
	$str = "<select class='noncomplete_execution_causes_select' data-id='$id'>";
	$str .= "<option value='0'>...</option>";

	    try
        {
            $query ="
                        SELECT * 
                        FROM `noncomplete_execution_causes`
                        WHERE 1";

            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
			$str .= "<option value='".( $row -> id )."'>".conv( $row -> description )."</option>";

	$str .= "</select>";

	return $str;
}