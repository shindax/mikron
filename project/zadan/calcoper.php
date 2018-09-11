<?php

	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$ID_oper."')");
	if ($opi = mysql_fetch_array($xxx)) {

		$norm_fact = 0;
		$fact = 0;

		$zadresult = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (ID_operitems = '".$opi["ID"]."') and (EDIT_STATE = '1')");
		while($zadres = mysql_fetch_array($zadresult)) {
			$fact = $fact + $zadres["FACT"];
			$norm_fact = $norm_fact + $zadres["NORM_FACT"];
		}

		$fact = number_format($fact, 2, '.', ' ');
		$norm_fact = number_format($norm_fact, 2, '.', ' ');

		dbquery("Update ".$db_prefix."db_operitems Set NORM_FACT:='".$norm_fact."' where (ID='".$opi["ID"]."')");
		dbquery("Update ".$db_prefix."db_operitems Set FACT:='".$fact."' where (ID='".$opi["ID"]."')");

	}

?>