<?php				
	define("MAV_ERP", TRUE);

	include "../config.php";
	include "../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	
$findes_val = $_GET['p1'];
$id_dse_kyda = $_GET['p2'];

$arr_tid = explode("|","|ОЗ|КР|СП|БЗ|ХЗ|ВЗ");

$resp_txt = "";
$rs_1 = dbquery("SELECT ID, ID_zak, NAME, OBOZ FROM okb_db_zakdet where NAME like '%".$findes_val."%' OR OBOZ like '%".$findes_val."%' order by NAME, OBOZ");
while ($tx_1 = mysql_fetch_array($rs_1)) {
	$rs_2 = dbquery("SELECT ID, NAME, TID FROM okb_db_zak where ID=".$tx_1['ID_zak']);
	$tx_2 = mysql_fetch_array($rs_2);
	$resp_txt = $resp_txt."<tr><td class='Field'><a href='index.php?do=show&formid=39&id=".$tx_2['ID']."' target='_blank'><img src='uses/view.gif' alt='Просмотр'></a>".$arr_tid[$tx_2['TID']]."&nbsp;&nbsp;".$tx_2['NAME']."</td>
	<td class='Field'>". $tx_1['NAME']."</td>
	<td class='Field'>".$tx_1['OBOZ']."</td>
	<td class='Field'><input type=button value='Копировать' onclick='copy_dsetodse(".$tx_1['ID'].", ".$id_dse_kyda.")'></td></tr>";
}

echo $resp_txt;
?>
