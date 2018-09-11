<link rel="stylesheet" href="/project/orders/coop_orders/css/jquery-ui.min.css">
<link rel="stylesheet" href="/project/orders/coop_orders/css/coop_orders.css">

<script type="text/javascript" src="/project/orders/coop_orders/js/coop_orders.js"></script>
<script type="text/javascript" src="/project/orders/coop_orders/js/jquery-ui.min.js"></script>

<?php
error_reporting( E_ALL );

require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");
require_once("class.coop_orders.php");
require_once("modal_dialog.php");

global $user ; 
$user_id = $user['ID'];
echo "<script>var user_id = '$user_id';</script>";

function GetOrders()
{
  global $mysqli ; 
  
  $arr = array();

  $query = "SELECT 
            cr.ID, cr.NAME cr_name, cr.OBOZ, cr.COUNT, cr.CDATE, cr.DATE, cr.STATE, cr.ID_zak, 
            cr.TXT, cr.VIDRABOT, cr.material, cr.comment, cr.NAZN, cr.PLAN_NCH, cr.CENA_PLAN, cr.STOIM_RAB, cr.CENA_FACT, cr.EFFECTN, 
            rc.FIO res_NAME, 
            zak.NAME zak_name, zak.TID ord_type
            FROM okb_db_koop_req cr 
            INNER JOIN okb_users rc ON rc.ID = cr.ID_users
            INNER JOIN okb_db_zak zak ON zak.ID = cr.ID_zak
            WHERE cr.STATE=0 ORDER BY ID DESC";
            
  $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Database access error in ".__FILE__." in line ".__LINE__.": ".$mysqli->error); 

        if( $result -> num_rows )
            while( $row = $result -> fetch_object() )
            {
                $el = new CoopOrder( 
                    $row -> ID,
                    $row -> cr_name,
                    $row -> OBOZ,
                    $row -> COUNT,
                    $row -> PLAN_NCH * 1,
                    $row -> CDATE,
                    $row -> DATE,
                    $row -> STATE,
                    $row -> ID_zak,
                    $row -> zak_name,
                    $row -> ord_type,
                    $row -> TXT,
                    $row -> res_NAME,
                    $row -> VIDRABOT,
                    $row -> material,
                    $row -> comment,
                    $row -> NAZN,
                    $row -> CENA_PLAN,
                    $row -> STOIM_RAB,
                    $row -> CENA_FACT,
                    $row -> EFFECTN                   
                );
            
              $arr[] = $el;
            }
  return $arr ;
}

$orders = GetOrders();
//$disabled = 'disabled';

$str = "<H2>Заявки на кооперацию</H2>";
$str .= "<div class='addline'><a class='alink $disabled'>Добавить</a></div>";

$str .= $orders[0] -> GetHtmlHead();
      
foreach( $orders AS $order )
      $str .= $order -> GetHtmlBody();

$str .= $orders[0] -> GetHtmlFoot();

echo $str;

?>

