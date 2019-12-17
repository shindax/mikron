<?php
require_once( "functions.php" );

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

$id = $_POST['id'];
$count = $_POST['count'];
$user_id = $_POST['user_id'];

$user_info = GetUserInfo( $user_id  );
$user_name = $user_info['name'];
$gender = $user_info['gender'];

$now = new DateTime();
$time = $now->format('m.d.Y H:i');

try
{
    $query = "UPDATE okb_db_semifinished_store_invoices
              SET 
              count = $count,
              accepted_by_QCD = $count,
              accepted_by_QCD_res_id = $user_id,
              accepted_by_QCD_date = NOW()
              WHERE id = $id";
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
}

$str = "<span class='accepted-by-qcd'>{$count}шт.</span><br>";
$str .= "<span class='accepted-by-qcd'>$user_name</span><br>";
$str .= "<span class='accepted-by-qcd'>$time</span>";
echo conv( $str );
