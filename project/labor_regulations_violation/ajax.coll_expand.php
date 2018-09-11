<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.LaborRegulationsViolationItem.php" );

//error_reporting( E_ALL );
error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$res_id = $_POST['res_id'];
$value = $_POST['value'];
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
$shift = $_POST['shift'];
$can_edit = $_POST['can_edit'];
$date="$year-$month-$day";

            try
            {
                $query = "
                            UPDATE 
							labor_regulations_violation_items
                            SET collapsed = $value
                            WHERE
                            resource_id = $res_id
                            AND
                            date='$year-$month-$day'
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
            }

$cp = new LaborRegulationsViolationItem( $pdo, $res_id, $date, $shift, $can_edit );
$str = $cp -> GetTable();

//echo iconv("Windows-1251", "UTF-8", $str );
echo $str;
 