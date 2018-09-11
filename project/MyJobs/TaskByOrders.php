<link rel='stylesheet' href='/project/MyJobs/css/myCSS.css' type='text/css'>
<script type="text/javascript" src="/project/MyJobs/js/treeView.js"></script>    
<script type="text/javascript" src="/project/MyJobs/js/myJobs.js"></script>    

<?php
include "TaskByOrderFunctions.php";
// <script type="text/javascript" src="/project/MyJobs/js/myJobs.js"></script>    
// *********************************************************************************************

  
// ********************************************************************************************
$users = array ( 3,10,13,23,31,33,41,58,77,124,134,142,192,193,203,231,273,275,283,291,293,312,320,337,343 );

if( 0 )
{    
    Clear5();
    foreach( $users AS $user_id )
    {
        $zak_arr = AdjustOrders( $user_id );
        UpdateOrders( $zak_arr );
    }
    HardSubordinate();
}

$zak_list = GetZakList(  ); // ( 293 )
$task_list = GetTaskList( $zak_list ); // ( $zak_list, 293 )
unset( $zak_list );
$task_list = CreateSubordinate( $task_list );
echo CreateTree( $task_list );

?>


