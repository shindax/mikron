<?php
$ID_resurs = $_GET["p2"];
$smena = $_GET["p1"];
$opercur = $_GET["p3"];
$back_url = "index.php?do=show&formid=112&p0=".$pdate."&p1=".$smena."&p2=".$ID_resurs;

// предыдущая операция
$res3 = dbquery("SELECT * FROM ".$db_prefix."db_zadan where ID_operitems='".$opercur."'");

echo "
<h4><a href='".$back_url."'>Назад</a></h4>
</form><form>
<h2>Сколько выставлено в сменных заданиях:</h2>
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
	
/*$res_13 = dbquery("SELECT COUNT(ID) FROM ".$db_prefix."db_zadan where ((EDIT_STATE='0') and (ID_operitems='".$opercur."'))");
$nam_13 = mysql_fetch_row($res_13);
	echo $nam_13[0]." = ".$opercur;*/
	
	while ($res3_1 = mysql_fetch_array($res3)){
		$res4 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID='".$res3_1['ID_resurs']."')");
		$res4_1 = mysql_fetch_array($res4);
		$date = IntToDate($res3_1['DATE']);
		echo "<tr>
			<td class='Field' style='text-align:center;'><a href='index.php?do=show&formid=64&p0=".$res3_1['DATE']."&p1=".$res3_1['SMEN']."'><b>".$res3_1['ID']."</b></a></td>
			<td class='Field' style='text-align:center;'>".$res4_1['NAME']."</td>
			<td class='Field' style='text-align:center;'>".$date."</td>
			<td class='Field' style='text-align:center;'>".$res3_1['SMEN']."</td>
			<td class='Field' style='text-align:center;'><b style='color:red'>".$res3_1['NUM']."</b></td>
			<td class='Field' style='text-align:center;'><b style='color:red'>".$res3_1['NORM']."</b></td>
			<td class='Field' style='text-align:center;'>".$res3_1['NUM_FACT']."</td>
			<td class='Field' style='text-align:center;'>".$res3_1['NORM_FACT']."</td>
		</tr>";
	}
echo "</tbody></table>";
?>