<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;

$id = $_POST['id'];

try
{
    $query ="
                INSERT INTO noncomplete_execution_cause_explanations 
                ( cause_id, description )
                VALUES( $id, '' )
                ";

    $query = str_replace( '"', '', $query );

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$last_id = $pdo -> lastInsertId();

$str = "<div class='cause_expl_input' data-id='$last_id'>
							<input class='cause_expl' data-id='$last_id' value='' />
							<img src='uses/del.png' class='del_expl_img' />
						</div>";

echo $str;