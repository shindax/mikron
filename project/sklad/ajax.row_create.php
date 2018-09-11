<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$line = $_POST['line'] ;
$user_id = $_POST['user_id'] ;


      try
      {
          $query ="SELECT FIO FROM `okb_users` WHERE ID=$user_id";
                      
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        $row = $stmt->fetch(PDO::FETCH_OBJ );
        $name = conv( $row -> FIO );


$str =
        "<tr data-id='' data-rec-id=''>
          <td class='AC'><span class='line'>$line</span></td>
          <td class='AC'><span class='inv_num'>...</span></td>
          <td class='AL'><span class='hidden'>...</span><input class='dse_name_input'/></td>
          <td class='AL'><span class='comment'>".conv("Из накладной по полуфабрикатам.")."</span></td>
          <td class='AC'><span class='warehouse'>...</span></td>
          <td class='AC'><span class='warehouse_cell'>...</span></td>
          <td class='AC'><span class='warehouse_tier'>...</span></td>
          <td class='AC'><span class='count'>0</span></td>
          <td class='AC'><span class='res_count hidden'>0</span><input class='res_count_input' value='' disabled/></td>                  
          <td class='AC'><span data-id='$user_id' class='declarant'>$name</span></td>
          <td class='AC'><button data-state='0' class='btn btn-info add_invoice_button' disabled>".conv("Добавить")."</button></td>
          <td class='AC'><img class='del_img_dis' src='/uses/del_dis.png' disable/></td>
        </tr>";

echo $str;
//echo iconv( "Windows-1251", "UTF-8", $str );