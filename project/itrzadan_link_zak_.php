<?php

$result = dbquery("SELECT * FROM okb_db_itrzadan where (ID='".$render_row['ID']."') ");
$name = mysql_fetch_array($result);
$name2 = $name['ID_zak'];

$result2 = dbquery("SELECT * FROM okb_db_zak where (ID='".$name2."') ");
$name5 = mysql_fetch_array($result2);
$zak_tip = array(" ","ÎÇ","ÊÐ","ÑÏ","ÁÇ","ÕÇ","ÂÇ");

if ($name2 !== '0')	
{
	$asd2 = "<a href='index.php?do=show&formid=39&id=".$name2."'><img src='uses/view.gif'></a><b>".$zak_tip[$name5['TID']]."&nbsp;&nbsp;".$name5['NAME']."</b>&nbsp;&nbsp;&nbsp;".$name5['DSE_NAME'];
}
?>