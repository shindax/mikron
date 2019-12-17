<script src="/js/tinymce/tinymce.min.js"></script>  
<script type="text/javascript" src="/project/dss/js/dss.js?arg=0"></script>
<script type="text/javascript" src="/project/dss/js/jquery-ui.min.js"></script>

<link rel='stylesheet' href='/project/dss/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/dss/css/style.css' type='text/css'>
<link rel='stylesheet' href='/project/dss/css/levels.css' type='text/css'>
<link rel='stylesheet' href='/project/dss/css/disc.css' type='text/css'>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemItem.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemDiscussion.php" );
// error_reporting( E_ALL );
error_reporting( E_ERROR );

error_reporting( 0 );

function conv( $str )
{
   global $dbpasswd;
   return iconv( "UTF-8", "Windows-1251",  $str );    	
}

function GetResInfo( $user_id )
{
	global $user, $pdo;

    try
    {
       $query ="SELECT ID, NAME FROM `okb_db_resurs` WHERE ID_users = $user_id";
       $stmt = $pdo -> prepare( $query );
       $stmt->execute();
    }

    catch (PDOException $e)
    {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
    }

    $res_id = 0 ;
    
    if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
      $res_id = $row -> ID;

    return $res_id ;
 }

global $user;

$user_id = $user['ID'];
$res_id = GetResInfo( $user_id );
$base_id = 0 ;

$str = "<script>var user_id = $user_id</script>";
$str .= "<script>var res_id = $res_id</script>";
$str .= "<script>var prj_id = 0</script>";
$str .= "<script>var disc_id = 0</script>";

if( isset( $_GET['id'] ) )
	{
		$disc = new DecisionSupportSystemItem( $pdo, $res_id, $_GET['id'] );
 		$chain = $disc -> GetChain() ;
		$str .= "<script>prj_id = {$_GET['id']}</script>"; 
	}

if( isset( $_GET['disc_id'] ) )
	{
		$str .= "<script>disc_id = {$_GET['disc_id']}</script>"; 
	}


$str .= "<div class='container'>";

$str .= "<div class='row'>";
$str .= "<div class='col-sm-12'><h2>".conv( "Система принятия решений")."</h2></div>";
$str .= "</div>";

$str .= "<div class='row'>
         <div class='col-sm-12'>
         <button class='btn btn-small btn-primary pull-right add_project' type='button'>".conv('Добавить проект')."</button>
         </div></div>";

$str .=  "<div class='row'>";
$str .= "<div class='col-sm-12'>";
$str .= "<table class='tbl dss_table'>";

$str .= "<col width='1%'>";
$str .= "<col width='30%'>";
$str .= "<col width='30%'>";
$str .= "<col width='10%'>";
$str .= "<col width='10%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='2%'>";

$str .= "<tr class='first'>";
$str .= "<td class='Field' colspan='2'>".conv("Проект")."</td>";
$str .= "<td class='Field'>".conv("Описание")."</td>";
$str .= "<td class='Field'>".conv("Автор")."</td>";
$str .= "<td class='Field'>".conv("Дата создания")."</td>";
$str .= "<td class='AC Field'><div><img class='head_icon' src='/uses/svg/settings-4.svg' /></div></td>";
$str .= "<td class='AC Field'><div><img class='head_icon' src='/uses/svg/speech-bubble-right-4.svg' /></div></td>";
$str .= "<td class='AC Field'><div><img class='head_icon' src='/uses/svg/users.svg' /></div></td>";
$str .= "<td class='AC Field'><div><img class='head_icon' src='/uses/svg/camera.svg' /></div></td>";
$str .= "<td class='AC Field'><div><img class='head_icon' src='/uses/del.png' /></div></td>";
$str .= "</tr>";

$str .= "<tbody class='draggable'>";

