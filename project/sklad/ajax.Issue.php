<?php
require_once( "functions.php" );
global $pdo;

$doc_suffix = ["","","-а","-б","-в","-г","-д","-е","-ж","-з","-и","-к","-л","-м","-н","-о","-п","-р","-с","-т","-у","-ф","-х","-ц","-ч","-ш"];

$res_id = $_POST['res_id'];
$batch = $_POST['batch'];
$arr = $_POST['arr'];
$user_id = $_POST['user_id'];
$issued_count = 0 ;
$dest_arr = [];

$now = new DateTime();
$today = $now -> format('d.m.Y H:i');
$issued_count = 0 ;
$count = 0 ;

foreach( $arr AS $key => $value )
{
  $rec_id = + $value['id'];
  $inv_id = + $value['inv_id'];
  $count = + $value['count'];

  // Получаем исходное распределение ДСЕ в накладной
  try
  {
      $query = " SELECT storage_place 
           FROM okb_db_semifinished_store_invoices 
           WHERE id = $inv_id";

      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");

  }

  $row = $stmt->fetch( PDO::FETCH_OBJ );
  $temp_arr = json_decode( $row -> storage_place, true );

  $new_count = 0 ;

// Поиск записи о позиции на складе в накладной
  foreach( $temp_arr AS $skey => $svalue )
  {
    if( $svalue['id'] == $rec_id )
    {
      $old_count = $temp_arr[ $skey ]['count']; // Исходное количество в накладной
      $temp_arr[ $skey ]['count'] = $count ; // Новое количество для сохранения
      $dest_arr[] = $temp_arr[ $skey ]; // Для накладной выданных ДСЕ
      $temp_arr[ $skey ]['count'] = $old_count - $count ; // Обновляем исходные данные накладной
      $issued_count += $count;
    }
    if( $temp_arr[ $skey ]['count'] == 0 )
      unset( $temp_arr[ $skey ] );

    if( isset( $temp_arr[ $skey ]['count'] ))
        $new_count += $temp_arr[ $skey ]['count'];
  }

// Одновление распределения ДСЕ в накладной
  try
  {
      $query = " 
            UPDATE okb_db_semifinished_store_invoices 
            SET 
              storage_place = '".json_encode( $temp_arr )."', count = $new_count
            WHERE id = $inv_id";
      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
  }

// Обновление количества на складе
  try
  {
      $query = " SELECT 
                  COUNT AS count 
                  FROM okb_db_sklades_detitem
                  WHERE id = $rec_id";
      
      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
  }

    $row = $stmt->fetch( PDO::FETCH_OBJ );

    $count = $row -> count - $count ;

  if( $count )
  {
    try
    {
        $query = " UPDATE okb_db_sklades_detitem
                    SET COUNT = $count
                    WHERE id = $rec_id";
        
        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
    }
  }
  else
  {
   try
    {
        $query = " 
                  DELETE FROM okb_db_sklades_detitem
                  WHERE id = $rec_id";
  
        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
    } 
  }

} // foreach( $arr AS $key => $value )

// Получаем исходное количество резерва по накладной
try
{
    $query = " SELECT count 
    			     FROM `okb_db_warehouse_reserve` 
    			     WHERE id = $res_id";
    
    $stmt = $pdo -> prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");

}
$row = $stmt->fetch( PDO::FETCH_OBJ );
$res_count = $row -> count - $issued_count;

// Обновить количество резерва
  try
  {
      $query = "  UPDATE okb_db_warehouse_reserve
                  SET 
                  count = $res_count
                  WHERE id = $res_id" ;
      $stmt = $pdo->prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
     die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
  }

$transaction = 1 ;

// Проверить была-ли уже выдача
try
{
    $query = "SELECT MAX( transaction ) AS transaction
              FROM okb_db_semifinished_store_issued_invoices
              WHERE 
                issued_from_res_id = $res_id
                AND
                batch = $batch
             " ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

if( $stmt -> rowCount() )
{
  $row = $stmt->fetch( PDO::FETCH_OBJ );
  $transaction = 1 + $row -> transaction ;
}

// Добавить выдачу в таблицу okb_db_semifinished_store_issued_invoices
try
{
    $query = "  INSERT INTO okb_db_semifinished_store_issued_invoices
                ( 
                    name,
                    created_from,
                    issued_from_res_id,
                    batch,
                    transaction,
                    issued_from, 
                    `date`, 
                    issued_user_id, 
                    comment 
                )
                VALUES
                ( 
                  'Накладная',
                  $inv_id, 
                  $res_id,                  
                  $batch,
                  $transaction,
                  '".json_encode( $dest_arr, JSON_UNESCAPED_UNICODE )."',
                  NOW(),
                  $user_id,
                  ''
                )
             " ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$last_insert_id = $pdo->lastInsertId();

try
{
    $query = "  UPDATE okb_db_semifinished_store_issued_invoices
                SET name = 'Накладная №$batch{$doc_suffix[$transaction]}'
                WHERE id = $last_insert_id
             " ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$issues_count = GetIssuesCount( $batch );
$state_str = [ "Готово к выдаче", "Выдается", "Выдано" ];
$state = GetState( $batch );

echo join( ",", [ $issued_count, $issues_count, $state, conv( $state_str[ $state ])]);
