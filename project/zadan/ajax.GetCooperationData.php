<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$operitems_id = $_POST['operitems_id'];

function conv( $str )
{
  return iconv( "UTF-8", "Windows-1251",  $str );
}


try
{
    $query =
    "SELECT count, norm_hours, comment, YEAR( date ) year, MONTH( date ) month, DAY( date ) day
    FROM `okb_db_operations_with_coop_dep` 
    WHERE oper_id = $operitems_id
    ORDER BY date" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query was : $query");
}

$row_count = $stmt -> rowCount() ;

$str = "<table class='tbl coop_tasks'>";
$str .= "<col width='5%'>";
$str .= "<col width='10%'>";
$str .= "<col width='10%'>";
$str .= "<col width='85%'>";
$str .= "<tr class='first'>";
$str .= "<td class='field'>№</td>";
$str .= "<td class='field'>Кол.</td>";
$str .= "<td class='field'>Дата</td>";
$str .= "<td class='field'>Комментарий</td>";
$str .= "</tr>";
$line = 1 ;

 if( $row_count )
   while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
         {
            $day = $row->day > 9 ? $row->day : "0".$row->day;
            $month = $row->month > 9 ? $row->month : "0".$row->month;
            $str .= "<tr>";
            $str .= "<td class='field AC'>".( $line ++ )."</td>";
            $str .= "<td class='field'>".($row->count)."</td>";
            $str .= "<td class='field AC'>$day.$month.".($row->year)."</td>";
            $str .= "<td class='field'>".($row->comment)."</td>";
            $str .= "</tr>";
         }

$str .= "</table>";

echo conv( $str ) ;