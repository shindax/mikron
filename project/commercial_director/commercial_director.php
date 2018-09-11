<script type="text/javascript" src="/project/commercial_director/js/popper.min.js"></script>
<script type="text/javascript" src="/project/commercial_director/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/project/commercial_director/js/commercial_director.js"></script>

<link rel='stylesheet' href='/project/commercial_director/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/commercial_director/css/style.css' type='text/css'>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.PenaltyRates.php" );

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

error_reporting( E_ALL );

global $user;
$user_id = $user['ID'];

echo "<script>var user_id = $user_id;</script>";

$str = conv("<h2>Страница коммерческого директора</h2>");
$str .= "<div class='container'>";

$str .= "<hr><div class='row'>";

$str .= "
<h3>".conv("Вопросы для обсуждения на совещании")."</h3>
<table id='meet_question_table' class='table table-striped'>
<col width='2%'>
<col width='10%'>
<col width='75%'>
<col width='5%'>
  <thead>
    <tr class='table-primary'>
      <th>".conv( "№" )."</th>
      <th>".conv( "Заказ" )."</th>
      <th>".conv( "Тема" )."</th>
      <th>".conv( "Удалить" )."</th>
    </tr>
  </thead>
  <tbody>";

  global $pdo ;

        try
        {
            $query = "
            SELECT
            okb_db_plan_fact_notification.id,
            okb_db_plan_fact_notification.description,
            okb_db_zak_type.description AS zak_type,
            okb_db_zak.`NAME` AS zak_name,
            okb_db_plan_fact_notification.zak_id
            FROM
            okb_db_plan_fact_notification
            INNER JOIN okb_db_zak ON okb_db_plan_fact_notification.zak_id = okb_db_zak.ID
            INNER JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id
            WHERE `to_user`=$user_id AND ack=2";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
        }

        $line = 1 ;
        $tooltip = conv("Удалить с повестки");

        // Multiple record
        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
            $zak_id = $row -> zak_id ;
            $zak_type = conv( $row -> zak_type );
            $zak_name = conv( $row -> zak_name );
            $zak = "$zak_type $zak_name";

            $str .=
                "<tr id='".( $row -> id )."'>
                  <td class='AC'><span>$line</span></td>
                  <td class='AC'><a target='_blank' href='index.php?do=show&formid=241&list=$zak_id'>$zak</a></td>
                  <td><span>".conv( $row -> description )."</span></td>
                  <td  class='AC'><img data-placement='right' data-toggle='tooltip' data-original-title='$tooltip' class='delete_theme' src='uses/del.png' /></td>
                </tr>";
                $line ++ ;
        }


$str .= "</tbody>
</table>";

$str .= "</div>"; // "<div class='row'>"

$str .= "<hr>";

$rates = new PenaltyRates( $pdo, 1 );
$str .= $rates -> getHtml();
$rates = new PenaltyRates( $pdo, 2 );
$str .= $rates -> getHtml();
$rates = new PenaltyRates( $pdo, 4 );
$str .= $rates -> getHtml();
$rates = new PenaltyRates( $pdo, 5 );
$str .= $rates -> getHtml();


$str .= "</div>"; // "<div class='container'>"

echo $str ;




