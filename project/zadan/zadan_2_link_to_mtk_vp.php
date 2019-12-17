<style>

#coop_table
{
	width: 705px ;
  	table-layout : fixed ;
}
#coop_table td
{
	vertical-align: middle;
}

strong
{
  font-weight: bold ;
  color:red;
}

strong.black
{
  text-decoration: underline;
   color:black;
}

.first strong
{
   color:black;
}

td.Field
{
  vertical-align: middle;
}

</style>

<?php

$ID_resurs = $_GET["p2"];
$smena = $_GET["p1"];
$opercur = $_GET["p3"];
$back_url = "index.php?do=show&formid=112&p0=".$pdate."&p1=".$smena."&p2=".$ID_resurs;

// предыдущая операция
$query = "SELECT * FROM ".$db_prefix."db_zadan where ID_operitems=".$opercur;
echo $query;
$res3 = dbquery( $query );

echo "
<h4><a href='".$back_url."'>Назад</a></h4>
</form><form>
<h2>Выполнено по операции:</h2>
<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: rgb(0, 0, 0); padding: 0px;' border='1' cellpadding='0' cellspacing='0'>
	<thead>
	<tr class='first'>
		<td width='60px'>ID СЗ</td>
		<td width='200px'>Ресурс</td>
		<td width='80px'>Дата</td>
		<td width='40px'>Смена</td>
		<td width='60px'>План<br>Кол-во</td>
		<td width='60px'>План<br>Н/Ч</td>
		<td width='60px'>Факт<br>Кол-во</td>
		<td width='60px'>Факт<br>Н/Ч</td>
	</tr>
	</thead>
	<tbody>";

	$plan_sum = 0;
	$fact_sum = 0;
      $count_sum = 0;

	while ($res3_1 = mysql_fetch_array($res3))
    {
    	$query = "SELECT * FROM ".$db_prefix."db_resurs where ID=".$res3_1['ID_resurs'];
		$res4 = dbquery( $query );
		$res4_1 = mysql_fetch_array($res4);
		$date = IntToDate($res3_1['DATE']);

            if( $res4_1['NAME'] == '' )
                $res4_1['NAME'] = '<strong class="black">Кооперация</strong>';

		echo "<tr>
			<td class='Field' style='text-align:center;'><a href='index.php?do=show&formid=64&p0=".$res3_1['DATE']."&p1=".$res3_1['SMEN']."'><b>".$res3_1['ID']."</b></a></td>
			<td class='Field' style='text-align:center;'>".$res4_1['NAME']."</td>
			<td class='Field' style='text-align:center;'>".$date."</td>
			<td class='Field' style='text-align:center;'>".$res3_1['SMEN']."</td>
			<td class='Field' style='text-align:center;'>".$res3_1['NUM']."</td>
			<td class='Field' style='text-align:center;'>".$res3_1['NORM']."</td>
			<td class='Field' style='text-align:center;'><strong>".$res3_1['NUM_FACT']."</strong></td>
			<td class='Field' style='text-align:center;'><strong>".$res3_1['FACT']."</strong></td>
		</tr>";

		$fact_sum += $res3_1['FACT'];
		$plan_sum += $res3_1['NORM'];
            $count_sum += $res3_1['NUM_FACT'];
	}

echo '<tr><td class="Field" style="text-align:center;"><b>Итого:</b></td>
	<td class="Field"></td>
	<td class="Field"></td>
	<td class="Field"></td>
	<td class="Field"></td>
	<td class="Field" style="text-align:center;"><strong>' . $plan_sum .'</strong></td>
	<td class="Field" style="text-align:center;"><strong>'.$count_sum.'</strong></td>
	<td class="Field" style="text-align:center;"><strong>' . $fact_sum .'</strong></td>
</tr>';

echo "</tbody></table>";

// shindax
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );


function conv( $str )
{
  global $dblocation ;

  if( $dblocation == "127.0.0.1" )
    $result = iconv("UTF-8", "Windows-1251", $str );
      else
        $result = iconv("UTF-8", "Windows-1251", $str );
//        $result = $str ;

  return $result;
}

try
{
    $query = "SELECT
    			 DAYOFMONTH(date) day,
    			 MONTH(date) month,
    			 YEAR(date) year,
    			 count,
                  norm_hours,
    			 comment
    			 FROM `okb_db_operations_with_coop_dep` WHERE `oper_id`=$opercur" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$row_count = $stmt -> rowCount() ;

$count_sum = 0 ;

 if( $row_count )
 {
      $norm_hours_sum = 0 ;

	$str = "<h3>Учет работ по кооперации</h3>";
	$str .= "<table id='coop_table' class='rdtbl tbl'>";
	$str .= "<col width='15%'>";
	$str .= "<col width='15%'>";
      $str .= "<col width='15%'>";
	$str .= "<col width='55%'>";
	$str .= "<tr class='first'>";
	$str .= "<td class='Field'><strong>Дата</strong></td>";
	$str .= "<td class='Field'><strong>Факт<br>Кол-во</strong></td>";
      $str .= "<td class='Field'><strong>Факт<br>Н/ч</strong></td>";
	$str .= "<td class='Field'><strong>Комментарии</strong></td>";
	$str .= "</tr>";

	   while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
	         {
	         		$day = $row -> day > 9 ? $row -> day : "0".$row -> day;
	         		$month = $row -> month > 9 ? $row -> month : "0".$row -> month;
	         		$year = $row -> year;
	         		$date = "$day.$month.$year";
				$str .= "<tr>";
				$str .= "<td class='Field AC'>$date</td>";
				$str .= "<td class='Field AC'>".( $row -> count)."</td>";
                       $str .= "<td class='Field AC'>".( $row -> norm_hours)."</td>";
				$str .= "<td class='Field AL'>".conv( $row -> comment)."</td>";
				$str .= "</tr>";

                        $count_sum += $row -> count ;
                        $norm_hours_sum += $row -> norm_hours ;
	         }

      $str .= "<tr>";
      $str .= "<td class='Field AC'>Итого</td>";
      $str .= "<td class='Field AC'><strong>$count_sum</strong></td>";
      $str .= "<td class='Field AC'><strong>$norm_hours_sum</strong></td>";
      $str .= "<td class='Field AC'></td>";
      $str .= "</tr>";

	$str .= "</table>";
	echo $str;
 }

?>