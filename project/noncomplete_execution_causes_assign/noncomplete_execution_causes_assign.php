<script type="text/javascript" src="/project/noncomplete_execution_causes_assign/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/project/noncomplete_execution_causes_assign/js/noncomplete_execution_causes_assign.js"></script>


<link rel='stylesheet' href='/project/noncomplete_execution_causes_assign/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/noncomplete_execution_causes_assign/css/jquery-ui.css' type='text/css'>

<link rel='stylesheet' href='/project/noncomplete_execution_causes_assign/css/style.css' type='text/css'>

<?php
require_once( "functions.php" );

$user_id = $user['ID'];
$res_id = GetResInfo( $user_id );
$can_edit = 0;

if( $user_id == 13 ) // Матикова
	$can_edit = 1;

$str = "<script>var user_id = $user_id;</script>";
$str .= "<script>var res_id = $res_id;</script>";
$str .= "<script>var can_edit = $can_edit;</script>";

$can_edit = $can_edit ? "" : "disabled";

$users_list = GetUsersOptions();

$head_title = "<div class='head'>
                            <div><h2>".conv( "Назначение ответственных за нарушения при закрытии сменных заданий")."</h2></div>
                      </div><hr>";

$button_group = "
<div class='btn-toolbar mb-3' role='toolbar' aria-label='Toolbar with button groups'>

  <div class='btn-group mr-2' role='group' aria-label='First group'>
    <button class='btn btn-small btn-primary' type='button' id='add_cause' $can_edit>".conv('Добавить причину')."</button>
  </div></div>";

$str .= $head_title ;
$str .= "<div id='main_div'>";
$str .= $button_group ;

$str .= "<div id='table_div'>";
$str .=  "</div>";

$str .=  "</div>";

$str .= "<div id='user_job_dialog' class='hidden' title='".conv("Список ответственных")."'>
			<div>
				<div>
				   <select id='user_select_from' size='10' multiple>
				   $users_list
				   </select>
				</div>
				<div>
				<button id='add_to_team'><img class='icon' src='/uses/svg/arrow-right.svg' /></button>
				<button id='remove_from_team'><img class='icon' src='/uses/svg/arrow-left.svg' /></button>
				</div>
				<div>
		   			<select id='user_select_to' size='10' multiple>
		   			</select>
				</div>
		    </div>
		</div>";

$str .= "<div id='delete_dialog' class='hidden' data-id='0' title='".conv("Удаление записи")."'>
				<div>
					<p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span><span class='dialog_text'>".conv("Данная запись будет удалена. Вы уверены?")."</span></p>				
			    </div>
		</div>";


echo $str ;

