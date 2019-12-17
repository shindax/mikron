<?php
error_reporting( 0 );
require_once( "functions.php" );

$inv_id = $_POST['inv_id'];
$rec_id = $_POST['rec_id'];
$operation_id = $_POST['operation_id'];

$inv_arr = [];
$storekeepers = [];

try
{
    $query ="
              SELECT 
              iss_inv.id,
              iss_inv.name,
              iss_inv.issued_from,
              iss_inv.comment,
              iss_inv.issued_user_id,
              iss_inv.batch,
              DATE_FORMAT( iss_inv.date, '%d.%m.%Y %H:%i' ) AS ins_date
              FROM okb_db_semifinished_store_issued_invoices AS iss_inv
              WHERE
              iss_inv.issued_from_res_id = $rec_id
              ORDER BY iss_inv.date DESC
                ";

    // echo $query;
              
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query was : $query");
  }
while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
{
  $iss_arr = json_decode( $row -> issued_from, true );
  $count = 0;
  foreach( $iss_arr AS $value )
    $count += $value['count'];

  $inv_arr[ $row -> id ] = [ 'name' => conv( $row -> name ), 'count' => $count, 'batch' => $row ->batch, 'date' => $row -> ins_date, 'comment' => conv( $row -> comment ), 'issued_user_id' => $row -> issued_user_id ];
}

foreach ( $inv_arr AS $value )
  $storekeepers[ $value[ "issued_user_id" ] ] = "";

$array_keys = array_keys( $storekeepers );

if( count( $array_keys ) )
{
  try
  {
      $query ="
                SELECT ID AS user_id, FIO AS user_name
                FROM okb_users
                WHERE ID IN( ".( join(",", $array_keys) ).")";
                 
      $stmt = $pdo->prepare( $query );
      $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query was : $query");
    }

  while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    $storekeepers[ $row -> user_id ] = conv( $row -> user_name );

        $str = "<h3>".conv( "История выдачи полуфабрикатов. ДСЕ " ).$dse_name."</h3>";
        $str .= "<table class='tbl history'>";

        $str .= "<col width='2%' />";
        $str .= "<col width='20%' />";
        $str .= "<col width='10%' />";
        $str .= "<col width='20%' />";
        $str .= "<col width='20%' />";

        $line = 1;
        $str .= "<tr class='first'>
        <td class = 'AC'>".conv("№")."</td>";
        $str .= "<td class = 'AC'>".conv("#Заявки")."</td>";

        $str .= 
        "<td class = 'AC'>".conv("Кол.")."</td>
        <td class = 'AC'>".conv("Дата")."</td>
        <td class = 'AC'>".conv("Выдал")."</td>
        <td class = 'AC'>".conv("Комментарий")."</td>
        </tr>";


  foreach ( $inv_arr AS $key => $value )
  {
    $str .= "<tr class='data_row'>";
    $str .= "<td class='field AC'>$line</td>";
    
    $str .= "<td class='field AC'><a target='_blank' class='invoice_a' href='index.php?do=show&formid=303&p0={$value["batch"]}'>{$value["name"]}</a></td>";

    $str .= "<td class='field AC'>".$value["count"]."</td>";
    $str .= "<td class='field AC'>".$value["date"]."</td>";
    $str .= "<td class='field AC'>".$storekeepers[ $value["issued_user_id"] ] ."</td>";  
    $str .= "<td class='field AC'>".$value["comment"]."</td>";
    $str .= "</tr>";
    $line ++;
  }
 }
 else
  $str = "<h3>".conv( "Ошибочные данные. Обновите страницу и попробуйте ещё раз." )."</h3>";

echo $str; 