<style>
div.VCD {
display: block;
-o-transform: rotate(90deg);
-moz-transform: rotate(90deg);
-webkit-transform: rotate(90deg);
font-height: 16px;
padding: 0;
margin: 0;
height: 12px;
width: 12px;
}
</style>

<?php

	if (!defined("MAV_ERP")) { die("Access Denied"); }

	$step = 1;

	$date1 = $_GET["p1"];
	$pdate1 = substr($date1,5,2);

	if ($pdate1>0) $step = 2;

if ($step==1) 
{
	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";


	echo "<h2>Дни рождения персонала</h2>";

	echo "<table class='tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 700px;' border='1' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='250'>Параметр</td>";
	echo "<td>Значение</td>";
	echo "</tr>\n";

	echo "<tr><td class='Field first'><b>Выберите нужный месяц, любой день</b></td><td class='rwField ntabg'>";
	echo "<input name='p1' type='date'>";
	echo "</td></tr>\n";
	
	echo "</table>\n";

	$prturl = str_replace ("index.php","print.php", $pageurl);
	echo "<br><table style='width: 700px;'><tr><td style='text-align: right;'><input type='submit' value='Показать'></td></tr></table>";

}


if ( $step == 2 ) 
{
  $curdate = 0 ;
  
  if( isset( $_GET["p1"] ) )
    $curdate = $_GET["p1"];

  if( $curdate == 0 )
    return;

  $month_names = 	['январе','феврале','марте','апреле','мае','июне','июле','августе','сентябре','октябре','ноябре','декабре'];
  $year = substr( $curdate, 0, 4 );
  $month = substr( $curdate, 5, 2 );
  $day = substr( $curdate, 8, 2 );

  switch( $month )
  {
    case 1:
    case 3:
    case 5:
    case 7:
    case 8:
    case 10:
    case 12:    
             $day = 31; break ;

    case 4:
    case 6:
    case 9:
    case 11:
             $day = 30; break ;

    default : 
              $day = $year % 4 ? 29 : 28 ;
             
  }
  
    $curdate = "$day.$month.$year";

	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";
	echo "<input type='hidden' name='p1' value='$curdate'>";
	echo "<input type='hidden' name='p2' value='".$_GET["p2"]."'>";

	echo "<h2>Дни рождения работников в ".$month_names[ $month - 1 ]." $year г.</h2>";
		
		echo "<table class='rdtbl tbl' style='width: 500px;' cellpadding='0' cellspacing='0'><tbody><tr class='first'>
		<td>Дата рождения</td><td>ФИО</td><td>Сколько<br>исполняется</td>
		</tr>";
		
		$yyy = dbquery("SELECT FF, II, OO, DATE, DATE_FROM FROM ".$db_prefix."db_resurs where TID='0' order by RIGHT(DATE,2),NAME");

		while ($xxx = mysql_fetch_array($yyy)) 
			if( $pdate1 == substr( $xxx['DATE'], 4, 2 ) ) 
			{
        $date_of_birth = substr($xxx['DATE'],6,2).".".substr($xxx['DATE'],4,2).".".substr($xxx['DATE'],0,4);

        $from_date = new DateTime( $date_of_birth );  
        $to_date = new DateTime( $curdate );
        
        $diff = $to_date -> diff( $from_date );
        $full_years = $diff->format('%Y');
		
				echo "<tr>
				<td width='100px' class='Field'>$date_of_birth</td>
				<td width='250px' class='Field'>".$xxx['FF']." ".$xxx['II']." ".$xxx['OO']."</td>";
				echo "<td width='50px' class='Field'>".AgeWithSuffix( $full_years )."</td></tr>";					
			}
		
	echo "</tbody></table>";
}

function AgeWithSuffix( $number ) 
{ 
  $titles = [ 'год', 'года', 'лет'];
  $cases = [ 2, 0, 1, 1, 1, 2 ];  
  $suffix = $titles[( $number % 100 > 4 && $number % 100 < 20 ) ? 2 : $cases[ ( $number % 10 < 5 ) ? $number % 10 : 5 ]];
  
  return "$number $suffix" ;  
}


?>
