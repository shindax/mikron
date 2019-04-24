<?php
error_reporting( 0 );
// error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/service_note/functions.php" );

$id = $_POST['id'];
$res_id = $_POST['res_id'];
$str = "$id";
$uploaddir = str_replace("//", "/",  LOCAL_FILES_PATH );

// Создадим папку если её нет
if( ! is_dir( $uploaddir ) )
    mkdir( $uploaddir, 0777 );

$file = $_FILES[ 0 ]; // in this place one file only processed
{
    $tmp_file_name = time() ;
    $pass = 0 ;

    while( time() == $tmp_file_name && ( count( $_FILES  ) > 1 ) )
        $tmp_file_name = time()."_".$pass ++ ;

    $real_file_name_arr = explode('.', $file['name'] );
    $tmp_file_name .= ".".$real_file_name_arr[ 1 ];
    $viewpath = LOCAL_FILES_PATH.DIRECTORY_SEPARATOR."$tmp_file_name" ;

    if( @move_uploaded_file( $file['tmp_name'] , $uploaddir . $tmp_file_name ) )
        $files_arr[] = $tmp_file_name;
    else
        $error = 1 ;
}

$filename = $files_arr[0];

  try
  {
      $query ="
                      UPDATE service_notes
                      SET note_scan_name='$filename'
                      WHERE id = $id
                      ";
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

  $receivers = [];
  $sender = "";
  $gender = 0 ;
  $pict_name = "";
  $note_number = "";
  $creation_date = "";
  $description = "";

  // $uploaddir = str_replace("\\", "/",  $uploaddir );

  try
  {
      $query ="
                      SELECT 
                      cr_res.NAME AS name,
                      cr_res.GENDER AS gender,
                      res.EMAIL AS email,
                      sn.note_scan_name AS note_scan_name,
                      sn.creation_date AS creation_date,
                      sn.description AS description,
                      sn.note_number AS note_number 
                      FROM service_notes sn
                      LEFT JOIN okb_db_resurs res ON JSON_CONTAINS(sn.receivers_res_id, CAST( res.id AS JSON ), '$')
                      LEFT JOIN okb_db_resurs cr_res ON cr_res.ID = $res_id
                      WHERE 
                      sn.id = $id
                      ";
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }
      while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
      {
         $email = $row -> email ;
         $sender = $row -> name ;
         $gender = $row -> gender ;
         $note_number = $row -> note_number ;
         $pict_name = $uploaddir.$row -> note_scan_name ;
         $creation_date = $row -> creation_date;
         $description = $row -> description;

         if( strlen( $email ) )
         {
          preg_match('/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i', $email, $matches ); 
          $receivers [] = $matches[0];
         }
      }

      $creation_date = new DateTime( $creation_date );
      $creation_date = $creation_date->format('d.m.Y');

      $msg = $sender;
      $sender .= $gender == 1 ? " прикрепил " : " прикрепила " ;
      $sender .= " новый документ к служебной записке № $note_number от $creation_date. Содержание : \"$description\". <br>Документ во вложении";

      SendMail( $receivers, "Обновление в служебных записках", $sender, $pict_name );

echo  $filename; // $pict_name." : ".$sender." : ".$gender." : ".join( ",", $receivers );
