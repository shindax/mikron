<?php
global $user ;

$right_groups = $user['ID_rightgroups'];
$user_id = $user['ID'];
$right_groups = explode('|', $right_groups );

$can = 0 ;

foreach( $right_groups AS $val )
{
        if( ( $val == '13' ) || ( $val == 1 ) )
          {
            $can = 1 ;
            break ;
          }
}

			$val = FVal($row,$db,'DATE');
			$sval = $val;
			if ($val=="") $sval = "---";
			if ($val=="") $val = $today_0;
			$val = explode(".",$val);
       
      $id = $row['ID'];
      $date = $row['DATE'];

      echo "<script>var id = $id ;</script>";

      $year = substr( $date, 0, 4 );  
      $month = substr( $date, 4, 2 );
      $day = substr( $date, 6, 2 );      
			$full_date = $year."-".$month."-".$day ;
			$ronly_full_date = "$day.$month.$year" ;
			
      if( $can )
        $str = "<input class='manual_input' style='cursor: hand; width=100%' type='date' value='$full_date'/>";
          else
            $str = $ronly_full_date;

			echo $str;
     
?>
<script>
function updateDate()
{
  var value = $( this ).val();
  
  if( ! value.length )
    {
      alert( 'Неверная дата' );
      $( this ).val('');
      return ;
    }
    
    var newDate = value.substr(0,4)+value.substr(5,2)+value.substr(8,2);
    var url = 'db_edit.php?db=db_resurs&field=DATE&id=' + id + '&value=' + newDate;
    vote( this, url );
}

$( function()
{
  $('.manual_input').parent().attr('class','rwField ntabg');
  $('.manual_input').blur( updateDate );
});
      
</script>
