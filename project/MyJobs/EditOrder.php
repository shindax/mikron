<script type="text/javascript" src="/project/MyJobs/js/editOrder.js"></script>

<?php
function SplitDate( $raw_date, $our_format = 0 )
{
  $date = array();
  $date[0] = substr( $raw_date, 0, 4 );
  $date[1] = substr( $raw_date, 4, 2 );
  $date[2] = substr( $raw_date, 6, 2 );

  if( $our_format )
  {
    $date = $date[2].".".$date[1].".".$date[0] ;  
  }
  else
  {
    $date = $date[0]."-".$date[1]."-".$date[2] ;
  }
  
  return $date ;
}


function RowOut( $render_row , $field )
{
  global $db_prefix, $user;

  $can_edit = 0;

  $user_id = $user['ID'];

  $id = $render_row['ID'] ;

  $proj_id = $render_row['ID_proj'] ;
  $raw_date = $render_row[ $field ] ;
  $exec_list = explode('|', $render_row[ 'ID_users2' ] );

  $query = "SELECT `ID` FROM `".$db_prefix."db_resurs` where `ID_users` = $user_id " ;

  $res = dbquery( $query );
	$res_row = mysql_fetch_assoc($res);
  $res_id = $res_row['ID'];

  if( $res_id == '' )
      $res_id = 1 ;

  echo "<script>var proj_id=$proj_id; var user_id=$res_id;</script>";

  $res = dbquery("SELECT * FROM ".$db_prefix."db_projects where  ID = $proj_id");
	$prj_row = mysql_fetch_assoc($res);

  $id_creator = $prj_row['ID_creator'];

  if( $user_id  == 1 || $id_creator == $res_id  )
      $can_edit = 1 ;

  if( $can_edit )
  {
    $date = SplitDate( $raw_date );

    $beg_date_plan = $prj_row['beg_date_plan'];
    $end_date_plan = $prj_row['end_date_plan'];

    $min = SplitDate( $beg_date_plan );
    $max = SplitDate( $end_date_plan );
  
    return "<input class='one_row_data' data-id='$id' data-field='$field' type='date' min='$min' max='$max' name='db_itrzadan_".$field."_edit_$id' value='$date' data-old_value='$date'/>";
 }
 else
 {
  $date = SplitDate( $raw_date , 1 );
  return "<span>$date</span>";
 }    
}

?>
