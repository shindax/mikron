<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
 $result = iconv("UTF-8", "Windows-1251", $str );
 //$result = $str ;
 return $result;
}

function DateConvert( $date )
{
    return date("Y-m-d", strtotime( $date ));
}

function MakeDateWithDot( $date, $day = 0 )
{
    $timestamp = strtotime( $date );

    if( $day )
        $out_date = $day ;
        else
            $out_date = date('d', $timestamp);

    $out_date .= ".".date('m', $timestamp) . "." . date('Y', $timestamp);

    return $out_date;
}

function MakeDateWithDash( $date, $day = 0 )
{
    $timestamp = strtotime( $date );
    $out_date = date('Y', $timestamp) . "-" . date('m', $timestamp) . "-";
    if( $day )
        $out_date .= $day ;
    else
        $out_date .= date('d', $timestamp);
    return $out_date;
}

function GetEnviroment()
{
  global $user, $pdo;
  $dash_date = date("Y-m-d");
  $dot_date = date("d.m.Y");
  $login_id = $user["ID"];

//  $login_id = 31; // Роев

//  $login_id = 2; // Мирошников
//  $login_id = 15;   // Кумановская
//    $login_id = 66;   // Козловский

  if( $login_id == 1 )
  {
    $user_id = 0 ;
  }
  else
  {
        try
        {
            $query ="
                      SELECT resurs.ID
                      FROM `okb_db_resurs` resurs
                      INNER JOIN okb_users users ON users.ID = resurs.ID_users
                      WHERE users.ID = $login_id";

            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        if ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
                $user_id = $row -> ID ;
  }

  echo "<script>var user_id = $user_id; var dash_date = '$dash_date'; var dot_date = '$dot_date'; </script>";
  return [ 'dash_date' => $dash_date, 'dot_date' => $dot_date, 'user_id' => $user_id ];
}

