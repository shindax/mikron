<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );

error_reporting( E_ALL );
error_reporting( 0 );

function cmp($aval, $bval)
{
	$a = intval( $aval['id']);
	$b = intval( $bval['id']);	

    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

function debug( $arr , $conv = 0 )
    {
        $str = print_r($arr, true);
        if( $conv )
            $str = conv( $str );
        echo $str;
    }

$id = $_POST['id'];
$arr = [];
$data = 0;
$str = "";

try
{
$query = "	SELECT storage_place
			FROM okb_db_semifinished_store_invoices
          	WHERE id = $id";
$stmt = $pdo->prepare( $query );
$stmt->execute();
}
catch (PDOException $e)
{
die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
}

$row = $stmt->fetch( PDO::FETCH_OBJ );
$data = json_decode( $row -> storage_place, true );

$str = 	GetStoragePlaceDialogTableBegin();

if( $data && count( $data ) )
{
	foreach ($data AS $value) 
		$arr[] = $value ;

	 usort($arr, "cmp");	

	$line = 1 ;
	foreach ( $arr AS $value )
	{
 		$str .= GetStoragePlaceDialogTableRow( $line,
 			$value['id'] , $value['count'], mb_convert_encoding($value['comments'],'HTML-ENTITIES','utf-8'),$value['wh'],$value['cell'],$value['tier']);
 		$line ++;
	}

}
else
$str .= GetStoragePlaceDialogTableRow(1);
$str .= GetStoragePlaceDialogTableEnd();

if( strlen( $dbpasswd ) )
	echo $str;
		else
			echo iconv('cp1251', 'utf-8', $str);
