<?php
error_reporting( 0 );

require_once( "functions.php" );

$id = $_POST['id'];
$field = $_POST['field'];
$line = 1 ;
$disabled = 'disabled';

try
{
    $query = "SELECT $field FROM okb_db_zak
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}
$row = $stmt->fetch(PDO::FETCH_OBJ );

$data = $row -> $field;
$arr = explode('|',$data );

unset( $arr[0] );

$out_str = "<table id='change_list_table'>
              <col width='3%'>
              <col width='5%'>
              <col width='10%'>
              <col width='5%'>
              <col width='10%'>
              <col width='1%'>              
              <col width='20%'>
            ";

foreach( $arr AS $key => $val )
{
    try
    {
        $query = "
        SELECT 
        cause.id AS cause_id, cause.cause, hist.comment, hist.confirmed, hist.id
        FROM okb_db_zak_ch_date_history hist
        LEFT JOIN okb_db_plan_fact_carry_causes cause ON cause.ID = hist.cause
        WHERE
        zak_ID=$id
        AND
        pd = '$field'
        AND
        date_index = $key
        " ;
        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

  $row = $stmt->fetch(PDO::FETCH_OBJ );
  $cause = conv( $row -> cause );
  $comment = conv( $row -> comment );
  $checked = $row -> confirmed ? 'checked' : '' ;
  $row_id = $row -> id;  
  $cause_id = $row -> cause_id;  

  $date_arr = explode('#', $val );
  $ch_date = explode(' ', $date_arr[0])[0];
  $user_id = $date_arr[1];
  $new_date = explode(' ', $date_arr[2])[0];

    try
    {
        $query = "
        SELECT 
        FIO 
        FROM  okb_users
        WHERE
        ID=$user_id
        " ;
        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

  $row = $stmt->fetch(PDO::FETCH_OBJ );
  $name = conv( $row -> FIO );

  if( !strlen( $name ) )
      $name = conv( "Начальная дата" ) ;

  $checkbox = '';
  if( $cause_id )
    $checkbox = "<input type='checkbox' data-cause_id='$cause_id' class='time_shift_confirm_checkbox' $checked $disabled data-field='$field' data-row_id='$row_id'/>";

  $out_str .= "<tr>
    <td class='AC'>".( $line ++ ) ." </td>
    <td class='AC'>$ch_date</td>
    <td class='AC'>$name</td>
    <td class='AC'>$new_date</td>
    <td class='AC'>$cause</td>
    <td class='AC'>$checkbox</td>    
    <td class='ch_date_list_comm AC'>$comment</td>
    </tr>";    
}

$out_str  .= "</table>";

if( strlen( $dbpasswd ))
  echo $out_str ;
    else
      echo iconv("Windows-1251", "UTF-8", $out_str );

