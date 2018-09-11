<?php

define('MAV_ERP', true);
date_default_timezone_set('Asia/Krasnoyarsk');



include '../config.php';
include '../includes/database.php';
	
	$start = trim($_GET['start']);
	$end = trim($_GET['end']);
	
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);


list ($i, $month, $year) = explode('.', $start);

$sql = "SELECT *, SUBSTRING(`okb_db_krz`.`NAME`, -5, 2) as `month`, `okb_db_krz`.`NAME` as NAMEKRZ, okb_db_krzdet.NAME as NAMEKRZDet, okb_db_krz.ID as IDKrz, okb_db_krzdet.OBOZ as KRZDetOBOZ FROM `okb_db_krz`
			LEFT JOIN `okb_db_krzdet` ON `okb_db_krzdet`.`ID_krz` = `okb_db_krz`.`ID`
			WHERE `okb_db_krz`.`NAME` >= '" . $start . "'
			AND `okb_db_krz`.`NAME` <= '" . $end. "'
			AND SUBSTRING(`okb_db_krz`.`NAME`, -5, 2) = " . $month . "
			AND SUBSTRING(`okb_db_krz`.`NAME`, -2, 2) = " . $year . "
			ORDER BY `okb_db_krz`.`NAME`";

$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
	echo '<option value="' . $row['IDKrz'] . '">' . htmlspecialchars($row['NAMEKRZ']) . ' — ' . htmlspecialchars($row['NAMEKRZDet']) . ' — ' . $row['KRZDetOBOZ'] . '</option>' . "\n";
}
							
?>