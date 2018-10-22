<?php
error_reporting( 0 );
//error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemItem.php" );

global $pdo;

$id = 1 * $_POST['id'];
$project_name = $_POST['project_name'];
$short_description = $_POST['short_description'];
$html = $pdo -> quote( $_POST['html'] );
$res_id = $_POST['res_id'] ? $_POST['res_id'] : 293;

$query = '';

if( $id )
{

    try
        {
            $query ="
                     UPDATE `dss_projects` 
                     SET 
                     `name` = '$project_name', 
                     `description` = '$short_description', 
                     `html`= $html
                     WHERE id = $id
                        ";
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }

        catch (PDOException $e)
        {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
        }
}
else
{
        try
        {
            $query ="
                     INSERT INTO `dss_projects` 
                     (`id`, `base_id`, `parent_id`, `name`, `description`, `html`, `creator_id`, `create_date`, `team`, `pictures`, `timestamp`) VALUES
                     ( NULL,  0, 0, '$project_name', '$short_description', $html, $res_id, NOW(), '[$res_id]', '[]', NOW())
                        ";
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }

        catch (PDOException $e)
        {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
        }

        $id = $pdo -> lastInsertId();

        try
        {
            $query ="
                     UPDATE `dss_projects` 
                     SET `base_id` = $id
                        ";
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }

        catch (PDOException $e)
        {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
        }
}

echo $id;
