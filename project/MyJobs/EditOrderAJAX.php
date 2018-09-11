<?php
header('Content-Type: text/html; charset=windows-1251');
error_reporting( E_ALL );

require_once("CommonFunctions.php");

$ord_id = $_POST['id'];
$proj_id = $_POST['proj_id'];
$user_res_id = $_POST['user_id'];
$field = $_POST['field'];
$comment = $_POST['comment'];

$date_new = $_POST['date_str'];
$date_new = substr( $date_new , 0, 4 )."-".substr( $date_new , 4, 2 )."-".substr( $date_new , 6, 2 );      

// Change logging start

    $query ="SELECT * FROM okb_db_itrzadan WHERE ID = $ord_id"; 

    $result = $mysqli -> query( $query );

    if( ! $result )
        exit("Îøèáêà îáğàùåíèÿ ê ÁÄ ¹1 â ôàéëå EditOrderAJAX.php : ".$query." : ".$mysqli->error); 

    $row = $result -> fetch_assoc();
    
    $beg_date_old = substr( $row['STARTDATE'] , 0, 4 )."-".substr( $row['STARTDATE'], 4, 2 )."-".substr( $row['STARTDATE'], 6, 2 );
    $beg_date_new = $beg_date_old ;
    
    $end_date_old = substr( $row['DATE_PLAN'], 0, 4 )."-".substr( $row['DATE_PLAN'], 4, 2 )."-".substr( $row['DATE_PLAN'], 6, 2 );      
    $end_date_new = $end_date_old ;
  
    $today = date("Y-m-d H:i:s", time());

    if( $field == 'STARTDATE' )
      $beg_date_new = $date_new ;

    if( $field == 'DATE_PLAN' )
      $end_date_new = $date_new ;

    $query ="INSERT INTO okb_db_project_orders_date_changes_history 
           VALUES(
           NULL,
           $proj_id,
           $ord_id,
           '$today',
           '$beg_date_old',
           '$end_date_old',
           '$beg_date_new',
           '$end_date_new',
            $user_res_id,
            $user_res_id,
            '$comment'
           )";

    $result = $mysqli -> query( $query );

    if( ! $result ) 
        exit("Îøèáêà îáğàùåíèÿ ê ÁÄ ¹2 â ôàéëå EditOrederAJAX.php : ".$mysqli->error); 

              $str =  "<tr>";            
           
              $str .=  "<td class='field'>".date_format( date_create( $today ),"d.m.Y")."</td>";
              $str .=  "<td class='field'>".date_format( date_create( $beg_date_old ),"d.m.Y")."</td>";
              $str .=  "<td class='field'>".date_format( date_create( $end_date_old ),"d.m.Y")."</td>";

              $changed = $beg_date_old == $beg_date_new ? '' : 'changed';
              $str .=  "<td class='field $changed'>".date_format( date_create( $beg_date_new ),"d.m.Y")."</td>";
              
              $changed = $end_date_old == $end_date_new ? '' : 'changed';              
              $str .=  "<td class='field $changed'>".date_format( date_create( $end_date_new ),"d.m.Y")."</td>";
              
              $str .=  "<td class='field'></td>";
              $str .=  "<td class='field'>$comment</td>";
              $str .=  "</tr>";

// Change logging end

//echo iconv("Windows-1251", "UTF-8", $str );
echo json_encode( array( 'key' =>  $str  ) );

?>
