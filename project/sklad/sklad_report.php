<style>
.wh_table
{
	width : 1000px;
	table-layout: fixed;
}

.wh_table td.AL
{
	text-align: left ;
}

.wh_table td.AC
{
	text-align: center ;
}

.wh_table td
{
	vertical-align: middle ;
}

</style>	

<?php

	$wh_name = mysql_result(dbquery("SELECT `NAME` FROM `okb_db_sklades` WHERE ID = " . $_GET['p0']), 0 );

	echo "<div><h4>$wh_name".'&nbsp;&nbsp;<a href="/print.php?do=show&formid=224&p0=' . $_GET['p0'] . '" class="top_menu" target="_blank">Печать</a>'."</h4>";

	

	$query = "SELECT sy.ID as ID, si.NAME, 
					 SUBSTR( si.NAME, 1, 1 ) AS ord_name_alpha,
					 CAST( SUBSTR( si.NAME, 2, LENGTH( si.NAME ) ) AS UNSIGNED ) AS ord_name_int,

					 sy.ORD as ORD, COUNT(sd.ID) as YarusItemCount FROM okb_db_sklades_yaruses sy
						LEFT JOIN okb_db_sklades_detitem sd ON sd.ID_sklades_yarus = sy.ID 
						LEFT JOIN okb_db_sklades_item si ON si.ID = sy.ID_sklad_item
						WHERE ID_sklad = " . $_GET['p0'] . "
						GROUP BY sy.ID
						ORDER BY ord_name_alpha, ord_name_int";
	
	$result = dbquery( $query ) ;

	while ($row = mysql_fetch_array($result)){
		if ($row['YarusItemCount'] == 0) continue;
		
		echo '<div><h4>Ячейка: ' . $row['NAME'] . '. Ярус: ' . ($row['ORD'] == 0 ? 'Пол' : $row['ORD']) . '.</h4>
	
		<table class="tbl wh_table">
		<col width="2%">
		<col width="20%">
		<col width="20%">
		<col width="20%">
		<col width="5%">

		<tr class="first">
		<td>№</td>
		<td>Наименование ДСЕ</td>
		<td>Чертеж</td>
		<td>Комментарий</td>
		<td>Кол-во</td> 
		</tr>';
		
		$result2 = dbquery("SELECT sd.COUNT,sd.ORD,sd.KOMM,zd.NAME,zd.OBOZ,sd.NAME as ItemName FROM okb_db_sklades_detitem sd
						LEFT JOIN okb_db_semifinished_store_invoices ssi ON ssi.id = sd.ref_id
						LEFT JOIN okb_db_zakdet zd ON zd.id = ssi.id_zakdet
						WHERE ID_sklades_yarus = " . $row['ID']);
		
		while ($row2 = mysql_fetch_assoc($result2)) {
			if (empty($row2['ref_id'])) {
				$row2['NAME'] = $row2['ItemName'];
			}
			
			echo '<tr>'
				,'<td class="field AC">' . $row2['ORD'] . '</td>'
				,'<td class="field AL">' . $row2['NAME'] . '</td>'
				,'<td class="field AL">' . $row2['OBOZ'] . '</td>'
				,'<td class="field AL">' . $row2['KOMM'] . '</td>'
				,'<td class="field AC">' . $row2['COUNT'] . '</td>' 
				,'</tr>';
					
		}
		
		echo '</table></div>';
	}

?>
</div>
</center>
