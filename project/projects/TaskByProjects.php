<link rel='stylesheet' href='/project/projects/css/bootstrap/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/projects/css/jquery-ui.css' type='text/css'>
<link rel='stylesheet' href='/project/projects/css/myCSS.css' type='text/css'>
<link rel='stylesheet' href='/project/projects/css/my_autocomplete.css' type='text/css'>


<script type="text/javascript" src="/project/projects/js/projects.js"></script>
<script type="text/javascript" src="/project/projects/js/editProjectAJAX.js"></script>
<script type="text/javascript" src="/project/projects/js/uploadProjectFiles.js"></script>
<script type="text/javascript" src="/project/projects/js/sortByProjects.js"></script>
<script type="text/javascript" src="/project/projects/js/my_autocomplete.js"></script>
<script type="text/javascript" src="/project/projects/js/insertOneRowAfterAJAX.js"></script>

<script type="text/javascript" src="/project/projects/js/tether.min.js"></script>
<script type="text/javascript" src="/project/projects/js/bootstrap.min.js"></script>

<?php

$configpath = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."/config.php" );
require_once( $configpath );

require_once("CommonFunctions.php");
require_once("pie.php");
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

global $user, $EDIT_PROJECT_PAGE_ID;

$right_groups = $user['ID_rightgroups'];
$right_groups = explode('|', $right_groups );
$uder_id = $user['ID'];

// Для скрытия кнопок 'Добавить'
$can_add = 0 ;
foreach( $right_groups AS $val )
        if( ( $val == '68' ) || ( $val == '1' ) )
            $can_add = 1 ;


echo "<script>var can_add = $can_add ;
       var edit_order_page = $PROJECT_ORDER_DETAIL_PAGE_ID ;
       var edit_project_page = $EDIT_PROJECT_PAGE_ID ;
      </script>";

error_reporting( 0 );
ini_set('display_errors', false);

error_reporting( E_ALL );
ini_set('display_errors', true);

$project_row_ind_count = 0 ;

require_once "TaskByProjectFunctions.php";

//debug( $user );

echo "<script>var user_id = ".$user['ID'].";</script>";

echo "<div id='ProjectsDiv'>";
echo GetDataBeforeProjectTree();
//$arr = GetProjectsList( 0, true );
$arr = GetProjectsList( 0, false );
CalcProjectsTaskCount( $arr ) ;
echo CreateProjectTree( $user['ID'], $arr );
echo GetDataAfterProjectTree();
echo "</div>";

// Элементы для загрузки файлов
echo '<input id="upload_file_input" type="file" accept="image/*">' ;
// Элементы для AJAX
echo '<img id="loadImg" src="project/img/loading_2.gif" />';

?>
