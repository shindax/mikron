<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

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