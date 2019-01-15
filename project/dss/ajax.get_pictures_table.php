<?php
header('Content-Type: text/html');
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
//error_reporting( 0 );

$dss_images_path = '/dss_images@filename/';
$full_files_path = "/project/$files_path".$dss_images_path;

$id = $_POST['id'];

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
    $query ="   SELECT pictures
                FROM `dss_projects` 
                WHERE
                ID = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
    $arr = json_decode( $row -> pictures );

    $str = "<table class='tbl img_table' data-count='".count( $arr )."'>";
    $str .= "<col width='43%'>";
    $str .= "<col width='12%'>";    
    $str .= "<col width='40%'>";
    $str .= "<col width='5%'>";

    foreach ( $arr AS $key => $value )
    {
        $file_name = str_replace('+','%20', urlencode( conv( $value -> name )) );
    
        $href = $full_files_path."$id/".$file_name ;

        $str .= "<tr>";
        $str .= "<td class='Field'><a download href='$href' class='file_name'>". conv( $value -> name )."</a></td>";
        $str .= "<td class='Field AC'><span>".$value -> date."</span></td>";
        $str .= "<td class='Field'><input class='img_comment' value='". conv( $value -> comment )."' /></td>";        
        $str .= "<td class='Field AC'><div><img class='del_img' title='".conv( "Удалить документ")."' src='uses/del.png' /></div></td>";
        $str .= "</tr>";
    }

    $str .= "</table>";
}

echo $str;
?>
