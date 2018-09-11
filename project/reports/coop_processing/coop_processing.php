<link rel="stylesheet" href="/project/reports/coop_processing/css/jquery-ui.min.css">
<link rel="stylesheet" href="/project/reports/coop_processing/css/theme.css">
<link rel="stylesheet" href="/project/reports/coop_processing/css/bootstrap.min.css">
<link rel="stylesheet" href="/project/reports/coop_processing/css/style.css">

<script type="text/javascript" src="/uses/jquery-ui.js"></script>
<script type="text/javascript" src="/project/reports/coop_processing/js/adjust_calendar.js"></script>
<script type="text/javascript" src="/project/reports/coop_processing/js/coop_processing.js?ver2"></script>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/common_functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.CoopProcessing.php" );
global $pdo, $user;

$user_id = $user['ID'];

// $user_id = 15 ;
// $disabled = ( $user_id == 15 ) ? '' : 'disabled' ;

$id_arr = [];

try
{
    $query = "
      SELECT * FROM `okb_db_coop_processing_types`
      WHERE 1 ORDER BY name
    ";
    $stmt = $pdo->prepare( $query  );
    $stmt->execute();
}
  catch (PDOException $e) 
    {
      die("Can't get data: " . $e->getMessage());
    }  
  
while ( $row = $stmt -> fetchObject() )
    $id_arr [] = [ 'id' => $row -> id , 'name' => $row -> name ];

$str = "<h2>".conv("Стоимость обработок по кооперации")."</h2><div id='main_div'><div id='main_accordion' class='hidden'>";

foreach( $id_arr AS $el )
{
  $name = conv( $el['name'] );
  $id = $el['id'];

$str .= "<h3>$name</h3>
        <div class = 'my_pan'>";

   $proc = new CoopProcessing( $id, $pdo );

$str .= "</div>"; 
}

$str .= "</div></div>";

echo $str ;
