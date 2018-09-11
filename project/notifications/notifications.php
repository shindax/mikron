<script type="text/javascript" src="/project/notifications/js/notifications.js"></script>
<script type="text/javascript" src="/project/notifications/js/bootstrap.min.js"></script>

<link rel='stylesheet' href='/project/notifications/css/style.css' type='text/css'>
<link rel='stylesheet' href='/project/notifications/css/bootstrap.min.css' type='text/css'>

<?php
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo, $user;

$user_id = $user['ID'];

$tab = $_GET['tab'];

function conv( $str )
{
        return iconv( "UTF-8", "Windows-1251",  $str );
}

function getNotifications( $why_arr )
{
	global $pdo, $user_id;

	$why_list = join(",", $why_arr );
	$str = "";

try
{
    $query = 	"
				SELECT
				okb_db_plan_fact_notification.id,
				okb_db_plan_fact_notification.field field,				
				okb_db_zak.`NAME` AS zak_name,
				okb_db_zak.`DSE_NAME` AS dse_name,
				okb_db_zak_type.description AS zak_type,
				okb_db_plan_fact_notification.description AS notification_description,
				DATE_FORMAT( okb_db_plan_fact_notification.timestamp, '%d.%m.%Y') AS notification_time,
				okb_db_plan_fact_notification.zak_id,
				okb_db_notification_types.area area,
				okb_db_plan_fact_notification.why why
				FROM
				okb_db_plan_fact_notification
				LEFT JOIN okb_db_zak ON okb_db_plan_fact_notification.zak_id = okb_db_zak.ID
				LEFT JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id
				LEFT JOIN okb_db_notification_types ON okb_db_notification_types.ID = okb_db_plan_fact_notification.why
				WHERE
				okb_db_plan_fact_notification.to_user = $user_id 
				AND
				okb_db_plan_fact_notification.ack = 0
				AND
				okb_db_plan_fact_notification.why IN ( $why_list )
				";
   
    $stmt = $pdo -> prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
}

if( $stmt -> rowCount() )
	while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
	{
		$zak_id = $row -> zak_id ;
		$zak_name = conv( $row -> zak_name );
		$zak_type = conv( $row -> zak_type );
		$dse_name = conv( $row -> dse_name );	
		$rec_id = $row -> id ;
		$time = $row -> notification_time ;
		$description = conv( $row -> notification_description );
		$area = conv( $row -> area );
		$why = $row -> why ;
		$field = $row -> field ;

		$str .= makeCard( $rec_id, $area, $zak_type." ".$zak_name, $dse_name, $zak_id, $description, $time, $why, $field );
	}
else
	$str .= conv("<h2>Нет непрочитанных уведомлений</h2>");

	return $str ;
}


function makeCard( $rec_id, $area, $zak_name, $dse_name, $zak_id,  $note_description, $time, $why, $field )
{
	global $user_id ;

	$href = '';
	$zak_str = '';
	$zak_details = '';


	$header_str = conv("Уведомление от ").$time.conv(". Область : ").$area;

	if( $zak_id )
	{
		$href = "index.php?do=show&formid=241&list=$zak_id" ;
		$zak_str = conv(". Заказ ").$zak_name. conv(". ДСЕ : ").$dse_name;
		$zak_details = conv("Заказ ")."</span><a target='_blank' href='$href'>$zak_name</a><span> $dse_name";
		$header_str .= $zak_str;
	}


	if( $why == 9 || $why == 10 )
	{
		$note_description = "<a href='/index.php?do=show&formid=259#$field' target='_blank' >$note_description</a>";
	}

	$str = 
		"<div class='card' id='card_$rec_id'>
	    <div class='card-header ".( $why == 12 ? 'alert-danger' : '') ."' role='tab' id='$rec_id'>
	        <a class='collapsed' data-toggle='collapse' data-parent='.accordion' href='#collapse_$rec_id' aria-expanded='true' aria-controls='collapse_$rec_id'>$header_str</a>
	    </div>
	    <div id='collapse_$rec_id' class='collapse' role='tabpanel' aria-labelledby='$rec_id'>
	      <div class='card-block row'>
	      	<div class='col-9'><span>$zak_details $note_description</span></div>
			
			<div class='col-3 text-right'>";
	
	if( $user_id == 145 )
		$str .= 
				"<button type='button' data-id='$rec_id' class='btn btn-default pull-right'>".conv("На совещание")."</button>&nbsp;";

		$str .=	"<button type='button' data-id='$rec_id' class='btn btn-primary pull-right'>".conv("Прочитано")."</button>
			</div>	      	
	      
	      </div>
	    
	    </div>
	  </div>";

     //return iconv( "UTF-8", "Windows-1251",  $str );
	  return $str;
}

$str = "";

echo conv("<h1>Страница уведомлений.</h1>");

$plan_fact_acc = "<div id='accordion' role='tablist' aria-multiselectable='true'>";
$plan_fact_acc .= getNotifications( [2,3,4,5,6,7,8,9,10] );
$plan_fact_acc .= "</div>";

$coord_page_acc = "<div id='accordion' role='tablist' aria-multiselectable='true'>";
$coord_page_acc .= getNotifications( [11,12] );
$coord_page_acc .= "</div>";

$str .= "
<ul class='nav nav-tabs' id='myTab' role='tablist'>
  <li class='nav-item'>
    <a class='nav-link ".( $tab == 'plan_fact' ? 'active' : '')."' id='home-tab' data-toggle='tab' href='#plan-fact' role='tab' aria-controls='home' aria-selected='true'>".conv("План-факт")."</a>
  </li>
  <li class='nav-item'>
    <a class='nav-link ".( $tab == 'coord_page' ? 'active' : '')."' id='profile-tab' data-toggle='tab' href='#coordination-page' role='tab' aria-controls='profile' aria-selected='false'>".conv("Листы согласования")."</a>
  </li>
</ul>";

$str .= "<div class='tab-content' id='myTabContent'>
  <div class='tab-pane fade ".( $tab == 'plan_fact' ? 'show active' : '')."' id='plan-fact' role='tabpanel' aria-labelledby='home-tab'>$plan_fact_acc</div>";

$str .= "<div class='tab-pane fade ".( $tab == 'coord_page' ? 'show active' : '')."' id='coordination-page' role='tabpanel' aria-labelledby='profile-tab'>$coord_page_acc</div>";
  
echo $str ;
