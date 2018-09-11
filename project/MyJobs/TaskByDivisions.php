<link rel='stylesheet' href='/project/MyJobs/css/myCSS.css' type='text/css'>
<script type="text/javascript" src="/project/MyJobs/js/treeView.js"></script>    
<script type="text/javascript" src="/project/MyJobs/js/myJobsByDivision.js"></script> 

<script type="text/javascript" src="/project/MyJobs/js/jquery-ui.min.js"></script>
<link rel='stylesheet' href='/project/MyJobs/css/jquery-ui.css' type='text/css'>

<?php

include "TaskByDivisionFunctions.php";

$arr = getEnterpriseState();
GetTasks( $arr );
CalcTaskCount( $arr );
echo CreateStateTree( $arr );

?>
