<script type="text/javascript" src="/project/notifications/js/notifications.js"></script>
<script type="text/javascript" src="/project/notifications/js/bootstrap.min.js"></script>

<link rel='stylesheet' href='/project/notifications/css/style.css' type='text/css'>
<link rel='stylesheet' href='/project/notifications/css/bootstrap.min.css' type='text/css'>

<?php
// error_reporting( E_ALL );
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo, $user;

$user_id = isset( $user['ID'] ) ? $user['ID'] : 0 ;
$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '' ;

$stages = [];
$stages['pd1'] = conv("Подготовка производства. КД");
$stages['pd2'] = conv("Подготовка производства. Нормы расхода");
$stages['pd3'] = conv("Подготовка производства. МТК");
$stages['pd13'] = conv("Подготовка производства. Инструмент и оснастка");

$stages['pd4'] = conv("Комплектация. Проработка");
$stages['pd7'] = conv("Комплектация. Поставка");

$stages['pd_coop1'] = conv("Кооперация. Проработка");
$stages['pd_coop2'] = conv("Кооперация. Поставка");

$stages['pd12'] = conv("Производство. Дата начала");
$stages['pd8'] = conv("Производство. Дата окончания");

$stages['pd9'] = conv("Коммерция. Предоплата");
$stages['pd10'] = conv("Коммерция. Оконч.расчет");
$stages['pd11'] = conv("Коммерция. Поставка");

function conv( $str )
{
	global $dbpasswd;

	if( strlen( $dbpasswd ) )
	  return $str;
		else
        	return iconv( "UTF-8", "Windows-1251",  $str );
}

