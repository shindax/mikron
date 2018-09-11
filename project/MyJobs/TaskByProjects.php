<link rel='stylesheet' href='/project/MyJobs/css/myCSS.css' type='text/css'>

<script type="text/javascript" src="/project/MyJobs/js/myJobs.js"></script>    
<script type="text/javascript" src="/project/MyJobs/js/editProjectAJAX.js"></script>
<script type="text/javascript" src="/project/MyJobs/js/uploadProjectFiles.js"></script>
<script type="text/javascript" src="/project/MyJobs/js/sortByProjects.js"></script>

<?php
global $user ;

error_reporting( E_ALL );
//error_reporting( 0 );

$project_row_ind_count = 0 ;

include "TaskByProjectFunctions.php";

echo "<div id='ProjectsDiv'>";

$arr = GetProjectsList();
CalcProjectsTaskCount( $arr ) ;
echo CreateProjectTree( $arr );

echo "</div>";

// Ёлементы дл€ загрузки файлов
echo '<input id="upload_file_input" type="file" accept="image/*">' ;
// Ёлементы дл€ AJAX
echo '<img id="loadImg" src="project/img/loading_2.gif" />';

?>
