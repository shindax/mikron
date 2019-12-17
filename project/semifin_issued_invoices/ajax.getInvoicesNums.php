<?php
require_once( "functions.php" );
global $pdo;

$year = $_POST['year'];
$month = $_POST['month'] ;

$where = " 1 ";

if( $year )
	$where = " AND `date` >= '$year-01-01 00:00:00' AND `date` <= '$year-12-31 23:59:59'";

if( $month && $year )
	$where = " AND `date` >= '$year-$month-01 00:00:00' AND `date` <= '$year-$month-31 23:59:59'";

if( $month && !$year )
	$where = " AND  DATE_FORMAT( `date` , '%m' ) = $month ";

$ids = [];

try
{
  $query ="
                SELECT DISTINCT( batch ) AS id
                FROM `okb_db_semifinished_store_issued_invoices`
                WHERE 1";

  $stmt = $pdo->prepare( $query );
  $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
}
while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
  $ids[] = $row -> id;

try
{
  $query ="
                SELECT id, name, batch
                FROM `okb_db_semifinished_store_issued_invoices`
                WHERE id in (".join(",", $ids).") AND transaction = 1 $where";

  $stmt = $pdo->prepare( $query );
  $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
}

$options = "<option value='0'>все</option>";

while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
      $options .= "<option value='{$row->batch}'>{$row->name}</option>";

echo conv( $options );

