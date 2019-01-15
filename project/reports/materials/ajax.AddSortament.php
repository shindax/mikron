<?php
header('Content-Type: text/html');
error_reporting( 0 );

// error_reporting( E_ALL );
// ini_set('display_errors', true);

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$id = $_POST[ 'id' ];

$disabled = ( $user_id == 15 ) ? '' : 'disabled' ;

global $pdo ;

$query = '';

      try
      {
          $query = "INSERT INTO `okb_db_material_price` SET id_mat='$id'" ;
          $stmt = $pdo->prepare( $query );
          $stmt->execute();
      }
      catch (PDOException $e)
      {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
      }

      $id = $pdo ->  lastInsertId();
 
       $str = "<tr data-id='$id'>
        <td class='Field'>
        <input class='sort_select' data-cur-val='0' data-id='$id' data-field='price' value='' />
        </td>
        <td class='Field AC'>
        <input class='price_input' data-cur-val='0' data-id='$id' data-field='price' value='' />
        </td>
        <td class='Field'><input class='note_input' data-id='$id' data-field='note' value='' /></td>
        <td class='Field'><input class='actuality_input' data-id='$id' data-field='actuality' value='' /></td>
        </tr>";

echo $str;