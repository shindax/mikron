<script type='text/javascript' charset='utf-8' src='.././uses/jquery.js'></script>
<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting( E_ALL );

function sort_rights( $str )
{
    $res = "";
    $arr = array_unique( explode("|", $str ), SORT_NUMERIC );
    foreach ( $arr AS $key => $value) 
        if( !strlen( trim( $value )))
            unset( $arr[$key] );

    sort( $arr, SORT_NUMERIC ) ;
    if( count( $arr ) )
        $res = "|".join("|", $arr )."|";

    return $res ;
}

$data = [];

try
{
    $query = "SELECT id, ID_forms, ID_rightgroups FROM `okb_users` WHERE 1";
    $stmt = $pdo -> prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");

}
while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    $data[ $row -> id ] = [
                            'ID_forms' => sort_rights( $row -> ID_forms ),
                            'ID_rightgroups' => sort_rights( $row -> ID_rightgroups )
                            ];

foreach ( $data AS $key => $value ) 
{
    try
    {
        $query = "UPDATE `okb_users` 
                  SET 
                    ID_forms = '{$value['ID_forms']}', 
                    ID_rightgroups = '{$value['ID_rightgroups']}'
                  WHERE id = $key";
        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");

    }
}

echo "Completed";

function _conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}
