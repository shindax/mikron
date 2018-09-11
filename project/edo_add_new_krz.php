<?php
	define("MAV_ERP", TRUE);

	include "../config.php";
	include "../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	
$arr_dse_nam = explode("|", $_GET['p11']);
$arr_dse_obz = explode("|", $_GET['p12']);
$arr_dse_cou = explode("|", $_GET['p8']);

for($aa=0; $aa<$_GET['p10']; $aa++){

	$result2 = dbquery("SELECT MAX(ID) FROM okb_db_krz ");
	$row2 = mysql_fetch_row($result2);
	$result = dbquery("SELECT NAME FROM okb_db_krz WHERE ID=".$row2[0]);
	$row = mysql_fetch_array($result);
	$expl_oboz = explode(".", $row['NAME']);
	if ((($expl_oboz[1]*1)==(date("m")*1)) and (($expl_oboz[2]*1)==(date("y")*1))) {
		$oboz_1 = $expl_oboz[0]+1;
		if ($oboz_1<10) $oboz_1 = "0".$oboz_1;
		if ($oboz_1<100) $oboz_1 = "0".$oboz_1;
		$oboz = $oboz_1.".".date("m").".".date("y");
	}else{
		$oboz = "001.".date("m").".".date("y");
	}

	dbquery("INSERT INTO okb_db_krz (DATE_START, ID_users, ID_clients, ID_postavshik, SERIYA, DATE_PLAN, DOCS, NORM_PRICE, NORM_PRICE_OSN, MORE) VALUES ('".$_GET['p0']."', '".$_GET['p1']."', '".$_GET['p1_1']."', '".$_GET['p2']."', '".$_GET['p3']."', '".$_GET['p4']."', '".$_GET['p5']."', '".$_GET['p6']."', '".$_GET['p7']."', '".$_GET['p9']."')");
	$id_new_krz = mysql_insert_id();
	$res_2 = dbquery("SELECT ID_krz FROM okb_db_edo_inout_files where ID=".$_GET['p13']);
	$txt_2 = mysql_fetch_array($res_2);
	dbquery("Update okb_db_edo_inout_files Set ID_krz='".$txt_2['ID_krz'].$id_new_krz."|' where (ID='".$_GET['p13']."')");
	dbquery("INSERT INTO okb_db_krzdet (ID_krz, NAME, OBOZ, COUNT) VALUES ('".$id_new_krz."', '".$arr_dse_nam[$aa]."', '".$arr_dse_obz[$aa]."', '".$arr_dse_cou[$aa]."')");
	
	dbquery("Update okb_db_krz Set NAME='".$oboz."' where (ID='".$id_new_krz."')");

}

//echo $_GET['p0']." = ".$_GET['p1']." = ".$_GET['p1_1']." = ".$_GET['p2']." = ".$_GET['p3']." = ".$_GET['p4']." = ".$_GET['p5']." = ".$_GET['p6']." = ".$_GET['p7']." = ".$_GET['p8']." = ".$_GET['p9']." = ".$_GET['p10']." = ".$_GET['p11']." = ".$_GET['p12']." = ".$_GET['p13'];
?>