function GetUserName( $user_id )
{
	global $pdo;

	$user = [];
	    try
        {
            $query ="
                        SELECT
                        NAME, GENDER
                        FROM `okb_db_resurs`
                        WHERE ID_users=".$user_id;

            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        if ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        	$user = ['name' => conv( $row -> NAME ), 'gender' => $row -> GENDER ];

        return $user;
}

function getNotifications( $why_arr, $plan_fact_page = 0 )
{
	global $pdo, $user_id, $stages;

	$plan_fact_arr = [];

	$plan_fact_arr[ PREPARE_GROUP ] = [ 'count' => 0, 'dep_name' => conv("Подготовка производства"), 'notifications' => [ 'pd1' => [], 'pd2' => [],'pd3' => [],'pd13' => [] ]];

	$plan_fact_arr[ EQUIPMENT_GROUP ] = [ 'count' => 0, 'dep_name' => conv("Комплектация"), 'notifications' => [ 'pd4' => [], 'pd6' => [] ]];

	$plan_fact_arr[ COOPERATION_GROUP ] = [ 'count' => 0, 'dep_name' => conv("Кооперация"), 'notifications' => [ 'pd_coop1' => [], 'pd_coop2' => [] ]];

	$plan_fact_arr[ PRODUCTION_GROUP ] = [ 'count' => 0, 'dep_name' => conv("Производство"), 'notifications' => [ 'pd12' => [], 'pd8' => [] ]];
	$plan_fact_arr[ COMMERTION_GROUP ] = [ 'count' => 0, 'dep_name' => conv("Коммерция"), 'notifications' => [ 'pd9' => [], 'pd10' => [], 'pd11' => [] ]];

	$why_list = join(",", $why_arr );
	$str = "";

	try
	{
	    $query = 	"
					SELECT
					okb_db_plan_fact_notification.id,
					okb_db_plan_fact_notification.field field,
					okb_db_plan_fact_notification.stage stage,				
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


		// echo "$query<br>";
	  
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
				$area = conv( $row -> area );
				$why = $row -> why ;
				$field = $row -> field ;
				$stage = $row -> stage ;		
				$description = conv( $row -> notification_description );
				$card = makeCard( $rec_id, $area, $zak_type." ".$zak_name, $dse_name, $zak_id, $description, $time, $why, $field, $stage );

				if( $plan_fact_page )
				{
					switch( $field )
					{
						case 'pd1':
						case 'pd2':
						case 'pd3':
						case 'pd13': 

										$plan_fact_arr[ PREPARE_GROUP ]['count'] ++;
										$plan_fact_arr[ PREPARE_GROUP ]['notifications'][$field][] = $card;
										break;
						case 'pd4':
						case 'pd7':
										$plan_fact_arr[ EQUIPMENT_GROUP ]['count'] ++;
										$plan_fact_arr[ EQUIPMENT_GROUP ]['notifications'][$field][] = $card;
										break;
						case 'pd_coop1':
						case 'pd_coop2':
										$plan_fact_arr[ COOPERATION_GROUP ]['count'] ++;
										$plan_fact_arr[ COOPERATION_GROUP ]['notifications'][$field][] = $card;
										break;
						case 'pd12':
						case 'pd8':
										$plan_fact_arr[ PRODUCTION_GROUP ]['count'] ++;
										$plan_fact_arr[ PRODUCTION_GROUP ]['notifications'][$field][] = $card;
										break;
						
						case 'pd9':
						case 'pd10':
						case 'pd11':
										$plan_fact_arr[ COMMERTION_GROUP ]['count'] ++;
										$plan_fact_arr[ COMMERTION_GROUP ]['notifications'][$field][] = $card;
										break;
					}
					$plan_fact_arr[ $field ][] = $card;
				}
				else
					$str .= $card;
			}
		else
			$str .= conv("<h2>Нет непрочитанных уведомлений</h2>");

	if( $plan_fact_page )
	{
		$str = '';

		if( 	$plan_fact_arr[1]['count'] 
			||	$plan_fact_arr[2]['count'] 
			||	$plan_fact_arr[3]['count'] 
			||	$plan_fact_arr[4]['count'] 
			||	$plan_fact_arr[5]['count'] 
		   )
		foreach ( $plan_fact_arr AS $key => $value ) 
		{
			if( $value['count'] )
			{
				$str .= "<div class='card dep_name'><span>{$value['dep_name']}</span></div>";
				foreach( $value['notifications'] AS $skey => $svalue )
				{
					if( count( $svalue ) )
					{
					$str .= "<div class='card stage_name'><span>{$stages[$skey]}</span></div>";
						foreach( $svalue AS $sskey => $ssvalue )
							$str .= $ssvalue;
					}
				}
			}
		}
		else
			$str .= conv("<h2>Нет непрочитанных уведомлений</h2>");
	}

	return $str ;

} // function getNotifications( $why_arr, $plan_fact_page = 0 )

function makeCard( $rec_id, $area, $zak_name, $dse_name, $zak_id,  $note_description, $time, $why, $field, $stage )
{
	global $user_id, $stages;

	$href = '';
	$zak_str = '';
	$zak_details = '';

	$header_str = conv("Уведомление от ").$time.conv(". Область : ").$area;

	if( $zak_id && ( 
						$why != DECISION_SUPPORT_SYSTEM_THEME_CREATE && 
						$why != DECISION_SUPPORT_SYSTEM_NEW_MESSAGE && 
						$why != DECISION_SUPPORT_DECISION_MAKING &&
						$why != DECISION_SUPPORT_DECISION_CONFIRM_REQUEST
					)
			)
	{
		$href = "index.php?do=show&formid=241&list=$zak_id" ;
		$zak_str = conv(". Заказ ").$zak_name. conv(". ДСЕ : ").$dse_name;
		$zak_details = conv("Заказ ")."</span><a target='_blank' href='$href'>$zak_name</a><span> $dse_name";
		$header_str .= $zak_str;
	}

	if( $why == NEW_ENTRANCE_CONTROL_PAGE_ADDED || $why == ENTRANCE_CONTROL_PAGE_DATA_MODIFIED )
	{
		$note_description = "<a href='/index.php?do=show&formid=259#$field' target='_blank' >$note_description</a>";
	}

	if( $why == DECISION_SUPPORT_SYSTEM_THEME_CREATE || $why == DECISION_SUPPORT_SYSTEM_NEW_MESSAGE || $why == DECISION_SUPPORT_DECISION_MAKING || $why == DECISION_SUPPORT_DECISION_CONFIRM_REQUEST )
	{

		$zak_details = "";
		$zak_str = "";
		$note_description = "<a href='/index.php?do=show&formid=283&id=$zak_id&disc_id=$stage' target='_blank' >$note_description</a>";
	}

	if( $why ==  PLAN_FACT_CONFIRMATION_REQUEST )
	{
		$note_description = "<a href='/index.php?do=show&formid=259#$field' target='_blank' >$note_description</a>";
		
		$user = GetUserName( $stage );
		$name = $user['name'];
		$gender = $user['gender'];
		$message = "";
		if( $gender == 1 )
			$message = conv("запросил");
				else
					$message = conv("запросила");
		
		$message .= conv(" подтверждение на этапе: ");

		$stage = $stages[ $field ];
		$note_description = "$name $message &laquo;".$stages[ $field ]."&raquo;";
	}

	$str = 
		"<div class='card' id='card_$rec_id'>
	    <div class='card-header ".( $why == COORDINATION_PAGE_DATA_MODIFIED ? 'alert-danger' : '') ."' role='tab' id='$rec_id'>
	        <a class='collapsed' data-toggle='collapse' data-parent='.accordion' href='#collapse_$rec_id' aria-expanded='true' aria-controls='collapse_$rec_id'>$header_str</a>
	    </div>
	    <div id='collapse_$rec_id' class='collapse' role='tabpanel' aria-labelledby='$rec_id'>
	      <div class='card-block row'>
	      	<div class='col-9'><span>$zak_details $note_description</span></div>
			
			<div class='col-3 text-right'>";
	
// 	$user_id == 145 Трифонов
			
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

$plan_fact_acc .= getNotifications( [
										PLAN_FACT_STATE_CHANGE,
										PLAN_FACT_1_DAY_BEFORE_STATE_END,
										PLAN_FACT_DATE_CHANGE,
										PLAN_FACT_DATE_EXPIRE,
										PLAN_FACT_STATE_END_DATE,
										PLAN_FACT_10_DAY_BEFORE_STATE_END,
										PLAN_FACT_5_DAY_BEFORE_STATE_END,
									], true );
$plan_fact_acc .= "</div>";

$entrance_control_acc = "<div id='accordion' role='tablist' aria-multiselectable='true'>";
$entrance_control_acc .= getNotifications( [
										NEW_ENTRANCE_CONTROL_PAGE_ADDED,
										ENTRANCE_CONTROL_PAGE_DATA_MODIFIED
									] );
$entrance_control_acc .= "</div>";

$conf_request_acc = "<div id='accordion' role='tablist' aria-multiselectable='true'>";

$conf_request_acc .= getNotifications( [
										PLAN_FACT_CONFIRMATION_REQUEST
									] );
$conf_request_acc .= "</div>";

$coord_page_acc = "<div id='accordion' role='tablist' aria-multiselectable='true'>";
$coord_page_acc .= getNotifications( [
										COORDINATION_PAGE_CREATE,
    									COORDINATION_PAGE_DATA_MODIFIED
									 ] );
$coord_page_acc .= "</div>";

$dss_page_acc = "<div id='accordion' role='tablist' aria-multiselectable='true'>";
$dss_page_acc .= getNotifications( [
										DECISION_SUPPORT_SYSTEM_THEME_CREATE,
    									DECISION_SUPPORT_SYSTEM_NEW_MESSAGE,
    									DECISION_SUPPORT_DECISION_MAKING,
    									DECISION_SUPPORT_DECISION_CONFIRM_REQUEST
									] );
$dss_page_acc .= "</div>";

$str .= "
<ul class='nav nav-tabs' id='myTab' role='tablist'>
  <li class='nav-item'>
    <a class='nav-link ".( $tab == 'plan_fact' ? 'active' : '')."' id='home-tab' data-toggle='tab' href='#plan-fact' role='tab' aria-controls='home' aria-selected='true'>".conv("План-факт")."</a>
  </li>

  <li class='nav-item'>
    <a class='nav-link ".( $tab == 'entrance_control' ? 'active' : '')."' id='home-tab' data-toggle='tab' href='#entrance-control' role='tab' aria-controls='home' aria-selected='true'>".conv("Листы входного контроля")."</a>
  </li>

  <li class='nav-item'>
    <a class='nav-link ".( $tab == 'conf_request' ? 'active' : '')."' id='home-tab' data-toggle='tab' href='#conf-request' role='tab' aria-controls='home' aria-selected='true'>".conv("Запрос на подтверждение")."</a>
  </li>
  <li class='nav-item'>
    <a class='nav-link ".( $tab == 'coord_page' ? 'active' : '')."' id='profile-tab' data-toggle='tab' href='#coordination-page' role='tab' aria-controls='profile' aria-selected='false'>".conv("Листы согласования")."</a>
  </li>
  <li class='nav-item'>
    <a class='nav-link ".( $tab == 'dss_page' ? 'active' : '')."' id='profile-tab' data-toggle='tab' href='#decision-support-system-page' role='tab' aria-controls='profile' aria-selected='false'>".conv("Система принятия решений")."</a>
  </li>
 </ul>";

$str .= "<div class='tab-content' id='myTabContent'>
  <div class='tab-pane fade ".( $tab == 'plan_fact' ? 'show active' : '')."' id='plan-fact' role='tabpanel' aria-labelledby='home-tab'>$plan_fact_acc</div>";

$str .= "<div class='tab-pane fade ".( $tab == 'entrance_control' ? 'show active' : '')."' id='entrance-control' role='tabpanel' aria-labelledby='home-tab'>$entrance_control_acc</div>";

$str .= "<div class='tab-pane fade ".( $tab == 'conf_request' ? 'show active' : '')."' id='conf-request' role='tabpanel' aria-labelledby='home-tab'>$conf_request_acc</div>";

$str .= "<div class='tab-pane fade ".( $tab == 'coord_page' ? 'show active' : '')."' id='coordination-page' role='tabpanel' aria-labelledby='profile-tab'>$coord_page_acc</div>";

$str .= "<div class='tab-pane fade ".( $tab == 'dss_page' ? 'show active' : '')."' id='decision-support-system-page' role='tabpanel' aria-labelledby='profile-tab'>$dss_page_acc</div>";
 
echo $str ;