try
{
    $query ="	SELECT id
                FROM `dss_projects`
                WHERE parent_id = 0
                ORDER BY ord
                ";
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$dss_item = 0 ;

while ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
{
	$id = $row -> id;

	if( count( $chain ) && $id == $chain[0] )
	{
		$level = 0;
		foreach( $chain AS $key => $val )
		{
			$dss_item = new DecisionSupportSystemItem( $pdo, $res_id, $val, $level );
			$str .= conv( $dss_item -> GetTableRow('','Field') );
			$level += 20 ;
		}
	}
	else
	{
		$dss_item = new DecisionSupportSystemItem( $pdo, $res_id, $id );
		$str .= conv( $dss_item -> GetTableRow('','Field') );
	}
}

if( $dss_item )
	$user_list = $dss_item -> GetUserListOption();

$str .= "</tbody>";
$str .= "</table>";
$str .= "</div><!--div class='col-sm-12'-->";
$str .= "</div><!--div class='row'-->";
$str .= "</div><!--div class='container'-->";

$str .= "<div id='user_job_dialog' class='hidden' title='".conv("Участники направления")."'>
			<div>
				<div>
					<select id='user_select_from' size='10' multiple>
					$user_list
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


$str .= "<div id='picture_job_dialog' title='".conv("Прикрепленные документы")."'>
			<div>
		    </div>
		</div>";



$str .= "<div id='discussions_job_dialog' title='".conv("Обсуждения")."'>
				<div class='discussions_themes'>
			    </div>
				<div class='discussions'>
			    </div>
		</div>";

$str .= "<div id='discussions_dialog_response' title='".conv("Ответ")."'>
				<div>
				<textarea class='resp_textarea'></textarea>
			    </div>
		</div>";

$str .= "<div  class='hidden' id='discussions_dialog_new_theme' title='".conv("Новое обсуждение")."'>
				<div>
				<span>".conv("Тема")."</span>
				<input class='new_theme_input' />
				<span>".conv("Сообщение")."</span>
				<textarea class='new_theme_textarea'></textarea>
			    </div>
		</div>";

$str .= "<div class='hidden' id='discussions_dialog_theme_decide' title='".conv("Принять решение")."'>
				<div>
					<span>".conv("Тема : ")."</span><span class='theme_decision_theme'></span><br>
					<span>".conv("Автор : ")."</span><span class='theme_decision_author'></span><br>
					<span>".conv("Окончательное решение")."</span>
					<textarea class='theme_decision_textarea'></textarea>

										<div class='confirmation_set'>
						<div>
							<span>".conv("Группа")."</span>
							<select id='confirm_select_from' size='5' multiple></select>
						</div>
						<div>
						<button id='add_to_confirm'><img class='icon' src='/uses/svg/arrow-right.svg' /></button>
						<button id='remove_from_confirm'><img class='icon' src='/uses/svg/arrow-left.svg' /></button>
						</div>
						<div>
							<span>".conv("Подтверждающие")."</span>
				   			<select id='confirm_select_to' size='5' multiple></select>
						</div>
					</div>
			    </div>
		</div>";


$str .= "<div class='hidden' id='project_create_dialog' data-id='0' title='".conv("Создание нового проекта")."'>
				<div>
					<span>".conv("Название проекта : ")."</span><br>
					<div class='wrap'>
						<input id='new_project_name_input' />
  						<div class='toolbar'>
      					<button id='refresh'>".conv("Обновить")."</button>
      					<button id='close'>".conv("Закрыть")."</button>
      					</div>
      				</div>	

					<span>".conv("Краткое описание : ")."</span><input id='new_project_short_name_input' />
					<span>".conv("Подробное описание : ")."</span>
					<textarea class='project_create_textarea'></textarea>
			    </div>
		</div>";


$str .= "<div id='delete_row_dialog' class='hidden' data-id='0' title='".conv("Удаление записи")."'>
				<div>
					<p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>".conv("Все данные записи, включая присоединенные документы будут удалены. Вы уверены?")."</p>				
			    </div>
		</div>";


// Элемент для загрузки файлов
$str .= "<input id='upload_file_input' data-id='' data-what='' type='file' accept='*' class='hidden' multiple>" ;

$str .="<img ='*'' class='hidden' id='loadImg' src='project/img/loading_2.gif' />";

echo $str ;

// ******************************************************************************

 // $disc = new DecisionSupportSystemItem( $pdo, $res_id, 211 );
 // debug( $disc -> GetData(), 1 );